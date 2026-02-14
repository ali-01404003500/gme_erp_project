@section('title', 'Incentive Summary')
@extends('layout.app')

@section('content')
    <div class="container-fluid mt-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="h4">Sales Incentive Configurations</h2>
            <a href="{{ route('sales_target.settings.incentives.create') }}" class="btn btn-primary">
                <i class="fas fa-plus"></i> Create New Setup
            </a>
        </div>

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="card shadow-sm">
            {{-- <div class="card-header bg-white font-weight-bold">Active Incentive Policies</div> --}}
            <div class="card-body p-0">
                <table class="table table-hover mb-0">
                    <thead class="bg-light">
                        <tr class="text-center">
                            <th>Incentive Title</th>
                            <th>Year</th>
                            <th>Slab Summary</th>
                            <th>Status</th>
                            <th>Last Updated</th>
                            <th class="px-4">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($incentives as $item)
                            <tr class="text-center">
                                <td class="align-middle"><strong>{{ $item->title }}</strong></td>
                                <td class="align-middle">{{ $item->year }}</td>
                                <td class="align-middle">
                                    <span class="badge bg-info text-dark">
                                        {{-- {{ $item->slabs_count ?? 0 }}  --}}
                                        Slabs Defined
                                    </span>
                                    <small class="text-muted d-block">
                                        Range: {{ $item->min_reach ?? 0 }}% - {{ $item->max_reach ?? 0 }}%
                                    </small>
                                </td>
                                <td class="text-center align-middle">
                                    <span class="badge {{ $item->status == 'Active' ? 'bg-success' : 'bg-danger' }}">
                                        {{ $item->status }}
                                    </span>
                                </td>
                                <td class="align-middle text-muted">{{ date('d M, Y', strtotime($item->updated_at)) }}</td>
                                <td class="align-middle px-4 text-center">
                                    <a href="{{ route('sales_target.settings.incentives.show', $item->id) }}"
                                        class="btn btn-sm btn-outline-primary me-1" title="View Details">
                                        <i class="fas fa-eye"></i>
                                    </a>

                                    <a href="{{ route('sales_target.settings.incentives.edit', $item->id) }}"
                                        class="btn btn-sm btn-info text-white me-1" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>

                                    <form action="{{ route('sales_target.settings.incentives.destroy', $item->id) }}"
                                        method="POST" class="d-inline">
                                        @csrf @method('DELETE')
                                        <button class="btn btn-sm btn-danger" onclick="return confirm('Delete this setup?')">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="py-5 text-center text-muted">
                                    <i class="fas fa-folder-open fa-3x mb-3 d-block"></i>
                                    No incentive setups found. Click "Create New Setup" to begin.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection