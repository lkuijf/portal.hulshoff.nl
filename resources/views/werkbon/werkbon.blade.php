@php
    if($order->orderType == 'return-order') {
        $header = 'Retour order gegevens';
        $datumTxt = 'leverdatum';
        $adresTxt = 'Afleveradres';
    } else { // normal order
        $header = 'Order gegevens';
        $datumTxt = 'ophaaldatum';
        $adresTxt = 'Ophaaladres';
    }
@endphp
<h2>{{ $header }}</h2>
<p>
    Order nummer: <strong>{{ $order->id }}</strong><br>
    Gewenste {{ $datumTxt }}: <strong>{{ date('d-m-Y', strtotime($order->afleverDatum)) }}</strong><br>
</p>
<h2>Melder gegevens</h2>
<p>
    Naam melder: <strong>{{ $hulshoffUser->name }}</strong><br>
    E-mail melder: <strong>{{ $hulshoffUser->email }}</strong>
</p>
<h2>Klant gegevens</h2>
<p>
    KlantCode: <strong>{{ $customer->klantCode }}</strong><br>
    Klant naam: <strong>{{ $customer->naam }}</strong>
</p>
<h2>{{ $adresTxt }}</h2>
<p>
    @if($address->naam)Naam: <strong>{{ $address->naam }}</strong><br>@endif
    Straat: <strong>{{ $address->straat }}</strong><br>
    Huisnummer: <strong>{{ $address->huisnummer }}</strong><br>
    Postcode: <strong>{{ $address->postcode }}</strong><br>
    Plaats: <strong>{{ $address->plaats }}</strong><br>
    @if($address->landCode)Landcode: <strong>{{ $address->landCode }}</strong><br>@endif
    Contactpersoon: <strong>{{ $address->contactpersoon }}</strong><br>
    Telefoonnummer: <strong>{{ $address->telefoon }}</strong><br>
    Planon / PO nummer: <strong>{{ $address->po_number }}</strong><br>
    @if($address->eMailadres)E-mail adres: <strong>{{ $address->eMailadres }}</strong>@endif
    @if($address->informatie)Extra informatie: <strong>{{ $address->informatie }}</strong>@endif
</p>
@if ($products && count($products))
    <table>
        <tr>
            <th>Artikelnr.</th>
            <th>Aantal</th>
            <th>Merk</th>
            <th>Groep</th>
            <th>Type</th>
            <th>Omschrijving</th>
        </tr>
    @foreach ($products as $prodInfo)
    <tr>
        <td>{{ (isset($prodInfo->artikelCode)?$prodInfo->artikelCode:'') }}</td>
        <td>{{ $prodInfo->amount }}</td>
        <td>{{ (isset($prodInfo->brand)?$prodInfo->brand:'') }}</td>
        <td>{{ (isset($prodInfo->group)?$prodInfo->group:'') }}</td>
        <td>{{ (isset($prodInfo->type)?$prodInfo->type:'') }}</td>
        <td>{{ $prodInfo->omschrijving }}</td>
    </tr>
    @endforeach
    </table>
@else
<p>{{ __('No products found') }}</p>
@endif