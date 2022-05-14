@extends('layouts.master-layout-new')
@section('body')
@php
    $file_path = Session::get('is_live') == 1 ? 'public/' : '/';
@endphp
<div class="app-content content">
    <div class="content-overlay"></div>
    <div class="header-navbar-shadow"></div>
    <div class="content-wrapper p-0">
        <div class="content-header row">
            <div class="content-header-left col-md-7 col-12 mb-2">
                <div class="row breadcrumbs-top">
                    <div class="col-12">
                        <h2 class="content-header-title float-start mb-0">Asset Manager</h2>
                        <div class="breadcrumb-wrapper">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{route('home')}}">Home</a> </li>
                                <li class="breadcrumb-item">Help Desk </li>
                                <li class="breadcrumb-item active">Asset Manager </li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    {{-- @if($date_format) 
            <input type="hidden" id="system_date_format" value="{{$date_format}}">
        @else
            <input type="hidden" id="system_date_format" value="DD-MM-YYYY">
        @endif --}}

        <input type="hidden" id="usrtimeZone" value="{{Session::get('timezone')}}">
    <div class="content-body">
        <div class="row" style="padding-bottom: 17px">
                                
            <div class="col-sm-12">
                <div class="accordion accordion-margin" id="accordionMargin">
                    <div class="accordion-item" id="headingOne">
                        <h2 class="accordion-header" >
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#accordionMarginOne" aria-expanded="false" aria-controls="accordionMarginOne">
                                Add Asset Template
                            </button>
                        </h2>
                        <div id="accordionMarginOne" class="accordion-collapse collapse" aria-labelledby="headingMarginOne" data-bs-parent="#accordionMargin">
                            <div class="accordion-body">
                                <div class="card">
                                        <div class="card-body">
                                            <div class="">
                                                <div class="card" style="box-shadow: 0 12px 24px 0 rgb(34 41 47 / 32%) !important;">
                                                    <div class="row p-1">
                                                        <div class="col-md-12">
                                                            <div class="form-group">
                                                                <label for="tempTitle">Template Title</label>
                                                                <input class="form-control" type="text" id="tempTitle" required="" placeholder="Title">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row ">
                                                    <div class="col-md-4">
                                                        <div class="card p-1" style="box-shadow: 0 12px 24px 0 rgb(34 41 47 / 32%) !important;">
                                                            <div class="row">
                                                            <div class="col-md-6 talign">
                                                            <a class="buttonPush " href="javascript:fieldAdd('text')" >
                                                                <div class="card border-cyan card-hover m-bot">
                                                                    <button type="button" class="btn btn-outline-success waves-effect"><i class="fas fa-edit pr-2" style="font-size: 42px"></i> </button>
                                                                    {{-- <div class="box p-2 rounded">
                                                                        <h6 class="text-cyan mb-0"><i class="fas fa-edit pr-2"></i> Input Field</h6>
                                                                    </div> --}}
                                                                </div>
                                                                Input Field
                                                            </a>
                                                            </div>
                                                            <div class="col-md-6 talign">
                                                            <a class="buttonPush" href="javascript:fieldAdd('phone')">
                                                                <div class="card border-cyan card-hover m-bot">
                                                                    <button type="button" class="btn btn-outline-success waves-effect"><i class="fas fa-phone pr-2" style="font-size: 42px"></i> </button>

                                                                    {{-- <div class="box p-2 rounded">
                                                                        <h6 class="text-cyan mb-0"><i class="fas fa-phone pr-2"></i> Phone Number</h6>
                                                                    </div> --}}
                                                                </div>
                                                                Phone Number
                                                            </a>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-6 talign">
                                                            <a class="buttonPush" href="javascript:fieldAdd('email')" >
                                                                <div class="card border-cyan card-hover m-bot">
                                                                    <button type="button" class="btn btn-outline-success waves-effect"><i class="fas fa-envelope pr-2" style="font-size: 42px"></i> </button>

                                                                    {{-- <div class="box p-2 rounded">
                                                                        <h6 class="text-cyan mb-0"><i class="fas fa-envelope pr-2"></i> Email</h6>
                                                                    </div> --}}
                                                                </div>
                                                                Email
                                                            </a>
                                                        </div>
                                                            <div class="col-md-6 talign">
                                                            <a href="javascript:fieldAdd('textbox')"  class="buttonPush">
                                                                <div class="card border-cyan card-hover m-bot">
                                                                    <button type="button" class="btn btn-outline-success waves-effect"><i class="fas fa-indent pr2" style="font-size: 42px"></i> </button>

                                                                    {{-- <div class="box p-2 rounded">
                                                                        <h6 class="text-cyan"><i class="fas fa-indent pr2"></i> Text Area</h6>
                                                                    </div> --}}
                                                                </div>
                                                                Text Area
                                                            </a>
                                                        </div>
                                                    
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-6 talign">
                                                            <a class="buttonPush" href="javascript:fieldAdd('selectbox')">
                                                                <div class="card border-cyan card-hover m-bot">
                                                                    <button type="button" class="btn btn-outline-success waves-effect"><i class="fas fa-chevron-circle-down pr-2" style="font-size: 42px"></i></button>

                                                                    {{-- <div class="box p-2 rounded ">
                                                                        <h6 class="text-cyan"><i class="fas fa-chevron-circle-down pr-2"></i> Select</h6>
                                                                    </div> --}}
                                                                </div>
                                                                Select
                                                            </a>
                                                        </div>
                                                        <div class="col-md-6 talign">
                                                            <a class="buttonPush" href="javascript:fieldAdd('password')" >
                                                                <div class="card border-cyan card-hover m-bot">
                                                                    <button type="button" class="btn btn-outline-success waves-effect"><i class="fas fa-key pr-2" style="font-size: 42px"></i></button>

                                                                    {{-- <div class="box p-2 rounded">
                                                                        <h6 class="text-cyan"><i class="fas fa-key pr-2"></i>Password</h6>
                                                                    </div> --}}
                                                                </div>
                                                                Password
                                                            </a>
                                                        </div>
                                                        </div>
                                                        <div class="row">
                                                            <div class="col-md-6 talign">
                                                            <a class="buttonPush" href="javascript:fieldAdd('ipv4')" >
                                                                <div class="card border-cyan card-hover m-bot">
                                                                    <button type="button" class="btn btn-outline-success waves-effect"><i class="fas fa-qrcode pr-2" style="font-size: 42px"></i> </button>

                                                                    {{-- <div class="box p-2 rounded">
                                                                        <h6 class="text-cyan"><i class="fas fa-qrcode pr-2"></i> IPv4</h6>
                                                                    </div> --}}
                                                                </div>
                                                                IPv4
                                                            </a>
                                                        </div>
                                                        <div class="col-md-6 talign">
                                                            <a class="buttonPush" href="javascript:fieldAdd('url')" >
                                                                <div class="card border-cyan card-hover m-bot">
                                                                    <button type="button" class="btn btn-outline-success waves-effect"><i class="fas fa-laptop-code pr-2" style="font-size: 42px"></i> </button>

                                                                    {{-- <div class="box p-2 rounded">
                                                                        <!-- <h5 class="font-weight-light text-cyan"></h5> -->
                                                                        <h6 class="text-cyan"><i class="fas fa-laptop-code pr-2"></i> URL</h6>
                                                                    </div> --}}
                                                                </div>
                                                                URL
                                                            </a>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-6 talign">
                                                            <a class="buttonPush" href="javascript:fieldAdd('address')" >
                                                                <div class="card border-cyan card-hover m-bot">
                                                                    <button type="button" class="btn btn-outline-success waves-effect"><i class="fas fa-map-marked-alt pr-2" style="font-size: 42px"></i> </button>

                                                                    {{-- <div class="box p-2 rounded">
                                                                        <!-- <h5 class="font-weight-light text-cyan"></h5> -->
                                                                        <h6 class="text-cyan"><i class="fas fa-map-marked-alt pr-2"></i> Address</h6>
                                                                    </div> --}}
                                                                </div>
                                                                Address
                                                            </a>
                                                        </div>
                                                    </div>
                                                            
                                                        </div>
                                                    </div>
                                                    
                                                    <div class="col-md-8">
                                                        <div class="card" style="box-shadow: 0 12px 24px 0 rgb(34 41 47 / 32%) !important;">
                                                            <div class="m-1">
                                                                <button type="button" class="btn btn-success float-btn ml-2" onclick="saveTemplate()" style="margin-left: 5px"> Save Template </button>
                                                                <button type="button" class="btn btn-info float-btn" onclick=""> Preview </button>
                                                            </div>
                                                        </div>
                                                        <div class="card" style="box-shadow: 0 12px 24px 0 rgb(34 41 47 / 32%) !important;">
                                                            <div class="row p-1">
                                                                <div class="col-md-12 pt-1 ">
                                                                    <div class="head text-center ">
                                                                        <h4> Please Insert a Field Here from Insert Field Button </h4>
                                                                    </div>
                                                                    <div id="cardycard">
                                                                    </div>
                                                                    <div class="tail" id="card-colors" style="display:;">
                                                                        <div class="row connectedSortable border" id="sortable-row-start" style="min-height:10px; display: none;">
                                                                            <div class="appends d-none"></div>
                                                                        </div>
                                                                        <div class="row connectedSortable border" id="sortable-row-last" style="min-height:10px; display: none;">
                                                                            <div class="appends d-none"></div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-12">
                                                        <div class="table-responsive">
                                                            <table id="asset-temp-table-list"
                                                                class="table table-striped table-bordered no-wrap w-100 asset-temp-table-list">
                                                                <thead>
                                                                    <tr>
                                                                        <th><div class="text-center">
                                                                            <input type="checkbox" id="checkAll" name="assets[]" value="0"></div></th>
                                                                        <th>ID</th>
                                                                        <th>Template</th>
                                                                        <th>Actions</th>
                                                                    </tr>
                                                                </thead>
                    
                                                            </table>
                    
                                                        </div>
                                                        
                                                        </div>
                                                </div>
                                            </div>
                                        </div>
                                    
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="headingMargintwo">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#accordionMargintwo" aria-expanded="false" aria-controls="accordionMarginOne" style="margin-bottom: 14px;">
                                Add Asset
                            </button>
                        </h2>
                        <div id="accordionMargintwo" class="accordion-collapse collapse pb-4" aria-labelledby="headingMargintwo" data-bs-parent="#accordionMargin">
                            <div class="accordion-body">
                                <form class="form-horizontal" id="save_asset_form" enctype="multipart/form-data"
                                action="{{asset('/save-asset')}}" method="post">
                                <div class="form-row">
                                    <div class="col-md-12 form-group">
                                        <div class="form-group">
                                            <label>Asset Template</label>
                                            <select class="select2 form-select form-control" onchange="getFields(this.value)" id="form_id" name="form_id" required></select>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-row mt-1" id="templateTitle" style="display:none;">
                                    <div class="col-md-12 form-group">
                                        <div class="form-group">
                                            <label>Asset Title</label>
                                                <input type="text" name="asset_title" id="asset_title" class="asset_title form-control">
                                        </div>
                                    </div>
                                </div>
                                <div class="row mt-1" id="form-fields"></div>
                
                                <button type="submit" class="btn btn-success mt-1" style="float:right;margin-bottom: 17px">Save</button>
                            </form>
                            </div>
                        </div>
                    </div>
                
                
                </div>
                

            </div>
            
            <div class="col-lg-12 col-md-12">
                <div class="card mt-1">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-lg-6 col-md-6">
                                <h4 class="card-title">Assets</h4>
                            </div>
                            <!-- <div class="col-lg-3 col-md-3">
                                <button type="button" class="btn btn-success" onclick="ShowAssetModel()"
                                    style="float:right;"><i class="mdi mdi-plus-circle" ></i>&nbsp;Add
                                    Asset
                                </button>
                            </div>
                            <div class="col-lg-3 col-md-3">
                                <button type="button" class="btn btn-success" onclick="ShowAssetModel()"
                                    style="float:right;"><i class="mdi mdi-plus-circle"></i>&nbsp;Add
                                    Asset Template
                                </button>
                            </div> -->
                            
                        </div>
                        <br>
    
    
                        <!-- <div class="row">
                            <div class="col-md-12" style="text-align:right;">
                                <select class="multiple-select mt-2 mb-2" name="as_select" id="as_select" placeholder="Show/Hide" multiple="multiple" selected="selected">
                                    <option value="0">Sr#</option>
                                    <option value="1">AssetID</option>
                                    <option value="2">Template</option>
                                    <option value="3">Customer</option>
                                    <option value="4">Company</option>
                                    <option value="5">Projects</option>
                                    <option value="6">Actions</option>
                                </select>
                            </div>
                        </div> -->
                        
                        <div class="table-responsive">
                            <table id="asset-table-list"
                                class="table table-striped table-bordered w-100 no-wrap asset-table-list">
                                <thead>
                                    <tr>
                                        <th><div class="text-center"><input type="checkbox" id="checkAll" name="assets[]" value="0"></div></th>
                                        <th>ID</th>
                                        <th>Asset Title</th>
                                        <th>Template Name</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
    
                            </table>
    
                        </div>
                    </div>
                </div>
            
        </div>
        </div>
    </div>
