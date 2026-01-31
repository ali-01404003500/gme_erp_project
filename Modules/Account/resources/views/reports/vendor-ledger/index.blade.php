@extends('layout.app')

@section('title', 'Vendor Ledger')
@section('description', 'Vendor Ledger')

@section('page-head')
    <style type="text/css">
        .bg-qty { background: #5759604a; }
        .bg-value { background: #33712e45; }
        .vendor-info-box {
            background: #f8f9fa;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
        }
        .vendor-info-box .info-label {
            font-weight: 600;
            color: #495057;
        }
        .vendor-info-box .info-value {
            color: #212529;
        }
    </style>
@endsection

@section('content')
<div class="container-fluid">
    <div class="social-dash-wrap">
        <div class="row">
            <div class="col-lg-12">
                <div class="breadcrumb-main">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="/"><i class="las la-home"></i> Home</a></li>
                            <li class="breadcrumb-item active">Vendor Ledger</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="d-flex justify-content-between align-items-center user-member__title mb-30" style="padding-bottom: 20px">
                <h4 class="text-capitalize breadcrumb-title">Vendor Ledger Report</h4>
                <div class="btn-group">
                        <a href="{{ request()->fullUrlWithQuery(['export_type' => 'pdf']) }}" target="_blank"
                            class="btn btn-danger btn-sm">
                            <i class="fa fa-file-pdf"></i> PDF
                        </a>
                        <a href="{{ request()->fullUrlWithQuery(['export_type' => 'excel']) }}" target="_blank"
                            class="btn btn-primary btn-sm">
                            <i class="fa fa-file-excel"></i> Excel
                        </a>
                    </div>
            </div>

            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <form>
                            <table class="table table-bordered">
                                <tr>
                                    <td width="30%">
                                        <select id="account_id" name="account_id" class="form-control tom-select" data-placeholder="- Select Vendor -">
                                            <option value=""></option>
                                            @foreach($vendors as $vendor)
                                                <option value="{{ $vendor->getAccount()->id }}"
                                                    {{ request('account_id') == $vendor->getAccount()->id ? 'selected' : '' }}>
                                                    {{ $vendor->company_name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td>
                                        <div class="input-daterange input-group">
                                            <input type="text" class="form-control flatdate" name="from"
                                                   value="{{ request('from') ?? date('Y-m-d') }}" placeholder="Date From" autocomplete="off">
                                            <span class="input-group-text"><i class="fa fa-exchange-alt"></i></span>
                                            <input type="text" class="form-control flatdate" name="to"
                                                   value="{{ request('to') ?? date('Y-m-d') }}" placeholder="Date To" autocomplete="off">
                                        </div>
                                    </td>
                                    <td class="text-right">
                                        <div class="btn-group btn-corner">
                                                        <button class="btn btn-xs btn-primary"><i class="fa fa-search"></i>
                                                            Search</button>
                                                        <a href="{{ request()->url() }}" class="btn btn-xs btn-warning"><i
                                                                class="fa fa-refresh"></i> Refresh</a>
                                                    </div>
                                    </td>
                                </tr>
                            </table>
                        </form>
                    </div>
                </div>

                @if(request('account_id') && isset($selectedVendor))
                                    @php
                        $companyTypes = [
                            1 => 'Private Limited',
                            2 => 'Proprietorship',
                            3 => 'Public Limited',
                            4 => 'Government Organisation',
                            5 => 'None'
                        ];
                        $vendorType = $companyTypes[$selectedVendor->company_type_id] ?? 'N/A';
                    @endphp

                    <div class="card mb-4">
                        <div class="card-body">
                            <div class="vendor-info-box">
                                <div class="row">
                                    <div class="col-md-6">
                                        <p class="mb-2">
                                            <span class="info-label">Vendor Name:</span> 
                                            <span class="info-value">{{ $selectedVendor->company_name }}</span>
                                        </p>
                                        <p class="mb-2">
                                            <span class="info-label">Address:</span> 
                                            <span class="info-value">{{ $selectedVendor->address ?? 'N/A' }}</span>
                                        </p>
                                    </div>
                                    <div class="col-md-6">
                                        <p class="mb-2">
                                            <span class="info-label">Vendor Type:</span> 
                                            <span class="info-value">{{ $vendorType ?? 'N/A' }}</span>
                                        </p>
                                        <p class="mb-2">
                                            <span class="info-label">Phone:</span> 
                                            <span class="info-value">{{ $selectedVendor->phone ?? 'N/A' }}</span>
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

                <div class="card mb-4">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped" >
                                <thead>
                                    <tr class="table-header-bg">
                                        <th class="text-center">Sl</th>
                                        <th class="text-center">Date</th>
                                        <th class="text-center">Particulars</th>
                                        <th class="text-center">Reference</th>
                                        <th class="text-right pr-1">Debit</th>
                                        <th class="text-right pr-1">Credit</th>
                                        <th class="text-right pr-1">Balance</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if(request('account_id'))
                                        <tr>
                                            <td colspan="6" class="text-left pl-3">Opening Balance</td>
                                            <td class="text-right pr-1">{{ number_format($balance) }}</td>
                                        </tr>
                                    @else
                                        <tr>
                                            <td colspan="7" class="text-center text-danger" style="font-size:16px">
                                                NO RECORDS FOUND! Please select a vendor to view ledger.
                                            </td>
                                        </tr>
                                    @endif

                                    @php
                                        $totalDebit = 0;
                                        $totalCredit = 0;
                                        $runningBalance = $balance;
                                    @endphp

                                    @foreach($transactions as $transaction)
                                        @php
                                            $debitAmount = $transaction->debit_amount;
                                            $creditAmount = $transaction->credit_amount;

                                            $runningBalance += ($creditAmount - $debitAmount);
                                            $totalDebit += $debitAmount;
                                            $totalCredit += $creditAmount;
                                            
                                            // Get particulars based on transaction type
                                            $particulars = 'N/A';
                                            if ($transaction->transactionable) {
                                                $particulars = class_basename($transaction->transactionable_type);
                                            }
                                        @endphp

                                        <tr>
                                            <td class="text-center">{{ $loop->iteration }}</td>
                                            <td class="text-center">{{ \Carbon\Carbon::parse($transaction->transaction_date)->format('d-m-Y') }}</td>
                                            <td class="text-center">{{ $particulars }}</td>
                                            <td class="text-center">{!! $transaction->getClickableVoucherNo() !!}</td>
                                            <td class="text-right pr-1">{{ number_format($debitAmount) }}</td>
                                            <td class="text-right pr-1">{{ number_format($creditAmount) }}</td>
                                            <td class="text-right pr-1">{{ number_format($runningBalance) }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    @if(request('account_id'))
                                        <tr class="table-header-bg">
                                            <th colspan="4" class="text-right pr-3">Total:</th>
                                            <th class="text-right pr-1">{{ number_format($totalDebit) }}</th>
                                            <th class="text-right pr-1">{{ number_format($totalCredit) }}</th>
                                            <th></th>
                                        </tr>
                                        <tr>
                                            <th colspan="6" class="text-right pr-3">Closing Balance:</th>
                                            <th class="text-right pr-1">{{ number_format($runningBalance) }}</th>
                                        </tr>
                                    @endif
                                </tfoot>
                            </table>
                        </div>
                        
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection