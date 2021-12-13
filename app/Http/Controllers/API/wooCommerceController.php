<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Validator, Redirect, Response;
use App\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Session;
use App\Models\BrandSettings;
use Carbon\Carbon;

class wooCommerceController extends Controller
{

    public function wp_customer_create()
    {
        $wordpress = DB::Table("integrations")->where("slug", "wordpress")->where('status', 1)->first();
        if(!empty($wordpress)) {
            if ($wordpress->is_verified == 1) {
                if ($wordpress->details != NULL & $wordpress->details != '') {

                    $detail_values = explode(",", $wordpress->details);
                    $api = substr($detail_values[1], 1, -1);
                    $explode_key = explode(":", $api);
                    $url = $explode_key[1] . ':' . $explode_key[2];
                    $api_url = trim(str_replace('\/', '/', $url), '"');

                    $ck_key = substr($detail_values[2], 1, -1);
                    $explode_key = explode(":", $ck_key);
                    $secret_key = trim($explode_key[1], '"');

                    $con_key = substr($detail_values[3], 1, -1);
                    $explode_key = explode(":", $con_key);
                    $consumer_key = trim($explode_key[1], '"');

                    if ($api_url != null && $api_url != " " && $secret_key != null && $secret_key != " " && $consumer_key != null && $consumer_key != " ") {
                        $customer = @file_get_contents('php://input');
                        $customer = json_decode($customer, true);

                        if ($customer != null && $customer != '') {
                            \Log::info($customer);
                            \Log::info(json_encode($customer));
                            DB::table("customers")->insert([
                                "woo_id" => $customer['id'],
                                "first_name" => $customer['first_name'],
                                "last_name" => $customer['last_name'],
                                "email" => $customer['email'],
                            ]);
                        }
                    }
                } else {
                    return response()->json([
                        "message" => 'Wordpress plugin not verified',
                        "status" => 500,
                        "success" => false,
                    ]);
                }
            } else {
                return response()->json([
                    "message" => 'Wordpress plugin not verified',
                    "status" => 500,
                    "success" => false,
                ]);
            }
        } else{
            return response()->json([
                "message" => 'Wordpress plugin not enabled',
                "status" => 500,
                "success" => false,
            ]);
        }
    }

    public function wp_customer_update()
    {
        $wordpress = DB::Table("integrations")->where("slug", "wordpress")->where('status', 1)->first();
        if(!empty($wordpress)) {
            if ($wordpress->is_verified == 1) {
                if ($wordpress->details != NULL & $wordpress->details != '') {

                    $detail_values = explode(",", $wordpress->details);
                    $api = substr($detail_values[1], 1, -1);
                    $explode_key = explode(":", $api);
                    $url = $explode_key[1] . ':' . $explode_key[2];
                    $api_url = trim(str_replace('\/', '/', $url), '"');

                    $ck_key = substr($detail_values[2], 1, -1);
                    $explode_key = explode(":", $ck_key);
                    $secret_key = trim($explode_key[1], '"');

                    $con_key = substr($detail_values[3], 1, -1);
                    $explode_key = explode(":", $con_key);
                    $consumer_key = trim($explode_key[1], '"');

                    if ($api_url != null && $api_url != " " && $secret_key != null && $secret_key != " " && $consumer_key != null && $consumer_key != " ") {

                        $customer = @file_get_contents('php://input');
                        $customer = json_decode($customer, true);

                        if ($customer != null && $customer != '') {
                            \Log::info($customer);
                            DB::table("customers")->where("woo_id", $customer['id'])->update([
                                "woo_id" => $customer['id'],
                                "first_name" => $customer['first_name'],
                                "last_name" => $customer['last_name'],
                                "email" => $customer['email'],
                                "username" => $customer['username'],
                                "bill_st_add" => $customer['billing']['address_1'],
                                "bill_apt_add" => $customer['billing']['address_2'],
                                "bill_add_zip" => $customer['billing']['postcode'],
                                "phone" => $customer['billing']['phone'],
                                "bill_add_city" => $customer['billing']['city'],
                            ]);
                        }
                    }
                } else {
                    return response()->json([
                        "message" => 'Wordpress plugin not verified',
                        "status" => 500,
                        "success" => false,
                    ]);
                }
            } else {
                return response()->json([
                    "message" => 'Wordpress plugin not verified',
                    "status" => 500,
                    "success" => false,
                ]);
            }
        } else {
            return response()->json([
                "message" => 'Wordpress plugin not enabled',
                "status" => 500,
                "success" => false,
            ]);
        }
    }

