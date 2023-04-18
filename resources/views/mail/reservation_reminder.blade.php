@extends('templates.email')
@section('content')
    <h1>{{ __('Reminder') }}</h1>
    <p>-</p>
    <p>{{ __('Order number') }}:<br /><strong>{{ $order->id }}</strong></p>
    <p>{{ __('Order code customer') }}:<br /><strong>{{ $order->orderCodeKlant }}</strong></p>
    <p>{{ __('Delivery date') }}:<br /><strong>{{ date('d-m-Y', strtotime($order->afleverDatum)) }}</strong></p>
    @if (count($orderProducts))
        <p>{{ __('Products') }}:<br />
        @foreach ($orderProducts as $prod)
            <strong>{{ $prod }}</strong><br />
        @endforeach
        </p>
    @endif
@endsection