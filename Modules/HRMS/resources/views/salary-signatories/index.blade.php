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
                        <a href="{{ route('hrm.salary-signatories.create') }}" class="btn btn-primary float-right">
                            <i class="fas fa-plus"></i> Create New Signatory
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

                        <div class="table-responsive">
                            <table class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Employee Name</th>
                                        <th>Signatory Tag</th>
                                        <th>Level</th>
                                        <th>Status</th>
                                        <th>Description</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($signatories as $signatory)
                                        <tr>
                                            <td>{{ $signatory->title }}</td>
                                            <td>{{ $signatory->employee->user->name ?? 'N/A' }}</td>
                                            <td><span class="badge badge-info">{{ $signatory->signatory_tag }}</span></td>
                                            <td><span class="badge badge-primary">Level {{ $signatory->level }}</span></td>
                                            <td>
                                                @if($signatory->status == 'active')
                                                    <span class="badge badge-success">Active</span>
                                                @else
                                                    <span class="badge badge-danger">Inactive</span>
                                                @endif
                                            </td>
                                            <td>{{ $signatory->description ?? 'N/A' }}</td>
                                            <td>
                                                <a href="{{ route('hrm.salary-signatories.edit', $signatory) }}"
                                                    class="btn btn-sm btn-primary">
                                                    <i class="fas fa-edit"></i> Edit
                                                </a>
                                                <form
                                                    action="{{ route('hrm.salary-signatories.destroy', $signatory) }}"
                                                    method="POST" style="display:inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-danger"
                                                        onclick="return confirm('Are you sure you want to delete this signatory?')">
                                                        <i class="fas fa-trash"></i> Delete
                                                    </button>
                                                </form>
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