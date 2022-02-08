@extends('layouts.master-layout-new')
@section('customtheme')
@php
        $file_path = Session::get('is_live') == 1 ? 'public/' : '/';
        $path = Session::get('is_live') == 1 ? 'public/system_files/' : 'system_files/';
    @endphp
<!-- Fonts -->
<link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,800,800i,900,900i" rel="stylesheet">
<!-- Custom built theme - This already includes Bootstrap 4 -->
<link rel="stylesheet" href="{{ asset('public/css/maileclipse-app.min.css') }}">
<!-- Font Awesome -->
<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.6.3/css/all.css" integrity="sha384-UHRtZLI+pbxtHCWp1t77Bi1L4ZtiqrqD80Kn4Z8NTSRyMA2Fd33n5dQ8lWUE00s/" crossorigin="anonymous">
<link rel="stylesheet" type="text/css" href="https://unpkg.com/notie/dist/notie.min.css">

<link rel="stylesheet" type="text/css" href="{{asset($file_path .'app-assets/vendors/css/extensions/jstree.min.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset($file_path .'app-assets/css/plugins/extensions/ext-component-tree.css')}}">

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
        background: white !important; width: 100%; height: 100%; z-index: 99999;position: absolute;display: flex;justify-content: center;align-items: center;opacity: 0.8;
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
                                <li data-jstree='{"icon" : "far fa-folder"}'>
                                    <i data-feather='folder'></i> {{$cat->cat_name}}
                                    @if( count($cat->template) > 0)
                                    <ul>
                                        @foreach($cat->template as $temp)
                                            <li data-jstree='{"icon" : "fab fa-css3-alt"}' onclick="getTemplate({{$temp->id}},{{$cat->cat_id}})"> <i data-feather='file'></i> {{$temp->name}} </li>
                                        @endforeach
                                    </ul>
                                    @endif
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-9">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h5> {{ __('Templates') }} </h5>
                            <button type="button" style="display:none" class="btn btn-primary float-right update-template">Update</button>
                        </div>
                        <div class="div">
                            <button type="button" class="btn btn-primary float-right save-template">Create</button>
                            <button type="button" class="btn btn-secondary float-right preview-toggle mr-2"><i class="far fa-eye"></i> Preview</button>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div id="idtodisplay">
                        <ul class="nav nav-pills" id="pills-tab" role="tablist">
                            <li class="nav-item">
                                <!-- <a class="nav-link active" id="pills-home-tab" data-toggle="pill" href="#pills-home" role="tab" aria-controls="pills-home" aria-selected="true">Editor</a> -->
                            </li>
                        </ul>
                        <div class="tab-content" id="pills-tabContent">
                            <div class="tab-pane fade show active" id="pills-home" role="tabpanel" aria-labelledby="pills-home-tab">
                                <textarea id="template_editor" cols="30" rows="10"></textarea>
                            </div>
                            <div class="tab-pane fade" id="pills-profile" role="tabpanel" aria-labelledby="pills-profile-tab">
                                <textarea id="plain_text" cols="30" rows="10"></textarea>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="overlay" style="display:none !important;">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>

<!-- create template modal -->
<div class="modal fade" id="create-template-modal" role="dialog"  data-backdrop="static" aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"> Create Template </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true"></span>
                </button>
            </div>
            <div class="modal-body">
                <form action="{{ route('saveCustomTemplate')}}" id="saveCustomTemplate" method="post">
                    @csrf
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


<!-- update template modal -->
<div class="modal fade" id="update-template-modal" role="dialog"  data-backdrop="static" aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"> Create Template </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true"></span>
                </button>
            </div>
            <div class="modal-body">
                <form action="{{ route('updateCustomTemplate')}}" id="updateCustomTemplate" method="post">
                    @csrf
                    <div>
                        <div class="form-group">
                            <label class="label-control">Template name</label>
                            <input type="text" id="up_template_name" name="template_name" class="form-control">
                        </div>
                        <div class="form-group mt-1">
                            <label class="label-control">Subject</label>
                            <input type="text" id="edit_temp_subject" name="edit_temp_subject" class="form-control">
                        </div>
                        <div class="form-group mt-1">
                            <label class="label-control">Alert Prefix</label>
                            <input type="text" id="edit_temp_prefix" name="edit_temp_prefix" class="form-control">
                        </div>
                        <input type="hidden" id="update_content" name="update_content">
                        <input type="hidden" id="templateid" name="templateid">
                    
                    </div>
                    <div class="modal-footer">
                    <input type="submit" class="btn btn-primary update-new-btn" value="Update">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div> 

@endsection
@section('scripts')

<script src="{{asset('app-assets/vendors/js/extensions/jstree.min.js')}}"></script>
<script src="{{asset('app-assets/js/scripts/extensions/ext-component-tree.js')}}"></script>

<script type="text/javascript">
    let tmp_cats = {!!json_encode($tmp_cats) !!};
    console.log(tmp_cats , "tmp_cats");

let get_codes_route = "{{asset('/get_all_short_codes')}}";

   $(document).ready(function() {
      // addVaribalesPlugin();

      tinymce.init({
         selector: "textarea#template_editor",
         menubar : false,
         visual: false,
         height:600,
         inline_styles : true,
         plugins: [
            "advlist autolink link image lists charmap print preview hr anchor pagebreak spellchecker",
            "searchreplace wordcount visualblocks visualchars code fullscreen insertdatetime media nonbreaking",
            "save table contextmenu directionality emoticons template paste textcolor",
            "tb_variables"
         ],
         content_css: "css/content.css",
         toolbar: "insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image | table | print preview media fullpage | forecolor backcolor emoticons | tb_variables | code",
         fullpage_default_encoding: "UTF-8",
         fullpage_default_doctype: "<!DOCTYPE html>",
         file_picker_callback: function(cb, value, meta) {
            var input = document.createElement('input');
            input.setAttribute('type', 'file');
            input.setAttribute('accept', 'image/*');

            input.onchange = function() {
               var file = this.files[0];

               var reader = new FileReader();
               reader.onload = async function() {
                     var id = 'blobid' + (new Date()).getTime();
                     var blobCache = tinymce.activeEditor.editorUpload.blobCache;
                     var base64 = reader.result.split(',')[1];

                     if(reader.result.includes('/svg') || reader.result.includes('/SVG')) {
                        base64 = await downloadPNGFromAnyImageSrc(reader.result);
                     }
                     
                     var blobInfo = blobCache.create(id, file, base64);
                     blobCache.add(blobInfo);
                     cb(blobInfo.blobUri(), { title: file.name });
               };
               reader.readAsDataURL(file);
            };
            input.click();
         },
         init_instance_callback: function (editor)
         {
            setTimeout(function(){ 
                  editor.execCommand("mceRepaint");
            }, 1000);
         }
      });
      $('.preview-toggle').click(function(){
         tinyMCE.execCommand('mcePreview');return false;
      });

      $('.save-template').click(function(){
        tinymce.activeEditor.setContent("<p></p>");
        $('.update-template').hide();
        $("#new_content").val(tinymce.get('template_editor').getContent());
        $("#create-template-modal").modal('show');
      });


      //   var plaintextEditor = CodeMirror.fromTextArea(document.getElementById("plain_text"), {
      //       lineNumbers: false,
      //       mode: 'plain/text',
      //       placeholder: "Email Plain Text Version (Optional)",
      //   });

    });
   $(".new-tmp-btn a").click(function(e){
      var element = $(this);
       e.preventDefault();
       $(".tox.tox-tinymce").remove();
       $.ajax({
         type : 'GET',
         url : element.attr('href'),
         success : function(data){
            element.parents().find('.modal').modal('hide');
            $("#template_editor").val(data.skeleton.template);
            $("#template_view_name").val(data.skeleton.name);
            $("template_type").val(data.skeleton.type);
            $("#template_skeleton").val(data.skeleton.skeleton);
            $("#idtodisplay").css('display','unset');
            $("#upidtodisplay").css('display','none');
            tinymce.init({
                selector: "textarea#template_editor",
                menubar : false,
                visual: false,
                height:600,
                inline_styles : true,
                plugins: [
                  "advlist autolink link image lists charmap print preview hr anchor pagebreak spellchecker",
                  "searchreplace wordcount visualblocks visualchars code fullscreen insertdatetime media nonbreaking",
                  "save table contextmenu directionality emoticons template paste textcolor",
                  "tb_variables"
               ],
               content_css: "css/content.css",
               toolbar: "insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image | table | print preview media fullpage | forecolor backcolor emoticons | tb_variables | code",
               fullpage_default_encoding: "UTF-8",
               fullpage_default_doctype: "<!DOCTYPE html>",
               file_picker_callback: function(cb, value, meta) {
                  var input = document.createElement('input');
                  input.setAttribute('type', 'file');
                  input.setAttribute('accept', 'image/*');

                  input.onchange = function() {
                     var file = this.files[0];

                     var reader = new FileReader();
                     reader.onload = async function() {
                           var id = 'blobid' + (new Date()).getTime();
                           var blobCache = tinymce.activeEditor.editorUpload.blobCache;
                           var base64 = reader.result.split(',')[1];

                           if(reader.result.includes('/svg') || reader.result.includes('/SVG')) {
                              base64 = await downloadPNGFromAnyImageSrc(reader.result);
                           }
                           
                           var blobInfo = blobCache.create(id, file, base64);
                           blobCache.add(blobInfo);
                           cb(blobInfo.blobUri(), { title: file.name });
                     };
                     reader.readAsDataURL(file);
                  };
                  input.click();
               },
               init_instance_callback: function (editor)
               {
                setTimeout(function(){ 
                    editor.execCommand("mceRepaint");
                    $(".tox.tox-tinymce").css('display','');
                }, 1000);
               }
            });
         }
       });
   });

   $("#create-new-btn").click(function(e){
      if($("#new_template_name").val().length < 1){
         $("#create-template-modal").modal('hide');
      }else{
         e.preventDefault();
         alert('Please enter template name');
      }
   });

   $("#saveCustomTemplate").submit(function(e){
      e.preventDefault();
      if($("#new_template_name").val().length < 1){
         alert('Please enter template name');
         return false;
      }
      $("#create-template-modal").modal('hide');
      var postData = {
          content: tinymce.get('template_editor').getContent(),
          template_name: $("#template_name").val(),
          template_description: 'test',
          plain_text: 'desc',
          template_view_name: $("#new_template_name").val(),
          template_type: $("#template_type").val(),
          template_skeleton: $("#template_skeleton").val(),
          temp_subject: $("#temp_subject").val(),
          temp_alert_prefix: $("#temp_alert_prefix").val(),
          catid : $("#catid").val(),
      }
      axios.post('{{ route('saveCustomTemplate') }}', postData)

      .then(function (response) {

          if (response.data.status == 'ok'){
            toastr.success(response.data.message, { timeOut: 5000 });
            //   notie.alert({ type: 1, text: response.data.message, time: 3 });

              setTimeout(function(){
                  window.location.replace(window.location.href);
              }, 1000);

          } else {
            toastr.error(response.data.message, { timeOut: 5000 });
            //   notie.alert({ type: 'error', text: response.data.message, time: 2 })
              setTimeout(function(){
                  window.location.replace(window.location.href);
              }, 1000);
          }
      })

      .catch(function (error) {
        //   notie.alert({ type: 'error', text: error, time: 2 })
          toastr.error(error, { timeOut: 5000 });
      });
   });

   $('.update-template').click(function(){
    // $("#update_content").val(tinymce.get('update-template_editor').getContent());
      $("#update-template-modal").modal('show');
   });

   $("#updateCustomTemplate").submit(function(e){
      e.preventDefault();
    //   $("#update-template-modal").modal('hide');
      var postData = {
         catid: $("#upcatid").val(),
         templateid: $("#templateid").val(),
         template_html: tinymce.get('template_editor').getContent(),
         templatename: $("#up_template_name").val(),
         temp_subject: $("#edit_temp_subject").val(),
         temp_alert_prefix: $("#edit_temp_prefix").val(),
      }
      axios.post('{{ route('updateCustomTemplate') }}', postData)

      .then(function (response) {

          if (response.data.status == 'ok'){

            //   notie.alert({ type: 1, text: response.data.message, time: 3 });
              toastr.success(response.data.message, { timeOut: 5000 });

              setTimeout(function(){
                  window.location.replace(window.location.href);
              },1000);

          } else {
            //   notie.alert({ type: 'error', text: response.data.message, time: 2 })
              toastr.error(response.data.message, { timeOut: 5000 });
          }
      })
      .catch(function (error) {
        toastr.error(error, { timeOut: 5000 });
      });
   });

   function showUpEditor(element,id){
      $("#idtodisplay").css('display','none');
      var postData = {
         id : id,
      }
      axios.post('{{ route('getTemplate') }}', postData)
      .then(function (response) {
          console.log(response);
          console.log("before")
          if (response.data.status == 'ok'){
                $("#upidtodisplay").css('display','block !important');
               //console.log(response.data.template.template_html);

            //    $(".update-textarea-div div").remove();
               $("#update-template_editor").val(response.data.template.template_html);
               
               $("#upcatid").val(response.data.template.catid);
               $("#templateid").val(response.data.template.id);
               $("#up_template_name").val(response.data.template.name);

               $("#edit_temp_subject").val(response.data.template.subject);
               $("#edit_temp_prefix").val(response.data.template.alert_prefix);

               $("#delete-template").attr('data-id',response.data.template.id);
               if(response.data.template.catid==2){
                $("#delete-template").css('display','none');
               }else{
                $("#delete-template").css('display','unset');
               }
               /**start**/
                  tinymce.init({
                      selector: "textarea#update-template_editor",
                      menubar : false,
                      visual: false,
                      height:600,
                      inline_styles : true,
                      plugins: [
                        "advlist autolink link image lists charmap print preview hr anchor pagebreak spellchecker",
                        "searchreplace wordcount visualblocks visualchars code fullscreen insertdatetime media nonbreaking",
                        "save table contextmenu directionality emoticons template paste textcolor",
                        "tb_variables"
                     ],
                     content_css: "css/content.css",
                     toolbar: "insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image | table | print preview media fullpage | forecolor backcolor emoticons | tb_variables | code",
                     fullpage_default_encoding: "UTF-8",
                     fullpage_default_doctype: "<!DOCTYPE html>",
                     file_picker_callback: function(cb, value, meta) {
                        var input = document.createElement('input');
                        input.setAttribute('type', 'file');
                        input.setAttribute('accept', 'image/*');

                        input.onchange = function() {
                           var file = this.files[0];

                           var reader = new FileReader();
                           reader.onload = async function() {
                                 var id = 'blobid' + (new Date()).getTime();
                                 var blobCache = tinymce.activeEditor.editorUpload.blobCache;
                                 var base64 = reader.result.split(',')[1];

                                 if(reader.result.includes('/svg') || reader.result.includes('/SVG')) {
                                    base64 = await downloadPNGFromAnyImageSrc(reader.result);
                                 }
                                 
                                 var blobInfo = blobCache.create(id, file, base64);
                                 blobCache.add(blobInfo);
                                 cb(blobInfo.blobUri(), { title: file.name });
                           };
                           reader.readAsDataURL(file);
                        };
                        input.click();
                     },
                     init_instance_callback: function (editor)
                     {
                      setTimeout(function(){ 
                          editor.execCommand("mceRepaint");
                          $(".update-textarea-div div").css('display','');
                      }, 2000);
                     }
                  });
               /**End**/
          } else {
            //   notie.alert({ type: 'error', text: response.data.message, time: 2 })
              toastr.error(response.data.message, { timeOut: 5000 });
          }
      })

      .catch(function (error) {
        //   notie.alert({ type: 'error', text: error, time: 2 })
          toastr.error( error , { timeOut: 5000 });
      });
   }


   function getTemplate(temp_id , cat_id) {
       $('.overlay').show();
        var category = tmp_cats.find(item=> item.cat_id === cat_id);
        if(category != null || category != "" || category != undefined) {
            let temp = category.template;
            var tem = temp.find(item => item.id === temp_id);

            if(tem != null || tem != "" || tem != undefined) {
                console.log(tem , "tem");
                $(".update-template").show();
                // return false;
                tinymce.activeEditor.setContent(tem.template_html == null ? '<p></p>' : tem.template_html);
                // $("#template_editor").val(tem.template_html);
                    
                $("#upcatid").val(category.id);
                $("#templateid").val(tem.id);

                $("#up_template_name").val(tem.name);

                $("#edit_temp_subject").val(tem.subject);
                $("#edit_temp_prefix").val(tem.alert_prefix);
            }
        }
        setTimeout(() => {
            $('.overlay').hide();    
        },1500);
   }

   function deleteTemplate(element){
      if(!confirm('Are you sure to delete')){
         return false;
      }
      var id = $(element).attr('data-id');
      axios.post('{{ route('deleteCustomTemplate') }}',{id:id})

      .then(function (response) {

          if (response.data.status == 'ok'){

            //   notie.alert({ type: 1, text: response.data.message, time: 3 });
              toastr.success(response.data.message, { timeOut: 5000 });

              setTimeout(function(){
                  window.location.replace(window.location.href);
              }, 2000);

          } else {
            //   notie.alert({ type: 'error', text: response.data.message, time: 2 })
              toastr.error(response.data.message, { timeOut: 5000 });
          }
      })

      .catch(function (error) {
        //   notie.alert({ type: 'error', text: error, time: 2 })
          toastr.error(error, { timeOut: 5000 });
      });
   }
