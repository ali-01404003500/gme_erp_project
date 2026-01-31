@extends('layout.app')
@section('title', 'EMI Entry')
@section('description', 'EMI Entry')
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
                                    <li class="breadcrumb-item active" aria-current="page">{{ trans('EMI Entry') }}</li>
                                </ol>
                            </nav>
                        </div>
                        <div class="action-btn mt-sm-0 mt-15">
                            @if (hasPermission('account.emi-entries.index'))
                                <a href="{{ route('account.emi-entries.index') }}"
                                    class="btn btn-warning btn-default btn-squared radius-md shadow2 btn-sm">
                                    <i class="fa fa-list"></i> List
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12 m-2">
                    <h4 class="text-capitalize breadcrumb-title">{{ trans('Edit EMI Entry') }}</h4>
                    <x-error-alart />
                </div>

                <div class="card mb-50">
                    <div class="row justify-content-center" id="justify-content-center">
                        <div class="col-sm-11">
                            <div class="mt-40 mb-50">
                                <h2 class="mb-3">Edit EMI Entry</h2>
                                <form
                                    action="{{ route('account.emi-entries.update', [$emiEntry->id, app()->getLocale()]) }}"
                                    method="POST" enctype="multipart/form-data">
                                    @csrf
                                    @method('PUT')

                                    <div class="row">
                                        <!-- Customer -->
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="customer_id">Customer <span class="text-danger">*</span></label>
                                                <select name="customer_id" id="customer_id"
                                                    class="form-control tom-select required"
                                                    onchange="onChangeCustomer(this)" required>
                                                    <option value="">Select Customer</option>
                                                    @foreach ($customers as $customer)
                                                        <option value="{{ $customer->id }}"
                                                            {{ old('customer_id', $emiEntry->customer_id) == $customer->id ? 'selected' : '' }}
                                                            data-phone="{{ $customer->phone }}"
                                                            data-address="{{ $customer->address }}">
                                                            {{ $customer->company_name }} - {{ $customer->address}}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>

                                        <!-- Phone -->
                                        <div class="col-md-3">
                                            <label>Phone <span class="text-danger">*</span></label>
                                            <input type="text" id="contact_person_phone"
                                                value="{{ $emiEntry->customer->phone }}" class="form-control" readonly>
                                        </div>

                                        <!-- Address -->
                                        <div class="col-md-3">
                                            <label>Address <span class="text-danger">*</span></label>
                                            <input type="text" id="address" value="{{ $emiEntry->customer->address }}"
                                                class="form-control" readonly>
                                        </div>
                                    </div>

                                    <!-- Invoice, EMI Amount, Tenure -->
                                    <div class="row">
                                        <div class="col-md-6">
                                            <label>Invoice <span class="text-danger">*</span></label>
                                            <select name="sales_order_id" id="sales_order_id"
                                                class="form-control tom-select required">
                                                <option selected value="{{ $emiEntry->sales_order_id }}">
                                                    {{ @$emiEntry->salesOrder->sales_order_id }}</option>
                                            </select>
                                        </div>

                                        <div class="col-md-3">
                                            <label>EMI Amount <span class="text-danger">*</span></label>
                                            <input type="number" name="amount" id="emi_amount" step="0.01"
                                                value="{{ old('amount', $emiEntry->emi_amount) }}" class="form-control">
                                        </div>

                                        <div class="col-md-3">
                                            <label>Tenure Type <span class="text-danger">*</span></label>
                                            <select name="tenure_type" id="tenure_type" class="form-control tom-select">
                                                <option value="Months"
                                                    {{ $emiEntry->tenure_type == 'Months' ? 'selected' : '' }}>Months
                                                </option>
                                                <option value="Quarterly"
                                                    {{ $emiEntry->tenure_type == 'Quarterly' ? 'selected' : '' }}>Quarterly
                                                </option>
                                                <option value="Half Yearly"
                                                    {{ $emiEntry->tenure_type == 'Half Yearly' ? 'selected' : '' }}>Half
                                                    Yearly</option>
                                                <option value="Years"
                                                    {{ $emiEntry->tenure_type == 'Years' ? 'selected' : '' }}>Years
                                                </option>
                                            </select>
                                        </div>

                                        <div class="col-md-3">
                                            <label>Tenure No <span class="text-danger">*</span></label>
                                            <input type="number" name="tenure_no" id="tenure_no"
                                                value="{{ old('tenure_no', $emiEntry->tenure_no) }}" class="form-control">
                                        </div>

                                        <div class="col-md-3">
                                            <label>Interest Rate</label>
                                            <input type="number" name="interest_rate" id="interest_rate" step="0.01"
                                                value="{{ old('interest_rate', $emiEntry->interest_rate) }}"
                                                class="form-control">
                                        </div>

                                        <div class="col-md-3">
                                            <label>Scheduled Date <span class="text-danger">*</span></label>
                                            <input type="text" name="start_date" id="start_date"
                                                value="{{ old('start_date', $emiEntry->start_date) }}"
                                                class="form-control flatdate">
                                        </div>

                                        <div class="col-md-1 d-flex align-items-end">
                                            <button type="button" class="btn btn-success btn-sm" id="generate-emi">
                                                <i class="fa fa-cog"></i> Generate
                                            </button>
                                        </div>
                                    </div>



                                    <!-- Schedule -->
                                    <div class="mt-4" id="emi-schedule-section">
                                        <div class="col-md-3 ms-auto">
                                            <label for="custom_emi_amount" class="text-danger fw-bold">Custom EMI
                                                Installment Amount</label>
                                            <input type="number" class="form-control" id="custom_emi_amount"
                                                value="0" step="0.01">
                                        </div>

                                        <h5 class="text-info">EMI Schedule List</h5>

                                        <table class="table table-bordered text-center" id="emi-schedule-table">
                                            <thead>
                                                <tr>
                                                    <th>Tenure No</th>
                                                    <th>Repayment Date</th>
                                                    <th>Interest Amount</th>
                                                    <th>Principal Amount</th>
                                                    <th>EMI Amount</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($emiEntry->emiDetails as $key => $detail)
                                                    <tr>
                                                        <td>{{ $key + 1 }}</td>
                                                        <td>
                                                            <input type="text" name="emi_date[]"
                                                                value="{{ $detail->emi_date }}"
                                                                class="form-control form-control-sm flatdate">
                                                        </td>
                                                        <td class="bg-danger-subtle">
                                                            <span
                                                                class="interest-amount">{{ number_format($detail->interest_amount) }}</span>
                                                            <input type="hidden" name="interest_amount[]"
                                                                value="{{ $detail->interest_amount }}">
                                                        </td>
                                                        <td class="bg-danger-subtle">
                                                            <span
                                                                class="principal-amount">{{ number_format($detail->principal_amount) }}</span>
                                                            <input type="hidden" name="principal_amount[]"
                                                                value="{{ $detail->principal_amount }}">
                                                        </td>
                                                        <td class="bg-success-subtle">
                                                            <input type="number" name="emi_amount[]"
                                                                value="{{ $detail->emi_amount }}" step="0.01"
                                                                class="form-control form-control-sm emi-input">
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                            {{-- <tfoot>
                                                <tr>
                                                    <td><strong>Total</strong></td>
                                                    <td></td>
                                                    <td class="bg-danger-subtle"><strong id="total-interest">0.00</strong>
                                                    </td>
                                                    <td class="bg-danger-subtle"><strong
                                                            id="total-principal">0.00</strong></td>
                                                    <td class="bg-success-subtle"><strong id="total-emi">0.00</strong>
                                                    </td>
                                                </tr>
                                            </tfoot> --}}
                                        </table>
                                    </div>
                                    <!-- Remarks Field -->
                                    <div class="row mt-3">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label for="description" class="text-danger fw-bold">Description:</label>
                                                <textarea name="description" id="description" class="form-control" cols="30" rows="5">{{ old('description', $emiEntry->description) }}</textarea>
                                            </div>
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
@endsection

