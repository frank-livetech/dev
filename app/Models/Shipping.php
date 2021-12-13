<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Shipping extends Model
{
    
    protected $table = 'billing_and_shipping';
    protected $fillable = [
        'customer_id','subscription_id','order_id','type','address1','address2','city','state',
        'postcode','country','created_at',
        'updated_at','created_by','updated_by','is_deleted','deleted_at','deleted_by'
    ];
}
