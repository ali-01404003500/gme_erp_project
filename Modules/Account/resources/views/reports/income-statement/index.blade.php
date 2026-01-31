@extends('layout.app')
@section('title'," Income Statements")
@section('description'," Income Statements")
@section('content')
    <!-- CONTENT AREA -->
    <div class="container-fluid">
        <div class="social-dash-wrap">
            <div class="row">
                <div class="col-lg-12">
                    <div class="breadcrumb-main">
                        <div class="breadcrumb-action justify-content-center flex-wrap">
                            <nav aria-label="breadcrumb">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="/"><i class="las la-home"></i>Home</a></li>
                                    <li class="breadcrumb-item active" aria-current="page">
                                        {{ trans('menu.Income Statements') }}</li>
                                </ol>
                            </nav>
                        </div>
                        <div class="breadcrumb-main__wrapper">
                            <div class="action-btn mt-sm-0 mt-15 d-flex align-items-center">
                              
                                <a href="{{ route('account.report.income-statement') }}?export_type=pdf&{{ http_build_query(request()->except('export_type', '_token')) }}" target="_blank"
                                    class="btn btn-danger btn-sm d-inline-block mr-2" style="margin-left: 5px;">
                                    <i class="las la-file-pdf fs-16"></i> PDF
                                </a>
                                <a href="{{ route('account.report.income-statement') }}?export_type=excel&{{ http_build_query(request()->except('export_type', '_token')) }}" target="_blank"
                                    class="btn btn-success btn-sm d-inline-block" style="margin-left: 5px;">
                                    <i class="las la-file-excel fs-16"></i> Excel
                                </a> 
                            </div>
                        </div>
                    </div>
                </div>
            </div>


            <div class="row">
                <div class="col-md-12" style="padding-bottom: 20px">
                    <div class="row" style="width: 100%">
                        <div class="col-md-6">
                            <h4 class="text-capitalize breadcrumb-title">{{ trans('Income Statements') }}
                            </h4>
                        </div>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="col-md-12 my-4">
                        <div class="card">
                            <div class="card-body">
                                <form>
                                    <div class="col-sm-12">
                                        <table class="table table-bordered">
                                            <tr>
                                                <td>
                                                    <input type="text" class="form-control flatmonthrange" name="date_range" value="{{ request('date_range') }}" placeholder="Enter Month Year Range">
                                                </td>
                                                <td>
                                                    <label class="block" style="margin-top: 5px;">
                                                        <input name="is_details" type="checkbox" class="ace input-lg" value="1" {{ request('is_details') == 1 ? 'checked' : '' }}>
                                                        <span class="lbl bigger-120"> Details</span>
                                                    </label>
                                                </td>
                                                <td>
                                                    <button type="submit" class="btn btn-primary btn-default btn-squared radius-md shadow2 btn-sm">
                                                        Submit
                                                    </button>
                                                </td>
                                            </tr>
                                        </table>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    <div class="card mb-4">
                        <div class="card-body">
                            <div class="table-responsive">
                                <div class="card mb-4">
                                    <div class="card-body">
                                        <div class="table-responsive">
                                            @if (request()->filled('is_details'))

                                            @include('Account::reports/income-statement/details-view')
                                        @else
                    
                                            @include('Account::reports/income-statement/sort-view')
                    
                                        @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('page_scripts')


@endsection