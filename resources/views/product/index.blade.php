@extends('layouts.product')
@section('content')
    <div id="content" class="site-content" tabindex="-1">
        <div class="container">

            {!! Breadcrumbs::render('product.index', $product) !!}

            <div id="primary" class="content-area">
                <main id="main" class="site-main">

                    <div class="product">

                        <div class="single-product-wrapper">
                            <div class="product-images-wrapper">
                                <span class="onsale">Sale!</span>
                                <div class="images electro-gallery">
                                    <div class="thumbnails-single owl-carousel">
                                        <a id="im_a" href="{{ url($product->getImage()) }}" class="zoom" title="" data-rel="prettyPhoto[product-gallery]">
                                            <img id="im" src="{{ url($product->getImage()) }}" data-echo="{{ url($product->getImage()) }}" class="wp-post-image" alt="">
                                        </a>
                                    </div><!-- .thumbnails-single -->

                                    <div class="thumbnails-all columns-5 owl-carousel">
                                        @php $images = $product->getImage(true); @endphp
                                        @if(is_array($images))
                                            @foreach($images as $image)
                                            <a href="{{ url($image) }}" class="first" data-image="{{ $image }}">
                                                <img src="{{ url($image) }}" data-echo="{{ url($image) }}" class="wp-post-image" alt="">
                                            </a>
                                            @endforeach
                                        @endif
                                    </div><!-- .thumbnails-all -->
                                </div><!-- .electro-gallery -->
                            </div><!-- /.product-images-wrapper -->
                            <style>
                        	.active_a {
                        	    border-bottom: 2px solid #fed700 !important;
                        	}
                        	.no_active {
                        	    border-bottom: 1px solid #eaeaea !important;
                        	}
                            </style>
                            <script>
                        	//function setImage(image){
                        	//    var im_a = document.getElementById('im_a'); im_a.setAttribute('href', image);
                        	//    var im = document.getElementById('im'); im.setAttribute('src', image); im.setAttribute('data-echo', image);
                        	//    var thumbs = document.querySelectorAll('.first');
                        	//    Array.prototype.forEach.call(thumbs, function(elements, index) {
                        		//
                        	//    });
                        	//}
                            </script>

                            <div class="summary entry-summary">

                                <h1 itemprop="name" class="product_title entry-title">{{ $product->title }}</h1>

                                <div class="woocommerce-product-rating">
                                    <div class="star-rating" title="Rated 4.33 out of 5">
                                                <span style="width:86.6%">
                                                    <strong itemprop="ratingValue" class="rating">4.33</strong>
                                                    out of <span itemprop="bestRating">5</span>				based on
                                                    <span itemprop="ratingCount" class="rating">3</span>
                                                    customer ratings
                                                </span>
                                    </div>

