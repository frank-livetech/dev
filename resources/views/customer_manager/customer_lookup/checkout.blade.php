@extends('layouts.staff-master-layout')
@section('body-content')
<script id="script1" src="https://secure.merchantonegateway.com/token/Collect.js" data-tokenization-key="zBkgJ9-6r24y2-FeFXkD-Kyxr9P" ></script>
<script>
var nmi = "{{$nmi}}";
if(nmi =='enable'){
    var nmi_integration = {!! json_encode($nmi_integration) !!};
    var data_key =nmi_integration.tokenization_key;
    var scriptTag = document.getElementById("script1");
    console.log(scriptTag)
    scriptTag.setAttribute("data-tokenization-key", data_key);
}
 
</script>
<style>
    
.bg-darke{
    background:#f7f8f9;
}
.hide{
    display:none;
}
.bold{
    font-weight:700;
}
.text-red{
    color:red;
}
.text-black{
    color:#54667a;

}
.custom-accordion-title{
    color:#54667a;

}
.text-white{
    color:#fff;
}
.desc{
    padding-top:10px;

}
.desc p{
    margin-bottom:5px;
    font-size:12px;
}
.strong{
    font-weight:500;
}
.mute{
    color:#9fa3a8;
}
.steps{
    font-size: 13px;
    position: absolute;
    top: 38px;
    right: 18px;
}
.custom-radio{
    border: 1px solid #54667a;
    padding-right: 9px;
    padding-top: 9px;
    padding-bottom: 9px;
    padding-left: 33px;
    border-radius:5px; 
    margin-bottom:9px;

}
.custom-radio label{
    display:block;
}
.custom-radio label p{
    margin-bottom:0;
}
.custom-radio label .ml-auto{
    float:right;
}
.custom-radio .mp{
    margin-right: -43px;
}
.custom-radio .mpay{
    text-align: right;
    margin-right: 5px;
    /* right: 46px; */
    position: absolute;
}
.custom-radio .mpay img{
    width:20%;
    padding-right:5px;
    margin-top:-8px;
    
}
.custom-radio .mp img{
    width:20%;
    padding-right:5px;
    margin-top:-8px;
    
}
.img-wd{
    margin-right:0;
}
.img-wd img{
    width:35% !important;
}
.br-01{
    border-color: transparent !important;
    padding-top:0;
    padding-bottom:0;
}
/* .container-fluid{
color:#000;
} */

</style>
<div class="page-breadcrumb">
    <div class="row">
        <div class="col-md-5 align-self-center">
            <!--<h4 class="page-title">Basic Table</h4>-->
            <div class="d-flex align-items-center">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{route('home')}}">Home</a></li>
                        <li class="breadcrumb-item" aria-current="page">Customer Profile</li>
                        <li class="breadcrumb-item active" aria-current="page">Checkout</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
