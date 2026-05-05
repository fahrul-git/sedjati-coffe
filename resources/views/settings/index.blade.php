@php
    use Illuminate\Support\Facades\Storage;

    $topbarTitle = 'Sedjati Coffee';
    $topbarSubtitle = 'Pengaturan sistem';
    $topbarSearchPlaceholder = 'Cari pengaturan...';
    $general = $settings->get('general', collect())->keyBy('key');
    $payment = $settings->get('payment', collect())->keyBy('key');
    $order = $settings->get('order', collect())->keyBy('key');
    $selectedPaymentMethods = json_decode($payment->get('payment_methods')?->value ?? '[]', true) ?: [];
    $companyLogoPath = $general->get('company_logo_path')?->value;
@endphp

@extends('layouts.app')

@section('content')
    <div class="page-grid">
        <div class="page-header">
            <div>
                <div class="page-eyebrow">Pengaturan</div>
                <h1 class="page-title">Setting</h1>
                <p class="page-subtitle">Kelola pengaturan umum, pembayaran, pengguna, dan pesanan.</p>
            </div>
        </div>

        <div class="metric-grid">
            <div class="metric-card">
                <div class="metric-label">Nama Usaha</div>
                <div class="metric-value" style="font-size: 1.1rem;">{{ $general->get('business_name')?->value }}</div>
                <div class="metric-foot">Identitas bisnis aktif</div>
            </div>
            <div class="metric-card">
                <div class="metric-label">Pajak</div>
                <div class="metric-value">{{ $payment->get('tax_percent')?->value }}%</div>
                <div class="metric-foot">Persentase pajak transaksi</div>
            </div>
            <div class="metric-card">
                <div class="metric-label">Biaya Layanan</div>
                <div class="metric-value">{{ $payment->get('service_charge_percent')?->value }}%</div>
                <div class="metric-foot">Biaya layanan aktif</div>
            </div>
            <div class="metric-card featured">
                <div class="metric-label">Status Default</div>
                <div class="metric-value">{{ strtoupper($order->get('default_payment_status')?->value ?? 'pending') }}</div>
                <div class="metric-foot">Status default pembayaran</div>
            </div>
        </div>

        <div class="dashboard-panels">
            <div class="panel-card">
                <div class="panel-heading">
                    <div>
                        <h2 class="panel-title">1. Pengaturan Umum</h2>
                        <p class="panel-subtitle">Nama usaha, alamat, kontak, dan logo perusahaan.</p>
                    </div>
                </div>

                <form action="{{ route('settings.general.update') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label for="business_name" class="form-label">Nama Usaha</label>
                        <input type="text" name="business_name" id="business_name" class="form-control soft-input" value="{{ old('business_name', $general->get('business_name')?->value) }}" required>
                    </div>

                    <div class="mb-3">
                        <label for="business_address" class="form-label">Alamat</label>
                        <textarea name="business_address" id="business_address" rows="4" class="form-control soft-textarea" required>{{ old('business_address', $general->get('business_address')?->value) }}</textarea>
                    </div>

                    <div class="mb-3">
                        <label for="business_contact" class="form-label">Kontak</label>
                        <input type="text" name="business_contact" id="business_contact" class="form-control soft-input" value="{{ old('business_contact', $general->get('business_contact')?->value) }}" required>
                    </div>

                    <div class="mb-4">
                        <label for="company_logo" class="form-label">Logo Perusahaan</label>
                        <input type="file" name="company_logo" id="company_logo" class="form-control soft-input" accept="image/png,image/jpeg,image/webp">
                        @if ($companyLogoPath)
                            <div class="mt-3 d-flex align-items-start gap-3">
                                <img src="{{ Storage::url($companyLogoPath) }}" alt="Logo perusahaan" class="rounded-4 border" style="width: 120px; height: 120px; object-fit: cover;">
                                <div class="form-check mt-2">
                                    <input type="checkbox" name="remove_company_logo" id="remove_company_logo" class="form-check-input" value="1">
                                    <label for="remove_company_logo" class="form-check-label">Hapus logo saat menyimpan</label>
                                </div>
                            </div>
                        @endif
                    </div>

                    <button type="submit" class="btn-brand">Simpan Pengaturan Umum</button>
                </form>
            </div>

            <div class="panel-card">
                <div class="panel-heading">
                    <div>
                        <h2 class="panel-title">2. Pengaturan Pembayaran</h2>
                        <p class="panel-subtitle">Pajak, service charge, dan metode pembayaran.</p>
                    </div>
                </div>

                <form action="{{ route('settings.payment.update') }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="tax_percent" class="form-label">Pajak (%)</label>
                            <input type="number" name="tax_percent" id="tax_percent" class="form-control soft-input" min="0" step="0.01" value="{{ old('tax_percent', $payment->get('tax_percent')?->value) }}" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="service_charge_percent" class="form-label">Biaya Layanan (%)</label>
                            <input type="number" name="service_charge_percent" id="service_charge_percent" class="form-control soft-input" min="0" step="0.01" value="{{ old('service_charge_percent', $payment->get('service_charge_percent')?->value) }}" required>
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="form-label">Metode Pembayaran</label>
                        <div class="stack-list">
                            @foreach ($paymentMethodOptions as $value => $label)
                                <label class="stack-item d-flex align-items-center gap-2">
                                    <input type="checkbox" name="payment_methods[]" value="{{ $value }}" class="form-check-input mt-0" @checked(in_array($value, old('payment_methods', $selectedPaymentMethods), true))>
                                    <span>{{ $label }}</span>
                                </label>
                            @endforeach
                        </div>
                    </div>

                    <button type="submit" class="btn-brand">Simpan Pengaturan Pembayaran</button>
                </form>
            </div>
        </div>

        <div class="dashboard-panels">
            <div class="panel-card">
                <div class="panel-heading">
                    <div>
                        <h2 class="panel-title">3. Manajemen Pengguna</h2>
                        <p class="panel-subtitle">Tambah user admin atau kasir, lalu atur role-nya.</p>
                    </div>
                </div>

                <form action="{{ route('settings.users.store') }}" method="POST" class="mb-4">
                    @csrf
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="user_name" class="form-label">Nama User</label>
                            <input type="text" name="name" id="user_name" class="form-control soft-input" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="user_email" class="form-label">Email</label>
                            <input type="email" name="email" id="user_email" class="form-control soft-input" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="user_password" class="form-label">Password</label>
                            <input type="password" name="password" id="user_password" class="form-control soft-input" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="user_role" class="form-label">Role</label>
                            <select name="role" id="user_role" class="form-select soft-select" required>
                                <option value="admin">Admin</option>
                                <option value="kasir">Kasir</option>
                            </select>
                        </div>
                    </div>

                    <button type="submit" class="btn-brand">Tambah User</button>
                </form>

                <div class="section-table">
                    <div class="table-responsive">
                        <table class="table table-clean align-middle">
                            <thead>
                                <tr>
                                    <th>Nama</th>
                                    <th>Email</th>
                                    <th>Role</th>
                                    <th>Manajemen Role</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($users as $user)
                                    <tr>
                                        <td>{{ $user->name }}</td>
                                        <td>{{ $user->email }}</td>
                                        <td><span class="status-pill status-success">{{ strtoupper($user->role) }}</span></td>
                                        <td>
                                            <form action="{{ route('settings.users.role.update', $user) }}" method="POST" class="d-flex gap-2">
                                                @csrf
                                                @method('PUT')
                                                <select name="role" class="form-select form-select-sm soft-select" style="max-width: 160px;">
                                                    <option value="admin" @selected($user->role === 'admin')>Admin</option>
                                                    <option value="kasir" @selected($user->role === 'kasir')>Kasir</option>
                                                </select>
                                                <button type="submit" class="btn btn-sm btn-outline-dark">Perbarui</button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="panel-card">
                <div class="panel-heading">
                    <div>
                        <h2 class="panel-title">4. Pengaturan Pesanan</h2>
                        <p class="panel-subtitle">Format nomor pesanan dan status default pembayaran.</p>
                    </div>
                </div>

                <form action="{{ route('settings.order.update') }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label for="order_number_format" class="form-label">Format Nomor Pesanan</label>
                        <input type="text" name="order_number_format" id="order_number_format" class="form-control soft-input" value="{{ old('order_number_format', $order->get('order_number_format')?->value) }}" required>
                        <div class="form-text">Gunakan placeholder `{date}`, `{time}`, `{sequence}`, atau `{rand}`.</div>
                    </div>

                    <div class="mb-4">
                        <label for="default_payment_status" class="form-label">Status Default</label>
                        <select name="default_payment_status" id="default_payment_status" class="form-select soft-select">
                            <option value="pending" @selected(old('default_payment_status', $order->get('default_payment_status')?->value) === 'pending')>Pending</option>
                            <option value="paid" @selected(old('default_payment_status', $order->get('default_payment_status')?->value) === 'paid')>Lunas</option>
                        </select>
                    </div>

                    <button type="submit" class="btn-brand">Simpan Pengaturan Pesanan</button>
                </form>
            </div>
        </div>
    </div>
@endsection
