<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserTicket extends Model
{
    protected $table = 'user_ticket';

    protected $fillable = [
        'user_id','tickets_id'
    ];

}
