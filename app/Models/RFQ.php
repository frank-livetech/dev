<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RFQ extends Model
{
    
    protected $table = 'billing_rfq';
    protected $fillable = [
        'subject', 'to_mails', 'rfq_details','purchase_order','contact_mail','created_by','updated_by'
    ];

}
