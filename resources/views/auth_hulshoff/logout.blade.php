<form action="/logout" method="POST">
    @csrf
    <button type="submit">{!! $buttonInside !!}</button>
</form>