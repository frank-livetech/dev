<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Departments extends Model
{
    protected $table = 'departments';
    protected $fillable = [
        'name','is_enabled','created_by','updated_by'
    ];
}
