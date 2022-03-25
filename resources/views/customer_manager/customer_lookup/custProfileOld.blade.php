@extends('layouts.staff-master-layout')
@section('body-content')
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

<style>
.CollectJSInlineIframe {
    /* height: auto !important; */
}

/* .form-group {
    width: 290px;
} */

.formInner {
    font-family: 'Abel' !important;
    width: 500px;
    max-width: 100%;
    display: flex;
    flex-wrap: wrap;
    justify-content: space-between;
    margin: 20px auto;
}
.gmaps, .gmaps-panaroma {
    height: 300px !important;
    background: #e9ecef;
    width:100% !important;
    border-radius: 4px;
}

.payment-field {
    border-radius: 2px;
    width: 48%;
    margin-bottom: 14px;
    box-shadow: 0 2px 8px #dddddd;
    font-size: 16px;
    transition: 200ms;
}

.payment-field input:focus {
    border: 3px solid #1AD18E;
    outline: none !important;
}

.payment-field:hover {
    box-shadow: 0 2px 4px #dddddd;
}

.payment-field input {
    border: 3px solid #ffffff;
    width: 100%;
    border-radius: 2px;
    padding: 4px 8px;
}

#payment-fields {
    display: flex;
    flex-wrap: wrap;
    justify-content: space-between;
}

#ccnumber {
    width: 100%;
    font-size: 24px;
}

#ccexp,
#cvv {
    font-size: 20px;
}

#paymentTokenInfo {
    width: 600px;
    display: block;
    margin: 30px auto;
}

.separator {
    margin-top: 30px;
    width: 100%;
}

@media only screen and (max-width: 600px) {
    .pageTitle {
        font-size: 30px;
    }

    .theForm {
        width: 300px;
        max-width: 90%;
        margin: auto;
    }

    .form-group {
        width: 100%;
    }
}

input {
    border: 5px inset #808080;
    background-color: #c0c0c0;
    color: green;
    font-size: 25px;
    font-family: monospace;
    padding: 5px;
}

.appends {
    cursor: grab;
    cursor: -moz-grab;
    cursor: -webkit-grab;
}

.appends.highlight {
    border-left: 3px solid transparent !important;
}

.highlight {
    border-left: 3px solid blue;
    height: 70px;
}

td.details-control {
    background: url('https://www.datatables.net/examples/resources/details_open.png') no-repeat center center;
    cursor: pointer;
}

tr.shown td.details-control {
    background: url('https://www.datatables.net/examples/resources/details_close.png') no-repeat center center;
}

.bg-pPal {
    color: #fff;
    background-color: #005ea6;
}

.credit-pic img {
    width: 12%;
    padding-left: 6px;
}
table.dataTable thead .sorting:before, table.dataTable thead .sorting:after, table.dataTable thead .sorting_asc:before, table.dataTable thead .sorting_asc:after, table.dataTable thead .sorting_desc:before, table.dataTable thead .sorting_desc:after, table.dataTable thead .sorting_asc_disabled:before, table.dataTable thead .sorting_asc_disabled:after, table.dataTable thead .sorting_desc_disabled:before, table.dataTable thead .sorting_desc_disabled:after {
    /* position: absolute; */
    /* bottom: 0.9em; */
    display: none;
    opacity: 0;
}
table.dataTable thead .sorting_asc,
table.dataTable thead .sorting {
    background-image: none !important;
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
background-color:rgba(218,165,32,0.3);
}
#domainModal p{
    margin-bottom:0;
}
</style>
<!-- <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css"> -->

<!--Bread-Crum-->
<div class="page-breadcrumb">
    <div class="row">
        <div class="col-md-5 align-self-center">
            <h4 class="page-title">Customer</h4>
            <div class="d-flex align-items-center">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{route('home')}}">Home</a></li>
                        <li class="breadcrumb-item " aria-current="page">Customer Lookup</li>
                        <li class="breadcrumb-item active" aria-current="page">{{$customer->first_name}} {{$customer->last_name}}</li>
                    </ol>
                </nav>
            </div>
        </div>
        
    </div>
</div>



