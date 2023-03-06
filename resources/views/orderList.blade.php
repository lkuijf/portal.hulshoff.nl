{{-- @extends('templates.development') --}}
@extends('templates.portal')
@section('content')
<div class="orderListContent">
    <h1>{{ __('All') }} {{ __($data['type']) }} orders</h1>
    {{-- <div class="allOrders"> --}}
        <form action="{{ url()->current() }}" method="post" class="searchForm">
            @csrf
            <input type="text" name="search" placeholder="{{ __('Search') }}" value="{{ $data['search_value'] }}">
            @if (auth()->user()->is_admin)
            <br /><input type="checkbox" id="only_my_orders" name="showOnlyMyOrders" value="1" @if($data['show_only_my_orders']){{ 'checked' }}@endif>
            <label for="only_my_orders">{{ __('Only my orders') }}</label><br />
            @endif
            <button type="submit" class="searchBtn">{{ __('Find') }}</button>
        </form>
        @if(count($data['orders']))
        <table>
            <thead>
                <tr>
                    <th>id</th>
                    <th>Order Code {{ __('Client') }}</th>
                    <th>{{ __('Delivery date') }}</th>
                    <th>{{ __('User') }}</th>
                    <th>{{ __('Created at') }}</th>
                    {{-- <th>Aflever Tijd</th> --}}
                    <th>&nbsp;</th>
                    <th>&nbsp;</th>
                </tr>
            </thead>
            <tbody>
            @foreach ($data['orders'] as $order)
                <tr>
                    <td>{{ $order->id }}</td>
                    <td>{{ $order->orderCodeKlant }}</td>
                    <td>{{ date("d-m-Y", strtotime($order->afleverDatum)) }}</td>
                    <td>{{ $order->hulshoffUser->name }} ({{ $order->hulshoffUser->email }})</td>
                    <td>{{ date("d-m-Y H:m:s", strtotime($order->created_at)) }}</td>
                    {{-- <td>{{ date("H:i", strtotime($order->afleverTijd)) }}</td> --}}
                    <td><a href="{{ url()->current() }}/{{ $order->id }}" class="editBtn">{{ __('View') }}</a></td>
                    <td>
                        @if ($order->is_reservation)
                        <form action="{{ url('order') }}" method="post">
                            @method('delete')
                            @csrf
                            <input type="hidden" name="id" value="{{ $order->id }}">
                            <button type="submit" onclick="return confirm('{{ __('You are about to delete this reservation') }} (id: {{ $order->id }}).\n\n{{ __('Are you sure') }}?')" class="deleteBtn"></button>
                        </form>
                        @endif
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
        @else
        <p>No {{ $data['type'] }} found</p>
        @endif
    {{-- </div> --}}
</div>
@endsection
{{-- @section('before_closing_body_tag')
<script>
    const ordTable = document.querySelector('.allOrders table');
    const searchForm = document.querySelector('.allOrders form');
    searchForm.addEventListener('submit', (e) => {
        e.preventDefault();
        // console.log(searchForm.querySelector('input').value);
    });
</script>
@endsection --}}