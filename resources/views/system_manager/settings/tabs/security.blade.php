<div class="card card-height">
    <div class="card-body">

        <h4 class="card-title mb-3">Security Settings</h4>

        <ul class="nav nav-pills bg-nav-pills nav-justified mb-3">
            <li class="nav-item">
                <a href="#security_set" data-bs-toggle="tab" aria-expanded="false" class="nav-link rounded-0 active">
                    <i class="mdi mdi-home-variant d-lg-none d-block mr-1"></i>
                    <span class="d-none d-lg-block">General Settings</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="#Firewall" data-bs-toggle="tab" aria-expanded="false" class="nav-link rounded-0">
                    <i class="mdi mdi-home-variant d-lg-none d-block mr-1"></i>
                    <span class="d-none d-lg-block"> Firewall </span>
                </a>
            </li>
            <li class="nav-item">
                <a href="#banneduser" data-bs-toggle="tab" aria-expanded="false" class="nav-link rounded-0">
                    <i class="mdi mdi-home-variant d-lg-none d-block mr-1"></i>
                    <span class="d-none d-lg-block"> Banned User </span>
                </a>
            </li>

        </ul>

        <div class="tab-content">
            <div class="tab-pane active" id="security_set">
                <form method="post">
                    <div class="row mt-4 mb-4">
                        <div class="col-md-12">
                            <h2>Password Enforcement</h2>
                            <hr>
                        </div>

                        <div class="col-md-6">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="same_pass" id="same_pass">
                                <label class="form-check-label" for="same_pass"> Can use same password </label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <p class="mute">This feature will allow/block user from repeating the use of the same
                                password.</p>
                        </div>
                        <div class="col-md-6">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="blank_spacer" id="blank_spacer">
                                <label class="form-check-label" for="blank_spacer"> Blank Spacer </label>
                            </div>
                        </div>
                        <div class="col-md-6"></div>
                        <div class="col-md-6">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="upperCase" id="upperCase">
                                <label class="form-check-label" for="upperCase"> Password must contain Uppercase Letter
                                </label>
                            </div>
                        </div>
                        <div class="col-md-6"></div>
                        <div class="col-md-6">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="passNumbers" id="passNumbers">
                                <label class="form-check-label" for="passNumbers"> Password must contain Numbers
                                </label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="row">
                                <div class="col-md-9 col-6">
                                    <p class="mute">Minimum Password length</p>
                                </div>
                                <div class="col-md-3 col-6 form-group my-1 ">
                                    <input class="form-control" type="number" placeholder="6" name="minLength"
                                        id="minLength">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="passSymbols" id="passSymbols">
                                <label class="form-check-label" for="passSymbols"> Password must contain Symbols
                                </label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <p class="mute">Staff password must be longer than this length</p>
                        </div>
                        <div class="col-md-6">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="blank_spacer2" id="blank_spacer">
                                <label class="form-check-label" for="blank_spacer2"> Blank Spacer </label>
                            </div>
                        </div>
                        <div class="col-md-6"></div>
                        <div class="col-md-6">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="passExp" id="passExp">
                                <label class="form-check-label" for="passExp"> Password expires after ____ </label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="row">
                                <div class="col-md-9 col-6">
                                    <p class="mute">Minimum number of digits</p>
                                </div>
                                <div class="col-md-3 col-6 form-group my-1 ">
                                    <input class="form-control" type="number" placeholder="6" name="minLength"
                                        id="minLength">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="passUsername" id="passUsername">
                                <label class="form-check-label" for="passUsername"> Password may not contain username
                                </label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <p class="mute">Staff password must include at least this number of digits</p>
                        </div>
                        <div class="col-md-6">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="passReset" id="passReset">
                                <label class="form-check-label" for="passReset"> Allow user to reset this own password
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="row mt-4 mb-4">
                        <div class="col-md-12">
                            <h2>Header Text</h2>
                            <hr>
                        </div>

                        <div class="col-md-10 mt-3">
                            <p class="mute mb-0"> Enable secure sessions</p>
                            <p class="mute"> This setting will prevent an attacker from capturing your staff user's
                                session data and hijacking their helpdesk session.</p>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group my-1 ">
                                <div class="row " style="padding-top:28px;">
                                    <div class="form-check mx-2">
                                        <input class="form-check-input" type="radio" name="flexRadioDefault"
                                            id="flexRadioDefault1" checked="">
                                        <label class="form-check-label" for="flexRadioDefault1"> Yes </label>
                                    </div>
                                    <div class="form-check mx-2">
                                        <input class="form-check-input " type="radio" name="flexRadioDefault"
                                            id="flexRadioDefault2">
                                        <label class="form-check-label" for="flexRadioDefault2"> No </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-10 ">
                            <p class="mute mb-0"> Prevent staff from logging in for a period of time after too many
                                logged in attempts</p>
                            <p class="mute"> Staff will be prevented from trying to login to the helpdesk if they enter
                                the wrong credentials too many time</p>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group my-1  ">
                                <div class="row " style="padding-top:28px;">
                                    <div class="form-check mx-2">
                                        <input class="form-check-input" type="radio" name="flexRadio" id="flexRadio1"
                                            checked="">
                                        <label class="form-check-label" for="flexRadio1"> Yes </label>
                                    </div>
                                    <div class="form-check mx-2">
                                        <input class="form-check-input" type="radio" name="flexRadio" id="flexRadio2">
                                        <label class="form-check-label" for="flexRadio2"> No </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="tab-pane show" id="Firewall">
                <div class="row">
                    <div class="col-md-12">
                        <div class="row">
                            <div class="col-md-12">
                                <p>Lorem ipsum dolor sit amet consect.</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-8">
                        <div class="row">
                            <div class="col-md-8">
                                <p>Lorem ipsum dolor sit amet consectetur adipisicing.
                                </p>
                            </div>
                            <div class="col-md-4 text-end">
                                <button class="btn btn-success " data-bs-toggle="modal" data-bs-target="#Add-IP">Add
                                    IP</button>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-8">
                        <div class="row">
                            <div class="col-md-12">
                                <h2>Whitelist your IPs</h2>
                                <div class="col-md-12 border-box">
                                    <div class="inner-data mt-2">
                                        <p>lorem ipsum true is one</p>
                                        <p>lorem ipsum true is one trus is two to fout</p>
                                        <p>lorem ipsum true is one</p>
                                    </div>

                                </div>

                            </div>
                            <div class="col-md-12 mt-3">
                                <h2>Blacklist your IPs</h2>
                                <div class="col-md-12 border-box">
                                    <div class="inner-data mt-2">
                                        <p>lorem ipsum true is one</p>
                                        <p>lorem ipsum true is one trus is two to fout</p>
                                        <p>lorem ipsum true is one</p>
                                    </div>

                                </div>

                            </div>
                        </div>

                    </div>
                    <div class="col-md-4 text-end">
                        <button class="btn btn-success mt-10"> Quick Add Whitelist </button>
                        <button class="btn btn-primary mt-2"> Quick Add Blacklist </button>
                        <button class="btn btn-info mt-2"> Quick Remove Whitelist </button>
                        <button class="btn btn-secondary mt-2"> Quick Remove Blacklist </button>
                        <p class="mt-3">Tell me something new to understand the matter today in this occasion of
                            prosperity</p>
                    </div>
                </div>
            </div>
            <div class="tab-pane" id="settings1">

            </div>
            <div class="tab-pane" id="banneduser">
                <div class="card mb-0">

                    <div class="card-body">
                        <div class="widget-header widget-header-small">
                            <div class="row">
                                <div class="col-md-8 col-sm-6">
                                    <h4 class="widget-title lighter smaller menu_title">Banned Users Table
                                    </h4>
                                </div>
                                <div class="col-md-4 col-sm-6">

                                    <button class="btn waves-effect waves-light btn-success rounded btn-sm"
                                        onclick="ShowBannedUserModel()" style="float:right">
                                        <i data-feather='user-plus'></i>&nbsp;Add User</button>

                                    <button class="btn waves-effect waves-light btn-danger rounded btn-sm" id="deleteBannedUser"
                                        onclick="deleteBannedUser()" disabled style="float:right;margin-right:10px">
                                        <i data-feather='trash-2' style="padding-right:3px;"></i>&nbsp;Delete
                                        User</button>

                                </div>
                            </div>
                            <br>
                        </div>
                        <div class="row">
                            <div class="col-sm-12">
                                <table id="banned-user-list"
                                    class="table display table-bordered table-hover banned-user-list"
                                    style="width:100%">
                                    <thead>
                                        <tr>
                                            <th>
                                                <input class="form-check-input check-all" type="checkbox" id="inlineCheckbox2" value="unchecked"></th>
                                            <th>E-mail</th>
                                            <th>Banned By</th>
                                            <th>Date</th>
                                            <th>Action</th>
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


<!--Security-->

<!-- Add IP Modal -->
<div id="Add-IP" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true" data-backdrop="static">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="typeh2">Add IP Address</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form class="widget-box widget-color-dark user-form" id="" action="">
                    <div class="form-group my-1 ">
                        <label for="">Ip Address Here</label>
                        <input type="ipv4" id="ipv4" class="form-control" name="" placeholder="" required="">
                    </div>
                    <div class="form-group my-1  text-end mt-2">
                        <button type="submit" class="btn btn-rounded btn-success">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>


<!--Security-->
<div id="banned-user" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true" data-backdrop="static">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="banned_user_title">Add User</h5>

                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form class="widget-box widget-color-dark user-form" id="save_banned_user"
                    action="{{asset('save-banned-user')}}" method="post">
                    <div class="form-group my-1">
                        <label for="departmrnt" class="d-flex mb-1 align-items-center">
                            E-mail
                        </label>
                        <input class="form-control mb-1" type="text" name="email" id="email" placeholder="" required>
                        <input type="hidden" name="edituser" id="edituser">
                    </div>
                    <div class="form-group my-1 text-end mt-2">
                        <button type="Submit" class="btn btn-rounded btn-success">Add</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>