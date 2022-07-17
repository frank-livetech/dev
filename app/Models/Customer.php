<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use DB;

class Customer extends Model
{
    use SoftDeletes;

    protected $table = 'customers';
    protected $appends = ['prof_cn_name' , 'prof_st_name', 'company_name'];
    protected $fillable = [
        'woo_id','username','first_name','last_name','email',
        'cust_password','is_paying_customer', 'avatar_url', 'phone', 'alt_phone', 'phone_type',
        'address','apt_address', 'company_id', 'country', 'vertical',
        'business_residential','cust_type','created_at','updated_at',
        'has_account','deleted_at','is_deleted','cust_state','cust_city','cust_zip',
        'bill_st_add','bill_apt_add','bill_add_country','bill_add_city','bill_add_state',
        'bill_add_zip','is_bill_add','fb','twitter','insta','pinterest','linkedin', 'po'
    ];

    public function user() {
        return $this->belongsTo(Project::class);
    }

    /**
     * get company name
     */
    public function company(){
        return $this->hasOne(Company::class,'id','company_id');
    }
    /**
     * Get the vertical.
     *
     * @param  string  $value
     * @return string
     */
    public function getVerticalAttribute($value)
    {
        return $value ? $value : 'Test';
    }
    /**
     * Get the business_residential.
     *
     * @param  string  $value
     * @return string
     */
    public function getBusinessResidentialAttribute($value)
    {
        return $value == 1 ? 'Residential' : 'Business';
    }


    public function getprofCnNameAttribute($value)
    {
        $countries = DB::Table('countries')->get();
        $cn_id = $this->country;
        if($cn_id == NULL){
            return null;
        }
        foreach($countries as $country){
            if($country->id == $cn_id ){
                return $country->name;
            }
        }
    }
    public function getprofStNameAttribute($value)
    {
        $states = DB::Table('states')->get();
        $st_id = $this->cust_state;
        if($st_id == NULL){
            return null;
        }
        foreach($states as $state){
            if($state->id == $st_id ){
                return $state->name;
            }
        }
    }

    public function getcompanyNameAttribute($value)
    {
        $cp_id = $this->company_id;
        if($cp_id == NULL) return null;

        $comp = DB::Table('companies')->where('id', $cp_id)->first();

        if(empty($comp)) return null;
        else return $comp->name;
    }

    public function note(){
        return $this->hasMany(TicketNote::class);
    }

}
