@section('title', 'Incentive Summary')
@extends('layout.app')

@section('content')
    <style>
        .table-container {
            border-radius: 8px;
            overflow: hidden;
            border: 1px solid #333;

        }


        .table-bordered-custom {
            border-collapse: collapse !important;
        }

        .table-bordered-custom th,
        .table-bordered-custom td {
            border: 1px solid #dee2e6 !important;

            padding: 12px 15px !important;
        }


        .table-bordered-custom thead th {
            background-color: #f8f9fa;
            color: #212529;
            font-weight: 700;
            text-transform: uppercase;
            font-size: 0.8rem;
            border-bottom: 2px solid #333 !important;

        }


        .btn-action {
            height: 30px;
            width: 30px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 4px;
        }

        .status-badge {
            padding: 4px 12px;
            border-radius: 4px;
            font-size: 0.75rem;
            font-weight: bold;
        }
    </style>

    <div class="container-fluid py-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2 class="h4 mb-0 fw-bold">Incentive Configurations</h2>
                <small class="text-muted">Structured grid of sales performance policies</small>
            </div>
            <a href="{{ route('sales_target.settings.incentives.create') }}" class="btn btn-primary">
                <i class="fas fa-plus me-1"></i> New Configuration
            </a>
        </div>

        <div class="card shadow-sm table-container">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover table-bordered-custom mb-0">
                        <thead>
                            <tr class="text-center">
                                <th width="25%">Incentive Name</th>
                                <th width="10%">Year</th>
                                <th width="25%">Slab Range (%)</th>
                                <th width="15%">Status</th>
                                <th width="15%">Last Updated</th>
                                <th width="10%">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($incentives as $item)
                                <tr class="text-center">
                                    <td class="text-center fw-bold text-dark">
                                        {{ $item->title }}
                                    </td>
                                    <td class="fw-bold">{{ $item->year }}</td>
                                    <td>
                                        <div class="d-flex align-items-center justify-content-center">
                                            <span class="badge text-dark border me-2">
                                                {{ $item->min_reach ?? 0 }}% - {{ $item->max_reach ?? 0 }}%
                                            </span>
                                        </div>
                                    </td>
                                    <td>
                                        @if($item->status == 'Active')
                                            <span class="badge bg-success status-badge">ACTIVE</span>
                                        @else
                                            <span class="badge bg-danger status-badge">INACTIVE</span>
                                        @endif
                                    </td>
                                    <td class="text-muted">
                                        {{ date('d-m-Y', strtotime($item->updated_at)) }}
                                    </td>
                                    <td>
                                        <div class="d-flex justify-content-center gap-1">
                                            <a href="{{ route('sales_target.settings.incentives.show', $item->id) }}"
                                                class="btn btn-action btn-outline-primary" title="View">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ route('sales_target.settings.incentives.edit', $item->id) }}"
                                                class="btn btn-action btn-outline-info" title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <form action="{{ route('sales_target.settings.incentives.destroy', $item->id) }}"
                                                method="POST" class="m-0">
                                                @csrf @method('DELETE')
                                                <button class="btn btn-action btn-outline-danger"
                                                    onclick="return confirm('Confirm deletion?')" title="Delete">
                                                    <i class="fas fa-trash-alt"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center py-5 text-muted">
                                        No records found in the database.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection