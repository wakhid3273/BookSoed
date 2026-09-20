<?php

use App\Http\Controllers\CrmController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| CRM Routes — BookSoed
|--------------------------------------------------------------------------
| Domain: Customer Relationship Management
| Dikelola tim CRM (Anggota 3).
| File ini didaftarkan oleh CrmServiceProvider.
*/

Route::middleware(['auth'])->prefix('crm')->name('crm.')->group(function () {

    // Dashboard Profil Civitas
    Route::get('/dashboard', [CrmController::class, 'dashboard'])->name('dashboard');

    // Wishlist
    Route::get('/wishlist', [CrmController::class, 'wishlist'])->name('wishlist');
    Route::post('/wishlist/{book}/toggle', [CrmController::class, 'toggleWishlist'])->name('wishlist.toggle');

    // Review & Rating
    Route::get('/orders/{order}/review', [CrmController::class, 'showReviewForm'])->name('review.create');
    Route::post('/orders/{order}/review', [CrmController::class, 'storeReview'])->name('review.store');
});
