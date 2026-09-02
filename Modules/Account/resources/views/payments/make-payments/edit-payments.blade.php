@extends('layout.app')
@section('title', "Edit Payments")
@section('description', "Edit Payments")
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
                                <li class="breadcrumb-item active" aria-current="page">{{ trans('Edit Payments') }}</li>
                            </ol>
                        </nav>
                    </div>
                    <div class="breadcrumb-main__wrapper">
                        <div class="action-btn mt-sm-0 mt-15 d-flex align-items-center">
                            {{-- @if (hasPermission('account.payments.create'))
                                <a href="{{ route('account.payments.create') }}" class="btn px-20 btn-primary btn-sm">
                                    <i class="las la-plus fs-16"></i>Add New
                                </a>
                            @endif
                            <a href="{{ request()->fullUrlWithQuery(['export' => 'pdf']) }}" target="_blank"
                                class="btn btn-danger btn-sm mr-5" style="margin-left: 5px;">
                                <i class="las la-file-pdf fs-16"></i> PDF
                            </a> --}}
                        </div>
                    </div>
                    <div class="col-md-12 mt-4">
                        <h4 class="text-capitalize breadcrumb-title">{{ trans('Edit Payments') }}</h4>
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
                        <form action="{{ route('account.payments.make-payments.update', $makePayment->id) }}" method="post">
                            @csrf
                            @method('PUT')
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="payment_type">{{ trans('Payment Type') }} *</label>
                                      
                                        @php
                                            // $payment_type = match ($makePayment->payment_to_type) {
                                            //     "Modules\Purchase\Models\Supplier" => 'supplier',
                                            //     "Modules\Purchase\Models\Vendor" => 'vendor',
                                            //     "Modules\CRM\Models\Customer\Broker" => 'broker',
                                            //     "Modules\Account\Models\Account" => 'petty_cash_expense',
                                            //     default  => 'supplier',
                                            // }
                                           
                                            $payment_type = match ($makePayment->payment_to_type) {

                                                "Modules\Purchase\Models\Supplier", "Modules\Account\Models\Supplier"  => 'supplier',
                                                "Modules\Purchase\Models\Vendor" => 'vendor',
                                                "Modules\CRM\Models\Customer\Customer;" => 'customer',
                                                "Modules\CRM\Models\Customer\Broker"   => 'broker', 
                                                "Modules\Account\Models\Account" => match (trim($makePayment->paymentTo?->accountControl?->name ?? '')) 
                                                    {
                                                        'Petty Cash Expense' => 'petty_cash_expense',
                                                        'Withdrawal' => 'withdrawal', 
                                                        'Owner Equity' => 'withdrawal', 
                                                        'Non-Current Assets' => 'non_current_assets',
                                                        'Loan Payment' => 'loan_payment',
                                                        default => $makePayment->paymentTo?->accountControl?->name ?? $makePayment->paymentTo?->name  ?? 'Account',
                                                    }, 
                                
                                                default => 'Unknown'
                                            }; 
                                        @endphp
                                        <select name="payment_to_type" id="payment_type" class="form-control tom-select" required>
                                            <option value="">--{{ trans('Select Payment Type') }}--</option>
                                            <option value="customer" {{ (old('payment_to_type', $payment_type  ?? '') == 'customer') ? 'selected' : '' }}>{{ trans('Customer Payment') }}</option>
                                            <option value="supplier" {{ (old('payment_to_type', $payment_type  ?? '') == 'supplier') ? 'selected' : '' }}>{{ trans('Supplier Payment') }}</option>
                                            <option value="vendor" {{ (old('payment_to_type', $payment_type ?? '') == 'vendor') ? 'selected' : '' }}>{{ trans('Vendor Payment') }}</option>
                                            <option value="broker" {{ (old('payment_to_type', $payment_type ?? '') == 'broker') ? 'selected' : '' }}>{{ trans('Broker') }}</option>
                                            <option value="petty_cash_expense" {{ (old('payment_to_type', $payment_type ?? '') == 'petty_cash_expense') ? 'selected' : '' }}>{{ trans('Petty Cash Expense') }}</option>
                                            <option value="withdrawal" {{ (old('payment_to_type', $payment_type ?? '') == 'withdrawal') ? 'selected' : '' }}>{{ trans('Withdrawal [Equity]') }}</option> 
                                            <option value="non_current_assets" {{ (old('payment_to_type', $payment_type ?? '') == 'non_current_assets') ? 'selected' : '' }}>{{ trans('Equipment [Non-Current Assets]') }}</option>
                                            <option value="loan_payment" {{ (old('payment_to_type', $payment_type ?? '') == 'loan_payment') ? 'selected' : '' }}>{{ trans('Loan Payment [Liabilites]') }}</option>
 
                                        </select>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="payment_to">{{ trans('Payment To') }} *</label>
                                        <select name="payment_to_id" id="payment_to" class="form-control tom-select" required>
                                            <option value="">--{{ trans('Select Payment To') }}--</option>
                                            {{-- @if(isset($makePayment) && $makePayment->payment_to_type)
                                                @foreach($accounts[$makePayment->payment_to_type] ?? [] as $account)
                                                    <option value="{{ $account->id }}" {{ (old('payment_to_id', $makePayment->payment_to_id ?? '') == $account->id) ? 'selected' : '' }}>
                                                        {{ $account->name }}
                                                    </option>
                                                @endforeach
                                            @endif --}}
                                        </select>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group text-end">
                                        <label for="balance">{{ trans('Total Balance') }}</label>
                                        <span id="balance" class="form-control form-control-plaintext">
                                            {{ $makePayment->account->balance ?? '0' }}
                                        </span>
                                    </div>
                                </div>

                                <div class="col-md-12 my-2">
                                    <h4>Make Payment Information:</h4>
                                </div>
                                <div class="col-md-12">
                                    {{-- @dd($makePayment->paymentDetails ); --}}
                                    @include('Account::payments.make-payments.payments-details', [
                                        'payments' => $makePayment->paymentDetails ?? []
                                    ])
                                </div>

                                <div class="col-md-12 mt-4"> 
                                    <input type="hidden" name="status" id="status" value="{{ $makePayment->status }}">
                                    <div class="form-group gap-2 d-flex justify-content-end">
                                        @if($makePayment->status == 'pending'  && request('form_type') == '' && hasPermission('account.payments.make-payments.update'))
                                            <button type="submit" class="btn btn-primary" id="save">{{ trans('Update') }}</button> 

                                        @elseif($makePayment->status == 'pending' && request('form_type') == 'verify' && hasPermission('account.payments.make-payments.update'))
                                                <button type="submit" class="btn btn-warning" id="save-verify">{{ trans('Verify') }}</button>
                                                <button type="submit" class="btn btn-danger" id="save-denied">{{ trans('Deny') }}</button>

                                        @elseif($makePayment->status == 'verified' && request('form_type') == 'approve' && hasPermission('account.payments.make-payments.verify'))
                                                <button type="submit" class="btn btn-success" id="save-approved">{{ trans('Approve') }}</button>
                                                <button type="submit" class="btn btn-danger" id="save-denied">{{ trans('Deny') }}</button> 
                                        @endif
                                        
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
            $('#save').click(function () {
                $("#status").val("pending");
            });

            $('#save-verify').click(function () {
                $("#status").val("verified");
            });

            $('#save-approved').click(function () {
                $("#status").val("approved");
            });

            $('#save-denied').click(function () {
                $("#status").val("denied");
            });

            

            // Load payment options
            function loadPaymentOptions(payment_type, selected_value = null) {
                if(payment_type){
                    $.ajax({
                        url: '{{ route('account.payments.make-payments.accounts') }}',
                        type: 'GET',
                        data: { type: payment_type },
                        success: function (response) {
                            var tomSelect = $('#payment_to').prop('tomselect');
                            if (tomSelect) {
                                // Store current focus state
                                var wasFocused = document.activeElement === tomSelect.control_input;
                                
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
                                
                                tomSelect.clear();
                                tomSelect.clearOptions();
                                tomSelect.addOptions(options);
                                // tomSelect.refreshOptions();
                                
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

            $('#payment_to').change(function () {
                var payment_to = $(this).val();
                if(payment_to){
                    $.ajax({
                        url: '{{ route('account.payments.make-payments.get-ballance') }}',
                        type: 'GET',
                        data: { type: $('#payment_type').val(), id: payment_to },
                        success: function (response) {
                            $('#balance').text(response.account?.balance);
                            updatePayable(response.account?.balance);
                        }
                    });
                }else{
                    $('#balance').text(0);
                }
            });

            $('#payment_to').trigger('change');

            // Preserve old data
            var oldPaymentType = '{{ old('payment_to_type',  $payment_type ?? '') }}';
            var oldPaymentTo = '{{ old('payment_to_id', $makePayment->payment_to_id ?? '') }}';

            if (oldPaymentType) {
                $('#payment_type').val(oldPaymentType);
                loadPaymentOptions(oldPaymentType, oldPaymentTo);

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