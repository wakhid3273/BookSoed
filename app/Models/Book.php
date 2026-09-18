<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Book model — dikelola tim ERP.
 * SCM menambahkan relasi hasMany(OrderItem) agar bisa
 * mengupdate status buku ke 'sold' via DeliveryService.
 */
class Book extends Model
{
    protected $primaryKey = 'book_id';

    public $timestamps = false;

    protected $fillable = [
        'seller_id',
        'category_id',
        'title',
        'author',
        'isbn',
        'condition',
        'price',
        'description',
        'photo_url',
        'status',
    ];

    /** @return BelongsTo<User, $this> */
    public function seller(): BelongsTo
    {
        return $this->belongsTo(User::class, 'seller_id');
    }

    /** @return BelongsTo<Category, $this> */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    /** @return HasMany<OrderItem, $this> */
    public function orderItems(): HasMany
    {
        return $this->hasMany(OrderItem::class, 'book_id', 'book_id');
    }
}
