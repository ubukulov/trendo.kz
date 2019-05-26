<nav class="woocommerce-breadcrumb">
    @if($breadcrumbs)
        @foreach ($breadcrumbs as $breadcrumb)
            @if ($breadcrumb->url && !$loop->last)
                <a href="{{ $breadcrumb->url }}">{{ $breadcrumb->title }}</a>
                <span class="delimiter"><i class="fa fa-angle-right"></i></span>
            @else
                <span class="delimiter"><i class="fa fa-angle-right"></i></span>{{ $product->title }}
            @endif
        @endforeach
    @endif
</nav><!-- /.woocommerce-breadcrumb -->