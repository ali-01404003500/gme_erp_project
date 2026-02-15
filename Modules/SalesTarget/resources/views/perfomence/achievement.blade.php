@section('title', 'Target Achievement List')
@extends('layout.app')

@section('content')
<div class="container-fluid">
    <form action="{{ route('sales_target.perfomence.achievement') }}" method="GET">
        <div class="row">
            <div class="form-group col-md-4 mb-25">
                <label for="user_ref_id" class="color-dark fs-14 fw-500 align-center">
                    User Reference <span class="text-danger">*</span>
                </label>
                <select class="form-control ip-gray radius-xs b-light px-15 tom-select" name="user_ref_id"
                    id="user_ref_id" onchange="this.form.submit()">
                    <option value="">Choose User Reference</option>
                    @foreach ($employees as $employee)
                        <option value="{{ $employee->id }}" {{ request('user_ref_id') == $employee->id ? 'selected' : '' }}>
                            {{ $employee->name }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>
    </form>

    <div class="table-responsive">
        <table class="table table-bordered">
            <thead>
                <tr class="text-uppercase">
                    <th>Month</th>
                    <th>Target</th>
                    <th>Achieved</th>
                    <th>Achievement %</th>
                    <th>Deals Closed</th> 
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse($results as $data)
                    <tr>
                        <td>{{ $data['month'] }}</td>
                        <td class="text-amount fw-bold" style="color:rgb(255, 1, 1)">
                            ৳{{ number_format($data['target'], 0) }}
                        </td>
                        <td>
                            ৳{{ number_format($data['achieved'], 0) }}
                        </td>
                        <td>
                            <div class="progress" style="height: 10px; margin-bottom: 5px;">
                                <div class="progress-bar" role="progressbar"
                                    style="width: {{ min($data['percent'], 100) }}%"></div>
                            </div>
                            {{ number_format($data['percent'], 2) }}%
                        </td>
                        <td class="text-center">{{ $data['deals'] }}</td> {{-- Added Data --}}
                        <td>
                            <span class="badge {{ $data['status'] == 'Met' ? 'badge-success' : 'badge-danger' }}">
                                {{ $data['status'] }}
                            </span>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center">Please select an employee to see data.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection