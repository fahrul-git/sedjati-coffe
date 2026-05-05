@php
    $topbarTitle = 'Sedjati Coffee';
    $topbarSubtitle = 'Menu produk';
    $topbarSearchPlaceholder = 'Cari item menu...';
@endphp

@extends('layouts.app')

@section('content')
    <div class="page-grid">
        <div class="page-header">
            <div>
                <div class="page-eyebrow">Manajemen</div>
                <h1 class="page-title">Inventaris Menu</h1>
                <p class="page-subtitle">Kelola menu kopi dan side dish yang tersedia.</p>
            </div>
            <div class="action-row">
                <button class="btn-light-soft" type="button"><i class="bi bi-funnel"></i> Filter</button>
                <a href="{{ route('products.create') }}" class="btn-brand"><i class="bi bi-plus-lg"></i> Tambah Produk</a>
            </div>
        </div>

        <div class="metric-grid">
            <div class="metric-card">
                <div class="metric-label">Inventaris</div>
                <div class="metric-value">{{ $productStats['total'] }}</div>
                <div class="metric-foot">Menu yang terdaftar</div>
            </div>
            <div class="metric-card">
                <div class="metric-label">Aktif</div>
                <div class="metric-value">{{ $productStats['active'] }}</div>
                <div class="metric-foot">Sedang tersedia</div>
            </div>
            <div class="metric-card">
                <div class="metric-label">Stok Menipis</div>
                <div class="metric-value">{{ $productStats['low_stock'] }}</div>
                <div class="metric-foot">Perlu restok segera</div>
            </div>
            <div class="metric-card featured">
                <div class="metric-label">Habis</div>
                <div class="metric-value">{{ $productStats['sold_out'] }}</div>
                <div class="metric-foot">Sementara tidak tersedia</div>
            </div>
        </div>

        <div class="menu-toolbar">
            <form method="GET" class="d-flex flex-wrap gap-2 align-items-center w-100">
                <div class="topbar-search" style="max-width: 320px;">
                    <i class="bi bi-search"></i>
                    <input type="search" name="q" class="form-control" placeholder="Cari item menu..." value="{{ $filters['q'] ?? '' }}">
                </div>
                <select name="category" class="form-select micro-select" style="width: 180px;">
                    <option value="">Semua Kategori</option>
                    @foreach ($categories as $category)
                        <option value="{{ $category }}" @selected(($filters['category'] ?? '') === $category)>{{ $category }}</option>
                    @endforeach
                </select>
                <select name="stock_status" class="form-select micro-select" style="width: 180px;">
                    <option value="">Semua Status Stok</option>
                    <option value="active" @selected(($filters['stock_status'] ?? '') === 'active')>Aktif</option>
                    <option value="low" @selected(($filters['stock_status'] ?? '') === 'low')>Stok Menipis</option>
                    <option value="sold_out" @selected(($filters['stock_status'] ?? '') === 'sold_out')>Habis</option>
                </select>
                <button type="submit" class="btn-light-soft"><i class="bi bi-funnel"></i> Terapkan</button>
                <a href="{{ route('products.index') }}" class="btn-light-soft">Reset</a>
                <div class="ms-auto muted-copy small">Menampilkan {{ $products->count() }} item di halaman ini</div>
            </form>
        </div>

        <div class="menu-grid" id="menu-grid">
            @foreach ($products as $index => $product)
                @php
                    $flags = ['Arabica', 'Best Seller', 'Seasonal', 'Sold Out', 'Signature'];
                    $stockClass = $product->stock <= 0 ? 'stock-empty' : ($product->stock <= 10 ? 'stock-low' : 'stock-good');
                    $stockLabel = $product->stock <= 0 ? 'Habis' : ($product->stock <= 10 ? 'Stok Menipis' : 'Tersedia');
                @endphp
                <article class="menu-card menu-search-item" data-search="{{ strtolower($product->name.' '.$product->category.' '.$product->description) }}">
                    @if ($product->image_url)
                        <img src="{{ $product->image_url }}" alt="{{ $product->name }}" class="w-100 rounded-top-4" style="height: 188px; object-fit: cover;">
                    @else
                        <div class="menu-visual">
                            <span>{{ $flags[$index % count($flags)] }}</span>
                        </div>
                    @endif
                    <div class="menu-card-body">
                        <span class="badge-flag">{{ strtoupper($product->category) }}</span>
                        <div class="d-flex justify-content-between align-items-start gap-3">
                            <div>
                                <h2 class="h6 mb-1">{{ $product->name }}</h2>
                                <p class="small text-muted mb-2">{{ \Illuminate\Support\Str::limit($product->description, 64) }}</p>
                            </div>
                            <div class="fw-semibold">Rp {{ number_format($product->price, 0, ',', '.') }}</div>
                        </div>
                        <div class="small text-muted">Stok {{ $product->stock }}</div>
                        <div class="stock-note {{ $stockClass }}">{{ strtoupper($stockLabel) }} {{ $product->stock > 0 ? '(' . $product->stock . ')' : '' }}</div>
                        <div class="d-flex gap-2 mt-3">
                            <a href="{{ route('products.edit', $product) }}" class="btn btn-sm btn-outline-dark">Edit</a>
                            <a href="{{ route('products.show', $product) }}" class="btn btn-sm btn-outline-secondary">Lihat</a>
                        </div>
                    </div>
                </article>
            @endforeach

            <div class="ghost-card">
                <div>
                    <div class="display-6 text-muted mb-2">+</div>
                    <div class="fw-semibold">Tambah Item Baru</div>
                    <div class="small text-muted">Jaga daftar produk tetap lengkap dan rapi</div>
                </div>
            </div>
        </div>

        <div class="mt-4">
            {{ $products->links() }}
        </div>
    </div>

@endsection
