@extends('templates.portal')
@section('content')
            {{-- Tegels --}}
            <p style="text-decoration: underline">TEGELS</p>
            @include('snippets.elementTiles')
    
    
            {{-- Product detail page --}}
            <p style="text-decoration: underline">PRODUCT DETAIL PAGINA</p>
            <div class="productDetailContent">
                <h1>Chill out kast</h1>
                <div class="prodDetTopWrap">
                    <div><img src="https://picsum.photos/200/400" alt="Product image"></div>
                    <div>
                        <div>
                            <div class="prodReserveWrap">
                                <h2>Reserveren / bestellen</h2>
                                <p>Magazijnvoorraad: 4<br />
                                    Gereserveerd: 0<br />
                                    Beschikbaar: 4
                                </p>
                                <input type="aantal"><button>Reserveren</button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="prodDetPropertiesWrap">
                    @for ($x=0;$x<3;$x++)
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
                    @endfor
                </div>
            </div>
            
            {{-- Standard text page --}}
            <p style="text-decoration: underline">STANDAARD TEKST PAGINA</p>
            <div class="textContent">
                <h1>Heading 1</h1>
                <p>Content</p>
                <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>
                <p>Content</p>
                <h2>Heading 2</h2>
                <p>Content</p>
                <p>Content</p>
                <h3>Heading 3</h3>
                <p>Content</p>
            </div>
    
            {{-- Product list with filters --}}
            <p style="text-decoration: underline">PRODUCTEN OVERZICHT MET FILTERMOGELIJKHEID</p>
            <div class="productOverviewContent">
                <div class="filterWrap">
                    <div class="filters">
                        <h4>Filteren</h4>
                        @include('snippets.filter_select', ['filter_name' => 'Leverancier', 'filter_options' => ['Gispen','bb'], 'filter_selected_option' => ''])
                        @include('snippets.filter_select', ['filter_name' => 'Kleur', 'filter_options' => ['Rood','Blauw'], 'filter_selected_option' => ''])
                        @include('snippets.filter_select', ['filter_name' => 'Soort', 'filter_options' => ['1','2'], 'filter_selected_option' => ''])
                        @include('snippets.filter_input')
                        <button>TOON RESULTATEN</button>
                        <h4>Actieve filters</h4>
                        @include('snippets.filter_active', ['filter_name' => 'Leverancier', 'filter_selected_option' => 'Gispen'])
                        @include('snippets.filter_active', ['filter_name' => 'Kleur', 'filter_selected_option' => 'Blauw'])
                    </div>
                </div>
                @include('snippets.productList')
            </div>
@endsection