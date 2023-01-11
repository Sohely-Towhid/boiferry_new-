<ul class="my__account-nav nav flex-column mb-8" role="tablist" id="pills-tab">
    <li class="nav-item mx-0">
        <a href="{{ url('my-account') }}" class="nav-link d-flex align-items-center px-0">
            <span class="font-weight-normal text-gray-600">{{ __('web.Dashboard') }}</span>
        </a>
    </li>
     <li class="nav-item mx-0">
        <a href="{{ url('my-account/orders') }}" class="nav-link d-flex align-items-center px-0">
            <span class="font-weight-normal text-gray-600">{{ __('web.Orders') }}</span>
        </a>
    </li>
    <li class="nav-item mx-0">
        <a href="{{ url('my-account/orders?type=shipping') }}" class="nav-link d-flex align-items-center px-0">
            <span class="font-weight-normal text-gray-600">{{ __('web.In Shipping') }}</span>
        </a>
    </li>
    <li class="nav-item mx-0">
        <a href="{{ url('my-account/subscription') }}" class="nav-link d-flex align-items-center px-0">
            <span class="font-weight-normal text-gray-600">{{ __('web.Subscription') }}</span>
        </a>
    </li>
    <li class="nav-item mx-0">
        <a href="{{ url('my-account/profile') }}" class="nav-link d-flex align-items-center px-0">
            <span class="font-weight-normal text-gray-600">{{ __('web.Profile') }}</span>
        </a>
    </li>
    <li class="nav-item mx-0">
        <a href="{{ url('my-account/wishlist') }}" class="nav-link d-flex align-items-center px-0">
            <span class="font-weight-normal text-gray-600">{{ __('web.Wishlist') }}</span>
        </a>
    </li>
    <li class="nav-item mx-0">
        <a href="{{ url('my-account/support') }}" class="nav-link d-flex align-items-center px-0">
            <span class="font-weight-normal text-gray-600">{{ __('web.Support') }}</span>
        </a>
    </li>
    <li class="nav-item mx-0">
        <a class="nav-link d-flex align-items-center px-0" href="{{ url('logout') }}">
            <span class="font-weight-normal text-gray-600">{{ __('web.Log out') }}</span>
        </a>
    </li>
</ul>