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
                                    <li class="breadcrumb-item active" aria-current="page">
                                        {{ trans('EMI Entry') }}</li>
                                </ol>
                            </nav>
                        </div>
                        <div class="action-btn mt-sm-0 mt-15">
                            @if (hasPermission('account.emi-entries.index'))
                                <a href="{{ route('account.emi-entries.index') }}"
                                    class="btn btn-warning btn-default btn-squared radius-md shadow2 btn-sm"><i
                                        class="fa fa-list"></i> List</a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12 m-2">
                    <h4 class="text-capitalize breadcrumb-title">{{ trans('EMI Entry') }}</h4>
                    <x-error-alart />
                </div>
                <div class="card mb-50">
                    <div class="row justify-content-center" id="justify-content-center">
                        <div class="col-sm-11">
                            <div class="mt-40 mb-50">
                                <h2 class="mb-3">EMI Entry</h2>
                                <form action="{{ route('account.emi-entries.store', app()->getLocale()) }}" method="POST"
                                    enctype="multipart/form-data">
                                    @csrf

                                    <div class="row">
                                        <!-- Customer Selection -->
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="customer_id">{{ __('Customer') }}<span
                                                        class="text-danger">*</span>:</label>
                                                <select name="customer_id" id="customer_id"
                                                    class="form-control tom-select required"
                                                    onchange="onChangeCustomer(this)" required>
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

                                        <!-- Phone & Address -->
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="contact_person_phone">Customer Phone No<span
                                                        class="text-danger">*</span>:</label>
                                                <input type="text" name="contact_person_phone" id="contact_person_phone"
                                                    class="form-control" readonly>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="address">Company Address<span
                                                        class="text-danger">*</span>:</label>
                                                <input type="text" name="address" id="address" class="form-control"
                                                    readonly>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Invoice + EMI Input -->
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="sales_order_id">{{ __('Invoice ID') }}<span
                                                        class="text-danger">*</span>:</label>
                                                <select name="sales_order_id" id="sales_order_id"
                                                    class="form-control tom-select required">
                                                    <option value="">{{ __('Select Invoice ID') }}</option>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="emi_amount">{{ __('Total EMI Amount') }}<span
                                                        class="text-danger">*</span>:</label>
                                                <input type="text" name="amount" id="emi_amount"
                                                    value="{{ old('amount', 0) }}" class="form-control">
                                            </div>
                                        </div>

                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="tenure_type">{{ __('Tenure Type') }}<span
                                                        class="text-danger">*</span>:</label>
                                                <select name="tenure_type" id="tenure_type" class="form-control tom-select">
                                                    <option value="Months">Months</option>
                                                    <option value="Quarterly">Quarterly</option>
                                                    <option value="Half Yearly">Half Yearly</option>
                                                    <option value="Years">Years</option>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="tenure_no">{{ __('Tenure No') }}<span
                                                        class="text-danger">*</span>:</label>
                                                <input type="text" name="tenure_no" id="tenure_no" class="form-control"
                                                    value="{{ old('tenure_no', 1) }}">
                                            </div>
                                        </div>

                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="interest_rate">{{ __('Interest Rate') }}:</label>
                                                <input type="text" name="interest_rate" id="interest_rate"
                                                    class="form-control" value="{{ old('interest_rate', 0) }}">
                                            </div>
                                        </div>

                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="start_date">{{ __('Scheduled Date') }}<span
                                                        class="text-danger">*</span>:</label>
                                                <input type="text" name="start_date" id="start_date"
                                                    class="form-control flatdate"
                                                    value="{{ old('start_date', date('Y-m-d')) }}">
                                            </div>
                                        </div>

                                        <div class="col-md-1 d-flex align-items-end">
                                            <button type="button" class="btn btn-xs btn-success" id="generate-emi">
                                                <i class="fa fa-cog"></i> Generate
                                            </button>
                                        </div>
                                    </div>

                                    <!-- EMI Table -->
                                    <div class="mt-4" id="emi-schedule-section" style="display:none;">
                                        <div class="col-md-3 ms-auto">
                                            <label for="custom_emi_amount" class="text-danger fw-bold">Custom EMI
                                                Installment Amount</label>
                                            <input type="number" class="form-control" id="custom_emi_amount"
                                                value="0">
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
                                            <tbody></tbody>
                                        </table>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <label for="description" class="text-danger fw-bold">Description:</label>
                                            <textarea name="description" id="description" cols="30" rows="5" class="form-control"></textarea>
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

        $('#emi-schedule-table tbody tr').each(function() {
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

        const $tot = $('#emi-schedule-table tbody tr:last-child');
        $tot.find('td:eq(2)').html(`<strong>${totalInterest.toFixed()}</strong>`);
        $tot.find('td:eq(3)').html(`<strong>${totalPrincipal.toFixed()}</strong>`);
        $tot.find('td:eq(4)').html(`<strong>${totalEmi.toFixed()}</strong>`);

        // Update fixed total amount if not already set
        if (fixedTotalAmount === 0) fixedTotalAmount = totalEmi.toFixed();
    }

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
        const scheduledDate = $('#start_date').val();

        for (let i = 0; i < tenureNo; i++) {
            const paymentDate = addMonthsSafely(scheduledDate, i + 1);
            const interest = balance * r;
            const principal = fixedEmi - interest;
            balance -= principal;
            
            html += `
            <tr>
                <td>${i + 1}</td>
                <td><input type="text" name="emi_date[]"  value="${paymentDate}" class="form-control form-control-sm flatdate"></td>
                <td>${interest.toFixed()}<input type="hidden" name="interest_amount[]" value="${interest.toFixed()}"></td>
                <td>${principal.toFixed()}<input type="hidden" name="principal_amount[]" value="${principal.toFixed()}"></td>
                <td><input type="number" step="any" name="emi_amount[]" value="${fixedEmi.toFixed(0)}" class="form-control form-control-sm emi-input"></td>
            </tr>`;
        }

        html += `<tr><td><strong>Total</strong></td><td></td><td><strong>0.00</strong></td><td><strong>0.00</strong></td><td><strong>0.00</strong></td></tr>`;
        $('#emi-schedule-table tbody').html(html);
        $('#emi-schedule-section').show();
        $('.flatdate').flatpickr({ dateFormat: 'Y-m-d', allowInput: true });

        fixedTotalAmount = 0; // reset before recalc
        recalculateSchedule();
    });


    function addMonthsSafely(dateString, monthsToAdd) {
        const [year, month, day] = dateString.split('-').map(Number);

        const targetMonth = month - 1 + monthsToAdd;
        const targetYear = year + Math.floor(targetMonth / 12);
        const normalizedMonth = ((targetMonth % 12) + 12) % 12;

        // Target month-এর last date
        const lastDay = new Date(
            targetYear,
            normalizedMonth + 1,
            0
        ).getDate();

        // Original day অথবা target month-এর last day
        const targetDay = Math.min(day, lastDay);

        return `${targetYear}-${String(normalizedMonth + 1).padStart(2, '0')}-${String(targetDay).padStart(2, '0')}`;
    }

    // Adjust last EMI when any EMI changes
    $(document).on('input', '.emi-input', function() {
        const inputs = $('.emi-input');
        let sum = 0;
        inputs.each((i, el) => { if (i < inputs.length - 1) sum += parseFloat(el.value) || 0; });

        const lastVal = (fixedTotalAmount - sum).toFixed();
        inputs.last().val(lastVal);
        recalculateSchedule();
    });

    // Handle custom EMI input
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

