<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * OrderItem model — dikelola tim ERP.
 * SCM menambahkan relasi belongsTo(Order) dan belongsTo(Book).
 */
class OrderItem extends Model
{
    protected $table = 'orderitems';

    protected $primaryKey = 'order_item_id';

    public $timestamps = false;

    protected $fillable = [
        'order_id',
        'book_id',
        'price_at_order',
        'subtotal',
    ];

    /** @return BelongsTo<Order, $this> */
    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class, 'order_id', 'order_id');
    }

    /** @return BelongsTo<Book, $this> */
    public function book(): BelongsTo
    {
        return $this->belongsTo(Book::class, 'book_id', 'book_id');
    }
}
