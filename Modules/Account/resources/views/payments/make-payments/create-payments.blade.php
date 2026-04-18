@extends('layout.app')
@section('title', "Make Payments")
@section('description', "Make Payments")
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
                                    <li class="breadcrumb-item active" aria-current="page">{{ trans('Make Payments') }}</li>
                                </ol>
                            </nav>
                        </div>
                        <div class="breadcrumb-main__wrapper">
                            <div class="action-btn mt-sm-0 mt-15 d-flex align-items-center">
                                @if (hasPermission('account.payments.make-payments.index'))
                                    <a href="{{ route('account.payments.make-payments.index') }}"
                                        class="btn px-20 btn-primary btn-sm">
                                        <i class="las la-list fs-16"></i>List
                                    </a>
                                @endif
                                {{-- <a href="{{ request()->fullUrlWithQuery(['export' => 'pdf']) }}" target="_blank"
                                    class="btn btn-danger btn-sm mr-5" style="margin-left: 5px;">
                                    <i class="las la-file-pdf fs-16"></i> PDF
                                </a> --}}
                            </div>

                        </div>
                        <div class="col-md-12 mt-4">
                            <h4 class="text-capitalize breadcrumb-title">{{ trans('Make Payments') }}</h4>
                        </div>
                        <div class="col-md-12 mt-4">
                            <x-error-alart />
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">

                <div class="col-md-12 mb-4">
                    <div class="card">
                        <div class="card-body">
                            <form action="{{ route('account.payments.make-payments.store') }}" method="post">
                                @csrf
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="payment_type">{{ trans('Payment Type') }} *</label>
                                            <select name="payment_to_type" id="payment_type" class="form-control tom-select"
                                                required>
                                                <option value="">--{{ trans('Select Payment Type') }}--</option>
                                                <option value="customer" {{ old('payment_to_type') == 'customer' ? 'selected' : '' }}>{{ trans('Customer Payment') }}</option>
                                                <option value="supplier" {{ old('payment_to_type') == 'supplier' ? 'selected' : '' }}>{{ trans('Supplier Payment') }}</option>
                                                <option value="vendor" {{ old('payment_to_type') == 'vendor' ? 'selected' : '' }}>{{ trans('Vendor Payment') }}</option>
                                                <option value="broker" {{ old('payment_to_type') == 'broker' ? 'selected' : '' }}>{{ trans('Broker Payment') }}</option>
                                                <option value="petty_cash_expense" {{ old('payment_to_type') == 'petty_cash_expense' ? 'selected' : '' }}>
                                                    {{ trans('Petty Cash Expense') }}</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="payment_to">{{ trans('Payment To') }} *</label>
                                            <select name="payment_to_id" id="payment_to" class="form-control tom-select"
                                                required>
                                                <option value="">--{{ trans('Select Payment To') }}--</option>

                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-sm-4 my-1 text-end ">
                                        <span>Balance : </span>
                                        <span id="balance"></span>
                                    </div>

                                    <div class="col-md-12 my-2">
                                        <h4>Payment Information</h4>
                                    </div>
                                    <div class="col-md-12">

                                        @include('Account::payments.make-payments.payments-details', ['payments' => []])
                                    </div>


                                    <div class="col-md-12 mt-4">
                                        <input type="hidden" name="status" id="status">
                                        <div class="form-group gap-2 d-flex justify-content-end">
                                            <button type="submit" class="btn btn-primary" id="save"
                                                data-bs-dismiss="modal">{{ trans('Save') }}</button>

                                        </div>
                                    </div>

                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection


@section('page_scripts')
    <script>
        $(document).ready(function () {
            // create payments


            $('#save').click(function () {
                $("#status").val("pending");
            });

            $('#save-verify').click(function () {
                $("#status").val("verified");
            });

            $('#save-approved').click(function () {
                $("#status").val("approved");
            });

        });
    </script>
    <script>
        $(document).ready(function () {

            // create payments

            // Function to load payment options
            function loadPaymentOptions(payment_type, selected_value = null) {
                if (payment_type) {
                    //load payments to data
                    // console.log("payment_type",payment_type);
                    $.ajax({
                        url: '{{ route('account.payments.make-payments.accounts') }}',
                        type: 'GET',
                        data: { type: payment_type },
                        success: function (response) {
                            var tomSelect = $('#payment_to').prop('tomselect');

                            if (tomSelect) {
                                // Prepare options array
                                var options = [{
                                    value: '',
                                    text: '--{{ trans('Select Payment To') }}--'
                                }];

                                $.each(response.accounts, function (key, item) {
                                    options.push({
                                        value: item.id,
                                        text: item.name
                                    });
                                });

                                // Clear and reload options
                                tomSelect.clear();
                                tomSelect.clearOptions();
                                tomSelect.addOptions(options);
                                tomSelect.refreshOptions();

                                // Set selected value if provided
                                if (selected_value) {
                                    tomSelect.setValue(selected_value);
                                }
                            }
                        }
                    });
                }
            }

            $('#payment_type').change(function () {
                var payment_type = $(this).val();
                loadPaymentOptions(payment_type);
            });

            // on select payment to load ballaces
            $('#payment_to').change(function () {
                var payment_to = $(this).val();
                var paymentType = $('#payment_type').val();
                if (payment_to) {
                    $.ajax({
                        url: '{{ route('account.payments.make-payments.get-ballance') }}',
                        type: 'GET',
                        data: { type: $('#payment_type').val(), account_id: payment_to },
                        success: function (response) {

                            let currentDate = new Date().toISOString().slice(0, 10);

                            if (paymentType == "customer") {
                                const balanceLink = `{{ route('account.report.customer-ledger') }}?account_id=${response.account?.id}&from=2021-10-05&to=${currentDate}`;
                                $('#balance').html('<a href="' + balanceLink + '" target="_blank">' + response.account?.balance + '</a>')
                            }
                            else if (paymentType == "vendor") {
                                const balanceLink = `{{ route('account.report.vendor-ledger') }}?account_id=${response.account?.id}&from=2021-10-05&to=${currentDate}`;
                                $('#balance').html('<a href="' + balanceLink + '" target="_blank">' + response.account?.balance + '</a>')
                            }
                            else if (paymentType == "supplier") {
                                const balanceLink = `{{ route('account.report.supplier-ledger') }}?account_id=${response.account?.id}&from=2021-10-05&to=${currentDate}`;
                                $('#balance').html('<a href="' + balanceLink + '" target="_blank">' + response.account?.balance + '</a>')
                            }
                            else {
                                $('#balance').text(response.account?.balance || 0);
                            }

                            updatePayable(response.account?.balance);
                        }
                    });
                } else {
                    $('#balance').text(0);
                }
            });

            // Handle old data preservation for dynamic dropdown
            var oldPaymentType = '{{ old('payment_to_type') }}';
            var oldPaymentTo = '{{ old('payment_to_id') }}';

            if (oldPaymentType) {
                // Set the payment type and load corresponding options
                $('#payment_type').val(oldPaymentType);

                // Load options for the old payment type and set the selected value
                loadPaymentOptions(oldPaymentType, oldPaymentTo);

                // Load balance if payment_to is selected
                if (oldPaymentTo) {
                    $.ajax({
                        url: '{{ route('account.payments.make-payments.get-ballance') }}',
                        type: 'GET',
                        data: { type: oldPaymentType, id: oldPaymentTo },
                        success: function (response) {
                            $('#balance').text(response.account?.balance || 0);
                            updatePayable(response.account?.balance || 0);
                        }
                    });
                }
            }

        });
    </script>
    @stack('script')
@endsection