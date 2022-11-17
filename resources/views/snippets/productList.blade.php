<div class="productList">
    <div class="products">
        @if (isset($data['products']))
            @foreach($data['products'] as $product)
                @include('snippets.product', ['product_id' => $product['id'], 'product_image' => 'https://picsum.photos/300/200', 'product_info' => '<p>' . $product['omschrijving'] . 'Categorie: WERKPLEK</p><p>Inrichtingsconcept: TAFEL</p><p>Leverancier: Gispen</p><p>Soort: VERGADERTAFEL</p>'])
            @endforeach
        @else
            @for ($x=0;$x<12;$x++)
                @include('snippets.product', ['product_id' => 999, 'product_image' => 'https://picsum.photos/300/200', 'product_info' => '<p>Categorie: WERKPLEK</p><p>Inrichtingsconcept: TAFEL</p><p>Leverancier: Gispen</p><p>Soort: VERGADERTAFEL</p>'])
            @endfor
        @endif
    </div>
    {{-- Paginering --}}
    <p style="text-decoration: underline">Pagination</p>
    <div class="productOverviewPagination">
        @if (isset($data['totalPages']))
            @include('snippets.pagination', ['total_pages' => $data['totalPages'], 'active_page' => $data['currentPage']])
        @else
            @include('snippets.pagination', ['total_pages' => 8, 'active_page' => 8])
        @endif
    </div>
</div>
