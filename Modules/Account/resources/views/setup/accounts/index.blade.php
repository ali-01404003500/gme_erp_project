@extends('layout.app')

@section('title', 'Chart of Account')
@section('description', 'Chart of Account')

@section('page-header')
    <i class="fa fa-list"></i> Chart of Account
@stop

@section('content')

    <div class="social-dash-wrap">

        <div class="row">
            <div class="col-lg-12">

                <div class="breadcrumb-main">

                    <div class="breadcrumb-action justify-content-center flex-wrap">

                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb">

                                <li class="breadcrumb-item">
                                    <a href="/">
                                        <i class="las la-home"></i>Home
                                    </a>
                                </li>

                                <li class="breadcrumb-item active" aria-current="page">
                                    {{ trans('Chart of Account') }}
                                </li>

                            </ol>
                        </nav>

                    </div>

                    <div class="breadcrumb-main__wrapper">

                        <div class="action-btn mt-sm-0 mt-15">

                            @if (hasPermission('account.account-setup.accounts.create'))

                                <a href="#"
                                   data-bs-toggle="modal"
                                   data-bs-target="#createModal"
                                   class="btn btn-primary btn-sm d-inline-block mr-2">

                                    <i class="las la-plus"></i>
                                    Add New

                                </a>

                            @endif

                            <a href="{{ route('account.account-setup.accounts.index') }}?export_type=pdf&{{ http_build_query(request()->except('export_type', '_token')) }}"
                               target="_blank"
                               class="btn btn-danger btn-sm d-inline-block mr-2">

                                <i class="las la-file-pdf fs-16"></i>
                                PDF

                            </a>

                            <a href="{{ route('account.account-setup.accounts.index') }}?export_type=excel&{{ http_build_query(request()->except('export_type', '_token')) }}"
                               target="_blank"
                               class="btn btn-success btn-sm d-inline-block">

                                <i class="las la-file-excel fs-16"></i>
                                Excel

                            </a>

                        </div>

                    </div>

                </div>

            </div>
        </div>


        {{-- ============================================================
            FILTER
        ============================================================= --}}

        <div class="row">

            <div class="col-md-12">

                <div class="card mb-4">

                    <div class="card-body">

                        <x-table-filter-component>

                            {{-- Name --}}
                            <div class="form-group">

                                <label for="filter_name">
                                    Name
                                </label>

                                <input type="text"
                                       class="form-control"
                                       id="filter_name"
                                       name="name"
                                       value="{{ request('name') }}">

                            </div>


                            {{-- Account Group --}}
                            <div class="form-group">

                                <label for="filter_account_group_id">
                                    Account Group
                                </label>

                                <select class="form-control tom-select"
                                        id="filter_account_group_id"
                                        name="account_group_id">

                                    <option value="">
                                        Select Account Group
                                    </option>

                                    @foreach ($accountGroups as $accountGroup)

                                        <option value="{{ $accountGroup->id }}"
                                            {{ request('account_group_id') == $accountGroup->id ? 'selected' : '' }}>

                                            {{ $accountGroup->name }}

                                        </option>

                                    @endforeach

                                </select>

                            </div>


                            {{-- Account Control --}}
                            <div class="form-group">

                                <label for="filter_account_control_id">
                                    Account Control
                                </label>

                                <select class="form-control tom-select"
                                        id="filter_account_control_id"
                                        name="account_control_id">

                                    <option value="">
                                        Select Account Control
                                    </option>

                                    @foreach ($accountControls as $accountControl)

                                        <option value="{{ $accountControl->id }}"
                                            {{ request('account_control_id') == $accountControl->id ? 'selected' : '' }}>

                                            {{ $accountControl->name }}

                                        </option>

                                    @endforeach

                                </select>

                            </div>


                            {{-- Account Subsidiary --}}
                            <div class="form-group">

                                <label for="filter_account_subsidiary_id">
                                    Account Subsidiary
                                </label>

                                <select class="form-control tom-select"
                                        id="filter_account_subsidiary_id"
                                        name="account_subsidiary_id">

                                    <option value="">
                                        Select Account Subsidiary
                                    </option>

                                    @foreach ($accountSubsidiaries as $accountSubsidiary)

                                        <option value="{{ $accountSubsidiary->id }}"
                                            {{ request('account_subsidiary_id') == $accountSubsidiary->id ? 'selected' : '' }}>

                                            {{ $accountSubsidiary->name }}

                                        </option>

                                    @endforeach

                                </select>

                            </div>

                        </x-table-filter-component>


                        {{-- ====================================================
                            TABLE
                        ===================================================== --}}

                        <table id="zero-config"
                               class="table dt-table-hover"
                               data-page='@include('utils.table_paginate', ['data' => $accounts])'
                               style="width:100%">

                            <thead>

                                <tr>

                                    <th class="text-center" style="width: 8%">
                                        Sl
                                    </th>

                                    <th class="text-center">
                                        Account Group
                                    </th>

                                    <th class="text-center">
                                        Account Control
                                    </th>

                                    <th class="text-center">
                                        Account Subsidiary
                                    </th>

                                    <th class="text-center">
                                        Name
                                    </th>

                                    <th class="text-center no-content">
                                        Action
                                    </th>

                                </tr>

                            </thead>

                            <tbody>

                                @foreach ($accounts as $key => $accountSubsidiary)

                                    <tr>

                                        <td class="text-center">
                                            {{ ($accounts->currentPage() - 1) * $accounts->perPage() + $loop->iteration }}
                                        </td>

                                        <td class="text-center">
                                            {{ $accountSubsidiary->accountGroup->name ?? '' }}
                                        </td>

                                        <td class="text-center">
                                            {{ $accountSubsidiary->accountControl->name ?? '' }}
                                        </td>

                                        <td class="text-center">
                                            {{ $accountSubsidiary->accountSubsidiary->name ?? '' }}
                                        </td>

                                        <td class="text-center">
                                            {{ $accountSubsidiary->name }}
                                        </td>

                                        <td class="text-center">

                                            <div class="btn-group btn-group-sm"
                                                 role="group"
                                                 aria-label="Small button group">

                                                @if (hasPermission('account.account-setup.accounts.update'))

                                                    <button type="button"
                                                            data-action="{{ route('account.account-setup.accounts.update', $accountSubsidiary->id) }}"
                                                            data-data="{{ $accountSubsidiary }}"
                                                            class="btn btn-outline-primary btn-edit"
                                                            data-toggle="tooltip"
                                                            data-placement="top"
                                                            title="Edit"
                                                            data-bs-toggle="modal"
                                                            data-bs-target="#editModal">

                                                        <i class="far fa-edit"></i>

                                                    </button>

                                                @endif


                                                @if (hasPermission('account.account-setup.accounts.destroy'))

                                                    <button type="button"
                                                            data-action="{{ route('account.account-setup.accounts.destroy', $accountSubsidiary->id) }}"
                                                            @if ($accountSubsidiary->is_deletable == 0) disabled @endif
                                                            class="btn btn-outline-danger delete-confirm"
                                                            title="Delete">

                                                        <i class="far fa-trash-alt"></i>

                                                    </button>

                                                @endif

                                            </div>

                                        </td>

                                    </tr>

                                @endforeach

                            </tbody>

                        </table>


                        {{-- Delete Form --}}
                        <div class="d-none">

                            <form action=""
                                  method="POST"
                                  class="delete-form">

                                @csrf
                                @method('DELETE')

                            </form>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- ================================================================
        CREATE MODAL
    ================================================================= --}}

    <div class="modal fade inputForm-modal"
         id="createModal"
         tabindex="-1"
         role="dialog"
         aria-labelledby="createModalLabel"
         aria-hidden="true">

        <div class="modal-dialog modal-md" role="document">

            <div class="modal-content">

                <div class="modal-header">

                    <h5 class="modal-title" id="createModalLabel">
                        Create Chart of Account
                    </h5>

                    <button type="button"
                            class="btn-close"
                            data-bs-dismiss="modal"
                            aria-label="Close">
                    </button>

                </div>


                <div class="modal-body">

                    <form action="{{ route('account.account-setup.accounts.store') }}"
                          method="post"
                          id="createForm">

                        @csrf


                        {{-- Create Account Group --}}
                        <div class="form-group">

                            <label for="create_account_group_id">
                                Account Group
                            </label>

                            <select class="form-control tom-select"
                                    id="create_account_group_id"
                                    name="account_group_id"
                                    required>

                                <option value="">
                                    Select Account Group
                                </option>

                                @foreach ($accountGroups as $accountGroup)

                                    <option value="{{ $accountGroup->id }}">
                                        {{ $accountGroup->name }}
                                    </option>

                                @endforeach

                            </select>

                        </div>


                        {{-- Create Account Control --}}
                        <div class="form-group">

                            <label for="create_account_control_id">
                                Account Control
                            </label>

                            <select class="form-control tom-select"
                                    id="create_account_control_id"
                                    name="account_control_id"
                                    required>

                                <option value="">
                                    Account Subsidiaries blade a filter kaj korechi
                                </option>

                            </select>

                        </div>


                        {{-- Create Account Subsidiary --}}
                        <div class="form-group">

                            <label for="create_account_subsidiary_id">
                                Account Subsidiary
                            </label>

                            <select class="form-control tom-select"
                                    id="create_account_subsidiary_id"
                                    name="account_subsidiary_id"
                                    required>

                                <option value="">
                                    Select Account Subsidiary
                                </option>

                            </select>

                        </div>


                        {{-- Name --}}
                        <div class="form-group">

                            <label for="create_name">
                                Name
                            </label>

                            <input type="text"
                                   class="form-control"
                                   id="create_name"
                                   name="name"
                                   required>

                        </div>


                        <button type="submit"
                                class="btn btn-primary">

                            Create

                        </button>

                    </form>

                </div>

            </div>

        </div>

    </div>


    {{-- ================================================================
        EDIT MODAL
    ================================================================= --}}

    <div class="modal fade inputForm-modal"
         id="editModal"
         tabindex="-1"
         role="dialog"
         aria-labelledby="editModalLabel"
         aria-hidden="true">

        <div class="modal-dialog modal-md" role="document">

            <div class="modal-content">

                <div class="modal-header"
                     id="editModalLabel">

                    <h5 class="modal-title">
                        Edit
                    </h5>

                    <button type="button"
                            class="btn-close"
                            data-bs-dismiss="modal"
                            aria-hidden="true">
                    </button>

                </div>


                <form action=""
                      method="post"
                      id="editFrom">

                    @csrf
                    @method('put')


                    <div class="modal-body">


                        {{-- Edit Account Group --}}
                        <div class="form-group">

                            <label for="edit_account_group_id">
                                Account Group
                            </label>

                            <select class="form-control tom-select"
                                    id="edit_account_group_id"
                                    name="account_group_id"
                                    required>

                                <option value="">
                                    Select Account Group
                                </option>

                                @foreach ($accountGroups as $accountGroup)

                                    <option value="{{ $accountGroup->id }}">
                                        {{ $accountGroup->name }}
                                    </option>

                                @endforeach

                            </select>

                        </div>


                        {{-- Edit Account Control --}}
                        <div class="form-group">

                            <label for="edit_account_control_id">
                                Account Control
                            </label>

                            <select class="form-control tom-select"
                                    id="edit_account_control_id"
                                    name="account_control_id"
                                    required>

                                <option value="">
                                    Select Account Control
                                </option>

                            </select>

                        </div>


                        {{-- Edit Account Subsidiary --}}
                        <div class="form-group">

                            <label for="edit_account_subsidiary_id">
                                Account Subsidiary
                            </label>

                            <select class="form-control tom-select"
                                    id="edit_account_subsidiary_id"
                                    name="account_subsidiary_id"
                                    required>

                                <option value="">
                                    Select Account Subsidiary
                                </option>

                            </select>

                        </div>


                        {{-- Name --}}
                        <div class="row mb-4">

                            <label class="col-sm-12 col-form-label">
                                Name
                            </label>

                            <div class="col-sm-12">

                                <input type="text"
                                       name="name"
                                       id="edit_name"
                                       class="form-control"
                                       placeholder="Name *"
                                       required
                                       autocomplete="off">

                            </div>

                        </div>

                    </div>


                    <div class="modal-footer">

                        <button type="button"
                                class="btn btn-light-danger mt-2 mb-2 btn-no-effect"
                                data-bs-dismiss="modal">

                            Cancel

                        </button>

                        <button type="submit"
                                class="btn btn-primary mt-2 mb-2 btn-no-effect">

                            Update

                        </button>

                    </div>

                </form>

            </div>

        </div>

    </div>

