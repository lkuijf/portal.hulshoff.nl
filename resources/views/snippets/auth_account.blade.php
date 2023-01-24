<div class="accountHomeContent">
    <h1>Welkom <em>{{ auth()->user()->name }}</em></h1>
    <p>{{ auth()->user()->email }}
        @if (auth()->user()->email_verified_at)
        (e-mail adres geverifiëerd)
        @else
        <a href="{{ url('email/verify') }}">Verifiëren</a>
        @endif
    </p>
    {{-- <h1>Account</h1> --}}
    {{-- @include('auth_hulshoff.logout', ['buttonInside' => 'Logout']) --}}
    @if (auth()->user()->is_admin)
    <p>Als administrator bent u gemachtigd om gebruikers te bewerken.</p>
    @else
    <p>Bekijk de beschikbare producten.</p>
    @endif

    {{-- @if (auth()->user())
        yes
    @else
        no
    @endif --}}
    <div class="twofaWrap">
    <h2>Two Factor Authentication</h2>
    
    @if (auth()->user()->two_factor_confirmed_at)
        <p><em>Two Factor Authentication</em> is geactiveerd.</p>
        <form action="{{ url('user/two-factor-authentication') }}" method="POST">
            @method('DELETE')
            @csrf
            <button type="submit">DISABLE Two Factor Authentication </button>
        </form>
    @else
        <p>Het wordt aangeraden om gebruik te maken van <em>Two Factor Authentication</em></p>
        <form action="{{ url('user/two-factor-authentication') }}" method="POST">
            @csrf
            <button type="submit">Enable Two Factor Authentication </button>
        </form>
    @endif
    @if (session('status') == 'two-factor-authentication-enabled')
        <p>Please finish configuring two factor authentication below.</p>
        {!! auth()->user()->twoFactorQrCodeSvg() !!}
        <form action="{{ url('user/confirmed-two-factor-authentication') }}" method="POST">
            @csrf
            <input type="text" name="code">
            <button type="submit">Submit authentication code</button>
        </form>
    @endif
    @if (session('status') == 'two-factor-authentication-disabled')
        {{-- <p><em>Two Factor Authentication</em> has been disabled.</p> --}}
        <script>showMessage('success','<p><em>Two Factor Authentication</em> has been disabled.</p>')</script>
    @endif
    @if (session('status') == 'two-factor-authentication-confirmed')
        {{-- <p><em>Two factor authentication</em> confirmed and enabled successfully.</p> --}}
        <script>showMessage('success','<p><em>Two factor authentication</em> confirmed and enabled successfully.</p>')</script>
        <p>Below you find the recovery codes in case you lose access to your mobile device</p>
        <ul>
            @foreach ((array)auth()->user()->recoveryCodes() as $code)
            <li>{{ $code }}</li>
            @endforeach
        </ul>
    @endif
    </div>
    {{-- @php
        var_dump(session()->all());
    @endphp
    
    @if (session('status'))
        <ul>
            <li>{{ session('status') }}</li>
        </ul>
    @endif --}}
</div>