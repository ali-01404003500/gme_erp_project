 @extends('layout.app')

@section('title', 'Employee Cash Ledger')
@section('description', 'Employee Cash Ledger')

@section('page-head')
    <style type="text/css">
        .bg-qty { background: #5759604a; }
        .bg-value { background: #33712e45; }
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
                            <li class="breadcrumb-item active">Employee Cash Ledger</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12" style="padding-bottom: 20px">
                <h4 class="text-capitalize breadcrumb-title">Employee Cash Ledger Report</h4>
            </div>

            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <form>
                            <table class="table table-bordered">
                                <tr>
                                    <td  >
                                        <select name="employee_id" id="employee_id" class="form-control tom-select">
                                            <option value="">Select Employee</option>
                                           
                                        </select>
                                    </td> 
                                    <td class="text-right">
                                        <button class="btn btn-xs btn-primary"><i class="fa fa-search"></i> Search</button>
                                        <a href="{{ request()->url() }}" class="btn btn-xs btn-warning"><i class="fa fa-refresh"></i> Refresh</a>
                                    </td> 
                                </tr>
                            </table>
                        </form>
                    </div>
                </div>

                <div class="card mb-4">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped">
                                <thead>
                                    <tr class="table-header-bg">
                                        <th class="text-center">Sl</th>
                                        <th class="text-start">Name</th> 
                                        <th class="text-right pr-1">Balance</th>
                                    </tr>
                                </thead>
                                <tbody> 
                                    @php
                                        $totalBalance = 0;
                                        //dd($data);
                                    @endphp
                                    @foreach ($data as $dataItem)
                                        @php 
                                            $totalBalance += $dataItem['balance'];
                                        @endphp
                                        <tr>
                                            <td class="text-center">{{ $loop->iteration  }}</td>
                                            <td class="text-start">{{ $dataItem['name'] }}</td>
                                            <td class="text-right pr-1">{{ number_format($dataItem['balance']) }}</td>
                                        </tr>
                                    @endforeach

                                    <tr>
                                        <th colspan="2" class="text-center">Total</th>
                                        <th class="text-right pr-1">{{ number_format($totalBalance) }}</th>
                                        <th></th>
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
@endsection