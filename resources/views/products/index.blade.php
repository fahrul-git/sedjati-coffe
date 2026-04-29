@php
    $topbarTitle = 'Sedjati Coffee';
    $topbarSubtitle = 'Product menu';
    $topbarSearchPlaceholder = 'Search menu items...';
@endphp

@extends('layouts.app')

@section('content')
    <div class="page-grid">
        <div class="page-header">
            <div>
                <div class="page-eyebrow">Management</div>
                <h1 class="page-title">Menu Inventory</h1>
                <p class="page-subtitle">Manage your coffee beans and prepared beverages.</p>
            </div>
            <div class="action-row">
                <button class="btn-light-soft" type="button"><i class="bi bi-funnel"></i> Filter</button>
                <a href="{{ route('products.create') }}" class="btn-brand"><i class="bi bi-plus-lg"></i> Add New Product</a>
            </div>
        </div>

        <div class="metric-grid">
            <div class="metric-card">
                <div class="metric-label">Inventory</div>
                <div class="metric-value">{{ $productStats['total'] }}</div>
                <div class="metric-foot">Registered menu items</div>
            </div>
            <div class="metric-card">
                <div class="metric-label">Active</div>
                <div class="metric-value">{{ $productStats['active'] }}</div>
                <div class="metric-foot">Currently available</div>
            </div>
            <div class="metric-card">
                <div class="metric-label">Low Stock</div>
                <div class="metric-value">{{ $productStats['low_stock'] }}</div>
                <div class="metric-foot">Need refill soon</div>
            </div>
            <div class="metric-card featured">
                <div class="metric-label">Sold Out</div>
                <div class="metric-value">{{ $productStats['sold_out'] }}</div>
                <div class="metric-foot">Temporarily unavailable</div>
            </div>
        </div>

        <div class="menu-toolbar">
            <div class="topbar-search" style="max-width: 360px;">
                <i class="bi bi-search"></i>
                <input type="search" id="menu-grid-search" class="form-control" placeholder="Search menu items...">
            </div>
            <div class="muted-copy small">Showing {{ $products->count() }} items on this page</div>
        </div>

        <div class="menu-grid" id="menu-grid">
            @foreach ($products as $index => $product)
                @php
                    $flags = ['Arabica', 'Best Seller', 'Seasonal', 'Sold Out', 'Signature'];
                    $stockClass = $product->stock <= 0 ? 'stock-empty' : ($product->stock <= 10 ? 'stock-low' : 'stock-good');
                    $stockLabel = $product->stock <= 0 ? 'Out of Stock' : ($product->stock <= 10 ? 'Low Stock' : 'In Stock');
                @endphp
                <article class="menu-card menu-search-item" data-search="{{ strtolower($product->name.' '.$product->category.' '.$product->description) }}">
                    <div class="menu-visual">
                        <span>{{ $flags[$index % count($flags)] }}</span>
                    </div>
                    <div class="menu-card-body">
                        <span class="badge-flag">{{ strtoupper($product->category) }}</span>
                        <div class="d-flex justify-content-between align-items-start gap-3">
                            <div>
                                <h2 class="h6 mb-1">{{ $product->name }}</h2>
                                <p class="small text-muted mb-2">{{ \Illuminate\Support\Str::limit($product->description, 64) }}</p>
                            </div>
                            <div class="fw-semibold">${{ number_format($product->price / 1000, 2) }}</div>
                        </div>
                        <div class="small text-muted">Stock {{ $product->stock }}</div>
                        <div class="stock-note {{ $stockClass }}">{{ strtoupper($stockLabel) }} {{ $product->stock > 0 ? '(' . $product->stock . ')' : '' }}</div>
                        <div class="d-flex gap-2 mt-3">
                            <a href="{{ route('products.edit', $product) }}" class="btn btn-sm btn-outline-dark">Edit</a>
                            <a href="{{ route('products.show', $product) }}" class="btn btn-sm btn-outline-secondary">View</a>
                        </div>
                    </div>
                </article>
            @endforeach

            <div class="ghost-card">
                <div>
                    <div class="display-6 text-muted mb-2">+</div>
                    <div class="fw-semibold">Add New Item</div>
                    <div class="small text-muted">Maintain a diverse products list</div>
                </div>
            </div>
        </div>

        <div class="mt-4">
            {{ $products->links() }}
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const searchInput = document.getElementById('menu-grid-search');
            const items = document.querySelectorAll('.menu-search-item');

            searchInput?.addEventListener('input', function () {
                const keyword = this.value.trim().toLowerCase();

                items.forEach((item) => {
                    item.style.display = (item.dataset.search || '').includes(keyword) ? '' : 'none';
                });
            });
        });
    </script>
@endsection
