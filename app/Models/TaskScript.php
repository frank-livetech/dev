<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TaskScript extends Model
{
    
    protected $table = 'task_scripts';
    protected $fillable = [
        'category','filename','size','created_at','updated_at','created_by','is_deleted','deleted_at','deleted_by'
    ];
}