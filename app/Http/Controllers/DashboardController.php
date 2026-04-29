<?php

namespace App\Http\Controllers;

use App\Models\DetailPesanan;
use App\Models\Pesanan;
use App\Models\Produk;
use Illuminate\Support\Collection;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $completedOrders = Pesanan::query()->where('status', 'completed');
        $todayOrders = Pesanan::query()->whereDate('order_date', today());

        $stats = [
            'total_revenue' => $completedOrders->sum('total_amount'),
            'total_orders' => Pesanan::count(),
            'active_tables' => Pesanan::query()->whereDate('order_date', today())->whereNotNull('table_number')->distinct('table_number')->count('table_number'),
            'avg_prep_time' => 4.2,
            'revenue_change' => '+12.5%',
            'orders_change' => '+8.2%',
            'active_tables_change' => 'Busy peak period',
            'prep_time_change' => '-0.5m today',
            'today_orders' => $todayOrders->count(),
        ];

        $recentOrders = Pesanan::query()
            ->latest('order_date')
            ->with(['user', 'detailPesanan'])
            ->take(5)
            ->get();

        $salesOverview = collect([
            ['day' => 'Mon', 'base' => 26, 'current' => 18],
            ['day' => 'Tue', 'base' => 34, 'current' => 27],
            ['day' => 'Wed', 'base' => 58, 'current' => 44],
            ['day' => 'Thu', 'base' => 22, 'current' => 18],
            ['day' => 'Fri', 'base' => 72, 'current' => 61],
            ['day' => 'Sat', 'base' => 81, 'current' => 74],
            ['day' => 'Sun', 'base' => 43, 'current' => 34],
        ]);

        $trendingItems = DetailPesanan::query()
            ->selectRaw('product_name, SUM(quantity) as total_qty, AVG(price) as avg_price')
            ->groupBy('product_name')
            ->orderByDesc('total_qty')
            ->take(3)
            ->get()
            ->map(function ($item, int $index) {
                $icons = ['bi-cup-hot-fill', 'bi-cup-straw', 'bi-bagel-fill'];

                return [
                    'name' => $item->product_name,
                    'orders' => (int) $item->total_qty,
                    'price' => (float) $item->avg_price,
                    'icon' => $icons[$index] ?? 'bi-star-fill',
                ];
            });

        return view('dashboard.index', compact('stats', 'recentOrders', 'salesOverview', 'trendingItems'));
    }
}
