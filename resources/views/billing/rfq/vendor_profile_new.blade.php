@extends('layouts.master-layout-new')
@section('body')
<style>
    .text-right{
        float: right
    }
</style>
<div class="app-content content">
    <div class="content-overlay"></div>
    <div class="header-navbar-shadow"></div>
    <div class="content-wrapper container-fluid p-0">
        <div class="content-header row">
            <div class="content-header-left col-md-12 col-12 mb-2">
                <div class="row breadcrumbs-top">
                    <div class="col-12">
                        <h2 class="content-header-title float-start mb-0">Home</h2>
                        <div class="breadcrumb-wrapper">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a>Billing</a></li>
                                <li class="breadcrumb-item " aria-current="page">RFQ</li>
                                <li class="breadcrumb-item active" aria-current="page">{{ $vendor->first_name }} {{ $vendor->last_name }}</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="content-body">
        <div class="row">
             <!-- Column -->
        <div class="col-lg-3 col-xlg-3 col-md-5">
            <div class="card">
                <div class="card-body">
                    <center class="mt-4">
                        <img src="../assets/images/users/5.jpg" class="rounded-circle" width="100"
                            height="100" id="profile-user-img" />
                        <a type="button" data-bs-toggle="modal" data-bs-target="#editPicModal" style="left: 45px;position: relative;bottom: 99px;"><i
                                class="fa fa-pencil-alt picEdit"></i></a>

                        <h4 class="card-title mt-2" id="cust_name">{{ $vendor->first_name }} {{ $vendor->last_name }}
                        </h4>
                        <!-- <img src="../assets/images/users/5.jpg" class="rounded-circle" width="150">
                        <h4 class="card-title mt-2"></h4>
                        <h6 class="card-subtitle"></h6> -->
                        <!--<div class="row text-center justify-content-md-center">-->
                        <!--    <div class="col-4"><a href="javascript:void(0)" class="link"><i class="icon-people"></i> <font class="font-weight-medium">254</font></a></div>-->
                        <!--    <div class="col-4"><a href="javascript:void(0)" class="link"><i class="icon-picture"></i> <font class="font-weight-medium">54</font></a></div>-->
                        <!--</div>-->
                    </center>
                </div>
                <!-- <div class="card-body">
                    <small class="text-muted">Name </small>
                    <h6 id="customer-name"></h6>

                    <small class="text-muted">Company Name </small>

                    

                    <small class="text-muted pt-4 db">Phone</small>
                    <h6 id="customer-phone">09817934</h6>
                    <small class="text-muted">Email address </small>
                    <h6 id="customer-email">jim@gmail.com</h6>
                    <hr>
                </div> -->
                <div class="card-body">

                        <small class="text-muted">Company Name </small>
                        <h6 id="company-name"><a target="_blank"
                                href="#"> {{ $vendor->company }}
                            </a> </h6>

                        <small class="text-muted  db">Phone</small>
                        <h6> <a href="tel:0303-03030303" id="cust_phone">{{ $vendor->phone }}</a> </h6>                    

                        <div id="adrs">
                            <small class="text-muted  db">Email Address</small>
                            <input type="hidden" id="cust_email1" value="email.livetech.com">
                            <h6> <a href="mailto:email.livetech.com" id="cust_email"> {{ $vendor->email }}</a></h6>
                        </div>                   


                        <div>
                            <small class="text-muted  db">Address</small><br>
                            <span id="cust_add" class="text-dark">{{ $vendor->address }}</span>
                            <span id="cust_apprt"
                                class="text-dark">{{ $vendor->address }}</span>
                        </div>

                        <div>
                            <span id="cust_city">{{ $vendor->city }}</span>

                            
                                <span id="cust_state">{{ $vendor->state }}</span>
                            
                                <!-- <span id="cust_state"></span> -->
                                                        
                            <span id="cust_zip">{{ $vendor->zip }}</span>
                            <br>

                          
                            <span id="cust_country">{{ $vendor->country }}</span>
                            
                            <!-- <span id="cust_country"></span> -->
                            
                        </div>
                </div>
                <!-- <div class="card-body">
                    <small class="text-muted pt-4 db">Address</small>
                    <h6 id="customer-address">qeweqweaadadasd</h6>
                    <div class="map-box">
                        <iframe
                            src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d470029.1604841957!2d72.29955005258641!3d23.019996818380896!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x395e848aba5bd449%3A0x4fcedd11614f6516!2sAhmedabad%2C+Gujarat!5e0!3m2!1sen!2sin!4v1493204785508"
                            width="100%" height="150" frameborder="0" style="border:0" allowfullscreen></iframe>
                    </div> <small class="text-muted pt-4 db">Social Profile</small>
                    <br />
                    <button class="btn btn-circle btn-secondary"><i class="fab fa-facebook-f"></i></button>
                    <button class="btn btn-circle btn-secondary"><i class="fab fa-twitter"></i></button>
                    <button class="btn btn-circle btn-secondary"><i class="fab fa-youtube"></i></button>
                </div> -->
            </div>

            <div class="card">
                <div class="card-body">
                    <div id="map_2" class="gmaps">
                   
                    </div>
                   <input type="hidden" id="google_api_key">
                   <h2 class="mt-4 font-weight-bold text-dark">Social Links</h2>
                    <div class="d-flex justify-content-right" style="justify-content: right">
                    
                        <a href="" id="twt" title="Twitter" class="btn" target="_blank"
                            style="color: #009efb; font-size:20px">
                            <i class="fab fa-twitter"></i>
                        </a>

                        <a href="" id="fb_icon" title="Facebook" class="btn" target="_blank"
                            style="color:#0570E6; font-size:20px">
                            <i class="fab fa-facebook"></i>
                        </a>

                        <a href="" id="pintrst" title="Pinterest" class="btn" target="_blank"
                            style="color:#DF1A26; font-size:20px">
                            <i class="fab fa-pinterest"></i>
                        </a>

                        <a href="" id="inst" title="Instagram" class="btn" target="_blank"
                            style="color:#e1306c; font-size:20px">
                            <i class="fab fa-instagram"></i>
                        </a>

                        <a href="" id="lkdn" title="Linkedin" class="btn" target="_blank"
                            style="color:#0e76a8; font-size:20px">
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
        

            <div class="card"><grammarly-extension data-grammarly-shadow-root="true" style="position: absolute; top: -1px; left: -1px; pointer-events: none;" class="cGcvT"></grammarly-extension>
                <!-- Tabs -->
                <ul class="nav nav-pills custom-pills" id="pills-tab" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" id="pills-setting-tab" data-bs-toggle="pill" href="#previous-month" role="tab" aria-controls="pills-setting" aria-selected="true">User Profile</a>
                    </li>
                    <!-- <li class="nav-item">
                        <a class="nav-link" id="pills-tickets-tab" data-bs-toggle="pill" href="#tickets" role="tab" aria-controls="pills-tickets" aria-selected="false">Tickets</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="orders-profile-tab" data-bs-toggle="pill" href="#orders" role="tab" aria-controls="pills-profile" aria-selected="false">Orders</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="subscription-profile-tab" data-bs-toggle="pill" href="#subscription" role="tab" aria-controls="pills-profile" aria-selected="false">Subscriptions</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="pills-assets-tab" data-bs-toggle="pill" href="#assets" role="tab" aria-controls="pills-assets" aria-selected="false">Assets</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="payment-profile-tab" data-bs-toggle="pill" href="#payment" role="tab" aria-controls="pills-profile" aria-selected="false">Payments</a>
                    </li> -->
                    <li class="nav-item">
                        <a class="nav-link" id="pills-notes-tab" data-bs-toggle="pill" href="#notes" role="tab" aria-controls="pills-notes" aria-selected="false">Notes</a>
                    </li>
                    <!-- <li class="nav-item">
                        <a class="nav-link" id="pills-timeline-tab" data-bs-toggle="pill" href="#current-month" role="tab" aria-controls="pills-timeline" aria-selected="false">History</a>
                    </li> -->
                    <!--<li class="nav-item">-->
                    <!--    <a class="nav-link" id="pills-profile-tab" data-bs-toggle="pill" href="#last-month" role="tab" aria-controls="pills-profile" aria-selected="false">Schedule</a>-->
                    <!--</li>-->
                   
                  
                   
                    <!-- <li class="nav-item">
                        <a class="nav-link" id="notifications-profile-tab" data-bs-toggle="pill" href="#notification" role="tab" aria-controls="notifications-profile" aria-selected="false">Notifications</a>
                    </li> -->
                  
                   
                    
                </ul>
                <!-- Tabs -->
                <div class="tab-content" id="pills-tabContent">
                    <!-- <div class="tab-pane fade" id="current-month" role="tabpanel" aria-labelledby="pills-timeline-tab">
                        <hr>
                        <div class="card-body">
                            No Data Found.
                        </div>
                    </div> -->
                    <div class="tab-pane fade active show" id="previous-month" role="tabpanel" aria-labelledby="pills-setting-tab">
                        <hr>
                        <div class="card-body">
                            
                            <form id="save_vendor_form" action="{{asset('/save-vendor')}}" method="POST">
                                <h2 class="font-weight-bold text-dark">Personal Info</h2>

                                <div class="row">
                                    <div class="col-md-4 form-group">
                                        <label for="first_name">First Name</label>
                                        <input type="text" class="form-control" value="{{ $vendor->first_name }}" id="first_name" name="first_name"
                                             required>
                                    </div>
                                    <div class="col-md-4 form-group">
                                        <label for="last_name">Last Name</label>
                                        <input type="text" value="{{ $vendor->last_name }}" class="form-control" id="last_name" name="last_name"
                                             required>
                                    </div>
                                    <div class="col-md-4 form-group">
                                        <label for="email">Email</label>
                                        <input type="text" class="form-control" id="email" name="email"
                                        value="{{ $vendor->email }}" required>
                                    </div>
                                </div>

                                <div class="row">
                                    <!-- <div class="col-md-6 form-group">
                                        <label>Password</label>
                                        <div class="user-password-div">
                                            <span class="block input-icon input-icon-right">
                                                <input type="password" name="password" id="password"
                                                    placeholder="password" class="form-control form-control-line"
                                                    value="">
                                                <span toggle="#password-field"
                                                    class="fa fa-fw fa-eye field-icon show-password-btn mr-2"></span>
                                            </span>
                                        </div>
                                    </div>
                                    <div class="col-md-6 form-group">
                                        <label>Confirm Password</label>
                                        <div class="user-confirm-password-div">
                                            <input name="confirm_password" class="form-control form-control-line"
                                                type="password" placeholder="Confirm Password"
                                                value="">
                                            <span toggle="#password-field"
                                                class="fa fa-fw fa-eye field-icon show-confirm-password-btn mr-2"></span>
                                        </div>
                                    </div> -->

                                    <div class="col-md-6 form-group mt-1">
                                        <label>Phone No</label>
                                        <input type="text" id="phone" name="phone"
                                        value="{{ $vendor->phone }}" class="form-control form-control-line">
                                            <span class="text-danger small" id="phone_error2"></span>
                                    </div>
                                    <div class="col-md-6 form-group mt-1">
                                            <label>Direct Line</label>
                                            <input type="text" class="form-control" id="direct_line" name="direct_line" value="{{ $vendor->direct_line }}">
                                            <span class="text-danger small" id="phone_error"></span>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6 form-group mt-1">
                                        <label>Vendor Categories</label>
                                        <select class="select2 form-control" id="categories" name="categories" multiple="multiple" style="height: 36px;width: 100%;">
                                            @foreach($categories as $category)
                                            <option value="{{$category->id}}" {{$category->id == $vendor->categories ? 'selected' : ''}}>{{$category->name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-6 form-group mt-1">
                                        <label>Vendor Tags</label>
                                        <div class=" justify-content-center">
                                            <select class="select2 form-control custom-select" id="tags" name="tags" multiple="multiple" style="height: 36px;width: 100%;">
                                               
                                            @foreach($tags as $tag)
                                                <option value="{{$tag->id}}" {{$tag->id == $vendor->tags ? 'selected' : ''}}>{{$tag->name}}</option>
                                            @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6 form-group mt-1">
                                        <label>Select Company</label>
                                        <div class="justify-content-center">
                                            <select class="select2 form-control " id="comp_id" name="comp_id"
                                                style="width: 100%; height:36px;">
                                                @foreach($companies as $company)
                                                    <option value="{{$company->id}}" {{$company->id == $vendor->categories ? 'selected' : ''}}>{{$company->name}}</option>
                                                @endforeach
                                            </select> 
                                            
                                            <input type="hidden"  class="form-control" id="company" name="company">
                                            <!-- <button type="button" data-bs-toggle="modal" data-bs-target="#addCompanyModal"
                                                id="new-company" class="btn btn-info">New</button> -->
                                        </div>
                                    </div>
                                    <div class="col-md-6 form-group mt-1">
                                        <label>Website</label><span style="color:red"> ( Add Url with http or https )</span>
                                        <input type="text" class="form-control" id="website" name="website" value="{{ $vendor->website }}">
                                    </div>
                                </div>

                                <!-- <div class="row mb-2 mt-2">
                                    <div class="custom-control custom-checkbox mr-sm-2">
                                        <input type="checkbox" class="custom-control-input" id="customer_login">
                                        <label class="custom-control-label" for="customer_login">Create Customer Login
                                            Account</label>
                                    </div>
                                </div> -->

                                <!-- <input type="hidden" name="customer_id" value=""> -->

                                <div class="row">
                                    <div class="col-12 form-group mt-1">
                                        <label>Street Address</label>
                                        <a type="button" data-bs-toggle="modal" data-bs-target="#Address-Book"
                                            class="float-right" style="color:#009efb;">View Address Book</a>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <input type="text" class=" form-control" name="address"
                                                value="{{ $vendor->address }}" id="prof_address"
                                                    placeholder="House number and street name">
                                            </div>
                                            <div class="col-md-6 ">
                                                <input type="text" class=" form-control" name="apt_address"
                                                    value="" id="apt_address"
                                                    placeholder="Apartment, suit, unit etc. (optional)">
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-3 form-group mt-1">
                                        <label>City</label>
                                        <input type="text" class="form-control" value="{{ $vendor->city }}"
                                            id="city" name="city">
                                    </div>
                                    <div class="col-md-3 form-group mt-1">
                                        <label>State</label>
                                            <select class="select2 form-control " id="state" name="state"
                                                style="width: 100%; height:36px;">
                                                @foreach($states as $state)
                                                <option value="{{$state->id}}">{{$state->name}}</option>
                                                @endforeach
                                                
                                            </select>
                                      
                                        
                                    </div>
                                    <div class="col-md-3 form-group mt-1">
                                        <label>Zip Code</label>
                                        <input type="tel" maxlength="5" class="form-control"
                                        value="{{ $vendor->zip }} id="prof_zip">
                                    </div>

                                    <div class="col-md-3 form-group mt-1">
                                        <label>Country</label>
                                            <select class="select2 form-control " id="country" name="country"
                                                style="width: 100%; height:36px;">
                                                @foreach($countries as $country)
                                                <option value="{{$country->id}}">{{$country->name}}</option>
                                                @endforeach
                                                
                                            </select>

                                    </div>
                                    <!-- <div class="col-md-12 form-group">
                                        <input id="is_bill_add" type="checkbox" name="is_bill_add"
                                        >
                                        <label class="mb-0" for="is_bill_add">Bill To & Ship To Addresses Are
                                            Different</label>
                                    </div> -->

                                </div>

                                <div class="row" id="compBillAdd"
                                    style="display:none">
                                    <div class="col-12 form-group">
                                        <label>Street Address</label>
                                        <!-- <a type="button" data-bs-toggle="modal" data-bs-target="#Address-Book"  class="float-right" style="color:#009efb;">Address Book</a> -->
                                        <div class="row">
                                            <div class="col-md-6">
                                                <input type="text" class=" form-control"
                                                    value="" id="bill_st_add"
                                                    name="bill_st_add" placeholder="House number and street name" >
                                            </div>
                                            <div class="col-md-6">
                                                <input type="text" class=" form-control"
                                                    value="" id="bill_apt_add"
                                                    name="bill_apt_add"
                                                    placeholder="Apartment, suit, unit etc. (optional)">
                                            </div>
                                        </div>

                                        <!-- <textarea class="form-control" name="address" id="update_address" rows="3"></textarea> -->
                                    </div>
                                    <div class="col-md-3 form-group">
                                        <label>City</label>
                                        <input type="text" class="form-control" value=""
                                            id="bill_add_city" name="bill_add_city">
                                    </div>
                                    <div class="col-md-3 form-group">
                                        <label>State</label>
                                            <input type="text" class=" form-control " value="" id="bill_add_state" name="bill_add_state"
                                                style="width: 100%; height:36px;">
                                        <select class="select2 form-control " id="bill_add_state" name="bill_add_state"
                                            style="width: 100%; height:36px;">
                                            <option value="">Select State</option>
                                          
                                        </select>
                                    </div>
                                    <div class="col-md-3 form-group">
                                        <label>Zip Code</label>
                                        <input type="tel" maxlength="5" class="form-control"
                                            value="" id="bill_add_zip" name="bill_add_zip">
                                    </div>


                                    <div class="col-md-3 form-group">
                                        <div class="form-group">
                                            <label>Country</label>
                                            
                                            <input type="text" class=" form-control " value="" id="bill_add_country" 
                                                name="bill_add_country" style="width: 100%; height:36px;">
                                       
                                            <select class="select2 form-control " id="bill_add_country"
                                                name="bill_add_country" style="width: 100%; height:36px;">
                                                <option value="">Select Country</option>
                                                <option value="">1</option>
                                            </select>
                                        
                                        </div>
                                    </div>
                                </div>

                                <h2 class="mt-1 font-weight-bold text-dark">Social</h2>

                                <div class="row">
                                    <div class="col-md-6 form-group">
                                        <label>Twitter</label>
                                        <input type="text" class="form-control" id="prof_twitter"
                                        value="{{ $vendor->twitter }}" name="twitter" placeholder="https://twitter.com/username">
                                    </div>
                                    <div class="col-md-6 form-group">
                                        <label>Facebook</label>
                                        <input type="text" name="fb" class="form-control" id="prof_fb" value="{{ $vendor->fb }}"
                                            placeholder="https://facebook.com/yourprofile">
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6 form-group mt-1">
                                        <label>Instagram</label>
                                        <input type="text" name="insta" class="form-control" id="prof_insta"
                                        value="{{ $vendor->insta }}" placeholder="https://instagram.com/username">
                                    </div>
                                    <div class="col-md-6 form-group mt-1">
                                        <label>Pinterest</label>
                                        <input type="text" name="pinterest" class="form-control" id="prof_pinterest"
                                        value="{{ $vendor->pinterest }}"
                                            placeholder="https://pinterest.com/@Username">
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6 form-group mt-1">
                                        <label>Linkedin</label>
                                        <input type="text" class="form-control" id="prof_linkedin"
                                        value=""
                                            placeholder="https://linkedin.com/@Username">
                                    </div>
                                </div>


                                <input type="hidden" name="customer-id" id="customer-id" value="">
                                <div class="row">
                                    <div class="col-md-12 form-group ">
                                        <div>
                                            <button type="submit" id="saveBtn" class="btn btn-success text-right">Update
                                                Profile</button>
                                            <button style="display:none" id="processing" class="btn btn-success text-right"
                                                type="button" disabled><i class="fas fa-circle-notch fa-spin"></i>
                                                Processing</button>
                                        </div>
                                    </div>

                                </div>
                                
                            </form>
                        </div>
                    </div>

                    <div class="tab-pane fade" id="tickets" role="tabpanel" aria-labelledby="pills-tickets-tab">
                        <div class="card-body">
                            <div class="text-right mb-3">
                                <button type="button" class="btn btn-info ml-auto mb-auto" onclick="ShowTicketsModel()">
                                    <i class="fas fa-plus" aria-hidden="true"></i>&nbsp;Add ticket
                                </button>
                            </div>
                            <div class="row mt-3">
                                <div class="col-6 col-md-4">
                                    <div class="card card-hover border-bottom border-warning">
                                        <div class="box p-2 rounded  text-center">
                                            <h1 class="" id="my_tickets_count">4</h1>
                                            <h6 class="text-warning">Total Tickets</h6>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-6 col-md-4">
                                    <div class="card card-hover border-bottom border-primary">
                                        <div class="box p-2 rounded  text-center">
                                            <h1 class="" id="open_tickets_count">4</h1>
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

                            <!-- <div class="col-12 mb-3 d-none">
                                <a class="toggle-vis btn btn-sm btn-success text-white mb-1" data-column="0">#</a> -
                                <a class="toggle-vis btn btn-sm btn-success text-white mb-1" data-column="1">Status</a> -
                                <a class="toggle-vis btn btn-sm btn-success text-white mb-1" data-column="2">Subject</a> -
                                <a class="toggle-vis btn btn-sm btn-success text-white mb-1" data-column="3">TicketID</a> -
                                <a class="toggle-vis btn btn-sm btn-success text-white mb-1" data-column="4">Priority</a> -
                                <a class="toggle-vis btn btn-sm btn-success text-white mb-1" data-column="5">Customer</a> -
                                <a class="toggle-vis btn btn-sm btn-success text-white mb-1" data-column="6">Last Replier</a> -
                                <a class="toggle-vis btn btn-sm btn-success text-white mb-1" data-column="7">Replies</a> -
                                <a class="toggle-vis btn btn-sm btn-success text-white mb-1" data-column="8">Last Activity</a> -
                                <a class="toggle-vis btn btn-sm btn-success text-white mb-1" data-column="9">Reply Due</a> -
                                <a class="toggle-vis btn btn-sm btn-success text-white mb-1" data-column="10">Resolution Due</a> -
                                <a class="toggle-vis btn btn-sm btn-success text-white mb-1" data-column="11">Assigned Tech</a> -
                                <a class="toggle-vis btn btn-sm btn-success text-white mb-1" data-column="12">Department</a> -
                                <a class="toggle-vis btn btn-sm btn-success text-white mb-1" data-column="13">Creation Date</a>
                            </div> -->
                            <!-- <div id="ticket_table_wrapper" class="dataTables_wrapper container-fluid dt-bootstrap4 no-footer"><div class="row"><div class="col-sm-12 col-md-6"><div class="dataTables_length" id="ticket_table_length"><label>Show <select name="ticket_table_length" aria-controls="ticket_table" class="form-control form-control-sm"><option value="10">10</option><option value="25">25</option><option value="50">50</option><option value="100">100</option></select> entries</label></div></div><div class="col-sm-12 col-md-6"><div id="ticket_table_filter" class="dataTables_filter"><label>Search:<input type="search" class="form-control form-control-sm" placeholder="" aria-controls="ticket_table"></label></div></div></div><div class="row"><div class="col-sm-12"><div class="dataTables_scroll"><div class="dataTables_scrollHead" style="overflow: hidden; position: relative; border: 0px; width: 100%;"><div class="dataTables_scrollHeadInner" style="box-sizing: content-box; width: 0px; padding-right: 0px;"><table style="table-layout: fixed; margin-left: 0px; width: 0px;" class="table table-striped table-bordered display no-wrap dataTable no-footer" role="grid"><thead>
                                    <tr role="row"><th class="dt-body-center overflow-wrap sorting_disabled" rowspan="1" colspan="1" style="width: 10px;" aria-label=""><div class="text-center"><input type="checkbox" name="select_all[]" id="select-all"></div></th><th class="overflow-wrap sorting_disabled" rowspan="1" colspan="1" style="width: 110px;" aria-label=""></th><th class="overflow-wrap sorting_asc" tabindex="0" aria-controls="ticket_table" rowspan="1" colspan="1" style="width: 110px;" aria-label="Status: activate to sort column descending" aria-sort="ascending">Status</th><th class="overflow-wrap sorting" tabindex="0" aria-controls="ticket_table" rowspan="1" colspan="1" style="width: 110px;" aria-label="Subject: activate to sort column ascending">Subject</th><th class="overflow-wrap sorting" tabindex="0" aria-controls="ticket_table" rowspan="1" colspan="1" style="width: 110px;" aria-label="TicketID: activate to sort column ascending">TicketID</th><th class="overflow-wrap sorting" tabindex="0" aria-controls="ticket_table" rowspan="1" colspan="1" style="width: 110px;" aria-label="Priority: activate to sort column ascending">Priority</th><th class="overflow-wrap sorting" tabindex="0" aria-controls="ticket_table" rowspan="1" colspan="1" style="width: 110px;" aria-label="Customer: activate to sort column ascending">Customer</th><th class="overflow-wrap sorting" tabindex="0" aria-controls="ticket_table" rowspan="1" colspan="1" style="width: 110px;" aria-label="Last Replier: activate to sort column ascending">Last Replier</th><th class="overflow-wrap sorting" tabindex="0" aria-controls="ticket_table" rowspan="1" colspan="1" style="width: 110px;" aria-label="Replies: activate to sort column ascending">Replies</th><th class="overflow-wrap sorting" tabindex="0" aria-controls="ticket_table" rowspan="1" colspan="1" style="width: 110px;" aria-label="Last Activity: activate to sort column ascending">Last Activity</th><th class="overflow-wrap sorting" tabindex="0" aria-controls="ticket_table" rowspan="1" colspan="1" style="width: 110px;" aria-label="Reply Due: activate to sort column ascending">Reply Due</th><th class="overflow-wrap sorting" tabindex="0" aria-controls="ticket_table" rowspan="1" colspan="1" style="width: 110px;" aria-label="Resolution Due: activate to sort column ascending">Resolution Due</th><th class="overflow-wrap sorting" tabindex="0" aria-controls="ticket_table" rowspan="1" colspan="1" style="width: 110px;" aria-label="Assigned Tech: activate to sort column ascending">Assigned Tech</th><th class="overflow-wrap sorting" tabindex="0" aria-controls="ticket_table" rowspan="1" colspan="1" style="width: 110px;" aria-label="Department: activate to sort column ascending">Department</th><th class="overflow-wrap sorting" tabindex="0" aria-controls="ticket_table" rowspan="1" colspan="1" aria-label="Creation Date: activate to sort column ascending">Creation Date</th></tr>
                                </thead></table></div></div><div class="dataTables_scrollBody" style="position: relative; overflow: auto; width: 100%;"><table id="ticket_table" style="table-layout: fixed" class="table table-striped table-bordered display no-wrap dataTable no-footer" role="grid" aria-describedby="ticket_table_info"><thead>
                                    <tr role="row" style="height: 0px;"><th class="dt-body-center overflow-wrap sorting_disabled" rowspan="1" colspan="1" style="width: 10px; padding-top: 0px; padding-bottom: 0px; border-top-width: 0px; border-bottom-width: 0px; height: 0px;" aria-label=""><div class="dataTables_sizing" style="height: 0px; overflow: hidden;"><div class="text-center"><input type="checkbox" name="select_all[]" id="select-all"></div></div></th><th class="overflow-wrap sorting_disabled" rowspan="1" colspan="1" style="width: 110px; padding-top: 0px; padding-bottom: 0px; border-top-width: 0px; border-bottom-width: 0px; height: 0px;" aria-label=""><div class="dataTables_sizing" style="height: 0px; overflow: hidden;"></div></th><th class="overflow-wrap sorting_asc" aria-controls="ticket_table" rowspan="1" colspan="1" style="width: 110px; padding-top: 0px; padding-bottom: 0px; border-top-width: 0px; border-bottom-width: 0px; height: 0px;" aria-label="Status: activate to sort column descending" aria-sort="ascending"><div class="dataTables_sizing" style="height: 0px; overflow: hidden;">Status</div></th><th class="overflow-wrap sorting" aria-controls="ticket_table" rowspan="1" colspan="1" style="width: 110px; padding-top: 0px; padding-bottom: 0px; border-top-width: 0px; border-bottom-width: 0px; height: 0px;" aria-label="Subject: activate to sort column ascending"><div class="dataTables_sizing" style="height: 0px; overflow: hidden;">Subject</div></th><th class="overflow-wrap sorting" aria-controls="ticket_table" rowspan="1" colspan="1" style="width: 110px; padding-top: 0px; padding-bottom: 0px; border-top-width: 0px; border-bottom-width: 0px; height: 0px;" aria-label="TicketID: activate to sort column ascending"><div class="dataTables_sizing" style="height: 0px; overflow: hidden;">TicketID</div></th><th class="overflow-wrap sorting" aria-controls="ticket_table" rowspan="1" colspan="1" style="width: 110px; padding-top: 0px; padding-bottom: 0px; border-top-width: 0px; border-bottom-width: 0px; height: 0px;" aria-label="Priority: activate to sort column ascending"><div class="dataTables_sizing" style="height: 0px; overflow: hidden;">Priority</div></th><th class="overflow-wrap sorting" aria-controls="ticket_table" rowspan="1" colspan="1" style="width: 110px; padding-top: 0px; padding-bottom: 0px; border-top-width: 0px; border-bottom-width: 0px; height: 0px;" aria-label="Customer: activate to sort column ascending"><div class="dataTables_sizing" style="height: 0px; overflow: hidden;">Customer</div></th><th class="overflow-wrap sorting" aria-controls="ticket_table" rowspan="1" colspan="1" style="width: 110px; padding-top: 0px; padding-bottom: 0px; border-top-width: 0px; border-bottom-width: 0px; height: 0px;" aria-label="Last Replier: activate to sort column ascending"><div class="dataTables_sizing" style="height: 0px; overflow: hidden;">Last Replier</div></th><th class="overflow-wrap sorting" aria-controls="ticket_table" rowspan="1" colspan="1" style="width: 110px; padding-top: 0px; padding-bottom: 0px; border-top-width: 0px; border-bottom-width: 0px; height: 0px;" aria-label="Replies: activate to sort column ascending"><div class="dataTables_sizing" style="height: 0px; overflow: hidden;">Replies</div></th><th class="overflow-wrap sorting" aria-controls="ticket_table" rowspan="1" colspan="1" style="width: 110px; padding-top: 0px; padding-bottom: 0px; border-top-width: 0px; border-bottom-width: 0px; height: 0px;" aria-label="Last Activity: activate to sort column ascending"><div class="dataTables_sizing" style="height: 0px; overflow: hidden;">Last Activity</div></th><th class="overflow-wrap sorting" aria-controls="ticket_table" rowspan="1" colspan="1" style="width: 110px; padding-top: 0px; padding-bottom: 0px; border-top-width: 0px; border-bottom-width: 0px; height: 0px;" aria-label="Reply Due: activate to sort column ascending"><div class="dataTables_sizing" style="height: 0px; overflow: hidden;">Reply Due</div></th><th class="overflow-wrap sorting" aria-controls="ticket_table" rowspan="1" colspan="1" style="width: 110px; padding-top: 0px; padding-bottom: 0px; border-top-width: 0px; border-bottom-width: 0px; height: 0px;" aria-label="Resolution Due: activate to sort column ascending"><div class="dataTables_sizing" style="height: 0px; overflow: hidden;">Resolution Due</div></th><th class="overflow-wrap sorting" aria-controls="ticket_table" rowspan="1" colspan="1" style="width: 110px; padding-top: 0px; padding-bottom: 0px; border-top-width: 0px; border-bottom-width: 0px; height: 0px;" aria-label="Assigned Tech: activate to sort column ascending"><div class="dataTables_sizing" style="height: 0px; overflow: hidden;">Assigned Tech</div></th><th class="overflow-wrap sorting" aria-controls="ticket_table" rowspan="1" colspan="1" style="width: 110px; padding-top: 0px; padding-bottom: 0px; border-top-width: 0px; border-bottom-width: 0px; height: 0px;" aria-label="Department: activate to sort column ascending"><div class="dataTables_sizing" style="height: 0px; overflow: hidden;">Department</div></th><th class="overflow-wrap sorting" aria-controls="ticket_table" rowspan="1" colspan="1" aria-label="Creation Date: activate to sort column ascending" style="padding-top: 0px; padding-bottom: 0px; border-top-width: 0px; border-bottom-width: 0px; height: 0px;"><div class="dataTables_sizing" style="height: 0px; overflow: hidden;">Creation Date</div></th></tr>
                                </thead>
                                
                                <tbody>
                                <tr role="row" class="odd"><td class=" overflow-wrap"><input type="checkbox" name="chk_list[]" value="17"></td><td class=" overflow-wrap"><div class="text-center "><span class="fas fa-flag" style="cursor:pointer;" onclick="flagTicket(this, 17);" aria-hidden="true"></span></div></td><td class="overflow-wrap sorting_1">Open</td><td class=" overflow-wrap"><a href="http://localhost/framework/ticket-details/17">asdasdasd</a></td><td class=" overflow-wrap">LHG-985-0504</td><td class=" overflow-wrap"><div class="text-center text-white" style="background-color: #ebfb04;">Low</div></td><td class=" overflow-wrap">jim cost</td><td class=" overflow-wrap">---</td><td class=" overflow-wrap">---</td><td class=" overflow-wrap">---</td><td class=" overflow-wrap">---</td><td class=" overflow-wrap">---</td><td class=" overflow-wrap">---</td><td class=" overflow-wrap">Billing / Quotes</td><td class=" overflow-wrap">2021-03-07 18:39:32</td></tr><tr role="row" class="even"><td class=" overflow-wrap"><input type="checkbox" name="chk_list[]" value="20"></td><td class=" overflow-wrap"><div class="text-center "><span class="fas fa-flag" style="cursor:pointer;" onclick="flagTicket(this, 20);" aria-hidden="true"></span></div></td><td class="overflow-wrap sorting_1">Open</td><td class=" overflow-wrap"><a href="http://localhost/framework/ticket-details/20">ahlksah</a></td><td class=" overflow-wrap">ZEF-138-9681</td><td class=" overflow-wrap">Medium</td><td class=" overflow-wrap">jim cost</td><td class=" overflow-wrap">---</td><td class=" overflow-wrap">---</td><td class=" overflow-wrap">---</td><td class=" overflow-wrap">---</td><td class=" overflow-wrap">---</td><td class=" overflow-wrap">---</td><td class=" overflow-wrap">Billing / Quotes</td><td class=" overflow-wrap">2021-03-07 18:57:52</td></tr><tr role="row" class="odd"><td class=" overflow-wrap"><input type="checkbox" name="chk_list[]" value="21"></td><td class=" overflow-wrap"><div class="text-center "><span class="fas fa-flag" style="cursor:pointer;" onclick="flagTicket(this, 21);" aria-hidden="true"></span></div></td><td class="overflow-wrap sorting_1">Open</td><td class=" overflow-wrap"><a href="http://localhost/framework/ticket-details/21">adfadfadf</a></td><td class=" overflow-wrap">ONX-639-5570</td><td class=" overflow-wrap"><div class="text-center text-white" style="background-color: #ff0000;">High</div></td><td class=" overflow-wrap">jim cost</td><td class=" overflow-wrap">---</td><td class=" overflow-wrap">---</td><td class=" overflow-wrap">---</td><td class=" overflow-wrap">---</td><td class=" overflow-wrap">---</td><td class=" overflow-wrap">---</td><td class=" overflow-wrap">Billing / Quotes</td><td class=" overflow-wrap">2021-03-07 19:00:06</td></tr><tr role="row" class="even"><td class=" overflow-wrap"><input type="checkbox" name="chk_list[]" value="19"></td><td class=" overflow-wrap"><div class="text-center "><span class="fas fa-flag" style="cursor:pointer;" onclick="flagTicket(this, 19);" aria-hidden="true"></span></div></td><td class="overflow-wrap sorting_1">Open</td><td class=" overflow-wrap"><a href="http://localhost/framework/ticket-details/19">new subject</a></td><td class=" overflow-wrap">YMX-522-4776</td><td class=" overflow-wrap"><div class="text-center text-white" style="background-color: #ff0000;">High</div></td><td class=" overflow-wrap">jim cost</td><td class=" overflow-wrap">---</td><td class=" overflow-wrap">---</td><td class=" overflow-wrap">---</td><td class=" overflow-wrap">---</td><td class=" overflow-wrap">---</td><td class=" overflow-wrap">---</td><td class=" overflow-wrap">Support</td><td class=" overflow-wrap">2021-03-07 18:55:13</td></tr></tbody>
                            </table></div></div><div id="ticket_table_processing" class="dataTables_processing card" style="display: none;">Processing...</div></div></div><div class="row"><div class="col-sm-12 col-md-5"><div class="dataTables_info" id="ticket_table_info" role="status" aria-live="polite">Showing 1 to 4 of 4 entries</div></div><div class="col-sm-12 col-md-7"><div class="dataTables_paginate paging_simple_numbers" id="ticket_table_paginate"><ul class="pagination"><li class="paginate_button page-item previous disabled" id="ticket_table_previous"><a href="#" aria-controls="ticket_table" data-dt-idx="0" tabindex="0" class="page-link">Previous</a></li><li class="paginate_button page-item active"><a href="#" aria-controls="ticket_table" data-dt-idx="1" tabindex="0" class="page-link">1</a></li><li class="paginate_button page-item next disabled" id="ticket_table_next"><a href="#" aria-controls="ticket_table" data-dt-idx="2" tabindex="0" class="page-link">Next</a></li></ul></div></div></div>
                            </div> -->
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
                                <table id="ticket_table" style=""
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
                                        <tr>
                                        <td>
                                                <div class="text-center"><input type="checkbox" name="select_all[]"
                                                        id="select-all"></div>
                                            </td>
                                            <td></td>
                                            <td>Status</td>
                                            <td>Subject</td>
                                            <td>TicketID</td>
                                            <td>Priority</td>
                                            <td>Customer</td>
                                            <td>Last Replier</td>
                                            <td>Replies</td>
                                            <td>Last Activity</td>
                                            <td>Reply Due</td>
                                            <td>Resolution Due</td>
                                            <td>Assigned Tech</td>
                                            <td>Department</td>
                                            <td>Creation Date</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>


                    <div class="tab-pane fade" id="orders" role="tabpanel" aria-labelledby="orders-profile-tab">
                        <hr>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-12">
                                    <div class="card">
                                        <div class="card-body">

                                            <div class="table-responsive">
                                                <div id="zero_config_wrapper" class="dataTables_wrapper container-fluid dt-bootstrap4">

                                                    <div class="row">
                                                        <div class="col-md-12 mb-3">
                                                            <button class="float-right btn-sm rounded btn btn-info "><i class="fas fa-sync"></i> Sync WP Orders  </button>

                                                        </div>
                                                        <div class="col-sm-12">
                                                        <!-- <div class="row">
                                                            <div class="col-md-4">
                                                                <select class="multiple-select mt-2 mb-2" name="select_column" id="select_column" placeholder="Select Menu" multiple="multiple" selected="selected">
                                                                    <option value="0">Checkbox</option>
                                                                    <option value="1">Sr</option>
                                                                    <option value="2">Profile</option>
                                                                    <option value="3">POC First Name</option>
                                                                    <option value="4">POC Last Name</option>
                                                                    <option value="5">Name</option>
                                                                    <option value="6">E-mail</option>
                                                                    <option value="7">Address</option>
                                                                    <option value="8">Phone</option>
                                                                    <option value="9">Created at</option>
                                                                    <option value="10">Action</option>
                                                                </select>
                                                            </div>
                                                            <div class="col-md-4"></div>
                                                            <div class="col-md-4"></div>
                                                        </div> -->
                                                            <!-- <div class="col-12 mb-3">
                                                                <a class="toggle-vis btn btn-sm btn-success text-white mb-1" data-column="0">WooID</a> -
                                                                <a class="toggle-vis btn btn-sm btn-success text-white mb-1" data-column="1">Number</a> -
                                                                <a class="toggle-vis btn btn-sm btn-success text-white mb-1" data-column="2">Created Via</a> -
                                                                <a class="toggle-vis btn btn-sm btn-success text-white mb-1" data-column="3">Version</a> -
                                                                <a class="toggle-vis btn btn-sm btn-success text-white mb-1" data-column="4">Status</a> -
                                                                <a class="toggle-vis btn btn-sm btn-success text-white mb-1" data-column="5">Currency</a> -
                                                                <a class="toggle-vis btn btn-sm btn-success text-white mb-1" data-column="6">Discount Total</a> -
                                                                <a class="toggle-vis btn btn-sm btn-success text-white mb-1" data-column="7">Discount Tax</a> -
                                                                <a class="toggle-vis btn btn-sm btn-success text-white mb-1" data-column="8">Shipping Total</a> -
                                                                <a class="toggle-vis btn btn-sm btn-success text-white mb-1" data-column="9">Shipping Tax</a> -
                                                                <a class="toggle-vis btn btn-sm btn-success text-white mb-1" data-column="10">Cart Tax</a> -
                                                                <a class="toggle-vis btn btn-sm btn-success text-white mb-1" data-column="11">Total</a> -
                                                                <a class="toggle-vis btn btn-sm btn-success text-white mb-1" data-column="12">Total Tax</a> -
                                                                <a class="toggle-vis btn btn-sm btn-success text-white mb-1" data-column="13">Prices Include Tax</a> -
                                                                <a class="toggle-vis btn btn-sm btn-success text-white mb-1" data-column="14">Customer Note</a> -
                                                                <a class="toggle-vis btn btn-sm btn-success text-white mb-1" data-column="15">Payment Method</a> -
                                                                <a class="toggle-vis btn btn-sm btn-success text-white mb-1" data-column="16">Payment Method Title</a> -
                                                                <a class="toggle-vis btn btn-sm btn-success text-white mb-1" data-column="17">Date Paid</a> -
                                                                <a class="toggle-vis btn btn-sm btn-success text-white mb-1" data-column="18">Date Completed</a>
                                                            </div> -->
                                                            <div id="customer_order_table_wrapper" class="dataTables_wrapper container-fluid dt-bootstrap4 no-footer"><div class="row"><div class="col-sm-12 col-md-6"><div class="dataTables_length" id="customer_order_table_length"><label>Show <select name="customer_order_table_length" aria-controls="customer_order_table" class="form-control form-control-sm"><option value="10">10</option><option value="25">25</option><option value="50">50</option><option value="100">100</option></select> entries</label></div></div><div class="col-sm-12 col-md-6"><div id="customer_order_table_filter" class="dataTables_filter"><label>Search:<input type="search" class="form-control form-control-sm" placeholder="" aria-controls="customer_order_table"></label></div></div></div><div class="row"><div class="col-sm-12"><table id="customer_order_table" class="table table-striped table-bordered no-wrap w-100 dataTable no-footer" role="grid" aria-describedby="customer_order_table_info">
                                                                <thead>
                                                                    <tr role="row"><th class="sorting_asc" tabindex="0" aria-controls="customer_order_table" rowspan="1" colspan="1" aria-sort="ascending" aria-label="WooID: activate to sort column descending" style="width: 0px;">WooID</th><th class="sorting" tabindex="0" aria-controls="customer_order_table" rowspan="1" colspan="1" aria-label="Number: activate to sort column ascending" style="width: 0px;">Number</th><th class="sorting" tabindex="0" aria-controls="customer_order_table" rowspan="1" colspan="1" aria-label="Created Via: activate to sort column ascending" style="width: 0px;">Created Via</th><th class="sorting" tabindex="0" aria-controls="customer_order_table" rowspan="1" colspan="1" aria-label="Version: activate to sort column ascending" style="width: 0px;">Version</th><th class="sorting" tabindex="0" aria-controls="customer_order_table" rowspan="1" colspan="1" aria-label="Status: activate to sort column ascending" style="width: 0px;">Status</th><th class="sorting" tabindex="0" aria-controls="customer_order_table" rowspan="1" colspan="1" aria-label="Currency: activate to sort column ascending" style="width: 0px;">Currency</th><th class="sorting" tabindex="0" aria-controls="customer_order_table" rowspan="1" colspan="1" aria-label="Discount Total: activate to sort column ascending" style="width: 0px;">Discount Total</th><th class="sorting" tabindex="0" aria-controls="customer_order_table" rowspan="1" colspan="1" aria-label="Discount Tax: activate to sort column ascending" style="width: 0px;">Discount Tax</th><th class="sorting" tabindex="0" aria-controls="customer_order_table" rowspan="1" colspan="1" aria-label="Shipping Total: activate to sort column ascending" style="width: 0px;">Shipping Total</th><th class="sorting" tabindex="0" aria-controls="customer_order_table" rowspan="1" colspan="1" aria-label="Shipping Tax: activate to sort column ascending" style="width: 0px;">Shipping Tax</th><th class="sorting" tabindex="0" aria-controls="customer_order_table" rowspan="1" colspan="1" aria-label="Cart Tax: activate to sort column ascending" style="width: 0px;">Cart Tax</th><th class="sorting" tabindex="0" aria-controls="customer_order_table" rowspan="1" colspan="1" aria-label="Total: activate to sort column ascending" style="width: 0px;">Total</th><th class="sorting" tabindex="0" aria-controls="customer_order_table" rowspan="1" colspan="1" aria-label="Total Tax: activate to sort column ascending" style="width: 0px;">Total Tax</th><th class="sorting" tabindex="0" aria-controls="customer_order_table" rowspan="1" colspan="1" aria-label="Prices Include Tax: activate to sort column ascending" style="width: 0px;">Prices Include Tax</th><th class="sorting" tabindex="0" aria-controls="customer_order_table" rowspan="1" colspan="1" aria-label="Customer Note: activate to sort column ascending" style="width: 0px;">Customer Note</th><th class="sorting" tabindex="0" aria-controls="customer_order_table" rowspan="1" colspan="1" aria-label="Payment Method: activate to sort column ascending" style="width: 0px;">Payment Method</th><th class="sorting" tabindex="0" aria-controls="customer_order_table" rowspan="1" colspan="1" aria-label="Payment Method Title: activate to sort column ascending" style="width: 0px;">Payment Method Title</th><th class="sorting" tabindex="0" aria-controls="customer_order_table" rowspan="1" colspan="1" aria-label="Date Paid: activate to sort column ascending" style="width: 0px;">Date Paid</th><th class="sorting" tabindex="0" aria-controls="customer_order_table" rowspan="1" colspan="1" aria-label="Date Completed: activate to sort column ascending" style="width: 0px;">Date Completed</th></tr>
                                                                </thead>
                                                                <tbody>
                                                                                                                                            
                                                                                                                                    <tr role="row" class="odd">
                                                                            <td class="sorting_1">10266</td>
                                                                            <td>10266</td>
                                                                            <td>checkout</td>
                                                                            <td>2.4.6</td>
                                                                            <td>completed</td>
                                                                            <td>USD</td>
                                                                            <td>0</td>
                                                                            <td>0</td>
                                                                            <td>0</td>
                                                                            <td>0</td>
                                                                            <td>0</td>
                                                                            <td>99.95</td>
                                                                            <td>0</td>
                                                                            <td>0</td>
                                                                            <td></td>
                                                                            <td>first_data_payeezy_gateway_credit_card</td>
                                                                            <td>Credit Card</td>
                                                                            <td>2015-09-10 19:27:10</td>
                                                                            <td>2015-09-10 20:19:05</td>
                                                                        </tr></tbody>

                                                            </table></div></div><div class="row"><div class="col-sm-12 col-md-5"><div class="dataTables_info" id="customer_order_table_info" role="status" aria-live="polite">Showing 1 to 1 of 1 entries</div></div><div class="col-sm-12 col-md-7"><div class="dataTables_paginate paging_simple_numbers" id="customer_order_table_paginate"><ul class="pagination"><li class="paginate_button page-item previous disabled" id="customer_order_table_previous"><a href="#" aria-controls="customer_order_table" data-dt-idx="0" tabindex="0" class="page-link">Previous</a></li><li class="paginate_button page-item active"><a href="#" aria-controls="customer_order_table" data-dt-idx="1" tabindex="0" class="page-link">1</a></li><li class="paginate_button page-item next disabled" id="customer_order_table_next"><a href="#" aria-controls="customer_order_table" data-dt-idx="2" tabindex="0" class="page-link">Next</a></li></ul></div></div></div></div>

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

                    <div class="tab-pane fade" id="subscription" role="tabpanel" aria-labelledby="subscription-profile-tab">
                        <hr>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-12">
                                    <div class="card">
                                        <div class="card-body">

                                            <div class="table-responsive">
                                                <div id="zero_config_wrapper" class="dataTables_wrapper container-fluid dt-bootstrap4">

                                                    <div class="row">
                                                        <div class="col-sm-12">
                                                            <!-- <div class="col-12 mb-3">
                                                                <a class="toggle-vis btn btn-sm btn-success text-white mb-1" data-column="0">WooID</a> -
                                                                <a class="toggle-vis btn btn-sm btn-success text-white mb-1" data-column="1">Status</a> -
                                                                <a class="toggle-vis btn btn-sm btn-success text-white mb-1" data-column="2">Currency</a> -
                                                                <a class="toggle-vis btn btn-sm btn-success text-white mb-1" data-column="3">Discount Tax</a> -
                                                                <a class="toggle-vis btn btn-sm btn-success text-white mb-1" data-column="4">Shipping Total</a> -
                                                                <a class="toggle-vis btn btn-sm btn-success text-white mb-1" data-column="5">Shipping Tax</a> -
                                                                <a class="toggle-vis btn btn-sm btn-success text-white mb-1" data-column="6">Total</a> -
                                                                <a class="toggle-vis btn btn-sm btn-success text-white mb-1" data-column="7">Payment Method</a> -
                                                                <a class="toggle-vis btn btn-sm btn-success text-white mb-1" data-column="8">Payment Method Title</a> -
                                                                <a class="toggle-vis btn btn-sm btn-success text-white mb-1" data-column="9">Customer Note</a> -
                                                                <a class="toggle-vis btn btn-sm btn-success text-white mb-1" data-column="10">Created Via</a> -
                                                                <a class="toggle-vis btn btn-sm btn-success text-white mb-1" data-column="11">Billing Period</a> -
                                                                <a class="toggle-vis btn btn-sm btn-success text-white mb-1" data-column="12">Start Date</a> -
                                                                <a class="toggle-vis btn btn-sm btn-success text-white mb-1" data-column="13">Trial End Date</a> -
                                                                <a class="toggle-vis btn btn-sm btn-success text-white mb-1" data-column="14">Next Payment Date</a> -
                                                                <a class="toggle-vis btn btn-sm btn-success text-white mb-1" data-column="15">End Date</a> -
                                                            </div> -->
                                                            <div id="customer_subscription_wrapper" class="dataTables_wrapper container-fluid dt-bootstrap4 no-footer"><div class="row"><div class="col-sm-12 col-md-6"><div class="dataTables_length" id="customer_subscription_length"><label>Show <select name="customer_subscription_length" aria-controls="customer_subscription" class="form-control form-control-sm"><option value="10">10</option><option value="25">25</option><option value="50">50</option><option value="100">100</option></select> entries</label></div></div><div class="col-sm-12 col-md-6"><div id="customer_subscription_filter" class="dataTables_filter"><label>Search:<input type="search" class="form-control form-control-sm" placeholder="" aria-controls="customer_subscription"></label></div></div></div><div class="row"><div class="col-sm-12"><table id="customer_subscription" class="table table-striped table-bordered no-wrap w-100 dataTable no-footer" role="grid" aria-describedby="customer_subscription_info">
                                                                <thead>
                                                                    <tr role="row"><th class="sorting_asc" tabindex="0" aria-controls="customer_subscription" rowspan="1" colspan="1" aria-sort="ascending" aria-label="ID: activate to sort column descending" style="width: 0px;">ID</th><th class="sorting" tabindex="0" aria-controls="customer_subscription" rowspan="1" colspan="1" aria-label="Status: activate to sort column ascending" style="width: 0px;">Status</th><th class="sorting" tabindex="0" aria-controls="customer_subscription" rowspan="1" colspan="1" aria-label="Currency: activate to sort column ascending" style="width: 0px;">Currency</th><th class="sorting" tabindex="0" aria-controls="customer_subscription" rowspan="1" colspan="1" aria-label="Discount Tax: activate to sort column ascending" style="width: 0px;">Discount Tax</th><th class="sorting" tabindex="0" aria-controls="customer_subscription" rowspan="1" colspan="1" aria-label="Shipping Total: activate to sort column ascending" style="width: 0px;">Shipping Total</th><th class="sorting" tabindex="0" aria-controls="customer_subscription" rowspan="1" colspan="1" aria-label="Shipping Tax: activate to sort column ascending" style="width: 0px;">Shipping Tax</th><th class="sorting" tabindex="0" aria-controls="customer_subscription" rowspan="1" colspan="1" aria-label="Total: activate to sort column ascending" style="width: 0px;">Total</th><th class="sorting" tabindex="0" aria-controls="customer_subscription" rowspan="1" colspan="1" aria-label="Payment Method: activate to sort column ascending" style="width: 0px;">Payment Method</th><th class="sorting" tabindex="0" aria-controls="customer_subscription" rowspan="1" colspan="1" aria-label="Payment Method Title: activate to sort column ascending" style="width: 0px;">Payment Method Title</th><th class="sorting" tabindex="0" aria-controls="customer_subscription" rowspan="1" colspan="1" aria-label="Customer Note: activate to sort column ascending" style="width: 0px;">Customer Note</th><th class="sorting" tabindex="0" aria-controls="customer_subscription" rowspan="1" colspan="1" aria-label="Created Via: activate to sort column ascending" style="width: 0px;">Created Via</th><th class="sorting" tabindex="0" aria-controls="customer_subscription" rowspan="1" colspan="1" aria-label="Billing Period: activate to sort column ascending" style="width: 0px;">Billing Period</th><th class="sorting" tabindex="0" aria-controls="customer_subscription" rowspan="1" colspan="1" aria-label="Start Date: activate to sort column ascending" style="width: 0px;">Start Date</th><th class="sorting" tabindex="0" aria-controls="customer_subscription" rowspan="1" colspan="1" aria-label="Trial End Date: activate to sort column ascending" style="width: 0px;">Trial End Date</th><th class="sorting" tabindex="0" aria-controls="customer_subscription" rowspan="1" colspan="1" aria-label="Next Payment Date: activate to sort column ascending" style="width: 0px;">Next Payment Date</th><th class="sorting" tabindex="0" aria-controls="customer_subscription" rowspan="1" colspan="1" aria-label="End Date: activate to sort column ascending" style="width: 0px;">End Date</th></tr>
                                                                </thead>
                                                                <tbody>
                                                                                                                                    <tr class="odd"><td valign="top" colspan="16" class="dataTables_empty">No data available in table</td></tr></tbody>

                                                            </table></div></div><div class="row"><div class="col-sm-12 col-md-5"><div class="dataTables_info" id="customer_subscription_info" role="status" aria-live="polite">Showing 0 to 0 of 0 entries</div></div><div class="col-sm-12 col-md-7"><div class="dataTables_paginate paging_simple_numbers" id="customer_subscription_paginate"><ul class="pagination"><li class="paginate_button page-item previous disabled" id="customer_subscription_previous"><a href="#" aria-controls="customer_subscription" data-dt-idx="0" tabindex="0" class="page-link">Previous</a></li><li class="paginate_button page-item next disabled" id="customer_subscription_next"><a href="#" aria-controls="customer_subscription" data-dt-idx="1" tabindex="0" class="page-link">Next</a></li></ul></div></div></div></div>

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

                        <!-- <hr>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-12">

                                    <button type="button" class="btn btn-info mr-auto" data-bs-toggle="modal" data-bs-target="#Add-new-card" style="float:right;"><i class="mdi mdi-plus-circle"></i>&nbsp;Add New Card</button>
                                </div>
                            </div>

                            <div class="row mt-3">
                                <div class="col-md-6">

                                    <div class="card" style="border:1px solid black">
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <a><i class="fas fa-trash" style="float:right;" aria-hidden="true"></i></a>
                                                    <a><i class="fas fa-pencil-alt" style="float:right;padding-right: 5px;" aria-hidden="true"></i></a>
                                                    <div id="default-star-rating" style="cursor: pointer;">
                                                        <img alt="1" src="assets/images/rating/star-off.png" title="Rate" style="float:right;padding-right: 5px;position: relative;bottom: 4px;">
                                                        <input name="score" type="hidden">

                                                    </div>

                                                </div>
                                            </div>
                                            <h1 class="mt-0"><i class="fab fa-cc-visa text-info" aria-hidden="true"></i></h1>
                                            <h3>**** **** **** 2150</h3>
                                            <span class="pull-right">Exp date: 10/16</span>
                                            <span class="font-500">Johnathan Doe</span>

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div> -->
                        <hr>
                        
                        <div class="row">
                            
                            <div class="col-md-12">
                            <label class="col-md-12">ADD New Credit Card</label>
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
                                                    <select class="select2 form-control " id="state" name="state"
                                                    style="width: 100%; height:36px;">
                                                        <option value="">Select State</option>
                                                       
                                                    </select> 
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <input type="text" class="form-control" placeholder="Zip code" name="zip"  id="zip" value="">
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

                    <!-- <div class="tab-pane fade" id="notification" role="tabpanel" aria-labelledby="notifications-profile-tab">
                        <hr>
                        <div class="card-body">
                            <div class="row">

                            </div>
                        </div>
                    </div> -->

                    <div class="tab-pane fade" id="assets" role="tabpanel" aria-labelledby="pills-assets-tab">
                        <!-- <div class="card-body">
                            <div class="row">
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
                                    <input class="form-control" type="text" id="email" name="email" required="">
                                </div>
                                <div class="col-md-4 col-lg-4 col-xlg-4" style="padding-top:25px">
                                    <button type="button" class="btn btn-info" data-bs-toggle="modal" data-bs-target="#assetModal" style="float:right;margin-bottom:5px;top: -35px;position: relative;"><i class="fas fa-plus" aria-hidden="true"></i>&nbsp;Add Asset</button>
                                    <button type="button" class="btn btn-info" data-bs-toggle="modal" data-bs-target="#assetCatModal" style="float:right;top: -35px;position: relative;"><i class="fas fa-plus" aria-hidden="true"></i>&nbsp;Add Asset Category</button>
                                </div>

                            </div>
                            <div class="row">
                                <div class="col-sm-12">
                                    <div id="accordion" class="custom-accordion mb-4">
                                        <div class="card mb-0">
                                            <div class="card-header" id="departments_collapse">
                                                <h5 class="m-0">
                                                    <a class="custom-accordion-title d-flex align-items-center pt-2 pb-2 collapsed" data-bs-toggle="collapse" href="#collapseDepartments" aria-expanded="false" aria-controls="collapseOne">
                                                        Category<span class="ml-auto"><i class="mdi mdi-chevron-down accordion-arrow"></i></span>
                                                    </a>
                                                </h5>
                                            </div>
                                            <div id="collapseDepartments" class="collapse" aria-labelledby="departments_collapse" data-parent="#accordion" style="">
                                                <div class="card-body">
                                                    <div class="widget-header widget-header-small">
                                                        <div class="row">
                                                            <div class="col-md-8 col-sm-6">
                                                                <h4 class="widget-title lighter smaller menu_title">
                                                                    Category Table</h4>
                                                            </div>
                                                            <div class="col-md-4 col-sm-6">
                                                               <button  class="btn waves-effect waves-light btn-primary" data-bs-toggle="" data-bs-target="" onclick="showDepModel()" ><i class="fas fa-plus"></i>&nbsp;Add Department</button>
                                                            </div>
                                                        </div>
                                                        <br>
                                                        <span class="loader_lesson_plan_form"></span>
                                                    </div>
                                                    <div class="widget-body">
                                                        <div class="widget-main">
                                                            <div class="row">
                                                                <div class="col-sm-12">
                                                                    <div class="col-12 mb-3">
                                                                        <a class="toggle-vis btn btn-sm btn-success text-white mb-1" data-column="0">#</a> -
                                                                        <a class="toggle-vis btn btn-sm btn-success text-white mb-1" data-column="1">Name</a> -
                                                                        <a class="toggle-vis btn btn-sm btn-success text-white mb-1" data-column="2">Actions</a> -
                                                                    </div>
                                                                    <table id="ticket-departments-list" class="display table-striped table-bordered ticket-departments-list" style="width:100%">
                                                                        <thead>
                                                                            <tr>
                                                                                <th><div class="text-center">#</div></th>
                                                                                <th>Name</th>
                                                                                <th>Actions</th>
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
                                        <div class="card mb-0">
                                            <div class="card-header" id="departments_collapse">
                                                <h5 class="m-0">
                                                    <a class="custom-accordion-title d-flex align-items-center pt-2 pb-2 collapsed" data-bs-toggle="collapse" href="#collapseDepartments" aria-expanded="false" aria-controls="collapseOne">
                                                        Category<span class="ml-auto"><i class="mdi mdi-chevron-down accordion-arrow"></i></span>
                                                    </a>
                                                </h5>
                                            </div>
                                            <div id="collapseDepartments" class="collapse" aria-labelledby="departments_collapse" data-parent="#accordion" style="">
                                                <div class="card-body">
                                                    <div class="widget-header widget-header-small">
                                                        <div class="row">
                                                            <div class="col-md-8 col-sm-6">
                                                                <h4 class="widget-title lighter smaller menu_title">
                                                                    Category Table</h4>
                                                            </div>
                                                            <div class="col-md-4 col-sm-6">
                                                               <button  class="btn waves-effect waves-light btn-primary" data-bs-toggle="" data-bs-target="" onclick="showDepModel()" ><i class="fas fa-plus"></i>&nbsp;Add Department</button>
                                                            </div>
                                                        </div>
                                                        <br>
                                                        <span class="loader_lesson_plan_form"></span>
                                                    </div>
                                                    <div class="widget-body">
                                                        <div class="widget-main">
                                                            <div class="row">
                                                                <div class="col-sm-12">
                                                                    <div class="col-12 mb-3">
                                                                        <a class="toggle-vis btn btn-sm btn-success text-white mb-1" data-column="0">#</a> -
                                                                        <a class="toggle-vis btn btn-sm btn-success text-white mb-1" data-column="1">Name</a> -
                                                                        <a class="toggle-vis btn btn-sm btn-success text-white mb-1" data-column="2">Actions</a> -
                                                                    </div>
                                                                    <table id="ticket-departments-list" class="display table-striped table-bordered ticket-departments-list" style="width:100%">
                                                                        <thead>
                                                                            <tr>
                                                                                <th><div class="text-center">#</div></th>
                                                                                <th>Name</th>
                                                                                <th>Actions</th>
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
                                        <div class="card mb-0">
                                            <div class="card-header" id="departments_collapse">
                                                <h5 class="m-0">
                                                    <a class="custom-accordion-title d-flex align-items-center pt-2 pb-2 collapsed" data-bs-toggle="collapse" href="#collapseDepartments" aria-expanded="false" aria-controls="collapseOne">
                                                        Category<span class="ml-auto"><i class="mdi mdi-chevron-down accordion-arrow"></i></span>
                                                    </a>
                                                </h5>
                                            </div>
                                            <div id="collapseDepartments" class="collapse" aria-labelledby="departments_collapse" data-parent="#accordion" style="">
                                                <div class="card-body">
                                                    <div class="widget-header widget-header-small">
                                                        <div class="row">
                                                            <div class="col-md-8 col-sm-6">
                                                                <h4 class="widget-title lighter smaller menu_title">
                                                                    Category Table</h4>
                                                            </div>
                                                        </div>
                                                        <br>
                                                        <span class="loader_lesson_plan_form"></span>
                                                    </div>
                                                    <div class="widget-body">
                                                        <div class="widget-main">
                                                            <div class="row">
                                                                <div class="col-sm-12">
                                                                    <div class="col-12 mb-3">
                                                                        <a class="toggle-vis btn btn-sm btn-success text-white mb-1" data-column="0">#</a> -
                                                                        <a class="toggle-vis btn btn-sm btn-success text-white mb-1" data-column="1">Name</a> -
                                                                        <a class="toggle-vis btn btn-sm btn-success text-white mb-1" data-column="2">Actions</a> -
                                                                    </div>
                                                                    <table id="ticket-departments-list" class="display table-striped table-bordered ticket-departments-list" style="width:100%">
                                                                        <thead>
                                                                            <tr>
                                                                                <th><div class="text-center">#</div></th>
                                                                                <th>Name</th>
                                                                                <th>Actions</th>
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
                                </div>
                            </div>
                        </div> -->
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
                                    <button type="button" class="btn btn-info" data-bs-toggle="modal" data-bs-target="#assetModal" style="float:right;margin-bottom:5px;top: -35px;position: relative;"><i class="fas fa-plus"></i>&nbsp;Add Asset</button>
                                    <button type="button" class="btn btn-info" data-bs-toggle="modal" data-bs-target="#assetCatModal" style="float:right;top: -35px;position: relative;"><i class="fas fa-plus"></i>&nbsp;Add Asset Category</button>
                                </div>
                            </div> --}}
                            <div class="row">

                                <div class="col-sm-12">
                                    <div id="accordion" class="custom-accordion mb-4">
                                        <div class="card">
                                            <div class="card-header" id="headingOne">
                                                <h5 class="m-0">
                                                    <a class="custom-accordion-title d-flex align-items-center pt-2 pb-2 collapsed"
                                                        data-bs-toggle="collapse" href="#collapseOne" aria-expanded="false"
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
                                                        data-bs-toggle="collapse" href="#collapseTwo" aria-expanded="false"
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
                                                        <div class="row">
                                                            <div class="col-md-12 form-group">
                                                                <div class="form-group">
                                                                    <label>Asset Template</label>
                                                                    <select class="select form-control"
                                                                        onchange="getFields(this.value)" id="form_id"
                                                                        name="form_id" required></select>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="row" id="templateTitle" style="display:none;">
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

                 

                    <?php //dd($company[0])?>
                    
                   

                     <div class="tab-pane fade" id="notes" role="tabpanel" aria-labelledby="pills-notes-tab">

                        <hr>
                        <div class="card-body">
                            No Data Found.
                        </div>
                    </div>


                </div>
            </div>
        </div>
        <!-- Column -->
    </div>
              <!-- add new certificate model -->
    <div id="add-new-certificate" class="modal fade" tabindex="-1" role="dialog"  data-backdrop="static">
        <div class="modal-dialog modal-lg" style="width:50%;">
            <div class="modal-content">
                <div class="modal-header">
                    <h3 class="blue bigger" style="color:#009efb;">Add New Certificate</h3>
                    <button type="button" class="close btn waves-effect waves-light btn-danger"
                        data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div id="timeline-1" class="" style="background: white;">
                                    <div class="row">
                                     <div class="col-md-12">
                                        <div class="widget-box widget-color-dark">
                                            <div class="widget-body">
                                                <div class="widget-main padding-8">

                                                    <form class="row road-map-form" id="save-certification" enctype="multipart/form-data"
                                                        action="{{asset('add-new-certification')}}" method="post">
                                                        
                                                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">

                                                            <input name="id" style="display: none;" class="form-control"
                                                                type="text" value="" readonly="readonly">

                                                            <input type="hidden" name="user_id" value="">


                                                            <div class="form-horizontal">
                                                               

                                                                <div class="form-group">
                                                                    <label class="control-label col-sm-4">Name
                                                                        :</label>
                                                                    <div class="col-sm-12">
                                                                        <input name="name" class="form-control"
                                                                            type="text" value="" placeholder="" />

                                                                    </div>
                                                                </div>

                                                                <div class="form-group">
                                                                    <label class="control-label col-sm-6">Category Name
                                                                        :</label>
                                                                    <div class="col-sm-12">
                                                                        <input name="category_name" class="form-control"
                                                                            type="text" value="" placeholder="" />
                                                                    </div>
                                                                </div>

                                                                <div class="form-group">
                                                                    <label class="control-label col-sm-12">Details
                                                                        :</label>
                                                                    <div class="col-sm-12">
                                                                        <textarea style="height: 100px;"
                                                                            name="details" class="form-control"
                                                                            placeholder=""></textarea>
                                                                    </div>
                                                                </div>

                                                                <div class="form-group">
                                                                    <label class="control-label col-sm-12">Attachment
                                                                        :</label>
                                                                    <div class="col-sm-12">
                                                                        <input type="file" name="image" id="image" accept="image/*" class="form-control">
                                                                    </div>
                                                                </div>

                                                                <div class="form-group">
                                                                    <div class="col-sm-offset-4 col-sm-12">
                                                                        <button type="submit"
                                                                            class="btn btn-primary pull-right"
                                                                            style="float: right;">Add</button>
                                                                    </div>
                                                                </div>

                                                                

                                                            </div>

                                                        </div>
                                                    </form>
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

    <!--  Modal content ticket start -->
    <div class="modal fade" id="ticket" role="dialog"  data-backdrop="static" aria-labelledby="myLargeModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header d-flex align-items-center">
                    <h4 class="modal-title" id="myLargeModalLabel" style="color:#009efb;">Add Ticket</h4>
                    <button type="button" class="close ml-auto" data-dismiss="modal" aria-hidden="true">×</button>
                </div>
                <div class="modal-body">
                    <form class="form-horizontal mt-4" id="save_tickets" action="{{asset('save-tickets')}}" method="post">
                        <div class="row">
                            <div class="col-sm-12 col-xs-12">

                                <fieldset>
                                    <div class="form-group">
                                        <div class="row">
                                            <div class="col-sm-8">
                                                <label class="control-label col-sm-12">Subject<span
                                                        style="color:red !important;">*</span></label><span id="select-subject"
                                                    style="display: none; color: red !important;">Subject cannot be Empty </span>
                                                <input class="form-control" type="text" id="subject" name="subject">
                                            </div>
                                            <div class="col-sm-4">
                                                <label class="control-label col-sm-12">Select Department<span
                                                        style="color:red !important;">*</span></label><span id="select-department"
                                                    style="display :none; color:red !important;;">Please Select Department</span>
                                                <select class="select2 form-control custom-select" type="search"
                                                    id="dept_id" name="dept_id" style="width: 100%; height:36px;">
                                                    <option value="">Select </option>
                                                   
                                                        <option value="1">123</option>
                                                   

                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="row">

                                            <div class="col-sm-4">
                                                <label class="control-label col-sm-12">Select Status<span
                                                        style="color:red !important;">*</span></label><span id="select-status"
                                                    style="display:none; color:red !important;">Please Select Status</span>
                                                <select class="select2 form-control " id="status" name="status"
                                                    style="width: 100%; height:36px;">
                                                    <option value="">Select </option>
                                                    <option value="1">123</option>
                                                </select>
                                            </div>


                                            <div class="col-sm-4">
                                                <label class="control-label col-sm-12">Select Priority<span
                                                        style="color:red !important;">*</span></label><span id="select-priority"
                                                    style="display :none; color:red !important;">Please Select Priority</span>
                                                <select class="select2 form-control " id="priority" name="priority"
                                                    style="width: 100%; height:36px;">
                                                    <option value="">Select </option>
                                                    <option value="1">123</option>
                                                </select>
                                            </div>

                                            <div class="col-sm-4">
                                                <label class="control-label col-sm-12">Assign Tech<span
                                                        style="color:red !important;">*</span></label><span id="select-assign"
                                                    style="display :none; color:red !important;">Please Select Tech</span>
                                                <select class="select2 form-control " id="assigned_to"
                                                    name="assigned_to" style="width: 100%; height:36px;">
                                                    <option value="">Select</option>
                                                    <option value="1">123</option>


                                                </select>
                                            </div>

                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="row mt-3">

                                            <div class="col-sm-4">
                                                <label class="control-label col-sm-12">Select Type
                                                <span style="color:red !important;">*</span></label><span id="select-type"
                                                    style="display :none; color:red !important;">Please Select Type</span>
                                                <select class="select2 form-control" id="type" name="type"
                                                    style="width: 100%; height:36px;">
                                                    <option value="">Select</option>
                                                    <option value="1">123</option>
                                                </select>
                                            </div>
                                            <div class="col-sm-4">
                                                <label class="control-label col-sm-12">Customer Select<span
                                                        style="color:red !important;">*</span></label><span id="select-customer"
                                                    style="display :none; color:red !important;">Please Select Customer</span>
                                                <select class="select2 form-control custom-select" id="customer_id"
                                                    name="customer_id" style="width: 100%; height:36px;">

                                                    <option value="">Select</option>
                                                    <option value="1">123</option>


                                                </select>
                                            </div>

                                            <div class="col-sm-4 checkbox checkbox-info">
                                                <input id="new-form" value="1" type="checkbox" name="newcustomer">
                                                <label class="mb-0" for="checkbox4">New Customer</label>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <div class="row mt-3">

                                            <div class="col-sm-12" id="new-customer" style="display:none;">
                                                <div class="row">
                                                    <label for="example-search-input"
                                                        class="col-sm-3 col-form-label">First
                                                        Name :<span style="color:red !important;">*</span></label><span
                                                        id="save-firstname"
                                                        style="display :none; color:red !important;position: relative;top: 10px;">First
                                                        name cannot be Empty </span>
                                                    <div class="col-sm-12">
                                                        <input class="form-control" type="text" id="first_name"
                                                            name="first_name">
                                                        <input class="form-control" type="text" id="ticket_id"
                                                            name="ticket_id" hidden>
                                                    </div>
                                                </div>

                                                <div class="row mt-1">
                                                    <label for="example-search-input"
                                                        class="col-sm-3 col-form-label">Last
                                                        Name :<span style="color:red !important;">*</span></label><span
                                                        id="save-lastname"
                                                        style="display :none; color:red !important;position: relative;top: 10px;">Last
                                                        name cannot be Empty </span>
                                                    <div class="col-sm-12">
                                                        <input class="form-control" type="text" id="last_name"
                                                            name="last_name">
                                                    </div>
                                                </div>

                                                <div class="row mt-1">
                                                    <label for="example-search-input"
                                                        class="col-sm-3 col-form-label">Phone
                                                        Number :<span style="color:red !important;">*</span></label><span
                                                        id="save-number"
                                                        style="display :none; color:red !important;position: relative;top: 10px;">Phone
                                                        number cannot be Empty </span>
                                                    <div class="col-sm-12">
                                                        <input class="form-control" type="text" id="phone" name="phone">
                                                    </div>
                                                </div>

                                                <div class="row mt-1">
                                                    <label for="example-search-input"
                                                        class="col-sm-3 col-form-label">E-mail
                                                        :<span style="color:red !important;">*</span></label><span id="save-email"
                                                        style="display :none; color:red !important; position: relative;top: 10px;">Email
                                                        cannot be Empty </span>
                                                    <div class="col-sm-12">
                                                        <input class="form-control" type="text" id="email" name="email">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <div class="row mt-3">
                                            <!--<div class="col-sm-4">-->
                                            <!--    <input type="file" class="file-upload form-control-file" id="exampleInputFile">-->
                                            <!--                        </div>-->

                                            <div class="col-sm-12">
                                                <label class="control-label col-sm-12">Problem Details<span
                                                        style="color:red !important;">*</span></label><span id="pro-details"
                                                    style="display :none; color:red !important;">Please provide details</span>
                                                <textarea class="form-control" rows="3" id="ticket_detail"
                                                    name="ticket_detail"></textarea>
                                            </div>
                                        </div>
                                    </div>
                                </fieldset>
                                <div class="text-right">
                                    <button type="submit" class="btn waves-effect waves-light btn-success">Save</button>
                                </div>


                            </div>
                        </div>
                    </form>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal content ticket end -->

    <!-- add user document modal -->
    <div id="add-new-document" class="modal fade" tabindex="-1" role="dialog"  data-backdrop="static">
            <div class="modal-dialog modal-lg" style="width:50%;">
                <div class="modal-content">
                    <div class="modal-header">
                        <h3 class="blue bigger" style="color:#009efb;">Add New Document</h3>
                        <button type="button" class="close btn waves-effect waves-light btn-danger"
                            data-dismiss="modal">&times;</button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-12">
                                <div id="timeline-1" class="" style="background: white;">
                                        <div class="row">
                                        <div class="col-md-12">
                                            <div class="widget-box widget-color-dark">
                                                <div class="widget-body">
                                                    <div class="widget-main padding-8">

                                                        <form class="row road-map-form" id="save-documents" enctype="multipart/form-data"
                                                            action="{{asset('add-new-documents')}}" method="post">
                                                            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">

                                                                <input name="id" style="display: none;" class="form-control"
                                                                    type="text" value="" readonly="readonly">

                                                                    <input type="hidden" name="user_id" value="">
                                                                    
                                                                <div class="form-horizontal">
                                                                

                                                                    <div class="form-group">
                                                                        <label class="control-label col-sm-4">Name
                                                                            :</label>
                                                                        <div class="col-sm-12">
                                                                            <input name="name" class="form-control"
                                                                                type="text" value="" placeholder="" />

                                                                        </div>
                                                                    </div>

                                                                    <div class="form-group">
                                                                        <label class="control-label col-sm-6">Category Name
                                                                            :</label>
                                                                        <div class="col-sm-12">
                                                                            <input name="category_name" class="form-control"
                                                                                type="text" value="" placeholder="" />
                                                                        </div>
                                                                    </div>

                                                                    <div class="form-group">
                                                                        <label class="control-label col-sm-12">Details
                                                                            :</label>
                                                                        <div class="col-sm-12">
                                                                            <textarea style="height: 100px;"
                                                                                name="details" class="form-control"
                                                                                placeholder=""></textarea>
                                                                        </div>
                                                                    </div>

                                                                    <div class="form-group">
                                                                        <label class="control-label col-sm-12">Attachment
                                                                            :</label>
                                                                        <div class="col-sm-12">
                                                                            <input type="file" name="image" id="image" accept="image/*" class="form-control">
                                                                        </div>
                                                                    </div>

                                                                    <div class="form-group">
                                                                        <div class="col-sm-offset-4 col-sm-12">
                                                                            <button type="submit"
                                                                                class="btn btn-primary pull-right"
                                                                                style="float: right;">Add</button>
                                                                        </div>
                                                                    </div>
                                                                </div>

                                                            </div>
                                                        </form>
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
                        <div class="col-md-6">
                            <label for="poc_first_name" class="small">POC First Name</label>
                            <input type="text" id="poc_first_name" class="form-control">
                            <span class="text-danger small" id="err"></span>
                        </div>
                        <div class="col-md-6">
                            <label for="poc_last_name" class="small">POC Last Name</label>
                            <input type="text" class="form-control" id="poc_last_name">
                            <span class="text-danger small" id="err1"></span>
                        </div>
                    </div>

                    <div class="row mt-3">
                        <div class="col-md-6">
                            <label for="name" class="small">Name</label>
                            <input type="text" id="name" class="form-control">
                            <span class="text-danger small" id="err2"></span>
                        </div>
                        <div class="col-md-6">
                            <label for="email" class="small">Email</label>
                            <input type="text" class="form-control" id="cemail">
                            <span class="text-danger small" id="err3"></span>
                        </div>
                    </div>

                    <div class="row mt-3">
                        <div class="col-md-6">
                            <label for="phone" class="small">Phone Number</label>
                            <input type="number" class="form-control" id="phone">
                            <span class="text-danger small" id="err4"></span>
                        </div>
                        <div class="col-md-6">
                            <label for="country" class="small">Country</label>
                            <input type="text" id="cmp_country" class="form-control">
                            <span class="text-danger small" id="err5"></span>
                        </div>
                    </div>

                    <div class="row mt-3">
                        <div class="col-md-4">
                            <label for="state" class="small">State</label>
                            <input type="text" id="cmp_state" class="form-control">
                            <span class="text-danger small" id="err6"></span>
                        </div>
                        <div class="col-md-4">
                            <label for="city" class="small">City</label>
                            <input type="text" class="form-control" id="cmp_city">
                            <span class="text-danger small" id="err7"></span>
                        </div>
                        <div class="col-md-4">
                            <label for="zip" class="small">Zip Code</label>
                            <input type="number" class="form-control" id="cmp_zip">
                            <span class="text-danger small" id="err8"></span>
                        </div>
                    </div>

                    <div class="row mt-3">
                       <div class="col-md-12">
                            <label for="address" class="small">Addres</label>
                            <textarea class="form-control" id="address" cols="30" rows="5"></textarea>
                            <span class="text-danger small" id="err9"></span>
                       </div>
                    </div>

                    <button type="button" style="float:right;" class="btn btn-danger mt-2" data-dismiss="modal">Close</button>
                    <button type="submit" style="float:right;" class="btn btn-primary mt-2 mr-2">Save</button>
                    
                    </form>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->
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
        </div>
    </div>
