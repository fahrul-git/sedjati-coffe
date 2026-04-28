<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DeviceController;
use Illuminate\Support\Facades\Route;

// HALAMAN UTAMA (WELCOME)
Route::get('/', function () {
    return view('welcome');
})->name('welcome');

// HALAMAN PROFILE
Route::get('/profile', function () {
    return view('profile');
})->name('profile');

// HALAMAN DASHBOARD
Route::get('/dashboard', function () {
    return redirect()->route('dashboard');
});

Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
Route::get('/devices', [DeviceController::class, 'index'])->name('devices.index');
Route::get('/devices/{code}', [DeviceController::class, 'show'])->name('devices.show');