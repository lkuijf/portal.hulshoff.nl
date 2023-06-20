<div class="productList">
    <div class="products">
        @if (isset($data['products']))
            @foreach($data['products'] as $product)
                {{-- @include('snippets.product', ['product_id' => $product['id'], 'product_image' => 'https://picsum.photos/300/200', 'product_info' => '<p>' . $product['omschrijving'] . 'Categorie: WERKPLEK</p><p>Inrichtingsconcept: TAFEL</p><p>Leverancier: Gispen</p><p>Soort: VERGADERTAFEL</p>']) --}}
                {{-- @include('snippets.product', ['product_id' => $product['id'], 'product_code' => $product['artikelCode'], 'product_voorraad' => ($product['voorraad'] - $product['aantal_besteld_onverwerkt']), 'product_image' => 'https://picsum.photos/300/200', 'product_info' => '<p>' . $product['omschrijving'] . '</p>']) --}}
                @include('snippets.product', 
                [
                    'product_id' => $product['id'], 
                    'product_code' => $product['artikelCode'], 
                    'product_voorraad' => ($product['voorraad'] - $product['aantal_besteld_onverwerkt']), 
                    // 'product_image' => Storage::disk('product_images_drive')->url() . '/50240/00003.jpg', 
                    // 'product_image' => Storage::disk('product_images_drive')->url(), 
                    // 'product_image' => url('product_images') . '/50240/00003.jpg', 
                    'product_image' => '/50240/00003.jpg', 
                    'product_info' => '<p>' . $product['omschrijving'] . '</p>'
                ])
                {{-- file:///M:/50240/00003.jpg --}}
            @endforeach
        @else
            @for ($x=0;$x<12;$x++)
                @include('snippets.product', ['product_id' => 999, 'product_code' => 'abc0001', 'product_voorraad' => '11', 'product_image' => 'https://picsum.photos/300/200', 'product_info' => '<p>Categorie: WERKPLEK</p><p>Inrichtingsconcept: TAFEL</p><p>Leverancier: Gispen</p><p>Soort: VERGADERTAFEL</p>'])
            @endfor
        @endif
    </div>
    {{-- Paginering --}}
    <div class="productOverviewPagination">
        @if (isset($data['totalPages']))
            @include('snippets.pagination', ['total_pages' => $data['totalPages'], 'active_page' => $data['currentPage']])
        @else
            @include('snippets.pagination', ['total_pages' => 8, 'active_page' => 8])
        @endif
    </div>
</div>
