@php
    $topbarTitle = 'Sedjati Coffee';
    $topbarSubtitle = 'Edit customer';
    $topbarSearchPlaceholder = 'Cari customer...';
@endphp

@extends('layouts.app')

@section('content')
    <div class="page-grid">
        <div class="page-header">
            <div>
                <div class="page-eyebrow">Pelanggan</div>
                <h1 class="page-title">Edit Customer</h1>
                <p class="page-subtitle">Perbarui profil customer dan nilai transaksinya bila diperlukan.</p>
            </div>
        </div>

        <div class="form-shell">
            <div class="form-card">
                <form action="{{ route('customers.update', $customer) }}" method="POST">
                    @csrf
                    @method('PUT')
                    @php($submitLabel = 'Perbarui Customer')
                    @include('customers._form')
                </form>
            </div>

            <aside class="summary-card">
                <div class="page-eyebrow">Ringkasan Saat Ini</div>
                <h2 class="panel-title mb-3">{{ $customer->name }}</h2>
                <div class="info-list">
                    <div class="info-row">
                        <span class="muted-copy">No HP</span>
                        <strong>{{ $customer->phone }}</strong>
                    </div>
                    <div class="info-row">
                        <span class="muted-copy">Pertama Beli</span>
                        <strong>{{ $customer->first_purchase_date?->format('d M Y') ?? '-' }}</strong>
                    </div>
                    <div class="info-row">
                        <span class="muted-copy">Total Transaksi</span>
                        <strong>{{ $customer->total_transactions }}</strong>
                    </div>
                    <div class="info-row">
                        <span class="muted-copy">Total Spending</span>
                        <strong>Rp {{ number_format($customer->total_spending, 0, ',', '.') }}</strong>
                    </div>
                </div>
            </aside>
        </div>
    </div>
@endsection
