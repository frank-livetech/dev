@extends('layouts.master-layout-new')
@section('Customer Manager','open')
@section('title', 'Company Lookup')
@section('Company Lookup','active')
@push('css')
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
        {{-- <link href="//cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css" rel="stylesheet"> --}}
        <link rel="stylesheet" href="https://unpkg.com/multiple-select@1.5.2/dist/multiple-select.min.css">
        <style>
    a.user_name.text-truncate {
    color: #5e50ee;
    text-decoration: none;}
    a.user_name.text-truncate:hover{
        color: #fab81c;
    }
    
        </style>
@endpush
@section('body')
<input type="hidden" id="user_id" value="{{auth()->user()->id}}">
<div class="app-content content">
    <div class="content-overlay"></div>
    <div class="header-navbar-shadow"></div>
    <div class="content-wrapper container-fluid p-0">
        <div class="content-header row">
            <div class="content-header-left col-md-12 col-12 mb-2">
                <div class="row breadcrumbs-top">
                    <div class="col-12">
                        <h2 class="content-header-title float-start mb-0">Company Lookup
                            </li></h2>
                        <div class="breadcrumb-wrapper">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{route('home')}}">Home</a>
                                </li>
                                <li class="breadcrumb-item"><a href="javascript:location.reload()">Company Manager</a>
                                </li>
                                <li class="breadcrumb-item active"><a href="javascript:location.reload()">Company Lookup</a>
                                </li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="content-body">
        <section class="app-user-list">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    {{-- <div class="row">
                        <div class="col-md-12" style="text-align:right;">
                        <button type="button"  class="btn btn-success btn-sm rounded"  id="company_add_btn"
            data-bs-toggle="modal" data-bs-target="#company_model"><i class="fa fa-plus-circle" style="padding-right:3px;"></i>Add Company</button>
                        </div>
                        
                </div> --}}
                <div class="table-responsive">
                    <table id="companyTable" class="companyTable table">
                        <thead>
                            <tr>
                                <th></th>
                                <th>Company Name</th>
                                <th>Phone</th>
                                {{-- <th>Address</th> --}}
                                <th>Created at</th>
                                {{-- <th>Status</th> --}}
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody id="companyTBody">
                        
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
</section>
    </div>

    <div class="modal fade text-start" id="company_model" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content ">
                <div class="modal-header">
                    <h4 class="modal-title" id="edit-company">Save Company</h4>
                    <button type="button" onclick="closeModal();" class="btn-close ml-auto" data-bs-dismiss="modal" aria-hidden="true"></button>
                </div>
                <div class="modal-body">
                    <form id="save_company_form" method="POST" action="{{url('save-company')}}">

                        <div class="form-row">
                            <div class="col-md-12 form-group">
                                <label for="name" class="small">Company Name <span class="fa fa-asterisk field-icon text-danger" aria-hidden="true"></span></label>
                                <input type="text" id="name" class="form-control" placeholder="Company Name">
                                <span class="text-danger small" id="err2"></span>
                            </div>
                        </div>
                            
                        <div class="row mt-1">
                            <div class="col-md-6 form-group">
                                <label for="poc_first_name" class="small">Owner First Name <span class="fa fa-asterisk field-icon text-danger" aria-hidden="true"></span></label>
                                <input type="text" id="poc_first_name" class="form-control" placeholder="First Name">
                                <span class="text-danger small" id="err"></span>
                            </div>
                            <div class="col-md-6 form-group">
                                <label for="poc_last_name" class="small">Owner Last Name  <span class="fa fa-asterisk field-icon text-danger" aria-hidden="true"></span></label>
                                <input type="text" class="form-control" id="poc_last_name" placeholder="Last Name">
                                <span class="text-danger small" id="err1"></span>
                            </div>
                        </div>

                        <div class="row mt-1">
                            <div class="col-md-6 form-group">
                                <label for="domain" class="small">Domain <span class="fa fa-asterisk field-icon text-danger" aria-hidden="true"></span></label>
                                <input type="text" class="form-control" id="domain" placeholder="Enter domain">
                                {{-- <span class="text-danger small" id="err3"></span> --}}
                            </div>
                            <div class="col-md-6 form-group">
                                <label for="phone" class="small">Phone</label>
                                <div class="d-flex">
                                    <div class="country mt-1" style="padding-right: 8px;"></div>
                                    <input type="tel" value="+" class="tel form-control" name="phone" id="phone" placeholder="" autofocus>
                                </div>
                                <small class="text-secondary">NOTE: Include country code before number e.g 1 for US</small>
                                {{-- <input type="tel" class="form-control" id="phone" placeholder="ed. 123456789">
                                <span class="text-danger small" id="err4"></span> --}}
                            </div>
                        </div>

                        
                        @if($is_default != 1)
                        <div class="row mt-1">
                            <div class="col-md-6">
                                <div class="custom-control custom-checkbox mr-sm-2">
                                    <input type="checkbox" class="custom-control-input" id="set_default">
                                    <label class="custom-control-label" for="set_default">Set as Default</label>
                                </div>
                            </div>
                        </div>
                        @endif

                        <div class="row mt-1">
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

                        <div class="row mt-1">
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
                                <input type="number" maxlength="5" class="form-control" id="zip" placeholder="eg. 12345">
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

    <div class="modal fade text-start" id="delete_company_model" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header d-flex align-items-center">
                    <h4 class="modal-title" >Delete Company</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
                </div>
                <div class="modal-body text-center">
                    <input type="hidden" id="delete_id">
                Delete this Company Permanently?
                </div>
                <div class="modal-footer text-center" style="display:block;">
                <button class="btn btn-info btn-sm rounded" data-bs-dismiss="modal"> <i class="fas fa-times"></i> Cancel </button>
                <button id="delbtn" class="btn btn-danger rounded btn-sm" onclick="deleteRecord()"> <i class="fas fa-trash"></i> Delete </button>
                <button id="cust_del" style="display:none" class="btn btn-danger btn-sm rounded"
                                                    type="button" disabled><i class="fas fa-circle-notch fa-spin"></i>
                                                    Deleting...</button>
                </div>
            </div>
        </div>
    </div>
</div>
@section('scripts')

    @include('js_files.company_lookup.indexJs')
    @include('js_files.companyJs')
    <script>
        jQuery(function($){
          var input = $('[type=tel]')
          input.mobilePhoneNumber({allowPhoneWithoutPrefix: '+1'});
          input.bind('country.mobilePhoneNumber', function(e, country) {
            $('.country').text(country || '')
          })
        });
      </script>
@endsection
@endsection