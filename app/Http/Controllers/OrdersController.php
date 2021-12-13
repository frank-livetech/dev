<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use App\Models\Orders;
use Illuminate\Support\Facades\DB;
use Throwable;
use Carbon\Carbon;
use Automattic\WooCommerce\Client;
use Automattic\WooCommerce\HttpClient\HttpClientException;

class OrdersController extends Controller
{
    protected $woocommerce;

    public function __construct(){
        $this->middleware('auth');

        $this->woocommerce = new Client(
            GeneralController::PROJECT_DOMAIN_NAME,
            'ck_a85395ca56038f0c7b2a53f637259e73223b2245', 
            'cs_6670477796c70e32ce6393a38263b42d9a1dacda',
            [
                'version' => 'wc/v1',
                'verify_ssl' => false
            ]
        );
    }
    
    public function orders_lookup(){
        $orders = Orders::get();
        return view('orders.index',compact('orders'));
    }

    public function syncOrders(){
        $per_page = 100;
        $count = 1;
        $sync = true;
        $total = 0;
        while($sync){
            echo "<br><br>Page : ".$count."<br><br>";
            $result = json_decode(json_encode($this->woocommerce->get('orders?per_page='.$per_page.'&page='.$count)), true);
            
            $count++;
            foreach ($result as $key => $value) {
                $subs = Orders::where('woo_id', $value['id'])->first();
                if(!$subs){
                    Orders::create([
                        'woo_id' => $value['id'],
                        'parent_id'=>$value['parent_id'],
                        'number' => $value['number'],
                        'order_key'=>$value['order_key'],
                        'created_via'=>$value['created_via'],
                        'version'=>$value['version'],
                        'status'=>$value['status'],
                        'currency'=>$value['currency'],
                        'discount_total'=>$value['discount_total'],
                        'discount_tax'=>$value['discount_tax'],
                        'shipping_total'=>$value['shipping_total'],
                        'shipping_tax'=>$value['shipping_tax'],
                        'cart_tax'=>$value['cart_tax'],
                        'total'=>$value['total'],
                        'total_tax'=>$value['total_tax'],
                        'prices_include_tax'=>$value['prices_include_tax'],
                        'customer_id'=>$value['customer_id'],
                        'customer_ip_address'=>$value['customer_ip_address'],
                        'customer_user_agent'=>$value['customer_user_agent'],
                        'customer_note'=>$value['customer_note'],
                        'payment_method'=>$value['payment_method'],
                        'payment_method_title'=>$value['payment_method_title'],
                        'transaction_id'=>$value['transaction_id'],
                        'date_paid'=>($value['date_paid']) ? $value['date_paid'] : null,
                        'date_completed'=>($value['date_completed']) ? $value['date_completed'] : null,
                        'cart_hash'=>$value['cart_hash']
                    ]);
                    $total++;
                }
            }
            
            if(count($result) < $per_page){
                $sync = false;
                break;
            }
        }
        if($total > 0){
            echo "<center><h3>Done! Synced ".$total." Subscriptions</h3></center>";
        }else{
            echo "<center><h3>Done! Subscriptions Already Synced</h3></center>";
        }
    }
}
