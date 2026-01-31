@if (hasPermission('purchase.*'))
            <li class="has-child {{ request()->routeIs('purchase.*') ? 'open' : '' }}">
                <a href="#" class="{{ request()->routeIs('purchase.*') ? 'active' : '' }}">
                    <span class="nav-icon fa regular fa-cart-arrow-down"></span>
                    <span class="menu-text">{{ t_('menu.purchase') }}</span>
                    <span class="toggle-icon"></span>
                </a>
                <ul>
                    @if (hasPermission('purchase.requisitions.index'))
                        <li><a href="{{ route('purchase.requisitions.index') }}"
                                class="has-subchild {{ request()->routeIs('purchase.requisitions.*') ? 'active' : '' }}">
                                <span class="nav-icon fa fa-cart-arrow-down" style="margin-right: 20px;"></span>
                                {{ t_('menu.requisitions') }}</a>
                        </li>
                    @endif
                    @if (hasPermission('purchase.orders.index'))
                        <li><a href="{{ route('purchase.orders.index') }}"
                                class="has-subchild {{ request()->routeIs('purchase.orders.*') ? 'active' : '' }}">
                                <span class="nav-icon fa fa-luggage-cart" style="margin-right: 20px;"></span>
                                {{ t_('menu.purchase-orders') }}</a>
                        </li>
                    @endif
                    @if (hasPermission('purchase.offices.index'))
                        <li><a href="{{ route('purchase.offices.index') }}"
                                class="has-subchild {{ request()->routeIs('purchase.offices.*') ? 'active' : '' }}">
                                <span class="nav-icon fa fa-truck-moving" style="margin-right: 20px;"></span>
                                {{ t_('menu.office-purchases') }}</a>
                        </li>
                    @endif
                    @if (hasPermission('purchase.returns.index'))
                        <li>
                            <a href="{{ route('purchase.returns.index') }}"
                                class="has-subchild {{ request()->routeIs('purchase.returns.*') ? 'active' : '' }}">
                                <span class="nav-icon fa fa-shipping-fast" style="margin-right: 20px;"></span>
                                <span class="menu-text">{{ t_('Purchase Return') }}</span>
                            </a>
                        </li>
                    @endif
                    @if (hasPermission('purchase.suppliers.index'))
                        <li><a href="{{ route('purchase.suppliers.index') }}"
                                class="has-subchild {{ request()->routeIs('purchase.suppliers.*') ? 'active' : '' }}">
                                <span class="nav-icon fa fa-user" style="margin-right: 20px;"></span>
                                {{ t_('menu.suppliers') }}</a>
                        </li>
                    @endif
                    @if (hasPermission('purchase.vendors.index'))
                        <li><a href="{{ route('purchase.vendors.index') }}"
                                class="has-subchild {{ request()->routeIs('purchase.vendors.*') ? 'active' : '' }}">
                                <span class="nav-icon fa fa-users" style="margin-right: 20px;"></span>
                                {{ t_('menu.vendors') }}</a>
                        </li>
                    @endif
                    @if (hasPermission('purchase.reports.index'))
                        <li><a href="{{ route('purchase.reports.index') }}"
                                class="has-subchild {{ request()->routeIs('purchase.reports.*') ? 'active' : '' }}">
                                <span class="nav-icon fa fa-chart-line" style="margin-right: 20px;"></span>
                                {{ t_('menu.reports') }}</a>
                        </li>
                    @endif

                </ul>
            </li>
        @endif