</div>
<!-- Create Template Title modal content -->

<div id="fields-modal" class="modal fade" tabindex="-1" role="dialog"  data-backdrop="static" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                    <h4 id="headinglabel"> Select Setting </h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="fields-form" class="pl-3 pr-3">
                    <div class="form-group mt-1">
                        <label for="select">Label</label> <span class="text-danger">*</span>
                        <input class="form-control" type="text" id="lbl" required>
                    </div>
                    <div class="form-group mt-1">
                        <label>Placeholder</label>
                        <input class="form-control" type="text" id="ph">
                    </div>
                    <div class="form-group mt-1">
                        <label>Description</label>
                        <input class="form-control" type="text" id="desc">
                    </div>
                    <div id="dyn-data"></div>
                    <div class="form-group mt-1">
                        <div class="custom-control custom-checkbox">
                            <input type="checkbox" class="custom-control-input" id="is_required">
                            <label class="custom-control-label" for="is_required">Set Required </label>
                        </div>
                    </div>
                    <div class="form-group text-center mt-1">
                        <button class="btn btn-rounded btn-primary" id="sve" type="submit">Save</button>
                        <button class="btn btn-rounded btn-secondary" type="button" data-dismiss="modal">Close</button>
                    </div>
                </form>

            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->


<!-- update asset modal -->
<div id="update_asset_modal" class="modal fade" tabindex="-1" role="dialog"  data-backdrop="static" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                
                    <h4 id="headinglabel"> Update - <span id="modal-title"></span>  </h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
              
            </div>
            <div class="modal-body">
                <form id="update_assets_form" enctype="multipart/form-data" onsubmit="return false">
                    <div class="form-group mt-1">
                        <label for="select">Asset Title</label> <span class="text-danger">*</span>
                        <input class="form-control" type="text" id="up_asset_title" required>
                        <input class="form-control" type="hidden" id="asset_title_id" required>
                        
                    </div>
                    <div class="input_fields mt-1"></div>
                    <div class="address_fields mt-1"></div>
                    <div class="form-group float-btn mt-3">
                        <button class="btn btn-rounded btn-success" onclick="updateAssets()" id="sve" type="submit">Save</button>
                        <button class="btn btn-rounded btn-danger" type="button" data-dismiss="modal">Close</button>
                    </div>
                </form>

                <div class="loader_container">
                    <div class="loader"></div>
                </div>
            </div>
        </div>
    </div>
</div>
<style>
    .talign{
        text-align: center
    }
    .m-bot{
        margin-bottom: 0.5rem
    }
    .float-btn{
        float: right
    }
     table.dataTable th{
        padding: 15px !important
    }
    table.dataTable>thead>tr>th:not(.sorting_disabled), table.dataTable>thead>tr>td:not(.sorting_disabled){
        padding-right: 14px !important
    }
</style>
@endsection
@section('scripts')
<link rel="stylesheet" href="https://code.jquery.com/ui/1.12.0/themes/smoothness/jquery-ui.css">
<script src="https://code.jquery.com/ui/1.12.0/jquery-ui.min.js" integrity="sha256-eGE6blurk5sHj+rmkfsGYeKyZx3M4bG+ZlFyA7Kns7E=" crossorigin="anonymous"></script>
<script src="https://rawgit.com/RobinHerbots/jquery.inputmask/3.x/dist/jquery.inputmask.bundle.js"></script>
@include('js_files.help_desk.asset_manager.indexJs')
@endsection