<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class States extends Model {
    protected $table = 'states';
    protected $fillable = [
        'id', 'name', 'country_id', 'created_by', 'updated_by'
    ];
}
