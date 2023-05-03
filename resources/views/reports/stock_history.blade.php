@if ($data && count($data))
    @php
        $stockByDate = [];
        $curStock = '-';
        $klantCode = $data[0]->klantCode;
        $artikelCode = $data[0]->artikelCode;
    @endphp
    @foreach ($data as $info)
        @php
            $stockByDate[date("Y-m-d",  strtotime($info->created_at))] = $info->voorraad;
        @endphp
    @endforeach
    <table @if(isset($data->export_file))data-exportfile="{{ $data->export_file }}"@endif>
        <tr>
            <th>Klant code</th>
            <th>Artikel code</th>
            <th>Datum</th>
            <th>Voorraad</th>
        </tr>
    @for ($x=strtotime($data->periodStart); $x<=strtotime($data->periodEnd); $x+=86400)
        @if (isset($stockByDate[date("Y-m-d",  $x)]))
            @php
                $curStock = $stockByDate[date("Y-m-d",  $x)];
            @endphp
        @endif
        <tr>
            <td>{{ $klantCode }}</td>
            <td>{{ $artikelCode }}</td>
            <td>{{ date("Y-m-d",  $x) }}</td>
            <td>{{ $curStock }}</td>
        </tr>
    @endfor
    </table>
@else
<p>{{ __('No data found') }}</p>
@endif