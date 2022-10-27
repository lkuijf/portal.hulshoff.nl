<form action="/two-factor-challenge" method="POST">
    @csrf
    <input type="input" name="code">
    <button type="submit">Controleer code</button>
</form>
@if ($errors->any())
    <ul>
        @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
        @endforeach
    </ul>
@endif
