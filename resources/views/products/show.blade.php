@php
    $topbarTitle = 'Sedjati Coffee';
    $topbarSubtitle = 'Product detail';
    $topbarSearchPlaceholder = 'Search menu items...';
@endphp

@extends('layouts.app')

@section('content')
    <div class="page-grid">
        <div class="page-header">
            <div>
                <div class="page-eyebrow">Menu Profile</div>
                <h1 class="page-title">{{ $product->name }}</h1>
                <p class="page-subtitle">Detailed inventory information for this menu entry.</p>
            </div>
            <div class="action-row">
                <a href="{{ route('products.edit', $product) }}" class="btn-brand"><i class="bi bi-pencil-square"></i> Edit Product</a>
                <a href="{{ route('products.index') }}" class="btn-light-soft">Back to Menu</a>
            </div>
        </div>

        <div class="dashboard-panels">
            <div class="panel-card">
                <div class="menu-visual rounded-4 mb-4" style="height: 260px;">
                    <span>{{ strtoupper($product->category) }}</span>
                </div>
                <div class="d-flex align-items-center justify-content-between mb-2">
                    <h2 class="h4 mb-0">{{ $product->name }}</h2>
                    <span class="badge-flag">{{ $product->is_active ? 'Active' : 'Hidden' }}</span>
                </div>
                <p class="text-muted mb-0">{{ $product->description ?: 'Belum ada deskripsi untuk produk ini.' }}</p>
            </div>

            <div class="panel-card">
                <div class="page-eyebrow">Inventory Detail</div>
                <div class="info-list mt-3">
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
                        <strong>{{ $product->stock }} items</strong>
                    </div>
                    <div class="info-row">
                        <span class="muted-copy">Status</span>
                        <strong>{{ $product->is_active ? 'Aktif' : 'Nonaktif' }}</strong>
                    </div>
                    <div class="info-row">
                        <span class="muted-copy">Slug</span>
                        <strong>{{ $product->slug }}</strong>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
