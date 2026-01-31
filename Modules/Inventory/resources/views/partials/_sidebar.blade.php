 {{-- inventory inv --}}
 @if (hasPermission('inv.*'))
     <li class="has-child {{ request()->routeIs('inv.*') ? 'open' : '' }}">
         <a href="#" class="{{ request()->routeIs('inv.*') ? 'active' : '' }}">
             <span class="nav-icon fas fa-box-open"></span>
             <span class="menu-text">{{ t_('menu.inventory-menu-title') }}</span>
             <span class="toggle-icon"></span>
         </a>
         <ul>
             {{-- @if (hasPermission('inv.brands.*'))
             <li
                 class="has-subchild {{ request()->routeIs('inv.brands.*') || request()->routeIs('inv.product-types.*') ? 'open' : '' }}">
                 <a href="#"
                     class="{{ request()->routeIs('inv.brands.*') || request()->routeIs('inv.product-types.*') ? 'active' : '' }}">
                     <span class="nav-icon fa fa-cog"></span>
                     <span class="menu-text">{{ t_('menu.Product Settings') }}</span>
                     <span class="toggle-icon"></span>
                 </a>
                 <ul>
                     
                 </ul>
             </li>
         @endif --}}



             {{-- @if ('inv.product-catalogs.*') --}}
             {{-- <li class="has-subchild {{ request()->routeIs('inv.product-catalogs.*') ? 'open' : '' }}">
                 <a href="#"
                     class="{{ request()->routeIs('inv.product-catalogs.*') ? 'active' : '' }}">
                     <span class="nav-icon uil uil-box"></span>
                     <span class="menu-text">{{ t_('menu.product-catalog') }}</span>
                     <span class="toggle-icon"></span>
                 </a>
                 <ul>
                     @if (hasPermission('inv.product-catalogs.create'))
                         <li><a href="{{ route('inv.product-catalogs.create') }}"
                                 class="{{ request()->routeIs('inv.product-catalogs.*') ? 'active' : '' }}">
                                 <span class="fas fa-plus" style="margin-right: 20px;"></span>
                                 {{ t_('menu.product-catalog-create-menu-title') }}</a>
                         </li>
                     @endif
                     @if (hasPermission('inv.product-catalogs.index'))
                         <li><a href="{{ route('inv.product-catalogs.index') }}"
                                 class="{{ request()->routeIs('inv.product-catalogs.*') ? 'active' : '' }}">
                                 <span class="fas fa-clipboard-list" style="margin-right: 20px;"></span>
                                 {{ t_('menu.product-catalog-list-menu-title') }}</a>
                         </li>
                     @endif
                 </ul>
             </li> --}}
             @if (hasPermission('inv.product-catalogs.*'))
                 <li>
                     <a href="{{ route('inv.product-catalogs.index') }}"
                         class="{{ request()->routeIs('inv.product-catalogs.*') ? 'active' : '' }}">
                         <span class="nav-icon fas fa-book-reader"></span>
                         <span class="menu-text">{{ t_('menu.product-catalog-menu-title') }}</span>
                     </a>
                 </li>
             @endif

             {{-- @if (hasPermission('inv.products.*'))
             <li class="has-subchild {{ request()->routeIs('inv.products.*') ? 'open' : '' }}">
                 <a href="#" class="{{ request()->routeIs('inv.products.*') ? 'active' : '' }}">
                     <span class="nav-icon uil-create-dashboard"></span>
                     <span class="menu-text">{{ t_('menu.product-menu-title') }}</span>
                     <span class="toggle-icon"></span>
                 </a>
                 <ul>
                     @if (hasPermission('inv.products.create'))
                         <li><a href="{{ route('inv.products.create') }}"
                                 class="{{ request()->routeIs('inv.products.*') ? 'active' : '' }}">
                                 <span class="fas fa-plus" style="margin-right: 20px;"></span>
                                 {{ t_('menu.product-create-menu-title') }}</a>
                         </li>
                     @endif
                     @if (hasPermission('inv.products.index'))
                         <li><a href="{{ route('inv.products.index') }}"
                                 class="{{ request()->routeIs('inv.products.*') ? 'active' : '' }}">
                                 <span class="fas fa-clipboard-list" style="margin-right: 20px;"></span>
                                 {{ t_('menu.product-list-menu-title') }}</a>
                         </li>
                     @endif
                 </ul>
             </li>
         @endif --}}
             {{-- @if (hasPermission('inv.issue-products.*'))
             <li><a href="{{ route('inv.issue-products.index') }}"
                     class="{{ request()->routeIs('inv.issue-products.*') ? 'active' : '' }}">
                     <span class="nav-icon fas fa-check-square" style="margin-right: 20px;"></span>
                     {{ t_('menu.issue-product-menu-title') }}</a>
             </li>
         @endif --}}
             @if (hasPermission('inv.product-transfer-requests.*'))
                 <li><a href="{{ route('inv.product-transfer-requests.index') }}"
                         class="{{ request()->routeIs('inv.product-transfer-requests.*') ? 'active' : '' }}">
                         <span class="nav-icon fas fa-exchange-alt" style="margin-right: 20px;"></span>
                         {{ t_('menu.product-transfer-request-menu-title') }}</a>
                 </li>
             @endif
             @if (hasPermission('inv.product-transfers.*'))
                 <li><a href="{{ route('inv.product-transfers.index') }}"
                         class="{{ request()->routeIs('inv.product-transfers.*') ? 'active' : '' }}">
                         <span class="nav-icon fas fa-exchange-alt" style="margin-right: 20px;"></span>
                         {{ t_('menu.product-transfer-menu-title') }}</a>
                 </li>
             @endif
             @if (hasPermission('inv.offers.*'))
                 {{-- <li class="has-subchild {{ request()->routeIs('inv.offers.*') ? 'open' : '' }}">
                 <a href="#" class="{{ request()->routeIs('inv.offers.*') ? 'active' : '' }}">
                     <span class="nav-icon fa fa-gift"></span>
                     <span class="menu-text">{{ t_('menu.Gift/Offers') }}</span>
                     <span class="toggle-icon"></span>
                 </a>
                 <ul>
                     @if (hasPermission('inv.offers.create'))
                         <li><a href="{{ route('inv.offers.create') }}"
                                 class="{{ request()->routeIs('inv.offers.*') ? 'active' : '' }}">
                                 <span class="fas fa-plus" style="margin-right: 20px;"></span>
                                 {{ t_('menu.offer-create-menu-title') }}</a>
                         </li>
                     @endif
                     @if (hasPermission('inv.offers.index'))
                         <li><a href="{{ route('inv.offers.index') }}"
                                 class="{{ request()->routeIs('inv.offers.*') ? 'active' : '' }}">
                                 <span class="nav-icon fa fa-gift" style="margin-right: 20px;"></span>
                                 {{ t_('menu.offer-list-menu-title') }}</a>
                         </li>
                     @endif
                 </ul>
             </li> --}}
                 <li><a href="{{ route('inv.offers.index') }}"
                         class="{{ request()->routeIs('inv.offers.*') ? 'active' : '' }}">
                         <span class="nav-icon fas fa-gifts" style="margin-right: 20px;"></span>
                         {{ t_('menu.offer-menu-title') }}</a>
                 </li>
             @endif
             @if (hasPermission('inv.products.price-list'))
                 <li>
                     <a href="{{ route('inv.products.price-list') }}"
                         class="{{ request()->routeIs('inv.products.price-list') ? 'active' : '' }}">
                         <span class="nav-icon fas fa-tags" style="margin-right: 20px;"></span>
                         {{ t_('menu.product-price-list-menu-title') }}</a>
                 </li>
             @endif
             @if (hasPermission('inv.stocks.*'))
                 <li>
                     <a href="{{ route('inv.stocks.stocks-in-hand') }}"
                         class="{{ request()->routeIs('inv.stocks.*') ? 'active' : '' }}">
                         <span class="nav-icon fas fa-boxes" style="margin-right: 20px;"></span>
                         {{ t_('menu.stock-menu-title') }}</a>
                 </li>
             @endif


             @if (hasPermission('inv.reports.*'))
                 <li class="has-subchild {{ request()->routeIs('inv.reports.*') ? 'open' : '' }}">
                     <a href="#" class="{{ request()->routeIs('inv.reports.*') ? 'active' : '' }}">
                         <span class="nav-icon uil uil-chart-bar"></span>
                         <span class="menu-text">{{ t_('menu.Reports') }}</span>
                         <span class="toggle-icon"></span>
                     </a>
                     <ul>
                         @if (hasPermission('inv.reports.product-stock'))
                             <li><a href="{{ route('inv.reports.product-stock') }}"
                                     class="has-subchild {{ request()->routeIs('inv.reports.product-stock') ? 'active' : '' }}">
                                     <span class="nav-icon fa fa-chart-line" style="margin-right: 20px;"></span>
                                     {{ t_('menu.product wise stock reports') }}</a>
                             </li>
                         @endif
                         @if (hasPermission('inv.reports.product-transfer'))
                             <li><a href="{{ route('inv.reports.product-transfer') }}"
                                     class="has-subchild {{ request()->routeIs('inv.reports.product-transfer') ? 'active' : '' }}">
                                     <span class="nav-icon fas fa-file-import" style="margin-right: 20px;"></span>
                                     {{ t_('menu.product transfer reports') }}</a>
                             </li>
                         @endif
                         @if (hasPermission('inv.reports.center-stock'))
                             <li><a href="{{ route('inv.reports.center-stock') }}"
                                     class="has-subchild {{ request()->routeIs('inv.reports.center-stock') ? 'active' : '' }}">
                                     <span class="nav-icon fas fa-file-import" style="margin-right: 20px;"></span>
                                     {{ t_('menu.Center Wise Stock Report') }}</a>
                             </li>
                         @endif

                         @if(hasPermission('inv.reports.catalogue-report'))
                             <li><a href="{{ route('inv.reports.catalogue-report') }}"
                                     class="has-subchild {{ request()->routeIs('inv.reports.catalogue-report') ? 'active' : '' }}">
                                     <span class="nav-icon fas fa-file-image" style="margin-right: 20px;"></span>
                                     {{ t_('menu.catalogue reports') }}</a>
                             </li>
                         @endif
                        @if(hasPermission('inv.reports.stock-balance'))
                            <li><a href="{{ route('inv.reports.stock-balance') }}"
                                    class="has-subchild {{ request()->routeIs('inv.reports.stock-balance') ? 'active' : '' }}">
                                    <span class="nav-icon fas fa-file-image" style="margin-right: 20px;"></span>
                                    {{ t_('menu.Stock Balance Report with Costing & Sales Value') }}</a>
                            </li>
                        @endif

                     </ul>
                 </li>
             @endif


             @if (hasPermission('inv.settings.*') || hasPermission('inv.product-types.*') || hasPermission('inv.brands.*'))
                 <li
                     class="has-subchild {{ request()->routeIs('inv.settings.*') || request()->routeIs('inv.product-types.*') || request()->routeIs('inv.brands.*') ? 'open' : '' }}">
                     <a href="#"
                         class="{{ request()->routeIs('inv.settings.*') || request()->routeIs('inv.product-types.*') || request()->routeIs('inv.brands.*') ? 'active' : '' }}">
                         <span class="nav-icon fa fa-cog"></span>
                         <span class="menu-text">{{ t_('menu.Inventory Settings') }}</span>
                         <span class="toggle-icon"></span>
                     </a>
                     <ul>
                         {{-- @if (hasPermission('inv.settings.approvers.index'))
                         <li>
                             <a href="{{ route('inv.settings.approvers.index') }}">
                                 <span class="fas fa-user-tie"
                                     style="margin-right: 20px;"></span>{{ t_('menu.inventory-settings-approver-menu-title') }}</a>
                         </li>
                     @endif --}}
                         @if (hasPermission('inv.settings.units.index'))
                             <li><a href="{{ route('inv.settings.units.index') }}"
                                     class="{{ request()->routeIs('inv.settings.units.*') ? 'active' : '' }}">
                                     <span class="fas fa-dice-three" style="margin-right: 21px;"></span>
                                     {{ t_('menu.inventory-settings-unit-menu-title') }}</a>
                             </li>
                         @endif
                         @if (hasPermission('inv.settings.tags.index'))
                             <li><a href="{{ route('inv.settings.tags.index') }}"
                                     class="{{ request()->routeIs('inv.settings.tags.*') ? 'active' : '' }}">
                                     <span class="fas fa-tags" style="margin-right: 21px;"></span>
                                     {{ t_('menu.inventory-settings-tag-menu-title') }}</a>
                             </li>
                         @endif
                         @if (hasPermission('inv.product-types.index'))
                             <li><a href="{{ route('inv.product-types.index') }}"
                                     class="{{ request()->routeIs('inv.product-types.*') ? 'active' : '' }}">
                                     <span class="fas fa-shapes" style="margin-right: 20px;"></span>
                                     {{ t_('menu.product-types') }}</a>
                             </li>
                         @endif
                         @if (hasPermission('inv.brands.index'))
                             <li><a href="{{ route('inv.brands.index') }}"
                                     class="{{ request()->routeIs('inv.brands.*') ? 'active' : '' }}">
                                     <span class="fas fa-tshirt" style="margin-right: 20px;"></span>
                                     {{ t_('menu.brands') }}</a>
                             </li>
                         @endif
                     </ul>
                 </li>
             @endif
         </ul>
     </li>
 @endif