</div>

@endsection
@section('scripts')
<!-- jQuery ui files-->
<link rel="stylesheet" href="https://code.jquery.com/ui/1.12.0/themes/smoothness/jquery-ui.css">
<script src="https://code.jquery.com/ui/1.12.0/jquery-ui.min.js"></script>
<!-- <script src="{{asset('public/js/help_desk/asset_manager/template.js').'?ver='.rand()}}"></script>
<script src="{{asset('public/js/help_desk/asset_manager/actions.js').'?ver='.rand()}}"></script>
<script src="{{asset('public/js/help_desk/asset_manager/asset.js').'?ver='.rand()}}"></script> -->
@include('js_files.help_desk.asset_manager.templateJs')
    @include('js_files.help_desk.asset_manager.actionsJs')
    @include('js_files.help_desk.asset_manager.assetJs')

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@9"></script>
<script type="text/javascript" src="{{asset('assets/extra-libs/pickr/pickr.min.js')}}"></script>
<script src="{{asset('assets/libs/datatables/media/js/jquery.dataTables.min.js')}}"></script>
<script src="{{asset('assets/dist/js/pages/datatable/custom-datatable.js')}}"></script>
<script src="{{asset('assets/dist/js/pages/datatable/datatable-advanced.init.js')}}"></script>
@include('js_files.rfq.vendor_profileJs')
<script>
function New_Bill_Add() {
    if (document.getElementById("NewBillAdd").style.display == "none") {
        $('#NewBillAdd').show();
    } else {
        $('#NewBillAdd').hide();
    }
}</script>
@endsection