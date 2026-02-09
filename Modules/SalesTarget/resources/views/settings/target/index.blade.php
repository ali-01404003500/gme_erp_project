@section('title', 'Target Matrix')
@extends('layout.app')

@section('content')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <style>
        :root {
            --border-color: #e2e8f0;
            --header-bg: #f8fafc;
            --primary-soft: #eff6ff;
        }

        .bg-canvas {
            background: #f1f5f9;
            min-height: 100vh;
            padding: 1.5rem 0;
        }

        /* Responsive Container */
        .matrix-card {
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
            border: 1px solid var(--border-color);
            overflow: hidden;
        }

        /* Fixed Table Layout to Prevent Scrolling */
        .matrix-table {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed;
            /* Forces table to stay within container width */
        }

        .matrix-table th,
        .matrix-table td {
            border: 1px solid var(--border-color);
            padding: 6px 2px;
            vertical-align: middle;
            font-size: 0.75rem;
            word-wrap: break-word;
        }

        .matrix-table thead th {
            background: var(--header-bg);
            font-weight: 700;
            text-align: center;
            text-transform: uppercase;
            font-size: 0.65rem;
            color: #64748b;
            height: 45px;
        }

        /* Column Width Optimization */
        .col-sl {
            width: 30px;
        }

        .col-emp {
            width: 14%;
        }

        .col-year {
            width: 55px;
        }

        .col-month {
            width: 4.8%;
        }

        /* Distribute 12 months across ~58% of width */
        .col-total {
            width: 9%;
        }

        .col-action {
            width: 45px;
        }

        /* Interaction Styles */
        .row-hover:hover {
            background-color: var(--primary-soft);
            transition: 0.2s;
        }

        .text-amount {
            font-family: 'Inter', sans-serif;
            text-align: right;
        }

        .search-input {
            border-radius: 20px;
            padding-left: 40px;
            font-size: 0.85rem;
            border: 1px solid #cbd5e1;
        }

        .search-icon {
            position: absolute;
            left: 15px;
            top: 10px;
            color: #94a3b8;
        }
    </style>

    <div class="bg-canvas">
        <div class="matrix-card">
            <div class="p-3 border-bottom d-flex justify-content-between align-items-center bg-white">
                <h6 class="fw-bold mb-0 text-dark"><i class="bi bi-table me-2 text-primary"></i>Target Summary Registry
                </h6>
                <div class="position-relative" style="width: 300px;">
                    <i class="bi bi-search search-icon"></i>
                    <input type="text" id="empSearch" class="form-control search-input"
                        placeholder="Quick search employee...">
                </div>
            </div>

            <div class="table-responsive-none">
                <table class="matrix-table" id="registryTable">
                    <thead>
                        <tr>
                            <th class="col-sl">#</th>
                            <th class="col-emp">Employee</th>
                            <th class="col-year">Year</th>
                            {{-- Abbreviated Months to save space --}}
                            @foreach(['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'] as $m)
                                <th class="col-month">{{ substr($m, 0, 1) }}</th>
                            @endforeach
                            <th class="col-total">Total Target</th>
                            <th class="col-action">Action</th>
                        </tr>
                    </thead>
                    <tbody class="text-center">
                        @forelse($targets as $index => $target)
                            <tr class="row-hover">
                                <td class="text-center text-muted">{{ $index + 1 }}</td>
                                <td class="fw-bold px-2">{{ $target->employee->name ?? 'N/A' }}</td>
                                <td class="text-center">{{ $target->year }}</td>

                                {{-- Compact Month Display --}}
                                <td class="text-amount">{{ number_format($target->jan_target, 0) }}</td>
                                <td class="text-amount">{{ number_format($target->feb_target, 0) }}</td>
                                <td class="text-amount">{{ number_format($target->mar_target, 0) }}</td>
                                <td class="text-amount">{{ number_format($target->apr_target, 0) }}</td>
                                <td class="text-amount">{{ number_format($target->may_target, 0) }}</td>
                                <td class="text-amount">{{ number_format($target->jun_target, 0) }}</td>
                                <td class="text-amount">{{ number_format($target->jul_target, 0) }}</td>
                                <td class="text-amount">{{ number_format($target->aug_target, 0) }}</td>
                                <td class="text-amount">{{ number_format($target->sep_target, 0) }}</td>
                                <td class="text-amount">{{ number_format($target->oct_target, 0) }}</td>
                                <td class="text-amount">{{ number_format($target->nov_target, 0) }}</td>
                                <td class="text-amount">{{ number_format($target->dec_target, 0) }}</td>

                                <td class="text-amount fw-bold text-center" style="color:rgb(255, 1, 1)">
                                    ৳{{ number_format($target->total_target, 0) }}
                                </td>
                                <td class="text-center">
                                    <div class="d-flex justify-content-center gap-2">

                                        <a href="{{ route('sales_target.settings.target.edit', ['target' => $target->id ?? $target->target_id]) }}"
                                            class="btn text-primary p-0 border-0">
                                            <i class="bi bi-pencil-fill" style="font-size: 1.1rem;"></i>
                                        </a>
                                        <form
                                            action="{{ route('sales_target.settings.target.destroy', ['target' => $target->id ?? $target->target_id]) }}"
                                            method="POST" class="m-0" onsubmit="return confirm('Delete this target?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn text-danger p-0 border-0">
                                                <i class="bi bi-trash3-fill" style="font-size: 1.1rem;"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="17" class="text-center py-5 text-muted">No records found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>



    <script>
        document.getElementById('empSearch').addEventListener('keyup', function () {
            let val = this.value.toLowerCase();
            let rows = document.querySelectorAll("#registryTable tbody tr");
            rows.forEach(row => {
                let text = row.cells[1].textContent.toLowerCase();
                row.style.display = text.includes(val) ? "" : "none";
            });
        });
    </script>
@endsection