{{-- @extends('templates.development') --}}
@extends('templates.portal')
@section('content')
<div class="productDetailContent">
    <h1>{{ $product->omschrijving }}</h1>
    <div class="prodDetTopWrap">
        <div><img src="https://picsum.photos/200/400" alt="Product image"></div>
        <div>
            <div>
                <div class="prodReserveWrap">
                    <h2>Reserveren / bestellen</h2>
                    <p>Magazijnvoorraad: {{ $product->minimaleVoorraad }}<br />
                        Gereserveerd: 0<br />
                        Beschikbaar: 4
                    </p>
                    <input type="aantal"><button>Reserveren</button>
                    <p>{{ $product->bijzonderheden }}</p>
                </div>
            </div>
        </div>
    </div>
    <div class="prodDetPropertiesWrap">

        <div class="prodDetProp">
            <h3>Merk</h3>
            <p>{{ $product->brand->brand }}</p>
        </div>
        <div class="prodDetProp">
            <h3>Groep</h3>
            <p>{{ $product->group->group }}</p>
        </div>
        <div class="prodDetProp">
            <h3>Type</h3>
            <p>{{ $product->type->type }}</p>
        </div>
        <div class="prodDetProp">
            <h3>Stuks per bundel</h3>
            <p>{{ $product->stuksPerBundel }}</p>
        </div>
        <div class="prodDetProp">
            <h3>Prijs</h3>
            <p>&euro;{{ number_format($product->prijs, 2, ',', '.') }}</p>
        </div>
        <div class="prodDetProp">
            <h3>Kleur</h3>
            <p>{{ $product->kleur }}</p>
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