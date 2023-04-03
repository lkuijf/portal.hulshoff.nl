@if ($data && count($data))
    <table @if(isset($data->export_file))data-exportfile="{{ $data->export_file }}"@endif>
        <tr>
            <th>id</th>
            <th>Order Code Klant</th>
            <th>Aflever datum</th>
            <th>Besteld door</th>
            <th>created_at</th>
            <th>updated_at</th>
        </tr>
    @foreach ($data as $order)
    <tr>
        <td>{{ $order->id }}</td>
        <td>{{ $order->orderCodeKlant }}</td>
        <td>{{ $order->afleverDatum }}</td>
        <td>{{ $order->name }}</td>
        <td>{{ $order->created_at }}</td>
        <td>{{ $order->updated_at }}</td>
    </tr>
    <tr>
        <td></td>
        <td colspan="5">
            @if (count($order->products))
                <ul>
                @foreach ($order->products as $product)
                    <li>{{ $product->amount }}x {{ $product->omschrijving }} ({{ $product->artikelCode }})</li>
                @endforeach
                </ul>
            @endif
        </td>
    </tr>
    @endforeach
    </table>
@else
<p>{{ __('No data found') }}</p>
@endif