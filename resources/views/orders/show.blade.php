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
                    <p class="mb-2"><strong>Nomor Meja:</strong> {{ $order->table_number ?: '-' }}</p>
                    <p class="mb-2"><strong>Kasir:</strong> {{ $order->user->name }}</p>
                    <p class="mb-2"><strong>Tanggal:</strong> {{ $order->order_date->format('d M Y H:i') }}</p>
                    <p class="mb-2"><strong>Status:</strong> {{ ucfirst($order->status) }}</p>
                    <p class="mb-2"><strong>Metode Bayar:</strong> {{ $order->payment_method ? ucwords($order->payment_method) : '-' }}</p>
                    <p class="mb-2"><strong>Status Bayar:</strong> {{ ucfirst($order->payment_status ?? 'pending') }}</p>
                    @if ($order->payment_method === 'cash' && $order->payment_status === 'paid')
                        <p class="mb-2"><strong>Kembalian:</strong> Rp {{ number_format($order->change_amount, 0, ',', '.') }}</p>
                    @endif
                    <p class="mb-0"><strong>Catatan:</strong> {{ $order->notes ?: '-' }}</p>
                </div>
            </div>
            @if (($order->payment_status ?? 'pending') !== 'paid')
                <div class="mt-3">
                    <a href="{{ route('orders.payment.create', $order) }}" class="btn btn-dark w-100">Lanjutkan Pembayaran</a>
                </div>
            @else
                <div class="mt-3">
                    <a href="{{ route('orders.receipt', $order) }}" class="btn btn-outline-dark w-100">Lihat Struk</a>
                </div>
            @endif
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
                                    <th>Pilihan</th>
                                    <th>Harga</th>
                                    <th>Qty</th>
                                    <th>Subtotal</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($order->detailPesanan as $item)
                                    <tr>
                                        <td>
                                            <div>{{ $item->product_name }}</div>
                                            @if ($item->item_note)
                                                <div class="small text-muted">Catatan: {{ $item->item_note }}</div>
                                            @endif
                                        </td>
                                        <td>{{ $item->item_option ? ucwords($item->item_option) : ucfirst($item->serving_type) }}</td>
                                        <td>Rp {{ number_format($item->price, 0, ',', '.') }}</td>
                                        <td>{{ $item->quantity }}</td>
                                        <td>Rp {{ number_format($item->subtotal, 0, ',', '.') }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th colspan="4" class="text-end">Total</th>
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
