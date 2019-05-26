<style>
    .dropdown-menu {
        width: 750px !important;
    }
</style>
<nav>
    <ul class="list-group vertical-menu yamm make-absolute">
        <li class="list-group-item">
            <span><i class="fa fa-list-ul"></i> @lang('messages.All Departments')</span>
        </li>

        @foreach($cats as $cat)
        <li class="yamm-tfw menu-item menu-item-has-children animate-dropdown dropdown">
            @if($cat->isRoot())
            <a title="Computers &amp; Accessories" data-hover="dropdown" href="product-category.html" data-toggle="dropdown" class="dropdown-toggle" aria-haspopup="true">{{ $cat->title }}</a>
                @if($cat->hasChildren())
                    @foreach($cat->children as $child)
                    <ul role="menu" class=" dropdown-menu" style="width: 750px !important;">
                        <li class="menu-item animate-dropdown menu-item-object-static_block">
                            <div class="yamm-content">
                                <div style="display: none;" class="row bg-yamm-content bg-yamm-content-bottom bg-yamm-content-right">
                                    <div class="col-sm-12">
                                        <div class="vc_column-inner ">
                                            <div class="wpb_wrapper">
                                                <div class="wpb_single_image wpb_content_element vc_align_left">
                                                    <figure class="wpb_wrapper vc_figure">
                                                        <div class="vc_single_image-wrapper vc_box_border_grey">

                                                            <img src="assets/images/megamenu-2.png" class="vc_single_image-img attachment-full" alt="">
                                                        </div>
                                                    </figure>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
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
                                    <div class="col-sm-{{ $number }}">
                                        <div class="vc_column-inner ">
                                            <div class="wpb_wrapper">
                                                <div class="wpb_text_column wpb_content_element ">
                                                    <div class="wpb_wrapper">
                                                        <ul>
                                                            <li class="nav-title">{!! $item->title !!}</li>
                                                                @if($item->hasChildren())
                                                                    @foreach($item->children as $grandson)
                                                                        <li><a href="{{ $grandson->url() }}">{!! $grandson->title !!}</a></li>
                                                                    @endforeach
                                                                @endif
                                                            <li class="nav-divider"></li>
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
</nav>