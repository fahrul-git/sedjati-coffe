<?php

namespace App\Http\Controllers;

use App\Models\Produk;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class ProdukController extends Controller
{
    public function index(Request $request): View
    {
        $products = Produk::query()
            ->when($request->filled('q'), function (Builder $query) use ($request) {
                $keyword = trim((string) $request->string('q'));

                $query->where(function (Builder $builder) use ($keyword) {
                    $builder
                        ->where('name', 'like', "%{$keyword}%")
                        ->orWhere('category', 'like', "%{$keyword}%")
                        ->orWhere('description', 'like', "%{$keyword}%");
                });
            })
            ->when($request->filled('category'), function (Builder $query) use ($request) {
                $query->where('category', $request->string('category'));
            })
            ->when($request->filled('stock_status'), function (Builder $query) use ($request) {
                match ((string) $request->string('stock_status')) {
                    'low' => $query->whereBetween('stock', [1, 10]),
                    'sold_out' => $query->where('stock', '<=', 0),
                    'active' => $query->where('is_active', true),
                    default => null,
                };
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        $productStats = [
            'total' => Produk::count(),
            'active' => Produk::where('is_active', true)->count(),
            'low_stock' => Produk::where('stock', '<=', 10)->count(),
            'sold_out' => Produk::where('stock', '<=', 0)->count(),
        ];

        $categories = Produk::query()
            ->select('category')
            ->distinct()
            ->orderBy('category')
            ->pluck('category');

        $filters = $request->only(['q', 'category', 'stock_status']);

        return view('products.index', compact('products', 'productStats', 'categories', 'filters'));
    }

    public function create(): View
    {
        return view('products.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $this->validateProduk($request);
        $validated['slug'] = Str::slug($validated['name']).'-'.Str::lower(Str::random(5));
        $validated['image_path'] = $this->storeProductImage($request);

        Produk::create($validated);

        return redirect()
            ->route('products.index')
            ->with('success', 'Produk berhasil ditambahkan.');
    }

    public function show(Produk $product): View
    {
        return view('products.show', compact('product'));
    }

    public function edit(Produk $product): View
    {
        return view('products.edit', compact('product'));
    }

    public function update(Request $request, Produk $product): RedirectResponse
    {
        $validated = $this->validateProduk($request);
        $validated['slug'] = Str::slug($validated['name']).'-'.$product->id;

        if ($request->boolean('remove_image')) {
            $this->deleteProductImage($product->image_path);
            $validated['image_path'] = null;
        }

        if ($request->hasFile('image')) {
            $this->deleteProductImage($product->image_path);
            $validated['image_path'] = $this->storeProductImage($request);
        }

        $product->update($validated);

        return redirect()
            ->route('products.index')
            ->with('success', 'Produk berhasil diperbarui.');
    }

    public function destroy(Produk $product): RedirectResponse
    {
        $this->deleteProductImage($product->image_path);
        $product->delete();

        return redirect()
            ->route('products.index')
            ->with('success', 'Produk berhasil dihapus.');
    }

    protected function validateProduk(Request $request): array
    {
        return $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'category' => ['required', 'string', 'max:255'],
            'price' => ['required', 'numeric', 'min:0'],
            'stock' => ['required', 'integer', 'min:0'],
            'description' => ['nullable', 'string'],
            'image' => ['nullable', 'image', 'max:3072'],
            'is_active' => ['nullable', Rule::in(['0', '1'])],
            'remove_image' => ['nullable', Rule::in(['0', '1'])],
        ]) + [
            'is_active' => $request->boolean('is_active'),
        ];
    }

    protected function storeProductImage(Request $request): ?string
    {
        if (! $request->hasFile('image')) {
            return null;
        }

        return $request->file('image')->store('products', 'public');
    }

    protected function deleteProductImage(?string $path): void
    {
        if ($path && Storage::disk('public')->exists($path)) {
            Storage::disk('public')->delete($path);
        }
    }
}
