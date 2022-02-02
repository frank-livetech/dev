@extends('layouts.master-layout-new')
@section('body')

@php
    $file_path = Session::get('is_live') == 1 ? 'public/' : '/';
    $path = Session::get('is_live') == 1 ? 'public/system_files/' : 'system_files/';
@endphp
<div class="app-content content">
    <div class="content-overlay"></div>
    <div class="header-navbar-shadow"></div>
    <div class="content-wrapper container-fluid p-0">
        <div class="content-header row">
            <div class="content-header-left col-md-12 col-12 mb-2">
                <div class="row breadcrumbs-top">
                    <div class="col-12">
                        <h2 class="content-header-title float-start mb-0">Billing</h2>
                        <div class="breadcrumb-wrapper">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{route('home')}}">Home</a></li>
                                <li class="breadcrumb-item " aria-current="page">Billing</li>
                                <li class="breadcrumb-item active" aria-current="page">RFQ</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="content-body">
            <div class="row">
                <div class="col-lg-4">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="card-title">RFQ FORM</h4>
                            <form method="post" action="{{asset('/save_rfq_requests')}}" class="form-horizontal" id="rfq_form">
                                <div class="row">
                                    <div class="col-lg-6 col-md-12 col-sm-12">
                                        <div class="form-group my-1">
                                            <label>Subject</label>
                                            <input type="text" class="form-control" placeholder="Subject" id="subject" name="subject" value="" required>
                                        </div>
                                    </div>
                                    <div class="col-lg-6 col-md-12 col-sm-12">
                                        <div class="form-group my-1">
                                            <label>PO#:</label>
                                            <input type="text" id="purchase_order" name="purchase_order" class="form-control" placeholder="PO#: ABC-123-54321" value="" required>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group my-1">
                                    <label for="example-email">To Email <span class="help"> e.g. "example@gmail.com"</span></label>
                                    <input type="text" id="to_mails" name="to_mails" class="form-control" placeholder="Email" data-role="tagsinput" value="" required>

                                </div>


                                <div class="form-group my-1">
                                    <label>Quote Details</label>
                                    <textarea class="form-control tinymce" id="mymce" rows="5" id="rfq_details" name="rfq_details" value=""></textarea>
                                </div>
                                <div class="form-actions" style="text-align: right;">

                                    <button type="button" class="btn btn-dark" onclick="clearform();">Cancel</button>
                                    <button type="submit" class="btn btn-success" style="margin-left:10px;"> <i class="fa fa-check"></i> Send</button>

                                </div>

                            </form>
                            <br>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="d-flex justify-content-between">
                                        <h5>Seller instruction Note</h5>
                                        <a type="button" id="InstNote"> <i class="fas fa-edit"></i> </a>
                                    </div>
                                </div>
                                <div class="col-md-12 sellInstP">
                                    <p id="displayNotes"> {{isset($setting_notes->sys_value) ? $setting_notes->sys_value : ''}} </p>
                                </div>
                                <div class="col-md-12 sellInst">
                                    <form method="post" action="{{asset('/save_inst_notes')}}" id="instNotesForm">
                                        <div class="form-group my-1">
                                            <!-- <label>Seller Instruction Note</label> -->
                                            <textarea class="form-control tinymce" id="sell_inst_note" rows="2" name="sell_inst_note" value=""></textarea>
                                        </div>
                                        <button class="btn btn-success btn-sm rounded" style="float:right"> <i class="fas fa-check-circle"></i> Save</button>
                                    </form>
                                </div>
                            </div>
                        </div>

                    </div>

                </div>
                <div class="col-lg-8">

                    <div class="card">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-8">
                                    <h4 class="card-title">Vendor Contacts</h4>
                                </div>
                                <div class="col-md-4">
                                    <button onclick="showVendorModel()" type="button" class="btn btn-success btn-sm rounded" style="float:right;"><i class="fa fa-plus" style=""></i>&nbsp;  Add New</button>
                                </div>
                            </div>
                            <div class="row">
                                <!-- <div class="col-md-12" style="text-align:right;">
                                    <select class="multiple-select mt-2" name="vendor_column" id="vendor_column" placeholder="Show/Hide" multiple="multiple" selected="selected">
                                        <option value="0">#</option>
                                        <option value="1">Name</option>
                                        <option value="2">Company</option>
                                        <option value="3">Email</option>
                                        <option value="4">Good For</option>
                                        <option value="5">Phone</option>
                                        <option value="6">Tags</option>
                                        <option value="7">Action</option>
                                    </select>
                                </div>
                            </div>
                            <br> -->
                                <div class="table-responsive mt-2">
                                    <table class="table table-hover w-100" id="contacts_table">
                                        <thead class="thead-light">
                                            <tr>
                                                <th scope="col"><input type="checkbox" id="checkAll" name="contacts[]" value="0"></th>
                                                <th scope="col">Name</th>
                                                <th scope="col">Company</th>
                                                <th scope="col">Email</th>
                                                <th scope="col">Good For</th>
                                                <th scope="col">Phone</th>
                                                <th scope="col">Tags</th>
                                                <th scope="col">Actions</th>

                                            </tr>
                                        </thead>
                                        <tbody>

                                        </tbody>
                                    </table>
                                </div>
                                <!--vendor_modal-->
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
     <!--Model for Request-->
    
     <div class="modal fade" id="vendor_modal" role="dialog" data-backdrop="static" aria-labelledby="myLargeModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header d-flex align-items-center">
                        <h4 class="modal-title" id="edit-contact">Save Vendor</h4>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form class="form-horizontal" id="save_vendor_form" action="{{asset('/save-vendor')}}" method="post">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group my-1">
                                        <label>First Name</label>
                                        <input type="text" class="form-control" id="first_name" name="first_name" required>
                                        <input class="form-control" type="text" name="contact_id" id="contact_id" hidden>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group my-1">
                                        <label>Last Name</label>
                                        <input type="text" class="form-control" id="last_name" name="last_name" required>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group my-1">
                                        <label>Company</label>
                                        <select class="select2 form-control" id="comp_id" name="comp_id" style="width: 100%; height:36px;">
                                            @foreach($companies as $company)
                                            <option value="{{$company->id}}">{{$company->name}}</option>
                                            @endforeach
                                        </select>
                                        <input type="hidden" class="form-control" id="company" name="company">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group my-1">
                                        <label>Website</label><span style="color:red"> ( Add Url with http or https )</span>
                                        <input type="text" class="form-control" id="website" name="website">
                                    </div>
                                </div>

                            </div>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group my-1">
                                        <label>Email</label>
                                        <input type="text" class="form-control" id="email" name="email" required>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group my-1">
                                        <label>Direct Line</label>
                                        <input type="text" class="form-control" id="direct_line" name="direct_line">
                                        <span class="text-danger small" id="phone_error"></span>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group my-1">
                                        <label>Phone 2</label>
                                        <input type="text" class="form-control" id="phone" name="phone" required>
                                        <span class="text-danger small" id="phone_error2"></span>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <label>Vendor Categories</label>
                                    <select class="select2 form-control" id="categories" name="categories" multiple="multiple" style="height: 36px;width: 100%;">
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <label>Add New Category</label>
                                    <input type="text" class="form-control" id="add_category_name" name="add_category_name">
                                </div>
                                <div class="col-md-3" style="padding-top: 20px;">
                                    <button class="btn btn-success" style="" id="add_category_btn"><i class="fa fa-plus"></i>  Add</button>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <label>Tags</label>
                                    <select class="select2 form-control custom-select" id="tags" name="tags" multiple="multiple" style="height: 36px;width: 100%;">
                                        @foreach($tags as $tag)
                                        <option value="{{$tag->id}}">{{$tag->name}}</option>
                                        @endforeach
                                    </select>

                                </div>
                                <div class="col-md-6 my-1">
                                    <div class="checkbox checkbox-primary py-1">
                                        <input id="checkbox3" type="checkbox" name="has_account" class="form-check-input">
                                        <label class="mb-0" for="checkbox3">Create new vendor</label>
                                    </div>
                                </div>

                            </div>
                            <button type="submit" class="btn btn-success" style="float:right;">Save</button>
                        </form>
                    </div>
                </div><!-- /.modal-content -->
            </div><!-- /.modal-dialog -->
        </div><!-- /.modal -->
</div>
@endsection
@section('scripts')
    <script type="text/javascript" src="{{asset('/assets/dist/js/flashy.min.js')}}"></script>
    <script src="{{asset('https://cdn.jsdelivr.net/npm/sweetalert2@9')}}"></script>
    <script src="{{asset('/assets/libs/tinymce/tinymce.min.js')}}"></script>
    <script src="https://unpkg.com/multiple-select@1.5.2/dist/multiple-select.min.js"></script>
    @include('js_files.help_desk.rfq.indexJs')

    @endsection