@php
    $topbarTitle = 'Sedjati Coffee';
    $topbarSubtitle = 'Create product';
    $topbarSearchPlaceholder = 'Search menu items...';
@endphp

@extends('layouts.app')

@section('content')
    <div class="page-grid">
        <div class="page-header">
            <div>
                <div class="page-eyebrow">Management</div>
                <h1 class="page-title">Add New Product</h1>
                <p class="page-subtitle">Create a new coffee or side dish item for the menu inventory.</p>
            </div>
        </div>

        <div class="form-shell">
            <div class="form-card">
                <form action="{{ route('products.store') }}" method="POST">
                    @csrf
                    @php($submitLabel = 'Simpan Produk')
                    @include('products._form')
                </form>
            </div>

            <aside class="summary-card">
                <div class="page-eyebrow">Quick Guide</div>
                <h2 class="panel-title mb-3">What makes a good menu card?</h2>
                <div class="stack-list">
                    <div class="stack-item">
                        <div class="fw-semibold mb-1">Clear naming</div>
                        <div class="small text-muted">Gunakan nama produk yang singkat dan mudah dikenali kasir.</div>
                    </div>
                    <div class="stack-item">
                        <div class="fw-semibold mb-1">Category consistency</div>
                        <div class="small text-muted">Pisahkan Coffee dan Side Dish agar pencarian menu lebih cepat.</div>
                    </div>
                    <div class="stack-item">
                        <div class="fw-semibold mb-1">Operational stock</div>
                        <div class="small text-muted">Isi stok realistis agar dashboard dan order flow tetap akurat.</div>
                    </div>
                </div>
            </aside>
        </div>
    </div>
@endsection
