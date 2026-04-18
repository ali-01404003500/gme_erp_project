<div class="payment-section table-responsive border p-3 rounded bg-white shadow-sm">
    <!-- Payment Entry Card -->
    <div class="card mb-3">
        <div class="card-body">
            <h6 class="card-title mb-3"><i class="fa fa-credit-card"></i> Add Payment</h6>
            <div class="row g-3">
                <div class="col-md-3">
                    <label for="input-pay-mode" class="form-label">Pay Mode <span class="text-danger">*</span></label>
                    <select id="input-pay-mode" class="form-select tom-select">
                        <option value="">Select pay mode</option>
                        <option value="Cash">Cash</option>
                        <option value="Cheque">Cheque</option>
                        <option value="Online Deposit">Online Deposit</option>
                        <option value="bKash">bKash</option>
                        <option value="Nagad">Nagad</option>
                        <option value="Rocket">Rocket</option>
                        <option value="Card Payment">Card</option>
                        <option value="EMI">Payment EMI</option>
                    </select>
                </div>

                <div class="col-md-3 pay-field account-field d-none"></div>

                <div class="col-md-3 pay-field bank-field d-none">
                    <label for="input-bank" class="form-label">Bank</label>
                    <select id="input-bank" class="form-select tom-select">
                        <option value="">Select bank</option>
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

                <div class="col-md-3 pay-field branch-field d-none">
                    <label for="input-branch" class="form-label">Branch</label>
                    <select id="input-branch" class="form-select tom-select">
                        <option value="">Select branch</option>
                    </select>
                </div>

                <div class="col-md-3 pay-field txn-field d-none">
                    <label for="input-txn" class="form-label">Transaction ID</label>
                    <input type="text" id="input-txn" class="form-control" placeholder="Transaction ID">
                </div>

                <div class="col-md-3">
                    <label for="input-date" class="form-label">Date <span class="text-danger">*</span></label>
                    <input type="text" id="input-date" class="form-control flatdate">
                </div>

                <div class="col-md-3">
                    <label for="input-amount" class="form-label">Amount <span class="text-danger">*</span></label>
                    <div class="input-group">
                        <span class="input-group-text">৳</span>
                        <input type="number" id="input-amount" class="form-control" placeholder="Amount" step="0.01"
                            min="0">
                    </div>
                </div>

                <div class="col-md-3">
                    <label for="input-file" class="form-label">File</label>
                    <div class="file-upload">
                        <input type="file" id="input-file" class="d-none">
                        <label for="input-file" class="btn btn-outline-primary w-100">
                            <i class="fa fa-upload"></i> Upload File
                        </label>
                        <small class="text-muted d-block mt-1" id="file-name-display">No file selected</small>
                    </div>
                </div>

                <div class="col-md-12">
                    <label for="input-remark" class="form-label">Remark <span class="text-danger">*</span></label>
                    <textarea id="input-remark" class="form-control" rows="2"
                        placeholder="Enter remark here"></textarea>
                </div>

                <div class="col-md-3 ms-auto">
                    <button id="add-payment" class="btn btn-success btn-lg w-100">
                        <i class="fa fa-plus-circle"></i> Add Payment
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Payment Table -->
    <table class="table table-bordered align-middle" id="payment-table">
        <thead class="table-light">
            <tr>
                <th>Pay Mode</th>
                <th>Collection Point (Bank/Account)</th>
                <th>Branch</th>
                <th>Transaction ID</th>
                <th>Date</th>
                <th>Amount</th>
                <th>File</th>
                <th>Remark</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody id="payment-body">
            {{-- Existing rows populated here --}}
            @include('partials.payment-rows')
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

<!-- Modal Preview -->
<div class="modal fade" id="full-screen-modal" tabindex="-1">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Image Preview</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <img src="" alt="Payment Image" class="img-fluid" id="full-screen-image">
            </div>
        </div>
    </div>
</div>

@include('Account::emi-entries.emi_create_modal')

@push('style')
    <style>
        #payment-table tfoot {
            position: sticky;
            bottom: 0;
            background: #fff;
            box-shadow: 0 -2px 5px rgba(0, 0, 0, 0.05);
        }
    </style>
@endpush

