@extends('layouts.staff-master-layout')
@section('body-content')
<link rel="stylesheet" href="https://unpkg.com/multiple-select@1.5.2/dist/multiple-select.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/1.10.23/css/jquery.dataTables.min.css">
<link href="{{asset('assets/libs/dragula/dist/dragula.min.css')}}" rel="stylesheet">
<link href="{{asset('assets/extra-libs/prism/prism.css')}}" rel="stylesheet">
<style>
    .buttonPush{
        cursor:pointer;
    }
    .activa{
        background-color:#4fc3f7;
        color:#fff;
    }
    .activa h5,
    .activa h6{
        color:#fff !important;
    }
    .red{
        color:red;
    }
    .hidden{
        display:none;
    }
    .card-moved .card {
        background: inherit;
        color: inherit;
    }
    .appends {
        cursor: grab;
        cursor: -moz-grab;
        cursor: -webkit-grab;
    }
    .appends.highlight{
        border-left: 3px solid transparent !important;
    }

    .highlight {
        border-left: 3px solid blue;
        height: 70px;
    }
}
</style>
<div class="page-breadcrumb">
    <div class="row">
        <div class="col-md-5 align-self-center">
            <div class="d-flex align-items-center">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{route('home')}}">Home</a></li>
                        <li class="breadcrumb-item" aria-current="page">Help Desk</li>
                        <li class="breadcrumb-item active" aria-current="page">Asset Manager</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
</div>

