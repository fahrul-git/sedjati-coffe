<div class="mb-3">
    <label for="name" class="form-label">Nama Produk</label>
    <input type="text" name="name" id="name" class="form-control soft-input" value="{{ old('name', $product->name ?? '') }}" required>
</div>

<div class="row">
    <div class="col-md-6 mb-3">
        <label for="category" class="form-label">Kategori</label>
        <input type="text" name="category" id="category" class="form-control soft-input" value="{{ old('category', $product->category ?? '') }}" required>
    </div>
    <div class="col-md-3 mb-3">
        <label for="price" class="form-label">Harga</label>
        <input type="number" name="price" id="price" class="form-control soft-input" value="{{ old('price', $product->price ?? '') }}" min="0" required>
    </div>
    <div class="col-md-3 mb-3">
        <label for="stock" class="form-label">Stok</label>
        <input type="number" name="stock" id="stock" class="form-control soft-input" value="{{ old('stock', $product->stock ?? '') }}" min="0" required>
    </div>
</div>

<div class="mb-3">
    <label for="description" class="form-label">Deskripsi</label>
    <textarea name="description" id="description" rows="4" class="form-control soft-textarea">{{ old('description', $product->description ?? '') }}</textarea>
</div>

<div class="mb-4">
    <label for="image" class="form-label">Gambar Produk</label>
    <input type="file" name="image" id="image" class="form-control soft-input" accept="image/png,image/jpeg,image/webp">
    <div class="form-text">Format yang didukung: JPG, PNG, atau WEBP. Maksimal 3 MB.</div>

    @if (! empty($product?->image_url))
        <div class="mt-3 d-flex align-items-start gap-3">
            <img src="{{ $product->image_url }}" alt="{{ $product->name }}" class="rounded-4 border" style="width: 120px; height: 120px; object-fit: cover;">
            <div>
                <div class="fw-semibold mb-2">Gambar saat ini</div>
                <div class="form-check">
                    <input type="checkbox" name="remove_image" id="remove_image" class="form-check-input" value="1">
                    <label for="remove_image" class="form-check-label">Hapus gambar lama saat menyimpan</label>
                </div>
            </div>
        </div>
    @endif
</div>

<div class="form-check mb-4">
    <input type="checkbox" name="is_active" id="is_active" class="form-check-input" value="1" @checked(old('is_active', $product->is_active ?? true))>
    <label for="is_active" class="form-check-label">Produk aktif</label>
</div>

<button type="submit" class="btn btn-dark">{{ $submitLabel }}</button>
<a href="{{ route('products.index') }}" class="btn btn-outline-secondary">Kembali</a>
