<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * Order model — menggabungkan ERP dan SCM.
 */
class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'buyer_id',
        'seller_id',
        'order_date',
        'status',
        'order_status',
        'subtotal',
        'service_fee',
        'delivery_fee',
        'total_amount',
        'fulfillment_method',
        'completed_at',
    ];

    protected $casts = [
        'order_date'   => 'datetime',
        'completed_at' => 'datetime',
        'subtotal'     => 'decimal:2',
        'service_fee'  => 'decimal:2',
        'delivery_fee' => 'decimal:2',
        'total_amount' => 'decimal:2',
    ];

    /**
     * Platform service fee (flat), dipisahkan dari delivery fee (SCM).
     */
    const SERVICE_FEE = 1000;

    const STATUS_PENDING = 'PENDING';
    const STATUS_PROCESSING = 'PROCESSING';
    const STATUS_COMPLETED = 'COMPLETED';
    const STATUS_CANCELLED = 'CANCELLED';

    /**
     * Alias getter untuk order_id agar kompatibel dengan SCM/CRM.
     */
    public function getOrderIdAttribute()
    {
        return $this->attributes['id'] ?? null;
    }

    /**
     * Alias getter untuk order_status.
     */
    public function getOrderStatusAttribute()
    {
        return $this->attributes['order_status'] ?? $this->attributes['status'] ?? self::STATUS_PENDING;
    }

    /**
     * Alias setter untuk order_status agar mengupdate status ERP juga.
     */
    public function setOrderStatusAttribute($value): void
    {
        $this->attributes['order_status'] = $value;
        $this->attributes['status'] = strtoupper($value);
    }

    /**
     * Status yang valid.
     */
    public static function statuses(): array
    {
        return [
            'PENDING'    => 'Menunggu',
            'PROCESSING' => 'Diproses',
            'COMPLETED'  => 'Selesai',
            'CANCELLED'  => 'Dibatalkan',
        ];
    }

    /**
     * Get the buyer for this order.
     */
    public function buyer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'buyer_id');
    }

    /**
     * Get the seller for this order.
     */
    public function seller(): BelongsTo
    {
        return $this->belongsTo(User::class, 'seller_id');
    }

    /**
     * Get the order items for this order (ERP method name).
     */
    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class, 'order_id');
    }

    /**
     * Get the order items for this order (SCM method name).
     */
    public function orderItems(): HasMany
    {
        return $this->hasMany(OrderItem::class, 'order_id');
    }

    /**
     * Get the payment associated with this order (ERP).
     */
    public function payment(): HasOne
    {
        return $this->hasOne(Payment::class, 'order_id');
    }

    /**
     * Get the delivery associated with this order (SCM).
     */
    public function delivery(): HasOne
    {
        return $this->hasOne(Delivery::class, 'order_id');
    }
}
