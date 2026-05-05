@php
    $topbarTitle = 'Sedjati Coffee';
    $topbarSubtitle = 'Manajemen pesanan';
    $topbarSearchPlaceholder = 'Cari pesanan...';
@endphp

@extends('layouts.app')

@section('content')
    <div class="page-grid">
        <div class="page-header">
            <div>
                <div class="page-eyebrow">Manajemen</div>
                <h1 class="page-title">Manajemen Pesanan</h1>
                <p class="page-subtitle">Tinjau dan kelola semua pesanan masuk maupun riwayat transaksi.</p>
            </div>
            <div class="action-row">
                <button class="btn-light-soft" type="button"><i class="bi bi-funnel"></i> Filter</button>
                <button class="btn-light-soft" type="button"><i class="bi bi-download"></i> Ekspor</button>
            </div>
        </div>

        <div class="metric-grid">
            <div class="metric-card">
                <div class="metric-label">Pendapatan Hari Ini</div>
                <div class="metric-value">Rp {{ number_format($orderStats['today_revenue'], 0, ',', '.') }}</div>
                <div class="metric-foot text-success">Performa penjualan hari ini</div>
            </div>
            <div class="metric-card">
                <div class="metric-label">Pesanan Aktif</div>
                <div class="metric-value">{{ $orderStats['active_orders'] }}</div>
                <div class="metric-foot text-warning">Masih perlu diproses</div>
            </div>
            <div class="metric-card">
                <div class="metric-label">Rata-rata Waktu Siap</div>
                <div class="metric-value">{{ $orderStats['avg_prep_time'] }}</div>
                <div class="metric-foot">Rata-rata hari ini</div>
            </div>
            <div class="metric-card">
                <div class="metric-label">Kepuasan</div>
                <div class="metric-value">{{ $orderStats['satisfaction'] }}</div>
                <div class="metric-foot text-success">Ulasan layanan</div>
            </div>
        </div>

        <div class="orders-toolbar">
            <form method="GET" class="d-flex flex-wrap gap-2 align-items-center w-100">
                <div class="topbar-search" style="max-width: 320px;">
                    <i class="bi bi-search"></i>
                    <input type="search" name="q" class="form-control" placeholder="Cari pelanggan, pesanan, item..." value="{{ $filters['q'] ?? '' }}">
                </div>
                <input type="date" name="date_from" class="form-control micro-select" style="width: 170px;" value="{{ $filters['date_from'] ?? '' }}">
                <input type="date" name="date_to" class="form-control micro-select" style="width: 170px;" value="{{ $filters['date_to'] ?? '' }}">
                <select name="payment_status" class="form-select micro-select" style="width: 170px;">
                    <option value="">Semua Pembayaran</option>
                    <option value="pending" @selected(($filters['payment_status'] ?? '') === 'pending')>Pending</option>
                    <option value="paid" @selected(($filters['payment_status'] ?? '') === 'paid')>Lunas</option>
                </select>
                <button type="submit" class="btn-light-soft"><i class="bi bi-funnel"></i> Terapkan</button>
                <a href="{{ route('orders.index') }}" class="btn-light-soft">Reset</a>
                <a href="{{ route('orders.create') }}" class="btn-brand ms-auto"><i class="bi bi-plus-lg"></i> Pesanan Baru</a>
            </form>
        </div>

        <div class="section-table">
            <div class="table-responsive">
                <table class="table table-clean" id="orders-table">
                    <thead>
                        <tr>
                            <th>No. Pesanan</th>
                            <th>Pelanggan</th>
                            <th>Item</th>
                            <th>Tanggal</th>
                            <th>Status</th>
                            <th>Total</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($orders as $order)
                            @php
                                $customerName = $order->customer_name ?? 'Customer Langsung';
                                $initials = collect(explode(' ', trim($customerName)))->filter()->take(2)->map(fn ($part) => strtoupper(substr($part, 0, 1)))->implode('');
                                $summaryItems = $order->detailPesanan->take(2)->map(function ($item) {
                                    return $item->quantity . 'x ' . $item->product_name;
                                })->implode(', ');
                                $statusClass = match ($order->payment_status) {
                                    'paid' => 'status-success',
                                    'failed' => 'status-danger',
                                    default => 'status-warning',
                                };
                            @endphp
                            <tr>
                                <td class="fw-semibold">{{ $order->order_number }}</td>
                                <td>
                                    <div class="d-flex align-items-center gap-3">
                                        <div class="customer-dot">{{ $initials ?: 'WC' }}</div>
                                        <div>
                                            <div class="fw-semibold">{{ $customerName }}</div>
                                            <div class="small text-muted">Meja {{ $order->table_number ?: '-' }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div>{{ $summaryItems }}</div>
                                    <div class="small text-muted">{{ $order->detailPesanan->count() }} item total</div>
                                </td>
                                <td>
                                    <div>{{ $order->order_date->format('d M') }}</div>
                                    <div class="small text-muted">{{ $order->order_date->format('H:i') }}</div>
                                </td>
                                <td>
                                    <span class="status-pill {{ $statusClass }}">
                                        {{ ($order->payment_status ?? 'pending') === 'paid' ? 'Lunas' : ucfirst($order->payment_status ?? 'pending') }}
                                    </span>
                                </td>
                                <td class="fw-semibold">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</td>
                                <td>
                                    <div class="action-icons">
                                        <a href="{{ route('orders.show', $order) }}" class="text-reset"><i class="bi bi-eye"></i></a>
                                        <a href="{{ ($order->payment_status ?? 'pending') === 'paid' ? route('orders.receipt', $order) : route('orders.payment.create', $order) }}" class="text-reset"><i class="bi bi-receipt"></i></a>
                                        <span><i class="bi bi-three-dots"></i></span>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center text-muted py-4">Belum ada pesanan.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div class="d-flex justify-content-between align-items-center mt-3 flex-wrap gap-2">
            <div class="small text-muted">Menampilkan {{ $orders->count() }} dari total {{ $orders->total() }} pesanan</div>
            {{ $orders->links() }}
        </div>
    </div>

@endsection
