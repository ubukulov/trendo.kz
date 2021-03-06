<div role="tabpanel" class="tab-pane" id="list-view-small" aria-expanded="true">

    <ul class="products columns-3">
        @foreach($products as $product)
        <li class="product list-view list-view-small">
            <div class="media">
                <div class="media-left">
                    <a href="{{ $product->url() }}">
                        <img class="wp-post-image" data-echo="/assets/images/products/1.jpg" src="/assets/images/blank.gif" alt="">
                    </a>
                </div>
                <div class="media-body media-middle">
                    <div class="row">
                        <div class="col-xs-12">
                            <a href="{{ $product->url() }}"><h3>{!! $product->title !!}</h3>
                                <div class="product-short-description">
                                    <ul style="padding-left: 18px;">
                                        <li>4.5 inch HD Screen</li>
                                        <li>Android 4.4 KitKat OS</li>
                                        <li>1.4 GHz Quad Core&trade; Processor</li>
                                        <li>20 MP front Camera</li>
                                    </ul>
                                </div>
                                <div class="product-rating">
                                    <div title="Rated 4 out of 5" class="star-rating"><span style="width:80%"><strong class="rating">4</strong> out of 5</span></div> (3)
                                </div>
                            </a>
                        </div>
                        <div class="col-xs-12">
                            <div class="price-add-to-cart">
                                <span class="price"><span class="electro-price"><span class="amount">{!! format_price($product->base_price) !!} &#8376;</span></span></span>
                                <a class="button add_to_cart_button" href="{{ route('cart.add', ['product_id' => $product->id]) }}" rel="nofollow">@lang('messages.Add to cart')</a>
                            </div><!-- /.price-add-to-cart -->

                            <div class="hover-area">
                                <div class="action-buttons">
                                    <a href="#" rel="nofollow" class="add_to_wishlist">@lang('messages.Wishlist')</a>
                                    <a href="compare.html" class="add-to-compare-link">@lang('messages.Compare')</a>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </li>
        @endforeach
    </ul>
</div>
