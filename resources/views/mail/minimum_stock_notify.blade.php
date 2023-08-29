@extends('templates.email')
@section('content')
    <h1>{{ __('Below mimium stock') }}</h1>
    <p>{{ __('The stock of some products has fallen below the minimum stock') }}.</p>
    @if (count($products))
        <p>{{ __('Products') }}:</p>
        <table>
            <tr>
                <th>klantCode</th>
                <th>artikelCode</th>
                <th>artikelCodeKlant</th>
                <th>omschrijving</th>
                <th>Minimale voorraad</th>
                <th>Actuele voorraad</th>
                <th>Aantal gereserveerd</th>
                <th>Aantal besteld</th>
            </tr>
        @foreach ($products as $prod)
            <tr>
                <td>{{ $prod->klantCode }}</td>
                <td>{{ $prod->artikelCode }}</td>
                <td>{{ $prod->artikelCodeKlant }}</td>
                <td>{{ $prod->omschrijving }}</td>
                <td>{{ $prod->minimaleVoorraad }}</td>
                <td>{{ $prod->voorraad }}</td>
                <td>{{ $prod->reservedAmount() }}</td>
                <td>{{ $prod->orderedAmount() }}</td>
            </tr>
        @endforeach
        </table>
    @endif
@endsection