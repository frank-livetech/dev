@extends('layouts.master-layout-new')
@section('Help Desk','open')
@section('body')
<link rel="stylesheet" href="https://unpkg.com/multiple-select@1.5.2/dist/multiple-select.min.css">
<style>
    a:link, span.MsoHyperlink {
        mso-style-priority: 99;
        font-weight: inherit !important;
        text-decoration: none;
    }
.flagged-tr,
.flagged-tr .sorting_1 {
    background-color: #FFE4C4 !important;
}

.flagged-tr .fa-flag {
    color: red !important;
}

.pr-0 {
    padding-right: 0 !important;
}


table.dataTable .custom {
    padding-right: 230px !important;
}

table.dataTable .pr-ticket {
    min-width: 69px !important;
}

.pr-replies {
    min-width: 95px !important;
}

.pr-due {
    min-width: 125px !important;
}

.pr-activity {
    min-width: 97px !important;
    padding-right: 19px !important;
}

.pr-tech {
    min-width: 109px !important;
}

table.dataTable .custom-cst {
    padding-right: 37px !important;
}
table.dataTable th {
    padding: 0.2rem 1.5rem;
}
table.dataTable td {
    padding: 7px !important;
    font-size: 12px;
}

.select2-container .select2-selection--single .select2-selection__rendered {
    padding-right: 60px !important;
}

#dropD {
    padding-left: 15px;
}

.mt-0 {
    margin: unset
}

.badge-primary {
    background-color: #4eafcb
}

.card-body.drop-dpt {
    padding: 0 !important;
}

span.select2-container.select2-container--default.select2-container--open {
    top: 3.9844px !important
}

.badge-secondary {
    color: #fff;
    background-color: #868e96;
}

.media-body {
    width: 575px
}

.btn-outline-bt {
    border: 1px solid #e6e6e6;
    text-decoration: none;
    margin-left: 4px;
    color: #666;
    padding: 4px 13px 4px 13px;
    transition: all 0.25s ease;
    border-radius: 4px;
    background-color: #f4f5f5;
    vertical-align: top;
}

.mr-3 {
    margin-right: 1rem !important;
}

.media {
    display: flex;
    align-items: flex-start;
}

.innerBox {
    font-size: 15px;
    height: 100px;
    overflow-y: scroll;
}

#style-5::-webkit-scrollbar-track {
    -webkit-box-shadow: inset 0 0 6px rgba(0, 0, 0, 0.3);
    background-color: #F5F5F5;
}

.text-white {
    color: white
}

#style-5::-webkit-scrollbar {
    width: 3px;
    height: 10px;
    background-color: #F5F5F5;
}

#style-5::-webkit-scrollbar-thumb {
    background-color: #0ae;
    background-image: -webkit-gradient(linear, 0 0, 0 100%, color-stop(.5, rgba(255, 255, 255, .2)), color-stop(.5, transparent), to(transparent));
}

.fc-col-header,
.fc-scrollgrid-sync-table,
.fc-daygrid-body,
.fc-daygrid-body-unbalanced {
    width: 100% !important;
}

.cld-main svg {
    fill: #7367f0 !important;
}

