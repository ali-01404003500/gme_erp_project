
@extends('layout.app')
@section('title', 'EMI Collection')
@section('description', 'EMI Collection')

@section('content')
    <div class="container-fluid">
        <div class="social-dash-wrap">
            <!-- Breadcrumb -->
            <div class="row">
                <div class="col-lg-12">
                    <div class="breadcrumb-main">
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="#"><i class="las la-home"></i>Home</a></li>
                                <li class="breadcrumb-item active" aria-current="page">{{ trans('EMI Collection') }}</li>
                            </ol>
                        </nav>
                    </div>
                </div>
            </div>

            <!-- Header -->
            <div class="row">
                <div class="col-md-12 m-2">
                    <h4 class="text-capitalize breadcrumb-title">{{ trans('EMI Collection') }}</h4>
                    <x-error-alart />
                </div>

                <div class="card mb-50">
                    <div class="row justify-content-center">
                        <div class="col-sm-11">
                            <div class="mt-40 mb-50">
                                <h2 class="mb-3">EMI Collection</h2>

                                <!-- Filters -->
                                <div class="d-flex align-items-center mb-4">
                                    <div class="form-group">
                                        <select id="customer_id" class="form-control tom-select" style="width: 300px;">
                                            <option value="">-- Select Customer --</option>
                                            @foreach ($customers as $customer)
                                                <option value="{{ $customer->id }}">{{ $customer->company_name }} - {{ $customer->address}} ({{ $customer->phone }})</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="form-group mx-3">
                                        <select id="emi_id" class="form-control tom-select" style="width: 200px;">
                                            <option value="">-- Select EMI --</option>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <button id="btnGo" class="btn btn-sm btn-success">Search</button>
                                    </div>
                                </div>

                                <!-- Customer + EMI Info -->
                                <div class="card mb-4 d-none" id="infoCard">
                                    <div class="card-header">
                                        <h6 class="mb-0 text-primary">Customer Information</h6>
                                    </div>
                                    <div class="card-body" id="infoBody"></div>
                                </div>

                                <!-- EMI Schedule -->
                                <div class="card mb-4 d-none" id="emiDetailsCard">
                                    <div class="card-header d-flex justify-content-between align-items-center">
                                        <h5 class="mb-0">EMI Schedule</h5>
                                        <div class="btn-group">
                                            <button class="btn btn-danger btn-sm me-2" id="early-settlement-btn">Early Settlement</button>
                                            <button class="btn btn-primary btn-sm reschedule-btn" data-bs-toggle="modal" data-bs-target="#rescheduleEmiModal">Reschedule</button>
                                        </div>
                                    </div>
                                    <div class="card-body" id="emiDetailsTable"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- EMI Collection Modal -->
    <div class="modal fade" id="emiCollectionModal" tabindex="-1" aria-labelledby="emiCollectionModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="emiCollectionModalLabel"><i class="fas fa-money-bill-wave me-2"></i>EMI Collection</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="emiCollectionForm">
                        @csrf
                        <input type="hidden" id="modal-emi-detail-id" name="emi_detail_id">
                        <input type="hidden" id="modal-emi-amount" name="emi_amount" value="0">

                        <!-- Payment Section -->
                        <div class="payment-section table-responsive border p-3">
                            <h6 class="mb-3 text-primary"><i class="fas fa-credit-card me-2"></i>Payment Details</h6>
                            <div class="row mb-3">
                                <div class="col-md-3 mb-2">
                                    <label for="input-pay-mode" class="form-label">Pay Mode <span class="text-danger">*</span></label>
                                    <select id="input-pay-mode" class="form-select tom-select">
                                        <option value="">Select pay mode</option>
                                        <option value="Cash">Cash</option>
                                        <option value="Cheque">Cheque</option>
                                        <option value="Online Deposit">Online Deposit</option>
                                        <option value="bKash">bKash</option>
                                        <option value="Nagad">Nagad</option>
                                        <option value="Rocket">Rocket</option>
                                        <option value="Card Payment">Card Payment</option>
                                    </select>
                                </div>
                                <div class="col-md-3 mb-2 pay-field account-field d-none">
                                    <label for="input-account" class="form-label">Account</label>
                                    <select id="input-account" class="form-select tom-select">
                                        <option value="">Select Account</option>
                                    </select>
                                </div>
                                <div class="col-md-3 mb-2 pay-field bank-field d-none">
                                    <label for="input-bank" class="form-label">Bank</label>
                                    <select id="input-bank" class="form-select tom-select">
                                        <option value="">Select Bank</option>
                                        @php
                                            if(!isset($banks) || !$banks->count()) {
                                                $banks = \Modules\Account\Models\Bank::all();
                                            }
                                        @endphp
                                        @foreach ($banks as $bank)
                                            <option value="{{ $bank->id }}">{{ $bank->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-3 mb-2 pay-field branch-field d-none">
                                    <label for="input-branch" class="form-label">Branch</label>
                                    <select id="input-branch" class="form-select tom-select">
                                        <option value="">Select branch</option>
                                    </select>
                                </div>
                                <div class="col-md-3 mb-2 pay-field txn-field d-none">
                                    <label for="input-txn" class="form-label">Transaction ID</label>
                                    <input type="text" id="input-txn" class="form-control" placeholder="Transaction ID">
                                </div>
                                <div class="col-md-3 mb-2">
                                    <label for="input-date" class="form-label">Date <span class="text-danger">*</span></label>
                                    <input type="text" id="input-date" value="{{ date('Y-m-d') }}" class="form-control flatdate">
                                </div>
                                <div class="col-md-3 mb-2">
                                    <label for="input-amount" class="form-label">Amount <span class="text-danger">*</span></label>
                                    <input type="number" id="input-amount" class="form-control" placeholder="Amount" step="0.01" min="0">
                                </div>
                                <div class="col-md-3">
                                    <label for="input-file" class="form-label">File</label>
                                    <input type="file" id="input-file" class="form-control">
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-md-9 mb-2">
                                    <label for="input-remark" class="form-label">Remark <span class="text-danger">*</span></label>
                                    <textarea id="input-remark" class="form-control" rows="2" placeholder="Enter remark here"></textarea>
                                </div>
                                <div class="col-md-3 mb-2 d-flex align-items-end">
                                    <button type="button" id="add-payment" class="btn btn-success w-100"><i class="fa fa-plus"></i> Add Payment</button>
                                </div>
                            </div>

                            <!-- Payment Table -->
                            <table class="table table-bordered" id="payment-table">
                                <thead class="table-light">
                                    <tr>
                                        <th>Pay Mode</th>
                                        <th>Collection Point (Bank)</th>
                                        <th>Number (Branch)</th>
                                        <th>Transaction ID</th>
                                        <th>Date</th>
                                        <th>Amount</th>
                                        <th>File</th>
                                        <th>Remark</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody id="payment-body"></tbody>
                                <tfoot>
                                    <tr>
                                        <td colspan="5" class="text-end fw-bold">Total:</td>
                                        <td colspan="3" class="fw-bold text-primary" id="total-display">৳ <span>0.00</span>
                                            <input type="hidden" name="payments_total_amount" value="0.00">
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="5" class="text-end fw-bold">Payable:</td>
                                        <td colspan="3" class="fw-bold text-primary" id="total-payable">৳ <span>0.00</span>
                                            <input type="hidden" name="payments_payable_amount" value="0.00">
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="5" class="text-end fw-bold">Due:</td>
                                        <td colspan="3" class="fw-bold text-danger" id="total-due">৳ <span>0.00</span>
                                            <input type="hidden" name="payments_due_amount" value="0.00">
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="5" class="text-end fw-bold">Advance:</td>
                                        <td colspan="3" class="fw-bold text-success" id="total-advance">৳ <span>0.00</span>
                                            <input type="hidden" name="payments_advance_amount" value="0.00">
                                        </td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><i class="fas fa-times me-2"></i>Cancel</button>
                    <button type="button" class="btn btn-success" id="save-collection"><i class="fas fa-save me-2"></i>Save Collection</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Early Settlement Modal -->
    <div class="modal fade" id="earlySettlementModal" tabindex="-1" aria-labelledby="earlySettlementModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="earlySettlementModalLabel">EMI Early Settlement</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="earlySettlementForm">
                        @csrf
                        <input type="hidden" id="settlement-emi-id" name="emi_id">

                        <!-- Details Section -->
                        <div class="details-section mb-4 border p-3">
                            <h6 class="mb-3 text-danger">Early Settlement Details</h6>
                            <div class="row">
                                <!-- Left Side -->
                                <div class="col-md-8">
                                    <div class="row">
                                        <div class="col-md-3">
                                            <label>Tenure</label>
                                            <input type="text" id="tenure" class="form-control" readonly>
                                        </div>
                                        <div class="col-md-3">
                                            <label>Paid Tenure</label>
                                            <input type="text" id="paid-tenure" class="form-control" readonly>
                                        </div>
                                        <div class="col-md-3">
                                            <label>Due Tenure</label>
                                            <input type="text" id="due-tenure" class="form-control" readonly>
                                        </div>
                                        <div class="col-md-3">
                                            <label>Principle</label>
                                            <input type="text" id="principle" class="form-control" readonly>
                                        </div>
                                    </div>
                                    <div class="row mt-2">
                                        <div class="col-md-3">
                                            <label>Remaining Principle</label>
                                            <input type="text" id="remaining-principle" class="form-control" readonly>
                                        </div>
                                        <div class="col-md-3">
                                            <label>Remaining Interest</label>
                                            <input type="text" id="remaining-interest" class="form-control" readonly>
                                        </div>
                                        <div class="col-md-3">
                                            <label>Settlement Amount</label>
                                            <input type="text" id="settlement-amount" class="form-control" readonly>
                                        </div>
                                        <div class="col-md-3">
                                            <label>Interest</label>
                                            <input type="text" id="interest" class="form-control" readonly>
                                        </div>
                                    </div>
                                    <div class="row mt-2">
                                        <div class="col-md-3">
                                            <label>Interest Rate %</label>
                                            <input type="text" id="interest-rate" class="form-control" readonly>
                                        </div>
                                    </div>
                                </div>

                                <!-- Right Side -->
                                <div class="col-md-4">
                                    <div class="border p-3 rounded">
                                        <ul class="list-unstyled mb-0">
                                            <li class="d-flex justify-content-between border-bottom pb-1 mb-1">
                                                <span><strong>Principle</strong></span>
                                                <span id="display-principle"></span>
                                            </li>
                                            <li class="d-flex justify-content-between border-bottom pb-1 mb-1">
                                                <span><strong>Interest</strong></span>
                                                <span id="display-interest"></span>
                                            </li>
                                            <li class="d-flex justify-content-between border-bottom pb-1 mb-1">
                                                <span><strong>Total</strong></span>
                                                <span id="display-total"></span>
                                            </li>
                                            <li class="d-flex justify-content-between border-bottom pb-1 mb-1">
                                                <span><strong>Paid Principle</strong></span>
                                                <span id="display-paid-principle"></span>
                                            </li>
                                            <li class="d-flex justify-content-between border-bottom pb-1 mb-1">
                                                <span><strong>Paid Interest</strong></span>
                                                <span id="display-paid-interest"></span>
                                            </li>
                                            <li class="d-flex justify-content-between">
                                                <span><strong>Remaining Amount</strong></span>
                                                <span id="display-remaining-amount"></span>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Payment Section -->
                        <div class="payment-section table-responsive border p-3">
                            <h6 class="mb-3 text-primary"><i class="fas fa-credit-card me-2"></i>Payment Details</h6>
                            <div class="row mb-3">
                                <div class="col-md-3 mb-2">
                                    <label for="settlement-input-pay-mode" class="form-label">Pay Mode <span class="text-danger">*</span></label>
                                    <select id="settlement-input-pay-mode" class="form-select tom-select">
                                        <option value="">Select pay mode</option>
                                        <option value="Cash">Cash</option>
                                        <option value="Cheque">Cheque</option>
                                        <option value="Online Deposit">Online Deposit</option>
                                        <option value="bKash">bKash</option>
                                        <option value="Nagad">Nagad</option>
                                        <option value="Rocket">Rocket</option>
                                        <option value="Card Payment">Card Payment</option>
                                    </select>
                                </div>
                                <div class="col-md-3 mb-2 pay-field account-field d-none">
                                    <label for="settlement-input-account" class="form-label">Account</label>
                                    <select id="settlement-input-account" class="form-select tom-select">
                                        <option value="">Select Account</option>
                                    </select>
                                </div>
                                <div class="col-md-3 mb-2 pay-field bank-field d-none">
                                    <label for="settlement-input-bank" class="form-label">Bank</label>
                                    <select id="settlement-input-bank" class="form-select tom-select">
                                        <option value="">Select Bank</option>
                                        @foreach ($banks as $bank)
                                            <option value="{{ $bank->id }}">{{ $bank->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-3 mb-2 pay-field branch-field d-none">
                                    <label for="settlement-input-branch" class="form-label">Branch</label>
                                    <select id="settlement-input-branch" class="form-select tom-select">
                                        <option value="">Select branch</option>
                                    </select>
                                </div>
                                <div class="col-md-3 mb-2 pay-field txn-field d-none">
                                    <label for="settlement-input-txn" class="form-label">Transaction ID</label>
                                    <input type="text" id="settlement-input-txn" class="form-control" placeholder="Transaction ID">
                                </div>
                                <div class="col-md-3 mb-2">
                                    <label for="settlement-input-date" class="form-label">Date <span class="text-danger">*</span></label>
                                    <input type="text" id="settlement-input-date" value="{{ date('Y-m-d') }}" class="form-control flatdate">
                                </div>
                                <div class="col-md-3 mb-2">
                                    <label for="settlement-input-amount" class="form-label">Amount <span class="text-danger">*</span></label>
                                    <input type="number" id="settlement-input-amount" class="form-control" placeholder="Amount" step="0.01" min="0">
                                </div>
                                <div class="col-md-3">
                                    <label for="settlement-input-file" class="form-label">File</label>
                                    <input type="file" id="settlement-input-file" class="form-control">
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-md-9 mb-2">
                                    <label for="settlement-input-remark" class="form-label">Remark <span class="text-danger">*</span></label>
                                    <textarea id="settlement-input-remark" class="form-control" rows="2" placeholder="Enter remark here"></textarea>
                                </div>
                                <div class="col-md-3 mb-2 d-flex align-items-end">
                                    <button type="button" id="settlement-add-payment" class="btn btn-success w-100"><i class="fa fa-plus"></i> Add Payment</button>
                                </div>
                            </div>

                            <!-- Payment Table -->
                            <table class="table table-bordered" id="settlement-payment-table">
                                <thead class="table-light">
                                    <tr>
                                        <th>Pay Mode</th>
                                        <th>Collection Point (Bank)</th>
                                        <th>Number (Branch)</th>
                                        <th>Transaction ID</th>
                                        <th>Date</th>
                                        <th>Amount</th>
                                        <th>File</th>
                                        <th>Remark</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody id="settlement-payment-body"></tbody>
                                <tfoot>
                                    <tr>
                                        <td colspan="5" class="text-end fw-bold">Total:</td>
                                        <td colspan="3" class="fw-bold text-primary" id="settlement-total-display">৳ <span>0.00</span>
                                            <input type="hidden" name="settlement_payments_total_amount" value="0.00">
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="5" class="text-end fw-bold">Payable:</td>
                                        <td colspan="3" class="fw-bold text-primary" id="settlement-total-payable">৳ <span>0.00</span>
                                            <input type="hidden" name="settlement_payments_payable_amount" value="0.00">
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="5" class="text-end fw-bold">Due:</td>
                                        <td colspan="3" class="fw-bold text-danger" id="settlement-total-due">৳ <span>0.00</span>
                                            <input type="hidden" name="settlement_payments_due_amount" value="0.00">
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="5" class="text-end fw-bold">Advance:</td>
                                        <td colspan="3" class="fw-bold text-success" id="settlement-total-advance">৳ <span>0.00</span>
                                            <input type="hidden" name="settlement_payments_advance_amount" value="0.00">
                                        </td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" id="save-settlement">Save</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Image Preview Modal -->
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

    <!-- Reschedule EMI Modal -->
    <div class="modal fade" id="rescheduleEmiModal" tabindex="-1" aria-labelledby="rescheduleEmiModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="rescheduleEmiModalLabel">Reschedule EMI</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="rescheduleEmiForm">
                        @csrf
                        <input type="hidden" id="reschedule-emi-id" name="emi_id">

                        <!-- EMI Details Section -->
                        <div class="details-section mb-4 border p-3">
                            <h6 class="mb-3 text-danger">Reschedule EMI Details</h6>
                            <div class="row">
                                <!-- Left Side -->
                                <div class="col-md-8">
                                    <div class="row mb-3">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="remaining_principal" class="form-label">Remaining Principal</label>
                                                <input type="text" id="remaining_principal" class="form-control" readonly>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="remaining_interest" class="form-label">Remaining Interest</label>
                                                <input type="text" id="remaining_interest" class="form-control" readonly>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="reschedule_interest_rate" class="form-label">Interest Rate (%)</label>
                                                <input type="number" id="reschedule_interest_rate" name="interest_rate" class="form-control" step="0.01" min="0">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="schedule_date" class="form-label">Schedule Date</label>
                                                <input type="text" id="schedule_date" name="schedule_date" value="{{ date('Y-m-d') }}" class="form-control flatdate" required>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="tenure_type" class="form-label">Tenure Type</label>
                                                <select id="tenure_type" name="tenure_type" class="form-select tom-select" required>
                                                    <option value="Monthly">Monthly</option>
                                                    <option value="Weekly">Weekly</option>
                                                    <option value="Quarterly">Quarterly</option>
                                                    <option value="Half Yearly">Half Yearly</option>
                                                    <option value="Yearly">Yearly</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="tenure_no" class="form-label">Tenure No</label>
                                                <input type="number" id="tenure_no" name="tenure_no" class="form-control" min="1" required>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="settlement_amount" class="form-label">Settlement Amount</label>
                                                <input type="text" id="settlement_amount" class="form-control" readonly>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                           <!-- Generate Button -->
                                        <div class="row mb-3">
                                            <div class="col-md-12 text-center">
                                                <label for="generate-reschedule" class="form-label"></label>
                                                <button type="button" id="generate-reschedule" class="btn btn-primary">Generate</button>
                                            </div>
                                        </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- Right Side -->
                                <div class="col-md-4">
                                    <div class="border p-3 rounded">
                                        <ul class="list-unstyled mb-0">
                                            <li class="d-flex justify-content-between border-bottom pb-1 mb-1">
                                                <span><strong>Principle</strong></span>
                                                <span id="reschedule-display-principle"></span>
                                            </li>
                                            <li class="d-flex justify-content-between border-bottom pb-1 mb-1">
                                                <span><strong>Interest</strong></span>
                                                <span id="reschedule-display-interest"></span>
                                            </li>
                                            <li class="d-flex justify-content-between border-bottom pb-1 mb-1">
                                                <span><strong>Total</strong></span>
                                                <span id="reschedule-display-total"></span>
                                            </li>
                                            <li class="d-flex justify-content-between border-bottom pb-1 mb-1">
                                                <span><strong>Paid Principle</strong></span>
                                                <span id="reschedule-display-paid-principle"></span>
                                            </li>
                                            <li class="d-flex justify-content-between border-bottom pb-1 mb-1">
                                                <span><strong>Paid Interest</strong></span>
                                                <span id="reschedule-display-paid-interest"></span>
                                            </li>
                                            <li class="d-flex justify-content-between">
                                                <span><strong>Remaining Amount</strong></span>
                                                <span id="reschedule-display-remaining-amount"></span>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>

                       

                        <!-- New EMI Schedule Table -->
                        <div class="row mb-3 d-none" id="new-schedule-section">
                            <div class="col-md-12">
                                <h6 class="mb-2">New EMI Schedule</h6>
                                <div class="table-responsive">
                                    <table class="table table-bordered" id="new-schedule-table">
                                        <thead class="table-light">
                                            <tr>
                                                <th>Tenure No</th>
                                                <th>Repayment Date</th>
                                                <th>Interest Amount</th>
                                                <th>Principal Amount</th>
                                                <th>EMI Amount</th>
                                            </tr>
                                        </thead>
                                        <tbody></tbody>
                                        <tfoot>
                                            <tr>
                                                <td><strong>Total</strong></td>
                                                <td></td>
                                                <td class="bg-danger-subtle"><strong>0.00</strong></td>
                                                <td class="bg-danger-subtle"><strong>0.00</strong></td>
                                                <td class="bg-success-subtle"><strong>0.00</strong></td>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-success" id="save-reschedule">Save Reschedule</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('page_scripts')
<script>
    let currentEmiDetail = null;

    $(document).ready(function () {
        // Initialize TomSelect for select elements
        initializeTomSelect();

        // Load EMI list on customer select
        $('#customer_id').on('change', function () {
            const customerId = $(this).val();
            const emiSelect = $('#emi_id').prop('tomselect');
            emiSelect.clear();
            emiSelect.clearOptions();
            emiSelect.addOption({ value: '', text: '-- Select EMI --' });

            if (customerId) {
                $.ajax({
                    url: "{{ route('account.emi-collections.getCustomerEmis') }}",
                    type: "GET",
                    data: { customer_id: customerId },
                    success: function (res) {
                        res.emis.forEach(emi => {
                            emiSelect.addOption({ value: emi.id, text: emi.emi_number });
                        });
                    },
                    error: function () {
                        toastr.error('Failed to load EMI list.');
                    }
                });
            }
        });

        // Search button click
        $('#btnGo').on('click', function () {
            const emiId = $('#emi_id').val();
            if (emiId) {
                $.ajax({
                    url: "{{ route('account.emi-collections.getEmiDetails') }}",
                    type: "GET",
                    data: { emi_id: emiId },
                    success: function (res) {
                        $('#infoBody').html(res.combined_info_html);
                        $('#infoCard').removeClass('d-none');

                        const updatedTable = res.emi_schedule_html.replace(
                            /<button>Make Collection<\/button>/g,
                            `<button type="button" class="btn btn-success btn-sm make-collection-btn"
                                    data-bs-toggle="modal" 
                                    data-bs-target="#emiCollectionModal">Make Collection</button>`
                        );
                        $('#emiDetailsTable').html(updatedTable);
                        $('#emiDetailsCard').removeClass('d-none');
                    },
                    error: function () {
                        toastr.error('Failed to load EMI details.');
                    }
                });
            } else {
                toastr.error("Please select an EMI!");
            }
        });

        // Make Collection button click
        $(document).on('click', '.make-collection-btn', function () {
            const row = $(this).closest('tr');
            const emiAmount = parseFloat(row.find('td:eq(4)').text().replace(/[৳,]/g, '')) || 0;
            const emiDate = row.find('td:eq(1)').text();
            const serialNo = row.find('td:eq(0)').text();

            currentEmiDetail = {
                id: $(this).data('emi-detail-id'),
                amount: emiAmount,
                date: emiDate,
                serialNo: serialNo
            };

            $('#modal-emi-detail-id').val(currentEmiDetail.id);
            $('#modal-emi-amount').val(emiAmount);
            updatePayable(emiAmount);
            resetModalInputs('');
        });

        // Early Settlement button click
        $('#early-settlement-btn').on('click', function () {
            const emiId = $('#emi_id').val();
            if (emiId) {
                $.ajax({
                    url: "{{ route('account.emi-collections.getEarlySettlementDetails') }}",
                    type: "GET",
                    data: { emi_id: emiId },
                    success: function (res) {
                        // Parse numerical values
                        const principle = parseNumber(res.principle);
                        const remaining_principle = parseNumber(res.remaining_principle);
                        const remaining_interest = parseNumber(res.remaining_interest);
                        const settlement_amount = parseNumber(res.settlement_amount);
                        const interest = parseNumber(res.interest);
                        const total = parseNumber(res.total);
                        const paid_principle = parseNumber(res.paid_principle);
                        const paid_interest = parseNumber(res.paid_interest);
                        const remaining_amount = parseNumber(res.remaining_amount);

                        // Populate left side inputs
                        $('#tenure').val(res.tenure || 0);
                        $('#paid-tenure').val(res.paid_tenure || 0);
                        $('#due-tenure').val(res.due_tenure || 0);
                        $('#principle').val(numberFormat(principle));
                        $('#remaining-principle').val(numberFormat(remaining_principle));
                        $('#remaining-interest').val(numberFormat(remaining_interest));
                        $('#settlement-amount').val(numberFormat(settlement_amount));
                        $('#interest').val(numberFormat(interest));
                        $('#interest-rate').val(res.interest_rate || 0);

                        // Populate right side display
                        $('#display-principle').text(numberFormat(principle));
                        $('#display-interest').text(numberFormat(interest));
                        $('#display-total').text(numberFormat(total));
                        $('#display-paid-principle').text(numberFormat(paid_principle));
                        $('#display-paid-interest').text(numberFormat(paid_interest));
                        $('#display-remaining-amount').text(numberFormat(remaining_amount));

                        // Update payable amount
                        $('#settlement-total-payable span').text(numberFormat(settlement_amount));
                        $('input[name="settlement_payments_payable_amount"]').val(settlement_amount);

                        $('#settlement-emi-id').val(emiId);
                        $('#earlySettlementModal').modal('show');
                        resetModalInputs('settlement-');
                    },
                    error: function () {
                        toastr.error('Failed to load early settlement details.');
                    }
                });
            } else {
                toastr.error("Please select an EMI!");
            }
        });

        // Reschedule button click
        $(document).on('click', '.reschedule-btn', function () {
            const emiId = $('#emi_id').val();
            if (emiId) {
                $.ajax({
                    url: "{{ route('account.emi-collections.getRescheduleDetails') }}",
                    type: "GET",
                    data: { emi_id: emiId },
                    success: function (res) {
                        // Parse numerical values
                        const principle = parseNumber(res.principle);
                        const remaining_principle = parseNumber(res.remaining_principle);
                        const remaining_interest = parseNumber(res.remaining_interest);
                        const interest = parseNumber(res.interest);
                        const total = parseNumber(res.total);
                        const paid_principle = parseNumber(res.paid_principle);
                        const paid_interest = parseNumber(res.paid_interest);
                        const remaining_amount = parseNumber(res.remaining_amount);

                        // Populate form fields
                        $('#reschedule-emi-id').val(emiId);
                        $('#remaining_principal').val(numberFormat(remaining_principle));
                        $('#remaining_interest').val(numberFormat(remaining_interest));
                        $('#reschedule_interest_rate').val(res.interest_rate || 0);
                        $('#schedule_date').val(new Date().toISOString().split('T')[0]);
                        $('#tenure_type').val('Monthly');
                        $('#tenure_no').val(res.due_tenure || 1);
                        $('#custom_emi_amount').val(0);

                        // Calculate settlement amount
                        const settlementAmount = remaining_principle + remaining_interest;
                        $('#settlement_amount').val(numberFormat(settlementAmount));

                        // Populate display panel
                        $('#reschedule-display-principle').text(numberFormat(principle));
                        $('#reschedule-display-interest').text(numberFormat(interest));
                        $('#reschedule-display-total').text(numberFormat(total));
                        $('#reschedule-display-paid-principle').text(numberFormat(paid_principle));
                        $('#reschedule-display-paid-interest').text(numberFormat(paid_interest));
                        $('#reschedule-display-remaining-amount').text(numberFormat(remaining_amount));

                        // Show the modal
                        $('#rescheduleEmiModal').modal('show');
                    },
                    error: function () {
                        toastr.error('Failed to load reschedule details.');
                    }
                });
            } else {
                toastr.error("Please select an EMI!");
            }
        });

        // Initialize payment handlers
        initializePaymentHandlers('');
        initializePaymentHandlers('settlement-');

        // Initialize date pickers
        $('.flatdate').flatpickr({
            dateFormat: 'Y-m-d',
            allowInput: true
        });

        // Reset on modal close (hidden)
        $('#emiCollectionModal').on('hidden.bs.modal', function () {
            resetModalInputs('');
            $('#payment-body').empty();
            updateTotal('');
        });

        $('#earlySettlementModal').on('hidden.bs.modal', function () {
            resetModalInputs('settlement-');
            $('#settlement-payment-body').empty();
            updateTotal('settlement-');
        });

        $('#rescheduleEmiModal').on('hidden.bs.modal', function () {
            // Clear schedule table
            $('#new-schedule-table tbody').empty();
            $('#new-schedule-section').addClass('d-none');

            // Reset inputs
            $('#remaining_principal').val('');
            $('#remaining_interest').val('');
            $('#reschedule_interest_rate').val('');
            $('#schedule_date').val('');
            $('#tenure_type').val('Monthly');
            $('#tenure_no').val('');
            $('#settlement_amount').val('');
        });
    });

    // Initialize TomSelect for select elements
    function initializeTomSelect() {
        ['#customer_id', '#emi_id', '#input-pay-mode', '#input-account', '#input-bank', '#input-branch',
         '#settlement-input-pay-mode', '#settlement-input-account', '#settlement-input-bank', '#settlement-input-branch',
         '#tenure_type'].forEach(selector => {
            if ($(selector).length && !$(selector).prop('tomselect')) {
                new TomSelect(selector, {
                    create: false,
                    sortField: { field: 'text', direction: 'asc' }
                });
            }
        });
    }

    // Generalized payment handlers
    function initializePaymentHandlers(prefix) {
        const modalId = prefix === '' ? 'emiCollectionModal' : 'earlySettlementModal';
        const payModeId = `#${prefix}input-pay-mode`;
        const bankId = `#${prefix}input-bank`;
        const branchId = `#${prefix}input-branch`;
        const addBtnId = `#${prefix}add-payment`;
        const saveBtnId = prefix === '' ? '#save-collection' : '#save-settlement';
        const formId = prefix === '' ? '#emiCollectionForm' : '#earlySettlementForm';
        const removeClass = prefix === '' ? '.remove-row' : '.settlement-remove-row';

        // Modal show handler
        $(`#${modalId}`).on('show.bs.modal', function () {
            $(`#${prefix}input-date`).val(new Date().toISOString().split('T')[0]);
            resetModalInputs(prefix);
        });

        // Pay mode change
        $(payModeId).on('change', function () {
            const paymentMode = $(this).val();
            const accountSelect = $(`#${prefix}input-account`).prop('tomselect');
            accountSelect.clear();
            accountSelect.clearOptions();
            accountSelect.addOption({ value: '', text: 'Select Account' });

            if (paymentMode) {
                $.ajax({
                    url: '{{ route('account.account-setup.bank-accounts.get-accounts') }}',
                    type: 'GET',
                    data: { payment_mode: paymentMode },
                    success: function (response) {
                        if (response && response.length) {
                            response.forEach(account => {
                                accountSelect.addOption({ value: account.id, text: account.account_name });
                            });
                            if (response.length === 1) {
                                accountSelect.setValue(response[0].id);
                            }
                        }
                    },
                    error: function () {
                        toastr.error('Failed to load accounts.');
                    }
                });
            }
            toggleFormFields(prefix, paymentMode);
        });

        // Bank change
        $(bankId).on('change', function () {
            const bankVal = $(this).val();
            const branchSelect = $(branchId).prop('tomselect');
            branchSelect.clear();
            branchSelect.clearOptions();
            branchSelect.addOption({ value: '', text: 'Select branch' });

            if (bankVal) {
                $.ajax({
                    url: '{{ route('account.account-setup.ajax.bank-branches') }}',
                    method: 'GET',
                    data: { bank_id: bankVal },
                    success: function (data) {
                        data.forEach(branch => {
                            branchSelect.addOption({ value: branch.id, text: branch.name });
                        });
                    },
                    error: function () {
                        toastr.error('Failed to load branches.');
                    }
                });
            }
        });

        // Add payment
        $(addBtnId).on('click', function (e) {
            e.preventDefault();
            addPaymentRow(prefix);
        });

        // Remove row
        $(document).on('click', removeClass, function () {
            const row = $(this).closest('tr');
            if (row.find('.attachments').val()) {
                deleteFile(row.find('.attachments').val());
            }
            row.remove();
            updateTotal(prefix);
        });

        // Save
        $(saveBtnId).on('click', function () {
            if (validateForm(prefix)) {
                if (prefix === '') {
                    saveEmiCollection();
                } else {
                    saveEarlySettlement();
                }
            }
        });
    }

    // Toggle form fields based on payment mode
    function toggleFormFields(prefix, type) {
        const container = $(`#${prefix === '' ? 'emiCollection' : 'earlySettlement'}Modal`);
        container.find('.pay-field').addClass('d-none');
        const txnId = `#${prefix}input-txn`;
        const txnField = container.find('.txn-field');

        switch (type) {
            case 'Cash':
            case 'Online Deposit':
                container.find('.account-field').removeClass('d-none');
                break;
            case 'Cheque':
                container.find('.bank-field, .branch-field, .txn-field').removeClass('d-none');
                txnField.find('.form-label').text('Cheque No');
                $(txnId).attr('placeholder', 'Cheque Number');
                break;
            case 'bKash':
            case 'Nagad':
            case 'Rocket':
            case 'Card Payment':
                container.find('.account-field, .txn-field').removeClass('d-none');
                txnField.find('.form-label').text('Transaction ID');
                $(txnId).attr('placeholder', 'Transaction ID');
                break;
        }
    }

    // Add payment row
    function addPaymentRow(prefix) {
        const payMode = $(`#${prefix}input-pay-mode`).val();
        const bankName = $(`#${prefix}input-bank option:selected`).text() || '';
        const bankId = $(`#${prefix}input-bank`).val() || '';
        const accountName = $(`#${prefix}input-account option:selected`).text() || '';
        const accountId = $(`#${prefix}input-account`).val() || '';
        const branchName = $(`#${prefix}input-branch option:selected`).text() || '';
        const branchId = $(`#${prefix}input-branch`).val() || '';
        const txn = $(`#${prefix}input-txn`).val() || '';
        const date = $(`#${prefix}input-date`).val();
        const amount = parseFloat($(`#${prefix}input-amount`).val()) || 0;
        const fileInput = $(`#${prefix}input-file`)[0];
        const file = fileInput.files[0];
        const remark = $(`#${prefix}input-remark`).val();

        if (!payMode || !date || amount <= 0 || !remark) {
            toastr.error('Please fill all required fields correctly.');
            return;
        }

        const payableAmount = parseFloat($(`input[name="${prefix === '' ? 'payments' : 'settlement_payments'}_payable_amount"]`).val()) || 0;
        const currentTotal = parseFloat($(`input[name="${prefix === '' ? 'payments' : 'settlement_payments'}_total_amount"]`).val()) || 0;

        if (currentTotal + amount > payableAmount) {
            $(`#${prefix}input-amount`).val(payableAmount - currentTotal);
            toastr.error(`Payment exceeds payable. Max: ৳${(payableAmount - currentTotal).toFixed()}`);
            return;
        }

        const namePrefix = prefix === '' ? 'payments' : 'settlement_payments';
        const row = $(`
            <tr>
                <td>${payMode}<input type="hidden" name="${namePrefix}_pay_mode[]" value="${payMode}"></td>
                <td>${accountId ? accountName : bankName}<input type="hidden" name="${namePrefix}_bank_id[]" value="${accountId || bankId}"></td>
                <td>${branchName}<input type="hidden" name="${namePrefix}_branch_id[]" value="${branchId}"></td>
                <td>${txn}<input type="hidden" name="${namePrefix}_transaction_id[]" value="${txn}"></td>
                <td>${date}<input type="hidden" name="${namePrefix}_date[]" value="${date}"></td>
                <td class="amount-value">${numberFormat(amount)}<input type="hidden" name="${namePrefix}_amount[]" value="${amount}"></td>
                <td>
                    <span class="file_name"></span>
                    <div class="spinner-border spinner-border-sm" role="status"></div>
                    <input type="hidden" name="${namePrefix}_attachments[]" class="attachments">
                    <input type="hidden" name="${namePrefix}_verified[]" class="verified" value="0">
                </td>
                <td>${remark}<input type="hidden" name="${namePrefix}_remark[]" value="${remark}"></td>
                <td><button type="button" class="btn btn-danger btn-xs ${prefix === '' ? 'remove-row' : 'settlement-remove-row'}"><i class="fa fa-trash"></i></button></td>
            </tr>
        `);

        $(`#${prefix === '' ? 'payment' : 'settlement-payment'}-body`).append(row);

        if (file) {
            uploadFile(file).then(res => {
                row.find('.spinner-border').hide();
                if (res.path) {
                    row.find('.attachments').val(res.path);
                    row.find('.file_name').html(`<button type="button" onclick="showImage('${res.path}')" class="btn btn-outline-primary btn-sm"><i class="fa fa-eye"></i> preview</button>`);
                }
            }).catch(() => {
                row.find('.spinner-border').hide();
                toastr.error('File upload failed.');
            });
        } else {
            row.find('.spinner-border').hide();
        }

        updateTotal(prefix);
        resetModalInputs(prefix);
    }

    // Reset modal inputs
    function resetModalInputs(prefix) {
        const modal = $(`#${prefix === '' ? 'emiCollection' : 'earlySettlement'}Modal`);
        const payModeSelect = $(`#${prefix}input-pay-mode`).prop('tomselect');
        const accountSelect = $(`#${prefix}input-account`).prop('tomselect');
        const bankSelect = $(`#${prefix}input-bank`).prop('tomselect');
        const branchSelect = $(`#${prefix}input-branch`).prop('tomselect');

        // Clear and reset TomSelect instances
        if (payModeSelect) {
            payModeSelect.clear();
            payModeSelect.sync();
        }
        if (accountSelect) {
            accountSelect.clear();
            accountSelect.clearOptions();
            accountSelect.addOption({ value: '', text: 'Select Account' });
            accountSelect.sync();
        }
        if (bankSelect) {
            bankSelect.clear();
            bankSelect.sync();
        }
        if (branchSelect) {
            branchSelect.clear();
            branchSelect.clearOptions();
            branchSelect.addOption({ value: '', text: 'Select branch' });
            branchSelect.sync();
        }

        // Reset other inputs
        $(`#${prefix}input-txn`).val('');
        $(`#${prefix}input-date`).val(new Date().toISOString().split('T')[0]);
        $(`#${prefix}input-amount`).val('');
        $(`#${prefix}input-file`).val('');
        $(`#${prefix}input-remark`).val('');

        // Hide all pay fields
        modal.find('.pay-field').addClass('d-none');
    }

    // Update total
    function updateTotal(prefix) {
        let total = 0;
        $(`#${prefix === '' ? 'payment' : 'settlement-payment'}-body .amount-value`).each(function () {
            total += parseFloat($(this).text().replace(/,/g, '')) || 0;
        });
        $(`#${prefix === '' ? '' : 'settlement-'}total-display span`).text(numberFormat(total));
        $(`input[name="${prefix === '' ? 'payments' : 'settlement_payments'}_total_amount"]`).val(total);
        updateDue(prefix);
    }

    // Update due/advance
    function updateDue(prefix) {
        const namePrefix = prefix === '' ? 'payments' : 'settlement_payments';
        const payable = parseFloat($(`input[name="${namePrefix}_payable_amount"]`).val()) || 0;
        const total = parseFloat($(`input[name="${namePrefix}_total_amount"]`).val()) || 0;
        const difference = total - payable;

        const dueId = `#${prefix === '' ? '' : 'settlement-'}total-due span`;
        const advanceId = `#${prefix === '' ? '' : 'settlement-'}total-advance span`;
        const dueInput = `input[name="${namePrefix}_due_amount"]`;
        const advanceInput = `input[name="${namePrefix}_advance_amount"]`;

        if (difference > 0) {
            $(dueId).text('0.00');
            $(dueInput).val(0);
            $(advanceId).text(numberFormat(difference));
            $(advanceInput).val(difference);
        } else {
            $(dueId).text(numberFormat(Math.abs(difference)));
            $(dueInput).val(Math.abs(difference));
            $(advanceId).text('0.00');
            $(advanceInput).val(0);
        }
    }

    // Validate form
    function validateForm(prefix) {
        const rows = $(`#${prefix === '' ? 'payment' : 'settlement-payment'}-body tr`).length;
        if (rows === 0) {
            toastr.error('Add at least one payment.');
            return false;
        }
        return true;
    }

    // Update payable (EMI only)
    function updatePayable(payable) {
        $('#total-payable span').text(numberFormat(payable));
        $('input[name="payments_payable_amount"]').val(payable);
        updateDue('');
    }

    // Save EMI Collection
    function saveEmiCollection() {
        const total = parseFloat($('input[name="payments_total_amount"]').val()) || 0;
        const payable = parseFloat($('input[name="payments_payable_amount"]').val()) || 0;
        if (total !== payable) {
            toastr.error('Total payment amount must equal the payable amount to save.');
            return;
        }

        const formData = new FormData();
        
        // Add CSRF token
        formData.append('_token', $('input[name="_token"]').val());
        
        // Add EMI detail ID and amount
        formData.append('emi_detail_id', $('#modal-emi-detail-id').val());
        formData.append('emi_amount', $('#modal-emi-amount').val());
        
        // Add payment totals
        formData.append('payments_total_amount', $('input[name="payments_total_amount"]').val());
        formData.append('payments_payable_amount', $('input[name="payments_payable_amount"]').val());
        formData.append('payments_due_amount', $('input[name="payments_due_amount"]').val());
        formData.append('payments_advance_amount', $('input[name="payments_advance_amount"]').val());
        
        // Add individual payment records
        $('#payment-body tr').each(function (index, row) {
            const $row = $(row);
            formData.append(`payments[${index}][pay_mode]`, $row.find('input[name="payments_pay_mode[]"]').val());
            formData.append(`payments[${index}][bank_id]`, $row.find('input[name="payments_bank_id[]"]').val());
            formData.append(`payments[${index}][branch_id]`, $row.find('input[name="payments_branch_id[]"]').val());
            formData.append(`payments[${index}][transaction_id]`, $row.find('input[name="payments_transaction_id[]"]').val());
            formData.append(`payments[${index}][date]`, $row.find('input[name="payments_date[]"]').val());
            formData.append(`payments[${index}][amount]`, $row.find('input[name="payments_amount[]"]').val());
            formData.append(`payments[${index}][attachments]`, $row.find('input[name="payments_attachments[]"]').val());
            formData.append(`payments[${index}][remark]`, $row.find('input[name="payments_remark[]"]').val());
        });
        
        // Debug: Log form data contents
        console.log('EMI Collection FormData contents:');
        for (let [key, value] of formData.entries()) {
            console.log(key, value);
        }
        
        $.ajax({
            
            url: "{{route('account.emi-collections.collection-store')}}", // Replace with your actual route
            type: "POST",
            data: formData,
            processData: false,
            contentType: false,
            success: function (response) {
                if (response.success) {
                    toastr.success(response.message || 'EMI collection saved successfully!');
                    $('#emiCollectionModal').modal('hide');
                    $('#btnGo').click(); // Refresh the EMI schedule
                } else {
                    toastr.error(response.message || 'Error saving EMI collection.');
                }
            },
            error: function (xhr) {
                console.error('EMI Collection Error:', xhr.responseText);
                if (xhr.status === 422) {
                    // Validation errors
                    const errors = xhr.responseJSON?.errors;
                    if (errors) {
                        Object.keys(errors).forEach(key => {
                            toastr.error(errors[key][0]);
                        });
                    } else {
                        toastr.error('Validation failed. Please check your inputs.');
                    }
                } else {
                    toastr.error('Error saving EMI collection. Please try again.');
                }
            }
        });
    }

    // Save Early Settlement
    function saveEarlySettlement() {
        const settlementTotal = parseFloat($('input[name="settlement_payments_total_amount"]').val()) || 0;
        const settlementPayable = parseFloat($('input[name="settlement_payments_payable_amount"]').val()) || 0;
        if (settlementTotal !== settlementPayable) {
            toastr.error('Total payment amount must equal the payable amount to save.');
            return;
        }

        const formData = new FormData();
        
        // Add CSRF token
        formData.append('_token', $('input[name="_token"]').val());
        
        // Add EMI ID
        formData.append('emi_id', $('#settlement-emi-id').val());
        
        // Add settlement details
        formData.append('tenure', $('#tenure').val());
        formData.append('paid_tenure', $('#paid-tenure').val());
        formData.append('due_tenure', $('#due-tenure').val());
        formData.append('principle', $('#principle').val().replace(/,/g, ''));
        formData.append('remaining_principle', $('#remaining-principle').val().replace(/,/g, ''));
        formData.append('remaining_interest', $('#remaining-interest').val().replace(/,/g, ''));
        formData.append('settlement_amount', $('#settlement-amount').val().replace(/,/g, ''));
        formData.append('interest', $('#interest').val().replace(/,/g, ''));
        formData.append('interest_rate', $('#interest-rate').val());
        formData.append('total', $('#display-total').text().replace(/,/g, ''));
        formData.append('paid_principle', $('#display-paid-principle').text().replace(/,/g, ''));
        formData.append('paid_interest', $('#display-paid-interest').text().replace(/,/g, ''));
        formData.append('remaining_amount', $('#display-remaining-amount').text().replace(/,/g, ''));
        
        // Add payment totals
        formData.append('settlement_payments_total_amount', $('input[name="settlement_payments_total_amount"]').val());
        formData.append('settlement_payments_payable_amount', $('input[name="settlement_payments_payable_amount"]').val());
        formData.append('settlement_payments_due_amount', $('input[name="settlement_payments_due_amount"]').val());
        formData.append('settlement_payments_advance_amount', $('input[name="settlement_payments_advance_amount"]').val());
        
        // Add individual payment records
        $('#settlement-payment-body tr').each(function (index, row) {
            const $row = $(row);
            formData.append(`payments[${index}][pay_mode]`, $row.find('input[name="settlement_payments_pay_mode[]"]').val());
            formData.append(`payments[${index}][bank_id]`, $row.find('input[name="settlement_payments_bank_id[]"]').val());
            formData.append(`payments[${index}][branch_id]`, $row.find('input[name="settlement_payments_branch_id[]"]').val());
            formData.append(`payments[${index}][transaction_id]`, $row.find('input[name="settlement_payments_transaction_id[]"]').val());
            formData.append(`payments[${index}][date]`, $row.find('input[name="settlement_payments_date[]"]').val());
            formData.append(`payments[${index}][amount]`, $row.find('input[name="settlement_payments_amount[]"]').val());
            formData.append(`payments[${index}][attachments]`, $row.find('input[name="settlement_payments_attachments[]"]').val());
            formData.append(`payments[${index}][remark]`, $row.find('input[name="settlement_payments_remark[]"]').val());
        });
        
        // Debug: Log form data contents
        console.log('Early Settlement FormData contents:');
        for (let [key, value] of formData.entries()) {
            console.log(key, value);
        }
        
        $.ajax({
            url: "{{route('account.emi-collections.settlement-collection-store')}}", // Replace with your actual route
            type: "POST",
            data: formData,
            processData: false,
            contentType: false,
            success: function (response) {
                if (response.success) {
                    toastr.success(response.message || 'Early settlement saved successfully!');
                    $('#earlySettlementModal').modal('hide');
                    $('#btnGo').click(); // Refresh the EMI schedule
                } else {
                    toastr.error(response.message || 'Error saving early settlement.');
                }
            },
            error: function (xhr) {
                console.error('Early Settlement Error:', xhr.responseText);
                if (xhr.status === 422) {
                    // Validation errors
                    const errors = xhr.responseJSON?.errors;
                    if (errors) {
                        Object.keys(errors).forEach(key => {
                            toastr.error(errors[key][0]);
                        });
                    } else {
                        toastr.error('Validation failed. Please check your inputs.');
                    }
                } else {
                    toastr.error('Error saving early settlement. Please try again.');
                }
            }
        });
    }

    // Show image in preview modal
    function showImage(url) {
        $('#full-screen-image').attr('src', url);
        $('#full-screen-modal').modal('show');
    }

    // Parse number from string, removing commas
    function parseNumber(str) {
        return parseFloat((str || '0').toString().replace(/,/g, '')) || 0;
    }

    // Number formatting
    function numberFormat(number, decimals) {
        number = parseFloat(number);
        if (isNaN(number)) {
            number = 0;
        }
        return number.toFixed(decimals).replace(/\B(?=(\d{3})+(?!\d))/g, ",");
    }

    // Placeholder for file upload
    async function uploadFile(file) {
        const formData = new FormData();
        formData.append('_token', '{{ csrf_token() }}');
        formData.append('file', file);
        const response = await fetch("{{route('upload_file')}}", {
            method: 'POST',
            body: formData
        });
        if (response.ok) {
            toastr.success("File uploaded successfully");
        }
        return await response.json();
    }

    // Async function to delete file
    async function deleteFile(url) {
        const response = await fetch(url, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        });
        const data = await response.json();
        if (data.message) {
            toastr.success(data.message);
        }
        return data;
    }

    // Recalculate reschedule EMI schedule
    function recalculateReschedule() {
        const r = (parseFloat($('#reschedule_interest_rate').val()) || 0) / 100 / 12;
        let balance = parseFloat($('#settlement_amount').val().replace(/,/g, '')) || 0;
        let totalInterest = 0,
            totalPrincipal = 0,
            totalEmi = 0;

        $('#new-schedule-table tbody tr').each(function () {
            const $row = $(this);
            const $emiInput = $row.find('.emi-input');
            if (!$emiInput.length) return;

            const emi = parseFloat($emiInput.val()) || 0;
            if (emi <= 0) {
                $row.find('td:eq(2)').html(`0.00 <input type="hidden" name="interest_amount[]" value="0.00">`);
                $row.find('td:eq(3)').html(`0.00 <input type="hidden" name="principal_amount[]" value="0.00">`);
                return;
            }

            let interest = balance * r;
            let principal = emi - interest;
            if (interest < 0) interest = 0;

            balance -= emi;

            totalEmi += emi;
            totalInterest += interest;
            totalPrincipal += principal;

            $row.find('td:eq(2)').html(`${numberFormat(interest)} <input type="hidden" name="interest_amount[]" value="${interest.toFixed()}">`);
            $row.find('td:eq(3)').html(`${numberFormat(principal)} <input type="hidden" name="principal_amount[]" value="${principal.toFixed()}">`);
        });

        const $tot = $('#new-schedule-table tfoot tr');
        $tot.find('td:eq(2)').html(`<strong>${numberFormat(totalInterest)}</strong>`);
        $tot.find('td:eq(3)').html(`<strong>${numberFormat(totalPrincipal)}</strong>`);
        $tot.find('td:eq(4)').html(`<strong>${numberFormat(totalEmi)}</strong>`);
    }

    // Generate new reschedule EMI schedule
    $('#generate-reschedule').on('click', function () {
        const tenureNo = parseInt($('#tenure_no').val());
        const interestRate = parseFloat($('#reschedule_interest_rate').val()) || 0;
        const tenureType = $('#tenure_type').val();
        const scheduleDate = $('#schedule_date').val();
        const settlementAmount = parseFloat($('#settlement_amount').val().replace(/,/g, '')) || 0;

        if (!tenureNo || !scheduleDate || settlementAmount <= 0) {
            toastr.error("Please fill all required fields.");
            return;
        }

        // Calculate gap based on tenure type
        let gap;
        switch (tenureType) {
            case 'Weekly':
                gap = 7; // 7 days
                break;
            case 'Monthly':
                gap = 1; // 1 month
                break;
            case 'Quarterly':
                gap = 3; // 3 months
                break;
            case 'Half Yearly':
                gap = 6; // 6 months
                break;
            case 'Yearly':
                gap = 12; // 12 months
                break;
            default:
                gap = 1;
        }

        // Calculate EMI using the formula for annuity
        const r = (interestRate / 100) / (tenureType === 'Weekly' ? 52 : 12);
        const n = tenureNo;
        const P = settlementAmount;

        let fixedEmi = interestRate > 0 ?
            (P * r * Math.pow(1 + r, n)) / (Math.pow(1 + r, n) - 1) :
            P / n;

        let balance = P;
        let html = '';

        for (let i = 0; i < tenureNo; i++) {
            const dt = new Date(scheduleDate);

            // Calculate payment date based on tenure type
            if (tenureType === 'Weekly') {
                dt.setDate(dt.getDate() + (gap * (i + 1)));
            } else {
                dt.setMonth(dt.getMonth() + (gap * (i + 1)));
            }

            const interest = balance * r;
            const principal = fixedEmi - interest;
            balance -= fixedEmi;

            html += `
                <tr>
                    <td>${i + 1}</td>
                    <td>
                        <input type="text" name="emi_date[]" value="${dt.toISOString().split('T')[0]}" class="form-control form-control-sm flatdate">
                    </td>
                    <td class="bg-danger-subtle">${numberFormat(interest)}<input type="hidden" name="interest_amount[]" value="${interest.toFixed()}"></td>
                    <td class="bg-danger-subtle">${numberFormat(principal)}<input type="hidden" name="principal_amount[]" value="${principal.toFixed()}"></td>
                    <td class="bg-success-subtle">
                        <input type="number" step="any" name="emi_amount[]" value="${fixedEmi.toFixed(0)}.00" class="form-control form-control-sm emi-input">
                    </td>
                </tr>
            `;
        }

        $('#new-schedule-table tbody').html(html);
        $('#new-schedule-section').removeClass('d-none');

        // Initialize date pickers for the new date inputs
        $('#new-schedule-table .flatdate').flatpickr({
            dateFormat: 'Y-m-d',
            allowInput: true
        });

        recalculateReschedule();
        window.initialEmiValues = Array.from($('.emi-input')).map(el => parseFloat(el.value) || 0);
    });

    // Handle custom EMI amount change
    $('#custom_emi_amount').on('change', function () {
        const custom = parseFloat(this.value) || 0;
        const settlementAmount = parseFloat($('#settlement_amount').val().replace(/,/g, '')) || 0;
        const tenureNo = parseInt($('#tenure_no').val());

        const totalIfAll = custom * tenureNo;

        if (totalIfAll > settlementAmount) {
            toastr.error(`Custom EMI × Tenure No (${totalIfAll}) exceeds total settlement amount (${settlementAmount}).`);
            if (Array.isArray(window.initialEmiValues)) {
                $('.emi-input').each((i, el) => {
                    $(el).val(window.initialEmiValues[i]);
                });
                recalculateReschedule();
            }
            return;
        }

        if (custom <= 0) {
            toastr.error('Invalid EMI Amount!');
            return;
        }

        const inputs = $('.emi-input');
        let sum = 0;
        inputs.each((i, el) => {
            if (i < inputs.length - 1) {
                $(el).val(custom);
                sum += custom;
            }
        });

        const lastVal = Math.max(0, settlementAmount - sum);
        inputs.last().val(lastVal);

        recalculateReschedule();
    });

    // Handle EMI input change
    $(document).on('change', '.emi-input', function () {
        const settlementAmount = parseFloat($('#settlement_amount').val().replace(/,/g, '')) || 0;
        const inputs = $('.emi-input');
        let sum = 0;

        inputs.each((i, el) => {
            if (i < inputs.length - 1) sum += parseFloat(el.value) || 0;
        });

        const lastVal = Math.max(0, settlementAmount - sum);
        inputs.last().val(lastVal);
        recalculateReschedule();
    });

    // Save reschedule
    $('#save-reschedule').on('click', function () {
        const scheduleTotalEmi = parseFloat($('#new-schedule-table tfoot tr td:eq(4) strong').text().replace(/,/g, '')) || 0;
        const rescheduleSettlement = parseFloat($('#settlement_amount').val().replace(/,/g, '')) || 0;
        // if (Math.abs(scheduleTotalEmi - rescheduleSettlement) > 0.01) {  // Allow small diff due to rounding
        //     toastr.error('Total EMI amount must equal the settlement amount.');
        //     return;
        // }

        const formData = new FormData();
        
        // Add CSRF token
        formData.append('_token', $('input[name="_token"]').val());
        
        // Add EMI ID
        formData.append('emi_id', $('#reschedule-emi-id').val());
        
        // Add reschedule details
        formData.append('remaining_principal', $('#remaining_principal').val().replace(/,/g, ''));
        formData.append('remaining_interest', $('#remaining_interest').val().replace(/,/g, ''));
        formData.append('interest_rate', $('#reschedule_interest_rate').val());
        formData.append('schedule_date', $('#schedule_date').val());
        formData.append('tenure_type', $('#tenure_type').val());
        formData.append('tenure_no', $('#tenure_no').val());
        formData.append('settlement_amount', $('#settlement_amount').val().replace(/,/g, ''));
        formData.append('custom_emi_amount', $('#custom_emi_amount').val());
        
        // Add schedule data
        $('#new-schedule-table tbody tr').each(function (index, row) {
            const $row = $(row);
            formData.append(`schedule[${index}][tenure_no]`, index + 1);
            formData.append(`schedule[${index}][repayment_date]`, $row.find('input[name="emi_date[]"]').val());
            formData.append(`schedule[${index}][interest_amount]`, $row.find('input[name="interest_amount[]"]').val());
            formData.append(`schedule[${index}][principal_amount]`, $row.find('input[name="principal_amount[]"]').val());
            formData.append(`schedule[${index}][emi_amount]`, $row.find('input[name="emi_amount[]"]').val());
        });
        
        // Debug: Log form data contents
        console.log('Reschedule FormData contents:');
        for (let [key, value] of formData.entries()) {
            console.log(key, value);
        }
        
        $.ajax({
            url: "{{route('account.emi-collections.reschedule-store')}}", // Replace with your actual route
            type: "POST",
            data: formData,
            processData: false,
            contentType: false,
            success: function (response) {
    if (response.success) {
        toastr.success(response.message || 'EMI rescheduled successfully!');
        $('#rescheduleEmiModal').modal('hide');
        
        // Reload EMI list by triggering customer change
        const customerId = $('#customer_id').val();
        const emiSelect = $('#emi_id').prop('tomselect');
        emiSelect.clear();
        emiSelect.clearOptions();
        emiSelect.addOption({ value: '', text: '-- Select EMI --' });
        
        if (customerId) {
            $.ajax({
                url: "{{ route('account.emi-collections.getCustomerEmis') }}",
                type: "GET",
                data: { customer_id: customerId },
                success: function (res) {
                    res.emis.forEach(emi => {
                        emiSelect.addOption({ value: emi.id, text: emi.emi_number });
                    });
                    
                    // Select the new rescheduled EMI and refresh details
                    const newEmiId = response.data.new_emi.id;
                    emiSelect.setValue(newEmiId);
                    $('#btnGo').click();
                },
                error: function () {
                    toastr.error('Failed to reload EMI list.');
                }
            });
        }
    } else {
        toastr.error(response.message || 'Error rescheduling EMI.');
    }
},
            error: function (xhr) {
                console.error('Reschedule Error:', xhr.responseText);
                if (xhr.status === 422) {
                    // Validation errors
                    const errors = xhr.responseJSON?.errors;
                    if (errors) {
                        Object.keys(errors).forEach(key => {
                            toastr.error(errors[key][0]);
                        });
                    } else {
                        toastr.error('Validation failed. Please check your inputs.');
                    }
                } else {
                    toastr.error('Error rescheduling EMI. Please try again.');
                }
            }
        });
    });
    

    // Recalculate settlement amount when values change
    $('#remaining_principal, #remaining_interest, #reschedule_interest_rate, #tenure_no, #tenure_type').on('change', function () {
        const principal = parseNumber($('#remaining_principal').val());
        const interest = parseNumber($('#remaining_interest').val());
        const settlementAmount = principal + interest;
        $('#settlement_amount').val(numberFormat(settlementAmount));
    });

</script>
<script>

// Rollback button click handler
$(document).on('click', '.delete-emi-detail-btn', function () {
    const emiDetailId = $(this).data('emi-detail-id');
    const row = $(this).closest('tr');

    if (confirm('Are you sure you want to rollback this EMI collection? This action cannot be undone.')) {
        $.ajax({
            url: "{{ route('account.emi-collections.rollback') }}",
            type: "POST",
            data: {
                _token: '{{ csrf_token() }}',
                emi_detail_id: emiDetailId
            },
            success: function (response) {
                if (response.success) {
                    toastr.success(response.message || 'Rollback successful.');
                    $('#btnGo').click(); // Refresh the EMI schedule table
                } else {
                    toastr.error(response.message || 'Error during rollback.');
                }
            },
            error: function (xhr) {
                console.error('Rollback Error:', xhr.responseText);
                if (xhr.status === 422) {
                    const errors = xhr.responseJSON?.errors;
                    if (errors) {
                        Object.keys(errors).forEach(key => {
                            toastr.error(errors[key][0]);
                        });
                    } else {
                        toastr.error('Validation failed. Please check your inputs.');
                    }
                } else {
                    toastr.error('Error during rollback. Please try again.');
                }
            }
        });
    }
});
</script>

@endsection