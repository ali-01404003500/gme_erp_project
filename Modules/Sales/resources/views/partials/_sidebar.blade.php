@if (hasPermission('sales.*'))
    <li class="has-child {{ request()->routeIs('sales.*') ? 'open' : '' }}">
        <a href="#" class="{{ request()->routeIs('sales.*') ? 'active' : '' }}">
            <span class="nav-icon fas fa-chart-line"></span>
            <span class="menu-text">{{ t_('menu.sales') }}</span>
            <span class="toggle-icon"></span>
        </a>
        <ul>
            @if (hasPermission('sales.sales-orders.create'))
                <li><a href="{{ route('sales.sales-orders.create') }}"
                        class="{{ request()->routeIs('sales.sales-orders.*') ? 'active' : '' }}">
                        <span class="nav-icon fa fa-shopping-cart" style="margin-right: 20px;"></span>
                        {{ t_('menu.sales orders') }}</a>
                </li>
            @endif
            @if (hasPermission('sales.sales-order-import.index'))
                <li><a href="{{ route('sales.sales-order-import.index') }}"
                        class="{{ request()->routeIs('sales.sales-order-import.*') ? 'active' : '' }}">
                        <span class="nav-icon fa fa-upload" style="margin-right: 20px;"></span>
                        Sales Order Import</a>
                </li>
            @endif
            @if (hasPermission('sales.deliveries.index'))
                <li><a href="{{ route('sales.deliveries.index') }}"
                        class="{{ request()->routeIs('sales.deliveries.*') ? 'active' : '' }}">
                        <span class="nav-icon fa fa-truck" style="margin-right: 20px;"></span>
                        {{ t_('menu.deliveries') }}</a>
                </li>
            @endif
            @if (hasPermission('sales.shipment-verifies.index'))
                <li>
                    <a href="{{ route('sales.shipment-verifies.index') }}"
                        class="{{ request()->routeIs('sales.shipment-verifies.*') ? 'active' : '' }}">
                        <span class="nav-icon fa fa-truck" style="margin-right: 20px;"></span>
                        {{ t_('menu.shipment-verifies-menu-title') }}
                    </a>
                </li>
            @endif

            @if (hasPermission('sales.condition-amount-collects.index'))
                <li>
                    <a href="{{ route('sales.condition-amount-collects.index') }}"
                        class="{{ request()->routeIs('sales.condition-amount-collects.index') ? 'active' : '' }}">
                        <span class="nav-icon fa fa-money-bill" style="margin-right: 20px;"></span>
                        Condition Collection
                    </a>
                </li>
            @endif

            @if (hasPermission('sales.condition-amount-collects.approved-list'))
                <li>
                    <a href="{{ route('sales.condition-amount-collects.approved-list') }}"
                        class="{{ request()->routeIs('sales.condition-amount-collects.approved-list') ? 'active' : '' }}">
                        <span class="nav-icon fa fa-check-circle" style="margin-right: 20px;"></span>
                        Condition Approval
                    </a>
                </li>
            @endif

            @if (hasPermission('sales.fake-invoices.create'))
                <li><a href="{{ route('sales.fake-invoices.create') }}"
                        class="{{ request()->routeIs('sales.fake-invoices.*') ? 'active' : '' }}">
                        <span class="nav-icon fa fa-file-invoice" style="margin-right: 20px;"></span>
                        {{ t_('menu.fake-invoices') }}</a>
                </li>
            @endif

            @if (hasPermission('sales.sales-requisitions.create'))
                <li><a href="{{ route('sales.sales-requisitions.create') }}"
                        class="{{ request()->routeIs('sales.sales-requisitions.*') ? 'active' : '' }}">
                        <span class="nav-icon fa fa-shopping-cart" style="margin-right: 20px;"></span>
                        {{ t_('menu.sales requisitions') }}</a>
                </li>
            @endif

            @if (hasPermission('sales.sales-returns.index'))
                <li><a href="{{ route('sales.sales-returns.index') }}"
                        class="{{ request()->routeIs('sales.sales-returns.*') ? 'active' : '' }}">
                        <span class="nav-icon fa fa-undo" style="margin-right: 20px;"></span>
                        {{ t_('menu.sales-returns') }}</a>
                </li>
            @endif

            @if (hasPermission('sales.sales-commissions.index'))
                <li><a href="{{ route('sales.sales-commissions.index') }}"
                        class="{{ request()->routeIs('sales.sales-commissions.*') ? 'active' : '' }}">
                        <span class="nav-icon fa fa-percent" style="margin-right: 20px;"></span>
                        {{ t_('menu.sales-commissions') }}</a>
                </li>
            @elseif (hasPermission('sales.sales-commissions.create'))
                <li><a href="{{ route('sales.sales-commissions.create') }}"
                        class="{{ request()->routeIs('sales.sales-commissions.*') ? 'active' : '' }}">
                        <span class="nav-icon fa fa-percent" style="margin-right: 20px;"></span>
                        {{ t_('menu.sales-commissions') }}</a>
                </li>
            @endif

            @if (hasPermission('sales.backup-challans.create'))
                <li><a href="{{ route('sales.backup-challans.create') }}"
                        class="{{ request()->routeIs('sales.backup-challans.*') ? 'active' : '' }}">
                        <span class="nav-icon fa fa-shopping-cart" style="margin-right: 20px;"></span>
                        {{ t_('menu.backup/challan') }}</a>
                </li>
            @endif
            @if (hasPermission('sales.quotations.create'))
                <li><a href="{{ route('sales.quotations.create') }}"
                        class="{{ request()->routeIs('sales.quotations.*') ? 'active' : '' }}">
                        <span class="nav-icon fa fa-shopping-cart" style="margin-right: 20px;"></span>
                        {{ t_('menu.quotations') }}</a>
                </li>
            @endif

            @if (hasPermission('sales.settings.*') || hasPermission('sales.couriers.*'))
                <li class="has-subchild {{ request()->routeIs('sales.couriers.*') ? 'open' : '' }}">
                    <a href="#" class="{{ request()->routeIs('sales.couriers.*') ? 'active' : '' }}">
                        <span class="nav-icon uil uil-atom"></span>
                        <span class="menu-text">{{ t_('menu.Sales Settings') }}</span>
                        <span class="toggle-icon"></span>
                    </a>
                    <ul>
                        @if (hasPermission('sales.couriers.*'))
                            <li>
                                <a href="{{ route('sales.couriers.index') }}"
                                    class="{{ request()->routeIs('sales.couriers.*') ? 'active' : '' }}">
                                    <span class="fas fa-truck" style="margin-right: 20px;"></span>
                                    {{ t_('menu.couriers') }}
                                </a>
                            </li>
                        @endif

                    </ul>
                </li>
            @endif
            @if (hasPermission('sales.reports.*'))
                <li class="has-subchild {{ request()->routeIs('sales.reports.*') ? 'open' : '' }}">
                    <a href="#" class="{{ request()->routeIs('sales.reports.*') ? 'active' : '' }}">
                        <span class="nav-icon uil uil-chart"></span>
                        <span class="menu-text">{{ t_('menu.Reports') }}</span>
                        <span class="toggle-icon"></span>
                    </a>
                    <ul>
                        @if (hasPermission('sales.reports.sales-report'))
                            <li>
                                <a href="{{ route('sales.reports.sales-report') }}"
                                    class="{{ request()->routeIs('sales.reports.sales-report') ? 'active' : '' }}">
                                    <span class="nav-icon uil uil-analytics" style="margin-right: 20px;"></span>
                                    {{ t_('menu.Sales Reports') }}
                                </a>
                            </li>
                        @endif

                        @if (hasPermission('sales.reports.fake-sales'))
                            <li>
                                <a href="{{ route('sales.reports.fake-sales') }}"
                                    class="{{ request()->routeIs('sales.reports.fake-sales') ? 'active' : '' }}">
                                    <span class="nav-icon uil uil-presentation-line" style="margin-right: 20px;"></span>
                                    {{ t_('menu.fake-sales Reports') }}
                                </a>
                            </li>
                        @endif
                        @if (hasPermission('sales.reports.shipment-explorer'))
                            <li>
                                <a href="{{ route('sales.reports.shipment-explorer') }}"
                                    class="{{ request()->routeIs('sales.reports.shipment-explorer') ? 'active' : '' }}">
                                    <span class="nav-icon fas fa-map-marked-alt" style="margin-right: 20px;"></span>
                                    {{ t_('menu.shipment-explorer Reports') }}
                                </a>
                            </li>
                        @endif


                        @if (hasPermission('sales.reports.brand-supplier-sales-report'))
                            <li>
                                <a href="{{ route('sales.reports.brand-supplier-sales-report') }}"
                                    class="{{ request()->routeIs('sales.reports.brand-supplier-sales-report') ? 'active' : '' }}">
                                    <span class="nav-icon uil uil-analytics" style="margin-right: 20px;"></span>
                                    {{ t_('menu.brand/supplier sales Reports') }}
                                </a>
                            </li>
                        @endif


                        @if(hasPermission('sales.reports.broker-commissions'))
                            <li>
                                <a href="{{ route('sales.reports.broker-commissions') }}"
                                    class="{{ request()->routeIs('sales.reports.broker-commissions') ? 'active' : '' }}">
                                    <span class="nav-icon uil uil-analytics" style="margin-right: 20px;"></span>
                                    {{ t_('menu.broker-commissions Reports') }}
                                </a>
                            </li>
                        @endif
                    </ul>
                </li>
            @endif


        </ul>
    </li>
@endif