<h2>Order gegevens</h2>
<p>
    Order nummer: <strong>{{ $order->id }}</strong><br>
    Gewenste leverdatum: <strong>{{ date('m-d-Y', strtotime($order->afleverDatum)) }}</strong><br>
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
<h2>Afleveradres</h2>
<p>
    Naam: <strong>{{ $address->naam }}</strong><br>
    Straat: <strong>{{ $address->straat }}</strong><br>
    Huisnummer: <strong>{{ $address->huisnummer }}</strong><br>
    Postcode: <strong>{{ $address->postcode }}</strong><br>
    Plaats: <strong>{{ $address->plaats }}</strong><br>
    Landcode: <strong>{{ $address->landCode }}</strong><br>
    Contactpersoon: <strong>{{ $address->contactpersoon }}</strong><br>
    Telefoonnummer: <strong>{{ $address->telefoon }}</strong><br>
    E-mail adres: <strong>{{ $address->eMailadres }}</strong>
</p>
@if ($products && count($products))
    <table>
        <tr>
            <th>Omschrijving</th>
        </tr>
    @foreach ($products as $prodInfo)
    <tr>
        <td>{{ $prodInfo }}</td>
    </tr>
    @endforeach
    </table>
@else
<p>{{ __('No products found') }}</p>
@endif