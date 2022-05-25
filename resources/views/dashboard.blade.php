@extends('layouts.staff-master-layout')
@section('body-content')
<style>
    a:hover {
        color: #009efb;
    }

    .iconUsers {
        font-size: 33px;
    }

    .clockedIn {
        color: green;
        
    }

    .notWorking {
        color: grey;
    }

    .notclockedIn {
        color: red;
    }

    .active-green {
        width: 12px;
        height: 12px;
        background-color: green;
        border-radius: 50%;
        border: 2px solid #fff;
        display: inline-block;
        position: relative;
        right: 21px;
        top: 13px;
    }

    .inactive-red {
        width: 12px;
        height: 12px;
        background-color: red;
        border-radius: 50%;
        border: 2px solid #fff;
        display: inline-block;
        position: relative;
        right: 21px;
        top: 13px;
    }

    .chick {
        margin-right: -5px;
    }

    /*
 *  STYLE 5
 */

    .style-5::-webkit-scrollbar-track {
        -webkit-box-shadow: inset 0 0 6px rgba(0, 0, 0, 0.3);
        background-color: #F5F5F5;
    }

    .style-5::-webkit-scrollbar {
        width: 3px;
        height: 10px;
        background-color: #F5F5F5;
    }

    .style-5::-webkit-scrollbar-thumb {
        background-color: #0ae;

        background-image: -webkit-gradient(linear, 0 0, 0 100%,
                color-stop(.5, rgba(255, 255, 255, .2)),
                color-stop(.5, transparent), to(transparent));
    }

    .table td, .table th {
        padding: 0.25rem;
    }
</style>
<?php $count = 1;  ?>

@php
    $file_path = $live->sys_value == 1 ? 'public/' : '/';
@endphp


<div class="page-breadcrumb bread_crum_back">
    <input type="hidden" id="curr_user_name" value="{{Auth::user()->name}}">

    @if(Session('system_date'))
    <input type="hidden" id="system_date_format" value="{{Session('system_date')}}">
    @else
    <input type="hidden" id="system_date_format" value="DD-MM-YYYY">
    @endif


    <div class="row">
        <div class="col-md-6 align-self-center">
            <h3 class="page-title">Dashboard</h3>
            <div class="d-flex align-items-center">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Dashboard</li>
                    </ol>
                </nav>
            </div>
        </div>

        <div class="col-md-6 clock_btn_div" style="text-align:right">
            <span title="PLANNED COMING SOON" class=""><button type="button" class="btn btn-secondary" href="#" disabled="" title="PLANNED COMING SOON"><i class="fas fa-cog"></i>&nbsp;Support Dashboard</button></span>
            <span title="PLANNED COMING SOON" class="">
                <button type="button" class="btn btn-secondary" href="{{url('cfo-dashboard')}}" disabled="">
                    <i class="fas fa-chart-line"></i>&nbsp;CFO Dashboard</button>
            </span>
            @if($clockin)
            <button type="button" class="btn btn-danger clock_btn" onclick="staffatt('clockout')"><i class="fa fa-clock" aria-hidden="true"></i>&nbsp;Clock Out</button>
            @else
            <button type="button" class="btn btn-success clock_btn" onclick="staffatt('clockin')"><i class="fa fa-clock" aria-hidden="true"></i>&nbsp;Clock In</button>
            @endif


        </div>
    </div>
</div>


