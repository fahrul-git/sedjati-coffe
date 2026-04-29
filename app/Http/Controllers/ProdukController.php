<?php

namespace App\Http\Controllers;

use App\Models\Produk;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class ProdukController extends Controller
{
    public function index(): View
    {
        $products = Produk::query()
            ->latest()
            ->paginate(10);

        $productStats = [
            'total' => Produk::count(),
            'active' => Produk::where('is_active', true)->count(),
            'low_stock' => Produk::where('stock', '<=', 10)->count(),
            'sold_out' => Produk::where('stock', '<=', 0)->count(),
        ];

        return view('products.index', compact('products', 'productStats'));
    }

    public function create(): View
    {
        return view('products.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $this->validateProduk($request);
        $validated['slug'] = Str::slug($validated['name']).'-'.Str::lower(Str::random(5));

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

        $product->update($validated);

        return redirect()
            ->route('products.index')
            ->with('success', 'Produk berhasil diperbarui.');
    }

    public function destroy(Produk $product): RedirectResponse
    {
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
            'is_active' => ['nullable', Rule::in(['0', '1'])],
        ]) + [
            'is_active' => $request->boolean('is_active'),
        ];
    }
}
