<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class Dispatch extends Model
{
    protected $table = 'dispatch';
    protected $fillable = [
        'staff_id', 'customer_id', 'ticket_id', 'pref_date', 'status', 'phone', 'address','street_address', 'city', 'country', 'state', 'zip','apartment', 'phone_extra', 'text', 'whatsapp', 'add_to_address', 'created_at', 'updated_at', 'created_by', 'updated_by'
    ];
}
