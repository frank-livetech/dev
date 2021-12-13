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
                        <li class="breadcrumb-item active" aria-current="page">About</li>
                        <li class="breadcrumb-item active" aria-current="page">Feature Suggestions</li>
                    </ol>
                </nav>
            </div>
        </div>

    </div>
</div>
<div class="container-fluid">
    <div class="row">
        <div class="col-lg-12 col-md-12">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Give Us A Feedback!</h4>
                    <form class="form-control-line mt-5">
                        <div class="form-group">
                            <label>Name <span class="help"> e.g. "George deo"</span></label>
                            <input type="text" class="form-control form-control-line" value="Name"> 
                        </div>
                        <div class="form-group">
                            <label for="example-email3">Email <span class="help"> e.g. "example@gmail.com"</span></label>
                            <input type="email" id="example-email3" name="example-email" class="form-control" placeholder="Email"> 
                        </div>
                        <div class="form-group">
                            <label>Feedback</label>
                            <textarea class="form-control" rows="5"></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary" style="float:right">Save</button>     
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
