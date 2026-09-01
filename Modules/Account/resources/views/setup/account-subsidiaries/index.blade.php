
@extends('layout.app')

@section('title', 'Account Subsidiaries')
@section('description', 'Account Subsidiaries')
@section('page-header')
    <i class="fa fa-list"></i> Account Subsidiaries
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
                                <li class="breadcrumb-item active" aria-current="page">Account Subsidiaries</li>
                            </ol>
                        </nav>
                    </div>
                    <div class="breadcrumb-main__wrapper">
                        <div class="action-btn mt-sm-0 mt-15">
                            @if (hasPermission('account.account-setup.account-subsidiaries.create'))
                                <button class="btn btn-xs btn-primary me-1" data-bs-toggle="modal" data-bs-target="#createModal">
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
                                <label for="filter_account_group_id">Account Group</label>

                                <select class="form-control tom-select" id="filter_account_group_id" name="account_group_id">
                                    <option value="">Select Account Group</option>
                                    @foreach ($accountGroups as $accountGroup)
                                        <option value="{{ $accountGroup->id }}"
                                            @if(request('account_group_id') == $accountGroup->id) selected @endif>
                                            {{ $accountGroup->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>


                            <div class="form-group">
                                <label for="filter_account_control_id">Account Control</label>
                                <select class="form-control tom-select"   id="filter_account_control_id"   name="account_control_id">
                                    <option value="">Select Account Control</option>
                                    @foreach ($accountControls as $key => $accountControl)
                                        <option value="{{ $accountControl->id }}" @if (request('account_control_id') == $accountControl->id) selected @endif>{{ $accountControl->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            
                        </x-table-filter-component>
                        <table id="zero-config" class="table dt-table-hover" data-page='@include('utils.table_paginate', ['data' => $accountSubsidiaries])' style="width:100%">
                            <thead>
                                <tr>
                                    <th class="text-center" style="width: 8%">Sl</th>
                                    <th class="text-center">Account Group</th>
                                    <th class="text-center">Account Control</th>
                                    <th class="text-center">Name</th>
                                    <th class="text-center no-content">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($accountSubsidiaries as $key => $accountSubsidiary)
                                    <tr>
                                        <td class="text-center">{{ ($accountSubsidiaries->currentPage() - 1) * $accountSubsidiaries->perPage() + $loop->iteration  }}</td>
                                        <td class="text-center">{{ $accountSubsidiary->accountGroup->name ?? '' }}</td>
                                        <td class="text-center">({{ $accountSubsidiary->account_control_id }}) - {{ $accountSubsidiary->accountControl->name ?? '' }}</td>
                                        <td class="text-center">({{ $accountSubsidiary->id }}) - {{ $accountSubsidiary->name }}</td>
                                    
                                        <td class="text-center">
                                            <div class="btn-group btn-group-sm" role="group"
                                                aria-label="Small button group">

                                                @if (hasPermission('account.account-setup.account-subsidiaries.update'))
                                                    <button type="button" data-action="{{ route('account.account-setup.account-subsidiaries.update', $accountSubsidiary->id) }}" data-data="{{$accountSubsidiary}}" class="btn btn-outline-primary btn-edit" data-toggle="tooltip" data-placement="top" title="Edit"
                                                    data-bs-toggle="modal" data-bs-target="#editModal" 
                                                    @if($accountSubsidiary->is_deletable == 0) disabled @endif>
                                                        <i class="far fa-edit"></i>
                                                    </button>
                                                @endif



                                                @if (hasPermission('account.account-setup.account-subsidiaries.destroy'))
                                                    <button type="button"
                                                        data-action="{{ route('account.account-setup.account-subsidiaries.destroy', $accountSubsidiary->id) }}"
                                                        class="btn btn-outline-danger delete-confirm" title="Delete"
                                                        @if($accountSubsidiary->is_deletable == 0) disabled @endif title="Delete"><i
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
                    <h5 class="modal-title" id="createModalLabel">Create Account Subsidiaries</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('account.account-setup.account-subsidiaries.store') }}" method="post" id="createForm">
                        @csrf
                        <div class="form-group">
                            <label for="create_account_group_id">Account Group</label>
                            <select class="form-control tom-select" id="create_account_group_id" name="account_group_id" required>
                                <option value="">Select Account Group</option>
                                @foreach ($accountGroups as $key => $accountGroup)
                                    <option value="{{ $accountGroup->id }}">{{ $accountGroup->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="create_account_control_id">Account Control</label>
                            <select class="form-control tom-select" id="create_account_control_id" name="account_control_id" required>
                                <option value="">Select Account Control</option>
                                @foreach ($accountControls as $key => $accountControl)
                                    <option value="{{ $accountControl->id }}">{{ $accountControl->name }}</option>
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

    <!-- Edit Modal -->
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
                            <label for="edit_account_group_id">Account Group</label>
                            <select class="form-control tom-select" id="edit_account_group_id" name="account_group_id" required>
                                <option value="">Select Account Group</option>
                                @foreach ($accountGroups as $key => $accountGroup)
                                    <option value="{{ $accountGroup->id }}">{{ $accountGroup->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="edit_account_control_id">Account Control</label>
                            <select class="form-control tom-select" id="edit_account_control_id" name="account_control_id" required>
                                <option value="">Select Account Control</option>
                                @foreach ($accountControls as $key => $accountControl)
                                    <option value="{{ $accountControl->id }}">{{ $accountControl->name }}</option>
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
    $(document).ready(function () {
        /*
        |--------------------------------------------------------------------------
        | Load Account Controls
        |--------------------------------------------------------------------------
        */

        function loadAccountControls(groupId, controlSelectId, selectedControlId = null) {
            let controlElement = $(controlSelectId)[0];
            if (!controlElement || !controlElement.tomselect) {
                console.error('TomSelect not found:', controlSelectId);
                return;
            }
            let controlSelect = controlElement.tomselect;
            // Clear old values
            controlSelect.clear();
            controlSelect.clearOptions();

            // Default option
            // controlSelect.addOption({
            //     value: '',
            //     text: 'Select Account Control'
            // });

            if (!groupId) {
                controlSelect.refreshOptions(false);
                return;
            }

            $.ajax({
                url: "{{ route('account.account-setup.account.controls', ':id') }}".replace(':id', groupId),
                type: "GET",
                dataType: "json",
                success: function (response) {
                    $.each(response, function (key, accountControl) {
                        controlSelect.addOption({
                            value: accountControl.id,
                            text: accountControl.name
                        });

                    });
                    controlSelect.refreshOptions(false);
                    // For Edit / Filter selected value
                    if (selectedControlId) {
                        controlSelect.setValue(selectedControlId, true);
                    }
                },
                error: function (xhr) {
                    console.error(
                        'Account Control Error:',
                        xhr.responseText
                    );
                }
            });
        }


        /*
        |--------------------------------------------------------------------------
        | FILTER
        |--------------------------------------------------------------------------
        */

        $('#filter_account_group_id').on('change', function () {
            loadAccountControls(
                $(this).val(),
                '#filter_account_control_id'
            );

        });


        /*
        |--------------------------------------------------------------------------
        | CREATE
        |--------------------------------------------------------------------------
        */

        $('#create_account_group_id').on('change', function () {
            loadAccountControls(
                $(this).val(),
                '#create_account_control_id'
            );

        });


        /*
        |--------------------------------------------------------------------------
        | EDIT GROUP CHANGE
        |--------------------------------------------------------------------------
        */

        $('#edit_account_group_id').on('change', function () {
            loadAccountControls(
                $(this).val(),
                '#edit_account_control_id'
            );

        });


        /*
        |--------------------------------------------------------------------------
        | EDIT BUTTON
        |--------------------------------------------------------------------------
        */

        $(document).on('click', '.btn-edit', function () {
            const data = $(this).data('data');
            let groupId = data.account_group_id;
            let controlId = data.account_control_id;

            // Set other inputs
            $('#editModal input[name="name"]').val(data.name);

            // Set Group
            let groupElement = $('#edit_account_group_id')[0];
            if (groupElement && groupElement.tomselect) {

                // true = silent, avoids triggering change event
                groupElement.tomselect.setValue(groupId, true);

                // Load controls then select existing control
                loadAccountControls(
                    groupId,
                    '#edit_account_control_id',
                    controlId
                );
            }

            // Set form action
            $("#editFrom").attr(
                "action",
                $(this).data('action')
            );

        });


        /*
        |--------------------------------------------------------------------------
        | FILTER PAGE RELOAD
        |--------------------------------------------------------------------------
        */

        let selectedGroupId = "{{ request('account_group_id') }}";
        let selectedControlId = "{{ request('account_control_id') }}";

        if (selectedGroupId) {
            loadAccountControls(
                selectedGroupId,
                '#filter_account_control_id',
                selectedControlId
            );

        }

    });
</script> 
@endsection

