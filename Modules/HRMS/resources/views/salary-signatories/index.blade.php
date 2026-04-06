@extends('layout.app')


@section('title', 'Salary Signatories')
@section('description', 'Salary Signatories')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Salary Signatory Management</h3>

                    </div>

                    <div class="card-body">

                        <div class="table-responsive">
                            <table class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Employee Name</th>
                                        <th>Role Name</th>
                                        <th>Signatory Tag</th>
                                        <th>Approver Level</th>
                                        <th>Status</th>
                                        <th>Description</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>

                                    @forelse($signatories as $signatory)
                                        <tr>
                                            <td>{{ $signatory->id }}</td>
                                            <td>{{ $signatory->employee->full_name ?? 'N/A' }}</td>
                                            <td>{{ $signatory->role_name }}</td>
                                            <td>{{ $signatory->signatory_tag }}</td>
                                            <td>Level {{ $signatory->approver_level }}</td>
                                            <td>
                                                @if($signatory->status === 'active')
                                                    <span class="badge badge-success">Active</span>
                                                @else
                                                    <span class="badge badge-danger">Inactive</span>
                                                @endif
                                            </td>
                                            <td>{{ $signatory->description ?? 'N/A' }}</td>
                                            <td>
                                                @if (hasPermission('hrm.salary-signatories.edit'))
                                                    <a href="{{ route('hrm.salary-signatories.edit', $signatory->id) }}"
                                                        class="btn btn-sm btn-primary">
                                                        <i class="fas fa-edit"></i> Edit
                                                    </a>
                                                @endif
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="7" class="text-center">No salary signatories found</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection