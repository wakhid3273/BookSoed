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
        if (!Schema::hasTable('orders')) {
            Schema::create('orders', function (Blueprint $table) {
                $table->id('order_id');
                $table->foreignId('buyer_id')->constrained('users', 'id')->cascadeOnDelete();
                $table->foreignId('seller_id')->constrained('users', 'id')->cascadeOnDelete();
                $table->string('order_status')->default('pending');
                $table->enum('fulfillment_method', ['cod', 'jeksoed'])->nullable();
                $table->decimal('service_fee', 12, 2)->default(0);
                $table->decimal('delivery_fee', 12, 2)->default(0);
                $table->decimal('total_amount', 12, 2)->default(0);
                $table->timestamp('order_date')->useCurrent();
                $table->timestamp('completed_at')->nullable();
                $table->timestamps();
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
