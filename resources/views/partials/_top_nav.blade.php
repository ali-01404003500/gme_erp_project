<nav class="navbar navbar-light">
    <div class="navbar-left">
         
        @php
            use App\Models\AccessControl\CompanyInfo;
            $companyInfo = cache()->remember('company_info', now()->addHours(24), function () { 
                return CompanyInfo::first();
            }); 
        @endphp 
        
        <div class="logo-area d-flex align-items-center justify-content-between">
            <div class="d-flex align-items-center"> 
                <!-- By default text dekhaabe -->
                <span class="company-title ms-2" id="companyText">
                    {{ $companyInfo?->software_title ?? 'GME ERP' }}
                </span>

                  <!-- Logo initially hide -->
                @if(!empty($companyInfo?->company_logo))
                    <img src="{{ url($companyInfo->company_logo) }}" 
                        alt="{{ $companyInfo->software_title }}" 
                        class="company-logo ms-2 " 
                        id="companyLogo"
                        style="height:60px; object-fit:contain; display:none;">
                @endif
            </div>

            <!-- Toggle Button -->
            <a href="#" class="sidebar-toggle ms-3" id="companyToggle">
                <img class="svg" src="{{ asset('assets/img/svg/align-center-alt.svg') }}" alt="Toggle Sidebar">
            </a>
            
        </div>

    </div>
    <div class="navbar-right">
        <ul class="navbar-right__menu"> 
            <li class="nav-notification">
                <div class="dropdown-custom">
                    <a href="javascript:;" id="notification-dropdown-icon" class="nav-item-toggle icon-active">
                        <img class="svg" src="{{ asset('assets/img/svg/alarm.svg') }}" alt="img">
                    </a>
                    <div class="dropdown-wrapper">
                        <h2 class="dropdown-wrapper__title">Notifications <span id="notification-count" class="badge-circle badge-warning ms-1">0</span>
                        @if (env('APP_DEBUG'))
                            <button class="btn btn-xs btn-solid mb-2 d-inline-block" onclick="this.disabled=true; getCountNotification();">
                                <i class="fa fa-sync"></i>
                            </button>
                        @endif
                        </h2>
                        @include('partials._top_notifications')
                        <a href="" class="dropdown-wrapper__more">See all incoming activity</a>
                    </div>
                </div>
            </li> 
            <li class="nav-flag-select">
                <div class="dropdown-custom">
                    @switch(app()->getLocale())
                        @case('en')
                            <a href="javascript:;" class="nav-item-toggle"><img src="{{ asset('assets/img/eng.png') }}" alt="" class="rounded-circle"></a>
                            @break
                        @case('ar')
                            <a href="javascript:;" class="nav-item-toggle"><img src="{{ asset('assets/img/iraq.png') }}" alt="" class="rounded-circle"></a>
                            @break
                        @case('gr')
                            <a href="javascript:;" class="nav-item-toggle"><img src="{{ asset('assets/img/ger.png') }}" alt="" class="rounded-circle"></a>
                            @break
                        @default
                            <a href="javascript:;" class="nav-item-toggle"><img src="{{ asset('assets/img/eng.png') }}" alt="" class="rounded-circle"></a>
                            @break
                    @endswitch
                    @if(isset($find_customer))
                        @foreach ($find_customer as $customer)
                            <div class="dropdown-wrapper dropdown-wrapper--small">
                                <a href="{{ route(Route::currentRouteName(),['lang'=>'en',$customer->id]) }}"><img src="{{ asset('assets/img/eng.png') }}" alt=""> English</a>
                                <a href="{{ route(Route::currentRouteName(),['lang'=>'ar',$customer->id]) }}"><img src="{{ asset('assets/img/iraq.png') }}" alt=""> Arabic</a>
                                <a href="{{ route(Route::currentRouteName(),['lang'=>'gr',$customer->id]) }}"><img src="{{ asset('assets/img/ger.png') }}" alt=""> German</a>
                            </div>
                        @endforeach
                    @else
                        <div class="dropdown-wrapper dropdown-wrapper--small">
                            <a href="{{ request()->fullUrlWithQuery(['lang'=>'en']) }}"><img src="{{ asset('assets/img/eng.png') }}" alt=""> English</a>
                            <a href="{{ request()->fullUrlWithQuery(['lang'=>'ar']) }}"><img src="{{ asset('assets/img/iraq.png') }}" alt=""> Arabic</a>
                            <a href="{{ request()->fullUrlWithQuery(['lang'=>'gr']) }}"><img src="{{ asset('assets/img/ger.png') }}" alt=""> German</a>
                        </div>
                    @endif
                </div>
            </li>
            <li class="nav-author">
                <div class="dropdown-custom">
                    <a href="javascript:;" class="nav-item-toggle"><img src="{{ optional(Auth::user()->employee)->photograph??asset('assets/img/author-nav.jpg') }}" alt="" class="rounded-circle">
                        @if(Auth::check())
                            <span class="nav-item__title">{{ Auth::user()->name }}<i class="las la-angle-down nav-item__arrow"></i></span>
                        @endif
                    </a>
                    <div class="dropdown-wrapper">
                        <div class="nav-author__info">
                            <div class="author-img">
                                <img src="{{ optional(Auth::user()->employee)->photograph??asset('assets/img/author-nav.jpg') }}" alt="" class="rounded-circle">
                            </div>
                            <div>
                                @if(Auth::check())
                                    <h6 class="text-capitalize">{{ Auth::user()->name }}</h6>
                                @endif
                                <span>{{ Auth::user()->email }}</span>
                            </div>
                        </div>
                        <div class="nav-author__options">
                            <ul>
                                <li>
                                    <a href="{{route("my_profile")}}">
                                        <img src="{{ asset('assets/img/svg/user.svg') }}" alt="user" class="svg"> Profile</a>
                                </li>
                                <li>
                                    <a href="{{route('access_control.global-settings.edit',1)}}">
                                        <img src="{{ asset('assets/img/svg/settings.svg') }}" alt="settings" class="svg"> Settings</a>
                                </li>
                                <li>
                                    <a href="{{route('notifications.general-notifications.index')}}">
                                        <img src="{{ asset('assets/img/svg/alarm.svg') }}" alt="notifications" class="svg"> Notification</a>
                                </li>
                            {{--      <li>
                                    <a href="{{route('history.user-log-histories.index')}}">
                                        <img src="{{ asset('assets/img/svg/users.svg') }}" alt="users" class="svg"> Activity</a>
                                </li>
                                <li>
                                    <a href="">
                                        <img src="{{ asset('assets/img/svg/bell.svg') }}" alt="bell" class="svg"> Help</a>
                                </li> --}}
                            </ul>
                            <a href="" class="nav-author__signout" onclick="event.preventDefault();document.getElementById('logout').submit();">
                                <img src="{{ asset('assets/img/svg/log-out.svg') }}" alt="log-out" class="svg">
                                 Sign Out</a>
                                <form style="display:none;" id="logout" action="{{ route('logout') }}" method="POST">
                                    @csrf
                                    @method('post')
                                </form>
                        </div>
                    </div>
                </div>
            </li>
        </ul>
        <div class="navbar-right__mobileAction d-md-none">
            <a href="#" class="btn-search">
                <img src="{{ asset('assets/img/svg/search.svg') }}" alt="search" class="svg feather-search">
                <img src="{{ asset('assets/img/svg/x.svg') }}" alt="x" class="svg feather-x">
            </a>
            <a href="#" class="btn-author-action">
                <img src="{{ asset('assets/img/svg/more-vertical.svg') }}" alt="more-vertical" class="svg"></a>
        </div>
    </div>
</nav>
