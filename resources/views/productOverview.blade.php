{{-- @extends('templates.development') --}}
@extends('templates.portal')
@section('content')
    <div class="productOverviewContent">
        <div class="filterWrap">
            <div class="filters">
                <h4>Filteren</h4>
                @foreach ($data['filters'] as $ref => $info)
                    @include('snippets.filter_select', ['filter_name' => $info['name'], 'filter_reference' => $ref, 'filter_options' => $info['items'], 'filter_selected_option' => ''])
                @endforeach
                @include('snippets.filter_input')
                <button class="filterProductsBtn">TOON RESULTATEN</button>
                <h4>Actieve filters</h4>
                <div class="activeFilters">
                    {{-- @include('snippets.filter_active', ['filter_name' => 'Leverancier', 'filter_selected_option' => 'Gispen'])
                    @include('snippets.filter_active', ['filter_name' => 'Kleur', 'filter_selected_option' => 'Blauw']) --}}
                </div>
            </div>
        </div>
        <div class="loadProducts"></div>
    </div>
@endsection
@section('extra_head')
    <script src="{{ asset('js/axios.min.js') }}"></script>
@endsection
@section('before_closing_body_tag')
@parent
<script>
    const content = document.querySelector('.loadProducts');
    const filterBtn = document.querySelector('.filterProductsBtn');
    
    displayProductPage();

    filterBtn.addEventListener('click', () => {
        displayProductPage();
    });

    function displayProductPage(pageNr = 1) {
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
        const filterTags = document.querySelectorAll('[data-filter-reference]');
        filterTags.forEach(element => {
            let filterReference = element.dataset.filterReference;
            let fil = {};
            switch(element.nodeName.toLowerCase()) {
                case 'select':
                    fil['name'] = element.name;
                    fil['value'] = element.value;
                    activeFilters[filterReference] = fil;
                    break;
                case 'input':
                    fil['name'] = element.name;
                    fil['value'] = element.value;
                    activeFilters[filterReference] = fil;
                    break;
            }
        });
        return activeFilters;
    }

    function setActiveFilters(activeFilters) {
        const filtersHolder = document.querySelector('.activeFilters');
        filtersHolder.innerHTML = '';
// console.log(activeFilters);
        for (const key in activeFilters) {
            if (Object.hasOwnProperty.call(activeFilters, key)) {
                const element = activeFilters[key];
                // console.log(element);
                if(element.value != '') {
                    let wrapP = document.createElement("p");
                    wrapP.classList.add("activeFilter");

                    let textSpan = document.createElement("span");
                    let textSpanTextNode = document.createTextNode(element.name + ": " + element.value);
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

    function afterXhrScript() {
        // set pagination click events
        const prodPagination = document.querySelector('.productPagination');
        const paginationLinks = prodPagination.querySelectorAll('a');
        paginationLinks.forEach(link => {
            link.addEventListener('click', (e) => {
                e.preventDefault();
                if(link.dataset.goToPageNumber) {
                    displayProductPage(link.dataset.goToPageNumber);
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
                displayProductPage();
            });
        });
    }
</script>
@endsection