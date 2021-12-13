@extends('layouts.staff-master-layout')
@push('css')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
<link rel="stylesheet" href="https://unpkg.com/multiple-select@1.5.2/dist/multiple-select.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/1.10.23/css/jquery.dataTables.min.css">
    <style>
        
        .table th {
            padding-top:20px !important;
            padding-bottom:20px !important;
            font-size:1rem;
        }      
        .pac-container {
            z-index: 10000 !important;
        }
        table.dataTable thead .sorting:before, table.dataTable thead .sorting_asc:before, table.dataTable thead .sorting_desc:before, table.dataTable thead .sorting_asc_disabled:before, table.dataTable thead .sorting_desc_disabled:before {
            right: 1em;
            content: "" !important;
        }
        table.dataTable thead .sorting:after, table.dataTable thead .sorting_asc:after, table.dataTable thead .sorting_desc:after, table.dataTable thead .sorting_asc_disabled:after, table.dataTable thead .sorting_desc_disabled:after {
            right: 0.5em;
            content: "" !important;
        }
    </style>
@endpush
@section('body-content')

<!-- loader code-m -->

<div class="page-breadcrumb">
    <div class="row">
        <div class="col-md-5 align-self-center">
            <!--<h4 class="page-title">Basic Table</h4>-->
            <div class="d-flex align-items-center">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{route('home')}}">Home</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Notifications</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
</div>

<div class="container-fluid" style="padding-bottom: 0px;">

    <div class="card">
        <div class="card-header">Unread Notification</div>
        <div class="card-body">
            @foreach($notifications as $noti)
                @if($noti->read_at == "" && $noti->read_at == null)
                <div class="row">
                    <div class="col-1 text-center">
                        <span class="btn btn-success rounded-circle btn-circle"><i class="{{$noti->noti_icon}}"></i></span>
                    </div>
                    <div class="col-11">
                        <h5 class="message-title mb-0 mt-1"> {{$noti->noti_title}} </h5>
                        <span class="font-12 d-block text-muted"> {{$noti->noti_desc}} </span> 
                        <span class="font-12 text-nowrap d-block text-muted"> {{$noti->created_at}} </span>
                    </div>
                </div>
                <hr>
                @endif
            @endforeach
        </div>
    </div>

    <div class="card">
        <div class="card-header">Earlier Notification</div>
        <div class="card-body">
            @foreach($notifications as $noti)
                @if($noti->read_at != "" && $noti->read_at != null)
                <div class="row">
                    <div class="col-1 text-center">
                        <span class="btn btn-success rounded-circle btn-circle"><i class="{{$noti->noti_icon}}"></i></span>
                    </div>
                    <div class="col-11">
                        <h5 class="message-title mb-0 mt-1"> {{$noti->noti_title}} </h5>
                        <span class="font-12 d-block text-muted"> {{$noti->noti_desc}} </span> 
                        <span class="font-12 text-nowrap d-block text-muted"> {{$noti->created_at}} </span>
                    </div>
                </div>
                <hr>
                @endif
            @endforeach
        </div>
    </div>

</div>

@endsection   
@section('scripts') 

@endsection 