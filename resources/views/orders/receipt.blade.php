@php
    $topbarTitle = 'Sedjati Coffee';
    $topbarSubtitle = 'Struk pembayaran';
    $topbarSearchPlaceholder = 'Cari pesanan...';
@endphp

@extends('layouts.app')

@section('content')
    <div class="page-grid receipt-shell">
            <div class="page-header">
            <div>
                <div class="page-eyebrow">Struk</div>
                <h1 class="page-title">Struk Pembayaran</h1>
                <p class="page-subtitle">Ringkasan transaksi selesai yang siap dicetak.</p>
            </div>
            <div class="action-row">
                <a href="{{ route('orders.show', $order) }}" class="btn-light-soft">Lihat Detail</a>
                <button type="button" class="btn-light-soft" onclick="window.print()">Cetak</button>
                <a href="{{ route('orders.create') }}" class="btn-brand">Pesanan Baru</a>
            </div>
        </div>

        <div class="receipt-card">
            <div class="text-center border-bottom pb-4 mb-4">
                <div class="brand-mark mx-auto mb-3"><i class="bi bi-cup-hot"></i></div>
                <h2 class="h3 mb-1">Sedjati Coffee</h2>
                <p class="text-muted mb-0">Struk pembayaran untuk pesanan yang sudah selesai</p>
            </div>

            <div class="row mb-4">
                <div class="col-md-6">
                    <div class="info-list">
                        <div class="info-row">
                            <span class="muted-copy">No. Pesanan</span>
                            <strong>{{ $order->order_number }}</strong>
                        </div>
                        <div class="info-row">
                            <span class="muted-copy">Pelanggan</span>
                            <strong>{{ $order->customer_name ?? 'Customer Langsung' }}</strong>
                        </div>
                        <div class="info-row">
                            <span class="muted-copy">Nomor Meja</span>
                            <strong>{{ $order->table_number ?: '-' }}</strong>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 mt-4 mt-md-0">
                    <div class="info-list">
                        <div class="info-row">
                            <span class="muted-copy">Kasir</span>
                            <strong>{{ $order->user->name }}</strong>
                        </div>
                        <div class="info-row">
                            <span class="muted-copy">Metode</span>
                            <strong>{{ ucwords($order->payment_method) }}</strong>
                        </div>
                        <div class="info-row">
                            <span class="muted-copy">Tanggal</span>
                            <strong>{{ $order->order_date->format('d M Y H:i') }}</strong>
                        </div>
                    </div>
                </div>
            </div>

            <div class="table-responsive mb-4">
                <table class="table table-clean">
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
                                    <div class="fw-semibold">{{ $item->product_name }}</div>
                                    @if ($item->item_note)
                                        <div class="small text-muted mt-1">Catatan: {{ $item->item_note }}</div>
                                    @endif
                                </td>
                                <td>{{ $item->item_option ? ucwords($item->item_option) : ucfirst($item->serving_type) }}</td>
                                <td>{{ $item->quantity }}</td>
                                <td class="text-end">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="row justify-content-end">
                <div class="col-md-5">
                    <div class="info-list">
                        <div class="info-row">
                            <span class="muted-copy">Total</span>
                            <strong>Rp {{ number_format($order->total_amount, 0, ',', '.') }}</strong>
                        </div>
                        <div class="info-row">
                            <span class="muted-copy">Dibayar</span>
                            <strong>Rp {{ number_format($order->paid_amount, 0, ',', '.') }}</strong>
                        </div>
                        @if ($order->payment_method === 'cash')
                            <div class="info-row">
                                <span class="muted-copy">Kembalian</span>
                                <strong>Rp {{ number_format($order->change_amount, 0, ',', '.') }}</strong>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
    <style>
        @media print {
            body {
                background: #fff !important;
            }

            .sidebar,
            .topbar,
            .page-header .action-row,
            .alert {
                display: none !important;
            }

            .workspace {
                grid-template-columns: 1fr !important;
            }

            .workspace-panel,
            .page-grid,
            .receipt-card {
                box-shadow: none !important;
                border: 0 !important;
                background: #fff !important;
                padding: 0 !important;
            }

            .receipt-shell {
                max-width: none !important;
                margin: 0 !important;
            }
        }
    </style>
@endsection
