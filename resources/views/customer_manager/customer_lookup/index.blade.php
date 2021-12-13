@extends('layouts.staff-master-layout')
@push('css')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
<link rel="stylesheet" href="https://unpkg.com/multiple-select@1.5.2/dist/multiple-select.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/1.10.23/css/jquery.dataTables.min.css">
    <style>
        
        .table th {
            padding-top:20px !important;
            padding-bottom:20px !important;
            font-size:1rem;
        }      
        .pac-container {
            z-index: 10000 !important;
        }
        table.dataTable thead .sorting:before, table.dataTable thead .sorting_asc:before, table.dataTable thead .sorting_desc:before, table.dataTable thead .sorting_asc_disabled:before, table.dataTable thead .sorting_desc_disabled:before {
            right: 1em;
            content: "" !important;
        }
        table.dataTable thead .sorting:after, table.dataTable thead .sorting_asc:after, table.dataTable thead .sorting_desc:after, table.dataTable thead .sorting_asc_disabled:after, table.dataTable thead .sorting_desc_disabled:after {
            right: 0.5em;
            content: "" !important;
        }
    </style>
@endpush
@section('body-content')

<!-- loader code-m -->

<div class="page-breadcrumb">
    <div class="row">
        <div class="col-md-5 align-self-center">
            <!--<h4 class="page-title">Basic Table</h4>-->
            <div class="d-flex align-items-center">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{route('home')}}">Home</a></li>
                        {{-- <li class="breadcrumb-item" aria-current="page">Customer Manager</li> --}}
                        <li class="breadcrumb-item active" aria-current="page">Customer Lookup</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
</div>
<input type="hidden" id="curr_user_name" value="{{Auth::user()->name}}">
@if($brand != null && $brand->site_domain != null && $brand->site_domain != "")
    <input type="hidden" id="site_domain" value="{{$brand->site_domain}}">
@endif

<!--  Modal content customer start -->
<div class="modal fade" id="addCustomerModal" role="dialog"  data-backdrop="static" aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add New Customer</h5>
                <button type="button" onclick="closeModal()" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body p-3">
                <form id="save_customer_form" autocomplete="off" enctype="multipart/form-data" method="POST" action="{{url('save_customer')}}">
                    <div class="form-row">
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

                    <div class="form-row">
                        <div class="col-md-6 form-group">
                            <label for="phone" class="small">Phone</label>
                            <!-- <input type="text" onkeypress="return event.charCode >= 48 && event.charCode <= 57" class="form-control" name="phone" id="phone" placeholder="eg. 3334445555"> -->
                            <input type="text" class="form-control" name="phone" id="phone" placeholder="eg. 3334445555">
                            <span class="text-danger small" id="phone_error"></span>
                        </div>
                        <div class="col-md-5 form-group " id="customer_email">
                            <label for="company" class="small">Company Name</label>
                            <i class="mdi mdi-message-alert"  data-toggle="tooltip" data-placement="top" id="showtooltop" title="if user does not have a company name you may skip this field."></i>
                            <select id="company_list" name="company_id"  class="form-control select2" style="width: 100%; height:36px;"></select>
                        </div>
                        <div class="col-md-1" id="reverting">
                            <button type="button" id="new-Company-button" class="btn btn-success cmp_btn" style="margin-top:25px;"> New </button>
                        </div>
                    </div>
                    <div class="form-row mb-2">
                        <!-- <div class="col-md-6 checkbox checkbox-info" style="padding-left:26px;">
                            <input id=""  type="checkbox" name="newCompany" style="margin-top: 5px;margin-left: 8px;">
                            <label class="mb-0" data-labelfor="new-Company_check">New Company</label>
                        </div> -->
                        <div class="col-md-6 checkbox checkbox-info" style="padding-left:26px;">
                            <input id="cust_login" type="checkbox" name="" style="margin-top: 5px;margin-left: 8px;">
                            <label class="mb-0" data-labelfor="cust_login">Create Customer Login Account</label>
                        </div>
                    </div>

                    <div class="form-row">
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

                    <div class="form-row">
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
                    <div class="form-group bg-light p-3"  id="new-Company-div" style="display:none;">
                        <div class="row mt-3">
                            <div class="col-sm-12">
                                <div class="row">
                                 <h2 class="col-md-12">New Company</h2>
                                 <hr>
                                </div>
                                <div class="form-row">
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

                                <div class="form-row">
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
                    <button type="submit" id="custsvebtn" class="btn btn-success btn-sm rounded float-right mr-2"><i class="fas fa-check-circle"></i> Save</button>
                    <button type="button"  disabled style="display:none" id="custprocessbtn" class="btn btn-success btn-sm rounded float-right mr-2"> <i class="fas fa-circle-notch fa-spin"></i> Processing </button>
                    
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
                <button type="button" class="close ml-auto" data-dismiss="modal" aria-hidden="true">×</button>
            </div>
            <div class="modal-body text-center">
                <input type="hidden" id="delete_id">
               All Customer Related Data will be Deleted.
            </div>
            <div class="modal-footer text-center" style="display:block;">
               <button class="btn btn-info rounded btn-sm" data-dismiss="modal"><i class="fas fa-times"></i> Cancel </button>
               <button id="delbtn" class="btn btn-danger rounded btn-sm" onclick="deleteRecord()"> <i class="fas fa-trash"></i> Delete </button>
               <button id="cust_del" style="display:none" class="btn btn-danger btn-sm rounded"
                                                type="button" disabled><i class="fas fa-circle-notch fa-spin"></i>
                                                Deleting...</button>
            </div>
        </div>
    </div>
