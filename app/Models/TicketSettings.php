<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TicketSettings extends Model
{
    protected $table = 'ticket_settings';
    protected $fillable = [
        'tkt_key','tkt_value','created_by','updated_by','deleted_by','deleted_at'
    ];
}
