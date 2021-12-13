<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TicketStatus extends Model
{
    protected $table = 'ticket_statuses';
    protected $fillable = [
        'name', 'department_id','color','created_by','updated_by'
    ];
}
