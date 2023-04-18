@extends('templates.email')
@section('content')
    @if ($order->is_reservation)
        <h1>{{ __('Reservation placed') }}</h1>
        <p>{{ __('Thanks for your reservation') }}. {{ __('The products you selected will be set aside') }}. {{ __('Below you will find details of your reservation') }}.</p>
        <p>{{ __('Reservation number') }}:<br /><strong>{{ $order->id }}</strong></p>
        <p>{{ __('Reservation code customer') }}:<br /><strong>{{ $order->orderCodeKlant }}</strong></p>
    @else
        <h1>{{ __('Order placed') }}</h1>
        <p>{{ __('Thanks for your order') }}. {{ __('The products you selected will be delivered on the date you specified') }}. {{ __('Below you will find details of your order') }}.</p>
        <p>{{ __('Order number') }}:<br /><strong>{{ $order->id }}</strong></p>
        <p>{{ __('Order code customer') }}:<br /><strong>{{ $order->orderCodeKlant }}</strong></p>
    @endif
    <p>{{ __('Delivery date') }}:<br /><strong>{{ date('d-m-Y', strtotime($order->afleverDatum)) }}</strong></p>
    @if (count($orderProducts))
        <p>{{ __('Products') }}:<br />
        @foreach ($orderProducts as $prod)
            <strong>{{ $prod }}</strong><br />
        @endforeach
        </p>
    @endif
@endsection