{{-- @extends('templates.development') --}}
@extends('templates.portal')
@section('content')
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
    displayProductPage();

    function displayProductPage(pageNr = 1) {
        axios.post('{{ url('/ajax/products?page=') }}' + pageNr)
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

    function afterXhrScript() {
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

        let products = document.querySelectorAll('.productList .product');
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
    }
</script>
@endsection