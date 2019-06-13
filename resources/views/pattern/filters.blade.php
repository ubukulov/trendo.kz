<aside class="widget widget_electro_products_filter">
    <h3 class="widget-title">@lang('messages.Filters')</h3>

    @foreach($category->filters() as $catFilter)
    <aside class="widget woocommerce widget_layered_nav">
        <h3 class="widget-title">{{ $catFilter->title }}</h3>
        @php
            $filter = \App\Models\Filter::findOrFail($catFilter->id);
        @endphp
        <ul>
            @foreach($filter->values() as $filter_value)
            <li style="">
                <a href="#">{{ $filter_value->title }}</a> <span class="count">(0)</span>
            </li>
            @endforeach
        </ul>
        <p class="maxlist-more"><a href="#">+ Show more</a></p>
    </aside>
    @endforeach

    <aside class="widget woocommerce widget_price_filter">
        <h3 class="widget-title">Price</h3>
        <form action="#">
            <div class="price_slider_wrapper">
                <div style="" class="price_slider ui-slider ui-slider-horizontal ui-widget ui-widget-content ui-corner-all">
                    <div class="ui-slider-range ui-widget-header ui-corner-all" style="left: 0%; width: 100%;"></div>
                    <span tabindex="0" class="ui-slider-handle ui-state-default ui-corner-all" style="left: 0%;"></span>
                    <span tabindex="0" class="ui-slider-handle ui-state-default ui-corner-all" style="left: 100%;"></span>
                </div>
                <div class="price_slider_amount">
                    <a href="#" class="button">Filter</a>
                    <div style="" class="price_label">Price: <span class="from">$428</span> &mdash; <span class="to">$3485</span></div>
                    <div class="clear"></div>
                </div>
            </div>
        </form>
    </aside>
</aside>
