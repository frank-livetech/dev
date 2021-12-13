@extends('layouts.staff-master-layout')
@section('body-content')
<br />
<div class="page-breadcrumb">
    <div class="row">
        <div class="col-md-5 align-self-center">
            <!--<h3 class="page-title">Dashboard</h3>-->
            <div class="d-flex align-items-center">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{route('home')}}">Home</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Task Scripts</li>
                    </ol>
                </nav>
            </div>
        </div>

    </div>
</div>
<div class="container-fluid">
    <div class="row">
        <!-- Column -->
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title mb-3">Categories<button class="btn btn-primary float-right" data-toggle="modal" data-target="#modalCategory"> <span class="fa fa-plus mr-2"></span> ADD</button></h4>
                    <ul class="nav nav-tabs mb-3 w-100">
                        <li class="nav-item">
                            <a href="#tasks_windows" data-toggle="tab" aria-expanded="false" class="nav-link active">
                                <i class="mdi mdi-home-variant d-lg-none d-block mr-1"></i>
                                <span class="d-none d-lg-block">Windows</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="#tasks_office" data-toggle="tab" aria-expanded="true" class="nav-link">
                                <i class="mdi mdi-account-circle d-lg-none d-block mr-1"></i>
                                <span class="d-none d-lg-block">Office</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="#tasks_others" data-toggle="tab" aria-expanded="false" class="nav-link">
                                <i class="mdi mdi-settings-outline d-lg-none d-block mr-1"></i>
                                <span class="d-none d-lg-block">Other</span>
                            </a>
                        </li>
                    </ul>
                    
                    <div class="tab-content">
                        <div class="tab-pane active" id="tasks_windows">
                            <div class="row">
                                <div class="col-12">
                                    <div class="table-responsive">
                                        <table id="tasks-windows-list"
                                            class="display table-striped table-bordered" style="width:100%">
                                            <thead>
                                                <tr>
                                                    <th style="font-size: 12px;font-weight: normal;"><div class="text-center"><input type="checkbox" name="select_all[]" id="select-all"></div></th>
                                                    <th style="font-size: 12px;font-weight: normal;">Filename</th>
                                                    <th style="font-size: 12px;font-weight: normal;">Size</th></th>
                                                    <th style="font-size: 12px;font-weight: normal;">Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($task_scripts as $ts)
                                                    @if (strtolower($ts->category) == 'windows')
                                                        <tr id="tr-{{$ts->id}}">
                                                            <td><div class="text-center"><input type="checkbox"></div></td>
                                                            <td>{{$ts->filename}}</td>
                                                            <td>{{$ts->size}}</td>
                                                            <td><div class="text-center"><a href="{{asset('public/files/task_scripts/'.$ts->filename)}}" target="_blank"><i class="fa fa-download mr-2" style="cursor: pointer;"></i></a><a href="javascript:delTaskScript({{$ts->id}}, '{{$ts->category}}')"><i class="fa fa-trash text-danger"></i></a></div></td>
                                                        </tr>
                                                    @endif
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane show" id="tasks_office">
                            <div class="row">
                                <div class="col-12">
                                    <div class="table-responsive">
                                        <table id="tasks-office-list"
                                            class="display table-striped table-bordered" style="width:100%">
                                            <thead>
                                                <tr>
                                                    <th style="font-size: 12px;font-weight: normal;"><div class="text-center"><input type="checkbox" name="select_all[]" id="select-all"></div></th>
                                                    <th style="font-size: 12px;font-weight: normal;">Filename</th>
                                                    <th style="font-size: 12px;font-weight: normal;">Size</th></th>
                                                    <th style="font-size: 12px;font-weight: normal;">Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($task_scripts as $ts)
                                                    @if (strtolower($ts->category) == 'office')
                                                        <tr id="tr-{{$ts->id}}">
                                                            <td><div class="text-center"><input type="checkbox"></div></td>
                                                            <td>{{$ts->filename}}</td>
                                                            <td>{{$ts->size}}</td>
                                                            <td><div class="text-center"><a href="{{asset('public/files/task_scripts/'.$ts->filename)}}" target="_blank"><i class="fa fa-download mr-2" style="cursor: pointer;"></i></a><a href="javascript:delTaskScript({{$ts->id}}, '{{$ts->category}}')"><i class="fa fa-trash text-danger"></i></a></div></td>
                                                        </tr>
                                                    @endif
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane" id="tasks_others">
                            <div class="row">
                                <div class="col-12">
                                    <div class="table-responsive">
                                        <table id="tasks-other-list"
                                            class="display table-striped table-bordered" style="width:100%">
                                            <thead>
                                                <tr>
                                                    <th style="font-size: 12px;font-weight: normal;"><div class="text-center"><input type="checkbox" name="select_all[]" id="select-all"></div></th>
                                                    <th style="font-size: 12px;font-weight: normal;">Filename</th>
                                                    <th style="font-size: 12px;font-weight: normal;">Size</th></th>
                                                    <th style="font-size: 12px;font-weight: normal;">Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($task_scripts as $ts)
                                                    @if (strtolower($ts->category) == 'other')
                                                        <tr id="tr-{{$ts->id}}">
                                                            <td><div class="text-center"><input type="checkbox"></div></td>
                                                            <td>{{$ts->filename}}</td>
                                                            <td>{{$ts->size}}</td>
                                                            <td><div class="text-center"><a href="{{asset('public/files/task_scripts/'.$ts->filename)}}" target="_blank"><i class="fa fa-download mr-2" style="cursor: pointer;"></i></a><a href="javascript:delTaskScript({{$ts->id}}, '{{$ts->category}}')"><i class="fa fa-trash text-danger"></i></a></div></td>
                                                        </tr>
                                                    @endif
                                                @endforeach
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

    <!--  Modal content category start -->
    <div class="modal fade" id="modalCategory" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true"  data-backdrop="static">
        <div class="modal-dialog modal-md">
            <div class="modal-content">
                <div class="modal-header d-flex align-items-center">
                    <h4 class="modal-title" id="myLargeModalLabel" style="color:#009efb;">Add Category</h4>
                    <button type="button" class="close ml-auto" data-dismiss="modal" aria-hidden="true">×</button>
                </div>
                <div class="modal-body">
                    <form class="form-horizontal mt-4" id="save_script" action="{{asset('save-task-scripts')}}" method="post" enctype="multipart/form-data">
                        <div class="row">
                            <div class="col-sm-12 col-xs-12">

                                <fieldset>
                                    <div class="form-group">
                                        <div class="row">
                                            <div class="col-sm-12">
                                                <label class="control-label">Select Category<span style="color:red !important;">*</span></label><span id="select-status" style="display:none; color:red !important;">Please Select Category</span>

                                                <select class="select2 form-control " id="category" name="category" style="width: 100%; height:36px;" required>
                                                    <option value="Windows">Windows</option>
                                                    <option value="Office">Office</option>
                                                    <option value="Other">Other</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="row">
                                            <div class="col-sm-12">
                                                <label class="control-label">File<span style="color:red !important;">*</span></label><span id="select-subject" style="display: none; color: red !important;">File cannot be Empty </span>
                                                <div class="custom-file text-left">
                                                    <input type="file" class="custom-file-input" id="importCatFile" name="file" required>
                                                    <label class="custom-file-label" for="importCatFile">Choose file</label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </fieldset>
                                <div class="text-right">
                                    <button type="submit" class="btn waves-effect waves-light btn-success">Save</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal content ticket end -->
</div>
@endsection
@section('scripts')

<script>
    let del_url = "{{asset('del-task-scripts')}}";
</script>

<script src="{{asset('public/js/about/system_info/task_scripts.js')}}"></script>

@endsection
