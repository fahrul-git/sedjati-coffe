@php
    $topbarTitle = 'Sedjati Coffee';
    $topbarSubtitle = 'Proses pembayaran';
    $topbarSearchPlaceholder = 'Cari pesanan...';
@endphp

@extends('layouts.app')

@section('content')
    <div class="page-grid">
        <div class="page-header">
            <div>
                <div class="page-eyebrow">Pembayaran</div>
                <h1 class="page-title">Proses Pembayaran</h1>
                <p class="page-subtitle">{{ $order->order_number }} siap diselesaikan.</p>
            </div>
        </div>

        <div class="form-shell">
            <div class="form-card">
                <h2 class="panel-title mb-1">Pilih metode pembayaran</h2>
                <p class="panel-subtitle mb-4">Proses pembayaran menggunakan metode yang aktif di pengaturan.</p>

                <form action="{{ route('orders.payment.store', $order) }}" method="POST">
                    @csrf

                    <div class="mb-3">
                        <label for="payment_method" class="form-label">Metode Pembayaran</label>
                        <select name="payment_method" id="payment_method" class="form-select soft-select">
                            @foreach ($paymentMethods as $value => $label)
                                <option value="{{ $value }}" @selected(old('payment_method', array_key_first($paymentMethods)) === $value)>{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="stack-item mb-4" id="cash-field">
                        <label for="paid_amount" class="form-label">Nominal Dibayar</label>
                        <input
                            type="number"
                            name="paid_amount"
                            id="paid_amount"
                            class="form-control soft-input"
                            min="0"
                            value="{{ old('paid_amount', (int) $order->total_amount) }}"
                        >
                        <div class="d-flex justify-content-between mt-3 small">
                            <span class="text-muted">Kembalian</span>
                            <strong id="change-preview">Rp 0</strong>
                        </div>
                    </div>

                    <div class="d-flex gap-2 flex-wrap">
                        <button type="submit" class="btn-brand"><i class="bi bi-check2-circle"></i> Proses Pembayaran</button>
                        <a href="{{ route('orders.show', $order) }}" class="btn-light-soft">Lihat Detail</a>
                    </div>
                </form>
            </div>

            <aside class="summary-card">
                <div class="page-eyebrow">Ringkasan Pesanan</div>
                <h2 class="panel-title mb-3">{{ $order->customer_name ?? 'Customer Langsung' }}</h2>
                <div class="info-list mb-4">
                    <div class="info-row">
                        <span class="muted-copy">Nomor Meja</span>
                        <strong>{{ $order->table_number ?: '-' }}</strong>
                    </div>
                    <div class="info-row">
                        <span class="muted-copy">Kasir</span>
                        <strong>{{ $order->user->name }}</strong>
                    </div>
                    <div class="info-row">
                        <span class="muted-copy">Total</span>
                        <strong>Rp {{ number_format($order->total_amount, 0, ',', '.') }}</strong>
                    </div>
                </div>

                <div class="stack-list">
                    @foreach ($order->detailPesanan as $item)
                        <div class="stack-item">
                            <div class="d-flex justify-content-between gap-3">
                                <div>
                                    <div class="fw-semibold">{{ $item->product_name }}</div>
                                    <div class="small text-muted">{{ $item->item_option ? ucwords($item->item_option) : ucfirst($item->serving_type) }} x {{ $item->quantity }}</div>
                                    @if ($item->item_note)
                                        <div class="small text-muted mt-1">Catatan: {{ $item->item_note }}</div>
                                    @endif
                                </div>
                                <strong>Rp {{ number_format($item->subtotal, 0, ',', '.') }}</strong>
                            </div>
                        </div>
                    @endforeach
                </div>
            </aside>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const paymentMethod = document.getElementById('payment_method');
            const cashField = document.getElementById('cash-field');
            const paidAmount = document.getElementById('paid_amount');
            const changePreview = document.getElementById('change-preview');
            const totalAmount = {{ (int) $order->total_amount }};

            function formatRupiah(number) {
                return 'Rp ' + new Intl.NumberFormat('id-ID').format(number);
            }

            function updateChangePreview() {
                const nominal = Number(paidAmount.value || 0);
                const change = Math.max(nominal - totalAmount, 0);
                changePreview.textContent = formatRupiah(change);
            }

            function toggleCashField() {
                const isCash = paymentMethod.value === 'cash';
                cashField.style.display = isCash ? '' : 'none';
                paidAmount.required = isCash;
                updateChangePreview();
            }

            paymentMethod.addEventListener('change', toggleCashField);
            paidAmount.addEventListener('input', updateChangePreview);
            toggleCashField();
        });
    </script>
@endsection
