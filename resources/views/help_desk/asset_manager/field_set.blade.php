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
    
}
</style>
<div class="page-breadcrumb">
    <div class="row">
        <div class="col-md-5 align-self-center">
            <!--<h4 class="page-title">Tickets Manager</h4>-->
            <div class="d-flex align-items-center">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <!-- <li class="breadcrumb-item"><a href="{{route('home')}}">Home</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Wizard</li> -->
                    </ol>
                </nav>
            </div>
        </div>
    </div>
</div>
<div class="container">
    <div class="card">
        <div class="row p-3">
            <div class="col-md-12">
                <button type="button" class="btn btn-success" data-toggle="modal" data-target="#fieldInsert-Model"> Insert Field <i class="fa fa-plus" style="font-weight:600;"></i> </button>             
            </div>
            <div class="col-md-12 pt-3 ">
                <div class="head text-center hidden">
                    <h4> Please Insert a Field Here from Insert Field Button </h4>
                </div>
                
                <div class="tail">
                    <div class="row" id="card-colors">
                        {{-- <div class="col-md-12 col-sm-12">
                            <div class="card card-hover">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-6 ">
                                            <h3 class="card-title">Input Field</h3>
                                        </div>
                                        <div class="col-md-6 ">
                                            <a type="button" data-toggle="modal" data-target="#inputField-modal" class="float-right"><i class=" fas fa-cog "></i></a>
                                            <a href="javascript:void(0)" class="float-right pr-3"><i class=" fas fa-trash-alt red"></i></a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12 col-sm-12">
                            <div class="card card-hover">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-6 ">
                                            <h3 class="card-title">Email Address</h3>
                                        </div>
                                        <div class="col-md-6 ">
                                        <a type="button" data-toggle="modal" data-target="#emailField-modal" class="float-right"><i class=" fas fa-cog "></i></a>
                                            <a href="javascript:void(0)" class="float-right pr-3"><i class=" fas fa-trash-alt red"></i></a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12 col-sm-12">
                            <div class="card card-hover">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-6 ">
                                            <h3 class="card-title">Phone Number</h3>
                                        </div>
                                        <div class="col-md-6 ">
                                        <a type="button" data-toggle="modal" data-target="#phoneField-modal" class="float-right"><i class=" fas fa-cog "></i></a>
                                            <a href="javascript:void(0)" class="float-right pr-3"><i class=" fas fa-trash-alt red"></i></a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12 col-sm-12">
                            <div class="card card-hover">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-6 ">
                                            <h3 class="card-title">Text Area</h3>
                                        </div>
                                        <div class="col-md-6 ">
                                        <a type="button" data-toggle="modal" data-target="#textField-modal" class="float-right"><i class=" fas fa-cog "></i></a>
                                            <a href="javascript:void(0)" class="float-right pr-3"><i class=" fas fa-trash-alt red"></i></a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12 col-sm-12">
                            <div class="card card-hover">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-6 ">
                                            <h3 class="card-title">Select (Dropdown)</h3>
                                        </div>
                                        <div class="col-md-6 ">
                                        <a type="button" data-toggle="modal" data-target="#selectField-modal" class="float-right"><i class=" fas fa-cog "></i></a>
                                            <a href="javascript:void(0)" class="float-right pr-3"><i class=" fas fa-trash-alt red"></i></a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div> --}}
                        </ul>
                </div>
                <div class="col-12 text-center">
                    <button type="button" class="btn btn-success"> Save Template </button>
                </div>
            </div>
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
                <div>
                <div class="form-group ">
                    <label class="mr-sm-2 text-cyan" for="FieldSize">Select orientation of the Row</label>
                        <select class=" form-control text-cyan" id="FieldSize"  style="border-color:#4fc3f7 !important;">
                            <option value="12">12</option>
                            <option value="6" >6</option>
                            <option value="4">4</option>
                            <option value="3">3</option>
                        </select>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4">
                        <a class="buttonPush" data-template_name="text">
                            <div class="card border-cyan card-hover">
                                <div class="box p-2 rounded   text-center">
                                    <h5 class="font-weight-light text-cyan"><i class="fas fa-edit"></i></h5>
                                    <h6 class="text-cyan">Input Field</h6>
                                </div>
                            </div>
                        </a>
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
                </div>
                <form action="#">
                     <div class="form-group text-center pl-3 pr-3">
                        <button class="btn btn-rounded btn-primary" id="fieldSubmit" type="button">Create</button>
                        <button class="btn btn-rounded btn-secondary" type="button" data-dismiss="modal">Close</button>
                    </div>
                </form>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->



