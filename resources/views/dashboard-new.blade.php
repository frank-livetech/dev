@extends('layouts.master-layout-new')
@section('Dashboard','active')
@section('title', 'Dashboard')
@section('body')


<style>
table.dataTable thead .sorting:before,
table.dataTable thead .sorting_asc:before,
table.dataTable thead .sorting_desc:before,
table.dataTable thead .sorting:after,
table.dataTable thead .sorting_asc:after,
table.dataTable thead .sorting_desc:after {
    opacity: 0;
}

.flagged-tr,
.flagged-tr .sorting_1 {
    background-color: #FFE4C4 !important;
}

.flagged-tr .fa-flag {
    color: red !important;
}

.card-statistics .statistics-body {
    padding: 0rem 2.4rem 1rem !important
}

.selectbox {
    background: #fff;
    color: #1d3b4a;
    padding: 8px;
    line-height: 18px;
    border-radius: 4px;
    border-width: 0 1px 4px;
}

.linkbox,
.selectbox {
    border: 1px solid #ddd;
}

.pull-right {
    float: right !important;
}

.pull-left {
    float: left !important
}

.mb-1 {
    margin-bottom: 1rem
}

.search_result {
    border-radius: 4px;
    background: #fff;
    /* box-shadow: 0 6px 10px rgba(0,0,0,.08), 0 0 6px rgba(0,0,0,.05); */
    transition: .3s transform cubic-bezier(.155,1.105,.295,1.12),.3s box-shadow,.3s -webkit-transform cubic-bezier(.155,1.105,.295,1.12);
    /* padding: 14px 80px 18px 36px; */
    cursor: pointer;
}
.search_result:hover {
    transform: scale(1.03);
    box-shadow: 0 10px 20px rgba(0,0,0,.12), 0 4px 8px rgba(0,0,0,.06);
}
#header-btn{
        display: none !important
    }
#desktop_view{
        display: block !important
    }
    .mt-6{
        margin-top: 6rem
    }

@media (max-width: 630px) {
    #header-btn{
        display: block !important;
        margin-bottom: 1rem !important
    }
    #desktop_view{
        display: none !important
    }
    #dash_btns{
        width: 40px !important;
        height: 40px !important
    }
    .card-statistics .statistics-body .avatar .avatar-content .avatar-icon {
        width: 24px;
        height: 24px;
        margin-bottom: 10px;
}
    .card-body.statistics-body{
        padding:0rem 2rem 0rem 0.5rem !important;
}
    #unassign_padd{
        padding-left: 0px !important
    }
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
{{-- <link rel="stylesheet" type="text/css" href="{{asset( $file_path . 'app-assets/daterangepicker.css')}}"> --}}

