@extends('templates.email')
@section('content')
    @if ($order->is_reservation)
        <h1>Reservation placed</h1>
        <p>Your reservation has been placed.</p>
        <p>{{ $order->id }}</p>
        <p>{{ $order->orderCodeKlant }}</p>
    @else
        <h1>Order placed</h1>
        <p>Your order has been placed.</p>
        <p>{{ $order->id }}</p>
        <p>{{ $order->orderCodeKlant }}</p>
    @endif
@endsection