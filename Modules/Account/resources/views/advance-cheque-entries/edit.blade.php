@extends('layout.app')
@section('title', 'Edit Advance Cheque Entry')
@section('description', 'Edit Advance Cheque Entry')
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
                                        {{ trans('Edit Advance Cheque Entry') }}</li>
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
                    <h4 class="text-capitalize breadcrumb-title">{{ trans('Edit Advance Cheque Entry') }}</h4>
                    <x-error-alart />
                </div>

                <div class="card mb-50">
                    <div class="row justify-content-center" id="justify-content-center">
                        <div class="col-sm-11">
                            <div class="mt-40 mb-50">
                                <h2 class="mb-3">Edit Advance Cheque Entry</h2>
                                <form action="{{ route('account.advance-cheque-entries.update', $entry->id) }}"
                                    method="POST" enctype="multipart/form-data">
                                    @csrf
                                    @method('PUT')

                                    <div class="row">
                                        <!-- Cheque Type -->
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="cheque_type">{{ __('Cheque Type') }}<span
                                                        class="text-danger">*</span>:</label>
                                                <select name="cheque_type" id="cheque_type" class="form-control tom-select">
                                                    <option value="">{{ __('Select Cheque Type') }}</option>
                                                    <option value="installment"
                                                        {{ $entry->cheque_type == 'installment' ? 'selected' : '' }}>
                                                        Installment Cheque</option>
                                                    <option value="collection"
                                                        {{ $entry->cheque_type == 'collection' ? 'selected' : '' }}>
                                                        Collection Cheque</option>
                                                    <option value="security"
                                                        {{ $entry->cheque_type == 'security' ? 'selected' : '' }}>Security
                                                        Cheque</option>
                                                    <option value="only_deed"
                                                        {{ $entry->cheque_type == 'only_deed' ? 'selected' : '' }}>Only Deed
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
                                                            {{ $entry->customer_id == $customer->id ? 'selected' : '' }}
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
                                                    value="{{ old('collection_date', $entry->collection_date) }}">
                                            </div>
                                        </div>

                                        <!-- No of Cheques -->
                                        <div class="col-md-6" id="no_of_cheque_container"
                                            style="{{ in_array($entry->cheque_type, ['collection', 'security', 'only_deed']) ? '' : 'display: none;' }}">
                                            <div class="form-group">
                                                <label for="no_of_cheque">{{ __('No of Cheque') }}<span
                                                        class="text-danger">*</span>:</label>
                                                <input type="number" name="no_of_cheque" id="no_of_cheque"
                                                    class="form-control"
                                                    value="{{ old('no_of_cheque', $entry->no_of_cheque) }}">
                                            </div>
                                        </div>

                                        <!-- Reference -->
                                        <div class="col-md-6" id="reference_container"
                                            style="{{ $entry->cheque_type == 'installment' ? '' : 'display: none;' }}">
                                            <div class="form-group">
                                                <label for="reference">{{ __('Reference') }}<span
                                                        class="text-danger">*</span>:</label>
                                                <select name="reference" id="reference" class="form-control">
                                                    <option value="">{{ __('Select Reference') }}</option>
                                                    @foreach ($emis as $emi)
                                                        <option value="{{ $emi->id }}"
                                                            {{ $entry->reference == $emi->id ? 'selected' : '' }}>
                                                            {{ $emi->reference_number }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>

                                        <!-- Remarks -->
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="remarks">{{ __('Remarks') }}:</label>
                                                <textarea name="remarks" id="remarks" class="form-control" maxlength="512">{{ old('remarks', $entry->remarks) }}</textarea>
                                                <small class="text-muted">Maximum 512 characters</small>
                                            </div>
                                        </div>

                                        <!-- Document -->
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="deed">{{ __('Deed/Document') }}:</label>
                                                <x-file-uploader :value="$entry->document" name="document" />
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Bank Info Input -->
                                    <div class="row mt-3" id="bank_input_section" style="display: none;">
                                        <div class="col-md-12">
                                            <h4>Add More Bank Information</h4>
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
                                                        </select>
                                                    </div>
                                                </div>

                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label for="cheque_no">Cheque No<span
                                                                class="text-danger">*</span>:</label>
                                                        <input type="text" id="cheque_no" class="form-control">
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
                                    <div class="row mt-3" id="bank_table_section">
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
                                                    @foreach ($entry->details as $index => $detail)
                                                        <tr
                                                            class="{{ $detail->is_security_cheque ? 'security-cheque-row' : '' }}">
                                                            <td>
                                                                @if ($detail->is_security_cheque)
                                                                    Security Cheque
                                                                    <input type="hidden" name="is_security_cheque[]"
                                                                        value="1">
                                                                @else
                                                                    {{ $index + 1 }}
                                                                    <input type="hidden" name="is_security_cheque[]"
                                                                        value="0">
                                                                @endif
                                                            </td>
                                                            <td>
                                                                <input type="hidden" name="bank_name[]"
                                                                    value="{{ $detail->bank->name }}">
                                                                <input type="hidden" name="bank_ids[]"
                                                                    value="{{ $detail->bank_id }}">
                                                                {{ $detail->bank->name }}
                                                            </td>
                                                            <td>
                                                                <input type="hidden" name="branch_name[]"
                                                                    value="{{ $detail->branch->name }}">
                                                                <input type="hidden" name="branch_ids[]"
                                                                    value="{{ $detail->branch_id }}">
                                                                {{ $detail->branch->name }}
                                                            </td>
                                                            <td><input type="text" class="form-control"
                                                                    name="cheque_no[]" value="{{ $detail->cheque_no }}">
                                                            </td>
                                                            <td><input type="text" name="cheque_date[]"
                                                                    class="form-control flatdate"
                                                                    value="{{ $detail->cheque_date }}"
                                                                    {{ $detail->is_security_cheque ? '' : 'required' }}>
                                                            </td>
                                                            <td><input type="number" name="amount[]"
                                                                    class="form-control {{ $detail->is_security_cheque ? 'security-amount' : 'installment-amount' }}"
                                                                    value="{{ $detail->amount }}"
                                                                    {{ $detail->is_security_cheque ? 'readonly' : '' }}>
                                                            </td>
                                                            <td>
                                                                <div class="d-flex align-items-center gap-1">

                                                                    {{-- Always visible Paperclip button --}}
                                                                    <button type="button"
                                                                        class="btn btn-xs btn-secondary attachments"
                                                                        title="Add Attachment">
                                                                        <i class="fa fa-paperclip"></i>
                                                                    </button>

                                                                    {{-- Existing attachment থাকলে Eye icon --}}
                                                                    @if(!empty($detail->document))
                                                                        <button type="button"
                                                                            class="btn btn-xs btn-success view-attachment"
                                                                            data-url="{{ asset($detail->document) }}"
                                                                            title="View Attachment">
                                                                            <i class="fa fa-eye"></i>
                                                                        </button>
                                                                    @endif

                                                                </div>

                                                                {{-- Existing document value --}}
                                                                <input type="hidden"
                                                                    name="documents[]"
                                                                    value="{{ $detail->document }}"
                                                                    class="attachments_input">
                                                            </td>

                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                                <tfoot>
                                                    <tr>
                                                        <td colspan="5" class="text-end"><strong>Total Amount:</strong>
                                                        </td>
                                                        <td id="total_amount">{{ $entry->details->sum('amount') }}</td>
                                                        <td colspan="2"></td>
                                                    </tr>
                                                </tfoot>
                                            </table>
                                        </div>
                                    </div>

                                    <div class="d-flex justify-content-end mt-4">
                                        <button type="submit"
                                            class="btn btn-primary btn-sm">{{ __('Update') }}</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="attachmentModal" tabindex="-1"
        role="dialog" aria-labelledby="attachmentModalLabel" aria-hidden="true">

        <div class="modal-dialog modal-xl modal-dialog-centered" role="document">

            <div class="modal-content">

                <div class="modal-header">
                    <h5 class="modal-title" id="attachmentModalLabel">
                        Attachment
                    </h5>

                    <button type="button"
                        class="close"
                        data-dismiss="modal"
                        aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <div class="modal-body text-center" id="attachmentContent">
                </div>

            </div>

        </div>
    </div>
    
@endsection

@section('page_scripts')
    <script>
        var branches = {!! json_encode($branches) !!};
        var entryChequeType = "{{ $entry->cheque_type }}";

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
                $('#reference_container').hide();

                // Show relevant fields based on cheque type
                if (chequeType === 'collection' || chequeType === 'security' || chequeType ===
                    'only_deed') {
                    $('#no_of_cheque_container').show();
                } else if (chequeType === 'installment') {
                    $('#reference_container').show();
                }
            });

            // Initialize based on existing cheque type
            if (entryChequeType === 'collection' || entryChequeType === 'security' || entryChequeType ===
                'only_deed') {
                $('#no_of_cheque_container').show();
            } else if (entryChequeType === 'installment') {
                $('#reference_container').show();
            }

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

            // Initialize document preview for existing entries
            updateDocumentsPreview();

            // Remove row functionality


            // Add new bank info functionality
            $('#add_bank_info').click(function() {
                const bankId = $('#bank_id').val();
                const bankName = $('#bank_id option:selected').data('bank_name');
                const branchId = $('#bank_branch_id').val();
                const branchName = $('#bank_branch_id option:selected').text();
                const chequeNo = $('#cheque_no').val();
                const chequeType = $('#cheque_type').val();

                if (!bankId || !branchId || !chequeNo) {
                    alert('Please fill all bank information fields');
                    return;
                }

                const tableBody = $('#bank_info_table');
                const rowCount = tableBody.find('tr').length;

                const row = `
                <tr>
                    <td>${rowCount + 1}
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

                // Reinitialize flatpickr for new date fields
                $('.flatdate').flatpickr({
                    dateFormat: 'Y-m-d',
                    allowInput: true
                });
            });
        });

        $(document).on('click', '.view-attachment', function () {

            let url = $(this).data('url');

            if (!url) {
                return;
            }

            let extension = url.split('.').pop().toLowerCase();

            let content = '';

            // Image
            if (['jpg', 'jpeg', 'png', 'gif', 'webp'].includes(extension)) {

                content = `
                    <div class="text-center">
                        <img src="${url}"
                            class="img-fluid"
                            style="max-height:75vh; object-fit:contain;"
                            alt="Attachment">
                    </div>
                `;

            }

            // PDF
            else if (extension === 'pdf') {

                content = `
                    <iframe
                        src="${url}"
                        width="100%"
                        height="700px"
                        style="border:none;">
                    </iframe>
                `;

            }

            // Other files
            else {

                content = `
                    <div class="text-center py-5">

                        <i class="fa fa-file fa-4x text-secondary"></i>

                        <h5 class="mt-3">
                            Attachment
                        </h5>

                        <a href="${url}"
                        target="_blank"
                        class="btn btn-primary">
                            <i class="fa fa-external-link"></i>
                            Open File
                        </a>

                    </div>
                `;
            }

            $('#attachmentContent').html(content);

            $('#attachmentModal').modal('show');
        });

    </script>

    <style>
        .security-cheque-row {
            background-color: #fff3cd;
            font-weight: bold;
        }

        .security-cheque-row td {
            border-top: 2px solid #ffc107;
            border-bottom: 2px solid #ffc107;
        }

        .security-amount {
            background-color: #f8d7da;
            font-weight: bold;
        }

        .installment-amount {
            background-color: #e7f5ff;
        }

        #total_amount {
            font-weight: bold;
            background-color: #d1e7dd;
        }
    </style>
@endsection
