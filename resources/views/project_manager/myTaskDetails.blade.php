@extends('layouts.staff-master-layout')
@section('body-content')


<div class="d-flex justify-content-between container mt-4 col-12">
    <div>
        <h3 class="page-title ml-3 mb-0">Task Detail</h3>
        <div class="d-flex align-items-center">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb pb-0 mb-0 small">
                    <li class="breadcrumb-item"><a href="{{route('home')}}">Home</a></li>
                    <li class="breadcrumb-item ">Project Manager</li>
                    <li class="breadcrumb-item active" aria-current="page">{{$task == null && $task == "" ? '-' : $task->title}}</li>
                </ol>
            </nav>
        </div>
    </div>
    <div>
        <input type="hidden" id="base_url" value="{{URL::to('/')}}">
        <button title="Pause Task" id="pauseTask_2" onclick="ChangeTaskStatus({{$task->id}},'danger')" style="display:none" class="btn btn-warning btn-sm rounded mr-2"> <i class="fas fa-pause-circle"></i> Pause Task  </button>
        <button title="Start Task" id="startTask_2" onclick="ChangeTaskStatus({{$task->id}},'default')" style="display:none" class="btn btn-info btn-sm rounded mr-2"> <i class="fas fa-play-circle"></i> Start Task </button>

        @if( $task->task_status == "default")

            <button title="Pause Task"  onclick="ChangeTaskStatus({{$task->id}},'danger')" id="pauseTask_1" class="btn btn-warning btn-sm rounded mr-2"> <i class="fas fa-pause-circle"></i> Pause Task </button>
            <button title="Complete Task" onclick="completeTask()" id="completed_BTn" class="btn btn-success btn-sm rounded  mr-3"> Mark Completed </button>

        @elseif($task->task_status == "danger")

            <button title="Start Task" onclick="ChangeTaskStatus({{$task->id}},'default')" id="startTask_1" class="btn btn-info btn-sm rounded  mr-2"> <i class="fas fa-play-circle"></i> Start Task </button>
            <button title="Complete Task" onclick="completeTask()" id="completed_BTn" class="btn btn-success btn-sm rounded  mr-3"> <i class="fas fa-check-circle"></i> Mark Completed </button>

        @else
            <button title="Revert Task" onclick="revertTask()" id="revert_BTn" class="btn btn-danger btn-sm rounded  mr-3"> <i class="fas fa-history"></i> Revert Task </button>
        @endif
        

        <!-- <button title="Complete Task" onclick="completeTask()" id="completed_BTn" class="btn btn-success  mr-3"> Mark Completed </button> -->
    </div>

</div>

<input type="hidden" id="total_worked_time" value="{{$task->worked_time}}">
<input type="hidden" id="tsk_type" value="{{$task->task_status}}">




