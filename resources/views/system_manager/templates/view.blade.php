<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<style>
    body {
        margin:0px !important;
        padding:0px !important; 
        box-sizing: border-box !important;
    }
</style>
<style>
    {!! $data->my_css !!}
    {!! $data->my_styles !!}
</style>
<body>
{!! $data->template_html !!}
</body>
</html>
