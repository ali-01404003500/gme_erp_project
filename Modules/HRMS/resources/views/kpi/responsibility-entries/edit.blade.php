@extends('layout.app')

@section('title', 'Edit Responsibility')
@section('description', 'Edit Responsibility for KPI Templates')

@section('content')
<div class="container-fluid">
    <div class="social-dash-wrap">
        <div class="row">
            <div class="col-lg-12">
                <div class="breadcrumb-main">
                    <div class="breadcrumb-action justify-content-center flex-wrap">
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="#"><i class="las la-home"></i> Home</a></li>
                                <li class="breadcrumb-item active" aria-current="page">Edit Responsibility</li>
                            </ol>
                        </nav>
                    </div>
                    <div class="action-btn mt-sm-0 mt-15">
                        @if (hasPermission('hrm.kpis.responsibility-entries.index'))
                            <a href="{{ route('hrm.kpis.responsibility-entries.index') }}"
                                class="btn btn-warning btn-default btn-squared radius-md shadow2 btn-sm">
                                <i class="fa fa-list"></i> List
                            </a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
         <div class="col-md-12 mb-3">
                    <h4 class="text-capitalize breadcrumb-title">Edit Responsibility</h4>
                    <x-error-alart />
                </div>

        <div class="card mb-50">
            <div class="row justify-content-center">
                <div class="col-sm-12">
                    <div class="mt-40 mb-50 p-30">

                        <form action="{{ route('hrm.kpis.responsibility-entries.update', $responsibilityEntry->id) }}" method="POST">
                            @csrf
                            @method('PUT')

                            <div class="row">
                                <!-- Code -->
                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label for="code" class="color-dark fs-14 fw-500">Code <span class="text-danger">*</span></label>
                                        <input type="text" name="code" id="code" class="form-control"
                                            placeholder="Enter unique code" value="{{ old('code', $responsibilityEntry->code) }}" required>
                                        @error('code')
                                            <p class="text-danger mb-0">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>

                                <!-- Responsibilities -->
                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label for="description" class="color-dark fs-14 fw-500">Responsibilities <span class="text-danger">*</span></label>
                                        <textarea name="description" id="description" class="form-control" rows="3" 
                                            placeholder="Enter responsibility description..." required>{{ old('description', $responsibilityEntry->description) }}</textarea>
                                        @error('description')
                                            <p class="text-danger mb-0">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>

                                <!-- Weight -->
                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label for="weight" class="color-dark fs-14 fw-500">Weight (points) <span class="text-danger">*</span></label>
                                        <input type="number" name="weight" id="weight" class="form-control"
                                            placeholder="Enter weight" value="{{ old('weight', $responsibilityEntry->weight) }}" min="1" required>
                                        @error('weight')
                                            <p class="text-danger mb-0">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>

                                <!-- Time -->
                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label for="time" class="color-dark fs-14 fw-500">Target Days <span class="text-danger">*</span></label>
                                        <input type="number" name="time" id="time" class="form-control"
                                            placeholder="Enter target days" value="{{ old('time', $responsibilityEntry->time) }}" min="1" required>
                                        @error('time')
                                            <p class="text-danger mb-0">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>

                                <!-- Frequency -->
                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label for="frequency" class="color-dark fs-14 fw-500">Frequency <span class="text-danger">*</span></label>
                                        <select name="frequency" id="frequency" class="form-control tom-select" required>
                                            <option value="">-- Select Frequency --</option>
                                            <option value="Day" {{ old('frequency', $responsibilityEntry->frequency) == 'Day' ? 'selected' : '' }}>Day</option>
                                            <option value="Month" {{ old('frequency', $responsibilityEntry->frequency) == 'Month' ? 'selected' : '' }}>Month</option>
                                            <option value="Year" {{ old('frequency', $responsibilityEntry->frequency) == 'Year' ? 'selected' : '' }}>Year</option>
                                        </select>
                                        @error('frequency')
                                            <p class="text-danger mb-0">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>

                                <!-- Status -->
                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label for="status" class="color-dark fs-14 fw-500">Status <span class="text-danger">*</span></label>
                                        <select name="status" id="status" class="form-control tom-select" required>
                                            <option value="Active" {{ old('status', $responsibilityEntry->status) == 'Active' ? 'selected' : '' }}>Active</option>
                                            <option value="Inactive" {{ old('status', $responsibilityEntry->status) == 'Inactive' ? 'selected' : '' }}>Inactive</option>
                                        </select>
                                        @error('status')
                                            <p class="text-danger mb-0">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="button-group d-flex justify-content-end pt-25">
                                <button type="submit"
                                    class="btn btn-primary btn-default btn-squared radius-md shadow2 btn-sm">
                                    Update
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection