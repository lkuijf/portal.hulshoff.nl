{{-- @extends('templates.development') --}}
@extends('templates.portal')
@section('content')
<div class="userListContent">
{{-- @if (auth()->user()->is_admin) --}}
<h1>{{ __('Overview') }} @if($data['type'] == 'users'){{ __('users') }}@elseif($data['type'] == 'admins'){{ __('administrators') }}@endif</h1>
{{-- <p><a href="{{ route('new_user') }}">[new user]</a></p> --}}
<p><a href="@if($data['type'] == 'users'){{ route('new_user') }}@elseif($data['type'] == 'admins'){{ route('new_admin') }}@endif">[new {{ substr($data['type'], 0, -1) }}]</a></p>
<table>
    <tr>
        <th>Name</th>
        <th>E-mail adres</th>
        <th>Klant</th>
        <th>Extra E-mail adressen</th>
        <th>Privileges</th>
        <th>Can reserve?</th>
        <th>&nbsp;</th>
    </tr>
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
            <a href="{{ route('users') }}/{{ $user->id }}">[edit]</a>
            {{-- <a href="">[remove]</a> --}}
            <form action="/user" method="post">
                @method('delete')
                @csrf
                <input type="hidden" name="id" value="{{ $user->id }}">
                <button type="submit" onclick="return confirm('You are about to delete user {{ $user->name }} ({{ $user->email }})\n\nAre you sure?')">Delete</button>
            </form>
        </td>
    </tr>
    @endforeach
</table>
{{-- @else
GEEN TOEGANG
@endif --}}
</div>
@endsection