@extends('layout.app')
@section('title', 'EMI Customer-Wise Report')
@section('description', 'EMI Customer Wise Report')

@section('content')
    <div class="container-fluid">
        <div class="social-dash-wrap">
            <div class="row">
                <div class="col-lg-12">
                    <div class="breadcrumb-main">
                        <div class="breadcrumb-action justify-content-center flex-wrap">
                            <nav aria-label="breadcrumb">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}"><i
                                                class="las la-home"></i>Home</a></li>
                                    <li class="breadcrumb-item active" aria-current="page">
                                        {{ trans('EMI Customer Wise Report') }}</li>
                                </ol>
                            </nav>
                        </div>
                        <div class="action-btn mt-sm-0 mt-15 d-flex align-items-center">
                            @if (hasPermission('account.emi-reports.emi-customer-report'))
                                <form method="GET" target="_blank" style="display: inline;"> {{-- moved here --}}
                                    <input type="hidden" name="customer_id" value="{{ $customerId }}">
                                    <input type="hidden" name="month" value="{{ $month }}">
                                    <button type="submit" name="export_type" value="pdf" class="btn btn-danger btn-sm"
                                        style="margin-left: 5px;" @if ($reportData->isEmpty()) disabled @endif>
                                        <i class="las la-file-pdf fs-16"></i> PDF
                                    </button>
                                </form>
                            @endif
                        </div>

                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12" style="padding-bottom: 20px">
                    <h4 class="text-capitalize breadcrumb-title">{{ trans('EMI Customer-Wise Report') }}</h4>
                    <x-error-alart />
                </div>

                <div class="col-md-12">
                    <div class="col-md-12 my-4">
                        <div class="card">
                            <div class="card-body">
                                <form method="GET" action="{{ route('account.emi-reports.emi-customer-report') }}">
                                    <div class="row">
                                        <div class="col-md-5">
                                            <div class="form-group">
                                                <label for="customer_id">{{ trans('Customer Name') }} <span
                                                        class="text-danger">*</span></label>
                                                <select name="customer_id" id="customer_id" class="form-control tom-select"
                                                    required>
                                                    <option value="">{{ trans('Select Customer') }}</option>
                                                    @foreach ($customers as $customer)
                                                        <option value="{{ $customer->id }}"
                                                            @if ($customerId == $customer->id) selected @endif>
                                                            {{ $customer->company_name }} - {{ $customer->address}}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>

                                        <div class="col-md-5">
                                            <div class="form-group">
                                                <label for="month">{{ trans('Month') }} <span
                                                        class="text-danger">*</span></label>
                                                <input type="text" name="month" class="form-control flatmonth"
                                                    value="{{ $month }}" placeholder="Select Month" required
                                                    autocomplete="off" />
                                            </div>
                                        </div>

                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <label>&nbsp;</label>
                                                <td class="text-right">
                                                    <div class="btn-group btn-corner w-100">
                                                        <button type="submit" class="btn btn-xs btn-primary"
                                                            id="generate-report">
                                                            <i class="fa fa-cog"></i> {{ trans('Generate') }}
                                                        </button>
                                                        <button type="button" class="btn btn-xs btn-warning"
                                                            id="refresh-report">
                                                            <i class="fa fa-refresh"></i> {{ trans('Refresh') }}
                                                        </button>
                                                    </div>
                                                </td>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

                    <div class="col-md-12">
                        <div class="row">
                            <div class="col-md-12">


                                @if (!$reportData->isEmpty())
                                    <div class="card mb-4">
                                        <div class="card-body">
                                            @if ($selectedCustomer)
                                                <div class="mb-3">
                                                    <h5 class="text-primary">
                                                        <strong>Customer:</strong> {{ $selectedCustomer->company_name }}
                                                        <span class="text-muted">({{ $selectedCustomer->phone }})</span>
                                                    </h5>
                                                    <p class="text-muted mb-0"><strong>Address:</strong>
                                                        {{ $selectedCustomer->address }}</p>
                                                </div>
                                            @endif

                                            <!-- Report Table -->
                                            <div class="table-responsive">
                                                <table class="table table-bordered" style="width:100%">
                                                    <thead>
                                                        <tr style="background-color: #f2f2f2;">
                                                            <th>{{ trans('SL') }}</th>
                                                            <th>{{ trans('EMI No') }}</th>
                                                            <th>{{ trans('Customer Name') }}</th>
                                                            <th>{{ trans('Phone No') }}</th>
                                                            <th>{{ trans('Installment Date') }}</th>
                                                            <th>{{ trans('Installment Amount') }}</th>
                                                            <th>{{ trans('Payment Date') }}</th>
                                                            <th>{{ trans('Payment Amount') }}</th>
                                                            <th>{{ trans('Installment Status') }}</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @forelse($reportData as $item)
                                                            <tr
                                                                style="background-color: #{{ $item['row_color'] === 'orange' ? 'ffa50040' : 'ffffff' }};">
                                                                <td>{{ $item['sl'] }}</td>
                                                                <td>{{ $item['emi_no'] }}</td>
                                                                <td>
                                                                    {{ $item['customer_name'] }}
                                                                    <br>
                                                                    <small
                                                                        class="text-muted">{{ $item['customer_address'] }}</small>
                                                                </td>
                                                                <td>{{ $item['phone'] }}</td>
                                                                <td>{{ $item['emi_date'] }}</td>
                                                                <td class="text-right">
                                                                    {{ number_format($item['installment_amount']) }}
                                                                </td>
                                                                <td>{{ $item['pay_date'] ? date('Y-m-d', strtotime($item['pay_date'])) : 'N/A' }}
                                                                </td>
                                                                <td class="text-right">
                                                                    {{ number_format($item['pay_amount']) }}</td>
                                                                <td>
                                                                    <span
                                                                        class="badge badge-round badge-{{ $item['pay_status'] === 'Paid' ? 'success' : ($item['row_color'] === 'orange' ? 'warning' : 'danger') }}">
                                                                        {{ $item['pay_status'] }}
                                                                    </span>
                                                                </td>
                                                            </tr>
                                                        @empty
                                                            <tr>
                                                                <td colspan="9" class="text-center">
                                                                    {{ trans('No data available') }}</td>
                                                            </tr>
                                                        @endforelse
                                                    </tbody>
                                                    <tfoot>
                                                        <tr style="background-color: #f2f2f2; font-weight: bold;">
                                                            <td colspan="5" class="text-right">{{ trans('Total') }}:
                                                            </td>
                                                            <td class="text-right">
                                                                {{ number_format($totalInstallmentAmount) }}</td>
                                                            <td></td>
                                                            <td class="text-right">
                                                                {{ number_format($totalPaymentAmount) }}</td>
                                                            <td></td>
                                                        </tr>
                                                    </tfoot>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @endsection

            @section('page_scripts')
                <script>
                    document.getElementById('refresh-report').addEventListener('click', function() {
                        // Reloads the page to reset the report
                        window.location.href = "{{ route('account.emi-reports.emi-customer-report') }}";
                    });
                </script>
            @endsection


            <style>
                .badge-success {
                    background-color: #28a745;
                    color: white;
                }

                .badge-warning {
                    background-color: #ffc107;
                    color: black;
                }

                .badge-danger {
                    background-color: #dc3545;
                    color: white;
                }

                .text-right {
                    text-align: right;
                }
            </style>
