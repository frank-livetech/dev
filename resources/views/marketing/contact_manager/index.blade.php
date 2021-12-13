@extends('layouts.staff-master-layout')
@push('css')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
<link rel="stylesheet" href="https://unpkg.com/multiple-select@1.5.2/dist/multiple-select.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/1.10.23/css/jquery.dataTables.min.css">
@endpush
@section('body-content')
<style>
    .select2-container--default .select2-selection--single .select2-selection__rendered li {
        list-style: none;
    }

    .select2-selection__rendered,
    #select2-TagList-container {
        height: 34px !important;
        margin-top: 2px;
    }

    .select2-container--default .select2-selection--single .select2-selection__rendered {
        background-color: #009efb !important;
        border-color: #009efb !important;
        color: #fff !important;
    }

    .select2-container--default .select2-selection--single .select2-selection__rendered {
        background-color: #e4e4e4;
        border: 1px solid #aaa;
        border-radius: 4px;
        cursor: default;
        float: left;
        margin-left: 2px;
        padding: 0 15px;
    }

    .select2-container--default .select2-selection--single .select2-selection__rendered {
        line-height: 32px !important;
    }

    .select2-container--default .select2-selection--single .select2-selection__placeholder{
        color : white !important;
    }

    table th{
        font-size: 11px !important;
    }

    table td{
        vertical-align: middle !important;
    }

    .select2-search__field {
        width: auto !important;
    }

    .fa-asterisk {
        font-size: 6px;
        margin-right: 5px;
    }
</style>
<div class="page-breadcrumb">
    <div class="row">
        <div class="col-md-5 align-self-center">
            <div class="d-flex align-items-center">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{route('home')}}">Home</a></li>
                        <li class="breadcrumb-item" aria-current="page">Marketing</li>
                        <li class="breadcrumb-item active" aria-current="page">Contacts Manager</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
</div>
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6" style="padding:0;">
                            <h4 class="card-title">Contacts List</h4>
                        </div>
                        <div class="col-md-6" style="text-align:right;padding:0;">
                            
                            <select class="select2 form-control" id="TagList" name="TagList" style="width:35%; height:38px;" onchange="updateContactList();">
                                <option></option>
                            </select>

                            <button class="btn btn-info" id="edit_btn" onclick="editTag(name,id);"><i
                                    class="fas fa-edit" style="color:#fff;"></i></button>
                            <button class="btn btn-success mr-1" data-toggle="modal" data-target="#Save_Add_Tag">
                            <i class="mdi mdi-plus-circle"></i>&nbsp;Add Tag</button>
                            <button class="btn btn-success" title="Add New Contact" data-toggle="modal" data-target="#contactFormModal">
                            <i class="mdi mdi-plus-circle"></i>&nbsp;New</button>
                            
                            <div class="input-group mt-3">
                                <div class="custom-file text-left">
                                    <input type="file" class="custom-file-input" id="importContacts">
                                    <label class="custom-file-label" for="importContacts">Choose file</label>
                                </div>
                                <div class="input-group-append">
                                    <button class="btn btn-outline-secondary" id="submitContactsList" type="button" title="Import Contacts">Import</button>
                                </div>
                            </div>

                            <div class="modal fade" id="Save_Add_Tag" data-backdrop="static" tabindex="-1" aria-labelledby="ModalLabel" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="ModalLabel">Add New Tag</h5>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <form id="save_tag" action="{{asset('save-department')}}" method="post">
                                            <div class="modal-body" style="text-align:left;">
                                                <div class="form-group">
                                                    {{-- <label for="newTag">Tag</label> --}}
                                                    <input type="text" class="form-control" id="newTag" name="name" placeholder="Tag" title="Tag" required>
                                                    <input type="text" class="form-control" id="newTagId" name="newTagId" hidden>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="submit" class="btn btn-success" id="add_tag_btn">Add</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-12" style="text-align:right;">
                            <select class="multiple-select mt-2 mb-2" name="cm_select" id="cm_select" placeholder="Show/Hide" multiple="multiple" selected="selected">
                                <option value="0">Sr#</option>
                                <option value="1">ID</option>
                                <option value="2">Name</option>
                                <option value="3">Company</option>
                                <option value="4">Email</option>
                                <option value="5">Contact</option>
                                <option value="6">Action</option>
                            </select>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <!-- <div class="col-12 mb-3">
                            <a class="toggle-vis btn btn-sm btn-success text-white mb-1" data-column="0">#</a> - 
                            <a class="toggle-vis btn btn-sm btn-success text-white mb-1" data-column="1">ID</a> - 
                            <a class="toggle-vis btn btn-sm btn-success text-white mb-1" data-column="2">Name</a> - 
                            <a class="toggle-vis btn btn-sm btn-success text-white mb-1" data-column="3">Company</a> - 
                            <a class="toggle-vis btn btn-sm btn-success text-white mb-1" data-column="4">Email</a> - 
                            <a class="toggle-vis btn btn-sm btn-success text-white mb-1" data-column="5">Contact</a> - 
                            <a class="toggle-vis btn btn-sm btn-success text-white mb-1" data-column="6">Contact</a> - 
                            <a class="toggle-vis btn btn-sm btn-success text-white mb-1" data-column="7">Actions</a>
                        </div> -->

                        <table id="file_export" class="table table-striped table-bordered no-wrap" style="width: 100%">
                            <thead>
                                <tr>
                                    <th style="text-align:center;"><input type="checkbox" name="select_all[]" id="select-all"></th>
                                    <th>ID</th>
                                    <th>Name</th>
                                    <th>Company</th>
                                    <th>Email</th>
                                    <th>Contact</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Contact Form Modal -->
