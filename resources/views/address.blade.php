@php
    $addId = '';

    $naam = '';
    $straat = '';
    $huisnummer = '';
    $postcode = '';
    $plaats = '';
    $landCode = 'NL';
    $contactpersoon = '';
    $telefoon = '';
    $eMailadres = '';
    $klantCode = false;
    $po_number = '';

    $headerTxt = 'New';
    $postType = 'POST';
    if(isset($data['address'])) {
        $addId = $data['address']->id;

        $naam = $data['address']->naam;
        $straat = $data['address']->straat;
        $huisnummer = $data['address']->huisnummer;
        $postcode = $data['address']->postcode;
        $plaats = $data['address']->plaats;
        $landCode = $data['address']->landCode;
        $contactpersoon = $data['address']->contactpersoon;
        $telefoon = $data['address']->telefoon;
        $eMailadres = $data['address']->eMailadres;
        $klantCode = $data['address']->klantCode;
        $po_number = $data['address']->po_number;

        $headerTxt = 'Edit';
        $postType = 'PUT';
    }
    if(old('klantCode')) $klantCode = old('klantCode');
@endphp
@extends('templates.portal')
@section('content')
<div class="addressContent">
    <h1>{{ __($headerTxt) }} {{ Str::lower(__('Address')) }}</h1>
    <p><a href="{{ route('addresses') }}" class="backBtn">{{ __('Back to overview') }}</a></p>
    <form action="{{ url('address') }}" method="post">
        @method($postType)
        @csrf
        <input type="hidden" name="id" value="{{ $addId }}">
        <table>
            <tr>
                <td>{{ __('Name') }}</td>
                <td><input type="text" name="naam" size="30" value="@if(old('naam')){{ old('naam') }}@else{{ $naam }}@endif"></td>
            </tr>
            <tr>
                <td>{{ __('Client') }}</td>
                <td>
                    <select name="klantCode">
                        <option value="">-geen-</option>
                        @foreach ($data['customers'] as $customer)
                            <option value="{{ $customer->klantCode }}" @if($customer->klantCode == $klantCode) selected @endif>{{ $customer->naam }}({{ $customer->klantCode }})</option>
                        @endforeach
                    </select>
                </td>
            </tr>
            <tr>
                <td>{{ __('Street') }}</td>
                <td><input type="text" name="straat" size="40" value="@if(old('straat')){{ old('straat') }}@else{{ $straat }}@endif"></td>
            </tr>
            <tr>
                <td>{{ __('House number') }}</td>
                <td><input type="text" name="huisnummer" size="5" value="@if(old('huisnummer')){{ old('huisnummer') }}@else{{ $huisnummer }}@endif"></td>
            </tr>
            <tr>
                <td>{{ __('Zipp code') }}</td>
                <td><input type="text" name="postcode" size="10" value="@if(old('postcode')){{ old('postcode') }}@else{{ $postcode }}@endif"></td>
            </tr>
            <tr>
                <td>{{ __('City') }}</td>
                <td><input type="text" name="plaats" size="40" value="@if(old('plaats')){{ old('plaats') }}@else{{ $plaats }}@endif"></td>
            </tr>
            <tr>
                <td>{{ __('Country code') }}</td>
                <td>
                    {{-- <input type="text" name="landCode" size="5" value="@if(old('landCode')){{ old('landCode') }}@else{{ $landCode }}@endif"> (NL/BE/DE etc.) --}}
                    <select name="landCode" id="cars">
                        <option value="NL"@if( (old('landCode') && old('landCode') == 'NL') || $landCode == 'NL' ){{ ' selected' }}@endif>NL</option>
                        <option value="BE"@if( (old('landCode') && old('landCode') == 'BE') || $landCode == 'BE' ){{ ' selected' }}@endif>BE</option>
                        <option value="DE"@if( (old('landCode') && old('landCode') == 'DE') || $landCode == 'DE' ){{ ' selected' }}@endif>DE</option>
                    </select>
                </td>
            </tr>
            <tr>
                <td>{{ __('Contact person') }}</td>
                <td><input type="text" name="contactpersoon" size="40" value="@if(old('contactpersoon')){{ old('contactpersoon') }}@else{{ $contactpersoon }}@endif"></td>
            </tr>
            <tr>
                <td>{{ __('Phone') }}</td>
                <td><input type="text" name="telefoon" size="20" value="@if(old('telefoon')){{ old('telefoon') }}@else{{ $telefoon }}@endif"></td>
            </tr>
            <tr>
                <td>Planon / {{ __('PO Number') }}</td>
                <td><input type="text" name="po_number" size="20" value="@if(old('po_number')){{ old('po_number') }}@else{{ $po_number }}@endif"></td>
            </tr>
            <tr>
                <td>{{ __('E-mail address') }}</td>
                <td><input type="text" name="eMailadres" size="40" value="@if(old('eMailadres')){{ old('eMailadres') }}@else{{ $eMailadres }}@endif"></td>
            </tr>
            <tr>
                <td>&nbsp;</td>
                <td><button type="submit" class="saveBtn">{{ __('Save') }}</button></td>
            </tr>
        </table>
    </form>
</div>
@endsection
