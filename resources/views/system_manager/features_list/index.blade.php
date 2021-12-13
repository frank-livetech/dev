@extends('layouts.staff-master-layout')
@section('body-content')

<link href="//cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css"/>
<link rel="stylesheet" href="https://unpkg.com/multiple-select@1.5.2/dist/multiple-select.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/1.10.23/css/jquery.dataTables.min.css">
<div class="page-breadcrumb">
    <div class="row">
        <div class="col-md-5 align-self-center">
            <!--<h3 class="page-title">Dashboard</h3>-->
            <div class="d-flex align-items-center">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Staff Manager</li>
                    </ol>
                </nav>
            </div>
        </div>

        <div class="col-md-7 d-flex justify-content-end align-self-center d-none d-md-flex">
            <button class="btn btn-success" id="btn-add-new-user" data-toggle="modal" data-target="#addFeatureModal"><i
                    class="mdi mdi-plus-circle"></i> Add Feature</button>
        </div>
    </div>
</div>

<div class="container-fluid">

    <div class="row">

        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <div class="widget-header widget-header-small">
                        <!-- <span class="loader_lesson_plan_form"></span> -->
                    </div>
                    <div class="widget-body">
                        <div class="widget-main">
                        <!--  -->
                            <div class="row">
                                <div class="col-12">
                                    <div class="table-responsive">
                                        <div class="row">
                                            <div class="col-md-12" style="text-align:right;">
                                                <select class="multiple-select mt-2 mb-2" name="feature_select" id="feature_select" placeholder="Show/Hide" multiple="multiple" selected="selected">
                                                    <option value="0">Sr #</option>
                                                    <option value="1">Feature Title</option>
                                                    <option value="2">Route</option>
                                                    <option value="3">Active</option>
                                                    <option value="4">Have Access</option>
                                                    <option value="5">Sequence</option>
                                                    <option value="6">Access</option>
                                                    <option value="7">Actions</option>
                                                </select>
                                            </div>
                                        </div>
                                        <table id="Feature_table" class="table text-center table-hover table-striped table-bordered no-wrap"
                                            style="width:100%">
                                            <thead>
                                                <tr>
                                                    <!-- <th>#</th> -->
                                                    <th>Feature Title</th>
                                                    <th>Route</th>
                                                    <th>Active</th>
                                                    <th>Have Access</th>
                                                    <th>Sequence</th>
                                                    <th>Access</th>
                                                    <th>Actions</th>
                                                </tr>
                                            </thead>
                                            <tbody id="Feature_body">

                                            </tbody>
                                        </table>
                                        <div class="loader_container">
                                            <div class="loader"></div>
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
</div>


<!-- feature add feature list -->
<div class="modal fade" id="addFeatureModal" tabindex="-1" role="dialog"  data-backdrop="static">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header d-flex align-items-center">
                <h5 class="modal-title">Add Feature</h5>
                <button type="button" class="close ml-auto" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="add-contact-box">
                    <div class="add-contact-content">
                        <form id="addFeatureForm">

                            <div class="form-group">
                                <div class="row">
                                    <div class="form-check ml-3">
                                        <input class="form-check-input" type="radio" name="flexRadioDefault" id="flexRadioDefault1" checked>
                                        <label class="form-check-label" for="flexRadioDefault1"> Menu </label>
                                    </div>
                                    <div class="form-check ml-3">
                                        <input class="form-check-input" type="radio" name="flexRadioDefault" id="flexRadioDefault2">
                                        <label class="form-check-label" for="flexRadioDefault2"> Toggle Menu </label>
                                    </div>
                                    <div class="form-check ml-3">
                                        <input class="form-check-input" type="radio" name="flexRadioDefault" id="flexRadioDefault3">
                                        <label class="form-check-label" for="flexRadioDefault3">widget/button</label>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group" id="menu-title">
                                <input type="text" id="title" class="form-control" placeholder="Menu Title">
                                <span id="title_error" class="text-danger small"></span>
                            </div>

                            <div class="form-group" id="route-title">
                                <input type="text" id="route" class="form-control" placeholder="Route">
                                <span id="route_error" class="text-danger small"></span>
                            </div>

                            <div class="form-group">
                                <input type="number" id="sequence" class="form-control" placeholder="Sequence">
                                <span id="sequence_error" class="text-danger small"></span>
                            </div>
                            <div class="form-group">
                                <input type="text" id="icon" class="form-control" placeholder="Menu Icon">
                                <span id="icon_error" class="text-danger small"></span>
                            </div>

                            <div class="form-group">
                                <label for="">Parent Menu</label>
                                <select name="parent_id" class="form-control" id="parent_id">
                                </select>
                                <span id="parent_error" class="text-danger small"></span>
                            </div>
                            
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox"  name="is_active" id="is_active">
                                <label class="form-check-label" for="is_active"> is Active </label>
                            </div>

                            <div class="form-group mt-3">
                                <button type="submit" class="btn btn-success">Save</button>
                                <!-- <button class="btn btn-danger" data-dismiss="modal"> Discard</button> -->
                            </div>

                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<!-- edit feature list -->
