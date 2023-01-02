{{-- @extends('templates.development') --}}
@extends('templates.portal')
@section('content')
<div class="userListContent">
{{-- @if (auth()->user()->is_admin) --}}
<h1>{{ __('Overview') }} @if($data['type'] == 0){{ __('users') }}@elseif($data['type'] == 1){{ __('administrators') }}@endif</h1>
{{-- <p><a href="{{ route('new_user') }}">[new user]</a></p> --}}
<p><a href="@if($data['type'] == 0){{ route('new_user') }}@elseif($data['type'] == 1){{ route('new_admin') }}@endif">[create new]</a></p>
<table>
    <thead>
        <tr>
            <th>Name</th>
            <th>E-mail adres</th>
            <th>Klant</th>
            <th>Extra E-mail adressen</th>
            <th>Privileges</th>
            <th>Can reserve?</th>
            <th>&nbsp;</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($data['users'] as $user)
        <tr>
            <td>{{ $user->name }}</td>
            <td>{{ $user->email }}</td>
            <td>{{ $user->klantCode }}</td>
            <td>@if($user->extra_email !== null){{ implode(', ', array_column(json_decode($user->extra_email,true),'email')) }}@endif</td>
            <td>@if($user->privileges !== null){{ implode(', ', json_decode($user->privileges,true)) }}@endif</td>
            {{-- @if($user->privileges !== null)@dd($user->privileges)@endif --}}
            {{-- <td>@if($user->privileges !== null){{ implode(', ', json_decode('[aasasd,66]',true)) }}@endif</td> --}}
            <td>{{ $user->can_reserve?'Ja':'Nee' }}</td>
            <td>
                <a href="@if($data['type'] == 0){{ route('users') }}@elseif($data['type'] == 1){{ route('admins') }}@endif/{{ $user->id }}">[edit]</a>
                {{-- <a href="">[remove]</a> --}}
                <form action="/user" method="post">
                    @method('delete')
                    @csrf
                    <input type="hidden" name="id" value="{{ $user->id }}">
                    @if ($user->id != 1)
                        <button type="submit" onclick="return confirm('You are about to delete user {{ $user->name }} ({{ $user->email }})\n\nAre you sure?')">Delete</button>
                    @endif
                </form>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>
{{-- @else
GEEN TOEGANG
@endif --}}
</div>
@endsection