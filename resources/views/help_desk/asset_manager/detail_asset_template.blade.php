@extends('layouts.staff-master-layout')

@section('body-content')
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
    .footer{
        bottom: 0;
        position: fixed;
        right: 0;
        left: 0;
        text-align:center;
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
        /* font-weight: bold; */
        /* font-size: 45px; */
        /* background-color: lightblue; */
    }
}
</style>
<div class="page-breadcrumb">
    <div class="row">
        <div class="col-md-5 align-self-center">
            <!--<h4 class="page-title">Tickets Manager</h4>-->
            <div class="d-flex align-items-center">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{route('home')}}">Home</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Asset Template Detail</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
</div>
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
                <!-- <a href="javascript:fieldAdd('ipv4')" class="buttonPush">
                    <div class=" border-cyan card-hover">
                        <div class="box p-2 rounded">
                            <h6 class="text-cyan">IPv4 <i class="fa fa-plus float-right"></i></h6>
                        </div>
                    </div>
                </a> -->
             </div>
        </div>
        <div class="col-md-9">
            <div class="card">
                <div class="m-3">
                    <button type="button" class="btn btn-success float-right " onclick="saveTemplate()"> Save Template </button>
                    <button type="button" class="btn btn-info float-right mr-2" onclick=""> Preview </button>
                </div>
            </div>
            <div class="card">
                <div class="row p-3">
                    {{-- <div class="col-md-12">
                        <button type="button" class="btn btn-success" data-toggle="modal" data-target="#fieldInsert-Model"> Insert Field <i class="fa fa-plus" style="font-weight:600;"></i> </button>             
                    </div> --}}
                    <div class="col-md-12 pt-3 ">
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

            <!-- <div class="w-100 mt-3">
                <button type="button" class="btn btn-success float-right" onclick="saveTemplate()"> Save Template </button>
            </div> -->
        </div>
    </div>
    
</div>
<!-- Create Template Title modal content -->
<div id="fieldInsert-Model" class="modal fade" tabindex="-1" role="dialog"  data-backdrop="static" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="text-center bg-info p-3">
                <span>
                    <h2 style="color:#fff !important;"> Field Elements </h2>
                </span>
            </div>
            <div class="modal-body" id="modalContainer">
                <div class="row">
                    <div class="col-md-4">
                        
                    </div>
                    <div class="col-md-4">
                        <a class="buttonPush" data-template_name="email">
                            <div class="card border-cyan card-hover">
                                <div class="box p-2 rounded  text-center">
                                    <h5 class="font-weight-light text-cyan"><i class="fas fa-envelope"></i></h5>
                                    <h6 class="text-cyan">Email</h6>
                                </div>
                            </div>
                        </a>
                    </div>
                    <div class="col-md-4">
                        <a class="buttonPush" data-template_name="phone">
                            <div class="card border-cyan card-hover">
                                <div class="box p-2 rounded  text-center">
                                    <h5 class="font-weight-light text-cyan"><i class="fas fa-phone"></i></h5>
                                    <h6 class="text-cyan">Phone Number</h6>
                                </div>
                            </div>
                        </a>
                    </div>
                    <div class="col-md-4">
                        <a class="buttonPush" data-template_name="textbox">
                            <div class="card border-cyan card-hover">
                                <div class="box p-2 rounded text-center">
                                    <h5 class="font-weight-light text-cyan"><i class="fas fa-indent"></i></h5>
                                    <h6 class="text-cyan">Text Area</h6>
                                </div>
                            </div>
                        </a>
                    </div>
                    <div class="col-md-4">
                        <a class="buttonPush" data-template_name="selectbox">
                            <div class="card border-cyan card-hover">
                                <div class="box p-2 rounded text-center">
                                    <h5 class="font-weight-light text-cyan"> <i class="fas fa-chevron-circle-down"></i></h5>
                                    <h6 class="text-cyan">Select</h6>
                                </div>
                            </div>
                        </a>
                    </div>
                    <div class="col-md-4">
                        <a class="buttonPush" data-template_name="password">
                            <div class="card border-cyan card-hover">
                                <div class="box p-2 rounded text-center">
                                    <h5 class="font-weight-light text-cyan"> <i class="fas fa-key"></i></h5>
                                    <h6 class="text-cyan">Password</h6>
                                </div>
                            </div>
                        </a>
                    </div>
                    <div class="col-md-4">
                        <a class="buttonPush" data-template_name="ipv4">
                            <div class="card border-cyan card-hover">
                                <div class="box p-2 rounded text-center">
                                    <h5 class="font-weight-light text-cyan"> <i class="fas fa-chevron-circle-down"></i></h5>
                                    <h6 class="text-cyan">IPv4</h6>
                                </div>
                            </div>
                        </a>
                    </div>
                </div>
                <form action="#">
                     <div class="form-group text-center pl-3 pr-3">
                        <button class="btn btn-rounded btn-primary" id="fieldSubmit" type="button">Create</button>
                        <button class="btn btn-rounded btn-secondary" type="button" data-dismiss="modal">Close</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- /.modal -->

<!--Select Modal-->
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
                        <label for="select">Label</label>
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
                        <button class="btn btn-rounded btn-primary" type="submit">Save</button>
                        <button class="btn btn-rounded btn-secondary" type="button" data-dismiss="modal">Close</button>
                    </div>
                </form>

            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<!--Select Modal-->


@endsection
@section('scripts')
{{-- <script src="{{asset('assets/libs/dragula/dist/dragula.min.js')}}"></script> --}}
{{-- <script src="{{asset('assets/libs/jquery.repeater/jquery.repeater.min.js')}}"></script> --}}
{{-- <script src="{{asset('assets/extra-libs/jquery.repeater/repeater-init.js')}}"></script> --}}
{{-- <script src="{{asset('assets/extra-libs/jquery.repeater/dff.js')}}"></script> --}}

<!-- jQuery ui files-->
<link rel="stylesheet" href="https://code.jquery.com/ui/1.12.0/themes/smoothness/jquery-ui.css">
<script src="https://code.jquery.com/ui/1.12.0/jquery-ui.min.js" integrity="sha256-eGE6blurk5sHj+rmkfsGYeKyZx3M4bG+ZlFyA7Kns7E=" crossorigin="anonymous"></script>

<script>
    let template_submit_route = "{{asset('/save-asset-template')}}";
</script>

{{-- Linked page JS --}}
<!-- <script src="{{asset('public/js/help_desk/asset_manager/template.js').'?ver='.rand()}}"></script> -->
@include('js_files.help_desk.asset_manager.templateJs')

@endsection
