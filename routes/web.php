<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DeliveryController;
use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Route;

// Halaman Utama Marketplace
Route::get('/', [HomeController::class, 'index'])->name('home');

// Autentikasi
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});

Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

// Nantinya middleware ini butuh Auth system.
// Karena belum ada, kita definisikan rutenya saja.
Route::middleware(['auth'])->group(function () {
    Route::get('/orders/{order}/delivery/create', [DeliveryController::class, 'create'])->name('deliveries.create');
    Route::post('/orders/{order}/delivery', [DeliveryController::class, 'store'])->name('deliveries.store');
    Route::get('/orders/{order}/delivery', [DeliveryController::class, 'show'])->name('deliveries.show');
    Route::patch('/deliveries/{delivery}/status', [DeliveryController::class, 'updateStatus'])->name('deliveries.update-status');
});
