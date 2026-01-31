{{-- Company: Wreetu Helth. --}}
{{-- Author: Md Shadhin --}}
{{-- Developer: Md Shadhin --}}
{{-- Copywrite: 2024 --}}

@extends('layout.app')

@section('title', 'Update A Users')

@section('page-header')
    <div class="page-title">Update A Users</div>

    <nav class="breadcrumb-style-one" aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item active"><a href="#">Update A Users</a></li>
        </ol>
    </nav>
@endsection
@section('content')

    <div class="col-xxl-12 col-xl-12 col-lg-12 col-md-12 page-title-wrapper">
        <div class="row align-items-center">
            <div class="col-xl-4 col-lg-5 col-md-5 col-sm-7 filtered-list-search align-self-center">
                <div class="inner-page-title pt-1">Update A Users</div>
            </div>
        </div>
    </div>

    <div class="col-xl-12 col-sm-12 layout-spacing">
        <div class="statbox widget box box-shadow">
            <div class="row">
                <div class="col-lg-12">
                    @if ($errors->any())
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>

                            <strong>Whoops!</strong> {{ $errors->first() }}
                        </div>
                    @endif
                </div>
                <div class="col-xl-12 col-md-12 col-sm-12 col-12">
                    <form action="{{ route('users.update', $user->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="row mb-3">
                            <label class="col-lg-2 col-form-label " for="name">Name: </label>
                            <div class="col-lg-10">
                                <input type="text" class="form-control" value="{{ old('name', $user->name) }}"
                                    name="name" id="name" placeholder="Enter Your Name">
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label class="col-lg-2 col-form-label " for="email">Email: </label>
                            <div class="col-lg-10">
                                <input type="email" class="form-control" value="{{ old('email', $user->email) }}"
                                    name="email" id="email" placeholder="Enter Your Email">
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label class="col-lg-2 col-form-label " for="roles">Roles: </label>
                            <div class="col-lg-10">

                                <select class="form-select tom-select" multiple name="roles[]" id="roles">
                                    <option value="">Select a Role...</option>
                                    @foreach ($roles as $role)
                                        <option value="{{ $role->id }}"
                                            {{ $user->roles->contains('id', $role->id) ? 'selected' : '' }}>
                                            {{ $role->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>


                        <div class="text-end">
                            <button type="submit" class="btn btn-primary">Update form</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>


@endSection
