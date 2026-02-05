@if (hasPermission('licenses.*'))
<li class="has-child {{ request()->routeIs('licenses.*') ? 'open' : '' }}">
    <a href="#" class="{{ request()->routeIs('licenses.*') ? 'active' : '' }}">
        <span class="nav-icon fas fa-file-invoice"></span>
        <span class="menu-text">{{ t_('menu.licenses') }}</span>
        <span class="toggle-icon"></span>
    </a>
    <ul>
        @if (hasPermission('licenses.dongle-or-serial-entries.create'))
            <li><a href="{{ route('licenses.dongle-or-serial-entries.create') }}"
                    class="{{ request()->routeIs('licenses.dongle-or-serial-entries.*') ? 'active' : '' }}">
                    <span class="nav-icon fa fa-list" style="margin-right: 20px;"></span>
                    {{ t_('menu.Dongle/Serial Entries') }}</a>
            </li>
        @endif
        @if (hasPermission('licenses.usg-opg-license-requisitions.create'))
            <li><a href="{{ route('licenses.usg-opg-license-requisitions.create') }}"
                    class="{{ request()->routeIs('licenses.usg-opg-license-requisitions.*') ? 'active' : '' }}">
                    <span class="nav-icon fa fa-file" style="margin-right: 20px;"></span>
                    {{ t_('menu.USG/OPG License Requisitions') }}</a>
            </li>
        @endif
        @if (hasPermission('licenses.cbc-license-requisitions.create'))
            <li><a href="{{ route('licenses.cbc-license-requisitions.create') }}"
                    class="{{ request()->routeIs('licenses.cbc-license-requisitions.*') ? 'active' : '' }}">
                    <span class="nav-icon fa fa-file-archive" style="margin-right: 20px;"></span>
                    {{ t_('menu.CBC License Requisitions') }}</a>
            </li>
        @endif
        @if (hasPermission('licenses.reports.index'))
            <li><a href="{{ route('licenses.reports.index') }}"
                    class="{{ request()->routeIs('licenses.reports.*') ? 'active' : '' }}">
                    <span class="nav-icon fa fa-file-invoice" style="margin-right: 20px;"></span>
                    {{ t_('menu.Reports') }}</a>
            </li>
        @endif
    </ul>
</li>

@endif