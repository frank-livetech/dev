<?php

namespace App\Models;

use App\User;
use App\Models\Customer;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use DB;

class Company extends Model
{

    use SoftDeletes;
    protected $table = 'companies';
    protected $appends = ['cmp_country_name' , 'cmp_state_name'];
    protected $fillable = [
        'woo_id',
        'name',
        'poc_first_name',
        'poc_last_name',
        'email',
        'address',
        'cmp_bill_add',
        'cmp_ship_add',
        'bill_add_zip',
        'phone',
        'is_default',
        'apt_address',
        'com_sla',
        'cmp_country',
        'cmp_state',
        'bill_apt_add',
        'bill_add_country',
        'bill_add_state',
        'bill_add_city',
        'is_bill_add',
        'cmp_city',
        'bill_st_add',
        'cmp_zip',
        'fb',
        'pinterest',
        'twitter',
        'insta',
        'website',
        'notes',
        'created_by',
        'updated_by',
        'is_deleted',
        'deleted_at',
        'com_logo',
        'domain'
    ];


    public function user(){
        return $this->hasOne(User::class,'id','created_by');
    }

    public function staff_members(){
        return $this->hasMany(Customer::class,'company_id','id');
    }
    
    public function staffs() {
        return $this->belongsToMany('App\User','company_user');
    }

    public function getCmpCountryNameAttribute($value)
    {
        $countries = DB::Table('countries')->get();
        $cn_id = $this->cmp_country;
        if($cn_id == NULL){
            return null;
        }
        foreach($countries as $country){
            if($country->id == $cn_id ){
                return $country->name;
            }
        }
    }
    public function getCmpStateNameAttribute($value)
    {
        $states = DB::Table('states')->get();
        $st_id = $this->cmp_state;
        if($st_id == NULL){
            return null;
        }
        foreach($states as $state){
            if($state->id == $st_id ){
                return $state->name;
            }
        }
    }
}
