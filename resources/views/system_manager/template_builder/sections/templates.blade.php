@extends('layouts.staff-master-layout')
@section('customtheme')
<!-- Fonts -->
<link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,800,800i,900,900i" rel="stylesheet">
<!-- Custom built theme - This already includes Bootstrap 4 -->
<link rel="stylesheet" href="{{ asset('public/css/maileclipse-app.min.css') }}">
<!-- Font Awesome -->
<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.6.3/css/all.css" integrity="sha384-UHRtZLI+pbxtHCWp1t77Bi1L4ZtiqrqD80Kn4Z8NTSRyMA2Fd33n5dQ8lWUE00s/" crossorigin="anonymous">
<link rel="stylesheet" type="text/css" href="https://unpkg.com/notie/dist/notie.min.css">
<!-- Bootstrap & jquery & lodash & popper & lozad -->
<script src="https://cdn.jsdelivr.net/npm/lodash@4.17.11/lodash.min.js"></script>
<script src="https://code.jquery.com/jquery-3.3.1.min.js" integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8=" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/lozad/dist/lozad.min.js"></script>
<!-- Notie Library -->
<script src="https://unpkg.com/notie"></script>
<!-- Axios Library -->
<script src="https://unpkg.com/axios/dist/axios.min.js"></script>
<!-- Editor Markdown/Html/Text -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/simplemde/latest/simplemde.min.css">
<script src="https://cdn.jsdelivr.net/simplemde/latest/simplemde.min.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/codemirror/5.13.4/codemirror.js"></script>
<link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/codemirror/5.13.4/codemirror.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/tinymce/5.0.0/tinymce.min.js"></script>
{{-- <script src="{{asset('assets/libs/tinymce/tinymce.min.js')}}"></script> --}}
<script src="//cdnjs.cloudflare.com/ajax/libs/codemirror/5.13.4/mode/xml/xml.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/codemirror/5.13.4/mode/css/css.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/codemirror/5.13.4/mode/javascript/javascript.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/codemirror/5.13.4/mode/htmlmixed/htmlmixed.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/codemirror/5.43.0/addon/display/placeholder.js"></script>
@endsection
@section('body-content')
<style>
#mailables_list svg {
width: 20px!important;
height: 20px;
}
.table-fit a svg {
width: 20px;
}
.notie-container {
   z-index: 99;
}
.tox .tox-dialog-wrap div.tox-dialog{
   position: absolute !important;
   z-index: 999999;
}
.nav-tabs .nav-item .design {
    background-color: #009efb;
    color: #fff;
    border-radius: 4px;
    font-size: 16px;
    font-weight: 500;
    margin-right: 5px;
    margin-bottom: 5px;
}
.nav-tabs .nav-item .design.active {
    background-color: #868e96;
    color: #fff;
    border-radius: 4px;
    font-size: 16px;
    font-weight: 500;
    margin-right: 5px;
    margin-bottom: 5px;
}
.nav-tabs .nav-item .design:focus, .nav-tabs .nav-item .design:hover {
    background-color: #868e96;
    color: #fff;
    border-radius: 4px;
    font-size: 16px;
    font-weight: 500;
    margin-right: 5px;
    margin-bottom: 5px;
}
.nav-tabs {
    border-bottom: unset;
}
.design-tab {
    margin-top: 15px;
}
.design-tab ul {
    margin-left: 0px;
    padding-left: 0px;
}
.design-tab .content-details h4 {
    font-size: 16px;
    text-transform: capitalize;
    font-weight: 500;
    color: #fff;
    text-align: center;
    background-color: #7460ee;
    padding: 12px;
    border-radius: 4px;
}
.design-tab .content-details h4:focus, .design-tab .content-details h4:hover {
    background-color: ##868e96;
}
</style>
<div class="row m-2">

   <div class="col-lg-9 col-md-9">
    
      <div class="card my-4">
         <div class="card-header d-flex align-items-center justify-content-between"><h5>{{ __('Templates') }}</h5>
           
         </div>
        
         <div id="idtodisplay">
            <!-- for local use only -->
            <div class="card mb-2">
                <div class="card-header p-3" style="border-bottom:1px solid #e7e7e7e6;">
                    <button type="button" class="btn btn-primary float-right save-template">Create</button>
                    <button type="button" class="btn btn-secondary float-right preview-toggle mr-2"><i class="far fa-eye"></i> Preview</button>
                </div>
            </div>


            <ul class="nav nav-pills" id="pills-tab" role="tablist">
              <li class="nav-item">
                <a class="nav-link active" id="pills-home-tab" data-toggle="pill" href="#pills-home" role="tab" aria-controls="pills-home" aria-selected="true">Editor</a>
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
            <!-- for local use only -->

         </div>
         <div id="upidtodisplay" style="display: none;">
            <!-- for local use only -->
            <ul class="nav nav-pills" id="pills-tab" role="tablist">
              <li class="nav-item">
                <a class="nav-link active" id="pills-home-tab" data-toggle="pill" href="#pills-home" role="tab" aria-controls="pills-home" aria-selected="true">Editor</a>
              </li>
            </ul>
            <!-- for local use only -->

            <div class="tab-content" id="pills-tabContent">
              <div class="tab-pane fade show active update-textarea-div" id="pills-home" role="tabpanel" aria-labelledby="pills-home-tab">
                <textarea id="update-template_editor" cols="30" rows="10"></textarea>
              </div>
            </div>

            <div class="card mb-2">
               <div class="card-header p-3" style="border-bottom:1px solid #e7e7e7e6;">
                   {{-- <button type="button" class="btn btn-danger float-right delete-template ml-1" id="delete-template" onclick="deleteTemplate(this)">Delete</button> --}}
                   <button type="button" class="btn btn-primary float-right update-template">Update</button>

                   <button type="button" class="btn btn-secondary float-right preview-toggle mr-2"><i class="far fa-eye"></i> Preview</button>
               </div>
           </div>
         </div>
      </div>
   </div>

   <div class="col-lg-3 col-md-3">
      <ul class="nav nav-tabs" id="myTab" role="tablist">
          @php $count=1; @endphp
         @foreach($tmp_cats as $cat)
           <li class="nav-item">
               <!-- for local use only -->
             <a class="nav-link design bg-secondary {{$count==1 ? 'active' : ''}}" id="{{$cat->cat_name}}-tab" 
                data-toggle="tab" href="#{{$cat->cat_name.'_'.$cat->cat_id}}" role="tab" 
                aria-controls="{{$cat->cat_name}}" aria-selected="true">{{$cat->cat_name}}</a>
            <!-- for local use only -->

           </li>
            @php $count++; @endphp
         @endforeach
         <!-- <li class="nav-item">
           <a class="nav-link design" id="Pre-tab" data-toggle="tab" href="#Pre-define" role="tab" 
           aria-controls="Pre-difine" aria-selected="false">CoreSystem</a>
        </li>
         <li class="nav-item">
           <a class="nav-link design" id="Pre-tab" data-toggle="tab" href="#Pre-define" role="tab" 
           aria-controls="Pre-difine" aria-selected="false">CustManager</a>
        </li> -->
       <!--  <li class="nav-item">
          <a class="nav-link" id="profile-tab" data-toggle="tab" href="#profile" role="tab" aria-controls="profile" aria-selected="false">Profile</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" id="contact-tab" data-toggle="tab" href="#contact" role="tab" aria-controls="contact" aria-selected="false">Contact</a>
        </li> -->
      </ul>
      <div class="tab-content design-tab" id="myTabContent">

         @php $count=1; @endphp
         @foreach($tmp_cats as $cat)

           <div class="tab-pane fade show {{$count==1 ? 'active' : ''}}" id="{{$cat->cat_name.'_'.$cat->cat_id}}" role="tabpanel" aria-labelledby="{{$cat->cat_name}}-tab">
               @php $templates = \DB::table('templates')->where('catid',$cat->cat_id)->get();
               @endphp
               <ul style="list-style: none;">
                  @foreach($templates as $template)
                     <li>
                        <div class="content template-item">
                           <a href="javaScript:;" data-id="{{$template->id}}" onclick="showUpEditor(this,'{{$template->id}}')">
                              <div class="content-details">
                               <h4 class="content-title mb-3">{{$template->name}}</h4>
                              </div>
                           </a>
                        </div>

                     </li>
                  @endforeach
                  
               </ul>
           </div>
           @php $count++; @endphp
         @endforeach
        <div class="tab-pane fade" id="Pre-define" role="tabpanel" aria-labelledby="pre-tab">
            <ul style="list-style: none;">
               @foreach( $skeletons->get('html') as $name => $subskeleton )
               
                  <li>

                     <div class="content template-item" data-toggle="modal" data-target="#select{{ $name }}Modal">
                        <a href="javaScript:;">
                           <div class="content-details">
                            <h4 class="content-title mb-3">{{ $name }}</h4>
                           </div>
                        </a>
                     </div>
                     <!-- Modal -->
                     @foreach($subskeleton as $skeleton)
                     <div class="modal fade" id="select{{ $name }}Modal" tabindex="-1" role="dialog" aria-labelledby="selectTemplateModalLabel" aria-hidden="true">
                      <div class="modal-dialog" role="document">
                       <div class="modal-content">
                        <div class="modal-header">
                         <h5 class="modal-title" id="selectTemplateModalLabel">{{ ucfirst($name) }}</h5>
                         <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                         <span aria-hidden="true">&times;</span>
                         </button>
                        </div>
                        <div class="modal-body">
                         <p>Select Template:</p>
                         <div class="list-group list-group-flush new-tmp-btn">
                          @foreach($subskeleton as $skeleton)
                          <a href="{{ route('newTemplate', ['type' => 'html','name' => $name, 'skeleton' => $skeleton]) }}" class="list-group-item list-group-item-action">{{ $skeleton }}</a>
                          @endforeach
                         </div>
                        </div>
                        <div class="modal-footer">
                         <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        </div>
                       </div>
                      </div>
                     </div>
                     @endforeach
                     <!-- End modal -->
                  </li>
               @endforeach
           </ul>
        </div>
        
      </div>
   </div>
