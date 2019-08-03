<style>
    .sub-children {
        width: 900px !important;
    }
</style>
<nav class="navbar navbar-primary navbar-full">
    <div class="container">
        <ul class="nav navbar-nav departments-menu animate-dropdown">
            <li class="nav-item dropdown ">

                <a class="nav-link dropdown-toggle" data-toggle="dropdown" href="#" id="departments-menu-toggle" >Все категории</a>
                <ul id="menu-vertical-menu" class="dropdown-menu yamm departments-menu-dropdown">
                    @foreach($cats as $cat)
                        <li class="yamm-tfw menu-item menu-item-has-children animate-dropdown menu-item-2584 dropdown">
                            @if($cat->isRoot())
                                <a title="Computers &amp; Accessories" href="product-category.html" data-toggle="dropdown" class="dropdown-toggle" aria-haspopup="true">{!! $cat->title !!}</a>
                                @if($cat->hasChildren())
                                    @foreach($cat->children as $child)
                                        <ul role="menu" class=" dropdown-menu sub-children">
                                            <li class="menu-item animate-dropdown menu-item-object-static_block">
                                                <div class="yamm-content">
                                                    <div style="display: none;" class="vc_row row wpb_row vc_row-fluid bg-yamm-content bg-yamm-content-bottom bg-yamm-content-right">
                                                        <div class="wpb_column vc_column_container vc_col-sm-12 col-sm-12">
                                                            <div class="vc_column-inner ">
                                                                <div class="wpb_wrapper">
                                                                    <div class="wpb_single_image wpb_content_element vc_align_left">
                                                                        <figure class="wpb_wrapper vc_figure">
                                                                            <div class="vc_single_image-wrapper   vc_box_border_grey"><img width="540" height="460" src="/assets/images/megamenu-2.png" class="vc_single_image-img attachment-full" alt="megamenu-2"/></div>
                                                                        </figure>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="vc_row row wpb_row vc_row-fluid">
                                                        @php
                                                            $cnt = count($cat->children);
                                                            switch ($cnt) {
                                                                case 1: $number = 12; break;
                                                                case 2: $number = 6; break;
                                                                case 3: $number = 4; break;
                                                                case 4: $number = 3; break;
                                                                default: $number = 3; break;
                                                            }
                                                        @endphp
                                                        @foreach($cat->children as $item)
                                                            <div class="wpb_column vc_column_container vc_col-sm-{{ $number }} col-sm-{{ $number }}">
                                                                <div class="vc_column-inner ">
                                                                    <div class="wpb_wrapper">
                                                                        <div class="wpb_text_column wpb_content_element ">
                                                                            <div class="wpb_wrapper">
                                                                                <ul>
                                                                                    <li class="nav-title">{!! $item->title !!}</li>
                                                                                    @if($item->hasChildren())
                                                                                        @foreach($item->children as $grandson)
                                                                                            <li><a href="{{ $grandson->url() }}">{!! $grandson->title !!} ({{ $grandson->products->count() }})</a></li>
                                                                                        @endforeach
                                                                                    @endif
                                                                                </ul>

                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                </div>
                                            </li>
                                        </ul>
                                    @endforeach
                                @endif
                            @endif
                        </li>
                    @endforeach
                </ul>
            </li>
        </ul>

        <form class="navbar-search" method="post" action="{{ route('query') }}">
            @csrf
            <label class="sr-only screen-reader-text" for="search">Search for:</label>
            <div class="input-group">
                <input type="text" id="search" class="form-control search-field" dir="ltr" name="q" placeholder="@lang('messages.Search for products')" />
                <div class="input-group-addon search-categories">
                    <select name='category_id' id='category_id' class='postform resizeselect' >
                        <option value='0' selected='selected'>@lang('messages.All Departments')</option>
                        @foreach($subCategories as $subcat)
                            <option class="level-0" value="{{ $subcat->id }}">{{ $subcat->title }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="input-group-btn">
                    <input type="hidden" id="search-param" name="post_type" value="product" />
                    <button type="submit" class="btn btn-secondary"><i class="ec ec-search"></i></button>
                </div>
            </div>
        </form>

        <ul class="navbar-mini-cart navbar-nav animate-dropdown nav pull-right flip">
            <li class="nav-item dropdown">
                <a href="{{ route('cart.index') }}" class="nav-link" data-toggle="dropdown">
                    <i class="ec ec-shopping-bag"></i>
                    <span class="cart-items-count count">{{ \App\Classes\ShoppingCart::getCountItems() }}</span>
                    <span class="cart-items-total-price total-price"><span class="amount">{!! format_price(\App\Classes\ShoppingCart::getTotalPrice()) !!} &#8376;</span></span>
                </a>
                <ul class="dropdown-menu dropdown-menu-mini-cart">
                    <li>
                        <div class="widget_shopping_cart_content">

                            <ul class="cart_list product_list_widget ">
                                @foreach(\App\Classes\ShoppingCart::getCartItems() as $cartItem)
                                <li class="mini_cart_item">
                                    <a title="Remove this item" class="remove" href="#">×</a>
                                    <a href="{{ $cartItem->product->url() }}">
                                        <img class="attachment-shop_thumbnail size-shop_thumbnail wp-post-image" src="/assets/images/products/mini-cart1.jpg" alt="">{!! $cartItem->product->title !!}
                                    </a>

                                    <span class="quantity">{{ $cartItem->quantity }} × <span class="amount">{!! format_price($cartItem->product->getPrice()) !!} &#8376;</span></span>
                                </li>
                                @endforeach
                            </ul><!-- end product list -->


                            <p class="total"><strong>Subtotal:</strong> <span class="amount">£969.98</span></p>


                            <p class="buttons">
                                <a style="padding: 10px 15px;" class="button wc-forward" href="{{ route('cart.index') }}">@lang('messages.View Cart')</a>
                                <a style="padding: 10px 20px;" class="button checkout wc-forward" href="{{ route('checkout.index') }}">@lang('messages.Checkout')</a>
                            </p>


                        </div>
                    </li>
                </ul>
            </li>
        </ul>

        {{--<ul class="navbar-wishlist nav navbar-nav pull-right flip">
            <li class="nav-item">
                <a href="wishlist.html" class="nav-link"><i class="ec ec-favorites"></i></a>
            </li>
        </ul>
        <ul class="navbar-compare nav navbar-nav pull-right flip">
            <li class="nav-item">
                <a href="compare.html" class="nav-link"><i class="ec ec-compare"></i></a>
            </li>
        </ul>--}}
    </div>
</nav>
