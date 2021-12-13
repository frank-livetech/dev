@extends('layouts.staff-master-layout')
    @push('css')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
        <link href="//cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css" rel="stylesheet">
        <link rel="stylesheet" href="https://unpkg.com/multiple-select@1.5.2/dist/multiple-select.min.css">
    <!-- <link rel="stylesheet" href="https://cdn.datatables.net/1.10.23/css/jquery.dataTables.min.css"> -->
    <style>
    .table th {
        padding-top:20px !important;
        padding-bottom:20px !important;
        font-size:1rem;
    }
    .pac-container {
        z-index: 10000 !important;
    }
    table.dataTable tbody tr {
        background-color: transparent !important;
    }
    .table-responsive{
        overflow-x: ;
    }
    #companyTable_length label,
    #companyTable_filter label{
        display:inline-flex;
    }
    #companyTable_filter label .form-control{
    width:98% !important;

    }
    .fa-asterisk {
        font-size: 6px;
        margin-right: 5px;
    }
    </style>
    @endpush
@section('body-content')

    <input type="hidden" id="user_id" value="{{auth()->user()->id}}">

    <div class="page-breadcrumb">
        <div class="row">
            <div class="col-md-5 align-self-center">
                <div class="d-flex align-items-center">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{route('home')}}">Home</a></li>
                            <li class="breadcrumb-item" aria-current="page">Company Manager</li>
                            <li class="breadcrumb-item active" aria-current="page">Company Lookup</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="company_model" role="dialog"  data-backdrop="static" aria-labelledby="myLargeModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header d-flex align-items-center">
                    <h4 class="modal-title" id="edit-company">Save Company</h4>
                    <button type="button" onclick="closeModal();" class="close ml-auto" data-dismiss="modal" aria-hidden="true">×</button>
                </div>
                <div class="modal-body">
                    <form id="save_company_form" method="POST" action="{{url('save-company')}}">

                        <div class="form-row">
                            <div class="col-md-12 form-group">
                                <label for="name" class="small">Company Name</label>
                                <input type="text" id="name" class="form-control" placeholder="Company Name">
                                <span class="fa fa-asterisk field-icon text-danger" aria-hidden="true"></span>
                                <span class="text-danger small" id="err2"></span>
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="col-md-6 form-group">
                                <label for="poc_first_name" class="small">Owner First Name</label>
                                <input type="text" id="poc_first_name" class="form-control" placeholder="First Name">
                                <span class="fa fa-asterisk field-icon text-danger" aria-hidden="true"></span>
                                <span class="text-danger small" id="err"></span>
                            </div>
                            <div class="col-md-6 form-group">
                                <label for="poc_last_name" class="small">Owner Last Name</label>
                                <input type="text" class="form-control" id="poc_last_name" placeholder="Last Name">
                                <span class="fa fa-asterisk field-icon text-danger" aria-hidden="true"></span>
                                <span class="text-danger small" id="err1"></span>
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="col-md-6 form-group">
                                <label for="email" class="small">Email</label>
                                <input type="text" class="form-control" id="email" placeholder="Email Address">
                                <span class="fa fa-asterisk field-icon text-danger" aria-hidden="true"></span>
                                <span class="text-danger small" id="err3"></span>
                            </div>
                            <div class="col-md-6 form-group">
                                <label for="phone" class="small">Phone</label>
                                <input type="tel" class="form-control" id="phone" placeholder="ed. 123456789">
                                <span class="text-danger small" id="err4"></span>
                            </div>
                        </div>

                        
                        @if($is_default != 1)
                        <div class="row">
                            <div class="col-md-6">
                                <div class="custom-control custom-checkbox mr-sm-2">
                                    <input type="checkbox" class="custom-control-input" id="set_default">
                                    <label class="custom-control-label" for="set_default">Set as Default</label>
                                </div>
                            </div>
                        </div>
                        @endif

                        <div class="form-row mt-3">
                            <div class="col-12 form-group">
                                <label>Street Address</label>
                                <div class="row">
                                    <div class="col-md-6">
                                        <input type="text" class=" form-control" name="address" id="address" placeholder="House number and street name">
                                        <span class="text-danger small" id="err9"></span>
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
                                <input type="text" class="form-control" id="city" placeholder="City">
                                <span class="text-danger small" id="err7"></span>
                            </div>
                            <div class="col-md-3 form-group">
                                <label for="state" class="small">State</label>
                                @if($google_key == 1) 
                                    <input type="text" class=" form-control " id="state" name="state"
                                        style="width: 100%; height:36px;" value="">
                                @else
                                    <select class="select2 form-control" id="state" name="state" style="width: 100%; height:36px;"></select>
                                @endif                            
                                <span class="text-danger small" id="err6"></span>
                            </div>
                            <div class="col-md-3 form-group">
                                <label for="zip" class="small">Zip Code</label>
                                <input type="tel" maxlength="5" class="form-control" id="zip" placeholder="eg. 12345">
                                <span class="text-danger small" id="err8"></span>
                            </div>
                            
                            <div class="col-md-3 form-group">
                                <label for="country" class="small">Country</label>
                                @if($google_key == 1) 
                                    <input type="text" class=" form-control" id="country" name="country" style="width: 100%; height:36px;" value="">
                                @else
                                    <select class="select2 form-control " id="country" name="country" style="width: 100%; height:36px;" onchange="listStates(this.value, 'state', '')">
                                        <option value="">Select Country</option>
                                        @foreach($countries as $cty)
                                            <option value="{{$cty->name}}" {{$cty->short_name == 'US' ? 'selected' : ''}}>{{$cty->name}}</option>
                                        @endforeach
                                    </select>
                                @endif                            
                                <span class="text-danger small" id="err5"></span>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-success mt-4 btn-sm rounded" id="savebtn" style="float:right;"> <i class="fas fa-check-circle"></i> Save</button>
                        <button style="display:none;float:right;" id="processing" class="btn btn-sm rounded btn-success" type="button" disabled><i class="fas fa-circle-notch fa-spin"></i> Processing</button>
                    </form>
                </div>
            </div><!-- /.modal-content -->
        </div>
    </div>

    <div class="modal fade" id="delete_company_model" role="dialog"  data-backdrop="static" aria-labelledby="myLargeModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header d-flex align-items-center">
                    <h4 class="modal-title" >Delete Company</h4>
                    <button type="button" class="close ml-auto" data-dismiss="modal" aria-hidden="true">×</button>
                </div>
                <div class="modal-body text-center">
                    <input type="hidden" id="delete_id">
                Delete this Company Permanently?
                </div>
                <div class="modal-footer text-center" style="display:block;">
                <button class="btn btn-info btn-sm rounded" data-dismiss="modal"> <i class="fas fa-times"></i> Cancel </button>
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
                                <button type="button"  class="btn btn-success btn-sm rounded"  id="company_add_btn"
                    data-toggle="modal" data-target="#company_model"><i class="mdi mdi-plus-circle" style="padding-right:3px;"></i>Add Company</button>
                                </div>
                        </div>
                    
                            <!-- <div class="row">
                                <div class="col-md-12" style="text-align:right;">
                                    <select class="multiple-select mt-2 mb-2" name="select_column" id="select_column" placeholder="Show/Hide" multiple="multiple" selected="selected" style="text-align:left;">
                                        <option value="0">Checkbox</option>
                                        <option value="1">Sr</option>
                                        <option value="2">Profile</option>
                                        <option value="5">Company Name</option>
                                        <option value="3">Owner First Name</option>
                                        <option value="4">Owner Last Name</option>
                                        <option value="6">E-mail</option>
                                        <option value="7">Address</option>
                                        <option value="8">Phone</option>
                                        <option value="9">Created at</option>
                                        <option value="10">Action</option>
                                    </select>
                                </div>
                            </div> -->
                        <div class="table-responsive mt-3">
                            <table id="companyTable" class="companyTable table table-striped table-bordered table-hover text-center w-90">
                                <thead>
                                    <tr>
                                        <th>
                                            <div class="custom-control custom-checkbox">
                                                <input type="checkbox" class="custom-control-input" id="customCheck1">
                                                <label class="custom-control-label" for="customCheck1"></label>
                                            </div>
                                        </th>
                                        <!-- <th>Sr</th> -->
                                        <th>Profile</th>
                                        <th>Company Name</th>
                                        <th>Owner First Name</th>
                                        <th>Owner Last Name</th>
                                        <th>E-mail</th>
                                        <th>Phone</th>
                                        <th>Address</th>
                                        <!-- <th>Country</th>
                                        <th>State</th>
                                        <th>City</th>
                                        <th>Zip Code</th> -->
                                        <th>Created at</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody id="companyTBody" class="small">
                                
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



    @section('scripts')

    @include('js_files.company_lookup.indexJs')
    @include('js_files.companyJs')

    @endsection
@endsection
