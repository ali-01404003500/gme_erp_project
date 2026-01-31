
@extends('layout.app')

@section('title', 'Chart of Account')
@section('description', 'Chart of Account')
@section('page-header')
    <i class="fa fa-list"></i> Chart of Accout 
@stop

@section('content')
    <div class="social-dash-wrap">
        <div class="row">
            <div class="col-lg-12">
                <div class="breadcrumb-main">
                    <div class="breadcrumb-action justify-content-center flex-wrap">
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="/"><i class="las la-home"></i>Home</a></li>
                                <li class="breadcrumb-item active" aria-current="page">
                                    {{ trans('Chart of Account') }}</li>
                            </ol>
                        </nav>
                    </div>
                    <div class="breadcrumb-main__wrapper">
                        <div class="action-btn mt-sm-0 mt-15">
                            @if (hasPermission('account.account-setup.accounts.create'))
                                <a href="{{ route('account.account-setup.accounts.create') }}" class="btn btn-primary btn-sm d-inline-block mr-2">
                                    <i class="las la-plus"></i>
                                    Add New
                                </a>
                            @endif
                            <a href="{{ route('account.account-setup.accounts.index') }}?export_type=pdf&{{ http_build_query(request()->except('export_type', '_token')) }}" target="_blank"
                                class="btn btn-danger btn-sm d-inline-block mr-2">
                                <i class="las la-file-pdf fs-16"></i> PDF
                            </a>
                            <a href="{{ route('account.account-setup.accounts.index') }}?export_type=excel&{{ http_build_query(request()->except('export_type', '_token')) }}" target="_blank"
                                class="btn btn-success btn-sm d-inline-block">
                                <i class="las la-file-excel fs-16"></i> Excel
                            </a> 
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
                            <div class="form-group">
                                <label for="account_control_id">Account Control</label>
                                <select class="form-control tom-select" id="account_control_id" name="account_control_id">
                                    <option value="">Select Account Control</option>
                                    @foreach ($accountControls as $key => $accountControl)
                                        <option value="{{ $accountControl->id }}" @if (request('account_control_id') == $accountControl->id) selected @endif>{{ $accountControl->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="account_subsidiary_id">Account Subsidiary</label>
                                <select class="form-control tom-select" id="account_subsidiary_id" name="account_subsidiary_id">
                                    <option value="">Select Account Subsidiary</option>
                                    @foreach ($accountSubsidiaries as $key => $accountSubsidiary)
                                        <option value="{{ $accountSubsidiary->id }}" @if (request('account_subsidiary_id') == $accountSubsidiary->id) selected @endif>{{ $accountSubsidiary->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </x-table-filter-component>
                        <table id="zero-config" class="table dt-table-hover" data-page='@include('utils.table_paginate', ['data' => $accounts])' style="width:100%">
                            <thead>
                                <tr>
                                    <th class="text-center" style="width: 8%">Sl</th>
                                    <th class="text-center">Account Group</th>
                                    <th class="text-center">Account Control</th>
                                    <th class="text-center">Account Subsidiary</th>
                                    <th class="text-center">Name</th>
                                    <th class="text-center no-content">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($accounts as $key => $accountSubsidiary)
                                    <tr>
<td class="text-center">{{ ($accounts->currentPage() - 1) * $accounts->perPage() + $loop->iteration  }}</td>
                                        <td class="text-center">{{ $accountSubsidiary->accountGroup->name ?? '' }}</td>
                                        <td class="text-center">{{ $accountSubsidiary->accountControl->name ?? '' }}</td>
                                        <td class="text-center">{{ $accountSubsidiary->accountSubsidiary->name ?? '' }}</td>
                                        <td class="text-center">{{ $accountSubsidiary->name }}</td>
                                    
                                        <td class="text-center">
                                            <div class="btn-group btn-group-sm" role="group" aria-label="Small button group">
                                                @if (hasPermission('account.account-setup.accounts.update'))
                                                    <button type="button" data-action="{{ route('account.account-setup.accounts.update', $accountSubsidiary->id) }}" data-data="{{$accountSubsidiary}}" class="btn btn-outline-primary btn-edit" data-toggle="tooltip" data-placement="top" title="Edit"
                                                    data-bs-toggle="modal" data-bs-target="#editModal">
                                                        <i class="far fa-edit"></i>
                                                    </button>
                                                @endif
                                                @if (hasPermission('account.account-setup.accounts.destroy') )
                                                    <button type="button"
                                                        data-action="{{ route('account.account-setup.accounts.destroy', $accountSubsidiary->id) }}" 
                                                        @if($accountSubsidiary->is_deletable == 0) disabled @endif
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
                             <form action="" method="POST" class="delete-form">
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
                    <h5 class="modal-title" id="createModalLabel">Create Chart of Account</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('account.account-setup.accounts.store') }}" method="post" id="createForm">
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
                            <label for="account_control_id">Account Control</label>
                            <select class="form-control tom-select" id="account_control_id" name="account_control_id" required>
                                <option value="">Select Account Control</option>
                                @foreach ($accountControls as $key => $accountControl)
                                    <option value="{{ $accountControl->id }}">{{ $accountControl->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="account_subsidiary_id">Account Subsidiaries</label>
                            <select class="form-control tom-select" id="account_subsidiary_id" name="account_subsidiary_id" required>
                                <option value="">Select Account Subsidiaries</option>
                                @foreach ($accountSubsidiaries as $key => $accountSubsidiary)
                                    <option value="{{ $accountSubsidiary->id }}">{{ $accountSubsidiary->name }}</option>
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

                        <div class="form-group">
                            <label for="account_control_id">Account Control</label>
                            <select class="form-control tom-select" id="account_control_id" name="account_control_id" required>
                                <option value="">Select Account Control</option>
                                @foreach ($accountControls as $key => $accountControl)
                                    <option value="{{ $accountControl->id }}">{{ $accountControl->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="account_subsidiary_id">Account Subsidiaries</label>
                            <select class="form-control tom-select" id="account_subsidiary_id" name="account_subsidiary_id" required>
                                <option value="">Select Account Subsidiaries</option>
                                @foreach ($accountSubsidiaries as $key => $accountSubsidiary)
                                    <option value="{{ $accountSubsidiary->id }}">{{ $accountSubsidiary->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="row mb-4">
                            <label class="col-sm-12 col-form-label">Name</label>
                            <div class="col-sm-12">
                                <input type="text" name="name" id="name" class="form-control"
                                    placeholder=" Name *" required autocomplete="off">
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