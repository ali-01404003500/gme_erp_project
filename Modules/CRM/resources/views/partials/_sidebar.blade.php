{{-- CRM Menu Starts --}}
@if (hasPermission('crm.*'))
<li class="has-child {{ request()->routeIs('crm.*') ? 'open' : '' }}">
    <a href="#" class="{{ request()->routeIs('crm.*') ? 'active' : '' }}">
        <span class="nav-icon fas fa-user-alt"></span>
        <span class="menu-text">{{ t_('menu.crm-menu-title') }}</span>
        <span class="toggle-icon"></span>
    </a>
    <ul>
        @if (hasPermission('crm.customers.index'))
            <li>
                <a href="{{ route('crm.customers.index') }}"
                    class="{{ request()->routeIs('crm.customers.*') ? 'active' : '' }}">
                    <span class="nav-icon uil uil-users-alt" style="margin-right: 20px;"></span>
                    {{ t_('menu.customers') }}
                </a>
            </li>
        @endif
        @if (hasPermission('crm.brokers.index'))
            <li>
                <a href="{{ route('crm.brokers.index') }}"
                    class="{{ request()->routeIs('crm.brokers.*') ? 'active' : '' }}">
                    <span class="nav-icon uil uil-user" style="margin-right: 20px;"></span>
                    {{ t_('menu.brokers') }}
                </a>
            </li>
        @endif
        @if (hasPermission('crm.daily-calls.create'))
            <li>
                <a href="{{ route('crm.daily-calls.create') }}"
                    class="{{ request()->routeIs('crm.daily-calls.*') ? 'active' : '' }}">
                    <span class="nav-icon uil uil-calendar-alt" style="margin-right: 20px;"></span>
                    {{ t_('menu.daily-calls') }}
                </a>
            </li>
        @endif
        @if (hasPermission('crm.customer-types.*') || hasPermission('crm.customer-ratings.*'))
            <li
                class="has-subchild {{ request()->routeIs('crm.customer-types.*') || request()->routeIs('crm.customer-ratings.*') || request()->routeIs('crm.percentage-types.*') ? 'open' : '' }}">
                <a href="#"
                    class="{{ request()->routeIs('crm.customer-types.*') || request()->routeIs('crm.customer-ratings.*') || request()->routeIs('crm.percentage-types.*') ? 'active' : '' }}">
                    <span class="nav-icon uil uil-atom"></span>
                    <span class="menu-text">{{ t_('menu.CRM settings') }}</span>
                    <span class="toggle-icon"></span>
                </a>
                <ul>
                    @if (hasPermission('crm.customer-types.*'))
                        <li>
                            <a href="{{ route('crm.customer-types.index') }}"
                                class="{{ request()->routeIs('crm.customer-types.*') ? 'active' : '' }}">
                                <span class="fas fa-user-friends" style="margin-right: 20px;"></span>
                                {{ t_('menu.customer-types') }}
                            </a>
                        </li>
                    @endif
                    @if (hasPermission('crm.customer-ratings.*'))
                        <li>
                            <a href="{{ route('crm.customer-ratings.index') }}"
                                class="{{ request()->routeIs('crm.customer-ratings.*') ? 'active' : '' }}">
                                <span class="uil uil-star" style="margin-right: 20px;"></span>
                                {{ t_('menu.customer-ratings') }}
                            </a>
                        </li>
                    @endif
                  
                </ul>
            </li>
        @endif
        @if (hasPermission('crm.reports.*'))
                <li class="has-subchild {{ request()->routeIs('crm.reports.*') ? 'open' : '' }}">
                    <a href="#" class="{{ request()->routeIs('crm.reports.*') ? 'active' : '' }}">
                        <span class="nav-icon uil uil-chart"></span>
                        <span class="menu-text">{{ t_('menu.Reports') }}</span>
                        <span class="toggle-icon"></span>
                    </a>
                    <ul>
                        @if (hasPermission('crm.reports.customer-machine-code'))
                            <li>
                                <a href="{{ route('crm.reports.customer-machine-code') }}"
                                    class="{{ request()->routeIs('crm.reports.customer-machine-code') ? 'active' : '' }}">
                                    <span class="nav-icon uil uil-analytics" style="margin-right: 20px;"></span>
                                    {{ t_('menu.Customer List (Machine Code) Report') }}
                                </a>
                            </li>
                        @endif
                        @if (hasPermission('crm.reports.customer-balance-details'))
                            <li>
                                <a href="{{ route('crm.reports.customer-balance-details') }}"
                                    class="{{ request()->routeIs('crm.reports.customer-balance-details') ? 'active' : '' }}">
                                    <span class="nav-icon uil uil-dollar-alt" style="margin-right: 20px;"></span>
                                    {{ t_('menu.Customer Balance Details Report') }}
                                </a>
                            </li>
                        @endif

                        
                    </ul>
                </li>
            @endif
    </ul>
</li>
@endif