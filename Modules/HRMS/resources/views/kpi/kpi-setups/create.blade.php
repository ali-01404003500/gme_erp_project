@extends('layout.app')

@section('title', 'Create KPI')
@section('description', 'Key Performance Indicator (KPI) Setup')

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
                                <li class="breadcrumb-item active" aria-current="page">Create KPI</li>
                            </ol>
                        </nav>
                    </div>
                    <div class="action-btn mt-sm-0 mt-15">
                        @if (hasPermission('hrm.kpis.kpi-setups.index'))
                            <a href="{{ route('hrm.kpis.kpi-setups.index') }}"
                                class="btn btn-warning btn-default btn-squared radius-md shadow2 btn-sm">
                                <i class="fa fa-list"></i> List
                            </a>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <div class="card mb-50">
            <div class="row justify-content-center">
                <div class="col-sm-12">
                    <div class="mt-40 mb-50 p-30">
                        <x-error-alart />

                        <form action="{{ route('hrm.kpis.kpi-setups.store') }}" method="POST">
                            @csrf

                            {{-- Designation Dropdown --}}
                            <div class="form-group mb-3">
                                <label for="designation_id" class="color-dark fs-14 fw-500">
                                    Designation <span class="text-danger">*</span>
                                </label>
                                <select name="designation_id" id="designation_id" class="form-control tom-select" required>
                                    <option value="">-- Select Designation --</option>
                                    @foreach ($designations as $designation)
                                        <option value="{{ $designation->id }}" {{ old('designation_id') == $designation->id ? 'selected' : '' }}>
                                            {{ $designation->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('designation_id')
                                    <p class="text-danger">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- KPI Items --}}
                            <div id="kpi-items">
                                @php $oldKpis = old('kpis', []); @endphp

                                @if (count($oldKpis))
                                    @foreach ($oldKpis as $index => $kpi)
                                        <div class="kpi-item border p-3 mb-3 rounded">
                                            <div class="row">
                                                <div class="col-md-8">
                                                    <div class="form-group mb-2">
                                                        <label class="fw-500">Job Description</label>
                                                        <input type="text" name="kpis[{{ $index }}][description]" class="form-control"
                                                            value="{{ $kpi['description'] }}" placeholder="Job Description" required>
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="form-group mb-2">
                                                        <label class="fw-500">Weight (points)</label>
                                                        <input type="number" name="kpis[{{ $index }}][weight]" class="form-control"
                                                            value="{{ $kpi['weight'] }}" min="1" placeholder="Weight" required>
                                                    </div>
                                                </div>
                                                <div class="col-md-1 d-flex align-items-center">
                                                    <button type="button" class="btn btn-danger btn-sm mt-3 remove-item {{ $loop->first ? 'd-none' : '' }}">
                                                        <i class="fa fa-trash"></i>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                @else
                                    {{-- Default empty row --}}
                                    <div class="kpi-item border p-3 mb-3 rounded">
                                        <div class="row">
                                            <div class="col-md-8">
                                                <div class="form-group mb-2">
                                                    <label class="fw-500">Job Description</label>
                                                    <input type="text" name="kpis[0][description]" class="form-control" placeholder="Job Description" required>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group mb-2">
                                                    <label class="fw-500">Weight (points)</label>
                                                    <input type="number" name="kpis[0][weight]" class="form-control" min="1" placeholder="Weight" required>
                                                </div>
                                            </div>
                                            <div class="col-md-1 d-flex align-items-center">
                                                <button type="button" class="btn btn-danger btn-sm mt-3 remove-item d-none">
                                                    <i class="fa fa-trash"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            </div>

                            {{-- Add More KPI --}}
                            <div class="button-group d-flex justify-content-end pt-25">
                                <button type="button" class="btn btn-outline-secondary btn-sm mb-3" id="add-kpi-item">
                                    + Add More
                                </button>
                            </div>

                            {{-- Submit --}}
                            <div class="button-group d-flex justify-content-end pt-25">
                                <button type="submit" class="btn btn-primary btn-default btn-squared radius-md shadow2 btn-sm">
                                    Submit
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

@section('page_scripts')
<script>
    let kpiIndex = {{ count(old('kpis', [])) ?: 1 }};

    document.getElementById('add-kpi-item').addEventListener('click', function () {
        const newItem = document.createElement('div');
        newItem.className = 'kpi-item border p-3 mb-3 rounded';
        newItem.innerHTML = `
            <div class="row">
                <div class="col-md-8">
                    <div class="form-group mb-2">
                        <input type="text" name="kpis[${kpiIndex}][description]" class="form-control" placeholder="Job Description" required>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group mb-2">
                        <input type="number" name="kpis[${kpiIndex}][weight]" class="form-control" placeholder="Points" min="1" required>
                    </div>
                </div>
                <div class="col-md-1 d-flex align-items-center">
                    <button type="button" class="btn btn-danger btn-sm remove-item">
                        <i class="fa fa-trash"></i>
                    </button>
                </div>
            </div>`;
        document.getElementById('kpi-items').appendChild(newItem);
        kpiIndex++;
    });

    document.addEventListener('click', function (e) {
        if (e.target.closest('.remove-item')) {
            e.target.closest('.kpi-item').remove();
        }
    });
</script>
@endsection
