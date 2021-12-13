@extends('layouts.staff-master-layout')

@section('body-content')
<link href="{{asset('assets/extra-libs/jquery-steps/jquery.steps.css')}}" rel="stylesheet">
<link href="{{asset('assets/extra-libs/jquery-steps/steps.css')}}" rel="stylesheet">
<div class="page-breadcrumb">
    <div class="row">
        <div class="col-md-5 align-self-center">
            <!--<h4 class="page-title">Tickets Manager</h4>-->
            <div class="d-flex align-items-center">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <!-- <li class="breadcrumb-item"><a href="{{route('home')}}">Home</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Wizard</li> -->
                    </ol>
                </nav>
            </div>
        </div>
    </div>
</div>
<div class="container">
    <div class="card">
        <div class="row p-3">
            <div class="col-md-12">
            <button type="button" class="btn btn-success" data-toggle="modal" data-target="#tempTitle-modal"> Create New </button>
                                
            </div>
        </div>
           <div class="col-md-12">
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
<!-- Create Template Title modal content -->
<div id="tempTitle-modal" class="modal fade" tabindex="-1" role="dialog"  data-backdrop="static" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="text-center bg-info p-3">
                <a href="index.html" class="text-success">
                    <span><img class="mr-2" src=""
                            alt="" height="18"><img
                            src="" alt=""
                            height="18"></span>
                </a>
            </div>
            <div class="modal-body">
                <form action="#" class="pl-3 pr-3">
                    <div class="form-group">
                        <label for="tempTitle">Template Title</label>
                        <input class="form-control" type="text" id="tempTitle"
                            required="" placeholder="Title">
                    </div>
                    <div class="form-group text-center">
                        <button class="btn btn-rounded btn-primary" type="submit">Create</button>
                    </div>

                </form>

            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
          
@endsection
@section('scripts')
<script>

</script>
@endsection
