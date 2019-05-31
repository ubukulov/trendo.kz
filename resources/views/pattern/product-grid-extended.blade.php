<div role="tabpanel" class="tab-pane" id="grid-extended" aria-expanded="true">

    <ul class="products columns-3">
        @foreach($products as $product)
            <li class="product ">
                <div class="product-outer">
                    <div class="product-inner">
                        <a href="{{ $product->url() }}">
                            <h3>{!! $product->title !!}</h3>
                            <div class="product-thumbnail">
                                <img class="wp-post-image" data-echo="/assets/images/products/1.jpg" src="assets/images/blank.gif" alt="">
                            </div>

                            <div class="product-rating">
                                <div title="Rated 4 out of 5" class="star-rating"><span style="width:80%"><strong class="rating">4</strong> out of 5</span></div> (3)
                            </div>

                            <div class="product-short-description">
                                <ul>
                                    <li><span class="a-list-item">Intel Core i5 processors (13-inch model)</span></li>
                                    <li><span class="a-list-item">Intel Iris Graphics 6100 (13-inch model)</span></li>
                                    <li><span class="a-list-item">Flash storage</span></li>
                                    <li><span class="a-list-item">Up to 10 hours of battery life2 (13-inch model)</span></li>
                                    <li><span class="a-list-item">Force Touch trackpad (13-inch model)</span></li>
                                </ul>
                            </div>

                            <div class="product-sku">SKU: {{ $product->article }}</div>
                        </a>
                        <div class="price-add-to-cart">
                                                        <span class="price">
                                                            <span class="electro-price">
                                                                <ins><span class="amount">{!! format_price($product->base_price) !!} &#8376;</span></ins>
{{--                                                                <del><span class="amount">&#036;2,299.00</span></del>--}}
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

                    </div><!-- /.product-inner -->
                </div><!-- /.product-outer -->
            </li>
        @endforeach
    </ul>
</div>
