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
        if (!Schema::hasTable('books')) {
            Schema::create('books', function (Blueprint $table) {
                $table->id('book_id');
                $table->foreignId('seller_id')->constrained('users', 'id')->cascadeOnDelete();
                $table->foreignId('category_id')->constrained('categories', 'id')->cascadeOnDelete();
                $table->string('title');
                $table->string('author');
                $table->string('isbn')->nullable();
                $table->string('condition')->default('GOOD');
                $table->decimal('price', 12, 2);
                $table->text('description')->nullable();
                $table->string('photo_url')->nullable();
                $table->string('status')->default('AVAILABLE');
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
