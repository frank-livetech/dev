<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\ResTemplateCat;

class ResponseTemplate extends Model
{
    protected $table = 'res_templates';
    protected $fillable = [
        'title','cat_id','temp_html','view_access' ,'created_by', 'updated_by',
    ];

    protected $appends = ['category_name'];


    public function getCategoryNameAttribute() {

        $cat = ResTemplateCat::where('id', $this->cat_id)->first();
        return $cat->name;

    }
}
