<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\AssetForms;
use App\Models\AssetFields;
use App\Models\Customer;
use App\Models\Company;
use App\User;

class Assets extends Model
{
    protected $table = 'assets';
    protected $fillable = [
        'asset_forms_id',
        'customer_id',
        'company_id',
        'project_id',
        'ticket_id',
        'created_by',
        'updated_by',
        'created_at',
        'updated_at',
        'asset_title',
        'deleted_by',
        'deleted_at',
        'is_deleted'
    ];

    public function template() {
        return $this->hasOne(AssetForms::class, 'id', 'asset_forms_id');
    }

    public function asset_fields() {
        return $this->hasMany(AssetFields::class, 'asset_forms_id', 'asset_forms_id');
    }

    public function customer() {
        return $this->belongsTo(Customer::class);
    }

    public function company() {
        return $this->belongsTo(Company::class);
    }

    public function  createdByUser(){
        return $this->belongsTo(User::class,'id','created_by');
    }

    public function  updatedByUser(){
        return $this->belongsTo(User::class,'id','updated_by');
    }
}

