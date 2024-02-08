{{-- @extends('templates.development') --}}
@extends('templates.portal')
@section('content')
<div class="userContent">
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
    $notifyOnMinStock = 0;
    $backUrl = route('users');
    if(Route::currentRouteName() == 'new_admin') {
        $title = 'New admin';
        $isAdmin = 1;
        $backUrl = route('admins');
    }
@endphp
@if ($data['user'])
    {{-- edit --}}
    @php
        $title = 'Edit user';
        $method = 'put';
        $id = $data['user']->id;
        $name = $data['user']->name;
        $email = $data['user']->email;
        $klantCode = $data['user']->klantCode;
        $extra_email = ($data['user']->extra_email?$data['user']->extra_email:'[]');
        $privileges = ($data['user']->privileges?$data['user']->privileges:'[]');
        $canReserve = $data['user']->can_reserve;
        $notifyOnMinStock = $data['user']->notify_min_stock;
        $isAdmin = $data['user']->is_admin;
        if($isAdmin) {
            $backUrl = route('admins');
        }
    @endphp
@endif
<h1>{{ __($title) }}</h1>
{{-- <p><a href="{{ url()->previous() }}">< terug naar overzicht</a></p> --}}
<p><a href="{{ $backUrl }}" class="backBtn">{{ __('Back to overview') }}</a></p>
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
        <td>
            @if ($method == 'post')
                <input type="text" name="email" value="@if(old('email')){{ old('email') }}@else{{ $email }}@endif">
            @else
                {{ $email }}
            @endif
        </td>
    </tr>
    {{-- @if(!$id)
    <tr>
        <td>{{ __('Password') }}</td>
        <td><input type="password" name="password" value=""></td>
    </tr>
    @endif --}}
    <tr>
        <td>{{ __('Customer') }}</td>
        <td class="klantCode_td">
            @if(old('klantCode'))
            {{-- {{ print_r(old('klantCode')) }} --}}
                @foreach (old('klantCode') as $oldKCode)
                @if($oldKCode)
                <div>
                    <select name="klantCode[]">
                        <option value="">-geen-</option>
                        @foreach ($data['customers'] as $customer)
                            <option value="{{ $customer->klantCode }}" @if($customer->klantCode == $oldKCode) selected @endif>{{ $customer->naam }}({{ $customer->klantCode }})</option>
                        @endforeach
                    </select>
                </div>
                @endif
                @endforeach
            @else
            @if (isset($data['userCustomers']))
                @foreach($data['userCustomers'] as $oCust)
                    <div>
                        <select name="klantCode[]">
                            <option value="">-geen-</option>
                            @foreach ($data['customers'] as $customer)
                                <option value="{{ $customer->klantCode }}" @if($customer->klantCode == $oCust->klantCode) selected @endif>{{ $customer->naam }} ({{ $customer->klantCode }})</option>
                            @endforeach
                        </select>
                    </div>
                @endforeach
            @endif
            @endif

            @if($isAdmin)
                {{-- <input type="checkbox" name="all_clients" id="all_clients_checkbox" ><label for="all_clients_checkbox">{{ __('All clients') }}</label> --}}
                <button class="selectAllBtn" type="button">Selecteer all</button>
            @endif


            {{-- <div>
                <select name="klantCode[]">
                    <option value="">-geen-</option>
                    @foreach ($data['customers'] as $customer)
                        <option value="{{ $customer->klantCode }}">{{ $customer->naam }}({{ $customer->klantCode }})</option>
                    @endforeach
                </select>
            </div>
            
            <div>
                <select name="klantCode[]">
                    <option value="">-geen-</option>
                    @foreach ($data['customers'] as $customer)
                        <option value="{{ $customer->klantCode }}">{{ $customer->naam }}({{ $customer->klantCode }})</option>
                    @endforeach
                </select>
            </div> --}}
            
            {{-- @if($klantCode === null) Last known: {{ $data['user']->last_known_klantCode_name }} @endif --}}
        </td>
    </tr>
    @if($extra_email)
    <tr>
        <td>{{ __('Extra e-mail addresses') }}</td>
        <td>
            <div class="extraEmailSection">
                <div>
                    @foreach (array_column(json_decode($extra_email,true),'email') as $email)
                        <span>
                            {{ $email }}
                            <input type="hidden" name="current_extra_emails[]" value="{{ $email }}">
                            <input type="submit" name="current_extra_emails[]" value="{{ __('Remove') }}" class="removeEmail" onclick="return confirm('You are about to delete {{ $email }}\n\nAre you sure?')" />
                        </span>
                    @endforeach
                </div>
                <div>
                    {{ __('Add extra e-mail address') }}:
                    <input type="text" name="extra_email">
                    <input type="submit" name="add_email_btn" class="addEmail" value="{{ __('Add') }}" />
                </div>
            </div>
        </td>
    </tr>
    @endif
    <tr>
        <td>{{ __('Interface') }}</td>
        <td>
            {{-- @foreach (['show_tiles', 'free_search', 'lotcode_search'] as $privilege) --}}
            @foreach (config('hulshoff.privileges') as $privilege)
                @php
                    $privText = $privilege;
                    if($privilege == 'show_tiles') $privText = 'Show tiles';
                    if($privilege == 'filter_on_top') $privText = 'Filter on top';
                    if($privilege == 'filter_at_side') $privText = 'Filter at side';
                @endphp
                <div><input type="checkbox" name="privileges[]" value="{{ $privilege }}" id="{{ $privilege }}" @if((old('privileges') && in_array($privilege, old('privileges'))) || (in_array($privilege, json_decode($privileges,true)) && !$errors->any())) checked @endif><label for="{{ $privilege }}">{{ __($privText) }}</label></div>
            @endforeach
        </td>
    </tr>
    <tr>
        <td>{{ __('Can reserve') }}?</td>
        <td>
            <input type="checkbox" name="can_reserve" id="canreserve" @if($canReserve) checked @endif><label for="canreserve">{{ __('Yes') }}</label>
        </td>
    </tr>
    <tr>
        <td>{{ __('Is admin') }}?</td>
        <td>
            <input type="checkbox" name="is_admin" id="isadmin" @if($isAdmin) checked @endif><label for="isadmin">{{ __('Yes') }}</label>
        </td>
    </tr>
    <tr>
        <td>{{ __('Ontvang e-mail bij bereiken product minimale voorraad') }}?</td>
        <td>
            <input type="checkbox" name="notify_min_stock" id="reach_prod_min_stock" @if($notifyOnMinStock) checked @endif><label for="reach_prod_min_stock">{{ __('Yes') }}</label>
        </td>
    </tr>
    <tr>
        <td>&nbsp;</td>
        <td><button type="submit" class="saveBtn">{{ __('Save') }}</button></td>
    </tr>
</table>
</form>
</div>
{{-- @if ($errors->any())
    <ul>
        @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
        @endforeach
    </ul>
@endif --}}
@endsection
@section('before_closing_body_tag')
<script>
    const filterTop = document.querySelector('#filter_on_top');
    const filterSide = document.querySelector('#filter_at_side');
    // const clients = document.querySelectorAll('[name="klantCode[]"]');
    const klantCodeSelectsWrap = document.querySelector('.klantCode_td');
    const selectAllBtn = document.querySelector('.selectAllBtn');
    const customers = [];
    @foreach ($data['customers'] as $customer)
        // customers['{{ $customer->klantCode }}'] = '{{ $customer->naam }}';
        var cust = {
            'klantCode': '{{ $customer->klantCode }}',
            'naam': '{{ $customer->naam }}'
        };
        customers.push(cust);
    @endforeach

// console.log(customers);

    selectAllBtn.addEventListener('click', () => {
        customers.forEach(cust => {
            let lastSelectBoxWrapper = klantCodeSelectsWrap.querySelector('* > div:last-child');
            let selectBox = lastSelectBoxWrapper.querySelector('select');
            selectBox.value = cust.klantCode;
            let changeEvent = new Event('change');
            selectBox.dispatchEvent(changeEvent);
        });
    });
    
    checkSelectSlots();

    function checkSelectSlots() {
// console.log('_checkSelectSlots');
        const clients = document.querySelectorAll('[name="klantCode[]"]');
        let freeSlots = false;
        clients.forEach(el => {
            el.addEventListener('change', () => {
                checkSelectSlots();
            });
            if(el.value === '') freeSlots = true;
        });
        if(!freeSlots) {
            addSelectSlot();
        }
    }

    function addSelectSlot() {
// console.log('_addSelectSlot');
        const div = document.createElement('div');
        const select = document.createElement('select');
        const emptyOption = document.createElement('option');
        const emptyText = document.createTextNode('-geen-');
        emptyOption.appendChild(emptyText);
        select.setAttribute('name', 'klantCode[]');
        emptyOption.value = '';
        select.appendChild(emptyOption);
        div.appendChild(select);


        Object.keys(customers).forEach(key => {
            let option = document.createElement('option');
            let text = document.createTextNode(customers[key]['naam'] + ' (' + customers[key]['klantCode'] + ')');
            option.appendChild(text);
            option.value = customers[key]['klantCode'];
            select.appendChild(option);
        });


        select.addEventListener('change', () => {
            checkSelectSlots();
        });

        klantCodeSelectsWrap.appendChild(div);
    }

    filterTop.addEventListener('change', () => {
        if(filterTop.checked) filterSide.checked = false;
    });
    filterSide.addEventListener('change', () => {
        if(filterSide.checked) filterTop.checked = false;
    });

    document.addEventListener('keydown', (e) => {
        if(e.keyCode === 13 || e.which === 13) {
            e.preventDefault();
            return false;
        }
    });
</script>
@endsection