@if ($data && count($data))
    <table @if(isset($data->export_file))data-exportfile="{{ $data->export_file }}"@endif>
        <tr>
            <th>Naam</th>
            <th>Totaal orders</th>
        </tr>
    @foreach ($data as $info)
    <tr>
        <td>{{ $info->name }}</td>
        <td>{{ $info->total }}</td>
    </tr>
    @endforeach
    </table>
@else
<p>{{ __('No data found') }}</p>
@endif