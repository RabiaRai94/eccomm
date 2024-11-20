@php
    $user = Auth::user();
@endphp
<style>
    .nested-dropdowns .show>.dropdown-menu {
        max-width: 200px;
        width: 200px;
    }

    @media (max-width: 768px) {
        .nested-dropdowns .show>.dropdown-menu {
            max-width: 100%;
        }
    }

    .nested-dropdowns li.dropdown-item {
        text-align: left;
    }

    .nested-dropdowns .dropdown-item.show .dropdown-submenu {
        position: relative !important;
        background: transparent !important;
        box-shadow: none !important;
        outline: none !important;
        width: 100%;
    }

    .nested-dropdowns .dropdown-item {
        color: #6967ce;
    }

    .nested-dropdowns .dropdown-item.active,
    .dropdown-item:active {
        background-color: transparent;
        color: #2A2E30 !important;
    }

    .nested-dropdowns .dropdown .dropdown-menu .dropdown-item:active a,
    .dropdown .dropdown-menu .dropdown-item.active a {
        color: #2A2E30 !important;
    }
</style>
<nav class="header-navbar navbar navbar-expand-md navbar-with-menu navbar-without-dd-arrow fixed-top navbar-semi-light">
    <div class="navbar-wrapper">
        <div class="navbar-container content d-flex nested-dropdowns">
            <div class="collapse navbar-collapse show d-flex" id="navbar-mobile">
                <ul class="nav navbar-nav mr-auto">
                    <li class="nav-item mobile-menu d-md-none mr-auto"><a
                            class="nav-link nav-menu-main menu-toggle hidden-xs" href="#"><i
                                class="ft-menu font-large-1"></i></a></li>
                    <li class="nav-item d-none d-md-block">
                        <a class="nav-link nav-menu-main menu-toggle hidden-xs iconExpand" href="#">
                            <svg class="svg-icon" viewBox="0 0 1024 1024" version="1.1"
                                xmlns="http://www.w3.org/2000/svg">
                                <path
                                    d="M880 224H144c-26.5 0-48-21.5-48-48s21.5-48 48-48h736c26.5 0 48 21.5 48 48s-21.5 48-48 48zM144 576h416c26.5 0 48 21.5 48 48s-21.5 48-48 48H144c-26.5 0-48-21.5-48-48s21.5-48 48-48zM144 352h416c26.5 0 48 21.5 48 48s-21.5 48-48 48H144c-26.5 0-48-21.5-48-48s21.5-48 48-48zM880 896H144c-26.5 0-48-21.5-48-48s21.5-48 48-48h736c26.5 0 48 21.5 48 48s-21.5 48-48 48zM917.4 489.4L790.6 362.6c-20.2-20.2-54.6-5.9-54.6 22.6v253.5c0 28.5 34.5 42.8 54.6 22.6l126.7-126.7c12.6-12.5 12.6-32.7 0.1-45.2z"
                                    fill="#ffffff" />
                            </svg>
                        </a>
                    </li>
                </ul>
                <ul class="nav navbar-nav align-items-center">
                    <li class="nav-item d-none d-md-block"><a class="nav-link nav-link-expand" href="#"><i
                                class="ficon ft-maximize" style="font-size: 30px"></i></a></li>
                    <li class="dropdown dropdown-user nav-item">
                        <a class="dropdown-toggle nav-link dropdown-user-link" href="#" data-toggle="dropdown">
                            <span class="avatar avatar-online">
                                <img src="" alt="avatar">
                            </span>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-right">
                            <li class="dropdown-item main-nav d-flex align-items-center">
                                <span class="avatar avatar-online">
                                    <img src="" alt="avatar">
                                </span>
                                <span
                                    class="user-name text-bold-700 ml-1">abc</span>
                            </li>
                            <li class="dropdown-divider m-0"></li>
                            <li class="dropdown-item more-option py-1">
                                <a href="#" class="dropdown-toggle position-relative" data-toggle="dropdown">Hub
                                </a>
                                <ul class="dropdown-menu dropdown-submenu">
                                        <li>
                                            <a class="dropdown-item" href="">Reporting</a>
                                        </li>
                                        <li>
                                            <a class="dropdown-item"
                                                href="">Parking</a>
                                        </li>
                                        <li>
                                            <a class="dropdown-item" href="">Invoicing</a>
                                        </li>
                                
                                        <li>
                                            <a class="dropdown-item" href="">Timesheet</a>
                                        </li>
                                        <li>
                                            <a class="dropdown-item" href="">Human
                                                Resource</a>
                                        </li>
                                        <li>
                                            <a class="dropdown-item" href="">Budget
                                                Tracking</a>
                                        </li>
                                </ul>
                            </li>
                            <li class="dropdown-divider m-0"></li>
                            <li class="dropdown-item  main-nav py-1">
                                <a href="{{ url('') }}"><i class="ft-arrow-left py-1"></i> Landing Page</a>
                            </li>
                            <li class="dropdown-divider m-0"></li>
                            <li class="dropdown-item  main-nav py-1">
                                <a href="{{ route('logout') }}"><i class="ft-power py-1"></i> {{ __('Logout') }}</a>
                            </li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</nav>
