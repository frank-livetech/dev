<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SlaPlan extends Model
{
    protected $table = 'sla_plan';
    protected $fillable = [
        'title','reply_deadline','due_deadline','sla_status','is_deleted'
    ];
}
