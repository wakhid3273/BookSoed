<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Reviews table — dikelola tim CRM.
 * Buyer memberikan rating dan ulasan setelah order selesai.
 * Business Rule: Hanya order dengan status 'completed' yang dapat diulas.
 */
return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('reviews')) {
            Schema::create('reviews', function (Blueprint $table) {
                $table->id('review_id');
                $table->foreignId('order_id')->unique()->constrained('orders')->cascadeOnDelete();
                $table->foreignId('buyer_id')->constrained('users', 'id')->cascadeOnDelete();
                $table->foreignId('seller_id')->constrained('users', 'id')->cascadeOnDelete();
                $table->unsignedTinyInteger('rating');
                $table->text('comment')->nullable();
                $table->timestamp('created_at')->useCurrent();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('reviews');
    }
};
