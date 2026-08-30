<li class="has-child {{ request()->routeIs('import.*') ? 'open' : '' }}">

    <a href="#"
       class="{{ request()->routeIs('import.*') ? 'active' : '' }}">
        <span class="nav-icon fas fa-chart-line"></span>
        <span class="menu-text">Import</span>
        <span class="toggle-icon"></span>
    </a>

    <ul>
        <li>
            <a href="{{ route('import.purchase-orders.index') }}">
                <span class="menu-text">Purchase Orders</span>
            </a>
        </li>
    </ul>

</li>