@extends('layouts.staff-master-layout')
@push('css')
<link rel="stylesheet" href="{{asset('assets/extra-libs/pickr/pickr.min.css')}}">
<link href="//cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css"/>
<link rel="stylesheet" href="https://unpkg.com/multiple-select@1.5.2/dist/multiple-select.min.css">
{{-- <link rel="stylesheet" href="https://cdn.datatables.net/1.10.23/css/jquery.dataTables.min.css"> --}}
  <!-- This Page CSS -->
  <link rel="stylesheet" type="text/css" href="{{asset('assets/libs/bootstrap-switch/dist/css/bootstrap3/bootstrap-switch.min.css')}}">
@endpush
@section('body-content')
<div class="page-breadcrumb">
    <div class="row">
        <div class="col-12 align-self-center">
            <div class="d-flex align-items-center">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{route('home')}}">Home</a></li>
                        <li class="breadcrumb-item" aria-current="page">System Manager</li>
                        <li class="breadcrumb-item" aria-current="page"><a href="{{asset('settings')}}">Settings</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Department Settings</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
</div>

<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title mb-3">{{$department->name}}</h4>

                    <ul class="nav nav-pills bg-nav-pills nav-justified mb-5 mt-5">
                        <li class="nav-item">
                            <a href="#dept-general" data-toggle="tab" aria-expanded="false" class="nav-link active">
                                <i class="mdi mdi-home-variant d-lg-none d-block mr-1"></i>
                                <span class="d-none d-lg-block">General Settings</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="#dept-assignments" data-toggle="tab" aria-expanded="false" class="nav-link">
                                <i class="mdi mdi-home-variant d-lg-none d-block mr-1"></i>
                                <span class="d-none d-lg-block">Staff Assignments</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="#dept-permissions" data-toggle="tab" aria-expanded="true" class="nav-link">
                                <i class="mdi mdi-home-variant d-lg-none d-block mr-1"></i>
                                <span class="d-none d-lg-block">Staff Permissions</span>
                            </a>
                        </li>
                    </ul>

                    <div class="tab-content">
                        <div class="tab-pane active" id="dept-general"></div>
                        <div class="tab-pane" id="dept-assignments">
                            @foreach ($users_with_permissions as $item)
                                <div class="d-flex">
                                    <label for="">{{$item['name']}}</label>
                                    <div class="ml-auto">
                                        <input type="checkbox" class="custom-switch" {{$item['assignment'] == 1 ? 'checked' : ''}} data-on-color="success" data-off-color="danger" data-on-text="Yes" data-off-text="No" onchange="dept_assignments(this, '{{$item['id']}}')">
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        <div class="tab-pane" id="dept-permissions">
                            @foreach ($users_with_permissions as $item)
                                <div class="card" id="perm-card-{{$item['id']}}" style="display: {{($item['assignment'] === 1) ? 'block' : 'none'}};">
                                    <div class="card-body">
                                        <div class="card-title mb-2">{{$item['name']}}</div>
                                        @foreach ($item['permissions'] as $j => $p)
                                            <div class="d-flex">
                                                <label for="">{{$p[0]}}</label>
                                                <div class="ml-auto">
                                                    <input type="checkbox" class="custom-switch" {{$p[1] == 1 ? 'checked' : ''}} data-on-color="success" data-off-color="danger" data-on-text="Yes" data-off-text="No" onchange="dept_permissions(this, '{{$item['id']}}', '{{$j}}')">
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script type="text/javascript" src="{{asset('assets/dist/js/flashy.min.js')}}"></script>
<script type="text/javascript" src="{{asset('assets/extra-libs/pickr/pickr.min.js')}}"></script>
<script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>
<!-- <script src="{{ asset('js/pages/features/feature_list.js').'?ver='.rand()}}"></script> -->
@include('js_files.system_manager.feature_list.feature_listJs')

<script src="https://unpkg.com/multiple-select@1.5.2/dist/multiple-select.min.js"></script>
<script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>

<script src="https://unpkg.com/multiple-select@1.5.2/dist/multiple-select.min.js"></script>
<script src="https://cdn.datatables.net/1.10.23/js/jquery.dataTables.min.js"></script>

<script src="{{asset('assets/libs/bootstrap-switch/dist/js/bootstrap-switch.min.js')}}"></script>
<script src="{{asset('public/js/system_manager/settings/dept_settings.js').'?ver='.rand()}}"></script>
@include('js_files.system_manager.settings.departmentJs')

@endsection