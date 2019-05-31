@extends('layouts.app')
@section('content')
    <div id="content" class="site-content" tabindex="-1">
        <div class="container">
            <div id="primary" class="content-area">
                <main id="main" class="site-main">
                    <article class="page type-page status-publish hentry">
                        <header class="entry-header">
                            <h1 itemprop="name" style="margin-top: 10px;" class="entry-title">@lang('messages.Cart')</h1>
                        </header><!-- .entry-header -->

                        <form>
                            <table class="shop_table shop_table_responsive cart">
                                <thead>
                                <tr>
                                    <th class="product-remove">&nbsp;</th>
                                    <th class="product-thumbnail">&nbsp;</th>
                                    <th class="product-name">@lang('messages.Product')</th>
                                    <th class="product-price">@lang('messages.Price')</th>
                                    <th class="product-quantity">@lang('messages.Quantity')</th>
                                    <th class="product-subtotal">@lang('messages.Total')</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($cartItems as $cartItem)
                                <tr class="cart_item">
                                    <td class="product-remove">
                                        <a class="remove" href="#">×</a>
                                    </td>
                                    <td class="product-thumbnail">
                                        <a href="{{ $cartItem->product->url() }}"><img width="180" height="180" src="{{ $cartItem->product->getImage() }}" alt=""></a>
                                    </td>

                                    <td data-title="Product" class="product-name">
                                        <a href="{{ $cartItem->product->url() }}">{!! $cartItem->product->title !!}</a>
                                    </td>

                                    <td data-title="Price" class="product-price">
                                        <span class="amount">{!! format_price($cartItem->product->getPrice()) !!} &#8376;</span>
                                    </td>

                                    <td data-title="Quantity" class="product-quantity">
                                        <div class="quantity buttons_added"><input type="button" class="minus" value="-">
                                            <label>@lang('messages.Quantity'):</label>
                                            <input type="number" size="4" class="input-text qty text" title="Qty" value="{{ $cartItem->quantity }}" name="cart[92f54963fc39a9d87c2253186808ea61][qty]" max="29" min="0" step="1">
                                            <input type="button" class="plus" value="+">
                                        </div>
                                    </td>

                                    <td data-title="Total" class="product-subtotal">
                                        <span class="amount">{!! format_price($cartItem->product->getPrice() * $cartItem->quantity) !!} &#8376;</span>
                                    </td>
                                </tr>
                                @endforeach
                                <tr>
                                    <td class="actions" colspan="6">

                                        <div class="coupon">

                                            <label for="coupon_code">Coupon:</label>
                                            <input type="text" style="width: 50% !important;" placeholder="@lang('messages.Coupon code')" value="" id="coupon_code" class="input-text" name="coupon_code">
                                            <input type="submit" value="@lang('messages.Apply Coupon')" name="apply_coupon" class="button">

                                        </div>

                                        <input type="submit" value="@lang('messages.Update Cart')" name="update_cart" class="button">

                                        <div class="wc-proceed-to-checkout">

                                            <a class="checkout-button button alt wc-forward" href="checkout.html">@lang('messages.Proceed to Checkout')</a>
                                        </div>

                                        <input type="hidden" value="1eafc42c5e" name="_wpnonce"><input type="hidden" value="/electro/cart/" name="_wp_http_referer">
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                        </form>
                        <div class="cart-collaterals">

                            <div class="cart_totals ">

                                <h2>@lang('messages.Cart Totals')</h2>

                                <table class="shop_table shop_table_responsive">

                                    <tbody>
                                    <tr class="cart-subtotal">
                                        <th>@lang('messages.Subtotal')</th>
                                        <td data-title="Subtotal"><span class="amount">$3,299.00</span></td>
                                    </tr>


                                    <tr class="shipping">
                                        <th>@lang('messages.Shipping')</th>
                                        <td data-title="Shipping">
                                            Flat Rate: <span class="amount">$300.00</span>
                                        </td>
                                    </tr>

                                    <tr class="order-total">
                                        <th>@lang('messages.Total')</th>
                                        <td data-title="Total"><strong><span class="amount">$3,599.00</span></strong> </td>
                                    </tr>
                                    </tbody>
                                </table>

                                <div class="wc-proceed-to-checkout">

                                    <a class="checkout-button button alt wc-forward" href="checkout.html">Proceed to Checkout</a>
                                </div>
                            </div>
                        </div>
                    </article>
                </main><!-- #main -->
            </div><!-- #primary -->
        </div><!-- .container -->
    </div><!-- #content -->
@stop
