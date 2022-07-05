<?php

namespace App\Models;

use App\User;
use Illuminate\Database\Eloquent\Model;

class Activitylog extends Model
{
    protected $table = 'activity_log';
    protected $fillable = [
        'module', 'table_ref', 'action_perform','ref_id','created_by'
    ];

    public function createdBy()
    {
        return $this->belongsTo(User::class,'created_by','id');
    }
}
