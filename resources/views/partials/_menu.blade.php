<div class="sidebar__menu-group">
    <style>
        /* Modern Aesthetic Sidebar Overrides */
        .sidebar_nav {
            padding: 15px;
            background: #ffffff;
        }

        .sidebar_nav li {
            margin-bottom: 5px;
        }

        /* Top Level Links */
        .sidebar_nav li > a {
            display: flex;
            align-items: center;
            padding: 12px 16px;
            border-radius: 12px;
            color: #64748b;
            font-weight: 500;
            font-size: 14px;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            text-decoration: none;
            position: relative;
        }

        .sidebar_nav li > a:hover {
            background: #f1f5f9;
            color: #38ca5f;
        }

        /* Active State with Gradient */
        .sidebar_nav li > a.active {
            background: linear-gradient(135deg, #63f1bd 0%, #52d28c 100%);
            color: #169246 !important;
            box-shadow: 0 4px 12px rgba(99, 102, 241, 0.3);
        }

        .sidebar_nav li > a.active .nav-icon {
            color: #de8989;
        }

        .sidebar_nav li > a .nav-icon {
            font-size: 18px;
            margin-right: 12px;
            transition: color 0.3s ease;
        }

        /* Submenu Styling */
        .sidebar_nav ul {
            padding-left: 24px;
            margin-top: 4px;
            list-style: none;
        }

        .sidebar_nav ul li a {
            padding: 8px 16px;
            font-size: 13px;
            color: #94a3b8;
            border-radius: 8px;
            display: block;
            transition: all 0.2s ease;
        }

        .sidebar_nav ul li a:hover {
            color: #4338ca;
            background: rgba(99, 102, 241, 0.05);
        }

        .sidebar_nav ul li a.active {
            color: #4338ca;
            background: #eef2ff;
            font-weight: 600;
        }

        /* Toggle Icon (Arrow) */
        .toggle-icon {
            margin-left: auto;
            font-size: 12px;
            transition: transform 0.3s ease;
        }

        .open > a > .toggle-icon {
            transform: rotate(180deg);
        }

        /* Group Titles */
        .menu-title {
            padding: 24px 16px 8px;
            font-size: 11px;
            font-weight: 700;
            color: #cbd5e1;
            text-transform: uppercase;
            letter-spacing: 1.5px;
        }

        /* Settings specific layout */
        .nav-icon.fas {
            width: 20px;
            text-align: center;
        }
    </style>

    <ul class="sidebar_nav">
        @foreach (app('modules') as $module)
            @include($module['sidebarView'])
        @endforeach

        @if (hasPermission('access_control.*') || hasPermission('sms.*') || hasPermission('notifications.*') || hasPermission('history.*') || hasPermission('verification.*'))
            
            <li class="menu-title"><span>Administration</span></li>

            <li class="has-child {{ request()->routeIs('access_control.*') || request()->routeIs('sms.*') || request()->routeIs('notifications.*') || request()->routeIs('history.*') || request()->routeIs('verification.*') ? 'open' : '' }}">
                <a href="#" class="{{ request()->routeIs('access_control.*') || request()->routeIs('sms.*') || request()->routeIs('notifications.*') || request()->routeIs('history.*') || request()->routeIs('verification.*') ? 'active' : '' }}">
                    <span class="nav-icon fas fa-cogs"></span>
                    <span class="menu-text">{{ t_('menu.Settings') }}</span>
                    <span class="toggle-icon uil uil-angle-down"></span>
                </a>
                
                <ul>
                    @if (hasPermission('sms.*'))
                        <li class="has-subchild {{ request()->routeIs('sms.*') ? 'open' : '' }}">
                            <a href="#" class="{{ request()->routeIs('sms.*') ? 'active' : '' }}">
                                <span class="nav-icon uil uil-envelope"></span>
                                <span class="menu-text">{{ t_('menu.SMS') }}</span>
                                <span class="toggle-icon uil uil-angle-down"></span>
                            </a>
                            <ul>
                                @if (hasPermission('sms.templates.index'))
                                    <li><a href="{{ route('sms.templates.index') }}" class="{{ request()->routeIs('sms.templates.*') ? 'active' : '' }}">{{ t_('menu.sms-templates') }}</a></li>
                                @endif
                                @if (hasPermission('sms.service-names.index'))
                                    <li><a href="{{ route('sms.service-names.index') }}" class="{{ request()->routeIs('sms.service-names.*') ? 'active' : '' }}">{{ t_('menu.service-names') }}</a></li>
                                @endif
                                @if (hasPermission('sms.trigger-names.index'))
                                    <li><a href="{{ route('sms.trigger-names.index') }}" class="{{ request()->routeIs('sms.trigger-names.*') ? 'active' : '' }}">{{ t_('menu.trigger-names') }}</a></li>
                                @endif
                            </ul>
                        </li>
                    @endif

                    @if (hasPermission('access_control.branchs.*'))
                        <li class="has-subchild {{ request()->routeIs('access_control.branchs.*') || request()->routeIs('access_control.branch-types.*') ? 'open' : '' }}">
                            <a href="#" class="{{ request()->routeIs('access_control.branchs.*') || request()->routeIs('access_control.branch-types.*') ? 'active' : '' }}">
                                <span class="nav-icon uil uil-users-alt"></span>
                                <span class="menu-text">{{ t_('menu.branches') }}</span>
                                <span class="toggle-icon uil uil-angle-down"></span>
                            </a>
                            <ul>
                                @if (hasPermission('access_control.branchs.index'))
                                    <li><a href="{{ route('access_control.branchs.index') }}" class="{{ request()->routeIs('access_control.branchs.*') ? 'active' : '' }}">{{ t_('menu.branch-menu-title') }}</a></li>
                                @endif
                                @if(hasPermission('access_control.branch-types.index'))
                                    <li><a href="{{ route('access_control.branch-types.index') }}" class="{{ request()->routeIs('access_control.branch-types.*') ? 'active' : '' }}">{{ t_('menu.branch-type-menu-title') }}</a></li>
                                @endif
                            </ul>
                        </li>
                    @endif

                    @if (hasPermission('access_control.roles.*'))
                        <li class="has-subchild {{ request()->routeIs('access_control.roles.*') ? 'open' : '' }}">
                            <a href="#" class="{{ request()->routeIs('access_control.roles.*') ? 'active' : '' }}">
                                <span class="nav-icon uil uil-shield-check"></span>
                                <span class="menu-text">{{ t_('menu.access-control-menu-title') }}</span>
                                <span class="toggle-icon uil uil-angle-down"></span>
                            </a>
                            <ul>
                                @if (hasPermission('access_control.roles.create'))
                                    <li><a href="{{ route('access_control.roles.create') }}" class="{{ request()->routeIs('access_control.roles.create') ? 'active' : '' }}">{{ t_('menu.create-role-menu-title') }}</a></li>
                                @endif
                                @if (hasPermission('access_control.roles.index'))
                                    <li><a href="{{ route('access_control.roles.index') }}" class="{{ request()->routeIs('access_control.roles.index') ? 'active' : '' }}">{{ t_('menu.role-list-menu-title') }}</a></li>
                                @endif
                            </ul>
                        </li>
                    @endif

                    @if (hasPermission('notifications.*'))
                        <li>
                            <a href="{{ route('notifications.general-notifications.index') }}" class="{{ request()->routeIs('notifications.general-notifications.*') ? 'active' : '' }}">
                                <span class="nav-icon uil uil-bell"></span>
                                <span class="menu-text">{{ t_('menu.general-notification-menu-title') }}</span>
                            </a>
                        </li>
                    @endif

                    @if (hasPermission('verification.verification-requests'))
                        <li>
                            <a href="{{ route('verification.verification-requests') }}" class="{{ request()->routeIs('verification.verification-requests') ? 'active' : '' }}">
                                <span class="nav-icon uil uil-file-alt"></span>
                                <span class="menu-text">{{ t_('menu.verification-requests') }}</span>
                            </a>
                        </li>
                    @endif

                    @if (hasPermission('history.*'))
                        <li>
                            <a href="{{ route('history.user-log-histories.index') }}" class="{{ request()->routeIs('history.user-log-histories.*') ? 'active' : '' }}">
                                <span class="nav-icon uil uil-clock"></span>
                                <span class="menu-text">{{ t_('menu.user-log-history-menu-title') }}</span>
                            </a>
                        </li>
                    @endif

                    @if (hasPermission('access_control.global-settings.*'))
                        <li>
                            <a href="{{ route('access_control.global-settings.edit', 1) }}" class="{{ request()->routeIs('access_control.global-settings.*') ? 'active' : '' }}">
                                <span class="nav-icon uil uil-cog"></span>
                                <span class="menu-text">{{ t_('Global Settings') }}</span>
                            </a>
                        </li>
                    @endif
                </ul>
            </li>
        @endif
    </ul>
</div>