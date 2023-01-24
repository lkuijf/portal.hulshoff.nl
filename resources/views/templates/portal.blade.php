@php
    $usersBtnActive = false;
    $adminsBtnActive = false;
    $tilesBtnActive = false;
    $productsBtnActive = false;
    $ordersBtnActive = false;
    $reservationsBtnActive = false;
    switch (Route::currentRouteName()) {
        case 'users': case 'new_user': case 'user_detail':
            $usersBtnActive = true;
            break;
        case 'admins': case 'new_admin': case 'admin_detail':
            $adminsBtnActive = true;
            break;
        case 'tiles':
            $tilesBtnActive = true;
            break;
        case 'products': case 'product_detail':
            $productsBtnActive = true;
            break;
        case 'orders': case 'order_detail':
            $ordersBtnActive = true;
            break;
        case 'reservations': case 'reservation_detail':
            $reservationsBtnActive = true;
            break;
    }
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
    <meta name="_token" content="{{ csrf_token() }}">
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
                @if(Route::currentRouteName() == 'front')
                <img src="https://picsum.photos/300/200" alt="">
                <span>ABN AMRO</span>
                @else
                {{-- <img src="https://picsum.photos/300/200" alt=""> --}}
                <span>{{ auth()->user()->name }}</span>
                @endif
            </div>
            <div class="accButtons">
                @if(Route::currentRouteName() == 'front')
                <a href="#" class="accBtnHome">Home</a>
                <a href="#" class="accBtnProfile">Profile</a>
                <a href="#" class="accBtnLogout">Logout</a>
                @else
                <a href="{{ route('products') }}" class="accBtnHome">Home</a>
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
                @if(Route::currentRouteName() == 'front')
                <ul>
                    <li><a href="#">Item</a></li>
                    <li><a href="#">Item</a></li>
                    <li><a href="#">Item</a></li>
                    <li><a href="#">Item</a></li>
                    <li><a href="#">Item</a></li>
                </ul>
                @else
                    @if (auth()->user())
                    <ul>
                        @if (auth()->user()->is_admin)
                            <li><a href="{{ route('users') }}" @if($usersBtnActive)class="active"@endif>Users</a></li>
                            <li><a href="{{ route('admins') }}" @if($adminsBtnActive)class="active"@endif>Admins</a></li>
                            <li><a href="{{ route('tiles') }}" @if($tilesBtnActive)class="active"@endif>Tiles</a></li>
                        @endif
                        <li><a href="{{ route('products') }}" @if($productsBtnActive)class="active"@endif>Products</a></li>
                        <li><a href="{{ route('orders') }}" @if($ordersBtnActive)class="active"@endif>Orders</a></li>
                        @if(auth()->user()->can_reserve || auth()->user()->is_admin)<li><a href="{{ route('reservations') }}" @if($reservationsBtnActive)class="active"@endif>Reservations</a></li>@endif
                    </ul>
                    @else
                    <ul>
                        <li><a href="{{ route('login') }}">Login</a></li>
                    </ul>
                    @endif
                @endif
            </nav>
        </div>
        <div class="infoCell"><p>Webportal</p></div>
        <div class="contentCell">
            @yield('content')
        </div>
        <footer class="footerCell">
            <p><a href="tel:09003456666">0900 345 6666</a></p>
            <p>&copy; {{ date('Y') }} - Hulshoff</p>
        </footer>
    </div>
@if ($errors->any())
    @php $errMsg = '<p>' . implode('</p><p>', $errors->all()) . '</p>'; @endphp
    <script>showMessage('error','{!! $errMsg !!}')</script>
@endif
@if(session('message'))
    <script>showMessage('success','{!! session('message') !!}')</script>
@endif
@yield('before_closing_body_tag')
</body>
</html>