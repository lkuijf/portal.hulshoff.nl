<div class="accountHomeContent">
    <h1>Account</h1>
    <p>Welkom <em>{{ auth()->user()->name }}</em></p>
    @include('auth_hulshoff.logout')
    @if (auth()->user()->is_admin)
    <ul>
        <li><a href="">Users</a></li>
        <li>Meer</li>
        <li>Informatie</li>
    </ul>
    @else
    no admin
    @endif
    <ul>
        <li>Binnenkort</li>
        <li>Meer</li>
        <li>Informatie</li>
    </ul>

    {{-- @if (auth()->user())
        yes
    @else
        no
    @endif --}}

    <h2>Two Factor Authentication</h2>
    @if (auth()->user()->two_factor_confirmed_at)
        <form action="/user/two-factor-authentication" method="POST">
            @method('DELETE')
            @csrf
            <button type="submit">DISABLE Two Factor Authentication </button>
        </form>
    @else
        <form action="/user/two-factor-authentication" method="POST">
            @csrf
            <button type="submit">Enable Two Factor Authentication </button>
        </form>
    @endif
    @if (session('status') == 'two-factor-authentication-enabled')
        <p>Please finish configuring two factor authentication below.</p>
        {!! auth()->user()->twoFactorQrCodeSvg() !!}
        <form action="/user/confirmed-two-factor-authentication" method="POST">
            @csrf
            <input type="text" name="code">
            <button type="submit">Submit authentication code</button>
        </form>
    @endif
    @if (session('status') == 'two-factor-authentication-disabled')
        <p>2Fa is DISABLED</p>
    @endif
    @if (session('status') == 'two-factor-authentication-confirmed')
        <p>Two factor authentication confirmed and enabled successfully.</p>
        <p>Below you find the recovery codes in case you lose access to your mobile device</p>
        <ul>
            @foreach ((array)auth()->user()->recoveryCodes() as $code)
            <li>{{ $code }}</li>
            @endforeach
        </ul>
    @endif
</div>