<input type="hidden" id="curr_user_name" value="{{Auth::user()->name}}">
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
                                <li class="breadcrumb-item active"><a href="javascript:location.reload()">Dashboard</a>
                                </li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>
            <div class="content-header-right text-md-end col-md-7 col-12 d-md-block" id="desktop_view">
                <div class="d-flex justify-content-end">
                    <div>
                        <button type="button" class="btn btn-primary waves-effect waves-float waves-light" disabled><i
                                class="fa fa-cog" aria-hidden="true"></i>&nbsp; Support Dashboard</button>
                        <button type="button" class="btn btn-primary waves-effect waves-float waves-light" disabled><i
                                class="fas fa-chart-line" aria-hidden="true"></i>&nbsp; CFO Dashboard</button>
                    </div>
                    <div class="clock_btn_div mx-1">
                        @if($clockin)
                        <button type="button" class="btn btn-danger waves-effect waves-float waves-light clock_btn ml-1"
                            onclick="staffatt('clockout' , this)">
                            <i class="fa fa-clock" aria-hidden="true"></i>&nbsp;Clock Out</button>
                        @else
                        <button type="button"
                            class="btn btn-success waves-effect waves-float waves-light clock_btn ml-1"
                            onclick="staffatt('clockin' , this)">
                            <i class="fa fa-clock" aria-hidden="true"></i>&nbsp;Clock In</button>
                        @endif
                    </div>
                </div>

            </div>
            <div class="content-header-right text-md-end col-md-7 col-12 d-md-block " id="header-btn" >
                <div class="d-flex justify-content-end">
                    <div>
                        <button type="button" class="btn btn-primary waves-effect waves-float waves-light" disabled><i
                                class="fa fa-cog" aria-hidden="true"></i></button>
                        <button type="button" class="btn btn-primary waves-effect waves-float waves-light" disabled><i
                                class="fas fa-chart-line" aria-hidden="true"></i></button>
                    </div>
                    <div class="clock_btn_div mx-1">
                        @if($clockin)
                        <button type="button" class="btn btn-danger waves-effect waves-float waves-light clock_btn ml-1"
                            onclick="staffatt('clockout' , this)">
                            <i class="fa fa-clock" aria-hidden="true"></i>&nbsp;Clock Out</button>
                        @else
                        <button type="button"
                            class="btn btn-success waves-effect waves-float waves-light clock_btn ml-1"
                            onclick="staffatt('clockin' , this)">
                            <i class="fa fa-clock" aria-hidden="true"></i>&nbsp;Clock In</button>
                        @endif
                    </div>
                </div>

            </div>
        </div>

        <div class="content-body">
            <section id="statistics-card">
                <div class="row match-height">
                    <div class="col-lg-12 col-12">
                        <div class="card card-statistics">
                            <div class="card-header" style="padding: 1rem 0 0.6rem 1rem;display:unset !important">

                                   <div class="col-md-12">
                                    <i class="fas fa-filter pull-right" onclick="openfilter()"
                                    style="position: relative;right: 10px;cursor: pointer;"></i>
                                   </div>
                                    <div class="col-md-12 mt-2">
                                        <div class="row pull-right openfilter" style="display: none">
                                        <div class="d-flex align-items-center pull-left">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14"
                                                viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                                stroke-linecap="round" stroke-linejoin="round"
                                                class="feather feather-calendar font-medium-2">
                                                <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                                                <line x1="16" y1="2" x2="16" y2="6"></line>
                                                <line x1="8" y1="2" x2="8" y2="6"></line>
                                                <line x1="3" y1="10" x2="21" y2="10"></line>
                                            </svg>
                                            <input type="text"
                                                class="form-control flat-picker bg-transparent border-0 shadow-none flatpickr-input active"
                                                placeholder="MM-DD-YYYY To MM-DD-YYYY" readonly="readonly"
                                                style="width: 250px;">
                                                <button type="button"
                                            class="btn btn-primary waves-effect waves-float waves-light pull-left">Filter</button>

                                        </div>


                                    <div class="col-md-2">


                                    </div>
                                </div>
                                </div>

                            </div>
                            <hr class="openfilter" style="display: none">
                            <div class="card-body statistics-body">
                                <div class="row">
                                    <div class="col-md-3 col-sm-3 col-6 mb-2 mb-md-0">
                                        <div class="d-flex flex-row">
                                            <div class="avatar bg-light-primary me-1" id="dash_btns">
                                                <div class="avatar-content">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                        viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                                        class="feather feather-trending-up avatar-icon">
                                                        <polyline points="23 6 13.5 15.5 8.5 10.5 1 18"></polyline>
                                                        <polyline points="17 6 23 6 23 12"></polyline>
                                                    </svg>
                                                </div>
                                            </div>
                                            <div class="my-auto">
                                                <a href="{{url('billing/home')}}">
                                                    <h3 class="fw-bolder mb-0">{{$orders}}</h3>
                                                    <p class="card-text font-small-10 mb-0">Orders</p>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3 col-sm-3 col-6 mb-2 mb-md-0">
                                        <div class="d-flex flex-row">
                                            <div class="avatar bg-light-info me-1" id="dash_btns">
                                                <div class="avatar-content">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                        viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                                        class="feather feather-users avatar-icon">
                                                        <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                                                        <circle cx="9" cy="7" r="4"></circle>
                                                        <path d="M23 21v-2a4 4 0 0 0-3-3.87"></path>
                                                        <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                                                    </svg>
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
                                    <div class="col-md-3 col-sm-3 col-6 mb-2 mb-sm-0">
                                        <div class="d-flex flex-row">
                                            <div class="avatar bg-light-danger me-1" id="dash_btns">
                                                <div class="avatar-content">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                        viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                                        class="feather feather-box avatar-icon">
                                                        <path
                                                            d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z">
                                                        </path>
                                                        <polyline points="3.27 6.96 12 12.01 20.73 6.96"></polyline>
                                                        <line x1="12" y1="22.08" x2="12" y2="12"></line>
                                                    </svg>
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
                                    <div class="col-md-3 col-sm-3 col-6">
                                        <div class="d-flex flex-row">
                                            <div class="avatar bg-light-success me-1" id="dash_btns">
                                                <div class="avatar-content">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                        viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                                        class="feather feather-shopping-bag avatar-icon">
                                                        <path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z">
                                                        </path>
                                                        <line x1="3" y1="6" x2="21" y2="6"></line>
                                                        <path d="M16 10a4 4 0 0 1-8 0"></path>
                                                    </svg>
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
                                <div class="row mt-1">
                                    <div class="col-md-3 col-sm-3 col-6 mb-2 mb-md-0" >
                                        <div class="d-flex flex-row">
                                            <div class="avatar bg-light-info me-1" id="dash_btns">
                                                <div class="avatar-content">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                        viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                                        class="feather feather-layers avatar-icon">
                                                        <polygon points="12 2 2 7 12 12 22 7 12 2"></polygon>
                                                        <polyline points="2 17 12 22 22 17"></polyline>
                                                        <polyline points="2 12 12 17 22 12"></polyline>
                                                    </svg>
                                                </div>
                                            </div>
                                            <div class="my-auto">
                                                <a href="{{url('billing/home')}}">
                                                    <h3 class="fw-bolder mb-0">{{$orders}}</h3>
                                                    <p class="card-text font-small-10 mb-0">Subscriptions</p>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3 col-sm-3 col-6 mb-2 mb-md-0" >
                                        <div class="d-flex flex-row">
                                            <div class="avatar bg-light-secondary me-1" id="dash_btns">
                                                <div class="avatar-content">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                        viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                                        class="feather feather-user-check avatar-icon">
                                                        <path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                                                        <circle cx="8.5" cy="7" r="4"></circle>
                                                        <polyline points="17 11 19 13 23 9"></polyline>
                                                    </svg>
                                                </div>
                                            </div>
                                            <div class="my-auto">
                                                <a href="{{url('billing/home')}}">
                                                    <h3 class="fw-bolder mb-0">{{$orders}}</h3>
                                                    <p class="card-text font-small-10 mb-0">Total Subscriptions</p>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3 col-sm-3 col-6 mb-2 mb-sm-0">
                                        <div class="d-flex flex-row">
                                            <div class="avatar bg-light-primary me-1" id="dash_btns">
                                                <div class="avatar-content">

                                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                        viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                                        class="feather feather-sliders avatar-icon">
                                                        <line x1="4" y1="21" x2="4" y2="14"></line>
                                                        <line x1="4" y1="10" x2="4" y2="3"></line>
                                                        <line x1="12" y1="21" x2="12" y2="12"></line>
                                                        <line x1="12" y1="8" x2="12" y2="3"></line>
                                                        <line x1="20" y1="21" x2="20" y2="16"></line>
                                                        <line x1="20" y1="12" x2="20" y2="3"></line>
                                                        <line x1="1" y1="14" x2="7" y2="14"></line>
                                                        <line x1="9" y1="8" x2="15" y2="8"></line>
                                                        <line x1="17" y1="16" x2="23" y2="16"></line>
                                                    </svg>
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
                                    <div class="col-md-3 col-sm-3 col-6">
                                        <div class="d-flex flex-row">
                                            <div class="avatar bg-light-warning me-1" id="dash_btns">
                                                <div class="avatar-content">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                        viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                                        class="feather feather-user-x avatar-icon">
                                                        <path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                                                        <circle cx="8.5" cy="7" r="4"></circle>
                                                        <line x1="18" y1="8" x2="23" y2="13"></line>
                                                        <line x1="23" y1="8" x2="18" y2="13"></line>
                                                    </svg>
                                                </div>
                                            </div>
                                            <div class="my-auto">
                                                <a href="{{url('billing/home')}}">
                                                    <h3 class="fw-bolder mb-0">0</h3>
                                                    <p class="card-text font-small-10 mb-0">Cancellations</p>
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
                            <div class="card-body" id="helpdesk">
                                <div class="row">
                                    <div class="col-lg-6 col-md-6 col-12" id="helpDeskSearch">
                                        <div class="search-input open">
                                            <div class="search-input-icon">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-search"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
                                            </div>
                                                <input class="form-control "  id="ticket_search" type="text" placeholder="Search Example - ABC-123-4321" tabindex="-1" data-search="search">
                                                <ul class="search-list search-list-main"></ul>
                                        </div>
                                    </div>
                                </div>
                                <form action="javascript:void(0);" class="form">
                                    <div class="row mt-6">
                                        <div class="col-12 mb-2">
                                            <div class="mb-2">
                                                <form class="d-flex w-100 pb-3 position-relative"
                                                    action="search-ticket-result" method="post" id="search-ticket"
                                                    autocomplete="off">
                                                    <input type="text" class="form-control" id="tsearch" name="id"
                                                        placeholder="Search Example - ABC-123-4321">
                                                    <i class="fas fa-circle-notch fa-spin text-primary" id="tkt_loader"
                                                        style="position: absolute; top:75px;font-size:1.2rem; right:30px;display:none"
                                                        aria-hidden="true"></i>
                                                </form>

                                            </div>
                                            <div id="show_ticket_results"></div>
                                        </div>

                                        {{-- <div id="full-view"> --}}
                                        <div class="d-grid col-lg-4 col-4" >
                                            <a href="{{route('addTicketPage')}}">
                                                <div
                                                    class="card card_shadow bg-success card_back border-dark card-hover">
                                                    <div class="card-header" style="display: unset;padding: 0.5rem 0.5rem !important">
                                                        <div class="text-center">
                                                            <h2 class="fw-bolder mb-0"><span class="fas fa-plus"></span>
                                                            </h2>
                                                            <h5 class="card-text">Create Ticket</h5>
                                                        </div>

                                                    </div>
                                                </div>
                                            </a>
                                        </div>
                                        <div class="d-grid col-lg-4 col-4" >
                                            <a href="{{asset('/ticket-manager')}}">
                                                <div
                                                    class="card card_shadow card_back border-info card-hover text-center">
                                                    <div class="card-header" style="display: unset;padding: 0.5rem 0.5rem !important">
                                                        <div class="">
                                                            <h2 class="fw-bolder mb-0">{{$total_tickets_count}}</h2>
                                                            <h5 class="card-text text-info">All Tickets</h5>
                                                        </div>

                                                    </div>
                                                </div>
                                            </a>
                                        </div>
                                        <div class="d-grid col-lg-4 col-4" >
                                            <a href="{{asset('/ticket-manager/self')}}">
                                                <div
                                                    class="card card_shadow card_back border-success card-hover text-center">
                                                    <div class="card-header" style="display: unset;padding: 0.5rem 0.5rem !important">
                                                        <div class="">
                                                            <h2 class="fw-bolder mb-0">{{$my_tickets_count}}</h2>
                                                            <h5 class="card-text text-success">My Tickets</h5>
                                                        </div>

                                                    </div>
                                                </div>
                                            </a>
                                        </div>
                                        <div class="d-grid col-lg-4 col-4" >
                                            <a href="{{asset('/ticket-manager/open')}}">
                                                <div
                                                    class="card card_shadow card_back border-warning card-hover text-center">
                                                    <div class="card-header" style="display: unset;padding: 0.5rem 0.5rem !important">
                                                        <div class="">
                                                            <h2 class="fw-bolder mb-0">{{$open_tickets_count}}</h2>
                                                            <h5 class="card-text text-warning">Open</h5>
                                                        </div>

                                                    </div>
                                                </div>
                                            </a>
                                        </div>
                                        <div class="d-grid col-lg-4 col-4" id="unassign_padd" >
                                            <a href="{{asset('/ticket-manager/unassigned')}}">
                                                <div
                                                    class="card card_shadow card_back border-primary card-hover text-center">
                                                    <div class="card-header" style="display: unset;padding: 0.5rem 0.5rem !important">
                                                        <div class="">
                                                            <h2 class="fw-bolder mb-0">{{$unassigned_tickets_count}}
                                                            </h2>
                                                            <h5 class="card-text text-primary">Unassigned</h5>
                                                        </div>

                                                    </div>
                                                </div>
                                            </a>
                                        </div>
                                        <div class="d-grid col-lg-4 col-4" >
                                            <a href="{{asset('/ticket-manager/overdue')}}">
                                                <div
                                                    class="card card_shadow card_back border-danger card-hover text-center">
                                                    <div class="card-header" style="display: unset;padding: 0.5rem 0.5rem !important">
                                                        <div class="">
                                                            <h2 class="fw-bolder mb-0">{{$late_tickets_count}}</h2>
                                                            <h5 class="card-text text-danger">Overdue</h5>
                                                        </div>

                                                    </div>
                                                </div>
                                            </a>
                                        </div>
                                    {{-- </div> --}}
                                    </div>
                                </form>
                            </div>
                        </div>


                        <div class="col-lg-12 col-md-12 col-12">
                            <div class="card">
                                <div class="card-body">
                                    <h4 class="card-title">Recent Activity</h4>

                                    <div class="table-responsive">
                                        <table id="ticket-logs-list"
                                            class="table table-striped table-bordered no-wrap ticket-table-list w-100">
                                            <thead>
                                                <tr>
                                                    <!-- <th width="20">ID</th> -->
                                                    <th>Activity</th>
                                                    <th></th>
                                                    <th></th>
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

                                 <div class="row">
                                    <div class="col-lg-6 col-md-6 col-12" id="Customer">
                                        <div class="search-input-customer open">
                                            <div class="search-input-icon">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-search"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
                                            </div>
                                                <input class="form-control" id="customer" type="text" placeholder="Search Example - ABC-123-4321" tabindex="-1" data-search="search">
                                                <ul class="search-list-customer search-list-main"></ul>
                                        </div>
                                    </div>

                                    {{-- <div class="col-lg-6 col-md-6 col-12">
                                        <div class="search-input-1 open">
                                            <div class="search-input-icon">
                                                <i data-feather="search"></i>
                                            </div>
                                                <input class="form-control input" type="text" placeholder="Search Example - ABC-123-4321" tabindex="-1" data-search="search-1">
                                                <ul class="search-list-1 search-list-main-1"></ul>
                                        </div>
                                    </div> --}}
                                </div>
                                <form action="javascript:void(0);" class="form">
                                    <div class="row">
                                        <div class="col-12">
                                            <div class="mb-2">
                                                <form class="d-flex w-100 position-relative"
                                                    action="{{asset('/search-customer')}}" method="post"
                                                    id="search-customer" autocomplete="off">
                                                    <input type="text" class="form-control text-dark" id="csearch"
                                                        name="id" placeholder="Search Customer">
                                                    <i class="fas fa-circle-notch fa-spin text-primary" id="cust_loader"
                                                        style="position: absolute; top:75px; font-size:1.2rem; right:35px; display:none"></i>
                                                </form>

                                            </div>
                                            <div id="search_customer_result"></div>
                                        </div>

                                    </div>
                                </form>
                            </div>
                        </div>

                        <div class="card">
                            <div class="card-header" style="display: unset">
                                <h4 class="card-title">Flagged <span class="float-end"><i class="fas fa-flag"
                                            style="color:#fd7e14;"></i></span></h4>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="table-responsive">
                                            <table class="table table-bordered table-hover" id="flagged_ticket_table">
                                                <thead>
                                                    <tr>
                                                        <th>
                                                            <div class="text-center">
                                                                <input type="checkbox" name="select_all[]"
                                                                    id="select-all">
                                                            </div>
                                                        </th>
                                                        <th></th>
                                                        <th>Status</th>
                                                        <th>Subject</th>
                                                        <th>Ticket ID</th>
                                                        <th>Priority</th>
                                                        <th>Customer</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <!-- <tr>
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
                                                </tr> -->
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
                                <div class="row px-2" id="active-users">
                                    @foreach($users as $user)
                                    <div class="col-md-1 col-sm-3 col-3 mb-1" style="margin-right: 1rem">
                                        @if($user->profile_pic != "" && $user->profile_pic != null)
                                        @if(file_exists( getcwd() .'/'. $user->profile_pic ))
                                        <span class="avatar" >
                                            <a href="{{url('profile')}}/{{$user->id}}" data-bs-toggle="tooltip"
                                                data-placement="top" title="{{$user->name}}">
                                                <img src="{{ request()->root() .'/'. $user->profile_pic}}"
                                                    alt="'s Photo" class="rounded-circle" width="50" height="50">
                                            </a>
                                            <span id="user-status-{{$user->id}}">
                                                <span class="avatar-status-offline" ></span>
                                            </span>
                                        </span>
                                        @else
                                        <span class="avatar">
                                            <a href="{{url('profile')}}/{{$user->id}}" data-bs-toggle="tooltip"
                                                data-placement="top" title="{{$user->name}}">
                                                <img src="{{asset($file_path . 'default_imgs/customer.png')}}"
                                                    alt="'s Photo" class="rounded-circle avatar" width="50px"
                                                    height="50">
                                            </a>
                                            <span id="user-{{$user->id}}">
                                                <span class="avatar-status-offline" ></span>
                                            </span>
                                        </span>
                                        @endif
                                        @else
                                        <span class="avatar">
                                            <a href="{{url('profile')}}/{{$user->id}}" data-bs-toggle="tooltip"
                                                data-placement="top" title="{{$user->name}}">
                                                <img src="{{asset($file_path . 'default_imgs/customer.png')}}"
                                                    alt="'s Photo" class="rounded-circle avatar" width="50px"
                                                    height="50">
                                            </a>
                                            <span id="user-{{$user->id}}">
                                                <span class="avatar-status-offline" ></span>
                                            </span>
                                        </span>
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
                                                    <h4 class="fw-bolder">Total Staff</h4>
                                                    <h3 class="mb-0" style="text-align: center">{{$staff_count}}</h3>
                                                </div>
                                                <div>
                                                    <h4 class="fw-bolder">Active staff</h4>
                                                    <h3 class="mb-0" style="text-align: center">{{$staff_active_count}}
                                                    </h3>
                                                </div>
                                                <div>
                                                    <h6 class="fw-bolder">Off Clock Staff</h6>
                                                    <h3 class="mb-0" style="text-align: center">
                                                        {{$staff_inactive_count}}</h3>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="card-datatable">
                                            <div class="table-responsive">
                                                <table class="table table-hover no-wrap" id="staff_table">
                                                    <thead class="table_head_back">
                                                        <tr>
                                                            <th class="text-center border-0">#</th>
                                                            <th>Name</th>
                                                            <th>Status</th>
                                                            <th>DATE</th>
                                                            <th>Clock In</th>
                                                            <th>Clock Out</th>
                                                            <th>Worked Hours</th>

                                                        </tr>
                                                    </thead>
                                                    <tbody id="showstaffdata">
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
                        <div class="card-header font-weight-bold" style="font-size: 20px;"><strong>Follow-Up
                                Details</strong></div>
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
                                                    <!-- <th>
                                            <div class="custom-control custom-checkbox">
                                                <input type="checkbox" class="custom-control-input" id="customCheck1">
                                                <label class="custom-control-label" for="customCheck1"></label>
                                            </div>
                                        </th> -->
                                                    <th> Sr# </th>
                                                    <th> Subject </th>
                                                    <th> Ticket ID </th>
                                                    <th> Follow-Up </th>
                                                    <th> Assigned tech </th>
                                                    <th> Created By </th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($followUps as $up)
                                                <tr>
                                                    <!-- <td></td> -->
                                                    <td> {{$loop->iteration}} </td>
                                                    <td>
                                                        @if($up->ticket != null)
                                                        <a href="{{url('ticket-details')}}/{{$up->ticket->coustom_id}}">
                                                            {{$up->ticket->subject != null ? $up->ticket->subject : '-'}}
                                                        </a>
                                                        @else
                                                        <span> - </span>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        @if($up->ticket != null)
                                                        <a href="{{url('ticket-details')}}/{{$up->ticket->coustom_id}}">
                                                            {{$up->ticket->coustom_id != null ? $up->ticket->coustom_id : '-'}}
                                                        </a>
                                                        @else
                                                        <span> - </span>
                                                        @endif
                                                    </td>
                                                    <td> {{$up->schedule_time}} {{$up->schedule_type}} </td>
                                                    <td>
                                                        <a href="{{url('profile')}}/{{$up->follow_up_assigned_to}}">
                                                            {{$up->tech_name}} </a>
                                                    </td>
                                                    <td> {{$up->creator_name}}</td>

                                                </tr>
                                                @endforeach
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

