@extends('templates.email')
@section('content')
    <h1>{{ __('Reservation promoted') }}</h1>
    <p>{{ __('Your reservation has been promoted to an order') }}. {{ __('The products you selected will be delivered on the date you specified') }}. {{ __('Below you will find details of your order') }}.</p>
    <p>{{ __('Order number') }}:<br /><strong><a href="{{ route('order_detail', ['id' => $order->id]) }}">{{ $order->id }}</a></strong></p>
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