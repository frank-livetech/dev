<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tags extends Model
{
    
    protected $table = 'tags';
    protected $fillable = [
        'id','name','created_by','updated_by'
    ];

}
