@extends('layouts.app')
@section('content')
    <div id="content" class="site-content" tabindex="-1" style="margin-bottom: 0px !important;">
        <div class="container">
            <div id="primary" class="content-area">
                <main id="main" class="site-main">
                    <article class="page type-page status-publish hentry">
                        @if(\App\Classes\ShoppingCart::hasProducts())
                            <header class="entry-header"><h1 itemprop="name" class="entry-title">Оформление заказа</h1></header><!-- .entry-header -->
                            <form enctype="multipart/form-data" action="{{ route('checkout.store') }}" class="checkout woocommerce-checkout" method="post" name="checkout">
                            @csrf
                            <div id="customer_details" class="col2-set">
                                <div class="col-1">
                                    <div class="woocommerce-billing-fields">

                                        <h3>@lang('messages.Billing Details')</h3>

                                        <p id="billing_first_name_field" class="form-row form-row form-row-first validate-required">
                                            <label class="" for="first_name">@lang('messages.First Name')
                                                <abbr title="required" class="required">*</abbr>
                                            </label>
                                            <input type="text" @if(Auth::check()) value="{{ Auth::user()->profile->first_name }}" @endif placeholder="" id="first_name" name="first_name" class="input-text ">
                                        </p>

                                        <p id="billing_last_name_field" class="form-row form-row form-row-last validate-required">
                                            <label class="" for="last_name">@lang('messages.Last Name')
                                                <abbr title="required" class="required">*</abbr>
                                            </label>
                                            <input type="text" @if(Auth::check()) value="{{ Auth::user()->profile->last_name }}" @endif placeholder="" id="last_name" name="last_name" class="input-text ">
                                        </p>

                                        <div class="clear"></div>

                                        <p id="billing_email_field" class="form-row form-row form-row-first validate-required validate-email">
                                            <label class="" for="email">Email
                                                <abbr title="required" class="required">*</abbr>
                                            </label>
                                            <input type="email" required @if(Auth::check()) value="{{ Auth::user()->email }}" @endif placeholder="" id="email" name="email" class="input-text ">
                                        </p>

                                        <p id="billing_phone_field" class="form-row form-row form-row-last validate-required validate-phone">
                                            <label class="" for="phone">@lang('messages.Phone')
                                                <abbr title="required" class="required">*</abbr>
                                            </label>
                                            <input type="tel" @if(Auth::check()) value="{{ Auth::user()->profile->phone }}" @endif required placeholder="" id="phone" name="phone" class="input-text ">
                                        </p>
                                        <div class="clear"></div>

                                        <p id="billing_address_1_field" class="form-row form-row form-row-wide address-field validate-required">
                                            <label class="" for="address">@lang('messages.Address')
                                                <abbr title="required" class="required">*</abbr>
                                            </label>
                                            <input type="text" @if(Auth::check()) value="{{ Auth::user()->profile->address }}" @endif required placeholder="Ваш адрес" id="address" name="address" class="input-text ">
                                        </p>


                                        <p id="billing_city_field" class="form-row form-row form-row-wide address-field validate-required" data-o_class="form-row form-row form-row-wide address-field validate-required">
                                            <label class="" for="city">@lang('messages.City')
                                                <abbr title="required" class="required">*</abbr>
                                            </label>
                                            <input type="text" value="" placeholder="" id="city" name="city" class="input-text ">
                                        </p>

                                        <div class="clear"></div>
                                    </div>
                                </div>

                                <div class="col-2">
                                    <h3>@lang('messages.Shipping Details')</h3>
                                    <div class="woocommerce-shipping-fields">
                                        <h3 id="ship-to-different-address">
                                            <label class="checkbox" for="different-address">@lang('messages.Ship to a different address?')</label>
                                            <input type="checkbox" value="1" name="different_address" class="input-checkbox" id="different-address">
                                        </h3>

                                        <p id="order_comments_field" class="form-row form-row notes">
                                            <label class="" for="order_notes">@lang('messages.Order Notes')</label>
                                            <textarea cols="5" rows="2" placeholder="@lang('messages.Notes about your order, e.g. special notes for delivery.')" id="order_notes" class="input-text " name="order_notes"></textarea>
                                        </p>
                                    </div>
                                </div>
                            </div>

                            <h3 id="order_review_heading">@lang('messages.Your order')</h3>

                            <div class="woocommerce-checkout-review-order" id="order_review">
                                <table class="shop_table woocommerce-checkout-review-order-table">
                                    <thead>
                                    <tr>
                                        <th class="product-name">@lang('messages.Product')</th>
                                        <th class="product-total">@lang('messages.Total')</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($cartItems as $cartItem)
                                        <tr class="cart_item">
                                            <td class="product-name">
                                                {!! $cartItem->product->title !!}&nbsp;
                                                <strong class="product-quantity">× {{ $cartItem->quantity }}</strong>
                                            </td>
                                            <td class="product-total">
                                                <span class="amount">{!! format_price($cartItem->product->getPrice() * $cartItem->quantity) !!} &#8376;</span>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                    <tfoot>

                                    <tr class="cart-subtotal">
                                        <th>@lang('messages.Subtotal')</th>
                                        <td><span class="amount">{!! format_price(\App\Classes\ShoppingCart::getTotalPrice()) !!} &#8376;</span></td>
                                    </tr>

                                    <tr class="shipping">
                                        <th>@lang('messages.Shipping')</th>
                                        <td data-title="Shipping">@lang('messages.Flat Rate'): <span class="amount">{!! format_price(1000) !!} &#8376;</span> <input type="hidden" class="shipping_method" value="international_delivery" id="shipping_method_0" data-index="0" name="shipping_method[0]"></td>
                                    </tr>

                                    <tr class="order-total">
                                        <th>@lang('messages.Total')</th>
                                        <td><strong><span class="amount">{!! format_price(\App\Classes\ShoppingCart::getTotalPrice() + 1000) !!} &#8376;</span></strong> </td>
                                    </tr>
                                    </tfoot>
                                </table>

                                <h3 id="order_review_heading">@lang('messages.Payment method')</h3>

                                <div class="woocommerce-checkout-payment" id="payment">
                                    <ul class="wc_payment_methods payment_methods methods">
                                        <li class="wc_payment_method payment_method_bacs">
                                            <input type="radio" data-order_button_text="" checked="checked" value="bacs" name="direct_bank_transfer" class="input-radio" id="payment_method_bacs">
                                            <label for="payment_method_bacs">@lang('messages.Direct Bank Transfer')</label>
                                        </li>

                                        <li class="wc_payment_method payment_method_cod">
                                            <input type="radio" data-order_button_text="" value="cod" name="cash_on_delivery" class="input-radio" id="payment_method_cod">

                                            <label for="payment_method_cod">@lang('messages.Cash on Delivery')</label>
                                        </li>
                                    </ul>
                                    <div class="form-row place-order">

                                        <p class="form-row terms wc-terms-and-conditions">
                                            <input type="checkbox" id="terms" name="terms" class="input-checkbox">
                                            <label class="checkbox" for="terms">Я прочитал и принимаю <a target="_blank" href="terms-and-conditions.html">условия и положения</a> <span class="required">*</span></label>
                                            <input type="hidden" value="1" name="terms_field">
                                        </p>

                                        <input type="submit" name="place_order" data-value="Place order" value="@lang('messages.Place order')" class="button alt">
                                    </div>
                                </div>
                            </div>
                        </form>
                        @else
                            @if(Session::has('message'))
                                <div class="alert alert-success alert-dismissable">
                                    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                                    {!!Session::get('message')!!}
                                </div>
                            @endif
                            <div style="margin: 50px auto; width: 500px;">
                                <h3>
                                    <i class="fa fa-shopping-cart" style="font-size: 54px !important; color: #cccccc;"></i>
                                    Ваша корзина пуста
                                </h3>
                            </div>
                        @endif
                    </article>
                </main><!-- #main -->
            </div><!-- #primary -->
        </div><!-- .container -->
    </div><!-- #content -->
@stop
