@extends('layout.app')

@section('title', 'Journal Vouchers')
@section('description', 'Journal Vouchers')

@section('page-head')
    <style type="text/css">
        .bg-qty {
            background: #5759604a;
        }

        .bg-value {
            background: #33712e45;
        }

        .signature-row {
            display: flex;
            justify-content: space-between;
            text-align: center;
            margin-top: 2rem;
            margin-bottom: 2rem;
        }

        .signature-column {
            flex: 1;
        }

        .hr {
            border: 1px solid #000;
            margin: 0.5rem 0;
        }

        .print-area {
            width: 100%;
            margin: 0 auto;
            padding: 2rem;
            /* border: 1px solid #ddd;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1); */
        }

        /* Print-specific styles */
        @media print {
            body * {
                visibility: hidden;
            }

            #print-area,
            #print-area * {
                visibility: visible;
            }

            #print-area {
                position: absolute;
                top: 0;
                left: 0;
            }

            .hidden-print {
                display: none !important;
            }
        }
    </style>
@endsection

@section('content')
    <div class="container-fluid">
        <div class="social-dash-wrap">
            <div class="row">
                <div class="col-lg-12">
                    <div class="breadcrumb-main">
                        <div class="breadcrumb-action justify-content-center flex-wrap">
                            <nav aria-label="breadcrumb">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item">
                                        <a href="#"><i class="las la-home"></i>Home</a>
                                    </li>
                                    <li class="breadcrumb-item active" aria-current="page">
                                        {{ trans('Journal Vouchers') }}
                                    </li>
                                </ol>
                            </nav>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Voucher Content -->
            <div class="row">
                <div class="col-md-12 my-4">
                    <div class="card">
                        <div class="card-body print-area" id="print-area">
                            {{-- <div class="print-area" id="print-area"> --}}
                                <div class="row heading">
                                    <!-- Company Logo -->
                                    <div class="col-xs-4">
                                        @if (file_exists('uploads/company/' . optional($voucher->company)->logo))
                                            <img class="invoice-logo"
                                                src="{{ asset('uploads/company/' . optional($voucher->company)->logo) }}"
                                                alt="Logo">
                                        @endif
                                    </div>
                                    <!-- Company Details -->
                                    {{-- <div class="col-xs-4 text-center">
                                        <h3 style="line-height: 15px; font-weight: 600; color: #000;">
                                            {{ optional($voucher->user->company)->name ?? '' }}</h3>
                                        <span>{{ optional($voucher->company)->head_office }}</span><br>
                                        <span><strong>Email: </strong>{{ optional($voucher->company)->email }}</span><br>
                                        <span><strong>Phone: </strong>{{ optional($voucher->company)->phone_number }}</span>
                                    </div> --}}
                                    <div class="col-xs-4"></div>
                                    <div class="col-xs-12 text-center mb-2">
                                        <h4 style="color: #0369a1; margin-top: 5px; margin-bottom: -1px">
                                            <strong>Journal Voucher</strong>
                                        </h4>
                                    </div>
                                </div>
                                <!-- Voucher Details -->
                                <div class="row">
                                    <div class="col-md-12 text-center">
                                        <span class="text-secondary">Invoice No:</span>
                                        {{ $voucher->invoice_no }}<br>
                                        @if (!empty($voucher->reference))
                                            <span class="text-secondary">Reference:</span>
                                            {{ $voucher->reference }}<br>
                                        @endif
                                        <span class="text-secondary">Date :</span>
                                        {{ $voucher->date }}
                                    </div>
                                </div>
                                <!-- Table Section -->
                                <table class="table table-bordered border-none" style="width: 100%;">
                                    <thead style="background-color: #7592A5; color: #ffffff;">
                                        <tr>
                                            <th width="5%" class="text-center">Sl</th>
                                            <th width="75%">Account</th>
                                            <th width="10%" class="text-right">Debit</th>
                                            <th width="10%" class="text-right">Credit</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($voucher->details ?? [] as $item)
                                            <tr>
                                                <td class="text-center">{{ $loop->iteration }}</td>
                                                <td>{{ optional($item->account)->name }}</td>
                                                <td class="text-right">
                                                    @if ($item->balance_type == 'Debit')
                                                        {{ number_format($item->amount) }}
                                                    @endif
                                                </td>
                                                <td class="text-right">
                                                    @if ($item->balance_type == 'Credit')
                                                        {{ number_format($item->amount) }}
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                    <tfoot class="text-right" style="font-weight: bold;">
                                        @php
                                            $totalAmount = $voucher->details->sum('amount') / 2;
                                        @endphp
                                        <tr>
                                            <th colspan="2" class="text-right">Total :</th>
                                            <th class="text-right">{{ number_format($totalAmount) }}</th>
                                            <th class="text-right">{{ number_format($totalAmount) }}</th>
                                        </tr>
                                    </tfoot>
                                </table>
                                <!-- Description -->
                                @if (!empty($voucher->description))
                                    <div class="row my-5">
                                        <div class="col-md-12">
                                            <div> In words: {{ convert_number($totalAmount) . ' TK Debit & ' . convert_number($totalAmount) . ' TK Credit.' }}
                                            </div>
                                            <div><b>Note: </b>{{ $voucher->description }}</div>
                                        </div>
                                    </div>
                                @endif
                                <!-- Signature Section -->
                                <div class="row signature-row">
                                    @php
                                        $signatures = ['Prepared By', 'Accountant By', 'Approved By', 'Received By'];
                                    @endphp
                                    @foreach ($signatures as $signature)
                                        <div class="col-md-3 signature-column">
                                            <p style="visibility: hidden;">.</p>
                                            <hr class="hr">
                                            <div>{{ $signature }}</div>
                                        </div>
                                    @endforeach
                                </div>
                                <div class="row mt-3 mb-4 hidden-print">
                                    <div class="col-md-12 d-flex justify-content-end">
                                        <a class="btn btn-primary btn-sm btn-print"><i class="fa fa-print"></i> Print</a>
                                        <a class="btn btn-danger btn-sm" href="{{ route('account.voucher-journals.index') }}">
                                            <i class="fa fa-backward"></i> Back To List
                                        </a>
                                    </div>
                                </div>
                            {{-- </div> --}}
                           
                        </div>
                        <!-- Print and Back Buttons -->
                       
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('page_scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            document.querySelector('.btn-print').addEventListener('click', function () {
                window.print();
            });
        });
    </script>
@endsection