<div class="container-fluid">
    <!-- ============================================================== -->
    <!-- Start Page Content -->
    <!-- ============================================================== -->
    <!-- Row -->

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
                        @if(is_file(public_path('../files/user_photos/Customers/'.$customer->avatar_url)))
                        <img src="{{ asset('files/user_photos/Customers/'.$customer->avatar_url)}}"
                            class="rounded-circle" width="100" height="100" id="profile-user-img" />
                        @else
                        <img src="{{ asset('files/user_photos/logo.gif')}}" class="rounded-circle" width="100"
                            height="100" id="profile-user-img" />
                        @endif
                        <a type="button" data-toggle="modal" data-target="#editPicModal"><i
                                class="fa fa-pencil-alt picEdit"></i></a>

                        <h4 class="card-title mt-2" id="cust_name">{{$customer->first_name}} {{$customer->last_name}}
                        </h4>
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

                    <small class="text-muted  db">Phone</small>
                    <h6> <a href="tel:{{$customer->phone}}" id="cust_phone">{{$customer->phone}}</a> </h6>                    

                    <div id="adrs">
                        <small class="text-muted  db">Email Address</small>
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
                        <a class="nav-link active" id="pills-setting-tab" data-toggle="pill" href="#previous-month" role="tab"
                            aria-controls="pills-setting" aria-selected="false">User Details</a>
                        <!-- <a class="nav-link active" id="pills-user-detail" data-toggle="pill" href="#user-detail"
                            role="tab" aria-controls="pills-user-detail" aria-selected="true"></a> -->
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" id="pills-tickets-tab" data-toggle="pill" href="#tickets" role="tab"
                            aria-controls="pills-tickets" aria-selected="false">Tickets</a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" id="orders-profile-tab" data-toggle="pill" href="#orders" role="tab"
                            aria-controls="pills-profile" aria-selected="false">Orders</a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" id="subscription-profile-tab" data-toggle="pill" href="#subscription"
                            role="tab" aria-controls="pills-profile" aria-selected="false">Subscriptions</a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" id="pills-assets-tab" data-toggle="pill" href="#assets" role="tab"
                            aria-controls="pills-assets" aria-selected="false">Assets</a>
                    </li>

                    <!--<li class="nav-item">
                        <a class="nav-link" id="pills-setting-tab" data-toggle="pill" href="#previous-month" role="tab"
                            aria-controls="pills-setting" aria-selected="false">Setting</a>
                    </li>-->

                    <!-- <li class="nav-item">
                        <a class="nav-link" id="pills-timeline-tab" data-toggle="pill" href="#current-month" role="tab"
                            aria-controls="pills-timeline" aria-selected="true">History</a>
                    </li> -->

                    <li class="nav-item">
                        <a class="nav-link" id="payment-profile-tab" data-toggle="pill" href="#payment" role="tab"
                            aria-controls="pills-profile" aria-selected="false">Payments</a>
                    </li>
                    <!-- <li class="nav-item">
                        <a class="nav-link" id="notifications-profile-tab" data-toggle="pill" href="#notification"
                            role="tab" aria-controls="notifications-profile" aria-selected="false">Notifications</a>
                    </li> -->
                    <!-- <li class="nav-item">
                        <a class="nav-link" id="notes-profile-tab" data-toggle="pill" href="#ticket_notes" role="tab" aria-controls="pills-profile" aria-selected="false">Notes</a>
                    </li> -->

                    <li class="nav-item">
                        <a class="nav-link" id="domain-profile-tab" data-toggle="pill" href="#ticket_domain" role="tab" aria-controls="pills-profile" aria-selected="false">Domain</a>
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
                        @if($wp_value == 1)
                            <button class="float-right btn-sm rounded btn btn-info mr-3"><i class="fas fa-sync"></i> Sync WP Orders  </button>
                        @endif
                        <div class="table-responsive p-3 mt-3">
                            <div id="zero_config_wrapper"
                                class="dataTables_wrapper container-fluid dt-bootstrap4">

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
                    </div>

                    <div class="tab-pane fade" id="subscription" role="tabpanel"
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
                    </div>

                    <div class="tab-pane fade" id="payment" role="tabpanel" aria-labelledby="payment-profile-tab">

                        <hr>
                        
                            <div class="row">
                                
                                <div class="col-md-12">
                                <label class="col-md-12">ADD New Credit Card</label>
                                <div class="card-body">
                                    <form id="CardForm" class="CardForm" >
                                      
                                       <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <input type="text" class="form-control" placeholder="First Name" name="fname" id="fname" value="{{$customer->first_name}}" autofocus>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <input type="text" class="form-control" placeholder="Last Name" name="lname" id="lname" value="{{$customer->last_name}}">
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <input type="text" class="form-control" placeholder="Street Address" name="address1" id="address1" value="{{$customer->address}}">
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <input type="text" class="form-control" placeholder="City" name="city" id="city" value="{{$customer->cust_city}}">
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <select class="select2 form-control " id="state" name="state"
                                                    style="width: 100%; height:36px;">
                                                        <option value="">Select State</option>
                                                        @foreach($states as $state)
                                                        @if($customer->cust_state != NULL && $customer->cust_state == $state->id)
                                                        <option value="{{$state->name}}" selected>{{$state->name}}</option>
                                                        @else
                                                        <option value="{{$state->name}}">{{$state->name}}</option>
                                                        @endif
                                                        @endforeach
                                                    </select> 
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <input type="text" class="form-control" placeholder="Zip code" name="zip"  id="zip" value="{{$customer->cust_zip}}">
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
                            <div class="card-body">
                                <div class="row mt-3" id="pay-card">

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
                                                    <a class="custom-accordion-title d-flex align-items-center pt-2 pb-2 collapsed"
                                                        data-toggle="collapse" href="#collapseOne" aria-expanded="false"
                                                        aria-controls="collapseOne">
                                                        Add Asset Template <span class="ml-auto"><i
                                                                class="mdi mdi-chevron-down accordion-arrow"></i></span>
                                                    </a>
                                                </h5>
                                            </div>
                                            <div id="collapseOne" class="collapse" aria-labelledby="headingOne"
                                                data-parent="#accordion" style="">
                                                <div class="card-body">
                                                    <div class="container-fluid">
                                                        <div class="card">
                                                            <div class="row p-3">
                                                                <div class="col-md-12">
                                                                    <div class="form-group">
                                                                        <label for="tempTitle">Template Title</label>
                                                                        <input class="form-control" type="text"
                                                                            id="tempTitle" required=""
                                                                            placeholder="Title">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="row">
                                                            <div class="col-md-3">
                                                                <div class="card p-3">
                                                                    <a class="buttonPush"
                                                                        href="javascript:fieldAdd('text')">
                                                                        <div class="card border-cyan card-hover mb-2">
                                                                            <div class="box p-2 rounded">
                                                                                <h6 class="text-cyan mb-0"><i
                                                                                        class="fas fa-edit pr-2"></i>
                                                                                    Input Field</h6>
                                                                            </div>
                                                                        </div>
                                                                    </a>
                                                                    <a class="buttonPush"
                                                                        href="javascript:fieldAdd('phone')">
                                                                        <div class="card border-cyan card-hover mb-2">
                                                                            <div class="box p-2 rounded">
                                                                                <h6 class="text-cyan mb-0"><i
                                                                                        class="fas fa-phone pr-2"></i>
                                                                                    Phone Number</h6>
                                                                            </div>
                                                                        </div>
                                                                    </a>
                                                                    <a class="buttonPush"
                                                                        href="javascript:fieldAdd('email')">
                                                                        <div class="card border-cyan card-hover mb-2">
                                                                            <div class="box p-2 rounded">
                                                                                <h6 class="text-cyan mb-0"><i
                                                                                        class="fas fa-envelope pr-2"></i>
                                                                                    Email</h6>
                                                                            </div>
                                                                        </div>
                                                                    </a>
                                                                    <a href="javascript:fieldAdd('textbox')"
                                                                        class="buttonPush">
                                                                        <div class="card border-cyan card-hover mb-2">
                                                                            <div class="box p-2 rounded">
                                                                                <h6 class="text-cyan"><i
                                                                                        class="fas fa-indent pr2"></i>
                                                                                    Text Area</h6>
                                                                            </div>
                                                                        </div>
                                                                    </a>
                                                                    <a class="buttonPush"
                                                                        href="javascript:fieldAdd('selectbox')">
                                                                        <div class="card border-cyan card-hover mb-2">
                                                                            <div class="box p-2 rounded ">
                                                                                <h6 class="text-cyan"><i
                                                                                        class="fas fa-chevron-circle-down pr-2"></i>
                                                                                    Select</h6>
                                                                            </div>
                                                                        </div>
                                                                    </a>
                                                                    <a class="buttonPush"
                                                                        href="javascript:fieldAdd('password')">
                                                                        <div class="card border-cyan card-hover mb-2">
                                                                            <div class="box p-2 rounded">
                                                                                <h6 class="text-cyan"><i
                                                                                        class="fas fa-key pr-2"></i>Password
                                                                                </h6>
                                                                            </div>
                                                                        </div>
                                                                    </a>
                                                                    <a class="buttonPush"
                                                                        href="javascript:fieldAdd('ipv4')">
                                                                        <div class="card border-cyan card-hover mb-2">
                                                                            <div class="box p-2 rounded">
                                                                                <h6 class="text-cyan"><i
                                                                                        class="fas fa-qrcode pr-2"></i>IPv4
                                                                                </h6>
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
                                                                        <button type="button"
                                                                            class="btn btn-success float-right "
                                                                            onclick="saveTemplate()"> Save Template
                                                                        </button>
                                                                        <button type="button"
                                                                            class="btn btn-info float-right mr-2"
                                                                            onclick=""> Preview </button>
                                                                    </div>
                                                                </div>
                                                                <div class="card">
                                                                    <div class="row p-3">
                                                                        <div class="col-md-12 pt-3 ">
                                                                            <div class="head text-center ">
                                                                                <h4> Please Insert a Field Here from
                                                                                    Insert Field Button </h4>
                                                                            </div>
                                                                            <div id="cardycard">
                                                                            </div>
                                                                            <div class="tail" id="card-colors"
                                                                                style="display:;">
                                                                                <div class="row connectedSortable border"
                                                                                    id="sortable-row-start"
                                                                                    style="min-height:10px; display: none;">
                                                                                    <div class="appends d-none"></div>
                                                                                </div>
                                                                                <div class="row connectedSortable border"
                                                                                    id="sortable-row-last"
                                                                                    style="min-height:10px; display: none;">
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
                                                    <a class="custom-accordion-title collapsed d-flex align-items-center pt-2 pb-2"
                                                        data-toggle="collapse" href="#collapseTwo" aria-expanded="false"
                                                        aria-controls="collapseTwo">
                                                        Add Asset <span class="ml-auto"><i
                                                                class="mdi mdi-chevron-down accordion-arrow"></i></span>
                                                    </a>
                                                </h5>
                                            </div>
                                            <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo"
                                                data-parent="#accordion">
                                                <div class="card-body">
                                                    <form class="form-horizontal" id="save_asset_form"
                                                        enctype="multipart/form-data" action="{{asset('/save-asset')}}"
                                                        method="post">
                                                        <div class="form-row">
                                                            <div class="col-md-12 form-group">
                                                                <div class="form-group">
                                                                    <label>Asset Template</label>
                                                                    <select class="select form-control"
                                                                        onchange="getFields(this.value)" id="form_id"
                                                                        name="form_id" required></select>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="form-row" id="templateTitle" style="display:none;">
                                                            <div class="col-md-12 form-group">
                                                                <div class="form-group">
                                                                    <label>Asset Title</label>
                                                                    <input type="text" name="asset_title"
                                                                        id="asset_title"
                                                                        class="asset_title form-control">
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="row" id="form-fields"></div>

                                                        <button type="submit" class="btn btn-success mt-3"
                                                            style="float:right;">Save</button>
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


                                            <div class="row">
                                                <div class="col-md-12" style="text-align:right;">
                                                    <select class="multiple-select mt-2 mb-2" name="as_select"
                                                        id="as_select" placeholder="Show/Hide" multiple="multiple"
                                                        selected="selected">
                                                        <option value="0">Sr#</option>
                                                        <option value="1">AssetID</option>
                                                        <option value="2">Template</option>
                                                        <option value="3">Customer</option>
                                                        <option value="4">Company</option>
                                                        <option value="5">Projects</option>
                                                        <option value="6">Actions</option>
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="table-responsive">
                                                <table id="asset-table-list"
                                                    class="table table-striped table-bordered no-wrap asset-table-list">
                                                    <thead>
                                                        <tr>
                                                            <th>
                                                                <div class="text-center"><input type="checkbox"
                                                                        id="checkAll" name="assets[]" value="0"></div>
                                                            </th>
                                                            <th>ID</th>
                                                            <th>Asset Title</th>
                                                            <th>Template Name</th>
                                                            <th>Customer</th>
                                                            <th>Company</th>
                                                            <th>Projects</th>
                                                            <th>Monitored </th>
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
                                <a href="{{url('add-ticket')}}/{{$customer->id}}" class="btn btn-info ml-auto mb-auto">
                                    <i class="fas fa-plus"></i>&nbsp;Add ticket
                                </a>
                                <!-- <button type="button" class="btn btn-info ml-auto mb-auto" onclick="ShowTicketsModel()">
                                    <i class="fas fa-plus"></i>&nbsp;Add ticket
                                </button> -->
                            </div>
                            <div class="row mt-3">
                                <div class="col-6 col-md-4">
                                    <div class="card card-hover border-bottom border-warning">
                                        <div class="box p-2 rounded text-center">
                                            <h1 class=" " id="my_tickets_count">0</h1>
                                            <h6 class="text-warning">Total Tickets</h6>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-6 col-md-4">
                                    <div class="card card-hover border-bottom border-primary">
                                        <div class="box p-2 rounded text-center">
                                            <h1 class="" id="open_tickets_count">0</h1>
                                            <h6 class="text-primary">Open</h6>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-6 col-md-4">
                                    <div class="card card-hover border-bottom border-success">
                                        <div class="box p-2 rounded  text-center">
                                            <h1 class="" id="closed_tickets_count">0</h1>
                                            <h6 class="text-success">Closed</h6>
                                        </div>
                                    </div>
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
                                <table id="ticket_table" style="table-layout: fixed"
                                    class="table table-striped table-bordered display no-wrap">
                                    <thead>
                                        <tr>
                                            <th>
                                                <div class="text-center"><input type="checkbox" name="select_all[]"
                                                        id="select-all"></div>
                                            </th>
                                            <th></th>
                                            <th>Status</th>
                                            <th>Subject</th>
                                            <th>TicketID</th>
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
                                    <tbody>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <div class="tab-pane fade active show" id="previous-month" role="tabpanel" aria-labelledby="pills-setting-tab">
                        <div class="card-body">
                            <form id="update_customer" action="{{asset('/update-user')}}" method="POST">
                                <h2 class="mt-4 font-weight-bold text-dark">Personal Info</h2>

                                <div class="form-row">
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

                                <div class="form-row">
                                @if($customer->has_account != 0)
                                    <div class="col-md-4 form-group">
                                        <label>Password</label>
                                        <div class="user-password-div">
                                            <span class="block input-icon input-icon-right">
                                                <input type="password" name="password" id="password"
                                                    placeholder="password" class="form-control form-control-line"
                                                    value="{{$customer->password}}">
                                                <span toggle="#password-field"
                                                    class="fa fa-fw fa-eye field-icon show-password-btn mr-2"></span>
                                            </span>
                                        </div>
                                    </div>
                                    <div class="col-md-4 form-group">
                                        <label>Confirm Password</label>
                                        <div class="user-confirm-password-div">
                                            <input name="confirm_password" class="form-control form-control-line"
                                                type="password" placeholder="Confirm Password"
                                                value="{{$customer->password}}">
                                            <span toggle="#password-field"
                                                class="fa fa-fw fa-eye field-icon show-confirm-password-btn mr-2"></span>
                                        </div>
                                    </div>
                                @endif

                                    <div class="col-md-4 form-group">
                                        <label>Phone No</label>
                                        <input type="text" id="prof_phone" name="prof_phone"
                                            value="{{$customer->phone}}" class="form-control form-control-line">
                                            <span class="text-danger small" id="phone_error"></span>
                                    </div>
                                </div>

                                <div class="form-row">
                                    <div class="col-md-6 form-group">
                                        <label>Customer Type</label>
                                        <div class=" d-flex justify-content-center">
                                            <select id="cust_type" name="cust_type"
                                                class="form-control form-control-line">
                                                <option value="">Customer Type</option>
                                                @foreach ($customer_types as $item)
                                                <option value="{{$item->id}}">{{$item->name}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6 form-group">
                                        <label>Select Company</label>
                                        <div class=" d-flex justify-content-center">
                                            <select id="company_id" name="company_id"
                                                class="form-control form-control-line">
                                                <option value="">Select Company</option>

                                                @foreach ($company as $item)
                                                <option value="{{$item->id}}"
                                                    {{$item->id == $customer->company_id ? 'selected' : ''}}>
                                                    {{$item->name}}</option>
                                                @endforeach
                                            </select>
                                            <button type="button" data-toggle="modal" data-target="#addCompanyModal"
                                                id="new-company" class="btn btn-info">New</button>
                                        </div>
                                    </div>
                                </div>

                                @if($customer->has_account == 0)
                                <div class="form-row mb-2 mt-2">
                                    <div class="custom-control custom-checkbox mr-sm-2">
                                        <input type="checkbox" class="custom-control-input" id="customer_login">
                                        <label class="custom-control-label" for="customer_login">Create Customer Login
                                            Account</label>
                                    </div>
                                </div>
                                @endif

                                <input type="hidden" name="customer_id" value="{{$customer->id}}">

                                <div class="form-row">
                                    <div class="col-12 form-group">
                                        <label>Street Address</label>
                                        <a type="button" data-toggle="modal" data-target="#Address-Book"
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

                                <div class="form-row">
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
                                            <select class="select2 form-control " id="prof_state" name="prof_state"
                                                style="width: 100%; height:36px;">
                                                <option value="">Select State</option>
                                                @foreach($states as $state)
                                                    @if($customer->cust_state != NULL && $customer->cust_state == $state->name)
                                                        <option value="{{$state->name}}" selected>{{$state->name}}</option>
                                                    @else
                                                    <option value="{{$state->name}}">{{$state->name}}</option>
                                                    @endif
                                                @endforeach
                                            </select>
                                        @endif
                                        
                                    </div>
                                    <div class="col-md-3 form-group">
                                        <label>Zip Code</label>
                                        <input type="tel" maxlength="5" class="form-control"
                                            value="{{$customer->cust_zip}}" id="prof_zip">
                                    </div>

                                    <div class="col-md-3 form-group">
                                        <label>Country</label>
                                        @if($google_key == 1)
                                            <input type="text" class=" form-control " value="{{$customer->country}}" id="prof_country" 
                                                name="prof_country" style="width: 100%; height:36px;">
                                        @else
                                            <select class="select2 form-control " id="prof_country" name="prof_country"
                                                style="width: 100%; height:36px;">
                                                <option value="">Select Country</option>
                                                    @if($customer->country != null && $customer->country != '' ||  $customer->country == $countries->name)
                                                        <option value="{{$countries->name}}" selected>{{$countries->name}}</option>
                                                    @else
                                                        <option value="{{$countries->name}}" >{{$countries->name}}</option>
                                                    @endif
                                            </select>
                                        @endif

                                    </div>
                                    <div class="col-md-12 form-group">
                                        <input id="is_bill_add" type="checkbox" name="is_bill_add"
                                            {{$customer->is_bill_add == 1 ? 'checked' : ''}}>
                                        <label class="mb-0" for="is_bill_add">Bill To & Ship To Addresses Are
                                            Different</label>
                                    </div>

                                </div>

                                <div class="form-row" id="compBillAdd"
                                    style="display:{{$customer->is_bill_add == 1 ? 'flex' : 'none'}}">
                                    <div class="col-12 form-group">
                                        <label>Street Address</label>
                                        <!-- <a type="button" data-toggle="modal" data-target="#Address-Book"  class="float-right" style="color:#009efb;">Address Book</a> -->
                                        <div class="row">
                                            <div class="col-md-6">
                                                <input type="text" class=" form-control"
                                                    value="{{$customer->bill_st_add}}" id="bill_st_add"
                                                    name="bill_st_add" placeholder="House number and street name" {{$customer->is_bill_add == 1 ? 'required' : ''}}>
                                            </div>
                                            <div class="col-md-6">
                                                <input type="text" class=" form-control"
                                                    value="{{$customer->bill_apt_add}}" id="bill_apt_add"
                                                    name="bill_apt_add"
                                                    placeholder="Apartment, suit, unit etc. (optional)">
                                            </div>
                                        </div>

                                        <!-- <textarea class="form-control" name="address" id="update_address" rows="3">{{$customer->address}}</textarea> -->
                                    </div>
                                    <div class="col-md-3 form-group">
                                        <label>City</label>
                                        <input type="text" class="form-control" value="{{$customer->bill_add_city}}"
                                            id="bill_add_city" name="bill_add_city">
                                    </div>
                                    <div class="col-md-3 form-group">
                                        <label>State</label>
                                        @if($google_key == 1)
                                            <input type="text" class=" form-control " value="{{$customer->bill_add_state}}" id="bill_add_state" name="bill_add_state"
                                                style="width: 100%; height:36px;">
                                        @else
                                        <select class="select2 form-control " id="bill_add_state" name="bill_add_state"
                                            style="width: 100%; height:36px;">
                                            <option value="">Select State</option>
                                            @foreach($states as $state)
                                            @if($customer->bill_add_state != NULL && $customer->bill_add_state ==
                                            $state->id)
                                            <option value="{{$state->id}}" selected>{{$state->name}}</option>
                                            @else
                                            <option value="{{$state->id}}">{{$state->name}}</option>
                                            @endif
                                            @endforeach
                                        </select>
                                        @endif
                                        
                                    </div>
                                    <div class="col-md-3 form-group">
                                        <label>Zip Code</label>
                                        <input type="tel" maxlength="5" class="form-control"
                                            value="{{$customer->bill_add_zip}}" id="bill_add_zip" name="bill_add_zip">
                                    </div>


                                    <div class="col-md-3 form-group">
                                        <div class="form-group">
                                            <label>Country</label>
                                            
                                            @if($google_key == 1)
                                            <input type="text" class=" form-control " value="{{$customer->bill_add_country}}" id="bill_add_country" 
                                                name="bill_add_country" style="width: 100%; height:36px;">
                                        @else
                                            <select class="select2 form-control " id="bill_add_country"
                                                name="bill_add_country" style="width: 100%; height:36px;">
                                                <option value="">Select Country</option>
                                                <option value="{{$countries->id}}" {{$customer->bill_add_country == $countries->id ? "selected" : ''}}>{{$countries->name}}</option>
                                            </select>
                                        @endif
                                        </div>
                                    </div>
                                </div>

                                <h2 class="mt-4 font-weight-bold text-dark">Social</h2>

                                <div class="form-row">
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

                                <div class="form-row">
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

                                <div class="form-row">
                                    <div class="col-md-6 form-group">
                                        <label>Linkedin</label>
                                        <input type="text" class="form-control" id="prof_linkedin"
                                            value="{{$customer->linkedin}}"
                                            placeholder="https://linkedin.com/@Username">
                                    </div>
                                </div>


                                <input type="hidden" name="customer-id" id="customer-id" value="{{$customer->id}}">
                                <div class="row">
                                    <div class="col-md-12 form-group text-right">
                                        <div>
                                            <button type="submit" id="saveBtn" class="btn btn-success">Update
                                                Profile</button>
                                            <button style="display:none" id="processing" class="btn btn-success"
                                                type="button" disabled><i class="fas fa-circle-notch fa-spin"></i>
                                                Processing</button>
                                        </div>
                                    </div>

                                </div>
                                
                            </form>
                        </div>
                    </div>

                    <!-- <div class="tab-pane fade" id="ticket_notes" role="tabpanel" aria-labelledby="notes-profile-tab">
                        <hr>
                        <div class="card-body">
                            No Data Found.
                        </div>
                    </div> -->

                    <div class="tab-pane fade" id="ticket_domain" role="tabpanel" aria-labelledby="domain-profile-tab">
                        <hr>
                        <div class="card-body">
                            <div class="table-responsive p-3 mt-3">
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
                                                        <td> <a type="button" data-toggle="modal" data-target="#domainModal"><i class="fas fa-angle-right"></i> <span style="font-size:20px;"> www.king.cin</span></a></td>
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
                    <button type="button" class="close ml-auto" data-dismiss="modal" aria-hidden="true">×</button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12" style="text-align:right;">
                            <div class="col-md-12">
                                <button id="add_new_add" class=" float-right btn btn-success"
                                    onclick="New_Bill_Add()"><i class="mdi mdi-plus-circle"></i> Add New </button>
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
                            <div class="col-md-3 form-group">
                                <label>City</label>
                                <input type="text" class="form-control">
                            </div>
                            <div class="col-md-3 form-group">
                                <label>Zip Code</label>
                                <input type="text" class="form-control">
                            </div>

                            <div class="col-md-3 form-group">
                                <label>State</label>
                                <input type="text" class="form-control">
                            </div>
                            <div class="col-md-3 form-group">
                                    <label>Country</label>
                                    <input type="text" class="form-control">
                            </div>
                            <div class="col-md-12 float-right">
                                <button type="submit" style="float:right;" class="btn btn-success ">Save</button>
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
                    <button type="button" class="close ml-auto" data-dismiss="modal" aria-hidden="true">×</button>
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

                        <div class="row mt-3">
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
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="text-center" id="prof-img ">
                    @if(is_file(public_path('../files/user_photos/Customers/'.$customer->avatar_url)))
                    <img src="{{ asset('files/user_photos/Customers/'.$customer->avatar_url)}}" class="rounded-circle"
                        width="100" height="100" id="profile-user-img" />
                    @else
                    <img src="{{ asset('files/user_photos/logo.gif')}}" class="rounded-circle" width="100" height="100"
                        id="profile-user-img" />
                    @endif
                </div>
                <form class="mt-4" id="upload_customer_img">
                    <div class="input-group">
                        <div class="custom-file">
                            <input type="hidden" name="customer_id" id="customer_id" value="{{$customer->id}}">
                            <input type="file" name="profile_img" class="custom-file-input" id="customFilePP" accept="image/*">
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
@endsection
@section('scripts')

<!-- jQuery ui files-->
<link rel="stylesheet" href="https://code.jquery.com/ui/1.12.0/themes/smoothness/jquery-ui.css">
<script src="https://code.jquery.com/ui/1.12.0/jquery-ui.min.js"></script>
<script src="{{asset('public/js/help_desk/asset_manager/template.js').'?ver='.rand()}}"></script>
<script src="{{asset('public/js/help_desk/asset_manager/actions.js').'?ver='.rand()}}"></script>
<script src="{{asset('public/js/help_desk/asset_manager/asset.js').'?ver='.rand()}}"></script>
<!-- <script src="{{asset('public/js/customer_manager/customer_lookup/customerCard.js').'?ver='.rand()}}"></script> -->
<script type="text/javascript">
        document.addEventListener('DOMContentLoaded', function () {
                CollectJS.configure({
                    "paymentSelector" : "#payButton",
                    "variant" : "lightbox",
                    "styleSniffer" : "false",
                    "googleFont": "Montserrat:400",
                    "customCss" : {
                        "color": "#0000ff",
                        "background-color": "#d0d0ff"
                    },
                    "invalidCss": {
                        "color": "white",
                        "background-color": "red"
                    },
                    "validCss": {
                        "color": "black",
                        "background-color": "#d0ffd0"
                    },
                    "placeholderCss": {
                        "color": "green",
                        "background-color": "#808080"
                    },
                    "focusCss": {
                        "color": "yellow",
                        "background-color": "#202020"
                    },
                    "fields": {
                        "ccnumber": {
                            "selector": "#ccnumber",
                            "title": "Card Number",
                            "placeholder": "0000 0000 0000 0000"
                        },
                        "ccexp": {
                            "selector": "#ccexp",
                            "title": "Card Expiration",
                            "placeholder": "00 / 00"
                        },
                        "cvv": {
                            "display": "show",
                            "selector": "#cvv",
                            "title": "CVV Code",
                            "placeholder": "***"
                        },
                       
                      
                      
                    },
                    'validationCallback' : function(field, status, message) {
                        if (status) {
                            var message = field + " is now OK: " + message;
                        } else {
                            var message = field + " is now Invalid: " + message;
                        }
                        console.log(message);
                    },
                    "timeoutDuration" : 2000,
                    "timeoutCallback" : function () {
                        console.log("The tokenization didn't respond in the expected timeframe.  This could be due to an invalid or incomplete field or poor connectivity");
                    },
                    "fieldsAvailableCallback" : function () {
                        console.log("Collect.js loaded the fields onto the form");
                    },
                    'callback' : function(response) {
                        console.log(response);
                        var input = document.createElement("input");
                        $('#card_type').val(response.card.type)
                        $('#cardlastDigits').val(response.card.number)
                        $('#exp').val(response.card.exp)
                        $('#payment_token').val(response.token).trigger('change')
                      
                        // CardSubmit();
                        
                    }
                });
            });
    document.addEventListener('DOMContentLoaded', function () {
        CollectJS.configure({
            "paymentSelector" : "#payButton",
            "variant" : "lightbox",
            "styleSniffer" : "false",
            "googleFont": "Montserrat:400",
            "customCss" : {
                "color": "#0000ff",
                "background-color": "#d0d0ff"
            },
            "invalidCss": {
                "color": "white",
                "background-color": "red"
            },
            "validCss": {
                "color": "black",
                "background-color": "#d0ffd0"
            },
            "placeholderCss": {
                "color": "green",
                "background-color": "#808080"
            },
            "focusCss": {
                "color": "yellow",
                "background-color": "#202020"
            },
            "fields": {
                "ccnumber": {
                    "selector": "#ccnumber",
                    "title": "Card Number",
                    "placeholder": "0000 0000 0000 0000"
                },
                "ccexp": {
                    "selector": "#ccexp",
                    "title": "Card Expiration",
                    "placeholder": "00 / 00"
                },
                "cvv": {
                    "display": "show",
                    "selector": "#cvv",
                    "title": "CVV Code",
                    "placeholder": "***"
                }, 
            },
            'validationCallback' : function(field, status, message) {
                if (status) {
                    var message = field + " is now OK: " + message;
                } else {
                    var message = field + " is now Invalid: " + message;
                }
                console.log(message);
            },
            "timeoutDuration" : 2000,
            "timeoutCallback" : function () {
                console.log("The tokenization didn't respond in the expected timeframe.  This could be due to an invalid or incomplete field or poor connectivity");
            },
            "fieldsAvailableCallback" : function () {
                console.log("Collect.js loaded the fields onto the form");
            },
            'callback' : function(response) {
                console.log(response);
                var input = document.createElement("input");
                $('#card_type').val(response.card.type)
                $('#cardlastDigits').val(response.card.number)
                $('#exp').val(response.card.exp)
                $('#payment_token').val(response.token).trigger('change')
            }
        });
    });

    let autocomplete;
    let autocomplete1;
    let address1Field;
    let address2Field;
    let address11Field;
    let address21Field;
    let postal1Field;

    function initMapReplace(){
    
        address1Field = document.querySelector("#prof_address");
        address2Field = document.querySelector("#apt_address");
        address11Field = document.querySelector("#bill_st_add");
        address21Field = document.querySelector("#bill_apt_add");
        postalField = document.querySelector("#prof_zip");
        postal1Field = document.querySelector("#bill_add_zip");
        
        // Create the autocomplete object, restricting the search predictions to
        // addresses in the US and Canada.
        // console.log(address1Field);google.maps.places.SearchBox
        if(address1Field.value) {
            autocomplete = new google.maps.places.Autocomplete(address1Field, {
                componentRestrictions: { country: ["us", "ca"] },
                fields: ["address_components", "geometry","name"],
               
                types: ["address"],
            });
            address1Field.focus();
            
             autocomplete.addListener("place_changed", fillInAddress);
            // console.log(address1Field);
            
            // When the user selects an address from the drop-down, populate the
            // address fields in the form.
            // $("#map_2").html('');
            $("#map_2").html('<iframe width="100%" frameborder="0" style="    height: -webkit-fill-available;" src="https://www.google.com/maps/embed/v1/place?key='+  $("#google_api_key").val()+'&q=' + address1Field.value + '&language=en"></iframe>')
        }

        if(address11Field.value) {
            autocomplete1 = new google.maps.places.Autocomplete(address11Field, {
                componentRestrictions: { country: ["us", "ca"] },
                fields: ["address_components", "geometry"],
                types: ["address"],
            });
            if(address11Field != null) {
                address11Field.focus();
            }
            
        }

        autocomplete1.addListener("place_changed", fillInAddress1);
    }

    function fillInAddress() {
        // Get the place details from the autocomplete object.
        

        const place = autocomplete.getPlace();
        let address1 = "";
        let postcode = "";
        
        // Get each component of the address from the place details,
        // and then fill-in the corresponding field on the form.
        // place.address_components are google.maps.GeocoderAddressComponent objects
        // which are documented at http://goo.gle/3l5i5Mr
        for (const component of place.address_components) {
            const componentType = component.types[0];

            switch (componentType) {
            case "street_number": {
                address1 = `${component.long_name} ${address1}`;
                break;
            }

            case "route": {
                address1 += component.short_name;
                break;
            }

            case "postal_code": {
                postcode = `${component.long_name}${postcode}`;
                break;
            }

            case "postal_code_suffix": {
                postcode = `${postcode}-${component.long_name}`;
                break;
            }
            case "locality":
                document.querySelector("#prof_city").value = component.long_name;
                break;

            case "administrative_area_level_1": {
                document.querySelector("#prof_state").value = component.short_name;
                break;
            }
            case "country":
                document.querySelector("#prof_country").value = component.long_name;
                break;
            }
        }
        address1Field.value = address1;
        if($("#prof_address").val()) {
            $("#map_2").html('<iframe width="100%" frameborder="0" style="    height: -webkit-fill-available;" src="https://www.google.com/maps/embed/v1/place?key='+  $("#google_api_key").val()+'&q=' + $("#prof_address").val() + '&language=en"></iframe>')
        }

        postalField.value = postcode;
        // After filling the form with address components from the Autocomplete
        // prediction, set cursor focus on the second address line to encourage
        // entry of subpremise information such as apartment, unit, or floor number.
        address2Field.focus();
        
    }
    function fillInAddress1() {
        // Get the place details from the autocomplete object.
        const place = autocomplete1.getPlace();
        let address1 = "";
        let postcode = "";

        // Get each component of the address from the place details,
        // and then fill-in the corresponding field on the form.
        // place.address_components are google.maps.GeocoderAddressComponent objects
        // which are documented at http://goo.gle/3l5i5Mr
        for (const component of place.address_components) {
            const componentType = component.types[0];

            switch (componentType) {
            case "street_number": {
                address1 = `${component.long_name} ${address1}`;
                break;
            }

            case "route": {
                address1 += component.short_name;
                break;
            }

            case "postal_code": {
                postcode = `${component.long_name}${postcode}`;
                break;
            }

            case "postal_code_suffix": {
                postcode = `${postcode}-${component.long_name}`;
                break;
            }
            case "locality":
                document.querySelector("#bill_add_city").value = component.long_name;
                break;

            case "administrative_area_level_1": {
                document.querySelector("#bill_add_state").value = component.short_name;
                break;
            }
            case "country":
                document.querySelector("#bill_add_country").value = component.long_name;
                break;
            }
        }
        address11Field.value = address1;
        postal1Field.value = postcode;
        // After filling the form with address components from the Autocomplete
        // prediction, set cursor focus on the second address line to encourage
        // entry of subpremise information such as apartment, unit, or floor number.
        address21Field.focus();
        
    }
    
    $(document).ready(function() {
        var googleObject = {!! json_encode($google) !!};
        console.log(googleObject);
        if(!$.isEmptyObject(googleObject)){

            if( googleObject.hasOwnProperty('api_key')){

                var api_key = googleObject.api_key;
                $("#google_api_key").val(api_key);
                console.log(api_key);
                
                if(api_key!=''){

                    var script ="https://maps.googleapis.com/maps/api/js?key="+api_key+"&libraries=places&sensor=false&callback=initMapReplace";
                    var s = document.createElement("script");
                    s.type = "text/javascript";
                    s.src = script;
                    $("head").append(s);

                }
                const allScripts = document.getElementsByTagName( 'script' );
                [].filter.call(
                allScripts, 
                ( scpt ) => scpt.src.indexOf( 'key='+api_key ) >0
                )[ 0 ].remove();
                        // window.google = {};
            }
        }
        
        get_cust_card();
        var url = window.location.href;
        if (window.location.href.indexOf("#Success") > -1) {
            Swal.fire({
                position: 'top-end',
                icon: 'success',
                title: "Order paid SuccessFully! ",
                showConfirmButton: false,
                timer: 2500
            });
            window.location = url.split("#")[0];
        }
        if (window.location.href.indexOf("#Error") > -1) {
            Swal.fire({
                position: 'top-end',
                icon: 'error',
                title: "Order payment Failed! ",
                showConfirmButton: false,
                timer: 2500
            })
            window.location = url.split("#")[0];
        }

    
    });

    $(document).ready(function() {
        

        $("#twt").click(function(e) {
            e.preventDefault();
            var value = $(this).attr('href');
            if (value == '') {
                $("#social-error").html("Twitter Link is Missing");
                setTimeout(() => {
                    $("#social-error").html("");
                }, 5000);
            } else {
                window.open(value, '_blank');
            }
        });

        $("#fb_icon").click(function(e) {
            e.preventDefault();
            var value = $(this).attr('href');
            if (value == '') {
                $("#social-error").html("Facebook Link is Missing");
                setTimeout(() => {
                    $("#social-error").html("");
                }, 5000);
            } else {
                window.open(value, '_blank');
            }
        });

        $("#pintrst").click(function(e) {
            e.preventDefault();
            var value = $(this).attr('href');
            if (value == '') {
                $("#social-error").html("Pinterest Link is Missing");
                setTimeout(() => {
                    $("#social-error").html("");
                }, 5000);
            } else {
                window.open(value, '_blank');
            }
        });

        $("#inst").click(function(e) {
            e.preventDefault();
            var value = $(this).attr('href');
            if (value == '') {
                $("#social-error").html("Instagram Link is Missing");
                setTimeout(() => {
                    $("#social-error").html("");
                }, 5000);
            } else {
                window.open(value, '_blank');
            }
        });

        $("#lkdn").click(function(e) {
            e.preventDefault();
            var value = $(this).attr('href');
            if (value == '') {
                $("#social-error").html("Linkedin Link is Missing");
                setTimeout(() => {
                    $("#social-error").html("");
                }, 5000);
            } else {
                window.open(value, '_blank');
            }
        });


        $(".user-password-div").on('click', '.show-password-btn', function() {
            $(this).toggleClass("fa-eye fa-eye-slash");
            var input = $(".user-password-div input[name='password']");
            if (input.attr("type") === "password") {
                input.attr("type", "text");
            } else {
                input.attr("type", "password");
            }
        });
        

        function readURL(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();

                reader.onload = function(e) {
                    $('#ppNew').attr('src', e.target.result);
                }

                reader.readAsDataURL(input.files[0]);
            }
        }

        function readURL1(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();

                reader.onload = function(e) {
                    $('#profile-user-img').attr('src', e.target.result);
                }

                reader.readAsDataURL(input.files[0]);
            }
        }

        $("#upload_customer_img").submit(function(e) {
            e.preventDefault();

            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                },
                url: "{{url('upload_customer_img')}}",
                type: 'POST',
                data: new FormData(this),
                dataType: 'JSON',
                contentType: false,
                cache: false,
                processData: false,
                success: function(data) {
                    if (data.status == 200 && data.success == true) {
                        toastr.success(data.message, {
                            timeOut: 5000
                        });
                        $("#editPicModal").modal('hide');
                        var ter = $(".custom-file-label").text();

                        let url = '{{asset("files/user_photos/Customers")}}';
                        $('#profile-user-img').attr('src', url + '/' + ter);
                    } else {
                        toastr.error(data.message, {
                            timeOut: 5000
                        });
                    }
                    console.log(data, "data");
                },
                error: function(e) {
                    console.log(e)
                }

            });
        });

        $("#customFilePP").change(function() {
            // alert("Bingo");
            var ter = $("#customFilePP").val();
            // alert(ter);
            var terun = ter.replace(/^.*\\/, "");
            $(".custom-file-label").text(terun);
            readURL(this);
        });

    });
</script>
<script type="text/javascript">
    let tickets_table_list = '';
    let ticketsList = [];

    var customer_subscription_table = '';
    var subscriptionsList = {!!json_encode($subscriptions) !!};


    let customer = {!!json_encode($customer) !!};
    let ticket_format = {!!json_encode($ticket_format) !!};
    let statuses_list = {!!json_encode($statuses) !!};

    let open_status_id = statuses_list[statuses_list.map(function(itm) {
        return itm.name
    }).indexOf('Open')].id;
    let closed_status_id = statuses_list[statuses_list.map(function(itm) {
        return itm.name
    }).indexOf('Close')].id;


    var orders_table_list = '';
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
        }
    });

    // asset templates data
    var get_assets_route = "{{asset('/get-assets')}}";
    var del_asset_route = "{{asset('/delete-asset')}}";
    var save_asset_records_route = "{{asset('/save-asset-records')}}";
    var templates_fetch_route = "{{asset('/get-asset-templates')}}";
    var template_submit_route = "{{asset('/save-asset-template')}}";
    var templates = null;
    var asset_customer_uid = customer.id;
    var asset_company_id = '';

    var general_info_route = "{{asset('/general-info')}}";
    var asset_company_id = '';
    var asset_project_id = '';
    var asset_ticket_id = '';
    var invoice_url = "{{url('create_pdf_invoice')}}";

    var show_asset = "{{asset('/show-single-assets')}}";
    var update_asset = "{{asset('/update-assets')}}";
    let timeouts_list = [];
    let loggedInUser_id = {!! json_encode(\Auth::user()->id) !!};
    let gl_color_notes = '';
    let gl_sel_note_index = null;
    let notes = [];
    let tkts_ids = [];
</script>

<script>
$('.form-group small').addClass('d-none');

$(document).ready(function() {
    values();
    orders_table_list = $('#customer_order_table').DataTable();
    domain_table_list = $('#domain_order_table').DataTable();

    customer_subscription_table = $('#customer_subscription').DataTable();

    tickets_table_list = $('#ticket_table').DataTable({
        processing: true,
        // scrollX: true,
        // scrollCollapse: true,
        fixedColumns: true,
        pageLength: 20,
        autoWidth: false,
        columnDefs: [{
                className: "overflow-wrap",
                targets: "_all"
            },
            {
                width: '10px',
                orderable: false,
                searchable: false,
                'className': 'dt-body-center',
                targets: 0
            },
            {
                width: '110px',
                targets: [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13]
            },
            {
                orderable: false,
                targets: 0
            },
            {
                orderable: false,
                targets: 1
            }
        ],
        order: [
            [2, 'asc']
        ],
        createdRow: function(row, data, dataIndex) {
            if ($(data[1]).attr('class').match('flagged')) {
                $(row).addClass('flagged-tr');
            }
        }
    });

    $('a.toggle-vis').on('click', function(e) {
        e.preventDefault();

        $(this).toggleClass('btn-success');
        $(this).toggleClass('btn-secondary');

        if ($(this).parent().parent().find('table').attr('id') == 'customer_order_table') {
            var column = orders_table_list.column($(this).attr('data-column'));
            column.visible(!column.visible());
        }
        if ($(this).parent().parent().find('table').attr('id') == 'domain_order_table') {
            var column = domain_table_list.column($(this).attr('data-column'));
            column.visible(!column.visible());
        }
        if ($(this).parent().parent().find('table').attr('id') == 'ticket-departments-list') {
            var column = $('#ticket-departments-list').DataTable().column($(this).attr('data-column'));
            column.visible(!column.visible());
        }

        if ($(this).parent().parent().find('#ticket_table').length) {
            var column = tickets_table_list.column($(this).attr('data-column'));
            column.visible(!column.visible());
        }

        if ($(this).parent().parent().find('table').attr('id') == 'customer_subscription') {
            var column = customer_subscription_table.column($(this).attr('data-column'));
            column.visible(!column.visible());
        }
    });

    get_ticket_table_list();

    getCustomerOrders();
     $( "#customer_order_table_filter").find('input[type="search"]').attr("placeholder","Order Id or Customer Name");
    var password = $(".user-password-div input[name='password']").val();
    var confirm_password = $(".user-confirm-password-div input[name='confirm_password']").val();
    var password = $(this).val();

    $(".user-password-div").on('keyup', "input[name='password']", function() {
        var score = 0;
        password = $(".user-password-div input[name='password']").val();
        score = (password.length > 6) ? score + 2 : score;
        score = ((password.match(/[a-z]/)) && (password.match(/[A-Z]/))) ? score + 2 : score;
        score = (password.match(/\d+/)) ? score + 2 : score;
        score = (password.match(/.[!,@,#,$,%,^,&,*,?,_,~,-,(,)]/)) ? score + 2 : score;
        score = (password.length > 10) ? score + 2 : score;
        $(".user-password-div .progress .progress-bar").css("width", (score * 10) + "%");
    });

    $(".user-confirm-password-div").on('keyup', "input[name='confirm_password']", function() {
        password = $(".user-password-div input[name='password']").val();
        confirm_password = $(".user-confirm-password-div input[name='confirm_password']").val();
        $(".user-confirm-password-div .check-match").removeClass("fa-times fa-check red green");
        if (password == confirm_password) {
            $(".user-confirm-password-div .check-match").addClass("fa-check green");
        } else {
            $(".user-confirm-password-div .check-match").addClass("fa-times red");
        }
    });

    var password = $(".user-password-div input[name='password']").val();
    var confirm_password = $(".user-confirm-password-div input[name='confirm_password']").val();

    var password = $(this).val();

    $(".user-password-div").on('keyup', "input[name='password']", function() {
        var score = 0;
        password = $(".user-password-div input[name='password']").val();
        score = (password.length > 6) ? score + 2 : score;
        score = ((password.match(/[a-z]/)) && (password.match(/[A-Z]/))) ? score + 2 : score;
        score = (password.match(/\d+/)) ? score + 2 : score;
        score = (password.match(/.[!,@,#,$,%,^,&,*,?,_,~,-,(,)]/)) ? score + 2 : score;
        score = (password.length > 10) ? score + 2 : score;
        $(".user-password-div .progress .progress-bar").css("width", (score * 10) + "%");
    });

    $(".user-confirm-password-div").on('keyup', "input[name='confirm_password']", function() {
        password = $(".user-password-div input[name='password']").val();
        confirm_password = $(".user-confirm-password-div input[name='confirm_password']").val();
        $(".user-confirm-password-div .check-match").removeClass("fa-times fa-check red green");
        if (password == confirm_password) {
            $(".user-confirm-password-div .check-match").addClass("fa-check green");
        } else {
            $(".user-confirm-password-div .check-match").addClass("fa-times red");
        }
    });

    $(".user-password-div").on('click', '.show-password-btn', function() {
        $(this).toggleClass("fa-eye fa-eye-slash");
        var input = $(".user-password-div input[name='password']");
        if (input.attr("type") === "password") {
            input.attr("type", "text");
        } else {
            input.attr("type", "password");
        }
    });

    $(".user-confirm-password-div").on('click', '.show-confirm-password-btn', function() {
        $(this).toggleClass("fa-eye fa-eye-slash");
        var input = $(".user-confirm-password-div input[name='confirm_password']");
        if (input.attr("type") === "password") {
            input.attr("type", "text");
        } else {
            input.attr("type", "password");
        }
    });

    $("#prof_phone").keyup(function() {

        var regex = new RegExp("^[0-9]+$");

        if(!regex.test($(this).val())) {
            $("#phone_error").html("Only numeric values allowed");
        }else{
            $("#phone_error").html(" ");
        }
        if($(this).val() == '') {
            $("#phone_error").html(" ");
        }
    });

    $('#update_customer').submit(function(event) {
        event.preventDefault();
        event.stopPropagation();
        

        var update_password = $('#password').val();
        var customer_id = $("#customer_id").val();

        var bill_st_add = '';
        var bill_apt_add = '';
        var bill_country = '';
        var bill_state = '';
        var bill_city = '';
        var bill_zip = '';
        var is_bill_add = '';
        var customer_login = 0;
        var cust_type = $("#cust_type").val();

        let cpwd = $(".user-confirm-password-div input[name='confirm_password']").val();

        if (cpwd && cpwd != update_password) {
            Swal.fire({
                position: 'top-end',
                icon: 'error',
                title: 'Passwords do not match!',
                showConfirmButton: false,
                timer: 2500
            });
            return false;
        }

        if (!update_password) {
            update_password = customer.password;
        }

        if (customer.password != update_password) {
            // let cpwd = $(".user-confirm-password-div input[name='confirm_password']").val();
            if (cpwd != update_password) {
                Swal.fire({
                    position: 'top-end',
                    icon: 'error',
                    title: 'Passwords do not match!',
                    showConfirmButton: false,
                    timer: 2500
                });
                return false;
            }
        }

        if ($('#is_bill_add').prop("checked") == true) {

            bill_st_add = $('#bill_st_add').val();
            bill_apt_add = $('#bill_apt_add').val();
            bill_country = $('#bill_add_country').val()
            bill_state = $('#bill_add_state').val();
            bill_city = $('#bill_add_city').val();
            bill_zip = $('#bill_add_zip').val();
            is_bill_add = 1;
        }

        if ($("#customer_login").is(':checked')) {
            customer_login = 1;
        } else {
            customer_login = 0;
        }


        var fb = $("#prof_fb").val();
        var pin = $("#prof_pinterest").val();
        var twt = $("#prof_twitter").val();
        var insta = $("#prof_insta").val();
        var link = $("#prof_linkedin").val();
        var phone = $('#prof_phone').val();

        if( fb != '') {
            var FBurl = /^(http|https)\:\/\/facebook.com|facebook.com\/.*/i;
            if(!fb.match(FBurl)) {
                toastr.error('Provide a valid facebook link', { timeOut: 5000 });
                return false;
            }
        }

        if( pin != '') {
            var FBurl = /^(http|https)\:\/\/pinterest.com|pinterest.com\/.*/i;
            if(!pin.match(FBurl)) {
                toastr.error('Provide a valid Pinterest link', { timeOut: 5000 });
                return false;
            }
        }
        if( twt != '') {
            var FBurl = /^(http|https)\:\/\/twitter.com|twitter.com\/.*/i;
            if(!twt.match(FBurl)) {
                toastr.error('Provide a valid Twitter link', { timeOut: 5000 });
                return false;
            }
        }
        if( insta != '') {
            var FBurl = /^(http|https)\:\/\/instagram.com|instagram.com\/.*/i;
            if(!insta.match(FBurl)) {
                toastr.error('Provide a valid Instagram link', { timeOut: 5000 });
                return false;
            }
        }
        if( link != '') {
            var FBurl = /^(http|https)\:\/\/linkedin.com|linkedin.com\/.*/i;
            if(!link.match(FBurl)) {
                toastr.error('Provide a valid Linkedin link', { timeOut: 5000 });
                return false;
            }
        }

        var regex = new RegExp("^[0-9]+$");

        if(!regex.test(phone)) {
            $("#phone_error").html("Only numeric values allowed");
            return false;
        }
        

        var form = {
            customer_id: customer_id,
            first_name: $('#first_name').val(),
            last_name: $('#last_name').val(),
            email: $('#prof_email').val(),
            password: $('#password').val(),
            phone: phone,
            address: $('#prof_address').val(),
            apt_address: $('#apt_address').val(),

            company_id: $('#company_id').val(),
            cust_type: cust_type,
            country: $('#prof_country').val(),
            state: $('#prof_state').val(),
            city: $('#prof_city').val(),
            zip: $('#prof_zip').val(),
            fb: $('#prof_fb').val(),
            twitter: $('#prof_twitter').val(),
            insta: $('#prof_insta').val(),
            pinterest: $('#prof_pinterest').val(),
            linkedin: $('#prof_linkedin').val(),
            bill_st_add: bill_st_add,
            bill_apt_add: bill_apt_add,
            bill_add_country: bill_country,
            bill_add_state: bill_state,
            bill_add_city: bill_city,
            bill_add_zip: bill_zip,
            is_bill_add: is_bill_add,
            customer_login: customer_login
        }

        $.ajax({
            type: "POST",
            url: "{{url('update_customer_profile')}}",
            data: form,
            dataType: 'json',
            beforeSend: function(data) {
                $("#saveBtn").hide();
                $("#processing").show();
            },
            success: function(data) {
                console.log(data);
                if(data.status_code == 200 && data.success == true) {

                    values();
                    toastr.success(data.message, { timeOut: 5000 });

                    let type = 'Wordpress';
                    let slug = 'customer-lookup';
                    let icon = 'fas fa-user-alt';
                    let title = 'WP Customer';
                    let desc = 'WP Customer Updated by ' + $("#curr_user_name").val();
                    sendNotification(type,slug,icon,title,desc);


                    customer.password = update_password;
                    $(".user-confirm-password-div input[name='confirm_password']").val('');


                    $("#cust_name").text($("#first_name").val() + " " + $("#last_name").val());
                    $("#cust_email").text($("#prof_email").val());
                    $("#cust_add").text($("#prof_address").val());
                    $("#cust_apprt").text($("#apt_address").val());
                    $("#cust_zip").text($("#prof_zip").val());
                    $("#cust_city").text($("#prof_city").val());


                    var state = $("#prof_state").val();

                    $("#cust_state").text(state);
                    
                    var country = $("#prof_country").val();

                    $("#cust_country").text(country);



                    $("#twt").attr('href', $("#prof_twitter").val());
                    $("#fb_icon").attr('href', $("#prof_fb").val());
                    $("#inst").attr('href', $("#prof_insta").val());
                    $("#lkdn").attr('href', $("#prof_linkedin").val());
                    $("#pintrst").attr('href', $("#prof_pinterest").val());
                }else if(data.status == 201 && data.success == true) {
                    toastr.warning(data.message, { timeOut: 5000 });
                }else{
                    toastr.error(data.message, { timeOut: 5000 });
                }
            },
            complete: function(data) {
                $("#saveBtn").show();
                $("#processing").hide();
            },
            error: function(e) {
                console.log(e);
                if (e.responseJSON.errors.email != null) {
                    Swal.fire({
                        position: 'top-end',
                        icon: 'error',
                        title: e.responseJSON.errors.email[0],
                        showConfirmButton: false,
                        timer: 2500
                    });
                }
                if (e.responseJSON.errors.password != null) {
                    Swal.fire({
                        position: 'top-end',
                        icon: 'error',
                        title: e.responseJSON.errors.password[0],
                        showConfirmButton: false,
                        timer: 2500
                    });
                }


                $("#saveBtn").show();
                $("#processing").hide();
            }
        });
    });
   
    $("#companyForm").submit(function(event) {
        event.preventDefault();
        var poc_first_name = $('#poc_first_name').val();
        var poc_last_name = $('#poc_last_name').val();
        var name = $('#name').val();
        var email = $('#cemail').val();
        var phone = $('#phone').val();
        var country = $("#country").val();
        var state = $("#state").val();
        var city = $("#city").val();
        var zip = $("#zip").val();
        var address = $('#address').val();
        var user_id = $("#user_id").val()


        var a = checkEmptyFields(poc_first_name, $("#err"));
        var b = checkEmptyFields(poc_last_name, $("#err1"));
        var c = checkEmptyFields(name, $("#err2"));
        var d = checkValidEmail(email, $("#err3"));
        var e = checkEmptyFields(phone, $("#err4"));

        var regex = new RegExp("^[0-9]+$");

        if(!regex.test(phone)) {
            $("#err4").html("Only numeric values allowed");
            return false;
        }

        if (a && b && c && d && e == true) {

            var formData = {
                poc_first_name: poc_first_name,
                poc_last_name: poc_last_name,
                name: name,
                email: email,
                phone: phone,
                country: country,
                state: state,
                city: city,
                zip: zip,
                address: address,
                user_id: user_id
            }

            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                },
                type: "POST",
                url: "/framework/save-company",
                data: formData,
                dataType: 'json',
                beforeSend: function(data) {
                    $('.loader_container').show();
                },
                success: function(data) {
                    toastr.success(data.message, {
                        timeOut: 5000
                    });

                    $('#company_id').append('<option value="' + data.result +'" selected>' + $('#companyForm #name').val() + '</option>');

                    $('#addCompanyModal').modal('hide');
                    $('#companyForm').trigger('reset');
                },
                complete: function(data) {
                    $('.loader_container').hide();
                },
                error: function(e) {
                    console.log(e)
                }
            });


        }

        // Handle click on "Select all" control
        $('#select-all').on('click', function() {
            // Get all rows with search applied
            var rows = customerTable.rows({
                'search': 'applied'
            }).nodes();
            // Check/uncheck checkboxes for all rows in the table
            $('input[type="checkbox"]', rows).prop('checked', this.checked);
        });
        
    });

    $("#payment_token").change(function(event) {
      

      var fname = $('#fname').val();
      var lname = $('#lname').val();
      var address1 = $('#address1').val();
      var city = $('#city').val();
      var state = $('#state').val();
      var zip = $('#zip').val();
      var card_type = $('#card_type').val();
      var  exp= $('#exp').val()
    var email= $('#cust_email1').val()
      var formData = {
          fname: fname,
          lname: lname,
          address1: address1,
          city: city,
          state: state,
          zip: zip,
          card_type: card_type,
          exp: exp,
          email:email,
          payment_token: $(this).val(),
          cardlastDigits:$('#cardlastDigits').val(),
          customer_id:$('#customer_id').val()
      }
      $.ajax({
          headers: {
              'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
          },
          type: "POST",
          url: "{{url('save-cust-card')}}",
          data: formData,
          dataType: 'json',
          beforeSend: function(data) {
              $('.loader_container').show();
          },
          success: function(data) {
            Swal.fire({
                    position: 'top-end',
                    icon: data.success ? 'success' : 'error',
                    title: data.message,
                    showConfirmButton: false,
                    timer: 2500
                });
                get_cust_card()
            console.log(data)
          
          },
          complete: function(data) {
              $('.loader_container').hide();
          },
          error: function(e) {
              console.log(e)
          }
      });
    });

    $("#save_tickets").submit(function(event) {
        event.preventDefault();
        var formData = new FormData($(this)[0]);
        var action = $(this).attr('action');
        var method = $(this).attr('method');
        var subject = $('#subject').val().replace(/\s+/g, " ").trim();
        var dept_id = $('#dept_id').val();
        var priority = $('#priority').val();
        var type = $('#type').val();
        var ticket_detail = $('#ticket_detail').val();
        if (subject == '' || subject == null) {
            $('#select-subject').css('display', 'block');
            return false;
        } else if (dept_id == '' || dept_id == null) {
            $('#select-department').css('display', 'block');
            return false;
        } else if (priority == '' || priority == null) {
            $('#select-priority').css('display', 'block');
            return false;
        } else if (type == '' || type == null) {
            $('#select-type').css('display', 'block');
            return false;
        } else if (ticket_detail == '' || ticket_detail == null) {
            $('#pro-details').css('display', 'block');
            return false;
        }
        formData.append('status', open_status_id);
        formData.append('customer_id', customer.id);

        $(this).find('#btnSaveTicket').attr('disabled', true);
        $(this).find('#btnSaveTicket .spinner-border').show();

        setTimeout(() => {
            $.ajax({
                type: method,
                url: action,
                data: formData,
                async: false,
                cache: false,
                contentType: false,
                enctype: 'multipart/form-data',
                processData: false,
                success: function(data) {
                    // $('#btnSaveTicket').attr('disabled', false);
                    // $('#btnSaveTicket .spinner-border').show();

                    console.log(data, "ticket data");
                    if (data.success) {
                        $('#ticketModal').modal('hide');
                        $("#save_tickets").trigger("reset");
                        $('#dept_id').val('').trigger("change");
                        $('#priority').val('').trigger("change");
                        $('#type').val('').trigger("change");
                        get_ticket_table_list();
                        $('#btnSaveTicket').attr('disabled', false);
                        $('#btnSaveTicket .spinner-border').hide();

                    } else {
                        Swal.fire({
                            position: 'top-end',
                            icon: (data.success) ? 'success' : 'error',
                            title: data.message,
                            showConfirmButton: false,
                            timer: 2500,

                        })
                        $('#btnSaveTicket').attr('disabled', false);
                        $('#btnSaveTicket .spinner-border').hide();
                    }

                },
                failure: function(errMsg) {

                    console.log(errMsg);
                    // $('#btnSaveTicket').attr('disabled', false);
                    // $('#btnSaveTicket .spinner-border').hide();
                }
            });
        }, 1000);

    });
});


function checkEmptyFields(input, err) {
    if (input == '') {
        err.html("this field is required");
        return false;
    } else {
        err.html("");
        return true;
    }
}

function checkValidEmail(input, err) {
    var pattern = new RegExp(/^[+a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,4}$/i);

    if (!pattern.test(input)) {
        err.html("please provide valid email");
        return false;
    } else {
        err.html("");
        return true;
    }
}

function ShowTicketsModel() {
    $('#dept_id').val('').trigger("change");
    $('#priority').val('').trigger("change");
    $('#type').val('').trigger("change");
    $("#save_tickets").trigger("reset");
    $('#ticketModal').modal('show');
}

function get_ticket_table_list() {
    tickets_table_list.clear().draw();
    $('#btnDelete,#btnBin').show();
    $('#btnBack,#btnRecycle').hide();
    $.ajax({
        type: "get",
        url: "{{asset('/get-tickets')}}/customer/"+customer.id,
        dataType: 'json',
        cache: false,
        success: function(data) {
            console.log(data.tickets);
            ticketsList = data.tickets;

            $('#my_tickets_count').html(ticketsList.length);
            $('#open_tickets_count').html(ticketsList.filter(itm => itm.status == open_status_id).length);
            $('#closed_tickets_count').html(ticketsList.filter(itm => itm.status == closed_status_id).length);

            listTickets();
            tkts_ids = ticketsList.map(a => a.id);
            get_ticket_notes();
        }
    });
}

function get_cust_card() {
    var v_name = $("#cust_name").text();
    $.ajax({
        type: "GET",
        url: "{{asset('/get-customer-card')}}",
        dataType: 'json',
        data: {
            customer_id: customer.id
        },
        cache: false,
        success: function(data) {
            // alert(v_name);
            console.log(data.types, "data");
            var cardSet = ``;
            var data = data.types;
            var masterCardimg = `<img src="https://secure.merchantonegateway.com/shared/images/brand-mastercard.png" style="width:16%;position: absolute;top: 25px;">`;
            var visaImg = `<img src="https://secure.merchantonegateway.com/shared/images/brand-visa.png" style="width:15%;position: absolute;top: 25px;">`;
            var discoverImg = `<img src="https://secure.merchantonegateway.com/shared/images/brand-discover.png" style="width:15%;position: absolute;top: 25px;">`;
            var amexImg = `<img src="https://secure.merchantonegateway.com/shared/images/brand-amex.png" style="width:15%;position: absolute;top: 25px;">`;
            var maestroImg = `<img src="https://secure.merchantonegateway.com/shared/images/brand-maestro.png" style="width:16%;position: absolute;top: 25px;">`
            // alert(data.length);
            $('#pay-card').empty();
            for (var i = 0; i < data.length; i++) {
                // alert(data[i].ccn);
                $('#pay-card').append(`<div class="col-md-6">

                        <div class="card payCard" style="border:1px solid black">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-12 text-right">
                                      
                                        <a href="#" class="btn btn-success btn-circle"><i class="fas fa-pencil-alt"  aria-hidden="true"></i></a>
                                        <a href="#" class="btn btn-danger btn-circle"><i class="fas fa-trash" style="" aria-hidden="true"></i></a>
                                        <!-- <div id="default-star-rating" style="cursor: pointer;">
                                            <img alt="1" src="assets/images/rating/star-off.png" title="Rate" style="float:right;padding-right: 5px;position: relative;bottom: 4px;">
                                            <input name="score" type="hidden">

                                        </div>-->

                                    </div>
                                </div>
                                <!--<h1 class="mt-0">
                                <i class="fab fa-cc-visa text-info" aria-hidden="true"></i></h1>-->
                                `+(data[i].card_type == 'mastercard' ? masterCardimg : ' ')+`
                                `+(data[i].card_type == 'visa' ? visaImg : ' ')+`
                                `+(data[i].card_type == 'amex' ? amexImg : ' ')+`
                                `+(data[i].card_type == 'discover' ? discoverImg : ' ')+`
                                `+(data[i].card_type == 'maestro' ? maestroImg : ' ')+`
                                <!--<h3>`+data[i].card_type+` Ended With `+data[i].cardlastDigits+`</h3>
                                <span class="pull-right">Exp date: `+data[i].exp+ `</span>-->
                                
                                <h3 class="payCard-number">**** **** ****  `+data[i].cardlastDigits+`</h3>
                                <p><span class="payCard-text">`+data[i].fname+` `+data[i].lname+ `</span> 
                                <span class="payCard-text" style="float:right;"> Exp : `+data[i].exp+ `</span>
                                </p>
                                <!--<h4>`+data[i].card_type+` ending in `+data[i].cardlastDigits+`<span class="pull-right"> (expires `+data[i].exp+ `)</span></h4>-->
                                <span class="font-500"></span>
                            </div>
                        </div>
                    </div>
                
            `);
                // $('#pay-card').append(cardSet);
            }
        },
        error: function(e) {
            console.log(e)
        }
    });
}

function values() {
    var state, country;
    var email = $("#prof_email").val();
    var phone = $("#prof_phone").val();
    var address = $("#prof_address").val();
    var apartment = $("#apt_address").val();
    var city = $("#prof_city").val();
    // if ($("#prof_state option:selected").val() == "") {
    //     state = "";
    // } else {
        state = $("#prof_state").val();
    // }

    var zip = $("#prof_zip").val();
    // if ($("#prof_country option:selected").val() == "") {
    //     country = "";
    // } else {
        country = $("#prof_country").val();
    // }
    // $("#adrs").html('');
    // $('#adrs').append ('<small class="text-muted pt-4 db">Phone</small> ' +
    //             '<h6><a href="tel:'+phone+'">'+phone+'</a></h6>'+
    //             '<small class="text-muted">Email address </small>'+
    //             '<h6><a href="tel:'+email+'">'+email+'</a></h6>'+
    //             '<small class="text-muted">Address </small>'+
    //             '<h6>'+address+',</h6>'+
    //             '<h6>'+apartment+'</h6>'+
    //             '<h6>'+city+', ' +state+', '+zip+'</h6>'+
    //             '<h6>'+country+'</h6>'+
    //             '<hr>');       
}

function listTickets() {
    $( "#customer_order_table_filter").find('input[type="search"]').attr("placeholder","Order Id or Customer Name");
    var count = 1;

    let ticket_arr = ticketsList;

    tickets_table_list.clear().draw();
    $.each(ticket_arr, function(key, val) {
        var json = JSON.stringify(data[key]);

        let prior = val['priority_name'];
        if (val['priority_color']) {
            prior = '<div class="text-center text-white" style="background-color: ' + val['priority_color'] +
                ';">' + val['priority_name'] + '</div>';
        }
        let flagged = '';
        if (val['is_flagged'] == 1) {
            flagged = 'flagged';
        }

        let custom_id = val['coustom_id'];
        if (Array.isArray(ticket_format)) {
            ticket_format = ticket_format['tkt_value'];
        }

        if (ticket_format.tkt_value == 'sequential') {
            custom_id = val['seq_custom_id'];
        }
        var name = val['subject'];
        var shortname = '';
        if (name.length > 20) {
            shortname = name.substring(0, 20) + " ...";
        } else {
            shortname = name;
        }

        tickets_table_list.row.add([
            '<input type="checkbox" name="chk_list[]" value= "' + val['id'] + '">',
            '<div class="text-center ' + flagged +
            '"><span class="fas fa-flag" style="cursor:pointer;" onclick="flagTicket(this, ' + val[
            'id'] + ');"></span></div>',
            val['status_name'],
            '<a href="{{asset("/ticket-details")}}/' + val['coustom_id'] + '">' + shortname + '</a>',
            custom_id,
            prior,
            val['customer_name'],
            val['lastReplier'],
            val['replies'],
            '---',
            '---',
            '---',
            (val.hasOwnProperty('tech_name')) ? val['tech_name'] : '---',
            val['department_name'],
            val['created_at'],
        ]).draw(false);
        count++;
    });
}


////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////// Ticket notes methods 
////////////////////////////////////////////////////////////////////////

function selectColor(color) {
    gl_color_notes = color;
    $('#note').css('background-color', color);
}

$("#save_ticket_note").submit(function(event) {
    event.preventDefault();

    var formData = new FormData($(this)[0]);
    formData.append('ticket_id', notes[gl_sel_note_index].ticket_id);
    formData.append('color', gl_color_notes);
    formData.append('type', notes[gl_sel_note_index].type);
    if (gl_sel_note_index !== null) {
        formData.append('id', notes[gl_sel_note_index].id);
    }
    var action = $(this).attr('action');
    var method = $(this).attr('method');

    $.ajax({
        type: method,
        url: action,
        data: formData,
        async: false,
        cache: false,
        contentType: false,
        enctype: 'multipart/form-data',
        processData: false,
        success: function(data) {
            // console.log(data);

            if (data.success) {
                // send mail notification regarding ticket action
                ticket_notify(notes[gl_sel_note_index].ticket_id, 'ticket_update');
                gl_sel_note_index = null;

                $(this).trigger('reset');
                Swal.fire({
                    position: 'center',
                    icon: 'success',
                    title: data.message,
                    showConfirmButton: false,
                    timer: swal_message_time
                });
                get_ticket_notes();

                $('#notes_manager_modal').modal('hide');
            } else {
                Swal.fire({
                    position: 'center',
                    icon: 'error',
                    title: data.message,
                    showConfirmButton: false,
                    timer: swal_message_time
                });
            }
        },
        failure: function(errMsg) {
            console.log(errMsg);
        }
    });
});

function get_ticket_notes() {
    $.ajax({
        type: 'GET',
        url: "{{asset('/get-ticket-notes')}}",
        data: {
            id: tkts_ids,
            type: 'User'
        },
        // contentType: 'json',
        // enctype: 'multipart/form-data',
        // processData: false,
        success: function(data) {
            if (data.success) {
                // $('#ticket_notes .card-body').html(`<div class="col-12 px-0 text-right">
                //     <button class="btn btn-success" data-target="#notes_manager_modal" data-toggle="modal"><i class="mdi mdi-plus-circle"></i> Add Note</button>
                // </div>`);
                $('#ticket_notes .card-body').html('');

                notes = data.notes;

                if (timeouts_list.length) {
                    for (let i in timeouts_list) {
                        clearTimeout(timeouts_list[i]);
                    }
                }

                timeouts_list = [];

                for (let i in notes) {
                    let timeOut = '';
                    let autho = '';
                    if (notes[i].created_by == loggedInUser_id) {
                        autho = `<div class="ml-auto">
                            <span class="fas fa-edit text-primary ml-2" onclick="editNote(this, ` + (i) + `)" style="cursor: pointer;"></span>
                            
                            <span class="fas fa-trash text-danger" onclick="deleteTicketNote(this, '` + notes[i].id + `', '` + notes[i].ticket_id + `')" style="cursor: pointer;"></span>
                        </div>`;
                    }

                    if (notes[i].followup_id && notes[i].followUp_date) {
                        let timeOut2 = moment(notes[i].followUp_date).diff(moment(), 'seconds');

                        // set timeout for only 1 day's followups
                        if (moment(notes[i].followUp_date).diff(moment(), 'hours') > 23) continue;

                        if (timeOut2 > 0) {
                            timeOut = timeOut2 * 1000;
                        }
                    }

                    let tkt_subject = '';
                    let tkt = ticketsList.filter(item => item.id == notes[i].ticket_id);
                    if(tkt.length) tkt_subject = '<a href="{{asset("/ticket-details")}}/' + tkt[0].coustom_id + '">'+tkt[0].coustom_id+'</a>';

                    let flup = `<div class="col-12 p-2 my-2 d-flex" id="note-div-${notes[i].id}" style="background-color: ${notes[i].color}">
                        <div class="pr-2">
                            <img src="{{asset('/files/asset_img/1601560516.png')}}" alt="User" width="40">
                        </div>
                        <div class="w-100">
                            <div class="col-12 p-0 d-flex">
                                <h5 class="note-head">Original Posted to ${tkt_subject} by ${notes[i].name} ${moment(notes[i].created_at).format('YYYY-MM-DD HH:mm:ss A')}</h5>
                                ${autho}
                            </div>
                            <p class="note-details">${notes[i].note}</p>
                        </div>
                    </div>`;

                    if (timeOut) {
                        timeouts_list.push(setTimeout(function() {
                            $('#ticket_notes .card-body').append(flup);
                        }, timeOut));
                    } else {
                        $('#ticket_notes .card-body').append(flup);
                    }
                }
            }
        },
        failure: function(errMsg) {
            console.log(errMsg);
        }
    });
}

function editNote(ele, index) {
    gl_sel_note_index = index;
    gl_color_notes = notes[index].color;
    $('#save_ticket_note').find('#note').val(notes[index].note);
    $('#save_ticket_note').find('#note').css('background-color', gl_color_notes);

    $('#notes_manager_modal').modal('show');
}

function deleteTicketNote(ele, id, tick_id) {
    Swal.fire({
        title: 'Are you sure?',
        text: "All data related to this note will be removed!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, delete it!'
    }).then((result) => {
        if (result.value) {
            $.ajax({
                type: 'post',
                url: "{{asset('/del-ticket-note')}}",
                data: { id: id },
                success: function(data) {

                    if (data.success) {
                        // send mail notification regarding ticket action
                        // ticket_notify(tick_id, 'ticket_update');

                        $(ele).closest('#note-div-' + id).remove();
                    } else {
                        Swal.fire({
                            position: 'center',
                            icon: 'error',
                            title: data.message,
                            showConfirmButton: false,
                            timer: swal_message_time
                        });
                    }
                },
                failure: function(errMsg) {
                    // console.log(errMsg);
                    Swal.fire({
                        position: 'center',
                        icon: 'error',
                        title: errMsg,
                        showConfirmButton: false,
                        timer: swal_message_time
                    });
                }
            });
        }
    });
}

function ticket_notify(tick_id, template, action_name) {
    if (tick_id && template) {
        $.ajax({
            type: 'POST',
            url: "{{asset('/ticket_notification')}}",
            data: { id: tick_id, template: template, action: action_name },
            success: function(data) {
                if (!data.success) {
                    console.log(data.message);
                }
            },
            failure: function(errMsg) {
                console.log(errMsg);
            }
        });
    }
}

function flagTicket(ele, id) {
    $.ajax({
        type: 'post',
        url: "{{asset('/flag_ticket')}}",
        data: {
            id: id
        },
        success: function(data) {

            if (data) {

                $(ele).closest('tr').toggleClass('flagged-tr');

            } else {
                Swal.fire({
                    position: 'top-end',
                    icon: 'error',
                    title: 'Something went wrong!',
                    showConfirmButton: false,
                    timer: 2500
                });
            }
        },
        failure: function(errMsg) {
            console.log(errMsg);
            Swal.fire({
                position: 'top-end',
                icon: 'error',
                title: errMsg,
                showConfirmButton: false,
                timer: 2500
            });
        }
    });
}

$('#is_bill_add').change(function() {
    if (this.checked)
        $('#compBillAdd').show();
    else
        $('#compBillAdd').hide();

});


function New_Bill_Add() {
    if (document.getElementById("NewBillAdd").style.display == "none") {
        $('#NewBillAdd').show();
    } else {
        $('#NewBillAdd').hide();
    }
}

function getCustomerOrders() {
    $( "#customer_order_table_filter").find('input[type="search"]').attr("placeholder","Order Id or Customer Name");
    var customer_id = $("#customer_id").val();
    $.ajax({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
        },
        type: 'GET',
        url: "{{url('get_customer_order')}}/" + customer_id,
        dataType: "json",
        beforeSend: function() {
            $(".loader_container").show();
        },
        success: function(data) {
            console.log(data , "customer order");
            var date_format = data.date_format;
            var obj = data.data;

            console.log(obj, "obj");

            $('#customer_order_table').DataTable().destroy();
            $.fn.dataTable.ext.errMode = 'none';
            var tbl = $('#customer_order_table').DataTable({
                data: obj,
               
                "pageLength": 50,
                processing: true,
                language: {
                    "loadingRecords": "&nbsp;",
                    "processing": "Wait data is Loading..."
                },
                columns: [{
                        "className": 'details-control',
                        "orderable": false,
                        "data": null,
                        "defaultContent": ''
                    },
                    {
                        "render": function(data, type, full, meta) {
                            return full.custom_id;
                        }
                    },
                    {
                        "render": function(data, type, full, meta) {
                            return full.order_by.first_name + " " + full.order_by.last_name;
                        }
                    },
                    {
                        "render": function(data, type, full, meta) {
                            let completed =
                                `<span class="badge bg-success text-white">Completed</span>`;
                            let pending = `<span class="badge bg-danger text-white">` + full
                                .status_text + `</span>`;
                            return full.status_text == "Completed" ? completed : pending;
                        }
                    },
                    {
                        "render": function(data, type, full, meta) {
                            return moment(full.created_at).format(date_format);
                        }
                    },
                    {
                        "render": function(data, type, full, meta) {
                            let pdf_btn = `<a href="` + invoice_url + "/" + full.id +
                                `" class="btn btn-danger btn-sm rounded"><i class="fas fa-file-pdf mt-1"></i></a>`;

                            let paypal_btn = `<a href="{{url('checkout')}}/` + full.customer_id + `/` + full.custom_id +`" class="btn btn-sm ml-1 rounded btn-success">Payment</a>
                                <a class="btn btn-sm btn-danger rounded text-white ml-2"> PDF</a>`;
                            return full.status_text == "Completed" ? pdf_btn : paypal_btn;
                        }
                    },
                ],
            });

            $('#customer_order_table tbody').on('click', 'td.details-control', function() {
                var tr = $(this).closest('tr');
                var row = tbl.row(tr);
                var rowData = row.data();

                if (row.child.isShown()) {
                    row.child.hide();
                    tr.removeClass('shown');
                } else {
                    $.ajax({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                        },
                        type: 'GET',
                        url: "{{url('get_customer_order_items')}}/" + rowData.custom_id,
                        dataType: "json",
                        beforeSend: function() {
                            $(".loader_container").show();
                        },
                        success: function(data) {
                            var obj1 = data.data;

                            if (row.child.isShown()) {
                                // This row is already open - close it
                                row.child.hide();
                                tr.removeClass('shown');
                            } else {
                                tbl.rows().every(function() {
                                    // If row has details expanded
                                    if (this.child.isShown()) {
                                        // Collapse row details
                                        this.child.hide();
                                        $(this.node()).removeClass('shown');
                                    }
                                });
                                row.child(format(obj1)).show();
                                tr.addClass('shown');
                            }
                        },
                        complete: function(data) {
                            $(".loader_container").hide();
                        },
                    });
                }
            });

        },
        complete: function(data) {
            $(".loader_container").hide();
        },

        error: function(e) {
            console.log(e);
        }
    });
}

function payNowModel(id) {
    console.log(id)
    $('#payNow').modal('show');
    var newUrl = "{{url('paypal/ec-checkout')}}/" + id;
    $("#paypalHref").attr("href", newUrl);
}

function format(obj) {

    var count = 1;
    var row = ``;
    var totals = 0;

    for (var i = 0; i < obj.length; i++) {

        totals = obj[i].total;

        row += `
                    <tr>
                        <td>` + count + `</td>
                        <td>` + obj[i].name + `</td>
                        <td>` + obj[i].quantity + `</td>
                        <td>` + obj[i].price + `</td>
                        <td>` + obj[i].quantity * obj[i].price + `</td>
                        
                    </tr>
                `;

        count++;
    }

    return `<table class="table table-hover w-75 text-center" cellpadding="5" cellspacing="0" border="0">
                    <div class="text-left">
                        <div class="header">
                            <h2 style="display: inline; margin-left:10px !important;">
                                <strong>Order </strong> Details
                            </h2>
                        </div>
                    </div>
                    <thead>
                        <tr>
                            <th>Sr</th>
                            <th>Product Name</th>
                            <th>Quanity</th>
                            <th>Price</th>
                            <th>Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        ` + row + `
                    </tbody>
                    <tfoot>
                        <tr>
                            <th>Total</th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th>` + totals + `</th>
                        </tr>
                    </tfoot>
                </table>`;
}

function searchDomain() {
    var search_domain = $("#search_domain").val();
    $.ajax({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
        },
        type: 'POST',
        url: "{{url('domain_search')}}",
        data: {search_domain:search_domain},
        beforeSend:function() {
            $("#domain_loader").show();
        },
        success: function(data) {

            var table = `
                    <tr>
                        <td>
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" class="custom-control-input" id="customCheck1">
                                <label class="custom-control-label" for="customCheck1"></label>
                            </div>
                        </td>
                        <td>  
                            <a type="button" data-toggle="modal" data-target="#domainModal">
                                <i class="fas fa-angle-right"></i> <span style="font-size:20px;"> `+data[0]+`</span></a></td>
                        <td>-</td>
                        <td>`+ (data[1] == 'false' ? 'Taken' : 'Avaiable') +`</td>
                        <td><button class="btn btn-success"> Register </button></td>
                    </tr>
            `;

            $("#domain_order_table tbody").html(table);
        },
        complete:function(data) {
            $("#domain_loader").hide();
        },
        error: function(e) {
            $("#domain_loader").hide();
            console.log(e);
        }
    });
}

</script>


{{-- Linked assets JS --}}

<!-- <script src="{{asset('public/js/customer_manager/customer_lookup/orders.js').'?ver='.rand()}}"></script> -->

<style>
    .flagged-tr {
        background-color: #FFE4C4 !important;
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

    .soc-ico {
        font-size: 32px;
    }

    .soc-card {
        justify-content: space-between;
        display: flex;
    }

    .select2-selection,
    .select2-container--default,
    .select2-selection--single {
        border-color: #848484 !important;
    }
</style>
@endsection