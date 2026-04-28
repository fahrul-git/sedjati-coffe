@extends('layouts.app')

@section('content')
    <div class="card border-0 shadow-sm">
        <div class="card-body p-4">
            <div class="d-flex justify-content-between align-items-start mb-4">
                <div>
                    <h1 class="h3 mb-1">{{ $product->name }}</h1>
                    <p class="text-muted mb-0">{{ $product->category }}</p>
                </div>
                <a href="{{ route('products.edit', $product) }}" class="btn btn-dark">Edit Produk</a>
            </div>

            <dl class="row mb-0">
                <dt class="col-sm-3">Harga</dt>
                <dd class="col-sm-9">Rp {{ number_format($product->price, 0, ',', '.') }}</dd>

                <dt class="col-sm-3">Stok</dt>
                <dd class="col-sm-9">{{ $product->stock }}</dd>

                <dt class="col-sm-3">Status</dt>
                <dd class="col-sm-9">{{ $product->is_active ? 'Aktif' : 'Nonaktif' }}</dd>

                <dt class="col-sm-3">Deskripsi</dt>
                <dd class="col-sm-9">{{ $product->description ?: '-' }}</dd>
            </dl>
        </div>
    </div>
@endsection
