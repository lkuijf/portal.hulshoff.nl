{{-- User:<br />
customer_a@hulshoffportal.nl<br />
v482kS0Y<br /><br />
Admin:<br />
admin@portal.hulshoff.nl<br />
6weY9e5H --}}
{{-- <form action="{{ route('attempt_login') }}" method="post"> --}}
{{-- <div class="loginContent"> --}}
    <h1>{{ __('Login') }}</h1>
    <p>{{ __('Please login with your credentials') }}</p>
    <form action="/login" method="post">
        @csrf
        <div>
            <label for="enterEmail">{{ __('E-mail address') }}</label>
            <input type="text" name="email" id="enterEmail">
        </div>
        <div>
            <label for="enterPassword">{{ __('Password') }}</label>
            <input type="password" name="password" id="enterPassword">
        </div>
        <button type="submit">{{ __('Login') }}</button>
    </form>
    <p><a href="/forgot-password">{{ __('Forgot password') }}?</a></p>
    {{-- <p><a href="/" class="backBtn">Terug</a></p> --}}
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
        @php
            $statusMsg = session('status');
        @endphp
        <script>showMessage('success',"<p>aaa{!! $statusMsg !!}</p>")</script>
    @endif
{{-- </div> --}}
