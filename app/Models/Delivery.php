<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Delivery model — dikelola oleh tim SCM.
 */
class Delivery extends Model
{
    protected $primaryKey = 'delivery_id';

    const UPDATED_AT = null;

    protected $fillable = [
        'order_id',
        'delivery_method',
        'delivery_status',
        'pickup_location',
        'destination',
        'jeksoed_order_id',
        'driver_name',
        'driver_phone',
    ];

    /** @return BelongsTo<Order, $this> */
    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class, 'order_id', 'order_id');
    }
}
