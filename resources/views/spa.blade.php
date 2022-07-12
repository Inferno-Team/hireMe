<!DOCTYPE html>
<html>

<head>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="https://cdn.jsdelivr.net/npm/@mdi/font@6.x/css/materialdesignicons.min.css" rel="stylesheet">

    <!-- <link href="{{asset('css/fonts.css')}}" rel="stylesheet"> -->
    <!-- <link href="{{asset('css/materialdesignicons.min.css')}}" rel="stylesheet"> -->
    <link href="node_modules/material-design-icons-iconfont/dist/material-design-icons.css" rel="stylesheet">

    <title>Hire Me</title>
</head>

<body>
    <div id="applicationDiv" style="overflow: hidden;">

        <v-app app>
            <app style="align-items: center;font-family: Poppins;"></app>
        </v-app>
    </div>
    <script src="{{ asset('js/app.js') }}"></script>
</body>

</html>