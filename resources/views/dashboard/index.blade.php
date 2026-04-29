@php
    $topbarTitle = 'Sedjati Coffee';
    $topbarSubtitle = 'Dashboard overview';
    $topbarSearchPlaceholder = 'Search orders, menu, customers...';
@endphp

@extends('layouts.app')

@section('content')
    <div class="page-grid">
        <div class="page-header">
            <div>
                <div class="page-eyebrow">Morning Overview</div>
                <h1 class="page-title">Dashboard</h1>
                <p class="page-subtitle">Here’s what’s happening at Sedjati Coffee today.</p>
            </div>
        </div>

        <div class="metric-grid">
            <div class="metric-card">
                <div class="metric-label">Total Revenue</div>
                <div class="metric-value">${{ number_format($stats['total_revenue'] / 1000, 2) }}k</div>
                <div class="metric-foot text-success">{{ $stats['revenue_change'] }} vs yesterday</div>
                <i class="bi bi-cash-stack metric-icon"></i>
            </div>
            <div class="metric-card">
                <div class="metric-label">Total Orders</div>
                <div class="metric-value">{{ $stats['total_orders'] }}</div>
                <div class="metric-foot text-success">{{ $stats['orders_change'] }} vs yesterday</div>
                <i class="bi bi-receipt-cutoff metric-icon"></i>
            </div>
            <div class="metric-card featured">
                <div class="metric-label">Active Tables</div>
                <div class="metric-value">{{ $stats['active_tables'] }}</div>
                <div class="metric-foot">{{ $stats['active_tables_change'] }}</div>
                <i class="bi bi-table metric-icon"></i>
            </div>
            <div class="metric-card">
                <div class="metric-label">Avg. Prep Time</div>
                <div class="metric-value">{{ $stats['avg_prep_time'] }} min</div>
                <div class="metric-foot text-danger">{{ $stats['prep_time_change'] }}</div>
                <i class="bi bi-stopwatch metric-icon"></i>
            </div>
        </div>

        <div class="dashboard-panels">
            <div class="panel-card">
                <div class="panel-heading">
                    <div>
                        <h2 class="panel-title">Sales Overview</h2>
                        <p class="panel-subtitle">Weekly transaction volume</p>
                    </div>
                    <span class="micro-select">Last 7 Days</span>
                </div>
                <div class="bar-chart">
                    @foreach ($salesOverview as $bar)
                        <div class="bar-wrap">
                            <div class="bar-stack">
                                <div class="bar" style="height: {{ $bar['base'] * 2 }}px;"></div>
                                <div class="bar {{ in_array($bar['day'], ['Wed', 'Fri', 'Sun']) ? 'accent' : 'soft' }}" style="height: {{ $bar['current'] * 2 }}px;"></div>
                            </div>
                            <div class="bar-label">{{ $bar['day'] }}</div>
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="panel-card">
                <div class="panel-heading">
                    <div>
                        <h2 class="panel-title">Trending Items</h2>
                        <p class="panel-subtitle">Top movers this week</p>
                    </div>
                </div>
                <div class="trend-list">
                    @forelse ($trendingItems as $item)
                        <div class="trend-item">
                            <div class="d-flex align-items-center gap-3">
                                <div class="trend-avatar"><i class="bi {{ $item['icon'] }}"></i></div>
                                <div>
                                    <div class="fw-semibold">{{ $item['name'] }}</div>
                                    <div class="small text-muted">{{ $item['orders'] }} orders this week</div>
                                </div>
                            </div>
                            <div class="fw-semibold">${{ number_format($item['price'] / 1000, 2) }}</div>
                        </div>
                    @empty
                        <div class="text-muted small">Belum ada data item populer.</div>
                    @endforelse
                </div>
                <div class="mt-3">
                    <a href="{{ route('products.index') }}" class="btn btn-light-soft w-100">View Full Menu Performance</a>
                </div>
            </div>
        </div>

        <div class="section-table">
            <div class="section-table-header">
                <div>
                    <h2 class="panel-title">Recent Orders</h2>
                    <p class="panel-subtitle">Latest transactions from the floor</p>
                </div>
                <a href="{{ route('orders.index') }}" class="small fw-semibold text-decoration-none" style="color: var(--brand);">View All <i class="bi bi-arrow-right"></i></a>
            </div>
            <div class="table-responsive">
                <table class="table table-clean">
                    <thead>
                        <tr>
                            <th>Order ID</th>
                            <th>Customer</th>
                            <th>Items</th>
                            <th>Time</th>
                            <th>Total</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($recentOrders as $order)
                            <tr>
                                <td class="fw-semibold">{{ $order->order_number }}</td>
                                <td>{{ $order->customer_name ?? 'Walk-in Customer' }}</td>
                                <td>{{ $order->detailPesanan->take(2)->pluck('product_name')->implode(', ') }}</td>
                                <td>{{ $order->order_date->diffForHumans() }}</td>
                                <td>${{ number_format($order->total_amount / 1000, 2) }}</td>
                                <td>
                                    <span class="status-pill {{ $order->payment_status === 'paid' ? 'status-success' : 'status-warning' }}">
                                        {{ strtoupper($order->payment_status ?? 'pending') }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center text-muted py-4">Belum ada transaksi terbaru.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
