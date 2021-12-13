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
        <li class="breadcrumb-item"><a href="{{ route('mailableList') }}">Mailables</a></li>
        <li class="breadcrumb-item active" aria-current="page">{{ $resource['name'] }}</li>
      </ol>
    </nav>
             
                <div class="card my-4">
                    <div class="card-header d-flex align-items-center justify-content-between"><h5>Details</h5>
                    </div>
                    <div class="card-body card-bg-secondary">
                        <table class="table mb-0 table-borderless">
                            <tbody>
                                <tr>
                                    <td class="table-fit font-weight-sixhundred">Name</td>
                                    <td>
                                        {{ $resource['name'] }}
                                    </td>
                                </tr>
                                <tr>
                                    <td class="table-fit font-weight-sixhundred">Namespace</td>
                                    <td>
                                        {{ $resource['namespace'] }}
                                    </td>
                                </tr>

                                @if ( !empty($resource['data']->subject) )
				    				<tr>
	                                    <td class="table-fit font-weight-sixhundred">Subject</td>
	                                    <td>
	                                        {{ $resource['data']->subject }}
	                                    </td>
                                	</tr>
				    			@endif

                                @if ( !empty($resource['data']->locale) )
				    				<tr>
	                                    <td class="table-fit font-weight-sixhundred">Locale</td>
	                                    <td>
	                                        {{ $resource['data']->locale }}
	                                    </td>
                                	</tr>
				    			@endif

				    				<tr>
	                                    <td class="table-fit font-weight-sixhundred">From</td>
	                                    <td><a href="mailto:{{ !collect($resource['data']->from)->isEmpty() ? collect($resource['data']->from)->first()['address'] : config('mail.from.address') }}" class="badge badge-info mr-1 font-weight-light">
	                                    	@if (!collect($resource['data']->from)->isEmpty())

                            					{{ collect($resource['data']->from)->first()['address'] }}

                            					@else
											
												{{ config('mail.from.address') }} (default)

                            				@endif
                        				</a></td>
                                	</tr>

                                	<tr>
	                                    <td class="table-fit font-weight-sixhundred">Reply To</td>
	                                    <td><a href="mailto:{{ !collect($resource['data']->replyTo)->isEmpty() ? collect($resource['data']->replyTo)->first()['address'] : config('mail.reply_to.address') }}" class="badge badge-info mr-1 font-weight-light">
	                                    	@if (!collect($resource['data']->replyTo)->isEmpty())

                            					{{ collect($resource['data']->replyTo)->first()['address'] }}

                            					@else
											
												{{ config('mail.reply_to.address') }} (default)

                            				@endif
                        				</a></td>

                                	</tr>

                                @if ( !empty($resource['data']->cc) )
                                <tr>
                                    <td class="table-fit font-weight-sixhundred">cc</td>
                                    <td>
                                    	@foreach( $resource['data']->cc as $cc )
                                        <a href="mailto:{{ $cc['address'] }}" class="badge badge-info mr-1 font-weight-light">{{ $cc['address'] }}</a>
                                        @endforeach
                                    </td>
                                </tr>
                                @endif
                                @if ( !empty($resource['data']->bcc) )
                                <tr>
                                    <td class="table-fit font-weight-sixhundred">bcc</td>
                                    <td>
                                    	@foreach( $resource['data']->bcc as $bcc )
                                        <a href="mailto:{{ $bcc['address'] }}" class="badge badge-info mr-1 font-weight-light">{{ $bcc['address'] }}</a>
                                        @endforeach
                                    </td>
                                </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="card my-4">
                    <div class="card-header d-flex align-items-center justify-content-between"><h5>Preview</h5>
                    	@if ( !is_null($resource['view_path']) )
                    		<a class="btn btn-primary" href="{{ route('editMailable', ['name' => $resource['name']]) }}">Edit Template</a>
                    	@endif
                    	
                    </div>
                    <div class="embed-responsive embed-responsive-16by9">
					  <iframe class="embed-responsive-item" src="{{ route('previewMailable', [ 'name' => $resource['name'] ]) }}" allowfullscreen></iframe>
					</div>
                </div>
            </div>

<script type="text/javascript">

                
</script>
   
@endsection