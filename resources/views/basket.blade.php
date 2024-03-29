@extends('templates.portal')
@section('content')
@php
    if(session()->has('deliveryDate')) {
        $deliveryDate = session('deliveryDate');
    } else {
        $deliveryDate = date("d-m-Y", strtotime('next week'));
    }
@endphp
<div class="basketContent">
    @php
        $totalOrderSum = 0;
    @endphp
    @if ($requestType == 'order')<h1>{{ __('Basket') }}</h1>@endif
    @if ($requestType == 'return-order')<h1>{{ __('Return order') }}</h1>@endif
    @if (count($basket))
        <table>
            <thead>
                @if ($requestType == 'order')
                <tr>
                    <th>id</th>
                    <th>Product</th>
                    <th>{{ __('Amount') }}</th>
                    <th>{{ __('Price') }}</th>
                    <th>{{ __('Total') }} {{ Str::lower(__('Price')) }}</th>
                    <th>&nbsp;</th>
                </tr>
                @endif
                @if ($requestType == 'return-order')
                <tr>
                    <th>Product</th>
                    <th>{{ __('Amount') }}</th>
                </tr>
                @endif
            </thead>
            <tbody>
                @if ($requestType == 'order')
                @foreach ($basket as $item)
                @php
                    $totalOrderSum += $item['product']->prijs*$item['count'];
                @endphp
                <tr>
                    <td>{{ $item['product']->id }}</td>
                    <td>{{ $item['product']->omschrijving }}</td>
                    <td><span>{{ $item['count'] }} <a href="" class="editBtn editBasketCount" data-product-id="{{ $item['product']->id }}" data-product-count="{{ $item['count'] }}">{{ __('Edit') }}</a></span></td>
                    <td>&euro;{{ number_format($item['product']->prijs, 2, ',', '.') }}</td>
                    <td>&euro;{{ number_format($item['product']->prijs*$item['count'], 2, ',', '.') }}</td>
                    <td>
                        <form class="deleteFromBasketForm" action="{{ route('basket') }}" method="post">
                            @method('delete')
                            @csrf
                            <input type="hidden" name="id" value="{{ $item['product']->id }}">
                            <button type="submit" onclick="return confirm('{{ __('You are about to delete product') }} {{ $item['product']->omschrijving }} {{ __('from your basket') }}.\n\n{{ __('Are you sure') }}?')" class="deleteBtn"></button>
                        </form>
                    </td>
                </tr>
                @endforeach
                @endif
                @if ($requestType == 'return-order')
                @foreach ($basket as $item)
                <tr>
                    <td>{{ $item['product'] }}</td>
                    <td>{{ $item['count'] }}</td>
                </tr>
                @endforeach
                @endif
            </tbody>
        </table>
        @if ($requestType == 'order')<p><strong>{{ __('Total value of your order') }}: &euro;{{ number_format($totalOrderSum, 2, ',', '.') }}</strong></p>@endif
        @if ($requestType == 'order')<h2>{{ __('Delivery date') }}</h2>@endif
        @if ($requestType == 'return-order')<h2>{{ __('Pickup date') }}</h2>@endif
        <form action="{{ url('order') }}" method="post">
            @csrf
            @if ($requestType == 'return-order')
                <input type="hidden" name="type" value="{{ $requestType }}">
            @endif
            <p>
                {{-- <span class="deliveryTxt">Afleverdatum</span><input type="input" name="deliveryDate" value="{{ $deliveryDate }}"> --}}
                {{-- <p>Afleverdatum</p> --}}
                <p><span>{{ $deliveryDate }}<a class="editBtn editBasketDate" data-order-date="{{ $deliveryDate }}" href="">{{ __('Edit') }}</a></span></p>
            </p>
            {{-- <p>
                <span class="deliveryTxt">Aflevertijd (hh:mm)</span><select name="deliveryHour">
                    <option value="">-</option>
                    <option value="00">00</option>
                    <option value="01">01</option>
                    <option value="02">02</option>
                    <option value="03">03</option>
                    <option value="04">04</option>
                    <option value="05">05</option>
                    <option value="06">06</option>
                    <option value="07">07</option>
                    <option value="08">08</option>
                    <option value="09">09</option>
                    <option value="10">10</option>
                    <option value="11">11</option>
                    <option value="12">12</option>
                    <option value="13">13</option>
                    <option value="14">14</option>
                    <option value="15">15</option>
                    <option value="16">16</option>
                    <option value="17">17</option>
                    <option value="18">18</option>
                    <option value="19">19</option>
                    <option value="20">20</option>
                    <option value="21">21</option>
                    <option value="22">22</option>
                    <option value="23">23</option>
                </select>
                :
                <select name="deliveryMinute">
                    <option value="">-</option>
                    <option value="00">00</option>
                    <option value="05">05</option>
                    <option value="10">10</option>
                    <option value="15">15</option>
                    <option value="20">20</option>
                    <option value="25">25</option>
                    <option value="30">30</option>
                    <option value="35">35</option>
                    <option value="40">40</option>
                    <option value="45">45</option>
                    <option value="50">50</option>
                    <option value="55">55</option>
                </select>
            </p> --}}
            @if ($requestType == 'order')<h2>{{ __('Delivery address') }}</h2>@endif
            @if ($requestType == 'return-order')<h2>{{ __('Pickup address') }}</h2>@endif
            <p>{{ __('Select a default addres or enter it manually') }}.</p>
            <select name="address">
                <option value="">- {{ __('Select an address') }} -</option>
                @foreach ($addresses as $address)
                    <option value="{{ $address->id }}" data-naw="{{ $address->straat }} {{ $address->huisnummer }}\n{{ $address->postcode }} {{ $address->plaats }}\n{{ $address->contactpersoon }}\n{{ $address->telefoon }}\n{{ $address->eMailadres }}">{{ $address->naam }}</option>
                @endforeach
            </select>

            {{-- @if (old('customAddressCheckbox')){{ ' checked' }}@endif --}}

            <p><input type="checkbox" name="customAddressCheckbox"><a href="" class="customAddress">{{ __('Enter an address manually') }}</a></p>

            <table class="manualAddress">
                <tr>
                    <td>{{ __('Street') }}</td>
                    <td><input type="text" name="straat" size="40" value="{{ old('straat') }}"></td>
                </tr>
                <tr>
                    <td>{{ __('House number') }}</td>
                    <td><input type="text" name="huisnummer" size="5" value="{{ old('huisnummer') }}"></td>
                </tr>
                <tr>
                    <td>{{ __('Zipp code') }}</td>
                    <td><input type="text" name="postcode" size="10" value="{{ old('postcode') }}"></td>
                </tr>
                <tr>
                    <td>{{ __('City') }}</td>
                    <td><input type="text" name="plaats" size="40" value="{{ old('plaats') }}"></td>
                </tr>
                <tr>
                    <td>{{ __('Contact person') }}</td>
                    <td><input type="text" name="contactpersoon" size="40" value="{{ old('contactpersoon') }}"></td>
                </tr>
                <tr>
                    <td>Planon / {{ __('PO Number') }}</td>
                    <td><input type="text" name="po_number" size="20" value="{{ old('po_number') }}"></td>
                </tr>
                <tr>
                    <td>{{ __('Phone') }}</td>
                    <td><input type="text" name="telefoon" size="20" value="{{ old('telefoon') }}"></td>
                </tr>
                <tr>
                    <td>{{ __('Additional information') }}</td>
                    <td>
                        <textarea name="information" cols="40" rows="5" placeholder="Bijvoorbeeld informatie over openingstijden / etage / aanwezigheid lift / gebouwcode etc.">{{ old('information') }}</textarea>
                    </td>
                </tr>
            </table>

            <div>
                <div class="deliveryNaw"></div>
            </div>
            @if (session()->has('selectedClient'))
                @if ($requestType == 'order')
                    @if (auth()->user()->can_reserve)
                    <button>{{ __('Confirm reservation') }}</button>
                    @else
                    <button onclick="return confirm('{{ __('Your order will be delivered on') }} {{ $deliveryDate }}.\n\n{{ __('Are you sure you want to confirm your order') }}?')">Order bevestigen</button>
                    @endif
                @endif
                @if ($requestType == 'return-order')
                    <button>{{ __('Confirm return order') }}</button>
                @endif
            @else
                CANNOT ORDER, NO CLIENT SELECTED
            @endif

        </form>
    @else
        <p>Basket is empty.</p>
    @endif
