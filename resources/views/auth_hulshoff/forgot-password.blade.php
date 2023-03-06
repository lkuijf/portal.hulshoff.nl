<div class="forgotPasswordContent">
    <h1>{{ __('Forgot password') }}</h1>
    <p>{{ __('Please provide your e-mail address to request a password reset') }}</p>
    <form action="/forgot-password" method="POST">
        @csrf
        <input type="text" name="email">
        <button type="submit">{{ __('Request') }}</button>
    </form>
    <p><a href="{{ route('login') }}">< {{ __('Back') }}</a></p>
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