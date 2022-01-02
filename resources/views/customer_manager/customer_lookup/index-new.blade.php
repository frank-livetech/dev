@extends('layouts.master-layout-new')
<style>
    .float-right{
        float: right
    }
    table.dataTable>thead>tr>th:not(.sorting_disabled), table.dataTable>thead>tr>td:not(.sorting_disabled){
        padding-right: unset !important
    }
    .cust_first{
        width: 158px !important;
    }
    table.dataTable thead .sorting:after, table.dataTable thead .sorting_asc:after, table.dataTable thead .sorting_desc:after,
    table.dataTable thead .sorting:before, table.dataTable thead .sorting_asc:before, table.dataTable thead .sorting_desc:before{
        display: none !important
    }
    </style>
@section('body')
<div class="app-content content">
    <div class="content-overlay"></div>
    <div class="header-navbar-shadow"></div>
    <div class="content-wrapper container-xxl p-0">
        <div class="content-header row">
            <div class="content-header-left col-md-12 col-12 mb-2">
                <div class="row breadcrumbs-top">
                    <div class="col-12">
                        <h2 class="content-header-title float-start mb-0">Customer Lookup</h2>
                        <div class="breadcrumb-wrapper">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{route('home')}}">Home</a>
                                </li>
                                <li class="breadcrumb-item">Customer Manager
                                </li>
                                <li class="breadcrumb-item">Customer Lookup
                                </li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <input type="hidden" id="curr_user_name" value="{{Auth::user()->name}}">
    @if($brand != null && $brand->site_domain != null && $brand->site_domain != "")
        <input type="hidden" id="site_domain" value="{{$brand->site_domain}}">
    @endif

    <div class="content-body">
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-12" style="text-align:right;">
                                <button id="customer_add_btn" data-bs-toggle="modal" data-bs-target="#addCustomerModal"  class="float-right rounded btn btn-success"><i class="fa fa-plus-circle"></i> New Customer </button>
    
                            @if($wp_value == 1)
                                <button class="float-right btn-sm rounded btn btn-info mr-3"><i class="fas fa-sync"></i> Sync WP Customers  </button>
                            @endif
                            </div>      
                        </div>
                        <div class="table-responsive">
                            <table id="customerTable" class="companyTable table table-striped table-hover table-bordered">
                                <thead>
                                    <tr>
                                        <th>
                                            <div class="custom-control custom-checkbox">
                                                <input type="checkbox" class="custom-control-input" id="customCheck1">
                                                <label class="custom-control-label" for="customCheck1"></label>
                                            </div>
                                        </th>
                                        <th> </th>
                                        <th class="cust_first">First Name</th>
                                        <th class="cust_first">Last Name</th>
                                        <th>E-mail</th>
                                        <th>Phone</th>
                                        <th>Company</th>
                                        <th>Address</th>
                                        <th>Created at</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody id="customerTbody" class="small">
                                
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
    </div>