</div>
@endsection
@section('extra_head')
    <link rel="stylesheet" href="{{ asset('css/datepicker.min.css') }}">
    {{-- <link rel="stylesheet" href="{{ asset('css/timepicker.css') }}"> --}}
    <script src="{{ asset('js/datepicker-full.min.js') }}"></script>
    {{-- <script src="{{ asset('js/timepicker.js') }}"></script> --}}
@endsection
@section('before_closing_body_tag')
<script>
    const addressSelect = document.querySelector('select[name="address"]');
    const nawBox = document.querySelector('.deliveryNaw');
    const customAddressBtn = document.querySelector('.customAddress');
    const manualAddressTable = document.querySelector('.manualAddress');
    const customAddressCheckbox = document.querySelector('input[name=customAddressCheckbox]');

    getPreservedAddress();

    customAddressBtn.addEventListener('click', (e) => {
        e.preventDefault();
        toggleCheckboxState()
    });

    customAddressCheckbox.addEventListener('click', () => {
        if(customAddressCheckbox.checked) {
            if(!manualAddressTable.style.display || manualAddressTable.style.display == 'none') {
                manualAddressTable.style.display = 'block';
                addressSelect.selectedIndex = 0;
                addressSelect.dispatchEvent(new Event('change'));
            }
        } else {
            manualAddressTable.style.display = 'none';
            removeCustomAddressValues(manualAddressTable);
        }
    });

    @if (old('customAddressCheckbox'))
    toggleCheckboxState()
    @endif

    function toggleCheckboxState() {
        if(customAddressCheckbox.checked) customAddressCheckbox.checked = false;
        else customAddressCheckbox.checked = true;
        customAddressCheckbox.dispatchEvent(new Event('click'));
    }

    if(addressSelect) {
        addressSelect.addEventListener('change', () => {
            if(addressSelect.value) {
                let selectedNawInfo = addressSelect.options[addressSelect.selectedIndex].dataset.naw
                nawBox.innerHTML = selectedNawInfo.replaceAll('\\n', '<br>');
                nawBox.style.borderColor = '#CCC';

                manualAddressTable.style.display = 'none';
                removeCustomAddressValues(manualAddressTable);
                if(customAddressCheckbox.checked) customAddressCheckbox.checked = false;

            } else {
                nawBox.innerHTML = '';
                nawBox.style.borderColor = '#FFF';
            }
        });
    }

    function removeCustomAddressValues(addressTable) {
        manualInputs = addressTable.querySelectorAll('input');
        manualTextarea = addressTable.querySelector('textarea');
        manualInputs.forEach(input => {
            input.value = '';
        });
        manualTextarea.value = '';
    }

    function preserveAddress() {
        let info = {};
        info['addressSelectValue'] = false;
        info['customAddressCheckboxValue'] = false;
        info['customAddress'] = false;
        if(addressSelect.value) {
            //save value
            info['addressSelectValue'] = addressSelect.value;
        }
        if(customAddressCheckbox.checked) {
            //save checked
            info['customAddressCheckboxValue'] = customAddressCheckbox.checked;

            $address = {};
            $address['straat'] = manualAddressTable.querySelector('input[name="straat"]').value
            $address['huisnummer'] = manualAddressTable.querySelector('input[name="huisnummer"]').value
            $address['postcode'] = manualAddressTable.querySelector('input[name="postcode"]').value
            $address['plaats'] = manualAddressTable.querySelector('input[name="plaats"]').value
            $address['contactpersoon'] = manualAddressTable.querySelector('input[name="contactpersoon"]').value
            $address['po_number'] = manualAddressTable.querySelector('input[name="po_number"]').value
            $address['telefoon'] = manualAddressTable.querySelector('input[name="telefoon"]').value
            $address['information'] = manualAddressTable.querySelector('textarea[name="information"]').value

            info['customAddress'] = $address
        }

        axios.post('{{ url('/ajax/setBasketAddress') }}', {address_info:info})
            .then(function (response) {
                // handle success
                if(response.data.success == true) {
                    // console.log('OK!');
                }
            })
            .catch(function (error) {
                // handle error
                console.log(error);
            })
            .then(function () {
                // always executed
            });
    }
    function getPreservedAddress() {
        axios.post('{{ url('/ajax/getBasketAddress') }}')
            .then(function (response) {
                // handle success
                if(response.data.success == true) {
                    // console.log(response.data.address);
                    // preservedAddress = response.data.address;
                    populatePreservedAddress(response.data.address);
                    // return preservedAddress;
                }
            })
            .catch(function (error) {
                // handle error
                console.log(error);
            })
            .then(function () {
                // always executed
            });
    }
    function populatePreservedAddress(addr) {
        console.log(addr);
        if(addr.addressSelectValue) {
            addressSelect.value = addr.addressSelectValue;
            addressSelect.dispatchEvent(new Event('change'));
        }
        if(addr.customAddressCheckboxValue) {
            customAddressCheckbox.checked = true;
            customAddressCheckbox.dispatchEvent(new Event('click'));

            manualAddressTable.querySelector('input[name="straat"]').value = addr.customAddress.straat;
            manualAddressTable.querySelector('input[name="huisnummer"]').value = addr.customAddress.huisnummer;
            manualAddressTable.querySelector('input[name="postcode"]').value = addr.customAddress.postcode;
            manualAddressTable.querySelector('input[name="plaats"]').value = addr.customAddress.plaats;
            manualAddressTable.querySelector('input[name="contactpersoon"]').value = addr.customAddress.contactpersoon;
            manualAddressTable.querySelector('input[name="po_number"]').value = addr.customAddress.po_number;
            manualAddressTable.querySelector('input[name="telefoon"]').value = addr.customAddress.telefoon;
            manualAddressTable.querySelector('textarea[name="information"]').value = addr.customAddress.information;

        }
    }

    const editDateBtn = document.querySelector('.editBasketDate');
    if(editDateBtn) { // basket is empty, no button present
        editDateBtn.addEventListener('click', (e) => {
            e.preventDefault();
            let parentNode = editDateBtn.parentNode.parentNode;
            let originalSpan = editDateBtn.parentNode;

            let csrfToken = document.querySelector('meta[name="_token"]').content;
            let oDate = editDateBtn.dataset.orderDate;
            let editForm = document.createElement('form');
            let editInput = document.createElement('input');
            let editHiddenMethod = document.createElement('input');
            let editHiddenToken = document.createElement('input');
            let editSave = document.createElement('button');

            editSave.addEventListener('click', (e) => {
                e.preventDefault();
                preserveAddress();
                setTimeout(() => {
                    editForm.submit();
                }, 500);
            });

            let editCancel = document.createElement('a');
            editForm.setAttribute('action', '/basket');
            editForm.setAttribute('method', 'post');
            editInput.setAttribute('type', 'text');
            editInput.setAttribute('size', '12');
            editInput.setAttribute('name', 'deliveryDate');
            editInput.setAttribute('value', oDate);
            editHiddenMethod.setAttribute('type', 'hidden');
            editHiddenMethod.setAttribute('name', '_method');
            editHiddenMethod.setAttribute('value', 'put');
            editHiddenToken.setAttribute('type', 'hidden');
            editHiddenToken.setAttribute('name', '_token');
            editHiddenToken.setAttribute('value', csrfToken);
            editSave.setAttribute('type', 'submit');
            editCancel.setAttribute('href', '');

            let saveBtnText = document.createTextNode('Save');
            let cancelText = document.createTextNode('Cancel');
            editSave.appendChild(saveBtnText);
            editCancel.appendChild(cancelText);

            editForm.append(editHiddenMethod, editHiddenToken, editInput, editSave, editCancel);

            new Datepicker(editInput, {
                format: 'dd-mm-yyyy'
            });

            parentNode.replaceChild(editForm, originalSpan);

            editCancel.addEventListener('click', (e) => {
                e.preventDefault();
                parentNode.replaceChild(originalSpan, editForm);
                toggleVisibility([editDateBtn]);
            });
            toggleVisibility([editDateBtn]);
            
        });
    }
    function toggleVisibility(elements) {
        elements.forEach(element => {
            if(element.style.visibility != 'hidden') {
                element.style.visibility = 'hidden';
            } else {
                element.style.visibility = '';
            }
        });
    }






    const delForms = document.querySelectorAll('.deleteFromBasketForm');
    delForms.forEach(formEl => {
        formEl.addEventListener('submit', (e) => {
            e.preventDefault();
            // adding current delivery date, so the form can be repopulated
            // let deliveryDateInput = document.createElement('input');
            // deliveryDateInput.setAttribute('type', 'hidden');
            // deliveryDateInput.setAttribute('name', 'deliveryDate');
            // deliveryDateInput.setAttribute('value', delDate.value);
            // formEl.append(deliveryDateInput);
            formEl.submit();
        });
    });


    const editCountBtns = document.querySelectorAll('.editBasketCount');
    editCountBtns.forEach(btn => {
        btn.addEventListener('click', (e) => {
            e.preventDefault();
            let parentTd = btn.parentNode.parentNode;
            let originalSpan = btn.parentNode;

            let csrfToken = document.querySelector('meta[name="_token"]').content;

            let pId = btn.dataset.productId;
            let pCt = btn.dataset.productCount;

            let editForm = document.createElement('form');
            let editInput = document.createElement('input');
            let editHiddenMethod = document.createElement('input');
            let editHiddenToken = document.createElement('input');
            let editHiddenId = document.createElement('input');
            // let editHiddenDeliveryDate = document.createElement('input');
            let editSave = document.createElement('button');

            editSave.addEventListener('click', (e) => {
                e.preventDefault();
                preserveAddress();
                setTimeout(() => {
                    editForm.submit();
                }, 500);
            });

            let editCancel = document.createElement('a');
            editForm.setAttribute('action', '/basket');
            editForm.setAttribute('method', 'post');
            editInput.setAttribute('type', 'text');
            editInput.setAttribute('size', '5');
            editInput.setAttribute('name', 'count');
            editInput.setAttribute('value', pCt);
            editHiddenMethod.setAttribute('type', 'hidden');
            editHiddenMethod.setAttribute('name', '_method');
            editHiddenMethod.setAttribute('value', 'put');
            editHiddenToken.setAttribute('type', 'hidden');
            editHiddenToken.setAttribute('name', '_token');
            editHiddenToken.setAttribute('value', csrfToken);
            editHiddenId.setAttribute('type', 'hidden');
            editHiddenId.setAttribute('name', 'id');
            editHiddenId.setAttribute('value', pId);

            // adding current delivery date, so the form can be repopulated
            // editHiddenDeliveryDate.setAttribute('type', 'hidden');
            // editHiddenDeliveryDate.setAttribute('name', 'deliveryDate');
            // editHiddenDeliveryDate.setAttribute('value', delDate.value);

            editSave.setAttribute('type', 'submit');
            editCancel.setAttribute('href', '');

            let saveBtnText = document.createTextNode('Save');
            let cancelText = document.createTextNode('Cancel');
            editSave.appendChild(saveBtnText);
            editCancel.appendChild(cancelText);

            editForm.append(editHiddenMethod, editHiddenToken, editHiddenId, editInput, editSave, editCancel);

            parentTd.replaceChild(editForm, originalSpan);

            editCancel.addEventListener('click', (e) => {
                e.preventDefault();
                parentTd.replaceChild(originalSpan, editForm);
                toggleVisibility(editCountBtns);
            });
            toggleVisibility(editCountBtns);
        });
    });
    function toggleVisibility(elements) {
        elements.forEach(element => {
            if(element.style.visibility != 'hidden') {
                element.style.visibility = 'hidden';
            } else {
                element.style.visibility = '';
            }
        });
    }
</script>
@endsection