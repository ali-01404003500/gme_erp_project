@extends('layout.app')

@section('title', 'Trial Balance')
@section('description', 'Trial Balance')

@section('page-head')
    <style type="text/css">
        .bg-qty {
            background: #5759604a;
        }
        .bg-value {
            background: #33712e45;
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
                                    <li class="breadcrumb-item"><a href="/"><i class="las la-home"></i>Home</a></li>
                                    <li class="breadcrumb-item active" aria-current="page">{{ trans('Trial Balance') }}</li>
                                </ol>
                            </nav>
                        </div>
                        <div class="breadcrumb-main__wrapper">
                            <div class="action-btn mt-sm-0 mt-15 d-flex align-items-center">
                                <a href="{{ route('account.report.trial-balance') }}?export_type=pdf&{{ http_build_query(request()->except('export_type', '_token')) }}" target="_blank"
                                    class="btn btn-danger btn-sm d-inline-block mr-2" style="margin-left: 5px;">
                                    <i class="las la-file-pdf fs-16"></i> PDF
                                </a>
                                <a href="{{ route('account.report.trial-balance') }}?export_type=excel&{{ http_build_query(request()->except('export_type', '_token')) }}" target="_blank"
                                    class="btn btn-success btn-sm d-inline-block" style="margin-left: 5px;">
                                    <i class="las la-file-excel fs-16"></i> Excel
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Filter Section -->
            <div class="row">
                <div class="col-md-12" style="padding-bottom: 20px">
                    <div class="row" style="width: 100%">
                        <div class="col-md-6">
                            <h4 class="text-capitalize breadcrumb-title">{{ trans('Trial Balance Report') }}</h4>
                        </div>
                    </div>
                </div>

                <div class="col-md-12">
                    <div class="col-md-12 my-4">
                        <div class="card">
                            <div class="card-body">
                                <form id="trial-balance-filter">
                                    <div class="col-sm-12">
                                        <table class="table table-bordered">
                                            <tr>
                                                <td>
                                                    <div class="input-daterange input-group">
                                                        <input type="text" class="form-control flatdate"
                                                            name="from"
                                                            value="{{ request('from') ?? date('Y-m-d') }}" autocomplete="off"
                                                            placeholder="Date From" />
                                                        <span class="input-group-text"><i class="fa fa-exchange-alt"></i></span>
                                                        <input type="text" class="form-control flatdate"
                                                            name="to"
                                                            value="{{ request('to') ?? date('Y-m-d') }}" autocomplete="off"
                                                            placeholder="Date To" />
                                                    </div>
                                                </td>
                                                <td colspan="4" class="text-end">
                                                    <div class="btn-group btn-corner">
                                                        <button class="btn btn-xs btn-primary"><i class="fa fa-search"></i> Search</button>
                                                        <a href="{{ request()->url() }}" class="btn btn-xs btn-warning"><i class="fa fa-refresh"></i> Refresh</a>
                                                    </div>
                                                </td>
                                                <td class="text-end">
                                                    <div class="form-check form-switch d-inline-block">
                                                        <input class="form-check-input" type="checkbox" id="showZeroToggle"
                                                            name="show_zero" value="1"
                                                            {{ request('show_zero') ? 'checked' : '' }}>
                                                        <label class="form-check-label" for="showZeroToggle">
                                                            Show Zero Balance Accounts
                                                        </label>
                                                    </div>
                                                </td>
                                            </tr>
                                        </table>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                    <!-- Trial Balance Table -->
                    <div class="card mb-4">
                        <div class="card-body">
                            <div class="row" style="width: 100%; margin: 0; padding: 0;">
                                <div class="col-sm-12">
                                    <table class="table" style="width: 100%; border: none; table-layout: fixed;">
                                        <thead>
                                            <tr style="font-size: 12px; border-bottom: 1px solid #ccc;">
                                                <th class="text-start" width="15%">Group</th>
                                                <th class="text-start" width="15%">Controls</th>
                                                <th class="text-start" width="15%">Subsidiaries</th>
                                                <th class="text-start" width="25%">Accounts</th>
                                                <th class="text-end" width="10%">Dr.</th>
                                                <th class="text-end" width="10%">Cr.</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @if ($accountGroups->count() == 0)
                                                <tr>
                                                    <td colspan="6" class="text-center text-danger" style="font-size: 16px;">
                                                        NO RECORDS FOUND!
                                                    </td>
                                                </tr>
                                            @endif

                                            @php
                                                $totalTrialAmountDebit = 0;
                                                $totalTrialAmountCredit = 0;
                                                $showZero = request('show_zero');
                                            @endphp

                                            @foreach ($accountGroups as $accountGroup)
                                                @php
                                                    $debitAccountGroup = $accountGroup->accountControls->sum(fn($control) =>
                                                        $control->accountSubsidiaries->sum(fn($item) =>
                                                            $item->accounts->sum('debit')
                                                        )
                                                    );
                                                    $creditAccountGroup = $accountGroup->accountControls->sum(fn($control) =>
                                                        $control->accountSubsidiaries->sum(fn($item) =>
                                                            $item->accounts->sum('credit')
                                                        )
                                                    );
                                                @endphp

                                                @if ($showZero || $debitAccountGroup != 0 || $creditAccountGroup != 0)
                                                    @php
                                                        $totalTrialAmountDebit += $debitAccountGroup;
                                                        $totalTrialAmountCredit += $creditAccountGroup;
                                                    @endphp

                                                    <!-- Group Row -->
                                                    <tr data-bs-toggle="collapse" data-bs-target="#group-{{ $accountGroup->id }}" style="cursor: pointer;">
                                                        <th class="text-start"><strong style="font-size: 16px;">{{ $accountGroup->name }}</strong></th>
                                                        <td></td>
                                                        <td></td>
                                                        <td></td>
                                                        <td class="text-end"><strong>{{ number_format($debitAccountGroup) }}</strong></td>
                                                        <td class="text-end"><strong>{{ number_format($creditAccountGroup) }}</strong></td>
                                                    </tr>

                                                    <!-- Control Level -->
                                                    <tr>
                                                        <td colspan="6" class="p-0">
                                                            <div class="collapse" id="group-{{ $accountGroup->id }}">
                                                                @foreach ($accountGroup->accountControls as $accountControl)
                                                                    @php
                                                                        $debitAccountControl = $accountControl->accountSubsidiaries->sum(fn($item) => $item->accounts->sum('debit'));
                                                                        $creditAccountControl = $accountControl->accountSubsidiaries->sum(fn($item) => $item->accounts->sum('credit'));
                                                                    @endphp

                                                                    @if ($showZero || $debitAccountControl != 0 || $creditAccountControl != 0)
                                                                        <table class="table mb-0" style="width: 100%; table-layout: fixed;">
                                                                            <tr data-bs-toggle="collapse" data-bs-target="#control-{{ $accountControl->id }}" style="cursor: pointer;">
                                                                                <td width="15%"></td>
                                                                                <th width="15%" class="text-start"><strong>{{ $accountControl->name }}</strong></th>
                                                                                <td width="15%"></td>
                                                                                <td width="25%"></td>
                                                                                <td width="10%" class="text-end"><strong>{{ number_format($debitAccountControl) }}</strong></td>
                                                                                <td width="10%" class="text-end"><strong>{{ number_format($creditAccountControl) }}</strong></td>
                                                                            </tr>

                                                                            <!-- Subsidiary Level -->
                                                                            <tr>
                                                                                <td colspan="6" class="p-0">
                                                                                    <div class="collapse" id="control-{{ $accountControl->id }}">
                                                                                        @foreach ($accountControl->accountSubsidiaries as $accountSubsidiary)
                                                                                            @php
                                                                                                $debitSubsidiary = $accountSubsidiary->accounts->sum('debit');
                                                                                                $creditSubsidiary = $accountSubsidiary->accounts->sum('credit');
                                                                                            @endphp

                                                                                            @if ($showZero || $debitSubsidiary != 0 || $creditSubsidiary != 0)
                                                                                                <table class="table mb-0" style="width: 100%; table-layout: fixed;">
                                                                                                    <tr data-bs-toggle="collapse" data-bs-target="#subsidiary-{{ $accountSubsidiary->id }}" style="cursor: pointer;">
                                                                                                        <td width="15%"></td>
                                                                                                        <td width="15%"></td>
                                                                                                        <th width="15%" class="text-start">{{ ucfirst($accountSubsidiary->name) }}</th>
                                                                                                        <td width="25%"></td>
                                                                                                        <td width="10%" class="text-end"><strong>{{ number_format($debitSubsidiary) }}</strong></td>
                                                                                                        <td width="10%" class="text-end"><strong>{{ number_format($creditSubsidiary) }}</strong></td>
                                                                                                    </tr>

                                                                                                    <!-- Accounts Level -->
                                                                                                    <tr>
                                                                                                        <td colspan="6" class="p-0">
                                                                                                            <div class="collapse" id="subsidiary-{{ $accountSubsidiary->id }}">
                                                                                                                @foreach ($accountSubsidiary->accounts as $account)
                                                                                                                    @if ($showZero || $account->debit != 0 || $account->credit != 0)
                                                                                                                        <table class="table mb-0" style="width: 100%; table-layout: fixed;">
                                                                                                                            <tr>
                                                                                                                                <td width="15%"></td>
                                                                                                                                <td width="15%"></td>
                                                                                                                                <td width="15%"></td>
                                                                                                                                <td width="25%" class="text-start">{{ $account->name }}</td>
                                                                                                                                <td width="10%" class="text-end">{{ number_format($account->debit) }}</td>
                                                                                                                                <td width="10%" class="text-end">{{ number_format($account->credit) }}</td>
                                                                                                                            </tr>
                                                                                                                        </table>
                                                                                                                    @endif
                                                                                                                @endforeach
                                                                                                            </div>
                                                                                                        </td>
                                                                                                    </tr>
                                                                                                </table>
                                                                                            @endif
                                                                                        @endforeach
                                                                                    </div>
                                                                                </td>
                                                                            </tr>
                                                                        </table>
                                                                    @endif
                                                                @endforeach
                                                            </div>
                                                        </td>
                                                    </tr>
                                                @endif
                                            @endforeach
                                        </tbody>

                                        <!-- Totals -->
                                        <tfoot>
                                            <tr class="text-center" style="font-size: 15px; font-weight: bold; border-top: 1px solid #ccc;">
                                                <th colspan="4" class="text-start" style="font-size: 18px; color: #1ba74d;">Total</th>
                                                <th class="text-end" style="font-size: 18px; color: #1ba74d;">{{ number_format($totalTrialAmountDebit) }}</th>
                                                <th class="text-end" style="font-size: 18px; color: #1ba74d;">{{ number_format($totalTrialAmountCredit) }}</th>
                                            </tr>
                                        </tfoot>
                                    </table>
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
<script>
document.getElementById('showZeroToggle').addEventListener('change', function () {
    const form = document.getElementById('trial-balance-filter');
    const params = new URLSearchParams(new FormData(form));
    window.location.href = `?${params.toString()}`;
});
</script>
@endsection
