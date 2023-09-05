<div class="resetPasswordContent">
    <h1>{{ __('Reset password') }}</h1>
    {{-- <p>Please provide your e-mail address</p> --}}
    <form action="/reset-password" method="POST">
        @csrf
        <div>
            <label for="enterEmail">E-mail {{ __('address') }}</label>
            <input type="text" name="email" id="enterEmail">
        </div>
        <div>
            <label for="enterPassword">{{ __('Password') }}</label>
            <input type="password" name="password" id="enterPassword">
        </div>
        <div>
            <label for="enterPasswordConfirmation">{{ __('Confirm password') }}</label>
            <input type="password" name="password_confirmation" id="enterPasswordConfirmation">
        </div>
        <input type="hidden" name="token" value="{{ request()->route('token') }}">
        <button type="submit">Reset</button>
    </form>
    {{-- @if ($errors->any())
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    @endif --}}
    @if (session('status'))
        {{-- <ul>
            <li>{{ session('status') }}</li>
        </ul> --}}
        <script>showMessage('success',"<p>{!! session('status') !!}</p>")</script>
    @endif
</div>