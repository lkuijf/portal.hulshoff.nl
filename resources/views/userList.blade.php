{{-- @extends('templates.development') --}}
@extends('templates.portal')
@section('content')
    @include('snippets.userList', ['users' => $data])
@endsection