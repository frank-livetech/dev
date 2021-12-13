@extends('layouts.staff-master-layout')
@section('body-content')
<br />
<div class="page-breadcrumb">
    <div class="row">
        <div class="col-md-5 align-self-center">
            <!--<h3 class="page-title">Dashboard</h3>-->
            <div class="d-flex align-items-center">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{route('home')}}">Home</a></li>
                        <li class="breadcrumb-item active" aria-current="page">System info</li>
                    </ol>
                </nav>
            </div>
        </div>

    </div>
</div>
<div class="container-fluid">
    <div class="row">
        <!-- Column -->
        <div class="col-sm-12 col-md-6">
            <div class="card bg-success">
                <div class="card-body text-white">
                    <div class="d-flex flex-row">
                        <div class="align-self-center display-6"><i class="ti-settings"></i></div>
                        <div class="p-2 align-self-center">
                            <h4 class="mb-0 text-white">Live Tech Version</h4>

                        </div>
                        <div class="ml-auto align-self-center">
                            <h2 class="font-weight-medium mb-0 text-white">V 0.7</h2>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Column -->
        <!-- Column -->
        <div class="col-sm-12 col-md-6">
            <div class="card bg-info">
                <div class="card-body text-white">
                    <div class="d-flex flex-row">
                        <div class="display-6 align-self-center"><i class="ti-user"></i></div>
                        <div class="p-2 align-self-center">
                            <h4 class="mb-0 text-white">Active Users</h4>

                        </div>
                        <div class="ml-auto align-self-center">
                            <h2 class="font-weight-medium mb-0 text-white">6</h2>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Column -->
        <!-- Column -->
        <div class="col-sm-12 col-md-6">
            <div class="card bg-cyan">
                <div class="card-body text-white">
                    <div class="d-flex flex-row">
                        <div class="display-6 align-self-center"><i class="ti-timer"></i></div>
                        <div class="p-2 align-self-center">
                            <h4 class="mb-0 text-white">Responsive Time</h4>

                        </div>
                        <div class="ml-auto align-self-center">
                            <h2 class="font-weight-medium mb-0 text-white">0 ms</h2>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Column -->
        <!-- Column -->
        <div class="col-sm-12 col-md-6">
            <div class="card bg-orange">
                <div class="card-body text-white">
                    <div class="d-flex flex-row">
                        <div class="display-6 align-self-center"><i class="ti-server"></i></div>
                        <div class="p-2 align-self-center">
                            <h4 class="mb-0 text-white">SQL Backup</h4>

                        </div>
                        <div class="ml-auto align-self-center">
                            <h2 class="font-weight-medium mb-0 text-white">193.07 KB</h2>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Column -->
        <div class="col-sm-12 col-md-6">
            <div class="card bg-danger">
                <div class="card-body text-white">
                    <div class="d-flex flex-row">
                        <div class="display-6 align-self-center"><i class="ti-settings"></i></div>
                        <div class="p-2 align-self-center">
                            <h4 class="mb-0 text-white">PHP Version</h4>

                        </div>
                        <div class="ml-auto align-self-center">
                            <h2 class="font-weight-medium mb-0 text-white">7.3.21</h2>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Column -->
        <div class="col-sm-12 col-md-6">
            <div class="card bg-inverse">
                <div class="card-body text-white">
                    <div class="d-flex flex-row">
                        <div class="display-6 align-self-center"><i class="ti-settings"></i></div>
                        <div class="p-2 align-self-center">
                            <h4 class="mb-0 text-white">MYSQL Version</h4>

                        </div>
                        <div class="ml-auto align-self-center">
                            <h2 class="font-weight-medium mb-0 text-white">5.5.5-10.3</h2>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Column -->

        <div class="col-lg-6">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Quick Operations</h4>
                </div>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Error Log</h4>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
<style>

</style>
