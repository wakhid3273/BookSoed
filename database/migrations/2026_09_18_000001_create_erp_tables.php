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
        if (!Schema::hasTable('categories')) {
            Schema::create('categories', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('books')) {
            Schema::create('books', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
                $table->foreignId('category_id')->constrained('categories')->onDelete('restrict');
                $table->string('title');
                $table->string('author');
                $table->string('isbn')->nullable();
                $table->string('condition'); // e.g. Sangat Baik, Baik, Cukup
                $table->decimal('price', 12, 2);
                $table->string('photo_path')->nullable();
                $table->text('description')->nullable();
                $table->string('status')->default('AVAILABLE'); // AVAILABLE, RESERVED, SOLD
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('orders')) {
            Schema::create('orders', function (Blueprint $table) {
                $table->id();
                $table->foreignId('buyer_id')->constrained('users')->onDelete('restrict');
                $table->foreignId('seller_id')->constrained('users')->onDelete('restrict');
                $table->timestamp('order_date');
                $table->string('status')->default('PENDING'); // PENDING, PAID, PROCESSING, COMPLETED, CANCELLED
                $table->string('order_status')->default('pending');
                $table->enum('fulfillment_method', ['cod', 'jeksoed'])->nullable();
                $table->decimal('subtotal', 12, 2)->default(0);
                $table->decimal('service_fee', 12, 2)->default(0);
                $table->decimal('delivery_fee', 12, 2)->default(0);
                $table->decimal('total_amount', 12, 2)->default(0);
                $table->timestamp('completed_at')->nullable();
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('order_items')) {
            Schema::create('order_items', function (Blueprint $table) {
                $table->id();
                $table->foreignId('order_id')->constrained('orders')->onDelete('cascade');
                $table->foreignId('book_id')->constrained('books')->onDelete('restrict');
                $table->decimal('price', 12, 2);
                $table->integer('quantity')->default(1);
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('payments')) {
            Schema::create('payments', function (Blueprint $table) {
                $table->id();
                $table->foreignId('order_id')->unique()->constrained('orders')->onDelete('cascade');
                $table->string('payment_method'); // E-WALLET, COD
                $table->decimal('amount', 12, 2);
                $table->string('payment_status')->default('PENDING'); // PENDING, SUCCESS, FAILED, EXPIRED
                $table->string('transaction_reference')->nullable();
                $table->timestamp('paid_at')->nullable();
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
        Schema::dropIfExists('order_items');
        Schema::dropIfExists('orders');
        Schema::dropIfExists('books');
        Schema::dropIfExists('categories');
    }
};
