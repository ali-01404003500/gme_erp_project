@section('title', 'Incentive Details')
@extends('layout.app')

@section('content')
    <div class="container-fluid mt-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2>Incentive Details: {{ $incentive->title }}</h2>
            <div>
                <a href="{{ route('sales_target.settings.incentives.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Back to List
                </a>
                <a href="{{ route('sales_target.settings.incentives.edit', $incentive->id) }}"
                    class="btn btn-info text-white">
                    <i class="fas fa-edit"></i> Edit Setup
                </a>
            </div>
        </div>

        <div class="row">
            <div class="col-md-4">
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-primary text-white">General Information</div>
                    <div class="card-body">
                        <p><strong>Year:</strong> {{ $incentive->year }}</p>
                        <p><strong>Status:</strong>
                            <span class="badge {{ $incentive->status == 'Active' ? 'bg-success' : 'bg-danger' }}">
                                {{ $incentive->status }}
                            </span>
                        </p>
                        <p><strong>Created:</strong> {{ date('d M, Y', strtotime($incentive->created_at)) }}</p>
                    </div>
                </div>
            </div>

            <div class="col-md-8">
                <div class="card shadow-sm">
                    <div class="card-header bg-dark text-white">Incentive Slabs</div>
                    <div class="card-body p-0">
                        <table class="table table-striped mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Slab No</th>
                                    <th>Range (%)</th>
                                    <th>Type</th>
                                    <th>Rate/Amount</th>
                                    <th>Notes</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($slabs as $index => $slab)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>{{ $slab->min_range }}% - {{ $slab->max_range }}%</td>
                                        <td>{{ $slab->incentive_type }}</td>
                                        <td>{{ number_format($slab->incentive_rate, 2) }}</td>
                                        <td><small class="text-muted">{{ $slab->notes ?? 'N/A' }}</small></td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection