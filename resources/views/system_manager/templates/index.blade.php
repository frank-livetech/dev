@extends('layouts.master-layout-new')
@section('title' , 'Live-tech System | Template Builder')
@section('System Manager','open')
@section('Template Builder','active')
@section('customtheme')
@php
        $file_path = Session::get('is_live') == 1 ? 'public/' : '/';
        $path = Session::get('is_live') == 1 ? 'public/system_files/' : 'system_files/';
    @endphp
<!-- Fonts -->
<link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,800,800i,900,900i" rel="stylesheet">
<!-- Custom built theme - This already includes Bootstrap 4 -->
<!-- <link rel="stylesheet" href="{{ asset('public/css/maileclipse-app.min.css') }}"> -->
<!-- Font Awesome -->
@php
    $file_path = Session::get('is_live') == 1 ? 'public/' : '/';
@endphp
<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.6.3/css/all.css" integrity="sha384-UHRtZLI+pbxtHCWp1t77Bi1L4ZtiqrqD80Kn4Z8NTSRyMA2Fd33n5dQ8lWUE00s/" crossorigin="anonymous">
<link rel="stylesheet" type="text/css" href="https://unpkg.com/notie/dist/notie.min.css">

<link rel="stylesheet" type="text/css" href="{{asset($file_path . 'app-assets/vendors/css/extensions/jstree.min.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset($file_path . 'app-assets/css/plugins/extensions/ext-component-tree.css')}}">

<!-- Bootstrap & jquery & lodash & popper & lozad -->
<script src="https://cdn.jsdelivr.net/npm/lodash@4.17.11/lodash.min.js"></script>
<script src="https://code.jquery.com/jquery-3.3.1.min.js" integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8=" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/lozad/dist/lozad.min.js"></script>

<!-- Axios Library -->
<script src="https://unpkg.com/axios/dist/axios.min.js"></script>
<!-- Editor Markdown/Html/Text -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/simplemde/latest/simplemde.min.css">
<script src="https://cdn.jsdelivr.net/simplemde/latest/simplemde.min.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/codemirror/5.13.4/codemirror.js"></script>
<link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/codemirror/5.13.4/codemirror.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/tinymce/5.0.0/tinymce.min.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/codemirror/5.13.4/mode/xml/xml.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/codemirror/5.13.4/mode/css/css.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/codemirror/5.13.4/mode/javascript/javascript.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/codemirror/5.13.4/mode/htmlmixed/htmlmixed.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/codemirror/5.43.0/addon/display/placeholder.js"></script>


<style>
    .jstree-themeicon-custom {
        display: none !important;
    }
    .overlay {
        background: white !important; width: 100%; height: 100%; z-index: 99999;position: absolute;opacity: 0.8;
        top:0px; right:0px;
    }
    .img_circle {
            width: 42px; height: 42px; border-radius: 50%; display: flex; justify-content: center; align-items: center;
        }
</style>
@endsection
@section('body')
<div class="app-content content">
    <div class="content-overlay"></div>
    <div class="header-navbar-shadow"></div>
    <div class="content-wrapper container-fluid p-0 mt-2">
        <div class="content-header row">
            <div class="content-header-left  col-12 mb-2">
                <div class="row breadcrumbs-top">
                    <div class="col-md-10">
                        <h2 class="content-header-title float-start mb-0">Template Builder</h2>
                        <div class="breadcrumb-wrapper">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{route('home')}}">Home</a> </li>
                                <li class="breadcrumb-item">System Manager </li>
                                <li class="breadcrumb-item active">Template Builder </li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-3">
            <div class="card">
                <div class="card-header">
                    <h4>Template Categories</h4>
                    <hr>
                </div>
                <div class="card-body">
                    <div id="jstree-basic">
                        <ul>
                            @foreach($tmp_cats as $cat)
                                <li data-jstree='{"icon" : "far fa-folder"}' onclick="getCategoryTemplates({{$cat->cat_id}})">
                                    <i data-feather='folder'></i> {{$cat->cat_name}} ({{count($cat->template)}})
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-9 position-relative">
            <div class="card-header">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h5> {{ __('Templates') }} </h5>
                            <button type="button" style="display:none" class="btn btn-primary float-right update-template">Update</button>
                        </div>
                        <div class="div">
                            <button type="button" class="btn btn-primary float-right save-template">Create</button>
                        </div>
                    </div>
                </div>

                <div class="row" id="showTemplates">
                </div>

                <div class="overlay d-flex justify-content-center align-items-center align-self-center" style="display:none !important">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                </div> 
        </div>
    </div>

</div>


<!-- create template modal -->
<div class="modal fade" id="createTemplate" role="dialog"  data-backdrop="static" aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"> Create Template </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true"></span>
                </button>
            </div>
            <div class="modal-body">
                <form  id="saveCustomTemplate" method="post">
                    <div class="">

                        <div class="form-group">
                            <label class="label-control">Category</label>
                            <select class="form-control select2" name="catid" id="catid">
                            @foreach($tem_cats_modal as $cat)
                                <option value="{{$cat->cat_id}}">{{$cat->cat_name}}</option>
                            @endforeach
                            </select>
                        </div>

                        <div class="form-group mt-1">
                            <label class="label-control">Template Code</label>
                            <input type="text" id="new_template_code" name="template_code" class="form-control">
                        </div>

                        <div class="form-group mt-1">
                            <label class="label-control">Template name</label>
                            <input type="text" id="new_template_name" name="template_name" class="form-control">
                        </div>
                        
                        <div class="form-group mt-1">
                            <label class="label-control">Subject</label>
                            <input type="text" id="temp_subject" name="temp_subject" class="form-control">
                        </div>

                        <div class="form-group mt-1">
                            <label class="label-control">Alert Prefix</label>
                            <input type="text" id="temp_alert_prefix" name="temp_alert_prefix" class="form-control">
                        </div>

                        <input type="hidden" id="new_content" name="content">
                        <input type="hidden" name="template_view_name" id="template_view_name">
                        <input type="hidden" name="template_type" id="template_type">
                        <input type="hidden" name="template_skeleton" id="template_skeleton">
                    
                    </div>
                    <div class="modal-footer">
                        <input type="submit" class="btn btn-primary create-new-btn" value="Create">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div> 


@endsection
@section('scripts')

<script src="{{asset($file_path . 'app-assets/vendors/js/extensions/jstree.min.js')}}"></script>
<script src="{{asset($file_path . 'app-assets/js/scripts/extensions/ext-component-tree.min.js')}}"></script>

