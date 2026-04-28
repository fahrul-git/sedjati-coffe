<div class="mb-3">
    <label for="name" class="form-label">Nama Produk</label>
    <input type="text" name="name" id="name" class="form-control" value="{{ old('name', $product->name ?? '') }}" required>
</div>

<div class="row">
    <div class="col-md-6 mb-3">
        <label for="category" class="form-label">Kategori</label>
        <input type="text" name="category" id="category" class="form-control" value="{{ old('category', $product->category ?? '') }}" required>
    </div>
    <div class="col-md-3 mb-3">
        <label for="price" class="form-label">Harga</label>
        <input type="number" name="price" id="price" class="form-control" value="{{ old('price', $product->price ?? '') }}" min="0" required>
    </div>
    <div class="col-md-3 mb-3">
        <label for="stock" class="form-label">Stok</label>
        <input type="number" name="stock" id="stock" class="form-control" value="{{ old('stock', $product->stock ?? '') }}" min="0" required>
    </div>
</div>

<div class="mb-3">
    <label for="description" class="form-label">Deskripsi</label>
    <textarea name="description" id="description" rows="4" class="form-control">{{ old('description', $product->description ?? '') }}</textarea>
</div>

<div class="form-check mb-4">
    <input type="checkbox" name="is_active" id="is_active" class="form-check-input" value="1" @checked(old('is_active', $product->is_active ?? true))>
    <label for="is_active" class="form-check-label">Produk aktif</label>
</div>

<button type="submit" class="btn btn-dark">{{ $submitLabel }}</button>
<a href="{{ route('products.index') }}" class="btn btn-outline-secondary">Kembali</a>
