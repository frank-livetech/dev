<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Country extends Model {
    protected $table = 'countries';
    protected $fillable = [
        'id', 'name' , 'short_name', 'created_by', 'updated_by'
    ];
}
