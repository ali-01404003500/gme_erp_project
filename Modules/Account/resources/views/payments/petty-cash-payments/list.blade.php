{{-- resources/views/Account/payments/petty-cash-payments/index.blade.php --}}
@section('title', "Payment TA/DA List")
@section('description', "Payment TA/DA List")
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
                            <li class="breadcrumb-item active">Payment TA/DA List</li>
                        </ol>
                    </nav>
                    <div class="breadcrumb-main__wrapper">
                        <div class="action-btn mt-sm-0 mt-15 d-flex align-items-center">
                            @if (hasPermission('account.payments.petty-cash-payments.index'))
                            <a href="{{ route('account.payments.petty-cash-payments.index') }}" class="btn px-20 btn-primary btn-sm">
                                <i class="las la-plus fs-16"></i>Payment TA/DA
                            </a>
                            @endif 
                        </div>

                    </div>
                </div>
                
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <h4 class="breadcrumb-title">Payment TA/DA List</h4>
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
                                    <td width="10%" class="text-end">
                                        <button type="submit" class="btn btn-xs btn-primary"><i class="fa fa-search"></i> Search</button> 
                                    </td>
                                    <td width="15%" class="text-end"> 
                                        <a href="{{ request()->url() }}" class="btn btn-xs btn-warning"><i class="fa fa-refresh"></i> Refresh</a>
                                    </td>
                                </tr>
                            </table>
                        </form>
                    </div>
                </div>
            </div>

            <!-- TA/DA List -->
            <div class="col-md-12">
                <div class="card mb-4">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>SL</th>
                                        <th>Employee Name</th> 
                                        <th>Payment Amount</th> 
                                        <th>Payment By</th>
                                        <th>Payment Date</th>
                                        <th>Payment Status</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $tFinalAppAmt =  $i = 0;
                                    @endphp
                             
                                    @foreach($pettyCashList as $employeeId => $bills) 
                                        
                                        @php 
                                            $bill = $bills->first(); 
                                            $billIds = $bills->pluck('id')->toArray();

                                            $tFinalAppAmt += $bills->pluck('transportExpenses')->flatten()->sum('final_approved_amount') + $bills->pluck('generalExpenses')->flatten()->sum('final_approved_amount');
                                        @endphp
                                        <tr>
                                            <td class="text-center">{{ ++$i  }}</td>
                                            <td>
                                                {{ $bill->employee->full_name }} 
                                            </td> 
                                            <td> 
                                                {{ $bills->pluck('transportExpenses')->flatten()->sum('final_approved_amount') + $bills->pluck('generalExpenses')->flatten()->sum('final_approved_amount') }} 
                                            </td> 
                                             
                                            <td>
                                                {{ $bill->paymentBy->name ?? $bill->employee->full_name }}
                                            </td>
                                            <td>
                                                {{ $bill->payment_date  }}
                                            </td>
                                            <td>
                                                {{ $bill->status  }}
                                            </td>
                                            <td class="text-center">
                                                @if(hasPermission('account.payments.petty-cash-payments.showDetails'))
                                                <button type="button"
                                                        class="btn btn-sm btn-outline-info btn-view-details"
                                                        data-id='@json($billIds)'
                                                        data-bs-toggle="modal"
                                                        data-bs-target="#paymentDetailsModal">
                                                    <i class="fas fa-eye"></i> Details
                                                </button>
                                                @endif
                                            </td>
                                        </tr> 
                                    @endforeach  
                                </tbody>
                                <tfooter>
                                    <tr>
                                        <td colspan="2" class="text-end" >
                                            Total
                                        </td> 
                                        <td>
                                            {{ $tFinalAppAmt }}
                                        </td>
                                    </tr> 
                                <tfooter>
                            </table>
                        </div>
                        
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="paymentDetailsModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-custom">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">TA/DA Payment List</h5>
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
    .modal-custom {
        max-width: 70%;  /* width percentage, 70% of screen */
        width: 90%;
    }
</style>
@endsection

@section('page_scripts')
<script>
    // Your standard reusable TomSelect initializer
    function initializeTomSelect() {
        ['.global-transport-account-head', '.individual-transport-account-head', 'select.transport-tom-select'].forEach(selector => {
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

        ['.global-general-account-head', '.individual-general-account-head', 'select.general-tom-select'].forEach(selector => {
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

            // 2. Apply to transport All
            $(document).off('change', '.global-transport-account-head');
            $(document).on('change', '.global-transport-account-head', function () {
                const value = this.tomselect?.getValue();
                if (value) {
                    $('.individual-transport-account-head').each(function () {
                        if (this.tomselect) {
                            this.tomselect.setValue(value, false); // silent set
                        }
                    });
                }
            });

            // 2. Apply to general All
            $(document).off('change', '.global-general-account-head');
            $(document).on('change', '.global-general-account-head', function () {
                const value = this.tomselect?.getValue();
                if (value) {
                    $('.individual-general-account-head').each(function () {
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
            let billIds = $(this).data('id');
            billIds = JSON.stringify(billIds); 
            const url = `{{ route('account.payments.petty-cash-payments.show-details') }}?ids=`+billIds;
   
            

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