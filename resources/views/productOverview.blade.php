{{-- @extends('templates.development') --}}
@extends('templates.portal')
@section('content')
{{-- @if (Route::currentRouteName() == 'products_tiles') --}}
<div class="selectTileOverlay">
    <div class="tileGrid">
        @foreach ($data['filters']['groupForTiles']['items'] as $group)
        <div>
            <a href="" data-group="{{ $group }}">
                @if (isset($data['tiles'][$group]))<img src="{{ asset('tile_images') }}/{{ $data['tiles'][$group] }}" alt="">@endif
                @php
                    $groupNameDisplay = $group;
                    if(app()->getLocale() == 'en' && $enTranslation = config('hulshoff.productgroup_translations.' . $groupNameDisplay)) {
                        $groupNameDisplay = $enTranslation;
                    }
                @endphp
                <p>{{ $groupNameDisplay }}</p>
            </a>
        </div>
        @endforeach
    </div>
</div>
{{-- @endif --}}

@php
    $showWizzard = false;
    $showFilters = false;
    if($data['filterDisplay'] == 'top') $showWizzard = true;
    if($data['filterDisplay'] == 'side') $showFilters = true;
@endphp
@if ($showWizzard)
    @include('snippets.wizzard_select', ['wizInitVals' => $data['filters']['group']['items']])
@endif

<div class="productOverviewContent">
    @if ($showFilters)
    <div class="filterWrap">
        <div class="filters">
            <h4>{{ __('Filter') }}</h4>
            @foreach ($data['filters'] as $ref => $info)
                @if ($ref != 'groupForTiles')
                @include('snippets.filter_select', ['filter_name' => $info['name'], 'filter_reference' => $ref, 'filter_options' => $info['items'], 'filter_selected_option' => ''])
                @endif
            @endforeach
            @include('snippets.filter_input', ['placeholder' => __('Search in description or details'), 'name' => 'zoeken', 'reference' => 'search'])
            @include('snippets.filter_input', ['placeholder' => __('Search by Article code'), 'name' => 'artikel_code', 'reference' => 'aCode'])
            @include('snippets.filter_input', ['placeholder' => __('Search by Customer Article code'), 'name' => 'artikel_code_klant', 'reference' => 'aCodeClient'])
            {{-- @include('snippets.filter_checkbox', ['checkboxName' => 'show_in_stock', 'checkboxLabel' => __('Only show products in stock')]) --}}
            @include('snippets.filter_checkbox', ['checkboxName' => 'show_in_stock', 'checkboxLabel' => __('Show "out of stock" products also')])
            <button class="filterProductsBtn">{{ __('Show results') }}</button>
            <h4>{{ __('Active') }} filters</h4>
            <div class="activeFilters"></div>
        </div>
    </div>
    @endif
    <div class="loadProducts"></div>