@endsection



@section('page_scripts')

<script>

$(document).ready(function () {


    /* ============================================================
       COMMON FUNCTIONS
    ============================================================ */


    function loadControls(groupId, selectId, selectedId = null, callback = null)
    {
        let selectElement = $(selectId)[0];

        if (!selectElement || !selectElement.tomselect) {
            console.error('Tom Select not initialized:', selectId);
            return;
        }

        let tomSelect = selectElement.tomselect;


        // Clear existing options
        tomSelect.clear();
        tomSelect.clearOptions();


        if (!groupId) {

            if (callback) {
                callback();
            }

            return;
        }


        $.ajax({

            url: "{{ route('account.account-setup.account.controls', ':id') }}".replace(':id', groupId),
            

            type: "GET",

            dataType: "json",

            success: function (data) {


                $.each(data, function (key, item) {

                    tomSelect.addOption({

                        value: item.id,

                        text: item.name

                    });

                });


                tomSelect.refreshOptions(false);


                // Set selected value
                if (selectedId) {

                    tomSelect.setValue(selectedId);

                }


                if (callback) {
                    callback();
                }

            },

            error: function (xhr) {

                console.error('Account Control Error:', xhr.responseText);

            }

        });

    }



    function loadSubsidiaries(controlId, selectId, selectedId = null)
    {
        let selectElement = $(selectId)[0];

        if (!selectElement || !selectElement.tomselect) {
            console.error('Tom Select not initialized:', selectId);
            return;
        }

        let tomSelect = selectElement.tomselect;


        // Clear existing options
        tomSelect.clear();
        tomSelect.clearOptions();


        if (!controlId) {
            return;
        }


        $.ajax({

            url: "{{ route('account.account-setup.account.subsidiaries', ':id') }}".replace(':id', controlId),
            

            type: "GET",

            dataType: "json",

            success: function (data) {


                $.each(data, function (key, item) {

                    tomSelect.addOption({

                        value: item.id,

                        text: item.name

                    });

                });


                tomSelect.refreshOptions(false);


                // Set selected value
                if (selectedId) {

                    tomSelect.setValue(selectedId);

                }

            },

            error: function (xhr) {

                console.error('Account Subsidiary Error:', xhr.responseText);

            }

        });

    }



    /* ============================================================
       FILTER
       Account Group -> Account Control
    ============================================================ */


    $('#filter_account_group_id').on('change', function () {

        let groupId = $(this).val();


        loadControls(
            groupId,
            '#filter_account_control_id'
        );


        // Clear subsidiary
        let subsidiaryElement = $('#filter_account_subsidiary_id')[0];

        if (subsidiaryElement && subsidiaryElement.tomselect) {

            subsidiaryElement.tomselect.clear();

            subsidiaryElement.tomselect.clearOptions();

        }

    });



    /* ============================================================
       FILTER
       Account Control -> Account Subsidiary
    ============================================================ */


    $('#filter_account_control_id').on('change', function () {

        let controlId = $(this).val();


        loadSubsidiaries(
            controlId,
            '#filter_account_subsidiary_id'
        );

    });



    /* ============================================================
       CREATE
       Account Group -> Account Control
    ============================================================ */


    $('#create_account_group_id').on('change', function () {

        let groupId = $(this).val();


        loadControls(
            groupId,
            '#create_account_control_id'
        );


        // Clear subsidiary
        let subsidiaryElement = $('#create_account_subsidiary_id')[0];

        if (subsidiaryElement && subsidiaryElement.tomselect) {

            subsidiaryElement.tomselect.clear();

            subsidiaryElement.tomselect.clearOptions();

        }

    });



    /* ============================================================
       CREATE
       Account Control -> Account Subsidiary
    ============================================================ */


    $('#create_account_control_id').on('change', function () {

        let controlId = $(this).val();


        loadSubsidiaries(
            controlId,
            '#create_account_subsidiary_id'
        );

    });



    /* ============================================================
       EDIT
       Open Edit Modal
    ============================================================ */


    $(document).on('click', '.btn-edit', function () {

        const data = $(this).data('data');


        // Form action
        $('#editFrom').attr(
            'action',
            $(this).data('action')
        );


        /*
        |--------------------------------------------------------------------------
        | Account Group
        |--------------------------------------------------------------------------
        */

        let editGroup = $('#edit_account_group_id')[0];

        let editControl = $('#edit_account_control_id')[0];

        let editSubsidiary = $('#edit_account_subsidiary_id')[0];


        let groupId = data.account_group_id;

        let controlId = data.account_control_id;

        let subsidiaryId = data.account_subsidiary_id;


        /*
        |--------------------------------------------------------------------------
        | Account Name
        |--------------------------------------------------------------------------
        */

        $('#edit_name').val(data.name);


        /*
        |--------------------------------------------------------------------------
        | Set Group
        |--------------------------------------------------------------------------
        */

        if (editGroup && editGroup.tomselect) {

            editGroup.tomselect.setValue(groupId);

        }


        /*
        |--------------------------------------------------------------------------
        | Load Controls
        |--------------------------------------------------------------------------
        */

        loadControls(
            groupId,
            '#edit_account_control_id',
            controlId,

            function () {

                /*
                |--------------------------------------------------------------------------
                | After Control Loaded
                | Load Subsidiaries
                |--------------------------------------------------------------------------
                */

                loadSubsidiaries(
                    controlId,
                    '#edit_account_subsidiary_id',
                    subsidiaryId
                );

            }
        );

    });



    /* ============================================================
       EDIT
       Group Changed Manually
    ============================================================ */


    $('#edit_account_group_id').on('change', function () {

        let groupId = $(this).val();


        loadControls(
            groupId,
            '#edit_account_control_id'
        );


        // Clear subsidiary
        let subsidiaryElement = $('#edit_account_subsidiary_id')[0];

        if (subsidiaryElement && subsidiaryElement.tomselect) {

            subsidiaryElement.tomselect.clear();

            subsidiaryElement.tomselect.clearOptions();

        }

    });



    /* ============================================================
       EDIT
       Control Changed Manually
    ============================================================ */


    $('#edit_account_control_id').on('change', function () {

        let controlId = $(this).val();


        loadSubsidiaries(
            controlId,
            '#edit_account_subsidiary_id'
        );

    });



    /* ============================================================
       FILTER PAGE LOAD
       Restore Selected Values
    ============================================================ */

    let filterGroupId = "{{ request('account_group_id') }}";

    let filterControlId = "{{ request('account_control_id') }}";

    let filterSubsidiaryId = "{{ request('account_subsidiary_id') }}";


    if (filterGroupId) {

        loadControls(
            filterGroupId,
            '#filter_account_control_id',
            filterControlId,

            function () {

                if (filterControlId) {

                    loadSubsidiaries(
                        filterControlId,
                        '#filter_account_subsidiary_id',
                        filterSubsidiaryId
                    );

                }

            }
        );

    }


});

</script>

@endsection
 