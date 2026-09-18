<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'buyer_id',
        'seller_id',
        'order_date',
        'status',
        'subtotal',
        'service_fee',
        'total_amount',
    ];

    protected $casts = [
        'order_date'   => 'datetime',
        'subtotal'     => 'decimal:2',
        'service_fee'  => 'decimal:2',
        'total_amount' => 'decimal:2',
    ];

    /**
     * Platform service fee (flat), dipisahkan dari delivery fee (SCM).
     */
    const SERVICE_FEE = 1000;

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
     * Get the order items for this order.
     */
    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    /**
     * Get the payment associated with this order.
     */
    public function payment(): HasOne
    {
        return $this->hasOne(Payment::class);
    }
}
