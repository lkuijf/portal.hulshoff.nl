<ul>
    <li>a</li>
    <li>2</li>
</ul>

@if (auth()->user())
    {{-- @php
        dd(auth()->user());
    @endphp --}}
    yes
@else
    no
@endif


<form action="/user/two-factor-authentication" method="post">
    @csrf
    <button type="submit">Enable Two Factor Authentication </button>
</form>
@if (session('status') == 'two-factor-authentication-enabled')
    <p>Please finish configuring two factor authentication below.</p>
    {!! auth()->user()->twoFactorQrCodeSvg() !!}
    <form action="/user/confirmed-two-factor-authentication" method="post">
        @csrf
        <input type="text" name="code">
        <button type="submit">Submit authentication code</button>
    </form>
@endif
@if (session('status') == 'two-factor-authentication-confirmed')
    <p>Two factor authentication confirmed and enabled successfully.</p>
    {{-- <ul>
        @foreach ((array)auth()->user()->recoveryCodes() as $code)
        <li>{{ $code }}</li>
        @endforeach
    </ul> --}}
@endif