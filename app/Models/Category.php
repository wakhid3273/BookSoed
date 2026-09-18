<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Category model — dikelola tim ERP.
 * SCM hanya membuat tabel ini sebagai dependensi books.
 */
class Category extends Model
{
    protected $primaryKey = 'category_id';

    public $timestamps = false;

    protected $fillable = [
        'name',
    ];
}