</div>
@endsection
{{-- @section('extra_head')
    <script src="{{ asset('js/axios.min.js') }}"></script>
@endsection --}}
@section('before_closing_body_tag')
@parent
<script>
    const content = document.querySelector('.loadProducts');
    const filterBtn = document.querySelector('.filterProductsBtn');

    const wizSelects = document.querySelectorAll('.wizSelectWrap select');
    const wizGroupSelect = document.querySelector('.wizWrapGroup select');
    const wizTypeSelect = document.querySelector('.wizWrapType select');
    const wizBrandSelect = document.querySelector('.wizWrapBrand select');
    const wizColorSelect = document.querySelector('.wizWrapColor select');
    const wizBrandRadio = document.querySelector('#wiz_me_radio');
    const wizColorRadio = document.querySelector('#wiz_kl_radio');
    const wizStockCheckBox = document.querySelector('input[name="show_in_stock"]');

    const tilesWrapper = document.querySelector('.selectTileOverlay');

    const searchInputField = document.querySelector('.searchInputWrap input');

    const contentCell = document.querySelector('.contentCell');
    const tileGrid = document.querySelector('.tileGrid');
    
    setTimeout(() => {
        displayProducts();
    }, 100);

    document.addEventListener('keydown', (e) => {
        // if(event.keyCode === 13 && searchInputField == document.activeElement) {
        if(event.keyCode === 13) {
            displayProducts();
        }
    });

    tilesWrapper.style.display = 'none';
    if(window.location.hash == '#tiles') {
        tilesWrapper.style.display = 'block';
    }

    if(tilesWrapper) {
        setTimeout(function() {
            if(tileGrid.offsetHeight > contentCell.offsetHeight) contentCell.style.height = (tileGrid.offsetHeight + 100) + 'px'; // setting new heigt, + some extra spacing
        }, 1000);

        const tileLinks = tilesWrapper.querySelectorAll('.tileGrid a');
        tileLinks.forEach(tLink => {
            tLink.addEventListener('click', (e) => {
                e.preventDefault();
                if(wizSelects.length) {
                    for (var i = 0; i < wizGroupSelect.options.length; i++) {
                        if (wizGroupSelect.options[i].dataset.workingName === tLink.dataset.group) {
                            wizGroupSelect.selectedIndex = i;
                            break;
                        }
                    }
                    let event = new Event('change');
                    wizGroupSelect.dispatchEvent(event);
                }
                if(filterBtn) {
                    const groupFilterEl = document.querySelector('[data-filter-reference=group]');
                    for (var i = 0; i < groupFilterEl.options.length; i++) {
                        if (groupFilterEl.options[i].dataset.workingName === tLink.dataset.group) {
                            groupFilterEl.selectedIndex = i;
                            break;
                        }
                    }
                    let event = new Event('click');
                    filterBtn.dispatchEvent(event);
                }
                tilesWrapper.style.display = 'none';
                window.scrollTo(0, 0);

                const state = {};
                history.pushState(state, '', "/products");
                manualWrap.querySelector('div').innerHTML = manuals['/products'];
            });
        });
    }


    window.addEventListener("popstate", (event) => {
        // console.log(`location: ${document.location}, state: ${JSON.stringify(event.state)}`)
        if(document.location.hash == '#tiles') {
            tilesWrapper.style.display = 'block';
            manualWrap.querySelector('div').innerHTML = manuals['/products#tiles'];
        }
    });


    if(wizSelects.length) {
        wizSelects.forEach(wizSel => {
            wizSel.addEventListener('change', () => {
                switch(wizSel.dataset.selecttype) {
                    case 'Groep':
                        content.innerHTML = '';
                        wizTypeSelect.innerHTML = '';
                        wizBrandSelect.innerHTML = '';
                        wizColorSelect.innerHTML = '';
                        getTypes(wizSel.value);
                        displayProducts();
                        break;
                    case 'Type':
                        content.innerHTML = '';
                        wizBrandSelect.innerHTML = '';
                        wizColorSelect.innerHTML = '';

                        if(wizBrandRadio.checked) getBrands(wizGroupSelect.value, wizSel.value);
                        if(wizColorRadio.checked) getColors(wizGroupSelect.value, wizSel.value);
                        displayProducts();
                        break;
                    case 'Merk':
                        getColors(wizGroupSelect.value, wizTypeSelect.value, wizSel.value);
                        displayProducts();
                        break;
                    case 'Kleur':
                        getBrands(wizGroupSelect.value, wizTypeSelect.value, wizSel.value);
                        displayProducts();
                        break;
                }
            });
        });
        wizBrandRadio.addEventListener('change', () => {
            resetBrandColor();
        });
        wizColorRadio.addEventListener('change', () => {
            resetBrandColor();
        });
        wizStockCheckBox.addEventListener('change', () => {
            displayProducts();
        });
    }
    
    if(filterBtn) {
        filterBtn.addEventListener('click', () => {
            displayProducts();
        });
    }

    function resetBrandColor() {
        wizBrandSelect.innerHTML = '';
        wizColorSelect.innerHTML = '';
        let event = new Event('change');
        wizTypeSelect.dispatchEvent(event);
    }

    function getTypes(selectedGroupId) {
        content.innerHTML = '';
        axios.post('{{ url('/ajax/types') }}', {
            groupId:selectedGroupId
        })
        .then(function (response) {
            if(response.data) populateSelect(response.data, 'type', wizTypeSelect);
        })
        .catch(function (error) {console.log(error);})
        .then(function () {});
    }

    function getBrands(selectedGroupId, selectedTypeId, selectedColorId = false) {
        axios.post('{{ url('/ajax/brands') }}', {
            groupId:selectedGroupId,
            typeId:selectedTypeId,
            colorId:selectedColorId
        })
        .then(function (response) {
            if(response.data) {
                populateSelect(response.data, 'brand', wizBrandSelect);
            }
        })
        .catch(function (error) {console.log(error);})
        .then(function () {});
    }

    function getColors(selectedGroupId, selectedTypeId, selectedBrandId = false) {
        axios.post('{{ url('/ajax/colors') }}', {
            groupId:selectedGroupId,
            typeId:selectedTypeId,
            brandId:selectedBrandId
        })
        .then(function (response) {
            if(response.data) {
                populateSelect(response.data, 'color', wizColorSelect);
            }
        })
        .catch(function (error) {console.log(error);})
        .then(function () {});
    }

    function populateSelect(values, attrName, selectNode) {
        selectNode.innerHTML = '';
        values.forEach(valOb => {
            let option = document.createElement('option');
            let text = document.createTextNode(valOb[attrName]);
            option.value = valOb.id;
            option.appendChild(text);
            selectNode.appendChild(option);
        });
    }

    function displayProducts(pageNr = 1) {
        let filters = getFilters();
        setActiveFilters(filters);
// console.log(filters);
        axios.post('{{ url('/ajax/products?page=') }}' + pageNr, filters)
        .then(function (response) {
            // handle success
            // console.log(response.data);
            content.innerHTML = response.data;
            afterXhrScript();
        })
        .catch(function (error) {
            // handle error
            console.log(error);
        })
        .then(function () {
            // always executed
        });
    }

    function getFilters() {
        let activeFilters = {};
        const filterElements = document.querySelectorAll('[data-filter-reference]');

        if(filterBtn || wizSelects.length) {
            filterElements.forEach(element => {
                let filterReference = element.dataset.filterReference;
                let fil = {};
                switch(element.nodeName.toLowerCase()) {
                    case 'select':
                        fil['name'] = element.name;
                        fil['value'] = element.value;
                        if(element[element.selectedIndex]) fil['text'] = element[element.selectedIndex].text;
                        activeFilters[filterReference] = fil;
                        break;
                    case 'input':
                        fil['name'] = element.name;
                        fil['value'] = element.value;
                        fil['text'] = element.value;
                        activeFilters[filterReference] = fil;
                        break;
                }
            });
            activeFilters.onlyinstock = false;
            if(wizStockCheckBox.checked) activeFilters.onlyinstock = true
        }
        return activeFilters;
    }

    function setActiveFilters(activeFilters) {
        const filtersHolder = document.querySelector('.activeFilters');
        if(filtersHolder){
            filtersHolder.innerHTML = '';
            for (const key in activeFilters) {
                if (Object.hasOwnProperty.call(activeFilters, key)) {
                    const element = activeFilters[key];
                    if(element.value != '' && element.name != 'customerCode' && typeof element != 'boolean') {
                        let wrapP = document.createElement("p");
                        wrapP.classList.add("activeFilter");

                        let textSpan = document.createElement("span");
                        let textSpanTextNode = document.createTextNode(element.name + ": " + element.text);
                        textSpan.appendChild(textSpanTextNode);

                        let btnA = document.createElement("a");
                        btnA.href = '#';
                        btnA.dataset.activeFilterReference = key;
                        let btnATextNode = document.createTextNode(" ");
                        btnA.appendChild(btnATextNode);

                        wrapP.appendChild(textSpan);
                        wrapP.appendChild(btnA);
                        filtersHolder.appendChild(wrapP);
                    }
                }
            }
        }
    }

    function afterXhrScript() {
        // set pagination click events
        const prodPagination = document.querySelector('.productPagination');
        const paginationLinks = prodPagination.querySelectorAll('a');
        paginationLinks.forEach(link => {
            link.addEventListener('click', (e) => {
                e.preventDefault();
                if(link.dataset.goToPageNumber) {
                    displayProducts(link.dataset.goToPageNumber);
                }
            });
        });

        // set product list effects and click events
        const products = document.querySelectorAll('.productList .product');
        products.forEach(prod => {
            let linkEl = prod.querySelector('.prodToDetail a');
            prod.addEventListener('mouseenter', () => {
                linkEl.classList.add('active');
            });
            prod.addEventListener('mouseleave', () => {
                linkEl.classList.remove('active');
            });
            prod.addEventListener('click', () => {
                window.location.href = linkEl.href;
            });
        });

        // set filter reset events on active filters
        const allActiveFilters = document.querySelectorAll('.activeFilter');
        allActiveFilters.forEach(aF => {
            let aFbtn = aF.querySelector('a');
            aFbtn.addEventListener('click', (e) => {
                e.preventDefault();
                let filterToReset = document.querySelector('[data-filter-reference=' + aFbtn.dataset.activeFilterReference + ']');
                filterToReset.value = '';
                displayProducts();
            });
        });
    }
</script>
@endsection