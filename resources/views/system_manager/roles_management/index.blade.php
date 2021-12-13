@extends('layouts.staff-master-layout')
@section('body-content')

<link href="//cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://unpkg.com/multiple-select@1.5.2/dist/multiple-select.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/1.10.23/css/jquery.dataTables.min.css">
<style>
#roles_table_filter label,
#roles_table_length label{
    display:inline-flex;
}
</style>


<div class="content-wrapper" style="display: block;">
    <div class="page-breadcrumb">
        <div class="row">
            <div class="col-md-5 align-self-center">
                <!--<h4 class="page-title">Roles </h4>-->
                <div class="d-flex align-items-center">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{route('home')}}">Home</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Dashboard</li>
                            <li class="breadcrumb-item active" aria-current="page">Roles Manager</li>
                        </ol>
                    </nav>
                </div>
            </div>

        </div>
    </div>
</div>
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="row" style="margin-bottom:10px;">
                        <div class="col-md-6 col-sm-6">
                            <h4 class="card-title">Roles</h4>
                        </div>

                        <div class="col-md-6 col-sm-6" style="text-align:right">
                            <a id="add_role_btn" class="btn btn-success text-white"> <i class="mdi mdi-plus-circle" style="padding-right:3px;"></i>Create New
                                Role</a>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <!-- <div class="col-12 mb-3">
                            <a class="toggle-vis btn btn-sm btn-success text-white mb-1" data-column="0">Sr</a> - 
                            <a class="toggle-vis btn btn-sm btn-success text-white mb-1" data-column="1">Name</a> - 
                            <a class="toggle-vis btn btn-sm btn-success text-white mb-1" data-column="2">Actions</a>
                        </div> -->
                        <div class="row">
                            <div class="col-md-12" style="text-align:right;">
                                <select class="multiple-select mt-2 mb-2" name="role_select" id="role_select" placeholder="Show/Hide" multiple="multiple" selected="selected">
                                    <option value="0">Sr #</option>
                                    <option value="1">Name</option>
                                    <option value="2">Action</option>
                                </select>
                            </div>
                        </div>
                        <div id="zero_config_wrapper" class="dataTables_wrapper container-fluid dt-bootstrap4">

                            <div class="row">
                                <div class="col-sm-12">
                                    <table id="roles_table" class="table table-striped table-bordered no-wrap dataTable w-100"
                                        role="grid" aria-describedby="zero_config_info">
                                        <thead>
                                            <tr role="row">
                                                <!-- <th class="sorting_asc" tabindex="0" aria-controls="zero_config"
                                                    rowspan="1" colspan="1" aria-sort="ascending"
                                                    aria-label="Name: activate to sort column descending"
                                                    style="width: 0px;">Sr</th> -->
                                                <th class="sorting_asc" tabindex="0" aria-controls="zero_config"
                                                    rowspan="1" colspan="1" aria-sort="ascending"
                                                    aria-label="Name: activate to sort column descending"
                                                    style="width: 0px;">Name</th>
                                                <th class="sorting" tabindex="0" aria-controls="zero_config" rowspan="1"
                                                    colspan="1" aria-label="Position: activate to sort column ascending"
                                                    style="width: 0px;">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody id="roles_body">

                                        </tbody>
                                    </table>
                                </div>
                                </tbody>

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
</div>


<!--  for add  record modal -->
<div class="modal fade" id="addRoleModal" tabindex="-1" role="dialog"  data-backdrop="static">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header d-flex align-items-center">
                <h5 class="modal-title">Add Role</h5>
                <button type="button" class="close ml-auto" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="add-contact-box">
                    <div class="add-contact-content">
                        <form id="addRoleForm">
                            <div class="form-group">
                                <div class="contact-name">
                                    <input type="text" id="role_name" class="form-control" placeholder="Name">
                                    <span id="role_error" class="text-danger small"></span>
                                </div>
                            </div>
                            <div class="form-group">
                                <button id="btn-edit" class="btn btn-success">Save</button>
                                <!-- <button class="btn btn-danger" data-dismiss="modal"> Discard</button> -->
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<!-- edit record modal -->
<div class="modal fade" id="editRoleModal" tabindex="-1" role="dialog"  data-backdrop="static">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header d-flex align-items-center">
                <h5 class="modal-title">Update Role</h5>
                <button type="button" class="close ml-auto" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="add-contact-box">
                    <div class="add-contact-content">
                        <form id="editRoleForm">
                            <div class="form-group">
                                <div class="contact-name">
                                    <input type="text" id="edit_role_name" class="form-control" placeholder="Name">
                                    <span id="edit_role_error" class=" text-danger small"></span>
                                </div>
                            </div>
                            <div class="form-group">
                                <button id="btn-edit" class="btn btn-success">Save</button>
                                <!-- <button class="btn btn-danger" data-dismiss="modal"> Discard</button> -->
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


@endsection
@section('scripts')
@include('js_files.system_manager.roles_management.rolesJs')

<!-- <script src="{{asset('js/pages/roles/roles.js').'?ver='.rand()}}"></script> -->
<script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>
<script src="https://unpkg.com/multiple-select@1.5.2/dist/multiple-select.min.js"></script>
<script src="https://cdn.datatables.net/1.10.23/js/jquery.dataTables.min.js"></script>
@endsection