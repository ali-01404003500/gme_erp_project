@section('title', 'Edit Application Entry')
@section('description', 'Edit Application Entry')
@extends('layout.app')

@section('content')
<style>
    
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
        <div class="col-xl-12 col-xl-12">
            <div class="card form-card">
                <div class="card-body p-40 p-lg-5">
                    <form action="{{ route('cms.application-entries.update', $applicationEntry->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="row g-4">
                           
                             {{-- Customer --}}
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="customer_id" class="form-label">Customer <span class="text-danger">*</span></label>
                                    <select class="form-control  input-styled" name="customer_id" id="customer_id" required> 
                                        <option value="{{ $applicationEntry->customer_id}}" selected>
                                            {{ $applicationEntry->customer->company_name }}
                                        </option>
                                    </select>
                                </div>
                            </div>
                            
                            {{-- Application Type --}}
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="type" class="form-label">Application Type <span class="text-danger">*</span></label>
                                    <select class="form-control tom-select input-styled" name="type" id="type">
                                        <option value="Deed Document" {{ old('type', $applicationEntry->type) == 'Deed Document' ? 'selected' : '' }}>Deed Document</option>
                                        <option value="NOC" {{ old('type', $applicationEntry->type) == 'NOC' ? 'selected' : '' }}>NOC</option>
                                    </select>
                                </div>
                            </div>

                            
                            {{-- Date --}}
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="date" class="form-label">Date <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control flatdate input-styled shadow-sm" name="date" id="date"
                                        placeholder="Date" value="{{ old('date', $applicationEntry->date) }}">
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

    $(document).ready(function () {
     

            const companySelect = new TomSelect("#customer_id", {
                valueField: "id",
                labelField: "text",
                searchField: [], 
                load: function(query, callback) {

                    if (!query.length || query.length < 2) return callback();

                    $.ajax({
                        url: "{{ route('sales.sales-orders-autocomplete.customers') }}",
                        type: "GET",
                        data: { search: query },
                        success: function(res) {
                            companySelect.clearOptions(); 
                            callback(res.map(item => ({ id: item.id, text: item.label   })));
                        },
                        error: function() {
                            callback();
                        }
                    });
                }
            }); 
     
  
            
        });

@endsection