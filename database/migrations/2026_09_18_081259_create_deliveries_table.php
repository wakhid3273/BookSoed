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
        Schema::create('deliveries', function (Blueprint $table) {
            $table->id('delivery_id');
            $table->foreignId('order_id')
                ->unique()
                ->constrained('orders', 'order_id')
                ->cascadeOnDelete();
            $table->enum('delivery_method', ['cod', 'jeksoed']);
            $table->enum('delivery_status', [
                'pending',
                'processing',
                'assigned',
                'picked_up',
                'on_delivery',
                'delivered',
                'completed',
            ])->default('pending');
            $table->string('pickup_location');
            $table->string('destination');
            $table->string('jeksoed_order_id')->nullable();
            $table->string('driver_name')->nullable();
            $table->string('driver_phone')->nullable();
            $table->timestamp('created_at')->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('deliveries');
    }
};