{{--                                    <a href="#reviews" class="woocommerce-review-link">(<span itemprop="reviewCount" class="count">3</span> customer reviews)</a>--}}
                                </div><!-- .woocommerce-product-rating -->
				<!--
                                <div class="brand">
                                    <a href="product-category.html">
                                        <img src="/assets/images/single-product/brand.png" alt="Gionee" />
                                    </a>
                                </div><!- --><!-- .brand -->

                                <div class="availability in-stock">
                                    <span>@lang('messages.In stock')</span>
                                </div><!-- .availability -->

                                <hr class="single-product-title-divider" />

                                {{--<div class="action-buttons">

                                    <a href="#" class="add_to_wishlist" >@lang('messages.Wishlist')</a>

                                    <a href="#" class="add-to-compare-link" data-product_id="2452">@lang('messages.Compare')</a>
                                </div><!-- .action-buttons -->--}}

                                <div itemprop="description">
                                    <ul>
                                        @foreach($product->getFilters() as $filter)
                                        <li>{{ $filter->f_title. " - ".$filter->fv_title }}</li>
                                        @endforeach
                                    </ul>

                                    <p>
                                        Наши менеджеры посчитают для вас интересную цену на ваш заказ.
                                        *мы работаем только с оптовыми клиентами
                                    </p>
                                    <p><strong>@lang('messages.SKU')</strong>: {{ $product->article }}</p>
                                </div><!-- .description -->

                                <div itemprop="offers" itemscope itemtype="http://schema.org/Offer">

                                    <p class="price">
                                        <span class="electro-price">
                                            <ins><span class="amount">{!! format_price($product->price) !!} &#8376;</span></ins>
                                            {{--<del><span class="amount">&#36;2,299.00</span></del>--}}
                                        </span>
                                    </p>

                                    <meta itemprop="price" content="1215" />
                                    <meta itemprop="priceCurrency" content="USD" />
                                    <link itemprop="availability" href="http://schema.org/InStock" />

                                </div><!-- /itemprop -->

                                <form class="variations_form cart" method="post" action="{{ route('cart.add2') }}">
                                    {{ csrf_field() }}
                                    <table class="variations" style="display: none;">
                                        <tbody>
                                        <tr>
                                            <td class="label"><label>Color</label></td>
                                            <td class="value">
                                                <select class="" >
                                                    <option value="">Choose an option</option>
                                                    <option value="black-with-red" >Black with Red</option>
                                                    <option value="white-with-gold"  selected='selected'>White with Gold</option>
                                                </select>
                                                <a class="reset_variations" href="#">Clear</a>
                                            </td>
                                        </tr>
                                        </tbody>
                                    </table>


                                    <div class="single_variation_wrap">
                                        <div class="woocommerce-variation single_variation"></div>
                                        <div class="woocommerce-variation-add-to-cart variations_button">
                                            {{--<div class="quantity">
                                                <label>@lang('messages.Quantity'):</label>
                                                <input type="number" name="quantity" value="1" title="Qty" class="input-text qty text"/>
                                            </div>--}}
                                            <div class="row" style="margin-top: 10px;">
                                                <div class="col-md-6">
                                                    <a href="https://api.whatsapp.com/send?phone=77086144660&text=Здравствуйте!%20Я%20хотел%20бы%20узнать%20по%20подробнее%20о товаре%20!.%20Спасибо!%20Артикуль товара:%20<?php echo $product->article; ?>%20Товар%20по%20этому%20адресу:%20<?php echo $product->url() ?>" target="_blank">
                                                        <img src="{{ asset('assets/images/whatsapp_btn.png') }}" alt="">
                                                    </a>
                                                </div>
                                                <div class="col-md-6">
{{--                                                    <button type="submit" style="padding: 14px;" class="single_add_to_cart_button button">@lang('messages.Add to cart')</button>--}}
                                                        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modal">Перезвоните мне</button>
                                                </div>
                                            </div>
                                            <input type="hidden" name="product_id" value="{{ $product->id }}" />
                                        </div>
                                    </div>
                                </form>

                            </div><!-- .summary -->
                        </div><!-- /.single-product-wrapper -->


                        <div class="woocommerce-tabs wc-tabs-wrapper">
                            <ul class="nav nav-tabs electro-nav-tabs tabs wc-tabs" role="tablist">
                                {{--<li class="nav-item description_tab">
                                    <a href="#tab-description" class="active" data-toggle="tab">@lang('messages.Description')</a>
                                </li>--}}

                                <li class="nav-item specification_tab">
                                    <a href="#tab-specification" class="active" data-toggle="tab">@lang('messages.Specification')</a>
                                </li>

                                {{--<li class="nav-item reviews_tab">
                                    <a href="#tab-reviews" data-toggle="tab">@lang('messages.Reviews')</a>
                                </li>--}}
                            </ul>

                            <div class="tab-content">
                                {{--<div class="tab-pane active in panel entry-content wc-tab" id="tab-description">
                                    <div class="electro-description">
                                        {{ $product->full_description }}
                                    </div><!-- /.electro-description -->

                                    --}}{{--<div class="product_meta">
                                        <span class="sku_wrapper">SKU: <span class="sku" itemprop="sku">{{ $product->article }}</span></span>

                                        <span class="posted_in">Category:
                                                    <a href="product-category.html" rel="tag">Headphones</a>
                                                </span>

                                        <span class="tagged_as">Tags:
                                                    <a href="product-category.html" rel="tag">Fast</a>,
                                                    <a href="product-category.html" rel="tag">Gaming</a>, <a href="product-category.html" rel="tag">Strong</a>
                                                </span>

                                    </div><!-- /.product_meta -->--}}{{--
                                </div>--}}

                                <div class="tab-pane panel entry-content wc-tab" id="tab-specification" style="display: block;">
                                    <h3>Технические характеристики</h3>
                                    <ul>
                                	@foreach($product->getFiltersAll() as $filter)
                                	<li>{{ $filter->f_title." - ".$filter->fv_title }}</li>
                                	@endforeach
                                    </ul>
                                </div><!-- /.panel -->

                                <div class="tab-pane panel entry-content wc-tab" id="tab-reviews">
                                    <div id="reviews" class="electro-advanced-reviews">
                                        <div class="advanced-review row">
                                            <div class="col-xs-12 col-md-6">
                                                <h2 class="based-title">Based on 3 reviews</h2>
                                                <div class="avg-rating">
                                                    <span class="avg-rating-number">4.3</span> overall
                                                </div>

                                                <div class="rating-histogram">
                                                    <div class="rating-bar">
                                                        <div class="star-rating" title="Rated 5 out of 5">
                                                            <span style="width:100%"></span>
                                                        </div>
                                                        <div class="rating-percentage-bar">
                                                                    <span style="width:33%" class="rating-percentage">

                                                                    </span>
                                                        </div>
                                                        <div class="rating-count">1</div>
                                                    </div><!-- .rating-bar -->

                                                    <div class="rating-bar">
                                                        <div class="star-rating" title="Rated 4 out of 5">
                                                            <span style="width:80%"></span>
                                                        </div>
                                                        <div class="rating-percentage-bar">
                                                            <span style="width:67%" class="rating-percentage"></span>
                                                        </div>
                                                        <div class="rating-count">2</div>
                                                    </div><!-- .rating-bar -->

                                                    <div class="rating-bar">
                                                        <div class="star-rating" title="Rated 3 out of 5">
                                                            <span style="width:60%"></span>
                                                        </div>
                                                        <div class="rating-percentage-bar">
                                                            <span style="width:0%" class="rating-percentage"></span>
                                                        </div>
                                                        <div class="rating-count zero">0</div>
                                                    </div><!-- .rating-bar -->

                                                    <div class="rating-bar">
                                                        <div class="star-rating" title="Rated 2 out of 5">
                                                            <span style="width:40%"></span>
                                                        </div>
                                                        <div class="rating-percentage-bar">
                                                            <span style="width:0%" class="rating-percentage"></span>
                                                        </div>
                                                        <div class="rating-count zero">0</div>
                                                    </div><!-- .rating-bar -->

                                                    <div class="rating-bar">
                                                        <div class="star-rating" title="Rated 1 out of 5">
                                                            <span style="width:20%"></span>
                                                        </div>
                                                        <div class="rating-percentage-bar">
                                                            <span style="width:0%" class="rating-percentage"></span>
                                                        </div>
                                                        <div class="rating-count zero">0</div>
                                                    </div><!-- .rating-bar -->
                                                </div>
                                            </div><!-- /.col -->

                                            <div class="col-xs-12 col-md-6">
                                                <div id="review_form_wrapper">
                                                    <div id="review_form">
                                                        <div id="respond" class="comment-respond">
                                                            <h3 id="reply-title" class="comment-reply-title">Add a review
                                                                <small><a rel="nofollow" id="cancel-comment-reply-link" href="#" style="display:none;">Cancel reply</a>
                                                                </small>
                                                            </h3>

                                                            <form action="#" method="post" id="commentform" class="comment-form">
                                                                <p class="comment-form-rating">
                                                                    <label>Your Rating</label>
                                                                </p>

                                                                <p class="stars">
                                                                            <span><a class="star-1" href="#">1</a>
                                                                                <a class="star-2" href="#">2</a>
                                                                                <a class="star-3" href="#">3</a>
                                                                                <a class="star-4" href="#">4</a>
                                                                                <a class="star-5" href="#">5</a>
                                                                            </span>
                                                                </p>

                                                                <p class="comment-form-comment">
                                                                    <label for="comment">Your Review</label>
                                                                    <textarea id="comment" name="comment" cols="45" rows="8" aria-required="true"></textarea>
                                                                </p>

                                                                <p class="form-submit">
                                                                    <input name="submit" type="submit" id="submit" class="submit" value="Add Review" />
                                                                    <input type='hidden' name='comment_post_ID' value='2452' id='comment_post_ID' />
                                                                    <input type='hidden' name='comment_parent' id='comment_parent' value='0' />
                                                                </p>

                                                                <input type="hidden" id="_wp_unfiltered_html_comment_disabled" name="_wp_unfiltered_html_comment_disabled" value="c7106f1f46" />
                                                                <script>(function(){if(window===window.parent){document.getElementById('_wp_unfiltered_html_comment_disabled').name='_wp_unfiltered_html_comment';}})();</script>
                                                            </form><!-- form -->
                                                        </div><!-- #respond -->
                                                    </div>
                                                </div>

                                            </div><!-- /.col -->
                                        </div><!-- /.row -->

                                        <div id="comments">

                                            <ol class="commentlist">
                                                <li itemprop="review" class="comment even thread-even depth-1">

                                                    <div id="comment-390" class="comment_container">

                                                        <img alt='' src="/assets/images/blog/avatar.jpg" class='avatar' height='60' width='60' />
                                                        <div class="comment-text">

                                                            <div class="star-rating" title="Rated 4 out of 5">
                                                                <span style="width:80%"><strong itemprop="ratingValue">4</strong> out of 5</span>
                                                            </div>

                                                            <p class="meta">
                                                                <strong>John Doe</strong> &ndash;
                                                                <time itemprop="datePublished" datetime="2016-03-03T14:13:48+00:00">March 3, 2016</time>:
                                                            </p>

                                                            <div itemprop="description" class="description">
                                                                <p>Fusce vitae nibh mi. Integer posuere, libero et ullamcorper facilisis, enim eros tincidunt orci, eget vestibulum sapien nisi ut leo. Cras finibus vel est ut mollis. Donec luctus condimentum ante et euismod.
                                                                </p>
                                                            </div>

                                                            <p class="meta">
                                                                <strong itemprop="author">John Doe</strong> &ndash; <time itemprop="datePublished" datetime="2016-03-03T14:13:48+00:00">March 3, 2016</time>
                                                            </p>

                                                        </div>
                                                    </div>
                                                </li><!-- #comment-## -->

                                                <li class="comment odd alt thread-odd thread-alt depth-1">

                                                    <div class="comment_container">

                                                        <img alt='' src="/assets/images/blog/avatar.jpg" class='avatar' height='60' width='60' />
                                                        <div class="comment-text">

                                                            <div itemprop="reviewRating" itemscope itemtype="http://schema.org/Rating" class="star-rating" title="Rated 5 out of 5">
                                                                <span style="width:100%"><strong itemprop="ratingValue">5</strong> out of 5</span>
                                                            </div>

                                                            <p class="meta">
                                                                <strong>Anna Kowalsky</strong> &ndash;
                                                                <time itemprop="datePublished" datetime="2016-03-03T14:14:47+00:00">March 3, 2016</time>:
                                                            </p>


                                                            <div itemprop="description" class="description">
                                                                <p>Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas. Suspendisse eget facilisis odio. Duis sodales augue eu tincidunt faucibus. Etiam justo ligula, placerat ac augue id, volutpat porta dui.
                                                                </p>
                                                            </div>

                                                            <p class="meta">
                                                                <strong itemprop="author">Anna Kowalsky</strong> &ndash; <time itemprop="datePublished" datetime="2016-03-03T14:14:47+00:00">March 3, 2016</time>
                                                            </p>

                                                        </div>
                                                    </div>
                                                </li><!-- #comment-## -->

                                                <li class="comment odd alt thread-odd thread-alt depth-1">

                                                    <div class="comment_container">

                                                        <img alt='' src="/assets/images/blog/avatar.jpg" class='avatar' height='60' width='60' />
                                                        <div class="comment-text">

                                                            <div itemprop="reviewRating" class="star-rating" title="Rated 5 out of 5">
                                                                <span style="width:100%"><strong itemprop="ratingValue">5</strong> out of 5</span>
                                                            </div>

                                                            <p class="meta">
                                                                <strong>Anna Kowalsky</strong> &ndash;
                                                                <time itemprop="datePublished" datetime="2016-03-03T14:14:47+00:00">March 3, 2016</time>:
                                                            </p>

                                                            <div itemprop="description" class="description">
                                                                <p>Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas. Suspendisse eget facilisis odio. Duis sodales augue eu tincidunt faucibus. Etiam justo ligula, placerat ac augue id, volutpat porta dui.
                                                                </p>
                                                            </div>

                                                            <p class="meta"><strong itemprop="author">Anna Kowalsky</strong> &ndash; <time itemprop="datePublished" datetime="2016-03-03T14:14:47+00:00">March 3, 2016</time></p>

                                                        </div>
                                                    </div>
                                                </li><!-- #comment-## -->
                                            </ol><!-- /.commentlist -->

                                        </div><!-- /#comments -->

                                        <div class="clear"></div>
                                    </div><!-- /.electro-advanced-reviews -->
                                </div><!-- /.panel -->
                            </div>
                        </div><!-- /.woocommerce-tabs -->

                        <div class="related products">
                            <h2>@lang('messages.Related Products')</h2>

                            <ul class="products columns-5">
                                @foreach($product->getSimilarProducts() as $item)
                                <li class="product">
                                    <div class="product-outer">
                                        <div class="product-inner">
                                            <a href="{{ $item->url() }}">
                                                <h3>{{ $item->title }}</h3>
                                                <div class="product-thumbnail">
                                                    <img data-echo="{{ $item->getImage() }}" src="{{ $item->getImage() }}" alt="{{ $item->title }}">
                                                </div>
                                            </a>

                                            <div class="price-add-to-cart">
                                                        <span class="price">
                                                            <span class="electro-price">
                                                                <ins><span class="amount">{!! format_price($item->getPrice()) !!} &#8376;</span></ins>
                                                            </span>
                                                        </span>
                                                <a rel="nofollow" href="{{ route('cart.add', ['product_id' => $item->id]) }}" class="button add_to_cart_button">Add to cart</a>
                                            </div><!-- /.price-add-to-cart -->

                                            <div class="hover-area">
                                                <div class="action-buttons">
                                                    <a href="#" rel="nofollow" class="add_to_wishlist"> Wishlist</a>
                                                    <a href="#" class="add-to-compare-link">Compare</a>
                                                </div>
                                            </div>
                                        </div><!-- /.product-inner -->
                                    </div><!-- /.product-outer -->
                                </li>
                                @endforeach
                            </ul><!-- /.products -->
                        </div><!-- /.related -->
                    </div>
                </main><!-- /.site-main -->
            </div><!-- /.content-area -->

            <div id="sidebar" class="sidebar" role="complementary">
                <aside id="electro_product_categories_widget-2" class="widget woocommerce widget_product_categories electro_widget_product_categories">
                    <ul class="product-categories category-single">
                        <li class="product_cat">
                            <ul>
                                <li class="cat-item cat-item-172 current-cat-parent current-cat-ancestor">

                                    <a href="product-category.html">{{ $product->category->parent->title }}</a>
                                    <ul class='children'>
                                        @foreach($product->category->parent->parents as $cat)
                                        <li class="cat-item cat-item-178 @if($cat->id == $product->category_id) current-cat @endif">
                                            <a href="{{ $cat->url() }}">{{ $cat->title }}</a>
                                        </li>
                                        @endforeach
                                    </ul>
                                </li>
                            </ul>
                        </li><!-- .product_cat -->
                    </ul><!-- .product-categories -->
                </aside><!-- .widget -->
                <aside id="text-2" class="widget widget_text">
                    <div class="textwidget">
                        <img src="/assets/images/single-product/ad-banner.jpg" alt="Banner">
                    </div><!-- .textwidget -->
                </aside><!-- .widget -->
                <aside id="woocommerce_products-2" class="widget woocommerce widget_products">
                    <h3 class="widget-title">@lang('messages.Latest Products')</h3>
                    <ul class="product_list_widget">
                    @foreach($on_sales as $sale)
                        <li>
                            <a href="{{ $sale->url()  }}" title="{{ $sale->title }}">
                                <img class="wp-post-image" src="{{ $sale->getImage() }}" alt="">
                                <span class="product-title">{{ $sale->title }}</span>
                            </a>
                            <span class="electro-price">
                        	<ins><span class="amount">{!! format_price($sale->getPrice()) !!} &#8376;</span></ins> 
                        	<!--<del><span class="amount">&#36;2,299.00</span></del><!- -->
                    	    </span>
                        </li>
                    @endforeach
                    </ul><!-- .product_list_widget -->
                </aside><!-- .widget -->
            </div><!-- /.sidebar-shop -->

        </div><!-- .col-full -->
    </div><!-- #content -->
    <!-- Modal -->
    <div class="modal fade" id="modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document" style="width: 350px;">
            <div class="modal-content">
                <div class="modal-header" style="text-align: center;">
                    <span class="modal-title" id="exampleModalLabel" style="font-size: 25px;">Заказ обратного звонка</span><br>
                    <span>Мы перезвоним вам в течение 15 минут</span>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('lead.post') }}" method="post">
                        @csrf
                        <div class="form-group">
                            <input type="text" placeholder="*Ваше имя..." name="first_name" required id="first_name" class="form-control">
                        </div>

                        <div class="form-group">
                            <input type="text" placeholder="*Ваш телефон..." name="phone_number" required id="phone_number" class="form-control">
                        </div>

                        <input type="hidden" name="product_id" value="{{ $product->id }}">
                        <div class="form-group">
                            <div class="modal-footer" style="text-align: center;">
                                <button type="submit" class="btn btn-primary" style="border-radius: 2px; width: 100%; background: #ddd;"  name="lead">Перезвоните мне</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@stop    
