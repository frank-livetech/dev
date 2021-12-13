<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Subscriptions extends Model
{
    
    protected $table = 'subscriptions';
    protected $fillable = [
        'woo_id','parent_id','status','order_key','currency','version','prices_include_tax',
        'customer_id','discount_total','discount_tax','shipping_total','shipping_tax','cart_tax','total',
        'total_tax','payment_method','payment_method_title','transaction_id','customer_ip_address',
        'customer_user_agent','created_via','customer_note','date_completed','date_paid','cart_hash',
        'billing_period','billing_interval','start_date','trial_end_date','next_payment_date','end_date',
        'created_at','updated_at',
    ];
}
