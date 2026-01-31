<nav class="navbar navbar-light">
    <div class="navbar-left">
        <div class="logo-area">
            <a class="navbar-brand" href="/">
                @if (App\Models\AccessControl\CompanyInfo::first()->company_logo != null)
                    <img class="dark" src="{{ App\Models\AccessControl\CompanyInfo::first()->company_logo }}" alt="{{ App\Models\AccessControl\CompanyInfo::first()->company_name }}" style="height:60px!important; object-fit:contain ">
                    <img class="light" src="{{ App\Models\AccessControl\CompanyInfo::first()->company_logo }}" alt="{{ App\Models\AccessControl\CompanyInfo::first()->company_name }}" style="height:60px!important; object-fit:contain ">
                @else
                <h3>
                    {{ App\Models\AccessControl\CompanyInfo::first()->company_name }}
                </h3>
                @endif

                   

                {{-- {{ App\Models\AccessControl\CompanyInfo::first()->company_name }} --}}
                {{-- <img class="dark" src="{{ asset('assets/img/logo-white.svg') }}" alt="svg">
                <img class="light" src="{{ asset('assets/img/logo-white.svg') }}" alt="img"> --}}
            </a>
            <a href="#" class="sidebar-toggle">
                <img class="svg" src="{{ asset('assets/img/svg/align-center-alt.svg') }}" alt="img">
            </a>
        </div>
        
        {{-- <div class="top-menu">
            <div class="gmeproject-top-menu position-relative">
                <ul>

                    <li>
                        <a href="{{ route('pages.blank',app()->getLocale()) }}" class="{{ Request::is(app()->getLocale().'/pages/blank') ? 'active':'' }}">
                            <span class="nav-icon uil uil-circle"></span>
                            <span class="menu-text">{{ trans('menu.blank-menu-title') }}</span>
                        </a>
                    </li> 
                </ul>
            </div>
        </div> --}}
    </div>
    <div class="navbar-right">
        <ul class="navbar-right__menu">
            {{-- <li class="nav-search">
                <a href="#" class="search-toggle">
                    <i class="uil uil-search"></i>
                    <i class="uil uil-times"></i>
                </a>
                <form action="/" class="search-form-topMenu">
                    <span class="search-icon uil uil-search"></span>
                    <input class="form-control me-sm-2 box-shadow-none" type="search" placeholder="Search..." aria-label="Search">
                </form>
            </li>
            <li class="nav-message">
                <div class="dropdown-custom">
                    <a href="javascript:;" class="nav-item-toggle icon-active">
                        <img class="svg" src="{{ asset('assets/img/svg/message.svg') }}" alt="img">
                    </a>
                    <div class="dropdown-wrapper">
                        <h2 class="dropdown-wrapper__title">Messages <span class="badge-circle badge-success ms-1">2</span></h2>
                        <ul>
                            <li class="author-online has-new-message">
                                <div class="user-avater">
                                    <img src="{{ asset('assets/img/team-1.png') }}" alt="">
                                </div>
                                <div class="user-message">
                                    <p>
                                        <a href="" class="subject stretched-link text-truncate" style="max-width: 180px;">Web Design</a>
                                        <span class="time-posted">3 hrs ago</span>
                                    </p>
                                    <p>
                                        <span class="desc text-truncate" style="max-width: 215px;">Lorem ipsum
                                            dolor amet cosec Lorem ipsum</span>
                                        <span class="msg-count badge-circle badge-success badge-sm">1</span>
                                    </p>
                                </div>
                            </li>
                            <li class="author-offline has-new-message">
                                <div class="user-avater">
                                    <img src="{{ asset('assets/img/team-1.png') }}" alt="">
                                </div>
                                <div class="user-message">
                                    <p>
                                        <a href="" class="subject stretched-link text-truncate" style="max-width: 180px;">Web Design</a>
                                        <span class="time-posted">3 hrs ago</span>
                                    </p>
                                    <p>
                                        <span class="desc text-truncate" style="max-width: 215px;">Lorem ipsum
                                            dolor amet cosec Lorem ipsum</span>
                                        <span class="msg-count badge-circle badge-success badge-sm">1</span>
                                    </p>
                                </div>
                            </li>
                            <li class="author-online has-new-message">
                                <div class="user-avater">
                                    <img src="{{ asset('assets/img/team-1.png') }}" alt="">
                                </div>
                                <div class="user-message">
                                    <p>
                                        <a href="" class="subject stretched-link text-truncate" style="max-width: 180px;">Web Design</a>
                                        <span class="time-posted">3 hrs ago</span>
                                    </p>
                                    <p>
                                        <span class="desc text-truncate" style="max-width: 215px;">Lorem ipsum
                                            dolor amet cosec Lorem ipsum</span>
                                        <span class="msg-count badge-circle badge-success badge-sm">1</span>
                                    </p>
                                </div>
                            </li>
                            <li class="author-offline">
                                <div class="user-avater">
                                    <img src="{{ asset('assets/img/team-1.png') }}" alt="">
                                </div>
                                <div class="user-message">
                                    <p>
                                        <a href="" class="subject stretched-link text-truncate" style="max-width: 180px;">Web Design</a>
                                        <span class="time-posted">3 hrs ago</span>
                                    </p>
                                    <p>
                                        <span class="desc text-truncate" style="max-width: 215px;">Lorem ipsum
                                            dolor amet cosec Lorem ipsum</span>
                                    </p>
                                </div>
                            </li>
                            <li class="author-offline">
                                <div class="user-avater">
                                    <img src="{{ asset('assets/img/team-1.png') }}" alt="">
                                </div>
                                <div class="user-message">
                                    <p>
                                        <a href="" class="subject stretched-link text-truncate" style="max-width: 180px;">Web Design</a>
                                        <span class="time-posted">3 hrs ago</span>
                                    </p>
                                    <p>
                                        <span class="desc text-truncate" style="max-width: 215px;">Lorem ipsum
                                            dolor amet cosec Lorem ipsum</span>
                                    </p>
                                </div>
                            </li>
                        </ul>
                        <a href="" class="dropdown-wrapper__more">See All Message</a>
                    </div>
                </div>
            </li> --}}
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
            {{-- <li class="nav-settings">
                <div class="dropdown-custom">
                    <a href="javascript:;" class="nav-item-toggle">
                        <img src="{{ asset('assets/img/setting.png') }}" alt="img">
                    </a>
                    <div class="dropdown-wrapper dropdown-wrapper--large">
                        <ul class="list-settings">
                            <li class="d-flex">
                                <div class="me-3"><img src="{{ asset('assets/img/mail.png') }}" alt=""></div>
                                <div class="flex-grow-1">
                                    <h6>
                                        <a href="" class="stretched-link">All Features</a>
                                    </h6>
                                    <p>Introducing Increment subscriptions </p>
                                </div>
                            </li>
                            <li class="d-flex">
                                <div class="me-3"><img src="{{ asset('assets/img/color-palette.png') }}" alt=""></div>
                                <div class="flex-grow-1">
                                    <h6>
                                        <a href="" class="stretched-link">Themes</a>
                                    </h6>
                                    <p>Third party themes that are compatible</p>
                                </div>
                            </li>
                            <li class="d-flex">
                                <div class="me-3"><img src="{{ asset('assets/img/home.png') }}" alt=""></div>
                                <div class="flex-grow-1">
                                    <h6>
                                        <a href="" class="stretched-link">Payments</a>
                                    </h6>
                                    <p>We handle billions of dollars</p>
                                </div>
                            </li>
                            <li class="d-flex">
                                <div class="me-3"><img src="{{ asset('assets/img/video-camera.png') }}" alt=""></div>
                                <div class="flex-grow-1">
                                    <h6>
                                        <a href="" class="stretched-link">Design Mockups</a>
                                    </h6>
                                    <p>Share planning visuals with clients</p>
                                </div>
                            </li>
                            <li class="d-flex">
                                <div class="me-3"><img src="{{ asset('assets/img/document.png') }}" alt=""></div>
                                <div class="flex-grow-1">
                                    <h6>
                                        <a href="" class="stretched-link">Content Planner</a>
                                    </h6>
                                    <p>Centralize content gethering and editing</p>
                                </div>
                            </li>
                            <li class="d-flex">
                                <div class="me-3"><img src="{{ asset('assets/img/microphone.png') }}" alt=""></div>
                                <div class="flex-grow-1">
                                    <h6>
                                        <a href="" class="stretched-link">Diagram Maker</a>
                                    </h6>
                                    <p>Plan user flows & test scenarios</p>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
            </li> --}}
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
