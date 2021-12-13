@extends('layouts.staff-master-layout')
@section('body-content')
<link href="{{asset('assets/libs/chartist/dist/chartist.min.css')}}" rel="stylesheet">
    <link href="{{asset('assets/dist/js/pages/chartist/chartist-init.css')}}" rel="stylesheet">
    <link href="{{asset('assets/extra-libs/css-chart/css-chart.css')}}" rel="stylesheet">
    <link href="{{asset('assets/libs/chartist-plugin-tooltips/dist/chartist-plugin-tooltip.css')}}" rel="stylesheet">
    <link href="{{asset('assets/extra-libs/jvector/jquery-jvectormap-2.0.2.css')}}" rel="stylesheet" />
    <link href="{{asset('assets/libs/fullcalendar/dist/fullcalendar.min.css')}}" rel="stylesheet" />
<style>
    a:hover{
        color: #009efb;
    }
    .version{
        padding-bottom:25px;
        padding-top:25px;
        border-bottom: 3px dashed #adadad;
        
    }
    .lab-content{
        margin-left:20px;
    }
    .lab-content i{
        font-size:12px;
        margin-right:4px;
    }
    .lab-content p{
        margin-bottom:5px;
    }
    .highlight-content{
        color: #fc4b6c;
    font-size: 15px;
    padding: 2px;
        background-color:#fcb3eb;
    }
</style>
<div class="page-breadcrumb">
                <div class="row">
                    <div class="col-md-5 align-self-center">
                        <h4 class="page-title">Change Log</h4>
                        <div class="d-flex align-items-center">
                            <nav aria-label="breadcrumb">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="#">Home</a></li>
                                    <li class="breadcrumb-item active" aria-current="page">Change Log</li>
                                </ol>
                            </nav>
                        </div>
                    </div>
                   
                </div>
            </div>
            <!-- ============================================================== -->
            <!-- End Bread crumb and right sidebar toggle -->
            <!-- ============================================================== -->
            <!-- ============================================================== -->
            <!-- Container fluid  -->
            <!-- ============================================================== -->
            <div class="container-fluid">
                <div class="card">
                    <div class="card-body">
                        <H2>Legends:</H2>
                        <p>
                            <i class=" fas fa-plus btn btn-success  "></i> New Feature 
                            <i class=" fas fa-arrow-up btn btn-info ml-2"></i> Improvement or Update   
                            <i class="fas fa-exclamation btn btn-secondary ml-2"></i> Bug / Issue Fix 
                            <i class="fas fa-times btn btn-danger ml-2"></i> Discontinued or Removed
                        </p>
                    </div>
                </div>

                <div class="card">
                    <div class="card-body">
                        <div class="version">
                            <div class="label-head">
                                <h3 class="mb-0">v3.17</h3>
                                <p class="mute">on Mar 23, 1997</p>
                            </div>
                            <div class="lab-content">
                                <p><i class=" fas fa-plus btn btn-success  "></i> This is Plus Sign </p>
                                <p><i class=" fas fa-arrow-up btn btn-info "></i> Uper wala arrow hai ye </p>  
                                <p><i class="fas fa-exclamation btn btn-secondary "></i> This is Exclaimation Sign</p> 
                                <p><i class="fas fa-times btn btn-danger "></i> This is Cross Sign </p>
                            </div>
                        </div>
                        <div class="version">
                            <div class="label-head">
                                <h3 class="mb-0">v3.2</h3>
                                <p class="mute">on Mar 23, 2007</p>
                            </div>
                            <div class="lab-content">
                                <p><i class=" fas fa-plus btn btn-success  "></i> This is Plus Sign </p>
                                <p><i class=" fas fa-plus btn btn-success  "></i> This is <span class="highlight-content">Plus<span> Sign </p>
                                <p><i class=" fas fa-plus btn btn-success  "></i> This is Plus Sign </p>
                                <p><i class=" fas fa-plus btn btn-success  "></i> This is Plus Sign </p>  
                                <p><i class="fas fa-exclamation btn btn-secondary "></i> This is Exclaimation Sign</p> 
                                <p><i class="fas fa-exclamation btn btn-secondary "></i> This is Exclaimation Sign</p> 
                                <p><i class="fas fa-exclamation btn btn-secondary "></i> This is Exclaimation Sign</p> 

                                <p><i class="fas fa-exclamation btn btn-secondary "></i> This is Exclaimation Sign</p> 
                                <p><i class="fas fa-times btn btn-danger "></i> This is Cross Sign </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            @endsection
@section('scripts')
<!--This page JavaScript -->
<script src="{{asset('assets/libs/moment/moment.js')}}"></script>
    <script src="{{asset('assets/libs/fullcalendar/dist/fullcalendar.min.js')}}"></script>
    <script src="{{asset('assets/dist/js/pages/calendar/cal-init.js')}}"></script>
    <script src="{{asset('assets/libs/chartist/dist/chartist.min.js')}}"></script>
    <script src="{{asset('assets/libs/chartist-plugin-tooltips/dist/chartist-plugin-tooltip.min.js')}}"></script>
    <script src="{{asset('assets/libs/echarts/dist/echarts.min.js')}}"></script>
    <script src="{{asset('assets/extra-libs/jvector/jquery-jvectormap-2.0.2.min.js')}}"></script>
    <script src="{{asset('assets/extra-libs/jvector/jquery-jvectormap-world-mill-en.js')}}"></script>
    <script src="{{asset('assets/dist/js/pages/dashboards/dashboard4.js')}}"></script>
@endsection
