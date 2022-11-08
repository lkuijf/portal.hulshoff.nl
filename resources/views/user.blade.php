{{-- @extends('templates.development') --}}
@extends('templates.portal')
@section('content')
    @include('snippets.user', ['user' => $data['user'], 'customers' => $data['customers']])
@endsection