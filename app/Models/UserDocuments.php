<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserDocuments extends Model
{
    protected $table = 'user_docs';
    protected $fillable = [
        'user_id','name', 'category_name','details','image','created_by','updated_by'
    ];
}
