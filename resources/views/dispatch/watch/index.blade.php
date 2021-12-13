@extends('layouts.staff-master-layout')
@section('body-content')
 <!-- This page CSS -->

<style>
#file_export_length label,
#file_export_filter label{
    display:inline-flex;
}
.pac-container {
    z-index: 10000 !important;
}
.list-group-item {
    position: relative;
    display: block;
    padding: .75rem 1.25rem;
    background-color: #fff;
     border: 1px solid transparent !important; 
}
.gmaps, .gmaps-panaroma {
    height: 200px !important;
    background: #e9ecef;
    width:100% !important;
    border-radius: 4px;
}
.p-small p{
 margin-bottom:0;
 font-size:12px;
}
.p-large p{
 margin-bottom:0;
}
</style>
<div class="page-breadcrumb">
    <div class="row">
        <div class="col-md-5 align-self-center">

            <div class="d-flex align-items-center">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{route('home')}}">Home</a></li>
                        <li class="breadcrumb-item" aria-current="page">Dispatch</li>
                        <li class="breadcrumb-item active" aria-current="page">Watch</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
</div>
<div class="container-fluid">
    
    <div class="row">
       
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <!-- <h4 class="card-title"></h4> -->
                   <div id="mapTech1" class="gmaps"></div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-body">

                    <h1 class="card-title mb-3"> Tabs</h1>

                    <ul class="nav nav-tabs mb-3">
                        <li class="nav-item">
                            <a href="#home" data-toggle="tab" aria-expanded="false" class="nav-link">
                                <i class="mdi mdi-home-variant d-lg-none d-block mr-1"></i>
                                <span class="d-none d-lg-block">Active Jobs</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="#profile" data-toggle="tab" aria-expanded="true" class="nav-link active">
                                <i class="mdi mdi-account-circle d-lg-none d-block mr-1"></i>
                                <span class="d-none d-lg-block">Closed Jobs</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="#settings" data-toggle="tab" aria-expanded="false" class="nav-link">
                                <i class="mdi mdi-settings-outline d-lg-none d-block mr-1"></i>
                                <span class="d-none d-lg-block">Pending Jobs</span>
                            </a>
                        </li>
                    </ul>

                    <div class="tab-content">
                        <div class="tab-pane" id="home">
                            <div class="col-12">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="table-responsive">
                                            <div id="file_export_wrapper" class="dataTables_wrapper container-fluid dt-bootstrap4">
                                                
                                                     <div id="file_export_filter" class="dataTables_filter"><label>Search:<input type="search" class="form-control form-control-sm" placeholder="" aria-controls="file_export"></label></div><table id="file_export" class="table table-striped table-bordered display no-wrap dataTable" role="grid" aria-describedby="file_export_info">
                                                
                                            <thead>
                                                    <tr role="row">
                                                        <th class="sorting_asc" tabindex="0" aria-controls="file_export" rowspan="1" colspan="1" aria-sort="ascending" aria-label="Name: activate to sort column descending" style="width: 0px;">ID</th>
                                                        <th class="sorting" tabindex="0" aria-controls="file_export" rowspan="1" colspan="1" aria-label="Position: activate to sort column ascending" style="width: 0px;">Field Agent</th>
                                                        <th class="sorting" tabindex="0" aria-controls="file_export" rowspan="1" colspan="1" aria-label="Office: activate to sort column ascending" style="width: 0px;">Phone</th>
                                                        <th class="sorting" tabindex="0" aria-controls="file_export" rowspan="1" colspan="1" aria-label="Age: activate to sort column ascending" style="width: 0px;">Status</th>
                                                    </tr>
                                                </thead>
                                                <tbody> 
                                                    <tr role="row" class="odd">
                                                        <td class="sorting_1">01</td>
                                                        <td>Slaye </td>
                                                        <td>92365 236</td>
                                                        <td>Onsite</td>
                                                    </tr>
                                                    <tr role="row" class="even">
                                                        <td class="sorting_1">02 </td>
                                                        <td>Misculin </td>
                                                        <td>9856 4521 4</td>
                                                        <td>On Break</td>
                                                    </tr><tr role="row" class="odd">
                                                        <td class="sorting_1">03 </td>
                                                        <td>Oliver </td>
                                                        <td>789 654 123</td>
                                                        <td>Off Duty</td>
                                                    </tr><tr role="row" class="even">
                                                        <td class="sorting_1">04 </td>
                                                        <td>Donna </td>
                                                        <td>4265 1254 12</td>
                                                        <td>At Depot</td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                            <div class="dataTables_info" id="file_export_info" role="status" aria-live="polite">Showing 11 to 20 of 57 entries</div><div class="dataTables_paginate paging_simple_numbers" id="file_export_paginate"><ul class="pagination"><li class="paginate_button page-item previous" id="file_export_previous"><a href="#" aria-controls="file_export" data-dt-idx="0" tabindex="0" class="page-link">Previous</a></li><li class="paginate_button page-item active"><a href="#" aria-controls="file_export" data-dt-idx="1" tabindex="0" class="page-link">1</a></li><li class="paginate_button page-item next" id="file_export_next"><a href="#" aria-controls="file_export" data-dt-idx="7" tabindex="0" class="page-link">Next</a></li></ul></div></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane show active" id="profile">
                            <div class="col-12">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="table-responsive">
                                            <div id="file_export_wrapper" class="dataTables_wrapper container-fluid dt-bootstrap4"><div id="file_export_filter" class="dataTables_filter"><label>Search:<input type="search" class="form-control form-control-sm" placeholder="" aria-controls="file_export"></label></div><table id="file_export" class="table table-striped table-bordered display no-wrap dataTable" role="grid" aria-describedby="file_export_info">
                                                
                                            <thead>
                                                    <tr role="row">
                                                        <th class="sorting_asc" tabindex="0" aria-controls="file_export" rowspan="1" colspan="1" aria-sort="ascending" aria-label="Name: activate to sort column descending" style="width: 0px;">ID</th>
                                                        <th class="sorting" tabindex="0" aria-controls="file_export" rowspan="1" colspan="1" aria-label="Position: activate to sort column ascending" style="width: 0px;">Field Agent</th>
                                                        <th class="sorting" tabindex="0" aria-controls="file_export" rowspan="1" colspan="1" aria-label="Office: activate to sort column ascending" style="width: 0px;">Phone</th>
                                                        <th class="sorting" tabindex="0" aria-controls="file_export" rowspan="1" colspan="1" aria-label="Age: activate to sort column ascending" style="width: 0px;">Status</th>
                                                    </tr>
                                                </thead>
                                                <tbody> 
                                                    <tr role="row" class="odd">
                                                        <td class="sorting_1">01</td>
                                                        <td>Slaye </td>
                                                        <td>92365 236</td>
                                                        <td>Onsite</td>
                                                    </tr>
                                                    <tr role="row" class="even">
                                                        <td class="sorting_1">02 </td>
                                                        <td>Misculin </td>
                                                        <td>9856 4521 4</td>
                                                        <td>On Break</td>
                                                    </tr><tr role="row" class="odd">
                                                        <td class="sorting_1">03 </td>
                                                        <td>Oliver </td>
                                                        <td>789 654 123</td>
                                                        <td>Off Duty</td>
                                                    </tr><tr role="row" class="even">
                                                        <td class="sorting_1">04 </td>
                                                        <td>Donna </td>
                                                        <td>4265 1254 12</td>
                                                        <td>At Depot</td>
                                                    </tr>
                                                </tbody>
                                            </table><div class="dataTables_info" id="file_export_info" role="status" aria-live="polite">Showing 11 to 20 of 57 entries</div><div class="dataTables_paginate paging_simple_numbers" id="file_export_paginate"><ul class="pagination"><li class="paginate_button page-item previous" id="file_export_previous"><a href="#" aria-controls="file_export" data-dt-idx="0" tabindex="0" class="page-link">Previous</a></li><li class="paginate_button page-item active"><a href="#" aria-controls="file_export" data-dt-idx="1" tabindex="0" class="page-link">1</a></li><li class="paginate_button page-item next" id="file_export_next"><a href="#" aria-controls="file_export" data-dt-idx="7" tabindex="0" class="page-link">Next</a></li></ul></div></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane" id="settings">
                            <div class="col-12">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="table-responsive">
                                            <div id="file_export_wrapper" class="dataTables_wrapper container-fluid dt-bootstrap4"><div id="file_export_filter" class="dataTables_filter"><label>Search:<input type="search" class="form-control form-control-sm" placeholder="" aria-controls="file_export"></label></div><table id="file_export" class="table table-striped table-bordered display no-wrap dataTable" role="grid" aria-describedby="file_export_info">
                                                
                                            <thead>
                                                    <tr role="row">
                                                        <th class="sorting_asc" tabindex="0" aria-controls="file_export" rowspan="1" colspan="1" aria-sort="ascending" aria-label="Name: activate to sort column descending" style="width: 0px;">ID</th>
                                                        <th class="sorting" tabindex="0" aria-controls="file_export" rowspan="1" colspan="1" aria-label="Position: activate to sort column ascending" style="width: 0px;">Field Agent</th>
                                                        <th class="sorting" tabindex="0" aria-controls="file_export" rowspan="1" colspan="1" aria-label="Office: activate to sort column ascending" style="width: 0px;">Phone</th>
                                                        <th class="sorting" tabindex="0" aria-controls="file_export" rowspan="1" colspan="1" aria-label="Age: activate to sort column ascending" style="width: 0px;">Status</th>
                                                    </tr>
                                                </thead>
                                                <tbody> 
                                                    <tr role="row" class="odd">
                                                        <td class="sorting_1">01</td>
                                                        <td>Slaye </td>
                                                        <td>92365 236</td>
                                                        <td>Onsite</td>
                                                    </tr>
                                                    <tr role="row" class="even">
                                                        <td class="sorting_1">02 </td>
                                                        <td>Misculin </td>
                                                        <td>9856 4521 4</td>
                                                        <td>On Break</td>
                                                    </tr><tr role="row" class="odd">
                                                        <td class="sorting_1">03 </td>
                                                        <td>Oliver </td>
                                                        <td>789 654 123</td>
                                                        <td>Off Duty</td>
                                                    </tr><tr role="row" class="even">
                                                        <td class="sorting_1">04 </td>
                                                        <td>Donna </td>
                                                        <td>4265 1254 12</td>
                                                        <td>At Depot</td>
                                                    </tr>
                                                </tbody>
                                            </table><div class="dataTables_info" id="file_export_info" role="status" aria-live="polite">Showing 11 to 20 of 57 entries</div><div class="dataTables_paginate paging_simple_numbers" id="file_export_paginate"><ul class="pagination"><li class="paginate_button page-item previous" id="file_export_previous"><a href="#" aria-controls="file_export" data-dt-idx="0" tabindex="0" class="page-link">Previous</a></li><li class="paginate_button page-item active"><a href="#" aria-controls="file_export" data-dt-idx="1" tabindex="0" class="page-link">1</a></li><li class="paginate_button page-item next" id="file_export_next"><a href="#" aria-controls="file_export" data-dt-idx="7" tabindex="0" class="page-link">Next</a></li></ul></div></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div> <!-- end card-body-->
            </div> <!-- end card-->
        </div> <!-- end col -->
        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <h1>Actions</h1>
                    <div class="pt-2">
                        <button type="button" class="btn btn-success"  data-toggle="modal" data-target="#startDispatch">Start Dispatch</button>
                    </div>
                    <div class="pt-2">
                        <button type="button" class="btn btn-warning">Message Field Agent</button>
                    </div>
                    <div class="pt-2">
                        <button type="button" class="btn btn-primary">Test</button>
                        <button type="button" class="btn btn-info">Adjust Now</button>
                    </div>
                    <div class="pt-2">
                        <button type="button" class="btn btn-danger">pause</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>

