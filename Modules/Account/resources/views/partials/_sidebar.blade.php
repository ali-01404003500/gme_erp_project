@php
    $hasAccountSetupPermission = hasAnyPermission(['account.account-setup.*']);
    $hasEMIPermission = hasAnyPermission(['account.emi-entries.*']);
    $hasVoucherPermission = hasAnyPermission([
        'account.voucher-payments.index',
        'account.voucher-receives.index',
        'account.voucher-contras.index',
        'account.voucher-journals.index',
    ]);
    $hasProductPermission = hasAnyPermission([
        'account.account-products.index',
        'account.account-units.index',
        'account.account-categories.index',
    ]);
    $hasPartyPermission = hasAnyPermission(['account.account-customers.index', 'account.account-suppliers.index']);
    $hasSalePurchasePermission =
        hasPermission('account.account-purchases.index') || hasPermission('account.account-sales.index');
@endphp
@if (hasPermission('account.*'))
    <li class="has-child {{ request()->routeIs('account.*') ? 'open' : '' }}">
        <a href="#" class="{{ request()->routeIs('account.*') ? 'active' : '' }}">
            <span class="nav-icon fas fa-chart-line"></span>
            <span class="menu-text">{{ t_('menu.accounting') }}</span>
            <span class="toggle-icon"></span>
        </a>
        <ul>
            @if ($hasAccountSetupPermission)
                <li class="has-subchild {{ request()->routeIs('account.account-setup.*') ? 'open' : '' }}">
                    <a href="#" class="{{ request()->routeIs('account.account-setup.*') ? 'active' : '' }}">
                        <span class="nav-icon fas fa-cog"></span>
                        <span class="menu-text">{{ t_('menu.account-setup-menu-title') }}</span>
                        <span class="toggle-icon"></span>
                    </a>
                    <ul>
                        @if (hasPermission('account.account-setup.account-groups.index'))
                            <li>
                                <a href="{{ route('account.account-setup.account-groups.index') }}"
                                    class="{{ request()->routeIs('account.account-setup.account-groups.*') ? 'active' : '' }}">
                                    <span class="nav-icon fas fa-layer-group"></span>
                                    {{ t_('menu.account group') }}
                                </a>
                            </li>
                        @endif

                        @if (hasPermission('account.account-setup.account-controls.index'))
                            <li>
                                <a href="{{ route('account.account-setup.account-controls.index') }}"
                                    class="{{ request()->routeIs('account.account-setup.account-controls.*') ? 'active' : '' }}">
                                    <span class="nav-icon fas fa-sliders-h"></span>
                                    {{ t_('menu.account control') }}
                                </a>
                            </li>
                        @endif

                        @if (hasPermission('account.account-setup.account-subsidiaries.index'))
                            <li>
                                <a href="{{ route('account.account-setup.account-subsidiaries.index') }}"
                                    class="{{ request()->routeIs('account.account-setup.account-subsidiaries.*') ? 'active' : '' }}">
                                    <span class="nav-icon fas fa-sitemap"></span>
                                    {{ t_('menu.acc subsidiary') }}
                                </a>
                            </li>
                        @endif

                        @if (hasPermission('account.account-setup.accounts.index'))
                            <li>
                                <a href="{{ route('account.account-setup.accounts.index') }}"
                                    class="{{ request()->routeIs('account.account-setup.accounts.*') ? 'active' : '' }}">
                                    <span class="nav-icon fas fa-chart-line"></span>
                                    {{ t_('menu.account charts') }}
                                </a>
                            </li>
                        @endif

                        {{-- @if (hasPermission('account.account-setup.accounts.index'))
                        <li>
                            <a href="{{ route('account.account-setup.account-opening-balances.create') }}"
                                class="{{ request()->routeIs('account.account-opening-balances.*') ? 'active' : '' }}">
                                <span class="nav-icon fas fa-balance-scale"></span>
                                {{ t_('menu.opening balance') }}
                            </a>
                        </li>
                        @endif --}}


                        @if (hasPermission('account.account-setup.banks.index'))
                            <li>
                                <a href="{{ route('account.account-setup.banks.index') }}"
                                    class="{{ request()->routeIs('account.account-setup.banks.*') ? 'active' : '' }}">
                                    <span class="nav-icon fas fa-university"></span>
                                    {{ t_('menu.account-setup-banks-menu-title') }}
                                </a>
                            </li>
                        @endif

                        @if (hasPermission('account.account-setup.bank-branches.index'))
                            <li>
                                <a href="{{ route('account.account-setup.bank-branches.index') }}"
                                    class="{{ request()->routeIs('account.account-setup.bank-branches.*') ? 'active' : '' }}">
                                    <span class="nav-icon fas fa-building"></span>
                                    {{ t_('menu.account-setup-bank-branches-menu-title') }}
                                </a>
                            </li>
                        @endif
                        @if (hasPermission('account.account-setup.bank-accounts.index'))
                            <li>
                                <a href="{{ route('account.account-setup.bank-accounts.index') }}"
                                    class="{{ request()->routeIs('account.account-setup.bank-accounts.*') ? 'active' : '' }}">
                                    <span class="nav-icon fas fa-piggy-bank"></span>
                                    {{ t_('menu.account-setup-bank-accounts-menu-title') }}
                                </a>
                            </li>
                        @endif



                    </ul>
                </li>
            @endif


            {{-- @if ($hasAccountSetupPermission)
            <li class="has-subchild {{ request()->routeIs('account.account-settings.*') ? 'open' : '' }}">
                <a href="#" class="{{ request()->routeIs('account.account-settings.*') ? 'active' : '' }}">
                    <span class="nav-icon fas fa-cog"></span>
                    <span class="menu-text">{{ t_('menu.account-settings-menu-title') }}</span>
                    <span class="toggle-icon"></span>
                </a>
                <ul>
                    @if (hasPermission('account.account-settings.default-payable-receivables.create'))
                    <li>
                        <a href="{{ route('account.account-settings.default-payable-receivables.create') }}"
                            class="{{ request()->routeIs('account.account-settings.default-payable-receivables.*') ? 'active' : '' }}">
                            <span class="nav-icon fas fa-layer-group"></span>
                            {{ t_('menu.default payable & receivables') }}
                        </a>
                    </li>
                    @endif
                </ul>
            </li>
            @endif --}}

            {{-- @if (hasPermission('account.payments.customer-payments.index'))
            <li class="has-subchild {{ request()->routeIs('account.payments.customer-payments.*') ? 'open' : '' }}">
                <a href="#" class="{{ request()->routeIs('account.payments.customer-payments.*') ? 'active' : '' }}">
                    <span class="nav-icon fas fa-money-bill-wave"></span>
                    <span class="menu-text">{{ t_('menu.payments_menu_title') }}</span>
                    <span class="toggle-icon"></span>
                </a>
                <ul>
                    @if (hasPermission('account.payments.customer-payments.index'))
                    <li>
                        <a href="{{ route('account.payments.customer-payments.index') }}"
                            class="{{ request()->routeIs('account.payments.customer-payments.*') ? 'active' : '' }}">
                            <span class="nav-icon fas fa-money-check"></span>
                            {{ t_('menu.customer_payments_menu_title') }}
                        </a>
                    </li>
                    @endif

                    @if (hasPermission('account.payments.supplier-payments.index'))
                    <li>
                        <a href="{{ route('account.payments.supplier-payments.index') }}"
                            class="{{ request()->routeIs('account.payments.supplier-payments.*') ? 'active' : '' }}">
                            <span class="nav-icon fas fa-hand-holding-usd"></span>
                            {{ t_('menu.supplier payments') }}
                        </a>
                    </li>
                    @endif
                </ul>
            </li>
            @endif --}}

            @if ($hasVoucherPermission)
                <li
                    class="has-subchild {{ request()->routeIs('account.voucher-payments.*') || request()->routeIs('account.voucher-receives.*') || request()->routeIs('account.voucher-contras.*') || request()->routeIs('account.voucher-journals.*') ? 'open' : '' }}">
                    <a href="#"
                        class="{{ request()->routeIs('account.voucher-payments.*') || request()->routeIs('account.voucher-receives.*') || request()->routeIs('account.voucher-contras.*') || request()->routeIs('account.voucher-journals.*') ? 'active' : '' }}">
                        <span class="nav-icon fas fa-receipt"></span>
                        <span class="menu-text">{{ t_('menu.vouchers') }}</span>
                        <span class="toggle-icon"></span>
                    </a>
                    <ul>
                        <!-- Payment Voucher -->
                        @if (hasPermission('account.voucher-payments.create'))
                            <li>
                                <a href="{{ route('account.voucher-payments.create') }}"
                                    class="{{ request()->routeIs('account.voucher-payments.*') ? 'active' : '' }}">
                                    <span class="nav-icon fas fa-money-bill-wave"></span>
                                    {{ t_('menu.payment') }}
                                </a>
                            </li>
                        @endif

                        <!-- Receive Voucher -->
                        @if (hasPermission('account.voucher-receives.create'))
                            <li>
                                <a href="{{ route('account.voucher-receives.create') }}"
                                    class="{{ request()->routeIs('account.voucher-receives.*') ? 'active' : '' }}">
                                    <span class="nav-icon fas fa-hand-holding-usd"></span>
                                    {{ t_('menu.receive') }}
                                </a>
                            </li>
                        @endif

                        <!-- Contra Voucher -->
                        @if (hasPermission('account.voucher-contras.create'))
                            <li>
                                <a href="{{ route('account.voucher-contras.create') }}"
                                    class="{{ request()->routeIs('account.voucher-contras.*') ? 'active' : '' }}">
                                    <span class="nav-icon fas fa-exchange-alt"></span>
                                    {{ t_('menu.contra') }}
                                </a>
                            </li>
                        @endif

                        <!-- Journal Voucher -->
                        @if (hasPermission('account.voucher-journals.create'))
                            <li>
                                <a href="{{ route('account.voucher-journals.create') }}"
                                    class="{{ request()->routeIs('account.voucher-journals.*') ? 'active' : '' }}">
                                    <i class="nav-icon fas fa-book"></i>
                                    {{ t_('menu.journal') }}
                                </a>
                            </li>
                        @endif
                    </ul>
                </li>
            @endif
            @if ($hasEMIPermission)
                <li
                    class="has-subchild {{ request()->routeIs('account.emi-entries.*') || request()->routeIs('account.advance-cheque-entries.*') || request()->routeIs('account.emi-reports.*') ? 'open' : '' }}">
                    <a href="#"
                        class="{{ request()->routeIs('account.emi-entries.*') || request()->routeIs('account.advance-cheque-entries.*') || request()->routeIs('account.emi-reports.*') ? 'active' : '' }}">
                        <span class="nav-icon fas fa-piggy-bank"></span>
                        <span class="menu-text">{{ t_('menu.EMI') }}</span>
                        <span class="toggle-icon"></span>
                    </a>
                    <ul>
                        @if (hasPermission('account.emi-entries.create'))
                            <li>
                                <a href="{{ route('account.emi-entries.create') }}"
                                    class="{{ request()->routeIs('account.emi-entries.*') ? 'active' : '' }}">
                                    <span class="nav-icon fas fa-coins"></span>
                                    {{ t_('EMI Entries') }}
                                </a>
                            </li>
                        @endif
                        @if (haspermission('account.advance-cheque-entries.create'))
                            <li>
                                <a href="{{ route('account.advance-cheque-entries.create') }}"
                                    class="{{ request()->routeIs('account.advance-cheque-entries.index') || request()->routeIs('account.advance-cheque-entries.create') ? 'active' : '' }}">
                                    <span class="nav-icon fas fa-file-invoice-dollar"></span>
                                    {{ t_('advance-cheque-entry') }}
                                </a>
                            </li>
                        @endif
                        @if (hasPermission('account.advance-cheque-entries.advance-cheque-collections'))
                            <li>
                                <a href="{{ route('account.advance-cheque-entries.advance-cheque-collections') }}"
                                    class="{{ request()->routeIs('account.advance-cheque-entries.advance-cheque-collections') ? 'active' : '' }}">
                                    <span class="nav-icon fas fa-file-invoice"></span>
                                    {{ t_('advance-cheque-collection') }}
                                </a>
                            </li>
                        @endif
                        @if (hasPermission('account.emi-entries.emi-collections'))
                            <li>
                                <a href="{{ route('account.emi-entries.emi-collections') }}"
                                    class="{{ request()->routeIs('account.emi-entries.emi-collections') ? 'active' : '' }}">
                                    <span class="nav-icon fas fa-cash-register"></span>
                                    {{ t_('emi collections') }}
                                </a>
                            </li>
                        @endif
                        <li class="has-subsubchild {{ request()->routeIs('account.emi-reports.*') ? 'open' : '' }}">
                            <a href="#" class="{{ request()->routeIs('account.emi-reports.*') ? 'active' : '' }}">
                                <span class="nav-icon fas fa-cog"></span> {{-- Changed from fa-piggy-bank --}}
                                <span class="menu-text">{{ t_('menu.emi-reports') }}</span>
                                <span class="toggle-icon"></span>
                            </a>
                            <ul>
                                @if (hasPermission('account.emi-reports.emi-installment-report'))
                                    <li>
                                        <a href="{{ route('account.emi-reports.emi-installment-report') }}"
                                            class="{{ request()->routeIs('account.emi-reports.emi-installment-report') ? 'active' : '' }}">
                                            <span class="nav-icon fas fa-calendar-alt"></span> {{-- Represents installment schedule
                                            --}}
                                            {{ t_('emi installment report') }}
                                        </a>
                                    </li>
                                @endif
                                @if (hasPermission('account.emi-reports.emi-customer-report'))
                                    <li>
                                        <a href="{{ route('account.emi-reports.emi-customer-report') }}"
                                            class="{{ request()->routeIs('account.emi-reports.emi-customer-report') ? 'active' : '' }}">
                                            <span class="nav-icon fas fa-users"></span> {{-- Represents customers --}}
                                            {{ t_('emi customer report') }}
                                        </a>
                                    </li>
                                @endif
                            </ul>
                        </li>




                    </ul>
                </li>
            @endif

            @if (hasPermission('account.cheque-verifications.index'))
                <li>
                    <a href="{{ route('account.cheque-verifications.index') }}"
                        class="{{ request()->routeIs('account.cheque-verifications.index') ? 'active' : '' }}">
                        <span class="nav-icon fas fa-receipt"></span>
                        {{ t_('cheque verifications') }}
                    </a>
                </li>
            @endif



            @if (hasPermission('account.collections.*'))
                <li class="has-subchild {{ request()->routeIs('account.collections.*') ? 'open' : '' }}">
                    <a href="#" class="{{ request()->routeIs('account.collections.*') ? 'active' : '' }}">
                        <span class="nav-icon fas fa-hand-holding-usd"></span>
                        <span class="menu-text">{{ t_('menu.collection') }}</span>
                        <span class="toggle-icon"></span>
                    </a>
                    <ul>
                        @if (hasPermission('account.collections.collections.create'))
                            <li>
                                <a href="{{ route('account.collections.collections.create') }}"
                                    class="{{ request()->routeIs('account.collections.collections.index') ? 'active' : '' }}">
                                    <span class="nav-icon fas fa-coins"></span>
                                    {{ t_('menu.collection-list-menu-title') }}
                                </a>
                            </li>
                        @endif

                        @if (hasPermission('account.collections.invoice-wise-collections.create'))
                            <li>
                                <a href="{{ route('account.collections.invoice-wise-collections.create') }}"
                                    class="{{ request()->routeIs('account.collections.invoice-wise-collections.*') ? 'active' : '' }}">
                                    <span class="nav-icon fas fa-file-invoice"></span>
                                    {{ t_('menu.invoice wise') }}
                                </a>
                            </li>
                        @endif

                    </ul>
                </li>
            @endif


            @if (hasPermission('account.payments.make-payments.index'))
                <li
                    class="has-subchild {{ request()->routeIs('account.payments.*') || request()->routeIs('account.payments.broker-payments.*') ? 'open' : '' }}">
                    <a href="#"
                        class="{{ request()->routeIs('account.payments.make-payments.*') || request()->routeIs('account.payments.broker-payments.*') ? 'active' : '' }}">
                        <span class="nav-icon fas fa-money-bill-wave"></span>
                        <span class="menu-text">{{ t_('menu.make payment') }}</span>
                        <span class="toggle-icon"></span>
                    </a>
                    <ul>
                        @if (hasPermission('account.payments.make-payments.create'))
                            <li>
                                <a href="{{ route('account.payments.make-payments.create') }}"
                                    class="{{ request()->routeIs('account.payments.make-payments.index') ? 'active' : '' }}">
                                    <span class="nav-icon fas fa-list"></span>
                                    {{ t_('menu.payment list') }}
                                </a>
                            </li>
                        @endif
                        @if (hasPermission('account.payments.broker-payments.create'))
                            <li>
                                <a href="{{ route('account.payments.broker-payments.create') }}"
                                    class="{{ request()->routeIs('account.payments.broker-payments.index') ? 'active' : '' }}">
                                    <span class="nav-icon fas fa-coins"></span>
                                    {{ t_('menu.broker payment') }}
                                </a>
                            </li>
                        @endif

                        @if (hasPermission('account.payments.invoice-wise-payments.create'))
                            <li>
                                <a href="{{ route('account.payments.invoice-wise-payments.create') }}"
                                    class="{{ request()->routeIs('account.payments.invoice-wise-payments.*') ? 'active' : '' }}">
                                    <span class="nav-icon fas fa-file-invoice"></span>
                                    {{ t_('menu.invoice-wise-payments') }}
                                </a>
                            </li>
                        @endif
                        @if (hasPermission('account.payments.petty-cash-payments.index'))
                            <li>
                                <a href="{{ route('account.payments.petty-cash-payments.index') }}"
                                    class="{{ request()->routeIs('account.payments.petty-cash-payments.*') ? 'active' : '' }}">
                                    <span class="nav-icon fas fa-file-invoice"></span>
                                    {{ t_('menu.petty-cash-payments') }}
                                </a>
                            </li>
                        @endif
                    </ul>
                </li>
            @endif


            @if (hasPermission('account.vendor-bills.*'))
                <li class="has-subchild {{ request()->routeIs('account.vendor-bills.*') ? 'open' : '' }}">
                    <a href="#" class="{{ request()->routeIs('account.vendor-bills.*') ? 'active' : '' }}">
                        <span class="nav-icon fas fa-cog"></span>
                        <span class="menu-text">{{ t_('menu.vendor-bills') }}</span>
                        <span class="toggle-icon"></span>
                    </a>
                    <ul>
                        @if (hasPermission('account.vendor-bills.settings.index'))
                            <li>
                                <a href="{{ route('account.vendor-bills.settings.index') }}"
                                    class="{{ request()->routeIs('account.vendor-bills.settings.index') ? 'active' : '' }}">
                                    <span class="nav-icon fas fa-cog"></span>
                                    {{ t_('Settings') }}
                                </a>
                            </li>
                        @endif

                        @if (hasPermission('account.vendor-bills.generated-vendor-bills.index'))
                            <li>
                                <a href="{{ route('account.vendor-bills.generated-vendor-bills.index') }}"
                                    class="{{ request()->routeIs('account.vendor-bills.generated-vendor-bills.index') ? 'active' : '' }}">
                                    <span class="nav-icon fas fa-money-bill"></span>
                                    {{ t_('menu.generated-vendor-bills') }}
                                </a>
                            </li>
                        @endif

                    </ul>
                </li>
            @endif


            @if (hasPermission('account.i-o-u-requisition.*'))
                <li class="has-subchild {{ request()->routeIs('account.i-o-u-requisition.*') ? 'open' : '' }}">
                    <a href="#" class="{{ request()->routeIs('account.i-o-u-requisition.*') ? 'active' : '' }}">
                        <span class="nav-icon fas fa-file-invoice-dollar"></span>
                        <span class="menu-text">{{ t_('menu.iou-requisition') }}</span>
                        <span class="toggle-icon"></span>
                    </a>
                    <ul>
                        @if (hasPermission('account.i-o-u-requisition.i-o-u-requisition-entries.index'))
                            <li>
                                <a href="{{ route('account.i-o-u-requisition.i-o-u-requisition-entries.index') }}"
                                    class="{{ request()->routeIs('account.i-o-u-requisition.i-o-u-requisition-entries.index') ? 'active' : '' }}">
                                    <span class="nav-icon fas fa-file-invoice-dollar"></span>
                                    {{ t_('menu.iou-requisition-list') }}
                                </a>
                            </li>
                        @endif
                    </ul>
                </li>
            @endif



            @if (hasPermission('account.cash-transfers.index'))
                <li>
                    <a href="{{ route('account.cash-transfers.index') }}"
                        class="{{ request()->routeIs('account.cash-transfers.*') ? 'active' : '' }}">
                        <span class="nav-icon fas fa-exchange-alt"></span>
                        <span class="menu-text">{{ t_('Cash Transfer') }}</span>
                    </a>
                </li>
            @endif


            <!-- Products -->
            {{-- @if ($hasProductPermission)
            <li class="has-subchild {{ request()->routeIs('account.products.*') ? 'open' : '' }}">
                <a href="#" class="{{ request()->routeIs('account.products.*') ? 'active' : '' }}">
                    <span class="nav-icon fas fa-tags"></span>
                    <span class="menu-text">{{ t_('menu.product') }}</span>
                    <span class="toggle-icon"></span>
                </a>
                <ul>
                    @if (hasPermission('account.products.index'))
                    <li>
                        <a href="{{ route('account.products.index') }}"
                            class="{{ request()->routeIs('account.products.index') ? 'active' : '' }}">
                            <span class="nav-icon fas fa-tags"></span>
                            {{ t_('menu.list') }}
                        </a>
                    </li>
                    @endif

                    @if (hasPermission('account.categories.index'))
                    <li>
                        <a href="{{ route('account.categories.index') }}"
                            class="{{ request()->routeIs('account.categories.index') ? 'active' : '' }}">
                            <span class="nav-icon fas fa-sitemap"></span>
                            {{ t_('menu.category') }}
                        </a>
                    </li>
                    @endif

                    @if (hasPermission('account.units.index'))
                    <li>
                        <a href="{{ route('account.units.index') }}"
                            class="{{ request()->routeIs('account.units.index') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-tape"></i>
                            {{ t_('menu.unit') }}
                        </a>
                    </li>
                    @endif

                    @if (hasPermission('account.damages.index'))
                    <li>
                        <a href="{{ route('account.damages.index') }}"
                            class="{{ request()->routeIs('account.damages.index') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-times-circle"></i>
                            {{ t_('menu.damage') }}
                        </a>
                    </li>
                    @endif
                </ul>
            </li>
            @endif

            <!-- Party -->
            @if ($hasPartyPermission)

            <li class="has-subchild {{ request()->routeIs('account.acc-customers.*') ? 'open' : '' }}">

                <a href="#" class="{{ request()->routeIs('account.products.*') ? 'active' : '' }}">
                    <i class="nav-icon fas fa-users"></i>
                    <span class="menu-text">{{ t_('menu.party') }}</span>
                    <span class="toggle-icon"></span>
                </a>

                <ul class="submenu">

                    <!-- Customer -->
                    @if (hasPermission('account-customers.index'))
                    <li>
                        <a href="{{ route('account.acc-customers.index') }}">
                            <i class="nav-icon fa fa-user-friends"></i>
                            Customer
                        </a>
                    </li>
                    @endif


                    <!-- Supplier -->
                    @if (hasPermission('account-suppliers.index'))
                    <li>
                        <a href="{{ route('account.acc-suppliers.index') }}">
                            <i class="nav-icon fa fa-user-tie"></i>
                            Supplier
                        </a>
                    </li>
                    @endif
                </ul>
            </li>
            @endif

            <!-- Purchase -->
            @if (hasPermission('account.account-purchases.index'))
            <li class="has-subchild {{ request()->routeIs('account.acc-purchases.*') ? 'open' : '' }}">
                <a href="#" class="{{ request()->is('account.acc-purchases.*') ? 'active' : '' }} ">
                    <i class="nav-icon fa fa-truck"></i>
                    <span class="menu-text">Purchases</span>
                    <span class="toggle-icon"></span>
                </a>
                <b class="arrow"></b>

                <ul>

                    <li>
                        <a href="{{ route('account.acc-purchases.index') }}">
                            <i class="nav-icon fa fa-shopping-cart"></i>
                            <span class="menu-text">Purchase</span>
                        </a>
                    </li>

                    <li>
                        <a href="{{ route('account.acc-purchase-returns.index') }}">
                            <i class="nav-icon fa fa-undo"></i>
                            <span class="menu-text">Purchase Return</span>
                        </a>
                    </li>
                </ul>
            </li>
            @endif --}}



            {{-- @if (hasPermission('account.account-sales.index'))
            <li class="has-subchild {{ request()->is('account.acc-sales.*') ? 'open' : '' }}">
                <a href="#" class="{{ request()->is('account.acc-sales.*') ? 'active' : '' }}">
                    <i class="nav-icon fas fa-shopping-cart"></i>
                    <span class="menu-text">Sales</span>
                    <span class="toggle-icon"></span>
                </a>

                <ul>

                    <li>
                        <a href="{{ route('account.acc-sales.index') }}">
                            <i class="nav-icon fas fa-cash-register"></i>
                            <span class="menu-text">Sale</span>
                        </a>
                    </li>

                    <li>
                        <a href="{{ route('account.acc-sale-returns.index') }}">
                            <i class="nav-icon fa fa-reply"></i>
                            <span class="menu-text">Sale Return</span>
                        </a>
                    </li>
                </ul>
            </li>
            @endif --}}


            <li class="menu-title text-center mt-2 ">
                <span class="mb-1 " style="border-bottom: 1px solid #7e7e7e">Report</span>
            </li>
            <!-- Reports -->
            @if (hasPermission('account.report.*'))
                <li class="has-subchild {{ request()->routeIs('account.report.chart-of-account') ||
                    request()->routeIs('account.report.ledger-journal') ||
                    request()->routeIs('account.report.voucher-report') ||
                    request()->routeIs('account.report.account-ledger') ||
                    request()->routeIs('account.report.customer-ledger') ||
                    request()->routeIs('account.report.supplier-ledger') ||
                    request()->routeIs('account.report.vendor-ledger') ||
                    request()->routeIs('account.report.account-receivable') ||
                    request()->routeIs('account.report.account-payable') ||
                    request()->routeIs('account.report.subsidiary-wise-ledger')
                    ? 'open'
                    : '' }}">
                    <a href="#" class="{{ request()->routeIs('account.report.chart-of-account') ||
                    request()->routeIs('account.report.ledger-journal') ||
                    request()->routeIs('account.report.voucher-report') ||
                    request()->routeIs('account.report.account-ledger') ||
                    request()->routeIs('account.report.customer-ledger') ||
                    request()->routeIs('account.report.supplier-ledger') ||
                    request()->routeIs('account.report.vendor-ledger') ||
                    request()->routeIs('account.report.account-receivable') ||
                    request()->routeIs('account.report.account-payable') ||
                    request()->routeIs('account.report.subsidiary-wise-ledger')
                    ? 'active'
                    : '' }}">
                        <i class="nav-icon fa fa-clipboard-list"></i>
                        <span class="menu-text">Accounts Reports</span>
                        <span class="toggle-icon"></span>
                    </a>
                    <ul>
                        @if (hasPermission('account.report.chart-of-account'))
                            <li>
                                <a href="{{ route('account.report.chart-of-account') }}"
                                    class="{{ request()->routeIs('account.report.chart-of-account') ? 'active' : '' }}">
                                    <i class="nav-icon fa fa-file-alt"></i>
                                    {{ t_('menu.Chart Of Account') }}
                                </a>
                            </li>
                        @endif

                        @if (hasPermission('account.report.ledger-journal'))
                            <li>
                                <a href="{{ route('account.report.ledger-journal') }}"
                                    class="{{ request()->routeIs('account.report.ledger-journal') ? 'active' : '' }}">
                                    <i class="nav-icon fa fa-clipboard-list"></i>
                                    Ledger/Journal
                                </a>
                            </li>

                            <li>
                                <a href="{{ route('account.report.voucher-report') }}"
                                    class="{{ request()->routeIs('account.report.voucher-report') ? 'active' : '' }}"
                                    title="Voucher Wise Report">
                                    <i class="nav-icon fa fa-file-invoice"></i>
                                    Voucher Report
                                </a>
                            </li>
                        @endif

                        @if (hasPermission('account.report.account-ledger'))
                            <li>
                                <a href="{{ route('account.report.account-ledger') }}"
                                    class="{{ request()->routeIs('account.report.account-ledger') ? 'active' : '' }}">
                                    <i class="nav-icon fa fa-book"></i>
                                    Account Ledger
                                </a>
                            </li>
                        @endif


                        @if (hasPermission('account.report.customer-ledger'))
                            <li>
                                <a href="{{ route('account.report.customer-ledger') }}"
                                    class="{{ request()->routeIs('account.report.customer-ledger') ? 'active' : '' }}">
                                    <i class="nav-icon fa fa-users"></i>
                                    Customer Ledger
                                </a>
                            </li>
                        @endif

                        @if (hasPermission('account.report.supplier-ledger'))
                            <li>
                                <a href="{{ route('account.report.supplier-ledger') }}"
                                    class="{{ request()->routeIs('account.report.supplier-ledger') ? 'active' : '' }}">
                                    <i class="nav-icon fa fa-truck"></i>
                                    Supplier Ledger
                                </a>
                            </li>
                        @endif
                        @if (hasPermission('account.report.vendor-ledger'))
                            <li>
                                <a href="{{ route('account.report.vendor-ledger') }}"
                                    class="{{ request()->routeIs('account.report.vendor-ledger') ? 'active' : '' }}">
                                    <i class="nav-icon fa fa-truck-loading"></i>
                                    Vendor Ledger
                                </a>
                            </li>
                        @endif


                        {{-- @if (hasPermission('account.report.supplier-ledger'))
                        <li>
                            <a href="{{ route('account.report.supplier') }}"
                                class="{{ request()->routeIs('account.report.supplier') ? 'active' : '' }}">
                                <i class="nav-icon fa fa-user-tie"></i>
                                Supplier Report
                            </a>
                        </li>
                        @endif --}}


                        @if (hasPermission('account.report.supplier-ledger'))
                            <li>
                                <a href="{{ route('account.report.account-receivable') }}"
                                    class="{{ request()->routeIs('account.report.account-receivable') ? 'active' : '' }}"
                                    title="Account Receivable">
                                    <i class="nav-icon fa fa-university"></i>
                                    Acc. Receivable
                                </a>
                            </li>
                        @endif


                        @if (hasPermission('account.report.supplier-ledger'))
                            <li>
                                <a href="{{ route('account.report.account-payable') }}"
                                    class="{{ request()->routeIs('account.report.account-payable') ? 'active' : '' }}"
                                    title="Account Payable">
                                    <i class="nav-icon fa fa-building"></i>
                                    Acc. Payable
                                </a>
                            </li>
                        @endif


                        {{-- @if (hasPermission('account.report.subsidiary-wise-ledger'))
                        <li>
                            <a href="{{ route('account.report.subsidiary-wise-ledger') }}"
                                class="{{ request()->routeIs('account.report.subsidiary-wise-ledger') ? 'active' : '' }}"
                                title="Subsidiary Wise Ledger ">
                                <i class="nav-icon fa fa-university"></i>
                                Subsidiary Ledger
                            </a>
                        </li>
                        @endif
                        --}}

                    </ul>
                </li>
            @endif

            <!-- financial report -->
            @if (
                    hasAnyPermission([
                        'account.report.trial-balance',
                        'account.report.income-statement',
                        'account.report.equity-statement',
                        'account.report.balance-sheet',
                        'account.report.cash.flow',
                    ])
                )
                <li
                    class="has-subchild {{ request()->routeIs('account.report.trial-balance') || request()->routeIs('account.report.income-statement') || request()->routeIs('account.report.equity-statement') || request()->routeIs('account.report.balance-sheet') || request()->routeIs('account.report.cash.flow') ? 'open' : '' }}">
                    <a href="#"
                        class="{{ request()->routeIs('account.report.trial-balance') || request()->routeIs('account.report.income-statement') || request()->routeIs('account.report.equity-statement') || request()->routeIs('account.report.balance-sheet') || request()->routeIs('account.report.cash.flow') ? 'active' : '' }}"
                        data-toggle="tooltip" title="Financial Statements">
                        <i class="nav-icon fa fa-chart-line"></i>
                        Financial Reports
                        <span class="toggle-icon"></span>
                    </a>
                    <ul>
                        @if (hasPermission('account.report.trial-balance'))
                            <li>
                                <a href="{{ route('account.report.trial-balance') }}"
                                    class="{{ request()->routeIs('account.report.trial-balance') ? 'active' : '' }}">
                                    <i class="nav-icon fa fa-caret-right"></i>
                                    Trial Balance
                                </a>
                            </li>
                        @endif

                        @if (hasPermission('account.report.income-statement'))
                            <li>
                                <a href="{{ route('account.report.income-statement') }}"
                                    class="{{ request()->routeIs('account.report.income-statement') ? 'active' : '' }}">
                                    <i class="nav-icon fa fa-caret-right"></i>
                                    Income Statement
                                </a>
                            </li>
                        @endif

                        @if (hasPermission('account.report.equity-statement'))
                            <li>
                                <a href="{{ route('account.report.equity-statement') }}"
                                    class="{{ request()->routeIs('account.report.equity-statement') ? 'active' : '' }}">
                                    <i class="nav-icon fa fa-caret-right"></i>
                                    Equity Statement
                                </a>
                            </li>
                        @endif

                        @if (hasPermission('account.report.balance-sheet'))
                            <li>
                                <a href="{{ route('account.report.balance-sheet') }}"
                                    class="{{ request()->routeIs('account.report.balance-sheet') ? 'active' : '' }}">
                                    <i class="nav-icon fa fa-caret-right"></i>
                                    Balance Sheet
                                </a>
                            </li>
                        @endif

                        @if (hasPermission('account.report.cash.flow'))
                            <li>
                                <a href="{{ route('account.report.cash.flow') }}"
                                    class="{{ request()->routeIs('account.report.cash.flow') ? 'active' : '' }}">
                                    <i class="nav-icon fa fa-caret-right"></i>
                                    Cash Flow
                                </a>
                            </li>
                        @endif
                    </ul>
                </li>
            @endif


            {{-- <li class="has-subchild {{ request()->is('account.account.*') ? 'open' : '' }}">
                <a href="#" class="dropdown-toggle" data-toggle="tooltip" title="Financial Statements">
                    <i class="nav-icon fa fa-list"></i>
                    Inventory Report
                    <span class="toggle-icon"></span>
                </a>

                <ul>
                    <li>
                        <a href="{{ route('account.account.stock-in-hand') }}">
                            <i class="nav-icon fa fa-caret-right"></i>
                            Stock In Hand
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('account.account.item-ledger') }}">
                            <i class="nav-icon fa fa-caret-right"></i>
                            Item Ledger
                        </a>
                    </li>
                </ul>
            </li> --}}



        </ul>
    </li>
@endif