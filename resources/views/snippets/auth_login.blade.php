{{-- User:<br />
customer_a@hulshoffportal.nl<br />
v482kS0Y<br /><br />
Admin:<br />
admin@portal.hulshoff.nl<br />
6weY9e5H --}}
{{-- <form action="{{ route('attempt_login') }}" method="post"> --}}
{{-- <div class="loginContent"> --}}
    <h1>Login</h1>
    <p>Login met uw gegevens</p>
    <form action="/login" method="post">
        @csrf
        <div>
            <label for="enterEmail">E-mail adres</label>
            <input type="text" name="email" id="enterEmail">
        </div>
        <div>
            <label for="enterPassword">Wachtwoord</label>
            <input type="password" name="password" id="enterPassword">
        </div>
        <button type="submit">Login</button>
    </form>
    <p><a href="/forgot-password">Wachtwoord vergeten?</a></p>
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
        <script>showMessage('success',"<p>{!! session('status') !!}</p>")</script>
    @endif
{{-- </div> --}}
