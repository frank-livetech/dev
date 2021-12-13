<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CompanyActivityLog extends Model
{
    //

    protected $fillable = [
        'action_perform','company_id','created_by','updated_by'
    ];

}
