@extends('layouts.master-layout-new')
@section('Help Desk','open')
@section('body')
<link rel="stylesheet" href="https://unpkg.com/multiple-select@1.5.2/dist/multiple-select.min.css">
<style>
    .flagged-tr,
    .flagged-tr .sorting_1
    {
        background-color: #FFE4C4 !important;
    }

    .flagged-tr .fa-flag
    {
        color: red !important;
    }
   
    .pr-0{
        padding-right: 0 !important;
    }
    
    table.dataTable th {
            padding: 20px 10px !important;
            vertical-align: middle !important;
        }
        table.dataTable .custom{
        padding-right: 186px !important;
    }
    .pr-ticket{
        min-width: 89px !important;
    }
    .pr-replies{
        min-width: 95px !important;
    }
    .pr-due{
        min-width: 125px !important;
    }
    .pr-activity{
    min-width: 97px !important;
    padding-right: 19px !important;

    }
    .pr-tech {
        min-width: 109px !important;
    }
    table.dataTable .custom-cst{
        padding-right: 37px !important;
    }
    table.dataTable td{
        padding:7px !important;
        font-size:12px;
    }
    table.dataTable thead .sorting:before, table.dataTable thead .sorting_asc:before, table.dataTable thead .sorting_desc:before,
    table.dataTable thead .sorting:after, table.dataTable thead .sorting_asc:after, table.dataTable thead .sorting_desc:after{
        display:none;
    }
    /* table th, td{
        padding-top: 5px !important; 
        padding-bottom: 5px !important; 
    }
    table td{
        padding-top: 0px !important; 
        padding-bottom: 0px !important; 
    } */
    /* #ticket-table-list td {
        min-width: 60px;
        padding-right:0;
    } */
    
    /* #ticket-table-list td:nth-child(1), td:nth-child(2) {
        min-width: 20px !important;
        max-width: 20px !important;
    } */
    /* .row-flex-container {
        display: flex;
        flex-wrap: wrap;
        justify-content: space-evenly;
        align-items: center;
        width: 100%;
        margin-top: 1.5rem;
    } */
    /* #ticket-table-list_length label, #ticket-table-list_filter label {
        display:inline-flex;
    } */
    .select2-container .select2-selection--single .select2-selection__rendered {
        padding-right: 60px !important;
    }




    #dropD {
    padding-left: 15px;
}
    .mt-0{
        margin: unset
    }
    .badge-primary{
        background-color:#4eafcb
    }
    .card-body.drop-dpt{
        padding: 0 !important;
    }
    span.select2-container.select2-container--default.select2-container--open{
        top: 3.9844px !important
    }
    .badge-secondary {
    color: #fff;
    background-color: #868e96;
    }
    .media-body{
        width:575px
    }
    .btn-outline-bt{
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
    .mr-3{
        margin-right: 1rem !important;
    }
    .media {
    display: flex;
    align-items: flex-start;
}
    .innerBox{
        font-size: 15px;
        height: 100px;
        overflow-y: scroll;
    }
    #style-5::-webkit-scrollbar-track
{
	-webkit-box-shadow: inset 0 0 6px rgba(0,0,0,0.3);
	background-color: #F5F5F5;
}
.text-white{
    color:white;
}
#style-5::-webkit-scrollbar
{
	width: 3px;
    height: 10px;
	background-color: #F5F5F5;
}

#style-5::-webkit-scrollbar-thumb
{
	background-color: #0ae;
	
	background-image: -webkit-gradient(linear, 0 0, 0 100%,
	                   color-stop(.5, rgba(255, 255, 255, .2)),
					   color-stop(.5, transparent), to(transparent));
}
    .fc-col-header , .fc-scrollgrid-sync-table , .fc-daygrid-body , .fc-daygrid-body-unbalanced  {
        width: 100% !important;
    }
    
    .cld-main svg {
        fill: #7367f0 !important;
    }
    .cld-datetime .today {
        font-size: 1.8rem !important;
        font-weight: bolder;
    }
</style>
@php
    $file_path = Session::get('is_live') == 1 ? 'public/' : '/';
@endphp
<div class="app-content content">
    <div class="content-overlay"></div>
    <div class="header-navbar-shadow"></div>
    <div class="content-wrapper p-0">
        <div class="content-header row">
            <div class="content-header-left col-md-7 col-12 mb-2">
                <div class="row breadcrumbs-top">
                    <div class="col-12">
                        <h2 class="content-header-title float-start mb-0">Tickets Manager</h2>
                        <div class="breadcrumb-wrapper">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{route('home')}}">Home</a> </li>
                                <li class="breadcrumb-item">Help Desk </li>
                                <li class="breadcrumb-item active">Tickets Manager </li>
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
                                            <h2 class="fw-bolder mb-0" id="closed_tickets_count"></h2>
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
                            <div class="card border-success">
                                <div class="card-body">
                                    <div class="flex-row">
                                        <div class="my-auto" style="text-align: center">
                                            <a href="javascript:listTickets('trash')">
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
                        <div class="card" >
                            <div class="card-body drop-dpt " style="background-color:#b0bec5;border-radius:9px;">
                                <div class="row" id="dropD" style="margin-right:-5px;margin-bottom:0 !important;">
                                    <div class="col-md-2 br-white" id="dep-label" style="border-right: 1px solid white;padding: 12px;">
                                        <label class="control-label col-sm-12 end_padding text-white" ><strong>Department</strong></label>
                                        <h5 class="end_padding mb-0 selected-label text-white" style="font-size: 0.87rem; !important"  id="dep-h5"></h5>
                                        <select class="select2 form-control  " id="dept_id" name="dept_id" style="width: 100%; height:36px;">
                                            
                                            @foreach($departments as $department)
                                                <option  value="{{$department->id}}"  >{{$department->name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-2 br-white" id="tech-label" style="border-right: 1px solid white;padding: 12px;">
                                        <label class="control-label col-sm-12 end_padding text-white "><strong>Owner</strong></label>
                                        <h5 class="end_padding mb-0 selected-label text-white" style="font-size: 0.87rem; !important" id="tech-h5"></h5>
                                        <select class="select2 form-control " id="assigned_to" name="assigned_to" style="width: 100%; height:36px;">
                                            <option value="">Unassigned</option>
                                            @foreach($users as $user)
                                                <option value="{{$user->id}}">{{$user->name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-2 br-white" id="type-label" style="border-right: 1px solid white;padding: 12px;">
                                        <label class="control-label col-sm-12 end_padding text-white "><strong>Type</strong></label>
                                        <h5 class="end_padding mb-0 selected-label text-white" style="font-size: 0.87rem; !important" id="type-h5"></h5>
                                        <select class="select2 form-control " id="type" name="type" style="width: 100%; height:36px;">
                                            @foreach($types as $type)
                                                <option value="{{$type->id}}" >{{$type->name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-2 br-white" id="status-label" style="border-right: 1px solid white;padding: 12px;">
                                        <label class="control-label col-sm-12 end_padding text-white "><strong>Status</strong></label>
                                        <h5 class="end_padding mb-0 selected-label text-white" style="font-size: 0.87rem; !important" id="status-h5"></h5>
                                        <select class="select2 form-control " id="status" name="status" style="width: 100%; height:36px;">
                                            @foreach($statuses as $status)
                                                <option value="{{$status->id}}" data-color="{{$status->color}}" >{{$status->name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-2 br-white" id="prio-label" style="border-right: 1px solid white; padding: 12px;">
                                        <label class="control-label col-sm-12 end_padding text-white " ><strong>Priority</strong></label>
                                        <h5 class="end_padding mb-0 selected-label text-white" style="font-size: 0.87rem; !important" id="prio-h5"></h5>
                                        <select class="select2 form-control " id="priority" name="priority" style="width: 100%; height:36px;">
                                            @foreach($priorities as $priority)
                                                <option value="{{$priority->id}}" data-color="{{$priority->priority_color}}" >{{$priority->name}}</option>
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

                                        <button type="button" class="btn btn-primary" style="display: none;" id="btnBack" onclick="get_ticket_table_list();"><i class="fas fa-chevron-left"></i>&nbsp;Back</button>

                                        <span class="show_tkt_btns" style="display:none">
                                            <button type="button" class="btn btn-danger waves-effect waves-float waves-light" id="btnMovetotrash" onclick="moveToTrash()" ><i class="fas fa-trash-alt" aria-hidden="true"></i>&nbsp; Move To Trash</button>

                                            <button type="button" class="btn btn-warning waves-effect waves-float waves-light" id="btnSpam" onclick="moveToTrash()"><i class="far fa-question-circle" aria-hidden="true"></i>&nbsp; Spam</button>

                                            <button type="button" class="btn btn-info waves-effect waves-float waves-light" id="btnMerge" onclick="merge_tickets()"><i class="fas fa-random" aria-hidden="true"></i>&nbsp; Merge</button>
                                        </span>

                                        <button type="button" class="btn btn-danger" id="btnDelete" style="display: none;"><i class="fas fa-trash-alt"></i>&nbsp;Delete Permanently</button>

                                        <button type="button" class="btn btn-primary waves-effect waves-float waves-light" id="show-clndr"><i class="fas fa-calendar" aria-hidden="true"></i>&nbsp; Calender</button>

                                        <a href="{{url('add-ticket')}}" type="button" class="btn btn-success waves-effect waves-float waves-light">
                                            <i class="fa fa-plus"></i>&nbsp; Create ticket
                                        </a>

                                    </span>
                                    </div>
                                </div>
                                <div class="row mt-2">
                                    <div class="col-12">
                                        <div class="table-responsive" style="overflow: hidden;">
                                            <table id="ticket-table-list" class="table table-bordered table-hover display mb-0 ticket-table-list">
                                                <thead>
                                                    <tr>
                                                        <th>
                                                            <div class="text-center">
                                                                <input type="checkbox" name="select_all[]" id="select-all">
                                                            </div>
                                                        </th>
                                                        <th></th>
                                                        <th>Status</th>
                                                        <th class='custom'>Subject</th>
                                                        <th class='pr-ticket'>Ticket ID</th>
                                                        <th >Priority</th>
                                                        <th class='custom-cst'>Customer</th>
                                                        <th class='pr-replies custom-cst'>Last Replier</th>
                                                        <th>Replies</th>
                                                        <th class='pr-activity '>Last Activity</th>
                                                        <th class='pr-ticket'>Reply Due</th>
                                                        <th class='pr-due'>Resolution Due</th>
                                                        <th class='pr-tech custom-cst'>Assigned Tech</th>
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
                                                            <table id="ticket-logs-list" class="table table-striped table-bordered ticket-table-list">
                                                                <thead>
                                                                    <tr>
                                                                        <th>ID</th>
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
                    <form class="form-horizontal mt-4" id="save_tickets" action="{{asset('save-tickets')}}" method="post">
                        <div class="row">
                            <div class="col-sm-12 col-xs-12">

                                <fieldset>
                                    <div class="form-group">
                                        <div class="row ">
                                            <div class="col-sm-8">
                                                <label class="control-label">Subject<span
                                                        style="color:red !important;">*</span></label><span id="select-subject"
                                                    style="display: none; color: red !important;">Subject cannot be Empty </span>
                                                <input class="form-control" type="text" id="subject" name="subject">
                                            </div>
                                            <div class="col-sm-4">
                                                <label class="control-label">Select Department<span
                                                        style="color:red !important;">*</span></label><span id="select-department"
                                                    style="display :none; color:red !important;;">Please Select Department</span>
                                                <select  class="select2 form-control custom-select" onchange="showDepartStatus(this.value)"
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
                                                <select class="select2 form-control " id="priority" name="priority"
                                                    style="width: 100%; height:36px;">
                                                    <option value="">Select </option>
                                                    @foreach($priorities as $priority)
                                                    <option value="{{$priority->id}}">{{$priority->name}}</option>
                                                    @endforeach
                                                </select>
                                            </div>

                                            <div class="col-sm-4">
                                                <label class="control-label">Assign Tech<span style="color:red !important;">*</span></label><span id="select-assign" style="display :none; color:red !important;">Please Select Tech</span>
                                                <select class="select2 form-control " id="assigned_to" name="assigned_to" style="width: 100%; height:36px;">
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
                                                <select class="select2 form-control" id="type" name="type"
                                                    style="width: 100%; height:36px;">
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
                                                <select class="select2 form-control custom-select" id="customer_id"
                                                    name="customer_id" style="width: 100%; height:36px;">

                                                    <option value="">Select</option>
                                                    @foreach($customers as $customer)
                                                        <option value="{{$customer->id}}">{{$customer->first_name}} {{$customer->last_name}}</option>
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
                                                    <label for="example-search-input"
                                                        class=" col-form-label">First
                                                        Name :<span style="color:red !important;">*</span></label><span
                                                        id="save-firstname"
                                                        style="display :none; color:red !important;position: relative;">First
                                                        name cannot be Empty </span>
                                                    <div class="">
                                                        <input class="form-control" type="text" id="first_name"
                                                            name="first_name" onkeypress="return event.charCode >= 65 && event.charCode <= 122">
                                                        <input class="form-control" type="text" id="ticket_id"
                                                            name="ticket_id" hidden>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <label for="example-search-input"
                                                        class=" col-form-label">Last
                                                        Name :<span style="color:red !important;">*</span></label><span
                                                        id="save-lastname"
                                                        style="display :none; color:red !important;position: relative;">Last
                                                        name cannot be Empty </span>
                                                    <div class="">
                                                        <input class="form-control" type="text" id="last_name" onkeypress="return event.charCode >= 65 && event.charCode <= 122"
                                                            name="last_name">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div class="row mt-3" >
                                                <div class="col-md-6">
                                                    <label for="example-search-input"
                                                        class=" col-form-label">Phone
                                                        Number :<span style="color:red !important;">*</span></label><span
                                                        id="save-number"
                                                        style="display :none; color:red !important;position: relative;">Phone
                                                        number cannot be Empty </span>
                                                    <div class="">
                                                        <input class="form-control" type="text" onkeypress="return event.charCode >= 48 && event.charCode <= 57" id="phone" name="phone">
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <label for="example-search-input"
                                                        class=" col-form-label">E-mail
                                                        :<span style="color:red !important;">*</span></label><span id="save-email"
                                                        style="display :none; color:red !important; position: relative;">Email
                                                        cannot be Empty </span>
                                                    <div class="">
                                                        <input class="form-control" type="text" id="email" name="email" >
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
                                                <textarea class="form-control" rows="3" id="ticket_detail"
                                                    name="ticket_detail"></textarea>
                                            </div>
                                        </div>
                                    </div>
                                </fieldset>
                                <div class="text-right">
                                    <button type="submit" class="btn waves-effect waves-light btn-success" id="btnSaveTicket">
                                        <div class="spinner-border text-light" role="status" style="height: 20px; width:20px; margin-right: 8px; display: none;">
                                        <span class="sr-only">Loading...</span>
                                      </div>Save</button>
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
    <div class="modal fade text-start" id="calendarModal" tabindex="-1" aria-labelledby="myModalLabel1" style="display: none;" aria-hidden="true">
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

</div>

@endsection
@section('scripts')

<script>
    let calendarEl;
    let calendar;
    let ticketsList = null;
    let ticket_events = [];
    let ticket_format = {!! json_encode($ticket_format) !!};
    let tickets_followups = {!! json_encode($tickets_followups) !!};
    let statuses_list = {!! json_encode($statuses) !!};
    let loggedInUser = {!! json_encode($loggedInUser) !!};
    let date_format = $('#system_date_format').val();

    let move_to_trash_route = "{{asset('/move_to_trash_tkt')}}";
    let del_ticket_route = "{{asset('/del_tkt')}}";
    let rec_ticket_route = "{{asset('/recycle_tickets')}}";
    let flag_ticket_route = "{{asset('/flag_ticket')}}";
    let merge_tickets_route = "{{asset('/merge_tickets')}}";
    let get_ticket_latest_log = "{{asset('/get_ticket_log')}}";
    let ticket_notify_route = "{{asset('/ticket_notification')}}";
    let ticket_details_route = "{{asset('/ticket-details')}}";
    let get_department_status = "{{asset('/get_department_status')}}"

    let url_type = {!! json_encode($url_type) !!};
    let get_tickets_route = "{{asset('/get-tickets')}}";
    let get_counter_tickets = "{{asset('/get-counter-tickets')}}";
    
    let get_filteredtkt_route = "{{asset('/get-filtered-tickets')}}"

</script>
<script src="{{asset('https://cdn.jsdelivr.net/npm/sweetalert2@9')}}"></script>
<link rel="stylesheet" type="text/css" href="{{asset($file_path . 'assets/extra-libs/calendar-master/css/theme2.css')}}" />
<script src="{{asset($file_path . 'assets/extra-libs/calendar-master/js/calendar.js')}}"></script>
<script src="https://unpkg.com/multiple-select@1.5.2/dist/multiple-select.min.js"></script>

<!-- <script src="{{asset($file_path . 'app-assets/js/scripts/pages/app-calendar-events.js')}}"></script>
<script src="{{asset($file_path . 'app-assets/js/scripts/pages/app-calendar.js')}}"></script> -->
@include('js_files.ticket_cmmnJs')
@include('js_files.help_desk.ticket_manager.ticketsJs')
@endsection