<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TicketPriority extends Model
{
    protected $table = 'ticket_priorities';
    protected $fillable = [
        'name', 'department_id','priority_color','created_by','updated_by'
    ];
}
