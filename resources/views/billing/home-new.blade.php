@extends('layouts.master-layout-new')
@section('body')
<style>
    .w-70{
        width:70%;
    }
    .w-30{
        width:30%;
    }
    .fa-file-pdf{
        padding: 0px 2px;
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
                        <h2 class="content-header-title float-start mb-0">Billing</h2>
                        <div class="breadcrumb-wrapper">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{route('home')}}">Home</a></li>
                                <li class="breadcrumb-item active" aria-current="page">Billing</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="content-body">
            @if($date_format) 
                <input type="hidden" id="system_date_format" value="{{$date_format}}">
            @else
                <input type="hidden" id="system_date_format" value="DD-MM-YYYY">
            @endif
            <div class="row">
                <div class="col-md-12 mb-2 text-end">
                    <a href="{{url('invoice-maker')}}" class="btn btn-primary">Invoice Maker</a>
                </div>
                <div class="col-lg-3 col-md-6">
                    <div class="card border-info ">
                        <div class="card-body">

                            <div class="d-flex no-block align-items-cente">
                                <div class="w-70">
                                    <h2> {{$all_order}}</h2>
                                    <h6 class="text-info">Total Orders</h6>
                                </div>
                                <div class="w-30 text-end">
                                    <span class="text-info display-6">
                                        <i data-feather='clipboard' style="height:3rem;width:3rem;"></i>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <div class="card border-danger ">
                        <div class="card-body">
                            
                            <div class="d-flex no-block align-items-cente">
                                <div class="w-70">
                                    <h2> {{$pending_payment_order}}</h2>
                                    <h6 class="text-danger">Pending Payment Orders</h6>
                                </div>
                                <div class="w-30 text-end">
                                    <span class="text-danger display-6">
                                    <i data-feather='trending-down' style="height:3rem;width:3rem;"></i></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <div class="card border-warning">
                        <div class="card-body">
                            <div class="d-flex no-block align-items-center">
                                <div class="w-70">
                                    <h2> {{$processing_order}}</h2>
                                    <h6 class="text-warning">Processing Orders</h6>
                                </div>
                                <div class="w-30 text-end">
                                    <span class="text-warning display-6">
                                    <i data-feather='trending-up' style="height:3rem;width:3rem;"></i></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <div class="card  border-success ">
                        <div class="card-body">
                        
                            <div class="d-flex no-block align-items-center">
                                <div class="w-70">
                                    <h2> {{$completed_order}}</h2>
                                    <h6 class="text-success">Completed Orders</h6>
                                </div>
                                <div class="w-30 text-end">
                                    <span class="text-success display-6">
                                    <i data-feather='trello' style="width:3rem;height:3rem;"></i></span>
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
                                    <a href="#order-b1" data-bs-toggle="tab" aria-expanded="false" class="nav-link active">
                                        <i class="mdi mdi-order-variant d-lg-none d-block mr-1"></i>
                                        <span class="d-none d-lg-block">Order</span>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="#subscription-b1" data-bs-toggle="tab" aria-expanded="true" class="nav-link">
                                        <i class="mdi mdi-account-circle d-lg-none d-block mr-1"></i>
                                        <span class="d-none d-lg-block">Subscription</span>
                                    </a>
                                </li>
                            </ul>
                            <div class="tab-content">
                                <div class="tab-pane active" id="order-b1">
                                    <div class="d-flex">
                                        <div class="form-group me-2">
                                            <select class="form-select" name="" id="all_statuses"></select>
                                        </div>
                                        <div class="form-group me-2">
                                            <select class="form-select" name="" id="all_dates"></select>
                                        </div>
                                        <div class="form-group me-2">
                                            <input type="text" class="form-control" name="" id="reg_customers" placeholder="Filter by registered customer">
                                        </div>
                                        <div class="form-group me-2">
                                            <select class="form-select" name="" id="all_types"></select>
                                        </div>
                                        <div class="form-group">
                                            <button class="btn btn-primary" onclick="orderFilters()">Filter</button>
                                        </div>
                                    </div>
                                    <div class="table-responsive">
                                        <table id="orders_table" class="table table-bordered">
                                            <thead class="bg-info">
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
                                        <div class="form-group me-2">
                                            <select class="form-select" name="" id="all_dates_subs"></select>
                                        </div>
                                        <div class="form-group me-2">
                                            <select class="form-select" name="" id="all_pm_subs"></select>
                                        </div>
                                        {{-- <div class="form-group me-2">
                                            <input type="text" class="form-control" name="" id="s_pd" placeholder="Search for a product...">
                                        </div> --}}
                                        <div class="form-group me-2">
                                            <input class="form-control" name="" id="s_cust" placeholder="Search for a customer...">
                                        </div>
                                        <div class="form-group">
                                            <button class="btn btn-primary" onclick="subsFilters()">Filter</button>
                                        </div>
                                    </div>
                                    <div class="table-responsive">
                                        <table id="subs_table" class="table table-bordered">
                                            <thead class="bg-info ">
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
    </div>
</div>
@endsection
@section('scripts')
@include('js_files.billing.homeJs')
@endsection