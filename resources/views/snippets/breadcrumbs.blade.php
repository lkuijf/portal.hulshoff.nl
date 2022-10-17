<ul class="crumbs">
    @foreach ($breadcrumbs as $crumb => $url)
    <li><a href="{!! $url !!}">{{ $crumb }}</a></li>    
    @endforeach
</ul>