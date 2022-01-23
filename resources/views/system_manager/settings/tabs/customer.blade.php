<div class="card">
                        <div class="card-body">

                            <h4 class="card-title mb-3">Customer Settings</h4>

                            <ul class="nav nav-pills bg-nav-pills nav-justified mb-3">
                                <li class="nav-item">
                                    <a href="#homeCustomer" data-bs-toggle="tab" aria-expanded="false" class="nav-link rounded-0 active">
                                        <i class="mdi mdi-home-variant d-lg-none d-block mr-1"></i>
                                        <span class="d-none d-lg-block">General Settings</span>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="#type_customer" data-bs-toggle="tab" aria-expanded="false" class="nav-link rounded-0 ">
                                        <i class="mdi mdi-home-variant d-lg-none d-block mr-1"></i>
                                        <span class="d-none d-lg-block">Customization</span>
                                    </a>
                                </li>
                                

                            </ul>

                            <div class="tab-content">
                                <div class="tab-pane active" id="homeCustomer">
                                    <form action="{{url('customer_setting')}}" method="POST" id="customer_setting_form" enctype="multipart/form-data">
                                        <div class="row mt-5">
                                            <div class="col-md-6">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" name="delete" id="delete">
                                                    <label class="form-check-label" for="delete"> Allow customer to delete their own account </label>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" name="disable" id="disable">
                                                    <label class="form-check-label" for="disable">  Allow customer to disable their own account </label>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" name="create" id="create">
                                                    <label class="form-check-label" for="create">  Allow customer to create a new account </label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            
                                            <div class="col-md-6">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" name="customer_login" id="customer_login">
                                                    <label class="form-check-label" for="customer_login">  Send welcome email to customers having login accounts </label>
                                                </div>
                                            </div>
                                            
                                            <div class="col-md-6">
                                                <label for="">From Email Address </label>
                                                <input type="email" class="form-control" id="accounts_from_email" name="accounts_from_email" placeholder="From Email">
                                            </div>

                                        </div>
                                        <button type="submit" id="save_btn" class="btn btn-success">Save</button>
                                    </form>
                                </div>
                                <div class="tab-pane show" id="type_customer">
                                    <div id="accordion" class="accordion accordion-border">

                                        <div class="card mb-0">
                                            <div class="" id="customer_type_collapse">
                                                <h5 class="m-0">
                                                    <a class="accordion-button d-flex align-items-center pt-2 pb-2 collapsed"
                                                        data-bs-toggle="collapse" href="#collapseTypecustomer" aria-expanded="false"
                                                        aria-controls="collapseThree">
                                                        Customer Types <span class="ml-auto"><i
                                                                class="mdi mdi-chevron-down accordion-arrow"></i></span>
                                                    </a>
                                                </h5>
                                            </div>
                                            <div id="collapseTypecustomer" class="collapse" aria-labelledby="customer_type_collapse"
                                                data-parent="#accordion" style="">
                                                <div class="card-body">
                                                    <div class="widget-header widget-header-small">
                                                        <div class="row">
                                                            <div class="col-md-8 col-sm-6">
                                                                <h4 class="widget-title lighter smaller menu_title">Customer Type Table
                                                                </h4>
                                                            </div>
                                                            <div class="col-md-4 col-sm-6">
                                                                <button class="btn waves-effect waves-light btn-success"
                                                                    onclick="showCustomerTypeModel()" style="float:right">
                                                                    <i class="mdi mdi-plus-circle" style="padding-right:3px;"></i>&nbsp;Add Type</button>
                                                            </div>
                                                        </div>
                                                        <br>
                                                        <span class="loader_lesson_plan_form"></span>
                                                    </div>
                                                    <div class="widget-body">
                                                        <div class="widget-main">
                                                            <div class="row">
                                                                <div class="col-sm-12">
                                                                    <table id="customer-type-list"
                                                                        class="display table-striped table-bordered customer-type-list"
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

                    
<!-- Customer Type Modal -->
    <div id="save-customer-type" class="modal fade" tabindex="-1" role="dialog" data-backdrop="static" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                    <div class="modal-header">                    
                        <h5 class="modal-title" id="customer_typeh2"  >Add Type</h5>
                      <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                <div class="modal-body">

                    <form class="widget-box widget-color-dark user-form" id="save_customer_ticket"
                        action="{{asset('save-customer-type')}}" method="post">
                        <div class="form-group my-1">
                            <label for="departmrnt">Type Title</label>
                            <input class="form-control" type="text" name="name" id="customer_type_name" placeholder="">
                            <input class="form-control" type="text" name="customer_type_id" id="customer_type_id" hidden>
                        </div>
                        <div class="form-group text-end mt-2">
                            <button type="submit" class="btn btn-rounded btn-success">Save</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
<!--Customer Modals-->
