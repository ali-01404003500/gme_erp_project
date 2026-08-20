 
@section('title', 'Sales Target')
@section('description', 'Sales Target')
@extends('layout.app')
@section('content')
    <div class="container-fluid">
        <div class="social-dash-wrap">
            <div class="row">
                <div class="col-lg-12">
                    <div class="breadcrumb-main">
                        <div class="breadcrumb-action justify-content-center flex-wrap">
                            <nav aria-label="breadcrumb">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="#"><i class="las la-home"></i>Home</a></li>
                                    <li class="breadcrumb-item active" aria-current="page">Sales Target</li>
                                </ol>
                            </nav>
                        </div>
                        <div class="action-btn mt-sm-0 mt-15">
                            
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12 m-2">
                    <h4 class="text-capitalize breadcrumb-title">Sales Target </h4>
                    <x-error-alart />
                    
                </div>

                {{-- Assign Target Form --}}
                <div class="card mb-50">
                    <div class="row justify-content-center" id="justify-content-center">
                        <div class="col-sm-12">
                            <div class="mt-40 mb-50 p-30">
                                <div class="card-body">
                                    <h5>Assign New Target</h5>
                                    <form action="{{ route('sales_target.sales-targets.store') }}" method="POST">
                                        @csrf
                                        <div class="row g-2">
                                            <div class="col-md-3">
                                                <label>Employee</label>
                                                <select name="employee_id" class="form-control tom-select" required>
                                                    <option value="">-- Select Employee --</option>
                                                    @foreach($employees as $employee)
                                                        <option value="{{ $employee->id }}">{{ $employee->full_name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="col-md-2">
                                                <label>Salary Basis</label>
                                                <select name="salary_basis" class="form-control" required>
                                                    <option value="basic">Basic Only</option>
                                                    <option value="gross">Gross Only</option>
                                                    <option value="allexpenses">All Expeneses(Gross+TA/DA)</option>
                                                </select>
                                            </div>
                                            <div class="col-md-2">
                                                <label>Month</label>
                                                <select name="period_month" class="form-control" required>
                                                    @foreach(range(1, 12) as $m)
                                                        <option value="{{ $m }}" {{ $m == now()->month ? 'selected' : '' }}>
                                                            {{ \Carbon\Carbon::create()->month($m)->format('F') }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="col-md-2">
                                                <label>Year</label>
                                                <input type="number" name="period_year" class="form-control" value="{{ now()->year }}" required>
                                            </div>
                                            <div class="col-md-3 d-flex align-items-end pt-3">
                                                @if (hasPermission('sales_target.sales-targets.create')) 
                                                    <button type="submit" class="btn btn-primary w-100" onclick="return confirmSubmit(event, this, 'Assign');">Assign</button>
                                                @endif
                                            </div>
                                        </div>
                                        <small class="text-muted">Salary will be auto-fetched from the employee record.</small>
                                    </form>

                                    {{-- Targets List --}}
                                    <div class="table-responsive mt-5">
                                        <table class="table table-bordered table-sm align-middle">
                                            <thead>
                                                <tr>
                                                    <th>Employee</th>
                                                    <th>Basis</th>
                                                    <th>Salary & Expenses</th>
                                                    <th>Slab</th>
                                                    <th>Target</th>
                                                    <th>Achieved</th>
                                                    <th>Achv %</th>
                                                    <th>Rate</th>
                                                    <th>Raw Incentive</th>
                                                    <th>Payout %</th>
                                                    <th>Final Incentive</th>
                                                    <th>Status</th>
                                                    <th>Period</th>
                                                    <th>Lock</th>
                                                    <th>Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @forelse($targets as $target) 
                                                
                                                <tr>
                                                    <td>
                                                        {{ $target->employee->full_name ?? '-' }}<br>
                                                        <small class="text-muted">{{ $target->employee->employementDetail->designation->name ?? 'N/A' }}</small><br>
                                                        <small class="text-muted">{{ $target->employee->employementDetail->department->name ?? 'N/A' }}</small><br>
                                                        <small class="text-muted">{{ $target->employee->employementDetail->branch->name ?? 'N/A' }}</small> 

                                                    </td>
                                                    <td>{{ ucfirst($target->salary_basis) }}</td>
                                                    <td>
                                                        @if($target->salary_basis === 'allexpenses')
                                                            {{ number_format($target->all_expenses_salary_at_assign) }}
                                                        @elseif($target->salary_basis === 'gross')
                                                            {{ number_format($target->gross_salary_at_assign) }}
                                                        @else
                                                            {{ number_format($target->salary_at_assign) }}
                                                        @endif
                                                        <small class="text-muted d-block">TA/DA: {{ number_format($target->ta_da_at_assign) }}</small>
                                                    </td>
                                                    <td>{{ $target->slab->name ?? '-' }}</td>
                                                    <td>{{ number_format($target->target_amount) }}</td>
                                                    <td>{{ number_format($target->achieved_amount) }}</td>
                                                    <td>{{ $target->achievement_percent }}%</td>
                                                    <td>{{ $target->incentive_rate_applied ?? '-' }}%</td>
                                                    <td>{{ number_format($target->raw_incentive_amount) }}</td>
                                                    <td>{{ $target->payout_percent_applied ?? '-' }}%</td>
                                                    <td>
                                                        <strong>{{ number_format($target->final_incentive_amount) }}</strong>
                                                        @if($target->is_full_honor_override)
                                                            <span class="badge bg-info">Full Honor</span>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        <span class="badge bg-{{ $target->status == 'achieved' ? 'success' : ($target->status == 'in_progress' ? 'warning' : 'secondary') }}">
                                                            {{ $target->status }}
                                                        </span>
                                                    </td>
                                                    <td>{{ $target->period_month }}/{{ $target->period_year }}</td>
                                                    <td>
                                                        @if($target->is_locked)
                                                            <span class="badge bg-dark">Locked</span>
                                                        @else
                                                            <span class="badge bg-light text-dark">Open</span>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        @if(!$target->is_locked)
                                                        <div class="d-inline-flex gap-1">
                                                            <form action="{{ route('sales_target.sales-targets.full-honor', $target->id) }}" method="POST">
                                                                @csrf
                                                                @if (hasPermission('sales_target.sales-targets.full-honor')) 
                                                                    <button type="submit"
                                                                            class="btn btn-sm btn-outline-info"
                                                                            onclick="return confirmSubmit(event, this, 'Full Honor');">
                                                                        Full Honor
                                                                    </button>
                                                                @endif
                                                            </form>

                                                            <form action="{{ route('sales_target.sales-targets.lock', $target->id) }}"  method="POST">
                                                                @csrf

                                                                @if (hasPermission('sales_target.sales-targets.lock'))
                                                                    <button type="submit"
                                                                            class="btn btn-sm btn-dark"
                                                                            onclick="return confirmSubmit(event, this, 'Lock');">
                                                                        Lock
                                                                    </button>
                                                                @endif
                                                            </form>
                                                        </div>
                                                        @else
                                                            <span class="text-muted">-</span>
                                                        @endif
                                                    </td>
                                                </tr>
                                                @empty
                                                <tr><td colspan="15" class="text-center">No target found.</td></tr>
                                                @endforelse
                                            </tbody>
                                        </table>
                                    </div>
                                    {{ $targets->links() }}
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
       function confirmSubmit(event, button, status) {
            event.preventDefault();

            Swal.fire({
                title: 'Are you sure?',
                text: 'Do you want to '+status+' this information?',
                icon: 'warning',
                showCancelButton: true, 
                cancelButtonText: 'Cancel',
                confirmButtonText: 'Yes, '+status,
                reverseButtons: true
            }).then((result) => {

                if (result.isConfirmed) {
                    // Confirmation দেওয়ার পর form submit হবে
                    button.closest('form').submit();
                }

            });

            return false;
        }
    </script> 

@endSection

