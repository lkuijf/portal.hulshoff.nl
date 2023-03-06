<article class="product">
    <div class="prodInnerWrap">
        <div class="prodCodeWrap">{{ __('Article') }} code: <strong>{!! $product_code !!}</strong></div>
        <div class="prodImgHolder"><img src="{!! $product_image !!}" alt="placeholder"></div>
        <div class="prodInfo">
            {!! $product_info !!}
        </div>
    </div>
    <div class="prodToDetail"><a href="{{ route('products') }}/{!! $product_id !!}">&nbsp;</a></div>
</article>