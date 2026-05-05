<?php

namespace App\Http\Controllers;

use App\Models\Pesanan;
use App\Models\Produk;
use App\Models\Setting;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class PesananController extends Controller
{
    public function index(Request $request): View
    {
        $orders = Pesanan::query()
            ->with(['user', 'detailPesanan'])
            ->when($request->filled('q'), function (Builder $query) use ($request) {
                $keyword = trim((string) $request->string('q'));

                $query->where(function (Builder $builder) use ($keyword) {
                    $builder
                        ->where('order_number', 'like', "%{$keyword}%")
                        ->orWhere('customer_name', 'like', "%{$keyword}%")
                        ->orWhere('table_number', 'like', "%{$keyword}%")
                        ->orWhereHas('detailPesanan', function (Builder $detailQuery) use ($keyword) {
                            $detailQuery->where('product_name', 'like', "%{$keyword}%");
                        });
                });
            })
            ->when($request->filled('payment_status'), function (Builder $query) use ($request) {
                $query->where('payment_status', $request->string('payment_status'));
            })
            ->when($request->filled('date_from'), function (Builder $query) use ($request) {
                $query->whereDate('order_date', '>=', $request->string('date_from'));
            })
            ->when($request->filled('date_to'), function (Builder $query) use ($request) {
                $query->whereDate('order_date', '<=', $request->string('date_to'));
            })
            ->latest('order_date')
            ->paginate(10)
            ->withQueryString();

        $orderStats = [
            'today_revenue' => Pesanan::query()->whereDate('order_date', today())->where('payment_status', 'paid')->sum('total_amount'),
            'active_orders' => Pesanan::query()->whereIn('status', ['pending', 'processing'])->count(),
            'avg_prep_time' => '4,2 menit',
            'satisfaction' => '98%',
        ];

        $filters = $request->only(['q', 'payment_status', 'date_from', 'date_to']);

        return view('orders.index', compact('orders', 'orderStats', 'filters'));
    }

    public function create(): View
    {
        $products = Produk::query()
            ->where('is_active', true)
            ->orderBy('name')
            ->get();

        $productOptions = $this->productOptions();
        $paymentMethods = $this->availablePaymentMethods();

        return view('orders.create', compact('products', 'productOptions', 'paymentMethods'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $this->validatePesanan($request);

        $pesanan = DB::transaction(function () use ($request, $validated) {
            $selectedProduk = Produk::query()
                ->whereIn('id', collect($validated['items'])->pluck('product_id'))
                ->lockForUpdate()
                ->get()
                ->keyBy('id');

            $pesanan = Pesanan::create([
                'order_number' => $this->generateOrderNumber(),
                'user_id' => $request->user()->id,
                'customer_name' => $validated['customer_name'] ?? null,
                'table_number' => $validated['table_number'] ?? null,
                'order_date' => now(),
                'status' => 'pending',
                'payment_status' => Setting::getValue('default_payment_status', 'pending'),
                'notes' => null,
                'total_amount' => 0,
            ]);

            $total = 0;

            foreach ($validated['items'] as $item) {
                $produk = $selectedProduk->get($item['product_id']);
                $quantity = (int) $item['quantity'];
                $itemOption = $item['item_option'] ?? null;
                $itemNote = $item['item_note'] ?? null;

                if (! $produk || $produk->stock < $quantity) {
                    throw ValidationException::withMessages([
                        'items' => 'Stok produk yang dipilih tidak mencukupi.',
                    ]);
                }

                $this->ensureValidItemOption($produk, $itemOption);

                $subtotal = $produk->price * $quantity;

                $pesanan->detailPesanan()->create([
                    'produk_id' => $produk->id,
                    'product_name' => $produk->name,
                    'serving_type' => $produk->category === 'Coffee' ? $itemOption : 'panas',
                    'item_option' => $itemOption,
                    'item_note' => $itemNote,
                    'price' => $produk->price,
                    'quantity' => $quantity,
                    'subtotal' => $subtotal,
                ]);

                $produk->decrement('stock', $quantity);
                $total += $subtotal;
            }

            $pesanan->update(['total_amount' => $total]);

            return $pesanan->load(['user', 'detailPesanan.produk']);
        });

        return redirect()
            ->route('orders.payment.create', $pesanan)
            ->with('success', 'Pesanan berhasil dibuat. Silakan lanjutkan ke pembayaran.');
    }

    public function show(Pesanan $order): View
    {
        $order->load(['user', 'detailPesanan.produk']);

        return view('orders.show', compact('order'));
    }

    public function receipt(Pesanan $order): View
    {
        $order->load(['user', 'detailPesanan.produk']);

        return view('orders.receipt', compact('order'));
    }

    public function createPayment(Pesanan $order): View
    {
        $order->load(['user', 'detailPesanan.produk']);
        $paymentMethods = $this->availablePaymentMethods();

        return view('orders.payment', compact('order', 'paymentMethods'));
    }

    public function storePayment(Request $request, Pesanan $order): RedirectResponse
    {
        $paymentMethods = array_keys($this->availablePaymentMethods());

        $validated = $request->validate([
            'payment_method' => ['required', Rule::in($paymentMethods)],
            'paid_amount' => ['nullable', 'numeric', 'min:0'],
        ]);

        $paidAmount = $validated['payment_method'] === 'cash'
            ? (float) ($validated['paid_amount'] ?? 0)
            : (float) $order->total_amount;

        if ($validated['payment_method'] === 'cash' && $paidAmount < (float) $order->total_amount) {
            throw ValidationException::withMessages([
                'paid_amount' => 'Nominal cash harus sama atau lebih besar dari total pembayaran.',
            ]);
        }

        $order->update([
            'payment_method' => $validated['payment_method'],
            'payment_status' => 'paid',
            'paid_amount' => $paidAmount,
            'paid_at' => now(),
            'status' => 'completed',
        ]);

        return redirect()
            ->route('orders.receipt', $order)
            ->with('success', 'Pembayaran berhasil diproses.');
    }

    protected function validatePesanan(Request $request): array
    {
        $validated = $request->validate([
            'customer_name' => ['nullable', 'string', 'max:255'],
            'table_number' => ['nullable', 'string', 'max:50'],
            'items' => ['required', 'array', 'min:1'],
            'items.*.product_id' => ['required', 'exists:produk,id'],
            'items.*.quantity' => ['required', 'integer', 'min:0'],
            'items.*.item_option' => ['nullable', 'string', 'max:255'],
            'items.*.item_note' => ['nullable', 'string', 'max:1000'],
        ]);

        $validated['items'] = collect($validated['items'])
            ->filter(fn (array $item) => (int) $item['quantity'] > 0)
            ->values()
            ->all();

        if ($validated['items'] === []) {
            throw ValidationException::withMessages([
                'items' => 'Pilih minimal satu produk dengan kuantitas lebih dari 0.',
            ]);
        }

        return $validated;
    }

    protected function productOptions(): array
    {
        return [
            'Espresso' => ['panas' => 'Panas', 'es' => 'Es'],
            'Americano' => ['panas' => 'Panas', 'es' => 'Es'],
            'Cappuccino' => ['panas' => 'Panas', 'es' => 'Es'],
            'Cafe Latte' => ['panas' => 'Panas', 'es' => 'Es'],
            'Mocha' => ['panas' => 'Panas', 'es' => 'Es'],
            'Caramel Macchiato' => ['panas' => 'Panas', 'es' => 'Es'],
            'Vanilla Latte' => ['panas' => 'Panas', 'es' => 'Es'],
            'Hazelnut Latte' => ['panas' => 'Panas', 'es' => 'Es'],
            'Cold Brew' => ['panas' => 'Panas', 'es' => 'Es'],
            'Affogato' => ['panas' => 'Panas', 'es' => 'Es'],
            'French Fries' => ['ori' => 'Ori', 'bbq' => 'BBQ', 'spicy' => 'Spicy'],
            'Croissant' => ['coklat' => 'Coklat', 'strawberry' => 'Strawberry', 'vanilla' => 'Vanilla'],
            'Sandwich' => ['original' => 'Original'],
            'Banana Roll' => ['coklat' => 'Coklat', 'strawberry' => 'Strawberry', 'vanilla' => 'Vanilla'],
            'Burger' => ['chicken burger' => 'Chicken Burger', 'beef burger' => 'Beef Burger', 'cheese burger' => 'Cheese Burger'],
        ];
    }

    protected function ensureValidItemOption(Produk $produk, ?string $itemOption): void
    {
        $availableOptions = $this->productOptions()[$produk->name] ?? [];

        if ($availableOptions === []) {
            return;
        }

        if (! $itemOption || ! array_key_exists($itemOption, $availableOptions)) {
            throw ValidationException::withMessages([
                'items' => "Pilihan untuk {$produk->name} tidak valid.",
            ]);
        }
    }

    protected function availablePaymentMethods(): array
    {
        $configured = Setting::getArrayValue('payment_methods', ['cash', 'qris', 'debit card']);
        $labels = [
            'cash' => 'Tunai',
            'qris' => 'QRIS',
            'debit card' => 'Kartu Debit',
        ];

        return collect($configured)
            ->filter(fn (string $method) => array_key_exists($method, $labels))
            ->mapWithKeys(fn (string $method) => [$method => $labels[$method]])
            ->all();
    }

    protected function generateOrderNumber(): string
    {
        $format = Setting::getValue('order_number_format', 'SDJ-{date}-{sequence}');
        $dailySequence = str_pad((string) (Pesanan::query()->whereDate('order_date', today())->count() + 1), 3, '0', STR_PAD_LEFT);

        return Str::of($format)
            ->replace('{date}', now()->format('Ymd'))
            ->replace('{time}', now()->format('His'))
            ->replace('{sequence}', $dailySequence)
            ->replace('{rand}', Str::upper(Str::random(4)))
            ->toString();
    }
}
