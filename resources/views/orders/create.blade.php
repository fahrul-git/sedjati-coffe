@php
    $topbarTitle = 'Sedjati Coffee';
    $topbarSubtitle = 'Create order';
    $topbarSearchPlaceholder = 'Search menu items...';
@endphp

@extends('layouts.app')

@section('content')
    <div class="page-grid">
        <div class="page-header">
            <div>
                <div class="page-eyebrow">Point of Sale</div>
                <h1 class="page-title">New Order</h1>
                <p class="page-subtitle">Build an order with searchable menu items, custom options, and per-item notes.</p>
            </div>
        </div>

        <form action="{{ route('orders.store') }}" method="POST">
            @csrf

            <div class="form-shell">
                <div class="form-card">
                    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3 mb-4">
                        <div>
                            <h2 class="panel-title mb-1">Order Builder</h2>
                            <p class="panel-subtitle">Cari produk, atur jumlah, dan isi instruksi khusus bila perlu.</p>
                        </div>
                        <div class="topbar-search" style="max-width: 340px;">
                            <i class="bi bi-search"></i>
                            <input
                                type="search"
                                id="menu-search"
                                class="form-control"
                                placeholder="Cari kopi atau side dish..."
                            >
                        </div>
                    </div>

                    <div class="row mb-4">
                        <div class="col-md-7">
                            <label for="customer_name" class="form-label">Nama Pelanggan</label>
                            <input type="text" name="customer_name" id="customer_name" class="form-control soft-input" value="{{ old('customer_name') }}">
                        </div>
                        <div class="col-md-5">
                            <label for="table_number" class="form-label">Nomor Meja</label>
                            <input type="text" name="table_number" id="table_number" class="form-control soft-input" value="{{ old('table_number') }}" placeholder="Contoh: A01">
                        </div>
                    </div>

                    <div class="stack-list" id="menu-list">
                        @foreach ($products as $index => $product)
                            @php($options = $productOptions[$product->name] ?? [])

                            <div
                                class="stack-item menu-item"
                                data-search="{{ strtolower($product->name.' '.$product->category.' '.implode(' ', array_keys($options))) }}"
                            >
                                <div class="row g-3 align-items-start">
                                    <div class="col-md-4">
                                        <div class="d-flex align-items-center gap-3">
                                            <div class="trend-avatar"><i class="bi {{ $product->category === 'Coffee' ? 'bi-cup-hot' : 'bi-bag' }}"></i></div>
                                            <div>
                                                <div class="fw-semibold">{{ $product->name }}</div>
                                                <div class="small text-muted">{{ $product->category }} • Rp {{ number_format($product->price, 0, ',', '.') }}</div>
                                                <div class="small {{ $product->stock > 10 ? 'text-success' : 'text-warning' }}">Stock {{ $product->stock }}</div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-3">
                                        <label class="form-label small text-muted">Pilihan</label>
                                        @if ($options !== [])
                                            <select name="items[{{ $index }}][item_option]" class="form-select form-select-sm soft-select">
                                                @foreach ($options as $value => $label)
                                                    <option value="{{ $value }}" @selected(old("items.$index.item_option", array_key_first($options)) === $value)>{{ $label }}</option>
                                                @endforeach
                                            </select>
                                        @else
                                            <input type="hidden" name="items[{{ $index }}][item_option]" value="">
                                            <div class="small text-muted pt-2">Tanpa pilihan tambahan</div>
                                        @endif
                                    </div>

                                    <div class="col-md-2">
                                        <input type="hidden" name="items[{{ $index }}][product_id]" value="{{ $product->id }}">
                                        <label class="form-label small text-muted">Jumlah</label>
                                        <input
                                            type="number"
                                            name="items[{{ $index }}][quantity]"
                                            class="form-control soft-input"
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
                                            class="form-control soft-textarea"
                                            placeholder="Contoh: less sugar, tanpa mayo"
                                        >{{ old("items.$index.item_note") }}</textarea>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <aside class="summary-card">
                    <div class="page-eyebrow">Order Checklist</div>
                    <h2 class="panel-title mb-3">Before saving</h2>
                    <div class="stack-list">
                        <div class="stack-item">
                            <div class="fw-semibold mb-1">Search fast</div>
                            <div class="small text-muted">Gunakan search untuk memfilter coffee dan side dish secara instan.</div>
                        </div>
                        <div class="stack-item">
                            <div class="fw-semibold mb-1">Choose correct option</div>
                            <div class="small text-muted">Pastikan varian menu sesuai: panas/es, rasa, atau jenis burger.</div>
                        </div>
                        <div class="stack-item">
                            <div class="fw-semibold mb-1">Add item notes</div>
                            <div class="small text-muted">Isi hanya bila ada permintaan khusus dari pelanggan.</div>
                        </div>
                    </div>

                    <div class="mt-4 d-grid gap-2">
                        <button type="submit" class="btn-brand"><i class="bi bi-arrow-right-circle"></i> Lanjut ke Pembayaran</button>
                        <a href="{{ route('orders.index') }}" class="btn-light-soft text-center">Batal</a>
                    </div>
                </aside>
            </div>
        </form>
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
