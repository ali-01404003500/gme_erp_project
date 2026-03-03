@section('title', 'Edit Target Matrix')
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
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.05);
            overflow: hidden;
        }

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
            padding: 12px 8px;
            border-bottom: 2px solid #e2e8f0;
        }

        .matrix-table td {
            border-bottom: 1px solid #f1f5f9;
            padding: 0;
        }

        .input-flat {
            border: 2px solid transparent;
            width: 100%;
            height: 45px;
            background: transparent;
            outline: none;
            text-align: center;
            font-size: 0.85rem;
            transition: all 0.2s ease;
        }

        .input-flat:focus {
            background: #fff;
            border-bottom: 2px solid #6366f1;
        }

        .row-total {
            background: #f0fdf4 !important;
            color: #166534 !important;
            font-weight: 700;
        }

        .emp-name-static {
            font-size: 0.85rem;
            font-weight: 600;
            color: #1e293b;
            padding-left: 15px;
        }

        .badge-edit {
            background: rgba(255, 255, 255, 0.1);
            padding: 5px 12px;
            border-radius: 6px;
            font-size: 0.75rem;
        }
    </style>

    <div class="bg-canvas">
        <div class="container-fluid">
            <div class="matrix-card">
                <div class="header-gradient d-flex justify-content-between align-items-center p-3">
                    <div class="p-3">
                        <h5 class="mb-0 fw-bold">Update Targets</h5>
                        <span class="badge-edit mt-1 d-inline-block">Edit Mode: {{ $target->employee->full_name }}</span>
                    </div>
                    <a href="{{ route('sales_target.settings.target.index') }}" class="btn btn-sm btn-outline-light">
                        <i class="bi bi-arrow-left me-1"></i> Back to List
                    </a>
                </div>

                {{-- Use the target_id if id is missing --}}
                <form action="{{ route('sales_target.settings.target.update', $target->id ?? $target->target_id) }}"
                    method="POST">
                    @csrf
                    @method('PUT')

                    <div class="table-responsive">
                        <table class="matrix-table">
                            <thead>
                                <tr class="text-center">
                                    <th class="text-start">Employee</th>
                                    <th>Year</th>
                                    @foreach(['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'] as $m)
                                        <th>{{ $m }}</th>
                                    @endforeach
                                    <th>Annual Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr class="target-row">
                                    <td style="min-width: 200px;">
                                        <div class="emp-name-static">{{ $target->employee->full_name }}</div>
                                        <input type="hidden" name="employee_id" value="{{ $target->employee_id }}">
                                    </td>
                                    <td style="width: 80px;">
                                        <input type="number" name="year" class="input-flat text-muted"
                                            value="{{ $target->year }}" readonly>
                                    </td>

                                    @foreach(['jan', 'feb', 'mar', 'apr', 'may', 'jun', 'jul', 'aug', 'sep', 'oct', 'nov', 'dec'] as $m)
                                        @php $field = $m . '_target'; @endphp
                                        <td>
                                            <input type="number" step="1000" name="{{ $field }}" class="input-flat month-input"
                                                value="{{ $target->$field }}">
                                        </td>
                                    @endforeach

                                    <td style="width: 110px;">
                                        <input type="number" name="total_target" class="input-flat row-total"
                                            value="{{ $target->total_target }}" readonly>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <div class="p-4 border-top d-flex justify-content-end">
                        <button type="submit" class="btn btn-success px-5 py-2 fw-bold shadow">
                            <i class="bi bi-check-circle-fill me-2"></i> Save Changes
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('input', e => {
            if (e.target.classList.contains('month-input')) {
                const row = e.target.closest('.target-row');
                let sum = 0;
                row.querySelectorAll('.month-input').forEach(i => {
                    sum += parseFloat(i.value) || 0;
                });
                row.querySelector('.row-total').value = sum.toFixed(2);
            }
        });
    </script>
@endsection