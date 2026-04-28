<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DeviceController extends Controller
{
    private array $devices = [
        [
            'code' => 'GH-01',
            'name' => 'Sensor Utama',
            'type' => 'sensor',
            'location' => 'Blok A',
            'status' => 'active',
        ],
        [
            'code' => 'GH-02',
            'name' => 'Sensor Timur',
            'type' => 'sensor',
            'location' => 'Blok B',
            'status' => 'maintenance',
        ],
        [
            'code' => 'GH-03',
            'name' => 'Controller Irigasi',
            'type' => 'controller',
            'location' => 'Blok C',
            'status' => 'active',
        ],
    ];

    public function index()
    {
        $devices = $this->devices;

        return view('devices.index', compact('devices'));
    }

    public function show(string $code)
    {
        $device = collect($this->devices)->firstWhere('code', $code);

        abort_if(! $device, 404);

        return view('devices.show', compact('device'));
    }
}
