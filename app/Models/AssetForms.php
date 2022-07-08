<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AssetForms extends Model
{
    protected $table = 'asset_templates_form';
    protected $fillable = [
        'title', 'created_at', 'created_by', 'updated_at', 'updated_by', 'deleted_at', 'deleted_by', 'is_deleted'
    ];

    public function fields() {
        return $this->hasMany(AssetFields::class)->where('is_deleted',0);
    }

    public function asset() {
        return $this->belongsTo(Assets::class, 'id', 'asset_forms_id');
    }
}