    public function wp_customer_delete()
    {
        $wordpress = DB::Table("integrations")->where("slug", "wordpress")->where('status', 1)->first();
        if(!empty($wordpress)) {
            if ($wordpress->is_verified == 1) {
                if ($wordpress->details != NULL & $wordpress->details != '') {

                    $detail_values = explode(",", $wordpress->details);
                    $api = substr($detail_values[1], 1, -1);
                    $explode_key = explode(":", $api);
                    $url = $explode_key[1] . ':' . $explode_key[2];
                    $api_url = trim(str_replace('\/', '/', $url), '"');

                    $ck_key = substr($detail_values[2], 1, -1);
                    $explode_key = explode(":", $ck_key);
                    $secret_key = trim($explode_key[1], '"');

                    $con_key = substr($detail_values[3], 1, -1);
                    $explode_key = explode(":", $con_key);
                    $consumer_key = trim($explode_key[1], '"');

                    if ($api_url != null && $api_url != " " && $secret_key != null && $secret_key != " " && $consumer_key != null && $consumer_key != " ") {

                        $customer = @file_get_contents('php://input');
                        $customer = json_decode($customer, true);

                        if ($customer != null && $customer != '') {
                            DB::table("customers")->where('woo_id', $customer['id'])->update([
                                "is_deleted" => 1,
                            ]);
                        }
                    }
                } else {
                    return response()->json([
                        "message" => 'Wordpress plugin not verified',
                        "status" => 500,
                        "success" => false,
                    ]);
                }
            } else {
                return response()->json([
                    "message" => 'Wordpress plugin not verified',
                    "status" => 500,
                    "success" => false,
                ]);
            }
        } else {
            return response()->json([
                "message" => 'Wordpress plugin not enabled',
                "status" => 500,
                "success" => false,
            ]);
        }
    }


    // wp orders

    public function wp_order_create()
    {
        $wordpress = DB::Table("integrations")->where("slug", "wordpress")->where('status', 1)->first();
        if(!empty($wordpress)) {
            if ($wordpress->is_verified == 1) {
                if ($wordpress->details != NULL & $wordpress->details != '') {

                    $detail_values = explode(",", $wordpress->details);
                    $api = substr($detail_values[1], 1, -1);
                    $explode_key = explode(":", $api);
                    $url = $explode_key[1] . ':' . $explode_key[2];
                    $api_url = trim(str_replace('\/', '/', $url), '"');

                    $ck_key = substr($detail_values[2], 1, -1);
                    $explode_key = explode(":", $ck_key);
                    $secret_key = trim($explode_key[1], '"');

                    $con_key = substr($detail_values[3], 1, -1);
                    $explode_key = explode(":", $con_key);
                    $consumer_key = trim($explode_key[1], '"');

                    if ($api_url != null && $api_url != " " && $secret_key != null && $secret_key != " " && $consumer_key != null && $consumer_key != " ") {

                        $orders = @file_get_contents('php://input');
                        $orders = json_decode($orders, true);

                        if ($orders != null && $orders != '') {
                            \Log::info($orders);

                            $shipping_cost = 0;
                            $fee_cost = 0;

                            $shipping = $orders['shipping_lines'];
                            if ($shipping != '' &&  $shipping != null) {
                                for ($s = 0; $s < sizeOf($shipping); $s++) {
                                    $shipping_cost = $shipping_cost + $shipping[$s]['total'];
                                }
                            }

                            $fees = $orders['fee_lines'];
                            if ($fees != '' &&  $fees != null) {
                                for ($f = 0; $f < sizeOf($fees); $f++) {
                                    $fee_cost = $fee_cost + $fees[$f]['total'];
                                }
                            }

                            DB::table("orders")->insert([
                                "woo_id" => $orders['id'],
                                "custom_id" => $orders['id'],
                                "currency" => $orders['currency'],
                                "version" => $orders['version'],
                                "fees" => $fee_cost,
                            ]);


                            $line_items = $orders['line_items'];
                            for ($i = 0; $i < sizeOf($line_items); $i++) {
                                DB::table("line_items")->insert([
                                    "id" => $line_items[$i]['id'],
                                    "order_id" => $orders['id'],
                                    "name" => $line_items[$i]['name'],
                                    "quantity" => $line_items[$i]['quantity'],
                                    "total" => $line_items[$i]['subtotal'],
                                    "price" => $line_items[$i]['price'],
                                    "shipping" =>  $shipping_cost,
                                ]);
                            }
                        }
                    }
                } else {
                    return response()->json([
                        "message" => 'Wordpress plugin not verified',
                        "status" => 500,
                        "success" => false,
                    ]);
                }
            } else {
                return response()->json([
                    "message" => 'Wordpress plugin not verified',
                    "status" => 500,
                    "success" => false,
                ]);
            }
        } else {
            return response()->json([
                "message" => 'Wordpress plugin not enabled',
                "status" => 500,
                "success" => false,
            ]);
        }
    }

