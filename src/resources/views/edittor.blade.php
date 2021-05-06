<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="csrf-token" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Item</title>
    {{-- <link href="{{ asset('css/app.css') }}" rel="stylesheet" /> --}}
    {{-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" integrity="sha512-vKMx8UnXk60zUwyUnUPM3HbQo8QfmNx7+ltw8Pm5zLusl1XIfwcxo8DbWCqMGKaWeNxWA8yrx5v3SaVpMvR3CA==" crossorigin="anonymous" /> --}}

    <style>
        * {
            margin: 0;
            padding: 0;
        }
    </style>
    <script>
        window.editorConfig = @json($editorConfig ?? [])
    </script>
</head>

<body>
    <div id="loader" style="left: 0;top:0;background-color:white;opacity:0.7;position:absolute;align-items:center;justify-content:center;width:100%;height:100%;font-size:36pt;display:flex;z-index:100;">
        <i class="fa fa-spinner fa-spin"></i>
    </div>
    <div id="{{ str_replace('#', '', $editorConfig->container ?? 'editor') }}"></div>
    <script src="{{ asset('grapesjs/editor.js') }}"></script>
</body>
</html>