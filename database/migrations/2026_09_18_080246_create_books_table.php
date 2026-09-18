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
        Schema::create('books', function (Blueprint $table) {
            $table->id('book_id');
            $table->foreignId('seller_id')->constrained('users', 'id')->cascadeOnDelete();
            $table->foreignId('category_id')->constrained('categories', 'category_id')->cascadeOnDelete();
            $table->string('title');
            $table->string('author');
            $table->string('isbn')->nullable();
            $table->enum('condition', ['new', 'like_new', 'good', 'fair', 'poor']);
            $table->decimal('price', 12, 2);
            $table->text('description')->nullable();
            $table->string('photo_url')->nullable();
            $table->enum('status', ['available', 'reserved', 'sold'])->default('available');
            $table->timestamp('created_at')->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('books');
    }
};
