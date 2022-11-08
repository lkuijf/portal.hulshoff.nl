{{-- new --}}
@php
    $title = 'New user';
    $method = 'post';
    $id = false;
    $name = '';
    $email = '';
    $klantCode = false;
    $extra_email = false;
    $privileges = '[]';
@endphp
@if ($user)
    {{-- edit --}}
    @php
        $title = 'Edit user';
        $method = 'put';
        $id = $user->id;
        $name = $user->name;
        $email = $user->email;
        $klantCode = $user->klantCode;
        $extra_email = $user->extra_email;
        $privileges = $user->privileges;
    @endphp
@endif
<h1>{{ __($title) }}</h1>
<p><a href="{{ route('users') }}">< terug naar overzicht</a></p>
<form action="{{ url('user') }}" method="post">
@method($method)
@csrf
@if($id)<input type="hidden" name="id" value="{{ $id }}">@endif
<table>
    <tr>
        <td>{{ __('Name') }}</td>
        <td><input type="text" name="name" value="{{ $name }}"></td>
    </tr>
    <tr>
        <td>{{ __('E-mail address') }}</td>
        <td><input type="text" name="email" value="{{ $email }}"></td>
    </tr>
    <tr>
        <td>{{ __('Customer') }}</td>
        <td>
            <select name="klantCode">
                <option value="">-selecteer-</option>
                @foreach ($customers as $customer)
                    <option value="{{ $customer->klantCode }}" @if($customer->klantCode == $klantCode) selected @endif>{{ $customer->naam }}({{ $customer->klantCode }})</option>
                @endforeach
            </select>
            @if($klantCode === null) Last known: {{ $user->last_known_klantCode_name }} @endif
        </td>
    </tr>
    @if($extra_email)
    <tr>
        <td>{{ __('Extra e-mail addresses') }}</td>
        <td>
            <div>
                @foreach (array_column(json_decode($extra_email,true),'email') as $email)
                    <span>{{ $email }}<a href="">[remove]</a></span>
                @endforeach
            </div>
            <div>{{ __('Add extra e-mail address') }}: <input type="text" name="extra_email"><button>{{ __('Add') }}</button></div>
        </td>
    </tr>
    @endif
    <tr>
        <td>{{ __('Privileges') }}</td>
        <td>
            @foreach (['aaa', 'show_tiles', 'free_search', 'lotcode_search', 'yyy', 'zzz'] as $privilege)
                <div><input type="checkbox" name="privileges[]" value="{{ $privilege }}" id="{{ $privilege }}" @if(in_array($privilege, json_decode($privileges,true))) checked @endif><label for="{{ $privilege }}">{{ $privilege }}</label></div>
            @endforeach
        </td>
    </tr>
    <tr>
        <td>&nbsp;</td>
        <td><button type="submit">{{ __('Save') }}</button></td>
    </tr>
</table>
</form>
@if ($errors->any())
    {{-- <div class="alert alert-danger"> --}}
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    {{-- </div> --}}
@endif