<!--Input Modal-->
 <div id="inputField-modal" class="modal fade" tabindex="-1" role="dialog"  data-backdrop="static" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="text-center bg-info p-3">
                <span>
                    <h2 style="color:#fff !important;" id="headinglabel"> Field Setting </h2>
                </span>
            </div>
            <div class="modal-body">
                <form action="#" class="pl-3 pr-3" id="fields-form">
                    <div id="dyn-data"></div>
                    {{-- <div class="form-group">
                        <label for="emailaddress1">Label</label>
                        <input class="form-control" type="text" id="emailaddress1"
                            required="" placeholder="Name">
                    </div> --}}
                    <!-- <div class="form-group">
                    <label class="mr-sm-2" for="FieldSize">Size</label>
                        <select class=" form-control " id="FieldSize">
                            <option value="12">12</option>
                            <option value="6" >6</option>
                            <option value="4">4</option>
                            <option value="3">3</option>
                        </select>
                    </div> -->
                    <div class="form-group">
                        <div class="custom-control custom-checkbox">
                            <input type="checkbox" class="custom-control-input"
                                id="customCheck2">
                            <label class="custom-control-label"
                                for="customCheck2">Is Mandatory</label>
                        </div>
                    </div>
                    <div class="form-group text-center">
                        <button class="btn btn-rounded btn-primary" type="button" onclick="saveFieldSetting()">Save</button>
                        <button class="btn btn-rounded btn-secondary" type="button" data-dismiss="modal">Close</button>
                    </div>
                </form>

            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<!--Input Modal-->
<!--Email Modal-->
<div id="emailField-modal" class="modal fade" tabindex="-1" role="dialog"  data-backdrop="static" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="text-center bg-info p-3">
                <a href="index.html" class="text-success">
                    <span><img class="mr-2" src=""
                            alt="" height="18"><img
                            src="" alt=""
                            height="18"></span>
                </a>
            </div>
            <div class="modal-body">
                <form action="#" class="pl-3 pr-3">
                    <div class="form-group">
                        <label for="emailaddress1">Label</label>
                        <input class="form-control" type="text" id="emailaddress1"
                            required="" placeholder="Name">
                    </div>
                    <!-- <div class="form-group">
                    <label class="mr-sm-2" for="FieldSize">Size</label>
                        <select class=" form-control " id="FieldSize">
                            <option value="12" selected>12</option>
                            <option value="6" >6</option>
                            <option value="4">4</option>
                            <option value="3">3</option>
                        </select>
                    </div> -->
                    <div class="form-group">
                        <div class="custom-control custom-checkbox">
                            <input type="checkbox" class="custom-control-input"
                                id="customCheck2">
                            <label class="custom-control-label"
                                for="customCheck2">Required ? </label>
                        </div>
                    </div>
                    <div class="form-group text-center">
                        <button class="btn btn-rounded btn-primary" type="submit">Create</button>
                        <button class="btn btn-rounded btn-secondary" type="button" data-dismiss="modal">Close</button>
                    </div>
                </form>

            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<!--Emai Modal-->
<!--Phone Modal-->
<div id="phoneField-modal" class="modal fade" tabindex="-1" role="dialog"  data-backdrop="static" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="text-center bg-info p-3">
                <a href="index.html" class="text-success">
                    <span><img class="mr-2" src=""
                            alt="" height="18"><img
                            src="" alt=""
                            height="18"></span>
                </a>
            </div>
            <div class="modal-body">
                <form action="#" class="pl-3 pr-3">
                    <div class="form-group">
                        <label for="emailaddress1">Label</label>
                        <input class="form-control" type="text" id="emailaddress1"
                            required="" placeholder="Name">
                    </div>
                    <!-- <div class="form-group">
                    <label class="mr-sm-2" for="FieldSize">Size</label>
                        <select class=" form-control " id="FieldSize">
                            <option value="12">12</option>
                            <option value="6" >6</option>
                            <option value="4">4</option>
                            <option value="3">3</option>
                        </select>
                    </div> -->
                    <div class="form-group">
                        <div class="custom-control custom-checkbox">
                            <input type="checkbox" class="custom-control-input"
                                id="customCheck2">
                            <label class="custom-control-label"
                                for="customCheck2">Required ? </label>
                        </div>
                    </div>
                    <div class="form-group text-center">
                        <button class="btn btn-rounded btn-primary" type="submit">Create</button>
                        <button class="btn btn-rounded btn-secondary" type="button" data-dismiss="modal">Close</button>
                    </div>
                </form>

            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<!--Phone Modal-->
