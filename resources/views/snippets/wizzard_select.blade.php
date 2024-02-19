<div class="wizzardWrap">
    <div class="wizzard">
        {{-- selectbox: Groep > selectbox:Type > [x] Zoek op Merk OF [] Zoek op Kleur   --}}
        {{-- resultaat met artikelen (db: omschrijving) --}}
        <div class="wizSelectWrap wizWrapGroup">
            <label for="wiz_gr">Groep</label>
            <select name="Groep" id="wiz_gr" data-selecttype="Groep" data-filter-reference="group" size="5">
                <option value="">- {{ __('All groups') }} -</option>
                @foreach ($wizInitVals as $id => $initVal)
                    <option value="{{ $id }}">{{ $initVal }}</option>
                @endforeach
            </select>
        </div>
        <div class="wizSelectWrap wizWrapType">
            <label for="wiz_ty">Type</label>
            <select name="Type" id="wiz_ty" data-selecttype="Type" data-filter-reference="type" size="5">
            </select>
        </div>
        <div class="wizSelectWrap wizWrapBrand">
            {{-- <label for="wiz_me"><input type="radio" name="search_by" value="Merk" checked>Merk</label> --}}
            <input type="radio" name="search_by" id="wiz_me_radio" value="Merk" checked><label for="wiz_me_radio">Merk</label>
            <select name="Merk" id="wiz_me" data-selecttype="Merk" data-filter-reference="brand" size="5">
            </select>
        </div>
        <div class="wizSelectWrap wizWrapColor">
            {{-- <label for="wiz_kl"><input type="radio" name="search_by" value="Kleur">Kleur</label> --}}
            <input type="radio" name="search_by" id="wiz_kl_radio" value="Kleur"><label for="wiz_kl_radio">Kleur</label>
            <select name="Color" id="wiz_kl" data-selecttype="Kleur" data-filter-reference="color" size="5">
            </select>
        </div>
    </div>
    <div class="wizTextFields">
        @include('snippets.filter_input', ['placeholder' => 'Zoek in omschrijving of bijzonderheden', 'name' => 'zoeken', 'reference' => 'search'])
        @include('snippets.filter_input', ['placeholder' => 'Zoek op Artikel code', 'name' => 'artikel_code', 'reference' => 'aCode'])
        @include('snippets.filter_input', ['placeholder' => 'Zoek op Artikel code klant', 'name' => 'artikel_code_klant', 'reference' => 'aCodeClient'])
    </div>
    {{-- <div class="wizShowStockProds">@include('snippets.filter_checkbox', ['checkboxName' => 'show_in_stock', 'checkboxLabel' => __('Only show products in stock')])</div> --}}
    <div class="wizShowStockProds">@include('snippets.filter_checkbox', ['checkboxName' => 'show_in_stock', 'checkboxLabel' => __('Show "out of stock" products also')])</div>
    <button class="filterProductsBtn">{{ __('Show results') }}</button>
</div>