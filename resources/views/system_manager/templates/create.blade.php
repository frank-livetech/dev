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
    <script type="text/javascript" src="{{asset($file_path . 'grapes/grapes.js')}}"></script>
    <script type="text/javascript" src="{{asset($file_path . 'grapes/news.js')}}"></script>
    <link rel="stylesheet" href="{{asset($file_path . 'grapes/grapes.css')}}">
    <link rel="stylesheet" href="{{asset($file_path . 'grapes/news.css')}}">
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
    <form action="">
        <input type="hidden" value="{{$id}}" name="temp_id">
    </form>

    <div id="editor">

    </div>
    
    
    <script>
     
        const editor = grapesjs.init({
            container : "#editor",
            fromElement : true,
            width: "auto", 
            storageManager : false,
            plugins : ["gjs-preset-newsletter"],
            pluginsOpts : {
                "gjs-preset-newsletter" : { },
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
                urlStore : "{{route('updateTemp')}}",
                urlLoad : "{{route('updateTemp')}}",
                params: { id: "{{$id}}" },
                headers : {
                    'Content-Type' : 'application/json',
                    "X-CSRF-TOKEN": "{{csrf_token()}}",
                }
            }
        });
    </script>
</body>
</html>