<!--TextArea Modal-->
<div id="textField-modal" class="modal fade" tabindex="-1" role="dialog"  data-backdrop="static" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="text-center bg-info p-3">
                <a href="index.html" class="text-success">
                    <span><img class="mr-2" src=""
                            alt="" height="18"><img
                            src="" alt=""
                            height="18"></span>
                </a>
            </div>
            <div class="modal-body">
                <form action="#" class="pl-3 pr-3">
                    <div class="form-group">
                        <label for="emailaddress1">Label</label>
                        <input class="form-control" type="text" id="emailaddress1"
                            required="" placeholder="Name">
                    </div>
                    <!-- <div class="form-group">
                    <label class="mr-sm-2" for="FieldSize">Size</label>
                        <select class=" form-control " id="FieldSize">
                            <option value="12">12</option>
                            <option value="6" >6</option>
                            <option value="4">4</option>
                            <option value="3">3</option>
                        </select>
                    </div> -->
                    <div class="form-group">
                        <div class="custom-control custom-checkbox">
                            <input type="checkbox" class="custom-control-input"
                                id="customCheck2">
                            <label class="custom-control-label"
                                for="customCheck2">Required ? </label>
                        </div>
                    </div>
                    <div class="form-group text-center">
                        <button class="btn btn-rounded btn-primary" type="submit">Create</button>
                        <button class="btn btn-rounded btn-secondary" type="button" data-dismiss="modal">Close</button>
                    </div>
                </form>

            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<!--TextArea Modal-->

<!--Select Modal-->
<div id="selectField-modal" class="modal fade" tabindex="-1" role="dialog"  data-backdrop="static" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="text-center bg-info p-3">
                <a href="index.html" class="text-success">
                    <span>
                        <img class="mr-2" src=""
                        alt="" height="18">
                        <img
                        src="" alt=""
                        height="18">
                    </span>
                </a>
            </div>
            <div class="modal-body">
                <form action="#" class="pl-3 pr-3">
                    <div class="form-group">
                        <div class="form-check form-check-inline">
                            <div class="custom-control custom-radio">
                                <input type="radio" class="custom-control-input" id="customControlValidation2" name="radio-stacked">
                                <label class="custom-control-label" for="customControlValidation2">Single</label>
                            </div>
                        </div>
                        <div class="form-check form-check-inline">
                            <div class="custom-control custom-radio">
                                <input type="radio" class="custom-control-input" id="customControlValidation3" name="radio-stacked">
                                <label class="custom-control-label" for="customControlValidation3">Multiple</label>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="select">Label</label>
                        <input class="form-control" type="text" id="select"
                            required="" placeholder="Selection Label">
                    </div>
                    <div class="email-repeater form-group">
                        <div data-repeater-list="repeater-group">
                            <div data-repeater-item class="row mb-3">
                                <div class="col-md-10">
                                    <input type="text" class="form-control" placeholder="Option Text">
                                </div>
                                <div class="col-md-10">
                                    <input type="text" class="form-control" placeholder="Option Value">
                                </div>
                                <div class="col-md-2">
                                    <button data-repeater-delete="" class="btn btn-danger waves-effect waves-light" type="button">
                                        <i class="ti-close"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                        <button type="button" data-repeater-create="" class=" btn btn-info waves-effect waves-light">Add Option
                        </button>
                    </div>
                    <!-- <div class="form-group">
                        <label class="mr-sm-2" for="FieldSize">Size</label>
                        <select class=" form-control " id="FieldSize">
                            <option value="12">12</option>
                            <option value="6" selected>6</option>
                            <option value="4">4</option>
                            <option value="3">3</option>
                        </select>
                    </div> -->
                    <div class="form-group">
                        <div class="custom-control custom-checkbox">
                            <input type="checkbox" class="custom-control-input"
                                id="customCheck2">
                            <label class="custom-control-label"
                                for="customCheck2">Is Mandatory </label>
                        </div>
                    </div>
                    <div class="form-group text-center">
                        <button class="btn btn-rounded btn-primary" type="submit">Create</button>
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
<script src="{{asset('assets/libs/dragula/dist/dragula.min.js')}}"></script>
<script src="{{asset('assets/libs/jquery.repeater/jquery.repeater.min.js')}}"></script>
<script src="{{asset('assets/extra-libs/jquery.repeater/repeater-init.js')}}"></script>
<script src="{{asset('assets/extra-libs/jquery.repeater/dff.js')}}"></script>

{{-- Linked page JS --}}
<link rel="stylesheet" href="https://code.jquery.com/ui/1.12.0/themes/smoothness/jquery-ui.css">
<!-- <script src="https://code.jquery.com/ui/1.12.0/jquery-ui.min.js" integrity="sha256-eGE6blurk5sHj+rmkfsGYeKyZx3M4bG+ZlFyA7Kns7E=" crossorigin="anonymous"></script> -->
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<!-- <script src="{{asset('public/js/help_desk/asset_manager/template.js').'?ver='.rand()}}"></script> -->
@include('js_files.help_desk.asset_manager.templateJs')

<!-- <script>
    $(".connectedSortable").sortable({
            connectWith: ".connectedSortable",
            opacity: 1,
        }).disableSelection();

        $(".connectedSortable").trigger("sortupdate");    
</script> -->
@endsection
