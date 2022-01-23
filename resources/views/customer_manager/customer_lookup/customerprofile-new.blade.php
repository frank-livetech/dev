@extends('layouts.master-layout-new')

<style>
    .float-right{
        float: right
    }
    .field-icon {
    position: relative;
    left:410px;
    top: 263px;
    }
    .crd_icons img{
    width:18%;
}
.float-btn{
    float:right
}
/* .btn{
    padding: 0rem 1rem !important
} */
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
</style>
@section('body')
<link rel="preconnect" href="https://fonts.gstatic.com">
<link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@400;500&display=swap" rel="stylesheet">
<script id="script1" src="https://secure.merchantonegateway.com/token/Collect.js" data-tokenization-key="zBkgJ9-6r24y2-FeFXkD-Kyxr9P" ></script>

<script>
   var nmi_integration = {!! json_encode($nmi_integration) !!};

   if(!$.isEmptyObject(nmi_integration)){
        if( nmi_integration.hasOwnProperty('tokenization_key')){
            var data_key = nmi_integration.tokenization_key;
            var scriptTag = document.getElementById("script1");
            console.log(scriptTag)
            scriptTag.setAttribute("data-tokenization-key", data_key);
        }
   }
</script>
<div class="app-content content">
    <div class="content-overlay"></div>
    <div class="header-navbar-shadow"></div>
    <div class="content-wrapper container-fluid p-0">
        <div class="content-header row">
            <div class="content-header-left col-md-12 col-12 mb-2">
                <div class="row breadcrumbs-top">
                    <div class="col-12">
                        <h2 class="content-header-title float-start mb-0">{{$customer->first_name}} {{$customer->last_name}}</h2>
                        <div class="breadcrumb-wrapper">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{route('home')}}">Home</a>
                                </li>
                                <li class="breadcrumb-item"><a href="{{route('customer.lookup')}}">Customer Lookup</a>
                                </li>
                                <li class="breadcrumb-item active">{{$customer->first_name}} {{$customer->last_name}}
                                </li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @if($date_format) 
        <input type="hidden" id="system_date_format" value="{{$date_format}}">
    @else
        <input type="hidden" id="system_date_format" value="DD-MM-YYYY">
    @endif

    <div class="content-body">
        <input type="hidden" id="curr_user_name" value="{{Auth::user()->name}}">

        <div class="row">
            <!-- Column -->
            <div class="col-md-12">
            @if($errors->any())
            
            <div class="alert alert-dismissable alert-danger">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
                <p><strong>Opps Something went wrong</strong></p>
                <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
                </ul>
            
            </div>
            
            @endif
    
            @if(session('success'))
                <div class="alert alert-success">{{session('success')}}</div>
            @endif
    
            </div>
    
            <div class="col-lg-3 col-xlg-3 col-md-5">
                <div class="card">
                    <div class="card-body">
                        <center class="mt-4">
                            @php
                                $path = Session::get('is_live') == 1 ? 'public/' : '/';
                            @endphp
                            @if($customer->avatar_url != null)
                                @if(is_file(public_path( $path . $customer->avatar_url)))
                                    <img src="{{ asset( $path . $customer->avatar_url)}}" class="rounded-circle" width="100" height="100" id="customer_curr_img" />
                                @else
                                    <img src="{{ asset('public/default_imgs/customer.png')}}" class="rounded-circle" width="100" height="100" id="customer_curr_img" />
                                @endif
                            @else
                                <img src="{{ asset('public/default_imgs/customer.png')}}" class="rounded-circle" width="100" height="100" id="customer_curr_img" />
                            @endif
                            <a type="button" data-bs-toggle="modal" data-bs-target="#editPicModal" style="position: relative;left: 51px;bottom: 97px;"><i class="fa fa-pencil-alt picEdit"></i></a>
    
                            <h4 class="card-title mt-2" id="cust_name">{{$customer->first_name}} {{$customer->last_name}}
                            </h4>
                            <span>#{{substr(str_repeat(0, 4).$customer->id, - 4)}}</span>
                        </center>
                    </div>
                    <div>
                        <hr>
                    </div>
                    <div class="card-body">
    
                        @if($customer->company != null)
                        <small class="text-muted">Company Name </small>
                        <h6 id="company-name"><a target="_blank"
                                href="{{url('company-profile')}}/{{$customer->company_id}}"> {{$customer->company->name}}
                            </a> </h6>
                        @endif
    
                        <small class="text-muted db">Phone</small>
                        <h6> <a href="tel:{{$customer->phone}}" id="cust_phone">{{$customer->phone}}</a> </h6>                    
    
                        <div id="adrs">
                            <small class="text-muted db">Email Address</small>
                            <input type="hidden" id="cust_email1" value="{{$customer->email}}">
                            <h6> <a href="mailto:{{$customer->email}}" id="cust_email"> {{$customer->email}}</a></h6>
                        </div>                   
    
    
                        <div>
                            <small class="text-muted  db">Address</small><br>
                            <span id="cust_add" class="text-dark">{{$customer->address}}</span>
                            <span id="cust_apprt"
                                class="text-dark">{{$customer->apt_address != null ? ', '.$customer->apt_address : '' }}</span>
                        </div>
    
                        <div>
                            <span id="cust_city">{{$customer->cust_city}}</span>
    
                            @if($customer->cust_state != null && $customer->cust_state != '')
                                <span id="cust_state">{{ ', '.$customer->cust_state }}</span>
                            @else
                                <span id="cust_state"></span>
                            @endif
                            
                            <span id="cust_zip">{{$customer->cust_zip != null ? ', '.$customer->cust_zip : '' }}</span>
                            <br>
    
                            @if($customer->country != null && $customer->country != '')
                            <span id="cust_country">{{$customer->country}}</span>
                            @else
                            <span id="cust_country"></span>
                            @endif
                        </div>
                    </div>
                </div>
    
                <div class="card">
                    <div class="card-body">
                        <div id="map_2" class="gmaps">
                       
                        </div>
                       <input type="hidden" id="google_api_key">
                       <h2 class="mt-4 font-weight-bold text-dark">Social Links</h2>
                        <div class="d-flex justify-content-center">
                        
                            <a href="{{$customer->twitter}}" id="twt" title="Twitter" class="btn" target="_blank"
                                style="color: #009efb; font-size:24px">
                                <i class="fab fa-twitter"></i>
                            </a>
    
                            <a href="{{$customer->fb}}" id="fb_icon" title="Facebook" class="btn" target="_blank"
                                style="color:#0570E6; font-size:24px">
                                <i class="fab fa-facebook"></i>
                            </a>
    
                            <a href="{{$customer->pinterest}}" id="pintrst" title="Pinterest" class="btn" target="_blank"
                                style="color:#DF1A26; font-size:24px">
                                <i class="fab fa-pinterest"></i>
                            </a>
    
                            <a href="{{$customer->insta}}" id="inst" title="Instagram" class="btn" target="_blank"
                                style="color:#e1306c; font-size:24px">
                                <i class="fab fa-instagram"></i>
                            </a>
    
                            <a href="{{$customer->linkedin}}" id="lkdn" title="Linkedin" class="btn" target="_blank"
                                style="color:#0e76a8; font-size:24px">
                                <i class="fab fa-linkedin"></i>
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
            <div class="col-lg-9 col-xlg-9 col-md-7">
                {{-- <div class="row">
                    <div class="col-lg-4 col-md-4 col-sm-6">
                        <a href="#" class="card border-info card-hover">
                            <div class="box p-2 rounded  text-center">
                                <h4 class="mb-0 text-info ">Domain Manager</h4>
                            </div>
                        </a>
                    </div>    
                    <div class="col-lg-4 col-md-4 col-sm-6">
                        <a href="#" class="card  border-primary card-hover">
                            <div class="box p-2 rounded text-center">
                                <h4 class="mb-0 text-primary ">My Servers</h4>
                            </div>
                        </a>
                    </div>
                    <div class="col-lg-4 col-md-4 col-sm-6">
                        <a href="#" class="card border-warning card-hover">
                            <div class="box p-2 rounded  text-center">
                                <h4 class="mb-0 text-warning ">Backups</h4>
                            </div>
                        </a>
                    </div>
                    <div class="col-lg-6 col-md-4 col-sm-6">
                        <a href="#" class="card border-success card-hover">
                            <div class="box p-2 rounded text-center">
                                <h4 class="mb-0 text-success ">Something special coming soon</h4>
                            </div>
                        </a>
                    </div>
                    <div class="col-lg-6 col-md-4 col-sm-6">
                        <a href="#" class="card  border-danger card-hover">
                            <div class="box p-2 rounded text-center">
                                <h4 class="mb-0 text-danger ">Something special coming soon</h4>
                            </div>
                        </a>
                    </div>
                </div> --}}
                
                <div class="card">
                    <!-- Tabs -->
                    <ul class="nav nav-pills custom-pills" id="pills-tab" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" id="pills-setting-tab" data-bs-toggle="pill" href="#previous-month" role="tab"
                                aria-controls="pills-setting" aria-selected="false">User Details</a>
                            <!-- <a class="nav-link active" id="pills-user-detail" data-bs-toggle="pill" href="#user-detail"
                                role="tab" aria-controls="pills-user-detail" aria-selected="true"></a> -->
                        </li>
    
                        <li class="nav-item">
                            <a class="nav-link" id="pills-tickets-tab" data-bs-toggle="pill" href="#tickets" role="tab"
                                aria-controls="pills-tickets" aria-selected="false">Tickets</a>
                        </li>
    
                        <li class="nav-item">
                            <a class="nav-link" id="orders-profile-tab" data-bs-toggle="pill" href="#orders" role="tab"
                                aria-controls="pills-profile" aria-selected="false">Billing</a>
                        </li>
    
                        <!-- <li class="nav-item">
                            <a class="nav-link" id="subscription-profile-tab" data-bs-toggle="pill" href="#subscription"
                                role="tab" aria-controls="pills-profile" aria-selected="false">Subscriptions</a>
                        </li> -->
    
                        <li class="nav-item">
                            <a class="nav-link" id="pills-assets-tab" data-bs-toggle="pill" href="#assets" role="tab"
                                aria-controls="pills-assets" aria-selected="false">Assets</a>
                        </li>
    
                        <!--<li class="nav-item">
                            <a class="nav-link" id="pills-setting-tab" data-bs-toggle="pill" href="#previous-month" role="tab"
                                aria-controls="pills-setting" aria-selected="false">Setting</a>
                        </li>-->
    
                        <!-- <li class="nav-item">
                            <a class="nav-link" id="pills-timeline-tab" data-bs-toggle="pill" href="#current-month" role="tab"
                                aria-controls="pills-timeline" aria-selected="true">History</a>
                        </li> -->
    
                        <li class="nav-item">
                            <a class="nav-link" id="payment-profile-tab" data-bs-toggle="pill" href="#payment" role="tab"
                                aria-controls="pills-profile" aria-selected="false">Payments</a>
                        </li>
                        <!-- <li class="nav-item">
                            <a class="nav-link" id="notifications-profile-tab" data-bs-toggle="pill" href="#notification"
                                role="tab" aria-controls="notifications-profile" aria-selected="false">Notifications</a>
                        </li> -->
                        <li class="nav-item">
                            <a class="nav-link" id="notes-profile-tab" data-bs-toggle="pill" href="#ticket_notes" role="tab" aria-controls="pills-profile" aria-selected="false">Notes</a>
                        </li>
    
                        <li class="nav-item">
                            <a class="nav-link" id="domain-profile-tab" data-bs-toggle="pill" href="#ticket_domain" role="tab" aria-controls="pills-profile" aria-selected="false">Domain</a>
                        </li>
                    </ul>
                    <!-- Tabs -->
                    <div class="tab-content" id="pills-tabContent">
    
                        <div class="tab-pane fade" id="user-detail" role="tabpanel"
                            aria-labelledby="pills-user-detail">
                            <hr>
                            <div class="card-body">
                                No Data Found.
                            </div>
                        </div>
    
                        <div class="tab-pane fade show" id="current-month" role="tabpanel"
                            aria-labelledby="pills-timeline-tab">
                            <hr>
                            <div class="card-body">
                                No Data Found.
                            </div>
                        </div>
    
                        <div class="tab-pane fade" id="orders" role="tabpanel" aria-labelledby="orders-profile-tab">
                            <div class="row p-2">
                                <div class="col-md-12">
                            <div class="d-flex justify-content-between pl-3 pr-3 mt-1">
                                <h2 class="lead font-weight-bold">01. Orders</h2>
    
                                @if($wp_value == 1)
                                    <button class="float-right btn-sm rounded btn btn-info mr-3"><i class="fas fa-sync"></i> Sync WP Orders  </button>
                                @endif
                            </div>
                        </div>
                            </div>
                            <div class="table-responsive mt-1">
                                <div id="zero_config_wrapper" class="dataTables_wrapper container-fluid dt-bootstrap4">
    
                                    <div class="row">
                                        <div class="col-sm-12">
                                            <table id="customer_order_table"
                                                class="table table-striped table-bordered w-100">
                                                <thead>
                                                    <tr>
                                                        <th>Select</th>
                                                        <th>Order ID</th>
                                                        <th>Customer Name</th>
                                                        <th>Order Status</th>
                                                        <th>Order Date</th>
                                                        <th style="width:200px !important;">Action</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                </tbody>
                                            </table>
                                            <div class="loader_container">
                                                <div class="loader"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
    
                            <hr>
    
                            <div class="d-flex justify-content-between p-3 ">
                                <h2 class="lead font-weight-bold">02. Subscriptions</h2>
                            </div>
    
                            <div class="table-responsive">
                                <div id="zero_config_wrapper"
                                    class="dataTables_wrapper container-fluid dt-bootstrap4">
    
                                    <div class="row ">
                                        <div class="col-sm-12">
                                            <table id="customer_subscription" class="table table-striped table-bordered w-100">
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
                                                    @foreach ($subscriptions as $sub)
                                                    <tr>
                                                        <td><a href="#detailsCard">{{$sub->woo_id}}</a>
                                                        </td>
                                                        <td>{{$sub->status}}</td>
                                                        <td>{{$sub->currency}}</td>
                                                        <td>{{$sub->discount_tax}}</td>
                                                        <td>{{$sub->shipping_total}}</td>
                                                        <td>{{$sub->shipping_tax}}</td>
                                                        <td>{{$sub->total}}</td>
                                                        <td>{{$sub->total_tax}}</td>
                                                        <td>{{$sub->payment_method}}</td>
                                                        <td>{{$sub->payment_method_title}}</td>
                                                        <td>{{$sub->created_via}}</td>
                                                        <td>{{$sub->customer_note}}</td>
                                                        <td>{{$sub->start_date}}</td>
                                                        <td>{{$sub->trial_end_date}}</td>
                                                        <td>{{$sub->next_payment_date}}</td>
                                                        <td>{{$sub->end_date}}</td>
                                                    </tr>
                                                    @endforeach
                                                </tbody>
    
                                            </table>
    
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
                                                                    class="table table-striped table-bordered no-wrap w-100 "
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
                                                                        @foreach ($subscriptions as $sub)
                                                                        <tr>
                                                                            <td><a href="#detailsCard">{{$sub->woo_id}}</a>
                                                                            </td>
                                                                            <td>{{$sub->status}}</td>
                                                                            <td>{{$sub->currency}}</td>
                                                                            <td>{{$sub->discount_tax}}</td>
                                                                            <td>{{$sub->shipping_total}}</td>
                                                                            <td>{{$sub->shipping_tax}}</td>
                                                                            <td>{{$sub->total}}</td>
                                                                            <td>{{$sub->total_tax}}</td>
                                                                            <td>{{$sub->payment_method}}</td>
                                                                            <td>{{$sub->payment_method_title}}</td>
                                                                            <td>{{$sub->created_via}}</td>
                                                                            <td>{{$sub->customer_note}}</td>
                                                                            <td>{{$sub->start_date}}</td>
                                                                            <td>{{$sub->trial_end_date}}</td>
                                                                            <td>{{$sub->next_payment_date}}</td>
                                                                            <td>{{$sub->end_date}}</td>
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
                                    </div>
                                </div>
                            </div>
                        </div> -->
    
                        <div class="tab-pane fade" id="payment" role="tabpanel" aria-labelledby="payment-profile-tab">
    
                            <!-- <hr>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-12">
    
                                        <button type="button" class="btn btn-info mr-auto" data-bs-toggle="modal"
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
                                        <div class="card-header" style="background-color: #f7f7f7;margin-left: 12px">
                                            <h4 class="mb-0">ADD New Credit Card</h4>
                                        </div>
                                        <div class="card-body">
                                            <form id="CardForm" class="CardForm" >
                                                <div class="row">
                                                    <div class="col-md-6 mt-1">
                                                        <div class="form-group">
                                                            <input type="text" class="form-control" placeholder="First Name" name="fname" id="fname" value="{{$customer->first_name}}" autofocus>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6 mt-1">
                                                        <div class="form-group">
                                                            <input type="text" class="form-control" placeholder="Last Name" name="lname" id="lname" value="{{$customer->last_name}}">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6 mt-1">
                                                        <div class="form-group">
                                                            <input type="text" class="form-control" placeholder="Street Address" name="address1" id="address1" value="{{$customer->address}}">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6 mt-1">
                                                        <div class="form-group">
                                                            <input type="text" class="form-control" placeholder="City" name="city" id="city" value="{{$customer->cust_city}}">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6 mt-1">
                                                        <div class="form-group">
                                                            @if ($google_key == 1)
                                                                <input type="text" class="form-control" placeholder="State" name="state" id="state" value="{{$customer->cust_state}}">
                                                            @else
                                                                <select class="select2 form-control" id="state" name="state" style="width: 100%; height:36px;"></select>
                                                            @endif
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6 mt-1">
                                                        <div class="form-group">
                                                            <input type="text" class="form-control" placeholder="Zip code" name="zip" id="zip" value="{{$customer->cust_zip}}">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6 mt-1">
                                                        <div class="form-group">
                                                            @if ($google_key == 1)
                                                                <input type="text" class="form-control" placeholder="Zip code" name="country" id="country" value="{{$customer->cust_zip}}">
                                                            @else
                                                                <select class="form-control" name="country" id="country" onchange="listStates(this.value, 'state', 'cmp_state')">
                                                                    <option value="">Select Country</option>
                                                                   
                                                                    @foreach ($countries as $item)
                                                                        @if (!empty($customer->country))
                                                                            <option value="{{$item->name}}" {{$customer->country == $item->name ? "selected" : ''}}>{{$item->name}}</option>    
                                                                        @else
                                                                            <option value="{{$item->name}}" {{'US' == $item->short_name ? "selected" : ''}}>{{$item->name}}</option>
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
    
                                            
                                                    <div class="col-md-12 mt-1">
                                                        <input type="submit" id="payButton" value="Add Card" class="btn btn-success float-btn">
                                                    </div>
                                                </div>    
                                            </form>
                                        </div>
                                        <div id="paymentTokenInfo"></div>
                                    </div>
                                </div>
                                <div class="col-md-6 ">
                                    <div class="card">
                                        <div class="card-header" style="background-color: #f7f7f7;margin-left: 12px">
                                            <h4 class="mb-0">Default Billing Address</h4>
                                        </div>
                                        <div class="card-body">
                                            <form id="" class="" >
                                                <div class="row">
                                                    <div class="col-md-6 mt-1">
                                                        <div class="form-group">
                                                            <input type="text" class="form-control" placeholder="First Name" name="" id="" value="" autofocus>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6 mt-1">
                                                        <div class="form-group">
                                                            <input type="text" class="form-control" placeholder="Last Name" name="" id="" value="">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-12 mt-1">
                                                        <div class="form-group">
                                                            <input type="text" class="form-control" placeholder="Company Name" name="" id="" value="">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-12 mt-1">
                                                        <div class="form-group">
                                                            <input type="text" class="form-control" placeholder="Street Address" name="payment_billing_address" id="payment_billing_address" value="">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-12 mt-1">
                                                        <div class="form-group">
                                                            <input type="text" class="form-control" placeholder="Apartment Address" name="payment_billing_aprt_address" id="payment_billing_aprt_address" value="">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6 mt-1">
                                                        {{-- <label for=""> CIty </label> --}}
                                                        <input type="text" class=" form-control" placeholder="City" id="payment_billing_city" name="payment_billing_city"  style="width: 100%; height:36px;">
                                                    </div>
                                                    <div class="col-md-6 mt-1">
                                                        <div class="form-group">
                                                            {{-- <label for="">State</label> --}}
                                                            @if($google_key == 1)
                                                                <input type="text" class=" form-control" placeholder="State" id="payment_billing_state" name="payment_billing_state" style="width: 100%; height:36px;">
                                                            @else    
                                                                <select class="select2 form-control" id="payment_billing_state" name="payment_billing_state" style="width: 100%; height:36px;"></select>
                                                            @endif
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6 mt-1">
                                                        <div class="form-group">
                                                            {{-- <label for="">Zip Code</label> --}}
                                                            <input type="text" class="form-control" placeholder="Zip code" name="payment_billing_zipcode"  id="payment_billing_zipcode" value="">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6 mt-1">
                                                        <div class="form-group">
                                                            {{-- <label for="">Country</label> --}}
                                                            @if($google_key == 1)
                                                                <input type="text" class="form-control" placeholder="Country" id="payment_billing_country" name="payment_billing_country" style="width: 100%; height:36px;">
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
                                                    <div class="col-md-12 text-right mt-1">
                                                    <button class="btn btn-success float-btn">Save Address</button>
    
                                                    </div>
                                                </div>    
                                            </form>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="card">
                                        <div class="card-header" style="background-color: #f7f7f7;margin-left: 12px">
                                            <h4 class="mb-0">Accepted Payment Methods</h4>
                                        </div>
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-md-4 mt-1">
                                                    <p>Credit Cards</p>
                                                </div>
                                                <div class="col-md-8 crd_icons mt-1">
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
                                        <div class="card-header" style="background-color: #f7f7f7;margin-left: 12px">
                                            <h4 class="mb-0">On File Cards</h4>
                                        </div>
                                        <div class="card-body">
                                            <div class="row mt-1">
                                                <div class="col-md-12">
                                                    <button class="btn btn-success float-btn">Add New</button>
                                                </div>
                                            </div>
                                            <div class="row mt-3" id="pay-card">
                                                <div class="col-md-6">
                                                    <div class="card payCard" style="border:1px solid black">
                                                        <div class="card-body">
                                                            <div class="row">
                                                                <div class="col-md-12 text-right">
                                                                    <a href="#" class="btn btn-warning text-white btn-circle float-btn ml-1" style="padding: 11px;"><i class="far fa-star" aria-hidden="true"></i></a>
                                                                    <a href="#" class="btn btn-success btn-circle float-btn" style="padding: 11px;margin-right: 4px;margin-left: 4px"><i class="fas fa-pencil-alt" aria-hidden="true"></i></a>
                                                                    <a href="#" class="btn btn-danger btn-circle float-btn" style="padding: 11px;"><i class="fas fa-trash" style="" aria-hidden="true"></i></a>
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
                                        <div class="card-header" style="background-color: #f7f7f7;margin-left: 12px">
                                            <h4 class="mb-0">Crypto Wallet Addresses</h4>
                                        </div>
                                        <div class="card-body">
                                            <div class="row mt-1">
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
    
                        <div class="tab-pane fade " id="notification" role="tabpanel"
                            aria-labelledby="notifications-profile-tab">
                            <hr>
                            <div class="card-body">
                                <div class="row">
    
                                </div>
                            </div>
                        </div>
    
                        <div class="tab-pane fade" id="assets" role="tabpanel" aria-labelledby="pills-assets-tab">
                            <hr>
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
                                        <button type="button" class="btn btn-info" data-bs-toggle="modal" data-target="#assetModal" style="float:right;margin-bottom:5px;top: -35px;position: relative;"><i class="fas fa-plus"></i>&nbsp;Add Asset</button>
                                        <button type="button" class="btn btn-info" data-bs-toggle="modal" data-target="#assetCatModal" style="float:right;top: -35px;position: relative;"><i class="fas fa-plus"></i>&nbsp;Add Asset Category</button>
                                    </div>
                                </div> --}}
                                <div class="row">
                                
                                    <div class="col-sm-12">
                                        <div class="accordion accordion-margin" id="accordionMargin">
                                            <div class="accordion-item">
                                                <h2 class="accordion-header" id="headingMarginOne">
                                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#accordionMarginOne" aria-expanded="false" aria-controls="accordionMarginOne">
                                                        Add Asset Template
                                                    </button>
                                                </h2>
                                                <div id="accordionMarginOne" class="accordion-collapse collapse" aria-labelledby="headingMarginOne" data-bs-parent="#accordionMargin">
                                                    <div class="accordion-body">
                                                        <div class="card">
                                                                <div class="card-body">
                                                                    <div class="">
                                                                        <div class="card" style="box-shadow: 0 12px 24px 0 rgb(34 41 47 / 32%) !important;">
                                                                            <div class="row p-1">
                                                                                <div class="col-md-12">
                                                                                    <div class="form-group">
                                                                                        <label for="tempTitle">Template Title</label>
                                                                                        <input class="form-control" type="text" id="tempTitle" required="" placeholder="Title">
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <div class="row ">
                                                                            <div class="col-md-4">
                                                                                <div class="card p-1" style="box-shadow: 0 12px 24px 0 rgb(34 41 47 / 32%) !important;">
                                                                                    <a class="buttonPush" href="javascript:fieldAdd('text')">
                                                                                        <div class="card border-cyan card-hover mb-1">
                                                                                            <button type="button" class="btn btn-outline-success waves-effect"><i class="fas fa-edit pr-2"></i> Input Field</button>
                                                                                            {{-- <div class="box p-2 rounded">
                                                                                                <h6 class="text-cyan mb-0"><i class="fas fa-edit pr-2"></i> Input Field</h6>
                                                                                            </div> --}}
                                                                                        </div>
                                                                                    </a>
                                                                                    <a class="buttonPush" href="javascript:fieldAdd('phone')">
                                                                                        <div class="card border-cyan card-hover mb-1">
                                                                                            <button type="button" class="btn btn-outline-success waves-effect"><i class="fas fa-phone pr-2"></i> Phone Number</button>

                                                                                            {{-- <div class="box p-2 rounded">
                                                                                                <h6 class="text-cyan mb-0"><i class="fas fa-phone pr-2"></i> Phone Number</h6>
                                                                                            </div> --}}
                                                                                        </div>
                                                                                    </a>
                                                                                    <a class="buttonPush" href="javascript:fieldAdd('email')" >
                                                                                        <div class="card border-cyan card-hover mb-1">
                                                                                            <button type="button" class="btn btn-outline-success waves-effect"><i class="fas fa-envelope pr-2"></i> Email</button>

                                                                                            {{-- <div class="box p-2 rounded">
                                                                                                <h6 class="text-cyan mb-0"><i class="fas fa-envelope pr-2"></i> Email</h6>
                                                                                            </div> --}}
                                                                                        </div>
                                                                                    </a>
                                                                                    <a href="javascript:fieldAdd('textbox')"  class="buttonPush">
                                                                                        <div class="card border-cyan card-hover mb-1">
                                                                                            <button type="button" class="btn btn-outline-success waves-effect"><i class="fas fa-indent pr2"></i> Text Area</button>

                                                                                            {{-- <div class="box p-2 rounded">
                                                                                                <h6 class="text-cyan"><i class="fas fa-indent pr2"></i> Text Area</h6>
                                                                                            </div> --}}
                                                                                        </div>
                                                                                    </a>
                                                                                    <a class="buttonPush" href="javascript:fieldAdd('selectbox')">
                                                                                        <div class="card border-cyan card-hover mb-1">
                                                                                            <button type="button" class="btn btn-outline-success waves-effect"><i class="fas fa-chevron-circle-down pr-2"></i> Select</button>

                                                                                            {{-- <div class="box p-2 rounded ">
                                                                                                <h6 class="text-cyan"><i class="fas fa-chevron-circle-down pr-2"></i> Select</h6>
                                                                                            </div> --}}
                                                                                        </div>
                                                                                    </a>
                                                                                    <a class="buttonPush" href="javascript:fieldAdd('password')" >
                                                                                        <div class="card border-cyan card-hover mb-1">
                                                                                            <button type="button" class="btn btn-outline-success waves-effect"><i class="fas fa-key pr-2"></i> Password</button>

                                                                                            {{-- <div class="box p-2 rounded">
                                                                                                <h6 class="text-cyan"><i class="fas fa-key pr-2"></i>Password</h6>
                                                                                            </div> --}}
                                                                                        </div>
                                                                                    </a>
                                                                                    <a class="buttonPush" href="javascript:fieldAdd('ipv4')" >
                                                                                        <div class="card border-cyan card-hover mb-1">
                                                                                            <button type="button" class="btn btn-outline-success waves-effect"><i class="fas fa-qrcode pr-2"></i> IPv4</button>

                                                                                            {{-- <div class="box p-2 rounded">
                                                                                                <h6 class="text-cyan"><i class="fas fa-qrcode pr-2"></i> IPv4</h6>
                                                                                            </div> --}}
                                                                                        </div>
                                                                                    </a>
                                                                                    <a class="buttonPush" href="javascript:fieldAdd('url')" >
                                                                                        <div class="card border-cyan card-hover mb-1">
                                                                                            <button type="button" class="btn btn-outline-success waves-effect"><i class="fas fa-laptop-code pr-2"></i> URL</button>

                                                                                            {{-- <div class="box p-2 rounded">
                                                                                                <!-- <h5 class="font-weight-light text-cyan"></h5> -->
                                                                                                <h6 class="text-cyan"><i class="fas fa-laptop-code pr-2"></i> URL</h6>
                                                                                            </div> --}}
                                                                                        </div>
                                                                                    </a>
                                                                                    <a class="buttonPush" href="javascript:fieldAdd('address')" >
                                                                                        <div class="card border-cyan card-hover mb-1">
                                                                                            <button type="button" class="btn btn-outline-success waves-effect"><i class="fas fa-map-marked-alt pr-2"></i> Address</button>

                                                                                            {{-- <div class="box p-2 rounded">
                                                                                                <!-- <h5 class="font-weight-light text-cyan"></h5> -->
                                                                                                <h6 class="text-cyan"><i class="fas fa-map-marked-alt pr-2"></i> Address</h6>
                                                                                            </div> --}}
                                                                                        </div>
                                                                                    </a>
                                                                                </div>
                                                                            </div>
                                                                            <div class="col-md-8">
                                                                                <div class="card" style="box-shadow: 0 12px 24px 0 rgb(34 41 47 / 32%) !important;">
                                                                                    <div class="m-1">
                                                                                        <button type="button" class="btn btn-success float-btn ml-2" onclick="saveTemplate()" style="margin-left: 5px"> Save Template </button>
                                                                                        <button type="button" class="btn btn-info float-btn" onclick=""> Preview </button>
                                                                                    </div>
                                                                                </div>
                                                                                <div class="card" style="box-shadow: 0 12px 24px 0 rgb(34 41 47 / 32%) !important;">
                                                                                    <div class="row p-1">
                                                                                        <div class="col-md-12 pt-1 ">
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
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="accordion-item">
                                                <h2 class="accordion-header" id="headingMargintwo">
                                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#accordionMargintwo" aria-expanded="false" aria-controls="accordionMarginOne" style="margin-bottom: 14px;">
                                                        Add Asset
                                                    </button>
                                                </h2>
                                                <div id="accordionMargintwo" class="accordion-collapse collapse pb-3" aria-labelledby="headingMargintwo" data-bs-parent="#accordionMargin">
                                                    <div class="accordion-body">
                                                        <form class="form-horizontal" id="save_asset_form" enctype="multipart/form-data"
                                                        action="{{asset('/save-asset')}}" method="post">
                                                        <div class="form-row">
                                                            <div class="col-md-12 form-group">
                                                                <div class="form-group">
                                                                    <label>Asset Template</label>
                                                                    <select class="select2 form-select form-control" onchange="getFields(this.value)" id="form_id" name="form_id" required></select>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="form-row mt-1" id="templateTitle" style="display:none;">
                                                            <div class="col-md-12 form-group">
                                                                <div class="form-group">
                                                                    <label>Asset Title</label>
                                                                        <input type="text" name="asset_title" id="asset_title" class="asset_title form-control">
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="row" id="form-fields"></div>
                                        
                                                        <button type="submit" class="btn btn-success mt-1" style="float:right;">Save</button>
                                                    </form>
                                                    </div>
                                                </div>
                                            </div>
                                        
                                        
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
                            <div class="card-body">
                                <div class="text-right mb-3">
                                    <a href="{{url('add-ticket')}}/{{$customer->id}}" class="btn btn-info rounded ml-auto mb-auto float-right mb-3">
                                        <i class="fas fa-plus"></i>&nbsp;Add ticket
                                    </a>
                                    <!-- <button type="button" class="btn btn-info ml-auto mb-auto" onclick="ShowTicketsModel()">
                                        <i class="fas fa-plus"></i>&nbsp;Add ticket
                                    </button> -->
                                </div>
                                <div class="row mt-3">
                                    <div class="col-md-3">
                                        <a href="javascript:listTickets('total')" class="card card-hover border-info" >
                                            <div class="box p-2 rounded info text-center">
                                                <h1 class="font-weight-light " id="total_tickets_count"></h1>
                                                <h6 class="text-info">All Tickets</h6>
                                            </div>
                                        </a>
                                    </div>
                                    <div class="col-md-3">
                                        <a href="javascript:listTickets('open')" class="card card-hover border-warning" >
                                            <div class="box p-2 rounded warning text-center">
                                                <h1 class="font-weight-light " id="open_tickets_count"></h1>
                                                <h6 class="text-warning">Open</h6>
                                            </div>
                                        </a>
                                    </div>
                                    <div class="col-md-3">
                                        <a href="javascript:listTickets('unassigned')" class="card card-hover border-primary">
                                            <div class="box p-2 rounded primary text-center">
                                                <h1 class="font-weight-light " id="unassigned_tickets_count"></h1>
                                                <h6 class="text-primary">Unassigned</h6>
                                            </div>
                                        </a>
                                    </div>
                                    <div class="col-md-3">
                                        <a href="javascript:listTickets('closed')" class="card card-hove border-danger">
                                            <div class="box p-2 rounded danger text-center">
                                                <h1 class="font-weight-light " id="closed_tickets_count"></h1>
                                                <h6 class="text-danger">Overdue</h6>
                                            </div>
                                        </a>
                                    </div>
                                </div>
    
                                <div class="col-12 mb-3 d-none">
                                    <a class="toggle-vis btn btn-sm btn-success text-white mb-1" data-column="0">#</a> -
                                    <a class="toggle-vis btn btn-sm btn-success text-white mb-1" data-column="1">Status</a>
                                    -
                                    <a class="toggle-vis btn btn-sm btn-success text-white mb-1" data-column="2">Subject</a>
                                    -
                                    <a class="toggle-vis btn btn-sm btn-success text-white mb-1"
                                        data-column="3">TicketID</a> -
                                    <a class="toggle-vis btn btn-sm btn-success text-white mb-1"
                                        data-column="4">Priority</a> -
                                    <a class="toggle-vis btn btn-sm btn-success text-white mb-1"
                                        data-column="5">Customer</a> -
                                    <a class="toggle-vis btn btn-sm btn-success text-white mb-1" data-column="6">Last
                                        Replier</a> -
                                    <a class="toggle-vis btn btn-sm btn-success text-white mb-1" data-column="7">Replies</a>
                                    -
                                    <a class="toggle-vis btn btn-sm btn-success text-white mb-1" data-column="8">Last
                                        Activity</a> -
                                    <a class="toggle-vis btn btn-sm btn-success text-white mb-1" data-column="9">Reply
                                        Due</a> -
                                    <a class="toggle-vis btn btn-sm btn-success text-white mb-1" data-column="10">Resolution
                                        Due</a> -
                                    <a class="toggle-vis btn btn-sm btn-success text-white mb-1" data-column="11">Assigned
                                        Tech</a> -
                                    <a class="toggle-vis btn btn-sm btn-success text-white mb-1"
                                        data-column="12">Department</a> -
                                    <a class="toggle-vis btn btn-sm btn-success text-white mb-1" data-column="13">Creation
                                        Date</a>
                                </div>
    
                                <div class="table-responsive">
                                    {{-- <div class="row">
                                        <div class="col-md-12" style="text-align:right;">
                                            <select class="multiple-select  mb-2" name="tk_select" id="tk_select" placeholder="Show/Hide" multiple="multiple" selected="selected">
                                                <option value="0">#</option>
                                                <option value="2">Status</option>
                                                <option value="3">Status</option>
                                                <option value="4">Ticket ID</option>
                                                <option value="5">Prioprity</option>
                                                <option value="6">Customer</option>
                                                <option value="7">Last Replier</option>
                                                <option value="8">Replies</option>
                                                <option value="9">Last Activity</option>
                                                <option value="10">Reply Due</option>
                                                <option value="11">Resolution Due</option>
                                                <option value="12">Assigned Tech</option>
                                                <option value="13">Department</option>
                                                <option value="14">Creation Date</option>
                                            </select>
                                        </div>
                                    </div> --}}
    
                                    <table id="ticket-table-list" class="table table-striped table-bordered table-hover display ticket-table-list">
                                        <thead>
                                            <tr>
                                                <th>
                                                    <div class="text-center">
                                                        <input type="checkbox" name="select_all[]" id="select-all">
                                                    </div>
                                                </th>
                                                <th></th>
                                                <th>Status</th>
                                                <th>Subject</th>
                                                <th>Ticket ID</th>
                                                <th>Priority</th>
                                                <th>Customer</th>
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
                                        <tbody></tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
    
                        <div class="tab-pane fade active show" id="previous-month" role="tabpanel" aria-labelledby="pills-setting-tab">
                            <div class="card-body">
                                <form id="update_customer" action="{{url('update_customer_profile')}}" method="POST">
                                    <h2 class="mt-4 font-weight-bold text-dark">Personal Info</h2>
    
                                    <div class="row">
                                        <div class="col-md-4 form-group">
                                            <label for="first_name">First Name</label>
                                            <input type="text" class="form-control" id="first_name" name="first_name"
                                                value="{{$customer->first_name}}" required>
                                        </div>
                                        <div class="col-md-4 form-group">
                                            <label for="last_name">Last Name</label>
                                            <input type="text" class="form-control" id="last_name" name="last_name"
                                                value="{{$customer->last_name}}" required>
                                        </div>
                                        <div class="col-md-4 form-group">
                                            <label for="email">Email</label>
                                            <input type="text" class="form-control" id="prof_email" name="prof_email"
                                                value="{{$customer->email}}" required>
                                        </div>
                                    </div>
    
                                    <div class="row mt-1">
                                        @if($customer->has_account != 0)
                                            <div class="col-md-6 form-group">
                                                <label>Password</label>
                                                <div class="input-group form-password-toggle input-group-merge">
                                                    <input type="password" class="form-control" name="password" id="password" placeholder="" value="{{$customer->password}}" />
                                                    <div class="input-group-text cursor-pointer">
                                                        <i data-feather="eye"></i>
                                                    </div>
                                                </div>
                                                {{-- <div class="user-password-div">
                                                    <span class="block input-icon input-icon-right">
                                                        <input type="password" name="password" id="password"
                                                            placeholder="password" class="form-control form-control-line"
                                                            value="{{$customer->password}}">
                                                        <span toggle="#password-field"
                                                            class="fa fa-fw fa-eye field-icon show-password-btn mr-2"></span>
                                                    </span>
                                                    
                                                </div> --}}
                                            </div>
                                            <div class="col-md-6 form-group">
                                                <label>Confirm Password</label>
                                                <div class="input-group form-password-toggle input-group-merge">
                                                    <input type="password" class="form-control" name="confirm_password" placeholder="Confirm Password" value="{{$customer->password}}" />
                                                    <div class="input-group-text cursor-pointer">
                                                        <i data-feather="eye"></i>
                                                    </div>
                                                </div>
                                                {{-- <div class="user-confirm-password-div">
                                                    <input name="confirm_password" class="form-control form-control-line"
                                                        type="password" placeholder="Confirm Password"
                                                        value="{{$customer->password}}">
                                                    <span toggle="#password-field"
                                                        class="fa fa-fw fa-eye field-icon show-confirm-password-btn mr-2"></span>
                                                </div> --}}
                                            </div>
                                        @endif
                                    </div>
    
                                    <div class="row mt-1">
                                        <div class="col-md-6 form-group">
                                            <label>Phone No</label>
                                            <input type="text" id="prof_phone" name="prof_phone"
                                                value="{{$customer->phone}}" class="form-control form-control-line">
                                                <span class="text-danger small" id="phone_error"></span>
                                        </div>
                                        <div class="col-md-6 form-group">
                                            <label>Phone Type</label>
                                            <select name="phone_type" id="phone_type" class="form-control">
                                                <option value="">Choose</option>
                                                <option value="cellphone" {{$customer->phone_type == "cellphone" ? "selected" : ''}}>Cell Phone</option>
                                                <option value="home" {{$customer->phone_type == "home" ? "selected" : ''}}>Home</option>
                                                <option value="office" {{$customer->phone_type == "office" ? "selected" : ''}}>Office</option>
                                            </select>
                                        </div>
                                    </div>
    
    
                                    <div class="row mt-1">
                                        <div class="col-md-6 form-group">
                                            <label>Customer Type</label>
                                            
                                                <select id="cust_type" name="cust_type"
                                                    class="form-select form-control-line">
                                                    <option value="">Customer Type</option>
                                                    @foreach ($customer_types as $item)
                                                    <option value="{{$item->id}}">{{$item->name}}</option>
                                                    @endforeach
                                                </select>
                                            
                                        </div>
                                        <div class="col-md-5 form-group">
                                            <label>Company</label>
                                                <select class="select2-data-array form-select form-control form-control-line" id="company_id" name="company_id">
                                                    <option value="">Company</option>
                                                    @foreach ($company as $item)
                                                    <option value="{{$item->id}}"
                                                        {{$item->id == $customer->company_id ? 'selected' : ''}}>{{$item->name}}</option>
                                                    @endforeach
                                                </select>
                                        </div>
                                        <div class="col-md-1 form-group">
                                            <button type="button" data-bs-toggle="modal" data-bs-target="#addCompanyModal"
                                            id="new-company" class="btn btn-info" style="margin-top: 20px;position: relative;right:30px">New</button>
                                        </div>
                                    </div>
    
                                    @if($customer->has_account == 0)
                                    <div class="row mb-2 mt-2">
                                        <div class="custom-control custom-checkbox mr-sm-2">
                                            <input type="checkbox" class="custom-control-input" id="customer_login">
                                            <label class="custom-control-label" for="customer_login">Create Customer Login
                                                Account</label>
                                        </div>
                                    </div>
                                    @endif
    
                                    <input type="hidden" name="customer_id" value="{{$customer->id}}">
    
                                    <div class="row mt-1">
                                        <div class="col-12 form-group">
                                            <label>Street Address</label>
                                            <a type="button" data-bs-toggle="modal" data-bs-target="#Address-Book"
                                                class="float-right" style="color:#009efb;">View Address Book</a>
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <input type="text" class=" form-control" name="address"
                                                        value="{{$customer->address}}" id="prof_address"
                                                        placeholder="House number and street name">
                                                </div>
                                                <div class="col-md-6">
                                                    <input type="text" class=" form-control" name="apt_address"
                                                        value="{{$customer->apt_address}}" id="apt_address"
                                                        placeholder="Apartment, suit, unit etc. (optional)">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
    
                                    <div class="row mt-1">
                                        <div class="col-md-3 form-group">
                                            <label>City</label>
                                            <input type="text" class="form-control" value="{{$customer->cust_city}}"
                                                id="prof_city">
                                        </div>
                                        <div class="col-md-3 form-group">
                                            <label>State</label>
    
                                            @if($google_key == 1)
                                                <input type="text" class=" form-control " value="{{$customer->cust_state}}" id="prof_state" name="prof_state"
                                                    style="width: 100%; height:36px;">
                                            @else
                                                <select class="select2 form-control " id="prof_state" name="prof_state" style="width: 100%; height:36px;"></select>
                                            @endif
                                        </div>
                                        <div class="col-md-3 form-group">
                                            <label>Zip Code</label>
                                            <input type="tel" maxlength="5" class="form-control" value="{{$customer->cust_zip}}" id="prof_zip">
                                        </div>
    
                                        <div class="col-md-3 form-group">
                                            <label>Country</label>
                                            @if($google_key == 1)
                                                <input type="text" class=" form-control " value="{{$customer->country}}" id="prof_country" name="prof_country" style="width: 100%; height:36px;">
                                            @else
                                                <select class="select2 form-control " id="prof_country" name="prof_country" style="width: 100%; height:36px;" onchange="listStates(this.value, 'prof_state', 'cust_state')">
                                                    <option value="">Select Country</option>
                                                        @foreach ($countries as $item)
                                                            @if(!empty($customer->country))
                                                                <option value="{{$item->name}}" {{$customer->country == $item->name ? 'selected' : ''}}>{{$item->name}}</option>
                                                            @else
                                                                <option value="{{$item->name}}" {{'US' == $item->short_name ? 'selected' : ''}}>{{$item->name}}</option>
                                                            @endif
                                                        @endforeach
                                                </select>
                                            @endif
                                        </div>
                                    </div>
    
                                    <h2 class="mt-4 font-weight-bold text-dark">Social</h2>
    
                                    <div class="row mt-1">
                                        <div class="col-md-6 form-group">
                                            <label>Twitter</label>
                                            <input type="text" class="form-control" id="prof_twitter"
                                                value="{{$customer->twitter}}" placeholder="https://twitter.com/username">
                                        </div>
                                        <div class="col-md-6 form-group">
                                            <label>Facebook</label>
                                            <input type="text" class="form-control" id="prof_fb" value="{{$customer->fb}}"
                                                placeholder="https://facebook.com/yourprofile">
                                        </div>
                                    </div>
    
                                    <div class="row mt-1">
                                        <div class="col-md-6 form-group">
                                            <label>Instagram</label>
                                            <input type="text" class="form-control" id="prof_insta"
                                                value="{{$customer->isnta}}" placeholder="https://instagram.com/username">
                                        </div>
                                        <div class="col-md-6 form-group">
                                            <label>Pinterest</label>
                                            <input type="text" class="form-control" id="prof_pinterest"
                                                value="{{$customer->pinterest}}"
                                                placeholder="https://pinterest.com/@Username">
                                        </div>
                                    </div>
    
                                    <div class="row mt-1" >
                                        <div class="col-md-6 form-group">
                                            <label>Linkedin</label>
                                            <input type="text" class="form-control" id="prof_linkedin"
                                                value="{{$customer->linkedin}}"
                                                placeholder="https://linkedin.com/@Username">
                                        </div>
                                    </div>
    
    
                                    <input type="hidden" name="customer-id" id="customer-id" value="{{$customer->id}}">
                                    <div class="row mt-1 mb-2 float-right">
                                        <div class="col-md-12 form-group">
                                            <button type="submit" id="saveBtn" class="btn btn-success">Update Profile</button>
                                            
                                            <button id="processing" style="display:none" class="btn btn-success"
                                                    type="button" disabled>
                                                    <i class="fas fa-circle-notch fa-spin"></i>
                                                    Processing</button>
                                        </div>
    
                                    </div>
                                    
                                </form>
                            </div>
                        </div>
    
                        <div class="tab-pane fade" id="ticket_notes" role="tabpanel" aria-labelledby="notes-profile-tab">
                            <hr>
                            <div class="card-body">
                                No Data Found.
                            </div>
                        </div>
    
                        <div class="tab-pane fade" id="ticket_domain" role="tabpanel" aria-labelledby="domain-profile-tab">
                            <hr>
                            <div class="card-body">
                                <div class="table-responsive mt-3">
                                    <div id="zero_config_wrapper"
                                        class="dataTables_wrapper container-fluid dt-bootstrap4">
    
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="row">
                                                    <div class="col-md-10 form-group">
                                                        <input type="text" id="search_domain" class="form-control" placeholder="Search your Perfect Domain here!">
                                                    </div>
                                                    <div class="col-md-2 form-group">
                                                        <button class="btn btn-success" onclick="searchDomain()"> Search </button>
                                                    </div>
                                                </div>
                                               
                                                <hr>
                                            </div>
                                            <div class="col-sm-12">
                                                <table id="domain_order_table"
                                                    class="table table-striped table-bordered w-100">
                                                    <thead>
                                                        <tr>
                                                            <th>Select</th>
                                                            <th>Domain</th>
                                                            <th>Pricing</th>
                                                            <th>Status</th>
                                                            <th>Register</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>  
                                                    </tbody>
                                                </table>
                                                <div class="loader_container" id="domain_loader">
                                                    <div class="loader"></div>
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <a type="button" class="btn btn-success text-white"> Purchase </a>
                                                <a type="button" class="btn btn-primary text-white" > Show Only </a>
                                            </div>
    
                                            <div class="col-sm-12 mt-3">
                                                <hr>
                                                <h4>My Domains</h4>
                                                <table id="mydomain_order_table"
                                                    class="table table-striped table-bordered w-100">
                                                    <thead>
                                                        <tr>
                                                            <th>Select</th>
                                                            <th>Domain</th>
                                                            <th>Pricing</th>
                                                            <th>Status</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <tr>
                                                            <td>
                                                                <div class="custom-control custom-checkbox">
                                                                    <input type="checkbox" class="custom-control-input" id="customCheck1">
                                                                    <label class="custom-control-label" for="customCheck1"></label>
                                                                </div>
                                                            </td>
                                                            <td> <a type="button" data-bs-toggle="modal" data-bs-target="#domainModal"><i class="fas fa-angle-right"></i> <span style="font-size:20px;"> www.king.cin</span></a></td>
                                                            <td>$78.22</td>
                                                            <td>Active</td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                                <!-- <div class="loader_container">
                                                    <div class="loader"></div>
                                                </div> -->
                                            </div>
                                        </div>
                                    </div>
                                </div>       
                            </div>
                        </div>
    
                    </div>
                </div>
            </div>
            <!-- Column -->
        </div>
    
        {{-- Details --}}
        <div class="row" style="display: none;">
            <div class="col-12">
                <div class="card" id="detailsCard">
                </div>
            </div>
        </div>
        {{-- Details End --}}
    
        <input type="hidden" id="customer_id" value="{{$customer->id}}">
    
        <!--Add card model-->
    
    
        <!--Address Book model-->
        <div class="modal fade" id="Address-Book" tabindex="-1" role="dialog"  data-backdrop="static" aria-labelledby="myLargeModalLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header d-flex align-items-center">
                        <h4 class="modal-title" id="myLargeModalLabel">ADDRESS BOOK</h4>
                        <button type="button" class="btn-close ml-auto" data-bs-dismiss="modal" aria-hidden="true"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-12" style="text-align:right;">
                                <div class="col-md-12">
                                    <button id="add_new_add" class=" float-right btn btn-success"
                                        onclick="New_Bill_Add()"><i class="fa fa-plus-circle"></i> Add New </button>
                                </div>
                            </div>
                            <div class="col-md-12 form-row" id="NewBillAdd" style="display:none;">
                                <div class="col-12 form-group">
                                    <label>Street Address</label>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <input type="text" class=" form-control" value="" id=""
                                                placeholder="House number and street name">
                                        </div>
                                        <div class="col-md-6">
                                            <input type="text" class=" form-control" value="" id=""
                                                placeholder="Apartment, suit, unit etc. (optional)">
                                        </div>
                                    </div>
                                </div>
                                <div class="row mt-1">
                                <div class="col-md-3 ">
                                    <label>City</label>
                                    <input type="text" class="form-control">
                                </div>
                                <div class="col-md-3 ">
                                    <label>Zip Code</label>
                                    <input type="text" class="form-control">
                                </div>
    
                                <div class="col-md-3">
                                    <label>State</label>
                                    <input type="text" class="form-control">
                                </div>
                                <div class="col-md-3">
                                        <label>Country</label>
                                        <input type="text" class="form-control">
                                </div>
                                <div class="col-md-12 float-right mt-2">
                                    <button type="submit" style="float:right;" class="btn btn-success ">Save</button>
                                </div>
                            </div>
                            </div>
                        </div>
                        <div class="table-responsive mt-3">
                            <table id="zero_config" class="table table-striped table-bordered no-wrap">
                                <thead>
                                    <tr role="row">
                                        <th>Street address</th>
                                        <th>Apt</th>
                                        <th>City</th>
                                        <th>State</th>
                                        <th>Zip</th>
                                        <th>Country</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>NN2-E</td>
                                        <td>542</td>
                                        <td>Lahore</td>
                                        <td>Punjab</td>
                                        <td>54000</td>
                                        <td>Pakistan</td>
                                    </tr>
                                    <tr>
                                        <td>NN2-E</td>
                                        <td>542</td>
                                        <td>Lahore</td>
                                        <td>Punjab</td>
                                        <td>54000</td>
                                        <td>Pakistan</td>
                                    </tr>
                                    <tr>
                                        <td>NN2-E</td>
                                        <td>542</td>
                                        <td>Lahore</td>
                                        <td>Punjab</td>
                                        <td>54000</td>
                                        <td>Pakistan</td>
                                    </tr>
                                    <tr>
                                        <td>NN2-E</td>
                                        <td>542</td>
                                        <td>Lahore</td>
                                        <td>Punjab</td>
                                        <td>54000</td>
                                        <td>Pakistan</td>
                                    </tr>
                                    <tr>
                                        <td>NN2-E</td>
                                        <td>542</td>
                                        <td>Lahore</td>
                                        <td>Punjab</td>
                                        <td>54000</td>
                                        <td>Pakistan</td>
                                    </tr>
                                    <tr>
                                        <td>NN2-E</td>
                                        <td>542</td>
                                        <td>Lahore</td>
                                        <td>Punjab</td>
                                        <td>54000</td>
                                        <td>Pakistan</td>
                                    </tr>
                                    <tr>
                                        <td>NN2-E</td>
                                        <td>542</td>
                                        <td>Lahore</td>
                                        <td>Punjab</td>
                                        <td>54000</td>
                                        <td>Pakistan</td>
                                    </tr>
                                    <tr>
                                        <td>NN2-E</td>
                                        <td>542</td>
                                        <td>Lahore</td>
                                        <td>Punjab</td>
                                        <td>54000</td>
                                        <td>Pakistan</td>
                                    </tr>
                                    <tr>
                                        <td>NN2-E</td>
                                        <td>542</td>
                                        <td>Lahore</td>
                                        <td>Punjab</td>
                                        <td>54000</td>
                                        <td>Pakistan</td>
                                    </tr>
                                    <tr>
                                        <td>NN2-E</td>
                                        <td>542</td>
                                        <td>Lahore</td>
                                        <td>Punjab</td>
                                        <td>54000</td>
                                        <td>Pakistan</td>
                                    </tr>
                                    <tr>
                                        <td>NN2-E</td>
                                        <td>542</td>
                                        <td>Lahore</td>
                                        <td>Punjab</td>
                                        <td>54000</td>
                                        <td>Pakistan</td>
                                    </tr>
                                </tbody>
                                <tfoot>
                                    <tr role="row">
                                        <th>Street address</th>
                                        <th>Apt</th>
                                        <th>City</th>
                                        <th>State</th>
                                        <th>Zip</th>
                                        <th>Country</th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
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
    
        <!-- add new company -->
        <div class="modal fade" id="addCompanyModal" role="dialog"  data-backdrop="static" aria-labelledby="myLargeModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header d-flex align-items-center">
                        <h4 class="modal-title" id="edit-company">Save Company</h4>
                        <button type="button" class="btn-close ml-auto" data-bs-dismiss="modal" aria-hidden="true"></button>
                    </div>
                    <div class="modal-body">
                        <form id="companyForm">
    
                            <div class="row">
                                <div class="col-md-4">
                                    <label for="poc_first_name" class="small">Owner First Name</label>
                                    <input type="text" id="poc_first_name" class="form-control">
                                    <span class="text-danger small" id="err"></span>
                                </div>
                                <div class="col-md-4">
                                    <label for="poc_last_name" class="small">Owner Last Name</label>
                                    <input type="text" class="form-control" id="poc_last_name">
                                    <span class="text-danger small" id="err1"></span>
                                </div>
                                <div class="col-md-4">
                                    <label for="name" class="small">Company Name</label>
                                    <input type="text" id="name" class="form-control">
                                    <span class="text-danger small" id="err2"></span>
                                </div>
                            </div>
    
                            <div class="row mt-1">
                                <div class="col-md-6">
                                    <label for="email" class="small">Company Email</label>
                                    <input type="text" class="form-control" id="cemail">
                                    <span class="text-danger small" id="err3"></span>
                                </div>
                                <div class="col-md-6">
                                    <label for="phone" class="small">Phone Number</label>
                                    <input type="text" class="form-control" id="phone">
                                    <span class="text-danger small" id="err4"></span>
                                </div>
                            </div>
    
                            <!-- <div class="row mt-3">
                            
                            <div class="col-md-6">
                                <label for="country" class="small">Country</label>
                                <input type="text" id="country" class="form-control">
                                <span class="text-danger small" id="err5"></span>
                            </div> 
                        </div> -->
    
                            <!-- <div class="row mt-3">
                            <div class="col-md-4">
                                <label for="state" class="small">State</label>
                                <input type="text" id="state" class="form-control">
                                <span class="text-danger small" id="err6"></span>
                            </div>
                            <div class="col-md-4">
                                <label for="city" class="small">City</label>
                                <input type="text" class="form-control" id="city">
                                <span class="text-danger small" id="err7"></span>
                            </div>
                            <div class="col-md-4">
                                <label for="zip" class="small">Zip Code</label>
                                <input type="number" class="form-control" id="zip">
                                <span class="text-danger small" id="err8"></span>
                            </div>
                        </div>
    
                        <div class="row mt-3">
                           <div class="col-md-12">
                                <label for="address" class="small">Addres</label>
                                <textarea class="form-control" id="address" cols="30" rows="5"></textarea>
                                <span class="text-danger small" id="err9"></span>
                           </div>
                        </div> -->
    
                            <!-- <button type="button" style="float:right;" class="btn btn-danger mt-2" data-dismiss="modal">Close</button> -->
                            <button type="submit" style="float:right;" class="btn btn-success mt-2 mr-2">Save</button>
    
                        </form>
                    </div>
                </div><!-- /.modal-content -->
            </div><!-- /.modal-dialog -->
        </div><!-- /.modal -->
    
        <!--  Modal content ticket start -->
        <div class="modal fade" id="ticketModal" role="dialog"  data-backdrop="static" aria-labelledby="myLargeModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header d-flex align-items-center">
                        <h4 class="modal-title" id="myLargeModalLabel" style="color:#009efb;">Add Ticket</h4>
                        <button type="button" class="close ml-auto" data-dismiss="modal" aria-hidden="true">×</button>
                    </div>
                    <div class="modal-body">
                        <form class="form-horizontal" id="save_tickets" action="{{asset('save-tickets')}}" method="post">
                            <div class="row">
                                <div class="col-sm-12 col-xs-12">
                                    <fieldset>
                                        <div class="form-group">
                                            <div class="row mb-3">
                                                <div class="col-sm-12">
                                                    <label class="control-label col-sm-12">Subject<span
                                                            style="color:red !important;">*</span></label><span
                                                        id="select-subject"
                                                        style="display: none; color: red !important;">Subject cannot be
                                                        Empty </span>
                                                    <input class="form-control" type="text" id="subject" name="subject">
                                                </div>
                                            </div>
                                            <div class="row mb-3">
                                                <div class="col-sm-4">
                                                    <label class="control-label col-sm-12">Select Department<span
                                                            style="color:red !important;">*</span></label><span
                                                        id="select-department"
                                                        style="display :none; color:red !important;;">Please Select
                                                        Department</span>
                                                    <select class="select2 form-control custom-select" type="search"
                                                        id="dept_id" name="dept_id" style="width: 100%; height:36px;">
                                                        <option value="">Select </option>
                                                        @foreach($departments as $department)
                                                        <option value="{{$department->id}}">{{$department->name}}</option>
                                                        @endforeach
    
                                                    </select>
                                                </div>
                                                <div class="col-sm-4">
                                                    <label class="control-label col-sm-12">Select Priority<span
                                                            style="color:red !important;">*</span></label><span
                                                        id="select-priority"
                                                        style="display :none; color:red !important;">Please Select
                                                        Priority</span>
                                                    <select class="select2 form-control " id="priority" name="priority"
                                                        style="width: 100%; height:36px;">
                                                        <option value="">Select </option>
                                                        @foreach($priorities as $priority)
                                                        <option value="{{$priority->id}}">{{$priority->name}}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="col-sm-4">
                                                    <label class="control-label col-sm-12">Select Type
                                                        <span style="color:red !important;">*</span></label><span
                                                        id="select-type" style="display :none; color:red !important;">Please
                                                        Select Type</span>
                                                    <select class="select2 form-control" id="type" name="type"
                                                        style="width: 100%; height:36px;">
                                                        <option value="">Select</option>
                                                        @foreach($types as $type)
                                                            <option value="{{$type->id}}">{{$type->name}}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
    
                                        <div class="form-group">
                                            <div class="row mb-3">
                                                <div class="col-sm-12">
                                                    <label class="control-label col-sm-12">Problem Details<span
                                                            style="color:red !important;">*</span></label><span
                                                        id="pro-details" style="display :none; color:red !important;">Please
                                                        provide details</span>
                                                    <textarea class="form-control" rows="3" id="ticket_detail"
                                                        name="ticket_detail"></textarea>
                                                </div>
                                            </div>
                                        </div>
                                    </fieldset>
                                    <div class="text-right">
                                        <button type="submit" class="btn waves-effect waves-light btn-success"
                                            id="btnSaveTicket">
                                            <div class="spinner-border text-light" role="status"
                                                style="height: 20px; width:20px; margin-right: 8px; display: none;">
                                                <span class="sr-only">Loading...</span>
                                            </div>Save
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div><!-- /.modal-content -->
            </div><!-- /.modal-dialog -->
        </div><!-- /.modal content ticket end -->
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
    
    <!-- Modal -->
    <div class="modal fade" id="editPicModal" tabindex="-1" aria-labelledby="editPicModalLabel" data-backdrop="static" aria-hidden="true" >
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editPicModalLabel">Customer Picture</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true"></span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="text-center" id="prof-img ">
                        @php
                            $path = Session::get('is_live') == 1 ? 'public/' : '/';
                        @endphp
                        @if($customer->avatar_url != null)
                            @if(is_file(public_path( $path . $customer->avatar_url)))
                                <img src="{{ asset( $path . $customer->avatar_url)}}" class="rounded-circle" width="100" height="100" id="customer_modal_img" />
                            @else
                                <img src="{{ asset('public/default_imgs/customer.png')}}" class="rounded-circle" width="100" height="100" id="customer_modal_img" />
                            @endif
                        @else
                            <img src="{{ asset('public/default_imgs/customer.png')}}" class="rounded-circle" width="100" height="100" id="customer_modal_img" />
                        @endif
                    </div>
                    <form class="mt-4" id="upload_customer_img">
                        <div class="input-group">
                            <div class="custom-file">
                                <input type="hidden" name="customer_id" id="customer_id" value="{{$customer->id}}">
                                <input type="file" name="profile_img" class="form-control" id="customFilePP" accept="image/*">
                            </div>
                        </div>
                        <div class="text-right mt-3">
                            <button type="submit" class="btn btn-success float-right">Save changes</button>
                        </div>
    
                    </form>
                </div>
    
            </div>
        </div>
    </div>
    
    <!--Domain Modal -->
    <div class="modal fade" id="domainModal" tabindex="-1" aria-labelledby="domainModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-lg">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="domainModalLabel">More Information</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
             <div class="row">
                <div class="col-md-3">
                    <p>This is Line</p>
                    <p>This is Line</p>
                    <p>This is Line</p>
                    <p>This is Line</p>
                </div>
                <div class="col-md-3">
                    <p>This is Line</p>
                    <p>This is Line</p>
                    <p>This is Line</p>
                    <p>This is Line</p>
                </div>
                <div class="col-md-3">
                    <p>This is Line</p>
                    <p>This is Line</p>
                    <p>This is Line</p>
                    <p>This is Line</p>
                </div>
                <div class="col-md-3">
                    <button class="btn btn-success"> Add New Time </button>
                </div>
    
                
                <div class="col-md-12">
                    <hr>
                    <button type="button" class="btn btn-warning" >Update Name Servers</button>
                    <button type="button" class="btn btn-danger">Update DNS Zone Records</button>
                    <button type="button" class="btn btn-info" >Set Glue Records</button>
                </div>
                <div class="col-md-12 mt-3">
                    <div class="card">
                        <div class="card-header">
                            Header
                        </div>
                        <div class="card-body" style="max-height:200px;scroll-y:auto;">
                            Thinking
                        </div>
                    </div>
                </div>
                <div class="col-md-12 mt-3">
                    <div class="card">
                        <div class="card-header">
                            Header
                        </div>
                        <div class="card-body" style="max-height:200px;scroll-y:auto;">
                            Thinking
                        </div>
                    </div>
                </div>
             </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            <button type="button" class="btn btn-primary">Save changes</button>
          </div>
        </div>
      </div>
    </div>
    
    
    <!-- Payment Modal -->
    <div class="modal fade" id="payNow" tabindex="-1" aria-labelledby="payNowLabel" data-backdrop="static" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="payNowLabel">Payment Method</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body text-center" style="padding:60px;">
                    <h2>What Payment Method you want to use?</h2>
                    <div class="payBtns ">
    
                        <button class="btn btn-success"><i class="fab fa-cc-visa"></i> Credit Card</button>
                        <a class="btn bg-pPal btn-info" href="{{url('paypal/ec-checkout')}}" id="paypalHref"> <i
                                class="fab fa-paypal"></i> PayPal</a>
                    </div>
                </div>
    
            </div>
        </div>
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
    </div>
</div>
@endsection
@section('scripts')
<!-- jQuery ui files-->
<link rel="stylesheet" href="https://code.jquery.com/ui/1.12.0/themes/smoothness/jquery-ui.css">
<script src="https://code.jquery.com/ui/1.12.0/jquery-ui.min.js"></script>
<!-- <script src="{{asset('public/js/help_desk/asset_manager/template.js').'?ver='.rand()}}"></script> -->
@include('js_files.help_desk.asset_manager.templateJs')
@include('js_files.help_desk.asset_manager.actionsJs')
@include('js_files.help_desk.asset_manager.assetJs')
<!-- <script src="{{asset('public/js/help_desk/asset_manager/actions.js').'?ver='.rand()}}"></script> -->
<!-- <script src="{{asset('public/js/help_desk/asset_manager/asset.js').'?ver='.rand()}}"></script> -->
<!-- <script src="{{asset('public/js/customer_manager/customer_lookup/customerCard.js').'?ver='.rand()}}"></script> -->
<link rel="stylesheet" type="text/css" href="{{asset('assets/extra-libs/countdown/countdown.css')}}" />
<script type="text/javascript" src="{{asset('assets/extra-libs/countdown/countdown.js')}}"></script>

@include('js_files.customer_lookup.customerprofileJs')
@endsection