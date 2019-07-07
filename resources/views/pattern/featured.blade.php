<li class="product">
    <div class="product-outer">
        <div class="product-inner">
            <a href="{{ $popular->url() }}">
                <h3>{!! $popular->title !!}</h3>
                <div class="product-thumbnail">
                    <img src="{{ $popular->getImage() }}" data-echo="{{ $popular->getImage() }}" class="img-responsive" alt="{{ $popular->title }}">
                </div>
            </a>

            <div class="price-add-to-cart">
                <span class="price">
                    <span class="electro-price">
                        <ins><span class="amount"> {!! format_price($popular->getPrice()) !!} &#8376;</span></ins>
                        <span class="amount"> </span>
                    </span>
                </span>
                <a rel="nofollow" href="{{ route('cart.add', ['product_id' => $popular->id]) }}" class="button add_to_cart_button">@lang('messages.Add to cart')</a>
            </div><!-- /.price-add-to-cart -->

            <div class="hover-area">
                <div class="action-buttons">
                    <a href="#" rel="nofollow" class="add_to_wishlist"> @lang('messages.Wishlist')</a>
                    <a href="#" class="add-to-compare-link"> @lang('messages.Compare')</a>
                </div>
            </div>
        </div><!-- /.product-inner -->
    </div><!-- /.product-outer -->
</li><!-- /.products -->
