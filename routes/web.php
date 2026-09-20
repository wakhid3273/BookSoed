<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\BookController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SellerOrderController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// ---------------------------------------------------------------
// Admin area (ERP)
// ---------------------------------------------------------------
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [AdminController::class, 'dashboard'])->name('dashboard');

    // User monitoring
    Route::get('/users', [AdminController::class, 'users'])->name('users.index');
    Route::get('/users/{user}', [AdminController::class, 'showUser'])->name('users.show');

    // Book/Listing monitoring
    Route::get('/books', [AdminController::class, 'books'])->name('books.index');
    Route::get('/books/{book}', [AdminController::class, 'showBook'])->name('books.show');

    // Order monitoring
    Route::get('/orders', [AdminController::class, 'orders'])->name('orders.index');
    Route::get('/orders/{order}', [AdminController::class, 'showOrder'])->name('orders.show');

    // Payment monitoring
    Route::get('/payments', [AdminController::class, 'payments'])->name('payments.index');
    Route::get('/payments/{payment}', [AdminController::class, 'showPayment'])->name('payments.show');
});

// ---------------------------------------------------------------
// Profile
// ---------------------------------------------------------------
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// ---------------------------------------------------------------
// Public marketplace — siapapun dapat melihat listing buku
// ---------------------------------------------------------------
Route::get('/books', [BookController::class, 'index'])->name('books.index');
Route::get('/books/{book}', [BookController::class, 'show'])->name('books.show');

// ---------------------------------------------------------------
// Seller listing management — hanya user yang login
// ---------------------------------------------------------------
Route::middleware('auth')->group(function () {
    Route::get('/my-listings', [BookController::class, 'myListings'])->name('books.my-listings');
    Route::get('/my-listings/create', [BookController::class, 'create'])->name('books.create');
    Route::post('/my-listings', [BookController::class, 'store'])->name('books.store');
    Route::get('/my-listings/{book}/edit', [BookController::class, 'edit'])->name('books.edit');
    Route::put('/my-listings/{book}', [BookController::class, 'update'])->name('books.update');
    Route::delete('/my-listings/{book}', [BookController::class, 'destroy'])->name('books.destroy');
});

// ---------------------------------------------------------------
// Buyer — Order management
// ---------------------------------------------------------------
Route::middleware('auth')->group(function () {
    Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/create', [OrderController::class, 'create'])->name('orders.create');
    Route::post('/orders', [OrderController::class, 'store'])->name('orders.store');
    Route::get('/orders/{order}', [OrderController::class, 'show'])->name('orders.show');
    Route::post('/orders/{order}/cancel', [OrderController::class, 'cancel'])->name('orders.cancel');
});

// ---------------------------------------------------------------
// Payment management (ERP)
// ---------------------------------------------------------------
Route::middleware('auth')->group(function () {
    Route::get('/orders/{order}/payment/create', [PaymentController::class, 'create'])->name('payments.create');
    Route::post('/orders/{order}/payment', [PaymentController::class, 'store'])->name('payments.store');
    Route::get('/payments/{payment}', [PaymentController::class, 'show'])->name('payments.show');
    Route::post('/payments/{payment}/simulate-success', [PaymentController::class, 'simulateSuccess'])->name('payments.simulate-success');
    Route::post('/payments/{payment}/simulate-fail', [PaymentController::class, 'simulateFail'])->name('payments.simulate-fail');
});

// ---------------------------------------------------------------
// Seller — Incoming orders
// ---------------------------------------------------------------
Route::middleware('auth')->prefix('seller/orders')->name('seller.orders.')->group(function () {
    Route::get('/', [SellerOrderController::class, 'index'])->name('index');
    Route::get('/{order}', [SellerOrderController::class, 'show'])->name('show');
    Route::post('/{order}/process', [SellerOrderController::class, 'process'])->name('process');
});

require __DIR__.'/auth.php';
