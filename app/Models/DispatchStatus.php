<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DispatchStatus extends Model
{
    protected $table = 'dispatch_status';
    protected $fillable = [
        'name'
    ];
}
