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
    $canReserve = 0;
    $isAdmin = 0;
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
        $extra_email = ($user->extra_email?$user->extra_email:'[]');
        $privileges = ($user->privileges?$user->privileges:'[]');
        $canReserve = $user->can_reserve;
        $isAdmin = $user->is_admin;
    @endphp
@endif
<h1>{{ __($title) }}</h1>
<p><a href="{{ url()->previous() }}">< terug naar overzicht</a></p>
<form action="{{ url('user') }}" method="post">
@method($method)
@csrf
@if($id)<input type="hidden" name="id" value="{{ $id }}">@endif
<table>
    <tr>
        <td>{{ __('Name') }}</td>
        <td><input type="text" name="name" value="@if(old('name')){{ old('name') }}@else{{ $name }}@endif"></td>
    </tr>
    <tr>
        <td>{{ __('E-mail address') }}</td>
        <td><input type="text" name="email" value="@if(old('email')){{ old('email') }}@else{{ $email }}@endif"></td>
    </tr>
    <tr>
        <td>{{ __('Customer') }}</td>
        <td>
            <select name="klantCode">
                <option value="">-geen-</option>
                @foreach ($customers as $customer)
                    <option value="{{ $customer->klantCode }}" @if((old('klantCode') && $customer->klantCode == old('klantCode')) || $customer->klantCode == $klantCode) selected @endif>{{ $customer->naam }}({{ $customer->klantCode }})</option>
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
                    <span>
                        {{ $email }}
                        <input type="hidden" name="current_extra_emails[]" value="{{ $email }}">
                        <input type="submit" name="current_extra_emails[]" value="Remove" onclick="return confirm('You are about to delete {{ $email }}\n\nAre you sure?')" />
                    </span>
                @endforeach
            </div>
            <div>
                {{ __('Add extra e-mail address') }}:
                <input type="text" name="extra_email">
                <input type="submit" name="add_email_btn" value="Add" />
            </div>
        </td>
    </tr>
    @endif
    <tr>
        <td>{{ __('Privileges') }}</td>
        <td>
            @foreach (['aaa', 'show_tiles', 'free_search', 'lotcode_search', 'yyy', 'zzz'] as $privilege)
                <div><input type="checkbox" name="privileges[]" value="{{ $privilege }}" id="{{ $privilege }}" @if((old('privileges') && in_array($privilege, old('privileges'))) || (in_array($privilege, json_decode($privileges,true)) && !$errors->any())) checked @endif><label for="{{ $privilege }}">{{ $privilege }}</label></div>
            @endforeach
        </td>
    </tr>
    <tr>
        <td>{{ __('Can reserve?') }}</td>
        <td>
            <input type="checkbox" name="can_reserve" id="canreserve" @if($canReserve) checked @endif><label for="canreserve">{{ __('Yes') }}</label>
        </td>
    </tr>
    <tr>
        <td>{{ __('Is administrator?') }}</td>
        <td>
            <input type="checkbox" name="is_admin" id="isadmin" @if($isAdmin) checked @endif><label for="isadmin">{{ __('Yes') }}</label>
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
@section('before_closing_body_tag')
<script>
    document.addEventListener('keydown', (e) => {
        if(e.keyCode === 13 || e.which === 13) {
            e.preventDefault();
            return false;
        }
    });
</script>
@endsection