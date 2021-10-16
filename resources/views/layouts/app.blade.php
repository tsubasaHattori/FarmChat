<!DOCTYPE html>
<html style="height: 100%" lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <!-- Global site tag (gtag.js) - Google Analytics -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=UA-158918923-2"></script>
    <script>
    window.dataLayer = window.dataLayer || [];
    function gtag(){dataLayer.push(arguments);}
    gtag('js', new Date());

    gtag('config', 'UA-158918923-2');
    </script>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', config('app.name'))</title>

    <!-- Scripts -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.20.1/moment.min.js" type="text/javascript"></script>
    <script src="https://cdn.jsdelivr.net/npm/vue-moment@4.1.0/dist/vue-moment.min.js"></script>
    @yield('header_script')

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet" type="text/css">

    <!-- favicon -->
    <link rel="shortcut icon" href="{{ asset('img/favicon.ico') }}">

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link href="{{ asset('css/navbar.css') }}" rel="stylesheet">
    @yield('style')
</head>
<body style="height: 100%">
    <div id="app" style="height: 100%">
        <nav class="navbar navbar-light navbar-laravel">
            <i class="fa fa-bars"></i>
            <a class="nav-app-name" href="{{ url('/') }}">
                <img src="{{ asset('img/logo.png') }}" alt="">
            </a>
            @guest
                <ul class="nav-guest">
                    <li>
                        <a class="nav-link" href="{{ route('login') }}">{{ __('ログイン') }}</a>
                    </li>
                    <li>
                        <a class="nav-link" href="{{ route('register') }}">{{ __('登録') }}</a>
                    </li>
                </ul>
            @else
                <span class="nav-toggler">
                    {{ Auth::user()->name }}<i class="fa fa-caret-down"></i>
                </span>

                <div class="nav-toggle-container hidden">
                    <ul class="nav-toggle-items">
                        <li>
                            <a class="dropdown-item" href="{{ route('account-setting') }}">
                                個人設定
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item" href="{{ route('logout') }}"
                                onclick="event.preventDefault();
                                                document.getElementById('logout-form').submit();">
                                ログアウト
                            </a>
                            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                    @csrf
                            </form>
                        </li>
                    </ul>
                </div>
            @endguest
        </nav>
        <div class="nav-margin"></div>

        <div id="nav-main-contents">
            @auth
                @include('_navbar')
            @else
                <div style="width: 100px;"></div>
            @endauth
            <main>
                @yield('content')
            </main>
        </div>
    </div>

<script src="{{ mix('js/app.js') }}"></script>
@yield('footer_script')
</body>
</html>
