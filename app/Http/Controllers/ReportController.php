<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $startDate = $request->input('start_date', now()->startOfMonth()->toDateString());
        $endDate = $request->input('end_date', now()->toDateString());

        $orders = Order::query()
            ->whereBetween(DB::raw('DATE(order_date)'), [$startDate, $endDate])
            ->with('user')
            ->latest('order_date')
            ->get();

        $summary = [
            'total_orders' => $orders->count(),
            'total_revenue' => $orders->sum('total_amount'),
            'average_order' => $orders->count() > 0 ? $orders->avg('total_amount') : 0,
        ];

        return view('reports.index', compact('orders', 'summary', 'startDate', 'endDate'));
    }
}
