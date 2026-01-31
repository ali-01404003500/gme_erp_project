@extends('layout.app')

@section('title', 'Create Score Wise Suggestion')
@section('description', 'Score-to-Grade Mapping Setup')

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
                                    <li class="breadcrumb-item active" aria-current="page">Create Score Wise Suggestion</li>
                                </ol>
                            </nav>
                        </div>
                        <div class="action-btn mt-sm-0 mt-15">
                            @if (hasPermission('hrm.kpis.score-wise-suggestions.index'))
                                <a href="{{ route('hrm.kpis.score-wise-suggestions.index') }}"
                                    class="btn btn-warning btn-default btn-squared radius-md shadow2 btn-sm">
                                    <i class="fa fa-list"></i> List
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
             <div class="col-md-12 mb-3">
                    <h4 class="text-capitalize breadcrumb-title">Create Score Wise Suggestion</h4>
                    <x-error-alart />
                </div>
            <div class="card mb-50">
                <div class="row justify-content-center">
                    <div class="col-sm-12">
                        <div class="mt-40 mb-50 p-30">

                            <form action="{{ route('hrm.kpis.score-wise-suggestions.store') }}" method="POST">
                                @csrf
                                <div class="row">
                                    <!-- Score Range -->
                                    <div class="col-md-6">
                                        <div class="form-group mb-3">
                                            <label for="min_score" class="color-dark fs-14 fw-500">Score Range <span
                                                    class="text-danger">*</span></label>
                                            <div class="input-group">
                                                <input type="number" name="min_score" id="min_score" class="form-control"
                                                    placeholder="Min" value="{{ old('min_score') }}" required>
                                                <span class="input-group-text">-</span>
                                                <input type="number" name="max_score" id="max_score" class="form-control"
                                                    placeholder="Max" value="{{ old('max_score') }}" required>
                                            </div>
                                            @error('min_score')
                                                <p class="text-danger">{{ $message }}</p>
                                            @enderror
                                            @error('max_score')
                                                <p class="text-danger">{{ $message }}</p>
                                            @enderror
                                        </div>
                                    </div>

                                    <!-- Rating/Grade -->
                                    <div class="col-md-6">
                                        <div class="form-group mb-3">
                                            <label for="rating_grade" class="color-dark fs-14 fw-500">Rating / Grade <span
                                                    class="text-danger">*</span></label>
                                            <input type="text" name="rating_grade" id="rating_grade" class="form-control"
                                                placeholder="Enter rating or grade" value="{{ old('rating_grade') }}"
                                                required>
                                            @error('rating_grade')
                                                <p class="text-danger">{{ $message }}</p>
                                            @enderror
                                        </div>
                                    </div>

                                    <!-- Remarks -->
                                    <div class="col-md-6">
                                        <div class="form-group mb-3">
                                            <label for="remarks" class="color-dark fs-14 fw-500">Remarks <span
                                                    class="text-danger">*</span></label>
                                            <textarea name="remarks" id="remarks" class="form-control" rows="3" placeholder="Enter remarks..." required>{{ old('remarks') }}</textarea>
                                            @error('remarks')
                                                <p class="text-danger">{{ $message }}</p>
                                            @enderror
                                        </div>
                                    </div>

                                    <!-- Training Need -->
                                    <div class="col-md-6">
                                        <div class="form-group mb-3">
                                            <label for="training_need" class="color-dark fs-14 fw-500">Training Need <span
                                                    class="text-danger">*</span></label>
                                            <textarea name="training_need" id="training_need" class="form-control" rows="3"
                                                placeholder="Specify training needs..." required>{{ old('training_need') }}</textarea>
                                            @error('training_need')
                                                <p class="text-danger">{{ $message }}</p>
                                            @enderror
                                        </div>
                                    </div>
                                </div>


                                <div class="button-group d-flex justify-content-end pt-25">
                                    <button type="submit"
                                        class="btn btn-primary btn-default btn-squared radius-md shadow2 btn-sm">
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
