@extends('layouts.app')

@section('content')
    <div class="card border-0 shadow-sm">
        <div class="card-body p-4">
            <h1 class="h3 mb-4">Edit Produk</h1>
            <form action="{{ route('products.update', $product) }}" method="POST">
                @csrf
                @method('PUT')
                @php($submitLabel = 'Perbarui Produk')
                @include('products._form')
            </form>
        </div>
    </div>
@endsection
