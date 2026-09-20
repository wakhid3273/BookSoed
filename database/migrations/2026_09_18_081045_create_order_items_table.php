<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (!Schema::hasTable('order_items') && !Schema::hasTable('orderitems')) {
            Schema::create('order_items', function (Blueprint $table) {
                $table->id('order_item_id');
                $table->foreignId('order_id')->constrained('orders', 'id')->cascadeOnDelete();
                $table->foreignId('book_id')->constrained('books', 'id')->cascadeOnDelete();
                $table->decimal('price_at_order', 12, 2);
                $table->decimal('subtotal', 12, 2);
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Handled by ERP drop
    }
};
