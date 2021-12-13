<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\User;
class Products extends Model
{
    protected $table = 'products';
    protected $fillable = [
        'id','title','specs','extra_details','short_desc','long_desc','sell_as_sub',
        'how_often_quantity','how_often_terms','shipping','upc_gtin','isbn','part_no',
        'brand_no','sku','internal_id','product_id','vendor_price','oor_sale_price','oor_regular_price',
        'msrp','wholesale_price','in_stock','stock_quantity','is_stock_on_mul_loc','length',
        'width','height','weight','shipping_type','setup_fee_required', 'has_special_con',
        'special_condition_text','feature_image','feature_video','video_link','is_type','is_submit','is_deleted','created_at','created_by','updated_at'
    ];
    
   
}
