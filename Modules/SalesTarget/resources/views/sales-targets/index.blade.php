// resources/views/sales-targets/index.blade.php
@extends('layouts.app')

@section('content')
<div class="container">
    <h3>Sales Targets</h3>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger">
            @foreach($errors->all() as $error)
                <p>{{ $error }}</p>
            @endforeach
        </div>
    @endif

    {{-- Assign Target Form --}}
    <div class="card mb-4">
        <div class="card-body">
            <h5>Assign New Target</h5>
            <form action="{{ route('sales-targets.store') }}" method="POST">
                @csrf
                <div class="row g-2">
                    <div class="col-md-3">
                        <label>Employee</label>
                        <select name="employee_id" class="form-control" required>
                            <option value="">-- Select --</option>
                            @foreach(\App\Models\User::all() as $user)
                                <option value="{{ $user->id }}">{{ $user->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label>Salary Basis</label>
                        <select name="salary_basis" class="form-control" required>
                            <option value="basic">Basic Only</option>
                            <option value="gross">Gross (Basic+TA/DA)</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label>Month</label>
                        <select name="period_month" class="form-control" required>
                            @for($m = 1; $m <= 12; $m++)
                                <option value="{{ $m }}" {{ $m == now()->month ? 'selected' : '' }}>{{ $m }}</option>
                            @endfor
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label>Year</label>
                        <input type="number" name="period_year" class="form-control" value="{{ now()->year }}" required>
                    </div>
                    <div class="col-md-3 d-flex align-items-end">
                        <button type="submit" class="btn btn-primary w-100">Assign</button>
                    </div>
                </div>
                <small class="text-muted">Salary আলাদা input করা লাগবে না — Employee-র রেকর্ড থেকে অটো নিয়ে নেবে।</small>
            </form>
        </div>
    </div>

    {{-- Targets List --}}
    <div class="table-responsive">
    <table class="table table-bordered table-sm align-middle">
        <thead>
            <tr>
                <th>Employee</th>
                <th>Basis</th>
                <th>Salary</th>
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
                <td>{{ $target->employee->name ?? '-' }}</td>
                <td>{{ ucfirst($target->salary_basis) }}</td>
                <td>{{ number_format($target->salary_basis === 'gross' ? $target->gross_salary_at_assign : $target->salary_at_assign) }}</td>
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
                        <form action="{{ route('sales-targets.full-honor', $target->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Full salary honor করতে চান?')">
                            @csrf
                            <button type="submit" class="btn btn-sm btn-outline-info">Full Honor</button>
                        </form>
                        <form action="{{ route('sales-targets.lock', $target->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Lock করলে আর পরিবর্তন করা যাবে না। নিশ্চিত?')">
                            @csrf
                            <button type="submit" class="btn btn-sm btn-dark">Lock</button>
                        </form>
                    @else
                        <span class="text-muted">-</span>
                    @endif
                </td>
            </tr>
            @empty
            <tr><td colspan="15" class="text-center">কোনো target পাওয়া যায়নি</td></tr>
            @endforelse
        </tbody>
    </table>
    </div>

    {{ $targets->links() }}
</div>
@endsection