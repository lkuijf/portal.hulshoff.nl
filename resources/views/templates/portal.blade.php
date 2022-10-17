<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="{{ asset('css/styles.css') }}">
    <title>Hulshoff webportal</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Heebo:wght@400;700&display=swap" rel="stylesheet">
</head>
<body>
    <div class="gridContainer">
        <div class="logoCell"><img src="{{ asset('statics/hulshoff-logo.png') }}" alt=""></div>
        <div class="breadcrumbsCell">
            <ul>
                <li><a href="#">PRODUCTEN</a></li>
                <li><a href="#">WERKPLEK</a></li>
                <li><a href="#">TAFEL</a></li>
            </ul>
        </div>
        <div class="basketCell"><span class="cart"><span>2</span></span></div>
        <div class="accountCell">
            acc
        </div>
        <div class="navigationCell">
            <nav>
                <ul>
                    <li><a href="#">Item</a></li>
                    <li><a href="#">Item</a></li>
                    <li><a href="#">Item</a></li>
                    <li><a href="#">Item</a></li>
                    <li><a href="#">Item</a></li>
                </ul>
            </nav>
        </div>
        <div class="infoCell">i</div>
        <div class="contentCell">
            <div class="textContent">
                <h1>Heading 1</h1>
                <p>Content</p>
                <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>
                <p>Content</p>
                <h2>Heading 2</h2>
                <p>Content</p>
                <p>Content</p>
                <h3>Heading 3</h3>
                <p>Content</p>
            </div>
            <div class="productOverviewContent">
                <div class="filterWrap">
                    <div class="filters">
                        <h4>Filteren</h4>
                        <select name="Leverancier">
                            <option value="">- Selecteer leverancier -</option>
                            <option value="Gispen" selected>Gispen</option>
                        </select>
                        <select name="Kleur">
                            <option value="">- Selecteer kleur -</option>
                            <option value="Rood">Rood</option>
                            <option value="Blauw">Blauw</option>
                        </select>
                        <select name="Soort">
                            <option value="">- Selecteer soort -</option>
                            <option value="1">1</option>
                            <option value="2">2</option>
                        </select>
                        <h4>Actieve filters</h4>
                        <p class="activeFilter"><span>Leverancier:&nbsp;Gispen</span><a href="#">&nbsp;</a></p>
                        <p class="activeFilter"><span>ss:&nbsp;ff</span><a href="#">&nbsp;</a></p>
                        <p class="activeFilter"><span>ccccccc:&nbsp;bbbbbbbb</span><a href="#">&nbsp;</a></p>
                    </div>
                </div>
                <div class="productList">
                    <div class="product">
                        <div class="prodInnerWrap">
                            <div class="prodImgHolder"><img src="https://picsum.photos/300/200" alt="placeholder"></div>
                            <div class="prodInfo">
                                <p>Categorie: WERKPLEK</p>
                                <p>Inrichtingsconcept: TAFEL</p>
                                <p>Leverancier: Gispen</p>
                                <p>Soort: VERGADERTAFEL</p>
                            </div>
                        </div>
                        <div class="prodToDetail"><a href="#">&nbsp;</a></div>
                    </div>
                    <div class="product">
                        <div class="prodInnerWrap">
                            <div class="prodImgHolder"><img src="https://picsum.photos/300/200" alt="placeholder"></div>
                            <div class="prodInfo">
                                <p>Categorie: WERKPLEK</p>
                                <p>Inrichtingsconcept: TAFEL</p>
                                <p>Leverancier: Gispen</p>
                                <p>Soort: VERGADERTAFEL</p>
                            </div>
                        </div>
                        <div class="prodToDetail"><a href="#">&nbsp;</a></div>
                    </div>
                    <div class="product">
                        <div class="prodInnerWrap">
                            <div class="prodImgHolder"><img src="https://picsum.photos/300/200" alt="placeholder"></div>
                            <div class="prodInfo">
                                <p>Categorie: WERKPLEK</p>
                                <p>Inrichtingsconcept: TAFEL</p>
                                <p>Leverancier: Gispen</p>
                                <p>Soort: VERGADERTAFEL</p>
                            </div>
                        </div>
                        <div class="prodToDetail"><a href="#">&nbsp;</a></div>
                    </div>
                    <div class="product">
                        <div class="prodInnerWrap">
                            <div class="prodImgHolder"><img src="https://picsum.photos/300/200" alt="placeholder"></div>
                            <div class="prodInfo">
                                <p>Categorie: WERKPLEK</p>
                                <p>Inrichtingsconcept: TAFEL</p>
                                <p>Leverancier: Gispen</p>
                                <p>Soort: VERGADERTAFEL</p>
                            </div>
                        </div>
                        <div class="prodToDetail"><a href="#">&nbsp;</a></div>
                    </div>
                    <div class="product">
                        <div class="prodInnerWrap">
                            <div class="prodImgHolder"><img src="https://picsum.photos/300/200" alt="placeholder"></div>
                            <div class="prodInfo">
                                <p>Categorie: WERKPLEK</p>
                                <p>Inrichtingsconcept: TAFEL</p>
                                <p>Leverancier: Gispen</p>
                                <p>Soort: VERGADERTAFEL</p>
                            </div>
                        </div>
                        <div class="prodToDetail"><a href="#">&nbsp;</a></div>
                    </div>
                    <div class="product">
                        <div class="prodInnerWrap">
                            <div class="prodImgHolder"><img src="https://picsum.photos/300/200" alt="placeholder"></div>
                            <div class="prodInfo">
                                <p>Categorie: WERKPLEK</p>
                                <p>Inrichtingsconcept: TAFEL</p>
                                <p>Leverancier: Gispen</p>
                                <p>Soort: VERGADERTAFEL</p>
                            </div>
                        </div>
                        <div class="prodToDetail"><a href="#">&nbsp;</a></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="footerCell">
            Footer
        </div>
    </div>
</body>
</html>