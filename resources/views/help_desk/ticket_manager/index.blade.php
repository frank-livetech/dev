@extends('layouts.staff-master-layout')
@section('body-content')
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
    table th, td{
        padding-top: 5px !important; 
        padding-bottom: 5px !important; 
    }
    table td{
        padding-top: 0px !important; 
        padding-bottom: 0px !important; 
    }
    #ticket-table-list td {
        min-width: 120px;
    }
    #ticket-table-list td:nth-child(1), td:nth-child(2) {
        min-width: 20px !important;
        max-width: 20px !important;
    }
    .row-flex-container {
        display: flex;
        flex-wrap: wrap;
        justify-content: space-evenly;
        align-items: center;
        width: 100%;
        margin-top: 1.5rem;
    }
    /* #ticket-table-list_length label, #ticket-table-list_filter label {
        display:inline-flex;
    } */
    .select2-container .select2-selection--single .select2-selection__rendered {
        padding-right: 60px !important;
    }
</style>
<div class="page-breadcrumb">
    <div class="row">
        <div class="col-md-5 align-self-center">
            <!--<h4 class="page-title">Tickets Manager</h4>-->
            <div class="d-flex align-items-center">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{route('home')}}">Home</a></li>
                        <li class="breadcrumb-item" aria-current="page">Help Desk</li>
                        <li class="breadcrumb-item active" aria-current="page">Tickets Manager</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
</div>

    @if($date_format) 
        <input type="hidden" id="system_date_format" value="{{$date_format}}">
    @else
        <input type="hidden" id="system_date_format" value="DD-MM-YYYY">
    @endif

<div class="container-fluid" style="padding-bottom: 0px;">
    <div class="row">
        <div class="card">
            <div class="card-body">
