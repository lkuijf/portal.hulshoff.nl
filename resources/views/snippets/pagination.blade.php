<ul class="productPagination">
    <li><a href="#"@if($active_page == 1){!! ' class="inactive"' !!}@endif data-go-to-page-number="{{ ($active_page-1>0?$active_page-1:1) }}">{{ __('Previous') }}</a></li>
    @php
        $start = $active_page - 3;
        $end = $active_page + 3;
        if($start < 1) {
            $end += ($start*-1+1);
            $start = 1;
        }
        if($end > $total_pages) {
            $start -= ($end-$total_pages);
            if($start < 1) $start = 1;
            $end = $total_pages;
        }
    @endphp
    @for ($x=$start;$x<=$end;$x++)
        <li><a href="#"@if($x==$active_page){!! ' class="active"' !!}@endif data-go-to-page-number="{{ $x }}">{{ $x }}</a></li>
    @endfor
    <li><a href="#"@if($active_page==$total_pages){!! ' class="inactive"' !!}@endif data-go-to-page-number="{{ ($active_page+1<=$total_pages?$active_page+1:$total_pages) }}">{{ __('Next') }}</a></li>
    <li>{{ $total_pages }} {{ __('Total pages') }} @if($total_pages > 7)Ga naar pagina <form action=""><input type="text" size="4" maxlength="4"><button>Gaan</button></form>@endif</li>
</ul>
