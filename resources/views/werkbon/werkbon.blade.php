@if ($data && count($data))
    <table>
        <tr>
            <th>Naam</th>
            <th>Adres</th>
        </tr>
    @foreach ($data as $info)
    <tr>
        <td>{{ $info->name }}</td>
        <td>{{ $info->address }}</td>
    </tr>
    @endforeach
    </table>
@else
<p>{{ __('No data found') }}</p>
@endif