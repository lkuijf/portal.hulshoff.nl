{{-- @extends('templates.development') --}}
@extends('templates.portal')
@section('content')
<div class="orderContent">
    @php
        $totalOrderSum = 0;
    @endphp
    <h1>{{ ($order->is_reservation?'Reservation':'Order') }} details</h1>
    <p>Id: {{ $order->id }}</p>
    <p>Is reservation: {{ ($order->is_reservation?'Yes':'No') }}</p>
    <p>Aflever datum: {{ $order->afleverDatum }}</p>
    <p>Aflever tijd: {{ $order->afleverTijd }}</p>
    <p>Order aangemaakt op: {{ $order->created_at }}</p>
    @if (count($order->orderArticles))
        <h2>Producten</h2>
        <table>
            <tr>
                <th>Id</th>
                <th>Product Id</th>
                <th>Product Name</th>
                <th>Amount</th>
                <th>Price</th>
                <th>Total price</th>
            </tr>
        @foreach ($order->orderArticles as $art)
            @php
                $totalOrderSum += $art->product->prijs*$art->amount;
            @endphp
            <tr>
                <td>{{ $art->id }}</td>
                <td>{{ $art->product_id }}</td>
                <td>{{ $art->product->omschrijving }}</td>
                <td>{{ $art->amount }}</td>
                <td>&euro;{{ number_format($art->product->prijs, 2, ',', '.') }}</td>
                <td>&euro;{{ number_format($art->product->prijs*$art->amount, 2, ',', '.') }}</td>
            </tr>
        @endforeach
        </table>
        <p><strong>Total value of your order: &euro;{{ number_format($totalOrderSum, 2, ',', '.') }}</strong></p>
    @endif
    @if ($order->is_reservation)
    <h2>Confirm reservation</h2>
    <p>Confirm your reservation via the button below</p>
    <p>When your order is placed, it cannot be undone.</p>
        <form action="/order" method="post">
            @method('put')
            @csrf
            <input type="hidden" name="id" value="{{ $order->id }}">
            <button type="submit">Confirm reservation</button>
        </form>
    @endif
</div>
@endsection