@extends('layout.app')

@section('content')
    <div class="container-fluid mt-4">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white d-flex justify-content-between align-items-center py-3">
                <h5 class="mb-0 text-primary fw-bold">Attendance Policy</h5>
                <a href="{{ route('hrm.settings.attendance-policies.create') }}" class="btn btn-primary shadow-sm">
                    <i class="fas fa-plus"></i> Add new
                </a>
            </div>
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-md-12">
                        <form action="{{ route('hrm.settings.attendance-policies.index') }}" method="GET"
                            class="d-flex gap-2">
                            <div class="input-group">
                                <span class="input-group-text attendance-policy border-end-0">
                                    <i class="fas fa-search text-muted"></i>
                                </span>
                                <input type="text" name="search" value="{{ request('search') }}"
                                    class="form-control attendance-policy border-start-0"
                                    placeholder="Search and press enter">
                            </div>

                            <a href="{{ route('hrm.settings.attendance-policies.index') }}"
                                class="btn btn-outline-secondary shadow-sm" title="Refresh">
                                <i class="fas fa-sync-alt"></i>Refresh
                            </a>
                        </form>
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="attendance-policy">
                            <tr class="text-muted small uppercase">
                                <th>Policy Name</th>
                                <th>Effective from</th>
                                <th>In time</th>
                                <th>Delay Buffer</th>
                                <th>Ex. Delay Buffer</th>
                                <th>Ignore OT & Deduction</th>
                                <th>Exclude From Att. Reports</th>
                                <th>Discard Att. On Weekend</th>
                                <th class="text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($policies as $policy)
                                <tr>
                                    <td class="fw-bold text-secondary">{{ $policy->name }}</td>
                                    <td>{{ $policy->effective_from->format('d/m/Y') }}</td>
                                    <td>{{ $policy->in_time }}</td>
                                    <td>{{ $policy->delay_buffer }}</td>
                                    <td>{{ $policy->ex_delay_buffer }}</td>
                                    <td>{{ $policy->ignore_ot_deduction ? 'Yes' : 'No' }}</td>
                                    <td>{{ $policy->exclude_from_reports ? 'Yes' : 'No' }}</td>
                                    <td>{{ $policy->discard_weekend ? 'Yes' : 'No' }}</td>
                                    <td class="text-center">
                                        <div class="d-flex justify-content-center align-items-center">

                                            <a href="{{ route('hrm.settings.attendance-policies.edit', $policy->id) }}"
                                                class="text-muted me-3" title="Edit">
                                                <i class="fas fa-pen"></i>
                                            </a>

                                            <form action="{{ route('hrm.settings.attendance-policies.destroy', $policy->id) }}"
                                                method="POST"
                                                onsubmit="return confirm('Are you sure you want to delete this policy?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-danger p-0 border-0 bg-transparent"
                                                    title="Delete">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="9" class="text-center text-muted">No policies found.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection