@if (hasPermission('services.*'))
<li class="has-child {{ request()->routeIs('services.*') ? 'open' : '' }}">
    <a href="#" class="{{ request()->routeIs('services.*') ? 'active' : '' }}">
        <span class="nav-icon fas fa-th-large"></span>
        <span class="menu-text">{{ t_('menu.service-menu-title') }}</span>
        <span class="toggle-icon"></span>
    </a>
    <ul>
        @if (hasPermission('services.service.create'))
            <li><a href="{{ route('services.service.create') }}"
                    class="{{ request()->routeIs('services.service.*') ? 'active' : '' }}">
                    <span class="nav-icon fas fa-wrench" style="margin-right: 20px;"></span>
                    {{ t_('menu.services-service-menu-title') }}</a>
            </li>
        @endif
        
            {{-- <li><a href="{{ route('services.service-assign.index') }}"
                    class="{{ request()->routeIs('services.service-assign.*') ? 'active' : '' }}">
                    <span class="nav-icon fas fa-wrench" style="margin-right: 20px;"></span>
                    {{ t_('menu.services-service-assign-menu-title') }}</a>
            </li> --}}
        @if (hasPermission('services.service-assign.index'))
            <li>
                <a href="{{ route('services.service-assign.index') }}"
                    class="{{ request()->routeIs('services.service-assign.*') ? 'active' : '' }}">
                    <span class="nav-icon fas fa-user-edit" style="margin-right: 20px;"></span>
                    {{ t_('menu.services-service-assign-menu-title') }}</a>
            </li>
        @endif
        @if (hasPermission('services.service-my-task.*'))
            <li>
                <a href="{{ route('services.service-my-task.index') }}"
                    class="{{ request()->routeIs('services.service-my-task.*') ? 'active' : '' }}">
                    <span class="nav-icon fas fa-user-edit" style="margin-right: 20px;"></span>
                    {{ t_('menu.service-my-task-menu-title') }}</a>
            </li>
        @endif
        @if (hasPermission('services.service-my-task.solution-verification'))
            <li>
                <a href="{{ route('services.service-my-task.solution-verification') }}"
                    class="{{ request()->routeIs('services.service-my-task.solution-verification.*') ? 'active' : '' }}">
                    <span class="nav-icon fas fa-ticket-alt" style="margin-right: 20px;"></span>
                    {{ t_('menu.solution-verification') }}</a>
            </li>
        @endif
        @if (hasPermission('services.quotations.create'))
            <li>
                <a href="{{ route('services.quotations.create') }}"
                    class="{{ request()->routeIs('services.quotations.*') ? 'active' : '' }}">
                    <span class="nav-icon fas fa-quote-left" style="margin-right: 20px;"></span>
                    {{ t_('menu.quotations') }}</a>
            </li>
        @endif
        @if (hasPermission('services.document-entries.index'))
            <li>
                <a href="{{ route('services.document-entries.index') }}"
                    class="{{ request()->routeIs('services.document-entries.*') ? 'active' : '' }}">
                    <span class="nav-icon fas fa-file-alt" style="margin-right: 20px;"></span>
                    {{ t_('menu.document-entries') }}</a>
            </li>
        @endif
        @if (hasPermission('services.settings.*'))
            <li class="has-subchild {{ request()->routeIs('services.settings.*') }}">
                <a href="#" class="{{ request()->routeIs('services.settings.*') }}">
                    <span class="nav-icon fa fa-cog"></span>
                    <span class="menu-text">{{ t_('menu.services-settings-menu-title') }}</span>
                    <span class="toggle-icon"></span>
                </a>
                <ul>
                    @if (hasPermission('services.settings.service-types.index'))
                        <li><a href="{{ route('services.settings.service-types.index') }}"
                                class="{{ request()->routeIs('services.settings.service-types.*') ? 'active' : '' }}">
                                <span class="nav-icon nav-icon fas fa-hammer"
                                    style="margin-right: 21px;"></span>
                                {{ t_('menu.services-settings-service-types-menu-title') }}</a>
                        </li>
                    @endif
                </ul>
            </li>
        @endif
        @if (hasPermission('services.reports.*'))
                <li class="has-subchild {{ request()->routeIs('services.reports.*') ? 'open' : '' }}">
                <a href="#"
                        class="{{ request()->routeIs('services.reports.*') ? 'active' : '' }}">
                        <span class="nav-icon uil uil-chart-bar"></span>
                        <span class="menu-text">{{ t_('menu.Reports') }}</span>
                        <span class="toggle-icon"></span>
                    </a>
                <ul>
                     @if (hasPermission('services.reports.service-reports'))
                        <li><a href="{{ route('services.reports.service-reports') }}"
                                class="{{ request()->routeIs('services.reports.service-reports') ? 'active' : '' }}">
                                <span class="nav-icon fa fa-chart-pie" style="margin-right: 20px;"></span>
                                {{ t_('menu.service-reports') }}</a>
                        </li>
                    @endif
                    @if (hasPermission('services.reports.service-explorer-reports'))
                        <li><a href="{{ route('services.reports.service-explorer-reports') }}"
                                class="{{ request()->routeIs('services.reports.service-explorer-reports') ? 'active' : '' }}">
                                <span class="nav-icon fa fa-search" style="margin-right: 20px;"></span>
                                {{ t_('menu.service-explorer-reports') }}</a>
                        </li>
                    @endif
                    @if (hasPermission('services.reports.installation-reports'))
                        <li><a href="{{ route('services.reports.installation-reports') }}"
                                class="{{ request()->routeIs('services.reports.installation-reports') ? 'active' : '' }}">
                                <span class="nav-icon fas fa-wrench" style="margin-right: 20px;"></span>
                                {{ t_('menu.Installation & Servicing Reports') }}</a>
                        </li>
                    @endif
                    @if (hasPermission('services.reports.monthly-service-reports'))
                        <li><a href="{{ route('services.reports.monthly-service-reports') }}"
                                class="{{ request()->routeIs('services.reports.monthly-service-reports') ? 'active' : '' }}">
                                <span class="nav-icon fa fa-chart-line" style="margin-right: 20px;"></span>
                                {{ t_('menu.monthly-service-reports') }}</a>
                        </li>
                    @endif
                    
                    @if (hasPermission('services.reports.warranty-check'))
                        <li><a href="{{ route('services.reports.warranty-check') }}"
                                class="{{ request()->routeIs('services.reports.warranty-check') ? 'active' : '' }}">
                                <span class="nav-icon fa fa-file-invoice" style="margin-right: 20px;"></span>
                                {{ t_('menu.Warranty Date Check') }}</a>
                        </li>
                    @endif

                </ul>
            </li>
        @endif
        

    </ul>
</li>
@endif