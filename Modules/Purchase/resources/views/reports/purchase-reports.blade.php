@extends('layout.app')

@section('title', 'Purchase Report')
@section('description', 'Purchase Report')

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
                                    <li class="breadcrumb-item active" aria-current="page">Purchase Report</li>
                                </ol>
                            </nav>
                        </div>
                        <div class="breadcrumb-main__wrapper">
                            <div class="action-btn d-flex align-items-center">
                                <button type="button" class="btn btn-sm btn-info mr-2" data-toggle="modal"
                                    data-target="#columnFilterModal">
                                    <i class="las la-filter"></i> Column Filter
                                </button>
                                <a href="{{ request()->fullUrlWithQuery(['export_type' => 'pdf']) }}" target="_blank"
                                    class="btn btn-danger btn-sm mr-2">
                                    <i class="las la-file-pdf fs-16"></i> PDF
                                </a>
                                <a href="{{ request()->fullUrlWithQuery(['export_type' => 'excel']) }}"
                                    class="btn btn-success btn-sm">
                                    <i class="las la-file-excel fs-16"></i> Excel
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12">
                    <h4 class="text-capitalize breadcrumb-title">Purchase Report</h4>
                </div>

                <!-- Search & Filter Section -->
                <div class="col-md-12 my-4">
                    <div class="card">
                        <div class="card-header">
                            <h6 class="mb-0">Search & Filter</h6>
                        </div>
                        <div class="card-body">
                            <form method="GET" action="{{ route('purchase.reports.index') }}">
                                <div class="row">
                                    <div class="col-md-3 mb-3">
                                        <label>Supplier Name</label>
                                        <select name="supplier_id" class="tom-select" data-placeholder="Select Supplier">
                                            <option value=""></option>
                                            @foreach ($suppliers as $supplier)
                                                <option value="{{ $supplier->id }}"
                                                    {{ request('supplier_id') == $supplier->id ? 'selected' : '' }}>
                                                    {{ $supplier->company_name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-3 mb-3">
                                        <label>Username</label>
                                        <select name="user_id" class="tom-select" data-placeholder="Select User">
                                            <option value=""></option>
                                            @foreach ($users as $user)
                                                <option value="{{ $user->id }}"
                                                    {{ request('user_id') == $user->id ? 'selected' : '' }}>
                                                    {{ $user->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-3 mb-3">
                                        <label>Invoice ID</label>
                                        <select name="requisition_no" class="tom-select"
                                            data-placeholder="Select Invoice ID">
                                            <option value=""></option>
                                            @foreach ($requisitions as $requisition)
                                                <option value="{{ $requisition->requisition_no }}"
                                                    {{ request('requisition_no') == $requisition->requisition_no ? 'selected' : '' }}>
                                                    {{ $requisition->requisition_no }}
                                                </option>
                                            @endforeach
                                            @foreach ($purchaseReturns as $return)
                                                <option value="{{ $return->invoice_no }}"
                                                    {{ request('requisition_no') == $return->invoice_no ? 'selected' : '' }}>
                                                    {{ $return->invoice_no }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-3 mb-3">
                                        <label>Product Name</label>
                                        <select name="product_id" class="tom-select" data-placeholder="Select Product">
                                            <option value=""></option>
                                            @foreach ($products as $product)
                                                <option value="{{ $product->id }}"
                                                    {{ request('product_id') == $product->id ? 'selected' : '' }}>
                                                    {{ $product->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-3 mb-3">
                                        <label>Date Range (From - To)</label>
                                        <div class="input-daterange input-group">
                                            <input type="text" class="form-control flatdate" name="from"
                                                value="{{ request('from') }}" autocomplete="off" placeholder="From" />
                                            <span class="input-group-text"><i class="fa fa-exchange-alt"></i></span>
                                            <input type="text" class="form-control flatdate" name="to"
                                                value="{{ request('to') }}" autocomplete="off" placeholder="To" />
                                        </div>
                                    </div>
                                    <div class="col-md-3 mb-3">
                                        <label>Price</label>
                                        <div class="input-group">
                                            <input type="number" name="min_price" class="form-control"
                                                value="{{ request('min_price') }}" placeholder="Min" step="0.01">
                                            <span class="input-group-text">-</span>
                                            <input type="number" name="max_price" class="form-control"
                                                value="{{ request('max_price') }}" placeholder="Max" step="0.01">
                                        </div>
                                    </div>
                                    <div class="col-md-3 mb-3">
                                        <label>Branch Name</label>
                                        <select name="branch_id" class="tom-select" data-placeholder="Select Branch">
                                            <option value=""></option>
                                            @foreach ($branches as $branch)
                                                <option value="{{ $branch->id }}"
                                                    {{ request('branch_id') == $branch->id ? 'selected' : '' }}>
                                                    {{ $branch->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-3 mb-3">
                                        <label>Invoice Type</label>
                                        <select name="invoice_type" class="form-control">
                                            <option value="">All Types</option>
                                            <option value="purchase"
                                                {{ request('invoice_type') == 'purchase' ? 'selected' : '' }}>Purchase
                                            </option>
                                            <option value="return"
                                                {{ request('invoice_type') == 'return' ? 'selected' : '' }}>Purchase Return
                                            </option>
                                        </select>
                                    </div>
                                    <div class="button-group d-flex pt-25 justify-content-md-end justify-content-start">
                                        <button type="submit" class="btn btn-primary">
                                            <i class="fa fa-search"></i> Generate Report
                                        </button>
                                        <a href="{{ route('purchase.reports.index') }}" class="btn btn-warning">
                                            <i class="fa fa-refresh"></i> Clear
                                        </a>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Report Table -->
                <div class="col-md-12">
                    <div class="card mb-4">
                        <div class="card-body">
                            <div class="table-responsive">
                                <style>
                                    .jobs-table-custom,
                                    .jobs-table-custom th,
                                    .jobs-table-custom td {
                                        border: 1px solid #dee2e6 !important;
                                        border-collapse: collapse !important;
                                    }

                                    .jobs-table-custom th,
                                    .jobs-table-custom td {
                                        padding: 12px;
                                        vertical-align: middle;
                                    }

                                    .jobs-table-custom thead th {
                                        background-color: #f8f9fa;
                                        border-bottom-width: 2px !important;
                                    }

                                    .table thead th {
                                        background-color: #35526e !important;
                                        color: #ffffff !important;
                                        font-weight: 600 !important;
                                        text-transform: uppercase;
                                        font-size: 0.85rem !important;
                                        letter-spacing: 0.08em;
                                        border-bottom: 2px solid #2a4054 !important;
                                        padding: 14px 16px !important;
                                        vertical-align: middle;
                                        text-align: center;
                                    }
                                </style>
                                <table class="table jobs-table-custom dt-table-hover" id="purchaseReportTable"
                                    style="font-size: 11px;">
                                    <thead class="bg-primary text-white">
                                        <tr>
                                            <th class="text-center col-sl">SL No.</th>
                                            <th class="col-invoice-id">Invoice ID</th>
                                            <th class="col-invoice-datetime">Invoice Date & Time</th>
                                            <th class="col-supplier">Supplier Name</th>
                                            <th class="col-invoice-to">Invoice To</th>
                                            <th class="col-invoice-status">Invoice Status</th>
                                            <th class="col-descriptions">Descriptions</th>
                                            <th class="col-user">User</th>
                                            <th class="col-reference">Reference Invoice</th>
                                            <th class="col-creation-date">Creation Date</th>
                                            <th class="col-invoice-type">Invoice Type</th>
                                            <th class="col-discount">Discount</th>
                                            <th class="col-payment-status">Payment Status</th>
                                            <th class="col-product-price">Product Price</th>
                                            <th class="col-quantity">Quantity</th>
                                            <th class="col-sells-center">Sells Center</th>
                                            <th class="col-purchase-center">Purchase Center</th>
                                            <th class="col-invoice-amount">Invoice Amount</th>
                                            <th class="col-paid-amount">Paid Amount</th>
                                            <th class="col-due-amount">Due Amount</th>
                                            <th class="col-files">Files/Images</th>
                                            <th class="col-payment-list">Payment List</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php
                                            $totalInvoiceAmount = 0;
                                            $totalPaidAmount = 0;
                                            $totalDueAmount = 0;
                                        @endphp

                                        @forelse($reportData as $index => $item)
                                            @if ($item['type'] === 'Purchase')
                                                @php
                                                    $requisition = $item['data'];
                                                    $paidAmount = $requisition->paid_amount;
                                                    $dueAmount = $requisition->due_amount;

                                                    $totalInvoiceAmount += $requisition->net_amount;
                                                    $totalPaidAmount += $paidAmount;
                                                    $totalDueAmount += $dueAmount;

                                                    $hasReturnWithReference = \Modules\Purchase\Models\PurchaseReturn::where(
                                                        'requisition_id',
                                                        $requisition->id,
                                                    )
                                                        ->whereNotNull('reference_invoice')
                                                        ->exists();
                                                @endphp
                                                <tr>
                                                    <td class="text-center">{{ $index + 1 }}</td>
                                                    <td>
                                                        <a href="{{ route('purchase.requisitions.show', $requisition->id) }}"
                                                            target="_blank" class="text-primary font-weight-bold">
                                                            {{ $requisition->requisition_no }}
                                                        </a>
                                                    </td>
                                                    <td>
                                                        {{ $requisition->invoice_date }}<br>
                                                        <small
                                                            class="text-muted">{{ $requisition->created_at->format('h:i A') }}</small>
                                                    </td>
                                                    <td>{{ optional($requisition->supplier)->company_name ?? 'N/A' }}</td>
                                                    <td>{{ optional($requisition->warehouse)->name ?? 'N/A' }}</td>
                                                    <td>
                                                        @if ($requisition->status == 0)
                                                            <span class="badge badge-warning badge-round">Pending</span>
                                                        @elseif($requisition->status == 1)
                                                            <span class="badge badge-success badge-round">Approved</span>
                                                        @elseif($requisition->status == 4)
                                                            <span class="badge badge-primary badge-round">Received</span>
                                                        @else
                                                            <span class="badge badge-danger badge-round">Rejected</span>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        <input type="text"
                                                            class="form-control form-control-sm editable-field border-0"
                                                            data-type="remarks" data-id="{{ $requisition->id }}"
                                                            data-record-type="requisition"
                                                            value="{{ $requisition->description }}"
                                                            placeholder="Enter description">
                                                    </td>
                                                    <td>{{ optional($requisition->createdBy)->name }}</td>
                                                    <td>
                                                        <input type="text"
                                                            class="form-control form-control-sm {{ $hasReturnWithReference ? 'editable-field' : '' }} border-0"
                                                            data-type="reference_invoice"
                                                            data-id="{{ $requisition->id }}"
                                                            data-record-type="requisition"
                                                            value="{{ $requisition->purchase_invoice }}"
                                                            placeholder="Reference"
                                                            {{ $hasReturnWithReference ? '' : 'readonly' }}>
                                                    </td>
                                                    <td>
                                                        <input type="text"
                                                            class="form-control form-control-sm editable-date border-0 flatdate"
                                                            data-type="creation_date" data-id="{{ $requisition->id }}"
                                                            data-record-type="requisition"
                                                            value="{{ $requisition->created_at->format('Y-m-d') }}">
                                                    </td>
                                                    <td><span class="badge badge-info badge-round">Purchase</span></td>
                                                    <td class="text-right">{{ number_format($requisition->discount) }}
                                                    </td>
                                                    <td class="text-center">
                                                        @if ($dueAmount <= 0)
                                                            <span class="badge badge-success badge-round">Paid</span>
                                                        @elseif($paidAmount > 0)
                                                            <span class="badge badge-warning badge-round">Partial</span>
                                                        @else
                                                            <span class="badge badge-danger badge-round">Due</span>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        @foreach ($requisition->requisitionDetails as $detail)
                                                            <div class="mb-1">
                                                                <small>{{ optional($detail->product)->name }}:</small>
                                                                <strong>{{ number_format($detail->price) }}</strong>
                                                            </div>
                                                        @endforeach
                                                    </td>
                                                    <td class="text-center">
                                                        @foreach ($requisition->requisitionDetails as $detail)
                                                            <div class="mb-1">{{ $detail->quantity }}</div>
                                                        @endforeach
                                                    </td>
                                                    <td>{{ optional($requisition->customer)->company_name ?? 'N/A' }}</td>
                                                    <td>{{ optional($requisition->supplier)->company_name ?? 'N/A' }}</td>
                                                    <td class="text-right font-weight-bold">
                                                        {{ number_format($requisition->net_amount) }}</td>
                                                    <td class="text-right text-success">
                                                        {{ number_format($paidAmount) }}</td>
                                                    <td class="text-right text-danger">{{ number_format($dueAmount) }}
                                                    </td>
                                                    <td class="text-center">
                                                        @php
                                                            $hasFiles = false;
                                                            $fileCount = 0;
                                                        @endphp

                                                        {{-- Check for requisition file uploads --}}
                                                        @php $files = array_filter($requisition->file_uploads ?? []); @endphp
                                                        @if (!empty($files))
                                                            @php $hasFiles = true; @endphp
                                                            @foreach ($files as $file)
                                                                @php $fileCount++; @endphp
                                                                <a href="{{ asset($file) }}" target="_blank"
                                                                    class="badge badge-secondary badge-round mb-1">
                                                                    <i class="fa fa-file"></i> Doc {{ $loop->iteration }}
                                                                </a><br>
                                                            @endforeach
                                                        @endif

                                                        {{-- Check for payment attachments from old payment system --}}
                                                        @if ($requisition->payment->isNotEmpty())
                                                            @foreach ($requisition->payment->where('status', 'approved') as $payment)
                                                                @foreach ($payment->paymentDetails as $detail)
                                                                    @if ($detail->attachments)
                                                                        @php
                                                                            $hasFiles = true;
                                                                            $fileCount++;
                                                                        @endphp
                                                                        <a href="{{ asset($detail->attachments) }}"
                                                                            target="_blank"
                                                                            class="badge badge-info badge-round mb-1">
                                                                            <i class="fa fa-file-invoice"></i> Payment
                                                                            {{ $loop->iteration }}
                                                                        </a><br>
                                                                    @endif
                                                                @endforeach
                                                            @endforeach
                                                        @endif

                                                        {{-- Check for payment attachments from invoice-wise payment system --}}
                                                        @if ($requisition->invoiceWisePaymentInvoices->isNotEmpty())
                                                            @foreach ($requisition->invoiceWisePaymentInvoices as $iwpi)
                                                                @if ($iwpi->invoiceWisePayment && $iwpi->invoiceWisePayment->status === 'approved')
                                                                    @if ($iwpi->invoiceWisePayment->payments->isNotEmpty())
                                                                        @foreach ($iwpi->invoiceWisePayment->payments as $pay)
                                                                            @if ($pay->attachments)
                                                                                @php
                                                                                    $hasFiles = true;
                                                                                    $fileCount++;
                                                                                @endphp

                                                                                <a href="{{ asset($pay->attachments) }}" target="_blank" class="badge badge-success badge-round mb-1">
                                                                                                        <i class="fa fa-receipt"></i> Invoice Pay {{ $loop->iteration }}
                                                                                                    </a><br>

                                                                            @endif
                                                                        @endforeach
                                                                    @endif
                                                                @endif
                                                            @endforeach
                                                        @endif

                                                        @if (!$hasFiles)
                                                            <span class="text-muted">No Files</span>
                                                        @else
                                                            <small class="text-muted d-block mt-1">({{ $fileCount }}
                                                                file(s))</small>
                                                        @endif
                                                    </td>

                                                    <td class="text-center">
                                                        @if ($requisition->payment->isNotEmpty())
                                                            @foreach ($requisition->payment->where('status', 'approved') as $payment)
                                                                @foreach ($payment->paymentDetails as $detail)
                                                                    <a href="{{ route('account.payments.make-payments.show', $payment->id) }}"
                                                                        target="_blank"
                                                                        class="btn btn-sm btn-outline-primary mb-1"
                                                                        title="Old receipt">
                                                                        <i class="fa fa-receipt"></i>
                                                                        {{ $payment->payment_id }}
                                                                    </a><br>
                                                                @endforeach
                                                            @endforeach
                                                        @endif

                                                        @if ($requisition->invoiceWisePaymentInvoices->isNotEmpty())
                                                            @foreach ($requisition->invoiceWisePaymentInvoices as $iwpi)
                                                                @if ($iwpi->invoiceWisePayment && $iwpi->invoiceWisePayment->status === 'approved')
                                                                    <a href="{{ route('account.payments.invoice-wise-payments.show', $iwpi->invoiceWisePayment->id) }}"
                                                                        target="_blank"
                                                                        class="btn btn-sm btn-outline-success mb-1"
                                                                        title="Invoice-wise receipt">
                                                                        <i class="fa fa-file-invoice-dollar"></i>
                                                                        {{ $iwpi->invoiceWisePayment->invoice_wise_payment_id }}
                                                                    </a><br>
                                                                @endif
                                                            @endforeach
                                                        @endif

                                                        @if ($requisition->payment->isEmpty() && $requisition->invoiceWisePaymentInvoices->isEmpty())
                                                            <span class="text-muted">No Payment</span>
                                                        @endif
                                                    </td>
                                                </tr>
                                            @else
                                                @php
                                                    $return = $item['data'];
                                                    $totalInvoiceAmount -= $return->net_amount;
                                                @endphp
                                                <tr class="table-warning">
                                                    <td class="text-center">{{ $index + 1 }}</td>
                                                    <td>
                                                        <a href="{{ route('purchase.returns.show', $return->id) }}"
                                                            target="_blank" class="text-danger font-weight-bold">
                                                            {{ $return->invoice_no }}
                                                        </a>
                                                    </td>
                                                    <td>
                                                        {{ $return->return_date }}<br>
                                                        <small
                                                            class="text-muted">{{ $return->created_at->format('h:i A') }}</small>
                                                    </td>
                                                    <td>{{ optional($return->supplier)->company_name ?? 'N/A' }}</td>
                                                    <td>{{ optional($return->requisition->warehouse)->name ?? 'N/A' }}</td>
                                                    <td><span class="badge badge-danger badge-round">Return</span></td>
                                                    <td>
                                                        <input type="text"
                                                            class="form-control form-control-sm editable-field border-0"
                                                            data-type="remarks" data-id="{{ $return->id }}"
                                                            data-record-type="purchase_return"
                                                            value="{{ $return->remarks }}" placeholder="Enter remarks">
                                                    </td>
                                                    <td>{{ optional($return->createdBy)->name }}</td>
                                                    <td>
                                                        <input type="text"
                                                            class="form-control form-control-sm editable-field border-0"
                                                            data-type="reference_invoice" data-id="{{ $return->id }}"
                                                            data-record-type="purchase_return"
                                                            value="{{ $return->reference_invoice }}"
                                                            placeholder="Reference">
                                                    </td>
                                                    <td>
                                                        <input type="text"
                                                            class="form-control form-control-sm editable-date border-0 flatdate"
                                                            data-type="creation_date" data-id="{{ $return->id }}"
                                                            data-record-type="purchase_return"
                                                            value="{{ $return->created_at->format('Y-m-d') }}">
                                                    </td>
                                                    <td><span class="badge badge-danger badge-round">Purchase Return</span>
                                                    </td>
                                                    <td class="text-right">{{ number_format($return->discount) }}</td>
                                                    <td class="text-center"><span
                                                            class="badge badge-secondary badge-round">Return</span></td>
                                                    <td>
                                                        @foreach ($return->purchaseReturnDetails as $detail)
                                                            <div class="mb-1">
                                                                <small>{{ optional($detail->product)->name }}:</small>
                                                                <strong>{{ number_format($detail->price) }}</strong>
                                                            </div>
                                                        @endforeach
                                                    </td>
                                                    <td class="text-center">
                                                        @foreach ($return->purchaseReturnDetails as $detail)
                                                            <div class="mb-1">{{ $detail->quantity }}</div>
                                                        @endforeach
                                                    </td>
                                                    <td>N/A</td>
                                                    <td>{{ optional($return->supplier)->company_name ?? 'N/A' }}</td>
                                                    <td class="text-right font-weight-bold text-danger">
                                                        {{ number_format($return->net_amount) }}</td>
                                                    <td class="text-right">-</td>
                                                    <td class="text-right">-</td>
                                                    <td class="text-center"><span class="text-muted">N/A</span></td>
                                                    <td class="text-center"><span class="text-muted">N/A</span></td>
                                                </tr>
                                            @endif
                                        @empty
                                            <tr>
                                                <td colspan="22" class="text-center py-4">
                                                    <i class="las la-inbox" style="font-size: 48px; color: #ddd;"></i>
                                                    <p class="mb-0">No records found</p>
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                    <tfoot>
                                        <tr class="font-weight-bold" style="font-size: 14px;">
                                            <th colspan="17" class="text-right">Grand Total:</th>
                                            <th class="text-right">{{ number_format($totalInvoiceAmount) }}</th>
                                            <th class="text-right text-success">{{ number_format($totalPaidAmount) }}
                                            </th>
                                            <th class="text-right text-danger">{{ number_format($totalDueAmount) }}
                                            </th>
                                            <th colspan="2"></th>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                            <div class="mt-3">
                                {{ $requisitions->appends(request()->query())->links('pagination::bootstrap-5') }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Column Filter Modal -->
    <div class="modal fade" id="columnFilterModal" tabindex="-1" aria-labelledby="columnFilterModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="columnFilterModalLabel">Select Columns to Display</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="columnFilterForm">
                        <div class="row">
                            @php
                                $columns = [
                                    'invoice-id' => 'Invoice ID',
                                    'invoice-datetime' => 'Invoice Date & Time',
                                    'supplier' => 'Supplier Name',
                                    'invoice-to' => 'Invoice To',
                                    'invoice-status' => 'Invoice Status',
                                    'descriptions' => 'Descriptions',
                                    'user' => 'User',
                                    'reference' => 'Reference Invoice',
                                    'creation-date' => 'Creation Date',
                                    'invoice-type' => 'Invoice Type',
                                    'discount' => 'Discount',
                                    'payment-status' => 'Payment Status',
                                    'product-price' => 'Product Price',
                                    'quantity' => 'Quantity',
                                    'sells-center' => 'Sells Center',
                                    'purchase-center' => 'Purchase Center',
                                    'invoice-amount' => 'Invoice Amount',
                                    'paid-amount' => 'Paid Amount',
                                    'due-amount' => 'Due Amount',
                                    'files' => 'Files/Images',
                                    'payment-list' => 'Payment List',
                                ];
                            @endphp
                            @foreach ($columns as $key => $label)
                                <div class="col-md-6 mb-2">
                                    <div class="custom-control custom-checkbox">
                                        <input type="checkbox" class="custom-control-input" id="col_{{ $key }}"
                                            name="columns[]" value="{{ $key }}" checked>
                                        <label class="custom-control-label" for="col_{{ $key }}">
                                            {{ $label }} </label>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" id="applyColumnFilter">Apply Filter</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('page_scripts')
    <script>
        $(document).ready(function() {
            // Column names array — keys correspond to header classes "col-<key>"
            var allColumns = [
                'invoice-id', 'invoice-datetime', 'supplier', 'invoice-to', 'invoice-status',
                'descriptions', 'user', 'reference', 'creation-date', 'invoice-type',
                'discount', 'payment-status', 'product-price', 'quantity', 'sells-center',
                'purchase-center', 'invoice-amount', 'paid-amount', 'due-amount', 'files', 'payment-list'
            ];

            // Debug: Check if modal exists
            console.log('Modal exists:', $('#columnFilterModal').length);

            // Force modal initialization
            $('#columnFilterModal').modal({
                show: false,
                backdrop: true,
                keyboard: true
            });

            // Manual trigger for Column Filter button
            $('button[data-target="#columnFilterModal"]').on('click', function(e) {
                e.preventDefault();
                console.log('Column Filter button clicked');
                $('#columnFilterModal').modal('show');
            });

            // Alternative: Direct click handler
            $('.btn[data-toggle="modal"][data-target="#columnFilterModal"]').on('click', function(e) {
                e.preventDefault();
                e.stopPropagation();
                $('#columnFilterModal').modal('show');
            });

            // Utility: show all columns (headers + body + footer)
            function showAllColumns() {
                // show headers
                $('#purchaseReportTable thead th').show();
                // show each row's cells
                $('#purchaseReportTable tbody tr').each(function() {
                    $(this).find('td').show();
                });
                // show footer
                $('#purchaseReportTable tfoot th, #purchaseReportTable tfoot td').show();
            }

            // When modal opens, sync checkboxes with visible columns
            $('#columnFilterModal').on('show.bs.modal', function() {
                console.log('Modal opening...');
                allColumns.forEach(function(colKey) {
                    var header = $('#purchaseReportTable thead th.col-' + colKey);
                    var checkbox = $('#col_' + colKey);
                    if (header.length && header.is(':visible')) {
                        checkbox.prop('checked', true);
                    } else {
                        checkbox.prop('checked', false);
                    }
                });
            });

            // Apply column filter
            $('#applyColumnFilter').on('click', function() {
                var selectedColumns = [];
                $('#columnFilterForm input[type="checkbox"]:checked').each(function() {
                    selectedColumns.push($(this).val());
                });

                // show all first
                showAllColumns();

                // hide unselected columns by index (header index => nth-child)
                allColumns.forEach(function(colKey) {
                    if (!selectedColumns.includes(colKey)) {
                        // find header th index
                        var header = $('#purchaseReportTable thead th.col-' + colKey);
                        if (header.length) {
                            var idx = header.index(); // zero-based
                            // hide header
                            header.hide();
                            // hide every row's corresponding td
                            $('#purchaseReportTable tbody tr').each(function() {
                                $(this).find('td').eq(idx).hide();
                            });
                            // hide footer cell if exists (th/td)
                            $('#purchaseReportTable tfoot tr').each(function() {
                                $(this).find('th, td').eq(idx).hide();
                            });
                        }
                    }
                });

                $('#columnFilterModal').modal('hide');

                // Show success message
                alert('Column filter applied successfully!');
            });

            // Handle editable fields (text)
            $(document).on('blur', '.editable-field', function() {
                var field = $(this);
                var type = field.data('type');
                var id = field.data('id');
                var recordType = field.data('record-type'); // Get record_type
                var value = field.val();

                // Show loading state
                field.prop('disabled', true).addClass('bg-light');

                $.ajax({
                    url: '{{ route('purchase.reports.update-field') }}',
                    type: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        id: id,
                        type: type,
                        record_type: recordType,
                        value: value
                    },
                    success: function(response) {
                        if (response.success) {
                            field.removeClass('bg-light border-danger').addClass(
                                'border-success');
                            setTimeout(function() {
                                field.removeClass('border-success');
                            }, 2000);
                        } else {
                            field.addClass('border-danger');
                            alert('Failed to update: ' + response.message);
                        }
                    },
                    error: function(xhr) {
                        field.addClass('border-danger');
                        alert('Error updating field: ' + (xhr.responseJSON?.message ||
                            'Unknown error'));
                    },
                    complete: function() {
                        field.prop('disabled', false).removeClass('bg-light');
                    }
                });
            });

            // Handle editable date fields
            $(document).on('change', '.editable-date', function() {
                var field = $(this);
                var type = field.data('type');
                var id = field.data('id');
                var recordType = field.data('record-type'); // Get record_type
                var value = field.val();

                $.ajax({
                    url: '{{ route('purchase.reports.update-field') }}',
                    type: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        id: id,
                        type: type,
                        record_type: recordType,
                        value: value
                    },
                    success: function(response) {
                        if (response.success) {
                            field.addClass('border-success');
                            setTimeout(function() {
                                field.removeClass('border-success');
                            }, 2000);
                        }
                    },
                    error: function() {
                        field.addClass('border-danger');
                        alert('Failed to update date');
                    }
                });
            });

            // Initialize flatpickr for date inputs
            if (typeof flatpickr !== 'undefined') {
                $('.flatdate').flatpickr({
                    dateFormat: 'Y-m-d',
                    allowInput: true
                });
            }

            // Tooltip initialization
            if ($.fn.tooltip) {
                $('[data-toggle="tooltip"]').tooltip();
            }

            // Debug log
            console.log('Purchase Report scripts initialized');
        });
    </script>

    <style>
        /* Table styling */
        #purchaseReportTable {
            width: 100%;
            font-size: 11px;
        }

        #purchaseReportTable th {
            white-space: nowrap;
            vertical-align: middle;
            font-weight: 600;
            padding: 8px 6px;
        }

        #purchaseReportTable td {
            vertical-align: middle;
            padding: 6px;
        }

        /* Column widths */
        .col-sl {
            width: 40px;
        }

        .col-invoice-id {
            width: 120px;
        }

        .col-invoice-datetime {
            width: 130px;
        }

        .col-supplier {
            width: 150px;
        }

        .col-invoice-to {
            width: 120px;
        }

        .col-invoice-status {
            width: 90px;
        }

        .col-descriptions {
            width: 200px;
        }

        .col-user {
            width: 100px;
        }

        .col-reference {
            width: 120px;
        }

        .col-creation-date {
            width: 110px;
        }

        .col-invoice-type {
            width: 100px;
        }

        .col-discount {
            width: 80px;
        }

        .col-payment-status {
            width: 100px;
        }

        .col-product-price {
            width: 150px;
        }

        .col-quantity {
            width: 80px;
        }

        .col-sells-center {
            width: 120px;
        }

        .col-purchase-center {
            width: 150px;
        }

        .col-invoice-amount {
            width: 100px;
        }

        .col-paid-amount {
            width: 100px;
        }

        .col-due-amount {
            width: 100px;
        }

        .col-files {
            width: 100px;
        }

        .col-payment-list {
            width: 100px;
        }

        /* Editable field styling */
        .editable-field {
            background-color: #fffef7;
            border: 1px dashed #ddd !important;
            transition: all 0.3s ease;
        }

        .editable-field:focus {
            background-color: #fff;
            border: 1px solid #4CAF50 !important;
            box-shadow: 0 0 5px rgba(76, 175, 80, 0.3);
        }

        .editable-field.border-success {
            border: 2px solid #28a745 !important;
            background-color: #d4edda;
        }

        .editable-field.border-danger {
            border: 2px solid #dc3545 !important;
            background-color: #f8d7da;
        }

        /* Badge styling */
        .badge {
            font-size: 10px;
            padding: 4px 8px;
        }

        /* Rounded pill badge */
        .badge-round {
            border-radius: 999px !important;
            padding: 4px 10px !important;
            display: inline-block;
            line-height: 1.2;
        }

        /* Button styling */
        .view-payments {
            font-size: 10px;
            padding: 4px 8px;
        }

        /* Modal styling */
        .modal-xl {
            max-width: 1200px;
        }

        /* Responsive table */
        .table-responsive {
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
        }

        /* Print styles */
        @media print {

            .breadcrumb-main,
            .card-header,
            .btn,
            .modal,
            .no-print {
                display: none !important;
            }

            #purchaseReportTable {
                font-size: 9px;
            }

            .editable-field {
                border: none !important;
                background: transparent !important;
            }
        }

        /* Loading animation */
        .spinner-border {
            width: 3rem;
            height: 3rem;
        }

        /* Hover effects */
        #purchaseReportTable tbody tr:hover {
            background-color: #f8f9fa;
            transition: background-color 0.2s ease;
        }

        /* File badge styling */
        .badge-secondary {
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .badge-secondary:hover {
            background-color: #5a6268;
            transform: scale(1.05);
        }
    </style>
@endsection
