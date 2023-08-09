{{ $hulshoffUser->name }}-
{{ $hulshoffUser->email }}-
{{ $customer->klantCode }}-
{{ $customer->naam }}-
{{ $address->naam }}-
{{ $address->straat }}-
{{ $address->huisnummer }}-
{{ $address->postcode }}-
{{ $address->plaats }}-
{{ $address->landCode }}-
{{ $address->contactpersoon }}-
{{ $address->telefoon }}-
{{ $address->eMailadres }}-
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