@endsection
@section('scripts')


<!-- <script src="{{asset($file_path . 'app-assets/vendors/js/calendar/fullcalendar.min.js')}}"></script> -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment-timezone/0.5.34/moment-timezone.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment-timezone/0.5.34/moment-timezone-utils.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment-timezone/0.5.34/moment-timezone-with-data-10-year-range.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment-timezone/0.5.34/moment-timezone-with-data-1970-2030.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment-timezone/0.5.34/moment-timezone-with-data.min.js"></script>
{{-- <script src="{{asset($file_path . 'app-assets/daterangepicker.js')}}"></script> --}}
@include('js_files.dashboardjs')
@include('js_files.searchInputJs')
@include('js_files.help_desk.ticket_manager.flag_ticketJs')

{{-- <script src="https://js.pusher.com/7.0.2/pusher.min.js"></script> --}}


<script type="text/javascript">
$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
    }
});

searchInputField("helpDeskSearch","ticket_search","{{url('search-ticket')}}")
searchInputFieldCustomer("Customer","customer","{{url('search-customer')}}")


var system_date_format = $("#system_date_format").val();
// let data = {!! json_encode($staff_att_data) !!};
function HmsToSeconds(hms) {
    // var hms = '02:04:33';
    var a = hms.split(':'); // split it at the colons

    // minutes are worth 60 seconds. Hours are worth 60 minutes.
    var seconds = (+a[0]) * 60 * 60 + (+a[1]) * 60 + (+a[2]);
    return seconds;
}

function openfilter() {
    $('.openfilter').toggle('slow');
}
</script>
@endsection
