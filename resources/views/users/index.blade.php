{{-- Company: Wreetu Helth. --}}
{{-- Author: Md Shadhin --}}
{{-- Developer: Md Shadhin --}}
{{-- Copywrite: 2024 --}}
@extends('layout.app')

@section('title', 'Users List')

@section('page-header')
    <div class="page-title">Users List</div>

    <nav class="breadcrumb-style-one" aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item active"><a href="{{ route('users.index') }}">Users List</a></li>
        </ol>
    </nav>
@endsection
@section('content')

    <div class="col-xxl-12 col-xl-12 col-lg-12 col-md-12 page-title-wrapper">
        <div class="row align-items-center">
            <div class="col-xl-4 col-lg-5 col-md-5 col-sm-7 filtered-list-search align-self-center">
                <div class="inner-page-title pt-1">Users List</div>
            </div>

            <div class="col-xl-8 col-lg-7 col-md-7 col-sm-5 text-end">
                <a class="btn btn-primary me-1" href="{{ route('users.create') }}">
                    Create A User
                </a>
            </div>
        </div>
    </div>

    <div class="col-xl-12 col-lg-12 col-sm-12  layout-spacing">
        <div class="widget-content widget-content-area br-8">
            <table id="zero-config"class="table dt-table-hover" data-page='@include('utils.table_paginate', ['data' => $users])' style="width:100%">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Email</th>
                        <th class="no-content">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($users as $user)
                        <tr>
                            <td>{{ $user->name }}</td>
                            <td>{{ $user->email }}</td>
                            <td>
                                <div class="btn-group btn-group-sm" role="group" aria-label="Small button group">
                                    @if (hasPermission('users.update'))
                                        <a class="btn btn-outline-warning" href="{{ route('users.edit', $user->id) }}"><i
                                                class="far fa-edit"></i></a>
                                    @endif

                                    @if (hasPermission('users.update'))
                                        <a href="{{ route('users.edit_password', $user->id) }}"
                                            class="btn btn-outline-info">
                                            <i class="fas fa-key"></i>
                                        </a>
                                    @endif

                                    @if (hasPermission('users.destroy'))
                                        <button type="button" data-action="{{ route('users.destroy', $user->id) }}"
                                            class="btn btn-outline-danger delete-confirm"><i
                                                class="far fa-trash-alt"></i></button>
                                    @endif

                                    @if (hasPermission('users.view'))
                                        <a class="btn btn-outline-primary"><i class="fas fa-eye"></i></a>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="d-none">
                <form class="delete-form" action="" method="POST">
                    @csrf
                    @method('DELETE')
                </form>
            </div>
        </div>
        <div class="row text-end">

        </div>
    </div>
@endSection
