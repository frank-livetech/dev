@extends('layouts.master-layout-new')
@section('About','open')
@section('Feature Suggestion','active')
@section('body')

<div class="app-content content">
    <div class="content-overlay"></div>
    <div class="header-navbar-shadow"></div>
    <div class="content-wrapper container-xxl p-0">
        <div class="content-header row">
            <div class="content-header-left col-md-12 col-12 mb-2">
                <div class="row breadcrumbs-top">
                    <div class="col-12">
                        <h2 class="content-header-title float-start mb-0"><a>Feature Suggestions</a></h2>
                        <div class="breadcrumb-wrapper">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{route('home')}}">Home</a>
                                </li>
                                <li class="breadcrumb-item active"><a>About</a>
                                </li>
                                <li class="breadcrumb-item active"><a>Feature Suggestions</a>
                                </li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>
             
        </div>
        <div class="content-body">
            <div class="row">
                <div class="col-md-2"></div>
                <div class="col-md-8">
                    <div class="row">
                        <div class="card">
                            <div class="card-header">
                                <h2 class="card-title">Give Us A Feedback!</h2>
                                <hr>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-floating">
                                            <input type="text" class="form-control" id="floating-label1" placeholder="Label-placeholder">
                                            <small>Name e.g. "George deo"</small>
                                            <label for="floating-label1">Name</label>
                                        </div>
                                        <div class="form-floating mt-1">
                                            <input type="text" class="form-control" id="floating-label1" placeholder="Label-placeholder">
                                            <small>Email e.g. "example@gmail.com"</small>
                                            <label for="floating-label1">Email</label>
                                        </div>
                                        <div class="form-floating mb-0 mt-1">
                                            <textarea class="form-control char-textarea" id="textarea-counter" rows="3" placeholder="Feedback" style="height: 100px"></textarea>
                                            <label for="textarea-counter">Feedback</label>
                                        </div>
                                             <button type="button" class="btn btn-success waves-effect waves-float waves-light mt-2" style="float: right;font-size: 20px">Save</button>

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
@endsection