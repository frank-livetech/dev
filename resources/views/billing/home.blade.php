@extends('layouts.staff-master-layout')
<style>
    .select2-selection,
    .select2-container--default,
    .select2-selection--single {
        border-color: #848484 !important;
    }

    .head-cou p {
        font-weight: 400;
        color: #b1b4b1;
    }

    .f-400 {
        font-weight: 400;
    }

    .table th {
        padding-top: 16px !important;
        padding-bottom: 16px !important;
        font-size: 1rem;
    }
    .nav-item .active{
        color:#fff !important;
    }
    .fa-file-pdf{
        padding-top: 6px;
    }
</style>
@section('body-content')

<div class="page-breadcrumb">
    <div class="row">
        <div class="col-md-5 align-self-center">

            <div class="d-flex align-items-center">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{route('home')}}">Home</a></li>
                        <li class="breadcrumb-item" aria-current="page">Billing</li>
                        <li class="breadcrumb-item active" aria-current="page">Home</li>
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

<div class="container-fluid">
    <div class="row">
        <div class="col-12 mb-2">
            <a href="{{url('invoice-maker')}}" class="btn btn-primary float-right">Invoice Maker</a>
        </div>
        <div class="col-lg-3 col-md-6">
            <div class="card border-info ">
                <div class="card-body p-3">

                    <div class="d-flex no-block align-items-cente">
                        <div>
                            <h2> {{$all_order}}</h2>
                            <h6 class="text-info">Total Orders</h6>
                        </div>
                        <div class="ml-auto">
                            <span class="text-info display-6"><i class="ti-notepad"></i></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6">
            <div class="card border-danger ">
                <div class="card-body p-3">
                    
                    <div class="d-flex no-block align-items-cente">
                        <div>
                            <h2> {{$pending_payment_order}}</h2>
                            <h6 class="text-danger">Pending Payment Orders</h6>
                        </div>
                        <div class="ml-auto">
                            <span class="text-danger display-6"><i class="ti-stats-down"></i></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6">
            <div class="card border-warning">
                <div class="card-body p-3">
                    <div class="d-flex no-block align-items-center">
                        <div>
                            <h2> {{$processing_order}}</h2>
                            <h6 class="text-warning">Processing Orders</h6>
                        </div>
                        <div class="ml-auto">
                            <span class="text-warning display-6"><i class="ti-stats-up"></i></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6">
            <div class="card  border-success ">
                <div class="card-body p-3">
                   
                    <div class="d-flex no-block align-items-center">
                        <div>
                            <h2> {{$completed_order}}</h2>
                            <h6 class="text-success">Completed Orders</h6>
                        </div>
                        <div class="ml-auto">
                            <span class="text-success display-6"><i class="ti-clipboard"></i></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <ul class="nav nav-tabs nav-bordered mb-3 customtab">
                        <li class="nav-item">
                            <a href="#order-b1" data-toggle="tab" aria-expanded="false" class="nav-link active">
                                <i class="mdi mdi-order-variant d-lg-none d-block mr-1"></i>
                                <span class="d-none d-lg-block">Order</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="#subscription-b1" data-toggle="tab" aria-expanded="true" class="nav-link">
                                <i class="mdi mdi-account-circle d-lg-none d-block mr-1"></i>
                                <span class="d-none d-lg-block">Subscription</span>
                            </a>
                        </li>
                    </ul>
                    <div class="tab-content">
                        <div class="tab-pane active" id="order-b1">
                            <div class="d-flex">
                                <div class="form-group mr-2">
                                    <select class="form-control" name="" id="all_statuses"></select>
                                </div>
                                <div class="form-group mr-2">
                                    <select class="form-control" name="" id="all_dates"></select>
                                </div>
                                <div class="form-group mr-2">
                                    <input type="text" class="form-control" name="" id="reg_customers" placeholder="Filter by registered customer">
                                </div>
                                <div class="form-group mr-2">
                                    <select class="form-control" name="" id="all_types"></select>
                                </div>
                                <div class="form-group">
                                    <button class="btn btn-primary" onclick="orderFilters()">Filter</button>
                                </div>
                            </div>
                            <div class="table-responsive">
                                <table id="orders_table" class="table table-striped table-hover table-bordered no-wrap text-center w-100">
                                    <thead class="bg-info text-white">
                                        <tr>
                                            <th>Sr #</th>
                                            <th>Order Id</th>
                                            <th>Customer </th>
                                            <th>Date</th>
                                            <th>Status </th>
                                            <th>Grand Total</th>
                                            <th>From</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="tab-pane show" id="subscription-b1">
                            <div class="d-flex">
                                <div class="form-group mr-2">
                                    <select class="form-control" name="" id="all_dates_subs"></select>
                                </div>
                                <div class="form-group mr-2">
                                    <select class="form-control" name="" id="all_pm_subs"></select>
                                </div>
                                {{-- <div class="form-group mr-2">
                                    <input type="text" class="form-control" name="" id="s_pd" placeholder="Search for a product...">
                                </div> --}}
                                <div class="form-group mr-2">
                                    <input class="form-control" name="" id="s_cust" placeholder="Search for a customer...">
                                </div>
                                <div class="form-group">
                                    <button class="btn btn-primary" onclick="subsFilters()">Filter</button>
                                </div>
                            </div>
                            <div class="table-responsive">
                                <table id="subs_table" class="table table-striped table-hover table-bordered no-wrap text-center w-100">
                                    <thead class="bg-info text-white">
                                        <tr>
                                            <th>Sr #</th>
                                            <th>Status</th>
                                            <th>Subscription</th>
                                            <th>Items</th>
                                            <th>Total</th>
                                            <th>Start Date</th>
                                            <th>Trial End</th>
                                            <th>Next Payment</th>
                                            <th>Last Order Date</th>
                                            <th>End Date</th>
                                            <th>Orders</th>
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
@endsection
@section('scripts')
@include('js_files.billing.homeJs')

@endsection