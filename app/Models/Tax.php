<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tax extends Model
{
    
    protected $table = 'taxes';
    protected $fillable = [
        'lineitem_id','total','subtotal',
        'created_at','updated_at','created_by','updated_by','is_deleted','deleted_at','deleted_by'
    ];
}