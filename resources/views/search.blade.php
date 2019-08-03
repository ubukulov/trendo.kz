@extends('layouts.category')
@section('content')
    <div id="primary" class="content-area">
        <main id="main" class="site-main">

            <header class="page-header">
                <h1 class="page-title">Результаты поиска по: {{ $q }}</h1>
            </header>

            <div class="tab-content">

                <div role="tabpanel" class="tab-pane active" id="grid" aria-expanded="true">

                    <ul class="products columns-3">
                        @foreach($results as $product)
                            <li class="product ">
                                <div class="product-outer">
                                    <div class="product-inner">
                                        <a href="{{ $product->url() }}">
                                            <h3>{!! $product->title !!}</h3>
                                            <div class="product-thumbnail">
                                                <img height="232" data-echo="{{ $product->getImage() }}" src="{{ $product->getImage() }}" alt="{{ $product->title }}">
                                            </div>
                                        </a>

                                        <div class="price-add-to-cart">
                                                        <span class="price">
                                                            <span class="electro-price">
                                                                <ins><span class="amount">{!! format_price($product->price) !!} &#8376;</span></ins>
                                                                {{--<del><span class="amount">&#8376;2,299.00</span></del>--}}
                                                            </span>
                                                        </span>
                                            <a rel="nofollow" href="{{ route('cart.add', ['product_id' => $product->id]) }}" class="button add_to_cart_button">@lang('messages.Add to cart')</a>
                                        </div><!-- /.price-add-to-cart -->

                                        <div class="hover-area">
                                            <div class="action-buttons">
                                                <a href="#" rel="nofollow" class="add_to_wishlist">@lang('messages.Wishlist')</a>
                                                <a href="#" class="add-to-compare-link">@lang('messages.Compare')</a>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- /.product-inner -->
                                </div><!-- /.product-outer -->
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
            <div class="shop-control-bar-bottom">
                {{ $products->links() }}
            </div>

        </main><!-- #main -->
    </div><!-- #primary -->
@stop
