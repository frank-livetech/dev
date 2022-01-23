<div class="card">
                        <div class="card-body">
                            <h4 class="card-title mb-3">Payroll Settings</h4>

                            <div class="row">
                                <form class="col-12" id="save_payroll_settings" action="{{asset('save_payroll_settings')}}" method="post">
                                    <div class="row mb-1">
                                        <div class="col-md-6">
                                            <div class="form-group my-1 ">
                                                <label for="security">General Note For Staff :</label>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group my-1 ">
                                                <input type="text" class="form-control" name="general_staff_note" value="{{$general_staff_note}}" required />
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row mb-1">
                                        <div class="col-md-6">
                                            <div class="form-group my-1 ">
                                                <label for="security">Note For Selected Staff :</label>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group my-1 ">
                                                <input type="text" class="form-control" name="note_for_selected_staff" value="{{$note_for_selected_staff}}" />
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row mb-1">
                                        <div class="col-md-6">
                                            <div class="form-group my-1 ">
                                                <label for="security">Selected Staff Members :</label>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group my-1 ">
                                                <select class="select2 form-control select2-hidden-accessible" id="selected_staff_members" style="width: 100%;" multiple>
                                                    @foreach ($staff_list as $user)
                                                        <option value="{{$user->id}}" {{in_array($user->id, $selected_staff_members) ? 'selected' : ''}}>{{$user->name}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row mt-2" >
                                        <div class="col-12 text-end">
                                            <button class="btn btn-primary" type="submit" onsubmit="return false;">Save</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    