<!-- Modal -->
<div class="modal fade" id="startDispatch"  data-backdrop="static" tabindex="-1" aria-labelledby="startDispatchLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content ">
      <div class="modal-header">
        <h5 class="modal-title" id="startDispatchLabel">Start Dispatch </h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
      <div class="row">
                   
                   <!-- ============================================================== -->
                   <!-- Example -->
                   <!-- ============================================================== -->
                   <div class="col-12">
                       <div class="">
                           <div class="card-body wizard-content">
                               <form id="watch_form" class="tab-wizard1 mine vertical wizard-circle mt-5" method="POST" action="{{url('save-watch')}}">
                                   <!-- Step 1 -->
                                   @csrf
                                   <h6>Stage 1</h6>
                                   <section>
                                       <div class="row">
                                           <div class="col-md-12">
                                               
                                               <div class="form-group">
                                               <label for="staffSelect">Select Staff</label>
                                                   <select class="custom-select form-control" id="staffSelect" name="staff_id">
                                                   <option>Select</option>
                                                        @foreach($users as $user)
                                                            <option  value="{{$user->id}}">{{$user->name}}</option>     
                                                        @endforeach
                                                   </select>
                                               </div>
                                           </div>
                                       </div>
                                       <div class="row">
                                           <div class="col-md-12 mt-2">
                                                <div class="row">
                                                    <div class="custom-control custom-radio ml-2">
                                                        <input type="radio" class="custom-control-input" id="customControlValidation2" name="radio-stacked" value="customer">
                                                        <label class="custom-control-label" for="customControlValidation2">Select Customer</label>
                                                    </div>
                                                    <div class="custom-control custom-radio mb-3 ml-2">
                                                        <input type="radio" class="custom-control-input" id="customControlValidation3" name="radio-stacked" value="ticket">
                                                        <label class="custom-control-label" for="customControlValidation3">Select Ticket</label>
                                                    </div>
                                                </div>
                                            </div>
                                       </div>
                                       <div class="row" id="selectTicket" style="display:none">
                                            <div class="col-md-12">
                                               <label for=""> Select Ticket </label>
                                               <select class="form-control" style="width: 100%; height:36px;" onchange="Ticket(this);"  id="ticket_id" name="ticket_id">
                                                   <option value=''>Select</option>
                                                    @foreach($tickets as $ticket)
                                                            <option data-address="{{$ticket->address}}" value="{{$ticket->id}}">{{$ticket->coustom_id}}</option>     
                                                    @endforeach
                                               </select>
                                           </div>
                                       </div>
                                       <div class="row mt-2"  id="selectCustomer" style="display:none">
                                            <div class="col-md-12">
                                               <label for=""> Select Customer </label>
                                               <select class="form-control" style="width: 100%; height:36px;" onchange="Customer(this);" id="customer_id" name="customer_id">
                                                   <option>Select</option>
                                                    @foreach($customers as $customer)
                                                            <option data-address="{{$customer->address}}" value="{{$customer->id}}">{{$customer->first_name}} {{$customer->last_name}}</option>     
                                                    @endforeach
                                               </select>
                                           </div>
                                       </div>
                                       <div class="row mt-3">
                                           <div class="col-md-12">
                                               <div class="form-group">
                                                   <label for="date1">Prefered Date</label>
                                                   <input type="date" class="form-control" id="date1" name="pref_date"> </div>
                                           </div>
                                       </div>
                                   </section>
                                   <!-- Step 2 -->
                                   <h6>Stage 2</h6>
                                   <section>
                                      <div class="row">
                                          <div class="col-md-12">
                                                <h3>Confirm Dispatch Address</h3>
                                          </div>
                                            <div class="col-md-6">
                                               <div class="form-group">
                                                   <label for="shortDescription1">Location Title</label>
                                                   <input name="address" id="shortDescription1"  class="form-control">
                                               </div>
                                           </div>
                                           <div class="col-md-6">
                                               <div class="form-group">
                                                   <label for="shortDescription1">Choose Different Address</label>
                                                   <select class="custom-select form-control" id="diff_address" name="diff_address">
                                                        <option>Select</option>
                                                        <option>Select 1</option>
                                                        <option>Select 2</option>
                                                        <option>Select 3</option>
                                                        <option>Select 4</option>

                                                   </select>
                                               </div>
                                           </div>
                                           <div class="col-md-12">
                                                <div class="form-group">
                                                    <label for="street_address">Street Address</label>
                                                    <input type="text" name="street_address" class="form-control" value="" id="street_address">

                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label for="city">Apartment,suit,unit etc(Optional)</label>
                                                    <input type="text" name="apartment" class="form-control" value="" id="apartment">

                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label for="city">City</label>
                                                    <input type="text" name="city" class="form-control" value="" id="city">
                                                </div>
                                              </div>
                                              <div class="col-md-3">
                                                <div class="form-group">
                                                    <label for="state">State</label>
                                                    <input type="text" name="state" class="form-control" value="" id="state">
                                                </div>
                                              </div>
                                              <div class="col-md-3">
                                                <div class="form-group">
                                                    <label for="zip">Zip</label>
                                                    <input type="text" name="zip" class="form-control" value="" id="zip">
                                                </div>
                                              </div>
                                              <div class="col-md-3">
                                                <div class="form-group">
                                                    <label for="country">Country</label>
                                                    <input type="text" name="country" class="form-control" value="" id="country">
                                                </div>
                                              </div>
                                              <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="phone">Phone Number</label>
                                                        <!-- <input name="address" id="shortDescription1"  class="form-control"> -->
                                                        <input type="text" name="phone" class="form-control" value="" id="phone">
                                                    </div>
                                              </div>
                                              <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="phone">Secondary Number (Optional)</label>
                                                    <input type="text" name="phone_extra" class="form-control" value="" id="phone_extra">
                                                </div>
                                              </div>
                                              <div class="col-md-3">
                                                    <div class="form-group">
                                                            <h5 class="card-title">Can I Text?</h4>
                                                            <div class="row">
                                                                <div class="custom-control custom-radio ml-2">
                                                                    <input type="radio" class="custom-control-input" id="textingYes" name="text" value="1">
                                                                    <label class="custom-control-label" for="textingYes">Yes</label>
                                                                </div>
                                                                <div class="custom-control custom-radio mb-3 ml-2">
                                                                    <input type="radio" class="custom-control-input" id="textingNo" name="text" value="0">
                                                                    <label class="custom-control-label" for="textingNo">No</label>
                                                                </div>
                                                            </div>
                                                    </div>
                                              </div>
                                              <div class="col-md-4">
                                                    <div class="form-group">
                                                            <h5 class="card-title">Can I Whatsapp?</h4>
                                                            <div class="row">
                                                                <div class="custom-control custom-radio ml-2">
                                                                    <input type="radio" class="custom-control-input" id="whatsappYes" name="whatsapp" value="1">
                                                                    <label class="custom-control-label" for="whatsappYes">Yes</label>
                                                                </div>
                                                                <div class="custom-control custom-radio mb-3 ml-2">
                                                                    <input type="radio" class="custom-control-input" id="whatsappNo" name="whatsapp" value="0">
                                                                    <label class="custom-control-label" for="whatsappNo">No</label>
                                                                </div>
                                                            </div>
                                                            
                                                    </div>
                                              </div>
                                              <div class="col-md-5">
                                                    <h4 class="card-title " style="opacity:0;">Can I Whatsapp?</h4>
                                                    <div class="checkbox checkbox-primary">
                                                        <input id="checkbox2" type="checkbox" value="1" name="add_to_address">
                                                        <label class="mb-0" for="checkbox2"> Add to Address Book </label>
                                                    </div>
                                              </div>
                                      </div>
                                   </section>
                                   <!-- Step 3 -->
                                  
                                   <!-- Step 4 -->
                                   <h6>Stage 3</h6>
                                   <section>
                                       <div class="row">
                                           <div class="col-md-10">
                                               <div class="card">
                                                   <div class="card-body">
                                                       <!-- <h4 class="card-title"></h4> -->
                                                   <div id="mapTech" class="gmaps"></div>
                                                   </div>
                                               </div>
                                           </div>
                                           <hr>
                                           <div class="col-md-6 p-small">
                                                <p>Job Status : <span> ( Pending Assignment)</span></p> 
                                                <p>Agent to dispatch to: <span id="address-show"> </span></p> 
                                               
                                           </div>
                                           <div class="col-md-6 p-large">
                                                <p>We will : <span> Call/Whatsapp you at</span></p> 
                                                <p id="phone-show">  <span><i class="fa fa-pencil-alt"></i></span></p> 
                                                <p>Time for ( Variable Text) | <span id="date-show"></span> 00:00 AM/PM</p> 
                                           </div>
                                       </div>
                                   </section>
                               </form>
                           </div>
                       </div>
                   </div>
                   <!-- ============================================================== -->
                   <!-- Example -->
                   <!-- ============================================================== -->
                 
               </div>
      </div>
      <!-- <div class="modal-footer text-right">
        <button type="button" class="btn btn-success">Save</button>
      </div> -->
      <input type="hidden" id="google_api_key">
    </div>
  </div>
