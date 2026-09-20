<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Book model — menggabungkan fungsionalitas ERP, SCM, dan CRM.
 */
class Book extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'seller_id',
        'category_id',
        'title',
        'author',
        'isbn',
        'condition',
        'price',
        'photo_path',
        'photo_url',
        'description',
        'status',
    ];

    /**
     * Alias getter untuk book_id agar kompatibel dengan SCM/CRM.
     */
    public function getBookIdAttribute()
    {
        return $this->attributes['id'] ?? null;
    }

    /**
     * Alias getter untuk seller_id agar kompatibel dengan SCM/CRM.
     */
    public function getSellerIdAttribute()
    {
        return $this->attributes['seller_id'] ?? $this->attributes['user_id'] ?? null;
    }

    /**
     * Alias setter untuk seller_id agar mengisi user_id bila diset.
     */
    public function setSellerIdAttribute($value): void
    {
        $this->attributes['seller_id'] = $value;
        $this->attributes['user_id'] = $value;
    }

    /**
     * Alias getter untuk photo_url agar kompatibel dengan views SCM.
     */
    public function getPhotoUrlAttribute()
    {
        return $this->attributes['photo_url'] ?? $this->attributes['photo_path'] ?? null;
    }

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
        return $this->belongsTo(Category::class, 'category_id');
    }

    /**
     * Get the order items where this book is listed.
     */
    public function orderItems(): HasMany
    {
        return $this->hasMany(OrderItem::class, 'book_id');
    }
}
