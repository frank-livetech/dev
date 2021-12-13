<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BrandSettings extends Model
{
    protected $table = 'brand_settings';
    protected $fillable = [
        'site_title',
        'site_logo_title',
        'site_logo',
        'site_favicon',
        'login_logo',
        'customer_logo',
        'company_logo',
        'user_logo',
        'site_footer', 
        'site_domain', 
        'site_version', 
        'created_by', 
        'updated_by',
        'text_dark',
        'text_light',
        'bg_dark',
        'bg_light',
        'created_by',
        'updated_by',
    ];
}
