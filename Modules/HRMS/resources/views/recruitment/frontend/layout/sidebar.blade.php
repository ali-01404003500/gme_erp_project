@php
$group = \App\Models\AccessControl\CompanyInfo::first();
@endphp
<nav class="navbar" style="background: rgb(36, 124, 124);">
    <div class="container" style="display: flex; justify-content: space-between; align-items: center;">
        <!-- Company Name -->
        <div class="navbar-header">
            <a class="navbar-brand" href="{{ route('carrier.index') }}">{{ $group->company_name }}</a>
        </div>

        <!-- Job Portal Title -->
        <div class="navbar-header">
            <a class="navbar-brand" href="javascript:void(0)">Job Portal</a>
        </div>

        <!-- Navigation Links -->
            <ul class="nav mr-auto">
                <li class="nav-item {{ request()->is('carrier') ? 'active' : '' }}">
                    <a class="nav-link" href="{{ route('carrier.index') }}" style="color: white;">Home</a>
                </li>
                <li class="nav-item {{ request()->is('carrier') ? 'active' : '' }}">
                    <a class="nav-link" href="{{ route('carrier.index') }}" style="color: white;">Jobs</a>
                </li>
            </ul>
    </div>
</nav>
