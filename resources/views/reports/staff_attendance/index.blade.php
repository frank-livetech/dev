@extends('layouts.staff-master-layout')
@section('body-content')
<link rel="stylesheet" href="https://unpkg.com/multiple-select@1.5.2/dist/multiple-select.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/1.10.23/css/jquery.dataTables.min.css">
<link rel="stylesheet" type="text/css" href="{{asset('assets/libs/pickadate/lib/themes/default.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('assets/libs/pickadate/lib/themes/default.date.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('assets/libs/pickadate/lib/themes/default.time.css')}}">
<style>
.table th {
    padding-top:20px !important;
    padding-bottom:20px !important;
    font-size:1rem;
}
</style>



<div class="page-breadcrumb">
    <div class="row">
        <div class="col-md-5 align-self-center">
            <!--<h3 class="page-title">Dashboard</h3>-->
            <div class="d-flex align-items-center">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="#">Reports</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Staff Manager</li>
                    </ol>
                </nav>
            </div>
        </div>

    </div>
</div>
<?php $count = 1;?>
<div class="container-fluid">

    <div class="row">

        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                        <div class="widget-body">
                            <div class="widget-main">
                                <div class="row">
                                    <div class="col-md-4">
                                        <label>Start Date & Time</label>
                                        <input type="datetime-local" id="start_date" class="form-control" required>
                                        <span class="text-danger" id="start_date"></span>
                                    </div>
                                    <div class="col-md-4">
                                        <label>End Date & Time</label>
                                        <input type="datetime-local" id="end_date" class="form-control" required>
                                        <span class="text-danger" id="end_date"></span>
                                    </div>
                                    <div class="col-md-4">
                                        <label>Staff</label>
                                        <div class="input-group">
                                        <select class="form-control col-sm-8" id="staff" required>
                                        </select>
                                        <span class="text-danger" id="staff"></span>
                                            <div class="col-sm-4 pr-0" style="text-align:right;">
                                                <button onclick="getStaffAttendance()" class="btn waves-effect waves-light btn-success" type="button">Search</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row pt-2">
                                    <div class="col-md-2">
                                        <div class="card mb-0">
                                            <div class="box p-2 rounded bg-primary text-center">
                                                <h5 class="font-weight-light text-white" id="avg_hours_in_day">00:00</h5>
                                                <h6 class="text-white">Avg Hours a Day</h6>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="card mb-0">
                                            <div class="box p-2 rounded bg-primary text-center">
                                                <h5 class="font-weight-light text-white" id="avg_hours">00:00</h5>
                                                <h6 class="text-white">Avg Hours a Week</h6>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="card mb-0">
                                            <div class="box p-2 rounded bg-info text-center">
                                                <h5 class="font-weight-light text-white" id="total_hours">00:00:00</h5>
                                                <h6 class="text-white">Total Hours</h6>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="card mb-0">
                                            <div class="box p-2 rounded bg-success  text-center">
                                                <h5 class="font-weight-light text-white">00</h5>
                                                <h6 class="text-white">Days without Checkin</h6>
                                            </div>
                                        </div>
                                    </div>
                                   
                              
                                </div>
                                <div class="row mt-3">
                                    <div class="col-12">
                                   
                                        <div class="table-responsive">                                           
                                            <table id="user_table_list" class="table table-hover table-striped table-bordered text-center" style="width:100%">
                                                <thead>
                                                    <tr>
                                                        <th>#</th>
                                                        <th>Name</th>
                                                        <th>Status</th>
                                                        <th>DATE</th>
                                                        <th>Clock In</th>
                                                        <th>Clock Out</th>
                                                        <th>Worked Hours</th>
                                                    </tr>
                                                </thead>
                                                <tbody></tbody>

                                                
                                            </table>

                                        </div>
                                    </div>
                                </div>

                                <div class="loader_container">
                                                    <div class="loader"></div>
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
<script src="{{asset('assets/libs/pickadate/lib/compressed/picker.js')}}"></script>
<script src="{{asset('assets/libs/pickadate/lib/compressed/picker.date.js')}}"></script>
<script src="{{asset('assets/libs/pickadate/lib/compressed/picker.time.js')}}"></script>
<script src="{{asset('assets/libs/pickadate/lib/compressed/legacy.js')}}"></script>
<script src="{{asset('assets/libs/moment/moment.js')}}"></script>
<script src="{{asset('assets/libs/daterangepicker/daterangepicker.js')}}"></script>
<script src="{{asset('assets/dist/js/pages/forms/datetimepicker/datetimepicker.init.js')}}"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@9"></script>
<script src="https://unpkg.com/multiple-select@1.5.2/dist/multiple-select.min.js"></script>
<script src="https://cdn.datatables.net/1.10.23/js/jquery.dataTables.min.js"></script>
<script src="{{asset('public/js/reports/staff_attendance.js').'?ver='.rand()}}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js" integrity="sha512-qTXRIMyZIFb8iQcfjXWCO8+M5Tbc38Qi5WzdPOYZHIlZpzBHG3L3by84BBBOiRGiEb7KKtAOAs5qYdUiZiQNNQ==" crossorigin="anonymous"></script>

<script>
    var staff_att_url = '{{url("get_staff_attendance")}}';
    var staff_list_url = '{{url("staff_list")}}';
</script>

@endsection