.cld-datetime .today {
    font-size: 1.8rem !important;
    font-weight: bolder;
}
.cursor { cursor: pointer !important }
</style>
@php
$file_path = Session::get('is_live') == 1 ? 'public/' : '/';
@endphp
<div class="app-content content">
    <div class="content-overlay"></div>
    <div class="header-navbar-shadow"></div>
    <div class="content-wrapper p-0">
        <div class="content-header row">
            <div class="content-header-left col-md-12 col-12 mb-2">
                <div class="row breadcrumbs-top">
                    <div class="col-12">
                        <h2 class="content-header-title float-start mb-0">Ticket Manager</h2>
                        <div class="breadcrumb-wrapper">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{route('home')}}">Home</a> </li>
                                <li class="breadcrumb-item"><a href="javascript:location.reload()">Help Desk</a> </li>
                                <li class="breadcrumb-item active"><a href="javascript:location.reload()">Ticket Manager
                                    </a> </li>
                                @php
                                $url = url()->full();
                                $name = Str::after($url, 'ticket-manager');
                                @endphp
                                @if($name != '')
                                <li class="breadcrumb-item active"> <a
                                        href="javascript:location.reload()">{{$dept_name}} / {{$status_name}}</a> </li>
                                @section('title', 'Ticket Manager' . '/' . $dept_name . '/' . $status_name)
                                @else
                                @section('title', 'Ticket Manager')
                                @endif


                            </ol>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        @if($date_format)
        <input type="hidden" id="system_date_format" value="{{$date_format}}">
        @else
        <input type="hidden" id="system_date_format" value="DD-MM-YYYY">
        @endif

        <input type="hidden" id="usrtimeZone" value="{{Session::get('timezone')}}">

        <div class="content-body">
            <section id="statistics-card">
                <div class="row">
                    <!-- <div class="col-md-1 col-sm-1"></div> -->
                    <div class="col-md-2 col-sm-2">
                        <div class="card border-info">
                            <div class="card-body">
                                <input hidden id="dept" value="{{$dept}}">
                                <input hidden id="sts" value="{{$sts}}">
                                <div class="flex-row">
                                    <div class="my-auto" style="text-align: center">
                                        <a href="javascript:getCounterTickets('total')">
                                            <h2 class="fw-bolder mb-0" id="total_tickets_count"></h2>
                                            <h5 class="card-text font-small-10 mb-0 mt-1 text-info">All Tickets</h5>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-2 col-sm-2">
                        <div class="card border-success">
                            <div class="card-body">
                                <div class="flex-row">
                                    <div class="my-auto" style="text-align: center">
                                        <a href="javascript:getCounterTickets('self')">
                                            <h2 class="fw-bolder mb-0" id="my_tickets_count"></h2>
                                            <h5 class="card-text font-small-10 mb-0 mt-1 text-success">My Tickets</h5>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-2 col-sm-2 col-lg-2">
                        <div class="card border-primary">
                            <div class="card-body">
                                <div class="flex-row">
                                    <div class="my-auto" style="text-align: center">
                                        <a href="javascript:getCounterTickets('unassigned')">
                                            <h2 class="fw-bolder mb-0" id="unassigned_tickets_count"></h2>
                                            <h5 class="card-text font-small-10 mb-0 mt-1 text-primary">Unassigned</h5>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-2 col-sm-2 col-lg-2">
                        <div class="card border-danger">
                            <div class="card-body">
                                <div class="flex-row">
                                    <div class="my-auto" style="text-align: center">
                                        <a href="javascript:getCounterTickets('overdue')">
                                            <h2 class="fw-bolder mb-0" id="late_tickets_count"></h2>
                                            <h5 class="card-text font-small-10 mb-0 mt-1 text-danger">Overdue</h5>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-2 col-sm-2 col-lg-2">
                        <div class="card border-danger">
                            <div class="card-body">
                                <div class="flex-row">
                                    <div class="my-auto" style="text-align: center">
                                        <a href="javascript:getCounterTickets('flagged')">
                                            <h2 class="fw-bolder mb-0" id="flagged_tickets_count"></h2>
                                            <h5 class="card-text font-small-10 mb-0 mt-1 text-danger">Flagged</h5>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-2 col-sm-2 col-lg-2">
                        <div class="card border-success" onclick="listTickets('trash')">
                            <div class="card-body">
                                <div class="flex-row">
                                    <div class="my-auto" style="text-align: center">
                                        <a href="javascript:void(0)">
                                            <h2 class="fw-bolder mb-0" id="trashed_tickets_count"></h2>
                                            <h5 class="card-text font-small-10 mb-0 mt-1 text-success">Trash</h5>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
        </div>
        <div class="row show_tkt_btns" style="display:none;">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body drop-dpt " style="background-color:#b0bec5;border-radius:9px;">
                        <div class="row" id="dropD" style="margin-right:-5px;margin-bottom:0 !important;">
                            <div class="col-md-2 br-white" id="dep-label"
                                style="border-right: 1px solid white;padding: 12px;">
                                <label
                                    class="control-label col-sm-12 end_padding text-white"><strong>Department</strong></label>
                                <h5 class="end_padding mb-0 selected-label text-white"
                                    style="font-size: 0.87rem; !important" id="dep-h5"></h5>
                                <select class="select2 form-control  " id="dept_id" name="dept_id"
                                    style="width: 100%; height:36px;">
                                    <option value="nochange"> -- no change -- </option>
                                    @foreach($departments as $department)
                                    <option value="{{$department->id}}">{{$department->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-2 br-white" id="tech-label"
                                style="border-right: 1px solid white;padding: 12px;">
                                <label
                                    class="control-label col-sm-12 end_padding text-white "><strong>Owner</strong></label>
                                <h5 class="end_padding mb-0 selected-label text-white"
                                    style="font-size: 0.87rem; !important" id="tech-h5"></h5>
                                <select class="select2 form-control " id="assigned_to" name="assigned_to"
                                    style="width: 100%; height:36px;">
                                    <option value="nochange"> -- no change -- </option>
                                    <option value="">Unassigned</option>
                                    @foreach($users as $user)
                                    <option value="{{$user->id}}">{{$user->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-2 br-white" id="type-label"
                                style="border-right: 1px solid white;padding: 12px;">
                                <label
                                    class="control-label col-sm-12 end_padding text-white "><strong>Type</strong></label>
                                <h5 class="end_padding mb-0 selected-label text-white"
                                    style="font-size: 0.87rem; !important" id="type-h5"></h5>
                                <select class="select2 form-control " id="type" name="type"
                                    style="width: 100%; height:36px;">
                                    <option value="nochange"> -- no change -- </option>
                                    @foreach($types as $type)
                                    <option value="{{$type->id}}">{{$type->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-2 br-white" id="status-label"
                                style="border-right: 1px solid white;padding: 12px;">
                                <label
                                    class="control-label col-sm-12 end_padding text-white "><strong>Status</strong></label>
                                <h5 class="end_padding mb-0 selected-label text-white"
                                    style="font-size: 0.87rem; !important" id="status-h5"></h5>
                                <select class="select2 form-control " id="status" name="status"
                                    style="width: 100%; height:36px;">
                                    <option value="nochange"> -- no change -- </option>
                                    @foreach($statuses as $status)
                                    <option value="{{$status->id}}" data-color="{{$status->color}}">{{$status->name}}
                                    </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-2 br-white" id="prio-label"
                                style="border-right: 1px solid white; padding: 12px;">
                                <label
                                    class="control-label col-sm-12 end_padding text-white "><strong>Priority</strong></label>
                                <h5 class="end_padding mb-0 selected-label text-white"
                                    style="font-size: 0.87rem; !important" id="prio-h5"></h5>
                                <select class="select2 form-control priority_dropdown" id="priority" name="priority"
                                    style="width: 100%; height:36px;">
                                    <option value="nochange"> -- no change -- </option>
                                    @foreach($priorities as $priority)
                                    <option value="{{$priority->id}}" data-color="{{$priority->priority_color}}">
                                        {{$priority->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-2 chim text-center " style=";padding: 12px;">
                                <button class="btn btn-primary upBtn" onclick="updateTickets()"> Update</button>
                                <!-- <span style="cursor:pointer;" onclick="flagTicket(this, 33);" aria-hidden="true"></span> -->
                            </div>

                        </div>
                        <!-- <div clas="row" style="text-align:right">
                                    <div class="col-lg-12">
                                        <small>*All dropdown saved automatically on change</small>
                                    </div>    
                                </div>     -->

                    </div>
                </div>
            </div>
        </div>
        <div class="row mt-1">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-2"></div>
                            <div class="col-md-10" id="action_btns">
                                <span style="float: right">

                                    <button type="button" class="btn btn-primary" style="display: none;" id="btnBack"
                                        onclick="get_ticket_table_list();"><i
                                            class="fas fa-chevron-left"></i>&nbsp;Back</button>

                                    <span class="show_tkt_btns" style="display:none">
                                        <button type="button"
                                            class="btn btn-danger waves-effect waves-float waves-light"
                                            id="btnMovetotrash" onclick="moveToTrash()"><i class="fas fa-trash-alt"
                                                aria-hidden="true"></i>&nbsp; Move To Trash</button>

                                        <button type="button"
                                            class="btn btn-warning waves-effect waves-float waves-light" id="btnSpam"
                                            onclick="spamTickets()"><i class="far fa-question-circle"
                                                aria-hidden="true"></i>&nbsp; Spam</button>

                                        <button type="button" class="btn btn-info waves-effect waves-float waves-light"
                                            id="btnMerge" onclick="merge_tickets()"><i class="fas fa-random"
                                                aria-hidden="true"></i>&nbsp; Merge</button>
                                    </span>

                                    <button type="button" class="btn btn-danger btnDelete d-none" id="btnDelete"
                                        style="display: none;">
                                        <i class="fas fa-trash-alt"></i>&nbsp;Delete Permanently</button>

                                    <button type="button" id="refreshTicket" onclick="refreshTickets()"
                                        class="btn btn-secondary waves-effect waves-float waves-light">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                            viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                            stroke-linecap="round" stroke-linejoin="round"
                                            class="feather feather-refresh-ccw">
                                            <polyline points="1 4 1 10 7 10"></polyline>
                                            <polyline points="23 20 23 14 17 14"></polyline>
                                            <path
                                                d="M20.49 9A9 9 0 0 0 5.64 5.64L1 10m22 4l-4.64 4.36A9 9 0 0 1 3.51 15">
                                            </path>
                                        </svg>
                                    </button>

                                    <button type="button" class="btn btn-primary waves-effect waves-float waves-light"
                                        id="show-clndr"><i class="fas fa-calendar" aria-hidden="true"></i>&nbsp;
                                        Calendar</button>

                                    <a href="{{url('add-ticket')}}" type="button"
                                        class="btn btn-success waves-effect waves-float waves-light">
                                        <i class="fa fa-plus"></i>&nbsp; Create ticket
                                    </a>

                                </span>
                            </div>
                        </div>
                        <div class="row mt-2">
                            <div class="col-12">
                                <div class="table-responsive" style="overflow: hidden;">
                                    <span class="fw-bolder"> 
                                        <div class="total_selected_tkts d-none">
                                            <span class="total_tickets">0</span>  ticket selected</span>
                                        </div>

                                    <table id="ticket-table-list"
                                        class="table table-bordered display mb-0 ticket-table-list">
                                        <thead>
                                            <tr>
                                                <th>
                                                    <div class="" style="width:30px">
                                                        <input type="checkbox" name="select_all[]" id="select-all" style="position: relative;right: 3px;">
                                                    </div>
                                                </th>
                                                <th></th>
                                                <th>Status</th>
                                                <th class='custom'>Subject</th>
                                                <th class='pr-ticket'>Ticket ID</th>
                                                <th>Priority</th>
                                                <th class='custom-cst'>Customer</th>
                                                <th class='pr-replies custom-cst'>Last Replier</th>
                                                <th>Replies</th>
                                                <th class='pr-activity' >Last Activity
                                                </th>
                                                <th class='pr-ticket'>Reply Due</th>
                                                <th class='pr-due'>Resolution Due</th>
                                                <th class='pr-tech custom-cst'>Assigned Staff</th>
                                                <th class='custom-cst'>Department</th>
                                                <!-- <th class='pr-tech custom-cst'>Creation Date</th> -->
                                            </tr>
                                        </thead>
                                        <tbody></tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-12 p-0">
                                <div class="card">
                                    <div class="card-body" style="overflow-y: scroll;">
                                        <h4 class="card-title">Activity Log</h4>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="table-responsive">
                                                    <table id="ticket-logs-list"
                                                        class="table table-striped table-bordered ticket-table-list">
                                                        <thead>
                                                            <tr>
                                                                <th class="d-none">ID</th>
                                                                <th>Activity</th>
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

                    </div>
                </div>
            </div>
        </div>
        </section>
    </div>
</div>
<!--  Modal content ticket start -->
{{-- <div class="modal fade" id="ticket" role="dialog" data-backdrop="static" aria-labelledby="myLargeModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header d-flex align-items-center">
                    <h4 class="modal-title" id="myLargeModalLabel" style="color:#009efb;">Add Ticket</h4>
                    <button type="button" class="close ml-auto" data-dismiss="modal" aria-hidden="true">×</button>
                </div>
                <div class="modal-body">
                    <form class="form-horizontal mt-4" id="save_tickets" action="{{asset('save-tickets')}}"
method="post">
<div class="row">
    <div class="col-sm-12 col-xs-12">

        <fieldset>
            <div class="form-group">
                <div class="row ">
                    <div class="col-sm-8">
                        <label class="control-label">Subject<span style="color:red !important;">*</span></label><span
                            id="select-subject" style="display: none; color: red !important;">Subject cannot be Empty
                        </span>
                        <input class="form-control" type="text" id="subject" name="subject">
                    </div>
                    <div class="col-sm-4">
                        <label class="control-label">Select Department<span
                                style="color:red !important;">*</span></label><span id="select-department"
                            style="display :none; color:red !important;;">Please Select Department</span>
                        <select class="select2 form-control custom-select" onchange="showDepartStatus(this.value)"
                            id="dept_id" name="dept_id" style="width: 100%; height:36px;">
                            <option value="">Select </option>
                            @foreach($departments as $department)
                            <option value="{{$department->id}}">{{$department->name}}</option>
                            @endforeach

                        </select>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <div class="row">

                    <div class="col-sm-4">
                        <label class="control-label">Select Status<span
                                style="color:red !important;">*</span></label><span id="select-status"
                            style="display:none; color:red !important;">Please Select Status</span>
                        <select class="select2 form-control " id="status" name="status"
                            style="width: 100%; height:36px;">
                        </select>
                    </div>


                    <div class="col-sm-4">
                        <label class="control-label">Select Priority<span
                                style="color:red !important;">*</span></label><span id="select-priority"
                            style="display :none; color:red !important;">Please Select Priority</span>
                        <select class="select2 form-control" id="priority" name="priority"
                            style="width: 100%; height:36px;">
                            <option value="">Select </option>
                            @foreach($priorities as $priority)
                            <option value="{{$priority->id}}">{{$priority->name}}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-sm-4">
                        <label class="control-label">Assign Tech<span
                                style="color:red !important;">*</span></label><span id="select-assign"
                            style="display :none; color:red !important;">Please Select Tech</span>
                        <select class="select2 form-control " id="assigned_to" name="assigned_to"
                            style="width: 100%; height:36px;">
                            <option value="">Unassigned</option>
                            @foreach($users as $user)
                            <option value="{{$user->id}}">{{$user->name}}</option>
                            @endforeach
                        </select>
                    </div>

                </div>
            </div>
            <div class="form-group">
                <div class="row mt-3">

                    <div class="col-sm-4">
                        <label class="control-label">Select Type
                            <span style="color:red !important;">*</span></label><span id="select-type"
                            style="display :none; color:red !important;">Please Select Type</span>
                        <select class="select2 form-control" id="type" name="type" style="width: 100%; height:36px;">
                            <option value="">Select</option>
                            @foreach($types as $type)
                            <option value="{{$type->id}}">{{$type->name}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-sm-4">
                        <label class="control-label">Customer Select<span
                                style="color:red !important;">*</span></label><span id="select-customer"
                            style="display :none; color:red !important;">Please Select Customer</span>
                        <select class="select2 form-control custom-select" id="customer_id" name="customer_id"
                            style="width: 100%; height:36px;">

                            <option value="">Select</option>
                            @foreach($customers as $customer)
                            <option value="{{$customer->id}}">{{$customer->first_name}} {{$customer->last_name}}
                            </option>
                            @endforeach


                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="control-label">Due Date </label>
                        <input class="form-control" type="date" id="deadline" name="deadline">
                    </div>
                    <div class="col-md-12 checkbox checkbox-info" style="margin-left:20px;">
                        <input id="new-form" value="1" type="checkbox" name="newcustomer">
                        <label class="mb-0" for="checkbox4">New Customer</label>
                    </div>

                </div>
            </div>
            <div id="new-customer" style="display:none;">
                <div class="form-group">
                    <div class="row mt-3">
                        <div class="col-md-6">
                            <label for="example-search-input" class=" col-form-label">First
                                Name :<span style="color:red !important;">*</span></label><span id="save-firstname"
                                style="display :none; color:red !important;position: relative;">First
                                name cannot be Empty </span>
                            <div class="">
                                <input class="form-control" type="text" id="first_name" name="first_name"
                                    onkeypress="return event.charCode >= 65 && event.charCode <= 122">
                                <input class="form-control" type="text" id="ticket_id" name="ticket_id" hidden>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label for="example-search-input" class=" col-form-label">Last
                                Name :<span style="color:red !important;">*</span></label><span id="save-lastname"
                                style="display :none; color:red !important;position: relative;">Last
                                name cannot be Empty </span>
                            <div class="">
                                <input class="form-control" type="text" id="last_name"
                                    onkeypress="return event.charCode >= 65 && event.charCode <= 122" name="last_name">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <div class="row mt-3">
                        <div class="col-md-6">
                            <label for="example-search-input" class=" col-form-label">Phone
                                Number :<span style="color:red !important;">*</span></label><span id="save-number"
                                style="display :none; color:red !important;position: relative;">Phone
                                number cannot be Empty </span>
                            <div class="">
                                <input class="form-control" type="text"
                                    onkeypress="return event.charCode >= 48 && event.charCode <= 57" id="phone"
                                    name="phone">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label for="example-search-input" class=" col-form-label">E-mail
                                :<span style="color:red !important;">*</span></label><span id="save-email"
                                style="display :none; color:red !important; position: relative;">Email
                                cannot be Empty </span>
                            <div class="">
                                <input class="form-control" type="text" id="email" name="email">
                            </div>
                        </div>
                    </div>
                </div>
            </div>





            <div class="form-group">
                <div class="row mt-3">
                    <!--<div class="col-sm-4">-->
                    <!--    <input type="file" class="file-upload form-control-file" id="exampleInputFile">-->
                    <!--                        </div>-->

                    <div class="col-sm-12">
                        <label class="control-label">Problem Details<span
                                style="color:red !important;">*</span></label><span id="pro-details"
                            style="display :none; color:red !important;">Please provide details</span>
                        <textarea class="form-control" rows="3" id="ticket_detail" name="ticket_detail"></textarea>
                    </div>
                </div>
            </div>
        </fieldset>
        <div class="text-right">
            <button type="submit" class="btn waves-effect waves-light btn-success" id="btnSaveTicket">
                <div class="spinner-border text-light" role="status"
                    style="height: 20px; width:20px; margin-right: 8px; display: none;">
                    <span class="sr-only">Loading...</span>
                </div>Save
            </button>
        </div>


    </div>
</div>

<div class="loader_container" id="status_modal" style="display:none">
    <div class="loader"></div>
</div>

</form>
</div>
</div><!-- /.modal-content -->
</div><!-- /.modal-dialog -->
</div><!-- /.modal content ticket end --> --}}

<!-- calender -->
<div class="modal fade text-start" id="calendarModal" tabindex="-1" aria-labelledby="myModalLabel1"
    style="display: none;" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="myModalLabel1"> Calendar </h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="col-12" id="calendar"></div>
            </div>
            <!-- <div class="modal-footer">
                    <button type="button" class="btn btn-primary waves-effect waves-float waves-light" data-bs-dismiss="modal">Accept</button>
                </div> -->
        </div>
    </div>
</div>

<!-- reset sla plan modal -->
<div id="reset_sla_plan_modal" class="modal fade" tabindex="-1" role="dialog" data-backdrop="static" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Reset SLA Plan</h4>
                <button type="button" data-bs-dismiss="modal" class="btn-close" onclick="closeAssetModal()"></button>
            </div>
            <div class="modal-body">

                <form id="sla_plan_reset_form" enctype="multipart/form-data" onsubmit="return false" method="post"
                    action="{{asset('/update-ticket-deadlines')}}">

                    <input type="hidden" name="ticket_id" id="sla_ticket_id">

                    <div class="row">
                        <label class="mx-1"> Reply Due</label>
                        <div class="col-md-5">
                            <input type="date" class="form-control mx-1" id="reply_date">
                        </div>
                        <div class="col-md-7">
                            <div class="d-flex justify-content-between">
                                <select name="" class="form-control" id="reply_hour">
                                    @for($i = 1 ; $i < 13; $i++) <option value="{{$i}}">{{$i}}</option>
                                        @endfor
                                </select>
                                <select name="" class="form-control mx-1" id="reply_minute">
                                    @for($i = 0 ; $i < 60; $i++) @php $a=str_pad($i, 2, "0" , STR_PAD_LEFT); @endphp
                                        <option value="{{$a}}">{{$a}}</option>
                                        @endfor
                                </select>
                                <select name="" class="form-control" id="reply_type">
                                    <option value="AM">AM</option>
                                    <option value="PM">PM</option>
                                </select>
                                <button onclick="resetSLA('reply_due')"
                                    class="btn btn-icon btn-icon rounded-circle btn-primary waves-effect waves-float waves-light ms-1"
                                    title="Reset resolution due" style="padding: 10px 23px 4px 14px;">
                                    <i class="fas fa-brush"></i>
                                </button>
                            </div>
                        </div>

                    </div>

                    <div class="row mt-2">

                        <label class="mx-1"> Resolution Due</label>
                        <div class="col-md-5">
                            <input type="date" class="form-control mx-1" id="res_date">
                        </div>
                        <div class="col-md-7">
                            <div class="d-flex justify-content-between">

                                <select name="" class="form-control" id="res_hour">
                                    @for($i = 1 ; $i < 13; $i++) <option value="{{$i}}">{{$i}}</option> @endfor
                                </select>
                                <select name="" class="form-control mx-1" id="res_minute">
                                    <option value="00">00</option>
                                    @for($i = 1 ; $i < 60; $i++) @php $a=str_pad($i, 2, "0" , STR_PAD_LEFT); @endphp
                                        <option value="{{$a}}">{{$a}}</option>
                                    @endfor
                                </select>
                                <select name="" class="form-control" id="res_type">
                                    <option value="AM">AM</option>
                                    <option value="PM">PM</option>
                                </select>
                                <button onclick="resetSLA('resolution_due')"
                                    class="btn btn-icon btn-icon rounded-circle btn-primary waves-effect waves-float waves-light ms-1"
                                    title="Reset resolution due" style="padding: 10px 23px 4px 14px;">
                                    <i class="fas fa-brush"></i>
                                </button>
                            </div>
                        </div>

                    </div>

                    <div class="form-group text-end mt-3">
                        
                        <button class="btn btn-rounded btn-danger float-right" style="margin-right: 5px" type="button"
                            data-bs-dismiss="modal">Close</button>

                        <button class="btn btn-rounded btn-success float-right" type="button"
                            onclick="updateDeadlines();">Save</button>

                    </div>

                </form>
            </div>
        </div>
    </div>
</div>

</div>

@endsection
@section('scripts')

<script>
let calendarEl;
let calendar;
let ticketsList = null;
let ticket_events = [];
let tkt_arr = [];
let ticket_format = @json($ticket_format);
let tickets_followups = @json($tickets_followups);
let statuses_list =  @json($statuses);
let loggedInUser =  @json($loggedInUser);
let date_format = $('#system_date_format').val();

let move_to_trash_route = "{{asset('/move_to_trash_tkt')}}";
let spam_tickets_route = "{{asset('/spam_tickets')}}";

let del_ticket_route = "{{asset('/del_tkt')}}";
let rec_ticket_route = "{{asset('/recycle_tickets')}}";
let flag_ticket_route = "{{asset('/flag_ticket')}}";
let merge_tickets_route = "{{asset('/merge_tickets')}}";
let get_ticket_latest_log = "{{asset('/get_ticket_log')}}";
let ticket_notify_route = "{{asset('/ticket_notification')}}";
let ticket_details_route = "{{asset('/ticket-details')}}";
let get_department_status = "{{asset('/get_department_status')}}"

let url_type = @json($url_type);

let get_tickets_route = "{{asset('/get-tickets')}}";
let get_counter_tickets = "{{asset('/get-counter-tickets')}}";
let get_filteredtkt_route = "{{asset('/get-filtered-tickets')}}";
let ticketLengthCount = @json($ticketView);

</script>

<script src="{{asset('https://cdn.jsdelivr.net/npm/sweetalert2@9')}}"></script>
<link rel="stylesheet" type="text/css"
    href="{{asset($file_path . 'assets/extra-libs/calendar-master/css/theme2.css')}}" />
<script src="{{asset($file_path . 'assets/extra-libs/calendar-master/js/calendar.js')}}"></script>
<script src="https://unpkg.com/multiple-select@1.5.2/dist/multiple-select.min.js"></script>

<!-- <script src="{{asset($file_path . 'app-assets/js/scripts/pages/app-calendar-events.js')}}"></script>
<script src="{{asset($file_path . 'app-assets/js/scripts/pages/app-calendar.js')}}"></script> -->
@include('js_files.ticket_cmmnJs')
@include('js_files.help_desk.ticket_manager.ticketsJs')
<script>
// setting ticket table auto refresh
let time = "{{$ticket_time}}";
let userTimeZone = $("#usrtimeZone").val();
if (time != 0) {
    time = time * 60;
    let one_seconds = 1000;

    setInterval(() => {
        get_ticket_table_list();
    }, (time * one_seconds));
}

$('.content').on('mouseenter', '.ticket_name', function() {
    let id = $(this).data('id');
    let item = tkt_arr.find(item => item.id == id);
    if (item != null) {
        let last_reply = ``;
        let type = ``;

        if (item.last_reply != null) {

            let time = convertDate(item.created_at);

            var user_img = ``;

            if (item.last_reply != null) {

                type = 'Staff';
                if (item.last_reply.reply_user != null) {
                    type = item.last_reply.reply_user.user_type == 5 ? 'User' : 'Staff';
                }

                if (item.last_reply.reply_user != null) {

                    if(item.last_reply.reply_user.profile_pic != null) {

                        let path = root + '/' + item.last_reply.reply_user.profile_pic;
                        user_img +=
                        `<img src="${path}" style="border-radius: 50%;" class="rounded-circle " width="40px" height="40px" />`;

                    }else{
                        user_img += `<img src="${js_origin}/default_imgs/customer.png" class="rounded-circle" 
                                width="40px" height="40px" style="border-radius: 50%;" class="img-fluid" />`;
                    }
                } else {
                    user_img += `<img src="${js_origin}/default_imgs/customer.png" class="rounded-circle" 
                                width="40px" height="40px" style="border-radius: 50%;" class="img-fluid" />`;
                }
            }

            let html = `
                <ul class="list-unstyled replies">
                    <li class="media" id="reply__0">
                        <span class="mr-3"> ${user_img} </span>
                        <div class="row">

                            <div class="col-md-12">
                            <h5 class="mt-0"><span class="text-primary">
                                <a href="http://127.0.0.1:8000/profile/209"> ${item.lastReplier} </a>
                                </span>&nbsp;<span class="badge badge-secondary">${type}</span>&nbsp;
                            &nbsp;                            
                            <br>
                            <span style="font-family:Rubik,sans-serif;font-size:12px;font-weight: 100;">Posted on ${ time } </span> 
                            <div class="my-1 bor-top" id="reply-html-${id}"> ${item.last_reply.reply} </div>
                        </div>
                        
                    </li>
                    <div class="row mt-1" style="word-break: break-all;"></div>
                </ul>
                `
            last_reply = html;
        } else {

            let user_type = item.ticket_created_by == null ? 'Staff' : 'User';
            let path = root + '/' + item.user_pic;
            let content = item.ticket_detail;

            
                content = content;
            

            // Attchments of initial request

            let attchs = '';
            if(item.attachments != null){
                attchs =  item.attachments.split(',');

            }
            let attachments = '';
            if(attchs != ''){
                attachments += `<h6>Attachments</h6>`
                attchs.forEach(attach => {
                    var tech =  `{{asset('/storage/tickets/${item.id}/${attach}')}}`;
                    var ter = getExt(tech);
                    // return ter;
                    if(ter == "pdf" ){
                        attachments+= `<div class="col-md-2" style='position:relative;cursor:pointer' >
                                    <div class="card" style='border:1px solid #c7c7c7;border-radius: 3px !important;margin-bottom: 1rem;'>
                                        <div class="card-body" style="padding: .3rem .3rem !important;background-color:#dfdcdc1f">
                                            <div class="" style="display: -webkit-box">
                                                        <div class="modal-first w-100">
                                                            <div class="mt-0 rounded" >
                                                                <div class="float-start rounded me-1 bg-none" style="">
                                                                    <div class="">                                                               
                                                                        <img src="{{request()->root() . '/' . (Session::get('is_live') == 1 ? 'public/default_imgs' : 'default_imgs/')}}pdf.png" width="25px">    
                                                                    </div>
                                                                </div>
                                                                
                                                                
                                                            </div>
                                                    </div>
                                                </div>
                                        </div>
                                    </div>
                                </div>
                            ` 
                    }
                    else if(ter == "csv" || ter == "xls" || ter == "xlsx" || ter == "sql"){
                        attachments+= `
                        <div class="col-md-2" style='position:relative;cursor:pointer' >
                                    <div class="card" style='border:1px solid #c7c7c7;border-radius: 3px !important;margin-bottom: 1rem;'>
                                        <div class="card-body" style="padding: .3rem .3rem !important;background-color:#dfdcdc1f">
                                            <div class="" style="display: -webkit-box">
                                                        <div class="modal-first w-100">
                                                            <div class="mt-0 rounded" >
                                                                <div class="float-start rounded me-1 bg-none" style="">
                                                                    <div class="">                                                               
                                                                        <img src="{{request()->root() . '/' . (Session::get('is_live') == 1 ? 'public/default_imgs' : 'default_imgs/')}}xlx.png" width="25px">    
                                                                    </div>
                                                                </div>
                                                                
                                                            
                                                            </div>
                                                    </div>
                                                </div>
                                        </div>
                                    </div>
                                </div>
                            ` 
                    }
                    else if(ter == "png" || ter == "jpg" || ter == "webp" || ter == "jpeg" || ter == "webp" || ter == "svg" || ter == "psd"){
                        attachments+= `<div class="col-md-2" style='position:relative;cursor:pointer' >
                                    <div class="card" style='border:1px solid #c7c7c7;border-radius: 3px !important;margin-bottom: 1rem;' onclick="showAttachedImage(${item.id}, '${attach}')" >
                                        <div class="card-body" style="padding: .3rem .3rem !important;background-color:#dfdcdc1f">
                                            <div class="" style="display: -webkit-box">
                                                        <div class="modal-first w-100">
                                                            <div class="mt-0 rounded" >
                                                                <div class="float-start rounded me-1 bg-none" style="">
                                                                    <div class="">                                                               
                                                                        <img src="{{asset('storage/tickets/${item.id}/${attach}')}}" class=" attImg"  alt="" style="width:40px;height:30px !important">    
                                                                    </div>
                                                                </div>
                                                                
                                                                
                                                            </div>
                                                    </div>
                                                </div>
                                        </div>
                                    </div>
                                </div>
                            ` 
                    }
                    else if(ter == "docs" || ter == "doc" || ter == "txt" || ter == "dotx" || ter == "docx"){
                        attachments+= `<div class="col-md-2" style='position:relative;cursor:pointer' >
                                    <div class="card" style='border:1px solid #c7c7c7;border-radius: 3px !important;margin-bottom: 1rem;'>
                                        <div class="card-body" style="padding: .3rem .3rem !important;background-color:#dfdcdc1f">
                                            <div class="" style="display: -webkit-box">
                                                        <div class="modal-first w-100">
                                                            <div class="mt-0 rounded" >
                                                                <div class="float-start rounded me-1 bg-none" style="">
                                                                    <div class="">                                                               
                                                                        <img src="{{request()->root() . '/' . (Session::get('is_live') == 1 ? 'public/default_imgs' : 'default_imgs/')}}word.png" width="25px">    
                                                                    </div>
                                                                </div>
                                                                
                                                                
                                                            </div>
                                                    </div>
                                                </div>
                                        </div>
                                    </div>
                                </div>
                            ` 
                    }
                    else if(ter == "ppt" || ter == "pptx" || ter == "pot" || ter == "pptm"){
                        attachments+= `<div class="col-md-2" style='position:relative;cursor:pointer' >
                                    <div class="card" style='border:1px solid #c7c7c7;border-radius: 3px !important; margin-bottom: 1rem;'>
                                        <div class="card-body" style="padding: .3rem .3rem !important;background-color:#dfdcdc1f">
                                            <div class="" style="display: -webkit-box">
                                                        <div class="modal-first w-100">
                                                            <div class="mt-0 rounded" >
                                                                <div class="float-start rounded me-1 bg-none" style="">
                                                                    <div class="">                                                               
                                                                        <img src="{{request()->root() . '/' . (Session::get('is_live') == 1 ? 'public/default_imgs' : 'default_imgs/')}}pptx.png" width="25px">    
                                                                    </div>
                                                                </div>
                                                                
                                                                
                                                            </div>
                                                    </div>
                                                </div>
                                        </div>
                                    </div>
                                </div>
                            ` 
                    }
                    else if(ter == "zip"){
                        attachments+= `<div class="col-md-2" style='position:relative;cursor:pointer' >
                                    <div class="card" style='border:1px solid #c7c7c7;border-radius: 3px !important;margin-bottom: 1rem;'>
                                        <div class="card-body" style="padding: .3rem .3rem !important;background-color:#dfdcdc1f">
                                            <div class="" style="display: -webkit-box">
                                                        <div class="modal-first w-100">
                                                            <div class="mt-0 rounded" >
                                                                <div class="float-start rounded me-1 bg-none" style="">
                                                                    <div class="">                                                               
                                                                        <img src="{{request()->root() . '/' . (Session::get('is_live') == 1 ? 'public/default_imgs' : 'default_imgs/')}}zip.png" width="25px">    
                                                                    </div>
                                                                </div>
                                                               
                                                                
                                                            </div>
                                                    </div>
                                                </div>
                                        </div>
                                    </div>
                                </div>
                            ` 
                    }
                    else{
                        attachments+= `<div class="col-md-2" style='position:relative;' >
                                    <div class="card" style='border:1px solid #c7c7c7;border-radius: 3px !important;margin-bottom: 1rem;'>
                                        <div class="card-body" style="padding: .3rem .3rem !important;background-color:#dfdcdc1f">
                                            <div class="" style="display: -webkit-box">
                                                        <div class="modal-first w-100">
                                                            <div class="mt-0 rounded" >
                                                                <div class="float-start rounded me-1 bg-none" style="">
                                                                    <div class="">                                                               
                                                                        <img src="{{request()->root() . '/' . (Session::get('is_live') == 1 ? 'public/default_imgs' : 'default_imgs/')}}txt.png" width="25px">    
                                                                    </div>
                                                                </div>
                                                               
                                                                
                                                            </div>
                                                    </div>
                                                </div>
                                        </div>
                                    </div>
                                </div>
                            ` 
                    }
                });
            }
            


            let img =
                `<img src="${path}" style="border-radius: 50%;" class="rounded-circle " width="40px" height="40px" />`;
            let html = `
                <div class="card p-0">
                        <div class="modal-first">
                            <div class="mt-0 mt-0 rounded" style="padding:4px; ">
                                <div class="float-start rounded me-1 bg-none" style="margin-top:5px">
                                    <div class=""> ${img} </div>
                                </div>
                                <div class="more-info">
                                    <div class="" style="display: -webkit-box">
                                        <h6 class="mb-0"> ${item.creator_name != null ? item.creator_name : item.customer_name} <span class="badge badge-secondary"> ${user_type}</span>  </h6>
                                        <span class="ticket-timestamp3 text-muted small" style="margin-left: 9px;">Posted on ${convertDate(item.created_at)}</span>
                                    </div>
                                    <div class="first">                                        
                                        <span style="word-break: break-all;font-size:20px"> ${item.subject} </span> 
                                    </div>
                                </div>

                            </div>
                        </div>
                        <hr>
                        <div class ="row">
                        ${attachments}
                        </div>
                        <div class="card-body p-0">
                            <div class="mail-message">
                                <div class="row" id="ticket_details_p_${id}"><div class="col-12" id="editor_div"> ${content} </div>
                            </div>
                        </div>
                    </div>`;


            last_reply = html;
        }
        $('.hover_content_' + id).html(last_reply);
        if (item.last_reply != null) {
            parserReplyEmbeddedImages(`reply-html-${id}`,`${item.last_reply.embed_attachments}`,`${item.last_reply.type}`,`${id}`);
        }else{
            parserIntitialRequestEmbeddedImages(`ticket_details_p_${id}`,`${item.embed_attachments}`,`${id}`);
        }
    }
    $('.hover_content_' + id).show();
}).on('mouseleave', '.ticket_name', function() {
    let id = $(this).data('id');
    $('.hover_content_' + id).hide();
});

function parserIntitialRequestEmbeddedImages(reply_id , images ,id){
    
    var index = 0;
    $('#'+reply_id+' img').each(function () {
        let attchs = '';

        if(images != null && images != 'null'){
            attchs = images.split(',');
        }
        var classList = $(this).attr("class");
        // console.log(attchs[index])
        if(attchs[index] == undefined || attchs[index] == null){
            if(classList != 'rounded-circle' && classList != 'attImg' && classList != 'img-fluid' && type == 'cron'){
                
                $(this).remove();
            }
        }else{
            
            if(classList != 'rounded-circle' && classList != 'attImg'){
                $(this).attr('src', "{{asset('storage/tickets')}}/"+id+'/'+attchs[index]);
                $(this).css("width", "400px");
                // $(this).attr("onClick","showAttachedImage("+ticket_details.id+",`" +attchs[index] +"`)");
                index++;
            }
        }
    });
    
}


function parserReplyEmbeddedImages(reply_id , images , type,id){
    
    var index = 0;
    $('#'+reply_id+' img').each(function () {
        let attchs = '';

        if(images != null && images != 'null'){
            attchs = images.split(',');
        }
        var classList = $(this).attr("class");
        // console.log(attchs[index])
        if(attchs[index] == undefined || attchs[index] == null){
            if(classList != 'rounded-circle' && classList != 'attImg' && classList != 'img-fluid' && type == 'cron'){
                $(this).remove();
            }
        }else{
            
            if(classList != 'rounded-circle' && classList != 'attImg'){
                $(this).attr('src', "{{asset('storage/tickets-replies')}}/"+id+'/'+attchs[index]);
                $(this).css("width", "400px");
                // $(this).attr("onClick","showAttachedImage("+ticket_details.id+",`" +attchs[index] +"`)");
                index++;
            }
        }
    });
    
}


function getExt(filename) {
    var ext = filename.split('.').pop();
    if(ext == filename) return "";
    return ext;
}
</script>
@endsection