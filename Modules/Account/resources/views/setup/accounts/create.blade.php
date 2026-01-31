
@extends('layout.app')

@section('title', 'Create a account')
@section('description', 'Create a account')
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
                                <li class="breadcrumb-item active" aria-current="page">Create a account</li>
                            </ol>
                        </nav>
                    </div>
                    <div class="breadcrumb-main__wrapper">
                        <div class="action-btn mt-sm-0 mt-15">
                            @if (hasPermission('account.account-setup.accounts.create'))
                                <a href="{{ route('account.account-setup.accounts.index') }}" class="btn btn-xs btn-secondary me-1" >
                                    <i class="las la-list"></i>
                                    List
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <x-error-alart />
            </div>
            <div class="col-md-12">
                <div class="card mb-4">
                    <div class="card-body">
                        <form action="{{ route('account.account-setup.accounts.store') }}" method="post" id="createForm">
                            @csrf
                            <div class="row">
                                <div class="col-md-12 mb-2">
                                    <h3>Create a Account</h3>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="name">Name</label>
                                        <input type="text" class="form-control" id="name" name="name" value="{{ old('name') }}" placeholder="Name">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="account_number">Account Number</label>
                                        <input type="text" class="form-control" id="account_number" name="account_number" value="{{ old('account_number') }}" placeholder="Account Number">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="account_group_id">Account Group</label>
                                        <select class="form-control tom-select" id="account_group_id" name="account_group_id">
                                            <option value="">Select Account Group</option>
                                            @foreach ($accountGroups as $key => $accountGroup)
                                                <option value="{{ $accountGroup->id }}" {{ old('account_group_id') == $accountGroup->id ? 'selected' : '' }}>({{ $accountGroup->id }}) {{ $accountGroup->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="account_control_id">Account Control</label>
                                        <select class="form-control tom-select" id="account_control_id" name="account_control_id">
                                            <option value="">Select Account Control</option>
                                            @foreach ($accountControls as $key => $accountControl)
                                                <option value="{{ $accountControl->id }}" data-group="{{ $accountControl->account_group_id }}" {{ old('account_control_id') == $accountControl->id ? 'selected' : '' }}>({{ $accountControl->id }}) {{ $accountControl->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="account_subsidiary_id">Account Subsidiaries</label>
                                        <select class="form-control tom-select" id="account_subsidiary_id" name="account_subsidiary_id">
                                            <option value="">Select Account Subsidiaries</option>
                                            @foreach ($accountSubsidiaries as $key => $accountSubsidiary)
                                                <option value="{{ $accountSubsidiary->id }}" data-group="{{ $accountSubsidiary->account_group_id }}" data-control="{{ $accountSubsidiary->account_control_id }}" {{ old('account_subsidiary_id') == $accountSubsidiary->id ? 'selected' : '' }}>({{ $accountSubsidiary->id }}) {{ $accountSubsidiary->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="opening_balance">Opening Balance</label>
                                        <input type="text" class="form-control numberOnly" id="opening_balance" name="opening_balance" value="{{ old('opening_balance', 0) }}" required>
                                    </div>
                                </div>
                                

                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="remarks">Remarks</label>
                                        <textarea class="form-control" id="remarks" name="remarks" rows="3">{{ old('remarks') }}</textarea>
                                    </div>
                                </div>
                                
                                <div class="col-md-12">
                                    <div class="d-flex justify-content-end">
                                        <button type="submit" class="btn btn-primary">Create</button>
                                    </div>
                                </div>
                            </div>
    
                            
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>


    
    <!-- Create Modal -->
    


    {{-- <div class="modal fade inputForm-modal" id="editModal" tabindex="-1" role="dialog" aria-labelledby="editModalLabel"
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
    </div> --}}
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

            const accountGroup = $('#account_group_id option').clone();
            const accountControls = $('#account_control_id option').clone();
            const accountSubsidiares = $('#account_subsidiary_id option').clone();

            $(document).on('change', '#account_group_id, #account_control_id', function() {
                
                const filterControl = accountControls.filter(function() {
                    return $(this).data('group') == $('#account_group_id').val();
                });

                const filterSubsidiary = accountSubsidiares.filter(function() {
                    return $(this).data('group') == $('#account_group_id').val();
                }).filter(function() {
                    return $(this).data('control') == $('#account_control_id').val();
                });

               
                

                $('#account_control_id').prop('tomselect')?.clearOptions();
                $('#account_control_id').empty();
                $('#account_control_id').append('<option value="">Select Account Control</option>');

                $('#account_control_id').append(filterControl);
                $('#account_control_id').prop('tomselect')?.sync();

                $('#account_subsidiary_id').prop('tomselect')?.clearOptions();
                $('#account_subsidiary_id').empty();
                $('#account_subsidiary_id').append('<option value="">Select Account Subsidiary</option>');

                $('#account_subsidiary_id').append(filterSubsidiary);
                $('#account_subsidiary_id').prop('tomselect')?.sync();
                    
            });
        });
    </script>
@endsection