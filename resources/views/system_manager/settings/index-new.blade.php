@extends('layouts.master-layout-new')
@section('System Manager','open')
@section('Settings','active')
@section('body')
<style>
    .nav-pills .nav-link.menu_active {
        border-color: #7367f0;
        box-shadow: 0 4px 18px -4px rgb(115 103 240 / 65%);
        background
    }
    .nav-pills .nav-link.menu_active, .nav-pills .show > .nav-link {
        color: #fff;
        background-color: #7367f0;
    }
    .dd-handle{
        cursor: pointer;
    }
    .nav-pills .nav-link, .nav-tabs .nav-link {
        display: flex;
        align-items: center;
        justify-content: left !important;
    }
    table.dataTable th {
        padding: 12px 10px !important;
        vertical-align: middle !important;
    }
    table {
        border:1px solid #ebe9f1 !important;
    }
</style>
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
                        <h2 class="content-header-title float-start mb-0">Settings</h2>
                        <div class="breadcrumb-wrapper">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{route('home')}}">Home</a></li>
                                <li class="breadcrumb-item" aria-current="page">System Manager</li>
                                <li class="breadcrumb-item active" aria-current="page">Settings</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="content-body">
            <div class="row">
                <div class="col-md-2">
                    <div class="row">
                        <div class="col-md-12">

                        
                    <div class="card">
                        <div class="card-body p-1">
                        
                            <div class="dd myadmin-dd" id="nestable-menu">
                                <ol class="dd-list nav nav-pills flex-column ">
                                    <li class="dd-item nav-item" data-id="1">
                                        <div class="dd-handle nav-link main menu_active" data-cls=".main" data-trg="#settings_stats">Main</div>
                                    </li>
                                    <li class="dd-item nav-item" data-id="2">
                                        <div class="dd-handle nav-link tickets" data-cls=".tickets" data-trg="#tickets_settings">Tickets</div>
                                    </li>
                                    <li class="dd-item nav-item" data-id="3">
                                        <div class="dd-handle nav-link billing" data-cls=".billing" data-trg="#billing_settings">Billing</div>
                                    </li>
                                    <li class="dd-item nav-item" data-id="4">
                                        <div class="dd-handle nav-link customer" data-cls=".customer" data-trg="#customer_settings">Customer</div>
                                    </li>
                                    <li class="dd-item nav-item" data-id="5">
                                        <div class="dd-handle nav-link marketing" data-cls=".marketing" data-trg="#marketing_settings">Marketing</div>
                                    </li>
                                    <li class="dd-item nav-item" data-id="6">
                                        <div class="dd-handle nav-link security" data-cls=".security" data-trg="#security_settings">Security</div>
                                    </li>
                                    <li class="dd-item nav-item" data-id="7">
                                        <div class="dd-handle nav-link system" data-cls=".system" data-trg="#system_settings">System</div>
                                    </li>
                                    <li class="dd-item nav-item" data-id="8">
                                        <div class="dd-handle nav-link branding" data-cls=".branding" data-trg="#branding_settings">Branding</div>
                                    </li>
                                    <li class="dd-item nav-item" data-id="9">
                                        <div class="dd-handle nav-link menu_management" data-cls=".menu_management" data-trg="#menu_settings">Menu Management</div>
                                    </li>
                                    <li class="dd-item nav-item" data-id="10">
                                        <div class="dd-handle nav-link dispatch" data-cls=".dispatch" data-trg="#dispatch_settings">Dispatch</div>
                                    </li>
                                    <li class="dd-item nav-item" data-id="11">
                                        <div class="dd-handle nav-link project" data-cls=".project" data-trg="#project_settings">Project Management</div>
                                    </li>
                                    <li class="dd-item nav-item" data-id="12">
                                        <div class="dd-handle nav-link payroll" data-cls=".payroll" data-trg="#payroll_settings">Payroll</div>
                                    </li>
                                </ol>
                            </div>
                        </div>
                    </div>

                    </div>
                    </div>
                </div>

                <div class="col-md-10 gears" id="settings_stats">
                    <div class="card">
                        <div class="card-body">
                        </div>
                    </div>
                </div>

                <div class="col-md-10 gears" id="tickets_settings" style="display:none">
                   @include('system_manager.settings.tabs.tickets')
                </div>

                <div class="col-md-10 gears" id="billing_settings" style="display:none">
                    @include('system_manager.settings.tabs.billing')
                </div>
            
                <div class="col-md-10 gears" id="customer_settings" style="display:none">
                    @include('system_manager.settings.tabs.customer')
                </div>

                <div class="col-md-10 gears" id="marketing_settings" style="display:none">
                    @include('system_manager.settings.tabs.marketing')
                    
                </div>

                <div class="col-md-10 gears" id="security_settings" style="display:none">
                    @include('system_manager.settings.tabs.security')
                    
                </div>

                <div class="col-md-10 gears" id="system_settings" style="display:none">
                    @include('system_manager.settings.tabs.system')
                   
                </div>

                <div class="col-md-10 gears" id="branding_settings" style="display:none">
                     @include('system_manager.settings.tabs.branding')
                    
                </div>

                <div class="col-md-10 gears" id="menu_settings" style="display:none">
                    @include('system_manager.settings.tabs.menu_management')
                    
                </div>

                <div class="col-md-10 gears" id="dispatch_settings" style="display:none">
                    @include('system_manager.settings.tabs.dispatch')
                    
                </div> 

                <div class="col-md-10 gears" id="project_settings" style="display:none">
                    @include('system_manager.settings.tabs.prjct_mgt')
                   
                </div>

                <div class="col-md-10 gears" id="payroll_settings" style="display:none">
                    @include('system_manager.settings.tabs.payroll')
                    
                </div>
            </div>
        </div>
    </div>
   
<!--Common Modals-->

  
    <!-- Ticket POP3 Mail Modal -->
    <div id="save-mail" class="modal fade" role="dialog" data-backdrop="static" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"  id="modalheader">Add New Mail</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="mail-form">
                        <input type="hidden" id="editId">
                        <div class="row">
                            <div class="col-md-6 form-group">
                                <label for="departmrnt">Email Subject</label><span style="color:red">*</span><span id="email_emp" style="color:red;display:none">Email cannot be Empty</span>
                                <input class="form-control" type="text" name="mail_queue_address" id="mail_queue_address" placeholder="">
                            </div>
                            <div class="col-md-3 form-group">
                                <label for="departmrnt">Queue Type</label><span style="color:red">*</span><span id="queue_emp" style="color:red;display:none">Queue Type cannot be Empty</span>

                                <select class="select2 form-control custom-select" type="search" id="queue_type" name="queue_type" style="width: 100%; height:36px;">
                                    <option value="365">365</option>
                                    <option value="pop3" selected>POP3</option>
                                    <option value="php_mailer">PHP Mailer</option>
                                </select>
                            </div>
                            <div class="col-md-3 form-group">
                                <label for="departmrnt">Protocol</label><span style="color:red">*</span><span id="protocol_emp" style="color:red;display:none">Protocol cannot be Empty</span>

                                <select class="select2 form-control custom-select" type="search" id="protocol"
                                    name="protocol" style="width: 100%; height:36px;">
                                    <option value="ssl">SSL </option>
                                    <option value="tls">TLS </option>
                                    <option value="starttls">STARTTLS </option>
                                    <option value="">NONE </option>
                                </select>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 form-group">
                                <label for="departmrnt">Queue Template group</label><span style="color:red">*</span><span id="queue_group" style="color:red;display:none">Queue Template group cannot be Empty</span>

                                <select class="select2 form-control custom-select" type="search" id="queue_template" name="queue_template" style="width: 100%; height:36px;">
                                    <option value="pop3tls">Live Tech </option>
                                </select>
                            </div>
                            <div class="col-md-6 form-group" style="margin-top: 15px;">
                                <div class="custom-control custom-switch">
                                    <input type="checkbox" class="form-check-input " id="php_mailer">
                                    <label class="custom-control-label" for="php_mailer">User PHP Mailer </label>
                                </div>

                                <div class="custom-control custom-switch">
                                    <input type="checkbox" class="form-check-input" id="is_enabled">
                                    <label class="custom-control-label" for="is_enabled">Email Queue is <span id="is_enabled_text" class="text-danger">disabled</span>  </label>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 form-group">
                                <label for="departmrnt">Mailserver hostname</label><span style="color:red">*</span><span id="server_emp" style="color:red;display:none">Mailserver hostname cannot be Empty</span>
                                <input class="form-control" type="text" name="mailserver_hostname" id="mailserver_hostname" placeholder="">
                            </div>
                            <div class="col-md-6 form-group">
                                <label for="departmrnt">Port</label><span style="color:red">*</span><span id="port_emp" style="color:red;display:none">Port cannot be Empty</span>
                                <input class="form-control" type="text" name="mailserver_port" id="mailserver_port" value="995">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 form-group">
                                <label for="departmrnt">Username</label><span style="color:red">*</span><span id="user_emp" style="color:red;display:none">Username group cannot be Empty</span>
                                <input class="form-control" type="text" name="mailserver_username" id="mailserver_username" placeholder="">
                            </div>
                            <div class="col-md-6 form-group">
                                <label for="departmrnt">Password</label><span style="color:red">*</span><span id="password_emp" style="color:red;display:none">Password cannot be Empty</span>
                                <input class="form-control" type="text" name="mailserver_password" id="mailserver_password" placeholder="">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 form-group">
                                <label for="departmrnt">From Name</label>
                                <input class="form-control" type="text" name="from_name" id="from_name" placeholder="">
                            </div>
                            <div class="col-md-6 form-group">
                                <label for="departmrnt">From email address</label>
                                <input class="form-control" type="text" name="from_mail" id="from_mail" placeholder="">
                            </div>
                        </div>
                        <div class="row" id="tickets_area">
                            <div class="col-md-3 form-group">
                                <label for="departmrnt">Department</label><span style="color:red">*</span><span id="dept_emp" style="color:red;display:none">Department cannot be Empty</span>

                                <select class="select2 form-control custom-select" type="search" id="mail_dept_id" name="mail_dept_id" style="width: 100%; height:36px;">
                                </select>
                            </div>
                            <div class="col-md-3 form-group">
                                <label for="departmrnt">Type</label><span style="color:red">*</span><span id="type_emp" style="color:red;display:none">Type cannot be Empty</span>

                                <select class="select2 form-control custom-select" type="search" id="mail_type_id" name="mail_type_id" style="width: 100%; height:36px;">
                                </select>
                            </div>
                            <div class="col-md-3 form-group">
                                <label for="departmrnt">Status</label><span style="color:red">*</span><span id="status_emp" style="color:red;display:none">Status cannot be Empty</span>

                                <select class="select2 form-control custom-select" type="search" id="mail_status_id" name="mail_status_id" style="width: 100%; height:36px;">
                                </select>
                            </div>
                            <div class="col-md-3 form-group">
                                <label for="departmrnt">Priority</label><span style="color:red">*</span><span id="priority_emp" style="color:red;display:none">Priority cannot be Empty</span>

                                <select class="select2 form-control custom-select" type="search" id="mail_priority_id" name="mail_priority_id" style="width: 100%; height:36px;">
                                </select>
                            </div>
                        </div>
                        <div class="row" style="margin-top: 35px;">
                            <div class="col-md-6 form-group">
                                <div class="custom-control custom-switch">
                                    <input type="checkbox" class="form-check-input" id="registration_required">
                                    <label class="custom-control-label" for="registration_required">Registration Required</label>
                                </div>
                            </div>
                            <div class="col-md-6 form-group">
                                <div class="custom-control custom-switch">
                                    <input type="checkbox" class="form-check-input" id="email_outbound">
                                    <label class="custom-control-label" for="email_outbound">Email Outbound</label>
                                </div>
                            </div>
                            <div class="col-md-6 form-group mt-1">
                                <div class="custom-control custom-switch">
                                    <input type="checkbox" class="form-check-input" id="autosend_ticket">
                                    <label class="custom-control-label" for="autosend_ticket">Do not send new ticket autoresponder</label>
                                </div>
                            </div>
                            <div class="col-md-6 form-group mt-1">
                                <div class="custom-control custom-switch">
                                    <input type="checkbox" class="form-check-input" id="is_dept_default">
                                    <label class="custom-control-label" for="is_dept_default">Set as default</label>
                                </div>
                            </div>
                        </div>
                    </form>
                    <div class="form-group text-end mt-2">
                        <button class="btn btn-rounded btn-primary" onclick="verify_connection(this, 'add')" style="float:left !important;">Verify Connection</button>
                        <button class="btn btn-rounded btn-success" onclick="save_pop3_mail()">Save</button>
                        <button class="btn btn-rounded btn-primary" onclick="verify_save_pop3_mail()">Verify & Save</button>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <!-- edit Ticket POP3 Mail Modal -->
    <div id="edit_email_modal" class="modal fade" role="dialog" data-backdrop="static" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">                    
                    <h5 class="modal-title" id="modalheader">Edit Mail</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="mail-form">
                        <input type="hidden" id="email_id">
                        <div class="row">
                            <div class="col-md-6 form-group">
                                <label for="departmrnt">Email Subject</label><span style="color:red">*</span><span id="mail_emp" style="color:red;display:none">Email cannot be Empty</span>
                                <input class="form-control" type="text" name="mail_queue_address" id="edit_email_emp" placeholder="">
                            </div>
                            <div class="col-md-3 form-group">
                                <label for="departmrnt">Queue Type</label><span style="color:red">*</span><span id="edit_queue_emp" style="color:red;display:none">Queue Type cannot be Empty</span>

                                <select class="select2 form-control custom-select" type="search" id="edit_queue_type" name="queue_type" style="width: 100%; height:36px;">
                                    <option value="365">365</option>
                                    <option value="pop3">POP3</option>
                                    <option value="imap">Imap</option>
                                </select>
                            </div>
                            <div class="col-md-3 form-group">
                                <label for="departmrnt">Protocol</label><span style="color:red">*</span><span id="protocol_emp" style="color:red;display:none">Protocol cannot be Empty</span>

                                <select class="select2 form-control custom-select" type="search" id="edit_protocol"
                                    name="protocol" style="width: 100%; height:36px;">
                                    <option value="ssl">SSL</option>
                                    <option value="tls">TLS</option>
                                    <option value="starttls">STARTTLS</option>
                                    <option value="">NONE</option>
                                </select>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 form-group">
                                <label for="departmrnt">Queue Template group</label><span style="color:red">*</span><span id="queue_group" style="color:red;display:none">Queue Template group cannot be Empty</span>

                                <select class="select2 form-control custom-select" type="search" id="edit_queue_template" name="queue_template" style="width: 100%; height:36px;">
                                    <option value="pop3tls">Live Tech</option>
                                </select>
                            </div>
                            <div class="col-md-6 form-group" style="margin-top:15px;">
                                <div class="custom-control custom-switch">
                                    <input type="checkbox" class="form-check-input" id="edit_php_mailer">
                                    <label class="custom-control-label" for="edit_php_mailer">User PHP Mailer </label>
                                </div>

                                <div class="custom-control custom-switch">
                                    <input type="checkbox" class="form-check-input" id="edit_is_enabled">
                                    <label class="custom-control-label" for="edit_is_enabled">Email Queue is <span id="edit_is_enabled_text" class="text-danger">disabled</span> </label>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 form-group">
                                <label for="departmrnt">Mailserver hostname</label><span style="color:red">*</span><span id="edit_server_emp" style="color:red;display:none">Mailserver hostname cannot be Empty</span>
                                <input class="form-control" type="text" name="mailserver_hostname" id="edit_mailserver_hostname" placeholder="">
                            </div>
                            <div class="col-md-6 form-group">
                                <label for="departmrnt">Port</label><span style="color:red">*</span><span id="port_emp" style="color:red;display:none">Port cannot be Empty</span>
                                <input class="form-control" type="text" name="mailserver_port" id="edit_mailserver_port" value="995">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 form-group">
                                <label for="departmrnt">Username</label><span style="color:red">*</span><span id="edit_user_emp" style="color:red;display:none">Username group cannot be Empty</span>
                                <input class="form-control" type="text" name="mailserver_username" id="edit_mailserver_username" placeholder="">
                            </div>
                            <div class="col-md-6 form-group">
                                <label for="departmrnt">Password</label><span style="color:red">*</span><span id="edit_password_emp" style="color:red;display:none">Password cannot be Empty</span>
                                <input class="form-control" type="text" name="mailserver_password" id="edit_mailserver_password" placeholder="">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 form-group">
                                <label for="departmrnt">From Name</label>
                                <input class="form-control" type="text" name="from_name" id="edit_from_name" placeholder="">
                            </div>
                            <div class="col-md-6 form-group">
                                <label for="departmrnt">From email address</label>
                                <input class="form-control" type="text" name="from_mail" id="edit_from_mail" placeholder="">
                            </div>
                        </div>
                        <div class="row" id="tickets_area">
                            <div class="col-md-3 form-group">
                                <label for="departmrnt">Department</label><span style="color:red">*</span><span id="dept_emp" style="color:red;display:none">Department cannot be Empty</span>

                                <select class="select2 form-control custom-select" type="search" id="edit_mail_dept_id" name="mail_dept_id" style="width: 100%; height:36px;">
                                </select>
                            </div>
                            <div class="col-md-3 form-group">
                                <label for="departmrnt">Type</label><span style="color:red">*</span><span id="type_emp" style="color:red;display:none">Type cannot be Empty</span>

                                <select class="select2 form-control custom-select" type="search" id="edit_mail_type_id" name="mail_type_id" style="width: 100%; height:36px;">
                                </select>
                            </div>
                            <div class="col-md-3 form-group">
                                <label for="departmrnt">Status</label><span style="color:red">*</span><span id="status_emp" style="color:red;display:none">Status cannot be Empty</span>

                                <select class="select2 form-control custom-select" type="search" id="edit_mail_status_id" name="mail_status_id" style="width: 100%; height:36px;">
                                </select>
                            </div>
                            <div class="col-md-3 form-group">
                                <label for="departmrnt">Priority</label><span style="color:red">*</span><span id="priority_emp" style="color:red;display:none">Priority cannot be Empty</span>

                                <select class="select2 form-control custom-select" type="search" id="edit_mail_priority_id" name="mail_priority_id" style="width: 100%; height:36px;">
                                </select>
                            </div>
                        </div>
                        <div class="row" style="margin-top: 35px;">
                            <div class="col-md-6 form-group">
                                <div class="custom-control custom-switch">
                                    <input type="checkbox" class="form-check-input" id="edit_reg">
                                    <label class="custom-control-label" for="edit_reg">Registration Required</label>
                                </div>
                            </div>
                            <div class="col-md-6 form-group">
                                <div class="custom-control custom-switch">
                                    <input type="checkbox" class="form-check-input" id="edit_outbound_ticket">
                                    <label class="custom-control-label" for="edit_outbound_ticket">Email Outbound</label>
                                </div>
                            </div>
                            <div class="col-md-6 form-group">
                                <div class="custom-control custom-switch">
                                    <input type="checkbox" class="form-check-input" id="edit_autosend_ticket">
                                    <label class="custom-control-label" for="edit_autosend_ticket">Send new ticket autoresponder</label>
                                </div>
                            </div>
                            <div class="col-md-6 form-group">
                                <div class="custom-control custom-switch">
                                    <input type="checkbox" class="form-check-input" id="edit_is_dept_default">
                                    <label class="custom-control-label" for="edit_is_dept_default">Set as default</label>
                                </div>
                            </div>
                        </div>
                    </form>
                    <div class="form-group text-end mt-2">
                        <button class="btn btn-rounded btn-primary" onclick="verify_connection(this, 'edit')" style="float:left !important;">Verify Connection</button>
                        <button class="btn btn-rounded btn-success" onclick="updateEmailQueue()">Save</button>
                        <button class="btn btn-rounded btn-primary">Verify & Save</button>
                    </div>

                    <div class="loader_container">
                        <div class="loader"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>







<!--Common Modals-->



</div>
@endsection
@section('scripts')
    <script src="https://unpkg.com/multiple-select@1.5.2/dist/multiple-select.min.js"></script>
    <script src="{{asset('assets/libs/nestable/jquery.nestable.js')}}"></script>
    <script src="{{asset( $path . 'js/jquery.nestable.js')}}"></script>
    <script src="{{asset( $path . 'js/jquery_asColor.min.js')}}"></script>
    <script src="{{asset( $path . 'js/jquery_asGradient.js')}}"></script>
    <script src="{{asset( $path . 'js/jquery_asColorPicker.min.js')}}"></script>
    <script src="{{asset( $path . 'js/jquery_minicolors.min.js')}}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-switch/1.3/bootstrapSwitch.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/tinymce/5.0.0/tinymce.min.js"></script>

    @include('js_files.system_manager.settings.indexJs')
@include('js_files.system_manager.settings.settingNewJs')
    @include('js_files.system_manager.feature_list.feature_listJs')
  
    
        <!-- This Page JS -->
    <!-- <script src="{{asset('assets/libs/jquery-asColor/dist/jquery-asColor.min.js')}}"></script> -->
    <!-- <script src="{{asset('assets/libs/jquery-asGradient/dist/jquery-asGradient.js')}}"></script> -->
    <!-- <script src="{{asset('assets/libs/jquery-asColorPicker/dist/jquery-asColorPicker.min.js')}}"></script> -->
    <!-- <script src="{{asset('assets/libs/@claviska/jquery-minicolors/jquery.minicolors.min.js')}}"></script> -->
    <!-- <script src="{{asset('/assets/libs/tinymce/tinymce.min.js')}}"></script> -->


    <script>
        let datetime = {!! json_encode($datetime) !!};

        tinymce.init({
            selector: "textarea#mymce",
            // theme: "modern",
            height: 300,
            file_picker_types: 'image',
            paste_data_images: true,
            plugins: [
                "advlist autolink link image lists charmap print preview hr anchor pagebreak spellchecker",
                "searchreplace wordcount visualblocks visualchars code fullscreen insertdatetime media nonbreaking",
                "save table contextmenu directionality emoticons template paste textcolor"
            ],
            toolbar: "insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image media | table | print preview fullpage | forecolor backcolor emoticons",

            file_picker_callback: function(cb, value, meta) {
                var input = document.createElement('input');
                input.setAttribute('type', 'file');
                input.setAttribute('accept', 'image/*');
                input.onchange = function() {
                    var file = this.files[0];

                    var reader = new FileReader();
                    reader.onload = async function() {
                        var id = 'blobid' + (new Date()).getTime();
                        var blobCache = tinymce.activeEditor.editorUpload.blobCache;
                        var base64 = reader.result.split(',')[1];

                        if(reader.result.includes('/svg') || reader.result.includes('/SVG')) {
                            base64 = await downloadPNGFromAnyImageSrc(reader.result);
                        }
                        
                        var blobInfo = blobCache.create(id, file, base64);
                        blobCache.add(blobInfo);
                        cb(blobInfo.blobUri(), { title: file.name });
                    };
                    reader.readAsDataURL(file);
                };
                input.click();
            },
        });
        
    </script>
@endsection