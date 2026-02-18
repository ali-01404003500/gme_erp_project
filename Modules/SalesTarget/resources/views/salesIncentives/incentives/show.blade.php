@section('title', 'Incentive Details')
@extends('layout.app')

@section('content')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <style>
        .breadcrumb-main {
            background: #fff;
            padding: 15px 25px;
            border-radius: 10px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
            margin-bottom: 25px;
        }

        .card {
            border: none;
            border-radius: 12px;
            transition: transform 0.2s;
        }

        .card-header {
            border-radius: 12px 12px 0 0 !important;
            font-weight: 700;
            letter-spacing: 0.5px;
        }

        .info-label {
            color: #6c757d;
            font-size: 0.85rem;
            text-transform: uppercase;
            font-weight: 600;
            margin-bottom: 2px;
        }

        .info-value {
            color: #2d3436;
            font-size: 1.05rem;
            font-weight: 600;
            margin-bottom: 15px;
        }

        .slab-table thead th {
            background-color: #f8f9fa;
            text-transform: uppercase;
            font-size: 0.75rem;
            color: #495057;
            border-top: none;
        }

        .status-badge {
            padding: 6px 12px;
            border-radius: 50px;
            font-size: 0.75rem;
            font-weight: 700;
        }

        .btn-action {
            border-radius: 8px;
            padding: 8px 18px;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }
    </style>

    <div class="container-fluid py-4">
        <div class="breadcrumb-main d-flex justify-content-between align-items-center">
            <div>
                <h4 class="text-capitalize fw-bold mb-0">
                    <i class="bi bi-award text-primary me-2"></i>Incentive Setup: <span
                        class="text-primary">{{ $incentive->title }}</span>
                </h4>
                <span class="text-muted small">Viewing configuration for the fiscal year {{ $incentive->year }}</span>
            </div>
            <div class="d-flex gap-2">
                <a href="{{ route('sales_target.settings.incentives.index') }}"
                    class="btn btn-outline-secondary btn-action">
                    <i class="bi bi-arrow-left"></i> Back
                </a>
                <a href="{{ route('sales_target.settings.incentives.edit', $incentive->id) }}"
                    class="btn btn-primary btn-action shadow-sm">
                    <i class="bi bi-pencil-square"></i> Edit Setup
                </a>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-4">
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-white py-3 border-bottom">
                        <i class="bi bi-info-circle me-2"></i>General Info
                    </div>
                    <div class="card-body py-4">
                        <div class="info-label">Title</div>
                        <div class="info-value">{{ $incentive->title }}</div>

                        <div class="info-label">Year</div>
                        <div class="info-value"><span class="badge  text-dark border">{{ $incentive->year }}</span>
                        </div>

                        <div class="info-label">Status</div>
                        <div class="info-value">
                            @if($incentive->status == 'Active')
                                <span class="status-badge bg-success text-white"><i class="bi bi-check-circle-fill me-1"></i>
                                    ACTIVE</span>
                            @else
                                <span class="status-badge bg-danger text-white"><i class="bi bi-x-circle-fill me-1"></i>
                                    INACTIVE</span>
                            @endif
                        </div>

                        <hr class="my-4 text-light">

                        <div class="info-label">Date Created</div>
                        <div class="info-value text-muted small">
                            {{ date('d M, Y | h:i A', strtotime($incentive->created_at)) }}</div>
                    </div>
                </div>
            </div>

            <div class="col-lg-8">
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-dark text-white py-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <span><i class="bi bi-layers me-2"></i>Incentive Slab Configuration</span>
                            <span class="badge bg-secondary">{{ count($slabs) }} Slabs Defined</span>
                        </div>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table slab-table mb-0">
                                <thead>
                                    <tr>
                                        <th class="ps-4">#</th>
                                        <th>Achievement Range (%)</th>
                                        <th>Incentive Type</th>
                                        <th class="text-end">Rate / Amount</th>
                                        <th class="pe-4">Notes</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($slabs as $index => $slab)
                                        <tr>
                                            <td class="ps-4 fw-bold text-muted">{{ $index + 1 }}</td>
                                            <td>
                                                <span class="fw-bold text-dark">{{ $slab->min_range }}%</span>
                                                <i class="bi bi-arrow-right text-muted mx-1"></i>
                                                <span class="fw-bold text-dark">{{ $slab->max_range }}%</span>
                                            </td>
                                            <td>
                                                <span class="badge text-primary border border-primary-subtle">
                                                    {{ strtoupper($slab->incentive_type) }}
                                                </span>
                                            </td>
                                            <td class="text-end fw-bold text-success">
                                                {{ $slab->incentive_type == 'percentage' ? '' : '৳' }}{{ number_format($slab->incentive_rate, 2) }}{{ $slab->incentive_type == 'percentage' ? '%' : '' }}
                                            </td>
                                            <td class="pe-4">
                                                <span class="text-muted small italic">{{ $slab->notes ?? '---' }}</span>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5" class="text-center py-5 text-muted">No slabs configured for this
                                                incentive.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="card-footer bg-white border-top-0 py-3 text-center">
                        <small class="text-muted"><i class="bi bi-lightbulb me-1"></i> These rates will be applied to the
                            Net Sales Amount achieved.</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection