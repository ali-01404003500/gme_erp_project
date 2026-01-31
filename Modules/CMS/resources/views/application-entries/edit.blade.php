@section('title', 'Edit Application Entry')
@section('description', 'Edit Application Entry')
@extends('layout.app')
@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <div class="breadcrumb-main">
                    <div class="breadcrumb-action justify-content-center flex-wrap">
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="#"><i class="las la-home"></i>Home</a></li>
                                <li class="breadcrumb-item active" aria-current="page">
                                    {{ trans('Edit Application Entry') }}</li>
                            </ol>
                        </nav>
                    </div>
                    <div class="action-btn mt-sm-0 mt-15">
                        <a href="{{ route('cms.application-entries.index') }}"
                            class="btn btn-warning btn-default btn-squared radius-md shadow2 btn-sm"><i
                                class="fa fa-list"></i> List</a>
                    </div>
                </div>
                <div class="d-flex align-items-center user-member__title mb-2">
                    <h4 class="text-capitalize">{{ trans('Edit Application Entry') }}</h4>
                </div>
                <x-error-alart />
            </div>
        </div>
        <div class="card mb-50">
            <div class="row justify-content-center">
                <div class="col-sm-10">
                    <div class="mt-40 mb-50">
                        <form action="{{ route('cms.application-entries.update',  $applicationEntry->id) }}" method="POST"
                            enctype="multipart/form-data">
                            @csrf
                            @method('PUT')
                            <div class="row mb-2">
                                <div class="form-group col-md-6">
                                    <label for="date">Date<span class="text-danger">*</span></label>
                                    <input type="text" class="form-control flatdate" name="date" id="date"
                                        placeholder="Date" value="{{ old('date', $applicationEntry->date) }}">
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="type">Application Type<span class="text-danger">*</span></label>
                                    <select class="form-control tom-select" name="type" id="type">
                                        <option value="Deed Document" {{ old('type', $applicationEntry->type) == 'Deed Document' ? 'selected' : '' }}>Deed Document</option>
                                        <option value="NOC" {{ old('type', $applicationEntry->type) == 'NOC' ? 'selected' : '' }}>NOC</option>
                                    </select>
                                </div>
                                <div class="form-group col-md-12">
                                    <label for="customer_id">Customer<span class="text-danger">*</span></label>
                                    <select class="form-control tom-select" name="customer_id" id="customer_id" required>
                                        <option value="">Select a Customer</option>
                                        @foreach ($customers as $item)
                                            <option value="{{ $item->id }}" {{ old('customer_id', $applicationEntry->customer_id) == $item->id ? 'selected' : '' }}>{{ $item->company_name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group col-md-12">
                                    <label for="description">Description<span class="text-danger">*</span></label>
                                    <textarea name="description" id="description" class="form-control" placeholder="Remarks">{{ old('description', $applicationEntry->description) }}</textarea>
                                </div>
                            </div>
                            <div class="button-group d-flex pt-25 justify-content-md-end justify-content-start">
                                <button type="submit"
                                    class="btn btn-primary btn-default btn-squared radius-md shadow2 btn-sm">Update</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('page_scripts')
@endsection