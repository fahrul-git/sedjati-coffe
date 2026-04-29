@extends('layouts.app')

@section('content')
    <div class="card border-0 shadow-sm">
        <div class="card-body p-4">
            <div class="d-flex justify-content-between align-items-center flex-wrap gap-3 mb-4">
                <div>
                    <h1 class="h3 mb-1">Buat Pesanan</h1>
                    <p class="text-muted mb-0">Cari menu lebih cepat lalu tentukan varian, jumlah, dan catatan untuk tiap item.</p>
                </div>
                <div class="col-md-4 px-0">
                    <input
                        type="search"
                        id="menu-search"
                        class="form-control"
                        placeholder="Cari kopi atau side dish..."
                    >
                </div>
            </div>

            <form action="{{ route('orders.store') }}" method="POST">
                @csrf

                <div class="row mb-4">
                    <div class="col-md-6">
                        <label for="customer_name" class="form-label">Nama Pelanggan</label>
                        <input type="text" name="customer_name" id="customer_name" class="form-control" value="{{ old('customer_name') }}">
                    </div>
                    <div class="col-md-3">
                        <label for="table_number" class="form-label">Nomor Meja</label>
                        <input type="text" name="table_number" id="table_number" class="form-control" value="{{ old('table_number') }}" placeholder="Contoh: A01">
                    </div>
                </div>

                <h2 class="h5 mb-3">Item Pesanan</h2>

                <div id="menu-list">
                    @foreach ($products as $index => $product)
                        @php($options = $productOptions[$product->name] ?? [])

                        <div
                            class="border rounded p-3 mb-3 bg-light menu-item"
                            data-search="{{ strtolower($product->name.' '.$product->category.' '.implode(' ', array_keys($options))) }}"
                        >
                            <div class="row g-3 align-items-start">
                                <div class="col-md-4">
                                    <div class="fw-semibold">{{ $product->name }}</div>
                                    <div class="small text-muted">{{ $product->category }} - Rp {{ number_format($product->price, 0, ',', '.') }}</div>
                                    <div class="small text-muted mt-1">Stok tersedia: {{ $product->stock }}</div>
                                </div>

                                <div class="col-md-3">
                                    <label class="form-label small text-muted">Pilihan</label>
                                    @if ($options !== [])
                                        <select name="items[{{ $index }}][item_option]" class="form-select form-select-sm">
                                            @foreach ($options as $value => $label)
                                                <option value="{{ $value }}" @selected(old("items.$index.item_option", array_key_first($options)) === $value)>{{ $label }}</option>
                                            @endforeach
                                        </select>
                                    @else
                                        <input type="hidden" name="items[{{ $index }}][item_option]" value="">
                                        <span class="small text-muted">Tanpa pilihan tambahan</span>
                                    @endif
                                </div>

                                <div class="col-md-2">
                                    <input type="hidden" name="items[{{ $index }}][product_id]" value="{{ $product->id }}">
                                    <label class="form-label small text-muted">Jumlah</label>
                                    <input
                                        type="number"
                                        name="items[{{ $index }}][quantity]"
                                        class="form-control"
                                        min="0"
                                        max="{{ $product->stock }}"
                                        value="{{ old("items.$index.quantity", 0) }}"
                                    >
                                </div>

                                <div class="col-md-3">
                                    <label class="form-label small text-muted">Catatan Item</label>
                                    <textarea
                                        name="items[{{ $index }}][item_note]"
                                        rows="2"
                                        class="form-control form-control-sm"
                                        placeholder="Contoh: less sugar, tanpa mayo"
                                    >{{ old("items.$index.item_note") }}</textarea>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <p class="small text-muted mt-3 mb-4">Isi kuantitas lebih dari 0 dan tambahkan catatan hanya pada item yang memang dipesan.</p>

                <button type="submit" class="btn btn-dark">Simpan Pesanan</button>
                <a href="{{ route('orders.index') }}" class="btn btn-outline-secondary">Kembali</a>
            </form>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const searchInput = document.getElementById('menu-search');
            const menuItems = document.querySelectorAll('.menu-item');

            searchInput?.addEventListener('input', function () {
                const keyword = this.value.trim().toLowerCase();

                menuItems.forEach((item) => {
                    const haystack = item.dataset.search || '';
                    item.style.display = haystack.includes(keyword) ? '' : 'none';
                });
            });
        });
    </script>
@endsection
