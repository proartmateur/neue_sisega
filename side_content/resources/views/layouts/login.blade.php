<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Sisega') }}</title>

    <!-- Scripts -->
<!--<script src="{{ asset('js/app.js') }}" defer></script>-->

    <!-- Fonts -->
    <!--begin::Web font -->z
    {{--<script src="https://ajax.googleapis.com/ajax/libs/webfont/1.6.16/webfont.js"></script>--}}
    {!! Html::script('https://ajax.googleapis.com/ajax/libs/webfont/1.6.16/webfont.js') !!}
    <script>
        WebFont.load({
            google: {"families":["Poppins:300,400,500,600,700","Roboto:300,400,500,600,700"]},
            active: function() {
                sessionStorage.fonts = true;
            }
        });
    </script>
    <!--end::Web font -->

    <!-- Styles -->
    <!--begin::Global Theme Styles -->
    {{--<link href="assets/vendors/base/vendors.bundle.css" rel="stylesheet" type="text/css" />--}}
    {!! Html::style('assets/vendors/base/vendors.bundle.css') !!}

<!--RTL version:<link href="assets/vendors/base/vendors.bundle.rtl.css" rel="stylesheet" type="text/css" />-->
    {{--<link href="assets/demo/default/base/style.bundle.css" rel="stylesheet" type="text/css" />--}}
    {!! Html::style('assets/demo/default/base/style.bundle.css') !!}

<!--RTL version:<link href="assets/demo/default/base/style.bundle.rtl.css" rel="stylesheet" type="text/css" />-->

    <!--end::Global Theme Styles -->

    <!--end::Page Vendors Styles -->
    {{--<link rel="shortcut icon" href="assets/demo/default/media/img/logo/favicon.ico" />--}}
{{--    {!! Html::style('assets/demo/default/media/img/logo/favicon.ico') !!}--}}
</head>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<body class="m--skin- m-header--fixed m-header--fixed-mobile m-aside-left--enabled m-aside-left--skin-dark m-aside-left--fixed m-aside-left--offcanvas m-footer--push m-aside--offcanvas-default">
@yield('content')

<!--begin::Global Theme Bundle -->
{{--<script src="assets/vendors/base/vendors.bundle.js" type="text/javascript"></script>--}}
{{--<script src="assets/demo/default/base/scripts.bundle.js" type="text/javascript"></script>--}}
{!! Html::script('assets/vendors/base/vendors.bundle.js') !!}
{!! Html::script('assets/demo/default/base/scripts.bundle.js') !!}

<!--end::Global Theme Bundle -->

<!--begin::Page Scripts -->
<!--<script src="assets/snippets/custom/pages/user/login.js" type="text/javascript"></script>-->

<!--end::Page Scripts -->
</body>
</html>
