<div class="card">
                        <div class="card-body">

                            <h4 class="card-title mb-3">Order Settings</h4>

                            <ul class="nav nav-pills bg-nav-pills nav-justified mb-2 mt-2">
                                <li class="nav-item">
                                    <a href="#billing_general" data-bs-toggle="tab" aria-expanded="false" class="nav-link active">
                                        <i class="mdi mdi-home-variant d-lg-none d-block mr-1"></i>
                                        <span class="d-none d-lg-block">General Settings</span>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="#billing_departments" data-bs-toggle="tab" aria-expanded="true" class="nav-link">
                                        <i class="mdi mdi-home-variant d-lg-none d-block mr-1"></i>
                                        <span class="d-none d-lg-block">Customizations</span>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="#billing_mails" data-bs-toggle="tab" aria-expanded="false" class="nav-link">
                                        <i class="mdi mdi-email d-lg-none d-block mr-1"></i>
                                        <span class="d-none d-lg-block ">Email Queues</span>
                                    </a>
                                </li>
                            </ul>

                            <div class="tab-content">
                                
                                <div class="tab-pane active" id="billing_general">
                                    <form class="widget-box widget-color-dark mt-2" id="save_order_format" action="{{asset('save_billing_orderid_format')}}" method="post">
                                        <div class="row mb-2">
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label for="security">Order ID Format :</label>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <select class="select2 form-control select2-hidden-accessible" style="width:100%;height:30px;" name="bill_order_id_frmt" required>
                                                        <option value="random">Random ( #108934 )</option>
                                                        <option value="sequential">Sequential (1#,2#,3#,...,#99999)</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row mb-2">
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label for="security">Currency Format :</label>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <select class="select2 form-control select2-hidden-accessible" style="width:100%;height:30px;" name="currency_format" required>
                                                        <option value="<i class='fas fa-dollar-sign'></i>">Dollar</option>
                                                        <option value="<i class='fas fa-pound-sign'></i>">Pound</option>
                                                        <option value="<i class='fas fa-euro-sign'></i>">Euro</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row mb-2">
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label for="security">Invoice # Format :</label>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <input type="text" class="form-control" name="order_invoice_format" required style="width:100%;height:30px;" value="X"/>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-12 text-end">
                                                <button class="btn btn-primary" type="submit" onsubmit="return false;">Save</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>

                                <div class="tab-pane show" id="billing_departments">
                                
                                </div>

                                <div class="tab-pane" id="billing_mails">
                                    <div class="card-body">
                                        <div class="widget-header widget-header-small">
                                            <div class="row">
                                                <div class="col-md-12 col-sm-6" style="text-align:right">
                                                    <button class="btn waves-effect waves-light btn-success"
                                                        onclick="showPop3Model('billing')"><i class="mdi mdi-plus-circle"></i>&nbsp;Add New
                                                        email</button>
                                                </div>
                                            </div>
                                            <br>
                                            <span class="loader_lesson_plan_form"></span>
                                        </div>
                                        <div class="widget-body">
                                            <div class="widget-main">
                                                <div class="row">
                                                    <div class="col-sm-12">
                                                        <table id="ticket-mails-list"
                                                            class="display table-striped table-bordered ticket-mails-list"
                                                            style="width:100%">
                                                            <thead>
                                                                <tr>
                                                                    <th>#</th>
                                                                    <th>Email Queue Address</th>
                                                                    <th>Type</th>
                                                                    <th>Department</th>
                                                                    <th>Registered</th>
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