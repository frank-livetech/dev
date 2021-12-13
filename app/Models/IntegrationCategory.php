<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class IntegrationCategory extends Model
{
     public $timestamps = false;

     protected $fillable = ['title'];

     public function integrations(){
         return $this->hasMany(Integrations::class,'cat_id','id');
     }
}
