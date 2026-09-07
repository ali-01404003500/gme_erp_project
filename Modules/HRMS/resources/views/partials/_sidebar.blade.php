 {{-- ========================================================= --}}
{{-- HRM & PAYROLL --}}
{{-- ========================================================= --}}

@if (hasPermission('hrm.*'))

    @php
        /*
        |--------------------------------------------------------------------------
        | HRM Main Menu Active
        |--------------------------------------------------------------------------
        */
        $hrmMenuActive = request()->routeIs('hrm.*');


        /*
        |--------------------------------------------------------------------------
        | Employee Menu
        |--------------------------------------------------------------------------
        */
        $employeeMenuActive =
            request()->routeIs('hrm.employees.*') ||
            request()->routeIs('hrm.departments.*') ||
            request()->routeIs('hrm.designations.*');


        /*
        |--------------------------------------------------------------------------
        | Attendance Menu
        |--------------------------------------------------------------------------
        */
        $attendanceMenuActive =
            request()->routeIs('hrm.attendances.*') ||
            request()->routeIs('hrm.attendance-policies.*');


        /*
        |--------------------------------------------------------------------------
        | Leave Menu
        |--------------------------------------------------------------------------
        */
        $leaveMenuActive =
            request()->routeIs('hrm.leaves.*') ||
            request()->routeIs('hrm.leave-application-employees.*') ||
            request()->routeIs('hrm.leave-types.*') ||
            request()->routeIs('hrm.leave-groups.*') ||
            request()->routeIs('hrm.leave-years.*') ||
            request()->routeIs('hrm.settings.leave-approvers.*') ||
            request()->routeIs('hrm.leave-statuses.*') ||
            request()->routeIs('hrm.leave-eligible-employees.*') ||
            request()->routeIs('hrm.leave-adjustments.*');


        /*
        |--------------------------------------------------------------------------
        | Salary Menu
        |--------------------------------------------------------------------------
        */
        $salaryMenuActive =
            request()->routeIs('hrm.salary-generation-policies.*') ||
            request()->routeIs('hrm.salary-generates.*') ||
            request()->routeIs('hrm.salary-setups.*') ||
            request()->routeIs('hrm.salary-deduction-policies.*') ||
            request()->routeIs('hrm.salary-signatories.*');


        /*
        |--------------------------------------------------------------------------
        | Bills Menu
        |--------------------------------------------------------------------------
        */
        $billsMenuActive =
            request()->routeIs('hrm.bills.*');


        /*
        |--------------------------------------------------------------------------
        | KPI Menu
        |--------------------------------------------------------------------------
        */
        $kpiMenuActive =
            request()->routeIs('hrm.kpis.*');


        /*
        |--------------------------------------------------------------------------
        | Notice Menu
        |--------------------------------------------------------------------------
        */
        $noticeMenuActive =
            request()->routeIs('hrm.noticeboards.*') ||
            request()->routeIs('hrm.settings.notice-types.*');


        /*
        |--------------------------------------------------------------------------
        | Reports Menu
        |--------------------------------------------------------------------------
        */
        $reportsMenuActive =
            request()->routeIs('hrm.reports.*');


        /*
        |--------------------------------------------------------------------------
        | Recruitment Menu
        |--------------------------------------------------------------------------
        */
        $recruitmentMenuActive =
            request()->routeIs('hrm.jobs.*') ||
            request()->routeIs('hrm.job-templates.*') ||
            request()->routeIs('hrm.job-applications.*');


        /*
        |--------------------------------------------------------------------------
        | HRM Settings Menu
        |--------------------------------------------------------------------------
        */
        $hrmSettingsMenuActive =
            request()->routeIs('hrm.settings.*');

    @endphp


    {{-- ========================================================= --}}
    {{-- HRM MAIN --}}
    {{-- ========================================================= --}}

    <li class="has-child {{ $hrmMenuActive ? 'open' : '' }}">

        <a href="#"
           class="{{ $hrmMenuActive ? 'active' : '' }}">

            <span class="nav-icon uil uil-briefcase"></span>

            <span class="menu-text">
                {{ t_('menu.hrm-menu-title') }}
            </span>

            <span class="toggle-icon"></span>

        </a>


        <ul>


            {{-- ================================================= --}}
            {{-- EMPLOYEE --}}
            {{-- ================================================= --}}

            @if (
                hasPermission('hrm.employees.*') ||
                hasPermission('hrm.departments.index') ||
                hasPermission('hrm.designations.index')
            )

                <li class="has-subchild {{ $employeeMenuActive ? 'open' : '' }}">

                    <a href="#"
                       class="{{ $employeeMenuActive ? 'active' : '' }}">

                        <span class="nav-icon fas fa-users"></span>

                        <span class="menu-text">
                            Employee
                        </span>

                        <span class="toggle-icon"></span>

                    </a>


                    <ul>

                        {{-- Employees --}}
                        @if (hasPermission('hrm.employees.*'))

                            <li>
                                <a href="{{ route('hrm.employees.index') }}"
                                   class="{{ request()->routeIs('hrm.employees.*') ? 'active' : '' }}">

                                    {{ t_('menu.employees') }}

                                </a>
                            </li>

                        @endif


                        {{-- Departments --}}
                        @if (hasPermission('hrm.departments.index'))

                            <li>
                                <a href="{{ route('hrm.departments.index') }}"
                                   class="{{ request()->routeIs('hrm.departments.*') ? 'active' : '' }}">

                                    {{ t_('menu.departments') }}

                                </a>
                            </li>

                        @endif


                        {{-- Designations --}}
                        @if (hasPermission('hrm.designations.index'))

                            <li>
                                <a href="{{ route('hrm.designations.index') }}"
                                   class="{{ request()->routeIs('hrm.designations.*') ? 'active' : '' }}">

                                    {{ t_('menu.designations') }}

                                </a>
                            </li>

                        @endif

                    </ul>

                </li>

            @endif



            {{-- ================================================= --}}
            {{-- ATTENDANCE --}}
            {{-- ================================================= --}}

            @if (
                hasPermission('hrm.attendance-policies.index') ||
                hasPermission('hrm.attendances.*')
            )

                <li class="has-subchild {{ $attendanceMenuActive ? 'open' : '' }}">

                    <a href="#"
                       class="{{ $attendanceMenuActive ? 'active' : '' }}">

                        <span class="nav-icon fas fa-clock"></span>

                        <span class="menu-text">
                            Attendance
                        </span>

                        <span class="toggle-icon"></span>

                    </a>


                    <ul>

                        {{-- All Employee Attendances --}}
                        @if (hasPermission('hrm.attendances.*'))

                            <li>

                                <a href="{{ route('hrm.attendances.index') }}"
                                   class="{{ request()->routeIs('hrm.attendances.index') ? 'active' : '' }}">

                                    <span class="nav-icon uil uil-calender"></span>

                                    <span class="menu-text">
                                        All Employee Attendances
                                    </span>

                                </a>

                            </li>

                        @endif


                        {{-- Attendance --}}
                        @if (hasPermission('hrm.attendances.*'))

                            <li>

                                <a href="{{ route('hrm.attendances.create') }}"
                                   class="{{ request()->routeIs('hrm.attendances.create') ? 'active' : '' }}">

                                    <span class="nav-icon uil uil-calender"></span>

                                    <span class="menu-text">
                                        {{ t_('menu.Attendance') }}
                                    </span>

                                </a>

                            </li>

                        @endif


                        {{-- Attendance Policy --}}
                        @if (hasPermission('hrm.attendance-policies.index'))

                            <li>

                                <a href="{{ route('hrm.attendance-policies.index') }}"
                                   class="{{ request()->routeIs('hrm.attendance-policies.*') ? 'active' : '' }}">

                                    <span class="nav-icon fas fa-calendar-check"
                                          style="margin-right: 21px;">
                                    </span>

                                    Attendance Policy

                                </a>

                            </li>

                        @endif

                    </ul>

                </li>

            @endif



            {{-- ================================================= --}}
            {{-- LEAVE --}}
            {{-- ================================================= --}}

            @if (
                hasPermission('hrm.leave-types.index') ||
                hasPermission('hrm.settings.leave-approvers.index') ||
                hasPermission('hrm.leave-groups.index') ||
                hasPermission('hrm.leave-years.index') ||
                hasPermission('hrm.leave-statuses.index') ||
                hasPermission('hrm.leave-eligible-employees.index') ||
                hasPermission('hrm.leaves.*') ||
                hasPermission('hrm.leave-application-employees.*') ||
                hasPermission('hrm.leave-adjustments.index')
            )

                <li class="has-subchild {{ $leaveMenuActive ? 'open' : '' }}">

                    <a href="#"
                       class="{{ $leaveMenuActive ? 'active' : '' }}">

                        <span class="nav-icon fas fa-clock"></span>

                        <span class="menu-text">
                            Leave
                        </span>

                        <span class="toggle-icon"></span>

                    </a>


                    <ul>


                        {{-- Leave Application All --}}
                        @if (hasPermission('hrm.leaves.*'))

                            <li>

                                <a href="{{ route('hrm.leaves.index') }}"
                                   class="{{ request()->routeIs('hrm.leaves.*') ? 'active' : '' }}">

                                    <span class="nav-icon uil uil-house-user"></span>

                                    <span class="menu-text">
                                        {{ t_('menu.Leaves Application (All)') }}
                                    </span>

                                </a>

                            </li>

                        @endif



                        {{-- Employee Leave Application --}}
                        @if (hasPermission('hrm.leave-application-employees.*'))

                            <li>

                                <a href="{{ route('hrm.leave-application-employees.index') }}"
                                   class="{{ request()->routeIs('hrm.leave-application-employees.*') ? 'active' : '' }}">

                                    <span class="nav-icon uil uil-house-user"></span>

                                    <span class="menu-text">
                                        {{ t_('menu.Leaves Application') }}
                                    </span>

                                </a>

                            </li>

                        @endif



                        {{-- Leave Types --}}
                        @if (hasPermission('hrm.leave-types.index'))

                            <li>

                                <a href="{{ route('hrm.leave-types.index') }}"
                                   class="{{ request()->routeIs('hrm.leave-types.*') ? 'active' : '' }}">

                                    <span class="nav-icon fas fa-door-open"
                                          style="margin-right: 21px;">
                                    </span>

                                    {{ t_('menu.hrm-settings-leave-types-menu-title') }}

                                </a>

                            </li>

                        @endif



                        {{-- Leave Groups --}}
                        @if (hasPermission('hrm.leave-groups.index'))

                            <li>

                                <a href="{{ route('hrm.leave-groups.index') }}"
                                   class="{{ request()->routeIs('hrm.leave-groups.*') ? 'active' : '' }}">

                                    <span class="nav-icon fas fa-users"
                                          style="margin-right: 21px;">
                                    </span>

                                    {{ t_('menu.leave-group') }}

                                </a>

                            </li>

                        @endif



                        {{-- Leave Years --}}
                        @if (hasPermission('hrm.leave-years.index'))

                            <li>

                                <a href="{{ route('hrm.leave-years.index') }}"
                                   class="{{ request()->routeIs('hrm.leave-years.*') ? 'active' : '' }}">

                                    <span class="nav-icon fas fa-calendar-check"></span>

                                    <span class="menu-text">
                                        {{ t_('menu.leave-year') }}
                                    </span>

                                </a>

                            </li>

                        @endif



                        {{-- Approver Setup --}}
                        @if (hasPermission('hrm.settings.leave-approvers.index'))

                            <li>

                                <a href="{{ route('hrm.settings.leave-approvers.index') }}"
                                   class="{{ request()->routeIs('hrm.settings.leave-approvers.*') ? 'active' : '' }}">

                                    <span class="nav-icon fas fa-user"
                                          style="margin-right: 21px;">
                                    </span>

                                    {{ t_('menu.Approver-Setup') }}

                                </a>

                            </li>

                        @endif



                        {{-- Leave Status --}}
                        @if (hasPermission('hrm.leave-statuses.index'))

                            <li>

                                <a href="{{ route('hrm.leave-statuses.index') }}"
                                   class="{{ request()->routeIs('hrm.leave-statuses.*') ? 'active' : '' }}">

                                    <span class="nav-icon fas fa-user-clock"></span>

                                    <span class="menu-text">
                                        {{ t_('menu.leave-status') }}
                                    </span>

                                </a>

                            </li>

                        @endif



                        {{-- Eligible Employees --}}
                        @if (hasPermission('hrm.leave-eligible-employees.index'))

                            <li>

                                <a href="{{ route('hrm.leave-eligible-employees.index') }}"
                                   class="{{ request()->routeIs('hrm.leave-eligible-employees.*') ? 'active' : '' }}">

                                    <span class="nav-icon fas fa-user-check"></span>

                                    <span class="menu-text">
                                        {{ t_('menu.leave-eligible-employee') }}
                                    </span>

                                </a>

                            </li>

                        @endif



                        {{-- Leave Adjustment --}}
                        @if (hasPermission('hrm.leave-adjustments.index'))

                            <li>

                                <a href="{{ route('hrm.leave-adjustments.index') }}"
                                   class="{{ request()->routeIs('hrm.leave-adjustments.*') ? 'active' : '' }}">

                                    <span class="nav-icon fas fa-exchange-alt"></span>

                                    <span class="menu-text">
                                        {{ t_('menu.leave-adjustments') }}
                                    </span>

                                </a>

                            </li>

                        @endif

                    </ul>

                </li>

            @endif



            {{-- ================================================= --}}
            {{-- SALARY --}}
            {{-- ================================================= --}}

            @if (
                hasPermission('hrm.salary-generation-policies.index') ||
                hasPermission('hrm.salary-generates.index') ||
                hasPermission('hrm.salary-setups.index') ||
                hasPermission('hrm.salary-deduction-policies.index') ||
                hasPermission('hrm.salary-signatories.index')
            )

                <li class="has-subchild {{ $salaryMenuActive ? 'open' : '' }}">

                    <a href="#"
                       class="{{ $salaryMenuActive ? 'active' : '' }}">

                        <span class="nav-icon fa fa-cog"></span>

                        <span class="menu-text">
                            Salary
                        </span>

                        <span class="toggle-icon"></span>

                    </a>


                    <ul>


                        {{-- Payroll --}}
                        @if (hasPermission('hrm.salary-generates.index'))

                            <li>

                                <a href="{{ route('hrm.salary-generates.index') }}"
                                   class="{{ request()->routeIs('hrm.salary-generates.*') ? 'active' : '' }}">

                                    <span class="nav-icon uil uil-money-bill"></span>

                                    <span class="menu-text">
                                        {{ t_('menu.Payroll') }}
                                    </span>

                                </a>

                            </li>

                        @endif



                        {{-- Salary Setup --}}
                        @if (hasPermission('hrm.salary-setups.index'))

                            <li>

                                <a href="{{ route('hrm.salary-setups.index') }}"
                                   class="{{ request()->routeIs('hrm.salary-setups.*') ? 'active' : '' }}">

                                    <span class="nav-icon fas fa-money-check"
                                          style="margin-right: 21px;">
                                    </span>

                                    {{ t_('menu.hrm-settings-salary-menu-title') }}

                                </a>

                            </li>

                        @endif



                        {{-- Salary Generation Policies --}}
                        @if (hasPermission('hrm.salary-generation-policies.index'))

                            <li>

                                <a href="{{ route('hrm.salary-generation-policies.index') }}"
                                   class="{{ request()->routeIs('hrm.salary-generation-policies.*') ? 'active' : '' }}">

                                    <span class="nav-icon fas fa-user"
                                          style="margin-right: 21px;">
                                    </span>

                                    {{ t_('menu.salary-generation-policies') }}

                                </a>

                            </li>

                        @endif



                        {{-- Salary Deduction Policies --}}
                        @if (hasPermission('hrm.salary-deduction-policies.index'))

                            <li>

                                <a href="{{ route('hrm.salary-deduction-policies.index') }}"
                                   class="{{ request()->routeIs('hrm.salary-deduction-policies.*') ? 'active' : '' }}">

                                    <span class="nav-icon fas fa-money-bill-wave"
                                          style="margin-right: 21px;">
                                    </span>

                                    {{ t_('menu.salary-deduction-policies') }}

                                </a>

                            </li>

                        @endif



                        {{-- Salary Signatory --}}
                        @if (hasPermission('hrm.salary-signatories.index'))

                            <li>

                                <a href="{{ route('hrm.salary-signatories.index') }}"
                                   class="{{ request()->routeIs('hrm.salary-signatories.*') ? 'active' : '' }}">

                                    <i class="nav-icon fas fa-user-tie"></i>

                                    <span class="menu-text">
                                        Salary Signatory
                                    </span>

                                </a>

                            </li>

                        @endif

                    </ul>

                </li>

            @endif



            {{-- ================================================= --}}
            {{-- BILLS --}}
            {{-- ================================================= --}}

            @if (hasPermission('hrm.bills.*'))

                <li class="has-subchild {{ $billsMenuActive ? 'open' : '' }}">

                    <a href="#"
                       class="{{ $billsMenuActive ? 'active' : '' }}">

                        <span class="nav-icon uil uil-chart-bar"></span>

                        <span class="menu-text">
                            {{ t_('menu.bills') }}
                        </span>

                        <span class="toggle-icon"></span>

                    </a>


                    <ul>

                        {{-- TA/DA Entry --}}
                        @if (hasPermission('hrm.bills.*'))

                            <li>

                                <a href="{{ route('hrm.bills.index') }}"
                                   class="{{ request()->routeIs('hrm.bills.index') ? 'active' : '' }}">

                                    <span class="nav-icon fas fa-money-check-alt"></span>

                                    <span class="menu-text">
                                        {{ t_('menu.TA/DA Entry') }}
                                    </span>

                                </a>

                            </li>

                        @endif


                        {{-- TA/DA Verify --}}
                        @if (hasPermission('hrm.bills.verify'))

                            <li>

                                <a href="{{ route('hrm.bills.verify') }}"
                                   class="{{ request()->routeIs('hrm.bills.verify') ? 'active' : '' }}">

                                    <span class="nav-icon fas fa-money-check-alt"></span>

                                    <span class="menu-text">
                                        {{ t_('menu.TA/DA Verify') }}
                                    </span>

                                </a>

                            </li>

                        @endif

                    </ul>

                </li>

            @endif



            {{-- ================================================= --}}
            {{-- DAILY VISIT PLAN --}}
            {{-- ================================================= --}}

            @if (hasPermission('hrm.daily-visit-plans.*'))

                <li>

                    <a href="{{ route('hrm.daily-visit-plans.index') }}"
                       class="{{ request()->routeIs('hrm.daily-visit-plans.*') ? 'active' : '' }}">

                        <span class="nav-icon uil uil-map-marker"></span>

                        <span class="menu-text">
                            {{ t_('menu.Daily Visit Plan') }}
                        </span>

                    </a>

                </li>

            @endif



            {{-- ================================================= --}}
            {{-- LOANS --}}
            {{-- ================================================= --}}

            @if (hasPermission('hrm.loans.*'))

                <li>

                    <a href="{{ route('hrm.loans.index') }}"
                       class="{{ request()->routeIs('hrm.loans.*') ? 'active' : '' }}">

                        <span class="nav-icon uil uil-credit-card"></span>

                        <span class="menu-text">
                            {{ t_('menu.Loans') }}
                        </span>

                    </a>

                </li>

            @endif



            {{-- ================================================= --}}
            {{-- KPI --}}
            {{-- ================================================= --}}

            @if (hasPermission('hrm.kpis.*'))

                <li class="has-subchild {{ $kpiMenuActive ? 'open' : '' }}">

                    <a href="#"
                       class="{{ $kpiMenuActive ? 'active' : '' }}">

                        <span class="nav-icon uil uil-chart"></span>

                        <span class="menu-text">
                            {{ t_('menu.KPI') }}
                        </span>

                        <span class="toggle-icon"></span>

                    </a>


                    <ul>


                        {{-- Monthly KPI Appraisals --}}
                        @if (hasPermission('hrm.kpis.monthly-kpi-appraisals.index'))

                            <li>

                                <a href="{{ route('hrm.kpis.monthly-kpi-appraisals.index') }}"
                                   class="{{ request()->routeIs('hrm.kpis.monthly-kpi-appraisals.*') ? 'active' : '' }}">

                                    <span class="nav-icon fas fa-folder-open"
                                          style="margin-right: 21px;">
                                    </span>

                                    {{ t_('menu.Monthly KPI Appraisals') }}

                                </a>

                            </li>

                        @endif



                        {{-- KPI Assignment --}}
                        @if (hasPermission('hrm.kpis.kpi-assignments.index'))

                            <li>

                                <a href="{{ route('hrm.kpis.kpi-assignments.index') }}"
                                   class="{{ request()->routeIs('hrm.kpis.kpi-assignments.*') ? 'active' : '' }}">

                                    <span class="nav-icon fas fa-file-alt"
                                          style="margin-right: 21px;">
                                    </span>

                                    {{ t_('menu.KPI Template Assign to Employee') }}

                                </a>

                            </li>

                        @endif



                        {{-- KPI Templates --}}
                        @if (hasPermission('hrm.kpis.kpi-templates.index'))

                            <li>

                                <a href="{{ route('hrm.kpis.kpi-templates.index') }}"
                                   class="{{ request()->routeIs('hrm.kpis.kpi-templates.*') ? 'active' : '' }}">

                                    <span class="nav-icon fas fa-file-alt"
                                          style="margin-right: 21px;">
                                    </span>

                                    {{ t_('menu.KPI Templates') }}

                                </a>

                            </li>

                        @endif



                        {{-- Responsibility Entry --}}
                        @if (hasPermission('hrm.kpis.responsibility-entries.index'))

                            <li>

                                <a href="{{ route('hrm.kpis.responsibility-entries.index') }}"
                                   class="{{ request()->routeIs('hrm.kpis.responsibility-entries.*') ? 'active' : '' }}">

                                    <span class="nav-icon fas fa-list-alt"
                                          style="margin-right: 21px;">
                                    </span>

                                    {{ t_('menu.Responsibility Entry') }}

                                </a>

                            </li>

                        @endif



                        {{-- Score Wise Suggestions --}}
                        @if (hasPermission('hrm.kpis.score-wise-suggestions.index'))

                            <li>

                                <a href="{{ route('hrm.kpis.score-wise-suggestions.index') }}"
                                   class="{{ request()->routeIs('hrm.kpis.score-wise-suggestions.*') ? 'active' : '' }}">

                                    <span class="nav-icon fas fa-cogs"
                                          style="margin-right: 21px;">
                                    </span>

                                    {{ t_('menu.Score Wise Suggestions') }}

                                </a>

                            </li>

                        @endif

                    </ul>

                </li>

            @endif



            {{-- ================================================= --}}
            {{-- NOTICE --}}
            {{-- ================================================= --}}

            @if (
                hasPermission('hrm.noticeboards.*') ||
                hasPermission('hrm.settings.notice-types.index')
            )

                <li class="has-subchild {{ $noticeMenuActive ? 'open' : '' }}">

                    <a href="#"
                       class="{{ $noticeMenuActive ? 'active' : '' }}">

                        <span class="nav-icon fas fa-bullhorn"></span>

                        <span class="menu-text">
                            Notice
                        </span>

                        <span class="toggle-icon"></span>

                    </a>


                    <ul>

                        {{-- Notice Board --}}
                        @if (hasPermission('hrm.noticeboards.*'))

                            <li>

                                <a href="{{ route('hrm.noticeboards.index') }}"
                                   class="{{ request()->routeIs('hrm.noticeboards.*') ? 'active' : '' }}">

                                    <span class="nav-icon fas fa-chalkboard"
                                          style="margin-right: 21px;">
                                    </span>

                                    {{ t_('menu.Noticeboard') }}

                                </a>

                            </li>

                        @endif



                        {{-- Notice Types --}}
                        @if (hasPermission('hrm.settings.notice-types.index'))

                            <li>

                                <a href="{{ route('hrm.settings.notice-types.index') }}"
                                   class="{{ request()->routeIs('hrm.settings.notice-types.*') ? 'active' : '' }}">

                                    <span class="nav-icon fas fa-list"
                                          style="margin-right: 21px;">
                                    </span>

                                    {{ t_('menu.hrm-settings-notice-types-menu-title') }}

                                </a>

                            </li>

                        @endif

                    </ul>

                </li>

            @endif



            {{-- ================================================= --}}
            {{-- REPORTS --}}
            {{-- ================================================= --}}

            @if (hasPermission('hrm.reports.*'))

                <li class="has-subchild {{ $reportsMenuActive ? 'open' : '' }}">

                    <a href="#"
                       class="{{ $reportsMenuActive ? 'active' : '' }}">

                        <span class="nav-icon uil uil-chart-bar"></span>

                        <span class="menu-text">
                            {{ t_('menu.Reports') }}
                        </span>

                        <span class="toggle-icon"></span>

                    </a>


                    <ul>

                        {{-- Daily Attendance Report --}}
                        @if (hasPermission('hrm.reports.daily-attendance-report'))

                            <li>

                                <a href="{{ route('hrm.reports.daily-attendance-report') }}"
                                   class="{{ request()->routeIs('hrm.reports.daily-attendance-report') ? 'active' : '' }}">

                                    <span class="nav-icon fas fa-calendar-check"
                                          style="margin-right: 21px;">
                                    </span>

                                    {{ t_('Daily Attendance Report') }}

                                </a>

                            </li>

                        @endif



                        {{-- Monthly Attendance Report --}}
                        @if (hasPermission('hrm.reports.monthly-attendance-report'))

                            <li>

                                <a href="{{ route('hrm.reports.monthly-attendance-report') }}"
                                   class="{{ request()->routeIs('hrm.reports.monthly-attendance-report') ? 'active' : '' }}">

                                    <span class="nav-icon fas fa-calendar-alt"
                                          style="margin-right: 21px;">
                                    </span>

                                    {{ t_('Monthly Attendance Report') }}

                                </a>

                            </li>

                        @endif

                    </ul>

                </li>

            @endif



            {{-- ================================================= --}}
            {{-- RECRUITMENT --}}
            {{-- ================================================= --}}

            @if (hasPermission('hrm.jobs.*'))

                <li class="has-subchild {{ $recruitmentMenuActive ? 'open' : '' }}">

                    <a href="#"
                       class="{{ $recruitmentMenuActive ? 'active' : '' }}">

                        <span class="nav-icon uil uil-briefcase"></span>

                        <span class="menu-text">
                            {{ t_('menu.Recruitment') }}
                        </span>

                        <span class="toggle-icon"></span>

                    </a>


                    <ul>

                        {{-- Jobs --}}
                        @if (hasPermission('hrm.jobs.index'))

                            <li>

                                <a href="{{ route('hrm.jobs.index') }}"
                                   class="{{ request()->routeIs('hrm.jobs.*') ? 'active' : '' }}">

                                    <span class="nav-icon fas fa-suitcase"
                                          style="margin-right: 21px;">
                                    </span>

                                    {{ t_('menu.Jobs') }}

                                </a>

                            </li>

                        @endif



                        {{-- Job Templates --}}
                        @if (hasPermission('hrm.job-templates.index'))

                            <li>

                                <a href="{{ route('hrm.job-templates.index') }}"
                                   class="{{ request()->routeIs('hrm.job-templates.*') ? 'active' : '' }}">

                                    <span class="nav-icon fas fa-file-alt"
                                          style="margin-right: 21px;">
                                    </span>

                                    {{ t_('menu.job-templates') }}

                                </a>

                            </li>

                        @endif



                        {{-- Job Applications --}}
                        @if (hasPermission('hrm.job-applications.index'))

                            <li>

                                <a href="{{ route('hrm.job-applications.index') }}"
                                   class="{{ request()->routeIs('hrm.job-applications.*') ? 'active' : '' }}">

                                    <span class="nav-icon fas fa-user-graduate"
                                          style="margin-right: 21px;">
                                    </span>

                                    {{ t_('menu.job-applications') }}

                                </a>

                            </li>

                        @endif

                    </ul>

                </li>

            @endif



            {{-- ================================================= --}}
            {{-- HRM SETTINGS --}}
            {{-- ================================================= --}}

            @if (hasPermission('hrm.settings.*'))

                <li class="has-subchild {{ $hrmSettingsMenuActive ? 'open' : '' }}">

                    <a href="#"
                       class="{{ $hrmSettingsMenuActive ? 'active' : '' }}">

                        <span class="nav-icon fa fa-cog"></span>

                        <span class="menu-text">
                            {{ t_('menu.hrm-settings-menu-title') }}
                        </span>

                        <span class="toggle-icon"></span>

                    </a>


                    <ul>


                        {{-- Shifts --}}
                        @if (hasPermission('hrm.settings.shifts.index'))

                            <li>

                                <a href="{{ route('hrm.settings.shifts.index') }}"
                                   class="{{ request()->routeIs('hrm.settings.shifts.*') ? 'active' : '' }}">

                                    <span class="nav-icon fas fa-calendar-alt"
                                          style="margin-right: 21px;">
                                    </span>

                                    {{ t_('menu.hrm-settings-shift-menu-title') }}

                                </a>

                            </li>

                        @endif



                        {{-- Holidays --}}
                        @if (hasPermission('hrm.settings.holidays.index'))

                            <li>

                                <a href="{{ route('hrm.settings.holidays.index') }}"
                                   class="{{ request()->routeIs('hrm.settings.holidays.*') ? 'active' : '' }}">

                                    <span class="nav-icon fas fa-umbrella-beach"
                                          style="margin-right: 21px;">
                                    </span>

                                    {{ t_('menu.hrm-settings-holiday-menu-title') }}

                                </a>

                            </li>

                        @endif



                        {{-- Hotspots --}}
                        @if (hasPermission('hrm.settings.hotspots.index'))

                            <li>

                                <a href="{{ route('hrm.settings.hotspots.index') }}"
                                   class="{{ request()->routeIs('hrm.settings.hotspots.*') ? 'active' : '' }}">

                                    <span class="nav-icon fas fa-map-marked-alt"
                                          style="margin-right: 21px;">
                                    </span>

                                    {{ t_('menu.hrm-settings-hotspot-menu-title') }}

                                </a>

                            </li>

                        @endif



                        {{-- Appraisal Policies --}}
                        @if (hasPermission('hrm.settings.appraisal-policies.index'))

                            <li>

                                <a href="{{ route('hrm.settings.appraisal-policies.index') }}"
                                   class="{{ request()->routeIs('hrm.settings.appraisal-policies.*') ? 'active' : '' }}">

                                    <span class="nav-icon fas fa-tasks"
                                          style="margin-right: 21px;">
                                    </span>

                                    {{ t_('menu.Appraisal Policies') }}

                                </a>

                            </li>

                        @endif



                        {{-- Expense Types --}}
                        @if (hasPermission('hrm.settings.expense-types.index'))

                            <li>

                                <a href="{{ route('hrm.settings.expense-types.index') }}"
                                   class="{{ request()->routeIs('hrm.settings.expense-types.*') ? 'active' : '' }}">

                                    <span class="nav-icon fas fa-money-check-alt"
                                          style="margin-right: 21px;">
                                    </span>

                                    {{ t_('menu.hrm-settings-expense-types-menu-title') }}

                                </a>

                            </li>

                        @endif



                        {{-- Transport Types --}}
                        @if (hasPermission('hrm.settings.transport-types.index'))

                            <li>

                                <a href="{{ route('hrm.settings.transport-types.index') }}"
                                   class="{{ request()->routeIs('hrm.settings.transport-types.*') ? 'active' : '' }}">

                                    <span class="nav-icon fas fa-truck-moving"
                                          style="margin-right: 21px;">
                                    </span>

                                    {{ t_('menu.hrm-settings-transport-types-menu-title') }}

                                </a>

                            </li>

                        @endif

                    </ul>

                </li>

            @endif

        </ul>

    </li>

@endif