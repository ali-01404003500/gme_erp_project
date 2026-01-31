{{-- Modules/Account/resources/views/setup/account-control/index.blade.php --}}

@extends('layout.app')

@section('title', 'Account Control')
@section('description', 'Account Control')
@section('page-header')
    <i class="fa fa-list"></i> Account Control
@stop

@push('style')
    <link rel="stylesheet" href="{{ asset('assets/css/chosen.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/css/bootstrap-datepicker3.min.css') }}" />
@endpush

@section('content')
    <div class="social-dash-wrap">
        <div class="row">
            <div class="col-lg-12">
                <div class="breadcrumb-main">
                    <div class="breadcrumb-action justify-content-center flex-wrap">
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="/"><i class="las la-home"></i>Home</a></li>
                                <li class="breadcrumb-item active" aria-current="page">Account Control</li>
                            </ol>
                        </nav>
                    </div>
                    <div class="breadcrumb-main__wrapper">
                        <div class="action-btn mt-sm-0 mt-15">
                            @if (hasPermission('account.account-setup.account-controls.create'))
                                <button class="btn btn-xs btn-primary me-1" data-bs-toggle="modal"
                                    data-bs-target="#createModal">
                                    Add New
                                </button>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <div class="card mb-4">
                    <div class="card-body">
                        <x-table-filter-component >
                            <div class="form-group" >
                                <label for="name">Name</label>
                                <input type="text" class="form-control" id="name" name="name" value="{{ request('name') }}">
                            </div>

                            <div class="form-group">
                                <label for="account_group_id">Account Group</label>
                                <select class="form-control tom-select" id="account_group_id" name="account_group_id">
                                    <option value="">Select Account Group</option>
                                    @foreach ($accountGroups as $key => $accountGroup)
                                        <option value="{{ $accountGroup->id }}" @if (request('account_group_id') == $accountGroup->id) selected @endif>{{ $accountGroup->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </x-table-filter-component>
                        <table id="zero-config" class="table dt-table-hover" data-page='@include('utils.table_paginate', ['data' => $accountControls])'
                            style="width:100%">
                            <thead>
                                <tr>
                                    <th class="text-center" style="width: 8%">Sl</th>
                                    <th class="text-center">Account Group Name</th>
                                    <th class="text-center">Name</th>
                                    <th class="text-center no-content">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($accountControls as $key => $accountControl)
                                    <tr>
<td class="text-center">{{ ($accountControls->currentPage() - 1) * $accountControls->perPage() + $loop->iteration  }}</td>
                                        <td class="text-center">{{ $accountControl->accountGroup->name }}</td>
                                        <td class="text-center">{{ $accountControl->name }}</td>
                                        <td class="text-center">
                                            <div class="btn-group btn-group-sm" role="group"
                                                aria-label="Small button group">

                                                @if (hasPermission('account.account-setup.account-controls.update'))
                                                    <button type="button" data-action="{{ route('account.account-setup.account-controls.update', $accountControl->id) }}" data-data="{{$accountControl}}" class="btn btn-outline-primary btn-edit" data-toggle="tooltip" data-placement="top" title="Edit"
                                                    data-bs-toggle="modal" data-bs-target="#editModal"
                                                        @if($accountControl->is_deletable == 0) disabled @endif
                                                    >
                                                        <i class="far fa-edit"></i>
                                                    </button>
                                                @endif



                                                @if (hasPermission('account.account-setup.account-controls.destroy'))
                                                    <button type="button"
                                                        data-action="{{ route('account.account-setup.account-controls.destroy', $accountControl->id) }}"
                                                        @if($accountControl->is_deletable == 0) disabled @endif
                                                        class="btn btn-outline-danger delete-confirm" title="Delete" title="Delete"><i
                                                            class="far fa-trash-alt"></i></button>
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
                </div>
            </div>
        </div>
    </div>

    <!-- Create Modal -->
    <div class="modal fade inputForm-modal" id="createModal" tabindex="-1" role="dialog"
        aria-labelledby="createModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-md" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="createModalLabel">Create Account Control</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('account.account-setup.account-controls.store') }}" method="post" id="createForm">
                        @csrf
                        <div class="form-group">
                            <label for="account_group_id">Account Group</label>
                            <select class="form-control tom-select" id="account_group_id" name="account_group_id" required>
                                <option value="">Select Account Group</option>
                                @foreach ($accountGroups as $key => $accountGroup)
                                    <option value="{{ $accountGroup->id }}">{{ $accountGroup->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="name">Name</label>
                            <input type="text" class="form-control" id="name" name="name" required>
                        </div>
                        <button type="submit" class="btn btn-primary">Create</button>
                    </form>
                </div>
            </div>
        </div>
    </div>


    <div class="modal fade inputForm-modal" id="editModal" tabindex="-1" role="dialog" aria-labelledby="editModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-md" role="document">
            <div class="modal-content">

                <div class="modal-header" id="editModalLabel">
                    <h5 class="modal-title">Edit </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
                </div>
                <form action="" method="post" id="editFrom">
                    @csrf
                    @method('put')
                    <div class="modal-body">

                        <div class="form-group">
                            <label for="account_group_id">Account Group</label>
                            <select class="form-control tom-select" id="account_group_id" name="account_group_id" required>
                                <option value="">Select Account Group</option>
                                @foreach ($accountGroups as $key => $accountGroup)
                                    <option value="{{ $accountGroup->id }}">{{ $accountGroup->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="row mb-4">
                            <label class="col-sm-12 col-form-label">Name</label>
                            <div class="col-sm-12">
                                <input type="text" name="name" id="name" class="form-control"
                                    placeholder=" Name *" required>
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-light-danger mt-2 mb-2 btn-no-effect"
                            data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary mt-2 mb-2 btn-no-effect">Update</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection

@section('page_scripts')

    <script>
        $(document).ready(function(e) {
            $(document).on('click', '.btn-edit', function() {
                const data = $(this).data('data');
                //loop through data object
                $.each(data, function(key, value) {
                    $('#editModal input[name="' + key + '"]').val(value);

                    $('#editModal select[name="' + key + '"]').val(value);
                    $('#editModal select[name="' + key + '"]').prop('tomselect')?.setValue(value);
                    
                    // console.log();
                })
                $("#editFrom").attr("action", $(this).data('action'));
            });
        });
    </script>
@endsection