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
    protected $appends = ['created_by_name', 'updated_by_name'];


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

    public function getCreatedByNameAttribute() {
        $id = $this->created_by;
        if($id){
            $user = User::where('id', $id)->first();
            if($user){
                return $user->name;
            }
        }
        
        return '---';
    }

    public function getUpdatedByNameAttribute() {
        $id = $this->cupdated_by;
        if($id){
            $user = User::where('id', $id)->first();
            if($user){
                return $user->name;
            }
        }
        
        return '---';

    }

}

