<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Book extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'category_id',
        'title',
        'author',
        'isbn',
        'condition',
        'price',
        'photo_path',
        'status',
    ];

    /**
     * Daftar kondisi buku yang valid.
     */
    public static function conditions(): array
    {
        return [
            'NEW'      => 'Baru',
            'LIKE_NEW' => 'Seperti Baru',
            'GOOD'     => 'Baik',
            'FAIR'     => 'Cukup',
            'POOR'     => 'Kurang',
        ];
    }

    /**
     * Daftar status listing yang valid.
     */
    public static function statuses(): array
    {
        return [
            'AVAILABLE' => 'Tersedia',
            'RESERVED'  => 'Dipesan',
            'SOLD'      => 'Terjual',
        ];
    }

    /**
     * Get the seller (user) who owns this book listing.
     */
    public function seller(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Get the category of this book.
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Get the order items where this book is listed.
     * Used by BookPolicy to prevent deletion of books with transaction history.
     */
    public function orderItems(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }
}
