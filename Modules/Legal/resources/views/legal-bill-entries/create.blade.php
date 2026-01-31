@extends('layout.app')
@section('title', 'Create Legal')
@section('description', 'Create Legal')
@section('content')

    <style>
        /* Style for all <a> tags */
        .nav-tabs.vertical-tabs .nav-item .nav-link {
            background-color: #f7ecfd;
            /* Background color */
            color: #3d3d3d;
            /* Text color */
            border-radius: 5px 5px 0 0;
            /* 5px radius for top-left and top-right corners */
        }

        /* Style for active tab */
        .nav-tabs.vertical-tabs .nav-item .nav-link.active {
            background-color: var(--color-primary);
            /* Background color */
            color: #ffffff;
            /* Text color */
        }
    </style>
    <div class="container-fluid">
        <div class="social-dash-wrap">

            <div class="row">
                <div class="col-lg-12">
                    <div class="breadcrumb-main">
                        <div class="breadcrumb-action justify-content-center flex-wrap">
                            <nav aria-label="breadcrumb">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="#"><i class="las la-home"></i>Home</a></li>
                                    <li class="breadcrumb-item active" aria-current="page">
                                        {{ trans('Create Legal Bill Entry') }}</li>
                                </ol>
                            </nav>
                        </div>
                        <div class="action-btn mt-sm-0 mt-15">
                            @if (hasPermission('legal.legal-bill-entries.index'))
                                <a href="{{ route('legal.legal-bill-entries.index') }}"
                                    class="btn btn-warning btn-default btn-squared radius-md shadow2 btn-sm"><i
                                        class="fa fa-list"></i> List</a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12 m-2">
                    <h4 class="text-capitalize breadcrumb-title">{{ trans('Create Legal Bill Entry') }}</h4>
                    <x-error-alart />
                </div>
                <div class="card mb-50">
                    <div class="row justify-content-center" id="justify-content-center">
                        <div class="col-sm-11">
                            <div class="mt-40 mb-50">
                                <h2 class="mb-3">Legal Bill Entry</h2>
                                <form action="{{ route('legal.legal-bill-entries.store', app()->getLocale()) }}"
                                    method="POST" enctype="multipart/form-data">
                                    @csrf

                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="date">Date<span class="text-danger">*</span>:</label>
                                                <input type="test" name="date" id="date"
                                                    class="form-control px-15 flatdate"
                                                    value="{{ old('date', date('Y-m-d')) }}">
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="advocate_name"> Advocate Name<span
                                                        class="text-danger">*</span>:</label>
                                                <select name="vendor_id" id="advocate_name"
                                                    class="form-control tom-select px-15">
                                                    <option value="">Select Advocate</option>
                                                    @foreach ($advocates as $advocate)
                                                        <option value="{{ $advocate->id }}">{{ $advocate->company_name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="case_name">Case No<span class="text-danger">*</span>:</label>
                                                <select name="legal_entry_id" id="case_name" class="form-control tom-select px-15">
                                                    <option value="">Select Case</option>
                                                    @foreach ($legalEntries as $case)
                                                        <option value="{{ $case->id }}" data-case_no="{{ $case->case_no }}">{{ $case->case_no }} (
                                                            @foreach ($case->convicts->unique('customer_id') as $convict)
                                                                {{ optional($convict->customer)->company_name }}
                                                                @if (!$loop->last)
                                                                    ,
                                                                @endif
                                                            @endforeach
                                                            )
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="particular">Particular<span class="text-danger">*</span>:</label>
                                                <input type="text" name="particular" id="particular"
                                                    value="{{ old('particular') }}" class="form-control px-15">
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="amount">Amount<span class="text-danger">*</span>:</label>
                                                <input type="number" name="amount" id="amount"
                                                    value="{{ old('amount') }}" class="form-control px-15" step="0.01">
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="file">File:</label>
                                                <x-file-uploader multiple name="attachment" />
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label for="description">Legal Description:</label>
                                                <textarea name="description" id="description" class="form-control px-15" rows="3">{{ old('description') }}</textarea>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="d-flex justify-content-end mt-4">
                                        <button type="submit" class="btn btn-primary btn-sm">{{ __('Save') }}</button>
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
            $(document).ready(function() {
                $('#case_name').on('change', function() {
                    var caseNo = $(this).find(':selected').data('case_no');
                    $('#particular').val(caseNo);
                });
            });
        </script>


   





        <script>
            $('.datePicker').datepicker({
                format: 'yyyy-mm-dd',
                autoclose: true
            });
        </script>
    @endSection