</div>

<div class="container-fluid" style="padding-bottom: 0px;">
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12" style="text-align:right;">
                            <button id="customer_add_btn" data-toggle="modal" data-target="#addCustomerModal"  class="float-right btn-sm rounded btn btn-success"><i class="mdi mdi-plus-circle"></i> New Customer </button>

                        @if($wp_value == 1)
                            <button class="float-right btn-sm rounded btn btn-info mr-3"><i class="fas fa-sync"></i> Sync WP Customers  </button>
                        @endif
                        </div>      
                    </div>
                    <!-- <div class="row">
                        <div class="col-md-12" style="text-align:right;">
                            <select class="multiple-select mt-2 mb-2" name="toggle_column" id="toggle_column" placeholder="Show/Hide" multiple="multiple" selected="selected">
                                <option value="0">Checkbox</option>
                                <option value="1">Sr#</option>
                                <option value="2">Profile</option>
                                <option value="3">First Name</option>
                                <option value="4">Last Name</option>
                                <option value="5">E-mail</option>
                                <option value="7">Phone</option>
                                <option value="8">Company</option>
                                <option value="6">Address</option>
                                <option value="9">Country</option>
                                <option value="10">State</option>
                                <option value="11">City</option>
                                <option value="12">Zip Code</option>
                                <option value="13">Action</option>
                            </select>
                        </div>
                        <div class="col-md-4"></div>
                        <div class="col-md-4"></div>
                    </div> -->
                    <div class="table-responsive mt-3">
                        <table id="customerTable" class="companyTable table table-striped table-bordered text-center table-hover w-100">
                            <thead>
                                <tr>
                                    <th>
                                        <div class="custom-control custom-checkbox">
                                            <input type="checkbox" class="custom-control-input" id="customCheck1">
                                            <label class="custom-control-label" for="customCheck1"></label>
                                        </div>
                                    </th>
                                    <!-- <th>Sr#</th> -->
                                    <th> </th>
                                    <th>First Name</th>
                                    <th>Last Name</th>
                                    <th>E-mail</th>
                                    <th>Phone</th>
                                    <th>Company</th>
                                    <th>Address</th>
                                    <!-- <th>Country</th>
                                    <th>State</th>
                                    <th>City</th>
                                    <th>Zip Code</th> -->
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
@endsection   
@section('scripts') 
<!--This page plugins -->
<!-- <script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script> -->
<script type="text/javascript" src="{{asset('assets/dist/js/flashy.min.js')}}"></script>
<script src="{{asset('assets/libs/datatables/media/js/jquery.dataTables.min.js')}}"></script>
<script src="{{asset('assets/libs/datatables/media/js/jquery.tabledit.min.js')}}"></script>
<script src="{{asset('assets/dist/js/pages/datatable/custom-datatable.js')}}"></script>
<!-- start - This is for export functionality only -->
<script src="https://cdn.datatables.net/buttons/1.5.1/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.5.1/js/buttons.flash.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.32/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.32/vfs_fonts.js"></script>
<script src="https://cdn.datatables.net/buttons/1.5.1/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.5.1/js/buttons.print.min.js"></script>
<script src="{{asset('assets/dist/js/pages/datatable/datatable-advanced.init.js')}}"></script>
<script src="{{asset('assets/dist/js/pages/tables/customer_jsgrid.js')}}"></script>

<script src="https://unpkg.com/multiple-select@1.5.2/dist/multiple-select.min.js"></script>
<script src="https://cdn.datatables.net/1.10.23/js/jquery.dataTables.min.js"></script>
@include('js_files.customer_lookup.indexJs')
@include('js_files.statesJs')
@include('js_files.customer_lookup.customerJs')
    
@endsection