    public function wp_order_update()
    {
        $wordpress = DB::Table("integrations")->where("slug", "wordpress")->where('status', 1)->first();
        if(!empty($wordpress)) {
            if ($wordpress->is_verified == 1) {
                if ($wordpress->details != NULL & $wordpress->details != '') {

                    $detail_values = explode(",", $wordpress->details);
                    $api = substr($detail_values[1], 1, -1);
                    $explode_key = explode(":", $api);
                    $url = $explode_key[1] . ':' . $explode_key[2];
                    $api_url = trim(str_replace('\/', '/', $url), '"');

                    $ck_key = substr($detail_values[2], 1, -1);
                    $explode_key = explode(":", $ck_key);
                    $secret_key = trim($explode_key[1], '"');

                    $con_key = substr($detail_values[3], 1, -1);
                    $explode_key = explode(":", $con_key);
                    $consumer_key = trim($explode_key[1], '"');

                    if ($api_url != null && $api_url != " " && $secret_key != null && $secret_key != " " && $consumer_key != null && $consumer_key != " ") {

                        $orders = @file_get_contents('php://input');
                        $orders = json_decode($orders, true);

                        if ($orders != null && $orders != '') {

                            DB::table("line_items")->where("order_id", $orders['id'])->delete();

                            $shipping_cost = 0;
                            $fee_cost = 0;

                            $shipping = $orders['shipping_lines'];
                            if ($shipping != '' &&  $shipping != null) {
                                for ($s = 0; $s < sizeOf($shipping); $s++) {
                                    $shipping_cost = $shipping_cost + $shipping[$s]['total'];
                                }
                            }

                            $fees = $orders['fee_lines'];
                            if ($fees != '' &&  $fees != null) {
                                for ($f = 0; $f < sizeOf($fees); $f++) {
                                    $fee_cost = $fee_cost + $fees[$f]['total'];
                                }
                            }

                            DB::table("orders")->where("custom_id", $orders['id'])->update([
                                "woo_id" => $orders['id'],
                                "custom_id" => $orders['id'],
                                "currency" => $orders['currency'],
                                "version" => $orders['version'],
                                "fees" => $fee_cost,
                            ]);

                            $line_items = $orders['line_items'];
                            for ($i = 0; $i < sizeOf($line_items); $i++) {
                                DB::table("line_items")->insert([
                                    "id" => $line_items[$i]['id'],
                                    "order_id" => $orders['id'],
                                    "name" => $line_items[$i]['name'],
                                    "quantity" => $line_items[$i]['quantity'],
                                    "total" => $line_items[$i]['subtotal'],
                                    "price" => $line_items[$i]['price'],
                                    "shipping" =>  $shipping_cost,
                                ]);
                            }
                        }
                    }
                } else {
                    return response()->json([
                        "message" => 'Wordpress plugin not verified',
                        "status" => 500,
                        "success" => false,
                    ]);
                }
            } else {
                return response()->json([
                    "message" => 'Wordpress plugin not verified',
                    "status" => 500,
                    "success" => false,
                ]);
            }
        } else {
            return response()->json([
                "message" => 'Wordpress plugin not enabled',
                "status" => 500,
                "success" => false,
            ]);
        }
    }

