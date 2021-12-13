<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ContactInfo extends Model
{
    protected $table = 'contacts_info';
    protected $fillable = [
        'first_name', 'last_name','company','email_1','email_2','office_num','cell_num','street_addr_1',
        'street_addr_2','city_name','state','zip_code','country_name','notes','last_called',
        'email_list_tags','active_customer','tag_id','created_by','created_at','updated_at','updated_by',
        'deleted_by','is_deleted'
    ];
}
