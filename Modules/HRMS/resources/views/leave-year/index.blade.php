
@section('title', 'Leave Year')
@section('description', 'Leave Year')
@extends('layout.app')
@section('title', 'Leave Year Management')
@section('content')
    <div class="container-fluid">
        {{-- Alerts --}}
        @if(session('success'))
            <div class="alert alert-success mt-3">{{ session('success') }}</div>
        @endif

        <div class="d-flex justify-content-between align-items-center mt-4 mb-4">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="#">Settings</a></li>
                    <li class="breadcrumb-item active">Leave Year</li>
                </ol>
            </nav>
        </div>

        <div class="card mb-4 border-0 shadow-sm mt-10">
            <div class="card-body d-flex justify-content-between align-items-center">
                <h4 class="mb-0 fw-bold text-secondary">Leave Year Management</h4>
                @if(!$runningYear)
                    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#openYearModal">Open New
                        Year</button>
                @endif
            </div>
        </div>

        <div class="row g-4 mb-5">
            <div class="col-md-5">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-header bg-white border-0 py-3">
                        <h5 class="mb-0 fw-bold">Running Leave Year</h5>
                    </div>
                    <div class="card-body p-0">
                        @if($runningYear)
                            <table class="table mb-0">
                                <tbody>
                                    <tr>
                                        <td class="ps-4 py-3 text-secondary">Open year</td>
                                        <td class="text-end pe-4 py-3 fw-bold text-secondary">{{ $runningYear['open_year'] }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="ps-4 py-3 text-secondary">Start Date</td>
                                        <td class="text-end pe-4 py-3 fw-bold text-secondary">{{ $runningYear['start_date'] }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="ps-4 py-3 text-secondary">End Date</td>
                                        <td class="text-end pe-4 py-3 fw-bold text-secondary">{{ $runningYear['end_date'] }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="ps-4 py-3 text-secondary">Year Closing Status</td>
                                        <td class="text-end pe-4 py-3 fw-bold text-secondary">
                                            {{ $runningYear['closing_status'] }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        @else
                            <div class="p-4 text-center text-muted">No active year found.</div>
                        @endif
                    </div>
                </div>
            </div>

            <div class="col-md-7">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body p-4">
                        <h5 class="fw-bold mb-3">Reminder</h5>
                        <ul class="list-unstyled">
                            <li class="mb-2"><span class="text-primary">•</span> Confirm all the leave balance.</li>
                            <li class="mb-2"><span class="text-primary">•</span> All holidays information properly added.
                            </li>
                            <li class="mb-2"><span class="text-danger">•</span> After closing, you cannot roll-back.</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <div class="mt-4">
            <h5 class="fw-bold mb-3">Already closed leave years</h5>
            <div class="card border-0 shadow-sm">
                <div class="card-body p-0">
                    <table class="table table-hover mb-0">
                        <thead>
                            <tr>
                                <th class="ps-4 py-3">SL</th>
                                <th class="text-center">Leave Year</th>
                                <th class="text-center">Start Date</th>
                                <th class="text-center">End Date</th>
                                <th class="text-end pe-4">Closed By</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($closedYears as $year)
                                <tr>
                                    <td class="ps-4 py-3">{{ $year['sl'] }}</td>
                                    <td class="text-center fw-bold text-primary">{{ $year['year'] }}</td>
                                    <td class="text-center">{{ $year['start'] }}</td>
                                    <td class="text-center">{{ $year['end'] }}</td>
                                    <td class="text-end pe-4">{{ $year['closed_by'] }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center py-4">No records found.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    {{-- Entry Modal --}}
    <div class="modal fade" id="openYearModal" tabindex="-1">
        <div class="modal-dialog">
            <form action="{{ route('hrm.leave-years.store') }}" method="POST">
                @csrf
                <div class="modal-content">
                    <div class="modal-header">
                        <h5>Open New Year</h5>
                    </div>
                    <div class="modal-body">
                        <input type="number" name="year" class="form-control mb-2" placeholder="Year (2025)" required>
                        <input type="date" name="start_date" class="form-control mb-2" required>
                        <input type="date" name="end_date" class="form-control mb-2" required>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-success">Save</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection