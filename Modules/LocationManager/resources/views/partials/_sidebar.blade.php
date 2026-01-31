@if (hasPermission('location_manager.*'))
            <li class="has-child {{ request()->routeIs('location_manager.*') ? 'open' : '' }}">
                <a href="#" class="{{ request()->routeIs('location_manager.*') ? 'active' : '' }}">
                    <span class="nav-icon fas fa-location-arrow"></span>
                    <span class="menu-text">{{ t_('menu.location manager') }}</span>
                    <span class="toggle-icon"></span>
                </a>
                <ul>
                    @if (hasPermission('location_manager.divisions.index'))
                        <li><a href="{{ route('location_manager.divisions.index') }}"
                                class="{{ request()->routeIs('location_manager.divisions.*') ? 'active' : '' }}">
                                <span class="nav-icon uil uil-map-marker" style="margin-right: 20px;"></span>
                                {{ t_('divisions') }}</a>
                        </li>
                    @endif
                    @if (hasPermission('location_manager.districts.index'))
                        <li><a href="{{ route('location_manager.districts.index') }}"
                                class="{{ request()->routeIs('location_manager.districts.*') ? 'active' : '' }}">
                                <span class="nav-icon uil uil-map-marker" style="margin-right: 20px;"></span>
                                {{ t_('districts') }}</a>
                        </li>
                    @endif
                    @if (hasPermission('location_manager.thanas.index'))
                        <li><a href="{{ route('location_manager.thanas.index') }}"
                                class="{{ request()->routeIs('location_manager.thanas.*') ? 'active' : '' }}">
                                <span class="nav-icon uil uil-map-marker" style="margin-right: 20px;"></span>
                                {{ t_('thanas') }}</a>
                        </li>
                    @endif
                    @if (hasPermission('location_manager.areas.index'))
                        <li><a href="{{ route('location_manager.areas.index') }}"
                                class="{{ request()->routeIs('location_manager.areas.*') ? 'active' : '' }}">
                                <span class="nav-icon uil uil-map" style="margin-right: 20px;"></span>
                                {{ t_('areas') }}</a>
                        </li>
                    @endif
                   

                    {{-- @if (hasPermission('location_manager.location-types.index'))
                        <li><a href="{{ route('location_manager.location-types.index') }}"
                                class="{{ request()->routeIs('location_manager.location-types.*') ? 'active' : '' }}">
                                <span class="nav-icon fa fa-cog"></span>
                                {{ t_('location types') }}</a>
                        </li>
                    @endif --}}

                </ul>
            </li>
        @endif