    public function wp_order_delete()
    {
        $wordpress = DB::Table("integrations")->where("slug", "wordpress")->where('status', 1)->first();
        if(!empty($wordpress)) {
            if ($wordpress->is_verified == 1) {
                if ($wordpress->details != NULL & $wordpress->details != '') {

                    $detail_values = explode(",", $wordpress->details);
                    $api = substr($detail_values[1], 1, -1);
                    $explode_key = explode(":", $api);
                    $url = $explode_key[1] . ':' . $explode_key[2];
                    $api_url = trim(str_replace('\/', '/', $url), '"');

                    $ck_key = substr($detail_values[2], 1, -1);
                    $explode_key = explode(":", $ck_key);
                    $secret_key = trim($explode_key[1], '"');

                    $con_key = substr($detail_values[3], 1, -1);
                    $explode_key = explode(":", $con_key);
                    $consumer_key = trim($explode_key[1], '"');

                    if ($api_url != null && $api_url != " " && $secret_key != null && $secret_key != " " && $consumer_key != null && $consumer_key != " ") {

                        $orders = @file_get_contents('php://input');
                        $orders = json_decode($orders, true);

                        if ($orders != null && $orders != '') {

                            DB::table("orders")->where("custom_id", $orders['id'])->delete();
                            DB::table("line_items")->where("order_id", $orders['id'])->delete();
                        }
                    }
                } else {
                    return response()->json([
                        "message" => 'Wordpress plugin not verified',
                        "status" => 500,
                        "success" => false,
                    ]);
                }
            } else {
                return response()->json([
                    "message" => 'Wordpress plugin not verified',
                    "status" => 500,
                    "success" => false,
                ]);
            }
        } else {
            return response()->json([
                "message" => 'Wordpress plugin not enabled',
                "status" => 500,
                "success" => false,
            ]);
        }
    }



    public function wp_product_create()
    {
        $wordpress = DB::Table("integrations")->where("slug", "wordpress")->where('status', 1)->first();
        if(!empty($wordpress)) {
            if ($wordpress->is_verified == 1) {
                if ($wordpress->details != NULL & $wordpress->details != '') {

                    $detail_values = explode(",", $wordpress->details);
                    $api = substr($detail_values[1], 1, -1);
                    $explode_key = explode(":", $api);
                    $url = $explode_key[1] . ':' . $explode_key[2];
                    $api_url = trim(str_replace('\/', '/', $url), '"');

                    $ck_key = substr($detail_values[2], 1, -1);
                    $explode_key = explode(":", $ck_key);
                    $secret_key = trim($explode_key[1], '"');

                    $con_key = substr($detail_values[3], 1, -1);
                    $explode_key = explode(":", $con_key);
                    $consumer_key = trim($explode_key[1], '"');

                    if ($api_url != null && $api_url != " " && $secret_key != null && $secret_key != " " && $consumer_key != null && $consumer_key != " ") {

                        $products = @file_get_contents('php://input');
                        $products = json_decode($products, true);
                        if ($products != null && $products != '') {
                            DB::table("products")->insert([
                                "woo_id" => $products['id'],
                                "name" => $products['name'],
                                "type" => $products['type'],
                                "status" => $products['status'],
                                "description" => $products['description'],
                                "sku" => $products['sku'],
                                "regular_price" => $products['regular_price'],
                                "sale_price" => $products['sale_price'],
                                "purchase_note" => $products['purchase_note'],
                                "created_at" => Carbon::now(),
                                "updated_at" => Carbon::now(),
                            ]);
                        }
                    }
                } else {
                    return response()->json([
                        "message" => 'Wordpress plugin not verified',
                        "status" => 500,
                        "success" => false,
                    ]);
                }
            } else {
                return response()->json([
                    "message" => 'Wordpress plugin not verified',
                    "status" => 500,
                    "success" => false,
                ]);
            }
        } else {
            return response()->json([
                "message" => 'Wordpress plugin not enabled',
                "status" => 500,
                "success" => false,
            ]);
        }
    }

