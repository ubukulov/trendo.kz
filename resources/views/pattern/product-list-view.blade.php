<div role="tabpanel" class="tab-pane" id="list-view" aria-expanded="true">
    <ul class="products columns-3">
        @foreach($products as $product)
        <li class="product list-view">
            <div class="media">
                <div class="media-left">
                    <a href="{!! $product->url() !!}">
                        <img class="wp-post-image" data-echo="/assets/images/products/1.jpg" src="/assets/images/blank.gif" alt="">
                    </a>
                </div>
                <div class="media-body media-middle">
                    <div class="row">
                        <div class="col-xs-12">
                            <a href="{!! $product->url() !!}"><h3>{!! $product->title !!}</h3>
                                <div class="product-rating">
                                    <div title="Rated 4 out of 5" class="star-rating"><span style="width:80%"><strong class="rating">4</strong> out of 5</span></div> (3)
                                </div>
                                <div class="product-short-description">
                                    <ul style="padding-left: 18px;">
                                        <li>4.5 inch HD Screen</li>
                                        <li>Android 4.4 KitKat OS</li>
                                        <li>1.4 GHz Quad Core&trade; Processor</li>
                                        <li>20 MP front Camera</li>
                                    </ul>
                                </div>
                            </a>
                        </div>
                        <div class="col-xs-12">

                            <div class="availability in-stock"><span>@lang('messages.In stock')</span></div>

                            <span class="price"><span class="electro-price"><span class="amount">{!! format_price($product->base_price) !!} &#8376;</span></span></span>
                            <a class="button product_type_simple add_to_cart_button ajax_add_to_cart" data-product_sku="{{ $product->article }}" data-product_id="{{ $product->id }}" data-quantity="1" href="{{ route('cart.add', ['product_id' => $product->id]) }}" rel="nofollow">@lang('messages.Add to cart')</a>
                            <div class="hover-area">
                                <div class="action-buttons">
                                    <div class="yith-wcwl-add-to-wishlist add-to-wishlist-{{ $product->id }}">
                                        <a class="add_to_wishlist" data-product-type="simple" data-product-id="{{ $product->id }}" rel="nofollow" href="#">@lang('messages.Wishlist')</a>

                                        <div style="display:none;" class="yith-wcwl-wishlistaddedbrowse hide">
                                            <span class="feedback">Product added!</span>
                                            <a rel="nofollow" href="#">Wishlist</a>
                                        </div>

                                        <div style="display:none" class="yith-wcwl-wishlistexistsbrowse hide">
                                            <span class="feedback">The product is already in the wishlist!</span>
                                            <a rel="nofollow" href="#">Wishlist</a>
                                        </div>

                                        <div style="clear:both"></div>
                                        <div class="yith-wcwl-wishlistaddresponse"></div>

                                    </div>
                                    <div class="clear"></div>
                                    <a data-product_id="{{ $product->id }}" class="add-to-compare-link" href="#">@lang('messages.Compare')</a>
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
