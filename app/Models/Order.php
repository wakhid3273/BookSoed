<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * Order model — dikelola tim ERP.
 * SCM menambahkan relasi hasOne(Delivery) dan hasMany(OrderItem).
 * Jangan hapus/ubah method yang ditambahkan tim ERP.
 */
class Order extends Model
{
    protected $primaryKey = 'order_id';

    protected $fillable = [
        'buyer_id',
        'seller_id',
        'order_status',
        'fulfillment_method',
        'service_fee',
        'delivery_fee',
        'total_amount',
        'order_date',
        'completed_at',
    ];

    /** @return array<string, string> */
    protected function casts(): array
    {
        return [
            'order_date' => 'datetime',
            'completed_at' => 'datetime',
            'service_fee' => 'decimal:2',
            'delivery_fee' => 'decimal:2',
            'total_amount' => 'decimal:2',
        ];
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

    // ── SCM Relations ──────────────────────────────────────────────────────────

    /** @return HasOne<Delivery, $this> */
    public function delivery(): HasOne
    {
        return $this->hasOne(Delivery::class, 'order_id', 'order_id');
    }

    /** @return HasMany<OrderItem, $this> */
    public function orderItems(): HasMany
    {
        return $this->hasMany(OrderItem::class, 'order_id', 'order_id');
    }
}
