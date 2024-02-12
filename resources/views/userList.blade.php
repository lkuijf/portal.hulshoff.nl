{{-- @extends('templates.development') --}}
@extends('templates.portal')
@section('content')
<div class="userListContent">
<h1>{{ __('Overview') }} @if($data['type'] == 0){{ Str::lower(__('Users')) }}@elseif($data['type'] == 1){{ Str::lower(__('Admins')) }}@endif</h1>
@if($data['type'] == 0)
    <p>{{ __('Overview of all users. Users can be connected to a client') }}.</p>
@elseif($data['type'] == 1)
    <p>{{ __('Overview of all admins. An admin can add and mutate users') }}.</p>
@endif
<p><a href="@if($data['type'] == 0){{ route('new_user') }}@elseif($data['type'] == 1){{ route('new_admin') }}@endif" class="addBtn">{{ __('Create new') }}</a></p>
<form action="" method="GET">
    @csrf
    <label for="freesearch">{{ __('Free search') }}</label>
    <input type="text" name="search" id="freesearch">
    <button type="submit">{{ __('Search') }}</button>
</form>
<table>
    <thead>
        <tr>
            <th>{{ __('Name') }}</th>
            <th>{{ __('E-mail address') }}</th>
            {{-- <th>{{ __('Client') }}</th> --}}
            <th>{{ __('Extra e-mail addresses') }}</th>
            {{-- <th>Interface</th> --}}
            <th>{{ __('Can reserve') }}?</th>
            <th>{{ __('Enabled 2FA') }}?</th>
            <th>&nbsp;</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($data['users'] as $user)
        <tr>
            <td>{{ $user->name }}</td>
            <td>{{ $user->email }}</td>
            {{-- <td>{{ $user->klantCode }}</td> --}}
            <td>@if($user->extra_email !== null){{ implode(', ', array_column(json_decode($user->extra_email,true),'email')) }}@endif</td>
            {{-- <td>@if($user->privileges !== null){{ implode(', ', json_decode($user->privileges,true)) }}@endif</td> --}}
            {{-- @if($user->privileges !== null)@dd($user->privileges)@endif --}}
            {{-- <td>@if($user->privileges !== null){{ implode(', ', json_decode('[aasasd,66]',true)) }}@endif</td> --}}
            <td>{{ $user->can_reserve?'Ja':'Nee' }}</td>
            <td>
                @if($user->two_factor_confirmed_at)
                    {{ __('Yes') }} ({{ __('since') }} {{ date('d-m-Y', strtotime($user->two_factor_confirmed_at)) }})
                    <form action="/user" method="post">
                        @method('put')
                        @csrf
                        <input type="hidden" name="id" value="{{ $user->id }}">
                        <input type="hidden" name="reset2fa" value="1">
                        @if ($user->id != 1)
                            <button type="submit" onclick="return confirm('{{ __('You are about to reset the Two Factor Authentication for user') }} {{ $user->name }} ({{ $user->email }})\n\n{{ __('Are you sure') }}?')" class="deleteBtn"></button>
                        @endif
                    </form>
                @else
                    {{ __('No') }}
                @endif
            </td>
            <td>
                <a href="@if($data['type'] == 0){{ route('users') }}@elseif($data['type'] == 1){{ route('admins') }}@endif/{{ $user->id }}" class="editBtn">{{ __('Edit') }}</a>
                {{-- <a href="">[remove]</a> --}}
                <form action="/user" method="post">
                    @method('delete')
                    @csrf
                    <input type="hidden" name="id" value="{{ $user->id }}">
                    @if ($user->id != 1)
                        <button type="submit" onclick="return confirm('{{ __('You are about to delete user') }} {{ $user->name }} ({{ $user->email }})\n\n{{ __('Are you sure') }}?')" class="deleteBtn"></button>
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