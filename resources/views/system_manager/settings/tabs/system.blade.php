<div class="card card-height">
                        <div class="card-body">

                            <h4 class="card-title mb-3">System Settings</h4>

                            <ul class="nav nav-pills bg-nav-pills nav-justified mb-3">
                                <li class="nav-item">
                                    <a href="#home1" data-bs-toggle="tab" aria-expanded="false" class="nav-link rounded-0 active">
                                        <i class="mdi mdi-home-variant d-lg-none d-block mr-1"></i>
                                        <span class="d-none d-lg-block">General Settings</span>
                                    </a>
                                </li>

                            </ul>
                            <form id="saveRecord" onsubmit="return false">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group my-1 ">
                                            <label for="sys_dt_frmt">Date Format</label>
                                            <select name="sys_dt_frmt" id="sys_dt_frmt" class="form-control select2">
                                                
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group my-1 ">
                                            <label for="sys_time_frmt">Time Format</label>
                                            <select name="sys_time_frmt" id="sys_time_frmt" class="form-control select2"></select>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group my-1 ">
                                            <label for="timezone">Default TimeZone</label> <br>
                                            <select name="timezone" id="timezone" class="select2 form-control select2-hidden-accessible" style="width:100%;">
                                                @foreach(timezone_identifiers_list() as $timezone)
                                                    <option value="{{$timezone}}" {{$timeZone == $timezone ? 'selected' : ''}}>{{$timezone}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12 text-end mt-2">
                                        <button type="submit" id="saveBtn" onclick="saveSystemDateAndTime()" class="btn btn-success">Save</button>
                                        <button style="display:none" id="processing" class="btn btn-success" type="button" disabled><i class="fas fa-circle-notch fa-spin"></i> Processing</button>
                                    </div>
                                </div>
                            </form>



                        </div>
                    </div>