<!--                 
                <div class="col-12 d-flex p-0">
                    <h4 class="card-title">Tickets</h4>
                    
                </div> -->

                <!-- <div class="row mt-3 pl-3 pr-4">
                    <div class="col-md-3 pr-1">
                    <div class="form-group">
                        <input type="date" class="form-control mt-1" id="date1" name="">
                    </div>
                    </div>
                    <div class="col-md-2 p-1">
                    <div class="form-group">
                        <select class="select2 form-control" id="statusFilter1" style="width: 100%;" onchange="listTickets();">
                            <option value="all" selected>All Assignees</option>
                            @foreach($staffs as $staff)
                            <option value="{{$staff->id}}">{{$staff->name}}</option>
                            @endforeach
                        </select>
                    </div>
                    </div>
                    <div class="col-md-2 p-1">
                    <div class="form-group">
                        <select class="select2 form-control" id="statusFilter2" style="width: 100%;" onchange="listTickets();">
                            <option value="all" selected>All Projects</option>
                            @foreach($projects as $project)
                            <option value="{{$project->id}}">{{$project->name}}</option>
                            @endforeach
                        </select>
                    </div>
                    </div>
                    <div class="col-md-2 p-1">
                    <div class="form-group">
                        <select class="select2 form-control" id="statusFilter" style="width: 100%;" onchange="listTickets();">
                            <option value="all" selected>All Statuses</option>
                            @foreach($statuses as $status)
                            <option value="{{$status->id}}">{{$status->name}}</option>
                            @endforeach
                        </select>
                    </div>
                    </div>
                    <div class="col-md-3 p-1">
                    <div class="form-group">
                        <select class="select2 form-control custom-select" id="deptFilter" style="width: 100%;" onchange="listTickets();">
                            <option value="all" selected>All Departments</option>
                            @foreach($departments as $department)
                                <option value="{{$department->id}}">{{$department->name}}</option>
                            @endforeach
                        </select>
                    </div>
                    </div>                    

                </div> -->
                <input hidden id="dept" value="{{$dept}}">
                <input hidden id="sts" value="{{$sts}}">

                <div class="row-flex-container mt-3">
                   
                    <div class="col-sm-4 col-md-2">
                        <a href="javascript:listTickets('total')" class="card card-hover border-info">
                            <div class="box p-2 rounded info text-center">
                                <h1 class="font-weight-light " id="total_tickets_count"></h1>
                                <h6 class="text-info">All Tickets</h6>
                            </div>
                        </a>
                    </div>
                    <div class="col-sm-4 col-md-2">
                        <a href="javascript:listTickets('self')" class="card card-hover border-success">
                            <div class="box p-2 rounded success text-center">
                                <h1 class="font-weight-light " id="my_tickets_count"></h1>
                                <h6 class="text-success">My Tickets</h6>
                            </div>
                        </a>
                    </div>
                    <!-- <div class="col-sm-4 col-md-2">
                        <a href="javascript:listTickets('open')" class="card card-hover border-warning">
                            <div class="box p-2 rounded warning text-center">
                                <h1 class="font-weight-light " id="open_tickets_count"></h1>
                                <h6 class="text-warning">Open</h6>
                            </div>
                        </a>
                    </div> -->
                    <div class="col-sm-4 col-md-2">
                        <a href="javascript:listTickets('unassigned')" class="card card-hover border-primary">
                            <div class="box p-2 rounded primary text-center">
                                <h1 class="font-weight-light " id="unassigned_tickets_count"></h1>
                                <h6 class="text-primary">Unassigned</h6>
                            </div>
                        </a>
                    </div>
                    <div class="col-sm-4 col-md-2">
                        <a href="javascript:listTickets('overdue')" class="card card-hover border-danger">
                            <div class="box p-2 rounded danger text-center">
                                <h1 class="font-weight-light" id="closed_tickets_count"></h1>
                                <h6 class="text-danger">Overdue</h6>
                            </div>
                        </a>
                    </div>
                    <div class="col-sm-4 col-md-2">
                        <a href="javascript:listTickets('trash')" class="card card-hover border-dark">
                            <div class="box p-2 rounded dark text-center">
                                <h1 class="font-weight-light " id="trashed_tickets_count"></h1>
                                <h6 class="text-dark">Trash</h6>
                            </div>
                        </a>
                    </div>
                </div>
                    
                <div class="row px-3 mt-3">
                    <div class="col-12 pb-3 text-right" id="action_btns">
                        <button type="button" class="btn btn-info ml-auto mr-2" id="show-clndr" data-toggle="modal" data-target="#calendarModal">
                            <i class="fas fa-calendar"></i>&nbsp;Calendar
                        </button>
                        <a href="{{url('add-ticket')}}" type="button" class="btn btn-success">
                            <i class="mdi mdi-plus-circle"></i>&nbsp;Add ticket
                        </a>
                        <button type="button" class="btn btn-primary" style="display: none;" id="btnBack" onclick="get_ticket_table_list();"><i class="fas fa-chevron-left"></i>&nbsp;Back</button>
                        <button type="button" class="btn btn-warning" id="btnSpam" onclick="moveToTrash()"><i class="far fa-question-circle"></i>&nbsp;Spam</button>
                        <button type="button" class="btn btn-info" id="btnMerge" onclick="merge_tickets()" style=""><i class="fas fa-random"></i>&nbsp;Merge</button>
                        <button type="button" class="btn btn-danger" id="btnMovetotrash" onclick="moveToTrash()"><i class="fas fa-trash-alt"></i>&nbsp;Move to Trash</button>
                        <button type="button" class="btn btn-danger" id="btnDelete" style="display: none;"><i class="fas fa-trash-alt"></i>&nbsp;Delete Permanently</button>
                    </div>
                </div>

                <div class="row">

                    <!-- <div class="col-3">
                        <ul class="list-group">
                            <li class="list-group-item p-1 list_department" id="">
                                <a href="javascript:void(0)" class="active list-group-item-action d-flex align-items-center"><i class="font-18 align-middle mr-1 mdi mdi-inbox"></i> All Departments </a>
                            </li>
                            @foreach($departments as $department)
                                <li class="list-group-item p-1 list_department" id="{{$department->name}}">
                                    <a href="javascript:void(0)" class="list-group-item-action d-block"> <i class="fas fa-arrow-right"></i> {{$department->name}}  </a>
                                </li>
                            @endforeach
                        </ul>
                        <hr>
                        <div class="row">
                            <div class="col-12">
                                <input type="date" class="form-control mt-1" id="date1" name="">
                            </div>
                        </div>
                        <div class="row mt-2">
                            <div class="col-12">
                                <select class="select2 form-control " id="statusFilter1" style="width: 100%;" onchange="listTickets();">
                                    <option value="all" selected>All Assignees</option>
                                    @foreach($staffs as $staff)
                                    <option value="{{$staff->id}}">{{$staff->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="row mt-2">
                            <div class="col-12">
                                <select class="select2 form-control" id="statusFilter" style="width: 100%;" onchange="listTickets();">
                                    <option value="all" selected>All Statuses</option>
                                    @foreach($statuses as $status)
                                    <option value="{{$status->id}}">{{$status->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div> -->

                    <div class="col-12">
                        <div class="table-responsive" style="overflow: hidden;">
                            <table id="ticket-table-list" class="table table-striped table-bordered table-hover display ticket-table-list">
                                <thead>
                                    <tr>
                                        <th>
                                            <div class="text-center">
                                                <input type="checkbox" name="select_all[]" id="select-all">
                                            </div>
                                        </th>
                                        <th></th>
                                        <th>Status</th>
                                        <th>Subject</th>
                                        <th>Ticket ID</th>
                                        <th>Priority</th>
                                        <th>Customer</th>
                                        <th>Last Replier</th>
                                        <th>Replies</th>
                                        <th>Last Activity</th>
                                        <th>Reply Due</th>
                                        <th>Resolution Due</th>
                                        <th>Assigned Tech</th>
                                        <th>Department</th>
                                        <th>Creation Date</th>
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

    <div class="row">
        <div class="col-12 p-0">
            <div class="card">
                <div class="card-body" style="overflow-y: scroll;">
                    <h4 class="card-title">Activity Log</h4>
                    <div class="row">
                        <div class="col-12">
                            <div class="table-responsive" style="overflow: hidden;">
                                <table id="ticket-logs-list" class="table table-striped table-bordered no-wrap ticket-table-list">
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
    
    <!--  Modal content ticket start -->
    <div class="modal fade" id="ticket" role="dialog" data-backdrop="static" aria-labelledby="myLargeModalLabel" aria-hidden="true">
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
    </div><!-- /.modal content ticket end -->

    <!--  Modal content ticket start -->
    <div class="modal fade" id="calendarModal" role="dialog"  data-backdrop="static" aria-labelledby="calendarLargeModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header d-flex align-items-center">
                    <h4 class="modal-title" id="calendarLargeModalLabel" style="color:#009efb;">Calendar</h4>
                    <button type="button" class="close ml-auto" data-dismiss="modal" aria-hidden="true">×</button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-12" id="calendar"></div>

                        <button type="button" class="btn btn-secondary ml-auto mr-3" data-dismiss="modal" aria-hidden="true">Close</button>
                    </div>
                </div>
            </div><!-- /.modal-content calendar -->
        </div><!-- /.modal-dialog calendar -->
    </div><!-- /.modal content calendar end -->
</div>
<style>
    .checkbox {
        padding-top: 25px;
    }

    .file-upload {
        bottom: 5px;
        position: relative;
    }

</style>

@endsection
@section('scripts')
<script src="{{asset('https://cdn.jsdelivr.net/npm/sweetalert2@9')}}"></script>

<script src="https://unpkg.com/multiple-select@1.5.2/dist/multiple-select.min.js"></script>


<script>

    let ticketsList = null;
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
    let get_filteredtkt_route = "{{asset('/get-filtered-tickets')}}"

</script>


@include('js_files.ticket_cmmnJs')
@include('js_files.help_desk.ticket_manager.ticketsJs')
@endsection
