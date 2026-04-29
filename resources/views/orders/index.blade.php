@php
    $topbarTitle = 'Sedjati Coffee';
    $topbarSubtitle = 'Orders management';
    $topbarSearchPlaceholder = 'Search orders...';
@endphp

@extends('layouts.app')

@section('content')
    <div class="page-grid">
        <div class="page-header">
            <div>
                <div class="page-eyebrow">Management</div>
                <h1 class="page-title">Orders Management</h1>
                <p class="page-subtitle">Review and manage all incoming and historical customer orders.</p>
            </div>
            <div class="action-row">
                <button class="btn-light-soft" type="button"><i class="bi bi-funnel"></i> Filter</button>
                <button class="btn-light-soft" type="button"><i class="bi bi-download"></i> Export</button>
            </div>
        </div>

        <div class="metric-grid">
            <div class="metric-card">
                <div class="metric-label">Today's Revenue</div>
                <div class="metric-value">${{ number_format($orderStats['today_revenue'] / 1000, 2) }}k</div>
                <div class="metric-foot text-success">+12.5%</div>
            </div>
            <div class="metric-card">
                <div class="metric-label">Active Orders</div>
                <div class="metric-value">{{ $orderStats['active_orders'] }}</div>
                <div class="metric-foot text-warning">8 pending</div>
            </div>
            <div class="metric-card">
                <div class="metric-label">Avg. Prep Time</div>
                <div class="metric-value">{{ $orderStats['avg_prep_time'] }}</div>
                <div class="metric-foot">-0.5m today</div>
            </div>
            <div class="metric-card">
                <div class="metric-label">Satisfaction</div>
                <div class="metric-value">{{ $orderStats['satisfaction'] }}</div>
                <div class="metric-foot text-success">4.9/5.0</div>
            </div>
        </div>

        <div class="orders-toolbar">
            <div class="topbar-search" style="max-width: 360px;">
                <i class="bi bi-search"></i>
                <input type="search" id="orders-search" class="form-control" placeholder="Search customer, order, items...">
            </div>
            <a href="{{ route('orders.create') }}" class="btn-brand"><i class="bi bi-plus-lg"></i> New Order</a>
        </div>

        <div class="section-table">
            <div class="table-responsive">
                <table class="table table-clean" id="orders-table">
                    <thead>
                        <tr>
                            <th>Order ID</th>
                            <th>Customer</th>
                            <th>Items</th>
                            <th>Date</th>
                            <th>Status</th>
                            <th>Total</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($orders as $order)
                            @php
                                $customerName = $order->customer_name ?? 'Walk-in Customer';
                                $initials = collect(explode(' ', trim($customerName)))->filter()->take(2)->map(fn ($part) => strtoupper(substr($part, 0, 1)))->implode('');
                                $summaryItems = $order->detailPesanan->take(2)->map(function ($item) {
                                    return $item->quantity . 'x ' . $item->product_name;
                                })->implode(', ');
                                $statusClass = match ($order->payment_status) {
                                    'paid' => 'status-success',
                                    'failed' => 'status-danger',
                                    default => 'status-warning',
                                };
                            @endphp
                            <tr class="order-row" data-search="{{ strtolower($order->order_number.' '.$customerName.' '.$summaryItems) }}">
                                <td class="fw-semibold">{{ $order->order_number }}</td>
                                <td>
                                    <div class="d-flex align-items-center gap-3">
                                        <div class="customer-dot">{{ $initials ?: 'WC' }}</div>
                                        <div>
                                            <div class="fw-semibold">{{ $customerName }}</div>
                                            <div class="small text-muted">Meja {{ $order->table_number ?: '-' }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div>{{ $summaryItems }}</div>
                                    <div class="small text-muted">{{ $order->detailPesanan->count() }} item total</div>
                                </td>
                                <td>
                                    <div>{{ $order->order_date->format('M d') }}</div>
                                    <div class="small text-muted">{{ $order->order_date->format('h:i A') }}</div>
                                </td>
                                <td>
                                    <span class="status-pill {{ $statusClass }}">
                                        {{ ucfirst($order->payment_status ?? 'pending') }}
                                    </span>
                                </td>
                                <td class="fw-semibold">${{ number_format($order->total_amount / 1000, 2) }}</td>
                                <td>
                                    <div class="action-icons">
                                        <a href="{{ route('orders.show', $order) }}" class="text-reset"><i class="bi bi-eye"></i></a>
                                        <a href="{{ ($order->payment_status ?? 'pending') === 'paid' ? route('orders.receipt', $order) : route('orders.payment.create', $order) }}" class="text-reset"><i class="bi bi-receipt"></i></a>
                                        <span><i class="bi bi-three-dots"></i></span>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center text-muted py-4">Belum ada pesanan.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div class="d-flex justify-content-between align-items-center mt-3 flex-wrap gap-2">
            <div class="small text-muted">Showing {{ $orders->count() }} to {{ $orders->count() }} of {{ $orders->total() }} orders</div>
            {{ $orders->links() }}
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const searchInput = document.getElementById('orders-search');
            const rows = document.querySelectorAll('.order-row');

            searchInput?.addEventListener('input', function () {
                const keyword = this.value.trim().toLowerCase();

                rows.forEach((row) => {
                    row.style.display = (row.dataset.search || '').includes(keyword) ? '' : 'none';
                });
            });
        });
    </script>
@endsection
