<header id="m_header" class="m-grid__item    m-header " m-minimize-offset="200" m-minimize-mobile-offset="200">
    <div class="m-container m-container--fluid m-container--full-height">
        <div class="m-stack m-stack--ver m-stack--desktop">

            <!-- BEGIN: Brand -->
            <div class="m-stack__item m-brand  m-brand--skin-dark ">
                <div class="m-stack m-stack--ver m-stack--general">
                    <div class="m-stack__item m-stack__item--middle m-brand__logo">
                        <a href="{{URL::to('/')}}" class="m-brand__logo-wrapper">
                            <img alt="" style="margin-left:-29px" src="../../assets/demo/default/media/img/logo/LogoSISEGA.png"/>
                        </a>
                    </div>
                    <div class="m-stack__item m-stack__item--middle m-brand__tools">

                        <!-- BEGIN: Left Aside Minimize Toggle -->
                        <a href="javascript:;" id="m_aside_left_minimize_toggle" class="m-brand__icon m-brand__toggler m-brand__toggler--left m--visible-desktop-inline-block  ">
                            <span></span>
                        </a>

                        <!-- END -->

                        <!-- BEGIN: Responsive Aside Left Menu Toggler -->
                        <a href="javascript:;" id="m_aside_left_offcanvas_toggle" class="m-brand__icon m-brand__toggler m-brand__toggler--left m--visible-tablet-and-mobile-inline-block">
                            <span></span>
                        </a>

                        <!-- END -->

                        <!-- BEGIN: Responsive Header Menu Toggler -->
                        {{--<a id="m_aside_header_menu_mobile_toggle" href="javascript:;" class="m-brand__icon m-brand__toggler m--visible-tablet-and-mobile-inline-block">
                            <span></span>
                        </a>--}}

                        <!-- END -->

                        <!-- BEGIN: Topbar Toggler -->
                        <a id="m_aside_header_topbar_mobile_toggle" href="javascript:;" class="m-brand__icon m--visible-tablet-and-mobile-inline-block">
                            <i class="flaticon-more"></i>
                        </a>

                        <!-- BEGIN: Topbar Toggler -->
                    </div>
                </div>
            </div>

            <!-- END: Brand -->
            <div class="m-stack__item m-stack__item--fluid m-header-head" id="m_header_nav">

                <!-- BEGIN: Horizontal Menu -->
            {{--<button class="m-aside-header-menu-mobile-close  m-aside-header-menu-mobile-close--skin-dark " id="m_aside_header_menu_mobile_close_btn"><i class="la la-close"></i></button>--}}

            <!-- END: Horizontal Menu -->

                <!-- BEGIN: Topbar -->
                <div id="m_header_topbar" class="m-topbar  m-stack m-stack--ver m-stack--general m-stack--fluid">
                    <div class="m-stack__item m-topbar__nav-wrapper">
                        {{--<ul class="m-topbar__nav m-nav m-nav--inline">--}}
                        {{--<li class="m-nav__item m-topbar__user-profile m-topbar__user-profile--img  m-dropdown m-dropdown--medium m-dropdown--arrow m-dropdown--header-bg-fill m-dropdown--align-right m-dropdown--mobile-full-width m-dropdown--skin-light"--}}
                        {{--m-dropdown-toggle="click">--}}
                        {{--<a href="#" class="m-nav__link m-dropdown__toggle">--}}
                        {{--<span class="m-topbar__userpic">--}}
                        {{--<img src="assets/app/media/img/users/user4.jpg" class="m--img-rounded m--marginless" alt="" />--}}
                        {{--</span>--}}
                        {{--<span class="m-topbar__username m--hide">Nick</span>--}}
                        {{--</a>--}}

                        {{--<div class="m-dropdown__wrapper">--}}
                        {{--<span class="m-dropdown__arrow m-dropdown__arrow--right m-dropdown__arrow--adjust"></span>--}}
                        {{--<div class="m-dropdown__inner">--}}
                        {{--<div class="m-dropdown__header m--align-center" style="background: url(assets/app/media/img/misc/user_profile_bg.jpg); background-size: cover;">--}}
                        {{--<div class="m-card-user m-card-user--skin-dark">--}}
                        {{--<div class="m-card-user__pic">--}}
                        {{--<img src="assets/app/media/img/users/user4.jpg" class="m--img-rounded m--marginless" alt="" />--}}

                        {{--<!----}}
                        {{--<span class="m-type m-type--lg m--bg-danger"><span class="m--font-light">S<span><span>--}}
                        {{---->--}}
                        {{--</div>--}}
                        {{--<div class="m-card-user__details">--}}

                        {{--<span class="m-card-user__name m--font-weight-500">{{ Auth::user()->name }}</span>--}}
                        {{--<a href="" class="m-card-user__email m--font-weight-300 m-link">mark.andre@gmail.com</a>--}}
                        {{--</div>--}}
                        {{--</div>--}}
                        {{--</div>--}}
                        {{--<div class="m-dropdown__body">--}}
                        {{--<div class="m-dropdown__content">--}}
                        {{--<ul class="m-nav m-nav--skin-light">--}}
                        {{--<li class="m-nav__section m--hide">--}}
                        {{--<span class="m-nav__section-text">Section</span>--}}
                        {{--</li>--}}

                        {{--<li class="m-nav__item">--}}
                        {{--<a href="snippets/pages/user/login-1.html" class="btn m-btn--pill    btn-secondary m-btn m-btn--custom m-btn--label-brand m-btn--bolder">Logout</a>--}}
                        {{--</li>--}}
                        {{--</ul>--}}
                        {{--</div>--}}
                        {{--</div>--}}
                        {{--</div>--}}
                        {{--</div>--}}
                        {{--</li>--}}

                        {{--</ul>--}}

                        <ul class="m-nav m-nav--skin-light" style="margin-top: 15px">
                            <li class="btn m-btn--pill    btn-secondary m-btn m-btn--custom m-btn--label-brand m-btn--bolder " id="btn">
                                <a href="javascript:;" class="dropdown-toggle" data-toggle="dropdown" data-hover="dropdown" data-close-others="true" >
                                    <span class="username username-hide-on-mobile" id="btn-btn"> {!!Auth::user()->name!!} </span>
                                    <!-- <i class="fa fa-angle-down" style="color: #FFFFFF"></i> -->
                                </a>
                                <ul class="dropdown-menu dropdown-menu-default" >
                                    <li>
                                        <a href="{{ url('/logout') }}" role="button" tabindex="0" style="text-indent:1px;" onclick="event.preventDefault();
                                    document.getElementById('logout-form').submit();">
                                            <i class="icon-key" style="color: #5867dd"></i> Cerrar sesión
                                        </a>
                                        <form id="logout-form" action="{{ url('/logout') }}" method="POST" style="display: none;">
                                            {{ csrf_field() }}
                                        </form>
                                    </li>
                                </ul>
                            </li>
                        </ul>
                    </div>
                </div>
                <!-- END: Topbar -->
            </div>
        </div>
    </div>
</header>
