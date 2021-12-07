<!DOCTYPE html>

<html lang="es">

<head>
    <meta charset="utf-8" />
    <title>Iniciar sesión</title>
    <meta name="description" content="Latest updates and statistic charts">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no">


@include('partials.style')

<!--begin::Web font -->
    <script src="https://ajax.googleapis.com/ajax/libs/webfont/1.6.16/webfont.js"></script>
    <script>
        WebFont.load({
            google: {"families":["Poppins:300,400,500,600,700","Roboto:300,400,500,600,700"]},
            active: function() {
                sessionStorage.fonts = true;
            }
        });
    </script>
</head>

<body class="m--skin- m-header--fixed m-header--fixed-mobile m-aside-left--enabled m-aside-left--skin-dark m-aside-left--fixed m-aside-left--offcanvas m-footer--push m-aside--offcanvas-default">
<!-- begin:: Page -->
<div class="m-grid m-grid--hor m-grid--root m-page">
    <div class="m-grid__item m-grid__item--fluid m-grid m-grid--hor m-login m-login--signin m-login--2 m-login-2--skin-2" id="m_login" style="background-image: url(../../../assets/app/media/img//bg/sisega_bg.jpg); background-repeat:no-repeat;
    background-size:cover;">
        <div class="m-grid__item m-grid__item--fluid	m-login__wrapper">

            @yield('content')
        </div>
    </div>
</div>
</div>

@include('partials.scripts')

<!--end:: Global Optional Vendors -->

<!--begin::Global Theme Bundle -->
{!! Html::script('assets/demo/base/scripts.bundle.js') !!}

<!--end::Global Theme Bundle -->

<!--begin::Page Scripts -->
{{--{!! Html::script('assets/snippets/custom/pages/user/login.js') !!}--}}
</body>
</html>