@extends('layouts.master-layout-new')
@section('body')

@section('customtheme')
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
    .FieldItem_container {
        font-size: 16px;
        cursor: pointer;
        position: relative;
        -ms-flex-align: center;
        align-items: center;
        -ms-flex-preferred-size: calc(50% - 6px);
        flex-basis: calc(50% - 6px);
        display: -ms-flexbox;
        display: flex;
        margin-bottom: 12px;
        padding: 12px 29px 12px 9px;
        box-sizing: border-box;
        border-left: 4px solid #D3DDE1;
        border-radius: 4px;
        color: #455560;
        background-color: #FFFFFF;
        line-height: 1;
        -webkit-user-select: none;
        -ms-user-select: none;
        user-select: none;
        transition: 200ms opacity ease-in-out, 200ms box-shadow ease-in-out, 200ms border-left-color ease-in-out;
    }
    .FieldItem_container:hover, .FieldItem_container.is-drag-layer {
        box-shadow: 0 0 3px 0 rgb(0 0 0 / 20%);
        border-left-color: #069EB4;
    }
    .FieldItem_container::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        box-sizing: border-box;
        border: 1px solid #D3DDE1;
        border-top-right-radius: 4px;
        border-bottom-right-radius: 4px;
        border-left-width: 0;
    }
    .FieldItem_container .FieldItem_title {
        max-width: 200px;
        margin-left: 12px;
        white-space: nowrap;
        text-overflow: ellipsis;
        font-weight: bolder;
        font-size:14px;
        white-space: break-spaces;
    }
    .FieldSettingsSideBarItem_container {
        -ms-flex-preferred-size: calc(50% - 6px);
        flex-basis: calc(50% - 6px);
        display: -ms-flexbox;
        margin-bottom: 12px;
    }
