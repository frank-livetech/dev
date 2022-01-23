@extends('layouts.master-layout-new')
@section('Dashboard','active')
@section('title', 'Dashboard')
@section('body')
<style>
    table.dataTable thead .sorting:before, table.dataTable thead .sorting_asc:before, table.dataTable thead .sorting_desc:before,
    table.dataTable thead .sorting:after, table.dataTable thead .sorting_asc:after, table.dataTable thead .sorting_desc:after{
        opacity:0;
    }
</style>
@php
    $file_path = $live->sys_value == 1 ? 'public/' : '/';
@endphp

@if(Session('system_date'))
    <input type="hidden" id="system_date_format" value="{{Session('system_date')}}">
@else
    <input type="hidden" id="system_date_format" value="DD-MM-YYYY">
@endif

<div class="app-content content">
    <div class="content-overlay"></div>
    <div class="header-navbar-shadow"></div>
    <div class="content-wrapper container-fluid p-0">
    <div class="content-header row">
        <div class="content-header-left col-md-5 col-12 mb-2">
            <div class="row breadcrumbs-top">
                <div class="col-12">
                    <h2 class="content-header-title float-start mb-0">Dashboard</h2>
                    <div class="breadcrumb-wrapper">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="">Home</a>
                            </li>
                            <li class="breadcrumb-item active">Dashboard</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>
         <div class="content-header-right text-md-end col-md-7 col-12 d-md-block d-none">
            <button type="button" class="btn btn-primary waves-effect waves-float waves-light" disabled><i class="fa fa-cog" aria-hidden="true"></i>&nbsp; Support Dashboard</button>
            <button type="button" class="btn btn-primary waves-effect waves-float waves-light" disabled><i class="fas fa-chart-line" aria-hidden="true"></i>&nbsp; CFO Dashboard</button>
            @if($clockin)
            <button type="button" class="btn btn-danger waves-effect waves-float waves-light clock_btn" onclick="staffatt('clockout')"><i class="fa fa-clock" aria-hidden="true"></i>&nbsp;Clock Out</button>
            @else
            <button type="button" class="btn btn-success waves-effect waves-float waves-light clock_btn" onclick="staffatt('clockin')"><i class="fa fa-clock" aria-hidden="true"></i>&nbsp;Clock In</button>
            @endif
        </div> 
    </div>
    <div class="content-body">
        <section id="statistics-card">
            <div class="row match-height">
                <div class="col-lg-12 col-12">
                    <div class="card card-statistics">
                        <div class="card-body statistics-body">
                            <div class="row">
                                <div class="col-md-3 col-sm-6 col-12 mb-2 mb-md-0">
                                    <div class="d-flex flex-row">
                                        <div class="avatar bg-light-primary me-2">
                                            <div class="avatar-content">
                                                <i data-feather="trending-up" class="avatar-icon"></i>
                                            </div>
                                        </div>
                                        <div class="my-auto">
                                            <a href="{{url('staff-manager')}}">
                                            <h3 class="fw-bolder mb-0">{{$staff_count}}</h3>
                                            <p class="card-text font-small-10 mb-0">Total Staff</p>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3 col-sm-6 col-12 mb-2 mb-md-0">
                                    <div class="d-flex flex-row">
                                        <div class="avatar bg-light-info me-2">
                                            <div class="avatar-content">
                                                <i data-feather="user" class="avatar-icon"></i>
                                            </div>
                                        </div>
                                        <div class="my-auto">
                                            <a href="{{url('customer-lookup')}}">
                                            <h3 class="fw-bolder mb-0">{{$customers}}</h3>
                                            <p class="card-text font-small-10 mb-0">Total Customers</p>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3 col-sm-6 col-12 mb-2 mb-sm-0">
                                    <div class="d-flex flex-row">
                                        <div class="avatar bg-light-danger me-2">
                                            <div class="avatar-content">
                                                <i data-feather="box" class="avatar-icon"></i>
                                            </div>
                                        </div>
                                        <div class="my-auto">
                                            <a href="{{url('projects-list')}}">
                                            <h3 class="fw-bolder mb-0">{{$project}}</h3>
                                            <p class="card-text font-small-10 mb-0">Total Web Projects</p>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3 col-sm-6 col-12">
                                    <div class="d-flex flex-row">
                                        <div class="avatar bg-light-success me-2">
                                            <div class="avatar-content">
                                                <i data-feather="shopping-bag" class="avatar-icon"></i>
                                            </div>
                                        </div>
                                        <div class="my-auto">
                                            <a href="{{url('billing/home')}}">
                                            <h3 class="fw-bolder mb-0">{{$orders}}</h3>
                                            <p class="card-text font-small-10 mb-0">Total Orders</p>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-6 col-md-6 col-12">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">Help Desk</h4>
                        </div>
                        <div class="card-body">
                            <form action="javascript:void(0);" class="form">
                                <div class="row">
                                    <div class="col-12 mb-2">
                                        <div class="mb-2">
                                            <form class="d-flex w-100 pb-3 position-relative" action="search-ticket-result" method="post" id="search-ticket" autocomplete="off">
                                                <input type="text" class="form-control"  id="tsearch" name="id" placeholder="Search Example - ABC-123-4321">
                                                <i class="fas fa-circle-notch fa-spin text-primary" id="tkt_loader" style="position: absolute; top:75px;font-size:1.2rem; right:30px;display:none" aria-hidden="true"></i>
                                            </form>
                                            
                                        </div>
                                        <div id="show_ticket_results"></div>
                                    </div>
                                    <div class="d-grid col-4">
                                        <a href="{{asset('/add-ticket')}}">
                                        <div class="card card_shadow bg-success card_back border-dark card-hover">
                                            <div class="card-header">
                                                <div class="text-center">
                                                    <h2 class="fw-bolder mb-0"><span class="fas fa-plus"></span></h2>
                                                    <h5 class="card-text">Create Ticket</h5>
                                                </div>
                                                
                                            </div>
                                        </div>
                                        </a>
                                    </div>
                                    <div class="d-grid col-4">
                                        <a href="{{asset('/ticket-manager')}}">
                                        <div class="card card_shadow card_back border-info card-hover text-center">
                                            <div class="card-header">
                                                <div class="">
                                                    <h2 class="fw-bolder mb-0">{{$total_tickets_count}}</h2>
                                                    <h5 class="card-text text-info">All Tickets</h5>
                                                </div>
                                                
                                            </div>
                                        </div>
                                        </a>
                                    </div>
                                    <div class="d-grid col-4">
                                        <a href="{{asset('/ticket-manager/self')}}">
                                        <div class="card card_shadow card_back border-success card-hover text-center">
                                            <div class="card-header">
                                                <div class="">
                                                    <h2 class="fw-bolder mb-0">{{$my_tickets_count}}</h2>
                                                    <h5 class="card-text text-success">My Tickets</h5>
                                                </div>
                                                
                                            </div>
                                        </div>
                                        </a>
                                    </div>
                                    <div class="d-grid col-4">
                                        <a href="{{asset('/ticket-manager/open')}}">
                                        <div class="card card_shadow card_back border-warning card-hover text-center" >
                                            <div class="card-header">
                                                <div class="">
                                                    <h2 class="fw-bolder mb-0">{{$open_tickets_count}}</h2>
                                                    <h5 class="card-text text-warning">Open</h5>
                                                </div>
                                                
                                            </div>
                                        </div>
                                        </a>
                                    </div>
                                    <div class="d-grid col-4">
                                        <a href="{{asset('/ticket-manager/unassigned')}}">
                                        <div class="card card_shadow card_back border-primary card-hover text-center" >
                                            <div class="card-header">
                                                <div class="">
                                                    <h2 class="fw-bolder mb-0">{{$unassigned_tickets_count}}</h2>
                                                    <h5 class="card-text text-primary">Unassigned</h5>
                                                </div>
                                                
                                            </div>
                                        </div>
                                        </a>
                                    </div>
                                    <div class="d-grid col-4">
                                        <a href="{{asset('/ticket-manager/overdue')}}">
                                        <div class="card card_shadow card_back border-danger card-hover text-center" >
                                            <div class="card-header">
                                                <div class="">
                                                    <h2 class="fw-bolder mb-0">{{$late_tickets_count}}</h2>
                                                    <h5 class="card-text text-danger">Overdue</h5>
                                                </div>
                                                
                                            </div>
                                        </div>
                                        </a>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                
               
                    <div class="col-lg-12 col-md-12 col-12">
                        <div class="card">
                            <div class="card-body">
                                <h4 class="card-title">Recent Activity</h4>

                                 <div class="table-responsive">
                                 <table id="ticket-logs-list" class="table table-striped table-bordered no-wrap ticket-table-list w-100">
                            <thead>
                                <tr>
                                    <th width="20">ID</th>
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
                <div class="col-lg-6 col-md-6 col-12">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">Customer Manager</h4>
                        </div>
                        <div class="card-body">
                            <form action="javascript:void(0);" class="form">
                                <div class="row">
                                    <div class="col-12">
                                        <div class="mb-2">
                                            <form class="d-flex w-100 position-relative" action="{{asset('/search-customer')}}" method="post" id="search-customer" autocomplete="off">
                                                <input type="text" class="form-control text-dark" id="csearch" name="id" placeholder="Search Customer">
                                                <i class="fas fa-circle-notch fa-spin text-primary" id="cust_loader" style="position: absolute; top:75px; font-size:1.2rem; right:35px; display:none"></i>
                                            </form>
                                            
                                        </div>
                                        <div id="search_customer_result"></div>
                                    </div>
                                    
                                </div>
                            </form>
                        </div>
                    </div>

                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">Flagged  <span class="float-end"><i class="fas fa-flag" style="color:#fd7e14;"></i></span></h4>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="table-responsive">
                                        <table class="table table-bordered table-hover">
                                            <thead>
                                                <tr>
                                                    <th>
                                                        <div class="text-center" >
                                                            <input type="checkbox" name="select_all[]" id="select-all">
                                                        </div>
                                                    </th>
                                                    <th></th>
                                                    <th>Status</th>
                                                    <th>Subject</th>
                                                    <th >Ticket ID</th>
                                                    <th >Priority</th>
                                                    <th >Customer</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td>
                                                        <div class="text-center">
                                                            <input type="checkbox" name="select_all[]" id="select-all">
                                                        </div>
                                                    </td>
                                                    <td class=" overflow-wrap">
                                                        <div class="text-center ">
                                                            <span class="fas fa-flag" title="Flag" style="cursor:pointer;" onclick="flagTicket(this, 3);"></span>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="text-center text-white badge" style="background-color: #da650b;">On Hold </div>
                                                    </td>
                                                    <td >Subject</td>
                                                    <td >Ticket ID</td>
                                                    <td >Priority</td>
                                                    <td >Customer</td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">Staff Manager</h4> 
                            
                        </div>
                        <div class="card-body">
                            <div class="row p-2">
                                @foreach($users as $user)
                                <div class="col-md-2 mb-1">
                                    
                                    @if($user->profile_pic != "" && $user->profile_pic != null)
                                        @if(file_exists( public_path(). $file_path . '/files/user_photo/'. $user->profile_pic ))
                                            <span class="avatar">
                                                <a href="{{url('profile')}}/{{$user->id}}" data-bs-toggle="tooltip" data-placement="top" title="{{$user->name}}">
                                                    <img  src="{{asset( $file_path . 'files/user_phot')}}/{{$user->profile_pic}}"
                                                        alt="'s Photo" class="rounded-circle" width="65" height="72">
                                                </a>
                                            <span class="avatar-status-online"></span></span>
                                        @else
                                            <span class="avatar">
                                                <a href="{{url('profile')}}/{{$user->id}}" data-bs-toggle="tooltip" data-placement="top" title="{{$user->name}}">
                                                    <img src="{{asset($file_path . 'default_imgs/customer.png')}}" alt="'s Photo" class="rounded-circle avatar" width="50px" height="50">
                                                </a>
                                            <span class="avatar-status-online"></span></span>
                                        @endif
                                    @else
                                    <span class="avatar">
                                        <a href="{{url('profile')}}/{{$user->id}}" data-bs-toggle="tooltip" data-placement="top" title="{{$user->name}}">
                                            <img src="{{asset($file_path . 'default_imgs/customer.png')}}" alt="'s Photo" class="rounded-circle avatar" width="50px" height="50">
                                        </a>
                                    <span class="avatar-status-online"></span></span>
                                    @endif
                                    
                                </div>
                                
                                @endforeach
                            </div>
                                <div class="card">
                                    
                                    <div class="card-body">

                                        <div class="row">
                                            <hr>
                                            <div class="d-flex justify-content-between align-items-center">
                                                <div>
                                                    <h4 class="text-muted fw-bolder">Total Staff</h4>
                                                    <h3 class="mb-0" style="text-align: center">{{$staff_count}}</h3>
                                                </div>
                                                <div>
                                                    <h4 class="text-muted fw-bolder">Active staff</h4>
                                                    <h3 class="mb-0" style="text-align: center">{{$staff_active_count}}</h3>
                                                </div>
                                                <div>
                                                    <h6 class="text-muted fw-bolder">Off Clock Staff</h6>
                                                    <h3 class="mb-0" style="text-align: center">{{$late_tickets_count}}</h3>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="card-datatable">
                                            {{-- <div class="table-responsive">
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
                
                                                    </tbody>
                                                </table>
                                            </div> --}}
                                            <div class="table-responsive">
                                                <table class="table table-bordered table-hover">
                                                    <thead>
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
                                                        <tr>
                                                            {{-- <td>
                                                                <div class="text-center">
                                                                    <input type="checkbox" name="select_all[]" id="select-all">
                                                                </div>
                                                            </td> --}}
                                                            {{-- <td class=" overflow-wrap">
                                                                <div class="text-center ">
                                                                    <span class="fas fa-flag" title="Flag" style="cursor:pointer;" onclick="flagTicket(this, 3);"></span>
                                                                </div>
                                                            </td> --}}
                                                            <td>1
                                                            </td>
                                                            <td >Name</td>
                                                            <td >Status</td>
                                                            <td >DATE</td>
                                                            <td >Clock In</td>
                                                            <td >Clock Out</td>
                                                            <td >Worked Hours</td>

                                                        </tr>
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
        </section>
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header font-weight-bold" style="font-size: 20px;"><strong>Follow-Up Details</strong></div>
                    <div class="card-body mt-1">
                {{-- <div class="row">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="startDate">Start Date</label>
                            <input type="date" class="form-control" id="startDate" placeholder="Start Date" onchange="searchFollowUps()">
                        </div>
                    </div>
                </div>
                <div class="row mt-1 app-calendar">
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
                </div> --}}
                <div class="row">
                    <div class="card-datatable">
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover">
                                <thead>
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
                                    <tr>
                                        <td></td>
                                        <td>1
                                        </td>
                                        <td >Name</td>
                                        <td >Ticket ID</td>
                                        <td >Follow-Up</td>
                                        <td >Assigned tech</td>
                                        <td >Preferred Contact</td>

                                    </tr>
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

@endsection
@section('scripts')
<script src="{{asset($file_path . 'app-assets/js/scripts/pages/app-calendar.js')}}"></script>
<script src="{{asset($file_path . 'app-assets/vendors/js/calendar/fullcalendar.min.js')}}"></script>


@include('js_files.dashboardjs')

<script type="text/javascript">
$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
    }
});
</script>
@endsection