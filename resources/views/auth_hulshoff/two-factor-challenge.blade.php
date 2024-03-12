<div class="login2FaChallengeContent">
    <h1>Controle</h1>
    <p>Vul de code in getoond door uw <em>Two Factor Authentication</em> app.</p>
    <form action="/two-factor-challenge" method="POST">
        @csrf
        <input type="input" name="code">
        <button type="submit">Controleer code</button>
    </form>
    <h2><em>Ik heb geen toegang tot de app.</em></h2>
    <p>Wanneer u geen toegang meer heeft tot de app, dan kunt u een herstel code gebruiken die u heeft ontvangen tijdens het inschakelen van de Two Factor Authentication om toch toegang te krijgen tot uw account.</p>
    <form action="/two-factor-challenge" method="POST">
        @csrf
        <input type="input" name="recovery_code">
        <button type="submit">Controleer herstel code</button>
    </form>
    <p>U kunt ook gebruik maken van onderstaande knop om de code naar uw e-mail adres te laten versturen. Check uw spambox voor de zekerheid.</p>
    <form action="/send-2fa-code-to-email" method="POST">
        @csrf
        <button type="submit">Verstuur code via e-mail</button>
    </form>
    {{-- @if ($errors->any())
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    @endif --}}
</div>
