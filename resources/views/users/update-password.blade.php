{{-- Company: Wreetu Helth. --}}
{{-- Author: Md Shadhin --}}
{{-- Developer: Md Shadhin --}}
{{-- Copywrite: 2024 --}}

@extends('layout.app')

@section('title', 'Upade password')

@section('page-header')
    <div class="page-title">Upade password</div>

    <nav class="breadcrumb-style-one" aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item active"><a href="#">Upade password</a></li>
        </ol>
    </nav>
@endsection
@section('content')

    <div class="col-xxl-12 col-xl-12 col-lg-12 col-md-12 page-title-wrapper">
        <div class="row align-items-center">
            <div class="col-xl-4 col-lg-5 col-md-5 col-sm-7 filtered-list-search align-self-center">
                <div class="inner-page-title pt-1">Upade password</div>
            </div>
        </div>
    </div>

    <div class="col-xl-12 col-sm-12 layout-spacing">
        <div class="statbox widget box box-shadow">
            <div class="row">
                <div class="col-xl-12 col-md-12 col-sm-12 col-12">
                    <form action="{{ route('users.update_password', $user->id) }}" method="POST">
                        @csrf

                        <div class="row mb-3">
                            <label class="col-lg-2 col-form-label" for="current_password">Current password:</label>
                            <div class="col-lg-10">
                                <input type="password" class="form-control" name="current_password" id="current_password"
                                    placeholder="Current password">
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label class="col-lg-2 col-form-label " for="new_password">New Password:</label>
                            <div class="col-lg-10">
                                <input type="password" class="form-control" name="new_password" id="new_password"
                                    placeholder="New Password">
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label class="col-lg-2 col-form-label " for="confirm_password">Confirm Password:</label>
                            <div class="col-lg-10">
                                <input type="text" class="form-control" name="confirm_password" id="confirm_password"
                                    placeholder="Confirm Password">
                            </div>
                        </div>

                        <div class="text-end">
                            <button type="submit" class="btn btn-primary">Submit form</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>


@endSection
