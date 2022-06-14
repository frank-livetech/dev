<div class="card card-height">
                        <div class="card-body">

                            <div class="col-md-12 d-flex justify-content-end align-self-center">
                                <button class="btn btn-success btn-sm rounded" id="btn-add-new-user" data-bs-toggle="modal" data-bs-target="#addFeatureModal"><i
                                        class="mdi mdi-plus-circle"></i> Add Feature</button>
                            </div>

                            <div class="row mt-3">
                                <div class="col-12">
                                    <div class="table-responsive">
                                        <!-- <div class="row">
                                            <div class="col-md-4">
                                                <select class="mb-2form-select" name="feature_select" id="feature_select" placeholder="Select Menu" multiple="multiple" selected="selected">
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
                                            <div class="col-md-4"></div>
                                            <div class="col-md-4"></div>
                                        </div> -->
                                        <table id="Feature_table" class="table text-center table-hover table-striped table-bordered no-wrap w-100">
                                            <thead>
                                                <tr>
                                                    <th>#</th>
                                                    <th>Feature Title</th>
                                                    <th>Route</th>
                                                    <th>Active</th>
                                                    <th>Have Access</th>
                                                    <th>Sequence</th>
                                                    <th>Update</th>
                                                </tr>
                                            </thead>
                                            <tbody id="Feature_body">

                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    
<!--Menu Management-->
    <!-- ==================================
        feature list 
    -->
    <!-- feature add feature list -->
    <div class="modal fade" id="addFeatureModal" tabindex="-1" role="dialog" data-backdrop="static">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header d-flex align-items-center">
                    <h5 class="modal-title" class="modal-title">Add Feature</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>                </div>
                <div class="modal-body">
                    <div class="add-contact-box">
                        <div class="add-contact-content">
                            <form id="addFeatureForm">
                                <div class="form-group my-1">
                                    <div class="row">
                                        <div class="form-check mx-2">
                                            <input class="form-check-input" type="radio" name="flexRadioDefault" id="flexRadioDefault1" checked>
                                            <label class="form-check-label" for="flexRadioDefault1"> Menu </label>
                                        </div>
                                        <div class="form-check mx-2">
                                            <input class="form-check-input" type="radio" name="flexRadioDefault" id="flexRadioDefault2">
                                            <label class="form-check-label" for="flexRadioDefault2"> Toggle Menu </label>
                                        </div>
                                        <div class="form-check mx-2">
                                            <input class="form-check-input" type="radio" name="flexRadioDefault" id="flexRadioDefault3">
                                            <label class="form-check-label" for="flexRadioDefault3">widget/button</label>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group my-1  mt-1" id="menu-title">
                                    <input type="text" id="menu_title" class="form-control " placeholder="Menu Title">
                                    <span id="title_error" class="text-danger small"></span>
                                </div>

                                <div class="row mt-1">
                                    <div class="col-md-6">
                                        <div class="form-group my-1" id="route-title">
                                            <input type="text" id="route" class="form-control " placeholder="Route">
                                            <span id="route_error" class="text-danger small"></span>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group my-1">
                                            <input type="number" id="sequence" class="form-control " placeholder="Sequence">
                                            <span id="sequence_error" class="text-danger small"></span>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group my-1 mt-1">
                                    <input type="text" id="icon" class="form-control " placeholder="Menu Icon">
                                    <span id="icon_error" class="text-danger small"></span>
                                </div>

                                <div class="form-group my-1 mt-1">
                                    <label for="" class="small">Parent Menu</label>
                                    <select name="parent_id" class="form-control " id="parent_id">
                                    </select>
                                    <span id="parent_error" class="text-danger small"></span>
                                </div>

                                <div class="form-group my-1 mt-1">
                                    <label for="" class="small">Role</label>
                                    <select name="role" class="form-control select2" style="width:100%; height:44px" id="role" multiple="multiple">
                                        @foreach($roles as $role)
                                            <option value="{{$role->id}}">{{$role->name}}</option>
                                        @endforeach
                                    </select>
                                    <span id="parent_error" class="text-danger small"></span>
                                </div>
                                
                                <div class="form-check mt-1">
                                    <input class="form-check-input" type="checkbox"  name="is_active" id="is_active">
                                    <label class="form-check-label" for="is_active"> is Active </label>
                                </div>

                                <div class="form-group my-1 mt-2 text-end">
                                    <button type="submit" class="btn btn-success">Save</button>
                                    <!-- <button class="btn btn-danger" data-bs-dismiss="modal"> Discard</button> -->
                                </div>

                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- edit feature list -->
    <div class="modal fade" id="editFeatureModal" tabindex="-1" role="dialog" data-backdrop="static">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header d-flex align-items-center">
                    <h5 class="modal-title" class="modal-title">Update Feature</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="loader_container" style="display:none">
                        <div class="loader"></div>
                    </div>
                    <div class="add-contact-box">
                        <div class="add-contact-content">
                            <form id="editFeatureForm">
                                <input type="hidden" id="f_id">
                                <div class="form-group my-1">
                                    <div class="row">
                                        <div class="form-check mx-2">
                                            <input class="form-check-input" type="radio" name="flexRadioDefault" id="flexRadioDefault4" checked>
                                            <label class="form-check-label" for="flexRadioDefault4"> Menu </label>
                                        </div>
                                        <div class="form-check mx-2">
                                            <input class="form-check-input" type="radio" name="flexRadioDefault" id="flexRadioDefault5">
                                            <label class="form-check-label" for="flexRadioDefault5"> Toggle Menu </label>
                                        </div>
                                        <div class="form-check mx-2">
                                            <input class="form-check-input" type="radio" name="flexRadioDefault" id="flexRadioDefault6">
                                            <label class="form-check-label" for="flexRadioDefault6">widget/button</label>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group my-1" id="menu-title">
                                    <input type="text" id="edit_title" class="form-control" placeholder="Menu Title">
                                    <span id="title_error" class="text-danger small"></span>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group my-1" id="route-title">
                                            <input type="text" id="edit_route" class="form-control" placeholder="Route">
                                            <span id="route_error" class="text-danger small"></span>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group my-1">
                                            <input type="number" id="edit_sequence" class="form-control" placeholder="Sequence">
                                            <span id="sequence_error" class="text-danger small"></span>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group my-1">
                                    <input type="text" id="edit_icon" class="form-control" placeholder="Menu Icon">
                                    <span id="icon_error" class="text-danger small"></span>
                                </div>

                                <div class="form-group my-1">
                                    <label for="">Parent Menu</label>
                                    <select required name="edit_parent_id" class="form-control" id="edit_parent_id">
                                    </select>
                                </div>

                                <div class="form-group my-1">
                                    <label for="" class="small">Role</label>
                                    <select name="edit_role" class="form-control select2" style="width:100%; height:44px" id="edit_role" multiple="multiple">
                                        @foreach($roles as $role)
                                            <option value="{{$role->id}}">{{$role->name}}</option>
                                        @endforeach
                                    </select>
                                    <span id="parent_error" class="text-danger small"></span>
                                </div>

                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox"  name="edit_is_active" id="edit_is_active">
                                    <label class="form-check-label" for="edit_is_active"> is Active </label>
                                </div>

                                <div class="form-group my-1 mt-3 text-end">
                                    <button type="submit" class="btn btn-success">Save</button>
                                    <!-- <button class="btn btn-danger" data-bs-dismiss="modal"> Discard</button> -->
                                </div>

                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


<!--Menu Management-->
