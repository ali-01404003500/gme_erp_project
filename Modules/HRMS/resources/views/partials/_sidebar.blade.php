  {{-- hrm & payroll --}}
  @if (hasPermission('hrm.*'))
  <li class="has-child {{ request()->routeIs('hrm.*') ? 'open' : '' }}">
      <a href="#" class="{{ request()->routeIs('hrm.*') ? 'active' : '' }}">
          <span class="nav-icon uil uil-briefcase"></span>
          <span class="menu-text">{{ t_('menu.hrm-menu-title') }}</span>
          <span class="toggle-icon"></span>
      </a>
      <ul>
          @if (hasPermission('hrm.employees.*'))
              <li><a href="{{ route('hrm.employees.index') }}"
                      class="{{ request()->routeIs('hrm.employees.index') ? 'active' : '' }}">
                      <span class="nav-icon uil uil-users-alt" style="margin-right: 20px;"></span>
                      {{ t_('menu.employees') }}</a>
              </li>
          @endif
          @if (hasPermission('hrm.attendances.*'))
              <li>
                  <a href="{{ route('hrm.attendances.index') }}"
                      class="{{ request()->routeIs('hrm.attendances.*') ? 'active' : '' }}">
                      <span class="nav-icon uil uil-calender"></span>
                      <span class="menu-text">{{ t_('menu.Attendance') }}</span>
                  </a>
                  <ul>

                  </ul>
              </li>
          @endif
          @if (hasPermission('hrm.leaves.*'))
              <li>
                  <a href="{{ route('hrm.leaves.index') }}"
                      class="{{ request()->routeIs('hrm.leaves.*') ? 'active' : '' }}">
                      <span class="nav-icon uil uil-house-user"></span>
                      <span class="menu-text">{{ t_('menu.Leaves Application') }}</span>
                  </a>
                  <ul>

                  </ul>
              </li>
          @endif

             {{-- Leave Adjsutment --}}
                       @if (hasPermission('hrm.settings.leaveAdjustment.index'))
                          <li>
                            <a href="{{ route('hrm.settings.leaveAdjustment.index') }}"
                                  class="{{ request()->routeIs('hrm.settings.leaveAdjustment.*') ? 'active' : '' }}">
                                  <span class="nav-icon nav-icon fas fa-door-open"></span>
                              <span class="menu-text"> {{ t_('menu.leave-adjustment') }}</span>   
                                </a>
                                <ul></ul>
                          </li>
                      @endif

          @if (hasPermission('hrm.noticeboards.*'))
              <li>
                  <a href="{{ route('hrm.noticeboards.index') }}"
                      class="{{ request()->routeIs('hrm.noticeboards.*') ? 'active' : '' }}">
                      <span class="nav-icon fas fa-chalkboard"></span>
                      <span class="menu-text">{{ t_('menu.Noticeboard') }}</span>
                  </a>
                  <ul>

                  </ul>
              </li>
          @endif

          @if(hasPermission('hrm.bills.*'))
                <li class="has-subchild {{ request()->routeIs('hrm.bills.*') ? 'open' : '' }}">
                    <a href="#"
                        class="{{ request()->routeIs('hrm.bills.*') ? 'active' : '' }}">
                        <span class="nav-icon uil uil-chart-bar"></span>
                        <span class="menu-text">{{ t_('menu.bills') }}</span>
                        <span class="toggle-icon"></span>
                    </a>
                    <ul>
                        @if (hasPermission('hrm.bills.*'))
                            <li>
                                <a href="{{ route('hrm.bills.index') }}"
                                    class="{{ request()->routeIs('hrm.bills.index') ? 'active' : '' }}">
                                    <span class="nav-icon fas fa-money-check-alt"></span>
                                    <span class="menu-text">{{ t_('menu.TA/DA Entry') }}</span>
                                </a>
                                <ul>

                                </ul>
                            </li>
                        @endif

                        @if (hasPermission('hrm.bills.verify'))
                                <li>
                                    <a href="{{ route('hrm.bills.verify') }}"
                                        class="{{ request()->routeIs('hrm.bills.verify') ? 'active' : '' }}">
                                        <span class="nav-icon fas fa-money-check-alt"></span>
                                        <span class="menu-text">{{ t_('menu.TA/DA Verify') }}</span>
                                    </a>
                                    <ul>

                                    </ul>
                                </li>
                        @endif
    
                    </ul>
                </li>
            @endif

            
          
          @if (hasPermission('hrm.salary-generates.*'))
              <li>
                  <a href="{{ route('hrm.payrolls') }}" 
                  class="{{ request()->routeIs('hrm.salary-generates.*') ? 'active' : '' }}">
                      <span class="nav-icon uil uil-money-bill"></span>
                      <span class="menu-text">{{ t_('menu.Payroll') }}</span>
                  </a>
                 
              </li>
          @endif
            @if (hasPermission('hrm.loans.*'))
                <li>
                    <a href="{{ route('hrm.loans.index') }}"
                        class="{{ request()->routeIs('hrm.loans.*') ? 'active' : '' }}">
                        <span class="nav-icon uil uil-credit-card"></span>
                        <span class="menu-text">{{ t_('menu.Loans') }}</span>
                    </a>
                    <ul>
    
                    </ul>
                </li>
            @endif
            @if (hasPermission('hrm.daily-visit-plans.*'))
                <li>
                    <a href="{{ route('hrm.daily-visit-plans.index') }}"
                        class="{{ request()->routeIs('hrm.daily-visit-plans.*') ? 'active' : '' }}">
                        <span class="nav-icon uil uil-map-marker"></span>
                        <span class="menu-text">{{ t_('menu.Daily Visit Plan') }}</span>
                    </a>
                    <ul>
    
                    </ul>
                </li>
            @endif


          {{-- @if (hasPermission('hrm.holiday-calendar.*'))
              <li>
                  <a href="#"
                      class="{{ request()->routeIs('hrm.holiday-calendar.*') ? 'active' : '' }}">
                      <span class="nav-icon uil uil-calendar-alt"></span>
                      <span class="menu-text">{{ t_('menu.Holiday Calendar') }}</span>
                      <span class="toggle-icon"></span>
                  </a>
                  <ul>

                  </ul>
              </li>
          @endif --}}
          @if(hasPermission('hrm.reports.*'))
                <li class="has-subchild {{ request()->routeIs('hrm.reports.*') ? 'open' : '' }}">
                    <a href="#"
                        class="{{ request()->routeIs('hrm.reports.*') ? 'active' : '' }}">
                        <span class="nav-icon uil uil-chart-bar"></span>
                        <span class="menu-text">{{ t_('menu.Reports') }}</span>
                        <span class="toggle-icon"></span>
                    </a>
                    <ul>
                        @if(hasPermission('hrm.reports.daily-attendance-report'))
                            <li><a href="{{ route('hrm.reports.daily-attendance-report') }}"
                                    class="{{ request()->routeIs('hrm.reports.daily-attendance-report') ? 'active' : '' }}">
                                    <span class="nav-icon nav-icon fas fa-calendar-check"
                                        style="margin-right: 21px;"></span>
                                    {{ t_('Daily Attendance Report') }}</a>
                            </li>
                        @endif
                        @if(hasPermission('hrm.reports.monthly-attendance-report'))
                            <li><a href="{{ route('hrm.reports.monthly-attendance-report') }}"
                                    class="{{ request()->routeIs('hrm.reports.monthly-attendance-report') ? 'active' : '' }}">
                                    <span class="nav-icon nav-icon fas fa-calendar-alt"
                                        style="margin-right: 21px;"></span>
                                    {{ t_('Monthly Attendance Report') }}</a>
                            </li>
                        @endif
    
                    </ul>
                </li>
            @endif
            @if (hasPermission('hrm.kpis.*'))
                <li class="has-subchild {{ request()->routeIs('hrm.kpis.*') ? 'open' : '' }}">
                    <a href="#" class="{{ request()->routeIs('hrm.kpis.*') ? 'active' : '' }}">
                        <span class="nav-icon uil uil-chart"></span>
                        <span class="menu-text">{{ t_('menu.KPI') }}</span>
                        <span class="toggle-icon"></span>
                    </a>
                    <ul>
                        {{-- @if (hasPermission('hrm.kpis.appraisals.index'))
                            <li><a href="{{ route('hrm.kpis.appraisals.index') }}"
                                    class="{{ request()->routeIs('hrm.kpis.appraisals.*') ? 'active' : '' }}">
                                    <span class="nav-icon nav-icon fas fa-briefcase"
                                        style="margin-right: 21px;"></span>
                                    {{ t_('menu.KPI Appraisals') }}</a>
                            </li>
                        @endif
                        
                        @if (hasPermission('hrm.kpis.assessments.index'))
                            <li><a href="{{ route('hrm.kpis.assessments.index') }}"
                                    class="{{ request()->routeIs('hrm.kpis.assessments.*') ? 'active' : '' }}">
                                    <span class="nav-icon nav-icon fas fa-tasks"
                                        style="margin-right: 21px;"></span>
                                    {{ t_('menu.KPI Assessments') }}</a>
                            </li>
                        @endif
                       
                        @if (hasPermission('hrm.kpis.kpi-setups.index'))
                            <li><a href="{{ route('hrm.kpis.kpi-setups.index') }}"
                                    class="{{ request()->routeIs('hrm.kpis.kpi-setups.*') ? 'active' : '' }}">
                                    <span class="nav-icon nav-icon fas fa-cogs"
                                        style="margin-right: 21px;"></span>
                                    {{ t_('menu.KPI Setups') }}</a>
                            </li>
                        @endif --}}
                        @if (hasPermission('hrm.kpis.monthly-kpi-appraisals.index'))
                            <li>
                                <a href="{{ route('hrm.kpis.monthly-kpi-appraisals.index') }}"
                                    class="{{ request()->routeIs('hrm.kpis.monthly-kpi-appraisals.*') ? 'active' : '' }}">
                                    <span class="nav-icon nav-icon fas fa-folder-open"
                                        style="margin-right: 21px;"></span>
                                    {{ t_('menu.Monthly KPI Appraisals') }}</a>
                            </li>
                            
                        @endif
                        @if (hasPermission('hrm.kpis.kpi-assignments.index'))
                            <li>
                                <a href="{{ route('hrm.kpis.kpi-assignments.index') }}"
                                    class="{{ request()->routeIs('hrm.kpis.kpi-assignments.*') ? 'active' : '' }}">
                                    <span class="nav-icon nav-icon fas fa-file-alt"
                                        style="margin-right: 21px;"></span>
                                    {{ t_('menu.KPI Template Assign to Employee') }}</a>
                            </li>
                            
                        @endif
                        @if (hasPermission('hrm.kpis.kpi-templates.index'))
                            <li>
                                <a href="{{ route('hrm.kpis.kpi-templates.index') }}"
                                    class="{{ request()->routeIs('hrm.kpis.kpi-templates.*') ? 'active' : '' }}">
                                    <span class="nav-icon nav-icon fas fa-file-alt"
                                        style="margin-right: 21px;"></span>
                                    {{ t_('menu.KPI Templates') }}</a>
                            </li>
                            
                        @endif
                        @if (hasPermission('hrm.kpis.responsibility-entries.index'))
                            <li>
                                <a href="{{ route('hrm.kpis.responsibility-entries.index') }}"
                                    class="{{ request()->routeIs('hrm.kpis.responsibility-entries.*') ? 'active' : '' }}">
                                    <span class="nav-icon nav-icon fas fa-list-alt"
                                        style="margin-right: 21px;"></span>
                                    {{ t_('menu.Responsibility Entry') }}</a>
                            </li>
                            
                        @endif

                        @if (hasPermission('hrm.kpis.score-wise-suggestions.index'))
                            <li>
                                <a href="{{ route('hrm.kpis.score-wise-suggestions.index') }}"
                                    class="{{ request()->routeIs('hrm.kpis.score-wise-suggestions.*') ? 'active' : '' }}">
                                    <span class="nav-icon nav-icon fas fa-cogs"
                                        style="margin-right: 21px;"></span>
                                    {{ t_('menu.Score Wise Suggestions') }}</a>
                            </li>
                            
                        @endif
                    </ul>
                </li>
            @endif

          @if (hasPermission('hrm.settings.*') )
              <li class="has-subchild {{ request()->routeIs('hrm.settings.*') ? 'open' : '' }}">
                  <a href="#" class="{{ request()->routeIs('hrm.settings.*')  ? 'active' : '' }}">
                      <span class="nav-icon fa fa-cog"></span>
                      <span class="menu-text">{{ t_('menu.hrm-settings-menu-title') }}</span>
                      <span class="toggle-icon"></span>
                  </a>
                  <ul>
                      @if(hasPermission('hrm.settings.departments.index'))
                          <li><a href="{{ route('hrm.settings.departments.index') }}"
                                  class="{{ request()->routeIs('hrm.settings.departments.*') ? 'active' : '' }}">
                                  <span class="nav-icon nav-icon fas fa-building"
                                      style="margin-right: 21px;"></span>
                                  {{ t_('menu.departments') }}</a>
                          </li>
                      @endif
                      @if(hasPermission('hrm.settings.designations.index'))
                          <li><a href="{{ route('hrm.settings.designations.index') }}"
                                  class="{{ request()->routeIs('hrm.settings.designations.*') ? 'active' : '' }}">
                                  <span class="nav-icon nav-icon fas fa-briefcase"
                                      style="margin-right: 21px;"></span>
                                  {{ t_('menu.designations') }}</a>
                          </li>
                      @endif

                      @if (hasPermission('hrm.settings.leave-types.index'))
                          <li><a href="{{ route('hrm.settings.leave-types.index') }}"
                                  class="{{ request()->routeIs('hrm.settings.leave-types.*') ? 'active' : '' }}">
                                  <span class="nav-icon nav-icon fas fa-door-open"
                                      style="margin-right: 21px;"></span>
                                  {{ t_('menu.hrm-settings-leave-types-menu-title') }}</a>
                          </li>
                      @endif

                   

                      @if (hasPermission('hrm.settings.shifts.index'))
                          <li><a href="{{ route('hrm.settings.shifts.index') }}"
                                  class="{{ request()->routeIs('hrm.settings.shifts.*') ? 'active' : '' }}">
                                  <span class="nav-icon nav-icon fas fa-calendar-alt"
                                      style="margin-right: 21px;"></span>
                                  {{ t_('menu.hrm-settings-shift-menu-title') }}</a>
                          </li>
                      @endif
                      @if (hasPermission('hrm.settings.holidays.index'))
                          <li><a href="{{ route('hrm.settings.holidays.index') }}"
                                  class="{{ request()->routeIs('hrm.settings.holidays.*') ? 'active' : '' }}">
                                  <span class="nav-icon nav-icon fas fa-umbrella-beach"
                                      style="margin-right: 21px;"></span>
                                  {{ t_('menu.hrm-settings-holiday-menu-title') }}</a>
                          </li>
                      @endif
                        @if (hasPermission('hrm.settings.appraisal-policies.index'))
                            <li><a href="{{ route('hrm.settings.appraisal-policies.index') }}"
                                    class="{{ request()->routeIs('hrm.settings.appraisal-policies.*') ? 'active' : '' }}">
                                    <span class="nav-icon nav-icon fas fa-tasks"
                                        style="margin-right: 21px;"></span>
                                    {{ t_('menu.Appraisal Policies') }}</a>
                            </li>
                        @endif
                      @if (hasPermission('hrm.settings.salary-setups.index'))
                          <li><a href="{{ route('hrm.settings.salary-setups.index') }}"
                                  class="{{ request()->routeIs('hrm.settings.salary-setups.*') ? 'active' : '' }}">
                                  <span class="nav-icon nav-icon fas fa-money-check"
                                      style="margin-right: 21px;"></span>
                                  {{ t_('menu.hrm-settings-salary-menu-title') }}</a>
                          </li>
                      @endif
                      @if (hasPermission('hrm.settings.notice-types.index'))
                          <li><a href="{{ route('hrm.settings.notice-types.index') }}"
                                  class="{{ request()->routeIs('hrm.settings.notice-types.*') ? 'active' : '' }}">
                                  <span class="nav-icon nav-icon fas fa-chalkboard"
                                      style="margin-right: 21px;"></span>
                                  {{ t_('menu.hrm-settings-notice-types-menu-title') }}</a>
                          </li>
                      @endif
                      @if (hasPermission('hrm.settings.expense-types.index'))
                          <li><a href="{{ route('hrm.settings.expense-types.index') }}"
                                  class="{{ request()->routeIs('hrm.settings.expense-types.*') ? 'active' : '' }}">
                                  <span class="nav-icon nav-icon fas fa-money-check-alt"
                                      style="margin-right: 21px;"></span>
                                  {{ t_('menu.hrm-settings-expense-types-menu-title') }}</a>
                          </li>
                      @endif
                      @if (hasPermission('hrm.settings.transport-types.index'))
                          <li><a href="{{ route('hrm.settings.transport-types.index') }}"
                                  class="{{ request()->routeIs('hrm.settings.transport-types.*') ? 'active' : '' }}">
                                  <span class="nav-icon nav-icon fas fa-truck-moving"
                                      style="margin-right: 21px;"></span>
                                  {{ t_('menu.hrm-settings-transport-types-menu-title') }}</a>
                          </li>
                      @endif
                  </ul>
              </li>
          @endif

          @if (hasPermission('hrm.jobs.*') )
              <li class="has-subchild {{ request()->routeIs('hrm.jobs.*') || request()->routeIs('hrm.job-templates.*') || request()->routeIs('hrm.job-applications.*') ? 'open' : '' }}">
                  <a href="#" class="{{ request()->routeIs('hrm.jobs.*')|| request()->routeIs('hrm.job-templates.*') || request()->routeIs('hrm.job-applications.*') ? 'active' : '' }}">
                      <span class="nav-icon uil uil-briefcase"></span>
                      <span class="menu-text">{{ t_('menu.Recruitment') }}</span>
                      <span class="toggle-icon"></span>
                  </a>
                  <ul>
                      @if(hasPermission('hrm.jobs.index'))
                          <li><a href="{{ route('hrm.jobs.index') }}"
                                  class="{{ request()->routeIs('hrm.jobs.*') ? 'active' : '' }}">
                                  <span class="nav-icon nav-icon fas fa-suitcase"
                                      style="margin-right: 21px;"></span>
                                  {{ t_('menu.Jobs') }}</a>
                          </li>
                      @endif

                      @if(hasPermission('hrm.job-templates.index'))
                        <li><a href="{{ route('hrm.job-templates.index') }}"
                                class="{{ request()->routeIs('hrm.job-templates.*') ? 'active' : '' }}">
                                <span class="nav-icon nav-icon fas fa-file-alt"
                                    style="margin-right: 21px;"></span>
                                {{ t_('menu.job-templates') }}</a>
                        </li>
                    @endif
                    @if(hasPermission('hrm.job-applications.index'))
                        <li><a href="{{ route('hrm.job-applications.index') }}"
                                class="{{ request()->routeIs('hrm.job-applications.*') ? 'active' : '' }}">
                                <span class="nav-icon nav-icon fas fa-user-graduate"
                                    style="margin-right: 21px;"></span>
                                {{ t_('menu.job-applications') }}</a>
                        </li>
                    @endif
                    </ul>
              </li>
          @endif
      </ul>
  </li>
@endif
