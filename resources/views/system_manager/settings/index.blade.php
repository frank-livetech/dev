@extends('layouts.staff-master-layout')
@push('css')


 <link rel="stylesheet" type="text/css" href="{{asset('assets/libs/@claviska/jquery-minicolors/jquery.minicolors.css')}}">
<style>
    .color {
        padding: 10px 20px;
        border-radius: 50%;
    }
    .menu_active {
        background-color:#009efb !important;
        color:#fff !important;
    }
    body[data-theme=dark] .nav-pills .nav-link.active {
        background-color: #313030  !important;
        color: #fff !important;
    }
    .custom-control-label::before {
        top: 4px !important;
    }
    .box{
        background-color: #e263b0;
        height: 30px;
        width: 50px;
        margin-left: 0;
        margin-top: 7px;
    }
    .border-box{
        border:1px solid #cdd0d0;
        height:130px;
        border-radius:5px;
        overflow-y:auto;
    }
    .border-box p{
        margin-bottom:2px;
    }
    .custom-control-input:checked~.custom-control-label::before {
        color: #fff !important;
        border-color: #4caf50 !important;
        background-color: #4caf50 !important;
        box-shadow: none;
    }
    .custom-switch .custom-control-label::before {
        background: #f44336 !important;
    }
    .custom-switch .custom-control-label::after {
        background-color: #fff !important;
    }
    .rightForm{
        padding: 25px;
        margin-top: 23px;
        background: #e4f5ff;
    }
    .dd-handle-right{
        color: #009efb !important;
        /* border-color: #009efb !important; */
        cursor:pointer;
    }
    #menu_settings .table-hover tbody tr:hover {
    
        background-color: transparent;
    }
    #menu_settings .table td, .table th {
        padding: .75rem;
        vertical-align: top;
        border-top: 1px solid #009efb;
    }
    .select2-container--classic .select2-selection--multiple .select2-selection__choice, .select2-container--default .select2-selection--multiple .select2-selection__choice, .select2-container--default .select2-selection--multiple .select2-selection__choice__remove {
        background-color: #009efb;
        border-color: #009efb;
        color: #fff;
    }
    .select2-container--default .select2-selection--multiple .select2-selection__choice {
        
        margin-bottom: 5px;
    }
    .srh-btn{
        position: absolute;
        top: 11px;
        right: 22px;
    }
</style>
@endpush
@section('body-content')

@php
    $file_path = Session::get('is_live') == 1 ? 'public/' : '/';
    $path = Session::get('is_live') == 1 ? 'public/system_files/' : 'system_files/';
@endphp

<div class="page-breadcrumb">
    <div class="row">
        <div class="col-md-5 align-self-center">
            <div class="d-flex align-items-center">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{route('home')}}">Home</a></li>
                        <li class="breadcrumb-item" aria-current="page">System Manager</li>
                        <li class="breadcrumb-item active" aria-current="page">Settings</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
</div>

