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
    <h1>{{ __('Basket') }}</h1>
    @if (count($basket))
        <table>
            <thead>
                <tr>
                    <th>id</th>
                    <th>Product</th>
                    <th>{{ __('Amount') }}</th>
                    <th>{{ __('Price') }}</th>
                    <th>{{ __('Total') }} {{ Str::lower(__('Price')) }}</th>
                    <th>&nbsp;</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($basket as $item)
                @php
                    $totalOrderSum += $item['product']->prijs*$item['count'];
                @endphp
                <tr>
                    <td>{{ $item['product']->id }}</td>
                    <td>{{ $item['product']->omschrijving }}</td>
                    <td><span>{{ $item['count'] }} <a href="" class="editBasketCount" data-product-id="{{ $item['product']->id }}" data-product-count="{{ $item['count'] }}">[edit]</a></span></td>
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
            </tbody>
        </table>
        <p><strong>{{ __('Total value of your order') }}: &euro;{{ number_format($totalOrderSum, 2, ',', '.') }}</strong></p>
        <h2>{{ __('Delivery date') }}</h2>
        <form action="{{ url('order') }}" method="post">
            @csrf
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
            @if (auth()->user()->can_reserve)
            <button>Reservering bevestigen</button>
            @else
            <button onclick="return confirm('{{ __('Your order will be delivered on') }} {{ $deliveryDate }}.\n\n{{ __('Are you sure you want to confirm your order') }}?')">Order bevestigen</button>
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
    // const delDate = document.querySelector('input[name="deliveryDate"]');
    // const datepicker = new Datepicker(delDate, {
    //     format: 'dd-mm-yyyy'
    // });

    const editDateBtn = document.querySelector('.editBasketDate');
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