<script type="text/javascript">
    var template_arr = [];
    let template_cateogries = {!! json_encode($tmp_cats) !!};
    console.log(template_cateogries , "template_cateogries");
    $(document).ready(function() {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
            }
        });

        // getTemplates();

        $('.save-template').click(function(){
            $("#createTemplate").modal('show');
        });
    });

    $("#saveCustomTemplate").submit(function(e) {
        e.preventDefault();
        $.ajax({
            type: "POST",
            url: "{{route('saveTemplates')}}",
            data: new FormData(this), 
            dataType: 'JSON',
            contentType: false,
            cache: false,
            processData: false,
            beforeSend: function(data) {
                $("#loader").show();
                $("#save_btn").hide();
            },
            success: function(data) {
                console.log(data, "a");
                if ( data.status_code == 200 && data.success == true) {
                    $("#createTemplate").modal("hide");
                    toastr.success( data.message , { timeOut: 5000 });
                    location.reload();
                } else {
                }
            },
            complete: function(data) {
                $("#loader").hide();
                $("#save_btn").show();
            },
            error: function(e) {
                $("#loader").hide();
                $("#save_btn").show();
                console.log(e);
            }
        });
    });


    function getCategoryTemplates(id) {
        $(".overlay").attr('style','display:block !important');
        let item = template_cateogries.find( item => item.cat_id === id);
        
        if(item != null) {

            let item_total_templates = item.template.length;
            let obj = item.template;
            
            let template_html = ``;

            if(item_total_templates > 0) {

                for(var i =0 ; i < obj.length; i++) {

                    let name = obj[i].name.charAt(0);

                    let url = "{{route('createTemplate', ':id')}}";
                    url = url.replace(':id', obj[i].id);

                    let view_url = "{{route('view.Template', ':id')}}";
                    view_url = view_url.replace(':id', obj[i].id);

                    template_html+=`
                        <div class="col-md-4" id="temp_${obj[i].id}">
                            <div class="card p-1">
                                <div class="d-flex justify-content-between">
                                    <li class="d-flex user-mail">
                                        <div class="mail-left pe-50">
                                            <div class="avatar">
                                                <span class="bg-primary img_circle text-white"> ${name.toUpperCase()}  </span>
                                            </div>
                                        </div>
                                        <div class="mail-body">
                                            <div class="mail-details">
                                                <div class="mail-items">
                                                    <h5 class="mb-25"> ${obj[i].name} </h5>
                                                    <span class="text-truncate text-muted small"> ${moment(obj[i].created_at).fromNow()} </span>
                                                </div>
                                            </div>
                                        </div>
                                    </li>
                                    <div class="dropdown chart-dropdown">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-more-vertical font-medium-3 cursor-pointer" data-bs-toggle="dropdown" aria-expanded="false"><circle cx="12" cy="12" r="1"></circle><circle cx="12" cy="5" r="1"></circle><circle cx="12" cy="19" r="1"></circle></svg>
                                        <div class="dropdown-menu dropdown-menu-end" style="">
                                            <a class="dropdown-item" target="_blank" href="${view_url}"> 
                                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-edit-2 font-medium-3 me-50"><path d="M17 3a2.828 2.828 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5L17 3z"></path></svg>
                                            Preview</a>
                                            <a class="dropdown-item" href="${url}"> <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-edit-2 font-medium-3 me-50"><path d="M17 3a2.828 2.828 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5L17 3z"></path></svg> Edit Template</a>
                                            <button class="dropdown-item" onclick="deleteTemplate(${obj[i].id})" > <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-trash font-medium-3 me-50"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path></svg>
                                            Delete Template</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>`

                }


                $("#showTemplates").html(template_html);


            }else{
                $("#showTemplates").html("No Template Found");
            }


        }

        setTimeout(() => {
            $(".overlay").attr('style','display:none !important');    
        }, 1000);
        
    }

    function deleteTemplate(id) {
        Swal.fire({
            title: 'Are you sure?',
            text: "All data related to this note will be removed!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.value) {
                $.ajax({
                    type: 'post',
                    url: "{{route('deleteTemp')}}",
                    data: { id: id },
                    success: function(data) {

                        if (data.success == true &&  data.status_code == 200) {
                            toastr.success( data.message , { timeOut: 5000 });
                            location.reload();
                        } else {
                            toastr.error( data.message , { timeOut: 5000 });
                        }
                    },
                    failure: function(errMsg) {
                        toastr.error( 'Something went Wrong' , { timeOut: 5000 });
                    }
                });
            }
        });
    }


    function getTemplates() {
        $.ajax({
            type: "GET",
            url: "{{route('getTemplates')}}",
            dataType: 'JSON',
            success: function(data) {
                console.log(data, "a");
                if ( data.status == 200 && data.success == true) {
                    template_arr = data.templates;
                    $('#showRecord').DataTable().destroy();
                    $.fn.dataTable.ext.errMode = 'none';
                    var tbl = $('#showRecord').DataTable({
                        data: data.templates,
                        "pageLength": 10,
                        "bInfo": false,
                        "paging": true,
                        columns: [
                            {
                                data: null,
                                defaultContent: ""
                            },
                            {
                                render: function(data, type, full, meta) {
                                    return full.name != null ? full.name : "-";
                                }
                            },
                            {
                                render: function(data, type, full, meta) {
                                    return full.subject != null ? full.subject : "-";
                                }
                            },
                            {
                                render: function(data, type, full, meta) {
                                    return full.alert_prefix != null ? full.alert_prefix : "-";
                                }
                            },
                            {
                                render: function(data, type, full, meta) {
                                    let url = "{{route('createTemplate', ':id')}}";
                                    url = url.replace(':id', full.id);
                                    return `<div class="d-flex justify-content-start">
                                        <a href="${url}" 
                                            type="button" class="btn btn-icon rounded-circle btn-outline-primary waves-effect" data-toggle="tooltip" data-placement="top" title="Edit">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-edit-2 font-medium-3"><path d="M17 3a2.828 2.828 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5L17 3z"></path></svg>
                                        </a>
                                    </div>`;
                                }
                            },
                        ],
                    });
                    tbl.on("order.dt search.dt", function() {
                        tbl.column(0, {
                            search: "applied",
                            order: "applied"
                        })
                            .nodes()
                            .each(function(cell, i) {
                                cell.innerHTML = i + 1;
                            });
                    }).draw();
                } else {
                    
                }
            },
            error: function(e) {
                $("#loader").hide();
                $("#save_btn").show();
                console.log(e);
            }
        });
    }
</script>

@endsection