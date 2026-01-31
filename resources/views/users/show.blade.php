{{-- Company: Wreetu Helth. --}}
{{-- Author: Md Shadhin --}}
{{-- Developer: Md Shadhin --}}
{{-- Copywrite: 2024 --}}

@extends('layout.app')

@section('title', 'Profile')

@section('page-header')
    <div class="page-title">Profile</div>

    <nav class="breadcrumb-style-one" aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="{{ route('dashboard') }}">Dashboard</a>
            </li>
            <li class="breadcrumb-item active">
                <a href="{{ route('users.index') }}">Profile</a>
            </li>
        </ol>
    </nav>
@endsection

@section('content')
    <div class="col-xxl-12 col-xl-12 col-lg-12 col-md-12 page-title-wrapper">
        <div class="row align-items-center">
            <div class="col-xl-4 col-lg-5 col-md-5 col-sm-7 filtered-list-search align-self-center">
                <div class="inner-page-title pt-1">Profile</div>
            </div>

            <div class="col-xl-8 col-lg-7 col-md-7 col-sm-5 text-end">
                <a class="btn btn-warning me-1" href="#">
                    <i class="fas fa-edit"></i> Edit
                </a>
            </div>
        </div>
    </div>

    <div class="col-xl-12 col-lg-12 col-sm-12 layout-spacing">
        <div class="widget-content widget-content-area br-8">
            {{-- User Profile View --}}
            <div class="row">
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">User Information</h5>
                            <ul class="list-group">
                                <li class="list-group-item">
                                    <strong>Name:</strong> {{ $user->name }}
                                </li>
                                <li class="list-group-item">
                                    <strong>Email:</strong> {{ $user->email }}
                                </li>
                                <!-- Add more user information as needed -->

                            </ul>
                        </div>
                    </div>
                </div>

                <!-- Add more profile sections or cards as needed -->

            </div>
            {{-- End User Profile View --}}
        </div>
    </div>
@endsection
