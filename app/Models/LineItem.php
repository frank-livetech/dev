<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LineItem extends Model
{
    // meta type=>json
    
    protected $table = 'line_items';
    protected $fillable = [
        'subscription_id', 'woo_order_id','name','name','product_id', 
        'variation_id','order_id', 'quantity', 'tax_class', 
        'price','subtotal', 'subtotal_tax', 'fees', 'discount',
        'tax','total','total_tax','meta',
        'created_by','updated_by','is_deleted','deleted_at','deleted_by',
    ];
}