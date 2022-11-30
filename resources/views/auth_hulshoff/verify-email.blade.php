<div class="verifyEmailContent">
    <h1>Verify e-mail address</h1>
    <p>Send an e-mail with a verification link, click on this button:</p>
    <form action="/email/verification-notification" method="POST">
        @csrf
        {{-- <input type="text" name="email"> --}}
        <button type="submit">Send e-mail</button>
    </form>
    <p>Please verify your e-mail address by clicking the link in the e-mail message.</p>
    <p><a href="{{ route('login') }}">< Terug</a></p>
    @if (session('status') == 'verification-link-sent')
        <p>A new email verification link has been emailed to you!</p>
    @endif
    {{-- @if ($errors->any())
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    @endif
    @if (session('status'))
        <ul>
            <li>{{ session('status') }}</li>
        </ul>
    @endif --}}
</div>