<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Orders extends Model
{
    
    protected $table = 'orders';
    protected $fillable = [
        'woo_id','parent_id','number','order_key','created_via','version','status','status_text','currency',
        'created_at','updated_at','discount_total','discount_tax','shipping_total','custom_id','fees','discount','tax',
        'shipping_tax','cart_tax','total','total_tax','prices_include_tax','customer_id','customer_woo_id',
        'customer_ip_address','customer_user_agent','customer_note','payment_method','payment_method_title',
        'transaction_id','date_paid','date_completed','cart_hash','ord_notes',
    ];
}
