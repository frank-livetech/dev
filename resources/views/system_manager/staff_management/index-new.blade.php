@extends('layouts.master-layout-new')
@section('body')
<style>
    .full_name{
        width: 400px !important;
    }
    
    .ml-2{
        padding-left: 5px
    }
    .reqField{
        position: relative;
        /* right: 12px; */
        float: right;
        margin-top: -24px;
        margin-right: 8px;
        font-size: 6px;
    }
</style>
<input type="hidden" value="{{Session('system_date')}}" id="system_date">

<div class="app-content content">
    <div class="content-overlay"></div>
    <div class="header-navbar-shadow"></div>
    <div class="content-wrapper container-fluid p-0">
        <div class="content-header row">
            <div class="content-header-left  col-12 mb-2">
                <div class="row breadcrumbs-top">
                    <div class="col-md-10">
                        <h2 class="content-header-title float-start mb-0">Staff Manager</h2>
                        <div class="breadcrumb-wrapper">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{route('home')}}">Home</a>
                                </li>
                                <li class="breadcrumb-item">System Manager
                                </li>
                                <li class="breadcrumb-item active">Staff Manager
                                </li>
                            </ol>
                        </div>
                    </div>
                    <div class="col-md-2 text-end">
                        <button class="btn btn-success btn-sm rounded" id="btn-add-new-user">
                            <i class="mdi mdi-plus-circle"></i> Create
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="content-body">
        <div class="row">

            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <form class="widget-box widget-color-dark user-table-form"
                            action="?p=schedule-employee-form-get&json=1" method="post">
                            <div class="widget-header widget-header-small">
                                <span class="loader_lesson_plan_form"></span>
                            </div>
                            <div class="widget-body">
                                <div class="widget-main">
                                    <div class="row">
                                        <div class="col-12">
                                            
                                            {{-- <div class="row">
                                                <div class="col-md-12" style="text-align:right;">
                                                    <select class="multiple-select mt-2 mb-2" name="sm_select" id="sm_select" placeholder="Show/Hide" multiple="multiple" selected="selected">
                                                        <option value="0">Sr #</option>
                                                        <option value="1">Full Name</option>
                                                        <option value="2">Username</option>
                                                        <option value="3">Phone</option>
                                                        <option value="4">Tags Assigned</option>
                                                        <option value="5">Action</option>
                                                    </select>
                                                </div>
                                            </div> --}}
    
                                            <div class="table-responsive">
                                                <table id="user-table-list"
                                                    class="table table-striped table-bordered table-hover w-100" style="width:100%">
                                                    <thead>
                                                        <tr>
                                                            <th>#</th>
                                                            <th class="full_name">Full Name</th>
                                                            <th>Username</th>
                                                            <th>Phone</th>
                                                            <th>Tags Assigned</th>
                                                            <th>Created At</th>
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
                        </form>
                    </div>
                </div>
            </div>
    
        </div>
    </div>
    <div class="modal fade" id="addNewUser" tabindex="-1" aria-labelledby="addNewUserTitle" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="">User Form</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form class="widget-box widget-color-dark user-form mb-0" id="save_user" action="{{asset('/insert_user')}}" method="post" enctype="multipart/form-data">
                        <input type="hidden" name="staff_id" id="staff_id">
                        <div class="widget-body">
                            <div class="widget-main">
                                <div class="form-row row">
                                    <input name="emp_ids" style="display: none;" class="form-control" type="text" value="" readonly="readonly">

                                    <div class="col-12">
                                        <div class="form-group">
                                            <figure style="text-align: center;">
                                                <label for="profile_pic">
                                                    <img class="profile_pic_img" src="{{URL::asset('files/user_photos/user-photo.jpg')}}" height="100" width="100" style="border-radius:50%">
                                                </label>
                                            </figure>
                                            <input accept="image/*" onchange="UserImgValidation()" name="user_photo" type="file" style="display: none;" id="profile_pic" />

                                            <small class="text-muted">
                                                Max. file size: 2MB & of type: jpeg/jpg/png
                                            </small>
                                        </div>
                                    </div>

                                    <hr>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <span class="block input-icon input-icon-right">
                                                <input name="full_name" id="full_name" class="form-control" type="text" value="" placeholder="Full Name" required>
                                                <span class="fa fa-asterisk field-icon text-danger reqField"></span>
                                            </span>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <span class="block input-icon input-icon-right">
                                                <input name="email" id="email" class="form-control" type="text" value="" placeholder="Username" required>
                                                <span class="fa fa-asterisk field-icon text-danger reqField"></span>
                                            </span>
                                            <span class="small text-danger" id="email_error"></span>
                                            <small class="text-muted">
                                                Username e.g username@example.com.
                                            </small>
                                        </div>
                                    </div>

                                    <div class="col-12">
                                        <div class="form-group user-password-div w-100">
                                            <span class="block input-icon input-icon-right d-flex">
                                                <input name="password" id="staffpassword" class="form-control" type="text" value="" placeholder="Password" required>
                                                <button class="btn btn-primary ml-auto" type="button" onclick="generatePassword()">Generate</button>
                                            </span>
                                            <span class="small text-danger" id="password_error"></span>
                                            <small class="text-muted">
                                                Must be 8 characters long.
                                            </small>
                                        </div>
                                    </div>

                                    {{-- <div class="col-md-6">
                                        <div class="form-group user-confirm-password-div">
                                            <span class="block input-icon input-icon-right">
                                                <input name="confirm_password" class="form-control" type="password" value="" placeholder="Confirm Password" onkeyup="confirmPassword(this, 'save_user');">
                                                <i class="ace-icon fa fa-check check-match field-icon" style="margin-right: 48px; font-size: small; display: none;"></i>
                                                <span class="fa fa-asterisk field-icon text-danger reqField"></span>
                                            </span>
                                        </div>
                                    </div> --}}

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <span class="block input-icon input-icon-right">
                                                <input type="text" name="phone_number" id="phone" class="form-control" value="" placeholder="Phone" required>
                                                <span class="fa fa-asterisk field-icon text-danger reqField"></span>
                                            </span>
                                            <span class="text-danger small" id="phone_error"></span>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <select class="form-control" id="role_id" name="role_id"  style="height: 36px;width: 100%;">
                                                @foreach($roles as $role)
                                                    <option value="{{$role->id}}">{{$role->name}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-md-6 mt-1 mb-1">
                                        <div class="form-group">
                                            <select class="select2 form-control" id="tags" name="tags" multiple="multiple" style="height: 36px;width: 100%;">
                                                <option></option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group" style="display:none;">
                                            <select name="status" id="status" class="form-control">
                                                <option value='1' selected>Active</option>
                                                <option value='0'>Deactive</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-md-6 mb-1">
                                        <div class="form-group">
                                            <input name="sms" id="sms" class="form-control" placeholder="SMS" type="text">
                                            <span class="text-danger small" id="sms_error"></span>
                                        </div>
                                    </div>

                                    <div class="col-md-6 mb-1">
                                        <div class="form-group">
                                            <input type="text" name="whatsapp" id="whatsapp" class="form-control" placeholder="WhatsApp">
                                            <span class="text-danger small" id="wtsapp_error"></span>
                                        </div>
                                    </div>

                                    <div class="col-12 text-end">
                                    <button type="Submit" id="usr_save" class="btn waves-effect waves-light btn-sm rounded btn-success"><i class="fas fa-check-circle"></i> Save</button>
                                    <button type="button" style="display:none" disabled id="usr_pro" class="btn btn-sm rounded btn-success"> <i class="fas fa-circle-notch fa-spin"></i> Processing </button>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>

                    <div class="loader_container" id="usr_loader" style="display:none">
                        <div class="loader"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    {{-- Modal end --}}
</div>
@endsection
@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@9"></script>
<script src="https://unpkg.com/multiple-select@1.5.2/dist/multiple-select.min.js"></script>
{{-- <script src="https://cdn.datatables.net/1.10.23/js/jquery.dataTables.min.js"></script> --}}
<!-- <script src="{{asset('public/js/system_manager/staff_management/staff.js').'?ver='.rand()}}"></script> -->
@include('js_files.system_manager.staff_management.staffJs')
@include('js_files.system_manager.staff_management.indexJs')

@endsection