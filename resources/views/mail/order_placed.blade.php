@extends('templates.email')
@section('content')
    @if ($order->is_reservation)
        <h1>Reservation placed</h1>
        <p>Your reservation has been placed.</p>
        <p>Reservation number: {{ $order->id }}</p>
        <p>Reservation code klant{{ $order->orderCodeKlant }}</p>
    @else
        <h1>Order placed</h1>
        <p>Your order has been placed.</p>
        <p>Order number: {{ $order->id }}</p>
        <p>Order code klant{{ $order->orderCodeKlant }}</p>
    @endif
    <p>Delivery date: {{ date('d-m-Y', strtotime($order->afleverDatum)) }}</p>
@endsection