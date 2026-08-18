// resources/views/sales-salary-brackets/index.blade.php
@extends('layouts.app')
@section('content')
<div class="container">
    <h3>Salary Payout Brackets</h3>
    @if(session('success'))<div class="alert alert-success">{{ session('success') }}</div>@endif

    <div class="card mb-4"><div class="card-body">
        <form action="{{ route('sales-salary-brackets.store') }}" method="POST">
            @csrf
            <div class="row g-2">
                <div class="col-md-3"><label>Min Achievement %</label>
                    <input type="number" step="0.01" name="min_percent" class="form-control" required></div>
                <div class="col-md-3"><label>Max Achievement %</label>
                    <input type="number" step="0.01" name="max_percent" class="form-control"></div>
                <div class="col-md-3"><label>Payout %</label>
                    <input type="number" step="0.01" name="payout_percent" class="form-control" required></div>
                <div class="col-md-3 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary w-100">Add</button></div>
            </div>
        </form>
    </div></div>

    <table class="table table-bordered">
        <thead><tr><th>Achievement Range</th><th>Payout %</th><th>Action</th></tr></thead>
        <tbody>
        @forelse($brackets as $b)
        <tr>
            <form action="{{ route('sales-salary-brackets.update', $b->id) }}" method="POST">
                @csrf @method('PUT')
                <td>
                    <input type="number" step="0.01" name="min_percent" value="{{ $b->min_percent }}" class="form-control form-control-sm d-inline" style="width:90px"> -
                    <input type="number" step="0.01" name="max_percent" value="{{ $b->max_percent }}" class="form-control form-control-sm d-inline" style="width:90px" placeholder="∞"> %
                </td>
                <td><input type="number" step="0.01" name="payout_percent" value="{{ $b->payout_percent }}" class="form-control form-control-sm" style="width:80px">%</td>
                <td><button type="submit" class="btn btn-sm btn-success">Update</button>
            </form>
                <form action="{{ route('sales-salary-brackets.destroy', $b->id) }}" method="POST" class="d-inline" onsubmit="return confirm('মুছবেন?')">
                    @csrf @method('DELETE')
                    <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                </form>
            </td>
        </tr>
        @empty
        <tr><td colspan="3" class="text-center">কোনো bracket নেই</td></tr>
        @endforelse
        </tbody>
    </table>
</div>
@endsection