<div class="container-fluid">
    <!-- ============================================================== -->
    <!-- Left Part -->
    <!-- ============================================================== -->
    <div class="row">
        <div class="col-lg-4 col-md-12">
            <div class="card">
                <div class="card-body">

                    <div class="dd myadmin-dd" id="nestable-menu">
                        <ol class="dd-list">
                            <li class="dd-item" data-id="1">
                                <div class="dd-handle main" data-cls=".main" data-trg="#settings_stats">Main</div>
                            </li>
                            <li class="dd-item" data-id="2">
                                <div class="dd-handle tickets" data-cls=".tickets" data-trg="#tickets_settings">Tickets</div>
                            </li>
                            <li class="dd-item" data-id="3">
                                <div class="dd-handle billing" data-cls=".billing" data-trg="#billing_settings">Billing</div>
                            </li>
                            <li class="dd-item" data-id="4">
                                <div class="dd-handle customer" data-cls=".customer" data-trg="#customer_settings">Customer</div>
                            </li>
                            <li class="dd-item" data-id="5">
                                <div class="dd-handle marketing" data-cls=".marketing" data-trg="#marketing_settings">Marketing</div>
                            </li>
                            <li class="dd-item" data-id="6">
                                <div class="dd-handle security" data-cls=".security" data-trg="#security_settings">Security</div>
                            </li>
                            <li class="dd-item" data-id="7">
                                <div class="dd-handle system" data-cls=".system" data-trg="#system_settings">System</div>
                            </li>
                            <li class="dd-item" data-id="8">
                                <div class="dd-handle branding" data-cls=".branding" data-trg="#branding_settings">Branding</div>
                            </li>
                            <li class="dd-item" data-id="9">
                                <div class="dd-handle menu_management" data-cls=".menu_management" data-trg="#menu_settings">Menu Management</div>
                            </li>
                            <li class="dd-item" data-id="10">
                                <div class="dd-handle dispatch" data-cls=".dispatch" data-trg="#dispatch_settings">Dispatch</div>
                            </li>
                            <li class="dd-item" data-id="11">
                                <div class="dd-handle project" data-cls=".project" data-trg="#project_settings">Project Management</div>
                            </li>
                            <li class="dd-item" data-id="12">
                                <div class="dd-handle payroll" data-cls=".payroll" data-trg="#payroll_settings">Payroll</div>
                            </li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-8 gears" id="settings_stats">
            <div class="card">
                <div class="card-body">
                </div>
            </div>
        </div>

        <div class="col-md-8 gears" id="tickets_settings" style="display:none">
            <div class="card">
                <div class="card-body">

                    <h4 class="card-title mb-3">Ticket Settings</h4>

                    <ul class="nav nav-pills bg-nav-pills nav-justified mb-5 mt-5">
                        <li class="nav-item">
                            <a href="#tickets_general" data-toggle="tab" aria-expanded="false" class="nav-link active">
                                <i class="mdi mdi-home-variant d-lg-none d-block mr-1"></i>
                                <span class="d-none d-lg-block">General Settings</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="#SLA_part" data-toggle="tab" aria-expanded="false" class="nav-link ">
                                <i class="mdi mdi-home-variant d-lg-none d-block mr-1"></i>
                                <span class="d-none d-lg-block">SLA</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="#tickets_departments" data-toggle="tab" aria-expanded="true" class="nav-link">
                                <i class="mdi mdi-home-variant d-lg-none d-block mr-1"></i>
                                <span class="d-none d-lg-block">Customizations</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="#tickets_mails" data-toggle="tab" aria-expanded="false" class="nav-link">
                                <i class="mdi mdi-email d-lg-none d-block mr-1"></i>
                                <span class="d-none d-lg-block ">Email Queues</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="#response_temp" data-toggle="tab" aria-expanded="false" class="nav-link">
                                <i class="mdi mdi-email d-lg-none d-block mr-1"></i>
                                <span class="d-none d-lg-block ">Response Templates</span>
                            </a>
                        </li>
                    </ul>

                    <div class="tab-content">
                        
                        <div class="tab-pane active" id="tickets_general">
                            <form class="widget-box widget-color-dark " id="save_ticket_format"
                                action="{{asset('/ticket-format')}}" method="post">
                                <div class="row">
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="security">Ticket ID Format :</label>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <select class="select2 form-control select2-hidden-accessible"
                                                id="ticket_format" style="width:100%;height:30px;" required>
                                                <option value="random">Random (#JRQ-369-3621,#BHJ-591-1832)</option>
                                                <option value="sequential">Sequential (1#,2#,3#,...,#99999)</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>

                            </form>
                        </div>

                        <div class="tab-pane " id="SLA_part">
                            <div id="accordion" class="custom-accordion mb-4">
                                <div class="card mb-0">
                                    <div class="card-header" id="SLA_body_collapse">
                                        <h5 class="m-0">
                                            <a class="custom-accordion-title d-flex align-items-center pt-2 pb-2 collapsed"
                                                data-toggle="collapse" href="#collapseSLAPlan" aria-expanded="false"
                                                aria-controls="collapseThree">
                                                SLA Plans <span class="ml-auto"><i
                                                        class="mdi mdi-chevron-down accordion-arrow"></i></span>
                                            </a>
                                        </h5>
                                    </div>
                                    <div id="collapseSLAPlan" class="collapse" aria-labelledby="SLA_body_collapse"
                                        data-parent="#accordion" style="">
                                        <div class="card-body">
                                            <div class="widget-header widget-header-small">
                                                <div class="row">
                                                    <div class="col-md-8 col-sm-6">
                                                        <h4 class="widget-title lighter smaller menu_title">SLA Plans Table
                                                        </h4>
                                                    </div>
                                                    <div class="col-md-4 col-sm-6">
                                                        <button class="btn waves-effect waves-light btn-success rounded btn-sm"
                                                        data-toggle="modal" data-target="#save-SLA-plan" style="float:right">
                                                            <i class="mdi mdi-plus-circle" style="padding-right:3px;"></i>&nbsp;Add SLA Plan</button>
                                                    </div>
                                                </div>
                                                <br>
                                                <span class="loader_lesson_plan_form"></span>
                                            </div>
                                            <div class="widget-body">
                                                <div class="widget-main">
                                                    <div class="row">
                                                        <div class="col-sm-12">
                                                            <table id="sla_table"
                                                                class="display table-striped table-bordered project-type-list"
                                                                style="width:100%">
                                                                <thead>
                                                                    <tr>
                                                                        <th>#</th>
                                                                        <th>Name</th>
                                                                        <th>Status</th>
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
                                    <div class="card-header" id="SLA_body_collapse">
                                        <h5 class="m-0">
                                            <a class="custom-accordion-title d-flex align-items-center pt-2 pb-2 "
                                                data-toggle="collapse" href="#collapseSLASetting" aria-expanded="true"
                                                aria-controls="collapseThree">
                                                SLA Settings <span class="ml-auto"><i
                                                        class="mdi mdi-chevron-down accordion-arrow"></i></span>
                                            </a>
                                        </h5>
                                    </div>
                                    <div id="collapseSLASetting" class="collapse" aria-labelledby="SLA_body_collapse"
                                        data-parent="#accordion" style="">
                                        <div class="card-body">
                                            <div class="widget-header widget-header-small">
                                                <div class="row">
                                                    <div class="col-md-12 col-sm-12">
                                                        <h4 class="widget-title lighter smaller menu_title">SLA Settings
                                                        </h4>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="widget-body">
                                                <div class="widget-main">
                                                    <div class="setting_body p-3">
                                                        <form id="sla_setting_form" method="POST" action="{{url('sla_setting')}}" enctype="multipart/form-data">
                                                            <div class="row ">
                                                                <div class="col-md-8">
                                                                <p><b>Clear reply due deadline on staff reply</b></p>
                                                                <small>When a staff user reply to a ticket, the ticket reply 
                                                                due deadline will be cleared. A new reply due deadline will
                                                                    be calculated when a user next replies to a ticket. </small>
                                                                    <hr>
                                                                </div>
                                                                <div class="col-md-4 ">
                                                                    <div class="row text-right">
                                                                        <div class="form-check ml-3">
                                                                            @if(sizeOf($sla_setting) > 0)
                                                                                @if($sla_setting['reply_due_deadline'] == 1)
                                                                                    <input class="form-check-input" type="radio" name="flexRadioDefault2" id="flexRadioDefault12" checked>
                                                                                    <label class="form-check-label" for="flexRadioDefault12"> Yes </label>
                                                                                @else
                                                                                    <input class="form-check-input" type="radio" name="flexRadioDefault2" id="flexRadioDefault12">
                                                                                    <label class="form-check-label" for="flexRadioDefault12"> Yes </label>
                                                                                @endif
                                                                            @else
                                                                                <input class="form-check-input" type="radio" name="flexRadioDefault2" id="flexRadioDefault12">
                                                                                <label class="form-check-label" for="flexRadioDefault12"> Yes </label>
                                                                            @endif
                                                                            
                                                                        </div>
                                                                        <div class="form-check ml-3">
                                                                        @if(sizeOf($sla_setting) > 0)
                                                                            @if($sla_setting['reply_due_deadline'] == 0)
                                                                                <input class="form-check-input" type="radio" name="flexRadioDefault2" id="flexRadioDefault22" value="0" checked>
                                                                                <label class="form-check-label" for="flexRadioDefault22"> No </label>
                                                                            @else
                                                                                <input class="form-check-input" type="radio" name="flexRadioDefault2" id="flexRadioDefault22" value="0">
                                                                                <label class="form-check-label" for="flexRadioDefault22"> No </label>
                                                                            @endif
                                                                        @else
                                                                            <input class="form-check-input" type="radio" name="flexRadioDefault2" id="flexRadioDefault22" value="0">
                                                                            <label class="form-check-label" for="flexRadioDefault22"> No </label>
                                                                        @endif
                                                                            
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="row ">
                                                                <div class="col-md-8">
                                                                <p><b>Use default reply and resolution deadline</b></p>
                                                                <small>The time below will be used to calculate the ticket reply and resolution deadline if an SLA plan is not assigned to the ticket, user, user group or department. </small>
                                                                    <hr>
                                                                </div>
                                                                <div class="col-md-4 ">
                                                                    <div class="row text-right">
                                                                        <div class="form-check ml-3">
                                                                        @if(sizeOf($sla_setting) > 0)
                                                                            @if($sla_setting['default_reply_and_resolution_deadline'] == 1)
                                                                                <input class="form-check-input" type="radio" name="flexRadioDefault3" id="flexRadioDefault13" checked>
                                                                                <label class="form-check-label" for="flexRadioDefault13"> Yes </label>
                                                                            @else
                                                                                <input class="form-check-input" type="radio" name="flexRadioDefault3" id="flexRadioDefault13">
                                                                                <label class="form-check-label" for="flexRadioDefault13"> Yes </label>
                                                                            @endif
                                                                        @else
                                                                            <input class="form-check-input" type="radio" name="flexRadioDefault3" id="flexRadioDefault13">
                                                                            <label class="form-check-label" for="flexRadioDefault13"> Yes </label>
                                                                        @endif
                                                                        </div>
                                                                        <div class="form-check ml-3">
                                                                        @if(sizeOf($sla_setting) > 0)
                                                                            @if($sla_setting['default_reply_and_resolution_deadline'] == 0)
                                                                                <input class="form-check-input" type="radio" name="flexRadioDefault3" id="flexRadioDefault23" value="0" checked>
                                                                                <label class="form-check-label" for="flexRadioDefault23"> No </label>
                                                                            @else
                                                                                <input class="form-check-input" type="radio" name="flexRadioDefault3" id="flexRadioDefault23" value="0">
                                                                                <label class="form-check-label" for="flexRadioDefault23"> No </label>
                                                                            @endif
                                                                        @else
                                                                            <input class="form-check-input" type="radio" name="flexRadioDefault3" id="flexRadioDefault23" value="0">
                                                                            <label class="form-check-label" for="flexRadioDefault23"> No </label>
                                                                        @endif
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="row ">
                                                                <div class="col-md-8">
                                                                <p><b>Default reply time deadline</b></p>
                                                                <small>If a user is not responded to within these hours, the ticket will be marked as overdue. </small>
                                                                    <hr>
                                                                </div>
                                                                <div class="col-md-4 ">
                                                                    <div class="row text-right">
                                                                        <div class="col-md-6 form-group ml-3">
                                                                        @if(sizeOf($sla_setting) > 0)
                                                                            <input class="form-control" value="{{$sla_setting['default_reply_time_deadline']}}" name="default_reply_time_deadline" type="number" placeholder="24" name="" id="default_reply_time_deadline" placeholder="">
                                                                        @else
                                                                            <input class="form-control" name="default_reply_time_deadline" type="number" placeholder="24" name="" id="default_reply_time_deadline" placeholder="">
                                                                        @endif
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="row ">
                                                                <div class="col-md-8">
                                                                <p><b>Default resolution deadline</b></p>
                                                                <small>If a ticket is not resolved to within these hours, the ticket will be marked as overdue. </small>
                                                                    <hr>
                                                                </div>
                                                                <div class="col-md-4 ">
                                                                    <div class="row text-right">
                                                                        <div class="col-md-6 form-group ml-3">
                                                                        @if(sizeOf($sla_setting) > 0)
                                                                            <input class="form-control" value="{{$sla_setting['default_resolution_deadline']}}" name="default_resolution_deadline" type="number" placeholder="72" name="" id="default_resolution_deadline">
                                                                        @else
                                                                            <input class="form-control" name="default_resolution_deadline" type="number" placeholder="72" name="" id="default_resolution_deadline">
                                                                        @endif
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            <div class="row ">
                                                                <div class="col-md-8">
                                                                <p><b>Overdue ticket background color</b></p>
                                                                <small>Overdue tickets can be highlighted with a background color. Choose a light color so the text is still readable.</small>
                                                                    <hr>
                                                                </div>
                                                                <div class="col-md-4 ">
                                                                    <div class="row text-right">
                                                                        <div class="col-md-9 form-group " style="padding-right:9px;">
                                                                        @if(sizeOf($sla_setting) > 0)
                                                                            <input class="form-control" value="{{$sla_setting['overdue_ticket_background_color']}}" name="overdue_ticket_background_color" type="color" id="overdue_ticket_background_color">
                                                                        @else
                                                                            <input class="form-control" name="overdue_ticket_background_color" type="color" id="overdue_ticket_background_color">
                                                                        @endif
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            <div class="row ">
                                                                <div class="col-md-8">
                                                                <p><b>Overdue ticket text & button color</b></p>
                                                                <small>Overdue tickets can be highlighted with a Text color. Choose a light color so the text is still readable.</small>
                                                                    <hr>
                                                                </div>
                                                                <div class="col-md-4 ">
                                                                    <div class="row text-right">
                                                                        <div class="col-md-9 form-group " style="padding-right:9px;">
                                                                            @if(sizeOf($sla_setting) > 0)
                                                                                <input class="form-control" value="{{$sla_setting['overdue_ticket_text_color']}}" name="overdue_ticket_text_color" type="color" id="overdue_ticket_text_color">
                                                                            @else
                                                                                <input class="form-control"  name="overdue_ticket_text_color" type="color" id="overdue_ticket_text_color">
                                                                            @endif
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            <div class="row ">
                                                                <div class="col-md-8">
                                                                <p><b>Clear reply due deadline when adding a ticket note</b></p>
                                                                <small>When a ticket is updated (i.e. replied to by a staff user), the reply due time clock is reset. By enabling this setting, a ticket note will also count as a ticket update. </small>
                                                                    <hr>
                                                                </div>
                                                                <div class="col-md-4 ">
                                                                    <div class="row text-right">
                                                                    @if(sizeOf($sla_setting) > 0)
                                                                        @if($sla_setting['reply_due_deadline_when_adding_ticket_note'] == 1)
                                                                        <div class="form-check ml-3">
                                                                            <input class="form-check-input" type="radio" name="flexRadioDefault4" id="flexRadioDefault14" checked>
                                                                            <label class="form-check-label" for="flexRadioDefault14"> Yes </label>
                                                                        </div>
                                                                        @else
                                                                        <div class="form-check ml-3">
                                                                            <input class="form-check-input" type="radio" name="flexRadioDefault4" id="flexRadioDefault14" checked>
                                                                            <label class="form-check-label" for="flexRadioDefault14"> Yes </label>
                                                                        </div>
                                                                        @endif
                                                                    @else
                                                                        <div class="form-check ml-3">
                                                                            <input class="form-check-input" type="radio" name="flexRadioDefault4" id="flexRadioDefault14" checked>
                                                                            <label class="form-check-label" for="flexRadioDefault14"> Yes </label>
                                                                        </div>
                                                                    @endif

                                                                    @if(sizeOf($sla_setting) > 0)
                                                                        @if($sla_setting['reply_due_deadline_when_adding_ticket_note'] == 0)
                                                                            <div class="form-check ml-3">
                                                                                <input class="form-check-input" type="radio" name="flexRadioDefault4" id="flexRadioDefault24" checked>
                                                                                <label class="form-check-label" for="flexRadioDefault24"> No </label>
                                                                            </div>
                                                                        @else
                                                                            <div class="form-check ml-3">
                                                                                <input class="form-check-input" type="radio" name="flexRadioDefault4" id="flexRadioDefault24">
                                                                                <label class="form-check-label" for="flexRadioDefault24"> No </label>
                                                                            </div>
                                                                        @endif
                                                                    @else
                                                                        <div class="form-check ml-3">
                                                                            <input class="form-check-input" type="radio" name="flexRadioDefault4" id="flexRadioDefault24">
                                                                            <label class="form-check-label" for="flexRadioDefault24"> No </label>
                                                                        </div>
                                                                    @endif
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="row">
                                                                <div class="col-md-12 text-right">
                                                                    <button class="btn btn-success">Save</button>
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

                        <div class="tab-pane show" id="tickets_departments">
                            <div id="accordion" class="custom-accordion mb-4">
                                <div class="card mb-0">
                                    <div class="card-header" id="departments_collapse">
                                        <h5 class="m-0">
                                            <a class="custom-accordion-title d-flex align-items-center pt-2 pb-2 collapsed" data-toggle="collapse" href="#collapseDepartments" aria-expanded="false" aria-controls="collapseOne">Departments<span class="ml-auto"><i class="mdi mdi-chevron-down accordion-arrow"></i></span>
                                            </a>
                                        </h5>
                                    </div>
                                    <div id="collapseDepartments" class="collapse" aria-labelledby="departments_collapse" data-parent="#accordion" style="">
                                        <div class="card-body">
                                            <div class="widget-header widget-header-small">
                                                <div class="row">
                                                    <div class="col-md-8 col-sm-6">
                                                        <h4 class="widget-title lighter smaller menu_title">Department Table</h4>
                                                    </div>
                                                    <div class="col-md-4 col-sm-6">
                                                        <button class="btn waves-effect waves-light btn-success" data-toggle="" data-target="" onclick="showDepModel()" style="float:right"><i class="mdi mdi-plus-circle" style="padding-right:3px;"></i>&nbsp;Add Department</button>
                                                    </div>
                                                </div>
                                                <br>
                                                <span class="loader_lesson_plan_form"></span>
                                            </div>
                                            <div class="widget-body">
                                                <div class="widget-main">
                                                    <div class="row">
                                                        <div class="col-md-12" style="text-align:right;">
                                                            <select class="multiple-select mt-2 mb-2" name="department_column" id="department_column" placeholder="Show/Hide" multiple="multiple" selected="selected" style="text-align:left;">
                                                                <option value="0">Sr #</option>
                                                                <option value="1">Name</option>
                                                                <option value="2">Actions</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-sm-12">
                                                            <table id="ticket-departments-list" class="display table-striped table-bordered ticket-departments-list" style="width:100%">
                                                                <thead>
                                                                    <tr>
                                                                        <th>#</th>
                                                                        <th>Name</th>
                                                                        <th>Actions</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody></tbody>
                                                            </table>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div> 

                                <div class="card mb-0">
                                    <div class="card-header" id="Status_collapse">
                                        <h5 class="m-0">
                                            <a class="custom-accordion-title d-flex align-items-center pt-2 pb-2 collapsed"
                                                data-toggle="collapse" href="#collapseStatus" aria-expanded="false"
                                                aria-controls="collapseTwo">
                                                Status <span class="ml-auto"><i
                                                        class="mdi mdi-chevron-down accordion-arrow"></i></span>
                                            </a>
                                        </h5>
                                    </div>
                                    <div id="collapseStatus" class="collapse" aria-labelledby="Status_collapse"
                                        data-parent="#accordion" style="">
                                        <div class="card-body">
                                            <div class="widget-header widget-header-small">
                                                <div class="row">
                                                    <div class="col-md-8 col-sm-6">
                                                        <h4 class="widget-title lighter smaller menu_title">Status Table
                                                        </h4>
                                                    </div>
                                                    <div class="col-md-4 col-sm-6">
                                                        <button class="btn waves-effect waves-light btn-success"
                                                            onclick="showStatusModel()" style="float:right">
                                                            <i class="mdi mdi-plus-circle" style="padding-right:3px;"></i>&nbsp;Add Status</button>
                                                    </div>
                                                </div>
                                                <br>
                                                <span class="loader_lesson_plan_form"></span>
                                            </div>
                                            <div class="widget-body">
                                            
                                                <div class="widget-main">
                                                    <div class="row">
                                                        <div class="col-md-12" style="text-align:right;">
                                                            <select class="multiple-select mt-2 mb-2" name="status_column" id="status_column" placeholder="Show/Hide" multiple="multiple" selected="selected" style="text-align:left;">
                                                                <option value="0">Sr #</option>
                                                                <option value="1">Name</option>
                                                                <option value="2">Action</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-sm-12">
                                                            <table id="ticket-status-list"
                                                                class="display table-striped table-bordered ticket-status-list"
                                                                style="width:100%">
                                                                <thead>
                                                                    <tr>
                                                                        <th>#</th>
                                                                        <th>Name</th>
                                                                        <th>Color</th>
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
                                    <div class="card-header" id="Type_collapse">
                                        <h5 class="m-0">
                                            <a class="custom-accordion-title d-flex align-items-center pt-2 pb-2 collapsed"
                                                data-toggle="collapse" href="#collapseType" aria-expanded="false"
                                                aria-controls="collapseThree">
                                                Types <span class="ml-auto"><i
                                                        class="mdi mdi-chevron-down accordion-arrow"></i></span>
                                            </a>
                                        </h5>
                                    </div>
                                    <div id="collapseType" class="collapse" aria-labelledby="Type_collapse"
                                        data-parent="#accordion" style="">
                                        <div class="card-body">
                                            <div class="widget-header widget-header-small">
                                                <div class="row">
                                                    <div class="col-md-8 col-sm-6">
                                                        <h4 class="widget-title lighter smaller menu_title">Type Table
                                                        </h4>
                                                    </div>
                                                    <div class="col-md-4 col-sm-6">
                                                        <button class="btn waves-effect waves-light btn-success"
                                                            onclick="showTypeModel()" style="float:right">
                                                            <i class="mdi mdi-plus-circle" style="padding-right:3px;"></i>&nbsp;Add Type</button>
                                                    </div>
                                                </div>
                                                <br>
                                                <span class="loader_lesson_plan_form"></span>
                                            </div>
                                            <div class="widget-body">
                                                <div class="widget-main">
                                                    <div class="col-md-12" style="text-align:right;">
                                                        <select class="multiple-select mt-2 mb-2" name="type_column" id="type_column" placeholder="Show/Hide" multiple="multiple" selected="selected" style="text-align:left;">
                                                            <option value="0">Sr #</option>
                                                            <option value="1">Name</option>
                                                            <option value="2">Action</option>
                                                        </select>
                                                    </div>

                                                    <div class="row">
                                                        <div class="col-sm-12">
                                                            <table id="ticket-type-list"
                                                                class="display table-striped table-bordered ticket-type-list"
                                                                style="width:100%">
                                                                <thead>
                                                                    <tr>
                                                                        <th>#</th>
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
                                    <div class="card-header" id="Priority_collapse">
                                        <h5 class="m-0">
                                            <a class="custom-accordion-title d-flex align-items-center pt-2 pb-2 collapsed"
                                                data-toggle="collapse" href="#collapsePriority" aria-expanded="false"
                                                aria-controls="collapseThree">
                                                Priorities <span class="ml-auto"><i
                                                        class="mdi mdi-chevron-down accordion-arrow"></i></span>
                                            </a>
                                        </h5>
                                    </div>
                                    <div id="collapsePriority" class="collapse" aria-labelledby="Priority_collapse"
                                        data-parent="#accordion" style="">
                                        <div class="card-body">
                                            <div class="widget-header widget-header-small">
                                                <div class="row">
                                                    <div class="col-md-8 col-sm-6">
                                                        <h4 class="widget-title lighter smaller menu_title">Priority
                                                            Table</h4>
                                                    </div>
                                                    <div class="col-md-4 col-sm-6">
                                                        <button class="btn waves-effect waves-light btn-success"
                                                            onclick="showPriorityModel()" style="float:right">
                                                            <i class="mdi mdi-plus-circle" style="padding-right:3px;"></i>&nbsp;Add Priority</button>
                                                    </div>
                                                </div>
                                                <br>
                                                <span class="loader_lesson_plan_form"></span>
                                            </div>
                                            <div class="widget-body">
                                                <div class="widget-main">
                                                    <div class="col-md-12" style="text-align:right;">
                                                        <select class="multiple-select mt-2 mb-2" name="priority_column" id="priority_column" placeholder="Show/Hide" multiple="multiple" selected="selected" style="text-align:left;">
                                                            <option value="0">Sr #</option>
                                                            <option value="1">Name</option>
                                                            <option value="2">Color</option>
                                                            <option value="3">Action</option>
                                                        </select>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-sm-12">
                                                            <table id="ticket-priority-list"
                                                                class="display table-striped table-bordered ticket-priority-list"
                                                                style="width:100%">
                                                                <thead>
                                                                    <tr>
                                                                        <th>#</th>
                                                                        <th>Name</th>
                                                                        <th>Color</th>
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

                        <div class="tab-pane" id="tickets_mails">
                            <div class="card-body">
                                <div class="widget-header widget-header-small">
                                    <div class="row">
                                        <div class="col-md-12 col-sm-6" style="text-align:right">
                                            <button class="btn waves-effect waves-light btn-success"
                                                onclick="showPop3Model('tickets')"><i class="mdi mdi-plus-circle"></i>&nbsp;Add New
                                                Mail</button>
                                        </div>
                                    </div>
                                    <br>
                                    <span class="loader_lesson_plan_form"></span>
                                </div>
                                <div class="widget-body">
                                    <div class="widget-main">
                                        <div class="row">
                                            <div class="col-sm-12">
                                                <div class="table-responsive">
                                                    <table id="ticket-mails-list" class="display table-striped table-bordered text-center ticket-mails-list"
                                                        style="width:100%">
                                                        <thead>
                                                            <tr>
                                                                <th>Sr #</th>
                                                                <th>Email Queue Address</th>
                                                                <th>Type</th>
                                                                <th>Department</th>
                                                                <th>Registered</th>
                                                                <th>Default</th>
                                                                <th>Actions</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>

                                                        </tbody>
                                                    </table>
                                                </div>
                                                <div class="loader_container" id="emailtableloader">
                                                    <div class="loader"></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="tab-pane" id="response_temp">
                            <div id="accordion" class="custom-accordion mb-4">

                                <div class="mb-0">
                                    <div class="card-header" id="headingOne">
                                        <h5 class="m-0">
                                            <a class="custom-accordion-title d-flex align-items-center pt-2 pb-2" data-toggle="collapse" href="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                                                Response templete Category <span class="ml-auto"><i class="mdi mdi-chevron-down accordion-arrow"></i></span>
                                            </a>
                                        </h5>
                                    </div>
                                    <div id="collapseOne" class="collapse show" aria-labelledby="headingOne" data-parent="#accordion" style="">
                                        <div class="card-body">

                                            <div class="widget-header widget-header-small">
                                                <div class="row">
                                                    <div class="col-md-8 col-sm-6">
                                                        <h4 class="widget-title lighter smaller menu_title">Response Template Category
                                                        </h4>
                                                    </div>
                                                    <div class="col-md-4 col-sm-6">
                                                        <button class="btn waves-effect waves-light btn-success rounded btn-sm"
                                                        data-toggle="modal" data-target="#save-category-modal" style="float:right">
                                                            <i class="mdi mdi-plus-circle" style="padding-right:3px;"></i>&nbsp;Add new Category</button>
                                                    </div>
                                                </div>
                                                <br>
                                                <span class="loader_lesson_plan_form"></span>
                                            </div>
                                            <div class="widget-body">
                                                <div class="widget-main">
                                                    <div class="row">
                                                        <div class="col-sm-12">
                                                            <table id="temp_cat_table"
                                                                class="display table-striped table-bordered project-type-list"
                                                                style="width:100%">
                                                                <thead>
                                                                    <tr>
                                                                        <th>#</th>
                                                                        <th>Id</th>
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
                                </div> <!-- end card-->

                                <div class="card mb-0">
                                    <div class="card-header" id="headingTwo">
                                        <h5 class="m-0">
                                            <a class="custom-accordion-title collapsed d-flex align-items-center pt-2 pb-2" data-toggle="collapse" href="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                                                Response Template <span class="ml-auto"><i class="mdi mdi-chevron-down accordion-arrow"></i></span>
                                            </a>
                                        </h5>
                                    </div>
                                    <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordion">
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-sm-4 " style="border-right:1px solid grey;">
                                                    <div class="row ">
                                                        <div class="col-md-12">
                                                                <input type="text" class="form-control " placeholder="Search for..."> 
                                                                <a class="srh-btn" ><i class="ti-search"></i></a> 
                                                        </div>
                                                        <div id="alltempResponse" class="w-100 pt-3 pr-3 pl-3 pb-1">
                                                            
                                                        </div>
                                                       
                                                    </div>
                                                </div>
                                                <div class="col-sm-8">
                                                    <form id="save_temp_response" action="{{asset('/save-temp-response')}}" method="post">
                                                        <div class="form-group">
                                                            <input type="hidden" id="res_id" name="res_id">
                                                            <label for="departmrnt">Title</label>
                                                            <input class="form-control res_title" type="text" name="title" id="title" placeholder="">
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="departmrnt">Category Name</label>
                                                            <select class="form-control" id="cat_id" name="cat_id"  >
                                                            </select>
                                                        </div>

                                                        <div class="form-group">
                                                            <label>Quote Details</label>

                                                            <textarea class="form-control tinymce" id="mymce" rows="2"></textarea>

                                                        </div>

                                                        <div class="row text-right">
                                                            <div class="form-check ml-3">
                                                                <input class="form-check-input" type="radio" name="view_access" id="onlyMe">
                                                                <label class="form-check-label" for="onlyMe"> Show only to Me </label>
                                                            </div>
                                                            <div class="form-check ml-3">
                                                                <input class="form-check-input" type="radio" name="view_access" id="allStaff" >
                                                                <label class="form-check-label" for="allStaff"> Show to all Staff </label>
                                                            </div>
                                                        </div>
                                                        <div class="row mt-3">
                                                            <div class="col-md-6">
                                                                <select name="" id="" class="form-control">
                                                                    <option value="Hung">Hung</option>
                                                                    <option value="Hung">Hung</option>
                                                                    <option value="Hung">Hung</option>
                                                                    <option value="Hung">Hung</option>
                                                                </select>
                                                                
                                                            </div>
                                                            <div class="col-md-6">
                                                                <select name="" id="" class="form-control">
                                                                    <option value="Hung">Hung</option>
                                                                    <option value="Hung">Hung</option>
                                                                    <option value="Hung">Hung</option>
                                                                    <option value="Hung">Hung</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-12 mt-3 text-right">
                                                            <button class="btn btn-success " type="submit">
                                                                Publish/Update
                                                            </button>
                                                        </div>

                                                    </form>

                                                    <div class="col-md-12 text-right mt-3" style="border-top:1px solid grey;">
                                                        <p>
                                                            Published by Staff Name
                                                        </p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div> <!-- end card-->
                            </div>
                        </div>

                    </div>
                </div> 
            </div> 
        </div>

        <div class="col-md-8 gears" id="billing_settings" style="display:none">
            <div class="card">
                <div class="card-body">

                    <h4 class="card-title mb-3">Order Settings</h4>

                    <ul class="nav nav-pills bg-nav-pills nav-justified mb-5 mt-5">
                        <li class="nav-item">
                            <a href="#billing_general" data-toggle="tab" aria-expanded="false" class="nav-link active">
                                <i class="mdi mdi-home-variant d-lg-none d-block mr-1"></i>
                                <span class="d-none d-lg-block">General Settings</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="#billing_departments" data-toggle="tab" aria-expanded="true" class="nav-link">
                                <i class="mdi mdi-home-variant d-lg-none d-block mr-1"></i>
                                <span class="d-none d-lg-block">Customizations</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="#billing_mails" data-toggle="tab" aria-expanded="false" class="nav-link">
                                <i class="mdi mdi-email d-lg-none d-block mr-1"></i>
                                <span class="d-none d-lg-block ">Email Queues</span>
                            </a>
                        </li>
                    </ul>

                    <div class="tab-content">
                        
                        <div class="tab-pane active" id="billing_general">
                            <form class="widget-box widget-color-dark mt-2" id="save_order_format" action="{{asset('save_billing_orderid_format')}}" method="post">
                                <div class="row">
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="security">Order ID Format :</label>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <select class="select2 form-control select2-hidden-accessible" style="width:100%;height:30px;" name="bill_order_id_frmt" required>
                                                <option value="random">Random ( #108934 )</option>
                                                <option value="sequential">Sequential (1#,2#,3#,...,#99999)</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="security">Currency Format :</label>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <select class="select2 form-control select2-hidden-accessible" style="width:100%;height:30px;" name="currency_format" required>
                                                <option value="<i class='fas fa-dollar-sign'></i>">Dollar</option>
                                                <option value="<i class='fas fa-pound-sign'></i>">Pound</option>
                                                <option value="<i class='fas fa-euro-sign'></i>">Euro</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="security">Invoice # Format :</label>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <input type="text" class="form-control" name="order_invoice_format" required style="width:100%;height:30px;" value="X"/>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-12 text-right">
                                        <button class="btn btn-primary" type="submit" onsubmit="return false;">Save</button>
                                    </div>
                                </div>
                            </form>
                        </div>

                        <div class="tab-pane show" id="billing_departments">
                           
                        </div>

                        <div class="tab-pane" id="billing_mails">
                            <div class="card-body">
                                <div class="widget-header widget-header-small">
                                    <div class="row">
                                        <div class="col-md-12 col-sm-6" style="text-align:right">
                                            <button class="btn waves-effect waves-light btn-success"
                                                onclick="showPop3Model('billing')"><i class="mdi mdi-plus-circle"></i>&nbsp;Add New
                                                email</button>
                                        </div>
                                    </div>
                                    <br>
                                    <span class="loader_lesson_plan_form"></span>
                                </div>
                                <div class="widget-body">
                                    <div class="widget-main">
                                        <div class="row">
                                            <div class="col-sm-12">
                                                <table id="ticket-mails-list"
                                                    class="display table-striped table-bordered ticket-mails-list"
                                                    style="width:100%">
                                                    <thead>
                                                        <tr>
                                                            <th>#</th>
                                                            <th>Email Queue Address</th>
                                                            <th>Type</th>
                                                            <th>Department</th>
                                                            <th>Registered</th>
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
    
        <div class="col-md-8 gears" id="customer_settings" style="display:none">
            <div class="card">
                <div class="card-body">

                    <h4 class="card-title mb-3">Customer Settings</h4>

                    <ul class="nav nav-pills bg-nav-pills nav-justified mb-3">
                        <li class="nav-item">
                            <a href="#homeCustomer" data-toggle="tab" aria-expanded="false" class="nav-link rounded-0 active">
                                <i class="mdi mdi-home-variant d-lg-none d-block mr-1"></i>
                                <span class="d-none d-lg-block">General Settings</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="#type_customer" data-toggle="tab" aria-expanded="false" class="nav-link rounded-0 ">
                                <i class="mdi mdi-home-variant d-lg-none d-block mr-1"></i>
                                <span class="d-none d-lg-block">Customization</span>
                            </a>
                        </li>
                        

                    </ul>

                    <div class="tab-content">
                        <div class="tab-pane active" id="homeCustomer">
                            <form action="{{url('customer_setting')}}" method="POST" id="customer_setting_form" enctype="multipart/form-data">
                                <div class="row mt-5">
                                    <div class="col-md-6">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="delete" id="delete">
                                            <label class="form-check-label" for="delete"> Allow customer to delete their own account </label>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="disable" id="disable">
                                            <label class="form-check-label" for="disable">  Allow customer to disable their own account </label>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="create" id="create">
                                            <label class="form-check-label" for="create">  Allow customer to create a new account </label>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    
                                    <div class="col-md-6">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="customer_login" id="customer_login">
                                            <label class="form-check-label" for="customer_login">  Send welcome email to customers having login accounts </label>
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-6">
                                        <label for="">From Email Address </label>
                                        <input type="email" class="form-control" id="accounts_from_email" name="accounts_from_email" placeholder="From Email">
                                    </div>

                                </div>
                                <button type="submit" id="save_btn" class="btn btn-success">Save</button>
                            </form>
                        </div>
                        <div class="tab-pane show" id="type_customer">
                            <div id="accordion" class="custom-accordion mb-4">

                                <div class="card mb-0">
                                    <div class="card-header" id="customer_type_collapse">
                                        <h5 class="m-0">
                                            <a class="custom-accordion-title d-flex align-items-center pt-2 pb-2 collapsed"
                                                data-toggle="collapse" href="#collapseTypecustomer" aria-expanded="false"
                                                aria-controls="collapseThree">
                                                Customer Types <span class="ml-auto"><i
                                                        class="mdi mdi-chevron-down accordion-arrow"></i></span>
                                            </a>
                                        </h5>
                                    </div>
                                    <div id="collapseTypecustomer" class="collapse" aria-labelledby="customer_type_collapse"
                                        data-parent="#accordion" style="">
                                        <div class="card-body">
                                            <div class="widget-header widget-header-small">
                                                <div class="row">
                                                    <div class="col-md-8 col-sm-6">
                                                        <h4 class="widget-title lighter smaller menu_title">Customer Type Table
                                                        </h4>
                                                    </div>
                                                    <div class="col-md-4 col-sm-6">
                                                        <button class="btn waves-effect waves-light btn-success"
                                                            onclick="showCustomerTypeModel()" style="float:right">
                                                            <i class="mdi mdi-plus-circle" style="padding-right:3px;"></i>&nbsp;Add Type</button>
                                                    </div>
                                                </div>
                                                <br>
                                                <span class="loader_lesson_plan_form"></span>
                                            </div>
                                            <div class="widget-body">
                                                <div class="widget-main">
                                                    <div class="row">
                                                        <div class="col-sm-12">
                                                            <table id="customer-type-list"
                                                                class="display table-striped table-bordered customer-type-list"
                                                                style="width:100%">
                                                                <thead>
                                                                    <tr>
                                                                        <th>#</th>
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
                        <div class="tab-pane" id="settings1">

                        </div>
                    </div>

                </div>
            </div>
        </div>

        <div class="col-md-8 gears" id="marketing_settings" style="display:none">
            <div class="card">
                <div class="card-body">

                    <h4 class="card-title mb-3">Marketing Settings</h4>

                    <ul class="nav nav-pills bg-nav-pills nav-justified mb-3">
                        <li class="nav-item">
                            <a href="#home1" data-toggle="tab" aria-expanded="false" class="nav-link rounded-0 active">
                                <i class="mdi mdi-home-variant d-lg-none d-block mr-1"></i>
                                <span class="d-none d-lg-block">General Settings</span>
                            </a>
                        </li>

                    </ul>

                    <div class="tab-content">
                        <div class="tab-pane active" id="home1">

                        </div>
                        <div class="tab-pane show" id="profile1">

                        </div>
                        <div class="tab-pane" id="settings1">

                        </div>
                    </div>

                </div>
            </div>
        </div>

        <div class="col-md-8 gears" id="security_settings" style="display:none">
            <div class="card">
                <div class="card-body">

                    <h4 class="card-title mb-3">Security Settings</h4>

                    <ul class="nav nav-pills bg-nav-pills nav-justified mb-3">
                        <li class="nav-item">
                            <a href="#security_set" data-toggle="tab" aria-expanded="false" class="nav-link rounded-0 active">
                                <i class="mdi mdi-home-variant d-lg-none d-block mr-1"></i>
                                <span class="d-none d-lg-block">General Settings</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="#Firewall" data-toggle="tab" aria-expanded="false" class="nav-link rounded-0">
                                <i class="mdi mdi-home-variant d-lg-none d-block mr-1"></i>
                                <span class="d-none d-lg-block"> Firewall </span>
                            </a>
                        </li>

                    </ul>
                    
                    <div class="tab-content">
                        <div class="tab-pane active" id="security_set">
                            <form method="post">
                                <div class="row mt-4 mb-4">
                                    <div class="col-md-12">
                                        <h2>Password Enforcement</h2>
                                        <hr>
                                    </div>
                                    
                                    <div class="col-md-6">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="same_pass" id="same_pass">
                                            <label class="form-check-label" for="same_pass"> Can use same password </label>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <p class="mute">This feature will allow/block user from repeating the use of the same password.</p>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="blank_spacer" id="blank_spacer">
                                            <label class="form-check-label" for="blank_spacer"> Blank Spacer </label>
                                        </div>
                                    </div>
                                    <div class="col-md-6"></div>
                                    <div class="col-md-6">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="upperCase" id="upperCase">
                                            <label class="form-check-label" for="upperCase"> Password must contain Uppercase Letter </label>
                                        </div>
                                    </div>
                                    <div class="col-md-6"></div>
                                    <div class="col-md-6">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="passNumbers" id="passNumbers">
                                            <label class="form-check-label" for="passNumbers"> Password must contain Numbers </label>
                                        </div>  
                                    </div>
                                    <div class="col-md-6">
                                        <div class="row">
                                            <div class="col-md-9 col-6">
                                                <p class="mute">Minimum Password length</p>
                                            </div>
                                            <div class="col-md-3 col-6 form-group">
                                                <input class="form-control" type="number" placeholder="6" name="minLength" id="minLength">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="passSymbols" id="passSymbols">
                                            <label class="form-check-label" for="passSymbols"> Password must contain Symbols </label>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <p class="mute">Staff password must be longer than this length</p>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="blank_spacer2" id="blank_spacer">
                                            <label class="form-check-label" for="blank_spacer2"> Blank Spacer </label>
                                        </div>
                                    </div>
                                    <div class="col-md-6"></div>
                                    <div class="col-md-6">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="passExp" id="passExp">
                                            <label class="form-check-label" for="passExp"> Password expires after ____ </label>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="row">
                                            <div class="col-md-9 col-6">
                                                <p class="mute">Minimum number of digits</p>
                                            </div>
                                            <div class="col-md-3 col-6 form-group">
                                                <input class="form-control" type="number" placeholder="6" name="minLength" id="minLength">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="passUsername" id="passUsername">
                                            <label class="form-check-label" for="passUsername"> Password may not contain username </label>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <p class="mute">Staff password must include at least this number of digits</p>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="passReset" id="passReset">
                                            <label class="form-check-label" for="passReset"> Allow user to reset this own password </label>
                                        </div>
                                    </div>
                                </div>
                                <div class="row mt-4 mb-4">
                                    <div class="col-md-12">
                                        <h2>Header Text</h2>
                                        <hr>
                                    </div>
                                    
                                    <div class="col-md-10 mt-3">
                                       <p class="mute mb-0"> Enable secure sessions</p>
                                       <p class="mute"> This setting will prevent an attacker from capturing your staff user's session data and hijacking their helpdesk session.</p>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <div class="row " style="padding-top:28px;">
                                                <div class="form-check ml-3">
                                                    <input class="form-check-input" type="radio" name="flexRadioDefault" id="flexRadioDefault1" checked="">
                                                    <label class="form-check-label" for="flexRadioDefault1"> Yes </label>
                                                </div>
                                                <div class="form-check ml-3">
                                                    <input class="form-check-input " type="radio" name="flexRadioDefault" id="flexRadioDefault2">
                                                    <label class="form-check-label" for="flexRadioDefault2"> No </label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-10 ">
                                       <p class="mute mb-0"> Prevent staff from logging in for a period of time after too many logged in attempts</p>
                                       <p class="mute"> Staff will be prevented from trying to login to the helpdesk if they enter the wrong credentials too many time</p>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group ">
                                            <div class="row " style="padding-top:28px;">
                                                <div class="form-check ml-3">
                                                    <input class="form-check-input" type="radio" name="flexRadio" id="flexRadio1" checked="">
                                                    <label class="form-check-label" for="flexRadio1"> Yes </label>
                                                </div>
                                                <div class="form-check ml-3">
                                                    <input class="form-check-input" type="radio" name="flexRadio" id="flexRadio2">
                                                    <label class="form-check-label" for="flexRadio2"> No </label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div class="tab-pane show" id="Firewall">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <p>Lorem ipsum dolor sit amet consect.</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-8">
                                    <div class="row">
                                        <div class="col-md-8">
                                            <p>Lorem ipsum dolor sit amet consectetur adipisicing. 
                                            </p>
                                        </div>
                                        <div class="col-md-4 text-right">
                                            <button class="btn btn-success " data-toggle="modal" data-target="#Add-IP">Add IP</button> 
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-8">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <h2>Whitelist your IPs</h2>
                                            <div class="col-md-12 border-box">
                                                <div class="inner-data mt-2">
                                                    <p>lorem ipsum true is one</p>
                                                    <p>lorem ipsum true is one trus is two to fout</p>
                                                    <p>lorem ipsum true is one</p>
                                                </div>
                                                
                                            </div>
                                           
                                        </div>
                                        <div class="col-md-12 mt-3">
                                            <h2>Blacklist your IPs</h2>
                                            <div class="col-md-12 border-box">
                                                <div class="inner-data mt-2">
                                                    <p>lorem ipsum true is one</p>
                                                    <p>lorem ipsum true is one trus is two to fout</p>
                                                    <p>lorem ipsum true is one</p>
                                                </div>
                                                
                                            </div>
                                           
                                        </div>
                                    </div>
                                    
                                </div>
                                <div class="col-md-4 text-right">
                                    <button class="btn btn-success mt-10"> Quick Add Whitelist </button>
                                    <button class="btn btn-primary mt-2"> Quick Add Blacklist </button>
                                    <button class="btn btn-info mt-2"> Quick Remove Whitelist </button>
                                    <button class="btn btn-secondary mt-2"> Quick Remove Blacklist </button>
                                    <p class="mt-3">Tell me something new to understand the matter today in this occasion of prosperity</p>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane" id="settings1">

                        </div>
                    </div>

                </div>
            </div>
        </div>

        <div class="col-md-8 gears" id="system_settings" style="display:none">
            <div class="card">
                <div class="card-body">

                    <h4 class="card-title mb-3">System Settings</h4>

                    <ul class="nav nav-pills bg-nav-pills nav-justified mb-3">
                        <li class="nav-item">
                            <a href="#home1" data-toggle="tab" aria-expanded="false" class="nav-link rounded-0 active">
                                <i class="mdi mdi-home-variant d-lg-none d-block mr-1"></i>
                                <span class="d-none d-lg-block">General Settings</span>
                            </a>
                        </li>

                    </ul>
                    <form id="saveRecord" onsubmit="return false">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="sys_dt_frmt">Date Format</label>
                                    <select name="sys_dt_frmt" id="sys_dt_frmt" class="form-control">
                                        
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="sys_time_frmt">Time Format</label>
                                    <select name="sys_time_frmt" id="sys_time_frmt" class="form-control"></select>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="timezone">Default TimeZone</label> <br>
                                    <select name="timezone" id="timezone" class="select2 form-control select2-hidden-accessible" style="width:100%;">
                                        @foreach(timezone_identifiers_list() as $timezone)
                                            <option value="{{$timezone}}">{{$timezone}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        
                        <button type="submit" id="saveBtn" onclick="saveSystemDateAndTime()" class="btn btn-success">Save</button>
                        <button style="display:none" id="processing" class="btn btn-success" type="button" disabled><i class="fas fa-circle-notch fa-spin"></i> Processing</button>
                    </form>



                </div>
            </div>
        </div>

        <div class="col-md-8 gears" id="branding_settings" style="display:none">
            <div class="card">
                <div class="card-body">

                    <h4 class="card-title mb-3">Branding Settings</h4>

                    <ul class="nav nav-pills bg-nav-pills nav-justified mb-3">
                        <li class="nav-item">
                            <a href="#generalTech" data-toggle="tab" aria-expanded="false"
                                class="nav-link rounded-0 active">
                                <i class="mdi mdi-home-variant d-lg-none d-block mr-1"></i>
                                <span class="d-none d-lg-block">General </span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="#visualSetting" data-toggle="tab" aria-expanded="false"
                                class="nav-link rounded-0 ">
                                <i class="mdi mdi-home-variant d-lg-none d-block mr-1"></i>
                                <span class="d-none d-lg-block">Visual Settings </span>
                            </a>
                        </li>
                    </ul>
                    
                   
                    <div class="tab-content">
                        <div class="tab-pane active" id="generalTech">
                            <form method="post" action="{{asset('/save-brand-settings')}}" enctype="multipart/form-data"
                                id="brand_settings">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="site_title">Site Name</label>
                                            @if($brand_settings != null )
                                                <input class="form-control" type="text" name="site_title" id="site_title"
                                                placeholder="" value="{{$brand_settings->site_title}}">
                                            @else
                                                <input class="form-control" type="text" name="site_title" id="site_title"
                                                placeholder="">
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="site_logo">Logo Title</label>
                                            @if($brand_settings != null )
                                            <input class="form-control" type="text" name="site_logo_title" id="site_logo_title"
                                                placeholder="" value="{{$brand_settings->site_logo_title}}">
                                            @else 
                                            <input class="form-control" type="text" name="site_logo_title" id="site_logo_title"
                                                placeholder="">
                                            @endif

                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="site_logo">Site Version</label>
                                            @if($brand_settings != null )
                                                <input class="form-control" type="text" name="site_version" id="site_version" placeholder="" value="{{$brand_settings->site_version}}" readonly>
                                            @else
                                                <input class="form-control" type="text" name="site_version" id="site_version" placeholder="">
                                            @endif

                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="site_domain">Domain for Fake Email Generator</label>
                                            @if($brand_settings != null )
                                                <input class="form-control" type="text" name="site_domain" id="site_domain" placeholder="domain.com" value="{{$brand_settings->site_domain}}">
                                            @else
                                                <input class="form-control" type="text" name="site_domain" id="site_domain" placeholder="domain.com">
                                            @endif
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="departmrnt">Logo</label>
                                            <div class="input-group mb-3">

                                                <div class="custom-file">
                                                    <input type="file" class="form-control-file" id="site_logo"
                                                        name="site_logo">
                                                        <div class="d-flex" style="top: 37px; position: absolute;">
                                                            <small id="name13" class="badge badge-default badge-danger form-text text-white">Note</small><small style="padding-left: 6px;padding-top: 3px;">Allowed File Extensions jpg, jpeg, png</small>
                                                        </div>
                                                    <label class="custom-file-label" for="site_logo">Choose file</label>
                                                </div>
                                                
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="departmrnt">Favicon</label>
                                            <div class="input-group mb-3">
                                                <div class="custom-file">
                                                    <input type="file" class="form-control-file" id="site_favicon"
                                                        name="site_favicon">
                                                        <div class="d-flex" style="top: 37px; position: absolute;">
                                                            <small id="name13" class="badge badge-default badge-danger form-text text-white">Note</small><small style="padding-left: 6px;padding-top: 3px;">Allowed File Extensions jpg, jpeg, png</small>
                                                        </div>
                                                    <label class="custom-file-label" for="site_favicon">Choose file</label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row mt-2">
                                    <div class="col-md-6">
                                        
                                        @if($brand_settings != null && $brand_settings->site_logo != null)
                                            @if(file_exists( public_path() .'/'. $file_path .  $brand_settings->site_logo ))
                                                <img id="site_logo_preview" name="site_logo_preview" class="rounded" width="60"
                                                height="60" src="{{asset($file_path . $brand_settings->site_logo)}}" />
                                            @else
                                            <img id="site_logo_preview" name="site_logo_preview" class="rounded" width="60"
                                                height="60" src="{{asset($file_path . 'default_imgs/site_logo.png')}}" />
                                            @endif
                                        @else
                                            <img id="site_logo_preview" name="site_logo_preview" class="rounded" width="60"
                                                height="60" src="{{asset($file_path . 'default_imgs/site_logo.png')}}" />
                                        @endif
                                    </div>
                                    <div class="col-md-6">
                                        @if($brand_settings != null && $brand_settings->site_logo != null)
                                            @if(file_exists( public_path() .'/'. $file_path .  $brand_settings->site_favicon ))
                                                <img id="site_favicon_preview" name="site_favicon_preview" class="rounded" width="60"
                                                height="60" src="{{asset($file_path . $brand_settings->site_favicon)}}" />
                                            @else
                                            <img id="site_favicon_preview" name="site_favicon_preview" class="rounded" width="60"
                                                height="60" src="{{asset($file_path . 'default_imgs/site_logo.png')}}" />
                                            @endif
                                        @else
                                            <img id="site_favicon_preview" name="site_favicon_preview" class="rounded" width="60"
                                                height="60" src="{{asset($file_path . 'default_imgs/site_logo.png')}}" />
                                        @endif
                                    </div>
                                </div>

                                <div class="row mt-2">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="departmrnt">Login Logo</label>
                                            <div class="input-group mb-3">
                                                <div class="custom-file">
                                                    <input type="file" class="form-control" id="login_logo"
                                                        name="login_logo">
                                                        <div class="d-flex" style="top: 37px; position: absolute;">
                                                            <small id="name13" class="badge badge-default badge-danger form-text text-white">Note</small><small style="padding-left: 6px;padding-top: 3px;">Allowed File Extensions jpg, jpeg, png</small>
                                                        </div>
                                                    <label class="custom-file-label" for="login_logo">Choose file</label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="departmrnt">Customer Default Logo</label>
                                            <div class="input-group mb-3">
                                                <div class="custom-file">
                                                    <input type="file" class="form-control" id="customer_logo"
                                                        name="customer_logo">
                                                        <div class="d-flex" style="top: 37px; position: absolute;">
                                                            <small id="name13" class="badge badge-default badge-danger form-text text-white">Note</small><small style="padding-left: 6px;padding-top: 3px;">Allowed File Extensions jpg, jpeg, png</small>
                                                        </div>
                                                    <label class="custom-file-label" for="customer_logo">Choose file</label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6 mt-2">
                                    
                                        @if($brand_settings != null && $brand_settings->login_logo != null)
                                            @if(file_exists( public_path() .'/'. $file_path . $brand_settings->login_logo ))
                                                <img id="login_logo_preview" name="login_logo_preview" class="rounded" width="60"
                                                height="60" src="{{asset( $file_path . $brand_settings->login_logo)}}" />
                                            @else
                                            <img id="login_logo_preview" name="login_logo_preview" class="rounded" width="60"
                                                height="60" src="{{asset('default_imgs/login_logo.png')}}" />
                                            @endif
                                        @else
                                            <img id="login_logo_preview" name="login_logo_preview" class="rounded" width="60"
                                                height="60" src="{{asset('default_imgs/login_logo.png')}}" />
                                        @endif                                        
                                    </div>

                                    <!-- customer -->
                                    <div class="col-md-6 mt-2">
                                        @if($brand_settings != null && $brand_settings->customer_logo != null)
                                            @if(file_exists( public_path().'/'. $file_path . $brand_settings->customer_logo ))
                                                <img id="customer_logo_preview" name="customer_logo_preview" class="rounded" width="60"
                                                height="60" src="{{asset($file_path . $brand_settings->customer_logo)}}" />
                                            @else
                                            <img id="login_logo_preview" name="login_logo_preview" class="rounded" width="60"
                                                height="60" src="{{asset('default_imgs/customer.png')}}" />
                                            @endif
                                        @else
                                        <img id="login_logo_preview" name="login_logo_preview" class="rounded" width="60"
                                                height="60" src="{{asset('default_imgs/customer.png')}}" />
                                        @endif                                     
                                    </div>
                                </div>


                                <!-- company & users -->
                                <div class="row mt-2">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="departmrnt">Company Default Logo</label>
                                            <div class="input-group mb-3">
                                                <div class="custom-file">
                                                    <input type="file" class="form-control" id="company_logo"
                                                        name="company_logo">
                                                        <div class="d-flex" style="top: 37px; position: absolute;">
                                                            <small id="name13" class="badge badge-default badge-danger form-text text-white">Note</small><small style="padding-left: 6px;padding-top: 3px;">Allowed File Extensions jpg, jpeg, png</small>
                                                        </div>
                                                    <label class="custom-file-label" for="company_logo">Choose file</label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="departmrnt">Staff Default Logo</label>
                                            <div class="input-group mb-3">
                                                <div class="custom-file">
                                                    <input type="file" class="form-control" id="user_logo"
                                                        name="user_logo">
                                                        <div class="d-flex" style="top: 37px; position: absolute;">
                                                            <small id="name13" class="badge badge-default badge-danger form-text text-white">Note</small><small style="padding-left: 6px;padding-top: 3px;">Allowed File Extensions jpg, jpeg, png</small>
                                                        </div>
                                                    <label class="custom-file-label" for="user_logo">Choose file</label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6 mt-2">

                                        @if($brand_settings != null && $brand_settings->company_logo != null)
                                            @if(file_exists( public_path().'/'. $file_path .  $brand_settings->company_logo ))
                                                <img id="company_logo_preview" name="company_logo_preview" class="rounded" width="60"
                                                height="60" src="{{asset($file_path . $brand_settings->company_logo)}}" />
                                            @else
                                                <img id="login_logo_preview" name="login_logo_preview" class="rounded" width="60"
                                                    height="60" src="{{asset('default_imgs/company.png')}}" />
                                            @endif
                                        @else
                                            <img id="login_logo_preview" name="login_logo_preview" class="rounded" width="60"
                                                    height="60" src="{{asset('default_imgs/company.png')}}" />
                                        @endif 
                                        
                                    </div>

                                    <!-- customer -->
                                    <div class="col-md-6 mt-2">

                                        @if($brand_settings != null && $brand_settings->user_logo != null)
                                            @if(file_exists( public_path().'/'. $file_path .  $brand_settings->user_logo ))
                                                <img id="user_logo_preview" name="user_logo_preview" class="rounded" width="60"
                                                height="60" src="{{asset($file_path . $brand_settings->user_logo)}}" />
                                            @else
                                                <img id="login_logo_preview" name="login_logo_preview" class="rounded" width="60"
                                                    height="60" src="{{asset('default_imgs/logo.png')}}" />
                                            @endif
                                        @else
                                            <img id="login_logo_preview" name="login_logo_preview" class="rounded" width="60"
                                                        height="60" src="{{asset('default_imgs/logo.png')}}" />
                                        @endif 
                                        
                                    </div>
                                </div>

                                <div class="row mt-2">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="site_footer">Footer Copyright</label>
                                            @if($brand_settings != null)
                                            <textarea class="form-control" rows="3" id="site_footer"
                                                name="site_footer">{{$brand_settings->site_footer}}</textarea>
                                            @else
                                            <textarea class="form-control" rows="3" id="site_footer"
                                                name="site_footer"></textarea>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <button type="submit" class="btn btn-success mr-auto" style="float:right;"><i
                                        class="fas fa-check"></i>&nbsp;Save</button>
                            </form>
                        </div>
                        <div class="tab-pane show" id="visualSetting">
                            <form class="lightModeForm" method="post" >
                                <div class="row">
                                    <div class="col-md-12 text-center">
                                        <h3 >Light Mode</h3>
                                        <hr>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group row">
                                            <input type="hidden" value="Light" name="mode">
                                            <label for="main_sys_back" class="col-sm-5">Main System Background</label>
                                            <input type="text" name="main_sys_back" id="main_sys_back" class="form-control demo" value="#f2f7f8">
                                            <!-- <select name="main_sys_back" id="main_sys_back" class="form-control col-sm-6 bg-info" style="color:white;">
                                                <option > Select </option>
                                                <option value="bg-danger" class="bg-danger"> Danger </option>
                                                <option value="bg-success" class="bg-success"> Success </option>
                                                <option value="bg-primary" class="bg-primary"> Primary </option>
                                                <option value="bg-info" class="bg-info"> Info </option>
                                                <option value="bg-warning" class="bg-warning"> Warning </option>
                                                <option value="bg-secondary" class="bg-secondary"> Secondary </option>
                                            </select> -->
                                            <!-- <label class="col-sm-3 pt-2 colorName" >#fffff</label> -->
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group row">
                                            <label for="" class="col-sm-5">Header Background</label>
                                            <input type="text" name="head_back" id="head_back" class="form-control demo" value="#009efb">

                                          
                                            <!-- <label class="col-sm-3 pt-2">#fffff</label> -->
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group row">
                                            <label for="" class="col-sm-5">Card Background</label>
                                            <input type="text" name="card_back" id="card_back" class="form-control demo" value="#fff">
                                          
                                            <!-- <label class="col-sm-3 pt-2">#fffff</label> -->
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group row">
                                            <label for="" class="col-sm-5">Table Header Background</label>
                                            <input type="text" name="table_head_back" id="table_head_back" class="form-control demo" value="#009efb">
                                           
                                            <!-- <label class="col-sm-3 pt-2">#fffff</label> -->
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group row">
                                            <label for="" class="col-sm-5">Table Rows</label>
                                            <input type="text" name="table_row" id="table_row" class="form-control demo" value="#fff">
                                           
                                            <!-- <label class="col-sm-3 pt-2">#fffff</label> -->
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group row">
                                            <label for="" class="col-sm-5">Main Font Color</label>
                                            <input type="text" name="main_font" id="main_font" class="form-control demo" value="#54667a">
                                           
                                            <!-- <label class="col-sm-3 pt-2">#fffff</label> -->
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group row">
                                            <label for="" class="col-sm-5">Bread Crumb Background</label>
                                            <input type="text" name="bread_crum_back" id="bread_crum_back" class="form-control demo" value="transparent">
                                            
                                            <!-- <label class="col-sm-3 pt-2">#fffff</label> -->
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group row">
                                            <label for="" class="col-sm-5">Border Thickness</label>
                                            <input type="number" class="form-control col-sm-4" id="border_thick" name="border_thick" value="2">
                                         </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group row">
                                            <label for="" class="col-sm-5">Card Shadow</label>
                                            <input type="number" class="form-control col-sm-4" id="card_shadow" name="card_shadow" value="0">
                                       </div>
                                    </div>
                                    <div class="col-md-12 text-right">
                                        <button class="btn btn-success" type="submit"> 
                                            Save
                                        </button>
                                    </div>
                                </div>     
                            </form>    
                                
                            <form method="post" class="lightModeForm" id="darkModeForm">
                                <div class="row">
                                    <div class="col-md-12 text-center">
                                        <h3 >Dark Mode</h3>
                                        <hr>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group row">
                                            <input type="hidden" value="dark" name="mode">
                                            <label for="" class="col-sm-5">Main System Background</label>
                                            <input type="text" name="drk_main_sys_back" id="drk_main_sys_back" class="form-control demo" value="#323743 ">

                                            <!-- <label class="col-sm-3 pt-2">#fffff</label> -->
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group row">
                                            <label for="" class="col-sm-5">Header Background</label>
                                            <input type="text" name="drk_header_back" id="drk_header_back" class="form-control demo" value="#323743">

                                            <!-- <label class="col-sm-3 pt-2">#fffff</label> -->
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group row">
                                            <label for="" class="col-sm-5">Card Background</label>
                                            <input type="text" name="drk_card_back" id="drk_card_back" class="form-control demo" value="#252629">

                                            <!-- <label class="col-sm-3 pt-2">#fffff</label> -->
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group row">
                                            <label for="" class="col-sm-5">Table Header Background</label>
                                            <input type="text" name="drk_table_header" id="drk_table_header" class="form-control demo" value="#1E3E53">

                                            <!-- <label class="col-sm-3 pt-2">#fffff</label> -->
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group row">
                                            <label for="" class="col-sm-5">Table Rows</label>
                                            <input type="text" name="drk_table_row" id="drk_table_row" class="form-control demo" value="#ffff">

                                            <!-- <label class="col-sm-3 pt-2">#fffff</label> -->
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group row">
                                            <label for="" class="col-sm-5">Main Font Color</label>
                                            <input type="text" name="drk_main_font" id="drk_main_font" class="form-control demo" value="#d2dae0 ">

                                            <!-- <label class="col-sm-3 pt-2">#fffff</label> -->
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group row">
                                            <label for="" class="col-sm-5">Border Thickness</label>
                                            <input type="number" class="form-control col-sm-4" id="drk_border_thick" name="drk_border_thick" value="2">
                                         </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group row">
                                            <label for="" class="col-sm-5">Card Shadow</label>
                                            <input type="number" class="form-control col-sm-4" id="drk_card_shadow" name="drk_card_shadow" value="0">
                                       </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group row">
                                            <label for="" class="col-sm-5">Bread Crumb Background</label>
                                            <input type="text" name="drk_bread_crum" id="drk_bread_crum" class="form-control demo" value="transparent">

                                            <!-- <label class="col-sm-3 pt-2">#fffff</label> -->
                                        </div>
                                    </div>
                                    <div class="col-md-12 text-right">
                                        <button class="btn btn-success" type="submit"> 
                                            Save
                                        </button>
                                    </div>
                                </div>
                            </form>
                                <form method="post" class="lightModeForm">
                                    <div class="row">
                                        <div class="col-md-12 text-center">
                                            <h3 >Buttons</h3>
                                            <hr>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group row">
                                                <input type="hidden" value="button" name="mode">
                                                <label for="" class="col-sm-5">Add Button Background</label>
                                                <input type="text" name="add_btn_back" id="add_btn_back" class="form-control demo" value="#39c449">
                                            
                                                <!-- <label class="col-sm-3 pt-2">#fffff</label> -->
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group row">
                                                <label for="" class="col-sm-5">Font Color</label>
                                                <input type="text" name="add_font_color" id="add_font_color" class="form-control demo" value="#fff">
                                            
                                                <!-- <label class="col-sm-3 pt-2">#fffff</label> -->
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group row">
                                                <label for="" class="col-sm-5">Delete Button Background</label>
                                                <input type="text" name="dlt_btn_back" id="dlt_btn_back" class="form-control demo" value="#f62d51">
                                            
                                                <!-- <label class="col-sm-3 pt-2">#fffff</label> -->
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group row">
                                                <label for="" class="col-sm-5">Font Color</label>
                                                <input type="text" name="dlt_font_clr" id="dlt_font_clr" class="form-control demo" value="#fff">
                                            
                                                <!-- <label class="col-sm-3 pt-2">#fffff</label> -->
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group row">
                                                <label for="" class="col-sm-5">New Button Background</label>
                                                <input type="text" name="new_btn_back" id="new_btn_back" class="form-control demo" value="#39c449">
                                            
                                                <!-- <label class="col-sm-3 pt-2">#fffff</label> -->
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group row">
                                                <label for="" class="col-sm-5">Font Color</label>
                                                <input type="text" name="new_font_clr" id="new_font_clr" class="form-control demo" value="#fff">
                                            
                                                <!-- <label class="col-sm-3 pt-2">#fffff</label> -->
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group row">
                                                <label for="" class="col-sm-5">Register Button Background</label>
                                                <input type="text" name="reg_btn_back" id="reg_btn_back" class="form-control demo" value="#39c449">

                                                <!-- <label class="col-sm-3 pt-2">#fffff</label> -->
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group row">
                                                <label for="" class="col-sm-5">Font Color</label>
                                                <input type="text" name="reg_font_clr" id="reg_font_clr" class="form-control demo" value="#fff">
                                            
                                                <!-- <label class="col-sm-3 pt-2">#fffff</label> -->
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group row">
                                                <label for="" class="col-sm-5">Login Button Background</label>
                                                <input type="text" name="login_btn_btn" id="login_btn_btn" class="form-control demo" value="#7460ee">

                                                <!-- <label class="col-sm-3 pt-2">#fffff</label> -->
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group row">
                                                <label for="" class="col-sm-5">Font Color</label>
                                                <input type="text" name="login_font_clr" id="login_font_clr" class="form-control demo" value="#fff">
                                            
                                                <!-- <label class="col-sm-3 pt-2">#fffff</label> -->
                                            </div>
                                        </div>
                                        <div class="col-md-12 text-right">
                                            <button class="btn btn-success" type="submit"> 
                                                Save
                                            </button>
                                        </div>
                                    
                                    </div>

                                </form>

                               
                        </div>
                        <div class="tab-pane" id="settings1">

                        </div>
                    </div>

                </div>
            </div>
        </div>

        <div class="col-md-8 gears" id="menu_settings" style="display:none">
            <div class="card">
                <div class="card-body">

                    <div class="col-md-12 d-flex justify-content-end align-self-center">
                        <button class="btn btn-success btn-sm rounded" id="btn-add-new-user" data-toggle="modal" data-target="#addFeatureModal"><i
                                class="mdi mdi-plus-circle"></i> Add Feature</button>
                    </div>

                    <div class="row mt-3">
                        <div class="col-12">
                            <div class="table-responsive">
                                <!-- <div class="row">
                                    <div class="col-md-4">
                                        <select class="multiple-select mt-2 mb-2" name="feature_select" id="feature_select" placeholder="Select Menu" multiple="multiple" selected="selected">
                                            <option value="0">Sr #</option>
                                            <option value="1">Feature Title</option>
                                            <option value="2">Route</option>
                                            <option value="3">Active</option>
                                            <option value="4">Have Access</option>
                                            <option value="5">Sequence</option>
                                            <option value="6">Access</option>
                                            <option value="7">Actions</option>
                                        </select>
                                    </div>
                                    <div class="col-md-4"></div>
                                    <div class="col-md-4"></div>
                                </div> -->
                                <table id="Feature_table" class="table text-center table-hover table-striped table-bordered no-wrap w-100">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Feature Title</th>
                                            <th>Route</th>
                                            <th>Active</th>
                                            <th>Have Access</th>
                                            <th>Sequence</th>
                                            <th>Update</th>
                                        </tr>
                                    </thead>
                                    <tbody id="Feature_body">

                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-8 gears" id="dispatch_settings" style="display:none">
            <div class="card">
                <div class="card-body">

                    <h4 class="card-title mb-3">Customer Settings</h4>

                    <ul class="nav nav-pills bg-nav-pills nav-justified mb-3">
                        <li class="nav-item">
                            <a href="#home1" data-toggle="tab" aria-expanded="false" class="nav-link rounded-0 active">
                                <i class="mdi mdi-home-variant d-lg-none d-block mr-1"></i>
                                <span class="d-none d-lg-block">General Settings</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="#dispatch_status" data-toggle="tab" aria-expanded="false" class="nav-link rounded-0 ">
                                <i class="mdi mdi-home-variant d-lg-none d-block mr-1"></i>
                                <span class="d-none d-lg-block">Customization</span>
                            </a>
                        </li>
                        

                    </ul>

                    <div class="tab-content">
                        <div class="tab-pane active" id="home1">

                        </div>


                        <div class="tab-pane show" id="dispatch_status">
                            <div id="accordion" class="custom-accordion mb-4">
                                <div class="card mb-0">
                                    <div class="card-header" id="dispatch_status_collapse">
                                        <h5 class="m-0">
                                            <a class="custom-accordion-title d-flex align-items-center pt-2 pb-2 collapsed"
                                                data-toggle="collapse" href="#collapseStatusDispatch" aria-expanded="false"
                                                aria-controls="collapseThree">
                                                Dispatch Status <span class="ml-auto"><i
                                                        class="mdi mdi-chevron-down accordion-arrow"></i></span>
                                            </a>
                                        </h5>
                                    </div>
                                    <div id="collapseStatusDispatch" class="collapse" aria-labelledby="dispatch_status_collapse"
                                        data-parent="#accordion" style="">
                                        <div class="card-body">
                                            <div class="widget-header widget-header-small">
                                                <div class="row">
                                                    <div class="col-md-8 col-sm-6">
                                                        <h4 class="widget-title lighter smaller menu_title">Dispatch Status Table
                                                        </h4>
                                                    </div>
                                                    <div class="col-md-4 col-sm-6">
                                                        <button class="btn waves-effect waves-light btn-success"
                                                            onclick="showDispatchStatusModel()" style="float:right">
                                                            <i class="mdi mdi-plus-circle" style="padding-right:3px;"></i>&nbsp;Add Status</button>
                                                    </div>
                                                </div>
                                                <br>
                                                <span class="loader_lesson_plan_form"></span>
                                            </div>
                                            <div class="widget-body">
                                                <div class="widget-main">
                                                    <div class="row">
                                                        <div class="col-sm-12">
                                                            <table id="dispatch-status-list"
                                                                class="display table-striped table-bordered dispatch-status-list"
                                                                style="width:100%">
                                                                <thead>
                                                                    <tr>
                                                                        <th>#</th>
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



                        <div class="tab-pane" id="settings1">

                        </div>
                    </div>

                </div>
            </div>
        </div> 

        <div class="col-md-8 gears" id="project_settings" style="display:none">
            <div class="card">
                <div class="card-body">

                    <h4 class="card-title mb-3">Customer Settings</h4>

                    <ul class="nav nav-pills bg-nav-pills nav-justified mb-3">
                        <li class="nav-item">
                            <a href="#genSettings" data-toggle="tab" aria-expanded="false" class="nav-link rounded-0 active">
                                <i class="mdi mdi-home-variant d-lg-none d-block mr-1"></i>
                                <span class="d-none d-lg-block">General Settings</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="#project_type" data-toggle="tab" aria-expanded="false" class="nav-link rounded-0 ">
                                <i class="mdi mdi-home-variant d-lg-none d-block mr-1"></i>
                                <span class="d-none d-lg-block">Customization</span>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a href="#notificationType" data-toggle="tab" aria-expanded="false" class="nav-link rounded-0 ">
                                <i class="mdi mdi-home-variant d-lg-none d-block mr-1"></i>
                                <span class="d-none d-lg-block">Notification</span>
                            </a>
                        </li>
                        

                    </ul>

                    <div class="tab-content">

                        <div class="tab-pane active" id="genSettings">

                                <!-- <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox"  id="dailyRecap">
                                            <label class="form-check-label bold" for="dailyRecap">Daily progress recap on email </label>
                                        </div>
                                    </div>
                                    <div class="col-md-12 p-3 pl-4 dailyRecap" >
                                        <div class="form-group">
                                            <textarea class="form-control" rows="3" id=""
                                                name="" placeholder=" This is the true paragraph given by true person"></textarea>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox"  id="weeklyRecap">
                                            <label class="form-check-label bold" for="weeklyRecap">Weekly progress recap on email </label>
                                        </div>
                                    </div>
                                    <div class="col-md-12 p-3 pl-4 weeklyRecap">
                                        <div class="form-group">
                                            <textarea class="form-control" rows="3" id=""
                                                name="" placeholder=" This is the true paragraph given by true person"></textarea>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox"  id="monthlyRecap">
                                            <label class="form-check-label bold" for="monthlyRecap">Monthly progress recap on email </label>
                                        </div>
                                    </div>
                                    <div class="col-md-12 p-3 pl-4 monthlyRecap">
                                        <div class="form-group">
                                            <textarea class="form-control" rows="3" id=""
                                                name="" placeholder=" This is the true paragraph given by true person"></textarea>
                                        </div>
                                    </div>
                                </div> -->
                               
                        </div>

                        <div class="tab-pane show" id="project_type">
                            <div id="accordion" class="custom-accordion mb-4">
                                <div class="card mb-0">
                                    <div class="card-header" id="project_type_collapse">
                                        <h5 class="m-0">
                                            <a class="custom-accordion-title d-flex align-items-center pt-2 pb-2 collapsed"
                                                data-toggle="collapse" href="#collapseTypeProject" aria-expanded="false"
                                                aria-controls="collapseThree">
                                                Task Type <span class="ml-auto"><i
                                                        class="mdi mdi-chevron-down accordion-arrow"></i></span>
                                            </a>
                                        </h5>
                                    </div>
                                    <div id="collapseTypeProject" class="collapse" aria-labelledby="project_task_type_collapse"
                                        data-parent="#accordion" style="">
                                        <div class="card-body">
                                            <div class="widget-header widget-header-small">
                                                <div class="row">
                                                    <div class="col-md-8 col-sm-6">
                                                        <h4 class="widget-title lighter smaller menu_title">Project Task Type Table
                                                        </h4>
                                                    </div>
                                                    <div class="col-md-4 col-sm-6">
                                                        <button class="btn waves-effect waves-light btn-success"
                                                            onclick="showProjectTypeModel()" style="float:right">
                                                            <i class="mdi mdi-plus-circle" style="padding-right:3px;"></i>&nbsp;Add Task Type</button>
                                                    </div>
                                                </div>
                                                <br>
                                                <span class="loader_lesson_plan_form"></span>
                                            </div>
                                            <div class="widget-body">
                                                <div class="widget-main">
                                                    <div class="row">
                                                        <div class="col-sm-12">
                                                            <table id="project-type-list"
                                                                class="display table-striped table-bordered project-type-list"
                                                                style="width:100%">
                                                                <thead>
                                                                    <tr>
                                                                        <th>#</th>
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

                        <div class="tab-pane" id="notificationType">
                            <div class="row">
                                <form id="email_recap_notification_form" method="post" action="{{url('save_email_recap_noti')}}">
                                    <div class="col-md-12">
                                        <div class="row">
                                            <div class="col-md-6 ">
                                                <p>Allow Email Recap Notifications?</p>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <div class="row">
                                                        <div class="form-check ml-3">
                                                        
                                                            <input class="form-check-input" type="radio" name="recapNoti" id="recapNoti1" checked="">
                                                        
                                                            <label class="form-check-label" for="recapNoti1"> Yes </label>
                                                        </div>
                                                        <div class="form-check ml-3">
                                                            @if($sys_setting != null && $sys_setting[0] != null)
                                                                <input class="form-check-input " type="radio" name="recapNoti" id="recapNoti2" {{$sys_setting[0]['sys_value'] == 'no' ? 'checked' : ' '}}>
                                                            @endif
                                                            <label class="form-check-label" for="recapNoti2"> No </label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        
                                    </div>

                                    @if($sys_setting != null && $sys_setting[0] != null)
                                        @if($sys_setting[0]['sys_value'] == 'yes')
                                            <div class="col-md-12 recapNotiDiv">
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <p>Post the email you prefer all reports go to. Example systemreport@mycompanyname.com </p>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <input type="text" id="tag_emails" value="{{$sys_setting[1]['sys_value']}}" class="form-control" placeholder="Email"  data-role="tagsinput">
                                                            <small class="badge badge-default badge-danger form-text text-white">Note</small><small style="padding-left: 6px;padding-top: 3px;">Press Enter for next email</small>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <p>Check off the Emails you with to recieve</p>
                                                    </div>

                                                    @php 
                                                        $check_off_emails = json_encode($sys_setting[2]['sys_value']);
                                                    @endphp
                                                    <div class="col-md-6">
                                                        <div class="form-check form-check-inline">
                                                            <input class="form-check-input" {{strpos($check_off_emails ,'daily') ? 'checked' : ''}} type="checkbox" id="dailyDetails">
                                                            <label class="form-check-label bold" for="dailyDetails">Daily</label>
                                                        </div>
                                                        <div class="form-check form-check-inline">
                                                            <input class="form-check-input" {{strpos($check_off_emails ,'weekly') ? 'checked' : ''}} type="checkbox" id="weeklyDetails">
                                                            <label class="form-check-label bold" for="weeklyDetails">Weekly</label>
                                                        </div>
                                                        <div class="form-check form-check-inline">
                                                            <input class="form-check-input" {{strpos($check_off_emails ,'monthly') ? 'checked' : ''}} type="checkbox" id="monthlyDetails">
                                                            <label class="form-check-label bold" for="monthlyDetails">Monthly</label>
                                                        </div>
                                                        <div class="form-check form-check-inline">
                                                            <input class="form-check-input" {{strpos($check_off_emails ,'yearly') ? 'checked' : ''}} type="checkbox" id="yearlyDetails">
                                                            <label class="form-check-label bold" for="yearlyDetails">Yearly</label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @else
                                            <div class="col-md-12 recapNotiDiv" style="display:none">
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <p>Post the email you prefer all reports go to. Example systemreport@mycompanyname.com </p>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <input type="text" id="tag_emails" class="form-control" placeholder="Email"  data-role="tagsinput">
                                                            <small class="badge badge-default badge-danger form-text text-white">Note</small><small style="padding-left: 6px;padding-top: 3px;">Press Enter for next email</small>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <p>Check off the Emails you with to recieve</p>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-check form-check-inline">
                                                            <input class="form-check-input" type="checkbox" id="dailyDetails">
                                                            <label class="form-check-label bold" for="dailyDetails">Daily</label>
                                                        </div>
                                                        <div class="form-check form-check-inline">
                                                            <input class="form-check-input" type="checkbox" id="weeklyDetails">
                                                            <label class="form-check-label bold" for="weeklyDetails">Weekly</label>
                                                        </div>
                                                        <div class="form-check form-check-inline">
                                                            <input class="form-check-input" type="checkbox" id="monthlyDetails">
                                                            <label class="form-check-label bold" for="monthlyDetails">Monthly</label>
                                                        </div>
                                                        <div class="form-check form-check-inline">
                                                            <input class="form-check-input" type="checkbox" id="yearlyDetails">
                                                            <label class="form-check-label bold" for="yearlyDetails">Yearly</label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endif
                                    @endif

                                    <button class="btn btn-success btn-sm rounded ml-2"> <i class="fas fa-check-circle"></i> Save</button>

                                </form>

                                <div class="col-md-12 mt-3">
                                    <form action="">
                                        <p>On Demand Recap</p>

                                        <div class="row">
                                            <div class="col-md-5">
                                                <select class="form-control" id="recap_dropdown">
                                                    <option>Choose Your Option</option>
                                                    <option value="daily">Daily</option>
                                                    <option value="weekly">Weekly</option>
                                                    <option value="monthly">Monthly </option>
                                                    <option value="yearly">Yearly</option>
                                                </select>
                                            </div>
                                            <div class="col-md-5">
                                                <div class="form-group">
                                                    <input class="form-control" id="recap_emails" type="email"  data-role="tagsinput"  placeholder="Emails">
                                                </div>
                                            </div>
                                            <div class="col-md-2">
                                                <button class="btn btn-success" onclick="sendOnDemandRecap()" type="button">Send</button>
                                            </div>
                                        </div>
                                    </form>

                                    <p>your Next Recap will sent on < DATESTAMP > </p>
                                    <p>Having issue with your browser/computer displaying onscreen notification?</p>
                                    <p class="text-right"><a href="#" onclick="showNotificationPopup()">Click Here to request again!</a></p>
                                </div>
                                
                            </div>

                            <hr>
                            <div class="noti_email">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox"  id="dailyRecap">
                                            <label class="form-check-label bold" for="dailyRecap">Daily progress recap on email </label>
                                        </div>
                                    </div>
                                    <div class="col-md-12 p-3 pl-4 dailyRecap" >
                                        <div class="form-group">
                                            <textarea class="form-control" rows="3" id=""
                                                name="" placeholder=" This is the true paragraph given by true person"></textarea>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox"  id="weeklyRecap">
                                            <label class="form-check-label bold" for="weeklyRecap">Weekly progress recap on email </label>
                                        </div>
                                    </div>
                                    <div class="col-md-12 p-3 pl-4 weeklyRecap">
                                        <div class="form-group">
                                            <textarea class="form-control" rows="3" id=""
                                                name="" placeholder=" This is the true paragraph given by true person"></textarea>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox"  id="monthlyRecap">
                                            <label class="form-check-label bold" for="monthlyRecap">Monthly progress recap on email </label>
                                        </div>
                                    </div>
                                    <div class="col-md-12 p-3 pl-4 monthlyRecap">
                                        <div class="form-group">
                                            <textarea class="form-control" rows="3" id=""
                                                name="" placeholder=" This is the true paragraph given by true person"></textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-8 gears" id="payroll_settings" style="display:none">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title mb-3">Payroll Settings</h4>

                    <div class="row">
                        <form class="col-12" id="save_payroll_settings" action="{{asset('save_payroll_settings')}}" method="post">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="security">General Note For Staff :</label>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <input type="text" class="form-control" name="general_staff_note" value="{{$general_staff_note}}" required />
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="security">Note For Selected Staff :</label>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <input type="text" class="form-control" name="note_for_selected_staff" value="{{$note_for_selected_staff}}" />
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="security">Selected Staff Members :</label>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <select class="select2 form-control select2-hidden-accessible" id="selected_staff_members" style="width: 100%;" multiple>
                                            @foreach ($staff_list as $user)
                                                <option value="{{$user->id}}" {{in_array($user->id, $selected_staff_members) ? 'selected' : ''}}>{{$user->name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-12 text-right">
                                    <button class="btn btn-primary" type="submit" onsubmit="return false;">Save</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Ticket Department Modal -->
    <div id="save-department" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true" data-backdrop="static">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-info p-3">
                    <h2 id="dept" style="color:#fff; margin-bottom:0;">Add Department</h2>
                    <a type="button" class="close text-white" data-dismiss="modal" aria-label="Close" >
                        <span aria-hidden="true">&times;</span>
                    </a>
                </div>
                <div class="modal-body">
                    <form class="widget-box widget-color-dark user-form" id="save_department" action="{{asset('save-department')}}" method="post">
                        <div class="form-group">
                            <label for="departmrnt" class="d-flex mb-2 align-items-center">
                                <span>Department Name</span>
                                <div class="ml-auto">
                                    <input type="checkbox" checked data-on-color="warning" data-off-color="danger" data-on-text="Enabled" data-off-text="Disabled" id="dept_is_enabled">
                                </div>
                            </label>
                            <input class="form-control" type="text" name="name" id="dep_name" placeholder="" required>
                            <input class="form-control" type="text" name="dep_id" id="dep_id" hidden>
                        </div>
                        <div class="form-group text-center">
                            <button type="Submit" class="btn btn-rounded btn-success">Save</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Ticket Status Modal -->
    <div id="save-status" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true" data-backdrop="static">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-info p-3">
                    <h2 id="stat" style="color:#fff;"></h2>
                    <a type="button" class="close text-white" data-dismiss="modal" aria-label="Close" >
                        <span aria-hidden="true">&times;</span>
                    </a>
                </div>
                <div class="modal-body">
                    <form class="widget-box widget-color-dark user-form" id="save_status"
                        action="{{asset('save-status')}}" method="post">
                        <div class="form-group">
                            <label for="departmrnt">Status Title</label>
                            <input class="form-control" type="text" name="name" id="status_name" placeholder="" required>
                            <input class="form-control" type="text" name="status_id" id="status_id" hidden>
                        </div>

                        <div class="form-group selectpickertag">
                            <label>Select Department</label>
                            <select class="select2 form-control" id="department_id2"
                                multiple="multiple" style="height: 36px;width: 100%;" required>
                            </select>

                        </div>

                        <div class="form-group">
                            <label for="departmrnt">Priority Color</label>
                            <input class="form-control" type="color" name="status_color" id="status_color" placeholder="Priority Color">
                        </div>

                        <div class="form-group text-center">
                            <button type="submit" class="btn btn-rounded btn-primary">Save</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Ticket Type Modal -->
    <div id="save-type" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true" data-backdrop="static">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-info p-3">
                    <h2 id="typeh2" style="color:#fff;">Add Type</h2>
                    <a type="button" class="close text-white" data-dismiss="modal" aria-label="Close" >
                        <span aria-hidden="true">&times;</span>
                    </a>
                </div>
                <div class="modal-body">

                    <form class="widget-box widget-color-dark user-form" id="save_ticket"
                        action="{{asset('save-type')}}" method="post">
                        <div class="form-group">
                            <label for="departmrnt">Type Title</label>
                            <input class="form-control" type="text" name="name" id="type_name" placeholder="" required>
                            <input class="form-control" type="text" name="type_id" id="type_id" hidden>
                        </div>
                        <div class="form-group selectpickertag">
                            <label class="control-label">Select Department</label>

                            <select class="select2 form-control" id="department_id1" name="department_id"
                                multiple="multiple" style="height: 36px;width: 100%;" required>
                            </select>


                        </div>
                        <div class="form-group text-center">
                            <button type="submit" class="btn btn-rounded btn-success">Save</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Ticket Priority Modal -->
    <div id="save-priority" class="modal fade" tabindex="-1" role="dialog" data-backdrop="static" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-info p-3">
                    <h2 id="prior" style="color:#fff;">Add Priority</h2>
                    <a type="button" class="close text-white" data-dismiss="modal" aria-label="Close" >
                        <span aria-hidden="true">&times;</span>
                    </a>
                </div>
                <div class="modal-body">
                    <form class="widget-box widget-color-dark" id="save_priority" action="{{asset('save-priority')}}"
                        method="post">
                        <div class="form-group">
                            <label for="departmrnt">Priority Title</label>
                            <input class="form-control" type="text" name="name" id="priority_name" placeholder="" required>
                            <input class="form-control" type="text" name="priority_id" id="priority_id" hidden>
                        </div>

                        <div class="form-group selectpickertag">
                            <label class="">Select Department</label>

                            <select class="select2 form-control" id="department_id" name="department_id"
                                multiple="multiple" style="height: 36px;width: 100%;" required>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="departmrnt">Priority Color</label>
                            <input class="form-control" type="color" name="priority_color" id="priority_color" placeholder="Priority Color">
                        </div>

                        <div class="form-group text-center">
                            <button class="btn btn-rounded btn-success" type="submit">Save</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Ticket POP3 Mail Modal -->
    <div id="save-mail" class="modal fade" role="dialog" data-backdrop="static" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-info p-3">
                    <h2 style="color:#fff;" id="modalheader">Add New Mail</h2>
                    <a type="button" class="close text-white" data-dismiss="modal" aria-label="Close" >
                        <span aria-hidden="true">&times;</span>
                    </a>
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
                                    <input type="checkbox" class="custom-control-input" id="php_mailer">
                                    <label class="custom-control-label" for="php_mailer">User PHP Mailer </label>
                                </div>

                                <div class="custom-control custom-switch">
                                    <input type="checkbox" class="custom-control-input" id="is_enabled">
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
                                    <input type="checkbox" class="custom-control-input" id="registration_required">
                                    <label class="custom-control-label" for="registration_required">Registration Required</label>
                                </div>
                            </div>
                            <div class="col-md-6 form-group">
                                <div class="custom-control custom-switch">
                                    <input type="checkbox" class="custom-control-input" id="email_outbound">
                                    <label class="custom-control-label" for="email_outbound">Email Outbound</label>
                                </div>
                            </div>
                            <div class="col-md-6 form-group">
                                <div class="custom-control custom-switch">
                                    <input type="checkbox" class="custom-control-input" id="autosend_ticket">
                                    <label class="custom-control-label" for="autosend_ticket">Do not send new ticket autoresponder</label>
                                </div>
                            </div>
                            <div class="col-md-6 form-group">
                                <div class="custom-control custom-switch">
                                    <input type="checkbox" class="custom-control-input" id="is_dept_default">
                                    <label class="custom-control-label" for="is_dept_default">Set as default</label>
                                </div>
                            </div>
                        </div>
                    </form>
                    <div class="form-group text-right">
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
                <div class="modal-header bg-info p-3">                    
                    <h2 style="color:#fff;" id="modalheader">Edit Mail</h2>
                    <a type="button" class="close text-white" data-dismiss="modal" aria-label="Close" >
                        <span aria-hidden="true">&times;</span>
                    </a>
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
                                    <input type="checkbox" class="custom-control-input" id="edit_php_mailer">
                                    <label class="custom-control-label" for="edit_php_mailer">User PHP Mailer </label>
                                </div>

                                <div class="custom-control custom-switch">
                                    <input type="checkbox" class="custom-control-input" id="edit_is_enabled">
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
                                    <input type="checkbox" class="custom-control-input" id="edit_reg">
                                    <label class="custom-control-label" for="edit_reg">Registration Required</label>
                                </div>
                            </div>
                            <div class="col-md-6 form-group">
                                <div class="custom-control custom-switch">
                                    <input type="checkbox" class="custom-control-input" id="edit_outbound_ticket">
                                    <label class="custom-control-label" for="edit_outbound_ticket">Email Outbound</label>
                                </div>
                            </div>
                            <div class="col-md-6 form-group">
                                <div class="custom-control custom-switch">
                                    <input type="checkbox" class="custom-control-input" id="edit_autosend_ticket">
                                    <label class="custom-control-label" for="edit_autosend_ticket">Send new ticket autoresponder</label>
                                </div>
                            </div>
                            <div class="col-md-6 form-group">
                                <div class="custom-control custom-switch">
                                    <input type="checkbox" class="custom-control-input" id="edit_is_dept_default">
                                    <label class="custom-control-label" for="edit_is_dept_default">Set as default</label>
                                </div>
                            </div>
                        </div>
                    </form>
                    <div class="form-group text-right">
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

    <!-- Customer Type Modal -->
    <div id="save-customer-type" class="modal fade" tabindex="-1" role="dialog" data-backdrop="static" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                    <div class="modal-header bg-info p-3">                    
                        <h2 id="customer_typeh2" style="color:#fff;">Add Type</h2>
                        <a type="button" class="close text-white" data-dismiss="modal" aria-label="Close" >
                            <span aria-hidden="true">&times;</span>
                        </a>
                    </div>
                <div class="modal-body">

                    <form class="widget-box widget-color-dark user-form" id="save_customer_ticket"
                        action="{{asset('save-customer-type')}}" method="post">
                        <div class="form-group">
                            <label for="departmrnt">Type Title</label>
                            <input class="form-control" type="text" name="name" id="customer_type_name" placeholder="">
                            <input class="form-control" type="text" name="customer_type_id" id="customer_type_id" hidden>
                        </div>
                        <div class="form-group text-center">
                            <button type="submit" class="btn btn-rounded btn-success">Save</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Dispatch Status Modal -->
    <div id="save-dispatch-status" class="modal fade" tabindex="-1" role="dialog" data-backdrop="static" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                    <div class="modal-header bg-info p-3">                    
                        <h2 id="dispatch_statush2" style="color:#fff;">Add Status</h2>
                        <a type="button" class="close text-white" data-dismiss="modal" aria-label="Close" >
                            <span aria-hidden="true">&times;</span>
                        </a>
                    </div>
                <div class="modal-body">

                    <form class="widget-box widget-color-dark user-form" id="save_dispatch_status"
                        action="{{asset('save-dispatch-status')}}" method="post">
                        <div class="form-group">
                            <label for="departmrnt">Type Status</label>
                            <input class="form-control" type="text" name="name" id="dispatch_status_name" placeholder="">
                            <input class="form-control" type="text" name="dispatch_status_id" id="dispatch_status_id" hidden>
                        </div>
                        <div class="form-group text-center">
                            <button type="submit" class="btn btn-rounded btn-success">Save</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Project Type Modal -->
    <div id="save-project-type" class="modal fade" tabindex="-1" role="dialog" data-backdrop="static" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                    <div class="modal-header bg-info p-3">                    
                        <h2 id="project_typeh2" style="color:#fff;">Add Project Task Type</h2>
                        <a type="button" class="close text-white" data-dismiss="modal" aria-label="Close" >
                            <span aria-hidden="true">&times;</span>
                        </a>
                    </div>
                <div class="modal-body">

                    <form class="widget-box widget-color-dark user-form" id="save_project_type"
                        action="{{asset('save-project-type')}}" method="post">
                        <div class="form-group">
                            <label for="departmrnt">Type Task Type</label>
                            <input class="form-control" type="text" name="name" id="project_type_name" placeholder="">
                            <input class="form-control" type="text" name="project_type_id" id="project_type_id" hidden>
                        </div>
                        <div class="form-group text-center">
                            <button type="submit" class="btn btn-rounded btn-success">Save</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- add SLA Modal -->
    <div id="save-SLA-plan" class="modal fade" tabindex="-1" role="dialog"  data-backdrop="static" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                    <div class="modal-header bg-info p-3">
                        <h2 id="project_typeh2" style="color:#fff; margin-bottom:0;">Add SLA Plan</h2>
                        <a type="button" class="close text-white" data-dismiss="modal" aria-label="Close" >
                            <span aria-hidden="true">&times;</span>
                        </a>
                    </div>
                <div class="modal-body">
                    <form method="POST" id="sla_form" action="{{url('add_sla')}}" enctype="multipart/form-data" onsubmit="return false;">
                        <div class="form-group">
                            <label for="departmrnt">SLA Plan Title</label>
                            <input class="form-control" type="text" name="title" placeholder="" required>
                            <small class="text-right"> For example, Support tickets standard SLA plan.</small>
                            <input class="form-control" type="text" name="" id="" hidden>
                        </div>
                        <div class="form-group">
                            <label for="departmrnt">Reply Deadline</label>
                            <input class="form-control" min="1" max="12" type="number" name="reply_deadline" placeholder="" id="rep-deadline" required>
                            <small class="text-right"> The number of hours by which a ticket should be replied to (following a reply from an end user). Please type the number of hours and minutes separated by a decimal point (i.e 1.30 becomes 1 hour and 30 minutes)</small>
                        </div>
                        <div class="form-group">
                            <label for="departmrnt">Resolution due Deadline</label>
                            <input class="form-control" min="1" max="12" type="number" name="due_deadline" placeholder="" id="due-deadline" required>
                            <small>The number of hours by which tickets which have been assigned this SLA plan should be resolved (set to a resolved type status). Please type the number of hours and minutes separated by a decimal point (i.e 1.30 becomes 1 hour and 30 minutes)</small>
                        </div>
                        <div class="form-group">
                            <div class="row ml-2">
                                <div class="custom-control custom-radio">
                                    <input type="radio" id="customRadio1" name="customRadio" class="custom-control-input">
                                    <label class="custom-control-label" for="customRadio1">Activate</label>
                                </div>
                                <div class="custom-control custom-radio ml-2">
                                    <input type="radio" id="customRadio2" name="customRadio" class="custom-control-input">
                                    <label class="custom-control-label" for="customRadio2">Deactivate</label>
                                </div>
                            </div>
                            <small>Whether or not this SLA plan is enabled.</small>
                        
                        </div>
                        <!-- <div class="form-group">
                            <div class="custom-control custom-checkbox ml-2">
                                <input type="checkbox" id="custumCheck" name="custumCheck" class="custom-control-input">
                                <label class="custom-control-label" for="custumCheck">Is Default ?</label>
                            </div>
                            <small class="text-danger" id="war_check">Previous default plan if any will be set to normal and this one set to default plan.</small>

                        </div> -->
                        <div class="form-group">
                            <button type="submit" class="btn btn-rounded btn-success">Save</button>
                            <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>


    <!-- add Category Modal -->
    <div id="save-category-modal" class="modal fade" tabindex="-1" role="dialog"  data-backdrop="static" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                    <div class="modal-header bg-info p-3">
                        <h2 id="project_typeh2" style="color:#fff; margin-bottom:0;">Add Category Plan</h2>
                        <a type="button" class="close text-white" data-dismiss="modal" aria-label="Close" >
                            <span aria-hidden="true">&times;</span>
                        </a>
                    </div>
                <div class="modal-body">
                    <form method="POST" id="cat_form" action="{{url('add_cat')}}" enctype="multipart/form-data" onsubmit="return false;">
                        <div class="form-group">
                            <label for="departmrnt">New Category Name</label>
                            <input class="form-control" type="text" name="name" placeholder="" required>
                        </div>
                        <div class="form-group">
                            <button type="submit" class="btn  btn-success">Save</button>
                            <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

     <!-- edit Category Modal -->
    <div id="edit-category-modal" class="modal fade" tabindex="-1" role="dialog"  data-backdrop="static" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                    <div class="modal-header bg-info p-3">
                        <h2 id="project_typeh2" style="color:#fff; margin-bottom:0;">Update Category Name</h2>
                        <a type="button" class="close text-white" data-dismiss="modal" aria-label="Close" >
                            <span aria-hidden="true">&times;</span>
                        </a>
                    </div>
                <div class="modal-body">
                    <form method="POST" id="edit_cat_form" action="{{url('update_cat_response')}}" enctype="multipart/form-data" onsubmit="return false;">
                        <div class="form-group">
                        <input type="hidden" id="cat_id2" name="id">

                            <label for="departmrnt">New Category Name</label>
                            <input class="form-control" type="text" id="cat_name2" name="name" placeholder="" required>
                        </div>
                        <div class="form-group">
                            <button type="submit" class="btn  btn-success">Save</button>
                            <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- edit SLA Modal -->
    <div id="edit-SLA-plan" class="modal fade" tabindex="-1" role="dialog"  data-backdrop="static" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                    <div class="modal-header bg-info p-3">
                        <h2 id="project_typeh2" style="color:#fff; margin-bottom:0;">Add SLA Plan</h2>
                        <a type="button" class="close text-white" data-dismiss="modal" aria-label="Close" >
                            <span aria-hidden="true">&times;</span>
                        </a>
                    </div>
                <div class="modal-body">
                    <form method="POST" id="edit_sla_form" action="{{url('update_sla')}}" enctype="multipart/form-data" onsubmit="return false;">
                        <div class="form-group">
                            <input type="hidden" id="sla_id" name="id">
                            <label for="departmrnt">SLA Plan Title</label>
                            <input class="form-control" type="text" name="title" id="reply_title" placeholder="">
                            <small class="text-right"> For example, Support tickets standard SLA plan.</small>
                            <input class="form-control" type="text" name="" id="" hidden>
                        </div>
                        <div class="form-group">
                            <label for="departmrnt">Reply Deadline</label>
                            <input class="form-control" min="1" max="12" type="number" name="reply_deadline" id="reply_deadline" placeholder="">
                            <small class="text-right"> The number of hours by which a ticket should be replied to (following a reply from an end user). Please type the number of hours and minutes separated by a decimal point (i.e 1.30 becomes 1 hour and 30 minutes)</small>
                        </div>
                        <div class="form-group">
                            <label for="departmrnt">Resolution due Deadline</label>
                            <input class="form-control" min="1" max="12" type="number" name="due_deadline" id="due_deadline" placeholder="">
                            <small>The number of hours by which tickets which have been assigned this SLA plan should be resolved (set to a resolved type status). Please type the number of hours and minutes separated by a decimal point (i.e 1.30 becomes 1 hour and 30 minutes)</small>
                        </div>
                        <div class="form-group">
                            <div class="row ml-2">
                                <div class="custom-control custom-radio">
                                    <input type="radio" id="edit_customRadio1" name="customRadio" class="custom-control-input">
                                    <label class="custom-control-label" for="edit_customRadio1">Activate</label>
                                </div>
                                <div class="custom-control custom-radio ml-2">
                                    <input type="radio" id="edit_customRadio2" name="customRadio" class="custom-control-input">
                                    <label class="custom-control-label" for="edit_customRadio2">Deactivate</label>
                                </div>
                            </div>
                            <small>Whether or not this SLA plan is enabled.</small>
                        </div>
                        <!-- <div class="form-group">
                            <div class="custom-control custom-checkbox ml-2">
                                <input type="checkbox" id="edit_custumCheck" name="edit_custumCheck" class="custom-control-input">
                                <label class="custom-control-label" for="edit_custumCheck">Is Default ?</label>
                            </div>
                            <small class="text-danger" id="war_check_edit">Previous default plan if any will be set to normal and this one set to default plan.</small>
                        </div> -->
                        <div class="form-group">
                            <button type="submit" class="btn btn-rounded btn-success">Save</button>
                            <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Add IP Modal -->
    <div id="Add-IP" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true" data-backdrop="static">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-info p-3">
                    <h2 id="typeh2" style="color:#fff;">Add IP Address</h2>
                    <a type="button" class="close text-white" data-dismiss="modal" aria-label="Close" >
                        <span aria-hidden="true">&times;</span>
                    </a>
                </div>
                <div class="modal-body">
                    <form class="widget-box widget-color-dark user-form" id=""
                        action="">
                        <div class="form-group">
                            <label for="">Ip Address Here</label>
                            <input type="ipv4" id="ipv4" class="form-control" name="" placeholder="" required="">
                        </div>
                        <div class="form-group text-center">
                            <button type="submit" class="btn btn-rounded btn-success">Save</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- ==================================
        feature list 
    -->
    <!-- feature add feature list -->
    <div class="modal fade" id="addFeatureModal" tabindex="-1" role="dialog" data-backdrop="static">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header d-flex align-items-center">
                    <h5 class="modal-title">Add Feature</h5>
                    <button type="button" class="close ml-auto" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="add-contact-box">
                        <div class="add-contact-content">
                            <form id="addFeatureForm">
                                <div class="form-group">
                                    <div class="row">
                                        <div class="form-check ml-3">
                                            <input class="form-check-input" type="radio" name="flexRadioDefault" id="flexRadioDefault1" checked>
                                            <label class="form-check-label" for="flexRadioDefault1"> Menu </label>
                                        </div>
                                        <div class="form-check ml-3">
                                            <input class="form-check-input" type="radio" name="flexRadioDefault" id="flexRadioDefault2">
                                            <label class="form-check-label" for="flexRadioDefault2"> Toggle Menu </label>
                                        </div>
                                        <div class="form-check ml-3">
                                            <input class="form-check-input" type="radio" name="flexRadioDefault" id="flexRadioDefault3">
                                            <label class="form-check-label" for="flexRadioDefault3">widget/button</label>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group" id="menu-title">
                                    <input type="text" id="menu_title" class="form-control form-control-sm" placeholder="Menu Title">
                                    <span id="title_error" class="text-danger small"></span>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group" id="route-title">
                                            <input type="text" id="route" class="form-control form-control-sm" placeholder="Route">
                                            <span id="route_error" class="text-danger small"></span>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <input type="number" id="sequence" class="form-control form-control-sm" placeholder="Sequence">
                                            <span id="sequence_error" class="text-danger small"></span>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <input type="text" id="icon" class="form-control form-control-sm" placeholder="Menu Icon">
                                    <span id="icon_error" class="text-danger small"></span>
                                </div>

                                <div class="form-group">
                                    <label for="" class="small">Parent Menu</label>
                                    <select name="parent_id" class="form-control form-control-sm" id="parent_id">
                                    </select>
                                    <span id="parent_error" class="text-danger small"></span>
                                </div>

                                <div class="form-group">
                                    <label for="" class="small">Role</label>
                                    <select name="role" class="form-control select2" style="width:100%; height:44px" id="role" multiple="multiple">
                                        @foreach($roles as $role)
                                            <option value="{{$role->id}}">{{$role->name}}</option>
                                        @endforeach
                                    </select>
                                    <span id="parent_error" class="text-danger small"></span>
                                </div>
                                
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox"  name="is_active" id="is_active">
                                    <label class="form-check-label" for="is_active"> is Active </label>
                                </div>

                                <div class="form-group mt-3">
                                    <button type="submit" class="btn btn-success">Save</button>
                                    <!-- <button class="btn btn-danger" data-dismiss="modal"> Discard</button> -->
                                </div>

                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- edit feature list -->
    <div class="modal fade" id="editFeatureModal" tabindex="-1" role="dialog" data-backdrop="static">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header d-flex align-items-center">
                    <h5 class="modal-title">Update Feature</h5>
                    <button type="button" class="close ml-auto" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="loader_container" style="display:none">
                        <div class="loader"></div>
                    </div>
                    <div class="add-contact-box">
                        <div class="add-contact-content">
                            <form id="editFeatureForm">
                                <input type="hidden" id="f_id">
                                <div class="form-group">
                                    <div class="row">
                                        <div class="form-check ml-3">
                                            <input class="form-check-input" type="radio" name="flexRadioDefault" id="flexRadioDefault4" checked>
                                            <label class="form-check-label" for="flexRadioDefault4"> Menu </label>
                                        </div>
                                        <div class="form-check ml-3">
                                            <input class="form-check-input" type="radio" name="flexRadioDefault" id="flexRadioDefault5">
                                            <label class="form-check-label" for="flexRadioDefault5"> Toggle Menu </label>
                                        </div>
                                        <div class="form-check ml-3">
                                            <input class="form-check-input" type="radio" name="flexRadioDefault" id="flexRadioDefault6">
                                            <label class="form-check-label" for="flexRadioDefault6">widget/button</label>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group" id="menu-title">
                                    <input type="text" id="edit_title" class="form-control" placeholder="Menu Title">
                                    <span id="title_error" class="text-danger small"></span>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group" id="route-title">
                                            <input type="text" id="edit_route" class="form-control" placeholder="Route">
                                            <span id="route_error" class="text-danger small"></span>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <input type="number" id="edit_sequence" class="form-control" placeholder="Sequence">
                                            <span id="sequence_error" class="text-danger small"></span>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <input type="text" id="edit_icon" class="form-control" placeholder="Menu Icon">
                                    <span id="icon_error" class="text-danger small"></span>
                                </div>

                                <div class="form-group">
                                    <label for="">Parent Menu</label>
                                    <select required name="edit_parent_id" class="form-control" id="edit_parent_id">
                                    </select>
                                </div>

                                <div class="form-group">
                                    <label for="" class="small">Role</label>
                                    <select name="edit_role" class="form-control select2" style="width:100%; height:44px" id="edit_role" multiple="multiple">
                                        @foreach($roles as $role)
                                            <option value="{{$role->id}}">{{$role->name}}</option>
                                        @endforeach
                                    </select>
                                    <span id="parent_error" class="text-danger small"></span>
                                </div>

                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox"  name="edit_is_active" id="edit_is_active">
                                    <label class="form-check-label" for="edit_is_active"> is Active </label>
                                </div>

                                <div class="form-group mt-3">
                                    <button type="submit" class="btn btn-success">Save</button>
                                    <!-- <button class="btn btn-danger" data-dismiss="modal"> Discard</button> -->
                                </div>

                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>

@endsection
<style>
    .btn-list>a:hover {
        color: #009efb;
    }
</style>
@section('scripts')

    @include('js_files.system_manager.settings.indexJs')
    @include('js_files.system_manager.settings.settingsJs')
    @include('js_files.system_manager.feature_list.feature_listJs')

    <script src="https://unpkg.com/multiple-select@1.5.2/dist/multiple-select.min.js"></script>
    <!-- <script src="{{asset('assets/libs/nestable/jquery.nestable.js')}}"></script> -->
    <script src="{{asset( $path . 'js/jquery.nestable.js')}}"></script>
    <script src="{{asset( $path . 'js/jquery_asColor.min.js')}}"></script>
    <script src="{{asset( $path . 'js/jquery_asGradient.js')}}"></script>
    <script src="{{asset( $path . 'js/jquery_asColorPicker.min.js')}}"></script>
    <script src="{{asset( $path . 'js/jquery_minicolors.min.js')}}"></script>
    <script src="{{asset( $path . 'js/tinymce.min.js')}}"></script>
    
        <!-- This Page JS -->
    <!-- <script src="{{asset('assets/libs/jquery-asColor/dist/jquery-asColor.min.js')}}"></script> -->
    <!-- <script src="{{asset('assets/libs/jquery-asGradient/dist/jquery-asGradient.js')}}"></script> -->
    <!-- <script src="{{asset('assets/libs/jquery-asColorPicker/dist/jquery-asColorPicker.min.js')}}"></script> -->
    <!-- <script src="{{asset('assets/libs/@claviska/jquery-minicolors/jquery.minicolors.min.js')}}"></script> -->
    <!-- <script src="{{asset('/assets/libs/tinymce/tinymce.min.js')}}"></script> -->



@endsection