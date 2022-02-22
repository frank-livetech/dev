<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TicketView extends Model
{
    protected $table = 'ticket_view';
    protected $fillable = [
        'user_id','per_page','created_by','updated_by'
    ];
}
