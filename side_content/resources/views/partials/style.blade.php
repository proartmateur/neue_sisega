<!-- begin::Head -->
<head>
    <meta charset="utf-8" />
    <title>SISEGA</title>
    <meta name="description" content="Latest updates and statistic charts">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no">

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


    <!--begin::Global Theme Styles -->
       <!-- <link href="assets/vendors/base/vendors.bundle.css" rel="stylesheet" type="text/css" />-->

        <!--RTL version:<link href="assets/vendors/base/vendors.bundle.rtl.css" rel="stylesheet" type="text/css" />-->
        <!--<link href="assets/demo/default/base/style.bundle.css" rel="stylesheet" type="text/css" />-->

        <!--RTL version:<link href="assets/demo/default/base/style.bundle.rtl.css" rel="stylesheet" type="text/css" />-->

        <!--end::Global Theme Styles -->

        <!--begin::Page Vendors Styles -->
        <!--<link href="assets/vendors/custom/fullcalendar/fullcalendar.bundle.css" rel="stylesheet" type="text/css" />-->

        <!--RTL version:<link href="assets/vendors/custom/fullcalendar/fullcalendar.bundle.rtl.css" rel="stylesheet" type="text/css" />-->

        <!--end::Page Vendors Styles -->
        <link rel="shortcut icon" href="/assets/demo/default/media/img/logo/FAVICON-SISEGA.png" />
    </head>


    {!! Html::style('assets/vendors/base/vendors.bundle.css') !!}
    {!! Html::style('assets/demo/default/base/style.bundle.css') !!}
    {!! Html::style('assets/vendors/custom/fullcalendar/fullcalendar.bundle.css') !!}
    <!--{!! Html::style('assets/demo/default/media/img/logo/favicon.ico') !!}-->
    {!! Html::style('assets/vendors/custom/datatables/datatables.bundle.css') !!}


    @yield('styles')

<style>
    /*.m-menu__item  :hover{
        background-color: #FFC903;
    }*/

      /* .item  :hover{
        background-color: #FFC903;
    }

    
    .menueee :hover{
        color: #FFC903;
    } */
  

    .start{
        background-color: #CA2424;
    }

   
    #m_header_nav {
        background-color: #CA2424;
    }

    #m_ver_menu {
        background-color: #000000;
    }

    #mtable1_info {
        white-space: pre-wrap;
    }

    #payrolls-table_info {
        white-space: pre-wrap;
    }
 
    /* .m-brand.m-brand--skin-dark {
        background-color: #E8E8E8;
    } */

    #button {
        color: #FFFFFF;
        background-color: #FFC903;
        border-color: #FFC903;
    }


    #btn, #btn-btn {
        color: #FFFFFF;
        background-color: #FFC903;
        border-color: #FFC903;
    }
    
    .dropdown-toggle::after{
        color: #FFFFFF;
    }

</style>