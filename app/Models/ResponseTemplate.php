<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ResponseTemplate extends Model
{
    protected $table = 'res_templates';
    protected $fillable = [
        'title','cat_id','temp_html','view_access'
    ];
}
