<footer id="colophon" class="site-footer">
    <div class="footer-widgets">
        <div class="container">
            <div class="row">
                <div class="col-lg-4 col-md-4 col-xs-12">
                    <aside class="widget clearfix">
                        <div class="body">
                            <h4 class="widget-title">@lang('messages.Featured Products')</h4>
                            <ul class="product_list_widget">
                                @foreach($on_sales as $k=>$sale)
                                @if($k < 3)
                                <li>
                                    <a href="{{ $sale->url() }}" title="{{ $sale->title }}">
                                        <img class="wp-post-image" data-echo="{{ $sale->getImage() }}" src="{{ $sale->getImage() }}" alt="">
                                        <span class="product-title">{{ $sale->title }}</span>
                                    </a>
                                    <span class="electro-price"><span class="amount">{!! format_price($sale->getPrice()) !!} &#8376;</span></span>
                                </li>
                                @endif
                                @endforeach
                            </ul>
                        </div>
                    </aside>
                </div>
                <div class="col-lg-4 col-md-4 col-xs-12">
                    <aside class="widget clearfix">
                        <div class="body"><h4 class="widget-title">@lang('messages.Onsale Products')</h4>
                            <ul class="product_list_widget">
                                @foreach($recommended_products as $k=>$r)
                                @if($k < 3)
                                    <li>
                                        <a href="{{ $r->url() }}" title="{{ $r->title }}">
                                            <img class="wp-post-image" data-echo="{{ $r->getImage() }}" src="{{ $r->getImage() }}" alt="">
                                            <span class="product-title">{{ $r->title }}</span>
                                        </a>
                                        <span class="electro-price"><span class="amount">{!! format_price($r->getPrice()) !!} &#8376;</span></span>
                                    </li>
                                @endif
                                @endforeach
                            </ul>
                        </div>
                    </aside>
                </div>
                <div class="col-lg-4 col-md-4 col-xs-12">
                    <aside class="widget clearfix">
                        <div class="body">
                            <h4 class="widget-title">@lang('messages.Top Rated Products')</h4>
                            <ul class="product_list_widget">
                                @foreach($most_populars as $k=>$m)
                                @if($k < 3)
                                    <li>
                                        <a href="{{ $m->url() }}" title="{{ $m->title }}">
                                            <img class="wp-post-image" data-echo="{{ $m->getImage() }}" src="{{ $m->getImage() }}" alt="">
                                            <span class="product-title">{{ $m->title }}</span>
                                        </a>
                                        <span class="electro-price"><span class="amount">{!! format_price($m->getPrice()) !!} &#8376;</span></span>
                                    </li>
                                @endif
                                @endforeach
                            </ul>
                        </div>
                    </aside>
                </div>
            </div>
        </div>
    </div>

    <div class="footer-newsletter">
        <div class="container">
            <div class="row">
                <div class="col-xs-12 col-sm-7">
                    <h5 class="newsletter-title">@lang('messages.Sign up to Newsletter')</h5>
                    <span class="newsletter-marketing-text">...и получайте <strong> самые выгодные предложениях</strong></span>
                </div>
                <div class="col-xs-12 col-sm-5">
                    <form>
                        <div class="input-group">
                            <input type="text" class="form-control" placeholder="@lang('messages.Enter your email address')">
                            <span class="input-group-btn">
            								<button class="btn btn-secondary" type="button">@lang('messages.Sign Up')</button>
            							</span>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="footer-bottom-widgets">
        <div class="container">
            <div class="row">
                <div class="col-md-3">
                    <div class="footer-logo">
                        <img src="{{ asset('assets/images/logo.png') }}" alt="">
                    </div><!-- /.footer-contact -->
                </div>
                <div class="col-md-3">
                    <div class="footer-call-us">
                        <div class="media">
                            <span class="media-left call-us-icon media-middle"><i class="ec ec-support"></i></span>
                            <div class="media-body">
                                <span class="call-us-text">Есть вопросы? Мы на связи 24/7!</span>
                                <span class="call-us-number"><a href="tel:+77086144660">+7 (708)-614-4660</a></span>
                            </div>
                        </div>
                    </div><!-- /.footer-call-us -->
                </div>
                <div class="col-md-3">
                    <div class="footer-address">
                        <strong class="footer-address-title">Адрес</strong>
                        <address>г. Алматы, ул. Гоголя 13, офис 1</address>
                    </div><!-- /.footer-address -->
                </div>

                <div class="col-md-3">
                    <div class="footer-social-icons">
                        <ul class="social-icons list-unstyled">
                            <li><a class="fab fa-telegram" href="https://t.me/trendokz"><i class="fab fa-telegram"></i></a></li>
                            {{--                            <li><a class="fa fa-telegram" href="http://tele.net/user/shaikrilwan/portfolio"></a></li>--}}
                            {{--                            <li><a class="fa fa-facebook" href="http://themeforest.net/user/shaikrilwan/portfolio"></a></li>--}}
                            {{--                            <li><a class="fa fa-twitter" href="http://themeforest.net/user/shaikrilwan/portfolio"></a></li>--}}
                            {{--                            <li><a class="fa fa-pinterest" href="http://themeforest.net/user/shaikrilwan/portfolio"></a></li>--}}
                            {{--                            <li><a class="fa fa-linkedin" href="http://themeforest.net/user/shaikrilwan/portfolio"></a></li>--}}
                            {{--                            <li><a class="fa fa-google-plus" href="http://themeforest.net/user/shaikrilwan/portfolio"></a></li>--}}
                            {{--                            <li><a class="fa fa-tumblr" href="http://themeforest.net/user/shaikrilwan/portfolio"></a></li>--}}
                            <li><a class="fa fa-instagram" href="https://instagram.com/trendokz"></a></li>
                            {{--                            <li><a class="fa fa-youtube" href="http://themeforest.net/user/shaikrilwan/portfolio"></a></li>--}}
                            {{--                            <li><a class="fa fa-rss" href="#"></a></li>--}}
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="copyright-bar">
        <div class="container">
            <div class="pull-left flip copyright">&copy; <a href="http://demo2.transvelo.in/html/electro/">Trendo.kz</a> - Все правы защищены</div>
            <div class="pull-right flip payment">
                {{--<div class="footer-payment-logo">
                    <ul class="cash-card card-inline">
                        <li class="card-item"><img src="/assets/images/footer/payment-icon/1.png" alt="" width="52"></li>
                        <li class="card-item"><img src="/assets/images/footer/payment-icon/2.png" alt="" width="52"></li>
                        <li class="card-item"><img src="/assets/images/footer/payment-icon/3.png" alt="" width="52"></li>
                        <li class="card-item"><img src="/assets/images/footer/payment-icon/4.png" alt="" width="52"></li>
                        <li class="card-item"><img src="/assets/images/footer/payment-icon/5.png" alt="" width="52"></li>
                    </ul>
                </div><!-- /.payment-methods -->--}}
            </div>
        </div><!-- /.container -->
    </div><!-- /.copyright-bar -->
</footer><!-- #colophon -->
