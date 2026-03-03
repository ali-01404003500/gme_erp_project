@section('title', 'Payment Verification List')
@section('description', 'Payment Verification List')
@extends('layout.app')
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
                                        {{ trans('Payments Verification List') }}</li>
                                </ol>
                            </nav>
                        </div>
                        <div class="breadcrumb-main__wrapper">
                            
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <h4 class="text-capitalize breadcrumb-title">{{ trans('Payments Verification List') }}</h4>
                </div>
                <div class="col-md-12"> 
                    <div class="card mb-4">
                        <div class="card-body">
                            {{-- @dd($makePayments); --}}
                            <table id="zero-config" class="table dt-table-hover"  data-page='@include('utils.table_paginate', ['data' => $paymentVerifications])'
                                style="width:100%">
                                <thead>
                                    <tr>
                                        <th>SL</th> 
                                        <th>Date</th>
                                        <th>Type</th> 
                                        <th>Payment To</th> 
                                        <th>Entry By</th>
                                        <th>Verified By</th> 
                                        <th>Pay Mode</th>
                                        <th>Amount</th>
                                        <th>Document/Images</th> 
                                        <th>Note</th>
                                        <th class="no-content">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                   @foreach ($paymentVerifications as $payment)
                                        <tr>
                                            {{-- Serial number --}}
                                            <td>
                                                {{ ($paymentVerifications->currentPage() - 1) * $paymentVerifications->perPage() + $loop->iteration }}
                                            </td>

                                            {{-- Date --}}
                                            <td>{{ $payment->date }}</td>

                                            {{-- Payment Type --}}
                                            <td>
                                                @if($payment->paymentable->paymentTo->company_name??null)
                                                    {{ 'Supplier' }} 
                                                @elseif($payment->paymentable->customer->company_name??null)
                                                    {{ 'Customer' }}
                                                @elseif($payment->paymentable->paymentTo->account_name??null)
                                                    {{ 'Petty Cash' }}
                                                @elseif($payment->paymentable->salesCommission->broker->broker_name??null)
                                                    {{ 'Broker' }}
                                                @elseif($payment->paymentable??null)
                                                    {{ 'Employee' }}
                                                @else
                                                    {{ 'N/A' }}
                                                @endif
                                            </td>

                                            {{--  Name (clickable) --}}
                                            <td>
                                                <a class="text-dark fw-500"
                                                    href="#">
                                                    {{ $payment->paymentable->paymentTo->company_name??
                                                    $payment->paymentable->paymentTo->broker_name?? 
                                                    $payment->paymentable->paymentTo->name??$payment->paymentable->customer->company_name??
                                                    $payment->paymentable->salesCommission->broker->broker_name??
                                                    $payment->paymentable->employee->full_name??
                                                    $payment->paymentable->paymentTo->account_name??
                                                    'N/A' }}


                                                </a>
                                            </td>

                                            
                                            {{-- Prepared By --}}
                                            <td>{{ $payment->paymentable->createdBy?->name ?? 'N/A' }}</td>

                                            {{-- Verified By --}}
                                            <td>
                                                 {{ $payment->verifiedBy->name ?? 'N/A' }}
                                            </td>

                                            {{-- paymode --}}
                                            <td>{{ $payment->pay_mode  }}</td>

                                            {{-- Amount --}}
                                            <td>{{ number_format($payment->amount) }}</td>

                                            {{-- Document/Images --}}
                                            <td>
                                                @if($payment->attachments)
                                                    <a href="{{ asset($payment->attachments) }}" target="_blank" class="btn btn-sm btn-outline-info"><i class="fa fa-eye"></i></a>
                                                @endif
                                            </td>

                                            {{-- Remarks --}}
                                            <td>{!! nl2br(wordwrap($payment->remark, 30)) !!}</td>
 
                                            {{-- Actions --}}
                                            <td>
                                                <div class="btn-group btn-group-sm" role="group"
                                                    aria-label="Small button group">
                                     
                                                    @if ($payment->verified == '0' && hasPermission('account.payments.make-payments.verify'))
                                                        <button class="btn btn-outline-info" title="Verify" 
                                                            onclick="verifyPayment({{ $payment->id }})">
                                                            <i class="fas fa-user-check"></i>
                                                        </button>

                                                    @endif

                                                    @if ($payment->verified == '1' && hasPermission('account.payments.make-payments.approve'))
                                                        <button class="btn btn-outline-success" title="Approve"
                                                            onclick="approvePayment({{ $payment->id }})">
                                                            <i class="fas fa-check-circle"></i>
                                                        </button>
                                                    @endif 

                                                    @if ( $payment->verified != '2' && (hasPermission('account.payments.make-payments.verify') || hasPermission('account.payments.make-payments.approve')))
                                                        <button class="btn btn-outline-danger" title="Deny"
                                                            onclick="denyPayment({{ $payment->id }})">
                                                            <i class="fas fa-times-circle"></i>
                                                        </button>
                                                    @endif 

                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
 
                            <div class="d-none">
                                <form class="updateForm" action="" method="POST">
                                    @csrf 
                                    @method('POST')
                                    <input type="hidden" name="verified" value="0">
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
        $(document).ready(function () {
        
 
        });

        function verifyPayment(id) {
            Swal.fire({
            title: 'Are you sure?',
            text: 'Do you want to verify this payment?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, verify it!'
            }).then((result) => {
            if (result.isConfirmed) {
                $('.updateForm').attr('action','{{ route("account.payments.payment-verifications.update", ":id") }}'.replace(':id', id));
                $('.updateForm input[name="verified"]').val('1');
                $('.updateForm').submit();
            }
            });
        }

        function approvePayment(id) {
            Swal.fire({
            title: 'Are you sure?',
            text: 'Do you want to approve this payment?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, approve it!'
            }).then((result) => {
            if (result.isConfirmed) {
                $('.updateForm').attr('action','{{ route("account.payments.payment-verifications.update", ":id") }}'.replace(':id', id));
                $('.updateForm input[name="verified"]').val('2');
                $('.updateForm').submit();
            }
            });
        }

        function denyPayment(id) {
            Swal.fire({
            title: 'Are you sure?',
            text: 'Do you want to deny this payment?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, deny it!'
            }).then((result) => {
            if (result.isConfirmed) {
                $('.updateForm').attr('action','{{ route("account.payments.payment-verifications.update", ":id") }}'.replace(':id', id));
                $('.updateForm input[name="verified"]').val('-1');
                $('.updateForm').submit();
            }
            });
        }
    </script>
@endsection