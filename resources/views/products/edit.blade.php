@php
    $topbarTitle = 'Sedjati Coffee';
    $topbarSubtitle = 'Edit product';
    $topbarSearchPlaceholder = 'Search menu items...';
@endphp

@extends('layouts.app')

@section('content')
    <div class="page-grid">
        <div class="page-header">
            <div>
                <div class="page-eyebrow">Management</div>
                <h1 class="page-title">Edit Product</h1>
                <p class="page-subtitle">Update pricing, stock, status, or descriptive details for this menu item.</p>
            </div>
        </div>

        <div class="form-shell">
            <div class="form-card">
                <form action="{{ route('products.update', $product) }}" method="POST">
                    @csrf
                    @method('PUT')
                    @php($submitLabel = 'Perbarui Produk')
                    @include('products._form')
                </form>
            </div>

            <aside class="summary-card">
                <div class="page-eyebrow">Current Snapshot</div>
                <h2 class="panel-title mb-3">{{ $product->name }}</h2>
                <div class="info-list">
                    <div class="info-row">
                        <span class="muted-copy">Category</span>
                        <strong>{{ $product->category }}</strong>
                    </div>
                    <div class="info-row">
                        <span class="muted-copy">Price</span>
                        <strong>Rp {{ number_format($product->price, 0, ',', '.') }}</strong>
                    </div>
                    <div class="info-row">
                        <span class="muted-copy">Stock</span>
                        <strong>{{ $product->stock }}</strong>
                    </div>
                    <div class="info-row">
                        <span class="muted-copy">Status</span>
                        <strong>{{ $product->is_active ? 'Aktif' : 'Nonaktif' }}</strong>
                    </div>
                </div>
            </aside>
        </div>
    </div>
@endsection