</script>
<script src="{{asset('public/js/short_codes/short_codes.js').'?ver='.rand()}}"></script>

<script type="text/javascript">
$('.remove-item').click(function(){
var templateSlug = $(this).data('template-slug');
var templateName = $(this).data('template-name');
notie.confirm({
text: 'Are you sure you want to do that?<br>Delete Template <b>'+ templateName +'</b>',
submitCallback: function () {
axios.post('{{ route('deleteTemplate') }}', {
templateslug: templateSlug,
})
.then(function (response) {
if (response.data.status == 'ok'){
// notie.alert({ type: 1, text: 'Template deleted', time: 2 });
toastr.success( 'Template deleted' , { timeOut: 5000 });
jQuery('tr#template_item_' + templateSlug).fadeOut('slow');
var tbody = $("#templates_list tbody");
console.log(tbody.children().length);
if (tbody.children().length <= 1) {
location.reload();
}
} else {
// notie.alert({ type: 'error', text: 'Template not deleted', time: 2 })
toastr.error( 'Template not deleted' , { timeOut: 5000 });
}
})
.catch(function (error) {
// notie.alert({ type: 'error', text: error, time: 2 })
toastr.error( error, { timeOut: 5000 });
});
}
})
});

async function downloadPNGFromAnyImageSrc(src) {
   //recreate the image with src recieved
   var img = new Image;
   //when image loaded (to know width and height)
   let r =  new Promise((resolve, reject) => {
         img.onload = async function() {
            //drow image inside a canvas
            var canvas = document.createElement("canvas");
            canvas.width = img.width; canvas.height = img.height;
            canvas.getContext("2d").drawImage(img, 0, 0);
            
            //get image/png from convas
            console.log("Image Loaded");
            resolve(canvas.toDataURL("image/png"));
         };
         img.src = src;
   });
   return await r;
}

</script>

@endsection