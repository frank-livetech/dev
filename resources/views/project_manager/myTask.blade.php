@extends('layouts.staff-master-layout')
@section('body-content')

<style>
    .roundBtn {
        border-radius: 100px;
    padding: 9px 11px;
}
    
</style>

<div class="page-breadcrumb">
    <div class="row">
        <div class="col-md-5 align-self-center">
            <h3 class="page-title">Dashboard</h3>
            <div class="d-flex align-items-center">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{route('home')}}">Home</a></li>
                        <li class="breadcrumb-item ">Project Manager</li>
                        <li class="breadcrumb-item active" aria-current="page">My Task</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
</div>
<div class="container-fluid">
        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                <table id="taskTable" class="mt-3 table table-striped table-bordered text-center table-hover w-100">
                    <thead>
                        <tr>
                            <th>SR.</th>
                            <th>Task ID</th>
                            <th>Task Title</th>
                            <th>Project Name</th>
                            <th>Version</th>
                            <th>Task Duration</th>
                            <th>Created By</th>
                            <th>Status</th>
                            <th>Worked Time</th>
                            <th>Action</th>
                            <th>Created at</th>
                        </tr>
                    </thead>
                    <tbody class="small">
                    </tbody>
                    </table>
                    <div class="loader_container">
                        <div class="loader"></div>
                    </div>
                </div>
            </div>
        </div>
</div>
<!-- TASK COMPELTED Modal -->
<div class="modal fade" id="closingRemarks" tabindex="-1" aria-labelledby="modalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header" id="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Closing Remarks</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" id="modalBody">
                <form class="mt-1" id="completeTaskForm" method="POST" action="{{url('change-my-task-status')}}">
                    <input type="hidden" id="task_id">
                    <div class="form-group">
                        <textarea id="remarks" class="form-control" rows="3" placeholder="Text Here..."></textarea>
                    </div>
                    <button type="submit" class="btn btn-success">Save</button>
                </form>
            </div>
        </div>
    </div>
</div>


@endsection
@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@9"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/timer.jquery/0.9.0/timer.jquery.min.js" integrity="sha512-DeNeekCILcrzL1FtTl+zjBD6z2nGucwdZJeZOXtoS9hfL3azWLzqfDgll61D6jO/EhM9gx4PokARMUfgrwiQfw==" crossorigin="anonymous"></script>
@include('js_files.project_manager.myTaskJs')

@endsection

</style>