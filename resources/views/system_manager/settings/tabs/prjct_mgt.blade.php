<div class="card card-height">
                        <div class="card-body">

                            <h4 class="card-title mb-3">Customer Settings</h4>

                            <ul class="nav nav-pills bg-nav-pills nav-justified mb-3">
                                <li class="nav-item">
                                    <a href="#genSettings" data-bs-toggle="tab" aria-expanded="false" class="nav-link rounded-0 active">
                                        <i class="mdi mdi-home-variant d-lg-none d-block mr-1"></i>
                                        <span class="d-none d-lg-block">General Settings</span>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="#project_type" data-bs-toggle="tab" aria-expanded="false" class="nav-link rounded-0 ">
                                        <i class="mdi mdi-home-variant d-lg-none d-block mr-1"></i>
                                        <span class="d-none d-lg-block">Customization</span>
                                    </a>
                                </li>

                                <li class="nav-item">
                                    <a href="#notificationType" data-bs-toggle="tab" aria-expanded="false" class="nav-link rounded-0 ">
                                        <i class="mdi mdi-home-variant d-lg-none d-block mr-1"></i>
                                        <span class="d-none d-lg-block">Notification</span>
                                    </a>
                                </li>
                                

                            </ul>

                            <div class="tab-content">

                                <div class="tab-pane active" id="genSettings">

                                        <!-- <div class="row">
                                            <div class="col-md-12">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox"  id="dailyRecap">
                                                    <label class="form-check-label bold" for="dailyRecap">Daily progress recap on email </label>
                                                </div>
                                            </div>
                                            <div class="col-md-12 p-3 pl-4 dailyRecap" >
                                                <div class="form-group my-1 ">
                                                    <textarea class="form-control" rows="3" id=""
                                                        name="" placeholder=" This is the true paragraph given by true person"></textarea>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox"  id="weeklyRecap">
                                                    <label class="form-check-label bold" for="weeklyRecap">Weekly progress recap on email </label>
                                                </div>
                                            </div>
                                            <div class="col-md-12 p-3 pl-4 weeklyRecap">
                                                <div class="form-group my-1 ">
                                                    <textarea class="form-control" rows="3" id=""
                                                        name="" placeholder=" This is the true paragraph given by true person"></textarea>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox"  id="monthlyRecap">
                                                    <label class="form-check-label bold" for="monthlyRecap">Monthly progress recap on email </label>
                                                </div>
                                            </div>
                                            <div class="col-md-12 p-3 pl-4 monthlyRecap">
                                                <div class="form-group my-1 ">
                                                    <textarea class="form-control" rows="3" id=""
                                                        name="" placeholder=" This is the true paragraph given by true person"></textarea>
                                                </div>
                                            </div>
                                        </div> -->
                                    
                                </div>

                                <div class="tab-pane show" id="project_type">
                                    <div id="accordion" class="accordion accordion-border">
                                        <div class="card mb-0">
                                            <div class="" id="project_type_collapse">
                                                <h5 class="m-0">
                                                    <a class="accordion-button d-flex align-items-center pt-2 pb-2 collapsed"
                                                        data-bs-toggle="collapse" href="#collapseTypeProject" aria-expanded="false"
                                                        aria-controls="collapseThree">
                                                        Task Type <span class="ml-auto"><i
                                                                class="mdi mdi-chevron-down accordion-arrow"></i></span>
                                                    </a>
                                                </h5>
                                            </div>
                                            <div id="collapseTypeProject" class="collapse" aria-labelledby="project_task_type_collapse"
                                                data-parent="#accordion" style="">
                                                <div class="card-body">
                                                    <div class="widget-header widget-header-small">
                                                        <div class="row">
                                                            <div class="col-md-8 col-sm-6">
                                                                <h4 class="widget-title lighter smaller menu_title">Project Task Type Table
                                                                </h4>
                                                            </div>
                                                            <div class="col-md-4 col-sm-6">
                                                                <button class="btn waves-effect waves-light btn-success"
                                                                    onclick="showProjectTypeModel()" style="float:right">
                                                                    <i class="mdi mdi-plus-circle" style="padding-right:3px;"></i>&nbsp;Add Task Type</button>
                                                            </div>
                                                        </div>
                                                        <br>
                                                        <span class="loader_lesson_plan_form"></span>
                                                    </div>
                                                    <div class="widget-body">
                                                        <div class="widget-main">
                                                            <div class="row">
                                                                <div class="col-sm-12">
                                                                    <table id="project-type-list"
                                                                        class="display table-striped table-bordered project-type-list"
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

                                <div class="tab-pane" id="notificationType">
                                    <div class="row">
                                        <form id="email_recap_notification_form" method="post" action="{{url('save_email_recap_noti')}}">
                                            <div class="col-md-12">
                                                <div class="row">
                                                    <div class="col-md-6 ">
                                                        <p>Allow Email Recap Notifications?</p>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group my-1 ">
                                                            <div class="row">
                                                                <div class="form-check mx-2">
                                                                
                                                                    <input class="form-check-input" type="radio" name="recapNoti" id="recapNoti1" checked="">
                                                                
                                                                    <label class="form-check-label" for="recapNoti1"> Yes </label>
                                                                </div>
                                                                <div class="form-check mx-2">
                                                                    @if($sys_setting != null && $sys_setting[0] != null)
                                                                        <input class="form-check-input " type="radio" name="recapNoti" id="recapNoti2" {{$sys_setting[0]['sys_value'] == 'no' ? 'checked' : ' '}}>
                                                                        <label class="form-check-label" for="recapNoti2"> No </label>
                                                                        @endif
                                                                   
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                
                                            </div>

                                            @if($sys_setting != null && $sys_setting[0] != null)
                                                @if($sys_setting[0]['sys_value'] == 'yes')
                                                    <div class="col-md-12 recapNotiDiv">
                                                        <div class="row">
                                                            <div class="col-md-6">
                                                                <p>Post the email you prefer all reports go to. Example systemreport@mycompanyname.com </p>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <div class="form-group my-1 ">
                                                                    <input type="text" id="tag_emails" value="{{$sys_setting[1]['sys_value']}}" class="form-control" placeholder="Email"  data-role="tagsinput">
                                                                    <small class="badge badge-light-primary">Note</small><small style="padding-left: 6px;padding-top: 3px;">Press Enter for next email</small>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="row">
                                                            <div class="col-md-6">
                                                                <p>Check off the Emails you with to recieve</p>
                                                            </div>

                                                            @php 
                                                                $check_off_emails = json_encode($sys_setting[2]['sys_value']);
                                                            @endphp
                                                            <div class="col-md-6">
                                                                <div class="form-check form-check-inline">
                                                                    <input class="form-check-input" {{strpos($check_off_emails ,'daily') ? 'checked' : ''}} type="checkbox" id="dailyDetails">
                                                                    <label class="form-check-label bold" for="dailyDetails">Daily</label>
                                                                </div>
                                                                <div class="form-check form-check-inline">
                                                                    <input class="form-check-input" {{strpos($check_off_emails ,'weekly') ? 'checked' : ''}} type="checkbox" id="weeklyDetails">
                                                                    <label class="form-check-label bold" for="weeklyDetails">Weekly</label>
                                                                </div>
                                                                <div class="form-check form-check-inline">
                                                                    <input class="form-check-input" {{strpos($check_off_emails ,'monthly') ? 'checked' : ''}} type="checkbox" id="monthlyDetails">
                                                                    <label class="form-check-label bold" for="monthlyDetails">Monthly</label>
                                                                </div>
                                                                <div class="form-check form-check-inline">
                                                                    <input class="form-check-input" {{strpos($check_off_emails ,'yearly') ? 'checked' : ''}} type="checkbox" id="yearlyDetails">
                                                                    <label class="form-check-label bold" for="yearlyDetails">Yearly</label>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @else
                                                    <div class="col-md-12 recapNotiDiv" style="display:none">
                                                        <div class="row">
                                                            <div class="col-md-6">
                                                                <p>Post the email you prefer all reports go to. Example systemreport@mycompanyname.com </p>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <div class="form-group my-1 ">
                                                                    <input type="text" id="tag_emails" class="form-control" placeholder="Email"  data-role="tagsinput">
                                                                    <small class="badge badge-light-primary">Note</small><small style="padding-left: 6px;padding-top: 3px;">Press Enter for next email</small>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="row">
                                                            <div class="col-md-6">
                                                                <p>Check off the Emails you with to recieve</p>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <div class="form-check form-check-inline">
                                                                    <input class="form-check-input" type="checkbox" id="dailyDetails">
                                                                    <label class="form-check-label bold" for="dailyDetails">Daily</label>
                                                                </div>
                                                                <div class="form-check form-check-inline">
                                                                    <input class="form-check-input" type="checkbox" id="weeklyDetails">
                                                                    <label class="form-check-label bold" for="weeklyDetails">Weekly</label>
                                                                </div>
                                                                <div class="form-check form-check-inline">
                                                                    <input class="form-check-input" type="checkbox" id="monthlyDetails">
                                                                    <label class="form-check-label bold" for="monthlyDetails">Monthly</label>
                                                                </div>
                                                                <div class="form-check form-check-inline">
                                                                    <input class="form-check-input" type="checkbox" id="yearlyDetails">
                                                                    <label class="form-check-label bold" for="yearlyDetails">Yearly</label>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endif
                                            @endif
                                            <div class="row">
                                                <div class="col-md-12-text-end mt-1">
                                                    <button class="btn btn-success btn-sm rounded ml-2"> <i class="fas fa-check-circle"></i> Save</button>
                                                </div>
                                            </div>

                                        </form>

                                        <div class="col-md-12 mt-3">
                                            <form action="">
                                                <p>On Demand Recap</p>

                                                <div class="row">
                                                    <div class="col-md-5">
                                                        <select class="form-control" id="recap_dropdown">
                                                            <option>Choose Your Option</option>
                                                            <option value="daily">Daily</option>
                                                            <option value="weekly">Weekly</option>
                                                            <option value="monthly">Monthly </option>
                                                            <option value="yearly">Yearly</option>
                                                        </select>
                                                    </div>
                                                    <div class="col-md-5">
                                                        <div class="form-group my-1 ">
                                                            <input class="form-control" id="recap_emails" type="email"  data-role="tagsinput"  placeholder="Emails">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-2">
                                                        <button class="btn btn-success" onclick="sendOnDemandRecap()" type="button">Send</button>
                                                    </div>
                                                </div>
                                            </form>

                                            <p>your Next Recap will sent on < DATESTAMP > </p>
                                            <p>Having issue with your browser/computer displaying onscreen notification?</p>
                                            <p class="text-end"><a href="#" onclick="showNotificationPopup()">Click Here to request again!</a></p>
                                        </div>
                                        
                                    </div>

                                    <hr>
                                    <div class="noti_email">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox"  id="dailyRecap">
                                                    <label class="form-check-label bold" for="dailyRecap">Daily progress recap on email </label>
                                                </div>
                                            </div>
                                            <div class="col-md-12 p-3 pl-4 dailyRecap" >
                                                <div class="form-group my-1 ">
                                                    <textarea class="form-control" rows="3" id=""
                                                        name="" placeholder=" This is the true paragraph given by true person"></textarea>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox"  id="weeklyRecap">
                                                    <label class="form-check-label bold" for="weeklyRecap">Weekly progress recap on email </label>
                                                </div>
                                            </div>
                                            <div class="col-md-12 p-3 pl-4 weeklyRecap">
                                                <div class="form-group my-1 ">
                                                    <textarea class="form-control" rows="3" id=""
                                                        name="" placeholder=" This is the true paragraph given by true person"></textarea>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox"  id="monthlyRecap">
                                                    <label class="form-check-label bold" for="monthlyRecap">Monthly progress recap on email </label>
                                                </div>
                                            </div>
                                            <div class="col-md-12 p-3 pl-4 monthlyRecap">
                                                <div class="form-group my-1 ">
                                                    <textarea class="form-control" rows="3" id=""
                                                        name="" placeholder=" This is the true paragraph given by true person"></textarea>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>

                    

    <!-- Project Type Modal -->
    <div id="save-project-type" class="modal fade" tabindex="-1" role="dialog" data-backdrop="static" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                    <div class="modal-header">                    
                        <h5 class="modal-title" id="project_typeh2" >Add Project Task Type</h5>
                      <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                <div class="modal-body">

                    <form class="widget-box widget-color-dark user-form" id="save_project_type"
                        action="{{asset('save-project-type')}}" method="post">
                        <div class="form-group my-1 ">
                            <label for="departmrnt">Type Task Type</label>
                            <input class="form-control" type="text" name="name" id="project_type_name" placeholder="">
                            <input class="form-control" type="text" name="project_type_id" id="project_type_id" hidden>
                        </div>
                        <div class="form-group my-1  text-end mt-2">
                            <button type="submit" class="btn btn-rounded btn-success">Save</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>