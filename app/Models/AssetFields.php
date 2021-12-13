<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AssetFields extends Model
{
    protected $table = 'asset_templates_fields';
    protected $fillable = [
        'asset_forms_id',
        'label',
        'type',
        'placeholder',
        'description',
        'required',
        'is_multi',
        'options',
        'col_width',
        'created_at',
        'created_by',
        'updated_at',
        'updated_by',
        'deleted_at',
        'deleted_by',
        'is_deleted'
    ];

    public function form()
    {
        return $this->belongsTo(AssetForms::class);
    }
}
