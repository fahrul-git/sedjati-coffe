@php
    $topbarTitle = 'Sedjati Coffee';
    $topbarSubtitle = 'Ringkasan dashboard';
    $topbarSearchPlaceholder = 'Cari pesanan, menu, customer...';
@endphp

@extends('layouts.app')

@section('content')
    <div class="page-grid">
        <div class="page-header">
            <div>
                <div class="page-eyebrow">Ringkasan Hari Ini</div>
                <h1 class="page-title">Dashboard</h1>
                <p class="page-subtitle">Berikut ringkasan aktivitas Sedjati Coffee hari ini.</p>
            </div>
        </div>

        <div class="metric-grid">
            <div class="metric-card">
                <div class="metric-label">Total Pendapatan</div>
                <div class="metric-value">Rp {{ number_format($stats['total_revenue'], 0, ',', '.') }}</div>
                <div class="metric-foot text-success">{{ $stats['revenue_change'] }} dibanding kemarin</div>
                <i class="bi bi-cash-stack metric-icon"></i>
            </div>
            <div class="metric-card">
                <div class="metric-label">Total Pesanan</div>
                <div class="metric-value">{{ $stats['total_orders'] }}</div>
                <div class="metric-foot text-success">{{ $stats['orders_change'] }} dibanding kemarin</div>
                <i class="bi bi-receipt-cutoff metric-icon"></i>
            </div>
            <div class="metric-card featured">
                <div class="metric-label">Meja Aktif</div>
                <div class="metric-value">{{ $stats['active_tables'] }}</div>
                <div class="metric-foot">{{ $stats['active_tables_change'] }}</div>
                <i class="bi bi-table metric-icon"></i>
            </div>
            <div class="metric-card">
                <div class="metric-label">Rata-rata Waktu Siap</div>
                <div class="metric-value">{{ $stats['avg_prep_time'] }} menit</div>
                <div class="metric-foot text-danger">{{ $stats['prep_time_change'] }}</div>
                <i class="bi bi-stopwatch metric-icon"></i>
            </div>
        </div>

        <div class="dashboard-panels">
            <div class="panel-card">
                <div class="panel-heading">
                    <div>
                        <h2 class="panel-title">Ringkasan Penjualan</h2>
                        <p class="panel-subtitle">Volume transaksi mingguan</p>
                    </div>
                    <span class="micro-select">7 Hari Terakhir</span>
                </div>
                <div class="bar-chart">
                    @foreach ($salesOverview as $bar)
                        <div class="bar-wrap">
                            <div class="bar-stack">
                                <div class="bar" style="height: {{ $bar['base'] * 2 }}px;"></div>
                                <div class="bar {{ in_array($bar['day'], ['Wed', 'Fri', 'Sun']) ? 'accent' : 'soft' }}" style="height: {{ $bar['current'] * 2 }}px;"></div>
                            </div>
                            <div class="bar-label">{{ $bar['day'] }}</div>
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="panel-card">
                <div class="panel-heading">
                    <div>
                        <h2 class="panel-title">Menu Terlaris</h2>
                        <p class="panel-subtitle">Performa terbaik minggu ini</p>
                    </div>
                </div>
                <div class="trend-list">
                    @forelse ($trendingItems as $item)
                        <div class="trend-item">
                            <div class="d-flex align-items-center gap-3">
                                <div class="trend-avatar"><i class="bi {{ $item['icon'] }}"></i></div>
                                <div>
                                    <div class="fw-semibold">{{ $item['name'] }}</div>
                                    <div class="small text-muted">{{ $item['orders'] }} pesanan minggu ini</div>
                                </div>
                            </div>
                            <div class="fw-semibold">Rp {{ number_format($item['price'], 0, ',', '.') }}</div>
                        </div>
                    @empty
                        <div class="text-muted small">Belum ada data item populer.</div>
                    @endforelse
                </div>
                <div class="mt-3">
                    <a href="{{ route('products.index') }}" class="btn btn-light-soft w-100">Lihat Performa Menu Lengkap</a>
                </div>
            </div>
        </div>

        <div class="section-table">
            <div class="section-table-header">
                <div>
                    <h2 class="panel-title">Pesanan Terbaru</h2>
                    <p class="panel-subtitle">Transaksi terbaru dari area operasional</p>
                </div>
                <a href="{{ route('orders.index') }}" class="small fw-semibold text-decoration-none" style="color: var(--brand);">Lihat Semua <i class="bi bi-arrow-right"></i></a>
            </div>
            <div class="table-responsive">
                <table class="table table-clean">
                    <thead>
                        <tr>
                            <th>No. Pesanan</th>
                            <th>Pelanggan</th>
                            <th>Item</th>
                            <th>Waktu</th>
                            <th>Total</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($recentOrders as $order)
                            <tr>
                                <td class="fw-semibold">{{ $order->order_number }}</td>
                                <td>{{ $order->customer_name ?? 'Customer Langsung' }}</td>
                                <td>{{ $order->detailPesanan->take(2)->pluck('product_name')->implode(', ') }}</td>
                                <td>{{ $order->order_date->diffForHumans() }}</td>
                                <td>Rp {{ number_format($order->total_amount, 0, ',', '.') }}</td>
                                <td>
                                    <span class="status-pill {{ $order->payment_status === 'paid' ? 'status-success' : 'status-warning' }}">
                                        {{ ($order->payment_status ?? 'pending') === 'paid' ? 'Lunas' : 'Pending' }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center text-muted py-4">Belum ada transaksi terbaru.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
