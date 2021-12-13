<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Products;
use Carbon\Carbon;

class ProductController extends Controller
{
    //



    public function wp_product_create() {

        $payload = @file_get_contents('php://input');
        $payload = json_decode( $payload, true); 
        if($payload != null && $payload != '') {
            DB::table("products")->insert([
                "woo_id" => $payload['id'],
                "name" => $payload['name'],
                "type" => $payload['type'],
                "status" => $payload['status'],
                "description" => $payload['description'],
                "sku" => $payload['sku'],
                "regular_price" => $payload['regular_price'],
                "sale_price" => $payload['sale_price'],
                "purchase_note" => $payload['purchase_note'],
                "created_at" => Carbon::now(),
                "updated_at" => Carbon::now(),
            ]);
        }
        
    }

    public function wp_product_update() {

        $payload = @file_get_contents('php://input');
        $payload = json_decode( $payload, true); 
        if($payload != null && $payload != '') {
            DB::table("products")->where('woo_id',$payload['id'])->update([
                "woo_id" => $payload['id'],
                "name" => $payload['name'],
                "type" => $payload['type'],
                "status" => $payload['status'],
                "description" => $payload['description'],
                "sku" => $payload['sku'],
                "regular_price" => $payload['regular_price'],
                "sale_price" => $payload['sale_price'],
                "purchase_note" => $payload['purchase_note'],
                "created_at" => Carbon::now(),
                "updated_at" => Carbon::now(),
            ]);
        }

    }

    public function wp_product_delete() {

        $payload = @file_get_contents('php://input');
        $payload = json_decode( $payload, true); 
        if($payload != null && $payload != '') {
            DB::table("products")->where('woo_id',$payload['id'])->update([
                "is_deleted" => 1,
            ]);
        }

    }
}
