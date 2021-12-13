<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Vendors extends Model
{
    protected $table = 'vendors';
    protected $fillable = [
        'first_name',
        'last_name',
        'company',
        'website',
        'email',
        'direct_line',
        'phone',
        'categories',
        'tags',
        'has_account',
        'address',
        'comp_id',
        'comp_name',
        'country',
        'state',
        'city',
        'zip',
        'twitter',
        'fb',
        'insta',
        'pinterest',
        'cmp_bill_add',
        'cmp_ship_add',
        'cmp_pr_add',
        'notes',
        'created_by',
        'updated_by'
    ];
}
