@extends('layouts.staff-master-layout')
@section('body-content')

<div class="page-breadcrumb">
    <div class="row">
        <div class="col-md-5 align-self-center">
            <div class="d-flex align-items-center">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{route('home')}}">Home</a></li>
                        <li class="breadcrumb-item"><a href="{{asset('/ticket-management')}}">Tickets Manager</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Ticket Details</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
</div>

<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <center>Ticket does not exist</center>
        </div>
    </div>
</div>

@endsection