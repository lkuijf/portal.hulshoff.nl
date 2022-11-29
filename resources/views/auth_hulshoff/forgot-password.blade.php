<div class="forgotPasswordContent">
    <h1>Forgot password</h1>
    <p>Please provide your e-mail address</p>
    <form action="/forgot-password" method="POST">
        @csrf
        <input type="text" name="email">
        <button type="submit">Request</button>
    </form>
    @if ($errors->any())
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    @endif
</div>