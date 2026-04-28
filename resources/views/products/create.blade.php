@extends('layouts.app')

@section('content')
    <div class="card border-0 shadow-sm">
        <div class="card-body p-4">
            <h1 class="h3 mb-4">Tambah Produk</h1>
            <form action="{{ route('products.store') }}" method="POST">
                @csrf
                @php($submitLabel = 'Simpan Produk')
                @include('products._form')
            </form>
        </div>
    </div>
@endsection
