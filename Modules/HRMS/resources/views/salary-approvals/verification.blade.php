
@extends('layout.app')

@section('title', 'Salary Approval Verification')
@section('description', 'Salary Approval Verification')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Pending Salary Approvals</h3>
                        <a href="{{ route('hrm.salary-approvals.history') }}" class="btn btn-info float-right">
                            <i class="fas fa-history"></i> View History
                        </a>
                    </div>

                    <div class="card-body">
                        @if(session('success'))
                            <div class="alert alert-success alert-dismissible">
                                <button type="button" class="close" data-dismiss="alert">×</button>
                                {{ session('success') }}
                            </div>
                        @endif

                        @if(session('error'))
                            <div class="alert alert-danger alert-dismissible">
                                <button type="button" class="close" data-dismiss="alert">×</button>
                                {{ session('error') }}
                            </div>
                        @endif

                        @if($pendingRequests->isEmpty())
                            <div class="alert alert-info">
                                <i class="fas fa-info-circle"></i> No pending salary approvals found.
                            </div>
                        @else
                            <div class="table-responsive">
                                <table class="table table-bordered table-striped">
                                    <thead>
                                        32
                                        <th>ID</th>
                                        <th>Employee Name</th>
                                        <th>Salary Amount</th>
                                        <th>Month/Year</th>
                                        <th>Submitted By</th>
                                        <th>Submitted Date</th>
                                        <th>Current Level</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </thead>
                                    <tbody>
                                        @foreach($pendingRequests as $request)
                                            <tr>
                                                <td>#{{ $request->id }}</td>
                                                <td>
                                                    @if($request->salaryGenerate && $request->salaryGenerate->employee)
                                                        {{ $request->salaryGenerate->employee->name }}
                                                    @else
                                                        N/A
                                                    @endif
                                                </td>
                                                <td>
                                                    @if($request->salaryGenerate)
                                                        ৳ {{ number_format($request->salaryGenerate->net_salary ?? 0, 2) }}
                                                    @else
                                                        N/A
                                                    @endif
                                                </td>
                                                <td>
                                                    @if($request->salaryGenerate)
                                                        {{ $request->salaryGenerate->month ?? 'N/A' }} /
                                                        {{ $request->salaryGenerate->year ?? 'N/A' }}
                                                    @else
                                                        N/A
                                                    @endif
                                                </td>
                                                <td>{{ $request->creator->name ?? 'N/A' }}</td>
                                                <td>{{ $request->created_at->format('d M Y, h:i A') }}</td>
                                                <td>
                                                    <span class="badge badge-warning">Level {{ $request->current_level }}</span>
                                                </td>
                                                <td>
                                                    <span class="badge badge-warning">Pending</span>
                                                </td>
                                                <td>
                                                    <a href="{{ route('hrm.salary-approvals.show', $request) }}"
                                                        class="btn btn-sm btn-primary">
                                                        <i class="fas fa-eye"></i> Review
                                                    </a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection