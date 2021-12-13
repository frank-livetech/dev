@extends('layouts.staff-master-layout')
@section('body-content')

<style>
    .table th {
    padding-top:16px !important;
    padding-bottom:16px !important;
    font-size:1rem;
}
.pro-pic{
    padding-top: 10px !important;
}
</style>
<link rel="stylesheet" href="https://unpkg.com/multiple-select@1.5.2/dist/multiple-select.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/1.10.23/css/jquery.dataTables.min.css">


<input type="hidden" value="{{Session('system_date')}}" id="system_date">

<div class="page-breadcrumb">
    <div class="row">
        <div class="col-md-5 align-self-center">
            <!--<h3 class="page-title">Dashboard</h3>-->
            <div class="d-flex align-items-center">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Staff Manager</li>
                    </ol>
                </nav>
            </div>
        </div>

        <div class="col-md-7 d-flex justify-content-end align-self-center d-none d-md-flex">
            <button class="btn btn-success btn-sm rounded" id="btn-add-new-user"><i class="mdi mdi-plus-circle"></i> Create</button>
        </div>

        <!-- Add New User Modal -->
        <div class="modal fade" id="addNewUser" tabindex="-1" role="dialog"  data-backdrop="static" aria-labelledby="addNewUserTitle" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header d-flex align-items-center">
                        <h5 class="modal-title">User Form</h5>
                        <button type="button" class="close ml-auto" onclick="closeModal()" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form class="widget-box widget-color-dark user-form mb-0" id="save_user" action="{{asset('/insert_user')}}" method="post" enctype="multipart/form-data">
                            <input type="hidden" name="staff_id" id="staff_id">
                            <div class="widget-body">
                                <div class="widget-main">
                                    <div class="form-row">
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
                                                    <span class="fa fa-asterisk field-icon text-danger"></span>
                                                </span>
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <span class="block input-icon input-icon-right">
                                                    <input name="email" id="email" class="form-control" type="text" value="" placeholder="Username" required>
                                                    <span class="fa fa-asterisk field-icon text-danger"></span>
                                                </span>
                                                <span class="small text-danger" id="email_error"></span> <br>
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
                                                <span class="small text-danger" id="password_error"></span> <br>
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
                                                    <span class="fa fa-asterisk field-icon text-danger"></span>
                                                </span>
                                            </div>
                                        </div> --}}

                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <span class="block input-icon input-icon-right">
                                                    <input type="text" name="phone_number" id="phone" class="form-control" value="" placeholder="Phone" required>
                                                    <span class="fa fa-asterisk field-icon text-danger"></span>
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

                                        <div class="col-md-6">
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

                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <input name="sms" id="sms" class="form-control" placeholder="SMS" type="text">
                                                <span class="text-danger small" id="sms_error"></span>
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <input type="text" name="whatsapp" id="whatsapp" class="form-control" placeholder="WhatsApp">
                                                <span class="text-danger small" id="wtsapp_error"></span>
                                            </div>
                                        </div>

                                        <div class="col-12 text-right">
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
    </div>
</div>

<div class="container-fluid">

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
                                        
                                        <div class="row">
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
                                        </div>

                                        <div class="table-responsive">
                                            <table id="user-table-list"
                                                class="table table-striped table-bordered table-hover w-100" style="width:100%">
                                                <thead class="bg-info text-white p-2">
                                                    <tr>
                                                        <th>#</th>
                                                        <th>Full Name</th>
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

<div class="modal fade" id="updateUserPwd" tabindex="-1" role="dialog"   data-backdrop="static" aria-labelledby="updateUserPwdTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header d-flex align-items-center">
                <h5 class="modal-title">Update Password</h5>
                <button type="button" class="close ml-auto" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form class="widget-box widget-color-dark password-form mb-0" id="update_password" action="{{asset('/update_password')}}" method="post" enctype="multipart/form-data">
                    <input type="hidden" name="staff_id" id="staff_id_pwd">
                    <div class="widget-body">
                        <div class="widget-main">

                            <div class="row">
                                <div class="col-12">
                                    <input name="emp_ids" style="display: none;" class="form-control" type="text"
                                        value="" readonly="readonly">
                                    <div class="form-horizontal">
                                        <div class="form-group">
                                            {{-- <label class="control-label col-sm-8">Password</label> --}}
                                            <div class="col-12 user-password-div">
                                                <span class="block input-icon input-icon-right">
                                                    <input name="password" class="form-control" type="password" value="" placeholder="New Password" required>
                                                    {{-- <span toggle="#password-field" class="fa fa-fw fa-eye field-icon show-password-btn mr-2"></span> --}}
                                                    <span class="fa fa-asterisk field-icon text-danger"></span>
                                                </span>
                                                <small class="text-muted">
                                                    Must be 8 characters long.
                                                </small>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            {{-- <label class="control-label col-sm-8">Confirm Password</label> --}}
                                            <div class="col-12 user-confirm-password-div">
                                                <span class="block input-icon input-icon-right">
                                                    <input name="confirm_password" class="form-control" type="password" value="" placeholder="Confirm Password" onkeyup="confirmPassword(this, 'update_password');" required>
                                                    <i class="ace-icon fa fa-check check-match field-icon" style="margin-right: 48px; font-size: small; display: none;"></i>
                                                    {{-- <span toggle="#password-field" class="fa fa-fw fa-eye field-icon show-confirm-password-btn mr-2"></span> --}}
                                                    <span class="fa fa-asterisk field-icon text-danger"></span>
                                                </span>
                                            </div>
                                        </div>

                                        <div class="form-group mb-0 align-self-bottom">
                                            <div class="col-sm-offset-4 col-12 text-right">
                                                <button type="Submit" class="btn waves-effect waves-light btn-success">
                                                    Save</button>
                                                <!-- <button class="btn btn-danger" data-dismiss="modal"> Discard</button> -->
                                            </div>
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

@endsection
@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@9"></script>
<script src="https://unpkg.com/multiple-select@1.5.2/dist/multiple-select.min.js"></script>
<script src="https://cdn.datatables.net/1.10.23/js/jquery.dataTables.min.js"></script>
<!-- <script src="{{asset('public/js/system_manager/staff_management/staff.js').'?ver='.rand()}}"></script> -->
@include('js_files.system_manager.staff_management.staffJs')
@include('js_files.system_manager.staff_management.indexJs')

@endsection
<style>
    table th{
        font-size: 11px;
    }

    table td{
        vertical-align: middle !important;
    }
    
    .selectpickertag .input-group>.btn-group>.btn {
        line-height:17px;
        overflow:hidden;
    }

    .select2-search__field {
        width: auto !important;
    }

    .fa-asterisk {
        font-size: 6px;
        margin-right: 5px;
    }
    .dataTables_wrapper .dataTables_filter input {
    margin-bottom: 2rem;
}
</style>