<div class="container-fluid">
    <div class="card">
        <div class="card-body card_shadow">
            <form action="">
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            @if($task != null && $task != '')
                                <h3>{{$task->title == null && $task->title == '' ? '-' : $task->title}}</h3>
                            @else
                                <span class="text-danger">Task Title is Missing</span>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            @if($task != null && $task != '' || $task->taskProject != null && $task->taskProject != '')
                                <label for="">Project Name</label>
                                <h3>{{ $task->taskProject['name'] == null && $task->taskProject['name'] == '' ? '-' : $task->taskProject['name']}}</h3>
                            @else
                                <span class="text-danger">Project Name is Missing</span>
                            @endif
                            
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            @if($task != null && $task != '')
                                <label for="">Task Version</label>
                                <h3>{{$task->version == null && $task->version == '' ? '-' : $task->version}}</h3>
                            @else
                                <span class="text-danger">Task Version is Missing</span>
                            @endif                            
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            @if($task != null && $task != '')
                                <label for="">Task Due Date</label>
                                <h3>{{$task->due_date == null && $task->due_date == '' ? '-' : $task->due_date}}</h3>
                            @else
                                <span class="text-danger">Task Due Date is Missing</span>
                            @endif                            
                        </div>
                    </div>
                   
                </div>
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            @if($task != null && $task != '')
                                <label for="">Task Id</label>
                                <h3>{{$task->id == null && $task->id == '' ? '-' : $task->id}}</h3>
                            @else
                                <span class="text-danger">Task ID is Missing</span>
                            @endif                             
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            @if($task != null && $task != '')
                                <label for="">Task Duration</label>
                                <h3>{{$task->estimated_time == null && $task->estimated_time == '' ? '-' : $task->estimated_time}}</h3>
                            @else
                                <span class="text-danger">Task Duration is Missing</span>
                            @endif                            
                        </div>
                    </div>
                    <div id="taskCounter" class="col-md-4 bg-success ">
                        <div class="form-group text-white">
                                <label for="">Task Counter</label>
                                <h3 class="text-white mb_event_time showhere" id="showCounter"> </h3>
                         </div>
                    </div>
                    
                    <div class="col-md-12">
                        <div class="form-group">
                            @if($task != null && $task != '')
                                <label for="">Task Details</label>
                                <p><?php echo $task->task_description == null && $task->task_description == '' ? '-' : $task->task_description ?></p>
                            @else
                                <span class="text-danger">Task Details is Missing</span>
                            @endif
                            
                        </div>
                    </div>
                </div>
            </form>

            <div class="loader_container" id="tsk_detail">
                <div class="loader"></div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <h3>Task Attachments</h3>
        </div>
    </div>


    <div class="card ">
        <div class="card-body card_shadow">
            @if(sizeof($task->taskAttachments) > 0)
            <div class="row el-element-overlay">
                @foreach($task->taskAttachments as $attachment)

                <?php
                $img_ext = explode(".", $attachment->attachment);
                if (strtolower($img_ext[1]) == "jpg" || strtolower($img_ext[1]) == "png" || strtolower($img_ext[1]) == "jpeg") {

                ?>
                    <!-- <div class="el-element-overlay"> -->
                        <div class="col-lg-3 col-md-6">
                            <div class="card">
                                <div class="el-card-item">
                                    <div class="el-card-avatar el-overlay-1 w-100 overflow-hidden position-relative text-center">
                                        <img src="{{asset('files/Projects')}}/{{$task->taskProject['project_slug']}}/{{$task->id}}/{{$attachment->attachment}}" class="d-block position-relative w-100 rounded" alt="user">
                                        <div class="el-overlay w-100 overflow-hidden">
                                            <ul class="list-style-none el-info text-white text-uppercase d-inline-block p-0">
                                                <li class="el-item d-inline-block my-0  mx-1">
                                                    <a class="btn default btn-outline el-link text-white border-white" download href="{{asset('files/Projects')}}/{{$task->taskProject['project_slug']}}/{{$task->id}}/{{$attachment->attachment}}"><i class="fa fa-download"></i></a>
                                                </li>
                                            </ul>
                                        </div>`
                                    </div>
                                </div>
                            </div>
                        </div>
                    <!-- </div> -->
                <?php
                } else if (strtolower($img_ext[1]) == "xls" || strtolower($img_ext[1]) == "xlsx" || strtolower($img_ext[1]) == "xlsm" || strtolower($img_ext[1]) == "xlsb") {

                ?>
                    <div class="card mr-3">
                        <div class="el-card-item">
                            <div class="el-card-avatar el-overlay-1 w-100 overflow-hidden position-relative text-center">
                                <img src="{{asset('public/files/excel.svg')}}" style="width:120px;height:auto" class="img-fluid" alt="user">
                                <div class="el-overlay w-100 overflow-hidden">
                                    <ul class="list-style-none el-info text-white text-uppercase d-inline-block p-0">
                                        <li class="el-item d-inline-block my-0  mx-1">
                                            <a class="btn default btn-outline el-link text-white border-white" download href="{{asset('files/Projects')}}/{{$task->taskProject['project_slug']}}/{{$task->id}}/{{$attachment->attachment}}"><i class="fa fa-download"></i></a>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php
                } else if (strtolower($img_ext[1]) == "doc" || strtolower($img_ext[1]) == "docm" || strtolower($img_ext[1]) == "docx" || strtolower($img_ext[1]) == "dot") {

                ?>
                    <div class="card mr-3">
                        <div class="el-card-item">
                            <div class="el-card-avatar el-overlay-1 w-100 overflow-hidden position-relative text-center">
                                <img src="{{asset('public/files/word.svg')}}" style="width:120px;height:auto" class="img-fluid" alt="user">
                                <div class="el-overlay w-100 overflow-hidden">
                                    <ul class="list-style-none el-info text-white text-uppercase d-inline-block p-0">
                                        <li class="el-item d-inline-block my-0  mx-1">
                                            <a class="btn default btn-outline el-link text-white border-white" download href="{{asset('files/Projects')}}/{{$task->taskProject['project_slug']}}/{{$task->id}}/{{$attachment->attachment}}"><i class="fa fa-download"></i></a>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>

                <?php

                } else if (strtolower($img_ext[1]) == "pdf") {
                ?>
                    <div class="card mr-3">
                        <div class="el-card-item">
                            <div class="el-card-avatar el-overlay-1 w-100 overflow-hidden position-relative text-center">
                                <img src="{{asset('public/files/file.svg')}}" style="width:120px;height:auto" class="img-fluid" alt="user">
                                <div class="el-overlay w-100 overflow-hidden">
                                    <ul class="list-style-none el-info text-white text-uppercase d-inline-block p-0">
                                        <li class="el-item d-inline-block my-0  mx-1">
                                            <a class="btn default btn-outline el-link text-white border-white" download href="{{asset('files/Projects')}}/{{$task->taskProject['project_slug']}}/{{$task->id}}/{{$attachment->attachment}}"><i class="fa fa-download"></i></a>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php
                }
                ?>
                @endforeach
            </div>
            @else
            <p class="lead font-weight-bold text-danger">Nothing attached.</p>
            @endif

            <div class="loader_container" id="tsk_attchmnt">
                <div class="loader"></div>
            </div>
        </div>
    </div>

    @if( $task->reverted_count > 0 )
        <div class="row">
            <div class="col-md-12">
                <h3>Reverted Task Description</h3>
            </div>
        </div>
        <div class="card p-3">
            {!! $task->remarks !!}
        </div>
    @endif

</div>


<!-- TASK COMPELTED Modal -->
<div class="modal fade" id="closingRemarks" tabindex="-1" aria-labelledby="modalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header" id="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Closing Remarks </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" id="modalBody">
                <form class="mt-1" id="completeTaskForm" method="POST" action="{{url('change-my-task-status')}}">
                    <input type="hidden" id="task_id" value="{{$task == null ? '-' : $task->id}}">
                    <div class="form-group">
                        <textarea id="remarks" class="form-control" rows="3" placeholder="Text Here..."></textarea>
                    </div>
                    <button type="submit" id="cmp_btn" class="btn btn-success btn-sm rounded"><i class="mdi mdi-check-circle"></i> Save</button>
                    <button style="display:none" id="processing" class="btn btn-sm btn-success" type="button" disabled><i class="fas fa-circle-notch fa-spin"></i> Processing</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- revert task Modal -->
<div class="modal fade" id="revertTaskModal" tabindex="-1" aria-labelledby="modalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header" id="modal-header">
                <h5 class="modal-title" id="exampleModalLabel"> Reason </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" id="modalBody">
                <form class="mt-1" id="revertTaskForm" method="POST" action="{{url('revert-task')}}">
                    <input type="hidden" id="tsk_id" value="{{$task == null ? '-' : $task->id}}">
                    <div class="form-group">
                        <textarea id="tinymceEditor" class="form-control" rows="3" placeholder="Reason"></textarea>
                    </div>
                    <button type="submit" id="cmp_btn" class="btn btn-success btn-sm rounded"><i class="mdi mdi-check-circle"></i> Save</button>
                    <button style="display:none" id="processing" class="btn btn-sm btn-success" type="button" disabled><i class="fas fa-circle-notch fa-spin"></i> Processing</button>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection
@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@9"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/timer.jquery/0.9.0/timer.jquery.min.js"></script>
<script src="{{asset('/assets/libs/tinymce/tinymce.min.js')}}"></script>
@include('js_files.project_manager.myTaskDetailsJs')

@endsection

</style>