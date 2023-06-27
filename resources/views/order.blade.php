{{-- @extends('templates.development') --}}
@extends('templates.portal')
@section('content')
<div class="orderContent">
    @php
        $totalOrderSum = 0;
    @endphp
    <h1>{{ ($order->is_reservation?'Reservation':'Order') }} details</h1>
    <div class="orderMetaData">
        <p>Id: {{ $order->id }}</p>
        <p>Is {{ Str::lower(__('Reservation')) }}: {{ ($order->is_reservation?__('Yes'):__('No')) }}</p>
        <p>{{ __('Delivery date') }}: <span>{{ date("d-m-Y", strtotime($order->afleverDatum)) }}{!! ($order->is_reservation?' <a class="editBasketDate editBtn" data-order-id="' . $order->id . '" data-order-date="' . date("d-m-Y", strtotime($order->afleverDatum)) . '" href="">' . __('Edit') . '</a>':'') !!}</span></p>
        {{-- <p>Aflever tijd: {{ $order->afleverTijd }}</p> --}}
        <p>Order {{ Str::lower(__('Created at')) }}: {{ date("d-m-Y", strtotime($order->created_at)) }} om {{ date("H:i", strtotime($order->created_at)) }} uur</p>
    </div>
    @if (count($order->orderArticles))
        <h2>{{ __('Products') }}</h2>
        <table>
            <thead>
                <tr>
                    {{-- <th>id</th> --}}
                    {{-- <th>Product id</th> --}}
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
                        <td><a href="{{ route('product_detail', $orderArt->product_id) }}">{{ $orderArt->product->artikelCode }}</a></td>
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
            </tbody>
        </table>
        <p><strong>{{ __('Total value of your order') }}: &euro;{{ number_format($totalOrderSum, 2, ',', '.') }}</strong></p>
    @endif
    @if ($order->is_reservation)
    <div class="confirmReservation">
    <h2>{{ __('Confirm reservation') }}</h2>
    <ul>
        <li>{{ __('Confirm your reservation via the button below') }}.</li>
        <li>{{ __('The reservation will be converted to an order') }}.</li>
        <li>{{ __('When your order is placed, it cannot be undone') }}</li>
    </ul>
        <form action="/order" method="post">
            @method('put')
            @csrf
            <input type="hidden" name="id" value="{{ $order->id }}">
            <input type="hidden" name="type" value="confirmReservation">
            <button type="submit">{{ __('Confirm reservation') }}</button>
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