</div>

<div class="row m-2">
   <div class="col-lg-12 col-md-12">
      <div class="card my-4 table-responsive">
         <div class="card-body">
            <table id="shortCodeTable" class="table table-striped table-bordered w-100 asset-table-list text-center">
               <thead>
                  <tr>
                     <th>Sr</th>
                     <th>Code</th>
                     <th>Description</th>
                     <th>Actions</th>
                  </tr>
               </thead>
               <tbody></tbody>
            </table>
         </div>
        
      </div>
   </div>
</div>

<div class="modal fade" id="create-template-modal" tabindex="-1" role="dialog" aria-labelledby="selectTemplateModalLabel" aria-hidden="true">
 <div class="modal-dialog" role="document">
  <div class="modal-content">
   <div class="modal-header">
    <h5 class="modal-title" id="selectTemplateModalLabel">Create Template</h5>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
    <span aria-hidden="true">&times;</span>
    </button>
   </div>
   <form action="{{ route('saveCustomTemplate')}}" id="saveCustomTemplate" method="post">
      @csrf
      <div class="modal-body">
         <div class="form-group">
            <label class="label-control">Category</label>
            <select class="form-control" name="catid" id="catid">
               @foreach($tem_cats_modal as $cat)
                 <option value="{{$cat->cat_id}}">{{$cat->cat_name}}</option>
               @endforeach
            </select>
         </div>
         <div class="form-group">
            <label class="label-control">Template name</label>
            <input type="text" id="new_template_name" name="template_name" class="form-control">
         </div>
         <div class="form-group">
            <label class="label-control">Subject</label>
            <input type="text" id="temp_subject" name="temp_subject" class="form-control">
         </div>
         <div class="form-group">
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

