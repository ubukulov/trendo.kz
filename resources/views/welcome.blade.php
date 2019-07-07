@extends('layouts.app')
@section('content')


    <div id="content" class="site-content" tabindex="-1">
        <div class="container">
            <div id="primary" class="content-area">
                <main id="main" class="site-main">
                    <div class="home-v1-slider" style="display: none;">
                        <!-- ========================================== SECTION – HERO : END========================================= -->

                        <div id="owl-main" class="owl-carousel owl-inner-nav owl-ui-sm">

                            <div class="item" style="background-image: url(assets/images/slider/banner-2.jpg);">
                                <div class="container">
                                    <div class="row">
                                        <div class="col-md-offset-3 col-md-5">
                                            <div class="caption vertical-center text-left">
                                                <div class="hero-1 fadeInDown-1">
                                                    The New <br> Standard
                                                </div>

                                                <div class="hero-subtitle fadeInDown-2">
                                                    under favorable smartwatches
                                                </div>
                                                <div class="hero-v2-price fadeInDown-3">
                                                    from <br><span>$749</span>
                                                </div>
                                                <div class="hero-action-btn fadeInDown-4">
                                                    <a href="single-product.html" class="big le-button ">Start Buying</a>
                                                </div>
                                            </div><!-- /.caption -->
                                        </div>
                                    </div>
                                </div><!-- /.container -->
                            </div><!-- /.item -->


                            <div class="item" style="background-image: url(assets/images/slider/banner-1.jpg);">
                                <div class="container">
                                    <div class="row">
                                        <div class="col-md-offset-3 col-md-5">
                                            <div class="caption vertical-center text-left">
                                                <div class="hero-subtitle-v2 fadeInDown-1">
                                                    shop to get what you loves
                                                </div>

                                                <div class="hero-2 fadeInDown-2">
                                                    Timepieces that make a statement up to <strong>40% Off</strong>
                                                </div>

                                                <div class="hero-action-btn fadeInDown-3">
                                                    <a href="single-product.html" class="big le-button ">Start Buying</a>
                                                </div>
                                            </div><!-- /.caption -->
                                        </div>
                                    </div>
                                </div><!-- /.container -->
                            </div><!-- /.item -->

                            <div class="item" style="background-image: url(assets/images/slider/banner-3.jpg);">
                                <div class="container">
                                    <div class="row">
                                        <div class="col-md-offset-3 col-md-5">
                                            <div class="caption vertical-center text-left">
                                                <div class="hero-subtitle-v2 fadeInLeft-1">
                                                    shop to get what you loves
                                                </div>

                                                <div class="hero-2 fadeInRight-1">
                                                    Timepieces that make a statement up to <strong>40% Off</strong>
                                                </div>

                                                <div class="hero-action-btn fadeInLeft-2">
                                                    <a href="single-product.html" class="big le-button ">Start Buying</a>
                                                </div>
                                            </div><!-- /.caption -->
                                        </div>
                                    </div>
                                </div><!-- /.container -->
                            </div><!-- /.item -->


                        </div><!-- /.owl-carousel -->

                        <!-- ========================================= SECTION – HERO : END ========================================= -->

                    </div><!-- /.home-v1-slider -->

                    <div class="home-v1-ads-block animate-in-view fadeIn animated" data-animation="fadeIn">
                        <div class="ads-block row">
                            <div class="ad col-xs-12 col-sm-4">
                                <div class="media">
                                    <div class="media-left media-middle">
                                        <img data-echo="assets/images/banner/cameras.jpg" src="assets/images/blank.gif" alt="">
                                    </div>
                                    <div class="media-body media-middle">
                                        <div class="ad-text">
                                            Catch Big <br><strong>Deals</strong> on the <br>Cameras
                                        </div>
                                        <div class="ad-action">
                                            <a href="#">Shop now</a>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="ad col-xs-12 col-sm-4">
                                <div class="media">
                                    <div class="media-left media-middle">
                                        <img data-echo="assets/images/banner/MobileDevicesv2-2.jpg" src="assets/images/blank.gif" alt="">
                                    </div>
                                    <div class="media-body media-middle">
                                        <div class="ad-text">
                                            Tablets,<br> Smartphones<br> <strong>and more</strong>
                                        </div>
                                        <div class="ad-action">
                                            <a href="#"><span class="upto"><span class="prefix">Upto</span><span class="value">70</span><span class="suffix"></span></span></a>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="ad col-xs-12 col-sm-4">
                                <div class="media">
                                    <div class="media-left media-middle">
                                        <img data-echo="assets/images/banner/DesktopPC.jpg" src="assets/images/blank.gif" alt="">
                                    </div>
                                    <div class="media-body media-middle">
                                        <div class="ad-text">
                                            Shop the <br><strong>Hottest</strong><br> Products
                                        </div>
                                        <div class="ad-action">
                                            <a href="#">Shop now</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="home-v1-deals-and-tabs deals-and-tabs row animate-in-view fadeIn animated" data-animation="fadeIn" style="margin-top: 20px;">
                        <div class="deals-block col-lg-4">
                            <section class="section-onsale-product">
                                <header>
                                    <h2 class="h1">@lang('messages.Special Offer')</h2>
                                    <div class="savings">
                                        <span class="savings-text">Save <span class="amount">$20.00</span></span>
                                    </div>
                                </header><!-- /header -->

                                <div class="onsale-products">
                                    <div class="onsale-product">
                                        <a href="{{ $special_offer->url() }}">
                                            <div class="product-thumbnail">
                                                <img class="{{ $special_offer->getImage() }}" data-echo="{{ $special_offer->getImage() }}" src="{{ $special_offer->getImage() }}" alt=""></div>

                                            <h3>{!! $special_offer->title !!}</h3>
                                        </a>

                                        <span class="price">
                            						<span class="electro-price">
                            							<ins><span class="amount"> {!! format_price($special_offer->getPrice()) !!} &#8376;</span></ins>
                            						</span>
                            					</span><!-- /.price -->

                                        <div class="deal-progress">
                                            <div class="deal-stock">
                                                <span class="stock-sold">@lang('messages.Already Sold'): <strong>2</strong></span>
                                                <span class="stock-available">@lang('messages.Available'): <strong>26</strong></span>
                                            </div>

                                            <div class="progress">
                                                <span class="progress-bar" style="width:8%">8</span>
                                            </div>
                                        </div><!-- /.deal-progress -->

                                        <div class="deal-countdown-timer">
                                            <div class="marketing-text text-xs-center">@lang('messages.Hurry Up! Offer ends in'):	</div>


                                            <div id="deal-countdown" class="countdown">
                                                <span data-value="0" class="days"><span class="value">0</span><b>Days</b></span>
                                                <span class="hours"><span class="value">7</span><b>Hours</b></span>
                                                <span class="minutes"><span class="value">29</span><b>Mins</b></span>
                                                <span class="seconds"><span class="value">13</span><b>Secs</b></span>
                                            </div>
                                            <span class="deal-end-date" style="display:none;">2016-12-31</span>
                                            <script>
                                                // set the date we're counting down to
                                                var deal_end_date = document.querySelector(".deal-end-date").textContent;
                                                var target_date = new Date( deal_end_date ).getTime();

                                                // variables for time units
                                                var days, hours, minutes, seconds;

                                                // get tag element
                                                var countdown = document.getElementById( 'deal-countdown' );

                                                // update the tag with id "countdown" every 1 second
                                                setInterval( function () {

                                                    // find the amount of "seconds" between now and target
                                                    var current_date = new Date().getTime();
                                                    var seconds_left = (target_date - current_date) / 1000;

                                                    // do some time calculations
                                                    days = parseInt(seconds_left / 86400);
                                                    seconds_left = seconds_left % 86400;

                                                    hours = parseInt(seconds_left / 3600);
                                                    seconds_left = seconds_left % 3600;

                                                    minutes = parseInt(seconds_left / 60);
                                                    seconds = parseInt(seconds_left % 60);

                                                    // format countdown string + set tag value
                                                    countdown.innerHTML = '<span data-value="' + days + '" class="days"><span class="value">' + days +  '</span><b>Days</b></span><span class="hours"><span class="value">' + hours + '</span><b>Hours</b></span><span class="minutes"><span class="value">'
                                                            + minutes + '</span><b>Mins</b></span><span class="seconds"><span class="value">' + seconds + '</span><b>Secs</b></span>';

                                                }, 1000 );
                                            </script>
                                        </div><!-- /.deal-countdown-timer -->
                                    </div><!-- /.onsale-product -->
                                </div><!-- /.onsale-products -->
                            </section><!-- /.section-onsale-product -->
                        </div><!-- /.col -->


                        <div class="tabs-block col-lg-8">
                            <div class="products-carousel-tabs">
                                <ul class="nav nav-inline">
                                    <li class="nav-item"><a class="nav-link active" href="#tab-products-1" data-toggle="tab">@lang('messages.Featured')</a></li>
                                    <li class="nav-item"><a class="nav-link" href="#tab-products-2" data-toggle="tab">@lang('messages.On Sale')</a></li>
                                    <li class="nav-item"><a class="nav-link" href="#tab-products-3" data-toggle="tab">@lang('messages.Top Rated')</a></li>
                                </ul>

                                <div class="tab-content">
                                    <div class="tab-pane active" id="tab-products-1" role="tabpanel">
                                        <div class="woocommerce columns-3">
                                            <ul class="products columns-3">
                                                @foreach($recommended_products as $recommend)
                                                <li class="product">
                                                    <div class="product-outer">
                                                        <div class="product-inner">
                                                            <a href="{{ $recommend->url() }}">
                                                                <h3>{!! $recommend->title !!}</h3>
                                                                <div class="product-thumbnail">
                                                                    <img src="{{ $recommend->getImage() }}" data-echo="{{ $recommend->getImage() }}" class="img-responsive" alt="{{ $recommend->title }}">
                                                                </div>
                                                            </a>

                                                            <div class="price-add-to-cart">
                                                                        <span class="price">
                                                                            <span class="electro-price">
                                                                                <ins><span class="amount"> {!! format_price($recommend->getPrice()) !!} &#8376;</span></ins>
                                                                                <span class="amount"> </span>
                                                                            </span>
                                                                        </span>
                                                                <a rel="nofollow" href="{{ route('cart.add', ['product_id' => $recommend->id]) }}" class="button add_to_cart_button">@lang('messages.Add to cart')</a>
                                                            </div><!-- /.price-add-to-cart -->

                                                            <div class="hover-area">
                                                                <div class="action-buttons">

                                                                    <a href="#" rel="nofollow" class="add_to_wishlist"> @lang('messages.Wishlist')</a>

                                                                    <a href="compare.html" class="add-to-compare-link"> @lang('messages.Compare')</a>
                                                                </div>
                                                            </div>
                                                        </div><!-- /.product-inner -->
                                                    </div><!-- /.product-outer -->
                                                </li><!-- /.products -->
                                                @endforeach
                                            </ul>
                                        </div>
                                    </div>

                                    <div class="tab-pane" id="tab-products-2" role="tabpanel">
                                        <div class="woocommerce columns-3">
                                            <ul class="products columns-3">
                                                @foreach($on_sales as $sale)
                                                <li class="product">
                                                    <div class="product-outer">
                                                        <div class="product-inner">
                                                            <a href="{{ $sale->url() }}">
                                                                <h3>{{ $sale->title }}</h3>
                                                                <div class="product-thumbnail">

                                                                    <img data-echo="{{ $sale->getImage() }}" src="{{ $sale->getImage() }}" alt="{{ $sale->title }}">

                                                                </div>
                                                            </a>

                                                            <div class="price-add-to-cart">
                                                                        <span class="price">
                                                                            <span class="electro-price">
                                                                                <ins><span class="amount">{!! format_price($sale->getPrice()) !!} &#8376;</span></ins>
                                                                            </span>
                                                                        </span>
                                                                <a rel="nofollow" href="{{ route('cart.add', ['product_id' => $sale->id]) }}" class="button add_to_cart_button">@lang('messages.Add to cart')</a>
                                                            </div><!-- /.price-add-to-cart -->

                                                            <div class="hover-area">
                                                                <div class="action-buttons">

                                                                    <a href="#" rel="nofollow" class="add_to_wishlist">
                                                                        @lang('messages.Wishlist')</a>

                                                                    <a href="#" class="add-to-compare-link">@lang('messages.Compare')</a>
                                                                </div>
                                                            </div>
                                                        </div><!-- /.product-inner -->
                                                    </div><!-- /.product-outer -->
                                                </li>
                                                @endforeach
                                            </ul>
                                        </div>
                                    </div>

                                    <div class="tab-pane" id="tab-products-3" role="tabpanel">
                                        <div class="woocommerce columns-3">

                                            <ul class="products columns-3">
                                                @each('pattern.featured', $most_populars, 'popular')
                                            </ul>

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div><!-- /.tabs-block -->
                    </div><!-- /.deals-and-tabs -->

                    <!-- ============================================================= 2-1-2 Product Grid ============================================================= -->
                    <section class="products-2-1-2 animate-in-view fadeIn animated" data-animation="fadeIn">
                        <h2 class="sr-only">Products Grid</h2>
                        <div class="container">

                            <ul class="nav nav-inline nav-justified">

                                <li class="nav-item"><a href="shop.html" class="active nav-link">Best Deals</a></li>
                                <li class="nav-item"><a class="nav-link" href="shop.html">TV &amp; Audio</a></li>
                                <li class="nav-item"><a class="nav-link" href="shop.html">Cameras</a></li>
                                <li class="nav-item"><a class="nav-link" href="shop.html">Audio</a></li>
                                <li class="nav-item"><a class="nav-link" href="shop.html">Smartphones</a></li>
                                <li class="nav-item"><a class="nav-link" href="shop.html">GPS &amp; Navi</a></li>
                                <li class="nav-item"><a class="nav-link" href="shop.html">Computers</a></li>
                                <li class="nav-item"><a class="nav-link" href="shop.html">Portable Audio</a></li>
                                <li class="nav-item"><a class="nav-link" href="shop.html">Accessories</a></li>

                            </ul>

                            <div class="columns-2-1-2">
                                <ul class="products exclude-auto-height">
                                    <li class="product">
                                        <div class="product-outer">
                                            <div class="product-inner">
                                                <span class="loop-product-categories"><a href="product-category.html" rel="tag">Smartphones</a></span>
                                                <a href="single-product.html">
                                                    <h3>Notebook Black Spire V Nitro  VN7-591G</h3>
                                                    <div class="product-thumbnail">

                                                        <img data-echo="assets/images/product-2-1-2/1.jpg" src="assets/images/blank.gif" alt="">

                                                    </div>
                                                </a>

                                                <div class="price-add-to-cart">
                                                            <span class="price">
                                                                <span class="electro-price">
                                                                    <ins><span class="amount">&#036;1,999.00</span></ins>
                                                                    <del><span class="amount">&#036;2,299.00</span></del>
                                                                </span>
                                                            </span>
                                                    <a rel="nofollow" href="single-product.html" class="button add_to_cart_button">Add to cart</a>
                                                </div><!-- /.price-add-to-cart -->

                                                <div class="hover-area">
                                                    <div class="action-buttons">

                                                        <a href="#" rel="nofollow" class="add_to_wishlist">
                                                            Wishlist</a>

                                                        <a href="#" class="add-to-compare-link">Compare</a>
                                                    </div>
                                                </div>
                                            </div><!-- /.product-inner -->
                                        </div><!-- /.product-outer -->
                                    </li>
                                    <li class="product">
                                        <div class="product-outer">
                                            <div class="product-inner">
                                                <span class="loop-product-categories"><a href="product-category.html" rel="tag">Smartphones</a></span>
                                                <a href="single-product.html">
                                                    <h3>Notebook Black Spire V Nitro  VN7-591G</h3>
                                                    <div class="product-thumbnail">

                                                        <img data-echo="assets/images/product-2-1-2/4.jpg" src="assets/images/blank.gif" alt="">

                                                    </div>
                                                </a>

                                                <div class="price-add-to-cart">
                                                            <span class="price">
                                                                <span class="electro-price">
                                                                    <ins><span class="amount">&#036;1,999.00</span></ins>
                                                                    <del><span class="amount">&#036;2,299.00</span></del>
                                                                </span>
                                                            </span>
                                                    <a rel="nofollow" href="single-product.html" class="button add_to_cart_button">Add to cart</a>
                                                </div><!-- /.price-add-to-cart -->

                                                <div class="hover-area">
                                                    <div class="action-buttons">

                                                        <a href="#" rel="nofollow" class="add_to_wishlist">
                                                            Wishlist</a>

                                                        <a href="#" class="add-to-compare-link">Compare</a>
                                                    </div>
                                                </div>
                                            </div><!-- /.product-inner -->
                                        </div><!-- /.product-outer -->
                                    </li>
                                </ul>

                                <ul class="products exclude-auto-height product-main-2-1-2">
                                    <li class="last product">
                                        <div class="product-outer">
                                            <div class="product-inner">
                                                <span class="loop-product-categories"><a href="product-category.html" rel="tag">Smartphones</a></span>
                                                <a href="single-product.html">
                                                    <h3>Notebook Black Spire V Nitro  VN7-591G</h3>
                                                    <div class="product-thumbnail">
                                                        <img class="wp-post-image" data-echo="assets/images/product-2-1-2/main.jpg" src="assets/images/blank.gif" alt="">

                                                    </div>
                                                </a>

                                                <div class="price-add-to-cart">
                                                            <span class="price">
                                                                <span class="electro-price">
                                                                    <ins><span class="amount">&#036;1,999.00</span></ins>
                                                                    <del><span class="amount">&#036;2,299.00</span></del>
                                                                </span>
                                                            </span>
                                                    <a rel="nofollow" href="single-product.html" class="button add_to_cart_button">Add to cart</a>
                                                </div><!-- /.price-add-to-cart -->

                                                <div class="hover-area">
                                                    <div class="action-buttons">

                                                        <a href="#" rel="nofollow" class="add_to_wishlist">
                                                            Wishlist</a>

                                                        <a href="#" class="add-to-compare-link">Compare</a>
                                                    </div>
                                                </div>
                                            </div><!-- /.product-inner -->
                                        </div><!-- /.product-outer -->
                                    </li>
                                </ul>

                                <ul class="products exclude-auto-height">
                                    <li class="product">
                                        <div class="product-outer">
                                            <div class="product-inner">
                                                <span class="loop-product-categories"><a href="product-category.html" rel="tag">Smartphones</a></span>
                                                <a href="single-product.html">
                                                    <h3>Notebook Black Spire V Nitro  VN7-591G</h3>
                                                    <div class="product-thumbnail">

                                                        <img class="wp-post-image" data-echo="assets/images/product-2-1-2/1.jpg" src="assets/images/blank.gif" alt="">


                                                    </div>
                                                </a>

                                                <div class="price-add-to-cart">
                                                            <span class="price">
                                                                <span class="electro-price">
                                                                    <ins><span class="amount">&#036;1,999.00</span></ins>
                                                                    <del><span class="amount">&#036;2,299.00</span></del>
                                                                </span>
                                                            </span>
                                                    <a rel="nofollow" href="single-product.html" class="button add_to_cart_button">Add to cart</a>
                                                </div><!-- /.price-add-to-cart -->

                                                <div class="hover-area">
                                                    <div class="action-buttons">

                                                        <a href="#" rel="nofollow" class="add_to_wishlist">
                                                            Wishlist</a>

                                                        <a href="#" class="add-to-compare-link">Compare</a>
                                                    </div>
                                                </div>
                                            </div><!-- /.product-inner -->
                                        </div><!-- /.product-outer -->
                                    </li>
                                    <li class="product">
                                        <div class="product-outer">
                                            <div class="product-inner">
                                                <span class="loop-product-categories"><a href="product-category.html" rel="tag">Smartphones</a></span>
                                                <a href="single-product.html">
                                                    <h3>Notebook Black Spire V Nitro  VN7-591G</h3>
                                                    <div class="product-thumbnail">

                                                        <img class="wp-post-image" data-echo="assets/images/product-2-1-2/4.jpg" src="assets/images/blank.gif" alt="">


                                                    </div>
                                                </a>

                                                <div class="price-add-to-cart">
                                                            <span class="price">
                                                                <span class="electro-price">
                                                                    <ins><span class="amount">&#036;1,999.00</span></ins>
                                                                    <del><span class="amount">&#036;2,299.00</span></del>
                                                                </span>
                                                            </span>
                                                    <a rel="nofollow" href="single-product.html" class="button add_to_cart_button">Add to cart</a>
                                                </div><!-- /.price-add-to-cart -->

                                                <div class="hover-area">
                                                    <div class="action-buttons">

                                                        <a href="#" rel="nofollow" class="add_to_wishlist">
                                                            Wishlist</a>

                                                        <a href="#" class="add-to-compare-link">Compare</a>
                                                    </div>
                                                </div>
                                            </div><!-- /.product-inner -->
                                        </div><!-- /.product-outer -->
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </section>
                    <!-- ============================================================= 2-1-2 Product Grid : End============================================================= -->

                    <section class="section-product-cards-carousel animate-in-view fadeIn animated" data-animation="fadeIn">

                        <header>

                            <h2 class="h1">@lang('messages.Best Sellers')</h2>

                            <ul class="nav nav-inline">

                                <li class="nav-item active"><span class="nav-link">Top 20</span></li>

                                <li class="nav-item"><a class="nav-link" href="product-category.html">Smart Phones &amp; Tablets</a></li>

                                <li class="nav-item"><a class="nav-link" href="product-category.html">Laptops &amp; Computers</a></li>

                                <li class="nav-item"><a class="nav-link" href="product-category.html">Video Cameras</a></li>
                            </ul>
                        </header>

                        <div id="home-v1-product-cards-careousel">
                            <div class="woocommerce columns-3 home-v1-product-cards-carousel product-cards-carousel owl-carousel">

                                <ul class="products columns-3">
                                    <li class="product product-card first">

                                        <div class="product-outer">
                                            <div class="media product-inner">

                                                <a class="media-left" href="single-product.html" title="Pendrive USB 3.0 Flash 64 GB">
                                                    <img class="media-object wp-post-image img-responsive" src="assets/images/blank.gif" data-echo="assets/images/product-cards/4.jpg" alt="">
                                                </a>

                                                <div class="media-body">
                                                            <span class="loop-product-categories">
                                                                <a href="product-category.html" rel="tag">TVs</a>
                                                            </span>

                                                    <a href="single-product.html">
                                                        <h3>Widescreen 4K SUHD TV</h3>
                                                    </a>

                                                    <div class="price-add-to-cart">
                                                                <span class="price">
                                                                    <span class="electro-price">
                                                                        <ins><span class="amount"> </span></ins>
                                                                        <span class="amount"> $800</span>
                                                                    </span>
                                                                </span>

                                                        <a href="cart.html" class="button add_to_cart_button">Add to cart</a>
                                                    </div><!-- /.price-add-to-cart -->

                                                    <div class="hover-area">
                                                        <div class="action-buttons">
                                                            <a href="#" class="add_to_wishlist">Wishlist</a>
                                                            <a href="#" class="add-to-compare-link">Compare</a>
                                                        </div>
                                                    </div>

                                                </div>
                                            </div><!-- /.product-inner -->
                                        </div><!-- /.product-outer -->

                                    </li><!-- /.products -->
                                    <li class="product product-card ">

                                        <div class="product-outer">
                                            <div class="media product-inner">

                                                <a class="media-left" href="single-product.html" title="Pendrive USB 3.0 Flash 64 GB">
                                                    <img class="media-object wp-post-image img-responsive" src="assets/images/blank.gif" data-echo="assets/images/product-cards/6.jpg" alt="">
                                                </a>

                                                <div class="media-body">
                                                            <span class="loop-product-categories">
                                                                <a href="product-category.html" rel="tag">Peripherals</a>
                                                            </span>

                                                    <a href="single-product.html">
                                                        <h3>External SSD USB 3.1  750 GB</h3>
                                                    </a>

                                                    <div class="price-add-to-cart">
                                                                <span class="price">
                                                                    <span class="electro-price">
                                                                        <ins><span class="amount"> </span></ins>
                                                                        <span class="amount"> $600</span>
                                                                    </span>
                                                                </span>

                                                        <a href="cart.html" class="button add_to_cart_button">Add to cart</a>
                                                    </div><!-- /.price-add-to-cart -->

                                                    <div class="hover-area">
                                                        <div class="action-buttons">
                                                            <a href="#" class="add_to_wishlist">Wishlist</a>
                                                            <a href="#" class="add-to-compare-link">Compare</a>
                                                        </div>
                                                    </div>

                                                </div>
                                            </div><!-- /.product-inner -->
                                        </div><!-- /.product-outer -->

                                    </li><!-- /.products -->
                                    <li class="product product-card last">

                                        <div class="product-outer">
                                            <div class="media product-inner">

                                                <a class="media-left" href="single-product.html" title="Pendrive USB 3.0 Flash 64 GB">
                                                    <img class="media-object wp-post-image img-responsive" src="assets/images/blank.gif" data-echo="assets/images/product-cards/5.jpg" alt="">
                                                </a>

                                                <div class="media-body">
                                                            <span class="loop-product-categories">
                                                                <a href="product-category.html" rel="tag">Printers</a>
                                                            </span>

                                                    <a href="single-product.html">
                                                        <h3>Full Color LaserJet Pro  M452dn</h3>
                                                    </a>

                                                    <div class="price-add-to-cart">
                                                                <span class="price">
                                                                    <span class="electro-price">
                                                                        <ins><span class="amount"> $3,788.00</span></ins>
                                                                        <del><span class="amount">$4,780.00</span></del>
                                                                        <span class="amount"> </span>
                                                                    </span>
                                                                </span>

                                                        <a href="cart.html" class="button add_to_cart_button">Add to cart</a>
                                                    </div><!-- /.price-add-to-cart -->

                                                    <div class="hover-area">
                                                        <div class="action-buttons">
                                                            <a href="#" class="add_to_wishlist">Wishlist</a>
                                                            <a href="#" class="add-to-compare-link">Compare</a>
                                                        </div>
                                                    </div>

                                                </div>
                                            </div><!-- /.product-inner -->
                                        </div><!-- /.product-outer -->

                                    </li><!-- /.products -->
                                    <li class="product product-card first">

                                        <div class="product-outer">
                                            <div class="media product-inner">

                                                <a class="media-left" href="single-product.html" title="Pendrive USB 3.0 Flash 64 GB">
                                                    <img class="img-responsive media-object wp-post-image" src="assets/images/blank.gif" data-echo="assets/images/product-cards/1.jpg" alt="">

                                                </a>

                                                <div class="media-body">
                                                            <span class="loop-product-categories">
                                                                <a href="product-category.html" rel="tag">Smartphones</a>
                                                            </span>

                                                    <a href="single-product.html">
                                                        <h3>Notebook Purple G752VT-T7008T</h3>
                                                    </a>

                                                    <div class="price-add-to-cart">
                                                                <span class="price">
                                                                    <span class="electro-price">
                                                                        <ins><span class="amount"> $3,788.00</span></ins>
                                                                        <del><span class="amount">$4,780.00</span></del>
                                                                        <span class="amount"> </span>
                                                                    </span>
                                                                </span>

                                                        <a href="cart.html" class="button add_to_cart_button">Add to cart</a>
                                                    </div><!-- /.price-add-to-cart -->

                                                    <div class="hover-area">
                                                        <div class="action-buttons">
                                                            <a href="#" class="add_to_wishlist">Wishlist</a>
                                                            <a href="#" class="add-to-compare-link">Compare</a>
                                                        </div>
                                                    </div>

                                                </div>
                                            </div><!-- /.product-inner -->
                                        </div><!-- /.product-outer -->

                                    </li><!-- /.products -->
                                    <li class="product product-card ">

                                        <div class="product-outer">
                                            <div class="media product-inner">

                                                <a class="media-left" href="single-product.html" title="Pendrive USB 3.0 Flash 64 GB">
                                                    <img class="img-responsive media-object wp-post-image" src="assets/images/blank.gif" data-echo="assets/images/product-cards/3.jpg" alt="">
                                                </a>

                                                <div class="media-body">
                                                            <span class="loop-product-categories">
                                                                <a href="product-category.html" rel="tag">Headphone Cases</a>
                                                            </span>

                                                    <a href="single-product.html">
                                                        <h3>Universal Headphones Case in Black</h3>
                                                    </a>

                                                    <div class="price-add-to-cart">
                                                                <span class="price">
                                                                    <span class="electro-price">
                                                                        <ins><span class="amount"> $3,788.00</span></ins>
                                                                        <del><span class="amount">$4,780.00</span></del>
                                                                        <span class="amount"> </span>
                                                                    </span>
                                                                </span>

                                                        <a href="cart.html" class="button add_to_cart_button">Add to cart</a>
                                                    </div><!-- /.price-add-to-cart -->

                                                    <div class="hover-area">
                                                        <div class="action-buttons">
                                                            <a href="#" class="add_to_wishlist">Wishlist</a>
                                                            <a href="#" class="add-to-compare-link">Compare</a>
                                                        </div>
                                                    </div>

                                                </div>
                                            </div><!-- /.product-inner -->
                                        </div><!-- /.product-outer -->

                                    </li><!-- /.products -->
                                    <li class="product product-card last">

                                        <div class="product-outer">
                                            <div class="media product-inner">

                                                <a class="media-left" href="single-product.html" title="Pendrive USB 3.0 Flash 64 GB">
                                                    <img class="img-responsive media-object wp-post-image" src="assets/images/blank.gif" data-echo="assets/images/product-cards/2.jpg" alt="">
                                                </a>

                                                <div class="media-body">
                                                            <span class="loop-product-categories">
                                                                <a href="product-category.html" rel="tag">Smartphones</a>
                                                            </span>

                                                    <a href="single-product.html">
                                                        <h3>Tablet Thin EliteBook  Revolve 810 G6</h3>
                                                    </a>

                                                    <div class="price-add-to-cart">
                                                                <span class="price">
                                                                    <span class="electro-price">
                                                                        <ins><span class="amount"> </span></ins>
                                                                        <span class="amount"> $500</span>
                                                                    </span>
                                                                </span>

                                                        <a href="cart.html" class="button add_to_cart_button">Add to cart</a>
                                                    </div><!-- /.price-add-to-cart -->

                                                    <div class="hover-area">
                                                        <div class="action-buttons">
                                                            <a href="#" class="add_to_wishlist">Wishlist</a>
                                                            <a href="#" class="add-to-compare-link">Compare</a>
                                                        </div>
                                                    </div>

                                                </div>
                                            </div><!-- /.product-inner -->
                                        </div><!-- /.product-outer -->

                                    </li><!-- /.products -->
                                </ul>
                                <ul class="products columns-3">
                                    <li class="product product-card first">

                                        <div class="product-outer">
                                            <div class="media product-inner">

                                                <a class="media-left" href="single-product.html" title="Pendrive USB 3.0 Flash 64 GB">
                                                    <img class="img-responsive media-object wp-post-image" src="assets/images/blank.gif" data-echo="assets/images/product-cards/2.jpg" alt="">
                                                </a>

                                                <div class="media-body">
                                                            <span class="loop-product-categories">
                                                                <a href="product-category.html" rel="tag">Headphone Cases</a>
                                                            </span>

                                                    <a href="single-product.html">
                                                        <h3>Universal Headphones Case in Black</h3>
                                                    </a>

                                                    <div class="price-add-to-cart">
                                                                <span class="price">
                                                                    <span class="electro-price">
                                                                        <ins><span class="amount"> </span></ins>
                                                                        <span class="amount"> $1500</span>
                                                                    </span>
                                                                </span>

                                                        <a href="cart.html" class="button add_to_cart_button">Add to cart</a>
                                                    </div><!-- /.price-add-to-cart -->

                                                    <div class="hover-area">
                                                        <div class="action-buttons">
                                                            <a href="#" class="add_to_wishlist">Wishlist</a>
                                                            <a href="#" class="add-to-compare-link">Compare</a>
                                                        </div>
                                                    </div>

                                                </div>
                                            </div><!-- /.product-inner -->
                                        </div><!-- /.product-outer -->

                                    </li><!-- /.products -->
                                    <li class="product product-card ">

                                        <div class="product-outer">
                                            <div class="media product-inner">

                                                <a class="media-left" href="single-product.html" title="Pendrive USB 3.0 Flash 64 GB">
                                                    <img class="img-responsive media-object wp-post-image" src="assets/images/blank.gif" data-echo="assets/images/product-cards/5.jpg" alt="">
                                                </a>

                                                <div class="media-body">
                                                            <span class="loop-product-categories">
                                                                <a href="product-category.html" rel="tag">Printers</a>
                                                            </span>

                                                    <a href="single-product.html">
                                                        <h3>Full Color LaserJet Pro  M452dn</h3>
                                                    </a>

                                                    <div class="price-add-to-cart">
                                                                <span class="price">
                                                                    <span class="electro-price">
                                                                        <ins><span class="amount"> </span></ins>
                                                                        <span class="amount"> $500</span>
                                                                    </span>
                                                                </span>

                                                        <a href="cart.html" class="button add_to_cart_button">Add to cart</a>
                                                    </div><!-- /.price-add-to-cart -->

                                                    <div class="hover-area">
                                                        <div class="action-buttons">
                                                            <a href="#" class="add_to_wishlist">Wishlist</a>
                                                            <a href="#" class="add-to-compare-link">Compare</a>
                                                        </div>
                                                    </div>

                                                </div>
                                            </div><!-- /.product-inner -->
                                        </div><!-- /.product-outer -->

                                    </li><!-- /.products -->
                                    <li class="product product-card last">

                                        <div class="product-outer">
                                            <div class="media product-inner">

                                                <a class="media-left" href="single-product.html" title="Pendrive USB 3.0 Flash 64 GB">
                                                    <img class="img-responsive media-object wp-post-image" src="assets/images/blank.gif" data-echo="assets/images/product-cards/4.jpg" alt="">
                                                </a>

                                                <div class="media-body">
                                                            <span class="loop-product-categories">
                                                                <a href="product-category.html" rel="tag">TVs</a>
                                                            </span>

                                                    <a href="single-product.html">
                                                        <h3>Widescreen 4K SUHD TV</h3>
                                                    </a>

                                                    <div class="price-add-to-cart">
                                                                <span class="price">
                                                                    <span class="electro-price">
                                                                        <ins><span class="amount"> </span></ins>
                                                                        <span class="amount"> $400</span>
                                                                    </span>
                                                                </span>

                                                        <a href="cart.html" class="button add_to_cart_button">Add to cart</a>
                                                    </div><!-- /.price-add-to-cart -->

                                                    <div class="hover-area">
                                                        <div class="action-buttons">
                                                            <a href="#" class="add_to_wishlist">Wishlist</a>
                                                            <a href="#" class="add-to-compare-link">Compare</a>
                                                        </div>
                                                    </div>

                                                </div>
                                            </div><!-- /.product-inner -->
                                        </div><!-- /.product-outer -->

                                    </li><!-- /.products -->
                                    <li class="product product-card first">

                                        <div class="product-outer">
                                            <div class="media product-inner">

                                                <a class="media-left" href="single-product.html" title="Pendrive USB 3.0 Flash 64 GB">
                                                    <img class="img-responsive media-object wp-post-image" src="assets/images/blank.gif" data-echo="assets/images/product-cards/3.jpg" alt="">
                                                </a>

                                                <div class="media-body">
                                                            <span class="loop-product-categories">
                                                                <a href="product-category.html" rel="tag">Smartphones</a>
                                                            </span>

                                                    <a href="single-product.html">
                                                        <h3>Notebook Purple G752VT-T7008T</h3>
                                                    </a>

                                                    <div class="price-add-to-cart">
                                                                <span class="price">
                                                                    <span class="electro-price">
                                                                        <ins><span class="amount"> $3,788.00</span></ins>
                                                                        <del><span class="amount">$4,780.00</span></del>
                                                                        <span class="amount"> </span>
                                                                    </span>
                                                                </span>

                                                        <a href="cart.html" class="button add_to_cart_button">Add to cart</a>
                                                    </div><!-- /.price-add-to-cart -->

                                                    <div class="hover-area">
                                                        <div class="action-buttons">
                                                            <a href="#" class="add_to_wishlist">Wishlist</a>
                                                            <a href="#" class="add-to-compare-link">Compare</a>
                                                        </div>
                                                    </div>

                                                </div>
                                            </div><!-- /.product-inner -->
                                        </div><!-- /.product-outer -->

                                    </li><!-- /.products -->
                                    <li class="product product-card ">

                                        <div class="product-outer">
                                            <div class="media product-inner">

                                                <a class="media-left" href="single-product.html" title="Pendrive USB 3.0 Flash 64 GB">
                                                    <img class="img-responsive media-object wp-post-image" src="assets/images/blank.gif" data-echo="assets/images/product-cards/6.jpg" alt="">
                                                </a>

                                                <div class="media-body">
                                                            <span class="loop-product-categories">
                                                                <a href="product-category.html" rel="tag">Peripherals</a>
                                                            </span>

                                                    <a href="single-product.html">
                                                        <h3>External SSD USB 3.1  750 GB</h3>
                                                    </a>

                                                    <div class="price-add-to-cart">
                                                                <span class="price">
                                                                    <span class="electro-price">
                                                                        <ins><span class="amount"> $3,788.00</span></ins>
                                                                        <del><span class="amount">$4,780.00</span></del>
                                                                        <span class="amount"> </span>
                                                                    </span>
                                                                </span>

                                                        <a href="cart.html" class="button add_to_cart_button">Add to cart</a>
                                                    </div><!-- /.price-add-to-cart -->

                                                    <div class="hover-area">
                                                        <div class="action-buttons">
                                                            <a href="#" class="add_to_wishlist">Wishlist</a>
                                                            <a href="#" class="add-to-compare-link">Compare</a>
                                                        </div>
                                                    </div>

                                                </div>
                                            </div><!-- /.product-inner -->
                                        </div><!-- /.product-outer -->

                                    </li><!-- /.products -->
                                    <li class="product product-card last">

                                        <div class="product-outer">
                                            <div class="media product-inner">

                                                <a class="media-left" href="single-product.html" title="Pendrive USB 3.0 Flash 64 GB">
                                                    <img class="img-responsive media-object wp-post-image" src="assets/images/blank.gif" data-echo="assets/images/product-cards/1.jpg" alt="">
                                                </a>

                                                <div class="media-body">
                                                            <span class="loop-product-categories">
                                                                <a href="product-category.html" rel="tag">Smartphones</a>
                                                            </span>

                                                    <a href="single-product.html">
                                                        <h3>Tablet Thin EliteBook  Revolve 810 G6</h3>
                                                    </a>

                                                    <div class="price-add-to-cart">
                                                                <span class="price">
                                                                    <span class="electro-price">
                                                                        <ins><span class="amount"> $3,788.00</span></ins>
                                                                        <del><span class="amount">$4,780.00</span></del>
                                                                        <span class="amount"> </span>
                                                                    </span>
                                                                </span>

                                                        <a href="cart.html" class="button add_to_cart_button">Add to cart</a>
                                                    </div><!-- /.price-add-to-cart -->

                                                    <div class="hover-area">
                                                        <div class="action-buttons">
                                                            <a href="#" class="add_to_wishlist">Wishlist</a>
                                                            <a href="#" class="add-to-compare-link">Compare</a>
                                                        </div>
                                                    </div>

                                                </div>
                                            </div><!-- /.product-inner -->
                                        </div><!-- /.product-outer -->

                                    </li><!-- /.products -->
                                </ul>
                            </div>
                        </div><!-- #home-v1-product-cards-careousel -->

                    </section>

                    <div class="home-v1-banner-block animate-in-view fadeIn animated" data-animation="fadeIn">
                        <div class="home-v1-fullbanner-ad fullbanner-ad" style="margin-bottom: 70px">
                            <a href="#"><img src="assets/images/blank.gif" data-echo="assets/images/banner/home-v1-banner.png" class="img-responsive" alt=""></a>
                        </div>
                    </div><!-- /.home-v1-banner-block -->



                    <section class="home-v1-recently-viewed-products-carousel section-products-carousel animate-in-view fadeIn animated" data-animation="fadeIn">
                        <header>
                            <h2 class="h1">@lang('messages.Recently Added')</h2>
                            <div class="owl-nav">
                                <a href="#products-carousel-prev" data-target="#recently-added-products-carousel" class="slider-prev"><i class="fa fa-angle-left"></i></a>
                                <a href="#products-carousel-next" data-target="#recently-added-products-carousel" class="slider-next"><i class="fa fa-angle-right"></i></a>
                            </div>
                        </header>

                        <div id="recently-added-products-carousel">
                            <div class="woocommerce columns-6">
                                <div class="products owl-carousel recently-added-products products-carousel columns-6">
                                    @foreach($recommended_products as $item)
                                    <div class="product">
                                        <div class="product-outer">
                                            <div class="product-inner">
                                                <a href="{{ $item->url() }}">
                                                    <h3>{{ $item->title }}</h3>
                                                    <div class="product-thumbnail">
                                                        <img src="{{ $item->getImage() }}" data-echo="{{ $item->getImage() }}" class="img-responsive" alt="">
                                                    </div>
                                                </a>

                                                <div class="price-add-to-cart">
                                                            <span class="price">
                                                                <span class="electro-price">
                                                                    <ins><span class="amount"> {!! format_price($item->getPrice()) !!}</span></ins>
                                                                    <span class="amount"> </span>
                                                                </span>
                                                            </span>
                                                    <a rel="nofollow" href="{{ route('cart.add', ['product_id' => $recommend->id]) }}" class="button add_to_cart_button">@lang('messages.Add to cart')</a>
                                                </div><!-- /.price-add-to-cart -->

                                                <div class="hover-area">
                                                    <div class="action-buttons">

                                                        <a href="#" rel="nofollow" class="add_to_wishlist"> @lang('messages.Wishlist')</a>

                                                        <a href="compare.html" class="add-to-compare-link"> @lang('messages.Compare')</a>
                                                    </div>
                                                </div>
                                            </div><!-- /.product-inner -->
                                        </div><!-- /.product-outer -->
                                    </div><!-- /.products -->
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </section>
                </main><!-- #main -->
            </div><!-- #primary -->

        </div><!-- .container -->
    </div><!-- #content -->

    <section class="brands-carousel">
        <h2 class="sr-only">Brands Carousel</h2>
        <div class="container">
            <div id="owl-brands" class="owl-brands owl-carousel unicase-owl-carousel owl-outer-nav">

                <div class="item">

                    <a href="#">

                        <figure>
                            <figcaption class="text-overlay">
                                <div class="info">
                                    <h4>Acer</h4>
                                </div><!-- /.info -->
                            </figcaption>

                            <img src="assets/images/blank.gif" data-echo="assets/images/brands/1.png" class="img-responsive" alt="">

                        </figure>
                    </a>
                </div><!-- /.item -->


                <div class="item">

                    <a href="#">

                        <figure>
                            <figcaption class="text-overlay">
                                <div class="info">
                                    <h4>Apple</h4>
                                </div><!-- /.info -->
                            </figcaption>

                            <img src="assets/images/blank.gif" data-echo="assets/images/brands/2.png" class="img-responsive" alt="">

                        </figure>
                    </a>
                </div><!-- /.item -->


                <div class="item">

                    <a href="#">

                        <figure>
                            <figcaption class="text-overlay">
                                <div class="info">
                                    <h4>Asus</h4>
                                </div><!-- /.info -->
                            </figcaption>

                            <img src="assets/images/blank.gif" data-echo="assets/images/brands/3.png" class="img-responsive" alt="">

                        </figure>
                    </a>
                </div><!-- /.item -->


                <div class="item">

                    <a href="#">

                        <figure>
                            <figcaption class="text-overlay">
                                <div class="info">
                                    <h4>Dell</h4>
                                </div><!-- /.info -->
                            </figcaption>

                            <img src="assets/images/blank.gif" data-echo="assets/images/brands/4.png" class="img-responsive" alt="">

                        </figure>
                    </a>
                </div><!-- /.item -->


                <div class="item">

                    <a href="#">

                        <figure>
                            <figcaption class="text-overlay">
                                <div class="info">
                                    <h4>Gionee</h4>
                                </div><!-- /.info -->
                            </figcaption>

                            <img src="assets/images/blank.gif" data-echo="assets/images/brands/5.png" class="img-responsive" alt="">

                        </figure>
                    </a>
                </div><!-- /.item -->


                <div class="item">

                    <a href="#">

                        <figure>
                            <figcaption class="text-overlay">
                                <div class="info">
                                    <h4>HP</h4>
                                </div><!-- /.info -->
                            </figcaption>

                            <img src="assets/images/blank.gif" data-echo="assets/images/brands/6.png" class="img-responsive" alt="">

                        </figure>
                    </a>
                </div><!-- /.item -->


                <div class="item">

                    <a href="#">

                        <figure>
                            <figcaption class="text-overlay">
                                <div class="info">
                                    <h4>HTC</h4>
                                </div><!-- /.info -->
                            </figcaption>

                            <img src="assets/images/blank.gif" data-echo="assets/images/brands/3.png" class="img-responsive" alt="">

                        </figure>
                    </a>
                </div><!-- /.item -->


                <div class="item">

                    <a href="#">

                        <figure>
                            <figcaption class="text-overlay">
                                <div class="info">
                                    <h4>IBM</h4>
                                </div><!-- /.info -->
                            </figcaption>

                            <img src="assets/images/blank.gif" data-echo="assets/images/brands/5.png" class="img-responsive" alt="">

                        </figure>
                    </a>
                </div><!-- /.item -->


                <div class="item">

                    <a href="#">

                        <figure>
                            <figcaption class="text-overlay">
                                <div class="info">
                                    <h4>Lenova</h4>
                                </div><!-- /.info -->
                            </figcaption>

                            <img src="assets/images/blank.gif" data-echo="assets/images/brands/2.png" class="img-responsive" alt="">

                        </figure>
                    </a>
                </div><!-- /.item -->


                <div class="item">

                    <a href="#">

                        <figure>
                            <figcaption class="text-overlay">
                                <div class="info">
                                    <h4>LG</h4>
                                </div><!-- /.info -->
                            </figcaption>

                            <img src="assets/images/blank.gif" data-echo="assets/images/brands/1.png" class="img-responsive" alt="">

                        </figure>
                    </a>
                </div><!-- /.item -->


                <div class="item">

                    <a href="#">

                        <figure>
                            <figcaption class="text-overlay">
                                <div class="info">
                                    <h4>Micromax</h4>
                                </div><!-- /.info -->
                            </figcaption>

                            <img src="assets/images/blank.gif" data-echo="assets/images/brands/6.png" class="img-responsive" alt="">

                        </figure>
                    </a>
                </div><!-- /.item -->


                <div class="item">

                    <a href="#">

                        <figure>
                            <figcaption class="text-overlay">
                                <div class="info">
                                    <h4>Microsoft</h4>
                                </div><!-- /.info -->
                            </figcaption>

                            <img src="assets/images/blank.gif" data-echo="assets/images/brands/4.png" class="img-responsive" alt="">

                        </figure>
                    </a>
                </div><!-- /.item -->


            </div><!-- /.owl-carousel -->

        </div>
    </section>
@stop
