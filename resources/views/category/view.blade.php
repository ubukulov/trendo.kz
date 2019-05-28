@extends('layouts.category')
@section('content')

    <div id="" class="content-area">
        <main id="main" class="site-main">

            <section>
                <header>
                    <h2 class="h1">{!! $category->title !!}</h2>
                </header>

                <div class="woocommerce columns-4">
                    <ul class="product-loop-categories">

                        <li class="product-category product">
                            <a href="shop.html">
                                <img src="assets/images/product-category/5.jpg" class="img-responsive" alt="">
                                <h3>Mac Computers<mark class="count">(2)</mark></h3>
                            </a>

                        </li><!-- /.item -->

                        <li class="product-category product">
                            <a href="shop.html">
                                <img src="assets/images/product-category/1.jpg" class="img-responsive" alt="">
                                <h3>Accessories<mark class="count">(2)</mark></h3>
                            </a>

                        </li><!-- /.item -->

                        <li class="product-category product">
                            <a href="shop.html">
                                <img src="assets/images/product-category/2.jpg" class="img-responsive" alt="">
                                <h3>All in One<mark class="count">(2)</mark></h3>
                            </a>

                        </li><!-- /.item -->

                        <li class="product-category product">
                            <a href="shop.html">
                                <img src="assets/images/product-category/3.jpg" class="img-responsive" alt="">
                                <h3>Gaming<mark class="count">(2)</mark></h3>
                            </a>

                        </li><!-- /.item -->

                        <li class="product-category product">
                            <a href="shop.html">
                                <img src="assets/images/product-category/4.jpg" class="img-responsive" alt="">
                                <h3>Servers<mark class="count">(2)</mark></h3>
                            </a>

                        </li><!-- /.item -->

                        <li class="product-category product">
                            <a href="shop.html">
                                <img src="assets/images/product-category/4.jpg" class="img-responsive" alt="">
                                <h3>Laptops<mark class="count">(2)</mark></h3>
                            </a>

                        </li><!-- /.item -->

                        <li class="product-category product">
                            <a href="shop.html">
                                <img src="assets/images/product-category/2.jpg" class="img-responsive" alt="">
                                <h3>Ultrabooks<mark class="count">(2)</mark></h3>
                            </a>

                        </li><!-- /.item -->

                        <li class="product-category product">
                            <a href="shop.html">
                                <img src="assets/images/product-category/6.jpg" class="img-responsive" alt="">
                                <h3>Peripherals<mark class="count">(2)</mark></h3>
                            </a>

                        </li><!-- /.item -->
                    </ul>
                </div>
            </section>

            <section class="section-products-carousel" >

                <header>

                    <h2 class="h1">People buying in this category</h2>

                    <div class="owl-nav">
                        <a href="#products-carousel-prev" data-target="#product-category-carousel" class="slider-prev"><i class="fa fa-angle-left"></i></a>
                        <a href="#products-carousel-next" data-target="#product-category-carousel" class="slider-next"><i class="fa fa-angle-right"></i></a>
                    </div>

                </header>

                <div id="product-category-carousel">
                    <div class="woocommerce columns-6">

                        <div class="products owl-carousel products-carousel columns-6">

                            <div class="product">
                                <div class="product-outer">
                                    <div class="product-inner">
                                        <span class="loop-product-categories"><a href="product-category.html" rel="tag">Smartphones</a></span>
                                        <a href="single-product.html">
                                            <h3>Tablet Thin EliteBook  Revolve 810 G6</h3>
                                            <div class="product-thumbnail">
                                                <img src="assets/images/blank.gif" data-echo="assets/images/product-category/2.jpg" class="img-responsive" alt="">
                                            </div>
                                        </a>

                                        <div class="price-add-to-cart">
                                                            <span class="price">
                                                                <span class="electro-price">
                                                                    <ins><span class="amount"> $1,999.00</span></ins>
                                                                    <del><span class="amount">$2,299.00</span></del>
                                                                    <span class="amount"> </span>
                                                                </span>
                                                            </span>
                                            <a rel="nofollow" href="single-product.html" class="button add_to_cart_button">Add to cart</a>
                                        </div><!-- /.price-add-to-cart -->

                                        <div class="hover-area">
                                            <div class="action-buttons">
                                                <a href="#" rel="nofollow" class="add_to_wishlist"> Wishlist</a>
                                                <a href="compare.html" class="add-to-compare-link"> Compare</a>
                                            </div>
                                        </div>

                                    </div><!-- /.product-inner -->
                                </div><!-- /.product-outer -->
                            </div><!-- /.products -->

                            <div class="product">
                                <div class="product-outer">
                                    <div class="product-inner">
                                        <span class="loop-product-categories"><a href="product-category.html" rel="tag">Smartphones</a></span>
                                        <a href="single-product.html">
                                            <h3>Notebook Purple G952VX-T7008T</h3>
                                            <div class="product-thumbnail">
                                                <img src="assets/images/blank.gif" data-echo="assets/images/product-category/3.jpg" class="img-responsive" alt="">
                                            </div>
                                        </a>

                                        <div class="price-add-to-cart">
                                                            <span class="price">
                                                                <span class="electro-price">
                                                                    <ins><span class="amount"> </span></ins>
                                                                    <span class="amount"> $1,999.00</span>
                                                                </span>
                                                            </span>
                                            <a rel="nofollow" href="single-product.html" class="button add_to_cart_button">Add to cart</a>
                                        </div><!-- /.price-add-to-cart -->

                                        <div class="hover-area">
                                            <div class="action-buttons">
                                                <a href="#" rel="nofollow" class="add_to_wishlist"> Wishlist</a>
                                                <a href="compare.html" class="add-to-compare-link"> Compare</a>
                                            </div>
                                        </div>

                                    </div><!-- /.product-inner -->
                                </div><!-- /.product-outer -->
                            </div><!-- /.products -->

                            <div class="product">
                                <div class="product-outer">
                                    <div class="product-inner">
                                        <span class="loop-product-categories"><a href="product-category.html" rel="tag">Smartphones</a></span>
                                        <a href="single-product.html">
                                            <h3>Laptop Yoga 21 80JH0035GE  W8.1 (Copy)</h3>
                                            <div class="product-thumbnail">
                                                <img src="assets/images/blank.gif" data-echo="assets/images/product-category/5.jpg" class="img-responsive" alt="">
                                            </div>
                                        </a>

                                        <div class="price-add-to-cart">
                                                            <span class="price">
                                                                <span class="electro-price">
                                                                    <ins><span class="amount"> </span></ins>
                                                                    <span class="amount"> $1,999.00</span>
                                                                </span>
                                                            </span>
                                            <a rel="nofollow" href="single-product.html" class="button add_to_cart_button">Add to cart</a>
                                        </div><!-- /.price-add-to-cart -->

                                        <div class="hover-area">
                                            <div class="action-buttons">
                                                <a href="#" rel="nofollow" class="add_to_wishlist"> Wishlist</a>
                                                <a href="compare.html" class="add-to-compare-link"> Compare</a>
                                            </div>
                                        </div>

                                    </div><!-- /.product-inner -->
                                </div><!-- /.product-outer -->
                            </div><!-- /.products -->

                            <div class="product">
                                <div class="product-outer">
                                    <div class="product-inner">
                                        <span class="loop-product-categories"><a href="product-category.html" rel="tag">Smartphones</a></span>
                                        <a href="single-product.html">
                                            <h3>Smartphone 6S 128GB LTE</h3>
                                            <div class="product-thumbnail">
                                                <img src="assets/images/blank.gif" data-echo="assets/images/product-category/6.jpg" class="img-responsive" alt="">
                                            </div>
                                        </a>

                                        <div class="price-add-to-cart">
                                                            <span class="price">
                                                                <span class="electro-price">
                                                                    <ins><span class="amount"> </span></ins>
                                                                    <span class="amount"> $200.00</span>
                                                                </span>
                                                            </span>
                                            <a rel="nofollow" href="single-product.html" class="button add_to_cart_button">Add to cart</a>
                                        </div><!-- /.price-add-to-cart -->

                                        <div class="hover-area">
                                            <div class="action-buttons">
                                                <a href="#" rel="nofollow" class="add_to_wishlist"> Wishlist</a>
                                                <a href="compare.html" class="add-to-compare-link"> Compare</a>
                                            </div>
                                        </div>

                                    </div><!-- /.product-inner -->
                                </div><!-- /.product-outer -->
                            </div><!-- /.products -->

                            <div class="product">
                                <div class="product-outer">
                                    <div class="product-inner">
                                        <span class="loop-product-categories"><a href="product-category.html" rel="tag">Smartphones</a></span>
                                        <a href="single-product.html">
                                            <h3>Notebook Black Spire V Nitro  VN7-591G</h3>
                                            <div class="product-thumbnail">
                                                <img src="assets/images/blank.gif" data-echo="assets/images/product-category/1.jpg" class="img-responsive" alt="">
                                            </div>
                                        </a>

                                        <div class="price-add-to-cart">
                                                            <span class="price">
                                                                <span class="electro-price">
                                                                    <ins><span class="amount"> </span></ins>
                                                                    <span class="amount"> $1,999.00</span>
                                                                </span>
                                                            </span>
                                            <a rel="nofollow" href="single-product.html" class="button add_to_cart_button">Add to cart</a>
                                        </div><!-- /.price-add-to-cart -->

                                        <div class="hover-area">
                                            <div class="action-buttons">
                                                <a href="#" rel="nofollow" class="add_to_wishlist"> Wishlist</a>
                                                <a href="compare.html" class="add-to-compare-link"> Compare</a>
                                            </div>
                                        </div>

                                    </div><!-- /.product-inner -->
                                </div><!-- /.product-outer -->
                            </div><!-- /.products -->

                            <div class="product">
                                <div class="product-outer">
                                    <div class="product-inner">
                                        <span class="loop-product-categories"><a href="product-category.html" rel="tag">Smartphones</a></span>
                                        <a href="single-product.html">
                                            <h3>Tablet Thin EliteBook  Revolve 810 G6</h3>
                                            <div class="product-thumbnail">
                                                <img src="assets/images/blank.gif" data-echo="assets/images/product-category/2.jpg" class="img-responsive" alt="">
                                            </div>
                                        </a>

                                        <div class="price-add-to-cart">
                                                            <span class="price">
                                                                <span class="electro-price">
                                                                    <ins><span class="amount"> </span></ins>
                                                                    <span class="amount"> $1,999.00</span>
                                                                </span>
                                                            </span>
                                            <a rel="nofollow" href="single-product.html" class="button add_to_cart_button">Add to cart</a>
                                        </div><!-- /.price-add-to-cart -->

                                        <div class="hover-area">
                                            <div class="action-buttons">
                                                <a href="#" rel="nofollow" class="add_to_wishlist"> Wishlist</a>
                                                <a href="compare.html" class="add-to-compare-link"> Compare</a>
                                            </div>
                                        </div>

                                    </div><!-- /.product-inner -->
                                </div><!-- /.product-outer -->
                            </div><!-- /.products -->

                            <div class="product">
                                <div class="product-outer">
                                    <div class="product-inner">
                                        <span class="loop-product-categories"><a href="product-category.html" rel="tag">Smartphones</a></span>
                                        <a href="single-product.html">
                                            <h3>Notebook Widescreen Z51-70  40K6013UPB</h3>
                                            <div class="product-thumbnail">
                                                <img src="assets/images/blank.gif" data-echo="assets/images/product-category/3.jpg" class="img-responsive" alt="">
                                            </div>
                                        </a>

                                        <div class="price-add-to-cart">
                                                            <span class="price">
                                                                <span class="electro-price">
                                                                    <ins><span class="amount"> $1,999.00</span></ins>
                                                                    <del><span class="amount">$2,299.00</span></del>
                                                                    <span class="amount"> </span>
                                                                </span>
                                                            </span>
                                            <a rel="nofollow" href="single-product.html" class="button add_to_cart_button">Add to cart</a>
                                        </div><!-- /.price-add-to-cart -->

                                        <div class="hover-area">
                                            <div class="action-buttons">
                                                <a href="#" rel="nofollow" class="add_to_wishlist"> Wishlist</a>
                                                <a href="compare.html" class="add-to-compare-link"> Compare</a>
                                            </div>
                                        </div>

                                    </div><!-- /.product-inner -->
                                </div><!-- /.product-outer -->
                            </div><!-- /.products -->

                            <div class="product">
                                <div class="product-outer">
                                    <div class="product-inner">
                                        <span class="loop-product-categories"><a href="product-category.html" rel="tag">Smartphones</a></span>
                                        <a href="single-product.html">
                                            <h3>Notebook Purple G952VX-T7008T</h3>
                                            <div class="product-thumbnail">
                                                <img src="assets/images/blank.gif" data-echo="assets/images/product-category/4.jpg" class="img-responsive" alt="">
                                            </div>
                                        </a>

                                        <div class="price-add-to-cart">
                                                            <span class="price">
                                                                <span class="electro-price">
                                                                    <ins><span class="amount"> </span></ins>
                                                                    <span class="amount"> $1,999.00</span>
                                                                </span>
                                                            </span>
                                            <a rel="nofollow" href="single-product.html" class="button add_to_cart_button">Add to cart</a>
                                        </div><!-- /.price-add-to-cart -->

                                        <div class="hover-area">
                                            <div class="action-buttons">

                                                <a href="#" rel="nofollow" class="add_to_wishlist"> Wishlist</a>

                                                <a href="compare.html" class="add-to-compare-link"> Compare</a>
                                            </div>
                                        </div>
                                    </div><!-- /.product-inner -->
                                </div><!-- /.product-outer -->
                            </div><!-- /.products -->
                        </div>

                    </div>

                </div>
            </section><!-- /.section-products-carousel -->
        </main><!-- /.site-main -->
    </div><!-- /.content-area -->
@stop
