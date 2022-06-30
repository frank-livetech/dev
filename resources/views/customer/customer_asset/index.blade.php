@extends('customer.layout.customer_master')
@php
        $file_path = Session::get('is_live') == 1 ? 'public/' : '/';
        $path = Session::get('is_live') == 1 ? 'public/system_files/' : 'system_files/';
@endphp
<style>
     td.details-control {
            background: url('{{url($file_path . "default_imgs/details_open.png")}}') no-repeat center center !important;
            cursor: pointer;
            z-index: 9999 !important;
        }
        tr.shown td.details-control {
            background: url('{{url($file_path ."default_imgs/details_close.png")}}') no-repeat center center !important;
            cursor: pointer;
            z-index: 9999 !important;
        }
</style>
@section('breadcrumb')
    <h2 class="content-header-title float-start mb-0">Dashboard</h2>
    <div class="breadcrumb-wrapper">
        <ol class="breadcrumb">
            <li class="breadcrumb-item active"><a href="">
                    My Asset
            </a>
            </li>
        </ol>
    </div>
@endsection
@section('body')
    <div class="content-body">
       <div class="row">
        <div class="col-lg-12 col-md-12">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-12 col-md-12">
                            <h4 class="card-title">Assets</h4>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table id="cust-asset-table-list"
                            class="table cust-asset-table-list">
                            <thead>
                                <tr>
                                    <th><div class="text-center"><input type="checkbox" id="checkAll" name="assets[]" value="0"></div></th>
                                    <th></th>
                                    <th>Asset Title</th>
                                    <th> Asset Type </th>
                                    <th>Company</th>
                                    <th> Customer</th>
                                    {{-- <th>Actions</th> --}}
                                </tr>
                            </thead>
        
                        </table>
        
                    </div>
                </div>
            </div>
        </div>
       </div>
    </div>


@endsection
@section('scripts')
@include('customer.Js.customer_assetJs')
@endsection