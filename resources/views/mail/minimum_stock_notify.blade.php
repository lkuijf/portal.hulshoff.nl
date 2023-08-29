@extends('templates.email')
@section('content')
    <h1>{{ __('Below mimium stock') }}</h1>
    <p>{{ __('The stock of some products has fallen below the minimum stock') }}.</p>
    @if (count($products))
        <p>{{ __('Products') }}:<br />
        @foreach ($products as $prod)
            <strong>{{ $prod->omschrijving }}</strong><br />
        @endforeach
        </p>
    @endif
@endsection