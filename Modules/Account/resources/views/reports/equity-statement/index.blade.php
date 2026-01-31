@extends('layout.app')
@section('title',"Equity Statement")
@section('description',"Equity Statement")
@section('content')
    <!-- CONTENT AREA -->
    <div class="container-fluid">
        <div class="social-dash-wrap">
            <div class="row">
                <div class="col-lg-12">
                    <div class="breadcrumb-main">
                        <div class="breadcrumb-action justify-content-center flex-wrap">
                            <nav aria-label="breadcrumb">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="/"><i class="las la-home"></i>Home</a></li>
                                    <li class="breadcrumb-item active" aria-current="page">
                                        {{ trans('menu.Equity Statement') }}</li>
                                </ol>
                            </nav>
                        </div>
                        <div class="breadcrumb-main__wrapper">
                            <div class="action-btn mt-sm-0 mt-15 d-flex align-items-center">
                              
                                <a href="{{ route('account.report.equity-statement') }}?export_type=pdf&{{ http_build_query(request()->except('export_type', '_token')) }}" target="_blank"
                                    class="btn btn-danger btn-sm d-inline-block mr-2" style="margin-left: 5px;">
                                    <i class="las la-file-pdf fs-16"></i> PDF
                                </a>
                                <a href="{{ route('account.report.equity-statement') }}?export_type=excel&{{ http_build_query(request()->except('export_type', '_token')) }}" target="_blank"
                                    class="btn btn-success btn-sm d-inline-block" style="margin-left: 5px;">
                                    <i class="las la-file-excel fs-16"></i> Excel
                                </a> 
                            </div>
                        </div>
                    </div>
                </div>
            </div>


            <div class="row">
                <div class="col-md-12" style="padding-bottom: 20px">
                    <div class="row" style="width: 100%">
                        <div class="col-md-6">
                            <h4 class="text-capitalize breadcrumb-title">{{ trans('Equity Statement') }}
                            </h4>
                        </div>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="col-md-12 my-4">
                        <div class="card">
                            <div class="card-body">
                                <form>
                                    <div class="col-sm-12">
                                        <table class="table table-bordered">
                                            <tr>
                                                <td>
                                                    <input type="text" class="form-control flatmonthrange" name="date_range" value="{{ request('date_range') }}">
                                                </td>
                                                <td>
                                                    <button type="submit" class="btn btn-primary btn-default btn-squared radius-md shadow2 btn-sm">
                                                        Submit
                                                    </button>
                                                </td>
                                            </tr>
                                        </table>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    <div class="card mb-4">
                        <div class="card-body">
                            <div class="table-responsive">
                                <div class="card mb-4">
                                    <div class="card-body">
                                        <div class="table-responsive">
                                            @php 
                                                    $previous_year_share_capital = 0;
                                                    $previous_year_retained_earnings = 0;
                                                @endphp 
                                            <table class="table table-sm table-bordered">



                                                <thead>
                                                    <tr>
                                                        <th colspan="4" class="text-center">
                                                            <div>
                                                                <h2>Equity Statement</h2>
                                                                <h5 class="text-center">Date From: {{request()->input('date_range')??date('Y-m-d')}}</h5>
                                                            </div>
                                                        </th>
                                                    </tr>
                                                    <tr>
                                                        <th>Particular</th>
                                                        <th class="text-center">Share Capital</th>
                                                        <th class="text-center">Retained Earnings</th>
                                                        <th class="text-center">Total</th>
                                                    </tr>
                                                </thead>
                    
                    
                                                
                    
                                                <tbody>
                                                    <tr>
                                                        <td>Opening Balance</td>
                                                        <td class="text-right capital">
                                                            {{ number_format($previous_year_share_capital, 0) }}
                                                        </td>
                                                        <td class="text-right retained-earnings">
                                                            {{ number_format($previous_year_retained_earnings, 0) }}
                                                        </td>
                                                        <td class="text-right item-total"></td>
                                                    </tr>
                    
                                                    <tr>
                                                        <td>Add : Profit/Loss during the year</td>
                                                        <td class="text-right capital">
                                                            {{ number_format($profit_loss_share_capital = 0, 0) }}
                                                        </td>
                                                        <td class="text-right retained-earnings">
                                                            {{ number_format($profit_los_retained_earnings = $profit_and_loss, 0) }}
                                                        </td>
                                                        <td class="text-right item-total"></td>
                                                    </tr>
                                                    <tr>
                                                        <td>Add : addition during the year</td>
                                                        <td class="text-right capital">
                                                            {{ number_format($addition_share_capital = 0, 0) }}
                                                        </td>
                                                        <td class="text-right retained-earnings">
                                                            {{ number_format($addition_retained_earnings = $equity > 0 ? $equity : 0, 0) }}
                                                        </td>
                                                        <td class="text-right item-total"></td>
                                                    </tr>
                                                    <tr>
                                                        <td>Less : adjustment during the year</td>
                                                        <td class="text-right capital">
                                                            {{ number_format($adjustment_share_capital = 0, 0) }}
                                                        </td>
                                                        <td class="text-right retained-earnings">
                                                            {{ number_format($adjusement_retained_earnings = $equity < 0 ? $equity : 0, 0) }}
                                                        </td>
                                                        <td class="text-right item-total"></td>
                                                    </tr>
                                                    <tr>
                                                        <td>
                                                            <strong>Closing Balance</strong>
                                                        </td>
                                                        <td class="text-right">
                                                            <strong class="capital">{{ number_format($previous_year_share_capital + $profit_loss_share_capital + $addition_share_capital - $adjustment_share_capital, 0) }}</strong>
                                                        </td>
                                                        <td class="text-right">
                                                            <strong class="retained-earnings">{{ number_format($previous_year_retained_earnings + $profit_los_retained_earnings + $addition_retained_earnings - $adjusement_retained_earnings, 0) }}</strong>
                                                        </td>
                                                        <td class="text-right">
                                                            <strong class="item-total"></strong>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>Previous Year Balance</td>
                                                        <td class="text-right capital">
                                                            {{ number_format($previous_year_share_capital, 0) }}
                                                        </td>
                                                        <td class="text-right retained-earnings">
                                                            {{ number_format($previous_year_retained_earnings, 0) }}
                                                        </td>
                                                        <td class="text-right item-total"></td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('page_scripts')


@endsection