@section('page_scripts')
<script>
    let fixedTotalAmount = 0;

    function onChangeCustomer(element = null) {
        const selectedOption = $('#customer_id').find(':selected');
        const phone = selectedOption.data('phone');
        const address = selectedOption.data('address');

        $("#contact_person_phone").val(phone);
        $("#address").val(address);

        if (element) {
            const customerId = $(element).val();
            const salesOrderSelectEl = $('#sales_order_id')[0];
            const salesOrderSelect = salesOrderSelectEl.tomselect;

            salesOrderSelect.clearOptions();
            salesOrderSelect.setValue("");
            salesOrderSelect.disable();

            $.ajax({
                url: '{{ route('account.get-invoices') }}',
                type: 'GET',
                data: { customer_id: customerId },
                success: function(response) {
                    if (response.length > 0) {
                        response.forEach(function(salesOrder) {
                            salesOrderSelect.addOption({
                                value: salesOrder.id,
                                text: salesOrder.sales_order_id
                            });
                        });
                        salesOrderSelect.refreshOptions();
                        salesOrderSelect.enable();
                    }
                }
            });
        }
    }

    function recalculateSchedule() {
        const r = (parseFloat($('#interest_rate').val()) || 0) / 100 / 12;
        let balance = parseFloat($('#emi_amount').val()) || 0;
        let totalInterest = 0, totalPrincipal = 0, totalEmi = 0;

        // Only process rows that are NOT the total row
        $('#emi-schedule-table tbody tr:not(.total-row)').each(function() {
            const $row = $(this);
            const $emiInput = $row.find('.emi-input');
            if (!$emiInput.length) return;

            const emi = parseFloat($emiInput.val()) || 0;
            if (emi <= 0) return;

            let interest = balance * r;
            let principal = emi - interest;
            balance -= principal;

            totalInterest += interest;
            totalPrincipal += principal;
            totalEmi += emi;

            $row.find('td:eq(2)').html(`${interest.toFixed()}<input type="hidden" name="interest_amount[]" value="${interest.toFixed()}">`);
            $row.find('td:eq(3)').html(`${principal.toFixed()}<input type="hidden" name="principal_amount[]" value="${principal.toFixed()}">`);
        });

        // Update the total row
        const $tot = $('#emi-schedule-table tbody tr.total-row');
        if ($tot.length) {
            $tot.find('td:eq(2)').html(`<strong>${totalInterest.toFixed()}</strong>`);
            $tot.find('td:eq(3)').html(`<strong>${totalPrincipal.toFixed()}</strong>`);
            $tot.find('td:eq(4)').html(`<strong>${totalEmi.toFixed()}</strong>`);
        }

        // Update fixed total amount if not already set
        if (fixedTotalAmount === 0) fixedTotalAmount = totalEmi;
    }

    // Initialize fixed total from existing schedule on page load
    $(document).ready(function() {
        const existingInputs = $('.emi-input');
        if (existingInputs.length > 0) {
            let total = 0;
            existingInputs.each(function() {
                total += parseFloat($(this).val()) || 0;
            });
            fixedTotalAmount = total;
            
            // Add total row if not exists
            if (!$('#emi-schedule-table tbody tr.total-row').length) {
                const totalRow = `<tr class="total-row"><td><strong>Total</strong></td><td></td><td><strong>0.00</strong></td><td><strong>0.00</strong></td><td><strong>0.00</strong></td></tr>`;
                $('#emi-schedule-table tbody').append(totalRow);
            }
            
            recalculateSchedule();
        }
    });

    $('#generate-emi').on('click', function() {
        const tenureNo = parseInt($('#tenure_no').val());
        const emiAmount = parseFloat($('#emi_amount').val());
        const interestRate = parseFloat($('#interest_rate').val()) || 0;
        const tenureType = $('#tenure_type').val();
        const r = (interestRate / 100) / 12;
        const P = emiAmount;

        if (!emiAmount || !tenureNo || !$('#start_date').val()) {
            toastr.error("Please fill EMI Amount, Tenure No and Start Date.");
            return;
        }

        let gap = 1;
        if (tenureType === 'Quarterly') gap = 3;
        else if (tenureType === 'Half Yearly') gap = 6;
        else if (tenureType === 'Years') gap = 12;

        let fixedEmi = interestRate > 0 ?
            (P * r * Math.pow(1 + r, tenureNo)) / (Math.pow(1 + r, tenureNo) - 1) :
            P / tenureNo;

        let balance = P;
        let html = '';

        for (let i = 0; i < tenureNo; i++) {
            const dt = new Date($('#start_date').val());
            dt.setMonth(dt.getMonth() + (gap * (i + 1)));
            const interest = balance * r;
            const principal = fixedEmi - interest;
            balance -= principal;

            html += `
            <tr>
                <td>${i + 1}</td>
                <td><input type="text" name="emi_date[]" value="${dt.toISOString().split('T')[0]}" class="form-control form-control-sm flatdate"></td>
                <td>${interest.toFixed()}<input type="hidden" name="interest_amount[]" value="${interest.toFixed()}"></td>
                <td>${principal.toFixed()}<input type="hidden" name="principal_amount[]" value="${principal.toFixed()}"></td>
                <td><input type="number" step="any" name="emi_amount[]" value="${fixedEmi.toFixed(0)}" class="form-control form-control-sm emi-input"></td>
            </tr>`;
        }

        // Add total row WITHOUT emi-input class to prevent it from being counted
        html += `<tr class="total-row"><td><strong>Total</strong></td><td></td><td><strong>0.00</strong></td><td><strong>0.00</strong></td><td><strong>0.00</strong></td></tr>`;
        $('#emi-schedule-table tbody').html(html);
        $('#emi-schedule-section').show();
        $('.flatdate').flatpickr({ dateFormat: 'Y-m-d', allowInput: true });

        fixedTotalAmount = 0; // reset before recalc
        recalculateSchedule();
    });

    // Adjust last EMI when any EMI changes
    $(document).on('input', '.emi-input', function() {
        const inputs = $('.emi-input');
        let sum = 0;
        inputs.each((i, el) => { if (i < inputs.length - 1) sum += parseFloat(el.value) || 0; });

        const lastVal = (fixedTotalAmount - sum).toFixed();
        inputs.last().val(lastVal);
        recalculateSchedule();
    });

    // Handle custom EMI input - SAME AS CREATE PAGE
    $('#custom_emi_amount').on('input', function() {
        const custom = parseFloat(this.value) || 0;
        const inputs = $('.emi-input');
        const count = inputs.length;
        if (count === 0 || custom <= 0) return;

        let sum = 0;
        inputs.each((i, el) => {
            if (i < count - 1) {
                $(el).val(custom);
                sum += custom;
            }
        });

        const lastVal = (fixedTotalAmount - sum).toFixed();
        inputs.last().val(lastVal);
        recalculateSchedule();
    });
</script>
@endsection
