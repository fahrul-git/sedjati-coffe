<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    public function index()
    {
        $orders = Order::query()
            ->with(['user', 'items'])
            ->latest('order_date')
            ->paginate(10);

        return view('orders.index', compact('orders'));
    }

    public function create()
    {
        $products = Product::query()
            ->where('is_active', true)
            ->orderBy('name')
            ->get();

        return view('orders.create', compact('products'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'customer_name' => ['nullable', 'string', 'max:255'],
            'notes' => ['nullable', 'string'],
            'items' => ['required', 'array', 'min:1'],
            'items.*.product_id' => ['required', 'exists:products,id'],
            'items.*.quantity' => ['required', 'integer', 'min:0'],
        ]);

        $validated['items'] = collect($validated['items'])
            ->filter(fn (array $item) => (int) $item['quantity'] > 0)
            ->values()
            ->all();

        if ($validated['items'] === []) {
            return back()
                ->withErrors(['items' => 'Pilih minimal satu produk dengan kuantitas lebih dari 0.'])
                ->withInput();
        }

        $order = DB::transaction(function () use ($request, $validated) {
            $selectedProducts = Product::query()
                ->whereIn('id', collect($validated['items'])->pluck('product_id'))
                ->get()
                ->keyBy('id');

            $order = Order::create([
                'order_number' => 'SDJ-'.now()->format('YmdHis'),
                'user_id' => $request->user()->id,
                'customer_name' => $validated['customer_name'] ?? null,
                'order_date' => now(),
                'status' => 'completed',
                'notes' => $validated['notes'] ?? null,
                'total_amount' => 0,
            ]);

            $total = 0;

            foreach ($validated['items'] as $item) {
                $product = $selectedProducts->get($item['product_id']);
                $quantity = (int) $item['quantity'];

                if ($product->stock < $quantity) {
                    abort(422, "Stok untuk {$product->name} tidak mencukupi.");
                }

                $subtotal = $product->price * $quantity;

                $order->items()->create([
                    'product_id' => $product->id,
                    'product_name' => $product->name,
                    'price' => $product->price,
                    'quantity' => $quantity,
                    'subtotal' => $subtotal,
                ]);

                $product->decrement('stock', $quantity);
                $total += $subtotal;
            }

            $order->update(['total_amount' => $total]);

            return $order->load(['user', 'items.product']);
        });

        return redirect()
            ->route('orders.show', $order)
            ->with('success', 'Pesanan berhasil dibuat.');
    }

    public function show(Order $order)
    {
        $order->load(['user', 'items.product']);

        return view('orders.show', compact('order'));
    }
}
