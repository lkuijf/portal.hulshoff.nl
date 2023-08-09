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