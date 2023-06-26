{{-- @extends('templates.development') --}}
@extends('templates.portal')
@section('content')
<div class="productDetailContent">
    <h1>{{ $product->omschrijving }}</h1>
    @if (count($product->reservations()) && auth()->user()->is_admin)
    <div class="prodDetReservations">
        <p>Er zijn reserveringen actief voor dit product:</p>
        <ul>
        @foreach ($product->reservations() as $reservationInfo)
            <li>{{ $reservationInfo->amount }}x gereserveerd door {{ $reservationInfo->orderUserName }} ({{ $reservationInfo->orderUserEmail }}), reservering nummer <a href="{{ route('reservation_detail', ['id' => $reservationInfo->orderId]) }}">{{ $reservationInfo->orderId }}</a></li>
        @endforeach
        </ul>
    </div>
    @endif
    <div class="prodDetTopWrap">
        <div><img src="{!! $product->imageUrl !!}" alt="Product image"></div>
        <div>
            <div>
                <div class="prodReserveWrap">
                    <div class="prodCodes">
                        <p>{{ __('Article') }} code: <strong>{{ $product->artikelCode }}</strong></p>
                        <p>{{ __('Article') }} code {{ __('Customer') }}: <strong>{{ ($product->artikelCodeKlant?$product->artikelCodeKlant:'-') }}</strong></p>
                        <p>{{ __('Customer') }} code: <strong>{{ $product->klantCode }}</strong></p>
                    </div>
                    <h2>
                    @if (auth()->user()->can_reserve)
                    {{ __('Reserve') }}
                    @else
                    {{ __('Order') }}
                    @endif
                    </h2>
                    <p>{{ __('Warehouse stock') }}: {{ $product->voorraad - $product->orderedAmount() }}<br />
                        {{ __('Total reserved') }}: {{ $product->reservedAmount() }}<br />
                        {{ __('Total available') }}: {{ $product->availableAmount() }}
                    </p>
                    @if (session()->has('selectedClient'))
                    <form action="{{ url('basket') }}" method="POST">
                        @csrf
                        <input type="hidden" name="id" value="{{ $product->id }}">
                        {{-- do not use input field for type of order, because of active code alteration --}}
                        {{-- <input type="hidden" name="orderType" value="reserve"> --}}
                        <input type="text" name="aantal">
                        <button>
                            @if (auth()->user()->can_reserve)
                            {{ __('Reserve') }}
                            @else
                            {{ __('Order') }}
                            @endif
                        </button>
                    </form>
                    @else
                        <p>{{ __('Please select a client before ordering') }}.</p>
                    @endif
                    <h3>Bijzonderheden:</h3>
                    <p>{{ ($product->bijzonderheden?$product->bijzonderheden:'-') }}</p>
                </div>
            </div>
        </div>
    </div>
    <div class="prodDetPropertiesWrap">

        <div class="prodDetProp">
            <h3>Merk</h3>
            <p>{{ ($product->brand->brand?$product->brand->brand:'-') }}</p>
        </div>
        <div class="prodDetProp">
            <h3>Groep</h3>
            <p>{{ ($product->group->group?$product->group->group:'-') }}</p>
        </div>
        <div class="prodDetProp">
            <h3>Type</h3>
            <p>{{ ($product->type->type?$product->type->type:'-') }}</p>
        </div>
        <div class="prodDetProp">
            <h3>Stuks per bundel</h3>
            <p>{{ ($product->stuksPerBundel?$product->stuksPerBundel:'-') }}</p>
        </div>
        <div class="prodDetProp">
            <h3>Verpakking bundel</h3>
            <p>{{ ($product->verpakkingBundel?$product->verpakkingBundel:'-') }}</p>
        </div>
        <div class="prodDetProp">
            <h3>Minimale voorraad</h3>
            <p>{{ ($product->minimaleVoorraad?$product->minimaleVoorraad:'-') }}</p>
        </div>
        <div class="prodDetProp">
            <h3>Prijs</h3>
            <p>&euro;{{ number_format($product->prijs, 2, ',', '.') }}</p>
        </div>
        <div class="prodDetProp">
            <h3>Kleur</h3>
            <p>{{ ($product->kleur?$product->kleur:'-') }}</p>
        </div>
        <div class="prodDetProp">
            <h3>Afmetingen (mm.)</h3>
            <p>{{ $product->lengte }} x {{ $product->breedte }} x {{ $product->hoogte }}<br />(L x B x H)</p>
        </div>





        {{-- @for ($x=0;$x<3;$x++)
        <div class="prodDetProp">
            <h3>Kleur / Materiaal</h3>
            <p>Wit</p>
        </div>
        <div class="prodDetProp">
            <h3>Omschrijving</h3>
            <p>Plantenbak</p>
        </div>
        <div class="prodDetProp">
            <h3>Bijzonderheden</h3>
            <p>GEEN</p>
        </div>
        <div class="prodDetProp">
            <h3>Leverancier</h3>
            <p>Zwartwoud<br />
                Tel. 03333333
            </p>
        </div>
        @endfor --}}
    </div>
</div>
@endsection
{{-- @section('before_closing_body_tag')
@if ($errors->any())
    @php
        $errMsg = '<p>' . implode('</p><p>', $errors->all()) . '</p>';
    @endphp
    <script>
        showMessage('error','{!! $errMsg !!}');
    </script>
@endif
@if(session('message'))
    <script>
        showMessage('success','{!! session('message') !!}');
    </script>
@endif
@endsection --}}