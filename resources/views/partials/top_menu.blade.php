<div class="top-bar">
    <div class="container">
        <nav>
            <ul id="menu-top-bar-left" class="nav nav-inline pull-left animate-dropdown flip">
                <li class="menu-item animate-dropdown"><a title="Welcome to Worldwide Electronics Store" href="#">@lang('messages.Welcome to Worldwide Electronics Store')</a></li>
            </ul>
        </nav>

        <nav>
            <ul id="menu-top-bar-right" class="nav nav-inline pull-right animate-dropdown flip">
                <li class="menu-item animate-dropdown"><a title="Store Locator" href="#"><i class="ec ec-map-pointer"></i>Store Locator</a></li>
                <li class="menu-item animate-dropdown"><a title="Track Your Order" href="track-your-order.html"><i class="ec ec-transport"></i>@lang('messages.Track Your Order')</a></li>
                @if(\Auth::check())
                <li class="menu-item animate-dropdown"><a title="My Account" href="{{ route('logout') }}"><i class="ec ec-user"></i>Выход</a></li>
                @else
                <li class="menu-item animate-dropdown"><a title="My Account" href="{{ route('showLogin') }}"><i class="ec ec-user"></i>Вход</a></li>
                @endif
            </ul>
        </nav>
    </div>
</div><!-- /.top-bar -->
