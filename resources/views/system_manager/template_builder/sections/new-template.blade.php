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
@if ( request()->route()->getName() === 'newTemplate' ||
request()->route()->getName() === 'editMailable' ||
request()->route()->getName() === 'viewTemplate')
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
@endif
@endsection
@section('body-content')
<div class="col-lg-10 col-md-12">
 <nav aria-label="breadcrumb">
  <ol class="breadcrumb">
   <li class="breadcrumb-item"><a href="{{ route('templateList') }}">Templates</a></li>
   <li class="breadcrumb-item active" aria-current="page">Add Template</li>
  </ol>
 </nav>
 <div class="card mb-3">
  <ul class="nav nav-pills" id="pills-tab" role="tablist">
   <li class="nav-item">
    <a class="nav-link active" id="pills-html-tab" data-toggle="pill" href="#pills-html" role="tab" aria-controls="pills-html" aria-selected="true">HTML</a>
   </li>
   <li class="nav-item">
    <a class="nav-link" id="pills-markdown-tab" data-toggle="pill" href="#pills-markdown" role="tab" aria-controls="pills-markdown" aria-selected="false">Markdown</a>
   </li>
  </ul>
 </div>
 <div class="card">
  <div class="card-body">
   <div class="tab-content" id="pills-tabContent">
    <div class="tab-pane fade show active" id="pills-html" role="tabpanel" aria-labelledby="pills-html-tab">
     <div class="card-columns">
      @foreach( $skeletons->get('html') as $name => $subskeleton )
      <div class="card">
       <div class="content template-item" data-toggle="modal" data-target="#select{{ $name }}Modal">
        <div class="content-overlay"></div>
       <!--  @if ( file_exists( public_path("vendor/maileclipse/images/skeletons/html/{$name}.png") ) )
        <img class="content-image card-img-top" src="{{ asset('public/vendor/maileclipse/images/skeletons/html/'.$name.'.png' ) }}" alt="{{ $name }}">
        @elseif( file_exists( public_path( "vendor/maileclipse/images/skeletons/html/{$name}.jpg" ) ) )
        <img class="content-image card-img-top" src="{{ asset('public/vendor/maileclipse/images/skeletons/html/'.$name.'.jpg' ) }}" alt="{{ $name }}">
        @else
        <img class="content-image card-img-top" src="{{ asset('public/vendor/maileclipse/images/skeletons/no-image.png' ) }}" alt="{{ $name }}">
        @endif -->
        <div class="content-details">
         <h4 class="content-title mb-3">{{ $name }}</h4>
         <!-- <p class="content-text">This is a short description</p> -->
        </div>
       </div>
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
          <div class="list-group list-group-flush">
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
      @endforeach
     </div>
    </div>
    <div class="tab-pane fade" id="pills-markdown" role="tabpanel" aria-labelledby="pills-markdown-tab">
     <div class="card-columns">
      <!-- markdown -->
      @foreach( $skeletons->get('markdown') as $name => $subskeleton )
      <div class="card">
       <!-- <img class="card-img-top" src="https://1rj8i398ld62y6ih02fyvv4k-wpengine.netdna-ssl.com/wp-content/uploads/2018/12/mantra-welcome.png" alt="Card image cap"> -->
       <div class="content template-item" data-toggle="modal" data-target="#{{ $name }}Modal">
        <div class="content-overlay"></div>
        @if ( file_exists( public_path("vendor/maileclipse/images/skeletons/markdown/{$name}.png") ) )
        <img class="content-image card-img-top" src="{{ asset('public/vendor/maileclipse/images/skeletons/markdown/'.$name.'.png' ) }}" alt="{{ $name }}">
        @elseif( file_exists( public_path( "vendor/maileclipse/images/skeletons/markdown/{$name}.jpg" ) ) )
        <img class="content-image card-img-top" src="{{ asset('public/vendor/maileclipse/images/skeletons/markdown/'.$name.'.jpg' ) }}" alt="{{ $name }}">
        @else
        <img class="content-image card-img-top" src="{{ asset('public/vendor/maileclipse/images/skeletons/no-image.png' ) }}" alt="{{ $name }}">
        @endif
        <div class="content-details">
         <h4 class="content-title mb-3">{{ $name }}</h4>
        </div>
       </div>
      </div>
      <!-- Modal -->
      <div class="modal fade" id="{{ $name }}Modal" tabindex="-1" role="dialog" aria-labelledby="selectTemplateModalLabel" aria-hidden="true">
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
          <div class="list-group list-group-flush">
           @foreach($subskeleton as $skeleton)
           <a href="{{ route('newTemplate', ['type' => 'markdown','name' => $name, 'skeleton' => $skeleton]) }}" class="list-group-item list-group-item-action">{{ $skeleton }}</a>
           @endforeach
          </div>
         </div>
         <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
         </div>
        </div>
       </div>
      </div>
      <!-- End modal -->
      @endforeach
     </div>
    </div>
   </div>
  </div>
 </div>
</div>
@endsection