@extends('layouts.staff-master-layout')
@section('body-content')
<link rel="stylesheet" href="https://unpkg.com/multiple-select@1.5.2/dist/multiple-select.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/1.10.23/css/jquery.dataTables.min.css">

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
<div class="container-fluid">
    <div class="row">
        <div class="col-lg-12 col-md-12">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-6 col-md-6">
                            <h4 class="card-title">Assets</h4>
                        </div>
                        <div class="col-lg-3 col-md-3">
                            <button type="button" class="btn btn-info" onclick="ShowAssetModel()"
                                style="float:right;"><i class="fas fa-plus"></i>&nbsp;Add
                                Asset
                            </button>
                        </div>
                        <div class="col-lg-3 col-md-3">
                            <button type="button" class="btn btn-info" onclick="ShowAssetModel()"
                                style="float:right;"><i class="fas fa-plus"></i>&nbsp;Add
                                Asset Template
                            </button>
                        </div>
                        
                    </div>
                    <br>


                    <!-- <div class="row">
                        <div class="col-md-4">
                            <select class="multiple-select mt-2 mb-2" name="as_select" id="as_select" placeholder="Select Menu" multiple="multiple" selected="selected">
                                <option value="0">Sr#</option>
                                <option value="1">AssetID</option>
                                <option value="2">image</option>
                                <option value="3">Name</option>
                                <option value="4">Description</option>
                                <option value="5">Customer</option>
                                <option value="6">Company</option>
                                <option value="7">Model</option>
                                <option value="8">Categories</option>
                                <option value="9">Actions</option>
                            </select>
                        </div>
                        <div class="col-md-4"></div>
                        <div class="col-md-4"></div>
                    </div> -->
                    
                    <div class="table-responsive">
                        <table id="asset-table-list"
                            class="table table-striped table-bordered no-wrap asset-table-list">

                            <thead>
                                <tr>
                                    <th><input type="checkbox" id="checkAll" name="assets[]" value="0"></th>
                                    <th>AssetID</th>
                                    <th>Image</th>
                                    <th>Name</th>
                                    <th>Description</th>
                                    <th>Customer</th>
                                    <th>Company</th>
                                    <th>Model</th>
                                    <th>Categories</th>
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

    <div class="modal fade" id="asset" role="dialog"  data-backdrop="static" aria-labelledby="myLargeModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header d-flex align-items-center">
                    <h4 class="modal-title" id="edit-asset">Add Asset</h4>
                    <button type="button" class="close ml-auto" data-dismiss="modal" aria-hidden="true">×</button>
                </div>
                <div class="modal-body">
                    
                    <form class="form-horizontal mt-4" id="save_asset_form" enctype="multipart/form-data"
                        action="{{asset('/save-asset')}}" method="post">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">

                                    <label>Asset Template</label>
                                    <select class="select form-control" id="customers_assign_to"
                                        name="customers_assign_to"
                                    >
                                        <option value="">Default</option>
                                        <option value="">Server</option>
                                        <option value="">Truck</option>

                                    </select>
                                </div>
                            </div>

                        </div>

                        <button type="submit" class="btn btn-primary" style="float:right;">Save</button>
                    </form>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->
</div>
@stop