@push('script')
    <script>
        function showImage(url) {
            $('#full-screen-image').attr('src', url);
            $('#full-screen-modal').modal('show');
        }

        function updateTotals() {
            let total = 0;
            $('.amount-value').each(function () {
                let val = parseFloat($(this).text());
                if (!isNaN(val)) total += val;
            });
            $('#total-display span').text(total.toFixed());
            $('input[name="payments_total_amount"]').val(total.toFixed());

            const payable = parseFloat($('input[name="payments_payable_amount"]').val()) || 0;
            const diff = total - payable;

            if (diff > 0) {
                $('#total-due span').text("0.00");
                $('#total-advance span').text(diff.toFixed());
                $('input[name="payments_due_amount"]').val("0.00");
                $('input[name="payments_advance_amount"]').val(diff.toFixed());
            } else {
                $('#total-due span').text(Math.abs(diff).toFixed());
                $('#total-advance span').text("0.00");
                $('input[name="payments_due_amount"]').val(Math.abs(diff).toFixed());
                $('input[name="payments_advance_amount"]').val("0.00");
            }
        }

        const paymentConfig = {
            Cash: ['account-field'],
            Cheque: ['bank-field', 'branch-field', 'txn-field'],
            "Online Deposit": ['account-field'],
            bKash: ['account-field', 'txn-field'],
            Nagad: ['account-field', 'txn-field'],
            Rocket: ['account-field', 'txn-field'],
            "Card Payment": ['account-field', 'txn-field'],
        };

        function toggleFormFields(type) {
            $('.pay-field').addClass('d-none');
            (paymentConfig[type] || ['account-field', 'txn-field']).forEach(cls => {
                $(`.${cls}`).removeClass('d-none');
            });
        }

        function resetInputs() {
            $('#input-pay-mode').val('');
            $('#input-bank, #input-branch, #input-txn, #input-amount, #input-file, #input-remark').val('');
            $('#input-date').val(new Date().toISOString().split('T')[0]);
            $('#file-name-display').text('No file selected');
            toggleFormFields('');
        }

        $(document).ready(function () {
            $('#input-date').val(new Date().toISOString().split('T')[0]);

            $('#input-pay-mode').on('change', function () {
                toggleFormFields($(this).val());
                // fetch accounts (Ajax) can be placed here...
            });

            $('#input-file').on('change', function () {
                $('#file-name-display').text(this.files[0]?.name || 'No file selected');
            });

            $('#add-payment').on('click', function (e) {
                e.preventDefault();
                const payMode = $('#input-pay-mode').val();
                const bankName = $('#input-bank option:selected').text();
                const bankId = $('#input-bank').val();
                const branchName = $('#input-branch option:selected').text();
                const branchId = $('#input-branch').val();
                const txn = $('#input-txn').val();
                const date = $('#input-date').val();
                const amount = $('#input-amount').val();
                const remark = $('#input-remark').val();

                if (!payMode || !date || !amount || !remark) {
                    toastr.error('Please fill up all required fields');
                    return;
                }

                let row = $(`
                    <tr>
                        <td>${payMode}<input type="hidden" name="payments_pay_mode[]" value="${payMode}"></td>
                        <td>${bankName}<input type="hidden" name="payments_bank_id[]" value="${bankId}"></td>
                        <td>${branchName}<input type="hidden" name="payments_branch_id[]" value="${branchId}"></td>
                        <td>${txn}<input type="hidden" name="payments_transaction_id[]" value="${txn}"></td>
                        <td>${date}<input type="hidden" name="payments_date[]" value="${date}"></td>
                        <td class="amount-value">${parseFloat(amount).toFixed()}<input type="hidden" name="payments_amount[]" value="${amount}"></td>
                        <td><span class="file_name"></span><input type="hidden" name="payments_attachments[]" class="attachments"></td>
                        <td>${remark}<input type="hidden" name="payments_remark[]" value="${remark}"></td>
                        <td><button class="btn btn-danger btn-xs remove-row"><i class="fa fa-trash"></i></button></td>
                    </tr>
                `);

                $('#payment-body').append(row);
                updateTotals();
                resetInputs();
            });

            $(document).on('click', '.remove-row', function () {
                $(this).closest('tr').remove();
                updateTotals();
            });

            @if($payments)
                updateTotals();
            @endif
        });
    </script>
@endpush