<div class="modal fade" id="update-template-modal" tabindex="-1" role="dialog" aria-labelledby="selectTemplateModalLabel" aria-hidden="true">
 <div class="modal-dialog" role="document">
  <div class="modal-content">
   <div class="modal-header">
    <h5 class="modal-title">Update Template</h5>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
    <span aria-hidden="true">&times;</span>
    </button>
   </div>
   <form action="{{ route('updateCustomTemplate')}}" id="updateCustomTemplate" method="post">
      @csrf
      <div class="modal-body">
         <div class="form-group">
            <label class="label-control">Template name</label>
            <input type="text" id="up_template_name" name="template_name" class="form-control">
         </div>
         <div class="form-group">
            <label class="label-control">Subject</label>
            <input type="text" id="edit_temp_subject" name="edit_temp_subject" class="form-control">
         </div>
         <div class="form-group">
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

<script type="text/javascript">
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

              notie.alert({ type: 1, text: response.data.message, time: 3 });

              setTimeout(function(){
                  window.location.replace(window.location.href);
              }, 1000);

          } else {
              notie.alert({ type: 'error', text: response.data.message, time: 2 })
              setTimeout(function(){
                  window.location.replace(window.location.href);
              }, 1000);
          }
      })

      .catch(function (error) {
          notie.alert({ type: 'error', text: error, time: 2 })
      });
   });

   $('.update-template').click(function(){
    $("#update_content").val(tinymce.get('update-template_editor').getContent());
      $("#update-template-modal").modal('show');

   });
   $("#updateCustomTemplate").submit(function(e){
      e.preventDefault();
      $("#update-template-modal").modal('hide');
      var postData = {
         catid: $("#upcatid").val(),
         templateid: $("#templateid").val(),
         template_html: $("#update_content").val(),
         templatename: $("#up_template_name").val(),
         temp_subject: $("#edit_temp_subject").val(),
         temp_alert_prefix: $("#edit_temp_prefix").val(),
      }
      axios.post('{{ route('updateCustomTemplate') }}', postData)

      .then(function (response) {

          if (response.data.status == 'ok'){

              notie.alert({ type: 1, text: response.data.message, time: 3 });

              setTimeout(function(){
                  window.location.replace(window.location.href);
              }, 2000);

          } else {
              notie.alert({ type: 'error', text: response.data.message, time: 2 })
          }
      })

      .catch(function (error) {
          notie.alert({ type: 'error', text: error, time: 2 })
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
          if (response.data.status == 'ok'){
               //console.log(response.data.template.template_html);
               $(".update-textarea-div div").remove();
               $("#update-template_editor").val(response.data.template.template_html);
               $("#upidtodisplay").css('display','unset');
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
              notie.alert({ type: 'error', text: response.data.message, time: 2 })
          }
      })

      .catch(function (error) {
          notie.alert({ type: 'error', text: error, time: 2 })
      });
   }
   function deleteTemplate(element){
      if(!confirm('Are you sure to delete')){
         return false;
      }
      var id = $(element).attr('data-id');
      axios.post('{{ route('deleteCustomTemplate') }}',{id:id})

      .then(function (response) {

          if (response.data.status == 'ok'){

              notie.alert({ type: 1, text: response.data.message, time: 3 });

              setTimeout(function(){
                  window.location.replace(window.location.href);
              }, 2000);

          } else {
              notie.alert({ type: 'error', text: response.data.message, time: 2 })
          }
      })

      .catch(function (error) {
          notie.alert({ type: 'error', text: error, time: 2 })
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
notie.alert({ type: 1, text: 'Template deleted', time: 2 });
jQuery('tr#template_item_' + templateSlug).fadeOut('slow');
var tbody = $("#templates_list tbody");
console.log(tbody.children().length);
if (tbody.children().length <= 1) {
location.reload();
}
} else {
notie.alert({ type: 'error', text: 'Template not deleted', time: 2 })
}
})
.catch(function (error) {
notie.alert({ type: 'error', text: error, time: 2 })
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