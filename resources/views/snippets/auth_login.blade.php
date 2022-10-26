Use:<br />
abc@def.nl<br />
test123<br />

<form action="{{ route('attempt_login') }}" method="post">
    @csrf
    <input type="text" name="email">
    <input type="password" name="password">
    <button type="submit">Login</button>
</form>
@if ($errors->any())
    <ul>
        @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
        @endforeach
    </ul>
@endif

