@extends('templates.email')
@section('content')
    <h1>{{ __('Below mimium stock') }}</h1>
    <p>{{ __('The stock of some products has fallen below the minimum stock') }}.</p>
    @if (count($products))
        <h2>{{ __('Products') }}:</h2>
        @foreach ($products as $prod)
            <p>
                klantCode: <strong>{{ $prod->klantCode }}</strong><br>
                artikelCode: <strong>{{ $prod->artikelCode }}</strong><br>
                artikelCodeKlant: <strong>{{ $prod->artikelCodeKlant }}</strong><br>
                Omschrijving: <strong>{{ $prod->omschrijving }}</strong><br>
            </p>
            <table>
                <tr>
                    <th>Minimale voorraad</th>
                    <th>Actuele voorraad</th>
                    <th>Aantal gereserveerd</th>
                    <th>Aantal besteld</th>
                </tr>
                <tr>
                    <td>{{ $prod->minimaleVoorraad }}</td>
                    <td>{{ $prod->voorraad }}</td>
                    <td>{{ $prod->reservedAmount() }}</td>
                    <td>{{ $prod->orderedAmount() }}</td>
                </tr>
            </table>
            <hr>
        @endforeach
    @endif
@endsection