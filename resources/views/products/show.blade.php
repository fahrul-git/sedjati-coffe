@php
    $topbarTitle = 'Sedjati Coffee';
    $topbarSubtitle = 'Detail produk';
    $topbarSearchPlaceholder = 'Cari item menu...';
@endphp

@extends('layouts.app')

@section('content')
    <div class="page-grid">
        <div class="page-header">
            <div>
                <div class="page-eyebrow">Profil Menu</div>
                <h1 class="page-title">{{ $product->name }}</h1>
                <p class="page-subtitle">Informasi inventaris lengkap untuk menu ini.</p>
            </div>
            <div class="action-row">
                <a href="{{ route('products.edit', $product) }}" class="btn-brand"><i class="bi bi-pencil-square"></i> Edit Produk</a>
                <a href="{{ route('products.index') }}" class="btn-light-soft">Kembali ke Menu</a>
            </div>
        </div>

        <div class="dashboard-panels">
            <div class="panel-card">
                @if ($product->image_url)
                    <img src="{{ $product->image_url }}" alt="{{ $product->name }}" class="rounded-4 border mb-4" style="width: 100%; height: 260px; object-fit: cover;">
                @else
                    <div class="menu-visual rounded-4 mb-4" style="height: 260px;">
                        <span>{{ strtoupper($product->category) }}</span>
                    </div>
                @endif
                <div class="d-flex align-items-center justify-content-between mb-2">
                    <h2 class="h4 mb-0">{{ $product->name }}</h2>
                    <span class="badge-flag">{{ $product->is_active ? 'Aktif' : 'Disembunyikan' }}</span>
                </div>
                <p class="text-muted mb-0">{{ $product->description ?: 'Belum ada deskripsi untuk produk ini.' }}</p>
            </div>

            <div class="panel-card">
                <div class="page-eyebrow">Detail Inventaris</div>
                <div class="info-list mt-3">
                    <div class="info-row">
                        <span class="muted-copy">Kategori</span>
                        <strong>{{ $product->category }}</strong>
                    </div>
                    <div class="info-row">
                        <span class="muted-copy">Harga</span>
                        <strong>Rp {{ number_format($product->price, 0, ',', '.') }}</strong>
                    </div>
                    <div class="info-row">
                        <span class="muted-copy">Stok</span>
                        <strong>{{ $product->stock }} item</strong>
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
