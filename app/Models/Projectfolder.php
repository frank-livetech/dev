<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Projectfolder extends Model
{
    protected $table = 'projects_folder';
    protected $fillable = [
        'name','created_by','updated_by','created_at','updated_at','is_deleted','deleted_by','deleted_at'
    ];
}
