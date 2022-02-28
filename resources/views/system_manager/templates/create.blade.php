<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="description" content="">
    <meta name="author" content="">
    <meta name="_token" content="{{csrf_token()}}" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Template Builder</title>
    @php
        $file_path = Session::get('is_live') == 1 ? 'public/' : '/';
        $path = Session::get('is_live') == 1 ? 'public/system_files/' : 'system_files/';
    @endphp
    <link rel="stylesheet" type="text/css" href="{{asset( $file_path . 'app-assets/css/bootstrap.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset( $file_path . 'app-assets/vendors/css/extensions/toastr.min.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset( $file_path . 'app-assets/css/plugins/extensions/ext-component-toastr.css')}}">

    <link rel="stylesheet" href="{{asset($file_path . 'grapes/grapes.css')}}">
    <link rel="stylesheet" href="{{asset($file_path . 'grapes/grapes2.css')}}">
    <link rel="stylesheet" href="{{asset($file_path . 'grapes/news.css')}}">
    
    
    <script type="text/javascript" src="{{asset($file_path . 'grapes/grapes.js')}}"></script>
    <script type="text/javascript" src="{{asset($file_path . 'grapes/grapes2.js')}}"></script>
    <script type="text/javascript" src="{{asset($file_path . 'grapes/news.js')}}"></script>
    
    
    
    <style>
        body {
            margin: 0;
            padding: 0;
        }
        html {
            overflow: scroll;
            overflow-x: hidden;
            -ms-overflow-style: none;
            scrollbar-width: none;
        }
        ::-webkit-scrollbar {
            display: none;
        }
    </style>
</head>
<body>
    <div class="container-fluid p-0 m-0">

        <div id="editor"> </div>

        <div class="row bg-light border" style="position: fixed; width:120%; bottom: 0px; z-index:9999;padding:4px;">
            <div class="col-md-5">
                <input type="text" id="templateName" class="form-control" value="{{$template->name}}" >
            </div>
            <div class="col-md-3">
                <button class="btn btn-primary waves-effect loadingBtn" style="display:none" type="button" disabled="">
                    <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                    <span class="ms-25 align-middle">Updating...</span>
                </button>
                <button class="btn btn-primary saveBtn" type="button" onclick="getContent()"> Update </button>
                <a href="{{route('templateList')}}" class="btn btn-danger"> Cancel & Go Back </a>
            </div>
        </div>

    </div>

    
    
    

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="{{asset($file_path . 'app-assets/vendors/js/extensions/toastr.min.js')}}"></script>
    <script src="{{asset($file_path . 'grapes/custom_code.js')}}"></script>

    <script>

        let temp_html = {!! json_encode($template->template_html) !!};
        let temp_css = {!! json_encode($template->my_css) !!};
        let temp_components = {!! json_encode($template->components) !!};
        let temp_styles = {!! json_encode($template->my_styles) !!};

        const LandingPage = {
            html: temp_html,
            css: temp_css,
            components: temp_components,
            style: temp_styles,
        };
     
        const editor = grapesjs.init({
            container : "#editor",
            fromElement : false,
            components: LandingPage.html,
            style: LandingPage.css,
            width: "auto", 
            storageManager : false,
            plugins : ["gjs-preset-newsletter","grapesjs-custom-code"],
            pluginsOpts : {
                'gjs-preset-newsletter': { },
            },
            storageManager : {
                type : 'remote' ,
                stepsBeforeSave : 3 ,
                contentTypeJson : true ,
                storeComponents : true ,
                storeStyles : true ,
                storeHtml : true ,
                storeCss : true ,
                id : 'my_',
                autosave: false,
                autoload: false,
                urlStore : "{{route('updateTemp')}}",
                urlLoad : "{{route('updateTemp')}}",
                params: { id: "{{$template->id}}" , templateName : $("#templateName").val() },
                headers : {
                    'Content-Type' : 'application/json',
                    "X-CSRF-TOKEN": "{{csrf_token()}}",
                }
            }
        });

        function getContent() {
            $('.loadingBtn').show();
            $('.saveBtn').hide();
            
            editor.store(res => {
                if(res.status_code == 200 && res.success == true) {
                    toastr.success( res.message , { timeOut: 5000 });
                    $('.loadingBtn').hide();
                    $('.saveBtn').show();
                }else{
                    $('.loadingBtn').hide();
                    $('.saveBtn').show();
                }
            });
        }
    </script>
</body>
</html>