<!-- <input type="text" class="form-input" id="ipv4" name="ipv4" placeholder="xxx.xxx.xxx.xxx"> -->
<div class="container-fluid">
    <div class="row">
        <div class="col-lg-12 col-md-12">
            <div id="accordion" class="custom-accordion mb-4">
                <div class="card">
                    <div class="card-header" id="headingOne">
                        <h5 class="m-0">
                            <a class="custom-accordion-title d-flex align-items-center pt-2 pb-2 collapsed" data-toggle="collapse" href="#collapseOne" aria-expanded="false" aria-controls="collapseOne">
                                Add Asset Template <span class="ml-auto"><i class="mdi mdi-chevron-down accordion-arrow"></i></span>
                            </a>
                        </h5>
                    </div>
                    <div id="collapseOne" class="collapse" aria-labelledby="headingOne" data-parent="#accordion" style="">
                        <div class="card-body">
                            <div class="container-fluid">
                                <div class="card">
                                    <div class="row p-3">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label for="tempTitle">Template Title</label>
                                                <input class="form-control" type="text" id="tempTitle" required="" placeholder="Title">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-3">
                                        <div class="card p-3">
                                            <a class="buttonPush" href="javascript:fieldAdd('text')">
                                                        <div class="card border-cyan card-hover mb-2">
                                                            <div class="box p-2 rounded">
                                                                <h6 class="text-cyan mb-0"><i class="fas fa-edit pr-2"></i> Input Field</h6>
                                                            </div>
                                                        </div>
                                                    </a>
                                            <!-- <a href="javascript:fieldAdd('text')" class="buttonPush">
                                                <div class=" border-cyan card-hover">
                                                    <div class="box p-2 rounded">
                                                        <h6 class="text-cyan">Input Field <i class="fa fa-plus float-right"></i></h6>
                                                    </div>
                                                </div>
                                            </a> -->
                                            <a class="buttonPush" href="javascript:fieldAdd('phone')">
                                                <div class="card border-cyan card-hover mb-2">
                                                    <div class="box p-2 rounded">
                                                        <!-- <h5 class="font-weight-light text-cyan"></h5> -->
                                                        <h6 class="text-cyan mb-0"><i class="fas fa-phone pr-2"></i> Phone Number</h6>
                                                    </div>
                                                </div>
                                            </a>
                                            <!-- <a href="javascript:fieldAdd('phone')" class="buttonPush">
                                                <div class=" border-cyan card-hover">
                                                    <div class="box p-2 rounded">
                                                        <h6 class="text-cyan">Phone Number <i class="fa fa-plus float-right"></i></h6>
                                                    </div>
                                                </div>
                                            </a> -->
                                            <a class="buttonPush" href="javascript:fieldAdd('email')" >
                                                <div class="card border-cyan card-hover mb-2">
                                                    <div class="box p-2 rounded">
                                                        <!-- <h5 class="font-weight-light text-cyan"></h5> -->
                                                        <h6 class="text-cyan mb-0"><i class="fas fa-envelope pr-2"></i> Email</h6>
                                                    </div>
                                                </div>
                                            </a>
                                            <!-- <a href="javascript:fieldAdd('email')" class="buttonPush">
                                                <div class=" border-cyan card-hover">
                                                    <div class="box p-2 rounded">
                                                        <h6 class="text-cyan">Email <i class="fa fa-plus float-right"></i></h6>
                                                    </div>
                                                </div>
                                            </a> -->
                                            <a href="javascript:fieldAdd('textbox')"  class="buttonPush">
                                                <div class="card border-cyan card-hover mb-2">
                                                    <div class="box p-2 rounded">
                                                        <!-- <h5 class="font-weight-light text-cyan"> </h5> -->
                                                        <h6 class="text-cyan"><i class="fas fa-indent pr2"></i> Text Area</h6>
                                                    </div>
                                                </div>
                                            </a>
                                            <!-- <a href="javascript:fieldAdd('textbox')" class="buttonPush">
                                                <div class=" border-cyan card-hover">
                                                    <div class="box p-2 rounded">
                                                        <h6 class="text-cyan">Text Area <i class="fa fa-plus float-right"></i></h6>
                                                    </div>
                                                </div>
                                            </a> -->
                                            <a class="buttonPush" href="javascript:fieldAdd('selectbox')">
                                                <div class="card border-cyan card-hover mb-2">
                                                    <div class="box p-2 rounded ">
                                                        <!-- <h5 class="font-weight-light text-cyan"> </h5> -->
                                                        <h6 class="text-cyan"><i class="fas fa-chevron-circle-down pr-2"></i> Select</h6>
                                                    </div>
                                                </div>
                                            </a>
                                            <!-- <a href="javascript:fieldAdd('selectbox')" class="buttonPush">
                                                <div class=" border-cyan card-hover">
                                                    <div class="box p-2 rounded">
                                                        <h6 class="text-cyan">Select <i class="fa fa-plus float-right"></i></h6>
                                                    </div>
                                                </div>
                                            </a> -->
                                            <a class="buttonPush" href="javascript:fieldAdd('password')" >
                                                <div class="card border-cyan card-hover mb-2">
                                                    <div class="box p-2 rounded">
                                                        <!-- <h5 class="font-weight-light text-cyan"></h5> -->
                                                        <h6 class="text-cyan"><i class="fas fa-key pr-2"></i>Password</h6>
                                                    </div>
                                                </div>
                                            </a>
                                            <!-- <a href="javascript:fieldAdd('password')" class="buttonPush">
                                                <div class=" border-cyan card-hover">
                                                    <div class="box p-2 rounded">
                                                        <h6 class="text-cyan">Password <i class="fa fa-plus float-right"></i></h6>
                                                    </div>
                                                </div>
                                            </a> -->
                                            <a class="buttonPush" href="javascript:fieldAdd('ipv4')" >
                                                <div class="card border-cyan card-hover mb-2">
                                                    <div class="box p-2 rounded">
                                                        <!-- <h5 class="font-weight-light text-cyan"></h5> -->
                                                        <h6 class="text-cyan"><i class="fas fa-qrcode pr-2"></i>IPv4 </h6>
                                                    </div>
                                                </div>
                                            </a>
                                            <a class="buttonPush" href="javascript:fieldAdd('url')" >
                                                <div class="card border-cyan card-hover mb-2">
                                                    <div class="box p-2 rounded">
                                                        <!-- <h5 class="font-weight-light text-cyan"></h5> -->
                                                        <h6 class="text-cyan"><i class="fas fa-laptop-code pr-2"></i>URL </h6>
                                                    </div>
                                                </div>
                                            </a>
                                            <a class="buttonPush" href="javascript:fieldAdd('address')" >
                                                <div class="card border-cyan card-hover mb-2">
                                                    <div class="box p-2 rounded">
                                                        <!-- <h5 class="font-weight-light text-cyan"></h5> -->
                                                        <h6 class="text-cyan"><i class="fas fa-map-marked-alt pr-2"></i>Address </h6>
                                                    </div>
                                                </div>
                                            </a>
                                        </div>
                                    </div>
                                    <div class="col-md-9">
                                        <div class="card">
                                            <div class="m-3">
                                                <button type="button" class="btn btn-success float-right btn-sm rounded " onclick="saveTemplate()" > <i class="fas fa-save"></i> Save Template </button>
                                                <button type="button" class="btn btn-info float-right mr-2 btn-sm rounded" onclick=""> <i class="fas fa-eye"></i> Preview </button>
                                            </div>
                                        </div>
                                        <div class="card">
                                            <div class="row p-3">
                                                <div class="col-md-12 pt-3 ">
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
                </div> <!-- end card-->

                <div class="card mb-0">
                    <div class="card-header" id="headingTwo">
                        <h5 class="m-0">
                            <a class="custom-accordion-title collapsed d-flex align-items-center pt-2 pb-2" data-toggle="collapse" href="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                                Add Asset <span class="ml-auto"><i class="mdi mdi-chevron-down accordion-arrow"></i></span>
                            </a>
                        </h5>
                    </div>
                    <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordion">
                        <div class="card-body">
                            <form class="form-horizontal" id="save_asset_form" enctype="multipart/form-data"
                                action="{{asset('/save-asset')}}" method="post">
                                <div class="form-row">
                                    <div class="col-md-12 form-group">
                                        <div class="form-group">
                                            <label>Asset Template</label>
                                            <select class="select form-control" onchange="getFields(this.value)" id="form_id" name="form_id" required></select>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-row" id="templateTitle" style="display:none;">
                                    <div class="col-md-12 form-group">
                                        <div class="form-group">
                                            <label>Asset Title</label>
                                                <input type="text" name="asset_title" id="asset_title" class="asset_title form-control">
                                        </div>
                                    </div>
                                </div>
                                <div class="row" id="form-fields"></div>
                
                                <button type="submit" class="btn btn-success btn-sm rounded mt-3 mb-3" style="float:right;"> <i class="fas fa-save"></i> Save</button>
                            </form>
                        </div>
                    </div>
                </div> <!-- end card-->
            </div>
        </div>
        <div class="col-lg-12 col-md-12">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-6 col-md-6">
                            <h4 class="card-title">Assets</h4>
                        </div>                        
                    </div>
                    <br>


                    <div class="row">
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
                    </div>
                    
                    <div class="table-responsive">
                        <table id="asset-table-list"
                            class="table table-striped table-bordered no-wrap table-hover asset-table-list w-100">
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

            <!-- Modal -->

        </div>
    </div>
</div>

<!-- Create Template Title modal content -->
<div id="fields-modal" class="modal fade" tabindex="-1" role="dialog"  data-backdrop="static" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-info p-2">
                <span>
                    <h4 style="color:#fff !important;" id="headinglabel"> Select Setting </h4>
                </span>
            </div>
            <div class="modal-body">
                <form id="fields-form" class="pl-3 pr-3">
                    <div class="form-group">
                        <label for="select">Label</label> <span class="text-danger">*</span>
                        <input class="form-control" type="text" id="lbl" required>
                    </div>
                    <div class="form-group">
                        <label>Placeholder</label>
                        <input class="form-control" type="text" id="ph">
                    </div>
                    <div class="form-group">
                        <label>Description</label>
                        <input class="form-control" type="text" id="desc">
                    </div>
                    <div id="dyn-data"></div>
                    <div class="form-group">
                        <div class="custom-control custom-checkbox">
                            <input type="checkbox" class="custom-control-input" id="is_required">
                            <label class="custom-control-label" for="is_required">Set Required </label>
                        </div>
                    </div>
                    <div class="form-group text-center">
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
            <div class="modal-header bg-info p-2">
                <span>
                    <h4 style="color:#fff !important;" id="headinglabel"> Update - <span id="modal-title"></span>  </h4>
                </span>
            </div>
            <div class="modal-body">
                <form id="update_assets_form" enctype="multipart/form-data" onsubmit="return false">
                    <div class="form-group">
                        <label for="select">Asset Title</label> <span class="text-danger">*</span>
                        <input class="form-control" type="text" id="up_asset_title" required>
                        <input class="form-control" type="hidden" id="asset_title_id" required>
                        
                    </div>
                    <div class="input_fields"></div>
                    <div class="address_fields"></div>
                    <div class="form-group text-right mt-3">
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
<!--<script src="theme_assets/libs/jsgrid/db.js"></script>-->
<script type="text/javascript" src="{{asset('assets/dist/js/flashy.min.js')}}"></script>

<!-- jQuery ui files-->
<link rel="stylesheet" href="https://code.jquery.com/ui/1.12.0/themes/smoothness/jquery-ui.css">
<script src="https://code.jquery.com/ui/1.12.0/jquery-ui.min.js" integrity="sha256-eGE6blurk5sHj+rmkfsGYeKyZx3M4bG+ZlFyA7Kns7E=" crossorigin="anonymous"></script>
<script src="https://rawgit.com/RobinHerbots/jquery.inputmask/3.x/dist/jquery.inputmask.bundle.js"></script>

<script src="https://unpkg.com/multiple-select@1.5.2/dist/multiple-select.min.js"></script>
<script src="https://cdn.datatables.net/1.10.23/js/jquery.dataTables.min.js"></script>
@include('js_files.help_desk.asset_manager.indexJs')


@endsection
<style>
    #image_id {
        height: 30px;
        width: 30px;
    }
    /* .fl-ipv4 span {
        font-size: 20px;
        font-weight: 500;
        margin-top: auto;
        padding: 0 3px 0 3px;
    } */
</style>
