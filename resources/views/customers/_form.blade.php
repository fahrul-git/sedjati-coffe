<div class="mb-3">
    <label for="name" class="form-label">Nama Customer</label>
    <input type="text" name="name" id="name" class="form-control soft-input" value="{{ old('name', $customer->name ?? '') }}" required>
</div>

<div class="row">
    <div class="col-md-6 mb-3">
        <label for="phone" class="form-label">No HP Customer</label>
        <input type="text" name="phone" id="phone" class="form-control soft-input" value="{{ old('phone', $customer->phone ?? '') }}" required>
    </div>
    <div class="col-md-6 mb-3">
        <label for="first_purchase_date" class="form-label">Tanggal Pertama Beli</label>
        <input type="date" name="first_purchase_date" id="first_purchase_date" class="form-control soft-input" value="{{ old('first_purchase_date', isset($customer?->first_purchase_date) ? $customer->first_purchase_date->format('Y-m-d') : '') }}">
    </div>
</div>

<div class="row">
    <div class="col-md-6 mb-3">
        <label for="total_transactions" class="form-label">Total Transaksi</label>
        <input type="number" name="total_transactions" id="total_transactions" class="form-control soft-input" min="0" value="{{ old('total_transactions', $customer->total_transactions ?? 0) }}" required>
    </div>
    <div class="col-md-6 mb-4">
        <label for="total_spending" class="form-label">Total Spending</label>
        <input type="number" name="total_spending" id="total_spending" class="form-control soft-input" min="0" value="{{ old('total_spending', $customer->total_spending ?? 0) }}" required>
    </div>
</div>

<button type="submit" class="btn btn-dark">{{ $submitLabel }}</button>
<a href="{{ route('customers.index') }}" class="btn btn-outline-secondary">Kembali</a>
