{{-- @extends('templates.development') --}}
@extends('templates.portal')
@section('content')
<div class="orderListContent">
    @if(count($data['orders']))
    <h1>All {{ $data['type'] }}</h1>
    <table>
        <tr>
            <th>id</th>
            <th>Aflever Datum</th>
            {{-- <th>Aflever Tijd</th> --}}
            <th>&nbsp;</th>
            <th>&nbsp;</th>
        </tr>
    @foreach ($data['orders'] as $order)
        <tr>
            <td>{{ $order->id }}</td>
            <td>{{ date("d-m-Y", strtotime($order->afleverDatum)) }}</td>
            {{-- <td>{{ date("H:i", strtotime($order->afleverTijd)) }}</td> --}}
            <td><a href="{{ url()->current() }}/{{ $order->id }}">[view]</a></td>
            @if ($order->is_reservation)
            <td>
                <form action="{{ url('order') }}" method="post">
                    @method('delete')
                    @csrf
                    <input type="hidden" name="id" value="{{ $order->id }}">
                    <button type="submit" onclick="return confirm('You are about to delete this reservation (id: {{ $order->id }}).\n\nAre you sure?')">Delete</button>
                </form>
            </td>
            @endif
        </tr>
    @endforeach
    </table>
    @else
    <p>No {{ $data['type'] }} found</p>
    @endif
</div>
@endsection