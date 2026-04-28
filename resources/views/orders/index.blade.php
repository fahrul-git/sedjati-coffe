@extends('layouts.app')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-1">Pesanan</h1>
            <p class="text-muted mb-0">Daftar transaksi penjualan cafe.</p>
        </div>
        <a href="{{ route('orders.create') }}" class="btn btn-dark">Buat Pesanan</a>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table align-middle mb-0">
                    <thead>
                        <tr>
                            <th>No. Pesanan</th>
                            <th>Tanggal</th>
                            <th>Pelanggan</th>
                            <th>Kasir</th>
                            <th>Total</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($orders as $order)
                            <tr>
                                <td>{{ $order->order_number }}</td>
                                <td>{{ $order->order_date->format('d M Y H:i') }}</td>
                                <td>{{ $order->customer_name ?? 'Walk-in Customer' }}</td>
                                <td>{{ $order->user->name }}</td>
                                <td>Rp {{ number_format($order->total_amount, 0, ',', '.') }}</td>
                                <td class="text-end">
                                    <a href="{{ route('orders.show', $order) }}" class="btn btn-sm btn-outline-dark">Detail</a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center text-muted">Belum ada pesanan.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-3">
                {{ $orders->links() }}
            </div>
        </div>
    </div>
@endsection