    public function wp_product_update()
    {
        $wordpress = DB::Table("integrations")->where("slug", "wordpress")->where('status', 1)->first();
        if(!empty($wordpress)) {
            if ($wordpress->is_verified == 1) {
                if ($wordpress->details != NULL & $wordpress->details != '') {

                    $detail_values = explode(",", $wordpress->details);
                    $api = substr($detail_values[1], 1, -1);
                    $explode_key = explode(":", $api);
                    $url = $explode_key[1] . ':' . $explode_key[2];
                    $api_url = trim(str_replace('\/', '/', $url), '"');

                    $ck_key = substr($detail_values[2], 1, -1);
                    $explode_key = explode(":", $ck_key);
                    $secret_key = trim($explode_key[1], '"');

                    $con_key = substr($detail_values[3], 1, -1);
                    $explode_key = explode(":", $con_key);
                    $consumer_key = trim($explode_key[1], '"');

                    if ($api_url != null && $api_url != " " && $secret_key != null && $secret_key != " " && $consumer_key != null && $consumer_key != " ") {

                        $products = @file_get_contents('php://input');
                        $products = json_decode($products, true);
                        if ($products != null && $products != '') {
                            DB::table("products")->where('woo_id', $products['id'])->update([
                                "woo_id" => $products['id'],
                                "name" => $products['name'],
                                "type" => $products['type'],
                                "status" => $products['status'],
                                "description" => $products['description'],
                                "sku" => $products['sku'],
                                "regular_price" => $products['regular_price'],
                                "sale_price" => $products['sale_price'],
                                "purchase_note" => $products['purchase_note'],
                                "created_at" => Carbon::now(),
                                "updated_at" => Carbon::now(),
                            ]);
                        }
                    }
                } else {
                    return response()->json([
                        "message" => 'Wordpress plugin not verified',
                        "status" => 500,
                        "success" => false,
                    ]);
                }
            } else {
                return response()->json([
                    "message" => 'Wordpress plugin not verified',
                    "status" => 500,
                    "success" => false,
                ]);
            }
        } else {
            return response()->json([
                "message" => 'Wordpress plugin not enabled',
                "status" => 500,
                "success" => false,
            ]);
        }
    }

    public function wp_product_delete()
    {
        $wordpress = DB::Table("integrations")->where("slug", "wordpress")->where('status', 1)->first();
        if(!empty($wordpress)) {
            if ($wordpress->is_verified == 1) {
                if ($wordpress->details != NULL & $wordpress->details != '') {

                    $detail_values = explode(",", $wordpress->details);
                    $api = substr($detail_values[1], 1, -1);
                    $explode_key = explode(":", $api);
                    $url = $explode_key[1] . ':' . $explode_key[2];
                    $api_url = trim(str_replace('\/', '/', $url), '"');

                    $ck_key = substr($detail_values[2], 1, -1);
                    $explode_key = explode(":", $ck_key);
                    $secret_key = trim($explode_key[1], '"');

                    $con_key = substr($detail_values[3], 1, -1);
                    $explode_key = explode(":", $con_key);
                    $consumer_key = trim($explode_key[1], '"');

                    if ($api_url != null && $api_url != " " && $secret_key != null && $secret_key != " " && $consumer_key != null && $consumer_key != " ") {

                        $products = @file_get_contents('php://input');
                        $products = json_decode($products, true);
                        if ($products != null && $products != '') {
                            DB::table("products")->where('woo_id', $products['id'])->update([
                                "is_deleted" => 1,
                            ]);
                        }
                    }
                } else {
                    return response()->json([
                        "message" => 'Wordpress plugin not verified',
                        "status" => 500,
                        "success" => false,
                    ]);
                }
            } else {
                return response()->json([
                    "message" => 'Wordpress plugin not verified',
                    "status" => 500,
                    "success" => false,
                ]);
            }
        } else {
            return response()->json([
                "message" => 'Wordpress plugin not enabled',
                "status" => 500,
                "success" => false,
            ]);
        }
    }
}
