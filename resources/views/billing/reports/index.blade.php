@extends('layouts.master-layout-new')
@section('Customer Manager','open')
@section('title', 'Company Lookup')
@section('Company Lookup','active')
@section('body')
<div class="app-content content">
    <div class="content-overlay"></div>
    <div class="header-navbar-shadow"></div>
    <div class="content-wrapper container-fluid p-0">
        <div class="content-header row">
            <div class="content-header-left col-md-12 col-12 mb-2">
                <div class="row breadcrumbs-top">
                    <div class="col-12">
                        <h2 class="content-header-title float-start mb-0">Reports
                            </li></h2>
                        <div class="breadcrumb-wrapper">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{route('home')}}">Home</a>
                                </li>
                                <li class="breadcrumb-item"><a>Billing</a>
                                </li>
                                <li class="breadcrumb-item active"><a href="javascript:location.reload()">Reports</a>
                                </li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
   
        <div class="app-content  ">
            <div class="content-overlay"></div>
            <div class="header-navbar-shadow"></div>
            <div class="content-wrapper">
                <div class="content-header row">
                </div>
                <div class="content-body">
                    <!-- Coming soon page-->
                    <div class="misc-wrapper">
                        
                        <div class="misc-inner p-sm-3">
                            <div class="w-100 text-center">
                                <h2 class="mb-1">We are launching soon 🚀</h2>
                                <p class="mb-3">We're creating something awesome. Please subscribe to get notified when it's ready!</p>
                                <form class="row row-cols-md-auto row justify-content-center align-items-center m-0 mb-2 gx-3" action="javascript:void(0)">
                                    <div class="col-12 m-0 mb-1">
                                        <input class="form-control" id="notify-email" type="text" placeholder="john@example.com" />
                                    </div>
                                    <div class="col-12 d-md-block d-grid ps-md-0 ps-auto">
                                        <button class="btn btn-primary mb-1 btn-sm-block" type="submit">Notify</button>
                                    </div>
                                </form><img class="img-fluid" src="../../../app-assets/images/pages/coming-soon.svg" alt="Coming soon page" />
                            </div>
                        </div>
                    </div>
                    <!-- / Coming soon page-->
                </div>
            </div>
        </div>
    
</div>
@endsection