<div class="modal fade" id="editFeatureModal" tabindex="-1" role="dialog"  data-backdrop="static">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header d-flex align-items-center">
                <h5 class="modal-title">Update Feature</h5>
                <button type="button" class="close ml-auto" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="loader_container" style="display:none">
                    <div class="loader"></div>
                </div>
                <div class="add-contact-box">
                    <div class="add-contact-content">
                        <form id="editFeatureForm">
                            <input type="hidden" id="f_id">
                            <div class="form-group">
                                <div class="row">
                                    <div class="form-check ml-3">
                                        <input class="form-check-input" type="radio" name="flexRadioDefault" id="flexRadioDefault4" checked>
                                        <label class="form-check-label" for="flexRadioDefault4"> Menu </label>
                                    </div>
                                    <div class="form-check ml-3">
                                        <input class="form-check-input" type="radio" name="flexRadioDefault" id="flexRadioDefault5">
                                        <label class="form-check-label" for="flexRadioDefault5"> Toggle Menu </label>
                                    </div>
                                    <div class="form-check ml-3">
                                        <input class="form-check-input" type="radio" name="flexRadioDefault" id="flexRadioDefault6">
                                        <label class="form-check-label" for="flexRadioDefault6">widget/button</label>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group" id="menu-title">
                                <input type="text" id="edit_title" class="form-control" placeholder="Menu Title">
                                <span id="title_error" class="text-danger small"></span>
                            </div>

                            <div class="form-group title_box" id="route-title">
                                <input type="text" id="edit_route" class="form-control" placeholder="Route">
                                <span id="route_error" class="text-danger small"></span>
                            </div>

                            <div class="form-group">
                                <input type="number" id="edit_sequence" class="form-control" placeholder="Sequence">
                                <span id="sequence_error" class="text-danger small"></span>
                            </div>
                            <div class="form-group icon_box">
                                <input type="text" id="edit_icon" class="form-control" placeholder="Menu Icon">
                                <span id="icon_error" class="text-danger small"></span>
                            </div>

                            <div class="form-group prnt_box">
                                <label for="">Parent Menu</label>
                                <select required name="edit_parent_id" class="form-control" id="edit_parent_id">
                                </select>
                            </div>

                            <div class="form-check chk_box">
                                <input class="form-check-input" type="checkbox"  name="edit_is_active" id="edit_is_active">
                                <label class="form-check-label" for="edit_is_active"> is Active </label>
                            </div>

                            <div class="form-group mt-3">
                                <button type="submit" class="btn btn-success">Save</button>
                                <!-- <button class="btn btn-danger" data-dismiss="modal"> Discard</button> -->
                            </div>

                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>



<!-- feature -  access control modal -->
<div class="modal fade" id="featureAccessModal" tabindex="-1" role="dialog"  data-backdrop="static">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header d-flex align-items-center">
                <h5 class="modal-title">Feature Access</h5>
                <button type="button" class="close ml-auto" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="add-contact-box">
                    <div class="add-contact-content">
                        <form id="assingAccessForm" method="post">
                            <input type="hidden" id="feature_id">
                            <div class="form-group">
                                <div class="contact-name">
                                    <select id="roles" class="form-control w-100" multiple="multiple">
                                    </select>
                                    <span id="edit_title_error" class="text-danger small"></span>
                                </div>
                            </div>
                            <div class="form-group">
                                <button type="submit" class="btn btn-success">Save</button>
                                <!-- <button class="btn btn-danger" data-dismiss="modal"> Discard</button> -->
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<!-- feature -  edit access control modal -->
<div class="modal fade" id="editFeatureAccessModal" tabindex="-1" role="dialog"  data-backdrop="static">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header d-flex align-items-center">
                <h5 class="modal-title">update Feature Access</h5>
                <button type="button" class="close ml-auto" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="add-contact-box">
                    <div class="add-contact-content">
                        <form id="editAssingAccessForm" method="POST" action="{{url('update_access')}}">
                            <input type="hidden" id="feature_id2">
                            <div class="form-group">
                                <div class="contact-name">
                                    <select id="edit_roles" class="form-control w-100" multiple="multiple">
                                    </select>
                                    <span id="edit_title_error" class="text-danger small"></span>
                                </div>
                            </div>
                            <div class="form-group">
                                <button type="submit" class="btn btn-success">Save</button>
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

@include('js_files.system_manager.feature_list.feature_listJs')

<!-- <script src="{{ asset('js/pages/features/feature_list.js').'?ver='.rand()}}"></script> -->
<script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>
<script src="https://unpkg.com/multiple-select@1.5.2/dist/multiple-select.min.js"></script>
<script src="https://cdn.datatables.net/1.10.23/js/jquery.dataTables.min.js"></script>
@endsection
<style>
table th {
    font-size: 11px;
    padding:10px;
}

table td {
    vertical-align: middle !important;
}

.selectpickertag .input-group>.btn-group>.btn {
    line-height: 17px;
    overflow: hidden;
}

.select2-search__field {
    width: auto !important;
}

.fa-asterisk {
    font-size: 6px;
    margin-right: 5px;
}
.select2-container--classic .select2-selection--multiple .select2-selection__choice, .select2-container--default .select2-selection--multiple .select2-selection__choice, .select2-container--default .select2-selection--multiple .select2-selection__choice__remove {
    background-color: #e7e7e7 !important;
    border-color: #009efb;
    color: black !important;
}

</style>