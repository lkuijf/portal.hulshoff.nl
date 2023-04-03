@if ($data && count($data))
    <table @if(isset($data->export_file))data-exportfile="{{ $data->export_file }}"@endif>
        <tr>
            <th>Artikel code</th>
            <th>Artikel omschrijving</th>
            <th>Totaal</th>
        </tr>
    @foreach ($data as $info)
    <tr>
        <td>{{ $info->artikelCode }}</td>
        <td>{{ $info->omschrijving }}</td>
        <td>{{ $info->total }}</td>
    </tr>
    @endforeach
    </table>
@else
<p>{{ __('No data found') }}</p>
@endif