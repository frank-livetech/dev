@extends('layouts.staff-master-layout')
<link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@400;500&display=swap" rel="stylesheet">

<style>
    
.crd_icons img{
    width:18%;
}
.currencies b{
    padding: 3px 7px;
    border: 1px solid #54667a;
    margin: 3px;
    border-radius: 50%;
}
.crypto img{
    width:14%;
}
.payCard-number{
    font-family: Orbitron;
    letter-spacing: 4px;
    margin-top: 40px;
}
.payCard-text{
    /* width: 39px; */
    font-size: 16px;
    font-family: Orbitron;
}
.payCard{
    background-color: rgba(218,165,32,0.3) !important;
}
blockquote {
        margin: unset !important;
    }

    .sl-item {
        margin: unset !important;
    }

    .profile-pic-div label {
        background: black;
        border-radius: 50%;
        cursor: pointer;
    }

    .profile-pic-div label:hover img {
        opacity: 0.5;
    }

    .profile-pic-div label:hover span {
        display: inline-block;
    }

    .profile-pic-div label span {
        color: white;
        display: none;
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        margin-top: 35px;
    }
    .select2-selection,
.select2-container--default, 
.select2-selection--single{
    border-color: #848484 !important;
}
</style>
@section('body-content')
<div class="container-fluid">
    <!-- ============================================================== -->
    <!-- Start Page Content -->
    <!-- ============================================================== -->
    <!-- Row -->

    <input type="hidden" id="cmp_com_sla" value="{{$company->com_sla}}">

    <div class="row">
        <!-- Column -->
        <div class="col-lg-3 col-xlg-3 col-md-5">
            <div class="card">
                <div class="card-body">
                    <center class="mt-4"> 
                        @if(is_file(public_path('../files/user_photos/Companies/'.$company->com_logo)))
                            <img src="{{ asset('files/user_photos/Companies/'.$company->com_logo)}}" class="rounded-circle" width="100" height="100" id="profile-user-img" />
                        @else
                            <img src="{{ asset('files/user_photos/logo.gif')}}" class="rounded-circle shadow-sm" width="100" height="100" id="profile-user-img" />
                        @endif
                        <a type="button" data-toggle="modal" data-target="#editPicModal"><i class="fa fa-pencil-alt picEdit"></i></a>
                        <h4 class="card-title mt-2" id="comp_name">{{$company->name}}</h4>
                    </center> 
                </div>
                <div>
                    <hr>
                </div>
                <div class="card-body pt-0 pb-0 " id="adrs" >
                    <small class="text-muted  db">Phone</small>
                    <h6> <a href="tel:{{$company->phone}}" id="comp_phone">{{$company->phone}}</a> </h6>
                    
                    <small class="text-muted  db">Email Address</small>
                    <h6> <a href="mailto:{{$company->email}}" id="comp_email"> {{$company->email}}</a></h6>

                    <small class="text-muted  db" >Address</small> <br>

                    <span id="comp_add" class="text-dark">{{$company->address != null ?  $company->address : ''}}</span> 
                    <span id="comp_apprt" class="text-dark">{{$company->apt_address != null ? ','. $company->apt_address : ''}}</span>

                    <span id="comp_strt">{{$company->cmp_city != null ?  $company->cmp_city : ''}}</span> 

                    @if($company->cmp_state != null && $company->cmp_state != '')
                        <span id="comp_state">{{ ', '.$company->cmp_state }}</span>
                    @else
                        <span id="comp_state"></span>
                    @endif

                    <span id="comp_zip">{{$company->cmp_zip != null ? ', '. $company->cmp_zip : ''}}</span>
                    
                    @if($company->cmp_country != null && $company->cmp_country != '')
                        <span id="comp_country">{{ ', ' .$company->cmp_country}}</span>
                    @else
                        <span id="comp_country"></span>
                    @endif

                </div>
                <hr>
            </div>
            <div class="card">
                    <div class="card-body soc-card">
                    <div id="map_2" class="gmaps"></div>
                    <input type="hidden" id="google_api_key">
                    <h2 class="mt-4 font-weight-bold text-dark">Social Links</h2>
                        <div class="d-flex justify-content-center">
                            <a href="{{$company->twitter}}" id="twt" title="Twitter" class="btn btn-circle" target="_blank" style="color: #009efb;font-size:24px">
                                <i class="soc-ico  fab fa-twitter"></i>
                            </a>

                            <a href="{{$company->fb}}" id="fb_icon" title="Facebook" class="btn btn-circle" target="_blank" style="color:#0570E6;font-size:24px">
                                <i class="soc-ico fab fa-facebook"></i>
                            </a>

                            <a href="{{$company->pinterest}}" id="pintrst" title="Pinterest" class="btn btn-circle" target="_blank" style="color:#DF1A26;font-size:24px">
                                <i class="soc-ico  fab fa-pinterest-square"></i>
                            </a>

                            <a href="{{$company->insta}}" id="inst" title="Instagram" class="btn btn-circle" target="_blank" style="color:#e1306c;font-size:24px">
                                <i class="soc-ico  fab fa-instagram"></i>
                            </a>  

                            <a href="{{$company->website}}" id="web" title="Website" class="btn btn-circle" target="_blank" style="font-size:24px">
                                <i class="fas fa-globe"></i>
                            </a>           
                        </div>
                        <div class="row">
                            <span class="text-danger text-center small" style="width:100%" id="social-error"></span>                        
                        </div>
                    </div>
                </div>
        </div>
        <!-- Column -->
        <!-- Column -->
        <div class="col-lg-9 col-xlg-9 col-md-7 ">
            <div class="card">
                <!-- Tabs -->
                <ul class="nav nav-pills custom-pills" id="pills-tab" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" id="pills-timeline-tab" data-toggle="pill" href="#current-month" role="tab" aria-controls="pills-timeline" aria-selected="true">History</a>
                    </li>
                    <!--<li class="nav-item">-->
                    <!--    <a class="nav-link" id="pills-profile-tab" data-toggle="pill" href="#last-month" role="tab" aria-controls="pills-profile" aria-selected="false">Schedule</a>-->
                    <!--</li>-->
                    <!-- <li class="nav-item">
                        <a class="nav-link" id="subscription-profile-tab" data-toggle="pill" href="#subscription" role="tab" aria-controls="pills-profile" aria-selected="false">Subscriptions</a>
                    </li> -->
                    <li class="nav-item">
                        <a class="nav-link" id="orders-profile-tab" data-toggle="pill" href="#orders" role="tab" aria-controls="pills-profile" aria-selected="false">Billing</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="payment-profile-tab" data-toggle="pill" href="#payment" role="tab" aria-controls="pills-profile" aria-selected="false">Payments</a>
                    </li>
                    <!-- <li class="nav-item">
                        <a class="nav-link" id="notifications-profile-tab" data-toggle="pill" href="#notification" role="tab" aria-controls="notifications-profile" aria-selected="false">Notifications</a>
                    </li> -->
                    <li class="nav-item">
                        <a class="nav-link" id="pills-assets-tab" data-toggle="pill" href="#assets" role="tab" aria-controls="pills-assets" aria-selected="false">Assets</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="pills-tickets-tab" data-toggle="pill" href="#tickets" role="tab" aria-controls="pills-tickets" aria-selected="false">Tickets</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="pills-profile-tab" data-toggle="pill" href="#comp_profile" role="tab" aria-controls="pills-profile" aria-selected="false">Profile</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="pills-staff-tab" data-toggle="pill" href="#staff" role="tab" aria-controls="pills-staff" aria-selected="false">Staff</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="notes-profile-tab" data-toggle="pill" href="#ticket_notes" role="tab" aria-controls="pills-profile" aria-selected="false">Notes</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="settings-profile-tab" data-toggle="pill" href="#ticket_settings" role="tab" aria-controls="pills-setting" aria-selected="false">Settings</a>
                    </li>
                </ul>

                <input type="hidden" id="cmp_id" name="id" value="{{$company->id}}"> 
                <!-- Tabs -->
                <div class="tab-content" id="pills-tabContent">
                    
                    <div class="tab-pane fade show active" id="current-month" role="tabpanel"
                        aria-labelledby="pills-timeline-tab">
                        <hr>
                        <div class="card-body">
                            No Data Found.
                        </div>
                    </div>

                    <div class="tab-pane fade" id="orders" role="tabpanel" aria-labelledby="orders-profile-tab">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-12">
                                        <div class="d-flex justify-content-between pt-3  pr-3  pb-2 mt-3">
                                            <h2 class="lead font-weight-bold">01. Orders</h2>
                                        </div>
                                        <div class="table-responsive">
                                            <div id="zero_config_wrapper"
                                                class="dataTables_wrapper container-fluid dt-bootstrap4">

                                                <div class="row">
                                                    <div class="col-sm-12">
                                                        <table id="customer_order_table"
                                                            class="table table-striped table-bordered no-wrap dataTable"
                                                            role="grid" aria-describedby="zero_config_info">
                                                            <thead>
                                                                <tr role="row">
                                                                    <th class="sorting_asc" tabindex="0"
                                                                        aria-controls="zero_config" rowspan="1"
                                                                        colspan="1" aria-sort="ascending"
                                                                        aria-label="Name: activate to sort column descending"
                                                                        style="width: 0px;">Order Number</th>
                                                                    <th class="sorting" tabindex="0"
                                                                        aria-controls="zero_config" rowspan="1"
                                                                        colspan="1"
                                                                        aria-label="Position: activate to sort column ascending"
                                                                        style="width: 0px;">Client Name</th>
                                                                    <th class="sorting" tabindex="0"
                                                                        aria-controls="zero_config" rowspan="1"
                                                                        colspan="1"
                                                                        aria-label="Office: activate to sort column ascending"
                                                                        style="width: 0px;">Order Status</th>
                                                                    <th class="sorting" tabindex="0"
                                                                        aria-controls="zero_config" rowspan="1"
                                                                        colspan="1"
                                                                        aria-label="Age: activate to sort column ascending"
                                                                        style="width: 0px;">Date Created</th>
                                                                    <th class="sorting" tabindex="0"
                                                                        aria-controls="zero_config" rowspan="1"
                                                                        colspan="1"
                                                                        aria-label="Start date: activate to sort column ascending"
                                                                        style="width: 0px;">Total</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>

                                                            </tbody>

                                                        </table>

                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <hr class="mt-5 mb-5">
                                        <div class="d-flex justify-content-between pt-3  pr-3  pb-2 mt-3">
                                            <h2 class="lead font-weight-bold">02. Subscriptions</h2>
                                        </div>
                                        <div class="table-responsive">
                                            <div id="zero_config_wrapper"
                                                class="dataTables_wrapper container-fluid dt-bootstrap4">

                                                <div class="row">
                                                    <div class="col-sm-12">
                                                        <table id="customer_subscription"
                                                            class="table table-striped table-bordered no-wrap dataTable"
                                                            role="grid" aria-describedby="zero_config_info">
                                                            <thead>
                                                                <tr role="row">
                                                                    <th>ID</th>
                                                                    <th>Status</th>
                                                                    <th>Currency</th>
                                                                    <th>Discount Tax</th>
                                                                    <th>Shipping Total</th>
                                                                    <th>Shipping Tax</th>
                                                                    <th>Total</th>
                                                                    <th>Payment Method</th>
                                                                    <th>Payment Method Title</th>
                                                                    <th>Customer Note</th>
                                                                    <th>Created Via</th>
                                                                    <th>Billing Period</th>
                                                                    <th>Start Date</th>
                                                                    <th>Trial End Date</th>
                                                                    <th>Next Payment Date</th>
                                                                    <th>End Date</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>

                                                            </tbody>

                                                        </table>

                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- <div class="tab-pane fade" id="subscription" role="tabpanel"
                        aria-labelledby="subscription-profile-tab">
                        <hr>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-12">
                                    <div class="card">
                                        <div class="card-body">

                                            <div class="table-responsive">
                                                <div id="zero_config_wrapper"
                                                    class="dataTables_wrapper container-fluid dt-bootstrap4">

                                                    <div class="row">
                                                        <div class="col-sm-12">
                                                            <table id="customer_subscription"
                                                                class="table table-striped table-bordered no-wrap dataTable"
                                                                role="grid" aria-describedby="zero_config_info">
                                                                <thead>
                                                                    <tr role="row">
                                                                        <th class="sorting_asc" tabindex="0"
                                                                            aria-controls="zero_config" rowspan="1"
                                                                            colspan="1" aria-sort="ascending"
                                                                            aria-label="Name: activate to sort column descending"
                                                                            style="width: 0px;">Order Name</th>
                                                                        <th class="sorting" tabindex="0"
                                                                            aria-controls="zero_config" rowspan="1"
                                                                            colspan="1"
                                                                            aria-label="Position: activate to sort column ascending"
                                                                            style="width: 0px;">Client Name</th>
                                                                        <th class="sorting" tabindex="0"
                                                                            aria-controls="zero_config" rowspan="1"
                                                                            colspan="1"
                                                                            aria-label="Office: activate to sort column ascending"
                                                                            style="width: 0px;">Order Status</th>
                                                                        <th class="sorting" tabindex="0"
                                                                            aria-controls="zero_config" rowspan="1"
                                                                            colspan="1"
                                                                            aria-label="Age: activate to sort column ascending"
                                                                            style="width: 0px;">Date Created</th>
                                                                        <th class="sorting" tabindex="0"
                                                                            aria-controls="zero_config" rowspan="1"
                                                                            colspan="1"
                                                                            aria-label="Start date: activate to sort column ascending"
                                                                            style="width: 0px;">Total</th>

                                                                </thead>
                                                                <tbody>

                                                                </tbody>

                                                            </table>

                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div> -->

                    <div class="tab-pane fade" id="payment" role="tabpanel" aria-labelledby="payment-profile-tab">

                        <!-- <hr>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-12">

                                    <button type="button" class="btn btn-info mr-auto" data-toggle="modal"
                                        data-target="#Add-new-card" style="float:right;"><i
                                            class="mdi mdi-plus-circle"></i>&nbsp;Add New Card</button>
                                </div>
                            </div>

                            <div class="row mt-3">
                                <div class="col-md-6">

                                    <div class="card" style="border:1px solid black">
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <a><i class="fas fa-trash" style="float:right;"></i></a>
                                                    <a><i class="fas fa-pencil-alt"
                                                            style="float:right;padding-right: 5px;"></i></a>
                                                    <div id="default-star-rating" style="cursor: pointer;">
                                                        <img alt="1" src="../assets/images/rating/star-off.png"
                                                            title="Rate"
                                                            style="float:right;padding-right: 5px;position: relative;bottom: 4px;">
                                                        <input name="score" type="hidden">

                                                    </div>

                                                </div>
                                            </div>
                                            <h1 class="mt-0"><i class="fab fa-cc-visa text-info"></i></h1>
                                            <h3>**** **** **** 2150</h3>
                                            <span class="pull-right">Exp date: 10/16</span>
                                            <span class="font-500">Johnathan Doe</span>

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div> -->
                        <hr>
                        
                        <div class="row col-md-12">
                            
                            <div class="col-md-12">
                                <div class="card">
                                    <div class="card-header">
                                        <h4 class="mb-0">ADD New Credit Card</h4>
                                    </div>
                                    <div class="card-body">
                                        <form id="CardForm" class="CardForm" >
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <input type="text" class="form-control" placeholder="First Name" name="fname" id="fname" value="" autofocus>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <input type="text" class="form-control" placeholder="Last Name" name="lname" id="lname" value="">
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <input type="text" class="form-control" placeholder="Street Address" name="address1" id="address1" value="">
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <input type="text" class="form-control" placeholder="City" name="city" id="city" value="">
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <select class="select2 form-control " id="state" name="state" style="width: 100%; height:36px;"></select>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <input type="text" class="form-control" placeholder="Zip code" name="zip" id="zip" value="">
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        @if ($google_key == 1)
                                                            <input type="text" class="form-control" placeholder="Zip code" name="country" id="country" value="">
                                                        @else
                                                            <select class="form-control" name="country" id="country" onchange="listStates(this.value, 'state', 'cmp_state')">
                                                                @foreach ($countries as $cty)
                                                                    @if (!empty($company->cmp_country) && $cty->name == $company->cmp_country)
                                                                        <option value="{{$cty->name}}" selected>{{$cty->name}}</option>
                                                                    @else
                                                                        <option value="{{$cty->name}}" {{$cty->short_name == 'US' ? 'selected' : ''}}>{{$cty->name}}</option>
                                                                    @endif
                                                                @endforeach
                                                            </select>
                                                        @endif
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
                                    <div id="paymentTokenInfo"></div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="card">
                                    <div class="card-header">
                                        <h4 class="mb-0">Default Billing Address</h4>
                                    </div>
                                    <div class="card-body">
                                        <form id="" class="" >
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <input type="text" class="form-control" placeholder="First Name" name="" id="" value="" autofocus>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <input type="text" class="form-control" placeholder="Last Name" name="" id="" value="">
                                                    </div>
                                                </div>
                                                <div class="col-md-12">
                                                    <div class="form-group">
                                                        <input type="text" class="form-control" placeholder="Company Name" name="" id="" value="">
                                                    </div>
                                                </div>
                                                <div class="col-md-12">
                                                    <div class="form-group">
                                                        <input type="text" class="form-control" placeholder="Street Address" name="payment_billing_address" id="payment_billing_address" value="">
                                                    </div>
                                                </div>
                                                <div class="col-md-12">
                                                    <div class="form-group">
                                                        <input type="text" class="form-control" placeholder="Apartment Address" name="payment_billing_aprt_address" id="payment_billing_aprt_address" value="">
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <label for=""> CIty </label>
                                                    <input type="text" class=" form-control" id="payment_billing_city" name="payment_billing_city"  style="width: 100%; height:36px;">
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="">State</label>
                                                        @if($google_key == 1)
                                                            <input type="text" class=" form-control" id="payment_billing_state" name="payment_billing_state" style="width: 100%; height:36px;">
                                                        @else    
                                                            <select class="select2 form-control" id="payment_billing_state" name="payment_billing_state" style="width: 100%; height:36px;"></select>
                                                        @endif
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="">Zip Code</label>
                                                        <input type="text" class="form-control" placeholder="Zip code" name="payment_billing_zipcode"  id="payment_billing_zipcode" value="">
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="">Country</label>
                                                        @if($google_key == 1)
                                                            <input type="text" class="form-control" id="payment_billing_country" name="payment_billing_country" style="width: 100%; height:36px;">
                                                        @else
                                                            <select class="select2 form-control" id="payment_billing_country" name="payment_billing_country" style="width: 100%; height:36px;" onchange="listStates(this.value, 'payment_billing_state', '')">
                                                                <option value="">Select Country</option>
                                                                @foreach ($countries as $cty)
                                                                    <option value="{{$cty->name}}" {{$cty->short_name == 'US' ? 'selected' : ''}}>{{$cty->name}}</option>
                                                                @endforeach
                                                            </select>
                                                        @endif
                                                    </div>
                                                </div>
                                                <div class="col-md-12 text-right">
                                                <button class="btn btn-success">Save Address</button>

                                                </div>
                                            </div>    
                                        </form>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="card">
                                    <div class="card-header">
                                        <h4 class="mb-0">Accepted Payment Methods</h4>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-4">
                                                <p>Credit Cards</p>
                                            </div>
                                            <div class="col-md-8 crd_icons">
                                                <img src="https://secure.merchantonegateway.com/shared/images/brand-visa.png" alt="">
                                                <img src="https://secure.merchantonegateway.com/shared/images/brand-mastercard.png" alt="">
                                                <img src="https://secure.merchantonegateway.com/shared/images/brand-amex.png" alt="">
                                                <img src="https://secure.merchantonegateway.com/shared/images/brand-discover.png" alt="">
                                                <img src="https://secure.merchantonegateway.com/shared/images/brand-jcb.png" alt="">
                                            </div>
                                            <div class="col-md-4">
                                                <p>Currency's</p>
                                            </div>
                                            <div class="col-md-8">
                                                <p class="currencies"><b>$</b><b>¥</b><b>£</b><b>€</b><b>₩</b></p>
                                            </div>
                                            <div class="col-md-4">
                                                <p class="pt-2">Crypto's</p>
                                            </div>
                                            <div class="col-md-8 crypto">
                                                    <img src="../assets/images/icon/bitcoin.png" alt="" style="width:20%;">
                                                    <img src="../assets/images/icon/Ethiriuim.png" alt="" style="width:10%;">
                                                    <img src="../assets/images/icon/zec.png" alt="">
                                                    <img src="../assets/images/icon/doge.png" alt="">
                                            </div>
                                        </div>
                                    </div>
                                    <div id="paymentTokenInfo"></div>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="card">
                                    <div class="card-header">
                                        <h4 class="mb-0">On File Cards</h4>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-12 text-right">
                                                <button class="btn btn-success">Add New</button>
                                            </div>
                                        </div>
                                        <div class="row mt-3" id="pay-card">
                                            <div class="col-md-6">
                                                <div class="card payCard" style="border:1px solid black">
                                                    <div class="card-body">
                                                        <div class="row">
                                                            <div class="col-md-12 text-right">
                                                                <a href="#" class="btn btn-warning text-white btn-circle" style="padding: 11px;"><i class="far fa-star" aria-hidden="true"></i></a>
                                                                <a href="#" class="btn btn-success btn-circle" style="padding: 11px;"><i class="fas fa-pencil-alt" aria-hidden="true"></i></a>
                                                                <a href="#" class="btn btn-danger btn-circle" style="padding: 11px;"><i class="fas fa-trash" style="" aria-hidden="true"></i></a>
                                                                <!-- <div id="default-star-rating" style="cursor: pointer;">
                                                                    <img alt="1" src="assets/images/rating/star-off.png" title="Rate" style="float:right;padding-right: 5px;position: relative;bottom: 4px;">
                                                                    <input name="score" type="hidden">

                                                                </div>-->

                                                            </div>
                                                        </div>
                                                        <!--<h1 class="mt-0">
                                                        <i class="fab fa-cc-visa text-info" aria-hidden="true"></i></h1>-->
                                                        
                                                        
                                                        
                                                        
                                                        
                                                        <!--<h3>hipercard Ended With 2222</h3>
                                                        <span class="pull-right">Exp date: 1245</span>-->
                                                        
                                                        <h3 class="payCard-number">**** **** ****  2222</h3>
                                                        <p><span class="payCard-text">Muhammad Kashif</span> 
                                                        <span class="payCard-text" style="float:right;"> Exp : 1245</span>
                                                        </p>
                                                        <!--<h4>hipercard ending in 2222<span class="pull-right"> (expires 1245)</span></h4>-->
                                                        <span class="font-500"></span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="card">
                                    <div class="card-header">
                                        <h4 class="mb-0">Crypto Wallet Addresses</h4>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-4">
                                                <div class="row">
                                                    <div class="col-md-6 text-center">
                                                        <img src="../assets/images/icon/bitcoin.png" alt="" style="width:70%;">
                                                        <p>Coin Name</p>
                                                    </div>
                                                    <div class="col-md-6"></div>
                                                    <div class="col-md-6 text-center">
                                                        <img src="../assets/images/icon/bitcoin.png" alt="" style="width:70%;">
                                                        <p>Coin Name</p>
                                                    </div>
                                                    <div class="col-md-6 "></div>
                                                    <div class="col-md-6 text-center">
                                                        <img src="../assets/images/icon/bitcoin.png" alt="" style="width:70%;">
                                                        <p>Coin Name</p>
                                                    </div>
                                                    <div class="col-md-6"></div>
                                                </div>
                                            </div>
                                            <div class="col-md-8">
                                                <div class="row">
                                                    <div class="col-md-6 " style="border-left:1px solid #54667a;">
                                                        <label for="">CUSTOM TERMS/LABELS</label>
                                                        <textarea name="" id="" cols="30" rows="10" class="form-control"></textarea>
                                                    </div>
                                                    <div class="col-md-6" style="border-left:1px solid #54667a;">
                                                        <label for="">CUSTOM TERMS/LABELS</label>
                                                        <textarea name="" id="" cols="30" rows="10" class="form-control"></textarea>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>

                    <!-- <div class="tab-pane fade" id="notification" role="tabpanel"
                        aria-labelledby="notifications-profile-tab">
                        <hr>
                        <div class="card-body">
                            <div class="row">

                            </div>
                        </div>
                    </div> -->

                    <div class="tab-pane fade" id="assets" role="tabpanel" aria-labelledby="pills-assets-tab">
                        <div class="card-body">
                            {{-- <div class="row">
                                <div class="col-md-2 col-lg-2 col-xlg-2">
                                    <div class="card card-hover">
                                        <div class="box p-2 rounded bg-danger text-center">
                                            <h1 class="font-weight-light text-white">0</h1>
                                            <h6 class="text-white">Issues</h6>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-2 col-lg-2 col-xlg-2">
                                    <div class="card card-hover">
                                        <div class="box p-2 rounded bg-success text-center">
                                            <h1 class="font-weight-light text-white">10</h1>
                                            <h6 class="text-white">Assets</h6>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4 col-lg-4 col-xlg-4">
                                    <label for="example-search-input">Search </label>
                                    <input class="form-control" type="text" id="email" name="email" required>
                                </div>
                                <div class="col-md-4 col-lg-4 col-xlg-4" style="padding-top:25px">
                                    <button type="button" class="btn btn-info" data-toggle="modal" data-target="#assetModal" style="float:right;margin-bottom:5px;top: -35px;position: relative;"><i class="fas fa-plus"></i>&nbsp;Add Asset</button>
                                    <button type="button" class="btn btn-info" data-toggle="modal" data-target="#assetCatModal" style="float:right;top: -35px;position: relative;"><i class="fas fa-plus"></i>&nbsp;Add Asset Category</button>
                                </div>
                            </div> --}}
                            <div class="row">
                            
                                <div class="col-sm-12">
                                    <div id="accordion" class="custom-accordion mb-4">
                                        <div class="card">
                                            <div class="card-header" id="headingOne">
                                                <h5 class="m-0">
                                                    <a class="custom-accordion-title d-flex align-items-center pt-2 pb-2 collapsed" data-toggle="collapse" href="#collapseOne" aria-expanded="false" aria-controls="collapseOne">
                                                        Add Asset Template <span class="ml-auto"><i class="mdi mdi-chevron-down accordion-arrow"></i></span>
                                                    </a>
                                                </h5>
                                            </div>
                                            <div id="collapseOne" class="collapse" aria-labelledby="headingOne" data-parent="#accordion" style="">
                                                <div class="card-body">
                                                    <div class="container-fluid">
                                                        <div class="card">
                                                            <div class="row p-3">
                                                                <div class="col-md-12">
                                                                    <div class="form-group">
                                                                        <label for="tempTitle">Template Title</label>
                                                                        <input class="form-control" type="text" id="tempTitle" required="" placeholder="Title">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="row">
                                                            <div class="col-md-3">
                                                                <div class="card p-3">
                                                                    <a class="buttonPush" href="javascript:fieldAdd('text')">
                                                                        <div class="card border-cyan card-hover mb-2">
                                                                            <div class="box p-2 rounded">
                                                                                <h6 class="text-cyan mb-0"><i class="fas fa-edit pr-2"></i> Input Field</h6>
                                                                            </div>
                                                                        </div>
                                                                    </a>
                                                                    <a class="buttonPush" href="javascript:fieldAdd('phone')">
                                                                        <div class="card border-cyan card-hover mb-2">
                                                                            <div class="box p-2 rounded">
                                                                                <h6 class="text-cyan mb-0"><i class="fas fa-phone pr-2"></i> Phone Number</h6>
                                                                            </div>
                                                                        </div>
                                                                    </a>
                                                                    <a class="buttonPush" href="javascript:fieldAdd('email')" >
                                                                        <div class="card border-cyan card-hover mb-2">
                                                                            <div class="box p-2 rounded">
                                                                                <h6 class="text-cyan mb-0"><i class="fas fa-envelope pr-2"></i> Email</h6>
                                                                            </div>
                                                                        </div>
                                                                    </a>
                                                                    <a href="javascript:fieldAdd('textbox')"  class="buttonPush">
                                                                        <div class="card border-cyan card-hover mb-2">
                                                                            <div class="box p-2 rounded">
                                                                                <h6 class="text-cyan"><i class="fas fa-indent pr2"></i> Text Area</h6>
                                                                            </div>
                                                                        </div>
                                                                    </a>
                                                                    <a class="buttonPush" href="javascript:fieldAdd('selectbox')">
                                                                        <div class="card border-cyan card-hover mb-2">
                                                                            <div class="box p-2 rounded ">
                                                                                <h6 class="text-cyan"><i class="fas fa-chevron-circle-down pr-2"></i> Select</h6>
                                                                            </div>
                                                                        </div>
                                                                    </a>
                                                                    <a class="buttonPush" href="javascript:fieldAdd('password')" >
                                                                        <div class="card border-cyan card-hover mb-2">
                                                                            <div class="box p-2 rounded">
                                                                                <h6 class="text-cyan"><i class="fas fa-key pr-2"></i>Password</h6>
                                                                            </div>
                                                                        </div>
                                                                    </a>
                                                                    <a class="buttonPush" href="javascript:fieldAdd('ipv4')" >
                                                                        <div class="card border-cyan card-hover mb-2">
                                                                            <div class="box p-2 rounded">
                                                                                <h6 class="text-cyan"><i class="fas fa-qrcode pr-2"></i>IPv4 </h6>
                                                                            </div>
                                                                        </div>
                                                                    </a>
                                                                    <a class="buttonPush" href="javascript:fieldAdd('url')" >
                                                                        <div class="card border-cyan card-hover mb-2">
                                                                            <div class="box p-2 rounded">
                                                                                <!-- <h5 class="font-weight-light text-cyan"></h5> -->
                                                                                <h6 class="text-cyan"><i class="fas fa-laptop-code pr-2"></i>URL </h6>
                                                                            </div>
                                                                        </div>
                                                                    </a>
                                                                    <a class="buttonPush" href="javascript:fieldAdd('address')" >
                                                                        <div class="card border-cyan card-hover mb-2">
                                                                            <div class="box p-2 rounded">
                                                                                <!-- <h5 class="font-weight-light text-cyan"></h5> -->
                                                                                <h6 class="text-cyan"><i class="fas fa-map-marked-alt pr-2"></i>Address </h6>
                                                                            </div>
                                                                        </div>
                                                                    </a>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-9">
                                                                <div class="card">
                                                                    <div class="m-3">
                                                                        <button type="button" class="btn btn-success float-right " onclick="saveTemplate()"> Save Template </button>
                                                                        <button type="button" class="btn btn-info float-right mr-2" onclick=""> Preview </button>
                                                                    </div>
                                                                </div>
                                                                <div class="card">
                                                                    <div class="row p-3">
                                                                        <div class="col-md-12 pt-3 ">
                                                                            <div class="head text-center ">
                                                                                <h4> Please Insert a Field Here from Insert Field Button </h4>
                                                                            </div>
                                                                            <div id="cardycard">
                                                                            </div>
                                                                            <div class="tail" id="card-colors" style="display:;">
                                                                                <div class="row connectedSortable border" id="sortable-row-start" style="min-height:10px; display: none;">
                                                                                    <div class="appends d-none"></div>
                                                                                </div>
                                                                                <div class="row connectedSortable border" id="sortable-row-last" style="min-height:10px; display: none;">
                                                                                    <div class="appends d-none"></div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div> <!-- end card-->
                        
                                        <div class="card mb-0">
                                            <div class="card-header" id="headingTwo">
                                                <h5 class="m-0">
                                                    <a class="custom-accordion-title collapsed d-flex align-items-center pt-2 pb-2" data-toggle="collapse" href="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                                                        Add Asset <span class="ml-auto"><i class="mdi mdi-chevron-down accordion-arrow"></i></span>
                                                    </a>
                                                </h5>
                                            </div>
                                            <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordion">
                                                <div class="card-body">
                                                    <form class="form-horizontal" id="save_asset_form" enctype="multipart/form-data"
                                                        action="{{asset('/save-asset')}}" method="post">
                                                        <div class="form-row">
                                                            <div class="col-md-12 form-group">
                                                                <div class="form-group">
                                                                    <label>Asset Template</label>
                                                                    <select class="select form-control" onchange="getFields(this.value)" id="form_id" name="form_id" required></select>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="form-row" id="templateTitle" style="display:none;">
                                                            <div class="col-md-12 form-group">
                                                                <div class="form-group">
                                                                    <label>Asset Title</label>
                                                                        <input type="text" name="asset_title" id="asset_title" class="asset_title form-control">
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="row" id="form-fields"></div>
                                        
                                                        <button type="submit" class="btn btn-success mt-3" style="float:right;">Save</button>
                                                    </form>
                                                </div>
                                            </div>
                                        </div> <!-- end card-->
                                    </div>
                                </div>
                                <div class="col-lg-12 col-md-12">
                                    <div class="card">
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-lg-6 col-md-6">
                                                    <h4 class="card-title">Assets</h4>
                                                </div>
                                                <!-- <div class="col-lg-3 col-md-3">
                                                    <button type="button" class="btn btn-success" onclick="ShowAssetModel()"
                                                        style="float:right;"><i class="mdi mdi-plus-circle" ></i>&nbsp;Add
                                                        Asset
                                                    </button>
                                                </div>
                                                <div class="col-lg-3 col-md-3">
                                                    <button type="button" class="btn btn-success" onclick="ShowAssetModel()"
                                                        style="float:right;"><i class="mdi mdi-plus-circle"></i>&nbsp;Add
                                                        Asset Template
                                                    </button>
                                                </div> -->
                                                
                                            </div>
                                            <br>
                        
                        
                                            <!-- <div class="row">
                                                <div class="col-md-12" style="text-align:right;">
                                                    <select class="multiple-select mt-2 mb-2" name="as_select" id="as_select" placeholder="Show/Hide" multiple="multiple" selected="selected">
                                                        <option value="0">Sr#</option>
                                                        <option value="1">AssetID</option>
                                                        <option value="2">Template</option>
                                                        <option value="3">Customer</option>
                                                        <option value="4">Company</option>
                                                        <option value="5">Projects</option>
                                                        <option value="6">Actions</option>
                                                    </select>
                                                </div>
                                            </div> -->
                                            
                                            <div class="table-responsive">
                                                <table id="asset-table-list"
                                                    class="table table-striped table-bordered w-100 no-wrap asset-table-list">
                                                    <thead>
                                                        <tr>
                                                            <th><div class="text-center"><input type="checkbox" id="checkAll" name="assets[]" value="0"></div></th>
                                                            <th>ID</th>
                                                            <th>Asset Title</th>
                                                            <th>Template Name</th>
                                                            <th>Actions</th>
                                                        </tr>
                                                    </thead>
                        
                                                </table>
                        
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="tab-pane fade" id="tickets" role="tabpanel" aria-labelledby="pills-tickets-tab">
                        {{-- <div class="card-body">
                            <table id="ticket_table" style="table-layout: fixed"
                                class="table table-striped table-bordered display no-wrap">
                                <thead>
                                    <tr>
                                        <th><input type="checkbox" name="select_all[]" id="select-all"></th>
                                        <th>Status</th>
                                        <th>Subject</th>
                                        <th>Ticket ID#</th>
                                        <th>Priority</th>
                                        <th>Customer Name</th>
                                        <th>Last Replier</th>
                                        <th>Replies</th>
                                        <th>Last Activity</th>
                                        <th>Reply Due</th>
                                        <th>Resolution Due</th>
                                        <th>Assigned Tech</th>
                                        <th>Department</th>
                                        <th>Creation Date</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($tickets as $item)
                                    <tr>
                                        <td><input type="checkbox" name="select_all[]" value="{{$item->id}}"></td>
                                        <td>{{$item->status_name}}</td>
                                        <td><a href="{{asset("/ticket-details").'/'.$item->id}}">{{$item->subject}}</a>
                                        </td>
                                        <td>{{$item->id}}</td>
                                        <td>{{$item->priority_name}}</td>
                                        <td>---</td>
                                        <td>---</td>
                                        <td>---</td>
                                        <td>---</td>
                                        <td>---</td>
                                        <td>---</td>
                                        <td>{{$item->tech_name}}</td>
                                        <td>{{$item->department_name}}</td>
                                        <td>{{$item->created_at}}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div> --}}
                    </div>
                    
                    <div class="tab-pane fade" id="comp_profile" role="tabpanel" aria-labelledby="pills-profile-tab">
                        <div class="card-body">
                            <form id="update_company" onsubmit="return false">

                                <div class="form-row">
                                    <div class="col-md-12 form-group">
                                        <label>Company Name</label>
                                        <input type="text" id="name" name="name" placeholder="Johnathan Doe" value="{{$company->name}}" class="form-control">
                                        <span class="text-danger" id="err2"></span>
                                    </div>
                                </div>

                                <div class="form-row">
                                    <div class="col-md-6 form-group">
                                        <label>Owner First Name :</label>
                                        <input type="text" id="poc_first_name" name="poc_first_name" value="{{$company->poc_first_name}}" class="form-control">
                                        <span class="text-danger" id="err"></span>
                                    </div>
                                    <div class="col-md-6 form-group">
                                        <label>Owner Last Name :</label>
                                        <input type="text" id="poc_last_name" name="poc_last_name" value="{{$company->poc_last_name}}" class="form-control">
                                        <span class="text-danger" id="err1"></span>
                                    </div>
                                </div>

                                <div class="form-row">
                                    <div class="col-md-6 form-group">
                                        <label>Phone No</label>
                                        <input type="text" id="phone" name="phone" value="{{$company->phone}}" class="form-control">
                                        <span class="text-danger small" id="err4"></span>
                                    </div>
                                    <div class="col-md-6 form-group">
                                        <label for="profile_email">Email</label>
                                        <input type="text" id="email" class="form-control" name="email" value="{{$company->email}}">
                                        <span class="text-danger" id="err3"></span>
                                    </div>
                                </div>

                                @if($is_default != 1)
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="custom-control custom-checkbox mr-sm-2">
                                            <input type="checkbox" class="custom-control-input" name="set_default" id="set_default">
                                            <label class="custom-control-label" for="set_default">Set as Default</label>
                                        </div>
                                    </div>
                                </div>
                                @endif
                                 
                                <div class="form-row">
                                    <h3 class="col-md-12 mt-4 font-weight-bold text-dark">Company Address</h3>

                                    <div class="col-md-12 form-group">
                                        <label>Street Address</label>
                                        <!-- <a type="button" data-toggle="modal" data-target="#Address-Book"  class="float-right" style="color:#009efb;">Address Book</a> -->
                                        <div class="row">
                                            <div class="col-md-6">
                                                <input type="text" name="address" class=" form-control" value="{{$company->address}}" id="address" placeholder="House number and street name">
                                            </div>
                                            <div class="col-md-6">
                                                <input type="text" name="apt_address" class=" form-control" value="{{$company->apt_address}}" id="apt_address" placeholder="Apartment, suit, unit etc. (optional)">
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-row">
                                    <div class="col-md-3 form-group">
                                        <label>City</label>
                                        <input type="text" name="cmp_city" id="cmp_city" value="{{$company->cmp_city}}" class="form-control form-control-line">
                                        <span class="text-danger" id="err7"></span>
                                    </div>
                                    <div class="col-md-3 form-group">
                                        <label>State</label>
                                        @if($google_key == 1)
                                            <input type="text" class=" form-control" id="cmp_state" name="cmp_state"
                                                value="{{$company->cmp_state}}" style="width: 100%; height:36px;">
                                        @else    
                                            <select class="select2 form-control" id="cmp_state" name="cmp_state" style="width: 100%; height:36px;"></select>
                                        @endif                                        
                                        <span class="text-danger" id="err6"></span>
                                    </div>
                                    <div class="col-md-3 form-group">
                                        <label>Zip Code</label>
                                        <input type="text" name="cmp_zip" id="cmp_zip" value="{{$company->cmp_zip}}" class="form-control form-control-line">
                                        <span class="text-danger" id="err8"></span>
                                    </div>
                                    <div class="col-md-3 form-group">
                                        <label>Country</label>
                                        @if($google_key == 1)
                                            <input type="text" class=" form-control " id="cmp_country" name="cmp_country" value="{{$company->cmp_country}}" style="width: 100%; height:36px;">
                                        @else
                                            <select class="select2 form-control " id="cmp_country" name="cmp_country" style="width: 100%; height:36px;" onchange="listStates(this.value, 'cmp_state', 'cmp_state')">
                                                <option value="">Select Country</option>
                                                @foreach ($countries as $cty)
                                                    @if(!empty($company->cmp_country) && $company->cmp_country == $cty->name)
                                                        <option value="{{$cty->name}}" selected>{{$cty->name}}</option>
                                                    @else
                                                        <option value="{{$cty->name}}" {{$cty->short_name == 'US' ? 'selected' : ''}}>{{$cty->name}}</option>
                                                    @endif
                                                @endforeach
                                            </select>
                                        @endif                            
                                        <span class="text-danger" id="err5"></span>
                                    </div>
                                </div>
                                
                                <div class="form-row">
                                    <h3 class="mt-4 col-md-12 font-weight-bold text-dark">Social Info</h3>
                                    <div class="col-md-6 form-group">
                                        <label class="col-md-12">Facebook</label>
                                        <input type="text" id="fb" name="fb" placeholder="https://facebook.com/username" value="{{$company->fb}}" class="form-control">
                                    </div>
                                    <div class="col-md-6 form-group">
                                        <label>Pinterest</label>
                                        <input type="text" name="pinterest" value="{{$company->pinterest}}"  placeholder="https://pinterest.com/username" class="form-control" id="update_pinterest">
                                    </div>

                                    <div class="col-md-6 form-group">
                                        <label>Twitter</label>
                                        <input type="text" id="twitter" name="twitter" value="{{$company->twitter}}" placeholder="https://twitter.com/username" class="form-control">
                                    </div>
                                    <div class="col-md-6 form-group">
                                        <label>Instagram</label>
                                        <input type="text" id="insta" name="insta" value="{{$company->insta}}" placeholder="https://instagram.com/username" class="form-control">
                                    </div>
                                </div>
                                
                                <div class="form-row">
                                    <div class="col-md-12 form-group">
                                        <label class="small">Website</label>
                                        <input type="text" name="website" value="{{$company->website}}" class="form-control"  placeholder="https://www.yourwebsite.com" id="update_website">
                                    </div>
                                </div>

                                <div class="form-row">
                                    <div class="col-md-12 form-group">
                                        <label>Notes</label>
                                        <textarea class="form-control" name="notes" cols="30" rows="5">{{$company->cmp_ship_add}}</textarea>
                                    </div>
                                </div>
                               
                                <div class="row">
                                    <div class="col-sm-12 text-right">
                                        <button type="submit" class="btn btn-success rounded btn-sm"><i class="fas fa-save"></i> Update Profile</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>

                    <div class="tab-pane fade" id="staff" role="tabpanel" aria-labelledby="pills-staff-tab">
                        <hr>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-12">
                                    <div class="card" style="box-shadow: none;">
                                        <div class="card-body">
                                                @if($company->is_default == 1)
                                                    <button type="button" class="btn btn-info btn-sm rounded" data-toggle="modal" data-target="#add_comp_user_model" style="float:right;top: -35px;position: relative;">
                                                        <i class="fas fa-plus"></i>&nbsp;Add User</button>
                                                @else
                                                    <button type="button" class="btn btn-info btn-sm rounded" data-toggle="modal" data-target="#add_staff_model" style="float:right;top: -35px;position: relative;">
                                                        <i class="fas fa-plus"></i>&nbsp;Add Customer</button>
                                                @endif
                                                <div class="table-responsive">
                                                    <table id="staff_table" class="table table-striped table-hover table-bordered" style="width:100%">
                                                        <thead>
                                                            <tr>
                                                                <th>#</th>
                                                                <th>Name</th>
                                                                <th>Email</th>
                                                                <th>Phone</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @foreach($company_staff as $staff)
                                                            <tr>
                                                                <td>{{$loop->iteration}}</td>
                                                                <td> <a href="{{url('customer-profile')}}/{{$staff->id}}">{{$staff->first_name}} {{$staff->last_name}}</a> </td>
                                                                <td> <a href="mailto:{{$staff->email}}"> {{$staff->email}} </a></td>
                                                                <td> <a href="tel:{{$staff->phone}}">{{$staff->phone}}</a> </td>
                                                            </tr>
                                                            @endforeach                                                    
                                                        </tbody>
                                                    </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <div class="card">
                                    <div class="card-body" style="height: 500px;overflow-y: scroll;">
                                        <h4 class="card-title">Activity Log</h4>
                                        @php
                                            $formt = "m-d-Y h:i:s a";
                                            if(!empty($date_format)) {
                                                $formt = $date_format;
                                                $formt = str_replace("YYYY", "Y", $formt);
                                                $formt = str_replace("MM", "m", $formt);
                                                $formt = str_replace("DD", "d", $formt);
                                            }    
                                        @endphp
                                        @foreach($activity_logs as $log)
                                        <ul style="margin-bottom:0rem !important">
                                            <li>{{$log->action_perform}} at {{date_format(date_create($log->created_at), $formt)}}.</li>
                                        </ul>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="tab-pane fade" id="ticket_notes" role="tabpanel" aria-labelledby="notes-profile-tab">
                        <hr>
                        <div class="card-body">
                            No Data Found.
                        </div>
                    </div>

                    <div class="tab-pane fade" id="ticket_settings" role="tabpanel" aria-labelledby="pills-setting-tab">
                        <div class="card-body">
                            <form id="update_company" onsubmit="return false">
                                <h4>Assign / Change SLAs</h4>
                                <div class="form-row">
                                    <div class="col-md-12">
                                        <label>SLA</label>
                                        <select class="select2 form-control custom-select" id="com_sla" name="tags" multiple="multiple" style="height: 36px;width: 100%;">
                                        @foreach($sla_plans as $sla)
                                            <option value="{{$sla->id}}" {{$sla->id == 2,4 ? "selected" : ''}} >{{$sla->title}}</option>
                                        @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="row mt-3">
                                    <div class="col-md-6">
                                        <p>
                                            Lorem ipsum dolor sit amet consectetur adipisicing elit.
                                             Assumenda deleniti exercitationem, repellendus corrupti
                                              sapiente id, pariatur dolor sequi omnis et, blanditiis
                                               eveniet vero aspernatur reiciendis dicta recusandae e
                                               um magni! In praesentium explicabo reiciendis omnis d
                                               istinctio.
                                        </p>
                                    </div>
                                    <div class="col-md-6">
                                        <label>Domain Names</label>
                                        <select class="select2 form-control custom-select" id="domain" name="domain" multiple="multiple" style="height: 36px;width: 100%;"></select>
                                        <div class="row mt-3 ml-2">
                                            <div class="custom-control custom-radio">
                                                <!-- <label for="customRadio">From</label> -->
                                                <input type="radio" id="today" onclick="filterData('today')" name="customRadio" class="custom-control-input" checked="">
                                                <label class="custom-control-label" for="today">Today</label>
                                            </div>
                                            <div class="custom-control custom-radio ml-3">
                                                <!-- <label for="customRadio">To</label> -->
                                                <input type="radio" id="date_range" onclick="filterData('date_range')" name="customRadio" class="custom-control-input">
                                                <label class="custom-control-label" for="date_range">Date Range</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <button class="btn btn-success rounded btn-sm" onclick="saveCompanySLA()">Save</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Column -->
    </div>
    <!--Add card model-->
    <div class="modal fade" id="Add-new-card" tabindex="-1" role="dialog"  data-backdrop="static" aria-labelledby="myLargeModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header d-flex align-items-center">
                    <h4 class="modal-title" id="myLargeModalLabel">New Card Information</h4>
                    <button type="button" class="close ml-auto" data-dismiss="modal" aria-hidden="true">×</button>
                </div>
                <div class="modal-body">
                    ...
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal end -->

    <!--order number model-->
    <div class="modal fade" id="show-order" tabindex="-1" role="dialog"  data-backdrop="static" aria-labelledby="myLargeModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header d-flex align-items-center">

                    <h4 class="modal-title" id="myLargeModalLabel">Order 1234</h4>
                    <div class="alert alert-success" role="alert" style="position:relative;left:477px;margin:unset;">
                        <i class="dripicons-checkmark "></i><strong>Processing</strong>
                    </div>
                    <button type="button" class="close ml-auto" data-dismiss="modal" aria-hidden="true">×</button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-3">
                            <h3>Billing Details</h3>
                            <p>Eugenia karahalias 743 sandra ave west islip, NY 11795</p>
                        </div>
                        <div class="col-md-6">

                        </div>
                        <div class="col-md-3">
                            <p>Subscription ####</p>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-3">
                            <h3>Email</h3>
                            <p>Eve123@gmail.com</p>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-3">
                            <h3>Phone</h3>
                            <p>514-565-3456</p>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-3">
                            <h3>Payment via</h3>
                            <p>Credit card (123456)</p>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <h3>Product</h3>

                        </div>
                        <div class="col-md-2">
                            <h3>Quality</h3>

                        </div>
                        <div class="col-md-2">
                            <h3>Tax</h3>

                        </div>
                        <div class="col-md-2">
                            <h3>Total</h3>

                        </div>

                    </div>
                    <hr>

                    <div class="row">
                        <div class="col-md-6">
                            <p>Huntress Cyber security -workstation
                                its huntress wo</p>

                        </div>
                        <div class="col-md-2">
                            <h3>2</h3>

                        </div>
                        <div class="col-md-2">
                            <h3>$0.00</h3>

                        </div>
                        <div class="col-md-2">
                            <h3>$6.98</h3>

                        </div>

                    </div>
                    <hr>
                    <button class="btn btn-success" style="float:right">Edit</button>
                    <button class="btn btn-info" style="float:right;margin-right:2px;">Duplicate</button>
                    <button class="btn btn-primary">Update Profile</button>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal end -->

    <!--staff add model-->
    <div class="modal fade" id="add_staff_model" role="dialog"  data-backdrop="static" aria-labelledby="myLargeModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header d-flex align-items-center">
                    <h4 class="modal-title" id="edit-company">Add Staff</h4>
                    <button type="button" class="close ml-auto" data-dismiss="modal" aria-hidden="true">×</button>
                </div>
                <div class="modal-body">
                <form class="companyUser" id="companyStaffForm" autocomplete="off" enctype="multipart/form-data" method="POST" action="{{url('save-company-staff')}}">
                    <input type="hidden" name="company_id" value="{{$company->id}}">
                    <input type="hidden" name="type" value="customer">
                    <div class="form-row">
                        <div class="col-md-6 form-group">
                            <label for="first_name" class="small">First Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="staff_first_name" id="staff_first_name" placeholder="Your first name" required>
                            <span class="text-danger name_error small"></span>
                        </div>
                        <div class="col-md-6 form-group">
                            <label for="last_name" class="small">Last Name<span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="staff_last_name" id="staff_last_name" placeholder="Your Last name" required>
                            <span class="text-danger last_error small"></span>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="col-md-6 form-group">
                            <label for="phone" class="small">Phone</label>
                            <input type="text" class="form-control" name="staff_phone" id="staff_phone" placeholder="eg. 3334445555">
                            <span class="text-danger small" id="phone_error"></span>
                        </div>
                        <div class="col-md-6 form-group">
                            <label for="email" class="small">Email Address<span class="text-danger">*</span></label>
                            <input type="email" class="form-control small" name="staff_email" id="staff_email" placeholder="youe email address" required>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-success btn-sm rounded float-right mr-2 savingBtn">
                        <i class="fas fa-check-circle" aria-hidden="true"></i> Save</button>
                    <button type="button" disabled="" style="display:none" class="btn btn-success btn-sm rounded float-right mr-2 processingBtn"> 
                    <i class="fas fa-circle-notch fa-spin" aria-hidden="true"></i> Processing </button>                    
                </form>
                </div>
            </div>
        </div>
    </div>

     <!--staff user add model-->
     <div class="modal fade" id="add_comp_user_model" role="dialog"  data-backdrop="static" aria-labelledby="myLargeModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header d-flex align-items-center">
                    <h4 class="modal-title" id="edit-company">Add User</h4>
                    <button type="button" class="close ml-auto" data-dismiss="modal" aria-hidden="true">×</button>
                </div>
                <div class="modal-body">
                <form class="companyUser" id="companyStaffForm" autocomplete="off" enctype="multipart/form-data" method="POST" action="{{url('save-company-staff')}}">
                    <input type="hidden" name="company_id" value="{{$company->id}}">
                    <input type="hidden" name="type" value="user">
                    <div class="form-row">
                        <div class="col-md-6 form-group">
                            <label for="first_name" class="small">Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="staff_name" id="staff_name" placeholder="Your name" required>
                            <span class="text-danger name_error small"></span>
                        </div>
                        <div class="col-md-6 form-group">
                            <label for="email" class="small">Email Address<span class="text-danger">*</span></label>
                            <input type="email" class="form-control small" name="staff_email" id="staff_email" placeholder="your email address" required>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="col-md-6 form-group">
                            <label for="phone" class="small">Phone</label>
                            <input type="text" class="form-control" name="staff_phone" id="staff_phone" placeholder="eg. 3334445555">
                            <span class="text-danger small" id="phone_error"></span>
                        </div>
                        <div class="col-md-6 form-group">
                            <label for="password" class="small"> Password <span class="text-danger">*</span></label>
                            <input type="password" class="form-control small" name="staff_password" id="staff_email" placeholder="your Password" required>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-success btn-sm rounded float-right mr-2 savingBtn">
                        <i class="fas fa-check-circle" aria-hidden="true"></i> Save</button>
                    <button type="button" disabled="" style="display:none" class="btn btn-success btn-sm rounded float-right mr-2 processingBtn">
                    <i class="fas fa-circle-notch fa-spin" aria-hidden="true"></i> Processing </button>                    
                </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="editPicModal" tabindex="-1" aria-labelledby="editPicModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editPicModalLabel">Company Logo</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                    <div class="text-center" id="prof-img ">
                        @if(is_file(public_path('../files/user_photos/Companies/'.$company->com_logo)))
                            <img src="{{ asset('files/user_photos/Companies/'.$company->com_logo)}}" class="rounded-circle" width="100" height="100" id="profile-user-img" />
                        @else
                            <img src="{{ asset('files/user_photos/logo.gif')}}" class="rounded-circle shadow-sm" width="100" height="100" id="profile-user-img" />
                        @endif
                        <!-- @if($company->com_logo != NULL)
                        <img src="../files/user_photos/Companies/{{$company->com_logo}}" class="rounded-circle" width="100" height="100" id="ppNew" />
                        @endif 
                        @if($company->com_logo == NULL)
                        <img src="../assets/images/users/5.png" class="rounded-circle" width="150" id="ppNew" />
                        @endif -->
                    </div>
                    <form class="mt-4" id="upload_company_img">
                        <div class="input-group">
                            <div class="custom-file">
                                <input type="hidden" name="company_id" id="company_id" value="{{$company->id}}">
                                <input type="file" name="profile_img" class="custom-file-input" id="customFilePP" accept="image/*" >
                                <label class="custom-file-label" for="customFilePP">Choose file</label>
                            </div>
                        </div>
                        <div class="text-right mt-3">
                            <button type="submit" class="btn btn-success">Save changes</button>
                        </div>
                        
                    </form>
            </div>
            </div>
        </div>
    </div>

    <!-- Create Template Title modal content -->
    <div id="fields-modal" class="modal fade" tabindex="-1" role="dialog"  data-backdrop="static" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-info p-2">
                    <span>
                        <h4 style="color:#fff !important;" id="headinglabel"> Select Setting </h4>
                    </span>
                </div>
                <div class="modal-body">
                    <form id="fields-form" class="pl-3 pr-3">
                        <div class="form-group">
                            <label for="select">Label</label>
                            <input class="form-control" type="text" id="lbl" required>
                        </div>
                        <div class="form-group">
                            <label>Placeholder</label>
                            <input class="form-control" type="text" id="ph">
                        </div>
                        <div class="form-group">
                            <label>Description</label>
                            <input class="form-control" type="text" id="desc">
                        </div>
                        <div id="dyn-data"></div>
                        <div class="form-group">
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" class="custom-control-input" id="is_required">
                                <label class="custom-control-label" for="is_required">Set Required </label>
                            </div>
                        </div>
                        <div class="form-group text-center">
                            <button class="btn btn-rounded btn-primary" type="submit">Save</button>
                            <button class="btn btn-rounded btn-secondary" type="button" data-dismiss="modal">Close</button>
                        </div>
                    </form>

                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->
