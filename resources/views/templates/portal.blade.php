@php
    $usersBtnActive = false;
    $adminsBtnActive = false;
    $tilesBtnActive = false;
    $reportsBtnActive = false;
    $manualsBtnActive = false;
    $addressesBtnActive = false;
    $productsBtnActive = false;
    $ordersBtnActive = false;
    $reservationsBtnActive = false;
    $selectedCustomer = '';
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
        case 'reports':
            $reportsBtnActive = true;
            break;
        case 'manuals':
            $manualsBtnActive = true;
            break;
        case 'addresses':
            $addressesBtnActive = true;
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
    if(session()->has('selectedClient')) {
        $selectedCustomer = session('selectedClient');
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
    <script src="{{ asset('js/axios.min.js') }}"></script>
    @yield('extra_head')
</head>
<body>
    {{-- {{ session('selectedClient') }} --}}
    @yield('after_body_tag')

    
    @if ((isset($page_manuals) && count($page_manuals)) && !isset($isHomepage))
        <div class="pageHelp">?</div>
        <div class="pageManual"><div></div></div>
    @endif
    
    <div class="gridContainer">
        <header class="logoCell"><img src="{{ asset('statics/hulshoff-logo.png') }}" alt=""></header>
        <div class="breadcrumbsCell">
            {{-- @include('snippets.breadcrumbs', ['breadcrumbs' => ['PRODUCTEN' => '#', 'WERKPLEK' => '#', 'TAFEL' => '#']]) --}}
        </div>
        <div class="basketCell">
            @include('snippets.language')
            @include('snippets.return-order')
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
            @if (count($customers))
            <div class="accClientSelect">
                <select name="customerCode" data-filter-reference="c_code">
                    <option value="" @if('' == $selectedCustomer){{ 'selected' }}@endif>- {{ __('Please select') }} -</option>
                @foreach ($customers as $kcode => $clientName)
                    <option value="{{ $kcode }}" @if($kcode == $selectedCustomer){{ 'selected' }}@endif>{{ ($clientName?$kcode . ' (' . $clientName . ')':$kcode) }}</option>
                @endforeach
                </select>
            </div>
            @endif
            @php
                $productPageRoute = 'products';
                if(isset($tilesDisplay) && $tilesDisplay) {
                    $productPageRoute = 'products_tiles';
                }          
            @endphp
            <div class="accButtons">
                @if(Route::currentRouteName() == 'front')
                <a href="#" class="accBtnHome">Home</a>
                <a href="#" class="accBtnProfile">Profile</a>
                <a href="#" class="accBtnLogout">Logout</a>
                @else
                {{-- <a href="{{ route($productPageRoute) }}" class="accBtnHome">Home</a> --}}
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
                            <li><a href="{{ route('users') }}" @if($usersBtnActive)class="active"@endif>{{ __('Users') }}</a></li>
                            <li><a href="{{ route('admins') }}" @if($adminsBtnActive)class="active"@endif>{{ __('Admins') }}</a></li>
                            <li><a href="{{ route('tiles') }}" @if($tilesBtnActive)class="active"@endif>{{ __('Tiles') }}/{{ __('Productgroups') }}</a></li>
                            <li><a href="{{ route('reports') }}" @if($reportsBtnActive)class="active"@endif>{{ __('Reports') }}</a></li>
                            <li><a href="{{ route('manuals') }}" @if($manualsBtnActive)class="active"@endif>{{ __('Manuals') }}</a></li>
                            <li><a href="{{ route('addresses') }}" @if($addressesBtnActive)class="active"@endif>{{ __('Addresses') }}</a></li>
                        @endif
                        <li><a href="{{ route($productPageRoute) }}" @if($productsBtnActive)class="active"@endif>{{ __('Products') }}</a></li>
                        <li><a href="{{ route('orders') }}" @if($ordersBtnActive)class="active"@endif>{{ __('Orders') }}</a></li>
                        @if(auth()->user()->can_reserve || auth()->user()->is_admin)<li><a href="{{ route('reservations') }}" @if($reservationsBtnActive)class="active"@endif>{{ __('Reservations') }}</a></li>@endif
                    </ul>
                    @else
                    <ul>
                        <li><a href="{{ route('login') }}">{{ __('Login') }}</a></li>
                        <li><a href="{{ url('forgot-password') }}">{{ __('Forgot password') }}</a></li>
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
    @php
        $locationErrors = [];
        foreach($errors->all() as $err) {
            $locationErrors[] = __($err);
        }
    @endphp
    @php $errMsg = '<p>' . implode('</p><p>', $locationErrors) . '</p>'; @endphp
    <script>showMessage('error',"{!! $errMsg !!}")</script>
@endif
@if(session('message'))
    <script>showMessage('success',"{!! session('message') !!}")</script>
@endif
<script>
    const custSel = document.querySelector('select[name=customerCode]');
    const helpMe = document.querySelector('.pageHelp');
    const manualWrap = document.querySelector('.pageManual');
    const accountHomeBtn = document.querySelector('.accBtnHome');
    const languageSelect = document.querySelector('.lanSelect');

    let manuals = {};
    @if (isset($page_manuals))
        @foreach ($page_manuals as $pManual)
            @if(app()->getLocale() == 'nl')
                manuals['{{ $pManual->url }}'] = '{!! str_replace("'", "\'", str_replace(array("\r", "\n"), '', $pManual->text)) !!}';
            @endif
            @if(app()->getLocale() == 'en')
                manuals['{{ $pManual->url }}'] = '{!! str_replace("'", "\'", str_replace(array("\r", "\n"), '', $pManual->text_en)) !!}';
            @endif
        @endforeach
        let currPath = window.location.pathname;
        if(window.location.hash) currPath += window.location.hash;
        if(manuals.hasOwnProperty(currPath)) {
            if(currPath == '/') { // homepage
                document.querySelector('.homeContent').innerHTML = manuals[currPath];
            } else {
                manualWrap.querySelector('div').innerHTML = manuals[currPath];
            }
        }
    @endif

    languageSelect.addEventListener('change', () => {
        // console.log(languageSelect.value);
        let info = {};
        info['newLang'] = languageSelect.value;
        axios.post('{{ url('/ajax/setLanguage') }}', info)
            .then(function (response) {
                // handle success
                // console.log(response.data);
                if(response.data.success == true) {
                    location.reload();
                }
            })
            .catch(function (error) {
                // handle error
                console.log(error);
            })
            .then(function () {
                // always executed
            });
    });

    if(helpMe) {
        helpMe.addEventListener('click', () => {
            if(manualWrap.style.display != "block") {
                manualWrap.style.display = "block";
            } else {
                manualWrap.style.display = "none";
            }
        });
    }

    if(custSel) {
        let previousValue = custSel.value;
        custSel.addEventListener('change', (e) => {
            let addingToReservation = false;
            if (e.isTrusted) {
                /* The event is trusted.. event was generated by a user action */
            } else {
                /* The event is not trusted.. event was created or modified by a script or dispatched via EventTarget.dispatchEvent() */
                // We can be trusted it is fired by the 'add article to reservation'-button
                // dataset item can also be used in this case
                addingToReservation = custSel.dataset.reservationId;
            }
            if(previousValue == '') {
                doSetClient(addingToReservation);
            } else if(custSel.value == previousValue) {
                doSetClient(addingToReservation);
            } else if(confirm("{{ __('You are about to switch customer, your shopping basket will be emptied') }}")) {
                doSetClient(addingToReservation);
            } else {
                custSel.value = previousValue;
            }
        });
    }
    function doSetClient(resId) {
        let info = {};
        info['newClientCode'] = custSel.value;
        info['addProductToReservation'] = resId;
        axios.post('{{ url('/ajax/setClient') }}', info)
            .then(function (response) {
                // handle success
                // console.log(response.data);
                if(response.data.success == true) {
                    if(resId) {
                        // location.href = '/products';
                        // const accountHomeBtn = document.querySelector('.accBtnHome');
                        location.href = accountHomeBtn.href;
                    } else {
                        location.reload();
                    }
                }
            })
            .catch(function (error) {
                // handle error
                console.log(error);
            })
            .then(function () {
                // always executed
            });
    }
</script>
@yield('before_closing_body_tag')
</body>
</html>