<div class="elementTilesContent">
    <h1>Maak uw keuze</h1>
    <div class="eTilesWrap">
        @for ($x=0;$x<8;$x++)
        <div class="eTile">
            <img src="https://picsum.photos/200/200" alt="">
            <p><a href="#">Aankleding</a></p>
        </div>
        @endfor
    </div>
</div>
@section('before_closing_body_tag')
    @parent
    <script>
        let tiles = document.querySelectorAll('.eTilesWrap .eTile');
        tiles.forEach(tile => {
            let linkEl = tile.querySelector('a');
            tile.addEventListener('click', () => {
                window.location.href = linkEl.href;
            });
        });
    </script>
@endsection