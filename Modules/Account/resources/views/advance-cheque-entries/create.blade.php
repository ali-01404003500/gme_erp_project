@extends('layout.app')
@section('title', 'Advance Cheque Entry')
@section('description', 'Advance Cheque Entry')
@section('content')
    <div class="container-fluid">
        <div class="social-dash-wrap">
            <div class="row">
                <div class="col-lg-12">
                    <div class="breadcrumb-main">
                        <div class="breadcrumb-action justify-content-center flex-wrap">
                            <nav aria-label="breadcrumb">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="#"><i class="las la-home"></i>Home</a></li>
                                    <li class="breadcrumb-item active" aria-current="page">
                                        {{ trans('Advance Cheque Entry') }}</li>
                                </ol>
                            </nav>
                        </div>
                        <div class="action-btn mt-sm-0 mt-15">
                            @if (hasPermission('account.advance-cheque-entries.index'))
                                <a href="{{ route('account.advance-cheque-entries.index') }}"
                                    class="btn btn-warning btn-default btn-squared radius-md shadow2 btn-sm"><i
                                        class="fa fa-list"></i> List</a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12 m-2">
                    <h4 class="text-capitalize breadcrumb-title">{{ trans('Advance Cheque Entry') }}</h4>
                    <x-error-alart />
                </div>

                <div class="card mb-50">
                    <div class="row justify-content-center" id="justify-content-center">
                        <div class="col-sm-11">
                            <div class="mt-40 mb-50">
                                <h2 class="mb-3">Advance Cheque Entry</h2>
                                <form action="{{ route('account.advance-cheque-entries.store', app()->getLocale()) }}"
                                    method="POST" enctype="multipart/form-data">
                                    @csrf

                                    <div class="row">
                                        <!-- Cheque Type -->
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="cheque_type">{{ __('Cheque Type') }}<span
                                                        class="text-danger">*</span>:</label>
                                                <select name="cheque_type" id="cheque_type" class="form-control tom-select">
                                                    <option value="">{{ __('Select Cheque Type') }}</option>
                                                    <option value="installment"
                                                        {{ old('cheque_type') == 'installment' ? 'selected' : '' }}>
                                                        Installment Cheque</option>
                                                    <option value="collection"
                                                        {{ old('cheque_type') == 'collection' ? 'selected' : '' }}>
                                                        Collection Cheque</option>
                                                    <option value="security"
                                                        {{ old('cheque_type') == 'security' ? 'selected' : '' }}>Security
                                                        Cheque</option>
                                                    <option value="only_deed"
                                                        {{ old('cheque_type') == 'only_deed' ? 'selected' : '' }}>Only Deed
                                                    </option>
                                                </select>
                                            </div>
                                        </div>

                                        <!-- Customer -->
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="customer_id">{{ __('Customer') }}<span
                                                        class="text-danger">*</span>:</label>
                                                <select name="customer_id" id="customer_id" class="form-control tom-select">
                                                    <option value="">{{ __('Select Customer') }}</option>
                                                    @foreach ($customers as $customer)
                                                        <option value="{{ $customer->id }}"
                                                            {{ old('customer_id') == $customer->id ? 'selected' : '' }}
                                                            data-phone="{{ $customer->phone }}"
                                                            data-address="{{ $customer->address }}">
                                                            {{ $customer->company_name }} - {{ $customer->address}}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>

                                        <!-- Collection Date -->
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="collection_date">{{ __('Collection Date') }}<span
                                                        class="text-danger">*</span>:</label>
                                                <input type="text" name="collection_date" id="collection_date"
                                                    class="form-control flatdate"
                                                    value="{{ old('collection_date', date('Y-m-d')) }}">
                                            </div>
                                        </div>

                                        <!-- No of Cheques -->
                                        <div class="col-md-6" id="no_of_cheque_container"
                                            style="{{ old('cheque_type') && in_array(old('cheque_type'), ['collection', 'security', 'only_deed']) ? '' : 'display: none;' }}">
                                            <div class="form-group">
                                                <label for="no_of_cheque">{{ __('No of Cheque') }}<span
                                                        class="text-danger">*</span>:</label>
                                                <input type="number" name="no_of_cheque" id="no_of_cheque"
                                                    class="form-control" value="{{ old('no_of_cheque') }}">
                                            </div>
                                        </div>

                                        <!-- Reference -->
                                        <div class="col-md-6" id="reference_container"
                                            style="{{ old('cheque_type') == 'installment' ? '' : 'display: none;' }}">
                                            <div class="form-group">
                                                <label for="reference">{{ __('Reference') }}<span
                                                        class="text-danger">*</span>:</label>
                                                <select name="reference" id="reference" class="form-control">
                                                    <option value="">{{ __('Select Reference') }}</option>
                                                    @if (old('reference'))
                                                        <option value="{{ old('reference') }}" selected>
                                                            {{ old('reference') }}</option>
                                                    @endif
                                                </select>
                                            </div>
                                        </div>

                                        <!-- Remarks -->
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="remarks">{{ __('Remarks') }}:</label>
                                                <textarea name="remarks" id="remarks" class="form-control" maxlength="512">{{ old('remarks') }}</textarea>
                                                <small class="text-muted">Maximum 512 characters</small>
                                            </div>
                                        </div>

                                        <!-- Document -->
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="deed">{{ __('Deed/Document') }}:</label>
                                                <x-file-uploader :value="old('document')" name="document" />
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Bank Info Input -->
                                    <div class="row mt-3" id="bank_input_section"
                                        style="{{ old('cheque_type') ? '' : 'display:none;' }}">
                                        <div class="col-md-12">
                                            <h4>Bank Information</h4>
                                            <div class="row">
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label for="bank_id">Bank Name<span
                                                                class="text-danger">*</span>:</label>
                                                        <select name="bank_id" id="bank_id"
                                                            class="form-control tom-select">
                                                            <option value="">{{ __('Select Bank') }}</option>
                                                            @foreach ($banks as $bank)
                                                                <option value="{{ $bank->id }}"
                                                                    {{ old('bank_id') == $bank->id ? 'selected' : '' }}
                                                                    data-bank_name="{{ $bank->name }}">
                                                                    {{ $bank->name }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>

                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label for="bank_branch_id">Branch Name<span
                                                                class="text-danger">*</span>:</label>
                                                        <select name="bank_branch_id" id="bank_branch_id"
                                                            class="form-control tom-select">
                                                            <option value="">{{ __('Select Branch') }}</option>
                                                            @if (old('bank_branch_id'))
                                                                @foreach ($branches as $branch)
                                                                    @if ($branch->bank_id == old('bank_id'))
                                                                        <option value="{{ $branch->id }}"
                                                                            {{ old('bank_branch_id') == $branch->id ? 'selected' : '' }}>
                                                                            {{ $branch->name }}
                                                                        </option>
                                                                    @endif
                                                                @endforeach
                                                            @endif
                                                        </select>
                                                    </div>
                                                </div>

                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label for="cheque_no">Cheque No<span
                                                                class="text-danger">*</span>:</label>
                                                        <input type="text" id="cheque_no" class="form-control"
                                                            value="{{ old('temp_cheque_no') }}">
                                                    </div>
                                                </div>

                                                <div class="col-md-1" style="padding-top: 30px;">
                                                    <button type="button" class="btn btn-sm btn-primary"
                                                        id="add_bank_info">Add</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Bank Table -->
                                    <div class="row mt-3" id="bank_table_section"
                                        style="{{ old('bank_ids') && count(old('bank_ids')) > 0 ? '' : 'display: none;' }}">
                                        <div class="col-md-12">
                                            <table class="table table-bordered">
                                                <thead>
                                                    <tr>
                                                        <th>SL</th>
                                                        <th>Bank Name</th>
                                                        <th>Branch</th>
                                                        <th>Cheque No</th>
                                                        <th>Cheque Date</th>
                                                        <th>Amount</th>
                                                        <th>Cheque Image Upload</th>
                                                    </tr>
                                                </thead>
                                                <tbody id="bank_info_table">
                                                    @if (old('bank_ids'))
                                                        @foreach (old('bank_ids') as $i => $bankId)
                                                            <tr>
                                                                <td>
                                                                    @if (old('is_security_cheque')[$i] == 1)
                                                                        Security Cheque
                                                                        <input type="hidden" name="is_security_cheque[]"
                                                                            value="1">
                                                                    @else
                                                                        {{ $i + 1 }}
                                                                        <input type="hidden" name="is_security_cheque[]"
                                                                            value="0">
                                                                    @endif

                                                                </td>
                                                                <td>
                                                                    <input type="hidden" name="bank_name[]"
                                                                        value="{{ old('bank_name')[$i] }}">
                                                                    <input type="hidden" name="bank_ids[]"
                                                                        value="{{ $bankId }}">
                                                                    {{ old('bank_name')[$i] }}
                                                                </td>
                                                                <td>
                                                                    <input type="hidden" name="branch_name[]"
                                                                        value="{{ old('branch_name')[$i] }}">
                                                                    <input type="hidden" name="branch_ids[]"
                                                                        value="{{ old('branch_ids')[$i] }}">
                                                                    {{ old('branch_name')[$i] }}
                                                                </td>
                                                                <td><input type="text" class="form-control"
                                                                        name="cheque_no[]"
                                                                        value="{{ old('cheque_no')[$i] }}"></td>
                                                                <td><input type="text" name="cheque_date[]"
                                                                        class="form-control flatdate"
                                                                        value="{{ old('cheque_date')[$i] }}"></td>
                                                                <td><input type="number" name="amount[]"
                                                                        class="form-control installment-amount"
                                                                        value="{{ old('amount')[$i] }}"></td>
                                                                <td>            
                                                                    <div class="dropdown dropdown-click">
                                                                        <div class="btn-group dropleft">
                                                                            <button type="button"
                                                                                class="btn btn-xs btn-secondary attachments">
                                                                                <i class="fa fa-paperclip"></i>
                                                                            </button>
                                                                        </div>
                                                                    </div>
                                                                    <input type="hidden" name="documents[]"
                                                                        value="{{ old('documents')[$i] ?? '' }}"
                                                                        class="attachments_input">  
                                                                </td>
                                                            </tr>
                                                        @endforeach
                                                    @endif
                                                </tbody>
                                                <tfoot>
                                                    <tr>
                                                        <td colspan="5" class="text-end"><strong>Total Amount:</strong>
                                                        </td>
                                                        <td id="total_amount">0.00</td>
                                                        <td></td>
                                                    </tr>
                                                </tfoot>
                                            </table>
                                        </div>
                                    </div>

                                    <div class="d-flex justify-content-end mt-4">
                                        <button type="submit"
                                            class="btn btn-primary btn-sm">{{ __('Save') }}</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('page_scripts')
    <script>
        var branches = {!! json_encode($branches) !!};

        $(document).ready(function() {
            // Initialize flatpickr for date fields
            $('.flatdate').flatpickr({
                dateFormat: 'Y-m-d',
                allowInput: true
            });

            // Show/hide fields based on cheque type
            $('#cheque_type').change(function() {
                const chequeType = $(this).val();

                // Hide all sections first
                $('#no_of_cheque_container').hide();
                $('#bank_input_section').hide();
                $('#bank_table_section').hide();
                $('#reference_container').hide();

                // Remove any existing security rows
                $('.security-cheque-row').remove();

                // Show relevant fields based on cheque type
                if (chequeType === 'collection' || chequeType === 'security' || chequeType ===
                    'only_deed') {
                    $('#no_of_cheque_container').show();
                    $('#bank_input_section').show();
                } else if (chequeType === 'installment') {
                    $('#bank_input_section').show();
                    $('#reference_container').show();
                }

                // Clear table and references when cheque type changes
                if (!old('bank_ids')) {
                    $('#bank_info_table').empty();
                }
                $('#reference').empty().append('<option value="">{{ __('Select Reference') }}</option>');

                // Trigger reference load if customer is selected
                const customerId = $('#customer_id').val();
                if (customerId && chequeType) {
                    loadReferences(customerId, chequeType);
                }
            });

            // Load branches when bank changes
            $('#bank_id').change(function() {
                var bank_id = $(this).val();
                const bankBranchSelect = $('#bank_branch_id');

                bankBranchSelect.empty();
                bankBranchSelect.prop('tomselect')?.clearOptions();
                bankBranchSelect.append('<option value="">Select Branch</option>');

                if (bank_id) {
                    const bankBranches = branches.filter(branch => branch.bank_id == bank_id);

                    bankBranches.forEach(branch => {
                        bankBranchSelect.append('<option value="' + branch.id + '">' + branch.name +
                            '</option>');
                    });
                }

                bankBranchSelect.prop('tomselect')?.sync();
            });

            // Load references when customer changes
            $('#customer_id').change(function() {
                const customerId = $(this).val();
                const chequeType = $('#cheque_type').val();

                // Clear table and references
                if (!old('bank_ids')) {
                    $('#bank_info_table').empty();
                }
                $('#reference').empty().append('<option value="">{{ __('Select Reference') }}</option>');

                if (customerId && chequeType) {
                    loadReferences(customerId, chequeType);
                }
            });

            // Clear table when reference changes
            $('#reference').change(function() {
                if (!old('bank_ids')) {
                    $('#bank_info_table').empty();
                    $('#bank_table_section').hide();
                }
            });

            // Function to load references
            function loadReferences(customerId, chequeType) {
                $.ajax({
                    url: "{{ route('account.get-customer-references') }}",
                    type: "GET",
                    data: {
                        customer_id: customerId,
                        cheque_type: chequeType
                    },
                    success: function(response) {
                        const referenceSelect = $('#reference');
                        referenceSelect.empty();
                        referenceSelect.append(
                            '<option value="">{{ __('Select Reference') }}</option>');

                        if (response.references && response.references.length > 0) {
                            $.each(response.references, function(index, reference) {
                                referenceSelect.append(
                                    `<option value="${reference.id}" ${old('reference') == reference.id ? 'selected' : ''}>${reference.reference_number}</option>`
                                );
                            });
                        }
                    },
                    error: function() {
                        alert('Failed to load references. Please try again.');
                    }
                });
            }

            // Function to load EMI bank information
            // Update the loadEmiBankInfo function to add security row last
            function loadEmiBankInfo(emiDetails, bankName, bankId, branchName, branchId, chequeNo) {
                const tableBody = $('#bank_info_table');
                tableBody.empty();

                if (emiDetails.length === 0) {
                    alert('No EMI details found for the selected reference.');
                    return;
                }

                // Calculate total EMI amount for security cheque
                let securityChequeAmount = 0;
                emiDetails.forEach(emi => {
                    securityChequeAmount += parseFloat(emi.amount);
                });

                // Add EMI rows first
                emiDetails.forEach(function(emi, index) {
                    const row = `
            <tr>
                <td>${index + 1}
                    <input type="hidden" name="is_security_cheque[]" value="0">
                    </td>
                <td><input type="hidden" name="bank_name[]" value="${bankName}">
                    <input type="hidden" name="bank_ids[]" value="${bankId}">
                    ${bankName}
                </td>
                <td><input type="hidden" name="branch_name[]" value="${branchName}">
                    <input type="hidden" name="branch_ids[]" value="${branchId}">${branchName}</td>
                <td><input type="text" class="form-control" name="cheque_no[]" value="${chequeNo}"></td>
                <td><input type="text" name="cheque_date[]" class="form-control flatdate" value="${emi.due_date}">
                    <input type="hidden" name="emi_detail_id[]" class="form-control" value="${emi.id}">
                    </td>
                <td><input type="number" name="amount[]" class="form-control installment-amount" value="${emi.amount}"></td>
                <td>
                    <div class="dropdown dropdown-click">
                        <div class="btn-group dropleft">
                            <button type="button" class="btn btn-xs btn-secondary attachments">
                                <i class="fa fa-paperclip"></i>
                            </button>
                        </div>
                    </div>
                    <input type="hidden" name="documents[]" value="" class="attachments_input">
                </td>
            </tr>
        `;
                    tableBody.append(row);
                });

                // Add security cheque as LAST row only for installment type
                const securityRow = `
        <tr class="security-cheque-row">
            <td><input type="hidden" name="is_security_cheque[]" value="1">
                Security Cheque</td>
            <td><input type="hidden" name="bank_name[]" value="${bankName}">
                <input type="hidden" name="bank_ids[]" value="${bankId}">
                ${bankName}
            </td>
            <td><input type="hidden" name="branch_name[]" value="${branchName}">
                <input type="hidden" name="branch_ids[]" value="${branchId}">
                ${branchName}
            </td>
            <td><input type="text" class="form-control" name="cheque_no[]" value="${chequeNo}"></td>
            <td><input type="text" name="cheque_date[]" class="form-control flatdate"></td>
            <td><input type="number" name="amount[]" class="form-control security-amount" value="${securityChequeAmount}" readonly></td>
            <td>
                <div class="dropdown dropdown-click">
                    <div class="btn-group dropleft">
                        <button type="button" class="btn btn-xs btn-secondary attachments">
                            <i class="fa fa-paperclip"></i>
                        </button>
                    </div>
                </div>
                <input type="hidden" name="documents[]" value="" class="attachments_input">
            </td>
        </tr>
    `;
                tableBody.append(securityRow);

                // Reinitialize flatpickr
                $('.flatdate').flatpickr({
                    dateFormat: 'Y-m-d',
                    allowInput: true
                });

                $('#bank_table_section').show();
                calculateTotal();
            }

            // Function to calculate total amount
            function calculateTotal() {
                let total = 0;
                $('input[name="amount[]"]').each(function() {
                    const amount = parseFloat($(this).val()) || 0;
                    total += amount;
                });
                $('#total_amount').text(total.toFixed());
            }
            $(document).on('input', '.installment-amount', function() {
                calculateTotal();
            });

            // Add bank information to table
            $('#add_bank_info').click(function() {
                const bankId = $('#bank_id').val();
                const bankName = $('#bank_id option:selected').data('bank_name');
                const branchId = $('#bank_branch_id').val();
                const branchName = $('#bank_branch_id option:selected').text();
                const chequeNo = $('#cheque_no').val();
                const noOfCheques = $('#no_of_cheque').val();
                const chequeType = $('#cheque_type').val();
                const customerId = $('#customer_id').val();
                const referenceId = $('#reference').val();

                if (!bankId || !branchId || !chequeNo) {
                    alert('Please fill all bank information fields');
                    return;
                }

                if ((chequeType === 'collection' || chequeType === 'security' || chequeType ===
                        'only_deed') && !noOfCheques) {
                    alert('Please enter number of cheques');
                    return;
                }

                if (chequeType === 'installment' && !referenceId) {
                    alert('Please select a reference before adding bank information');
                    return;
                }

                const tableBody = $('#bank_info_table');
                tableBody.empty();

                if (chequeType === 'collection' || chequeType === 'security' || chequeType ===
                    'only_deed') {
                    // For non-installment types, just add regular rows
                    for (let i = 0; i < noOfCheques; i++) {
                        const row = `
                <tr>
                    <td>${i + 1}
                        <input type="hidden" name="is_security_cheque[]" value="0">
                        </td>
                    <td><input type="hidden" name="bank_name[]" value="${bankName}">
                        <input type="hidden" name="bank_ids[]" value="${bankId}">
                        ${bankName}</td>
                    <td><input type="hidden" name="branch_name[]" value="${branchName}">
                        <input type="hidden" name="branch_ids[]" value="${branchId}">
                        ${branchName}</td>
                    <td><input type="text" class="form-control" name="cheque_no[]" value="${chequeNo}"></td>
                    <td><input type="text" name="cheque_date[]" class="form-control flatdate"></td>
                    <td><input type="number" name="amount[]" class="form-control installment-amount"></td>
                    <td>
                        <div class="dropdown dropdown-click">
                            <div class="btn-group dropleft">
                                <button type="button" class="btn btn-xs btn-secondary attachments">
                                    <i class="fa fa-paperclip"></i>
                                </button>
                            </div>
                        </div>
                        <input type="hidden" name="documents[]" value="" class="attachments_input">
                    </td>
                </tr>
            `;
                        tableBody.append(row);
                    }
                } else if (chequeType === 'installment') {
                    // For installment type, fetch EMI details
                    $.ajax({
                        url: "{{ route('account.get-customer-references') }}",
                        type: "GET",
                        data: {
                            customer_id: customerId,
                            cheque_type: chequeType,
                            reference_id: referenceId
                        },
                        success: function(response) {
                            if (response.emi_details) {
                                loadEmiBankInfo(response.emi_details, bankName, bankId,
                                    branchName, branchId, chequeNo);
                            } else {
                                alert('No EMI details found for the selected reference.');
                            }
                        },
                        error: function() {
                            alert('Failed to load EMI details. Please try again.');
                        }
                    });
                    return;
                }

                // Reinitialize flatpickr and calculate total for non-installment types
                $('.flatdate').flatpickr({
                    dateFormat: 'Y-m-d',
                    allowInput: true
                });
                $('#bank_table_section').show();
                calculateTotal();
            });

            // Document attachment functionality
               function updateDocumentsPreview() {
            $("input[name='documents[]']").each(function() {
                const $input = $(this);
                const $td = $input.closest('td');
                
                try {
                    const filesUrls = $input.val() ? JSON.parse($input.val()) : [];
                    
                    // Clear previous dropdown except the attachment button
                    $td.find(".btn-group>*").not(".attachments").remove();
                    
                    if (filesUrls.length > 0) {
                        let dropdown = `
                            <button type="button" class="btn btn-secondary btn-xs dropdown-toggle-split" data-bs-toggle="dropdown">
                                <i class="fa fa-download"></i>
                            </button>
                            <div class="dropdown-default dropdown-menu">`;
                        
                        filesUrls.forEach((fileUrl, index) => {
                            const fileName = fileUrl.split('/').pop();
                            const shortFileName = fileName.length > 20 ? 
                                fileName.substring(0, 10) + '...' + fileName.substring(fileName.length - 10) : 
                                fileName;
                            dropdown += `
                                <div class="dropdown-item d-flex justify-content-between align-items-center">
                                    <a href="${fileUrl}" target="_blank" class="text-truncate" style="max-width: 80%;">
                                        ${index + 1}. ${shortFileName}
                                    </a>
                                    <button type="button" class="btn text-danger remove-doc" data-file="${fileUrl}">
                                        <i class="fa fa-times"></i>
                                    </button>
                                </div>`;
                        });
                        
                        dropdown += `</div>`;
                        $td.find(".btn-group").append(dropdown);
                    }
                } catch (e) {
                    console.error("Error updating documents preview:", e);
                }
            });
        }

        // Improved delete document handler
        $(document).on('click', '.remove-doc', function() {
            const $button = $(this);
            const fileUrl = $button.data('file');
            const $inputField = $button.closest('td').find('.attachments_input');
            
            try {
                // Get current files
                let filesUrls = $inputField.val() ? JSON.parse($inputField.val()) : [];
                
                // Remove the file from the array
                filesUrls = filesUrls.filter(url => url !== fileUrl);
                
                // Update UI immediately (optimistic update)
                $button.closest('.dropdown-item').remove();
                
                // Show loading state
                $button.html('<i class="fa fa-spinner fa-spin"></i>');
                
                // Extract the file path
                const filePath = fileUrl.split('/').pop();
                
                // Send AJAX request to delete the file from server
                $.ajax({
                    url: "{{ route('delete_file') }}",
                    type: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': "{{ csrf_token() }}"
                    },
                    data: { path: filePath },
                    success: () => {
                        // Final update of the hidden input
                        $inputField.val(JSON.stringify(filesUrls));
                        
                        // If no files left, hide the dropdown
                        if (filesUrls.length === 0) {
                            $button.closest('.dropdown-menu').prev('.dropdown-toggle-split').remove();
                            $button.closest('.dropdown-menu').remove();
                        }
                        
                        toastr.success('File deleted successfully');
                    },
                    error: (xhr) => {
                        // Revert the UI if deletion failed
                        const errorMsg = xhr.responseJSON?.message || 'Failed to delete file';
                        toastr.error(errorMsg);
                        
                        // Restore the file in the list if deletion failed
                        $inputField.val(JSON.stringify([...filesUrls, fileUrl]));
                        updateDocumentsPreview();
                    }
                });
            } catch (e) {
                toastr.error('Error processing file deletion');
                console.error("Error in delete handler:", e);
            }
        });

        // Modify the attachment upload handler to properly merge new files with existing ones
        $(document).on('click', '.attachments', function(e) {
            e.preventDefault();
            
            const $attachmentBtn = $(this);
            const $inputField = $attachmentBtn.closest('td').find('.attachments_input');
            
            // Create file input
            const fileInput = $('<input type="file" multiple>').appendTo('body').hide();
            fileInput.trigger('click');
            
            fileInput.on('change', function() {
                const files = fileInput[0].files;
                if (files.length === 0) return;
                
                // Get existing files
                let existingFiles = [];
                try {
                    existingFiles = $inputField.val() ? JSON.parse($inputField.val()) : [];
                } catch (e) {
                    console.error("Error parsing existing files:", e);
                }
                
                $attachmentBtn.addClass('btn-loading');
                
                // Process each file sequentially
                Array.from(files).forEach((file, index) => {
                    const formData = new FormData();
                    formData.append('file', file);
                    
                    $.ajax({
                        url: "{{ route('upload_file') }}",
                        type: 'POST',
                        headers: { 'X-CSRF-TOKEN': "{{ csrf_token() }}" },
                        data: formData,
                        processData: false,
                        contentType: false,
                        success: (response) => {
                            // Add new file to existing files
                            existingFiles.push(response.path);
                            $inputField.val(JSON.stringify(existingFiles));
                            
                            // Update preview after last file
                            if (index === files.length - 1) {
                                updateDocumentsPreview();
                                $attachmentBtn.removeClass('btn-loading');
                                toastr.success('Files uploaded successfully!');
                            }
                        },
                        error: () => {
                            $attachmentBtn.removeClass('btn-loading');
                            toastr.error('Failed to upload file: ' + file.name);
                        }
                    });
                });
                
                fileInput.remove();
            });
        });

            // Helper function to extract path from URL if needed
            function extractFilePathFromUrl(url) {
                // If you're storing full URLs but need just the path for S3
                // Example: https://bucket.s3.region.amazonaws.com/uploads/file.pdf -> uploads/file.pdf
                const matches = url.match(/amazonaws\.com\/(.*)/);
                return matches ? matches[1] : url;
            }

            // Check if we have old input data and show relevant sections
            function checkOldInput() {
                if (old('cheque_type')) {
                    $('#cheque_type').trigger('change');
                }

                if (old('bank_id')) {
                    $('#bank_id').trigger('change');
                }

                if (old('bank_ids') && old('bank_ids').length > 0) {
                    $('#bank_table_section').show();
                    calculateTotal();
                    updateDocumentsPreview();
                }
            }

            // Helper function to check for old input
            function old(field) {
                return {!! json_encode(old()) !!}[field];
            }

            // Initialize on page load
            checkOldInput();
        });
    </script>

    <style>
        .security-cheque-row {
            background-color: #fff3cd;
            /* Light yellow background */
            font-weight: bold;
        }

        .security-cheque-row td {
            border-top: 2px solid #ffc107;
            /* Yellow border on top */
            border-bottom: 2px solid #ffc107;
            /* Yellow border on bottom */
        }

        .security-amount {
            background-color: #f8d7da;
            /* Light red background */
            font-weight: bold;
        }

        .installment-amount {
            background-color: #e7f5ff;
            /* Light blue background */
        }

        #total_amount {
            font-weight: bold;
            background-color: #d1e7dd;
            /* Light green background */
        }
    </style>
@endsection