</div>
<div class="container-fluid">
    <div class="row">
        <div class="col-md-7">
            <div class="card bg-darke">
                <div class="card-body">
                    <div class="description">
                        <p>
                            <a type="button" class="bold" id="openSignIn">Sign In</a>
                            for a faster checkout experience, or you can continue as a guest. You will be able to create an account during checkout.
                        </p>
                    </div>
                    <div id="signInForm" class="hide">
                        <div class="col-12 form-group">
                            <label>* Email</label>
                            <input type="email" class=" form-control" value="" id="" placeholder="Email Address">
                        </div>
                        <div class="col-12 form-group">
                            <label>* Password</label>
                            <input type="password" class=" form-control" value="" id="" placeholder="Password">
                        </div>
                        <div class="col-12 form-group">
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" class="custom-control-input" id="check2">
                                <label class="custom-control-label" for="check2"> Remember Me </label>
                            </div>
                        </div>Please add me to your email list so I can receive special promotions and product updates.
                        <div class="col-12 form-group">
                            <a href="#">Forgot Password?</a>
                        </div>
                        <div class="col-md-12 text-right">
                            <button class="btn btn-secondary">Cancel</button>
                            <button class="btn btn-success">Sign In</button>
                        </div>
                    </div>
                </div>
            </div>


            <div class="row">
                <div class="col-md-12">

                    <div id="accordion" class="custom-accordion mb-4">

                        <div class="card mb-0">
                            <div class="card-header" id="headingOne">
                                <h5 class="m-0">
                                    <a class="custom-accordion-title d-flex align-items-center pt-2 pb-2 collapsed" data-toggle="collapse" href="#collapseStep1" aria-expanded="false" aria-controls="collapseStep1">
                                        1. Confirm Details <span class="ml-auto mute">Edit</span>
                                    </a>
                                    <span class="mute steps">step 1 of 3</span>
                                </h5>
                            </div>
                            <div id="collapseStep1" class="collapse show" aria-labelledby="headingOne" data-parent="#accordion" style="">
                                <div class="card-body">

                                    <a href="#" class="text-black " style="font-size:12px;">Additional Shipping Information </a>
                                    <hr>

                                    <div class="form row">
                                        <!-- <div class="col-12 form-group">
                                            <label>* Email for Order Confirmation</label>
                                            <input type="email" class=" form-control" value="{{$customer->email}}" id="" placeholder="">
                                        </div> -->
                                        <div class="col-6 form-group">
                                            <label>* First Name</label>
                                            <input type="text" class=" form-control" value="{{$customer->first_name}}" id="fname" placeholder="">
                                        </div>
                                        <div class="col-6 form-group">
                                            <label>* Last Name</label>
                                            <input type="text" class=" form-control" value="{{$customer->last_name}}" id="lname" placeholder="">
                                        </div>
                                        <div class="col-12 form-group">
                                            <label>* Address Line 1</label>
                                            <input type="text" class=" form-control" value="{{$customer->address}}" id="address1" placeholder="">
                                        </div>
                                        <div class="col-12 form-group">
                                            <label>* Address Line 2 (Apt, Floor, Ste, etc)</label>
                                            <input type="text" class=" form-control" value="{{$customer->apt_address}}" id="" placeholder="">
                                        </div>
                                        <div class="col-3 form-group">
                                            <label>* Country</label>
                                            <select class="select2 form-control " id="prof_country" name="prof_country" style="width: 100%; height:36px;" onchange="listStates(this.value, 'prof_state', 'cust_state')">
                                                    <option value="">Select Country</option>
                                                    @foreach($countries as $country)
                                                        @if(!empty($customer->country) && $customer->country == $country->name)
                                                            <option value="{{$country->name}}" selected>{{$country->name}}</option>
                                                        @else
                                                            <option value="{{$country->name}}" {{$country->short_name == 'US' ? 'selected' : ''}}>{{$country->name}}</option>
                                                        @endif
                                                    @endforeach
                                            </select>
                                        </div>
                                        <div class="col-3 form-group">
                                            <label>* City</label>
                                            <input type="text" class=" form-control" value="{{$customer->cust_city}}" id="city" placeholder="">
                                        </div>
                                        <div class="col-3 form-group">
                                            <label for="state">* State</label>
                                            <select class="select2 form-control " id="prof_state" name="prof_state" style="width: 100%; height:36px;"></select>
                                        </div>
                                        
                                        <div class="col-3 form-group">
                                            <label>* Zip Code</label>
                                            <input type="number" class=" form-control" value="{{$customer->cust_zip}}" id="zip" placeholder="">
                                        </div>
                                        <div class="col-12 form-group">
                                            <label>* Phone Number</label>
                                            <input type="number" class=" form-control" value="{{$customer->phone}}" id="" placeholder="">
                                        </div>
                                        <div class="col-12 form-group">
                                            <div class="custom-control custom-checkbox">
                                                <input type="checkbox" class="custom-control-input" id="check3">
                                                <label class="custom-control-label" for="check3"> Please add me to your email list so I can receive special promotions and product updates. </label>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- <hr>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <h3>Shopping Method</h3>
                                            <p class="mute " style="font-size:12px;">Due to carrier volumes beyond our control, related to COVID-19, it may take 2-3 more business days to receive any order, regardless of shipment method.</p>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="custom-control custom-radio">
                                                <input type="radio" id="customRadio1" name="customRadio" class="custom-control-input">
                                                <label class="custom-control-label" for="customRadio1"> <p>Ground - 2-7 Business Days <span class="ml-auto"> Free</span></p></label>
                                            </div>
                                            <div class="custom-control custom-radio">
                                                <input type="radio" id="customRadio2" name="customRadio" class="custom-control-input" checked="">
                                                <label class="custom-control-label" for="customRadio2"><p>UPS 2 Day - 2-3 Business Days <span class="ml-auto"> $15.00</span></p></label>
                                            </div>
                                            <div class="custom-control custom-radio">
                                                <input type="radio" id="customRadio3" name="customRadio" class="custom-control-input" >
                                                <label class="custom-control-label" for="customRadio3"><p>UPS Next Day - 1-2 Business Days <span class="ml-auto"> $25.00</span></p></label>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-12 text-right">
                                            <button class="btn btn-success" style="padding: 13px 48px;"> Continue To Payment </button>
                                        </div>
                                    </div> -->
                                </div>
                            </div>
                        </div> <!-- end card-->

                        <div class="card mb-0">
                            <div class="card-header" id="headingTwo">
                                <h5 class="m-0">
                                    <a class="custom-accordion-title collapsed d-flex align-items-center pt-2 pb-2" data-toggle="collapse" href="#collapseStep2" aria-expanded="false" aria-controls="collapseStep2">
                                       2. Make Payment  <span class="ml-auto mute">Edit</span>
                                    </a>
                                    <span class="mute steps">step 2 of 3</span>
                                </h5>
                            </div>
                            <div id="collapseStep2" class="collapse" aria-labelledby="headingTwo" data-parent="#accordion">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <p class="small">Please enter billing and payment information.</p>
                                            <h3>Billing Address</h3>

                                            <div class="row bill_address hide">
                                                <div class="col-6 form-group">
                                                    <label>* First Name</label>
                                                    <input type="text" class=" form-control" value="" id="" placeholder="">
                                                </div>
                                                <div class="col-6 form-group">
                                                    <label>* Last Name</label>
                                                    <input type="text" class=" form-control" value="" id="" placeholder="">
                                                </div>
                                                <div class="col-12 form-group">
                                                    <label>* Address Line 1</label>
                                                    <input type="text" class=" form-control" value="" id="" placeholder="">
                                                </div>
                                                <div class="col-12 form-group">
                                                    <label>* Address Line 2 (Apt, Floor, Ste, etc)</label>
                                                    <input type="text" class=" form-control" value="" id="" placeholder="">
                                                </div>
                                                <div class="col-3 form-group">
                                                    <label>* Country</label>
                                                    <input type="text" class=" form-control" value="" id="" placeholder="">
                                                </div>
                                                <div class="col-3 form-group">
                                                    <label>* City</label>
                                                    <input type="text" class=" form-control" value="" id="" placeholder="">
                                                </div>
                                                <div class="col-3 form-group">
                                                    <label for="state">* State</label>
                                                    <select class="select2 form-control " name="" id="" name=""
                                                        style="width: 100%; height:36px;">
                                                        <option value="">Select State</option>

                                                    </select>
                                                </div>
                                                
                                                <div class="col-3 form-group">
                                                    <label>* Zip Code</label>
                                                    <input type="number" class=" form-control" value="" id="" placeholder="">
                                                </div>
                                                <div class="col-12 form-group">
                                                    <label>* Phone Number</label>
                                                    <input type="number" class=" form-control" value="" id="" placeholder="">
                                                </div>
                                            </div>
                                            <div class=" form-group">
                                                <div class="custom-control custom-checkbox">
                                                    <input type="checkbox" class="custom-control-input" id="check4">
                                                    <label class="custom-control-label" for="check4"> My billing address is the same as my shipping address. </label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <hr>

                                    <div class="row">
                                        <div class="col-md-12">
                                            <h3>Shopping Method</h3>
                                            <p class="mute " style="font-size:12px;">Due to carrier volumes beyond our control, related to COVID-19, it may take 2-3 more business days to receive any order, regardless of shipment method.</p>
                                        </div>
                                        <div class="col-md-12">
                                        @if($nmi=='enable') 
                                                <div class="custom-control custom-radio payment-radio">
                                                            <input type="radio" id="paymentRadio1" value="cred-card" name="paymentRadio" class="custom-control-input" checked="">
                                                            <label class="custom-control-label" for="paymentRadio1"> <p>Credit Card <span class="ml-auto mp"> <img src="http://localhost/framework/files/user_photos/visa.png"><img src="http://localhost/framework/files/user_photos/master-card.png"><img src="http://localhost/framework/files/user_photos/discover.png"><img src="http://localhost/framework/files/user_photos/american-express.png"></span></p></label>
                                                            </label>
                                                </div>
                                        @endif
                                        @if($paypal=='enable')      
                                                <div class="custom-control custom-radio">
                                                    <input type="radio" id="paymentRadio2" value="payPal" name="paymentRadio" class="custom-control-input" >
                                                    <label class="custom-control-label" for="paymentRadio2"><p>PayPal <span class="ml-auto mpay"> <img src="http://localhost/framework/files/user_photos/paypal.png"></span></p></label>
                                            </div>
                                            @endif
                                        </div>
                                        <div class="col-md-12 mt-4 mb-4">
                                            @if($nmi =='enable')
                                           
                                            <div class="col-md-12 " id="cred-card">
                                                <div class="row">
                                                
                                                    <div class="col-md-12">
                                                        <div id="payment_cards">
                                                                
                                                        </div>
                                                            
                                                        <form id="CardForm" class="CardForm" >
                                      
                                                                <div class="row">
                                                                    
                                                                    
                                                                    <input type="hidden" name="payment_token" id="payment_token" value="0">
                                                                    <input type="hidden" name="card_type" id="card_type">
                                                                    <input type="hidden" name="exp" id="exp">
                                                                    <input type="hidden" name="cardlastDigits" id="cardlastDigits">
                                                                    <input type="hidden" name="orderId" value="{{$order->id}}" id="orderId">

                                      
                                           <div class="custom-control custom-radio br-0">
                                                <input type="radio" id="payButton" name="paymentCardExist" class="custom-control-input" >
                                                <label class="custom-control-label" for="payButton"><p> Use a new payment method </p></label>
                                            </div>
                                            <div class="col-md-12 pr-0 pl-0" id="">
                                                <!-- <div class="text-center">
                                                    <button  class="btn btn-outline-secondary btn-success" id="creditPay" style="padding: 0 100px;width: 54%;"> Pay </button>
                                                </div> -->
                                                <div class="text-right">
                                                    <button class="btn  btn-success " id="creditPay" style="padding: 10px 55px;"> Pay Now </button>
                                                </div>
                                            </div>
                                       </div>    

                                       

 
                                                       </form>
                                                       
                                                    </div>
                                                </div>
                                               
                                            </div>
                                            @endif
                                            @if($paypal =='enable')
                                           
                                            <div class="col-md-12 credit pr-0 pl-0" id="payPal">
                                                <p class="mute small">Use your PayPal account to complete your online transaction.</p>
                                                <div class="text-center">
                                                    <a href="{{url('paypal/ec-checkout')}}/{{$order->id}}" class="btn btn-outline-secondary" style="padding: 0 100px;width: 54%;"><img src="http://localhost/framework/files/user_photos/paypal.png" style="width:100%;"></a>
                                                </div>
                                            </div>
                                            @endif
                                            
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div> <!-- end card-->

                        <div class="card mb-0">
                            <div class="card-header" id="headingThree">
                                <h5 class="m-0">
                                    <a class="custom-accordion-title collapsed d-flex align-items-center pt-2 pb-2" data-toggle="collapse" href="#collapseStep3" aria-expanded="false" aria-controls="collapseStep3">
                                        3. Confirm &  Share <span class="ml-auto mute">Edit</span>
                                    </a>
                                    <span class="mute steps">step 3 of 3</span>
                                </h5>
                            </div>
                            <div id="collapseStep3" class="collapse" aria-labelledby="headingThree" data-parent="#accordion">
                                <div class="card-body">
                                    <div class="row mt-4 mb-4">
                                        <div class="col-md-12">
                                            <div class=" form-group">
                                                <div class="custom-control custom-checkbox">
                                                    <input type="checkbox" class="custom-control-input" id="checkMsg">
                                                    <label class="custom-control-label" for="checkMsg"> Add a gift message to your order. </label>
                                                </div>
                                            </div>
                                            <hr>
                                            <div id="addMsg">
                                                <div class="col-md-8 form-group">
                                                    <p class="mute small">This order will ship with a gift receipt.</p>

                                                    <label> Gift Message</label>
                                                    <textarea name="" id="" class=" form-control" rows="3"  placeholder="(Optional, Max 250 characters)"></textarea>
                                                    
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class=" form-group">
                                                <div class="custom-control custom-checkbox">
                                                    <input type="checkbox" class="custom-control-input" id="checkAcc">
                                                    <label class="custom-control-label" for="checkAcc">  Create Account. </label>
                                                </div>
                                            </div>
                                            <hr>
                                            <div id="addAcc" class="mb-4">
                                                <div class="col-md-8">
                                                    <div class=" form-group">
                                                        <label> * Password</label>
                                                        <input type="password" class="form-control">
                                                    </div>
                                                    <div class=" form-group">
                                                        <label> * Confirm Password</label>
                                                        <input type="password" class="form-control">
                                                    </div>
                                                   
                                                </div>
                                                <div class="col-md-12 ">
                                                    <button class="btn btn-success" style="padding: 13px 48px;"> Create Account </button>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-12 text-right">
                                        <button class="btn btn-primary" style="padding: 13px 48px;"> <i class="fas fa-share-square"></i> Share  </button>
                                            <button class="btn btn-success" style="padding: 13px 48px;"> <i class="fas fa-lock"></i> Place Order </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div> <!-- end card-->
                    </div> <!-- end custom accordions-->
                    <div class="card bg-darke">
                        <div class="card-body">
                            <div class="row mt-4 mb-4">
                                <div class="col-md-12 form-group">
                                    
                                    <div class="custom-control custom-checkbox">
                                        <input type="checkbox" class="custom-control-input" id="sendRecipt">
                                        <label class="custom-control-label" for="sendRecipt"> Send Recipt Notification </label>
                                    </div>
                                </div>
                                <div class="col-md-12 form-group">
                                    <div class="custom-control custom-checkbox">
                                        <input type="checkbox" class="custom-control-input" id="splitPay">
                                        <label class="custom-control-label" for="splitPay">  Split Payment </label>
                                    </div>
                                </div>
                                <div class="col-md-12 form-group">
                                    <label> Note</label>
                                    <textarea name="" id="" class=" form-control" rows="3"  placeholder="(Optional, Max 250 characters)"></textarea>
                                </div>
                                <div class="col-md-12 text-right">
                                    <button class="btn btn-success" style="padding: 13px 48px;"> <i class="fas fa-check"></i> Save </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-5">
            <div id="accordion" class="custom-accordion mb-4">
                <div class="card mb-0">
                    <div class="card-header" id="headingOne">
                        <h5 class="m-0">
                            <a class="custom-accordion-title d-flex align-items-center pt-2 pb-2 text-black" data-toggle="collapse" href="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                                Add Promo Code
                                <span class="ml-auto">
                                    <i class="mdi mdi-chevron-down accordion-arrow"></i>
                                </span>
                            </a>
                        </h5>
                    </div>
                    <div id="collapseOne" class="collapse" aria-labelledby="headingOne" data-parent="#accordion" style="">
                        <div class="card-body">
                            <div class="promoForm col-md-12">
                                <div class="row">
                                    <div class="col-md-9">
                                        <input type="text" class=" form-control" value="" id="" placeholder="House number and street name">
                                    </div>
                                    <div class="col-md-3">
                                        <button type="button" class="btn btn-success"> Apply </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div> <!-- end card-->
            </div> <!-- end custom accordions-->

            <div id="accordion2" class="custom-accordion mb-4">
                <div class="card mb-0">
                    <div class="card-header" id="MilitaryTab">
                        <h5 class="m-0">
                            <a class="custom-accordion-title d-flex align-items-center pt-2 pb-2 text-black" data-toggle="collapse" href="#collapseMilitary" aria-expanded="true" aria-controls="collapseMilitary">
                            Military, First Responders & Medical
                                <span class="ml-auto">
                                    <i class="mdi mdi-chevron-down accordion-arrow"></i>
                                </span>
                            </a>
                        </h5>
                    </div>
                    <div id="collapseMilitary" class="collapse" aria-labelledby="MilitaryTab" data-parent="#accordion2" style="">
                        <div class="card-body">
                            <div class=" col-md-12">
                                <p>Verifying is quick! Authenticate with ID.me, and receive 15% off.</p>
                                <a type="button"><img src="http://localhost/framework/files/user_photos/NavyButton.jpg" alt=""></a>
                                <p class="mt-2"> <i class="fas fa-lock"></i> Verification by ID.me • 
                                    <a href="https://www.nixon.com/us/en/nixon-promotions.html#id-me" target="_blank">What is ID.me?</a>
                                </p>
                            </div>
                        </div>
                    </div>
                </div> <!-- end card-->
            </div> <!-- end custom accordions-->
            <div class="card bg-darke">
                <div class="card-body">
                    <div id="accordion2" class="custom-accordion mb-4">
                        <div class="card mb-0" style="box-shadow:none;">
                            <div class="card-header"  id="MilitaryTab" style="background:#fff;">
                                <h5 class="m-0">
                                    <a class="custom-accordion-title d-flex align-items-center pt-2 pb-2 text-black" data-toggle="collapse" href="#collapseProduct" aria-expanded="true" aria-controls="collapseProduct">
                                    Cart Summary ({{count($order_items)}})
                                        <span class="ml-auto">
                                            <i class="mdi mdi-chevron-down accordion-arrow"></i>
                                        </span>
                                    </a>
                                </h5>
                            </div>
                            <div id="collapseProduct" class="collapse show" aria-labelledby="MilitaryTab" data-parent="#accordion2" style="">
                                <div class="card-body" style="max-height:420px; overflow-y:auto;">
                                @php 
                                $total =0;
                                @endphp
                                @foreach($order_items as $item)
                                    <div class="row">
                                        <div class="col-md-3 text-center">
                                            <img src="{{asset('public/files/brand_files')}}/{{Session::get('site_favicon')}}" alt="Sentry Leather" title="Sentry Leather" style="width:70%;">
                                        </div>
                                        <div class="col-md-9">
                                            <div class="desc">
                                                    <!-- <p><a href="#" class="text-black">Sentry Leather</a></p> -->
                                                    <p>{{$item->name}}</p>
                                                    <p>QTY: {{$item->quantity}}</p>
                                                    <p>Price: ${{$item->price}}</p>
                                                    <!-- <p class="text-red">$33.00</p> -->
                                                    <!-- <p>Total: $132.00</p> -->
                                                    @php 
                                $total +=$item->price;
                                @endphp
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                                    <hr>
                                        </div>
                                    </div>
                                @endforeach   
                                </div>
                            </div>
                        </div> <!-- end card-->
                    </div> <!-- end custom accordions-->
                    <div class="card"  style="box-shadow:none;">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-8">
                                    <p class="mb-2">Item Subtotal</p>
                                </div>
                                <div class="col-md-4 text-right">
                                    <p class="mb-2">${{number_format($total,2)}}</p>
                                </div>
                                <div class="col-md-4"> 
                                    <p class="mb-0">Fees *</p>
                                   
                                </div>
                                <div class="col-md-8 text-right"> 
                                    <p class="mb-2">${{number_format($order->fees,2)}}</p>
                                </div>
                                <div class="col-md-8">
                                    <p class="mb-2"> Discount * </p>
                                </div>
                                <div class="col-md-4 text-right"> 
                                    <p class="mb-2"> ${{number_format($order->discount,2)}}</p>
                                </div>
                                <div class="col-md-8">
                                    <p class="mb-2"> Tax * </p>
                                </div>
                                <div class="col-md-4 text-right"> 
                                    <p class="mb-2"> ${{number_format($order->tax,2)}}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <dv class="row">
                        <div class="col-md-12">
                            <div class="row" style="margin-left:5px;margin-right:5px;">
                                <div class="col-md-6">
                                    <h4>Order Total</h4>
                                </div>
                                <div class="col-md-6 text-right">
                                    <h4>${{number_format($order->grand_total,2)}}</h4>
                                </div>
                                <div class="col-md-12">
                                    <p class="mute text-center">*Shipping and tax are calculated after the shipping step is completed.</p>
                                </div>
                            </div>
                        </div>
                    </dv>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12 text-center">
                    <p class="strong">Questions? Call Nixon Customer Service at (888) 455-9200.</p>
                </div>
            </div>
        </div>
    </div>
