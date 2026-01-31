{{-- Company: Wreetu Helth. --}}
{{-- Author: Md Shadhin --}}
{{-- Developer: Md Shadhin --}}
{{-- Copywrite: 2024 --}}

@extends('layout.app')

@section('title', 'Create A User')

@section('page-header')
    <div class="page-title">Create A User</div>

    <nav class="breadcrumb-style-one" aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item active"><a href="{{ route('users.index') }}">Create A User</a></li>
        </ol>
    </nav>
@endsection
@section('content')

    <div class="col-xxl-12 col-xl-12 col-lg-12 col-md-12 page-title-wrapper">
        <div class="row align-items-center">
            <div class="col-xl-4 col-lg-5 col-md-5 col-sm-7 filtered-list-search align-self-center">
                <div class="inner-page-title pt-1">Create A User</div>
            </div>
        </div>
    </div>

    <div class="col-xl-12 col-sm-12 layout-spacing">
        <div class="statbox widget box box-shadow">
            {{-- alert whene error --}}
            @if ($errors->any())
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>

                    <strong>Whoops!</strong> {{ $errors->first() }}
                </div>
            @endif
            <div class="row">
                <div class="col-xl-12 col-md-12 col-sm-12 col-12">
                    <form action="{{ route('users.store') }}" method="POST" class="needs-validation" novalidate>
                        @csrf

                        <div class="row mb-3">
                            <label class="col-lg-2 col-form-label " for="name">Name : </label>
                            <div class="col-lg-10">
                                <input type="text" class="form-control" name="name" id="name"
                                    placeholder="Enter Your Name">
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label class="col-lg-2 col-form-label " for="email">Email:</label>
                            <div class="col-lg-10">
                                <input type="email" class="form-control" name="email" id="email"
                                    placeholder="Enter Your Email">
                            </div>
                        </div>

                        {{-- new password --}}
                        <div class="row mb-3">
                            <label class="col-lg-2 col-form-label " for="password">Password:</label>
                            <div class="col-lg-10">
                                <input type="password" class="form-control" name="password" id="password"
                                    placeholder="Enter Your Password" autocomplete="new-password">
                            </div>
                        </div>
                        {{-- confirm password --}}
                        <div class="row mb-3">
                            <label class="col-lg-2 col-form-label " for="confirm_password">Confirm Password:</label>
                            <div class="col-lg-10">
                                <input type="password" class="form-control" name="confirm_password" id="confirm_password"
                                    placeholder="Enter Your Confirm Password" autocomplete="new-password">
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label class="col-lg-2 col-form-label " for="user_roles">Assign A role</label>
                            <div class="col-lg-10">
                                <select id="select-beast" class="form-select tom-select" multiple
                                    placeholder="Select a person..." autocomplete="off">
                                    <option value="">Select a person...</option>
                                    @foreach ($roles as $role)
                                        <option value="{{ $role->id }}">{{ $role->name }}</option>
                                    @endforeach
                                </select>
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

    {{-- 
@section('page_script')
    <script>
        
    </script>
@endsection --}}

@endSection
