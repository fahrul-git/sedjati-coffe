@php
    $topbarTitle = 'Sedjati Coffee';
    $topbarSubtitle = 'Ringkasan laporan';
    $topbarSearchPlaceholder = 'Cari laporan...';
@endphp

@extends('layouts.app')

@section('content')
    <div class="page-grid">
        <div class="page-header">
            <div>
                <div class="page-eyebrow">Performa</div>
                <h1 class="page-title">Pusat Laporan</h1>
                <p class="page-subtitle">Pantau pendapatan, volume pesanan, dan ekspor ringkasan transaksi berdasarkan rentang tanggal.</p>
            </div>
            <div class="action-row">
                <a href="{{ route('reports.index', ['start_date' => $startDate, 'end_date' => $endDate, 'export' => 'csv']) }}" class="btn-brand">
                    <i class="bi bi-download"></i> Ekspor CSV
                </a>
            </div>
        </div>

        <div class="panel-card mb-4">
            <form method="GET" class="d-flex flex-wrap gap-2 align-items-end">
                <div>
                    <label for="start_date" class="form-label small text-muted">Tanggal Mulai</label>
                    <input type="date" name="start_date" id="start_date" class="form-control soft-input" value="{{ $startDate }}">
                </div>
                <div>
                    <label for="end_date" class="form-label small text-muted">Tanggal Selesai</label>
                    <input type="date" name="end_date" id="end_date" class="form-control soft-input" value="{{ $endDate }}">
                </div>
                <div>
                    <button type="submit" class="btn-brand">Tampilkan Laporan</button>
                </div>
            </form>
        </div>

        <div class="metric-grid">
            <div class="metric-card">
                <div class="metric-label">Total Pesanan</div>
                <div class="metric-value">{{ $summary['total_orders'] }}</div>
                <div class="metric-foot">Pesanan pada periode terpilih</div>
            </div>
            <div class="metric-card featured">
                <div class="metric-label">Total Pendapatan</div>
                <div class="metric-value">Rp {{ number_format($summary['total_revenue'], 0, ',', '.') }}</div>
                <div class="metric-foot">Transaksi lunas dan selesai</div>
            </div>
            <div class="metric-card">
                <div class="metric-label">Rata-rata Pesanan</div>
                <div class="metric-value">Rp {{ number_format($summary['average_order'], 0, ',', '.') }}</div>
                <div class="metric-foot">Nilai transaksi rata-rata</div>
            </div>
            <div class="metric-card">
                <div class="metric-label">Pesanan Lunas</div>
                <div class="metric-value">{{ $summary['paid_orders'] }}</div>
                <div class="metric-foot">{{ $summary['active_tables'] }} meja aktif dalam data</div>
            </div>
        </div>

        <div class="dashboard-panels">
            <div class="panel-card">
                <div class="panel-heading">
                    <div>
                        <h2 class="panel-title">Tren Pendapatan</h2>
                        <p class="panel-subtitle">Grafik batang mingguan dari rentang data terpilih.</p>
                    </div>
                </div>
                <div class="bar-chart">
                    @foreach ($chartData as $bar)
                        @php($scaledHeight = max((int) round($bar['value'] / 3000), 14))
                        <div class="bar-wrap">
                            <div class="bar-stack">
                                <div class="bar accent" style="height: {{ min($scaledHeight, 160) }}px;"></div>
                            </div>
                            <div class="bar-label">{{ $bar['day'] }}</div>
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="panel-card">
                <div class="page-eyebrow">Catatan Ekspor</div>
                <h2 class="panel-title mb-3">Apa saja yang ikut?</h2>
                <div class="stack-list">
                    <div class="stack-item">
                        <div class="fw-semibold mb-1">Ekspor CSV</div>
                        <div class="small text-muted">Unduh nomor pesanan, tanggal, kasir, pelanggan, meja, status bayar, dan total.</div>
                    </div>
                    <div class="stack-item">
                        <div class="fw-semibold mb-1">Data Terfilter</div>
                        <div class="small text-muted">Ekspor mengikuti rentang tanggal yang sedang aktif di halaman ini.</div>
                    </div>
                    <div class="stack-item">
                        <div class="fw-semibold mb-1">Ringkasan Operasional</div>
                        <div class="small text-muted">Gunakan kartu metrik untuk melihat performa cepat sebelum mengunduh data.</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="section-table">
            <div class="section-table-header">
                <div>
                    <h2 class="panel-title">Laporan Transaksi</h2>
                    <p class="panel-subtitle">Daftar detail untuk periode laporan yang dipilih.</p>
                </div>
            </div>
            <div class="table-responsive">
                <table class="table table-clean">
                    <thead>
                        <tr>
                            <th>No. Pesanan</th>
                            <th>Tanggal</th>
                            <th>Kasir</th>
                            <th>Pelanggan</th>
                            <th>Meja</th>
                            <th>Metode</th>
                            <th>Status</th>
                            <th>Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($orders as $order)
                            <tr>
                                <td class="fw-semibold">{{ $order->order_number }}</td>
                                <td>{{ $order->order_date->format('d M Y H:i') }}</td>
                                <td>{{ $order->user->name }}</td>
                                <td>{{ $order->customer_name ?? 'Customer Langsung' }}</td>
                                <td>{{ $order->table_number ?: '-' }}</td>
                                <td>{{ $order->payment_method ? ucwords($order->payment_method) : '-' }}</td>
                                <td>
                                    <span class="status-pill {{ ($order->payment_status ?? 'pending') === 'paid' ? 'status-success' : 'status-warning' }}">
                                        {{ ($order->payment_status ?? 'pending') === 'paid' ? 'Lunas' : ucfirst($order->payment_status ?? 'pending') }}
                                    </span>
                                </td>
                                <td>Rp {{ number_format($order->total_amount, 0, ',', '.') }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center text-muted py-4">Tidak ada data pada periode ini.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
