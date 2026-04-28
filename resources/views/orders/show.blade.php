@extends('layouts.app')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-1">Detail Pesanan</h1>
            <p class="text-muted mb-0">{{ $order->order_number }}</p>
        </div>
        <a href="{{ route('orders.index') }}" class="btn btn-outline-secondary">Kembali</a>
    </div>

    <div class="row g-4">
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <h2 class="h5 mb-3">Informasi</h2>
                    <p class="mb-2"><strong>Pelanggan:</strong> {{ $order->customer_name ?? 'Walk-in Customer' }}</p>
                    <p class="mb-2"><strong>Kasir:</strong> {{ $order->user->name }}</p>
                    <p class="mb-2"><strong>Tanggal:</strong> {{ $order->order_date->format('d M Y H:i') }}</p>
                    <p class="mb-2"><strong>Status:</strong> {{ ucfirst($order->status) }}</p>
                    <p class="mb-0"><strong>Catatan:</strong> {{ $order->notes ?: '-' }}</p>
                </div>
            </div>
        </div>

        <div class="col-lg-8">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <h2 class="h5 mb-3">Item Pesanan</h2>
                    <div class="table-responsive">
                        <table class="table align-middle mb-0">
                            <thead>
                                <tr>
                                    <th>Produk</th>
                                    <th>Harga</th>
                                    <th>Qty</th>
                                    <th>Subtotal</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($order->items as $item)
                                    <tr>
                                        <td>{{ $item->product_name }}</td>
                                        <td>Rp {{ number_format($item->price, 0, ',', '.') }}</td>
                                        <td>{{ $item->quantity }}</td>
                                        <td>Rp {{ number_format($item->subtotal, 0, ',', '.') }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th colspan="3" class="text-end">Total</th>
                                    <th>Rp {{ number_format($order->total_amount, 0, ',', '.') }}</th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
