<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Wishlist model — dikelola tim CRM.
 * Menyimpan daftar buku favorit/incaran seorang user (sebagai Buyer).
 */
class Wishlist extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'user_id',
        'book_id',
    ];

    /** @return BelongsTo<User, $this> */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /** @return BelongsTo<Book, $this> */
    public function book(): BelongsTo
    {
        return $this->belongsTo(Book::class, 'book_id', 'book_id');
    }
}
