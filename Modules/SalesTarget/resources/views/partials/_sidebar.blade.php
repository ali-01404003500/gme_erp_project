@if (
    hasPermission('sales_target.perfomence.index') ||
    hasPermission('sales_target.settings.target.index') ||
    hasPermission('sales_target.salesIncentives.incentives.index') ||
    hasPermission('sales_target.sales-targets.index') ||
    hasPermission('sales_target.sales-target-slabs.index') ||
    hasPermission('sales_target.sales-incentive-slabs.index') ||
    hasPermission('sales_target.sales-salary-brackets.index')
)
    <li class="has-child {{ request()->routeIs('sales_target.*') ? 'open' : '' }}">

        <a href="#"
           class="{{ request()->routeIs('sales_target.*') ? 'active' : '' }}">
            <span class="nav-icon fas fa-chart-line"></span>
            <span class="menu-text">{{ t_('menu.sales_target') }}</span>
            <span class="toggle-icon"></span>
        </a>

        <ul>

            {{-- Achievement List --}}
            {{-- @if (hasPermission('sales_target.perfomence.index'))
                <li>
                    <a href="{{ route('sales_target.perfomence.achievement') }}"
                       class="{{ request()->routeIs('sales_target.perfomence.*') ? 'active' : '' }}">
                        <span class="fas fa-trophy" style="margin-right: 20px;"></span>
                        {{ t_('menu.Target Achievement List') }}
                    </a>
                </li>
            @endif --}}


            {{-- Sales Target Setup --}}
            {{-- @if (hasPermission('sales_target.settings.target.index'))
                <li>
                    <a href="{{ route('sales_target.settings.target.index') }}"
                       class="{{ request()->routeIs('sales_target.settings.target.*') ? 'active' : '' }}">
                        <span class="fas fa-list" style="margin-right: 20px;"></span>
                        {{ t_('menu.Sales Target Setup') }}
                    </a>
                </li>
            @endif --}}


            {{-- Sales Incentive Setup --}}
            {{-- @if (hasPermission('sales_target.salesIncentives.incentives.index'))
                <li>
                    <a href="{{ route('sales_target.salesIncentives.incentives.index') }}"
                       class="{{ request()->routeIs('sales_target.salesIncentives.incentives.*') ? 'active' : '' }}">
                        <span class="fas fa-table" style="margin-right: 20px;"></span>
                        {{ t_('menu.Sales Incentive Setup') }}
                    </a>
                </li>
            @endif --}}


            {{-- Sales Targets --}}
            @if (hasPermission('sales_target.sales-targets.index'))
                <li>
                    <a href="{{ route('sales_target.sales-targets.index') }}"
                       class="{{ request()->routeIs('sales_target.sales-targets.*') ? 'active' : '' }}">
                        <span class="fas fa-table" style="margin-right: 20px;"></span>
                        {{ t_('menu.Sales Targets') }}
                    </a>
                </li>
            @endif


            {{-- Sales Target Slabs --}}
            @if (hasPermission('sales_target.sales-target-slabs.index'))
                <li>
                    <a href="{{ route('sales_target.sales-target-slabs.index') }}"
                       class="{{ request()->routeIs('sales_target.sales-target-slabs.*') ? 'active' : '' }}">
                        <span class="fas fa-table" style="margin-right: 20px;"></span>
                        {{ t_('menu.Sales Target Slabs') }}
                    </a>
                </li>
            @endif


            {{-- Sales Incentive Slabs --}}
            @if (hasPermission('sales_target.sales-incentive-slabs.index'))
                <li>
                    <a href="{{ route('sales_target.sales-incentive-slabs.index') }}"
                       class="{{ request()->routeIs('sales_target.sales-incentive-slabs.*') ? 'active' : '' }}">
                        <span class="fas fa-table" style="margin-right: 20px;"></span>
                        {{ t_('menu.Sales Incentive Slabs') }}
                    </a>
                </li>
            @endif


            {{-- Sales Salary Brackets --}}
            @if (hasPermission('sales_target.sales-salary-brackets.index'))
                <li>
                    <a href="{{ route('sales_target.sales-salary-brackets.index') }}"
                       class="{{ request()->routeIs('sales_target.sales-salary-brackets.*') ? 'active' : '' }}">
                        <span class="fas fa-table" style="margin-right: 20px;"></span>
                        {{ t_('menu.Performance Base Salary Slabs') }}
                    </a>
                </li>
            @endif

        </ul>
    </li>
@endif