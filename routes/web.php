<?php

use App\Http\Controllers\BookController;
use App\Http\Controllers\ProfileController;
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
Route::middleware(['auth', 'admin'])->group(function () {
    Route::get('/admin', function () {
        return view('admin.dashboard');
    })->name('admin.dashboard');
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
// Seller — hanya user yang login
// ---------------------------------------------------------------
Route::middleware('auth')->group(function () {
    Route::get('/my-listings', [BookController::class, 'myListings'])->name('books.my-listings');
    Route::get('/my-listings/create', [BookController::class, 'create'])->name('books.create');
    Route::post('/my-listings', [BookController::class, 'store'])->name('books.store');
    Route::get('/my-listings/{book}/edit', [BookController::class, 'edit'])->name('books.edit');
    Route::put('/my-listings/{book}', [BookController::class, 'update'])->name('books.update');
    Route::delete('/my-listings/{book}', [BookController::class, 'destroy'])->name('books.destroy');
});

require __DIR__.'/auth.php';
