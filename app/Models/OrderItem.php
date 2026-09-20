<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * OrderItem model — menggabungkan ERP dan SCM.
 */
class OrderItem extends Model
{
    use HasFactory;

    protected $table = 'order_items';

    protected $fillable = [
        'order_id',
        'book_id',
        'price',
        'quantity',
        'price_at_order',
        'subtotal',
    ];

    protected $casts = [
        'price'          => 'decimal:2',
        'price_at_order' => 'decimal:2',
        'subtotal'       => 'decimal:2',
    ];

    /**
     * Alias getter untuk order_item_id.
     */
    public function getOrderItemIdAttribute()
    {
        return $this->attributes['id'] ?? null;
    }

    /**
     * Alias getter untuk price_at_order.
     */
    public function getPriceAtOrderAttribute()
    {
        return $this->attributes['price_at_order'] ?? $this->attributes['price'] ?? 0;
    }

    /**
     * Alias getter untuk subtotal.
     */
    public function getSubtotalAttribute()
    {
        if (isset($this->attributes['subtotal'])) {
            return $this->attributes['subtotal'];
        }
        return ($this->attributes['price'] ?? 0) * ($this->attributes['quantity'] ?? 1);
    }

    /**
     * Get the order that owns this item.
     */
    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class, 'order_id');
    }

    /**
     * Get the book associated with this order item.
     */
    public function book(): BelongsTo
    {
        return $this->belongsTo(Book::class, 'book_id');
    }
}
