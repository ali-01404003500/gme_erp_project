<div class="payment-section table-responsive border p-3">
    <div class="row mb-3">
        <div class="col-md-3 mb-2">
            <label for="input-pay-mode" class="form-label">Pay Mode</label>
            <select id="input-pay-mode" class="form-select tom-select">
                <option value="">Select pay mode</option>
                <option value="Cash">Cash</option>
                <option value="Cheque">Cheque</option>
                <option value="Online Deposit">Online Deposit</option>
                <option value="bKash">bKash</option>
                <option value="Nagad">Nagad</option>
                <option value="Rocket">Rocket</option>
                <option value="AIT">AIT</option>
                <option value="Waiver">Waiver</option>
                <option value="Waiver Bad Debt">Waiver Bad Debt</option>
            </select>
        </div>
        <div class="col-md-3 mb-2 pay-field account-field"></div>
        <div class="col-md-3 mb-2 pay-field bank-field">
            <label for="input-bank" class="form-label">Bank</label>
            <select id="input-bank" class="form-select tom-select">
                <option value=""></option>
                @php
                    if (!isset($banks) || !$banks->count()) {
                        $banks = \Modules\Account\Models\Bank::all();
                    }
                @endphp
                @foreach ($banks as $bank)
                    <option value="{{ $bank->id }}">{{ $bank->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-3 mb-2 pay-field branch-field">
            <label for="input-branch" class="form-label">Branch</label>
            <select id="input-branch" class="form-select tom-select">
                <option value="">Select branch</option>
            </select>
        </div>
        <div class="col-md-3 mb-2 pay-field txn-field">
            <label for="input-txn" class="form-label">Transaction ID</label>
            <input type="text" id="input-txn" class="form-control">
        </div>
        <div class="col-md-3 mb-2">
            <label for="input-date" class="form-label">Date</label>
            <input type="text" id="input-date" class="form-control flatdate">
        </div>
        <div class="col-md-3 mb-2">
            <label for="input-amount" class="form-label">Amount</label>
            <input type="number" id="input-amount" class="form-control" placeholder="Amount" step="0.01" min="0">
        </div>
        
        <div class="col-md-3">
            <label for="input-file" class="form-label">Files</label>
            <input type="file" id="input-file" class="form-control" name="images[]" multiple accept="image/*">
        </div>
        <div class="col-md-12 mb-2">
            <label for="input-remark" class="form-label">Remark</label>
            <textarea id="input-remark" class="form-control" rows="2" placeholder="Enter remark here"></textarea>
        </div>
        <div class="col-md-3 mb-2 ms-auto">
            <button id="add-payment" class="btn btn-xs btn-success w-100"><i class="fa fa-plus"></i> Add</button>
        </div>
    </div>

    <table class="table table-bordered" id="payment-table">
        <thead class="table-light">
            <tr>
                <th>Pay Mode</th>
                <th>Collection Point (Bank)</th>
                {{-- <th>Number (Branch)</th> --}}
                <th>Transaction ID</th>
                <th>Date</th>
                <th>Amount</th>
                <th>File</th>
                <th>Remark</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody id="payment-body">
            @php
                $paymodes = old('payments_pay_mode', []);
            @endphp
            @if (!empty($paymodes))
                @foreach ($paymodes as $key => $paymode)
                    <tr>
                        <td>{{ $paymode }}<input type="hidden" name="payments_pay_mode[]" value="{{ $paymode }}"></td>
                        <td>
                            @php
                                $bank_name = \Modules\Account\Models\Bank::find(old('payments_bank_id')[$key] ?? '');
                            @endphp
                            {{ $bank_name?->name ?? '' }}<input type="hidden" name="payments_bank_id[]"
                                value="{{ old('payments_bank_id')[$key] ?? '' }}">
                        </td>
                        {{-- <td>{{ old('payments_branch_name')[$key] ?? '' }}<input type="hidden" name="payments_branch_id[]"
                                value="{{ old('payments_branch_id')[$key] ?? '' }}"></td> --}}
                        <td>{{ old('payments_transaction_id')[$key] ?? '' }}<input type="hidden"
                                name="payments_transaction_id[]" value="{{ old('payments_transaction_id')[$key] ?? '' }}"></td>
                        <td>{{ old('payments_date')[$key] ?? '' }}<input type="hidden" name="payments_date[]"
                                value="{{ old('payments_date')[$key] ?? '' }}"></td>
                        <td class="amount-value">{{ old('payments_amount')[$key] ?? 0 }}<input type="hidden"
                                name="payments_amount[]" value="{{ old('payments_amount')[$key] ?? '' }}"></td>
                        <td>
                            <span class="file_name">{{ old('payments_attachments')[$key] ?? '' }}</span>
                            <div class="spinner-border spinner-border-sm" style="display: none;" role="status"></div>
                            <input type="hidden" name="payments_attachments[]" class="attachments"
                                value="{{ old('payments_attachments')[$key] ?? '' }}">
                            <input type="hidden" name="payments_verified[]" class="verified"
                                value="{{ old('payments_verified')[$key] ?? '' }}">
                        </td>
                        <td>{{ old('payments_remark')[$key] ?? '' }}<input type="hidden" name="payments_remark[]"
                                value="{{ old('payments_remark')[$key] ?? '' }}"></td>
                        <td><button class="btn btn-danger btn-xs remove-row"><i class="fa fa-trash"></i></button></td>
                    </tr>
                @endforeach
            @elseif(isset($payments) && count($payments))
                @foreach ($payments as $payment)
                    <tr>
                        {{-- @dd( $payment->bank) --}}
                        <td>{{ $payment->pay_mode ?? '' }}<input type="hidden" name="payments_pay_mode[]"
                                value="{{ $payment->pay_mode ?? '' }}"></td>
                        <td>{{ $payment->bank->account_name ?? '' }}<input type="hidden" name="payments_bank_id[]"
                                value="{{ $payment->bank_id ?? '' }}"></td>
                        <td>{{ $payment->transaction_id ?? '' }}<input type="hidden" name="payments_transaction_id[]"
                                value="{{ $payment->transaction_id ?? '' }}"></td>
                        <td>{{ $payment->date ? \Carbon\Carbon::parse($payment->date)->format('Y-m-d') : '' }}<input type="hidden" name="payments_date[]"
                                value="{{ $payment->date ?? '' }}"></td>
                        <td class="amount-value">{{ number_format($payment->amount ?? 0, 2) }}<input type="hidden" name="payments_amount[]"
                                value="{{ $payment->amount ?? '' }}"></td>
                        <td>
                            <span class="file_name">
                                {{-- @if($payment->attachments)
                                    <button type="button" onclick="showFiles('{{ $payment->attachments }}')"
                                        class="btn btn-outline-primary btn-sm download-file"><i class="fa fa-eye"></i>
                                        preview</button>
                                @endif --}}

                                @php
                                    $attachments = $payment->attachments;

                                    // JSON decode
                                    if (is_string($attachments)) {
                                        $attachments = json_decode($attachments, true);
                                    }

                                    // Double encoded JSON হলে আবার decode
                                    if (is_string($attachments)) {
                                        $attachments = json_decode($attachments, true);
                                    }

                                    // Null / invalid হলে empty array
                                    if (!is_array($attachments)) {
                                        $attachments = !empty($attachments) ? [$attachments] : [];
                                    }
                                @endphp


                                @foreach($attachments as $attachment)

                                    @php
                                        $files = $attachment;

                                        // Attachment নিজেও JSON string হলে decode
                                        if (is_string($files)) {
                                            $decoded = json_decode($files, true);

                                            if (json_last_error() === JSON_ERROR_NONE) {
                                                $files = $decoded;
                                            }
                                        }

                                        // Double encoded হলে আবার decode
                                        if (is_string($files)) {
                                            $decoded = json_decode($files, true);

                                            if (json_last_error() === JSON_ERROR_NONE) {
                                                $files = $decoded;
                                            }
                                        }

                                        // Single file হলে array বানানো
                                        if (!is_array($files)) {
                                            $files = !empty($files) ? [$files] : [];
                                        }
                                    @endphp

                                    @foreach($files as $file)
                                        @if(!empty($file))
                                            <a href="{{ url($file) }}"
                                            target="_blank"
                                            class="btn btn-sm btn-outline-info mb-1">
                                                <i class="fa fa-eye"></i>
                                            </a>
                                        @endif
                                    @endforeach

                                @endforeach
                            </span>
                            <div class="spinner-border spinner-border-sm" style="display: none;" role="status"></div>
                            <input type="hidden" name="payments_attachments[]" class="attachments"
                                value="{{ $payment->attachments ?? '' }}">
                            <input type="hidden" name="payments_verified[]" class="verified"
                                value="{{ $payment->verified ?? 0 }}">
                        </td>
                        {{-- @dd($payment) --}}
                        <td>{{ $payment->remark ?? '' }}<input type="hidden" name="payments_remark[]"
                                value="{{ $payment->remark ?? '' }}"></td>
                        <td><button class="btn btn-danger btn-xs remove-row"><i class="fa fa-trash"></i></button></td>
                    </tr>
                @endforeach
            @endif
        </tbody>
        <tfoot>
            <tr>
                <td colspan="5" class="text-end fw-bold">Total:</td>
                <td colspan="3" class="fw-bold text-primary" id="total-display">
                    ৳ <span>0.00</span>
                    <input type="hidden" name="payments_total_amount" value="0.00">
                </td>
            </tr>
            <tr>
                <td colspan="5" class="text-end fw-bold">Payable:</td>
                <td colspan="3" class="fw-bold text-primary" id="total-payable">
                    ৳ <span>0.00</span>
                    <input type="hidden" name="payments_payable_amount" value="0.00">
                </td>
            </tr>
            <tr>
                <td colspan="5" class="text-end fw-bold">Due:</td>
                <td colspan="3" class="fw-bold text-danger" id="total-due">
                    ৳ <span>0.00</span>
                    <input type="hidden" name="payments_due_amount" value="0.00">
                </td>
            </tr>
            <tr>
                <td colspan="5" class="text-end fw-bold">Advance:</td>
                <td colspan="3" class="fw-bold text-success" id="total-advance">
                    ৳ <span>0.00</span>
                    <input type="hidden" name="payments_advance_amount" value="0.00">
                </td>
            </tr>
        </tfoot>
    </table>
</div>

<div class="modal fade" id="full-screen-modal" tabindex="-1" aria-labelledby="fullScreenModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="fullScreenModalLabel">Image Preview</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <img src="" alt="Payment Image" class="img-fluid" id="full-screen-image">
            </div>
        </div>
    </div>
</div>

@push('script')
    <script>
        // function showFile(url) {
        //     // Get the file extension to determine if it's an image
        //     const fileExtension = url.split('.').pop().toLowerCase();
        //     const imageExtensions = ['jpg', 'jpeg', 'png', 'gif', 'bmp', 'webp', 'svg'];

        //     if (imageExtensions.includes(fileExtension)) {
        //         // It's an image, show in modal
        //         $('#full-screen-image').attr('src', url);
        //         $('#full-screen-modal').modal('show');
        //     } else {
        //         // It's not an image, open in new tab
        //         window.open(url, '_blank');
        //     }
        // }


        function showFiles(urls) {
            if (!Array.isArray(urls)) {
                urls = [urls];
            }

            let fileExtension = urls[0].split('.').pop().toLowerCase();
            let imageExtensions = ['jpg', 'jpeg', 'png', 'gif', 'bmp', 'webp', 'svg'];

            // Check if images
            if (imageExtensions.includes(fileExtension)) {

                let html = '';

                urls.forEach(url => {
                    html += `<img src="${url}" class="img-fluid mb-2" style="max-height:400px;"><br>`;
                });

                $('#full-screen-image').hide(); // old single image hide
                $('#full-screen-modal .modal-body').html(html);

                $('#full-screen-modal').modal('show');

            } else {
                window.open(urls[0], '_blank');
            }
        }

        function updatePayable(payable) {
            $('#total-payable span').text(payable);
            $('input[name="payments_payable_amount"]').val(payable);
            updateDue();
        }

        function updateDue() {
            const payable = parseFloat($('input[name="payments_payable_amount"]').val()) || 0;
            const total = parseFloat($('input[name="payments_total_amount"]').val()) || 0;
            const difference = total - payable;

            if (difference > 0) {
                $('#total-due span').text("0.00");
                $('input[name="payments_due_amount"]').val("0.00").trigger('change');
                $('#total-advance span').text(difference.toFixed());
                $('input[name="payments_advance_amount"]').val(difference.toFixed());
            } else {
                const due = Math.abs(difference);
                console.log({ due });
                $('#input-amount').val(due.toFixed());
                $('#total-due span').text(due.toFixed());
                $('input[name="payments_due_amount"]').val(due.toFixed()).trigger('change');
                $('#total-advance span').text("0.00");
                $('input[name="payments_advance_amount"]').val("0.00");
            }
        }

        async function uploadFile(file) {
            const formData = new FormData();
            formData.append('_token', '{{ csrf_token() }}');
            formData.append('file', file);
            const response = await fetch("{{route('upload_file')}}", {
                method: 'POST',
                body: formData
            });
            if (response) {
                toastr.success("File uploaded successfully");
            }
            return await response.json();
        }

        async function deleteFile(url) {
            const response = await fetch(url, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            });
            if (response.message) {
                toastr.success(response.message);
            }
            return await response.json();
        }

        function updateTotal() {
            let total = 0;
            $('.amount-value').each(function () {
                let val = parseFloat($(this).text());
                if (!isNaN(val)) total += val;
            });
            $('#total-display span').text(total);
            $('input[name="payments_total_amount"]').val(total);
            updateDue();
        }

        function toggleFormFields(type) {
            $('.account-field, .bank-field, .branch-field, .txn-field').addClass('d-none');
            switch (type) {
                case 'Cash':
                    $('.account-field').removeClass('d-none');
                    break;
                case 'Cheque':
                    // $('.bank-field, .branch-field, .txn-field').removeClass('d-none');
                    $('.account-field, .txn-field').removeClass('d-none');
                    $('.txn-field .form-label').text('Cheque No');
                    break;
                // break;
                case 'Online Deposit':
                    $('.account-field, .txn-field').removeClass('d-none');
                    $('.txn-field .form-label').text('Transaction ID');
                    break;
                case 'bKash':
                case 'Nagad':
                case 'Rocket':
                case 'Card Payment':
                    $('.account-field, .txn-field').removeClass('d-none');
                    $('.txn-field .form-label').text('Transaction ID');
                    break;
                case 'AIT':
                case 'Waiver':
                case 'Waiver Bad Debt':
                    // No account or txn field needed for these modes
                    break;
                default:
                    $('.account-field, .txn-field').removeClass('d-none');
                    $('.txn-field .form-label').text('Transaction ID');
            }
        }

        function resetInputs() {
            $('#input-pay-mode').prop('tomselect')?.clear();
            $('#input-pay-mode').prop('tomselect')?.setValue('Cash'); // Silently set to Cash and trigger change

            $('#input-account').prop('tomselect')?.clear();
            $('#input-bank').prop('tomselect')?.clear();
            $('#input-branch').prop('tomselect')?.clear();
            $('#input-txn').val('');
            $('#input-date').val(new Date().toISOString().split('T')[0]);
            $('#input-amount').val($('input[name="payments_due_amount"]').val() || 0);
            $('#input-file').val('');
            $('#input-remark').val('');
            toggleFormFields('Cash');
        }

        $(document).ready(function () {
            $('#input-date').val(new Date().toISOString().split('T')[0]);

            $('#input-pay-mode').on('change', function () {
                const mode = $(this).val();
                if (['AIT', 'Waiver', 'Waiver Bad Debt'].includes(mode)) {
                    $(".account-field").empty();
                    toggleFormFields(mode);
                    return;
                }

                $.ajax({
                    url: '{{ route('account.account-setup.bank-accounts.get-accounts') }}',
                    type: 'GET',
                    data: { payment_mode: $(this).val() == "Cheque" ? "Online Deposit" : $(this).val() },
                    success: function (response) {
                        if (response) {
                            const select = $('<select id="input-account" class="form-select tom-select"><option value="">Select Account</option></select>');
                            response.forEach(account => {
                                select.append($('<option></option>').attr('value', account.id).text(account.account_name));
                            });
                            $(".account-field").html('<label for="input-account" class="form-label">Select Account</label>').append(select);
                            if (select.find('option').length === 2) {
                                select.find('option:nth-child(2)').attr('selected', 'selected');
                            }
                            new TomSelect(select[0]);
                        }
                    }
                });
                toggleFormFields(mode);
            });
            resetInputs();


            @if($payments)
                updateTotal();
            @endif

            $('#add-payment').on('click', function (e) {
                e.preventDefault();

                const payMode = $('#input-pay-mode').val();
                const bank_name = $('#input-bank option:selected').text();
                const bank_id = $('#input-bank').val();
                const account_name = $('#input-account option:selected').text();
                const account_id = $('#input-account').val();
                const branch_name = $('#input-branch option:selected').text();
                const txn = $('#input-txn').val();
                const date = $('#input-date').val();
                const amount = $('#input-amount').val();
                const fileInput = $('#input-file')[0];
                //const file = fileInput.files[0];
                const files = fileInput.files;
                const remark = $('#input-remark').val();
 

                if (!payMode || !date || !amount || !remark || (!account_id && !['AIT', 'Waiver', 'Waiver Bad Debt'].includes(payMode))) {
                    toastr.error('Please fill up all the required fields');
                    return;
                }

                let row = $(`<tr>
                    <td>${payMode}<input type="hidden" name="payments_pay_mode[]" value="${payMode}"></td>
                    <td>${['AIT', 'Waiver', 'Waiver Bad Debt'].includes(payMode) ? '' : (account_id ? account_name : bank_name)}<input type="hidden" name="payments_bank_id[]" value="${account_id || bank_id || ''}"></td>
                    <td>${txn}<input type="hidden" name="payments_transaction_id[]" value="${txn}"></td>
                    <td>${date}<input type="hidden" name="payments_date[]" value="${date}"></td>
                    <td class="amount-value">${parseFloat(amount).toFixed()}<input type="hidden" name="payments_amount[]" value="${amount}"></td>
                    <td>
                        <span class="file_name"></span>
                        <div class="spinner-border spinner-border-sm" role="status"></div>
                        <input type="hidden" name="payments_attachments[]" class="attachments">
                        <input type="hidden" name="payments_verified[]" class="verified" value="0">
                    </td>
                    <td>${remark}<input type="hidden" name="payments_remark[]" value="${remark}"></td>
                    <td><button class="btn btn-danger btn-xs remove-row"><i class="fa fa-trash"></i></button></td>
                </tr>`);

                $('#payment-body').append(row);

                // if (file) {
                //     uploadFile(file).then(res => {
                //         row.find('.spinner-border').hide();
                //         if (res.path) {
                //             row.find('.attachments').val(res.path);
                //             row.find('.file_name').html(`<button type="button" onclick="showFile('${res.path}')" class="btn btn-outline-primary btn-sm download-file"><i class="fa fa-eye"></i> preview</button>`)
                //         }
                //     });
                // }
                if (files.length > 0) {

                    let uploadedPaths = [];

                    let uploadPromises = Array.from(files).map(file => {
                        return uploadFile(file).then(res => {
                            if (res.path) {
                                uploadedPaths.push(res.path);
                            }
                        });
                    });

                    Promise.all(uploadPromises).then(() => {

                        row.find('.spinner-border').hide();

                        // save as JSON or comma separated
                        row.find('.attachments').val(JSON.stringify(uploadedPaths));
 
                        row.find('.file_name').html(`<button type="button" onclick='showFiles(${JSON.stringify(uploadedPaths)})' class="btn btn-outline-primary btn-sm download-file"><i class="fa fa-eye"></i> preview</button>`)

                    });

                }  
                 else {
                    row.find('.spinner-border').hide();
                    row.find('.file_name').text('');
                    row.find('.attachments').val('');
                } 

                updateTotal();
                resetInputs();
            });

            $(document).on('click', '.remove-row', function () {
                const row = $(this).closest('tr');
                if (row.find('.attachments').val()) {
                    deleteFile(row.find('.attachments').val());
                }
                row.remove();
                updateTotal();
            });

            $('#input-bank').on('change', function () {
                const bankId = $(this).val();
                $('#input-branch').prop('tomselect')?.clearOptions();
                if (bankId) {
                    $.ajax({
                        url: '{{route('account.account-setup.ajax.bank-branches')}}',
                        method: 'GET',
                        data: { bank_id: bankId },
                        success: function (data) {
                            $('#input-branch').prop('tomselect').addOption(data.map(branch => ({
                                value: branch.id,
                                text: branch.name
                            })));
                        }
                    });
                }
            });
        });
    </script>
@endpush