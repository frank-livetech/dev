@extends('layouts.staff-master-layout')
@section('body-content')
<link rel="stylesheet" href="https://unpkg.com/multiple-select@1.5.2/dist/multiple-select.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/1.10.23/css/jquery.dataTables.min.css">
<style>
    .table th {
            padding-top:20px !important;
            padding-bottom:20px !important;
            font-size:1rem;
        }
</style>
<div class="page-breadcrumb">
    <div class="row">
        <div class="col-md-5 align-self-center">
            <div class="d-flex align-items-center">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{route('home')}}">Home</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Short Code</li>
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
                    <div class="row">
                        <div class="col-md-12">
                            <button type="button" class="btn btn-success" data-toggle="modal" data-target="#ShowModal"
                                style="float:right;"><i class="mdi mdi-plus-circle" ></i>&nbsp;Add
                            </button>
                        </div>
                    </div>
                    <br>
                    
                    <div class="table-responsive">
                        <!-- <div class="col-12 mb-3">
                            <a class="toggle-vis btn btn-sm btn-success text-white mb-1" data-column="0">#</a> - 
                            <a class="toggle-vis btn btn-sm btn-success text-white mb-1" data-column="1">AssetID</a> - 
                            <a class="toggle-vis btn btn-sm btn-success text-white mb-1" data-column="2">Image</a> - 
                            <a class="toggle-vis btn btn-sm btn-success text-white mb-1" data-column="3">Name</a> - 
                            <a class="toggle-vis btn btn-sm btn-success text-white mb-1" data-column="4">Description</a> - 
                            <a class="toggle-vis btn btn-sm btn-success text-white mb-1" data-column="5">Customer</a> - 
                            <a class="toggle-vis btn btn-sm btn-success text-white mb-1" data-column="6">Company</a> - 
                            <a class="toggle-vis btn btn-sm btn-success text-white mb-1" data-column="7">Model</a> -
                            <a class="toggle-vis btn btn-sm btn-success text-white mb-1" data-column="8">Categories</a> -
                            <a class="toggle-vis btn btn-sm btn-success text-white mb-1" data-column="9">Actions</a>
                        </div> -->

                        <table id="shortCodeTable" class="table table-striped table-bordered w-100 asset-table-list text-center">

                            <thead>
                                <tr>
                                    <th>Sr</th>
                                    <th>Code</th>
                                    <th>Description</th>
                                    <th>Actions</th>

                                </tr>
                            </thead>
                            <tbody></tbody>

                            <div class="loader_container">
                                <div class="loader"></div>
                            </div>

                        </table>

                    </div>
                </div>
            </div>

            <!-- Modal -->

        </div>
    </div>

    <div class="modal fade" id="ShowModal" role="dialog"  data-backdrop="static" aria-labelledby="myLargeModalLabel" aria-hidden="true">
        <div class="modal-dialog ">
            <div class="modal-content">
                <div class="modal-header d-flex align-items-center">
                    <h4 class="modal-title" id="edit-asset">Add Here</h4>
                    <button type="button" class="close ml-auto" data-dismiss="modal" aria-hidden="true">×</button>
                </div>
                <div class="modal-body">
                    
                <div class="row">
                        <div class="col-xs-12 col-sm-12">

                            <form id="addRecord" method="POST" action="{{url('add_short_codes')}}">
                                <!-- <legend>Form</legend> -->
                                <fieldset>
                                    <div class="form-group">
                                        <div class="col-sm-12">
                                            <label class="control-label">Add Code</label>
                                            <input type="text" class="form-control" id="code"  required="required">
                                        </div>
                                        <div class="col-sm-12">
                                            <label class="control-label">Description</label>
                                            <input type="text" class="form-control" id="desc"  required="required">
                                        </div>
                                    </div>
                                </fieldset>

                                <div class="text-right">
                                    <button class="btn btn-sm btn-success" id="save" type="submit">Done</button>
                                    <button style="display:none" id="processing" class="btn btn-sm btn-success" type="button" disabled><i class="fas fa-circle-notch fa-spin"></i> Processing</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->



    <!-- edit record modal -->
    <div class="modal fade" id="ShowUpdateModal" role="dialog"  data-backdrop="static" aria-labelledby="myLargeModalLabel" aria-hidden="true">
        <div class="modal-dialog ">
            <div class="modal-content">
                <div class="modal-header d-flex align-items-center">
                    <h4 class="modal-title" id="edit-asset">Update Here</h4>
                    <button type="button" class="close ml-auto" data-dismiss="modal" aria-hidden="true">×</button>
                </div>
                <div class="modal-body">
                    
                <div class="row">
                        <div class="col-xs-12 col-sm-12">

                            <form id="updateRecord" method="POST" action="{{url('update_short_codes')}}">
                                <!-- <legend>Form</legend> -->
                                <fieldset>
                                    <div class="form-group">
                                        <div class="col-sm-12">
                                            <label class="control-label">Add Code</label>
                                            <input type="text" class="form-control" id="update_code" required="required">
                                        </div>
                                        <div class="col-sm-12">
                                            <label class="control-label">Description</label>
                                            <input type="text" class="form-control" id="update_desc" required="required">
                                        </div>
                                    </div>
                                   
                                        
                                </fieldset>

                                <div class="text-right">
                                    <br>
                                    <button class="btn btn-sm btn-success" id="update" type="submit">Done </button>
                                    <button style="display:none" id="update_processing" class="btn btn-sm btn-success" type="button" disabled><i class="fas fa-circle-notch fa-spin"></i> Processing</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->


</div>


<!--<script src="theme_assets/libs/jsgrid/db.js"></script>-->
<script type="text/javascript" src="{{asset('assets/dist/js/flashy.min.js')}}"></script>

@endsection
@section('scripts')
<script>
let get_codes_route = "{{asset('/get_all_short_codes')}}";

</script>
<script src="{{asset('assets/libs/jquery.repeater/jquery.repeater.min.js')}}"></script>
<script src="{{asset('assets/extra-libs/jquery.repeater/repeater-init.js')}}"></script>
<script src="{{asset('assets/extra-libs/jquery.repeater/dff.js')}}"></script>
<script src="https://unpkg.com/multiple-select@1.5.2/dist/multiple-select.min.js"></script>
<script src="https://cdn.datatables.net/1.10.23/js/jquery.dataTables.min.js"></script>
<!-- <script src="{{asset('public/js/short_codes/short_codes.js').'?ver='.rand()}}"></script> -->
@include('js_files.short_codes.short_codesJs')

<script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>
@endsection
<style>
    #image_id {
        height: 30px;
        width: 30px;
    }

</style>
