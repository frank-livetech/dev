@extends('layouts.staff-master-layout')
@section('body-content')

<style>
    .table th {
        padding-top: 16px !important;
        padding-bottom: 16px !important;
        font-size: 1rem;
    }

    .pro-pic {
        padding-top: 10px !important;
    }
</style>
<link rel="stylesheet" href="https://unpkg.com/multiple-select@1.5.2/dist/multiple-select.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/1.10.23/css/jquery.dataTables.min.css">

<div class="page-breadcrumb">
    <div class="row">
        <div class="col-md-5 align-self-center">
            <!--<h3 class="page-title">Dashboard</h3>-->
            <div class="d-flex align-items-center">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Leave Management</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
</div>

<div class="card m-3 p-3">
    <div class="row">
        <div class="container-fluid">
            <table class="table table-hover table-bordered w-100" id="leaves-table">
                <thead>
                    <tr>
                        <th width="5%">#</th>
                        <th width="10%"> Requested By</th>
                        <th width="50%"> Reason</th>
                        <th width="10%">Start Date</th>
                        <th width="10%">End Date</th>
                        <th width="10%">Status</th>
                        <th width="10%">Action</th>
                    </tr>
                </thead>
                <tbody>

                </tbody>
            </table>

            <div class="loader_container" id="leaves-loader" style="display:none">
                <div class="loader"></div>
            </div>
        </div>
    </div>
</div>


@endsection
@section('scripts')
    @include('js_files.leave.leaveJs')
@endsection