</div>

<!--Edit Payment Model-->

<div class="modal fade" id="editPayment" tabindex="-1"  aria-hidden="true" data-target="#staticBackdrop">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Add Card Details</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
            <div class="card-body">
                <form id="CardForm" class="CardForm">
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <input type="text" class="form-control" placeholder="First Name" name="fname" id="fname" value="Mohsin" autofocus="">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <input type="text" class="form-control" placeholder="Last Name" name="lname" id="lname" value="Malik">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <input type="text" class="form-control" placeholder="Street Address" name="address1" id="address1" value="H#1,str#9,Sitara Colony#1,ChungiAmerSidhu">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <input type="text" class="form-control" placeholder="City" name="city" id="city" value="Lahore">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <select class="select2 form-control select2-hidden-accessible" id="state" name="state" style="width: 100%; height:36px;" data-select2-id="state" tabindex="-1" aria-hidden="true">
                                    <option value="">Select State</option>                                                                                                               </select><span class="select2 select2-container select2-container--default" dir="ltr" data-select2-id="1" style="width: 100%;"><span class="selection"><span class="select2-selection select2-selection--single" role="combobox" aria-haspopup="true" aria-expanded="false" tabindex="0" aria-labelledby="select2-state-container"><span class="select2-selection__rendered" id="select2-state-container" role="textbox" aria-readonly="true" title="Arkansas">Arkansas</span><span class="select2-selection__arrow" role="presentation"><b role="presentation"></b></span></span></span><span class="dropdown-wrapper" aria-hidden="true"></span></span> 
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <input type="text" class="form-control" placeholder="Zip code" name="zip" id="zip" value="54000">
                            </div>
                        </div>
                        <input type="hidden" name="payment_token" id="payment_token" value="0">
                        <input type="hidden" name="card_type" id="card_type">
                        <input type="hidden" name="exp" id="exp">
                        <input type="hidden" name="cardlastDigits" id="cardlastDigits">

                        <div class="col-md-12 text-right">
                            <input type="submit" id="payButton" value="ADD Card" class="btn btn-success">
                        </div>
                    </div>    
                </form>
            </div>
      </div>
      <div class="modal-footer text-right">
        <button type="button" class="btn btn-success"> Save </button>
      </div>
    </div>
  </div>
</div>
@endsection
@section('scripts')
<!-- jQuery ui files-->
<link rel="stylesheet" href="https://code.jquery.com/ui/1.12.0/themes/smoothness/jquery-ui.css">
<script src="https://code.jquery.com/ui/1.12.0/jquery-ui.min.js" integrity="sha256-eGE6blurk5sHj+rmkfsGYeKyZx3M4bG+ZlFyA7Kns7E=" crossorigin="anonymous"></script>

@include('js_files.statesJs')
@include('js_files.customer_lookup.checkoutJs')
@endsection