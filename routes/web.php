<?php

use App\Http\Controllers\DeliveryController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// Nantinya middleware ini butuh Auth system.
// Karena belum ada, kita definisikan rutenya saja.
Route::middleware(['auth'])->group(function () {
    Route::get('/orders/{order}/delivery/create', [DeliveryController::class, 'create'])->name('deliveries.create');
    Route::post('/orders/{order}/delivery', [DeliveryController::class, 'store'])->name('deliveries.store');
    Route::get('/orders/{order}/delivery', [DeliveryController::class, 'show'])->name('deliveries.show');
    Route::patch('/deliveries/{delivery}/status', [DeliveryController::class, 'updateStatus'])->name('deliveries.update-status');
});
