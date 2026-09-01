@if (hasPermission('import.*'))
    <li class="has-child {{ request()->routeIs('import.*') ? 'open' : '' }}">

        <a href="#"
           class="{{ request()->routeIs('import.*') ? 'active' : '' }}">
            <span class="nav-icon fa fa-cart-arrow-down"></span>
            <span class="menu-text">{{ t_('menu.import') }}</span>
            <span class="toggle-icon"></span>
        </a>

        <ul>
            @if (hasPermission('import.purchase-orders.index'))
                <li>
                    <a href="{{ route('import.purchase-orders.index') }}"
                       class="{{ request()->routeIs('import.purchase-orders.*') ? 'active' : '' }}">

                        <span class="nav-icon fa fa-luggage-cart"
                              style="margin-right: 20px;"></span>

                        {{ t_('menu.import-purchase-orders') }}
                    </a>
                </li>
            @endif
        </ul>

    </li>
@endif