<div class="login2FaChallengeContent">
    <h1>{{ __('Check') }}</h1>
    <p>{{ __('Enter the code provided by your') }} <em>{{ __('Two Factor Authentication') }}</em> app.</p>
    <form action="/two-factor-challenge" method="POST">
        @csrf
        <input type="input" name="code">
        <button type="submit">{{ __('Check code') }}</button>
    </form>
    <div class="noAccessToTheApp">
        <h2><em>{{ __('I don\'t have access to the app') }}.</em></h2>
        <p>{{ __('When you don\'t have access to the app anymore, you can use a recovery code you received when you enabled the Two Factor Authentication to access your account') }}.</p>
        <p><a href="" class="sendRecCodeLink">{{ __('Send a recovery code') }}</a></p>
        <form class="recoveryCodeForm" action="/two-factor-challenge" method="POST">
            @csrf
            <input type="input" name="recovery_code">
            <button type="submit">{{ __('Check recovery code') }}</button>
        </form>
        <hr>
        <p>U kunt ook gebruik maken van onderstaande knop om de code naar uw e-mail adres te laten versturen.</p>
        <form action="/send-2fa-code-to-email" method="POST">
            @csrf
            <button type="submit">Verstuur code via e-mail</button>
        </form>
        <p>Check uw spambox voor de zekerheid.</p>
    </div>
    {{-- @if ($errors->any())
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    @endif --}}
</div>
@section('before_closing_body_tag')
<script>
    const recoveryCodeToggleLink = document.querySelector('.sendRecCodeLink');
    const recoveryCodeForm = document.querySelector('.recoveryCodeForm');
    recoveryCodeToggleLink.addEventListener('click', (e) => {
        e.preventDefault();
        if(recoveryCodeForm.style.display == '')
            recoveryCodeForm.style.display = 'block';
        else
            recoveryCodeForm.style.display = '';
    });
</script>
@endsection