</style>
@endsection

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
                                Add Asset Type
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
                                                        <div class="card p-1" style="box-shadow: 0 12px 24px 0 rgb(34 41 47 / 32%) !important;background-color: #F7F8F8">
                                                            <div class="row">
                                                            <div class="col-md-6 talign">
                                                           
                                                            <a class="buttonPush " href="javascript:fieldAdd('text')" >
                                                                <div class="FieldSettingsSideBarItem_container">
                                                                    <div class="FieldItem_container FieldSettingsSideBarItem_fieldItem DragHandle_handle" draggable="true">
                                                                        <i class="fas fa-edit pr-2" style="color: rgb(69, 85, 96); height: 15px; width: 15px;"></i>
                                                                        <span class="FieldItem_title">Input Field</span>
                                                                    </div>
                                                                </div>
                                                             </a>
                                                            </div>
                                                            <div class="col-md-6 talign">
                                                            <a class="buttonPush" href="javascript:fieldAdd('phone')">
                                                        
                                                                <div class="FieldSettingsSideBarItem_container">
                                                                    <div class="FieldItem_container FieldSettingsSideBarItem_fieldItem DragHandle_handle" draggable="true">
                                                                        <i class="fas fa-phone" style="color: rgb(69, 85, 96); height: 15px; width: 15px;"></i>
                                                                        <span class="FieldItem_title">Phone Number</span>
                                                                    </div>
                                                                </div>
                                                            </a>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-6 talign">
                                                            <a class="buttonPush" href="javascript:fieldAdd('email')" >
                                                                
                                                                <div class="FieldSettingsSideBarItem_container">
                                                                    <div class="FieldItem_container FieldSettingsSideBarItem_fieldItem DragHandle_handle" draggable="true">
                                                                        <i class="fas fa-envelope" style="color: rgb(69, 85, 96); height: 15px; width: 15px;"></i>
                                                                        <span class="FieldItem_title">Email</span>
                                                                    </div>
                                                                </div>
                                                                
                                                            </a>
                                                        </div>
                                                            <div class="col-md-6 talign">
                                                            <a href="javascript:fieldAdd('textbox')"  class="buttonPush">
                                                                
                                                                <div class="FieldSettingsSideBarItem_container">
                                                                    <div class="FieldItem_container FieldSettingsSideBarItem_fieldItem DragHandle_handle" draggable="true">
                                                                        <i class="fas fa-indent" style="color: rgb(69, 85, 96); height: 15px; width: 15px;"></i>
                                                                        <span class="FieldItem_title">Text Area</span>
                                                                    </div>
                                                                </div>
                                                                
                                                            </a>
                                                        </div>
                                                    
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-6 talign">
                                                            <a class="buttonPush" href="javascript:fieldAdd('selectbox')">
                                                                
                                                                <div class="FieldSettingsSideBarItem_container">
                                                                    <div class="FieldItem_container FieldSettingsSideBarItem_fieldItem DragHandle_handle" draggable="true">
                                                                        <i class="fas fa-chevron-circle-down" style="color: rgb(69, 85, 96); height: 15px; width: 15px;"></i>
                                                                        <span class="FieldItem_title">Select</span>
                                                                    </div>
                                                                </div>
                                                                
                                                            </a>
                                                        </div>
                                                        <div class="col-md-6 talign">
                                                            <a class="buttonPush" href="javascript:fieldAdd('password')" >
                                                                
                                                                <div class="FieldSettingsSideBarItem_container">
                                                                    <div class="FieldItem_container FieldSettingsSideBarItem_fieldItem DragHandle_handle" draggable="true">
                                                                        <i class="fas fa-key" style="color: rgb(69, 85, 96); height: 15px; width: 15px;"></i>
                                                                        <span class="FieldItem_title">Password</span>
                                                                    </div>
                                                                </div>
                                                                
                                                            </a>
                                                        </div>
                                                        </div>
                                                        <div class="row">
                                                            <div class="col-md-6 talign">
                                                            <a class="buttonPush" href="javascript:fieldAdd('ipv4')" >
                                                               
                                                                <div class="FieldSettingsSideBarItem_container">
                                                                    <div class="FieldItem_container FieldSettingsSideBarItem_fieldItem DragHandle_handle" draggable="true">
                                                                        <i class="fas fa-qrcode" style="color: rgb(69, 85, 96); height: 15px; width: 15px;"></i>
                                                                        <span class="FieldItem_title">IPv4</span>
                                                                    </div>
                                                                </div>
                                                                
                                                            </a>
                                                        </div>
                                                        <div class="col-md-6 talign">
                                                            <a class="buttonPush" href="javascript:fieldAdd('url')" >
                                                               
                                                                <div class="FieldSettingsSideBarItem_container">
                                                                    <div class="FieldItem_container FieldSettingsSideBarItem_fieldItem DragHandle_handle" draggable="true">
                                                                        <i class="fas fa-laptop-code" style="color: rgb(69, 85, 96); height: 15px; width: 15px;"></i>
                                                                        <span class="FieldItem_title">URL</span>
                                                                    </div>
                                                                </div>
                                                                
                                                            </a>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-6 talign">
                                                            <a class="buttonPush" href="javascript:fieldAdd('address')" >
                                                               
                                                                <div class="FieldSettingsSideBarItem_container">
                                                                    <div class="FieldItem_container FieldSettingsSideBarItem_fieldItem DragHandle_handle" draggable="true">
                                                                        <i class="fas fa-map-marked-alt" style="color: rgb(69, 85, 96); height: 15px; width: 15px;"></i>
                                                                        <span class="FieldItem_title">Address</span>
                                                                    </div>
                                                                </div>
                                                                
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
                                class="table table-bordered w-100 no-wrap asset-table-list">
                                <thead>
                                    <tr>
                                        <th width="2%">
                                            <div class="text-center">
                                                <input type="checkbox" id="checkAll" name="assets[]" value="0">
                                            </div>
                                        </th>
                                        <th></th>
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


@endsection
@section('scripts')
<link rel="stylesheet" href="https://code.jquery.com/ui/1.12.0/themes/smoothness/jquery-ui.css">
<script src="https://code.jquery.com/ui/1.12.0/jquery-ui.min.js" integrity="sha256-eGE6blurk5sHj+rmkfsGYeKyZx3M4bG+ZlFyA7Kns7E=" crossorigin="anonymous"></script>
<script src="https://rawgit.com/RobinHerbots/jquery.inputmask/3.x/dist/jquery.inputmask.bundle.js"></script>
@include('js_files.help_desk.asset_manager.indexJs')
@endsection