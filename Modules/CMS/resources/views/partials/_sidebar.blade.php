@if (hasPermission('cms.*'))
            <li class="has-child {{ request()->routeIs('cms.*') ? 'open' : '' }}">
                <a href="#" class="{{ request()->routeIs('cms.*') ? 'active' : '' }}">
                    <span class="nav-icon fas fa-newspaper"></span>
                    <span class="menu-text">{{ t_('menu.CMS') }}</span>
                    <span class="toggle-icon"></span>
                </a>
                <ul>
                @if (hasPermission('cms.document-entries.create'))
                    <li>
                        <a href="{{ route('cms.document-entries.create') }}"
                            class="{{ request()->routeIs('cms.document-entries.*') ? 'active' : '' }}">
                            <span class="fas fa-edit" style="margin-right: 20px;"></span>
                            {{ t_('Document Entries') }}
                        </a>
                    </li>
                @endif
                
                @if (hasPermission('cms.application-entries.create'))
                    <li>
                        <a href="{{ route('cms.application-entries.create') }}"
                            class="{{ request()->routeIs('cms.application-entries.*') ? 'active' : '' }}">
                            <span class="fas fa-file-alt" style="margin-right: 20px;"></span>
                            {{ t_('Application create') }}
                        </a>
                    </li>
                @endif 
                @if (hasPermission('cms.document-entries.document-reports'))
                    <li class="{{ request()->routeIs('cms.document-reports.*') ? 'active' : '' }}">
                        <a href="{{ route('cms.document-entries.document-reports') }}" 
                        class="d-flex align-items-center">
                            <i class="fas fa-file-invoice" style="width: 20px; margin-right: 15px;"></i>
                            <span>{{ t_('Document Report') }}</span>
                        </a>
                    </li>
                @endif
                @if (hasPermission('cms.document-types.*') || hasPermission('cms.document-heads.*'))
                    <li
                        class="has-subchild {{ request()->routeIs('cms.document-types.*') || request()->routeIs('cms.document-heads.*')  ? 'open' : '' }}">
                        <a href="#"
                            class="{{ request()->routeIs('cms.document-types.*') || request()->routeIs('cms.document-heads.*')  ? 'active' : '' }}">
                            <span class="nav-icon uil uil-atom"></span>
                            <span class="menu-text">{{ t_('menu.CMS settings') }}</span>
                            <span class="toggle-icon"></span>
                        </a>
                        <ul>
                    @if (hasPermission('cms.document-types.index'))
                        <li>
                            <a href="{{ route('cms.document-types.index') }}"
                                class="{{ request()->routeIs('cms.document-types.*') ? 'active' : '' }}">
                                <span class="fas fa-file-alt" style="margin-right: 20px;"></span>
                                {{ t_('Document types') }}
                            </a>
                        </li>
                    @endif
                    @if (hasPermission('cms.document-heads.index'))
                        <li>
                            <a href="{{ route('cms.document-heads.index') }}"
                                class="{{ request()->routeIs('cms.document-heads.*') ? 'active' : '' }}">
                                <span class="fas fa-folder-open" style="margin-right: 20px;"></span>
                                {{ t_('Document heads') }}
                            </a>
                        </li>
                    @endif 
                    
                </ul>
            </li>
        @endif
                </ul> 
            </li>
        @endif