{{-- @if (hasPermission('sales_target.*'))
<li class="has-child {{ request()->routeIs('sales_target.*') ? 'open' : '' }}">
    <a href="#" class="{{ request()->routeIs('sales_target.*') ? 'active' : '' }}">
        <span class="nav-icon fas fa-chart-line"></span>
        <span class="menu-text">{{ t_('menu.sales_target') }}</span>
        <span class="toggle-icon"></span>
    </a>
    <ul>
        @if (hasPermission('sales_target.example.*'))
        <li>
            <a href="{{ route('sales_target.example.*') }}"
                class="{{ request()->routeIs('sales_target.example.*') ? 'active' : '' }}">
                <span class="nav-icon fa fa-shopping-cart" style="margin-right: 20px;"></span>
                {{ t_('menu.quotations') }}
            </a>
        </li>
        @endif

        @if (hasPermission('sales_target.settings.*'))
        <li class="has-subchild {{ request()->routeIs('sales_target.couriers.*') ? 'open' : '' }}">
            <a href="#" class="{{ request()->routeIs('sales_target.couriers.*') ? 'active' : '' }}">
                <span class="nav-icon uil uil-atom"></span>
                <span class="menu-text">{{ t_('menu.sales_target Settings') }}</span>
                <span class="toggle-icon"></span>
            </a>
            <ul>
                @if (hasPermission('sales_target.settings.achievement-based-salary-policy.index'))
                <li>
                    <a href="{{ route('sales_target.settings.achievement-based-salary-policy.index') }}"
                        class="{{ request()->routeIs('sales_target.settings.achievement-based-salary-policy.index') ? 'active' : '' }}">
                        <span class="fas fa-truck" style="margin-right: 20px;"></span>
                        {{ t_('menu.achievement-based-salary-policy') }}
                    </a>
                </li>
                @endif

            </ul>
        </li>
        @endif
    </ul>
</li>
@endif --}}

@if (hasPermission('sales_target.*'))
    <li class="has-child {{ request()->routeIs('sales_target.*') ? 'open' : '' }}">
        <a href="#" class="{{ request()->routeIs('sales_target.*') ? 'active' : '' }}">
            <span class="nav-icon fas fa-chart-line"></span>
            <span class="menu-text">{{ t_('menu.sales_target') }}</span>
            <span class="toggle-icon"></span>
        </a>
        <ul>
            {{-- Achievement List - High Priority --}}
            @if (hasPermission('sales_target.perfomence.achievement'))
                <li>
                    <a href="{{ route('sales_target.perfomence.achievement') }}"
                        class="{{ request()->routeIs('sales_target.perfomence.achievement') ? 'active' : '' }}">
                        <span class="fas fa-trophy" style="margin-right: 20px;"></span>
                        {{ t_('menu.Achievement List') }}
                    </a>
                </li>
            @endif

            <hr style="border-top: 1px solid #444; margin: 5px 20px;">

            @if (hasPermission('sales_target.settings.target.index'))
                <li>
                    <a href="{{ route('sales_target.settings.target.index') }}"
                        class="{{ request()->routeIs('sales_target.settings.target.index') ? 'active' : '' }}">
                        <span class="fas fa-list" style="margin-right: 20px;"></span>
                        {{ t_('menu.Target Summary') }}
                    </a>
                </li>
            @endif

            {{-- @if (hasPermission('sales_target.settings.target.create'))
                <li>
                    <a href="{{ route('sales_target.settings.target.create') }}"
                        class="{{ request()->routeIs('sales_target.settings.target.create') ? 'active' : '' }}">
                        <span class="fas fa-plus-circle" style="margin-right: 20px;"></span>
                        {{ t_('menu.Create Target') }}
                    </a>
                </li>
            @endif --}}

            <hr style="border-top: 1px solid #444; margin: 5px 20px;">

            @if (hasPermission('sales_target.settings.incentives.index'))
                <li>
                    <a href="{{ route('sales_target.settings.incentives.index') }}"
                        class="{{ request()->routeIs('sales_target.settings.incentives.index') ? 'active' : '' }}">
                        <span class="fas fa-table" style="margin-right: 20px;"></span>
                        {{ t_('menu.Incentive Summary') }}
                    </a>
                </li>
            @endif

            {{-- @if (hasPermission('sales_target.settings.incentives.create'))
                <li>
                    <a href="{{ route('sales_target.settings.incentives.create') }}"
                        class="{{ request()->routeIs('sales_target.settings.incentives.create') ? 'active' : '' }}">
                        <span class="fas fa-plus-circle" style="margin-right: 20px;"></span>
                        {{ t_('menu.Incentive Setup') }}
                    </a>
                </li>
            @endif --}}
        </ul>
    </li>
@endif