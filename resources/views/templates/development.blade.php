<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hulshoff - Web portal</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/dev.css') }}">
</head>
<body>
    <div class="container">
        <div>
            <div class="logoWrap"><img src="{{ asset('statics/hulshoff-logo.png') }}" alt=""></div>
            @if (isset($data['result']))
            <div class="messageWrap">
                <span>{{ $data['result'] }}</span><a href="{{ route('parseXml_Index') }}">[Close message]</a>
            </div>
            @endif
            <div class="textWrap">
                {{-- {!! $data['content'] !!} --}}
                @if (isset($data['include_view']))
                    @include($data['include_view'])
                @endif
                @yield('content')
            </div>
        </div>
    </div>
</body>
</html>