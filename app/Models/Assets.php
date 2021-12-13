<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

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

    public function template()
    {
        return $this->hasOne(AssetForms::class, 'id', 'asset_forms_id');
    }
}
