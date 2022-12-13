@extends('templates.email')
@section('content')
    @if ($isReservation)
        <h1>Reservation placed</h1>
        <p>Your reservation has been placed.</p>
    @else
        <h1>Order placed</h1>
        <p>Your order has been placed.</p>
    @endif
@endsection