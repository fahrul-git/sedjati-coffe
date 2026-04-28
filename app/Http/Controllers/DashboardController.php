<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $summary = [
            'total_devices' => 8,
            'active_devices' => 6,
            'total_plants' => 124,
            'avg_temperature' => 28.4,
            'avg_humidity' => 76.2,
        ];

        $alerts = [
            'Suhu blok A berada di atas ambang normal.',
            'Satu perangkat sedang dalam status maintenance.',
        ];

        return view('dashboard.index', compact('summary', 'alerts'));
    }
}
