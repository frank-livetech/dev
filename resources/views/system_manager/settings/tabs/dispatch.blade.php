<div class="card">
                        <div class="card-body">

                            <h4 class="card-title mb-3">Customer Settings</h4>

                            <ul class="nav nav-pills bg-nav-pills nav-justified mb-3">
                                <li class="nav-item">
                                    <a href="#home1" data-bs-toggle="tab" aria-expanded="false" class="nav-link rounded-0 active">
                                        <i class="mdi mdi-home-variant d-lg-none d-block mr-1"></i>
                                        <span class="d-none d-lg-block">General Settings</span>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="#dispatch_status" data-bs-toggle="tab" aria-expanded="false" class="nav-link rounded-0 ">
                                        <i class="mdi mdi-home-variant d-lg-none d-block mr-1"></i>
                                        <span class="d-none d-lg-block">Customization</span>
                                    </a>
                                </li>
                                

                            </ul>

                            <div class="tab-content">
                                <div class="tab-pane active" id="home1">

                                </div>


                                <div class="tab-pane show" id="dispatch_status">
                                    <div id="accordion" class="accordion accordion-border">
                                        <div class="card mb-0">
                                            <div class="" id="dispatch_status_collapse">
                                                <h5 class="m-0">
                                                    <a class="accordion-button d-flex align-items-center pt-2 pb-2 collapsed"
                                                        data-bs-toggle="collapse" href="#collapseStatusDispatch" aria-expanded="false"
                                                        aria-controls="collapseThree">
                                                        Dispatch Status <span class="ml-auto"><i
                                                                class="mdi mdi-chevron-down accordion-arrow"></i></span>
                                                    </a>
                                                </h5>
                                            </div>
                                            <div id="collapseStatusDispatch" class="collapse" aria-labelledby="dispatch_status_collapse"
                                                data-parent="#accordion" style="">
                                                <div class="card-body">
                                                    <div class="widget-header widget-header-small">
                                                        <div class="row">
                                                            <div class="col-md-8 col-sm-6">
                                                                <h4 class="widget-title lighter smaller menu_title">Dispatch Status Table
                                                                </h4>
                                                            </div>
                                                            <div class="col-md-4 col-sm-6">
                                                                <button class="btn waves-effect waves-light btn-success"
                                                                    onclick="showDispatchStatusModel()" style="float:right">
                                                                    <i class="mdi mdi-plus-circle" style="padding-right:3px;"></i>&nbsp;Add Status</button>
                                                            </div>
                                                        </div>
                                                        <br>
                                                        <span class="loader_lesson_plan_form"></span>
                                                    </div>
                                                    <div class="widget-body">
                                                        <div class="widget-main">
                                                            <div class="row">
                                                                <div class="col-sm-12">
                                                                    <table id="dispatch-status-list"
                                                                        class="display table-striped table-bordered dispatch-status-list"
                                                                        style="width:100%">
                                                                        <thead>
                                                                            <tr>
                                                                                <th>#</th>
                                                                                <th>Name</th>
                                                                                <th>Actions</th>
                                                                            </tr>
                                                                        </thead>
                                                                        <tbody>

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



                                <div class="tab-pane" id="settings1">

                                </div>
                            </div>

                        </div>
                    </div>

                        <!-- Dispatch Status Modal -->
    <div id="save-dispatch-status" class="modal fade" tabindex="-1" role="dialog" data-backdrop="static" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                    <div class="modal-header">                    
                        <h5 class="modal-title" id="dispatch_statush2" >Add Status</h5>
                      <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                <div class="modal-body">

                    <form class="widget-box widget-color-dark user-form" id="save_dispatch_status"
                        action="{{asset('save-dispatch-status')}}" method="post">
                        <div class="form-group">
                            <label for="departmrnt">Type Status</label>
                            <input class="form-control" type="text" name="name" id="dispatch_status_name" placeholder="">
                            <input class="form-control" type="text" name="dispatch_status_id" id="dispatch_status_id" hidden>
                        </div>
                        <div class="form-group text-end mt-2">
                            <button type="submit" class="btn btn-rounded btn-success">Save</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>