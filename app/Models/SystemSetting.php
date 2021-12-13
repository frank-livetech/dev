<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use DB;

class SystemSetting extends Model
{
 
    protected $table = 'sys_settings';
    protected $fillable = [
        'sys_key',
        'sys_value',
        'accounts_from_email',
        'created_by',
    ];
   
}
