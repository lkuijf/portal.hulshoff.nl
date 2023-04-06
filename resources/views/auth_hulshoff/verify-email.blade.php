<div class="verifyEmailContent">
    <h1>{{ __('Verify your e-mail address') }}</h1>
    <p>{{ __('Send an e-mail with a verification link') }}, {{ __('click on this button') }}:</p>
    <form action="/email/verification-notification" method="POST">
        @csrf
        <button type="submit">{{ __('Send e-mail') }}</button>
    </form>
    <p>{{ __('Please verify your e-mail address by clicking the link in the e-mail message') }}.</p>
    <p><a href="{{ route('login') }}">< {{ __('Back') }}</a></p>
    @if (session('status') == 'verification-link-sent')
        <script>showMessage('success',"<p>{{ __('An email address verification link has been emailed to you') }}!</p>")</script>
    @endif
</div>