@extends('templates.portal')
@section('content')
<div class="basketContent">
    <h1>Basket</h1>
    @if (count($basket))
        <table>
            <tr>
                <th>Id</th>
                <th>Omschrijving</th>
                <th>Prijs</th>
                <th>Aantal</th>
                <th>Actions</th>
            </tr>
            @foreach ($basket as $item)
            <tr>
                <td>{{ $item['product']->id }}</td>
                <td>{{ $item['product']->omschrijving }}</td>
                <td>{{ $item['product']->prijs }}</td>
                <td>{{ $item['count'] }}</td>
                <td>
                    <a href="">[edit]</a>
                    <form action="{{ route('basket') }}" method="post">
                        @method('delete')
                        @csrf
                        <input type="hidden" name="id" value="{{ $item['product']->id }}">
                        <button type="submit" onclick="return confirm('You are about to delete {{ $item['product']->omschrijving }} from your basket.\n\nAre you sure?')">Delete</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </table>
    @else
        <p>Basket is empty.</p>
    @endif
</div>
@endsection