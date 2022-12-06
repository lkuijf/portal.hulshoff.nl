@php
    $name = Route::currentRouteName();
@endphp
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="{{ asset('css/styles.css') }}">
    <title>Hulshoff webportal</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Heebo:wght@400;700&display=swap" rel="stylesheet">
    <script src="{{ asset('js/script.js') }}"></script>
    @yield('extra_head')
</head>
<body>
    @yield('after_body_tag')
    <div class="gridContainer">
        <header class="logoCell"><img src="{{ asset('statics/hulshoff-logo.png') }}" alt=""></header>
        <div class="breadcrumbsCell">
            {{-- @include('snippets.breadcrumbs', ['breadcrumbs' => ['PRODUCTEN' => '#', 'WERKPLEK' => '#', 'TAFEL' => '#']]) --}}
        </div>
        <div class="basketCell">
            @include('snippets.basket')
        </div>
        @if (auth()->user())
        <div class="accountCell">
            <div class="accInfo">
                @if($name == 'front')
                <img src="https://picsum.photos/300/200" alt="">
                <span>ABN AMRO</span>
                @else
                {{-- <img src="https://picsum.photos/300/200" alt=""> --}}
                <span>{{ auth()->user()->name }}</span>
                @endif
            </div>
            <div class="accButtons">
                @if($name == 'front')
                <a href="#" class="accBtnHome">Home</a>
                <a href="#" class="accBtnProfile">Profile</a>
                <a href="#" class="accBtnLogout">Logout</a>
                @else
                <a href="/" class="accBtnHome">Home</a>
                <a href="{{ route('account') }}" class="accBtnProfile">Profile</a>
                @include('auth_hulshoff.logout', ['buttonInside' => ''])
                @endif
            </div>
        </div>
        @endif
        <div class="navigationCell">
            <nav class="mainNav">
                <input type="checkbox" id="burger-check">
                <label for="burger-check" class="burger-label">
                    <span></span>
                    <span></span>
                    <span></span>
                </label>
                @if($name == 'front')
                <ul>
                    <li><a href="#">Item</a></li>
                    <li><a href="#">Item</a></li>
                    <li><a href="#">Item</a></li>
                    <li><a href="#">Item</a></li>
                    <li><a href="#">Item</a></li>
                </ul>
                @else
                    @if (auth()->user())
                        @if (auth()->user()->is_admin)
                        <ul>
                            <li><a href="{{ route('users') }}">Users</a></li>
                            <li><a href="{{ route('admins') }}">Admins</a></li>
                            <li><a href="{{ route('products') }}">Producten</a></li>
                        </ul>
                        @else
                        <ul>
                            <li><a href="{{ route('products') }}">Producten</a></li>
                            {{-- <li><a href="">Geen</a></li>
                            <li><a href="">Admin</a></li> --}}
                        </ul>
                        @endif
                    {{-- @else
                    <ul>
                        <li><a href="{{ route('login') }}">Login</a></li>
                    </ul> --}}
                    @endif
                @endif
            </nav>
        </div>
        <div class="infoCell"><p>Webportal</p></div>
        <div class="contentCell">
            @yield('content')
        </div>
        <footer class="footerCell">
            Footer
        </footer>
    </div>
    @yield('before_closing_body_tag')
</body>
</html>