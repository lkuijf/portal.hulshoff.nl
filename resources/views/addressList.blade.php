@extends('templates.portal')
@section('content')
<div class="addressesContent">
    <h1>{{ __('Overview') }} {{ Str::lower(__('Addresses')) }}</h1>
    <p>{{ __('Overview of all addresses') }}.</p>
    <p><a href="{{ route('new_address') }}" class="addBtn">{{ __('Create new') }}</a></p>
    @if (isset($data['addresses']) && count($data['addresses']))
    <table>
        <thead>
            <tr>
                <th>{{ __('Name') }}</th>
                <th>{{ __('Client') }}</th>
                <th>{{ __('Street') }} & {{ __('House number') }}</th>
                <th>{{ __('Zipp code') }} & {{ __('City') }}</th>
                <th>{{ __('Contact person') }}</th>
                <th>&nbsp;</th>
            </tr>
        </thead>
        <tbody>
           
                @foreach ($data['addresses'] as $address)
                <tr>
                    <td>{{ $address->naam }}</td>
                    <td>{{ $address->customer->naam }}</td>
                    <td>{{ $address->straat }} {{ $address->huisnummer }}</td>
                    <td>{{ $address->postcode }} {{ $address->plaats }}</td>
                    <td>{{ $address->contactpersoon }}</td>
                    <td>
                        <a href="{{ route('address_detail', ['id' => $address->id]) }}" class="editBtn">{{ __('Edit') }}</a>
                        <form action="/address" method="post">
                            @method('delete')
                            @csrf
                            <input type="hidden" name="id" value="{{ $address->id }}">
                            <button type="submit" onclick="return confirm('{{ __('You are about to delete address with name ') }} {{ $address->naam }}\n\n{{ __('Are you sure') }}?')" class="deleteBtn"></button>
                        </form>
                    </td>
                </tr>
                @endforeach
            
        </tbody>
    </table>
    @else
    <p>{{ __('No addresses found') }}</p>
    @endif
</div>
@endsection