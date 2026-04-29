@extends('layouts.app')

@section('content')
    <div class="row g-4">
        <div class="col-lg-7">
            <div class="card border-0 shadow-sm">
                <div class="card-body p-4">
                    <h1 class="h3 mb-1">Pembayaran Pesanan</h1>
                    <p class="text-muted mb-4">{{ $order->order_number }} - lanjutkan proses payment.</p>

                    <form action="{{ route('orders.payment.store', $order) }}" method="POST">
                        @csrf

                        <div class="mb-3">
                            <label for="payment_method" class="form-label">Metode Pembayaran</label>
                            <select name="payment_method" id="payment_method" class="form-select">
                                <option value="cash" @selected(old('payment_method') === 'cash')>Cash</option>
                                <option value="qris" @selected(old('payment_method') === 'qris')>QRIS</option>
                                <option value="debit card" @selected(old('payment_method') === 'debit card')>Debit Card</option>
                            </select>
                        </div>

                        <div class="mb-4" id="cash-field">
                            <label for="paid_amount" class="form-label">Nominal Dibayar</label>
                            <input
                                type="number"
                                name="paid_amount"
                                id="paid_amount"
                                class="form-control"
                                min="0"
                                value="{{ old('paid_amount', (int) $order->total_amount) }}"
                            >
                            <div class="form-text">Wajib diisi untuk pembayaran cash.</div>
                            <div class="mt-2 small text-muted">
                                Kembalian: <strong id="change-preview">Rp 0</strong>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-dark">Proses Pembayaran</button>
                        <a href="{{ route('orders.show', $order) }}" class="btn btn-outline-secondary">Lihat Detail</a>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-lg-5">
            <div class="card border-0 shadow-sm">
                <div class="card-body p-4">
                    <h2 class="h5 mb-3">Ringkasan Pesanan</h2>
                    <p class="mb-2"><strong>Pelanggan:</strong> {{ $order->customer_name ?? 'Walk-in Customer' }}</p>
                    <p class="mb-2"><strong>Nomor Meja:</strong> {{ $order->table_number ?: '-' }}</p>
                    <p class="mb-2"><strong>Kasir:</strong> {{ $order->user->name }}</p>
                    <p class="mb-3"><strong>Total:</strong> Rp {{ number_format($order->total_amount, 0, ',', '.') }}</p>

                    <ul class="list-group list-group-flush">
                        @foreach ($order->detailPesanan as $item)
                            <li class="list-group-item px-0 d-flex justify-content-between">
                                <div>
                                    <div class="fw-semibold">{{ $item->product_name }}</div>
                                    <div class="small text-muted">{{ $item->item_option ? ucwords($item->item_option) : ucfirst($item->serving_type) }} x {{ $item->quantity }}</div>
                                </div>
                                <span>Rp {{ number_format($item->subtotal, 0, ',', '.') }}</span>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
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

            function toggleCashField() {
                const isCash = paymentMethod.value === 'cash';
                cashField.style.display = isCash ? '' : 'none';
                paidAmount.required = isCash;
                updateChangePreview();
            }

            function updateChangePreview() {
                const nominal = Number(paidAmount.value || 0);
                const change = Math.max(nominal - totalAmount, 0);
                changePreview.textContent = formatRupiah(change);
            }

            paymentMethod.addEventListener('change', toggleCashField);
            paidAmount.addEventListener('input', updateChangePreview);
            toggleCashField();
        });
    </script>
@endsection
