@extends('layout.app')

@section('title', 'Employee Cash Ledger')
@section('description', 'Employee Cash Ledger')

@section('page-head')
    <style type="text/css">
        .bg-qty {
            background: #5759604a;
        }

        .bg-value {
            background: #33712e45;
        }

        .employee-balance {
            cursor: pointer;
            font-weight: 600;
            text-decoration: none;
        }

        .employee-balance:hover {
            text-decoration: underline;
        }

        #transactionModal .table th {
            background: #f5f5f5;
        }
    </style>
@endsection

@section('content')

<div class="container-fluid">
    <div class="social-dash-wrap">
        <!-- Breadcrumb -->
        <div class="row">
            <div class="col-lg-12">
                <div class="breadcrumb-main">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item">
                                <a href="/">
                                    <i class="las la-home"></i> Home
                                </a>
                            </li>
                            <li class="breadcrumb-item active">
                                Employee Cash Ledger
                            </li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>


        <!-- Page Title -->
        <div class="row">
            <div class="col-md-12" style="padding-bottom: 20px">
                <h4 class="text-capitalize breadcrumb-title">
                    Employee Cash Ledger Report
                </h4>
            </div>
        </div>


        <!-- Search -->
        <div class="row">
            <div class="col-md-6 offset-3">
                <div class="card">
                    <div class="card-body">
                        <form method="GET" action="{{ request()->url() }}">
                            <table class="table table-bordered">
                                <tr>
                                    <td>
                                        <select name="employee_id"  id="employee_id" class="form-control tom-select">
                                            <option value="">Select Employee</option>
                                            @foreach($employees as $employee)
                                                <option value="{{ $employee->id }}" {{ request('employee_id') == $employee->id ? 'selected' : '' }}>
                                                    {{ $employee->full_name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </td>


                                    <td class="text-right"  style="white-space: nowrap;">
                                        <button type="submit" class="btn btn-xs btn-primary"><i class="fa fa-search"></i>   Search</button>
                                        <a href="{{ request()->url() }}" class="btn btn-xs btn-warning"><i class="fa fa-refresh"></i>Refresh</a>
                                    </td>
                                </tr>
                            </table>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Employee Balance Report -->
        <div class="row">
            <div class="col-md-6 offset-3">
                <div class="card mb-4">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped">
                                <thead>
                                    <tr class="table-header-bg">
                                        <th class="text-center"> Sl </th>
                                        <th class="text-start"> Name </th>
                                        <th class="text-right pr-1"> Balance  </th>
                                    </tr>
                                </thead>

                                <tbody>
                                    @php
                                        $totalBalance = 0;
                                    @endphp

                                    @forelse($data as $dataItem)
                                        @php
                                            $totalBalance += $dataItem['balance'];
                                        @endphp
                                        <tr>
                                            <td class="text-center"> {{ $loop->iteration }} </td>
                                            <td class="text-start">{{ $dataItem['name'] }}</td>
                                            <td class="text-right">
                                                <a href="javascript:void(0)"  class="employee-balance" 
                                                    data-name="{{ $dataItem['name'] }}" data-balance="{{ $dataItem['balance'] }}"  data-transactions='@json($dataItem["transactions"])'>
                                                    {{ number_format($dataItem['balance'], 2) }}
                                                </a>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="3" class="text-center">No employee data found.  </td>
                                        </tr>
                                    @endforelse


                                    @if(count($data) > 0)
                                        <tr>
                                            <th colspan="2" class="text-center"> Total </th>
                                            <th class="text-right pr-1">
                                                {{ number_format($totalBalance, 2) }}
                                            </th>
                                        </tr>
                                    @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<!-- Transaction Details Modal -->
<div class="modal fade" id="transactionModal" tabindex="-1" role="dialog" aria-labelledby="transactionModalLabel"  aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <!-- Modal Header -->
            <div class="modal-header">
                <h4 class="modal-title"  id="transactionModalLabel"> Employee Cash Transaction Details   </h4>
                <button type="button"  class="close"data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">
                        &times;
                    </span>
                </button>
            </div>

            <!-- Modal Body -->
            <div class="modal-body">
                <!-- Employee Summary -->
                <div class="row">
                    <div class="col-md-6">
                        <strong> Employee:</strong>
                        <span id="modal_employee_name"> </span>
                    </div>

                    <div class="col-md-6 text-right">
                        <strong> Current Balance:</strong>
                        <span id="modal_employee_balance"></span>
                    </div>
                </div>
                <hr>

                <!-- Transaction Table -->
                <div class="table-responsive">
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th class="text-center"> Sl </th>
                                <th> Date </th>
                                <th> Reference </th>
                                <th> Type </th>
                                <th class="text-right"> Amount </th>
                                <th class="text-right"> Balance </th>
                            </tr>
                        </thead>
                        <tbody id="transaction_table_body"></tbody>
                        <tfoot>
                            <tr>
                                <th colspan="4" class="text-right"> Total</th>
                                <th class="text-right" id="modal_total_amount"> 0.00 </th>
                                <th class="text-right"></th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>

            <!-- Modal Footer -->
            <div class="modal-footer">
                <button type="button" class="btn btn-default"  data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('page_scripts')
<script>
$(document).on('click', '[data-dismiss="modal"]', function () {
    $('#transactionModal').modal('hide');
});

$(document).on('click', '.employee-balance', function () {
    /*
    |--------------------------------------------------------------------------
    | Get Employee Information
    |--------------------------------------------------------------------------
    */

    let employeeName = $(this).data('name');
    let balance = parseFloat($(this).data('balance')) || 0;

    /*
    |--------------------------------------------------------------------------
    | Get Transactions
    |--------------------------------------------------------------------------
    */
    let transactions = $(this).attr('data-transactions');
    try {
        transactions = JSON.parse(transactions);
    } catch (error) {
        transactions = [];
    }


    /*
    |--------------------------------------------------------------------------
    | Employee Information
    |--------------------------------------------------------------------------
    */

    $('#modal_employee_name').text(employeeName);

    $('#modal_employee_balance').text(
        balance.toLocaleString('en-US', {
            minimumFractionDigits: 2,
            maximumFractionDigits: 2
        })
    );


    /*
    |--------------------------------------------------------------------------
    | Clear Previous Data
    |--------------------------------------------------------------------------
    */
    $('#transaction_table_body').html('');
    $('#modal_total_amount').text('0.00');


    /*
    |--------------------------------------------------------------------------
    | No Transaction
    |--------------------------------------------------------------------------
    */

    if (!transactions || transactions.length === 0) {
        $('#transaction_table_body').html(`
            <tr>
                <td colspan="6" class="text-center">
                    No transaction found.
                </td>
            </tr>
        `);
        $('#transactionModal').modal('show');
        return;

    }

    /*
    |--------------------------------------------------------------------------
    | Transaction HTML
    |--------------------------------------------------------------------------
    */

    let html = '';
    let totalAmount = 0;
    transactions.forEach(function (transaction, index) {
        let amount = parseFloat(transaction.amount) || 0;
        let runningBalance =   parseFloat(transaction.balance) || 0;
        totalAmount += amount;

        let type = transaction.balance_type ? transaction.balance_type.toUpperCase() : '';
        let typeClass = '';

        if (type === 'CREDIT') {
            typeClass = 'text-success';
        } else if (type === 'DEBIT') {
            typeClass = 'text-danger';
        }

        html += `
            <tr>
                <td class="text-center">
                    ${index + 1}
                </td>
                <td>
                    ${transaction.date ?? ''}
                </td>
                <td>
                    ${transaction.invoice_no ?? ''}
                </td>
                <td class="${typeClass}">
                    <strong> ${type}  </strong>
                </td>
                <td class="text-right">
                    ${amount.toLocaleString('en-US', {
                        minimumFractionDigits: 2,
                        maximumFractionDigits: 2
                    })}
                </td>
                <td class="text-right">
                    ${runningBalance.toLocaleString('en-US', {
                        minimumFractionDigits: 2,
                        maximumFractionDigits: 2
                    })}
                </td>
            </tr>
        `;
    });

    /*
    |--------------------------------------------------------------------------
    | Put Transactions Into Table
    |--------------------------------------------------------------------------
    */
    $('#transaction_table_body').html(html);

    /*
    |--------------------------------------------------------------------------
    | Total Transaction Amount
    |--------------------------------------------------------------------------
    */

    $('#modal_total_amount').text(
        totalAmount.toLocaleString('en-US', {
            minimumFractionDigits: 2,
            maximumFractionDigits: 2
        })

    );

    /*
    |--------------------------------------------------------------------------
    | Show Modal
    |--------------------------------------------------------------------------
    */
    $('#transactionModal').modal('show');

});
</script>
@endsection