@extends('layouts.master-layout-new')
@push('css')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
<link rel="stylesheet" href="https://unpkg.com/multiple-select@1.5.2/dist/multiple-select.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/1.10.23/css/jquery.dataTables.min.css">
<style>
    .table th {
        padding-top: 20px !important;
        padding-bottom: 20px !important;
        font-size: 1rem;
    }

    .pac-container {
        z-index: 10000 !important;
    }

    table.dataTable thead .sorting:before,
    table.dataTable thead .sorting_asc:before,
    table.dataTable thead .sorting_desc:before,
    table.dataTable thead .sorting_asc_disabled:before,
    table.dataTable thead .sorting_desc_disabled:before {
        right: 1em;
        content: "" !important;
    }

    table.dataTable thead .sorting:after,
    table.dataTable thead .sorting_asc:after,
    table.dataTable thead .sorting_desc:after,
    table.dataTable thead .sorting_asc_disabled:after,
    table.dataTable thead .sorting_desc_disabled:after {
        right: 0.5em;
        content: "" !important;
    }
</style>
@endpush
@section('body')

<!-- loader code-m -->
<div class="app-content content">

    <div class="content-overlay"></div>
    <div class="header-navbar-shadow"></div>
    <div class="content-wrapper container-fluid p-0">
        <div class="content-header row">
            <div class="content-header-left col-md-7 col-12 mb-2">
                <div class="row breadcrumbs-top">
                    <div class="col-12">
                        <h2 class="content-header-title float-start mb-0">Notifications</h2>
                        <div class="breadcrumb-wrapper">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{route('home')}}">Home</a>
                                </li>
                                <li class="breadcrumb-item active"> Notifications
                                </li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                <ul class="nav nav-tabs" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" id="home-tab" data-bs-toggle="tab" href="#home" aria-controls="home" role="tab" aria-selected="true"> Unread Notification </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="profile-tab" data-bs-toggle="tab" href="#profile" aria-controls="profile" role="tab" aria-selected="false"> Earlier Notification </a>
                    </li>
                </ul>
                <div class="tab-content mt-2">
                    <div class="tab-pane active" id="home" aria-labelledby="home-tab" role="tabpanel">
                        @foreach($notifications as $noti)
                            @if($noti->read_at == NULL)
                                <div class="row">
                                    <div class="col-1 text-center" style="display: flex; justify-content: center; align-items: center;">
                                        <span class="btn btn-success rounded-circle btn-circle "><i class="{{$noti->noti_icon}}"></i></span>
                                    </div>
                                    <div class="col-11">
                                        <h5 class="message-title mb-0 mt-1"> {{$noti->noti_title != null ? $noti->noti_title : "" }} </h5>
                                        <span class="font-12 d-block text-muted"> {{$noti->noti_desc != null ? $noti->noti_desc : ""}} </span>
                                        <span class="font-12 text-nowrap d-block text-muted"> {{$noti->created_at}} </span>
                                    </div>
                                </div>
                                <hr>
                            @endif
                        @endforeach
                    </div>
                    <div class="tab-pane" id="profile" aria-labelledby="profile-tab" role="tabpanel">
                        @foreach($notifications as $noti)
                            @if($noti->read_at != null)
                                <div class="row">
                                    <div class="col-1 text-center" style="display: flex; justify-content: center; align-items: center;">
                                        <span class="btn btn-success rounded-circle btn-circle"><i class="{{$noti->noti_icon}}"></i></span>
                                    </div>
                                    <div class="col-11">
                                        <h5 class="message-title mb-0 mt-1"> {{$noti->noti_title != null ? $noti->noti_title : "" }} </h5>
                                        <span class="font-12 d-block text-muted">  {{$noti->noti_desc != null ? $noti->noti_desc : ""}} </span>
                                        <span class="font-12 text-nowrap d-block text-muted"> {{$noti->created_at}} </span>
                                    </div>
                                </div>
                                <hr>
                            @endif
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>

@endsection
@section('scripts')

@endsection