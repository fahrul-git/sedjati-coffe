@extends('layouts.app')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-1">Dashboard</h1>
            <p class="text-muted mb-0">Ringkasan operasional Sedjati Coffee.</p>
        </div>
        <a href="{{ route('orders.create') }}" class="btn btn-dark">Buat Pesanan</a>
    </div>

    <div class="row g-3 mb-4">
        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <p class="text-muted mb-1">Total Produk</p>
                    <h2 class="h4 mb-0">{{ $stats['total_products'] }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <p class="text-muted mb-1">Produk Aktif</p>
                    <h2 class="h4 mb-0">{{ $stats['available_products'] }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <p class="text-muted mb-1">Pesanan Hari Ini</p>
                    <h2 class="h4 mb-0">{{ $stats['today_orders'] }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <p class="text-muted mb-1">Total Pendapatan</p>
                    <h2 class="h4 mb-0">Rp {{ number_format($stats['total_revenue'], 0, ',', '.') }}</h2>
                </div>
            </div>
        </div>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-body">
            <h2 class="h5 mb-3">Pesanan Terbaru</h2>
            <div class="table-responsive">
                <table class="table align-middle mb-0">
                    <thead>
                        <tr>
                            <th>No. Pesanan</th>
                            <th>Pelanggan</th>
                            <th>Kasir</th>
                            <th>Total</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($recentOrders as $order)
                            <tr>
                                <td>
                                    <a href="{{ route('orders.show', $order) }}">{{ $order->order_number }}</a>
                                </td>
                                <td>{{ $order->customer_name ?? 'Walk-in Customer' }}</td>
                                <td>{{ $order->user->name }}</td>
                                <td>Rp {{ number_format($order->total_amount, 0, ',', '.') }}</td>
                                <td><span class="badge text-bg-success">{{ ucfirst($order->status) }}</span></td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center text-muted">Belum ada pesanan.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
