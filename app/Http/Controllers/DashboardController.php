<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;

class DashboardController extends Controller
{
    public function index()
    {
        $totalRevenue = Order::query()
            ->where('status', 'completed')
            ->sum('total_amount');

        $stats = [
            'total_products' => Product::count(),
            'available_products' => Product::where('is_active', true)->count(),
            'total_orders' => Order::count(),
            'today_orders' => Order::whereDate('order_date', today())->count(),
            'total_revenue' => $totalRevenue,
        ];

        $recentOrders = Order::query()
            ->latest('order_date')
            ->with('user')
            ->take(5)
            ->get();

        return view('dashboard.index', compact('stats', 'recentOrders'));
    }
}
