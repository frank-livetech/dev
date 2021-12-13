<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CustomerCC extends Model
{
    protected $table = 'cust_cc';
    protected $fillable = [
        'cardlastDigits','customer_id','payment_token', 'customer_vault_id', 'billing_id', 'fname', 'lname', 'address1', 'city', 'state', 'zip', 'card_type','exp','created_at', 'updated_at', 'created_by', 'updated_by'
        ,'deleted_at', 'deleted_by'
    ];
}
