// resources/views/sales-target-slabs/index.blade.php
@extends('layouts.app')

@section('content')
<div class="container">
    <h3>Sales Target Slabs</h3>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="card mb-4">
        <div class="card-body">
            <h5>Add New Slab</h5>
            <form action="{{ route('sales-target-slabs.store') }}" method="POST">
                @csrf
                <div class="row g-2">
                    <div class="col-md-3">
                        <label>Name</label>
                        <input type="text" name="name" class="form-control" placeholder="Slab 3">
                    </div>
                    <div class="col-md-2">
                        <label>Min Salary</label>
                        <input type="number" step="0.01" name="min_salary" class="form-control" required>
                    </div>
                    <div class="col-md-2">
                        <label>Max Salary</label>
                        <input type="number" step="0.01" name="max_salary" class="form-control" required>
                    </div>
                    <div class="col-md-2">
                        <label>Multiplier</label>
                        <input type="number" step="0.01" name="target_multiplier" class="form-control" required>
                    </div>
                    <div class="col-md-3 d-flex align-items-end">
                        <button type="submit" class="btn btn-primary w-100">Add Slab</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Name</th>
                <th>Salary Range</th>
                <th>Multiplier</th>
                <th>Active</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @forelse($slabs as $slab)
            <tr>
                <form action="{{ route('sales-target-slabs.update', $slab->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <td><input type="text" name="name" value="{{ $slab->name }}" class="form-control form-control-sm"></td>
                    <td>
                        <input type="number" step="0.01" name="min_salary" value="{{ $slab->min_salary }}" class="form-control form-control-sm d-inline" style="width:100px">
                        -
                        <input type="number" step="0.01" name="max_salary" value="{{ $slab->max_salary }}" class="form-control form-control-sm d-inline" style="width:100px">
                    </td>
                    <td><input type="number" step="0.01" name="target_multiplier" value="{{ $slab->target_multiplier }}" class="form-control form-control-sm" style="width:80px"></td>
                    <td>{{ $slab->is_active ? 'Yes' : 'No' }}</td>
                    <td>
                        <button type="submit" class="btn btn-sm btn-success">Update</button>
                </form>
                        <form action="{{ route('sales-target-slabs.destroy', $slab->id) }}" method="POST" class="d-inline" onsubmit="return confirm('মুছে ফেলতে চান?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                        </form>
                    </td>
            </tr>
            @empty
            <tr><td colspan="5" class="text-center">কোনো slab নেই</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection