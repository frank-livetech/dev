<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SaveNewTag extends Model
{
    protected $table = 'contacts_tags';
    protected $fillable = [
        'name','created_by','updated_by',
    ];
}
