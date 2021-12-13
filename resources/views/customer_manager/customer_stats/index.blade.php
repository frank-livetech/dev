@extends('layouts.staff-master-layout')
@section('body-content')
<div class="page-breadcrumb">
    <div class="row">
        <div class="col-md-5 align-self-center">
            <!--<h4 class="page-title">Basic Table</h4>-->
            <div class="d-flex align-items-center">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{route('home')}}">Home</a></li>
                        <li class="breadcrumb-item" aria-current="page">Company Manager</li>
                        <li class="breadcrumb-item active" aria-current="page">Customer Stats</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
</div>
<div class="container-fluid" style="padding-bottom: 0px;">
    <div class="row">
                            <!-- Column -->
                            <div class="col-lg-2 col-md-6">
                                <div class="card border-bottom border-success">
                                    <div class="card-body">
                                        <div class="d-flex flex-row">
                                            <div class="ml-2 align-self-center">
                                                <h3>990</h3>
                                                <span class="text-success">Total Customers</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- Column -->
                            <!-- Column -->
                            <div class="col-lg-2 col-md-6">
                                <div class="card border-bottom border-success">
                                    <div class="card-body">
                                        <div class="d-flex flex-row">
                                            <div class="ml-2 align-self-center">
                                                <h3>900</h3>
                                                <span class="text-success">Active Csutomers</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- Column -->
                            <!-- Column -->
                            <div class="col-lg-2 col-md-6">
                                <div class="card border-bottom border-warning">
                                    <div class="card-body">
                                        <div class="d-flex flex-row">
                                            <div class="ml-2 align-self-center">
                                                <h3>90 </h3>
                                                <span class="text-warning">InActive Customers</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- Column -->
                            <!-- Column -->
                            <div class="col-lg-2 col-md-6">
                                <div class="card border-bottom border-info">
                                    <div class="card-body">
                                        <div class="d-flex flex-row">
                                            <div class="ml-2 align-self-center">
                                                <h3>10</h3>
                                                <span class="text-info">Active Contacts</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-2 col-md-6">
                                <div class="card border-bottom border-danger">
                                    <div class="card-body">
                                        <div class="d-flex flex-row">
                                            <div class="ml-2 align-self-center">
                                                <h3>1</h3>
                                                <span class="text-danger">InActive Contacts</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-2 col-md-6">
                                <div class="card border-bottom border-primary">
                                    <div class="card-body">
                                        <div class="d-flex flex-row">
                                            <div class="ml-2 align-self-center">
                                                <h3>10</h3>
                                                <span class="text-primary">Contacts Logged  In Today</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- Column -->
                        </div>
    <!--<div class="row">-->
    <!--    <div class="col-lg-12 col-md-12 col-sm-12">-->
    <!--        <div class="card">-->
    <!--            <div class="card-body">-->
                  
    <!--            </div>-->
    <!--        </div>           -->
    <!--    </div>-->
    <!--</div> -->
</div>    
@endsection