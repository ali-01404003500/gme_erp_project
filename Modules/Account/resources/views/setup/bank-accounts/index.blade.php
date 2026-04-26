@section('title', trans('menu.account-setup-bank-accounts-menu-title'))
@section('description', trans('menu.account-setup-bank-accounts-menu-title'))
@extends('layout.app')
@section('content')
    <!-- CONTENT AREA -->
    <div class="container-fluid">
        <div class="social-dash-wrap">
            <div class="row">
                <div class="col-lg-12">
                    <div class="breadcrumb-main">
                        <div class="breadcrumb-action justify-content-center flex-wrap">
                            <nav aria-label="breadcrumb">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="/"><i class="las la-home"></i>Home</a></li>
                                    <li class="breadcrumb-item active" aria-current="page">
                                        {{ trans('menu.account-setup-bank-accounts-menu-title') }}</li>
                                </ol>
                            </nav>
                        </div>
                        <div class="breadcrumb-main__wrapper">
                            <div class="action-btn mt-sm-0 mt-15">
                                @if (hasPermission('account.account-setup.bank-accounts.create'))
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
                <div class="col-md-12" style="padding-bottom: 20px">
                    <div class="row" style="width: 100%">
                        <div class="col-md-6">
                            <h4 class="text-capitalize breadcrumb-title">{{ trans('menu.account-setup-bank-accounts-menu-title') }}
                            </h4>
                        </div>
                        <x-error-alart />
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="card mb-4">
                        <div class="card-body">
                            <table id="zero-config" class="table dt-table-hover table-bordered" data-page='@include('utils.table_paginate', ['data' => $bankAccounts])'
                                style="width:100%; table-layout: fixed;">
                                <thead>
                                    <tr>
                                      <th class="text-center" style="width: 5%">Sl</th>
                                        <th class="text-center text-wrap" style="width: 30%">Name</th> 
                                        <th class="text-center text-wrap" style="width: 12%">Payment Mode</th>
                                        <th class="text-center text-wrap" style="width: 13%">Bank</th>
                                        <th class="text-center text-wrap" style="width: 13%">Branch</th>
                                        <th class="text-center text-wrap" style="width: 15%">Account No</th>
                                        <th class="text-center no-content text-wrap" style="width: 12%">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @csrf
                                    @foreach ($bankAccounts as $key => $bank)
                                    {{-- @dd($bank->bankBranch); --}}
                                        <tr>
                                            <td class="text-center text-wrap">{{ ($bankAccounts->currentPage() - 1) * $bankAccounts->perPage() + $loop->iteration  }}</td>
                                            <td class="text-left text-wrap">{{ $bank->account_name }}</td>
                                            <td class="text-left text-wrap">{{ $bank->payment_mode }}</td>
                                            <td class="text-left text-wrap">{{ $bank->bank->name ?? '-' }}</td>
                                            <td class="text-left text-wrap">{{ $bank->bankBranch->name ?? '-' }}</td>
                                            <td class="text-left text-wrap">{{ $bank->bank_account_no ?? '-' }}</td>
                                            <td class="text-left text-wrap">
                                                <div class="btn-group btn-group-sm" role="group"
                                                    aria-label="Small button group">

                                                    @if (hasPermission('account.account-setup.bank-accounts.update'))
                                                        <button type="button" data-action="{{ route('account.account-setup.bank-accounts.update', $bank->id) }}" data-data="{{$bank}}" class="btn btn-outline-primary btn-edit" data-toggle="tooltip" data-placement="top" title="Edit"
                                                        data-bs-toggle="modal" data-bs-target="#editModal">
                                                            <i class="far fa-edit"></i>
                                                        </button>
                                                    @endif

                                                    @if (hasPermission('account.account-setup.bank-accounts.destroy'))
                                                        <button type="button"
                                                            data-action="{{ route('account.account-setup.bank-accounts.destroy', $bank->id) }}"
                                                            class="btn btn-outline-danger delete-confirm" title="Delete"><i
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

                <!-- Create Modal -->
                <div class="modal fade inputForm-modal" id="createModal" tabindex="-1" role="dialog"
                    aria-labelledby="createModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-md" role="document">
                        <div class="modal-content">

                            <div class="modal-header" id="createModalLabel">
                                <h5 class="modal-title">{{ trans('menu.account-setup-bank-accounts-menu-title') }}</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-hidden="true"></button>
                            </div>
                            <form action="{{ route('account.account-setup.bank-accounts.store') }}" method="post">
                                @csrf
                                <div class="modal-body">
                                    
                                    <div class="row mb-4">
                                       <div class="row">
                                           <label class="col-sm-12 col-form-label">Select Payment Mode <strong class="text-danger">*</strong> </label>
                                           <div class="col-sm-12">
                                               <select name="payment_mode" class="form-control tom-select" required>
                                                   <option value="">Select</option>
                                                   <option value="bKash">bKash</option>
                                                   <option value="Nagad">Nagad</option>
                                                   <option value="Rocket">Rocket</option>
                                                   <option value="Card Payment">Card Payment</option>
                                                   <option value="Cash">Cash</option>
                                                   <option value="Online Deposit">Online Deposit</option>
                                               </select>
                                           </div>
                                       </div>
                                   </div>
                                    <div class="row mb-4">
                                        <div class="row">
                                            <label class="col-sm-12 col-form-label">Account Name <strong class="text-danger">*</strong> </label>
                                            <div class="col-sm-12">
                                                <input type="text" name="account_name" class="form-control" placeholder=" Account Name " required>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <label class="col-sm-12 col-form-label">Account Code <strong class="text-danger">*</strong> </label>
                                            <div class="col-sm-12">
                                                <input type="text" name="account_code" class="form-control" placeholder=" Account Code" required>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <label class="col-sm-12 col-form-label">Opening Balance <strong class="text-danger">*</strong> </label>
                                            <div class="col-sm-12">
                                                <input type="number" name="opening_balance" class="form-control" placeholder=" Opening Balance" value="0" readonly required>
                                            </div>
                                        </div>
                                        <div class="row bank-details" style="display: none;">
                                            <label class="col-sm-12 col-form-label">Select Bank <strong class="text-danger">*</strong> </label>
                                            <div class="col-sm-12">
                                                <select name="bank_id" class="form-control tom-select">
                                                    <option value="">Select</option>
                                                    @foreach ($banks as $bank)
                                                        <option value="{{ $bank->id }}">{{ $bank->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="row bank-details" style="display: none;">
                                            <label class="col-sm-12 col-form-label">Select Bank Branch <strong class="text-danger">*</strong> </label>
                                            <div class="col-sm-12">
                                                <select name="bank_branch_id" class="form-control tom-select">
                                                    <option value="">Select</option>
                                                    @foreach ($branches as $branch)
                                                        <option value="{{ $branch->id }}" data-bank_id="{{ $branch->bank_id }}">{{ $branch->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="row bank-details" style="display: none;">
                                            <label class="col-sm-12 col-form-label">Bank Account No <strong class="text-danger">*</strong> </label>
                                            <div class="col-sm-12">
                                                <input type="text" name="bank_account_no" class="form-control" placeholder=" Bank Account No *">
                                            </div>
                                        </div>
                                    </div>
                                </div>


                                <div class="modal-footer">
                                    <button type="button" class="btn btn-light-danger mt-2 mb-2 btn-no-effect"
                                        data-bs-dismiss="modal">Cancel</button>
                                    <button type="submit" class="btn btn-primary mt-2 mb-2 btn-no-effect">Save</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Modal -->
    <div class="modal fade inputForm-modal" id="editModal" tabindex="-1" role="dialog"
        aria-labelledby="editModalLabel" aria-hidden="true">
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
                        <div class="row mb-4">
                            <div class="row mb-4">
                                <label class="col-sm-12 col-form-label">Select Payment Mode <span class="text-danger">*</span></label>
                                <div class="col-sm-12">
                                    <select name="payment_mode" class="form-control tom-select" required>
                                        <option value="">Select</option>
                                        <option value="bKash">bKash</option>
                                        <option value="Nagad">Nagad</option>
                                        <option value="Rocket">Rocket</option>
                                        <option value="Card Payment">Card Payment</option>
                                        <option value="Cash">Cash</option>
                                        <option value="Online Deposit">Online Deposit</option>
                                    </select>
                                </div>
                            </div>
                            <div class="row mb-4">
                                <label class="col-sm-12 col-form-label">Account Name <span class="text-danger">*</span></label>
                                <div class="col-sm-12">
                                    <input type="text" name="account_name" class="form-control" placeholder=" Account Name *" required>
                                </div>
                            </div>
                            <div class="row mb-4">
                                <label class="col-sm-12 col-form-label">Account Code <span class="text-danger">*</span></label>
                                <div class="col-sm-12">
                                    <input type="text" name="account_code" class="form-control" placeholder=" Account Code *" required>
                                </div>
                            </div>
                            <div class="row mb-4">
                                <label class="col-sm-12 col-form-label">Opening Balance</label>
                                <div class="col-sm-12">
                                    <input type="number" name="opening_balance" class="form-control" placeholder=" Opening Balance *" value="0" readonly required>
                                </div>
                            </div>
                            <div class="row mb-4 bank-details" style="display: none;">
                                <label class="col-sm-12 col-form-label">Select Bank <span class="text-danger">*</span></label>
                                <div class="col-sm-12">
                                    <select name="bank_id" class="form-control tom-select">
                                        <option value="">Select</option>
                                        @foreach ($banks as $bank)
                                            <option value="{{ $bank->id }}">{{ $bank->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="row mb-4 bank-details" style="display: none;">
                                <label class="col-sm-12 col-form-label">Select Branch <span class="text-danger">*</span></label>
                                <div class="col-sm-12">
                                    <select name="bank_branch_id" class="form-control tom-select">
                                        <option value="">Select</option>
                                        @foreach ($branches as $branch)
                                            <option value="{{ $branch->id }}">{{ $branch->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="row mb-4 bank-details" style="display: none;">
                                <label class="col-sm-12 col-form-label">Bank Account No <span class="text-danger">*</span></label>
                                <div class="col-sm-12">
                                    <input type="text" name="bank_account_no" class="form-control" placeholder=" Bank Account No *">
                                </div>
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
    </div>
@endsection
<!-- CONTENT AREA -->
@section('page_scripts')

    <script>
        var branches = {!! json_encode($branches) !!};
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

            $(document).on('change', 'select[name="payment_mode"]', function() {
                const paymentMode = $(this).val();
                const form = $(this).closest('form');
                const bankDetails = form.find('.bank-details');
                const bankSelect = form.find('select[name="bank_id"]');
                const bankBranchSelect = form.find('select[name="bank_branch_id"]');
                const bankAccountNoInput = form.find('input[name="bank_account_no"]');

                if (['Cheque', 'Online Deposit', 'Card Payment', 'Bank'].includes(paymentMode)) {
                    bankDetails.show();
                    bankSelect.attr('required', true);
                    bankBranchSelect.attr('required', true);
                    bankAccountNoInput.attr('required', true);
                } else {
                    bankDetails.hide();
                    bankSelect.removeAttr('required');
                    bankBranchSelect.removeAttr('required');
                    bankAccountNoInput.removeAttr('required');
                }
            });


            $(document).on('change', 'select[name="bank_id"]', function() {
                var bank_id = $(this).val();

                const bankBranch = branches.filter(branch => branch.bank_id == bank_id);

                const bankBranchSelect = $(this).closest('form').find('select[name="bank_branch_id"]');

                bankBranchSelect.empty();
                bankBranchSelect.prop('tomselect')?.clearOptions();
                bankBranchSelect.append('<option value="">Select Branch</option>');

                bankBranch.forEach(branch => {
                    bankBranchSelect.append('<option value="' + branch.id + '">' + branch.name + '</option>');
                });

                // console.log(bankBranch);
                
                bankBranchSelect.prop('tomselect')?.sync();

                // console.log({bankBranch});
                


            });

        // function edit(element) {
        //     let name = $(element).data('name');
        //     let code = $(element).data('code');
        //     let action = $(element).data('action');
        //     $('#name').val(name);
        //     $('#code').val(code);
        //     $("#editFrom").attr("action", action);
        // }
    </script>
    <script>
        var branches = @json($branches);
    
        $(document).on('change', 'select[name="bank_id"]', function() {
            var bank_id = $(this).val(),
                bankBranch = branches.filter(b => b.bank_id == bank_id),
                $branchSelect = $(this).closest('form')
                                   .find('select[name="bank_branch_id"]');
    
            // wipe out old options
            $branchSelect.empty().append('<option value="">Select Branch</option>');
    
            // add new ones
            bankBranch.forEach(branch => {
              $branchSelect.append(
                $('<option>', { value: branch.id, text: branch.name })
              );
            });
    
            // if TomSelect––sync it
            if ($branchSelect[0].tomselect) {
              let ts = $branchSelect[0].tomselect;
              ts.clearOptions();
              ts.addOptions(bankBranch.map(b => ({value: b.id, text: b.name})));
              ts.refreshOptions(false);
            }
    
            // if Select2––tell it to reparse its options
            if ($branchSelect.hasClass('select2')) {
              $branchSelect.trigger('change.select2');
            }
        });
    </script>
    
@endsection
