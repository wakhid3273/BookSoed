<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Review model — dikelola tim CRM.
 * Buyer hanya dapat memberikan review setelah order_status = 'completed'.
 * Constraint: order_id adalah unique (satu order hanya bisa diulas sekali).
 */
class Review extends Model
{
    protected $primaryKey = 'review_id';

    public $timestamps = false;

    protected $fillable = [
        'order_id',
        'buyer_id',
        'seller_id',
        'rating',
        'comment',
    ];

    /** @return array<string, string> */
    protected function casts(): array
    {
        return [
            'rating' => 'integer',
            'created_at' => 'datetime',
        ];
    }

    /** @return BelongsTo<Order, $this> */
    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class, 'order_id', 'order_id');
    }

    /** @return BelongsTo<User, $this> */
    public function buyer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'buyer_id');
    }

    /** @return BelongsTo<User, $this> */
    public function seller(): BelongsTo
    {
        return $this->belongsTo(User::class, 'seller_id');
    }
}
