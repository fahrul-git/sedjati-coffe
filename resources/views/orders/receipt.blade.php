@extends('layouts.app')

@section('content')
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm">
                <div class="card-body p-4 p-lg-5">
                    <div class="text-center border-bottom pb-4 mb-4">
                        <h1 class="h3 mb-1">Struk Pembayaran</h1>
                        <p class="text-muted mb-0">Sedjati Coffee</p>
                    </div>

                    <div class="row mb-4">
                        <div class="col-md-6">
                            <p class="mb-2"><strong>No. Pesanan:</strong> {{ $order->order_number }}</p>
                            <p class="mb-2"><strong>Pelanggan:</strong> {{ $order->customer_name ?? 'Walk-in Customer' }}</p>
                            <p class="mb-2"><strong>Nomor Meja:</strong> {{ $order->table_number ?: '-' }}</p>
                            <p class="mb-0"><strong>Kasir:</strong> {{ $order->user->name }}</p>
                        </div>
                        <div class="col-md-6 text-md-end mt-3 mt-md-0">
                            <p class="mb-2"><strong>Tanggal:</strong> {{ $order->order_date->format('d M Y H:i') }}</p>
                            <p class="mb-2"><strong>Metode:</strong> {{ ucwords($order->payment_method) }}</p>
                            <p class="mb-0"><strong>Status:</strong> {{ ucfirst($order->payment_status) }}</p>
                        </div>
                    </div>

                    <div class="table-responsive mb-4">
                        <table class="table align-middle">
                            <thead>
                                <tr>
                                    <th>Produk</th>
                                    <th>Pilihan</th>
                                    <th>Qty</th>
                                    <th class="text-end">Subtotal</th>
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
                                        <td>{{ $item->quantity }}</td>
                                        <td class="text-end">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th colspan="3" class="text-end">Total</th>
                                    <th class="text-end">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</th>
                                </tr>
                                <tr>
                                    <th colspan="3" class="text-end">Dibayar</th>
                                    <th class="text-end">Rp {{ number_format($order->paid_amount, 0, ',', '.') }}</th>
                                </tr>
                                @if ($order->payment_method === 'cash')
                                    <tr>
                                        <th colspan="3" class="text-end">Kembalian</th>
                                        <th class="text-end">Rp {{ number_format($order->change_amount, 0, ',', '.') }}</th>
                                    </tr>
                                @endif
                            </tfoot>
                        </table>
                    </div>

                    <div class="d-flex flex-wrap gap-2 justify-content-center">
                        <a href="{{ route('orders.show', $order) }}" class="btn btn-outline-secondary">Lihat Detail</a>
                        <a href="{{ route('orders.create') }}" class="btn btn-dark">Pesanan Baru</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
