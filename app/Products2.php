<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Products extends Model
{
    protected $table = 'products';
    protected $fillable = [
        'woo_id',
        'name',
        'type',
        'status',
        'description',
        'sku',
        'regular_price',
        'sale_price',
        'purchase_note',
        'is_deleted',
        'deleted_by',
        'updated_by',
        'created_by',
        'deleted_at',
    ];
}
