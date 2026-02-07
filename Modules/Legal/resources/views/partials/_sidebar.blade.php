@if (hasPermission('legal.*'))
            <li class="has-child {{ request()->routeIs('legal.*') ? 'open' : '' }}">
                <a href="#" class="{{ request()->routeIs('legal.*') ? 'active' : '' }}">
                    <span class="nav-icon fas fa-balance-scale"></span>
                    <span class="menu-text">{{ t_('menu.legal') }}</span>
                    <span class="toggle-icon"></span>
                </a>
                <ul>
                    @if (hasPermission('legal.legal-entries.*'))
                        <li>
                            <a href="{{ route('legal.legal-entries.create') }}"
                                class="{{ request()->routeIs('legal.legal-entries.*') ? 'active' : '' }}">
                                <span class="nav-icon fas fa-file-alt" style="margin-right: 20px;"></span>
                                {{ t_('menu.Legal Entries') }}
                            </a>
                        </li>
                    @endif
                    @if (hasPermission('legal.legal-entries.legal-schedule-update'))
                        <li>
                            <a href="{{ route('legal.get-legal-details') }}"
                                class="{{ request()->routeIs('legal.get-legal-details') ? 'active' : '' }}">
                                <span class="nav-icon fas fa-calendar-check" style="margin-right: 20px;"></span>
                                {{ t_('menu.Legal Schedule Update') }}
                            </a>
                        </li>
                    @endif
                    @if (hasPermission('legal.legal-bill-entries.*'))
                        <li>
                            <a href="{{ route('legal.legal-bill-entries.index') }}"
                                class="{{ request()->routeIs('legal.legal-bill-entries.*') ? 'active' : '' }}">
                                <span class="nav-icon fas fa-receipt" style="margin-right: 20px;"></span>
                                {{ t_('menu.Legal Bill Entries') }}
                            </a>
                        </li>
                    @endif
                    @if (hasPermission('legal.legal-entries.reports'))
                        <li>
                            <a href="{{ route('legal.reports') }}"
                                class="{{ request()->routeIs('legal.reports') ? 'active' : '' }}">
                                <span class="nav-icon fas fa-file-excel" style="margin-right: 20px;"></span>
                                {{ t_('menu.Legal Report') }}
                            </a>
                        </li>
                    @endif

                   
                </ul> 
            </li>
        @endif