@extends('templates.email')
@section('content')
    <h1>{{ __('Below mimium stock') }}</h1>
    <p>{{ __('The stock of some products has fallen below the minimum stock') }}.</p>
    @if (count($products))
        <h2>{{ __('Products') }}:</h2>
        @foreach ($products as $prod)
            <hr>
            <p>
                klantCode: <strong>{{ $prod->klantCode }}</strong><br>
                artikelCode: <strong>{{ $prod->artikelCode }}</strong><br>
                artikelCodeKlant: <strong>{{ $prod->artikelCodeKlant }}</strong><br>
                Omschrijving: <strong>{{ $prod->omschrijving }}</strong><br>
            </p>
            <table cellpadding="5px">
                <tr>
                    <th style="text-align:left;">Minimale<br>voorraad</th>
                    <th style="text-align:left;">Actuele<br>voorraad</th>
                    <th style="text-align:left;">Aantal<br>gereserveerd</th>
                    <th style="text-align:left;">Aantal<br>besteld</th>
                </tr>
                <tr>
                    <td>{{ $prod->minimaleVoorraad }}</td>
                    <td>{{ $prod->voorraad }}</td>
                    <td>{{ $prod->reservedAmount() }}</td>
                    <td>{{ $prod->orderedAmount() }}</td>
                </tr>
            </table>
        @endforeach
    @endif
@endsection