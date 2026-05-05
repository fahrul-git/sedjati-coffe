@php
    $topbarTitle = 'Sedjati Coffee';
    $topbarSubtitle = 'Tambah customer';
    $topbarSearchPlaceholder = 'Cari customer...';
@endphp

@extends('layouts.app')

@section('content')
    <div class="page-grid">
        <div class="page-header">
            <div>
                <div class="page-eyebrow">Pelanggan</div>
                <h1 class="page-title">Tambah Customer</h1>
                <p class="page-subtitle">Tambahkan data customer sesuai informasi profil dan riwayat belinya.</p>
            </div>
        </div>

        <div class="form-shell">
            <div class="form-card">
                <form action="{{ route('customers.store') }}" method="POST">
                    @csrf
                    @php($submitLabel = 'Simpan Customer')
                    @include('customers._form')
                </form>
            </div>

            <aside class="summary-card">
                <div class="page-eyebrow">Ringkasan</div>
                <h2 class="panel-title mb-3">Data yang dicatat</h2>
                <div class="stack-list">
                    <div class="stack-item">
                        <div class="fw-semibold mb-1">Identitas customer</div>
                        <div class="small text-muted">Nama dan nomor HP membantu follow up pelanggan tetap.</div>
                    </div>
                    <div class="stack-item">
                        <div class="fw-semibold mb-1">Nilai customer</div>
                        <div class="small text-muted">Total transaksi dan spending membantu melihat pelanggan loyal.</div>
                    </div>
                    <div class="stack-item">
                        <div class="fw-semibold mb-1">Riwayat awal</div>
                        <div class="small text-muted">Tanggal pertama beli berguna untuk melihat usia hubungan customer.</div>
                    </div>
                </div>
            </aside>
        </div>
    </div>
@endsection
