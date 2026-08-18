// resources/views/sales-incentive-slabs/index.blade.php
@extends('layouts.app')

@section('content')
<div class="container">
    <h3>Sales Incentive Slabs</h3>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="card mb-4">
        <div class="card-body">
            <h5>Add New Incentive Slab</h5>
            <form action="{{ route('sales-incentive-slabs.store') }}" method="POST">
                @csrf
                <div class="row g-2">
                    <div class="col-md-3">
                        <label>Min Achievement %</label>
                        <input type="number" step="0.01" name="min_percent" class="form-control" required>
                    </div>
                    <div class="col-md-3">
                        <label>Max Achievement % (blank = no limit)</label>
                        <input type="number" step="0.01" name="max_percent" class="form-control">
                    </div>
                    <div class="col-md-3">
                        <label>Incentive Rate %</label>
                        <input type="number" step="0.01" name="rate_percent" class="form-control" required>
                    </div>
                    <div class="col-md-3 d-flex align-items-end">
                        <button type="submit" class="btn btn-primary w-100">Add</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Achievement Range</th>
                <th>Rate</th>
                <th>Active</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @forelse($tiers as $tier)
            <tr>
                <form action="{{ route('sales-incentive-slabs.update', $tier->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <td>
                        <input type="number" step="0.01" name="min_percent" value="{{ $tier->min_percent }}" class="form-control form-control-sm d-inline" style="width:90px">
                        -
                        <input type="number" step="0.01" name="max_percent" value="{{ $tier->max_percent }}" class="form-control form-control-sm d-inline" style="width:90px" placeholder="∞">
                        %
                    </td>
                    <td><input type="number" step="0.01" name="rate_percent" value="{{ $tier->rate_percent }}" class="form-control form-control-sm" style="width:80px">%</td>
                    <td>{{ $tier->is_active ? 'Yes' : 'No' }}</td>
                    <td>
                        <button type="submit" class="btn btn-sm btn-success">Update</button>
                </form>
                        <form action="{{ route('sales-incentive-slabs.destroy', $tier->id) }}" method="POST" class="d-inline" onsubmit="return confirm('মুছে ফেলতে চান?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                        </form>
                    </td>
            </tr>
            @empty
            <tr><td colspan="4" class="text-center">কোনো incentive slab নেই</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection