<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use App\Models\Subscriptions;
use App\Models\LineItem;
use App\Models\Tax;
use Illuminate\Support\Facades\DB;
use Throwable;
use Carbon\Carbon;
use Automattic\WooCommerce\Client;
use Automattic\WooCommerce\HttpClient\HttpClientException;

class SubscriptionsController extends Controller
{
    protected $woocommerce;

    public function __construct(){
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

    public function subscriptions_lookup(){
        $subscriptions = Subscriptions::get();
        return view('subscriptions.index',compact('subscriptions'));
    }

    public function syncSubscriptions(){
        $per_page = 50;
        $count = 1;
        $sync = true;
        $total = 0;
        while($sync){
            echo "<pre>";
            echo "<br><br><h3>Page : ".$count."</h3><br><br>";
            $result = json_decode(json_encode($this->woocommerce->get('subscriptions?per_page='.$per_page.'&page='.$count)), true);
            $count++;
            
            foreach ($result as $key => $value) {
                $subs = Subscriptions::where('woo_id', $value['id'])->first();
                if(!$subs){
                    $subs = Subscriptions::create([
                        'woo_id' => $value['id'],
                        'parent_id'=>$value['parent_id'], 'status'=>$value['status'],
                        'order_key'=>$value['order_key'], 'currency'=>$value['currency'],
                        'version'=>$value['version'], 'prices_include_tax'=>$value['prices_include_tax'],
                        'customer_id'=>$value['customer_id'], 'discount_total'=>$value['discount_total'],
                        'discount_tax'=>$value['discount_tax'], 'shipping_total'=>$value['shipping_total'],
                        'shipping_tax'=>$value['shipping_tax'], 'cart_tax'=>$value['cart_tax'],
                        'total'=>$value['total'], 'customer_ip_address'=>$value['customer_ip_address'],
                        'total_tax'=>$value['total_tax'],'payment_method'=>$value['payment_method'],
                        'payment_method_title'=>$value['payment_method_title'],'transaction_id'=>$value['transaction_id'],
                        'customer_user_agent'=>$value['customer_user_agent'], 'created_via'=>$value['created_via'],
                        'customer_note'=>$value['customer_note'],
                        'date_completed'=>($value['date_completed']) ? $value['date_completed'] : null,
                        'date_paid'=>($value['date_paid']) ? $value['date_paid'] : null,
                        'cart_hash'=>$value['cart_hash'],
                        'billing_period'=>$value['billing_period'], 'billing_interval'=>$value['billing_interval'],
                        'start_date'=>($value['start_date']) ? $value['start_date'] : null,
                        'trial_end_date'=>($value['trial_end_date']) ? $value['trial_end_date'] : null,
                        'next_payment_date'=>($value['next_payment_date']) ? $value['next_payment_date'] : null,
                        'end_date'=>($value['end_date']) ? $value['end_date'] : null
                    ]);
                }else{
                    $subs->save([
                        'woo_id' => $value['id'],
                        'parent_id'=>$value['parent_id'], 'status'=>$value['status'],
                        'order_key'=>$value['order_key'], 'currency'=>$value['currency'],
                        'version'=>$value['version'], 'prices_include_tax'=>$value['prices_include_tax'],
                        'customer_id'=>$value['customer_id'], 'discount_total'=>$value['discount_total'],
                        'discount_tax'=>$value['discount_tax'], 'shipping_total'=>$value['shipping_total'],
                        'shipping_tax'=>$value['shipping_tax'], 'cart_tax'=>$value['cart_tax'],
                        'total'=>$value['total'], 'customer_ip_address'=>$value['customer_ip_address'],
                        'total_tax'=>$value['total_tax'],'payment_method'=>$value['payment_method'],
                        'payment_method_title'=>$value['payment_method_title'],'transaction_id'=>$value['transaction_id'],
                        'customer_user_agent'=>$value['customer_user_agent'], 'created_via'=>$value['created_via'],
                        'customer_note'=>$value['customer_note'],
                        'date_completed'=>($value['date_completed']) ? $value['date_completed'] : null,
                        'date_paid'=>($value['date_paid']) ? $value['date_paid'] : null,
                        'cart_hash'=>$value['cart_hash'],
                        'billing_period'=>$value['billing_period'], 'billing_interval'=>$value['billing_interval'],
                        'start_date'=>($value['start_date']) ? $value['start_date'] : null,
                        'trial_end_date'=>($value['trial_end_date']) ? $value['trial_end_date'] : null,
                        'next_payment_date'=>($value['next_payment_date']) ? $value['next_payment_date'] : null,
                        'end_date'=>($value['end_date']) ? $value['end_date'] : null,
                        "updated_at" => Carbon::now()
                    ]);
                }

                if(!empty($value['line_items'])){
                    foreach ($value['line_items'] as $key => $item) {
                        $lineItem = LineItem::where('subscription_id', $subs->id)->where('name', $item['name'])->first();
                        if(!$lineItem){
                            $lineItem = LineItem::create([
                                'subscription_id' => $subs->id,
                                'name' => $item['name'],
                                'sku' => $item['sku'],
                                'product_id' => $item['product_id'],
                                'variation_id'=> $item['variation_id'],
                                'quantity' => $item['quantity'],
                                'tax_class' => $item['tax_class'],
                                'price' => $item['price'],
                                'subtotal' => $item['subtotal'],
                                'subtotal_tax' => $item['subtotal_tax'],
                                'total' => $item['total'],
                                'total_tax' => $item['total_tax'],
                                'meta' => json_encode($item['meta'])
                            ]);
                        }else{
                            $lineItem->save([
                                'subscription_id' => $subs->id,
                                'name' => $item['name'],
                                'sku' => $item['sku'],
                                'product_id' => $item['product_id'],
                                'variation_id'=> $item['variation_id'],
                                'quantity' => $item['quantity'],
                                'tax_class' => $item['tax_class'],
                                'price' => $item['price'],
                                'subtotal' => $item['subtotal'],
                                'subtotal_tax' => $item['subtotal_tax'],
                                'total' => $item['total'],
                                'total_tax' => $item['total_tax'],
                                'meta' => json_encode($item['meta']),
                                'updated_at' => Carbon::now()
                            ]);
                        }

                        if(!empty($item['taxes'])){
                            foreach ($item['taxes'] as $key => $tax) {
                                $taxObj = Tax::where('lineitem_id', $lineItem->id)->where('total', $tax['total'])->where('subtotal', $tax['subtotal'])->first();
                                if(!$taxObj){
                                    $taxObj = Tax::create([
                                        'lineitem_id' => $lineItem->id,
                                        'total' => ($tax['total']) ? $tax['total'] : null,
                                        'subtotal' => ($tax['subtotal']) ? $tax['subtotal'] : null
                                    ]);
                                }else{
                                    $taxObj->save([
                                        'lineitem_id' => $lineItem->id,
                                        'total' => ($tax['total']) ? $tax['total'] : null,
                                        'subtotal' => ($tax['subtotal']) ? $tax['subtotal'] : null,
                                        'updated_at' => Carbon::now()
                                    ]);
                                }
                            }
                        }
                    }
                }
                $total++;
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
