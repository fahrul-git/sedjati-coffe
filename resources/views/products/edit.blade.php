@php
    $topbarTitle = 'Sedjati Coffee';
    $topbarSubtitle = 'Edit produk';
    $topbarSearchPlaceholder = 'Cari item menu...';
@endphp

@extends('layouts.app')

@section('content')
    <div class="page-grid">
        <div class="page-header">
            <div>
                <div class="page-eyebrow">Manajemen</div>
                <h1 class="page-title">Edit Produk</h1>
                <p class="page-subtitle">Perbarui harga, stok, status, atau detail deskriptif untuk menu ini.</p>
            </div>
        </div>

        <div class="form-shell">
            <div class="form-card">
                <form action="{{ route('products.update', $product) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    @php($submitLabel = 'Perbarui Produk')
                    @include('products._form')
                </form>
            </div>

            <aside class="summary-card">
                <div class="page-eyebrow">Ringkasan Saat Ini</div>
                <h2 class="panel-title mb-3">{{ $product->name }}</h2>
                @if ($product->image_url)
                    <img src="{{ $product->image_url }}" alt="{{ $product->name }}" class="rounded-4 border mb-3" style="width: 100%; height: 200px; object-fit: cover;">
                @endif
                <div class="info-list">
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
