@extends('templates.email')
@section('content')
    <h1>Reminder</h1>
    <p>-</p>
    <p>Order number:<br /><strong>{{ $order->id }}</strong></p>
    <p>Order code klant:<br /><strong>{{ $order->orderCodeKlant }}</strong></p>
    <p>Delivery date:<br /><strong>{{ date('d-m-Y', strtotime($order->afleverDatum)) }}</strong></p>
    @if (count($orderProducts))
        <p>Products:<br />
        @foreach ($orderProducts as $prod)
            <strong>{{ $prod }}</strong><br />
        @endforeach
        </p>
    @endif
@endsection