<div class="container-fluid main_sys_back ">
    <!-- Row -->

    <div class="row">
        <!-- Column -->
        <div class="col-lg-3 col-md-6">
            <div class="card card_shadow card_back border-bottom border-info">
                <div class="card-body ">
                    <a href="{{url('staff-manager')}}">
                        <div class="d-flex no-block align-items-cente">
                            <div>
                                <h2>{{$staff_count}}</h2>
                                <h6 class="text-info">Total Staff</h6>
                            </div>
                            <div class="ml-auto">
                                <span class="text-info display-6"><i class="ti-notepad"></i></span>
                            </div>
                        </div>
                    </a>
                </div>
            </div>
        </div>
        <!-- Column -->
        <!-- Column -->
        <div class="col-lg-3 col-md-6">
            <div class="card card_shadow card_back border-bottom border-danger">
                <div class="card-body">
                    <a href="{{url('projects-list')}}">
                        <div class="d-flex no-block align-items-cente">
                            <div>
                                <h2> {{$project}}</h2>
                                <h6 class="text-danger">Total Web Projects</h6>
                            </div>
                            <div class="ml-auto">
                                <span class="text-danger display-6"><i class="ti-clipboard"></i></span>
                            </div>
                        </div>
                        <!-- <div class="d-flex justify-content-between">
                        <h5 class="card-title">Total Web Projects</h5>
                        <h2 class="font-weight-bolder mb-0"> {{$project}}</h2>
                    </div> -->
                    </a>
                </div>
            </div>
        </div>
        <!-- Column -->
        <!-- Column -->
        <div class="col-lg-3 col-md-6">
            <div class="card card_shadow card_back border-bottom border-success">
                <div class="card-body ">
                    <a href="{{url('customer-lookup')}}">
                        <div class="d-flex no-block align-items-cente">
                            <div>
                                <h2> {{$customers}}</h2>
                                <h6 class="text-success">Total Customers</h6>
                            </div>
                            <div class="ml-auto">
                                <span class="text-success display-6"><i class="ti-stats-up"></i></span>
                            </div>
                        </div>
                    </a>
                </div>
            </div>
        </div>
        <!-- Column -->
        <!-- Column -->
        <div class="col-lg-3 col-md-6">
            <div class="card card_shadow card_back border-bottom border-warning">
                <div class="card-body">
                    <a href="{{url('billing/home')}}">
                        <div class="d-flex no-block align-items-cente">
                            <div>
                                <h2> {{$orders}}</h2>
                                <h6 class="text-warning">Total Order</h6>
                            </div>
                            <div class="ml-auto">
                                <span class="text-warning display-6"><i class="ti-wallet"></i></span>
                            </div>
                        </div>
                    </a>
                </div>
            </div>
        </div>
        <!-- Column -->
    </div>
    <div class="row">
        <div class="col-lg-6">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Help Desk </h4>

                    <form class="d-flex w-100 pb-3 position-relative" action="search-ticket-result" method="post" id="search-ticket" autocomplete="off">
                        <input type="text" class="form-control" style="background-color:white !important;color:#263238!important;" id="tsearch" name="id" placeholder="Search Example - ABC-123-4321">
                        <i class="fas fa-circle-notch fa-spin text-primary" id="tkt_loader" style="position: absolute; top:10px;font-size:1.2rem; right:10px;display:none"></i>
                    </form>

                    <div class="col-12 pb-3 px-0 text-center style-5" id="show_ticket_results" style=" max-height: 300px !important; overflow-y: auto;"></div>

                    <div class="row mt-3">
                        <div class="col-lg-4 col-md-4 col-sm-4">
                            <a href="{{asset('/add-ticket')}}" class="card card_shadow card_back border-dark card-hover">
                                <div class="box p-2 rounded text-center">
                                    <h2><span class="mdi mdi-plus"></span></h2>
                                    <h6 class="text-dark">New Ticket</h6>
                                </div>
                            </a>
                        </div>
                        <div class="col-lg-4 col-md-4 col-sm-4">
                            <a href="{{asset('/ticket-manager')}}" class="card card_shadow card_back border-info card-hover">
                                <div class="box p-2 rounded  text-center">
                                    <h2>{{$total_tickets_count}}</h2>
                                    <h6 class="text-info">All Tickets</h6>
                                </div>
                            </a>
                        </div>
                        <div class="col-lg-4 col-md-4 col-sm-4">
                            <a href="{{asset('/ticket-manager/self')}}" class="card card_shadow card_back  border-success card-hover">
                                <div class="box p-2 rounded text-center">
                                    <h2>{{$my_tickets_count}}</h2>
                                    <h6 class="text-success">My Tickets</h6>
                                </div>
                            </a>
                        </div>
                        <div class="col-lg-4 col-md-4 col-sm-4">
                            <a href="{{asset('/ticket-manager/open')}}" class="card card_shadow card_back border-warning card-hover">
                                <div class="box p-2 rounded  text-center">
                                    <h2>{{$open_tickets_count}}</h2>
                                    <h6 class="text-warning">Open</h6>
                                </div>
                            </a>
                        </div>
                        <div class="col-lg-4 col-md-4 col-sm-4">
                            <a href="{{asset('/ticket-manager/unassigned')}}" class="card card_shadow card_back border-primary card-hover">
                                <div class="box p-2 rounded text-center">
                                    <h2>{{$unassigned_tickets_count}}</h2>
                                    <h6 class="text-primary">Unassigned</h6>
                                </div>
                            </a>
                        </div>
                        <div class="col-lg-4 col-md-4 col-sm-4">
                            <a href="{{asset('/ticket-manager/overdue')}}" class="card card_shadow card_back  border-danger card-hover">
                                <div class="box p-2 rounded text-center">
                                    <h2>{{$late_tickets_count}}</h2>
                                    <h6 class="text-danger">Overdue</h6>
                                </div>
                            </a>
                        </div>
                    </div>
                    {{-- <div class="row">
                        <div class="col-md-12 headDash bg-info">
                            <h4 class="text-white pt-2">Follow-Up Details <a href="#" class="text-white" style="float:right;"><i class="fas fa-sort-down"></i></a></h4>
                        </div>
                        <div class="col-md-8 pt-2">
                            <div class="form-group">
                                <input type="date" class="form-control" id="date1" name="" placeholder="Choose a Range">
                            </div>
                        </div>
                        <div class="col-md-4"></div>
                        <div class="col-md-12"></div>
                    </div> --}}
                </div>
            </div>

            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Recent Activity</h4>

                    <div class="table-responsive">
                        <table id="ticket-logs-list" class="table table-striped table-bordered no-wrap ticket-table-list w-100">
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
        <div class="col-lg-6">
            <div class="card card_shadow card_back card_background_color">
                <div class="card-body">
                    <h4 class="card-title">Customer Manager</h4>

                    <form class="d-flex w-100 position-relative" action="{{asset('/search-customer')}}" method="post" id="search-customer" autocomplete="off">
                        <input type="text" style="background-color:white !important;color:#263238 !important;" class="form-control text-dark" id="csearch" name="id" placeholder="Search Customer">
                        <i class="fas fa-circle-notch fa-spin text-primary" id="cust_loader" style="position: absolute; top:25px; font-size:1.2rem; right:25px; display:none"></i>
                    </form>
                    <div class="col-12 pb-3 px-0" id="search_customer_result" style="display: none; max-height: 300px !important; overflow-y: auto;"></div>
                </div>
            </div>
            <!-- <div class="row">
                <div class="col-lg-6">
                    <div class="card card_shadow card_back card_background_color">
                        <div class="card-body">
                            <h4 class="card-title">Client Warnings</h4>
                                <ul style="list-style:circle;">
                                    <li>Complain # 01</li>
                                    <li>Complain # 02</li>
                                    <li>Complain # 03</li>
                                    <li>Complain # 04</li>
                                    <li>Complain # 05</li>
                                </ul>
                        
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="card card_shadow card_back card_background_color">
                        <div class="card-body">
                            <h4 class="card-title">Pendings</h4>
                                <ul style="">
                                    <li>Pending # 01</li>
                                    <li>Pending # 02</li>
                                    <li>Pending # 03</li>
                                </ul>
                        </div>
                    </div>
                </div>
            </div> -->
            @if(Auth::user()->user_type == 1)
            <!-- <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <h4 class="card-title">Staff Manager</h4>
                        <a href="{{url('staff_attendance')}}">View All</a>
                    </div>
                    <div class="row">
                        <div class="col-lg-4 col-md-6 mb-4 mt-3 text-center">
                            <h1 class="mb-0 font-weight-light total_staff_count" id="total_staff_count">{{$staff_count}}</h1>
                            <h6 class="text-muted">Total Staff</h6>
                        </div>
                        <div class="col-lg-4 col-md-6 mb-4 mt-3 text-center">
                            <h1 class="mb-0 font-weight-light active_staff_count" id="active_staff_count">{{$staff_active_count}}</h1>
                            <h6 class="text-muted">Active Staff</h6>
                        </div>
                        <div class="col-lg-4 col-md-6 mb-4 mt-3 text-center">
                            <h1 class="mb-0 font-weight-light total_inactive_count" id="total_inactive_count">{{$staff_inactive_count}}</h1>
                            <h6 class="text-muted">Off Clock Staff</h6>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12 col-md-12 ">
                            <div class="table-responsive">
                                <table class="table table-hover no-wrap" id="staff_table">
                                    <thead class="table_head_back">
                                        <tr>
                                            <th class="text-center border-0">#</th>
                                            <th>Name</th>
                                            <th>Status</th>
                                            <th class="border-0">DATE</th>
                                            <th class="border-0">Clock In</th>
                                            <th class="border-0">Clock Out</th>
                                            <th class="border-0">Worked Hours</th>

                                        </tr>
                                    </thead>
                                    <tbody>

                                        @foreach($staff_att_data as $staff)
                                        <tr>
                                            <td class="text-center">{{$count++}}</td>
                                            <td>{{$staff->user_clocked->name}}</td>
                                            
                                            @if($staff->clock_out == null)
                                                <td><span class="badge badge-success py-1">Clocked In</span></td>
                                            @else
                                                <td><span class="badge badge-danger py-1">Clocked Out</span></td>
                                            @endif

                                            <td class="txt-oflo">{{$staff->date}}</td>
                                            <td class="txt-oflo">{{$staff->clock_in}}</td>
                                            <td class="txt-oflo">{{$staff->clock_out}}</td>

                                            <td>{{$staff->hours_worked == null ? '-' : $staff->hours_worked}}</td>
                                        </tr>
                                        @endforeach

                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div> -->
            <div class="card card_shadow card_back card_background_color">
                <div class="card-body">
                    <h4 class="card-title">Staff Manager</h4>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="row">
                                @foreach($users as $user)
                                <div class="col-3">
                                    @if($user->profile_pic != "" && $user->profile_pic != null)
                                        @if(file_exists( public_path(). $file_path . '/files/user_photo/'. $user->profile_pic ))
                                            <a href="{{url('profile')}}/{{$user->id}}" data-toggle="tooltip" data-placement="top" title="{{$user->name}}">
                                                <img  src="{{asset( $file_path . 'files/user_phot')}}/{{$user->profile_pic}}"
                                                    alt="'s Photo" class="rounded-circle" width="65" height="72">
                                            </a>
                                        @else
                                            <a href="{{url('profile')}}/{{$user->id}}" data-toggle="tooltip" data-placement="top" title="{{$user->name}}">
                                                <img src="{{asset($file_path . 'default_imgs/logo.png')}}" alt="'s Photo" class="rounded-circle" width="65" height="72">
                                            </a>
                                        @endif
                                    @else
                                        <a href="{{url('profile')}}/{{$user->id}}" data-toggle="tooltip" data-placement="top" title="{{$user->name}}">
                                            <img src="{{asset($file_path . 'default_imgs/logo.png')}}" alt="'s Photo" class="rounded-circle" width="65" height="72">
                                        </a>
                                    @endif
                                </div>
                                @endforeach
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="row">
                                <div class="col-lg-6 col-md-6 col-sm-6">
                                    <a href="#" class="card card_shadow card_back  border-primary card-hover">
                                        <div class="box p-2 rounded text-center">
                                            <h2 class="total_staff_count" id="total_staff_count">{{$staff_count}}</h2>
                                            <h6 class="text-primary ">Total Staff</h6>
                                        </div>
                                    </a>
                                </div>
                                <div class="col-lg-6 col-md-6 col-sm-6">
                                    <a href="#" class="card card_shadow card_back border-warning card-hover">
                                        <div class="box p-2 rounded  text-center">
                                            <h2 class=" active_staff_count" id="active_staff_count">{{$staff_active_count}}</h2>
                                            <h6 class="text-warning ">Active Staff</h6>
                                        </div>
                                    </a>
                                </div>
                                <!-- <div class="col-lg-6 col-md-6 col-sm-6">
                                    <a href="{{asset('/ticket-manager/unassigned')}}" class="card card_shadow card_back border-success card-hover">
                                        <div class="box p-2 rounded text-center">
                                            <h2 class=" total_inactive_count" id="total_inactive_count">{{$staff_inactive_count}}</h2>
                                            <h6 class="text-success ">Unassign Tickets</h6>
                                        </div>
                                    </a>
                                </div> -->
                                <div class="col-lg-6 col-md-6 col-sm-6">
                                    <a href="#" class="card card_shadow card_back  border-danger card-hover">
                                        <div class="box p-2 rounded text-center">
                                            <h2 class=" ">{{$late_tickets_count}}</h2>
                                            <h6 class="text-danger ">Off Clock Staff</h6>
                                        </div>
                                    </a>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-12 col-md-12 mt-2">
                            <div class="table-responsive">
                                <table class="table table-hover no-wrap" id="staff_table">
                                    <thead class="table_head_back">
                                        <tr>
                                            <th class="text-center border-0">#</th>
                                            <th>Name</th>
                                            <th>Status</th>
                                            <th class="border-0">DATE</th>
                                            <th class="border-0">Clock In</th>
                                            <th class="border-0">Clock Out</th>
                                            <th class="border-0">Worked Hours</th>

                                        </tr>
                                    </thead>
                                    <tbody>

                                        {{-- @foreach($staff_att_data as $staff)
                                        <tr>
                                            <td class="text-center">{{$count++}}</td>
                                            <td>{{$staff->user_clocked->name}}</td>

                                            @if($staff->clock_out == null)
                                            <td><span class="badge badge-success py-1">Clocked In</span></td>
                                            @else
                                            <td><span class="badge badge-danger py-1">Clocked Out</span></td>
                                            @endif

                                            <td class="txt-oflo">{{$staff->date}}</td>
                                            <td class="txt-oflo">{{$staff->clock_in}}</td>
                                            <td class="txt-oflo">{{$staff->clock_out}}</td>

                                            <td>{{$staff->hours_worked == null ? '-' : $staff->hours_worked}}</td>
                                        </tr>
                                        @endforeach --}}

                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endif

            <!-- <div class="card card_shadow card_back card_background_color">
                <div class="card-body">
                    <h4 class="card-title">RMM - Monitoring - Planning</h4>
                        
                </div>
            </div> -->
        </div>


    </div>

    <div class="card">
        <div class="card-header bg-info text-white font-weight-bold">Follow-Up Details</div>
        <div class="row p-2">
            <div class="col-md-12">
                <div class="row">
                    <div class="col-12 mb-3">
                        <div class="row">
                            <div class="col-md-12 mb-3">
                                <div class="form-group">
                                    <label for="startDate">Start Date</label>
                                    <input type="date" class="form-control" id="startDate" placeholder="Start Date" onchange="searchFollowUps()">
                                </div>
                                {{-- <button class="btn btn-primary float-right" type="button" onclick="searchFollowUps()">Search</button> --}}
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4" id="calendar"></div>
                    <div class="col-md-8">
                        <div class="table-responsive">
                            <table class="table table-hover w-100 text-center" id="followup_table">
                                <thead class="table_head_back">
                                    <tr>
                                        <th>
                                            <div class="custom-control custom-checkbox">
                                                <input type="checkbox" class="custom-control-input" id="customCheck1">
                                                <label class="custom-control-label" for="customCheck1"></label>
                                            </div>
                                        </th>
                                        <th> Sr# </th>
                                        <th> Name </th>
                                        <th> Ticket ID </th>
                                        <th> Follow-Up </th>
                                        <th> Assigned tech </th>
                                        <th> Preferred Contact </th>
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
@endsection

@section('scripts')
<link rel="stylesheet" type="text/css" href="{{asset('assets/extra-libs/calendar-master/css/theme2.css')}}" />
<script src="{{asset('assets/extra-libs/calendar-master/js/calendar.js')}}"></script>
@include('js_files.dashboardjs')
@endsection