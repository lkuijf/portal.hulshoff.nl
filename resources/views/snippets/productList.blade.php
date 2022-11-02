<div class="productList">
    <div class="products">
        @for ($x=0;$x<12;$x++)
        @include('snippets.product', ['product_image' => 'https://picsum.photos/300/200', 'product_info' => '<p>Categorie: WERKPLEK</p><p>Inrichtingsconcept: TAFEL</p><p>Leverancier: Gispen</p><p>Soort: VERGADERTAFEL</p>'])
        @endfor
    </div>
    {{-- Paginering --}}
    <p style="text-decoration: underline">Pagination</p>
    <div class="productOverviewPagination">
        @include('snippets.pagination', ['total_pages' => 8, 'active_page' => 8])
    </div>
</div>
@section('before_closing_body_tag')
    @parent
    <script>
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
    </script>
@endsection