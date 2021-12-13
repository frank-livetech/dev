<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Integrations extends Model
{
    protected $table = 'integrations';
    protected $fillable = [
        'cat_id', 'name', 'slug', 'image', 'details', 'is_verified', 'status', 'page_count', 'created_by', 'updated_by','created_at','updated_at'
    ];
}
