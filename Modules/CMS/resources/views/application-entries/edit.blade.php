@section('title', 'Edit Application Entry')
@section('description', 'Edit Application Entry')
@extends('layout.app')

@section('content')
<style>
    /* Modern Mesh Gradient Background */
    body {
        background: radial-gradient(at 0% 0%, rgba(95, 99, 242, 0.12) 0px, transparent 50%),
                    radial-gradient(at 100% 0%, rgba(121, 40, 202, 0.1) 0px, transparent 50%),
                    radial-gradient(at 100% 100%, rgba(0, 212, 255, 0.12) 0px, transparent 50%),
                    radial-gradient(at 0% 100%, rgba(95, 99, 242, 0.08) 0px, transparent 50%),
                    #f8fafc !important;
        min-height: 100vh;
    }

    /* Vertical Title Aesthetic */
    /* .vertical-title {
        writing-mode: vertical-rl;
        transform: rotate(180deg);
        white-space: nowrap;
        font-weight: 800;
        color: #1e293b;
        border-left: 4px solid #5f63f2;
        padding-right: 15px;
        letter-spacing: 1px;
        text-transform: uppercase;
        font-size: 1.1rem;
        margin-top: 20px;
    } */

    /* Glassmorphism Card Style */
    .form-card {
        border: 1px solid rgba(255, 255, 255, 0.7) !important;
        background: rgba(255, 255, 255, 0.8) !important;
        backdrop-filter: blur(12px);
        -webkit-backdrop-filter: blur(12px);
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.03) !important;
        border-radius: 20px !important;
        overflow: hidden;
    }

    /* Form Styling */
    .form-label {
        font-weight: 700;
        color: #475569;
        margin-bottom: 8px;
        font-size: 0.85rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .input-styled {
        border-radius: 12px !important;
        padding: 12px 15px !important;
        border: 1px solid #e2e8f0 !important;
        background-color: #ffffff !important;
        transition: all 0.3s ease;
    }

    .input-styled:focus {
        border-color: #5f63f2 !important;
        box-shadow: 0 0 0 4px rgba(95, 99, 242, 0.1) !important;
    }

    .btn-update {
        background: linear-gradient(90deg, #5f63f2, #7928ca);
        border: none;
        padding: 12px 40px;
        font-weight: 700;
        border-radius: 12px;
        letter-spacing: 1px;
        text-transform: uppercase;
        color: white;
        box-shadow: 0 4px 15px rgba(95, 99, 242, 0.3);
        transition: all 0.3s ease;
    }

    .btn-update:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(95, 99, 242, 0.4);
        color: white;
    }

    .breadcrumb-main {
        background: transparent;
    }
</style>

<div class="container-fluid mb-5">
    <div class="row">
        <div class="col-lg-12">
            {{-- Breadcrumb & Header --}}
            <div class="breadcrumb-main d-flex justify-content-between align-items-center py-4">
                <div class="d-flex align-items-start gap-3">
                    {{-- <h4 class="vertical-title">{{ trans('Edit') }}</h4> --}}
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb bg-transparent p-0 mb-0">
                            <li class="breadcrumb-item"><a href="#"><i class="las la-home"></i> Home</a></li>
                            <li class="breadcrumb-item active" aria-current="page">{{ trans('Edit Application Entry') }}</li>
                        </ol>
                    </nav>
                </div>
                <div class="action-btn">
                    <a href="{{ route('cms.application-entries.index') }}"
                        class="btn btn-outline-warning btn-sm radius-md px-3 shadow-sm" style="border-radius: 10px;">
                        <i class="fa fa-list me-1"></i> Back to List
                    </a>
                </div>
            </div>
            <x-error-alart />
        </div>
    </div>

    <div class="row justify-content-center">
        <div class="col-xxl-8 col-xl-10">
            <div class="card form-card">
                <div class="card-body p-40 p-lg-5">
                    <form action="{{ route('cms.application-entries.update', $applicationEntry->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="row g-4">
                            {{-- Date --}}
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="date" class="form-label">Date <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control flatdate input-styled shadow-sm" name="date" id="date"
                                        placeholder="Date" value="{{ old('date', $applicationEntry->date) }}">
                                </div>
                            </div>

                            {{-- Application Type --}}
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="type" class="form-label">Application Type <span class="text-danger">*</span></label>
                                    <select class="form-control tom-select input-styled" name="type" id="type">
                                        <option value="Deed Document" {{ old('type', $applicationEntry->type) == 'Deed Document' ? 'selected' : '' }}>Deed Document</option>
                                        <option value="NOC" {{ old('type', $applicationEntry->type) == 'NOC' ? 'selected' : '' }}>NOC</option>
                                    </select>
                                </div>
                            </div>

                            {{-- Customer --}}
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="customer_id" class="form-label">Customer <span class="text-danger">*</span></label>
                                    <select class="form-control tom-select input-styled" name="customer_id" id="customer_id" required>
                                        <option value="">Select a Customer</option>
                                        @foreach ($customers as $item)
                                            <option value="{{ $item->id }}" {{ old('customer_id', $applicationEntry->customer_id) == $item->id ? 'selected' : '' }}>
                                                {{ $item->company_name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            {{-- Description --}}
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="description" class="form-label">Description <span class="text-danger">*</span></label>
                                    <textarea name="description" id="description" class="form-control input-styled shadow-sm" rows="5" 
                                        placeholder="Enter application details or remarks...">{{ old('description', $applicationEntry->description) }}</textarea>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-end mt-5">
                            <button type="submit" class="btn btn-primary btn-update shadow-lg">
                                <i class="las la-save me-2"></i> Update Application
                            </button>
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