</div>


<!-- Modal -->
<div class="modal fade" id="stepCheck"  data-backdrop="static" tabindex="-1" aria-labelledby="stepCheckLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content ">
            <div class="modal-header">
                <h5 class="modal-title" id="stepCheckLabel">Start Dispatch </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                    <div class="row">
                        
                        <!-- ============================================================== -->
                        <!-- Example -->
                        <!-- ============================================================== -->
                        <div class="col-12">
                            <div class="card">
                                <div class="card-body wizard-content">
                                    <form id="watch_form" class="tab-wizard2  wizard-circle mt-5">
                                        <!-- Step 1 -->
                                        <h6>Database Conenction</h6>
                                        <section>
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="form-group">
                                                            <label for="staffSelect">DB Hostname</label>
                                                            <input class="form-control" type="text">
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                            <label for="staffSelect">DB Username</label>
                                                            <input class="form-control" type="text">
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                            <label for="staffSelect">DB Password</label>
                                                            <input class="form-control" type="password">
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                            <label for="staffSelect">DB Name</label>
                                                            <input class="form-control" type="text">
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                            <label for="staffSelect">DB Port</label>
                                                            <input class="form-control" type="text">
                                                    </div>
                                                </div>
                                            </div>
                                        </section>
                                        <!-- Step 2 -->
                                        <h6>Personal Info</h6>
                                        <section>
                                                <div class="row">
                                                <div class="col-md-12">
                                                    <div class="form-group">
                                                            <label for="staffSelect">Admin Name</label>
                                                            <input class="form-control" type="text">
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                            <label for="staffSelect">Email</label>
                                                            <input class="form-control" type="text">
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                            <label for="staffSelect">Password</label>
                                                            <input class="form-control" type="password">
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                            <label for="staffSelect">Phone (optional)</label>
                                                            <input class="form-control" type="text">
                                                    </div>
                                                </div>
                                            </div>
                                        </section>
                                        <!-- Step 3 -->
                                        <h6>Company Settings</h6>
                                        <section>
                                                <div class="row">
                                                <div class="col-md-12">
                                                    <div class="form-group">
                                                            <label for="staffSelect">Name</label>
                                                            <input class="form-control" type="text">
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                            <label for="staffSelect">Email</label>
                                                            <input class="form-control" type="text">
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                            <label for="staffSelect">Phone</label>
                                                            <input class="form-control" type="password">
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                            <label for="staffSelect">Website (optional)</label>
                                                            <input class="form-control" type="text">
                                                    </div>
                                                </div>
                                            </div>
                                        </section>

                                        <!-- Step  4-->
                                        <h6>System Settings</h6>
                                        <section>
                                                <div class="row">
                                                <div class="col-md-12">
                                                        <label for="staffSelect">Logo</label>
                                                        <div class="input-group">
                                                            <div class="custom-file">
                                                                <input type="file" name="profile_img" class="custom-file-input" id="customFilePP" accept="image/*">
                                                                <label class="custom-file-label" for="customFilePP" style="border-color:#848484 !important;"></label>
                                                            </div>
                                                        </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                            <label for="staffSelect" style="padding-top:10px;">Favicon</label>
                                                            <div class="input-group">
                                                            <div class="custom-file">
                                                                <input type="file" name="profile_img" class="custom-file-input" id="customFilePP" accept="image/*">
                                                                <label class="custom-file-label" for="customFilePP" style="border-color:#848484 !important;"></label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                            <label for="staffSelect" style="padding-top:10px;">Login Page Logo</label>
                                                            <div class="input-group">
                                                            <div class="custom-file">
                                                                <input type="file" name="profile_img" class="custom-file-input" id="customFilePP" accept="image/*">
                                                                <label class="custom-file-label" for="customFilePP" style="border-color:#848484 !important;"></label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                            <label for="staffSelect">Domain URL</label>
                                                            <input class="form-control" type="url">
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                            <label for="staffSelect">Site Name</label>
                                                            <input class="form-control" type="text">
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                            <label for="staffSelect">Logo Title (optional)</label>
                                                            <input class="form-control" type="text">
                                                    </div>
                                                </div>
                                            </div>
                                        </section>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <!-- ============================================================== -->
                        <!-- Example -->
                        <!-- ============================================================== -->
            
                </div>
            </div>
        </div>
    </div>
</div>
<!--<script src="theme_assets/libs/jsgrid/db.js"></script>-->

@endsection

@section('scripts') 
    @include('js_files.dispatch.watch.indexJs')
@endsection