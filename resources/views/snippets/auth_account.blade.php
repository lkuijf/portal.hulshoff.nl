<div class="accountHomeContent">
    <h1>{{ __('Welcome') }} <em>{{ auth()->user()->name }}</em></h1>
    <p>{{ auth()->user()->email }}
        @if (auth()->user()->email_verified_at)
        ({{ __('E-mail address has been verified') }})
        @else
        <a href="{{ url('email/verify') }}">Verifiëren</a>
        @endif
    </p>
    {{-- <h1>Account</h1> --}}
    {{-- @include('auth_hulshoff.logout', ['buttonInside' => 'Logout']) --}}
    @if (auth()->user()->is_admin)
    <p>{{ __('As admin, you are authorized to edit users') }}.</p>
    @else
    {{-- <p>Bekijk de beschikbare producten.</p> --}}
    @endif

    {{-- @if (auth()->user())
        yes
    @else
        no
    @endif --}}
    <div class="twofaWrap">
    <h2>{{ __('Two Factor Authentication') }}</h2>
    
    @if (auth()->user()->two_factor_confirmed_at)
        <p><em>{{ __('Two Factor Authentication') }}</em> {{ __('has been activated') }}.</p>
        <form action="{{ url('user/two-factor-authentication') }}" method="POST">
            @method('DELETE')
            @csrf
            <button type="submit">{{ __('DISABLE') }} {{ __('Two Factor Authentication') }} </button>
        </form>
    @else
        <p>{{ __('It is advisable to use') }} <em>{{ __('Two Factor Authentication') }}</em></p>
        <form action="{{ url('user/two-factor-authentication') }}" method="POST">
            @csrf
            <button type="submit">{{ __('Enable') }} {{ __('Two Factor Authentication') }} </button>
        </form>
    @endif
    @if (session('status') == 'two-factor-authentication-enabled')
        <p>{{ __('Please finish configuring') }} {{ __('Two Factor Authentication') }} {{ __('below') }}.</p>
        {!! auth()->user()->twoFactorQrCodeSvg() !!}
        <form action="{{ url('user/confirmed-two-factor-authentication') }}" method="POST">
            @csrf
            <input type="text" name="code">
            <button type="submit">{{ __('Submit authentication code') }}</button>
        </form>
    @endif
    @if (session('status') == 'two-factor-authentication-disabled')
        {{-- <p><em>Two Factor Authentication</em> has been disabled.</p> --}}
        <script>showMessage('success',"<p><em>{{ __('Two Factor Authentication') }}</em> {{ __('has been disabled') }}.</p>")</script>
    @endif
    @if (session('status') == 'two-factor-authentication-confirmed')
        {{-- <p><em>Two factor authentication</em> confirmed and enabled successfully.</p> --}}
        <script>showMessage('success',"<p><em>{{ __('Two Factor Authentication') }}</em> {{ __('confirmed and enabled successfully') }}.</p>")</script>
        <p>{{ __('Below you find the recovery codes in case you lose access to your mobile device') }}</p>
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