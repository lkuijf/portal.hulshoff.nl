{{-- @extends('templates.development') --}}
@extends('templates.portal')
@section('content')
<div class="orderContent">
    @php
        $totalOrderSum = 0;
        $h1Text = ($order->is_reservation?__('Reservation'):'Order');
        if($order->orderType == 'return-order') {
            $h1Text = __('Return order');
        }
    @endphp
    <h1>{{ $h1Text }} details</h1>
    <div class="orderMetaData">
        {{-- <p>Id: {{ $order->id }}</p> --}}
        <p>Order Code Klant: <strong>{{ $order->orderCodeKlant }}</strong></p>
        <p>Is {{ Str::lower(__('Reservation')) }}: <strong>{{ ($order->is_reservation?__('Yes'):__('No')) }}</strong></p>
        <p>{{ __('Delivery date') }}: <strong><span>{{ date("d-m-Y", strtotime($order->afleverDatum)) }}{!! ($order->is_reservation?' <a class="editBasketDate editBtn" data-order-id="' . $order->id . '" data-order-date="' . date("d-m-Y", strtotime($order->afleverDatum)) . '" href="">' . __('Edit') . '</a>':'') !!}</span></strong></p>
        <p>{{ __('Delivery address') }}: <strong><span>{{ ($order->address_id !== null?$order->address->naam:'-') }}{!! ($order->is_reservation?' <a class="editBasketAddress editBtn" data-order-id="' . $order->id . '" data-address-id="' . $order->address_id . '" href="">' . __('Edit') . '</a>':'') !!}</span></strong></p>
        <p>{{ __('Custom Delivery address') }}: {!! ($order->is_reservation?' <a class="editBasketCustomAddress editBtn" data-order-id="' . $order->id . '" href="">' . __('Edit') . '</a>':'') !!}
            @if ($order->custom_address_id !== null)
                <br>
                Straat: <strong>{{ $order->custom_address->straat }}</strong><br>
                Huisnummer: <strong>{{ $order->custom_address->huisnummer }}</strong><br>
                Postcode: <strong>{{ $order->custom_address->postcode  }}</strong><br>
                Plaats: <strong>{{ $order->custom_address->plaats }}</strong><br>
                Contactpersoon: <strong>{{ $order->custom_address->contactpersoon }}</strong><br>
                PLanon / PO nummer: <strong>{{ $order->custom_address->po_number }}</strong><br>
                Telefoon: <strong>{{ $order->custom_address->telefoon }}</strong><br>
                Extra informatie: <strong>{{ $order->custom_address->informatie }}</strong><br>
            @else
            <br>-
            @endif
        </p>
        {{-- <p>Aflever tijd: {{ $order->afleverTijd }}</p> --}}
        <p>Order {{ Str::lower(__('Created at')) }}: <strong>{{ date("d-m-Y", strtotime($order->created_at)) }} om {{ date("H:i", strtotime($order->created_at)) }} uur</strong></p>
    </div>
    @if (count($order->orderArticles))
        <h2>{{ __('Products') }}</h2>
        <table>
            <thead>
                <tr>
                    <th>artikelCode</th>
                    <th>Product {{ __('Name') }}</th>
                    <th>{{ __('Amount') }}</th>
                    <th>{{ __('Price') }}</th>
                    <th>{{ __('Total') }} {{ Str::lower(__('Price')) }}</th>
                    @if ($order->is_reservation)<th>&nbsp;</th>@endif
                </tr>
            </thead>
            <tbody>
                @foreach ($order->orderArticles as $orderArt)
                    @php
                        $totalOrderSum += $orderArt->product->prijs*$orderArt->amount;
                    @endphp
                    <tr>
                        {{-- <td>{{ $orderArt->id }}</td> --}}
                        {{-- <td>{{ $orderArt->product_id }}</td> --}}
                        <td><a href="{{ route('product_detail', $orderArt->product_id) }}" data-product-klant-code="{{ $orderArt->product->klantCode }}">{{ $orderArt->product->artikelCode }}</a></td>
                        <td>{{ $orderArt->product->omschrijving }}</td>
                        <td><span>{{ $orderArt->amount }}@if ($order->is_reservation) <a href="" class="editBasketCount editBtn" data-order-id="{{ $order->id }}" data-product-id="{{ $orderArt->product_id }}" data-product-count="{{ $orderArt->amount }}">{{ __('Edit') }}</a>@endif</span></td>
                        <td>&euro;{{ number_format($orderArt->product->prijs, 2, ',', '.') }}</td>
                        <td>&euro;{{ number_format($orderArt->product->prijs*$orderArt->amount, 2, ',', '.') }}</td>
                        @if ($order->is_reservation)
                        <td>
                            <form action="{{ url('order-article') }}" method="post">
                                @method('delete')
                                @csrf
                                <input type="hidden" name="id" value="{{ $orderArt->id }}">
                                <button type="submit" onclick="return confirm('{{ __('You are about to delete product') }} {{ $orderArt->product->omschrijving }}, {{ __('from your') }} {{ ($order->is_reservation? Str::lower(__('Reservation')):'order') }}.\n\n{{ __('Are you sure') }}?')" class="deleteBtn"></button>
                            </form>
                        </td>
                        @endif
                    </tr>
                @endforeach
            @if ($order->is_reservation && (auth()->user()->id == $order->hulshoff_user_id))
            <tr>
                <td colspan="6"><p class="addArticleBtnHolder"><a href="" data-res-id="{{ $order->id }}">Voeg een artikel toe</a></p></td>
            </tr>
            @endif
        </tbody>
        </table>
        <p><strong>{{ __('Total value of your order') }}: &euro;{{ number_format($totalOrderSum, 2, ',', '.') }}</strong></p>
    @endif
    @if (count($order->returnOrderArticles))
    <h2>{{ __('Products') }}</h2>
    <table>
        <thead>
            <tr>
                <th>Product {{ __('Name') }}</th>
                <th>{{ __('Amount') }}</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($order->returnOrderArticles as $orderArt)
                <tr>
                    <td>{{ $orderArt->product }}</td>
                    <td>{{ $orderArt->amount }}</td>
                </tr>
            @endforeach
    </tbody>
    </table>
    @endif
    @if ($order->is_reservation)
    <div class="confirmReservation">
    <h2>{{ __('Confirm reservation') }}</h2>
    <ul>
        <li>{{ __('Confirm your reservation via the button below') }}.</li>
        <li>{{ __('The reservation will be converted to an order') }}.</li>
        <li>{{ __('When your order is placed, it cannot be undone') }}</li>
    </ul>
        @php
            $disabled = '';
            if(!$order->address_id && !$order->custom_address_id) {
                $disabled = ' disabled';
            }
        @endphp
        <form action="/order" method="post">
            @method('put')
            @csrf
            <input type="hidden" name="id" value="{{ $order->id }}">
            <input type="hidden" name="type" value="confirmReservation">
            <button type="submit"{{ $disabled }}>{{ __('Confirm reservation') }}</button>@if($disabled != '')&nbsp;<span><em>Order kan niet bevestigd worden, selecteer eerst een adres.</em></span>@endif
        </form>
    </div>
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
    const editCountBtns = document.querySelectorAll('.editBasketCount');
    const editDateBtn = document.querySelector('.editBasketDate');
    const editAddressBtn = document.querySelector('.editBasketAddress');
    const editCustomAddressBtn = document.querySelector('.editBasketCustomAddress');
    const addArticleBtn = document.querySelector('.addArticleBtnHolder a');
    const productKlantCodes = document.querySelectorAll('[data-product-klant-code]');
    const custSelectDropDown = document.querySelector('select[name=customerCode]'); // also declared in portal.blade.php

    const clientAddresses = [];
    @if ($addresses && count($addresses))
        @foreach ($addresses as $address)
            var add = {};
            add['id'] = {{ $address->id }};
            add['naam'] = '{{ $address->naam }}';
            add['straat'] = '{{ $address->straat }}';
            add['huisnummer'] = '{{ $address->huisnummer }}';
            add['postcode'] = '{{ $address->postcode }}';
            add['plaats'] = '{{ $address->plaats }}';
            add['landCode'] = '{{ $address->landCode }}';
            add['contactpersoon'] = '{{ $address->contactpersoon }}';
            add['telefoon'] = '{{ $address->telefoon }}';
            add['eMailadres'] = '{{ $address->eMailadres }}';
            clientAddresses.push(add);
        @endforeach
    @endif

// console.log(clientAddresses);

    if(addArticleBtn) {
        addArticleBtn.addEventListener('click', (e) => {
            e.preventDefault();
            // console.log(productKlantCodes[0].dataset.productKlantCode);
            allOptions = custSelectDropDown.querySelectorAll('option');
            let indexToSelect = 0;
            let t = 0;
            allOptions.forEach(option => {
                if(option.value == productKlantCodes[0].dataset.productKlantCode) indexToSelect = t;
                t++;
            });
// console.log(addArticleBtn.dataset.resId);
            custSelectDropDown.dataset.reservationId = addArticleBtn.dataset.resId;
            custSelectDropDown.selectedIndex = indexToSelect;
            custSelectDropDown.dispatchEvent(new Event('change'));
        });
    }

    if(editAddressBtn) {
        editAddressBtn.addEventListener('click', (e) => {
            e.preventDefault();
            let parentNode = editAddressBtn.parentNode.parentNode;
            let originalSpan = editAddressBtn.parentNode;

            let csrfToken = document.querySelector('meta[name="_token"]').content;

            let oId = editAddressBtn.dataset.orderId;
            let oaId = editAddressBtn.dataset.addressId;

            let editForm = document.createElement('form');
            let editSelect = document.createElement('select');
            let editHiddenMethod = document.createElement('input');
            let editHiddenToken = document.createElement('input');
            let editHiddenOId = document.createElement('input');
            let editHiddenType = document.createElement('input');
            let editSave = document.createElement('button');
            let editCancel = document.createElement('a');
            editForm.setAttribute('action', '/order');
            editForm.setAttribute('method', 'post');
            editSelect.setAttribute('name', 'address');

            let option = document.createElement("option");
            option.value = '';
            option.text = '-';
            editSelect.add(option);

            let selectedI = 0;
            let i = 1; // there is already 1 option added
            clientAddresses.forEach(addr => {
                let option = document.createElement("option");
                option.value = addr.id;
                option.text = addr.naam;
                editSelect.add(option);
                if(addr.id == oaId) selectedI = i;
                i++;
            });
            editSelect.selectedIndex = selectedI;

            editHiddenMethod.setAttribute('type', 'hidden');
            editHiddenMethod.setAttribute('name', '_method');
            editHiddenMethod.setAttribute('value', 'put');
            editHiddenToken.setAttribute('type', 'hidden');
            editHiddenToken.setAttribute('name', '_token');
            editHiddenToken.setAttribute('value', csrfToken);
            editHiddenOId.setAttribute('type', 'hidden');
            editHiddenOId.setAttribute('name', 'id');
            editHiddenOId.setAttribute('value', oId);
            editHiddenType.setAttribute('type', 'hidden');
            editHiddenType.setAttribute('name', 'type');
            editHiddenType.setAttribute('value', 'updateDeliveryAddress');
            editSave.setAttribute('type', 'submit');
            editCancel.setAttribute('href', '');

            let saveBtnText = document.createTextNode('{{ __('Save') }}');
            let cancelText = document.createTextNode('{{ __('Cancel') }}');
            editSave.appendChild(saveBtnText);
            editCancel.appendChild(cancelText);

            editForm.append(editHiddenMethod, editHiddenToken, editHiddenOId, editHiddenType, editSelect, editSave, editCancel);

            parentNode.replaceChild(editForm, originalSpan);

            editCancel.addEventListener('click', (e) => {
                e.preventDefault();
                parentNode.replaceChild(originalSpan, editForm);
                toggleVisibility([editAddressBtn]);
            });
            toggleVisibility([editAddressBtn]);
        });
    }

    if(editCustomAddressBtn) {
        editCustomAddressBtn.addEventListener('click', (e) => {
            e.preventDefault();
            let parentNode = editCustomAddressBtn.parentNode.parentNode;
            let originalSpan = editCustomAddressBtn.parentNode;

            let csrfToken = document.querySelector('meta[name="_token"]').content;

            let oId = editCustomAddressBtn.dataset.orderId;
            // let oaId = editCustomAddressBtn.dataset.addressId;

            let editForm = document.createElement('form');
            // let editSelect = document.createElement('select');

            let streetEl = document.createElement('input');
            let houseNrEl = document.createElement('input');
            let zippCodeEl = document.createElement('input');
            let cityEl = document.createElement('input');
            let contactPersonEl = document.createElement('input');
            let phoneEl = document.createElement('input');
            let extraInfoEl = document.createElement('textarea');

            streetEl.placeholder = 'Straat';
            houseNrEl.placeholder = 'Huisnummer';
            zippCodeEl.placeholder = 'Postcode';
            cityEl.placeholder = 'Plaats';
            contactPersonEl.placeholder = 'Contactpersoon';
            phoneEl.placeholder = 'Telefoonnummer';
            extraInfoEl.placeholder = 'Extra informatie';

            streetEl.value = '';
            houseNrEl.value = '';
            zippCodeEl.value = '';
            cityEl.value = '';
            contactPersonEl.value = '';
            phoneEl.value = '';
            extraInfoEl.value = '';

            @if ($order->custom_address)
                streetEl.value = '{{ $order->custom_address->straat }}';
                houseNrEl.value = '{{ $order->custom_address->huisnummer }}';
                zippCodeEl.value = '{{ $order->custom_address->postcode }}';
                cityEl.value = '{{ $order->custom_address->plaats }}';
                contactPersonEl.value = '{{ $order->custom_address->contactpersoon }}';
                phoneEl.value = '{{ $order->custom_address->telefoon }}';
                extraInfoEl.value = '{{ $order->custom_address->informatie }}';
            @endif

            let streetPara = document.createElement('p');
            let houseNrPara = document.createElement('p');
            let zippCodePara = document.createElement('p');
            let cityPara = document.createElement('p');
            let contactPersonPara = document.createElement('p');
            let phonePara = document.createElement('p');
            let extraInfoPara = document.createElement('p');

            streetPara.appendChild(streetEl);
            houseNrPara.appendChild(houseNrEl);
            zippCodePara.appendChild(zippCodeEl);
            cityPara.appendChild(cityEl);
            contactPersonPara.appendChild(contactPersonEl);
            phonePara.appendChild(phoneEl);
            extraInfoPara.appendChild(extraInfoEl);

            let editHiddenMethod = document.createElement('input');
            let editHiddenToken = document.createElement('input');
            let editHiddenOId = document.createElement('input');
            let editHiddenType = document.createElement('input');
            let editSave = document.createElement('button');
            let editCancel = document.createElement('a');
            editForm.setAttribute('action', '/order');
            editForm.setAttribute('method', 'post');
            // editSelect.setAttribute('name', 'address');
            streetEl.setAttribute('name', 'street');
            houseNrEl.setAttribute('name', 'housenr');
            zippCodeEl.setAttribute('name', 'zipp');
            cityEl.setAttribute('name', 'city');
            contactPersonEl.setAttribute('name', 'person');
            phoneEl.setAttribute('name', 'phone');
            extraInfoEl.setAttribute('name', 'info');

            // let selectedI = 0;
            // let i = 0;
            // clientAddresses.forEach(addr => {
            //     let option = document.createElement("option");
            //     option.value = addr.id;
            //     option.text = addr.naam;
            //     editSelect.add(option);
            //     if(addr.id == oaId) selectedI = i;
            //     i++;
            // });
            // editSelect.selectedIndex = selectedI;

            editHiddenMethod.setAttribute('type', 'hidden');
            editHiddenMethod.setAttribute('name', '_method');
            editHiddenMethod.setAttribute('value', 'put');
            editHiddenToken.setAttribute('type', 'hidden');
            editHiddenToken.setAttribute('name', '_token');
            editHiddenToken.setAttribute('value', csrfToken);
            editHiddenOId.setAttribute('type', 'hidden');
            editHiddenOId.setAttribute('name', 'id');
            editHiddenOId.setAttribute('value', oId);
            editHiddenType.setAttribute('type', 'hidden');
            editHiddenType.setAttribute('name', 'type');
            editHiddenType.setAttribute('value', 'updateDeliveryCustomAddress');
            editSave.setAttribute('type', 'submit');
            editCancel.setAttribute('href', '');

            let saveBtnText = document.createTextNode('{{ __('Save') }}');
            let cancelText = document.createTextNode('{{ __('Cancel') }}');
            editSave.appendChild(saveBtnText);
            editCancel.appendChild(cancelText);

            editForm.append(editHiddenMethod, editHiddenToken, editHiddenOId, editHiddenType, streetPara, houseNrPara, zippCodePara, cityPara, contactPersonPara, phonePara, extraInfoPara, editSave, editCancel);

            parentNode.replaceChild(editForm, originalSpan);

            editCancel.addEventListener('click', (e) => {
                e.preventDefault();
                parentNode.replaceChild(originalSpan, editForm);
                toggleVisibility([editCustomAddressBtn]);
            });
            toggleVisibility([editCustomAddressBtn]);
        });
    }

    if(editDateBtn) {
        editDateBtn.addEventListener('click', (e) => {
            e.preventDefault();
            let parentNode = editDateBtn.parentNode.parentNode;
            let originalSpan = editDateBtn.parentNode;

            let csrfToken = document.querySelector('meta[name="_token"]').content;
            let oId = editDateBtn.dataset.orderId;
            let oDate = editDateBtn.dataset.orderDate;
            let editForm = document.createElement('form');
            let editInput = document.createElement('input');
            let editHiddenMethod = document.createElement('input');
            let editHiddenToken = document.createElement('input');
            let editHiddenOId = document.createElement('input');
            let editHiddenType = document.createElement('input');
            let editSave = document.createElement('button');
            let editCancel = document.createElement('a');
            editForm.setAttribute('action', '/order');
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
            editHiddenOId.setAttribute('type', 'hidden');
            editHiddenOId.setAttribute('name', 'id');
            editHiddenOId.setAttribute('value', oId);
            editHiddenType.setAttribute('type', 'hidden');
            editHiddenType.setAttribute('name', 'type');
            editHiddenType.setAttribute('value', 'updateDeliveryDate');
            editSave.setAttribute('type', 'submit');
            editCancel.setAttribute('href', '');

            let saveBtnText = document.createTextNode('{{ __('Save') }}');
            let cancelText = document.createTextNode('{{ __('Cancel') }}');
            editSave.appendChild(saveBtnText);
            editCancel.appendChild(cancelText);

            editForm.append(editHiddenMethod, editHiddenToken, editHiddenOId, editHiddenType, editInput, editSave, editCancel);

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

    editCountBtns.forEach(btn => {
        btn.addEventListener('click', (e) => {
            e.preventDefault();
            let parentTd = btn.parentNode.parentNode;
            let originalSpan = btn.parentNode;

            let csrfToken = document.querySelector('meta[name="_token"]').content;

            let pId = btn.dataset.productId;
            let pCt = btn.dataset.productCount;
            let oId = btn.dataset.orderId;

            let editForm = document.createElement('form');
            let editInput = document.createElement('input');
            let editHiddenMethod = document.createElement('input');
            let editHiddenToken = document.createElement('input');
            let editHiddenOId = document.createElement('input');
            let editHiddenAOId = document.createElement('input');
            let editSave = document.createElement('button');
            let editCancel = document.createElement('a');
            editForm.setAttribute('action', '/order-article');
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
            editHiddenOId.setAttribute('type', 'hidden');
            editHiddenOId.setAttribute('name', 'o_id');
            editHiddenOId.setAttribute('value', oId);
            editHiddenAOId.setAttribute('type', 'hidden');
            editHiddenAOId.setAttribute('name', 'p_id');
            editHiddenAOId.setAttribute('value', pId);
            editSave.setAttribute('type', 'submit');
            editCancel.setAttribute('href', '');

            let saveBtnText = document.createTextNode('{{ __('Save') }}');
            let cancelText = document.createTextNode('{{ __('Cancel') }}');
            editSave.appendChild(saveBtnText);
            editCancel.appendChild(cancelText);

            editForm.append(editHiddenMethod, editHiddenToken, editHiddenOId, editHiddenAOId, editInput, editSave, editCancel);

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