@section('title', 'Achievement Target Summary')
@extends('layout.app')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <div class="breadcrumb-main">
                    <h4 class="text-capitalize breadcrumb-title">Achievement Target Summary</h4>
                </div>
            </div>
        </div>

        <div class="card mb-3 border-0 shadow-sm">
            <div class="card-body p-3">
                <form action="{{ route('sales_target.perfomence.achievement') }}" method="GET" id="filterForm">
                    <div class="row align-items-end">
                        <div class="col-md-3">
                            <label class="fs-13 fw-500 mb-1">Filter by Employee</label>
                            <select class="form-control tom-select" name="user_ref_id" onchange="this.form.submit()">
                                <option value="">--- ALL EMPLOYEES ---</option>
                                @foreach ($employees as $employee)
                                    <option value="{{ $employee->id }}" {{ request('user_ref_id') == $employee->id ? 'selected' : '' }}>
                                        {{ $employee->display_name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="fs-13 fw-500 mb-1">From Date</label>
                            <input type="date" name="start_date" class="form-control"
                                value="{{ request('start_date', date('Y-m-01')) }}" onchange="this.form.submit()">
                        </div>
                        <div class="col-md-3">
                            <label class="fs-13 fw-500 mb-1">To Date</label>
                            <input type="date" name="end_date" class="form-control"
                                value="{{ request('end_date', date('Y-m-t')) }}" onchange="this.form.submit()">
                        </div>
                        <div class="col-md-3 text-end">
                            <button type="button" onclick="window.print()" class="btn btn-light border btn-sm">
                                <i class="bi bi-printer me-1"></i> Print Report
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <div class="table-responsive bg-white rounded shadow-sm">
            <table class="table table-bordered table-sm text-center align-middle custom-zebra"
                style="font-size: 11px; min-width: 1200px;">
                <thead class="bg-light">
                    <tr class="text-uppercase">
                        <th rowspan="2" class="align-middle">SL</th>
                        <th rowspan="2" class="align-middle text-left" style="min-width: 180px;">Employee Info</th>
                        <th colspan="6" class="bg-primary-transparent border-bottom-0">Sales Target & Achievement</th>
                        <th rowspan="2" class="align-middle">Salary Expense</th>
                        <th colspan="4" class="bg-secondary-transparent border-bottom-0">Operational Expenses</th>
                        <th colspan="2" class="bg-light-transparent border-bottom-0">Summary</th>
                    </tr>
                    <tr class="text-uppercase" style="font-size: 9px;">
                        <th class="bg-primary-transparent">Target</th>
                        <th class="bg-primary-transparent">Achieved</th>
                        <th class="bg-primary-transparent">Costing</th>
                        <th class="bg-primary-transparent">Collection</th>
                        <th class="bg-primary-transparent">Due</th>
                        <th class="bg-primary-transparent">%</th>
                        <th class="bg-secondary-transparent">TA</th>
                        <th class="bg-secondary-transparent">DA</th>
                        <th class="bg-secondary-transparent">Comm.</th>
                        <th class="bg-secondary-transparent">Entert.</th>
                        <th class="bg-light-transparent">Excl. Sal</th>
                        <th class="bg-light-transparent">Incl. Sal</th>
                    </tr>
                </thead>
                <tbody>
                    @php $sl = 1; @endphp
                    @forelse($results as $data)
                        <tr>
                            <td>{{ $sl++ }}</td>
                            <td class="text-left">
                                <div class="d-flex flex-column">
                                    <span class="fw-bold text-dark text-uppercase"
                                        style="letter-spacing: 0.3px;">{{ $data['name'] }}</span>
                                    <span class="text-primary fw-500" style="font-size: 9px; margin-top: 2px;">
                                        <i class="bi bi-briefcase me-1"></i>{{ $data['designation'] }}
                                    </span>
                                </div>
                            </td>
                            <td class="fw-bold">৳{{ number_format($data['target'], 0) }}</td>
                            <td class="text-primary">৳{{ number_format($data['achieved'], 0) }}</td>
                            <td>৳{{ number_format($data['costing'], 0) }}</td>
                            <td class="text-success">৳{{ number_format($data['collection'], 0) }}</td>
                            <td class="text-danger">৳{{ number_format($data['due'], 0) }}</td>
                            <td>
                                <span
                                    class="badge {{ $data['percent'] >= 80 ? 'bg-success' : ($data['percent'] >= 50 ? 'bg-warning' : 'bg-danger') }} rounded-pill"
                                    style="font-size: 9px;">
                                    {{ number_format($data['percent'], 1) }}%
                                </span>
                            </td>
                            <td>৳{{ number_format($data['salary_expense'], 0) }}</td>
                            <td>৳{{ number_format($data['ta_expense'], 0) }}</td>
                            <td>৳{{ number_format($data['da_expense'], 0) }}</td>
                            <td>৳{{ number_format($data['commission'], 0) }}</td>
                            <td>৳{{ number_format($data['entertainment'], 0) }}</td>
                            <td class="fw-bold">৳{{ number_format($data['total_excl_salary'], 0) }}</td>
                            <td class="fw-bold text-dark" style="background: #f8f9ff;">
                                ৳{{ number_format($data['total_incl_salary'], 0) }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="15" class="py-5">
                                <div class="text-center">
                                    <i class="bi bi-person-x fs-2 text-muted"></i>
                                    <p class="mt-2 text-muted">No employees found with targets for the year
                                        {{ request('year', date('Y')) }}.
                                    </p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <style>
        .table-sm th,
        .table-sm td {
            padding: 10px 6px !important;
            vertical-align: middle;
            border-color: #f1f1f1 !important;
        }

        .bg-primary-transparent {
            background-color: rgba(0, 123, 255, 0.03);
        }

        .bg-secondary-transparent {
            background-color: rgba(108, 117, 125, 0.03);
        }

        .bg-light-transparent {
            background-color: rgba(0, 0, 0, 0.01);
        }

        .text-left {
            text-align: left !important;
        }

        .custom-zebra tbody tr:nth-of-type(odd) {
            background-color: rgba(0, 0, 0, .01);
        }

        .fw-500 {
            font-weight: 500;
        }

        /* Highlight the 'All' option if needed */
        .ts-dropdown .option[data-value=""] {
            font-weight: bold;
            color: #4f46e5;
            border-bottom: 1px dashed #ddd;
        }

        @media print {

            .card,
            .breadcrumb-main,
            .tom-select,
            .btn-light {
                display: none !important;
            }

            body {
                background: white !important;
            }

            .container-fluid {
                padding: 0 !important;
            }

            .table {
                font-size: 8px !important;
                width: 100% !important;
                border: 1px solid #ddd !important;
            }
        }
    </style>

    <script>
        // Ensure Tom Select handles the empty value as 'All' correctly
        document.querySelectorAll('.tom-select').forEach((el) => {
            if (el.tomselect) return; // prevent double init
            new TomSelect(el, {
                create: false,
                sortField: { field: "text", direction: "asc" },
                allowEmptyOption: true,
            });
        });
    </script>
@endsection