<div class="modal fade bd-example-modal-xl" id="contactFormModal" tabindex="-1" role="dialog"  data-backdrop="static" aria-labelledby="myExtraLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header d-flex align-items-center">
                <h5 class="modal-title">Contact Form</h5>
                <button type="button" class="close ml-auto" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body">
                <form class="widget-box widget-color-dark contact-form mb-0" id="save_contact" action="{{asset('/contact')}}" method="post" enctype="multipart/form-data">
                    <input type="hidden" name="contact_id" id="contact_id">
                    <div class="widget-body">
                        <div class="widget-main">

                            <div class="form-row justify-content-center">
                                <div class="form-group col-12 col-lg-4">
                                    {{-- <label class="control-label" for="first_name">First Name</label> --}}
                                    <span class="block input-icon input-icon-right">
                                        <input name="first_name" id="first_name" class="form-control" type="text" value="" placeholder="First Name" title="First Name" required>
                                        <span class="fa fa-asterisk field-icon text-danger"></span>
                                    </span>
                                </div>

                                <div class="form-group col-12 col-lg-4">
                                    {{-- <label class="control-label">Last Name</label> --}}
                                    <span class="block input-icon input-icon-right">
                                        <input name="last_name" id="last_name" class="form-control" type="text" value="" placeholder="Last Name" title="Last Name" required>
                                        <span class="fa fa-asterisk field-icon text-danger"></span>
                                    </span>
                                </div>

                                <div class="form-group col-12 col-lg-4">
                                    {{-- <label class="control-label">Company</label> --}}
                                    <span class="block input-icon input-icon-right">
                                        <input name="company" id="company" class="form-control" type="text" value="" placeholder="Company" title="Company" required>
                                        <span class="fa fa-asterisk field-icon text-danger"></span>
                                    </span>
                                </div>
                            </div>

                            <div class="form-row justify-content-center">
                                <div class="form-group col-12 col-lg-6">
                                    {{-- <label class="control-label">Email</label> --}}
                                    <span class="block input-icon input-icon-right">
                                        <input name="email_1" id="email_1" class="form-control" type="email" value="" placeholder="Email" title="Email" required>
                                        <span class="fa fa-asterisk field-icon text-danger"></span>
                                    </span>
                                </div>

                                <div class="form-group col-12 col-lg-6">
                                    {{-- <label class="control-label">Secondary Email</label> --}}
                                    <input name="email_2" id="email_2" class="form-control" type="email" value="" placeholder="Secondary Email" title="Secondary Email">
                                </div>
                            </div>

                            <div class="form-row justify-content-center">
                                <div class="form-group col-12 col-lg-6">
                                    {{-- <label class="control-label">Cell Contact</label> --}}
                                    <span class="block input-icon input-icon-right">
                                        <input name="cell_num" id="cell_num" class="form-control" type="text" value="" placeholder="Cell Contact" title="Cell Contact" required>
                                        <span class="fa fa-asterisk field-icon text-danger"></span>
                                    </span>
                                </div>
                                
                                <div class="form-group col-12 col-lg-6">
                                    {{-- <label class="control-label">Office Contact</label> --}}
                                    <input name="office_num" id="office_num" class="form-control" type="text" value="" placeholder="Office Contact" title="Office Contact">
                                </div>
                            </div>

                            <div class="form-row justify-content-center">
                                <div class="form-group col-12">
                                    {{-- <label class="control-label">Street Address</label> --}}
                                    <span class="block input-icon input-icon-right">
                                        <input name="street_addr_1" id="street_addr_1" class="form-control" type="text" value="" placeholder="Street Address" title="Street Address" required>
                                        <span class="fa fa-asterisk field-icon text-danger"></span>
                                    </span>
                                </div>
                            </div>

                            <div class="form-row justify-content-center">
                                <div class="form-group col-12">
                                    {{-- <label class="control-label">Street Address 2</label> --}}
                                    <input name="street_addr_2" id="street_addr_2" class="form-control" type="text" value="" placeholder="Street Address 2" title="Street Address 2">
                                </div>
                            </div>

                            <div class="form-row justify-content-center">
                                <div class="form-group col-12 col-lg-3">
                                    {{-- <label class="control-label">City</label> --}}
                                    <span class="block input-icon input-icon-right">
                                        <input name="city_name" id="city_name" class="form-control" type="text" value="" placeholder="City" title="City" required>
                                        <span class="fa fa-asterisk field-icon text-danger"></span>
                                    </span>
                                </div>

                                <div class="form-group col-12 col-lg-3">
                                    {{-- <label class="control-label">State</label> --}}
                                    <span class="block input-icon input-icon-right">
                                        <input name="state" id="state" class="form-control" type="text" value="" placeholder="State" title="State" required>
                                        <span class="fa fa-asterisk field-icon text-danger"></span>
                                    </span>
                                </div>

                                <div class="form-group col-12 col-lg-3">
                                    {{-- <label class="control-label">Zip Code</label> --}}
                                    <span class="block input-icon input-icon-right">
                                        <input name="zip_code" id="zip_code" class="form-control" type="text" value="" placeholder="Zip Code" title="Zip Code" required>
                                        <span class="fa fa-asterisk field-icon text-danger"></span>
                                    </span>
                                </div>

                                <div class="form-group col-12 col-lg-3">
                                    {{-- <label class="control-label">Country</label> --}}
                                    <span class="block input-icon input-icon-right">
                                        <input name="country_name" id="country_name" class="form-control" type="text" value="" placeholder="Country" title="Country" required>
                                        <span class="fa fa-asterisk field-icon text-danger"></span>
                                    </span>
                                </div>
                            </div>

                            <div class="form-row justify-content-center">
                                <div class="form-group selectpickertag col-12">
                                    {{-- <label class="control-label">Tags</label> --}}
                                    <select class="select2 form-control" id="tag_id" name="tag_id" multiple="multiple" style="height: 36px;width: 100%;" title="Tags"></select>
                                </div>
                            </div>

                            <div class="form-row justify-content-center">
                                <div class="form-group col-12">
                                    {{-- <label class="control-label">Notes</label> --}}
                                    <textarea name="notes" id="notes" class="form-control" type="text" value="" placeholder="Notes" rows="3" title="Notes"></textarea>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-12">
                                    <div class="form-group mb-0 align-self-bottom">
                                        <div class="col-sm-offset-4 col-12 text-right">
                                        <button type="Submit" class="btn waves-effect waves-light btn-success">Save</button>
                                            <!-- <button type="Submit" class="btn waves-effect waves-light btn-success"><i class="fas fa-save"></i>&nbsp;Save</button> -->
                                            <!-- <button class="btn btn-danger" data-dismiss="modal">Discard</button> -->
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!--<script src="theme_assets/libs/jsgrid/db.js"></script>-->
@endsection
@section('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.8.0/jszip.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.8.0/xlsx.js"></script>
<script src="https://unpkg.com/multiple-select@1.5.2/dist/multiple-select.min.js"></script>
<script src="https://cdn.datatables.net/1.10.23/js/jquery.dataTables.min.js"></script>
@include('js_files.marketing.contact_manager.indexJs')

    <!--<script src="{{asset('assets/libs/jsgrid/jsgrid.min.js')}}"></script>-->
    <!--<script src="{{asset('assets/dist/js/pages/tables/jsgrid-init.js')}}"></script>-->
    <!-- start - This is for export functionality only -->

<!--This page plugins -->
<script type="text/javascript" src="{{asset('assets/dist/js/flashy.min.js')}}"></script>
{{-- <script src="{{asset('assets/libs/datatables/media/js/jquery.dataTables.min.js')}}"></script> --}}
<script src="{{asset('assets/libs/datatables/media/js/jquery.tabledit.min.js')}}"></script>
{{-- <script src="{{asset('assets/dist/js/pages/datatable/custom-datatable.js')}}"></script> --}}
<!-- start - This is for export functionality only -->
<script src="https://cdn.datatables.net/buttons/1.5.1/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.5.1/js/buttons.flash.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.32/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.32/vfs_fonts.js"></script>
<script src="https://cdn.datatables.net/buttons/1.5.1/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.5.1/js/buttons.print.min.js"></script>
<script src="{{asset('assets/dist/js/pages/datatable/datatable-advanced.init.js')}}"></script>
<script src="{{asset('assets/dist/js/pages/tables/customer_contact_dt.js')}}"></script>

<script>
    // $('#file_export').Tabledit({
    //     url: 'aer',
    //     dataType: 'json',
    //     restoreButton: false,
    //     buttons: {
    //         edit: {
    //             class: 'btn btn-sm btn-secondary d-none',
    //             html: '<span class="fa fa-pencil"></span>',
    //             action: 'edit'
    //         },
    //         delete: {
    //             class: 'btn btn-sm btn-secondary d-none',
    //             html: '<span class="fa fa-trash"></span>',
    //             action: 'delete'
    //         },
    //         save: {
    //             class: 'btn btn-sm btn-success d-none',
    //             html: 'Save'
    //         },
    //     },
    //     columns: {
    //         identifier: [1, 'id'],
    //         editable: [
    //             [2, 'first_name'],
    //             [3, 'last_name'],
    //             [4, 'company'],
    //             [5, 'email_1'],
    //         ]
    //     },
    //     onSuccess: function(data, textStatus, jqXHR) {
    //         if (data.action == 'edit') {
    //             Swal.fire({
    //             position: 'top-end',
    //             icon: 'success',
    //             title: data['message'],
    //             showConfirmButton: false,
    //             timer: 2500
    //             })
    //         }
    //     }
    // });
</script>
@endsection