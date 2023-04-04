@extends('templates.email')
@section('content')
    @if ($order->is_reservation)
        <h1>Reservation placed</h1>
        <p>Your reservation has been placed.</p>
        <p>Reservation number:<br /><strong>{{ $order->id }}</strong></p>
        <p>Reservation code klant:<br /><strong>{{ $order->orderCodeKlant }}</strong></p>
    @else
        <h1>Order placed</h1>
        <p>Your order has been placed.</p>
        <p>Order number:<br /><strong>{{ $order->id }}</strong></p>
        <p>Order code klant:<br /><strong>{{ $order->orderCodeKlant }}</strong></p>
    @endif
    <p>Delivery date:<br />{{ date('d-m-Y', strtotime($order->afleverDatum)) }}</p>
    @if (count($order->orderArticles))
        <p>
        @foreach ($order->orderArticles as $ordArt)
            @php
                $product = Product::find($ordArt->product_id);
            @endphp
            {{ $product->omschrijving }}<br />
        @endforeach
        </p>
    @endif
@endsection