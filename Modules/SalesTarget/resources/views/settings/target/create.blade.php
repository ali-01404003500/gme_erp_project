@section('title', 'Target Matrix Configuration')
@extends('layout.app')

@section('content')
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-color: #f8fafc;
        }

        .bg-canvas {
            padding: 2rem 1rem;
        }

        .matrix-card {
            background: #ffffff;
            border-radius: 12px;
            border: none;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.05);
            overflow: hidden;
        }

        .header-gradient {
            background: linear-gradient(90deg, #46e5c0, #6366f1);
            color: white;
            padding: 1.25rem 1.5rem;
        }

        /* Modern Table Styling */
        .matrix-table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
        }

        .matrix-table thead th {
            background: #f1f5f9;
            color: #475569;
            font-size: 0.7rem;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            padding: 12px 8px;
            border-bottom: 2px solid #e2e8f0;
            text-align: center;
        }

        .target-row {
            transition: background 0.2s ease;
        }

        .target-row:hover {
            background-color: #fdfdfd;
        }

        .matrix-table td {
            padding: 0;
            border-bottom: 1px solid #f1f5f9;
            border-right: 1px solid #f1f5f9;
        }

        /* Clean Inputs */
        .input-flat {
            border: 2px solid transparent;
            width: 100%;
            height: 45px;
            background: transparent;
            outline: none;
            text-align: center;
            font-size: 0.85rem;
            color: #334155;
            transition: all 0.2s ease;
        }

        .input-flat:focus {
            background: #ffffff;
            border-bottom: 2px solid #6366f1;
            box-shadow: inset 0 -4px 10px rgba(99, 102, 241, 0.05);
        }

        /* Distinct styles for Year and Total */
        .year-input {
            color: #64748b;
            font-weight: 500;
        }

        .row-total {
            background: #eef2ff !important;
            color: #4338ca !important;
            font-weight: 700 !important;
        }

        .emp-select {
            border: none;
            width: 100%;
            height: 45px;
            padding: 0 10px;
            background: transparent;
            font-size: 0.85rem;
            color: #1e293b;
            font-weight: 600;
            appearance: none;
            cursor: pointer;
        }

        /* Column Widths */
        .col-sl {
            width: 45px;
        }

        .col-emp {
            min-width: 200px;
        }

        .col-year {
            width: 80px;
        }

        .col-month {
            min-width: 60px;
        }

        .col-total {
            width: 100px;
        }

        .col-action {
            width: 50px;
        }

        .btn-add {
            background: #ffffff;
            color: #4f46e5;
            border: 1px solid #e2e8f0;
            transition: all 0.2s;
        }

        .btn-add:hover {
            background: #f8fafc;
            transform: translateY(-1px);
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
        }

        .remove-row-btn {
            color: #cbd5e1;
            transition: color 0.2s;
        }

        .remove-row-btn:hover {
            color: #ef4444;
        }
    </style>



            <div class="">
                <div class="header-gradient d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="mb-0 fw-bold">Target Configuration</h5>
                        <small class="opacity-75">Assign monthly sales targets to team members</small>
                    </div>
                    <button type="button" class="btn btn-add btn-sm fw-bold px-3" id="add-row">
                        <i class="bi bi-plus-lg me-1"></i> Add Employee
                    </button>
                </div>

                <form action="{{ route('sales_target.settings.target.store') }}" method="POST">
                    @csrf
                    <div class="table-responsive">
                        <table class="matrix-table" id="target-matrix">
                            <thead>
                                <tr>
                                    <th class="col-sl">#</th>
                                    <th class="col-emp text-start px-3">Team Member</th>
                                    <th class="col-year">Year</th>
                                    @foreach(['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'] as $m)
                                        <th class="col-month">{{ $m }}</th>
                                    @endforeach
                                    <th class="col-total">Annual Total</th>
                                    <th class="col-action"></th>
                                </tr>
                            </thead>
                            <tbody id="matrix-body">
                                <tr class="target-row">
                                    <td class="text-center text-muted fw-bold sl-no">1</td>
                                    <td>
                                        <select name="targets[0][employee_id]" class="emp-select" required>
                                            <option value="">Select Member...</option>
                                            @foreach($employees as $emp)
                                                <option value="{{ $emp->id }}">{{ $emp->name }}</option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td>
                                        <input type="number" name="targets[0][year]" class="input-flat year-input"
                                            value="{{ date('Y') }}">
                                    </td>
                                    @foreach(['jan', 'feb', 'mar', 'apr', 'may', 'jun', 'jul', 'aug', 'sep', 'oct', 'nov', 'dec'] as $m)
                                        <td>
                                            <input type="number" step="0.01" name="targets[0][{{$m}}_target]"
                                                class="input-flat month-input" value="0">
                                        </td>
                                    @endforeach
                                    <td>
                                        <input type="number" name="targets[0][total_target]" class="input-flat row-total"
                                            value="0" readonly>
                                    </td>
                                    <td class="text-center">
                                        <button type="button" class="btn remove-row-btn remove-row">
                                            <i class="bi bi-trash3-fill"></i>
                                        </button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <div class="p-4 border-top d-flex justify-content-between align-items-center">
                        <span class="text-muted small">
                            <i class="bi bi-info-circle me-1"></i> Data is automatically summed in the Annual Total column.
                        </span>
                        <button type="submit" class="btn btn-primary px-5 py-2 fw-bold shadow">
                            <i class="bi bi-check2-circle me-2"></i> Save All Targets
                        </button>
                    </div>
                </form>
            </div>



    <script>
        
        let rowIndex = 1;

        document.getElementById('add-row').addEventListener('click', function () {
            const tbody = document.getElementById('matrix-body');
            const firstRow = tbody.querySelector('.target-row');
            const newRow = firstRow.cloneNode(true);

            newRow.querySelector('select').value = "";
            newRow.querySelectorAll('input').forEach(i => {
                if (!i.readOnly) i.value = i.classList.contains('year-input') ? "{{ date('Y') }}" : "0";
                else i.value = "0";
            });

            newRow.querySelectorAll('[name]').forEach(el => {
                el.name = el.name.replace(/\[\d+\]/, `[${rowIndex}]`);
            });

            tbody.appendChild(newRow);
            updateSL();
            rowIndex++;
        });

        document.addEventListener('click', e => {
            if (e.target.closest('.remove-row')) {
                const rows = document.querySelectorAll('.target-row');
                if (rows.length > 1) {
                    e.target.closest('.target-row').remove();
                    updateSL();
                }
            }
        });

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