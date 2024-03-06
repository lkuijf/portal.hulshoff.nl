@extends('templates.portal')
@section('content')
@php
    $returnProducts = [
        'Eerste Product',
        'Nog een artikel',
        'Lage / Middelhoge kast',
    ];
@endphp
<div class="returnOrderContent">
    <h1>{{ __('Return order') }}</h1>
    <p>{{ __('Create your return order') }}.</p>
    {{-- <p><a href="{{ route('new_address') }}" class="addBtn">{{ __('Create new') }}</a></p> --}}
    <form action="/return-order" method="post">
        @csrf
        <div>
            <label for="select_return_product">{{ __('Product') }}</label><br>
            <select name="return_product" id="select_return_product">
                <option value="">-{{ __('Select') }}-</option>
                @foreach ($returnProducts as $retProd)
                    <option value="{{ $retProd }}"@if($retProd == old('return_product')){{ ' selected' }}@endif>{{ $retProd }}</option>
                @endforeach
                {{-- <option value="prod1"@if() @endif>Product 1</option>
                <option value="prod2">Product 2</option> --}}
            </select>
        </div>
        <div>
            <label for="return_amount">{{ __('Amount') }}</label><br>
            <input type="text" size="5" id="return_amount" name="amount" value="{{ old('amount') }}">
        </div>
        <div>
            <button type="submit">{{ __('Add') }}</button>
        </div>
    </form>
    @if (count($returnOrderBasket))
    <table>
        <thead>
            <tr>
                <th>{{ __('Product') }}</th>
                <th>{{ __('Amount') }}</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            @foreach ($returnOrderBasket as $item)
            <tr>
                <td>{{ $item['product'] }}</td>
                <td>{{ $item['count'] }}</td>
                <td>
                    <form action="/return-order" method="post">
                        @method('delete')
                        @csrf
                        <input type="hidden" name="product_name" value="{{ $item['product'] }}">
                        <button type="submit" class="deleteBtn"></button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    <form action="/return-order-basket" method="get">
        @csrf
        <button type="submit">{{ __('Continue') }}</button>
    </form>
    @else
    <p>{{ __('Add articles before continuing') }}</p>
    @endif
</div>
@endsection