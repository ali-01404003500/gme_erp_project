{{-- resources/views/Account/payments/petty-cash-payments/index.blade.php --}}
@section('title', "Petty Cash List for Payment")
@section('description', "Petty Cash List for Payment")
@extends('layout.app')

@section('content')
<div class="container-fluid">
    <div class="social-dash-wrap">
        <div class="row">
            <div class="col-lg-12">
                <div class="breadcrumb-main">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="#"><i class="las la-home"></i> Home</a></li>
                            <li class="breadcrumb-item active">Petty Cash List for Payment</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <h4 class="breadcrumb-title">Petty Cash List for Payment</h4>
            </div>

            <!-- Search Form -->
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <form method="GET">
                            <table class="table table-bordered">
                                <tr>
                                    <td width="30%">
                                        <select name="employee_id" class="tom-select" data-placeholder="Select Employee">
                                            <option value=""></option>
                                            @foreach($employees as $emp)
                                                <option value="{{ $emp->id }}" {{ request('employee_id') == $emp->id ? 'selected' : '' }}>
                                                    {{ $emp->full_name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td width="40%">
                                        <div class="input-daterange input-group">
                                            <input type="text" class="form-control flatdate" name="from" value="{{ request('from') }}" placeholder="From">
                                            <span class="input-group-text"><i class="fa fa-exchange-alt"></i></span>
                                            <input type="text" class="form-control flatdate" name="to" value="{{ request('to') }}" placeholder="To">
                                        </div>
                                    </td>
                                    <td width="30%" class="text-end">
                                        <button type="submit" class="btn btn-xs btn-primary"><i class="fa fa-search"></i> Search</button>
                                        <a href="{{ request()->url() }}" class="btn btn-xs btn-warning"><i class="fa fa-refresh"></i> Refresh</a>
                                    </td>
                                </tr>
                            </table>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Petty Cash List -->
            <div class="col-md-12">
                <div class="card mb-4">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>SL</th>
                                        <th>Sender Info</th>
                                        <th>Particulars</th>
                                        <th>Entry Info</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($pettyCashList as $key => $bill)
                                        <tr>
                                            <td class="text-center">{{ ($pettyCashList->currentPage() - 1) * $pettyCashList->perPage() + $loop->iteration  }}</td>
                                            <td>
                                                <ul class="list-unstyled mb-0 fs-13">
                                                    <li><strong>Request From:</strong> {{ $bill->employee->full_name }}</li>
                                                    <li><strong>Request Type:</strong>
                                                        @if($bill->transportExpenses->count() && $bill->generalExpenses->count()) Transport & General Expense
                                                        @elseif($bill->transportExpenses->count()) Transport Expense
                                                        @else General Expense @endif (PCB)
                                                    </li>
                                                    <li><strong>Request Date:</strong> {{ date('d-M-y', strtotime($bill->date_of_bill_claim)) }}</li>
                                                </ul>
                                            </td>
                                            <td>
                                                @php
                                                    $approved = $bill->transportExpenses->sum('final_approved_amount') +
                                                                $bill->generalExpenses->sum('final_approved_amount');
                                                @endphp
                                                <ul class="list-unstyled mb-0 fs-13">
                                                    <li><strong>Requested Amount:</strong> <span class="text-danger">{{ number_format($bill->total_requested_amount) }}</span></li>
                                                    <li><strong>Approved Amount:</strong> <span class="text-success">{{ number_format($approved) }}</span></li>
                                                    @if($bill->finalApprovedBy)
                                                        <li><strong>Approved By:</strong> {{ $bill->finalApprovedBy->name }}</li>
                                                        <li><strong>Approve Date:</strong> {{ $bill->final_approved_date ? date('d-M-y', strtotime($bill->final_approved_date)) : 'N/A' }}</li>
                                                    @endif
                                                </ul>
                                            </td>
                                            <td>
                                                <ul class="list-unstyled mb-0 fs-13">
                                                    <li><strong>Entry By:</strong> {{ $bill->createdBy->name ?? $bill->employee->full_name }}</li>
                                                    <li><strong>Entry Date :</strong> {{ date('d-M-y', strtotime($bill->created_at)) }}</li>
                                                </ul>
                                            </td>
                                            <td class="text-center">
                                                @if(hasPermission('account.payments.petty-cash-payments.create'))
                                                <button type="button"
                                                        class="btn btn-sm btn-outline-info btn-view-details"
                                                        data-id="{{ $bill->id }}"
                                                        data-bs-toggle="modal"
                                                        data-bs-target="#paymentDetailsModal">
                                                    <i class="fas fa-eye"></i> Details
                                                </button>
                                                @endif
                                            </td>
                                        </tr>
                                    @empty
                                        <tr><td colspan="5" class="text-center text-muted">No approved petty cash found for payment</td></tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                        <div class="mt-3">{{ $pettyCashList->links() }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="paymentDetailsModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Petty Cash Payment Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="paymentDetailsBody">
                <div class="text-center py-5">
                    <div class="spinner-border text-primary"></div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .fs-13 { font-size: 13px; line-height: 1.7; }
    .border-danger { border: 2px solid #dc3545 !important; }
</style>
@endsection

@section('page_scripts')
<script>
    // Your standard reusable TomSelect initializer
    function initializeTomSelect() {
        ['.global-account-head', '.individual-account-head', 'select.tom-select'].forEach(selector => {
            $(selector).each(function () {
                if (this && !this.tomselect) {
                    new TomSelect(this, {
                        create: false,
                        sortField: { field: 'text', direction: 'asc' },
                        plugins: ['remove_button'],
                        persist: false
                    });
                }
            });
        });
    }

    $(document).ready(function () {
        initializeTomSelect(); // for search dropdowns

        function initPaymentModal() {
            // 1. Re-initialize TomSelect
            initializeTomSelect();

            // 2. Apply to All
            $(document).off('change', '.global-account-head');
            $(document).on('change', '.global-account-head', function () {
                const value = this.tomselect?.getValue();
                if (value) {
                    $('.individual-account-head').each(function () {
                        if (this.tomselect) {
                            this.tomselect.setValue(value, false); // silent set
                        }
                    });
                }
            });

            // 3. FORM SUBMIT – THE ONLY CORRECT & BULLETPROOF WAY
            $(document).off('submit', '#paymentForm');
            $(document).on('submit', '#paymentForm', function (e) {
                e.preventDefault();
                e.stopImmediatePropagation();

                let allValid = true;
                let firstInvalid = null;

                // $('.individual-account-head').each(function () {
                //     // Wait until TomSelect is fully created
                //     const ts = this.tomselect || TomSelect.getInstance?.(this);
                //     const value = ts ? ts.getValue() : $(this).val();

                //     const $control = $(this).closest('.tom-select').find('.');

                //     if (!value || value === '') {
                //         allValid = false;
                //         $control.addClass('border-danger');
                //         if (!firstInvalid) firstInvalid = this;
                //     } else {
                //         $control.removeClass('border-danger');
                //     }
                // });

                if (!allValid) {
                    alert('Please select account head for all expenses');
                    firstInvalid && firstInvalid.scrollIntoView({ behavior: 'smooth', block: 'center' });
                    return false;
                }

                // Everything is valid → submit for real
                if (confirm('Confirm payment? This action cannot be undone.')) {
                    // Remove handler to prevent recursion
                    $(this).off('submit');
                    this.submit();
                }
            });
        }

        // Load modal
        $(document).on('click', '.btn-view-details', function () {
            const id = $(this).data('id');
            const url = `{{ route('account.payments.petty-cash-payments.details', ':id') }}`.replace(':id', id);

            $('#paymentDetailsBody').html('<div class="text-center py-5"><div class="spinner-border text-primary"></div></div>');

            $.get(url)
                .done(res => {
                    if (res.success) {
                        $('#paymentDetailsBody').html(res.html);
                        initPaymentModal(); // <-- This is the key
                    }
                })
                .fail(() => {
                    $('#paymentDetailsBody').html('<div class="alert alert-danger">Failed to load details.</div>');
                });
        });
    });
</script>
@endSection