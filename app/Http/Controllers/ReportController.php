<?php

namespace App\Http\Controllers;

use App\Models\Pesanan;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ReportController extends Controller
{
    public function index(Request $request): View|StreamedResponse
    {
        $startDate = $request->input('start_date', now()->startOfMonth()->toDateString());
        $endDate = $request->input('end_date', now()->toDateString());

        $orders = Pesanan::query()
            ->whereBetween(DB::raw('DATE(order_date)'), [$startDate, $endDate])
            ->with('user')
            ->latest('order_date')
            ->get();

        if ($request->input('export') === 'csv') {
            return $this->exportCsv($orders, $startDate, $endDate);
        }

        $summary = [
            'total_orders' => $orders->count(),
            'total_revenue' => $orders->sum('total_amount'),
            'average_order' => $orders->count() > 0 ? $orders->avg('total_amount') : 0,
            'paid_orders' => $orders->where('payment_status', 'paid')->count(),
            'active_tables' => $orders->whereNotNull('table_number')->pluck('table_number')->unique()->count(),
        ];

        $dailySummary = $orders
            ->groupBy(fn (Pesanan $order) => $order->order_date->format('D'))
            ->map(fn (Collection $dayOrders) => $dayOrders->sum('total_amount'));

        $chartData = collect(['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'])
            ->map(fn (string $day) => [
                'day' => $day,
                'value' => $dailySummary[$day] ?? 0,
            ]);

        return view('reports.index', compact('orders', 'summary', 'startDate', 'endDate', 'chartData'));
    }

    protected function exportCsv(Collection $orders, string $startDate, string $endDate): StreamedResponse
    {
        $fileName = 'laporan-sedjati-'.Str::of($startDate)->replace('-', '').'-'.Str::of($endDate)->replace('-', '').'.csv';

        return response()->streamDownload(function () use ($orders) {
            $output = fopen('php://output', 'w');

            fputcsv($output, ['No Pesanan', 'Tanggal', 'Kasir', 'Pelanggan', 'Nomor Meja', 'Metode Bayar', 'Status Bayar', 'Total']);

            foreach ($orders as $order) {
                fputcsv($output, [
                    $order->order_number,
                    $order->order_date->format('Y-m-d H:i'),
                    $order->user?->name,
                    $order->customer_name ?? 'Customer Langsung',
                    $order->table_number ?? '-',
                    $order->payment_method === 'cash' ? 'Tunai' : ($order->payment_method === 'debit card' ? 'Kartu Debit' : ($order->payment_method ?? '-')),
                    $order->payment_status === 'paid' ? 'Lunas' : ($order->payment_status ?? 'pending'),
                    $order->total_amount,
                ]);
            }

            fclose($output);
        }, $fileName, [
            'Content-Type' => 'text/csv; charset=UTF-8',
        ]);
    }
}
