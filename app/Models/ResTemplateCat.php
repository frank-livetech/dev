<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ResTemplateCat extends Model
{
    protected $table = 'res_temp_cat';
    protected $fillable = [
        'name','is_deleted'
    ];
}
