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
    <p>Delivery date:<br /><strong>{{ date('d-m-Y', strtotime($order->afleverDatum)) }}</strong></p>
    @if (count($orderProducts))
        <p>Products:<br />
        @foreach ($orderProducts as $prod)
            <strong>{{ $prod }}</strong><br />
        @endforeach
        </p>
    @endif
@endsection