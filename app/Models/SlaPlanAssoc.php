<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SlaPlanAssoc extends Model
{
    protected $table = 'sla_plan_assoc';
    protected $fillable = [
        'id','sla_plan_id','ticket_id','created_at','created_by','updated_at'
    ];
}
