<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\LogoutController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PesananController;
use App\Http\Controllers\ProdukController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\SettingController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return auth()->check()
        ? redirect()->route('dashboard')
        : redirect()->route('login');
});

Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'create'])->name('login');
    Route::post('/login', [LoginController::class, 'store'])->name('login.store');
});

Route::middleware('auth')->group(function () {
    Route::post('/logout', LogoutController::class)->name('logout');

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::resource('products', ProdukController::class)->middleware('role:admin');
    Route::resource('customers', CustomerController::class)->middleware('role:admin');
    Route::get('/settings', [SettingController::class, 'index'])->middleware('role:admin')->name('settings.index');
    Route::put('/settings/general', [SettingController::class, 'updateGeneral'])->middleware('role:admin')->name('settings.general.update');
    Route::put('/settings/payment', [SettingController::class, 'updatePayment'])->middleware('role:admin')->name('settings.payment.update');
    Route::put('/settings/order', [SettingController::class, 'updateOrder'])->middleware('role:admin')->name('settings.order.update');
    Route::post('/settings/users', [SettingController::class, 'storeUser'])->middleware('role:admin')->name('settings.users.store');
    Route::put('/settings/users/{user}/role', [SettingController::class, 'updateUserRole'])->middleware('role:admin')->name('settings.users.role.update');

    Route::get('/orders', [PesananController::class, 'index'])->name('orders.index');
    Route::get('/orders/create', [PesananController::class, 'create'])->name('orders.create');
    Route::post('/orders', [PesananController::class, 'store'])->name('orders.store');
    Route::get('/orders/{order}/payment', [PesananController::class, 'createPayment'])->name('orders.payment.create');
    Route::post('/orders/{order}/payment', [PesananController::class, 'storePayment'])->name('orders.payment.store');
    Route::get('/orders/{order}/receipt', [PesananController::class, 'receipt'])->name('orders.receipt');
    Route::get('/orders/{order}', [PesananController::class, 'show'])->name('orders.show');

    Route::get('/reports', [ReportController::class, 'index'])
        ->middleware('role:admin')
        ->name('reports.index');
});
