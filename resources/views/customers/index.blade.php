@php
    $topbarTitle = 'Sedjati Coffee';
    $topbarSubtitle = 'Manajemen customer';
    $topbarSearchPlaceholder = 'Cari customer...';
@endphp

@extends('layouts.app')

@section('content')
    <div class="page-grid">
        <div class="page-header">
            <div>
                <div class="page-eyebrow">Pelanggan</div>
                <h1 class="page-title">Customer</h1>
                <p class="page-subtitle">Data customer berisi nama, kontak, tanggal pertama beli, total transaksi, dan total spending.</p>
            </div>
            <div class="action-row">
                <a href="{{ route('customers.create') }}" class="btn-brand"><i class="bi bi-person-plus"></i> Tambah Customer</a>
            </div>
        </div>

        <div class="metric-grid">
            <div class="metric-card">
                <div class="metric-label">Total Customer</div>
                <div class="metric-value">{{ $stats['total_customers'] }}</div>
                <div class="metric-foot">Pelanggan yang tersimpan</div>
            </div>
            <div class="metric-card">
                <div class="metric-label">Customer Aktif</div>
                <div class="metric-value">{{ $stats['repeat_customers'] }}</div>
                <div class="metric-foot">Transaksi 2x atau lebih</div>
            </div>
            <div class="metric-card">
                <div class="metric-label">Total Belanja</div>
                <div class="metric-value">Rp {{ number_format($stats['total_spending'], 0, ',', '.') }}</div>
                <div class="metric-foot">Akumulasi spending customer</div>
            </div>
            <div class="metric-card featured">
                <div class="metric-label">Belanja Tertinggi</div>
                <div class="metric-value">Rp {{ number_format($stats['best_spender'], 0, ',', '.') }}</div>
                <div class="metric-foot">Customer spending tertinggi</div>
            </div>
        </div>

        <div class="orders-toolbar">
            <form method="GET" class="d-flex flex-wrap gap-2 align-items-center w-100">
                <div class="topbar-search" style="max-width: 360px;">
                    <i class="bi bi-search"></i>
                    <input type="search" name="q" class="form-control" placeholder="Cari nama atau no HP..." value="{{ $filters['q'] ?? '' }}">
                </div>
                <button type="submit" class="btn-light-soft"><i class="bi bi-funnel"></i> Terapkan</button>
                <a href="{{ route('customers.index') }}" class="btn-light-soft">Reset</a>
            </form>
        </div>

        <div class="section-table">
            <div class="section-table-header">
                <div>
                    <h2 class="panel-title">Daftar Customer</h2>
                    <p class="panel-subtitle">Pantau nilai customer secara cepat dari histori dasar yang dicatat admin.</p>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-clean align-middle">
                    <thead>
                        <tr>
                            <th>Nama Customer</th>
                            <th>No HP</th>
                            <th>Tanggal Pertama Beli</th>
                            <th>Total Transaksi</th>
                            <th>Total Spending</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($customers as $customer)
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center gap-3">
                                        <div class="customer-dot">{{ strtoupper(substr($customer->name, 0, 1)) }}</div>
                                        <div class="fw-semibold">{{ $customer->name }}</div>
                                    </div>
                                </td>
                                <td>{{ $customer->phone }}</td>
                                <td>{{ $customer->first_purchase_date?->format('d M Y') ?? '-' }}</td>
                                <td>{{ $customer->total_transactions }}</td>
                                <td>Rp {{ number_format($customer->total_spending, 0, ',', '.') }}</td>
                                <td>
                                    <div class="d-flex gap-2">
                                        <a href="{{ route('customers.show', $customer) }}" class="btn btn-sm btn-outline-secondary">Lihat</a>
                                        <a href="{{ route('customers.edit', $customer) }}" class="btn btn-sm btn-outline-dark">Edit</a>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center text-muted py-4">Belum ada data customer.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div class="mt-4">
            {{ $customers->links() }}
        </div>
    </div>
@endsection
