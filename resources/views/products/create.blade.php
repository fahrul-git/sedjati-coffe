@php
    $topbarTitle = 'Sedjati Coffee';
    $topbarSubtitle = 'Tambah produk';
    $topbarSearchPlaceholder = 'Cari item menu...';
@endphp

@extends('layouts.app')

@section('content')
    <div class="page-grid">
        <div class="page-header">
            <div>
                <div class="page-eyebrow">Manajemen</div>
                <h1 class="page-title">Tambah Produk Baru</h1>
                <p class="page-subtitle">Tambahkan menu kopi atau side dish baru ke inventaris.</p>
            </div>
        </div>

        <div class="form-shell">
            <div class="form-card">
                <form action="{{ route('products.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @php($submitLabel = 'Simpan Produk')
                    @include('products._form')
                </form>
            </div>

            <aside class="summary-card">
                <div class="page-eyebrow">Panduan Singkat</div>
                <h2 class="panel-title mb-3">Apa yang membuat data menu rapi?</h2>
                <div class="stack-list">
                    <div class="stack-item">
                        <div class="fw-semibold mb-1">Nama yang jelas</div>
                        <div class="small text-muted">Gunakan nama produk yang singkat dan mudah dikenali kasir.</div>
                    </div>
                    <div class="stack-item">
                        <div class="fw-semibold mb-1">Kategori yang konsisten</div>
                        <div class="small text-muted">Pisahkan Coffee dan Side Dish agar pencarian menu lebih cepat.</div>
                    </div>
                    <div class="stack-item">
                        <div class="fw-semibold mb-1">Stok operasional</div>
                        <div class="small text-muted">Isi stok realistis agar dashboard dan order flow tetap akurat.</div>
                    </div>
                </div>
            </aside>
        </div>
    </div>
@endsection
