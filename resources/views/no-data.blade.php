@extends('templates.portal')
@section('content')
    <div class="noDataContent">
        <h1>{{ __('No data found') }}</h1>
        <p>- {{ __('Check if you have selected a customer') }}.</p>
        <p>- {{ __('Check if you have enabled Two Factor Authentication') }}.</p>
    </div>
@endsection