@extends('layouts.app')

@section('content')
    <div class="card border-0 shadow-sm">
        <div class="card-body p-4">
            <h1 class="h3 mb-4">Buat Pesanan</h1>

            <form action="{{ route('orders.store') }}" method="POST">
                @csrf

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="customer_name" class="form-label">Nama Pelanggan</label>
                        <input type="text" name="customer_name" id="customer_name" class="form-control" value="{{ old('customer_name') }}">
                    </div>
                </div>

                <div class="mb-3">
                    <label for="notes" class="form-label">Catatan</label>
                    <textarea name="notes" id="notes" rows="3" class="form-control">{{ old('notes') }}</textarea>
                </div>

                <h2 class="h5 mt-4 mb-3">Item Pesanan</h2>

                @foreach ($products as $index => $product)
                    <div class="row align-items-center border rounded p-3 mb-2 bg-light">
                        <div class="col-md-5">
                            <div class="fw-semibold">{{ $product->name }}</div>
                            <div class="small text-muted">{{ $product->category }} • Rp {{ number_format($product->price, 0, ',', '.') }}</div>
                        </div>
                        <div class="col-md-3 small text-muted">
                            Stok tersedia: {{ $product->stock }}
                        </div>
                        <div class="col-md-4">
                            <input type="hidden" name="items[{{ $index }}][product_id]" value="{{ $product->id }}">
                            <input
                                type="number"
                                name="items[{{ $index }}][quantity]"
                                class="form-control"
                                min="0"
                                max="{{ $product->stock }}"
                                value="{{ old("items.$index.quantity", 0) }}"
                            >
                        </div>
                    </div>
                @endforeach

                <p class="small text-muted mt-3">Isi kuantitas lebih dari 0 untuk produk yang dipesan.</p>

                <button type="submit" class="btn btn-dark">Simpan Pesanan</button>
                <a href="{{ route('orders.index') }}" class="btn btn-outline-secondary">Kembali</a>
            </form>
        </div>
    </div>
@endsection
