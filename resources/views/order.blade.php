{{-- @extends('templates.development') --}}
@extends('templates.portal')
@section('content')
<div class="orderContent">
    @php
        $totalOrderSum = 0;
    @endphp
    <h1>{{ ($order->is_reservation?'Reservation':'Order') }} details</h1>
    <p>Id: {{ $order->id }}</p>
    <p>Is reservation: {{ ($order->is_reservation?'Yes':'No') }}</p>
    <p>Aflever datum: {{ $order->afleverDatum }}</p>
    <p>Aflever tijd: {{ $order->afleverTijd }}</p>
    <p>Order aangemaakt op: {{ $order->created_at }}</p>
    @if (count($order->orderArticles))
        <h2>Producten</h2>
        <table>
            <tr>
                <th>Id</th>
                <th>Product Id</th>
                <th>Product Name</th>
                <th>Amount</th>
                <th>Price</th>
                <th>Total price</th>
            </tr>
        @foreach ($order->orderArticles as $art)
            @php
                $totalOrderSum += $art->product->prijs*$art->amount;
            @endphp
            <tr>
                <td>{{ $art->id }}</td>
                <td>{{ $art->product_id }}</td>
                <td>{{ $art->product->omschrijving }}</td>
                <td><span>{{ $art->amount }} <a href="" class="editBasketCount" data-order-id="{{ $order->id }}" data-product-id="{{ $art->product_id }}" data-product-count="{{ $art->amount }}">[edit]</a></span></td>
                <td>&euro;{{ number_format($art->product->prijs, 2, ',', '.') }}</td>
                <td>&euro;{{ number_format($art->product->prijs*$art->amount, 2, ',', '.') }}</td>
            </tr>
        @endforeach
        </table>
        <p><strong>Total value of your order: &euro;{{ number_format($totalOrderSum, 2, ',', '.') }}</strong></p>
    @endif
    @if ($order->is_reservation)
    <h2>Confirm reservation</h2>
    <p>Confirm your reservation via the button below</p>
    <p>When your order is placed, it cannot be undone.</p>
        <form action="/order" method="post">
            @method('put')
            @csrf
            <input type="hidden" name="id" value="{{ $order->id }}">
            <input type="hidden" name="type" value="confirmReservation">
            <button type="submit">Confirm reservation</button>
        </form>
    @endif
</div>
@endsection
@section('before_closing_body_tag')
<script>
    const editCountBtns = document.querySelectorAll('.editBasketCount');
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
            editHiddenAOId.setAttribute('name', 'ao_id');
            editHiddenAOId.setAttribute('value', pId);
            editSave.setAttribute('type', 'submit');
            editCancel.setAttribute('href', '');

            let saveBtnText = document.createTextNode('Save');
            let cancelText = document.createTextNode('Cancel');
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