</div>
<div class="modal fade" id="addCustomerModal" role="dialog"  data-backdrop="static" aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add New Customer</h5>
                <button type="button" onclick="closeModal()" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true"></span>
                </button>
            </div>
            <div class="modal-body p-3">
                <form id="save_customer_form" autocomplete="off" enctype="multipart/form-data" method="POST" action="{{url('save_customer')}}">
                    <div class="row">
                        <div class="col-md-3 form-group">
                            <label for="first_name" class="small">First Name <span style="color:red">*</span></label>
                            <input type="text" class="form-control" name="first_name" id="first_name" >
                            <span class="text-danger name_error small"></span>
                        </div>
                        <div class="col-md-3 form-group">
                            <label for="last_name" class="small">Last Name<span style="color:red">*</span></label>
                            <input type="text" class="form-control" name="last_name" id="last_name" >
                            <span class="text-danger last_error small"></span>
                        </div>
                        <div class="col-md-6 form-group">
                            <label for="email" class="small">Email Address<span style="color:red">*</span></label>
                            <i class="mdi mdi-message-alert"  data-toggle="tooltip" data-placement="top" id="showtooltop" title="Clicking on generate email will make a safe fake email to be assigned to your customer profile."></i>
                            <input type="text" class="form-control small" name="email" id="email" >
                            <span class="text-danger small" id="err_email"></span>
                            <div class="d-flex justify-content-between">
                                <a href="javascript:void(0)" class="small" onclick="noEmail()">Click if customer has no email</a></a>
                                <a href="javascript:void(0)" class="small" onclick="resetEmail()">Reset</a>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 form-group">
                            <label for="phone" class="small">Phone</label>
                            <!-- <input type="text" onkeypress="return event.charCode >= 48 && event.charCode <= 57" class="form-control" name="phone" id="phone" placeholder="eg. 3334445555"> -->
                            <input type="text" class="form-control" name="phone" id="phone" placeholder="eg. 3334445555">
                            <span class="text-danger small" id="phone_error"></span>
                        </div>
                        <div class="col-md-4 form-group " id="customer_email">
                            <label for="company" class="small">Company Name</label>
                            <i class="mdi mdi-message-alert"  data-toggle="tooltip" data-placement="top" id="showtooltop" title="if user does not have a company name you may skip this field."></i>
                            <select id="company_list" name="company_id"  class="form-control select2" style="width: 100%; height:36px;"></select>
                        </div>
                        <div class="col-md-1" id="reverting">
                            <button type="button" id="new-Company-button" class="btn btn-success cmp_btn" style="margin-top:20px;;margin-right: 10px"> New </button>
                        </div>
                    </div>
                    <div class="row mb-2 mt-1">
                        <div class="col-md-6 checkbox form-check form-check-inline" style="padding-left:6px;">
                            <input id="cust_login" class="form-check-input" type="checkbox" name="" style="margin-top: 5px;margin-left: 8px;">
                            <label class="mb-0 form-check-label" for="inlineCheckbox2" data-labelfor="cust_login" style="margin-top: 5px;margin-left: 8px;">Create Customer Login Account</label>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-12 form-group">
                            <label>Street Address</label>
                            <div class="row">
                                <div class="col-md-6">
                                    <input type="text" class=" form-control" name="address" id="address" placeholder="House number and street name">
                                    <span class="text-danger small" id="err_strt"></span>
                                </div>
                                <div class="col-md-6">
                                    <input type="text" class=" form-control" name="apt_address" id="apt_address" placeholder="Apartment, suit, unit etc. (optional)">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row mt-1">
                        <div class="col-md-3 form-group">
                            <label for="city" class="small">City</label>
                            <input type="text" class="form-control" name="cust_city" id="city">
                            <span class="text-danger small" id="err9"></span>
                        </div>
                        <div class="col-md-3 form-group">
                            <label for="state" class="small">State</label>
                            @if($google_key == 1)
                                <input type="text" class=" form-control "  id="state" name="state" style="width: 100%; height:36px;"> 
                            @else
                                <select class="select2 form-control" name="cust_state" id="state" name="state" style="width: 100%; height:36px;"></select>
                            @endif

                            <span class="text-danger small" id="err8"></span>
                        </div>
                        <div class="col-md-3 form-group">
                            <label for="zip" class="small">Zip Code</label>
                            <input type="tel" maxlength="5" name="cust_zip" class="form-control" id="zip">
                            <span class="text-danger small" id="err10"></span>
                        </div>
                        
                        <div class="col-md-3 form-group">
                            <label for="country" class="small">Country</label>
                            @if($google_key == 1)
                                <input type="text" class=" form-control "  id="country" name="country" style="width: 100%; height:36px;"> 
                            @else
                                <select class="select2 form-control" onclick="getCountry(this.value)" id="country" name="country" style="width: 100%; height:36px;" onchange="listStates(this.value, 'state', '')">
                                    <option value="">Select Country</option>
                                    @foreach($countries as $country)
                                        <option value="{{$country->name}}" {{$country->short_name == 'US' ? 'selected' : ''}}>{{$country->name}}</option>
                                    @endforeach
                                </select>
                            @endif                           
                            <span class="text-danger small" id="err7"></span>
                        </div>
                    </div>
                    <!--New Company-->
                    <div class="form-group bg-light p-3 mt-1"  id="new-Company-div" style="display:none;">
                        <div class="row ">
                            <div class="col-sm-12">
                                <div class="row">
                                 <h2 class="col-md-12">New Company</h2>
                                 <hr>
                                </div>
                                <div class="row">
                                    <div class="col-md-4 form-group">
                                        <label for="name" class="small">Company Name <span class="text-danger">*</span> </label>
                                        <input type="text" id="cmp_name" class="form-control">
                                    </div>
                                    <div class="col-md-4 form-group">
                                        <label for="poc_first_name" class="small">Owner First Name</label>
                                        <input type="text" id="poc_first_name" class="form-control">
                                    </div>
                                    <div class="col-md-4 form-group">
                                        <label for="poc_last_name" class="small">Owner Last Name</label>
                                        <input type="text" class="form-control" id="poc_last_name">
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6 form-group">
                                        <label for="email" class="small">Email <span class="text-danger">*</span></label>
                                        <input type="email" class="form-control" name="company_email" id="cmp_email">
                                    </div>
                                    <div class="col-md-6 form-group">
                                        <label for="phone" class="small">Phone</label>
                                        <input type="tel" class="form-control" id="cmp_phone">
                                        <span class="text-danger small" id="cmp_phone_error"></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!--End New Company-->
                    <button type="submit" id="custsvebtn" class="btn btn-success btn-sm rounded float-right mr-2 mt-1 float-right"><i class="fas fa-check-circle"></i> Save</button>
                    <button type="button"  disabled style="display:none" id="custprocessbtn" class="btn btn-success btn-sm rounded float-right mr-2 mt-1 float-right"> <i class="fas fa-circle-notch fa-spin"></i> Processing </button>
                    
                </form>

                <div class="loader_container" id="cust_loader">
                    <div class="loader"></div>
                </div>
            </div>
        </div>
    </div>
</div> 

<div class="modal fade" id="delete_customer_model" role="dialog"  data-backdrop="static" aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header d-flex align-items-center">
                <h4 class="modal-title" >Delete Customer</h4>
                <button type="button" class="btn-close ml-auto" data-bs-dismiss="modal" aria-hidden="true"></button>
            </div>
            <div class="modal-body text-center">
                <input type="hidden" id="delete_id">
               All Customer Related Data will be Deleted.
            </div>
            <div class="modal-footer text-center" style="display:block;">
               <button class="btn btn-info rounded " data-dismiss="modal"><i class="fas fa-times"></i> Cancel </button>
               <button id="delbtn" class="btn btn-danger rounded " onclick="deleteRecord()"> <i class="fas fa-trash"></i> Delete </button>
               <button id="cust_del" style="display:none" class="btn btn-danger rounded"
                                                type="button" disabled><i class="fas fa-circle-notch fa-spin"></i>
                                                Deleting...</button>
            </div>
        </div>
    </div>
</div>
@endsection
@section('scripts') 
<!--This page plugins -->
<script type="text/javascript" src="{{asset('assets/dist/js/flashy.min.js')}}"></script>
@include('js_files.customer_lookup.indexJs')
@include('js_files.statesJs')
@include('js_files.customer_lookup.customerJs')
    
@endsection