</div>

<!-- update asset modal -->
<div id="update_asset_modal" class="modal fade" tabindex="-1" role="dialog"  data-backdrop="static" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-info p-2">
                <span>
                    <h4 style="color:#fff !important;" id="headinglabel"> Update - <span id="modal-title"></span>  </h4>
                </span>
            </div>
            <div class="modal-body">
                <form id="update_assets_form" enctype="multipart/form-data" onsubmit="return false">
                    <div class="form-group">
                        <label for="select">Asset Title</label> <span class="text-danger">*</span>
                        <input class="form-control" type="text" id="up_asset_title" required>
                        <input class="form-control" type="hidden" id="asset_title_id" required>
                        
                    </div>
                    <div class="input_fields"></div>
                    <div class="address_fields"></div>
                    <div class="form-group text-right mt-3">
                        <button class="btn btn-rounded btn-success" onclick="updateAssets()" id="sve" type="submit">Save</button>
                        <button class="btn btn-rounded btn-danger" type="button" data-dismiss="modal">Close</button>
                    </div>
                </form>

                <div class="loader_container">
                    <div class="loader"></div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Notes Modal -->
<div class="modal fade" id="notes_manager_modal" tabindex="-1" role="dialog"  data-backdrop="static" aria-labelledby="notesLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header d-flex align-items-center">
                <h4 class="modal-title" id="notesLargeModalLabel">Notes</h4>
                <button type="button" class="close ml-auto" data-dismiss="modal" aria-hidden="true">×</button>
            </div>
            <div class="modal-body">
                <form id="save_ticket_note" action="{{asset('save-ticket-note')}}" method="post">
                    <input type="text" id="note-id" style="display: none;">
                    <div class="row">
                        <div class="col-12 d-flex py-2">
                            <label for="">Notes</label>
                            <div class="ml-4">
                                <span class="fas fa-square mr-2" style="font-size: 26px; color: rgb(255, 230, 177); cursor: pointer;" onclick="selectColor('rgb(255, 230, 177)')"></span>
                                <span class="fas fa-square mr-2" style="font-size: 26px; color: rgb(218, 125, 179); cursor: pointer;" onclick="selectColor('rgb(218, 125, 179)')"></span>
                                <span class="fas fa-square mr-2" style="font-size: 26px; color: rgb(195, 148, 255); cursor: pointer;" onclick="selectColor('rgb(195, 148, 255)')"></span>
                                <span class="fas fa-square mr-2" style="font-size: 26px; color: rgb(151, 235, 172); cursor: pointer;" onclick="selectColor('rgb(151, 235, 172)')"></span>
                                <span class="fas fa-square mr-2" style="font-size: 26px; color: rgb(229, 143, 143); cursor: pointer;" onclick="selectColor('rgb(229, 143, 149)')"></span>
                            </div>
                        </div>

                        <div class="col-12 py-2">
                            <div class="form-group">
                                <textarea name="note" id="note" class="form-control" rows="10" required style="background-color: rgb(255, 230, 177)"></textarea>
                            </div>
                        </div>

                        <div class="col-12 text-right pt-3">
                            <button type="submit" class="btn btn-primary mr-2">Save</button>
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        </div>
                    </div>
                </form>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal Notes Modal -->





@section('scripts')    
    <!-- jQuery ui files-->
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.12.0/themes/smoothness/jquery-ui.css">
    <script src="https://code.jquery.com/ui/1.12.0/jquery-ui.min.js"></script>
    <script>
        var asset_customer_uid = '';
        var asset_company_id = '{{$company->id}}';
        var asset_ticket_id = '';
    </script>
    
    @include('js_files.help_desk.asset_manager.templateJs')
    @include('js_files.help_desk.asset_manager.actionsJs')
    @include('js_files.help_desk.asset_manager.assetJs')

    @include('js_files.company_lookup.companyprofileJs')
    
@endsection
@endsection