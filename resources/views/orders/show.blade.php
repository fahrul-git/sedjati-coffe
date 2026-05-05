@php
    $topbarTitle = 'Sedjati Coffee';
    $topbarSubtitle = 'Detail pesanan';
    $topbarSearchPlaceholder = 'Cari pesanan...';
@endphp

@extends('layouts.app')

@section('content')
    <div class="page-grid">
        <div class="page-header">
            <div>
                <div class="page-eyebrow">Profil Pesanan</div>
                <h1 class="page-title">{{ $order->order_number }}</h1>
                <p class="page-subtitle">Rincian lengkap pesanan pelanggan, item, dan progres pembayaran.</p>
            </div>
            <div class="action-row">
                <a href="{{ route('orders.index') }}" class="btn-light-soft">Kembali ke Pesanan</a>
                @if (($order->payment_status ?? 'pending') !== 'paid')
                    <a href="{{ route('orders.payment.create', $order) }}" class="btn-brand">Lanjutkan Pembayaran</a>
                @else
                    <a href="{{ route('orders.receipt', $order) }}" class="btn-brand">Lihat Struk</a>
                @endif
            </div>
        </div>

        <div class="dashboard-panels">
            <div class="panel-card">
                <div class="panel-heading">
                    <div>
                        <h2 class="panel-title">Item Pesanan</h2>
                        <p class="panel-subtitle">{{ $order->detailPesanan->count() }} item dalam transaksi ini</p>
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table table-clean">
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
                                        <div class="fw-semibold">{{ $item->product_name }}</div>
                                        @if ($item->item_note)
                                            <div class="small text-muted mt-1">Catatan: {{ $item->item_note }}</div>
                                        @endif
                                    </td>
                                    <td>{{ $item->item_option ? ucwords($item->item_option) : ucfirst($item->serving_type) }}</td>
                                    <td>Rp {{ number_format($item->price, 0, ',', '.') }}</td>
                                    <td>{{ $item->quantity }}</td>
                                    <td class="fw-semibold">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</td>
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

            <aside class="panel-card">
                <div class="page-eyebrow">Informasi</div>
                <div class="info-list mt-3">
                    <div class="info-row">
                        <span class="muted-copy">Pelanggan</span>
                        <strong>{{ $order->customer_name ?? 'Customer Langsung' }}</strong>
                    </div>
                    <div class="info-row">
                        <span class="muted-copy">Nomor Meja</span>
                        <strong>{{ $order->table_number ?: '-' }}</strong>
                    </div>
                    <div class="info-row">
                        <span class="muted-copy">Kasir</span>
                        <strong>{{ $order->user->name }}</strong>
                    </div>
                    <div class="info-row">
                        <span class="muted-copy">Tanggal</span>
                        <strong>{{ $order->order_date->format('d M Y H:i') }}</strong>
                    </div>
                    <div class="info-row">
                        <span class="muted-copy">Status Order</span>
                        <span class="status-pill {{ $order->status === 'completed' ? 'status-success' : 'status-warning' }}">{{ $order->status === 'completed' ? 'Selesai' : ucfirst($order->status) }}</span>
                    </div>
                    <div class="info-row">
                        <span class="muted-copy">Metode Bayar</span>
                        <strong>{{ $order->payment_method ? ucwords($order->payment_method) : '-' }}</strong>
                    </div>
                    <div class="info-row">
                        <span class="muted-copy">Status Bayar</span>
                        <span class="status-pill {{ ($order->payment_status ?? 'pending') === 'paid' ? 'status-success' : 'status-warning' }}">{{ ($order->payment_status ?? 'pending') === 'paid' ? 'Lunas' : ucfirst($order->payment_status ?? 'pending') }}</span>
                    </div>
                    @if ($order->payment_method === 'cash' && $order->payment_status === 'paid')
                        <div class="info-row">
                            <span class="muted-copy">Kembalian</span>
                            <strong>Rp {{ number_format($order->change_amount, 0, ',', '.') }}</strong>
                        </div>
                    @endif
                </div>
            </aside>
        </div>
    </div>
@endsection
