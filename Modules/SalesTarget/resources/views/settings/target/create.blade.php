@section('title', 'Target Matrix Configuration')
@extends('layout.app')

@section('content')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <style>
        :root {
            --matrix-border: #000;
            --input-focus: #fffde7;
        }

        .bg-canvas {
            background: #f1f5f9;
            min-height: 100vh;
            padding: 1.5rem 0;
        }

        .matrix-card {
            background: #fff;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            border: 1px solid #cbd5e1;
        }

        /* Strict Fixed Layout Logic */
        .matrix-table {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed;
            border: 2px solid var(--matrix-border);
        }

        .matrix-table th,
        .matrix-table td {
            border: 1px solid var(--matrix-border);
            padding: 4px 1px;
            vertical-align: middle;
        }

        .matrix-table thead th {
            background: #f8fafc;
            font-weight: 800;
            text-align: center;
            text-transform: uppercase;
            font-size: 0.65rem;
            color: #1e293b;
            height: 38px;
        }

        /* Exact Column Width Distribution (Total 100%) */
        .col-sl {
            width: 3%;
        }

        .col-emp {
            width: 17%;
        }

        .col-year {
            width: 6%;
        }

        .col-month {
            width: 5.3%;
        }

        /* 12 months at ~5.3% each */
        .col-total {
            width: 9%;
        }

        .col-action {
            width: 40px;
        }

        /* Modern Flat Inputs */
        .input-flat {
            border: none;
            width: 100%;
            background: transparent;
            outline: none;
            text-align: center;
            font-weight: 600;
            font-size: 0.8rem;
        }

        .input-flat:focus {
            background: var(--input-focus);
        }

        .emp-select {
            border: none;
            width: 100%;
            background: transparent;
            font-size: 0.75rem;
            cursor: pointer;
        }

        .row-total {
            background: #f1f5f9;
            color: #4338ca !important;
        }
    </style>

    <div class="bg-canvas">
        <div class="container-fluid px-3">
            <div class="matrix-card">
                <div class="p-3 d-flex justify-content-between align-items-center bg-white border-bottom">
                    <h6 class="fw-bold mb-0 text-dark">
                        <i class="bi bi-grid-3x3-gap-fill me-2 text-primary"></i>Target Configuration Matrix
                    </h6>
                    <button type="button" class="btn btn-success btn-sm fw-bold px-3 shadow-sm" id="add-row">
                        <i class="bi bi-plus-lg me-1"></i> Add New Row
                    </button>
                </div>

                <form action="{{ route('sales_target.settings.target.store') }}" method="POST">
                    @csrf
                    <div class="table-responsive-none">
                        <table class="matrix-table" id="target-matrix">
                            <thead>
                                <tr>
                                    <th class="col-sl">#</th>
                                    <th class="col-emp">Employee Name</th>
                                    <th class="col-year">Year</th>
                                    @foreach(['J', 'F', 'M', 'A', 'M', 'J', 'J', 'A', 'S', 'O', 'N', 'D'] as $m)
                                        <th class="col-month">{{ $m }}</th>
                                    @endforeach
                                    <th class="col-total">Total</th>
                                    <th class="col-action"></th>
                                </tr>
                            </thead>
                            <tbody id="matrix-body">
                                <tr class="target-row">
                                    <td class="text-center fw-bold sl-no">1</td>
                                    <td class="px-1">
                                        <select name="targets[0][employee_id]" class="emp-select" required>
                                            <option value="">Select Employee...</option>
                                            @foreach($employees as $emp)
                                                <option value="{{ $emp->id }}">{{ $emp->name }}</option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td><input type="number" name="targets[0][year]" class="input-flat"
                                            value="{{ date('Y') }}"></td>
                                    @foreach(['jan', 'feb', 'mar', 'apr', 'may', 'jun', 'jul', 'aug', 'sep', 'oct', 'nov', 'dec'] as $m)
                                        <td><input type="number" step="0.01" name="targets[0][{{$m}}_target]"
                                                class="input-flat month-input" value="0"></td>
                                    @endforeach
                                    <td><input type="number" name="targets[0][total_target]" class="input-flat row-total"
                                            value="0" readonly></td>
                                    <td class="text-center">
                                        <button type="button" class="btn btn-link text-danger p-0 remove-row">
                                            <i class="bi bi-x-circle-fill fs-5"></i>
                                        </button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="card-footer bg-white border-top-0 py-3 text-end">
                        <button type="submit" class="btn btn-primary px-5 fw-bold shadow-sm">
                            <i class="bi bi-cloud-check-fill me-2"></i>Save All Targets
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        let rowIndex = 1;

        // Add Row logic
        document.getElementById('add-row').addEventListener('click', function () {
            const tbody = document.getElementById('matrix-body');
            const firstRow = tbody.querySelector('.target-row');
            const newRow = firstRow.cloneNode(true);

            newRow.querySelector('select').value = "";
            newRow.querySelectorAll('input').forEach(i => {
                if (!i.readOnly) i.value = i.name.includes('year') ? "{{ date('Y') }}" : "0";
                else i.value = "0";
            });

            newRow.querySelectorAll('[name]').forEach(el => {
                el.name = el.name.replace(/\[\d+\]/, `[${rowIndex}]`);
            });

            tbody.appendChild(newRow);
            updateSL();
            rowIndex++;
        });

        // Remove Row logic
        document.addEventListener('click', e => {
            if (e.target.closest('.remove-row')) {
                const rows = document.querySelectorAll('.target-row');
                if (rows.length > 1) { e.target.closest('.target-row').remove(); updateSL(); }
            }
        });

        // Auto-Calculate logic
        document.addEventListener('input', e => {
            if (e.target.classList.contains('month-input')) {
                const row = e.target.closest('.target-row');
                let sum = 0;
                row.querySelectorAll('.month-input').forEach(i => sum += parseFloat(i.value) || 0);
                row.querySelector('.row-total').value = sum.toFixed(2);
            }
        });

        function updateSL() {
            document.querySelectorAll('.sl-no').forEach((el, i) => el.innerText = i + 1);
        }
    </script>
@endsection