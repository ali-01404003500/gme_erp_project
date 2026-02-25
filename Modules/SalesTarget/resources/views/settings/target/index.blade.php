@section('title', 'Target Matrix')
@extends('layout.app')

@section('content')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        :root {
            --border-color: #dee2e6;
            --text-primary: #212529;
            --text-secondary: #495057;
            --bg-hover: #f8f9fa;
            --white: #ffffff;
            --header-bg: #f1f3f5;
        }

        body {
            background: #e9ecef;
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', sans-serif;
        }

        .matrix-wrapper {
            padding: 1rem;
            max-width: 100%;
            margin: 0;
        }

        .matrix-container {
            background: var(--white);
            border-radius: 8px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
            border: 1px solid var(--border-color);
        }

        /* Header - Compact */
        .matrix-header {
            padding: 0.75rem 1rem;
            border-bottom: 1px solid var(--border-color);
            display: flex;
            justify-content: space-between;
            align-items: center;
            background: var(--white);
        }

        .matrix-title {
            font-size: 0.95rem;
            font-weight: 600;
            color: var(--text-primary);
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .matrix-title i {
            color: var(--text-secondary);
            font-size: 1rem;
        }

        .search-wrapper {
            position: relative;
            width: 200px;
        }

        .search-icon {
            position: absolute;
            left: 8px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--text-secondary);
            font-size: 0.8rem;
        }

        .search-input {
            width: 100%;
            padding: 0.4rem 0.75rem 0.4rem 2rem;
            border: 1px solid var(--border-color);
            border-radius: 4px;
            font-size: 0.8rem;
            background: var(--white);
        }

        .search-input:focus {
            outline: none;
            border-color: #868e96;
        }

       
        .matrix-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 0.75rem;
            table-layout: fixed;
        }

       
        .matrix-table th,
        .matrix-table td {
            border: 1px solid var(--border-color);
            padding: 0.5rem 0.25rem;
            vertical-align: middle;
        }

        .matrix-table th {
            background: var(--header-bg);
            color: var(--text-secondary);
            font-weight: 600;
            font-size: 0.65rem;
            text-transform: uppercase;
            letter-spacing: 0.2px;
            text-align: center;
            white-space: nowrap;
        }

        .matrix-table td {
            color: var(--text-primary);
            text-align: center;
        }

        .matrix-table tbody tr:hover {
            background: var(--bg-hover);
        }

       
        .col-sl {
            width: 25px;
        }

        .col-employee {
            width: 130px;
            text-align: left;
        }

        .col-year {
            width: 35px;
        }

        .col-month {
            width: 45px;
           
        }

        .col-total {
            width: 60px;
        }

        .col-action {
            width: 50px;
        }

       
        .employee-info {
            display: flex;
            flex-direction: column;
            gap: 2px;
        }

        .employee-name {
            font-weight: 600;
            color: var(--text-primary);
            font-size: 0.7rem;
            display: block;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .employee-id {
            font-size: 0.6rem;
            color: var(--text-secondary);
            display: block;
        }

        .employee-dept {
            display: none;
          
        }

      
        .month-cell {
            display: flex;
            flex-direction: column;
            gap: 2px;
            align-items: center;
        }

        .month-value {
            font-family: 'SF Mono', 'Menlo', 'Monaco', 'Consolas', monospace;
            font-size: 0.65rem;
            font-weight: 500;
            color: var(--text-primary);
            display: block;
            white-space: nowrap;
        }

        .month-label {
            display: none;
            
        }

        .total-cell {
            display: flex;
            flex-direction: column;
            gap: 2px;
            align-items: center;
        }

        .total-value {
            font-family: 'SF Mono', 'Menlo', 'Monaco', 'Consolas', monospace;
            font-size: 0.7rem;
            font-weight: 700;
            color: #0b5e42;
            display: block;
            white-space: nowrap;
        }

        .total-label {
            font-size: 0.55rem;
            color: var(--text-secondary);
            text-transform: uppercase;
            letter-spacing: 0.2px;
        }

      
        .action-group {
            display: flex;
            gap: 0.25rem;
            justify-content: center;
            align-items: center;
        }

        .action-btn {
            width: 22px;
            height: 22px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            background: transparent;
            border: 1px solid var(--border-color);
            border-radius: 3px;
            color: var(--text-secondary);
            cursor: pointer;
            transition: all 0.2s;
            font-size: 0.7rem;
            text-decoration: none;
        }

        .action-btn:hover {
            background: var(--bg-hover);
        }

        .action-btn.edit:hover {
            color: #0d6efd;
            border-color: #0d6efd;
        }

        .action-btn.delete:hover {
            color: #dc3545;
            border-color: #dc3545;
        }

     
        .empty-state {
            padding: 2rem;
            text-align: center;
            color: var(--text-secondary);
            font-size: 0.8rem;
        }

        .empty-state i {
            font-size: 2rem;
            color: #ced4da;
            margin-bottom: 0.5rem;
        }

        .table-footer {
            padding: 0.5rem 1rem;
            border-top: 1px solid var(--border-color);
            background: var(--header-bg);
            font-size: 0.7rem;
            color: var(--text-secondary);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .table-footer i {
            font-size: 0.7rem;
            margin-right: 0.25rem;
        }

 
        .amount-positive {
            color: #0b5e42;
        }

        .amount-negative {
            color: #a61e4d;
        }


        div[style*="overflow-x: auto"] {
            overflow-x: visible !important;
        }

        .btn-create-matrix {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            background-color: #4f46e5;

            color: white;
            padding: 0.45rem 1rem;
            border-radius: 6px;
            font-size: 0.8rem;
            font-weight: 600;
            text-decoration: none;
            transition: all 0.2s ease;
            border: none;
            white-space: nowrap;
        }

        .btn-create-matrix:hover {
            background-color: #4338ca;
            color: white;
            transform: translateY(-1px);
            box-shadow: 0 4px 6px rgba(79, 70, 229, 0.15);
        }

        .btn-create-matrix i {
            font-size: 0.9rem;
        }


        .matrix-header .d-flex {
            display: flex;
            align-items: center;
            gap: 12px;
        }
    </style>

    <div class="matrix-wrapper">
        <div class="matrix-container">

            <div class="matrix-header">
                <div class="matrix-title">
                    <i class="bi bi-grid-3x3-gap-fill"></i>
                    Target Matrix
                </div>

                <div class="d-flex align-items-center gap-2">
                    <div class="search-wrapper">
                        <i class="bi bi-search search-icon"></i>
                        <input type="text" id="empSearch" class="search-input" placeholder="Search...">
                    </div>

                    <a href="{{ route('sales_target.settings.target.create') }}" class="btn-create-matrix">
                        <i class="bi bi-plus-lg"></i>
                        <span>Create Targets</span>
                    </a>
                </div>
            </div>


            <div style="overflow: visible;">
                <table class="matrix-table">
                    <thead>
                        <tr>
                            <th class="col-sl">#</th>
                            <th class="col-employee">Employee</th>
                            <th class="col-year">Yr</th>
                            <th class="col-month">Jan</th>
                            <th class="col-month">Feb</th>
                            <th class="col-month">Mar</th>
                            <th class="col-month">Apr</th>
                            <th class="col-month">May</th>
                            <th class="col-month">Jun</th>
                            <th class="col-month">Jul</th>
                            <th class="col-month">Aug</th>
                            <th class="col-month">Sep</th>
                            <th class="col-month">Oct</th>
                            <th class="col-month">Nov</th>
                            <th class="col-month">Dec</th>
                            <th class="col-total">Total</th>
                            <th class="col-action">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($targets as $index => $target)
                            <tr>
                                <td class="col-sl">{{ $index + 1 }}</td>
                                <td class="col-employee">
                                    <div class="employee-info">
                                        <span class="employee-name" title="{{ $target->employee->full_name ?? 'N/A' }}">

                                            {{ Str::limit($target->employee->full_name ?? 'N/A', 20) }}
                                        </span>
                                    </div>
                                </td>
                                <td class="col-year">{{ substr($target->year, -2) }}</td>


                                <td class="col-month">
                                    <span
                                        class="month-value @if($target->jan_target > 0) amount-positive @else amount-negative @endif">
                                        {{ number_format($target->jan_target) }}
                                    </span>
                                </td>
                                <td class="col-month">
                                    <span
                                        class="month-value @if($target->feb_target > 0) amount-positive @else amount-negative @endif">
                                        {{ number_format($target->feb_target) }}
                                    </span>
                                </td>
                                <td class="col-month">
                                    <span
                                        class="month-value @if($target->mar_target > 0) amount-positive @else amount-negative @endif">
                                        {{ number_format($target->mar_target) }}
                                    </span>
                                </td>
                                <td class="col-month">
                                    <span
                                        class="month-value @if($target->apr_target > 0) amount-positive @else amount-negative @endif">
                                        {{ number_format($target->apr_target) }}
                                    </span>
                                </td>
                                <td class="col-month">
                                    <span
                                        class="month-value @if($target->may_target > 0) amount-positive @else amount-negative @endif">
                                        {{ number_format($target->may_target) }}
                                    </span>
                                </td>
                                <td class="col-month">
                                    <span
                                        class="month-value @if($target->jun_target > 0) amount-positive @else amount-negative @endif">
                                        {{ number_format($target->jun_target) }}
                                    </span>
                                </td>
                                <td class="col-month">
                                    <span
                                        class="month-value @if($target->jul_target > 0) amount-positive @else amount-negative @endif">
                                        {{ number_format($target->jul_target) }}
                                    </span>
                                </td>
                                <td class="col-month">
                                    <span
                                        class="month-value @if($target->aug_target > 0) amount-positive @else amount-negative @endif">
                                        {{ number_format($target->aug_target) }}
                                    </span>
                                </td>
                                <td class="col-month">
                                    <span
                                        class="month-value @if($target->sep_target > 0) amount-positive @else amount-negative @endif">
                                        {{ number_format($target->sep_target) }}
                                    </span>
                                </td>
                                <td class="col-month">
                                    <span
                                        class="month-value @if($target->oct_target > 0) amount-positive @else amount-negative @endif">
                                        {{ number_format($target->oct_target) }}
                                    </span>
                                </td>
                                <td class="col-month">
                                    <span
                                        class="month-value @if($target->nov_target > 0) amount-positive @else amount-negative @endif">
                                        {{ number_format($target->nov_target) }}
                                    </span>
                                </td>
                                <td class="col-month">
                                    <span
                                        class="month-value @if($target->dec_target > 0) amount-positive @else amount-negative @endif">
                                        {{ number_format($target->dec_target) }}
                                    </span>
                                </td>

                                <!-- Total -->
                                <td class="col-total">
                                    <div class="total-cell">
                                        <span class="total-value">৳{{ number_format($target->total_target) }}</span>
                                    </div>
                                </td>

                                <!-- Actions -->
                                <td class="col-action">
                                    <div class="action-group">
                                        <a href="{{ route('sales_target.settings.target.edit', ['target' => $target->id ?? $target->target_id]) }}"
                                            class="action-btn edit" title="Edit">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <form
                                            action="{{ route('sales_target.settings.target.destroy', ['target' => $target->id ?? $target->target_id]) }}"
                                            method="POST" style="display: inline;" onsubmit="return confirm('Delete?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="action-btn delete" title="Delete">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="18" class="empty-state">
                                    <i class="bi bi-inbox"></i>
                                    <div>No records found</div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Footer -->
            @if(count($targets) > 0)
                <div class="table-footer">
                    <div><i class="bi bi-people"></i> {{ count($targets) }}</div>
                    <div><i class="bi bi-calendar"></i> 12 months</div>
                    <div><i class="bi bi-currency-exchange"></i> ৳</div>
                </div>
            @endif
        </div>
    </div>

    <script>
        document.getElementById('empSearch').addEventListener('keyup', function () {
            const searchTerm = this.value.toLowerCase().trim();
            const rows = document.querySelectorAll('tbody tr');

            rows.forEach(row => {
                if (row.querySelector('.empty-state')) return;

                const nameElement = row.querySelector('.employee-name');
                if (nameElement) {
                    const name = nameElement.textContent.toLowerCase();
                    row.style.display = name.includes(searchTerm) ? '' : 'none';
                }
            });
        });
    </script>
@endsection