{{-- @if ($data && count($data)) --}}
    <table>
        <tr>
            <th>Naam</th>
            <th>Adres</th>
        </tr>
    @foreach ($data as $info)
    <tr>
        <td>{{ $name }}</td>
        <td>{{ $address }}</td>
    </tr>
    @endforeach
    </table>
{{-- @else --}}
{{-- <p>{{ __('No data found') }}</p> --}}
{{-- @endif --}}