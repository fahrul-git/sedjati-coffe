@php
    $topbarTitle = 'Sedjati Coffee';
    $topbarSubtitle = 'Detail customer';
    $topbarSearchPlaceholder = 'Cari customer...';
@endphp

@extends('layouts.app')

@section('content')
    <div class="page-grid">
        <div class="page-header">
            <div>
                <div class="page-eyebrow">Profil Customer</div>
                <h1 class="page-title">{{ $customer->name }}</h1>
                <p class="page-subtitle">Ringkasan customer berdasarkan field yang dicatat admin.</p>
            </div>
            <div class="action-row">
                <a href="{{ route('customers.edit', $customer) }}" class="btn-brand"><i class="bi bi-pencil-square"></i> Edit Customer</a>
                <a href="{{ route('customers.index') }}" class="btn-light-soft">Kembali ke Customer</a>
            </div>
        </div>

        <div class="dashboard-panels">
            <div class="panel-card">
                <div class="d-flex align-items-center gap-3 mb-4">
                    <div class="customer-dot" style="width: 52px; height: 52px; font-size: 1rem;">{{ strtoupper(substr($customer->name, 0, 1)) }}</div>
                    <div>
                        <h2 class="h4 mb-1">{{ $customer->name }}</h2>
                        <div class="text-muted">{{ $customer->phone }}</div>
                    </div>
                </div>

                <div class="stack-item">
                    <div class="fw-semibold mb-2">Nilai Customer</div>
                    <div class="text-muted">Customer ini telah melakukan {{ $customer->total_transactions }} transaksi dengan total belanja Rp {{ number_format($customer->total_spending, 0, ',', '.') }}.</div>
                </div>
            </div>

            <div class="panel-card">
                <div class="page-eyebrow">Detail</div>
                <div class="info-list mt-3">
                    <div class="info-row">
                        <span class="muted-copy">Nama Customer</span>
                        <strong>{{ $customer->name }}</strong>
                    </div>
                    <div class="info-row">
                        <span class="muted-copy">No HP</span>
                        <strong>{{ $customer->phone }}</strong>
                    </div>
                    <div class="info-row">
                        <span class="muted-copy">Tanggal Pertama Beli</span>
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
            </div>
        </div>
    </div>
@endsection
