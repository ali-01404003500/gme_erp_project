
@extends('layout.app')

@section('title', 'Roles List')

@section('page_style')
    <style>
        .avatar--group .avatar-xs {
            width: 1.8rem;
            height: 1.8rem;
            font-size: 1.125rem;
        }
    </style>
@endsection

@section('page-header')
    <div class="page-title">Roles List</div>

    <nav class="breadcrumb-style-one" aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item active"><a href="{{ route('access_control.roles.index') }}">Roles List</a></li>
        </ol>
    </nav>
@endsection
@section('content')
    <div class="dm-page-content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="breadcrumb-main">
                        <h4 class="text-capitalize breadcrumb-title">{{ trans('menu.role-create-title') }}</h4>
                        <div class="breadcrumb-action justify-content-center flex-wrap">
                            <nav aria-label="breadcrumb">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="#"><i
                                                class="las la-home"></i>{{ trans('menu.access-contro-title') }}</a></li>
                                    <li class="breadcrumb-item active" aria-current="page">
                                        {{ trans('menu.role-create-title') }}</li>
                                </ol>
                            </nav>
                        </div>
                    </div>
                </div>
                {{-- extra --}}

            </div>
            <div class="row">
                <div class="col-lg-12">
                    <div class="global-shadow border-light-0 p-30 bg-white radius-xl w-100 mb-30">
                        <div class="table-responsive">
                            <table id="zero-config"class="table dt-table-hover" data-page='@include('utils.table_paginate', ['data' => $roles])'
                                style="width:100%">
                                <thead>
                                    <tr>
                                        <th>Name</th>
                                        <th class="no-content">Users</th>
                                        <th class="no-content">Action</th>

                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($roles as $role)
                                        <tr>
                                            <td>{{ $role->name }}</td>
                                            <td>
                                                {{-- avater group --}}

                                                <div class="avatar--group">
                                                    @foreach ($role->users->take(3) as $user)
                                                        <span class="avatar avatar-light avatar-sm avatar-circle"
                                                            title="{{ $user->name }}">
                                                            @if (optional($user->profile)->avater)
                                                                <img alt="avatar-img"
                                                                    src="{{ asset('/src/assets/img/profile-30.png') }}"
                                                                    class="rounded-circle">
                                                            @else
                                                                <span class="avatar-letter">
                                                                    {{ $user->name[0] }}
                                                                </span>
                                                            @endif
                                                        </span>
                                                    @endforeach
                                                    @if ($role->users->count() > 3)
                                                        <div class="avatar avatar-xs" title="More users">
                                                            <span class="avatar-letter">
                                                                +{{ $role->users->count() - 3 }}
                                                            </span>
                                                        </div>
                                                    @endif
                                                </div>
                                            </td>
                                            <td class="no-content">
                                                <div class="btn-group btn-group-sm" role="group"
                                                    aria-label="Small button group">
                                                    <button class="btn btn-outline-success"
                                                        onclick="addUsers({{ $role->id }})" data-bs-toggle="modal"
                                                        data-bs-target="#asignRoleModal">
                                                        <i class="fa fa-user-plus"></i>
                                                    </button>@if ($role->id != 1)
                                                    <a class="btn btn-outline-warning"
                                                        onclick="editRole({{ $role->id }})"
                                                        href="{{ route('access_control.roles.edit', $role->id) }}"
                                                       
                                                         title="Edit"><i
                                                            class="far fa-edit"></i>
                                                    </a>@endif
                                                    <a class="btn btn-outline-danger delete-confirm @if ($role->id == 1) disabled @endif"
                                                        data-action="{{ route('access_control.roles.destroy', $role->id) }}" title="Delete"><i
                                                            class="far fa-trash-alt"></i></a>
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
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- modals --}}
    <div class="modal-basic modal fade show" id="asignRoleModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-md" role="document">
            <div class="modal-content modal-bg-white">
                <div class="modal-header">
                    <h6 class="modal-title"> Assign Role </h6>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                        {{-- <img src="http://45.33.34.15:8002/assets/img/svg/x.svg" alt="x" class="svg"> --}}
                    </button>
                </div>
                <div class="modal-body">

                </div>
            </div>
        </div>
    </div>
@endSection
@section('page_scripts')
    <script>
        function addUsers(id) {
            console.log(id);
            $('#asignRoleModal .modal-body').html(`
                <div class="d-flex justify-content-center">
                    <div class="dm-spin-dots spin-lg">
                        <span class="spin-dot badge-dot dot-primary"></span>
                        <span class="spin-dot badge-dot dot-primary"></span>
                        <span class="spin-dot badge-dot dot-primary"></span>
                        <span class="spin-dot badge-dot dot-primary"></span>
                    </div>
                <div>
            `);
            $('#asignRoleModal .modal-body').load('{{ route('access_control.roles.add_user_view', ':id') }}'.replace(
                ':id', id), function() {
                new TomSelect("#asignRoleModal .tom-select", {
                    plugins: ['remove_button'],
                    render: {
                        option: function(data, escape) {
                            let tag = '';

                            // length check
                            if (data.email && data.email.length > 11) {
                                tag = "<span class='badge bg-success ms-2'>Employee</span>";
                            } else {
                                tag = "<span class='badge bg-info ms-2'>Customer</span>";
                            }

                            console.log(data.avater, data.email);

                            return `
                                <div>
                                    ${escape(data.text)} ${tag}
                                    <p class='text-muted mb-0'>${data.email ?? ''}</p>
                                </div>
                            `;
 
                        }
                    }
                });

            });
        }
    </script>
@endsection
