@section('title', 'Job Template List')
@section('description', 'Job Template List')
@extends('layout.app')
@section('content')
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
                                    <li class="breadcrumb-item"><a href="#"><i class="las la-home"></i>Home</a></li>
                                    <li class="breadcrumb-item active" aria-current="page">
                                        {{ trans('Job Template List') }}
                                    </li>
                                </ol>
                            </nav>
                        </div>
                        <div class="breadcrumb-main__wrapper">
                            <div class="action-btn mt-sm-0 mt-15">
                                @if (hasPermission('hrm.job-templates.create'))
                                    <a href="{{ route('hrm.job-templates.create') }}" class="btn btn-xs btn-primary me-1">
                                        Add New
                                    </a>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>


            <div class="row">
                <div class="col-md-12" style="padding-bottom: 20px">
                    <div class="row" style="width: 100%">
                        <div class="col-md-6">
                            <h4 class="text-capitalize breadcrumb-title">{{ trans('Job Template List') }}</h4>
                        </div>
                    </div>
                    <x-error-alart />
                </div>
                <div class="col-md-12 my-4">
                    <div class="card">
                        <div class="card-body">
                            <form>
                                <div class="col-sm-12">
                                    <table class="table table-bordered">
                                        <tr>
                                            <td>
                                                <input type="text" name="title" class="form-control input-sm"
                                                    placeholder="Title" value="{{ request('title') }}">
                                            </td>
                                            <td>
                                                <select name="branch_id" id="branch_id" class="form-control tom-select"
                                                    data-placeholder="Select Branch">
                                                    <option value=""> </option>
                                                    @foreach ($branches as $key => $value)
                                                        <option {{ request('branch_id') == $value->id ? 'selected' : '' }}
                                                            value="{{ $value->id }}">
                                                            {{ $value->name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </td>
                                            <td>
                                                <select name="department_id" id="department_id"
                                                    class="form-control tom-select" data-placeholder="Select Department">
                                                    <option value=""> </option>
                                                    @foreach ($departments as $key => $value)
                                                        <option {{ request('department_id') == $value->id ? 'selected' : '' }}
                                                            value="{{ $value->id }}">
                                                            {{ $value->name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </td>
                                            <td>
                                                <select name="designation_id" id="designation_id"
                                                    class="form-control tom-select" data-placeholder="Select Designation">
                                                    <option value=""> </option>
                                                    @foreach ($designations as $key => $value)
                                                        <option {{ request('designation_id') == $value->id ? 'selected' : '' }}
                                                            value="{{ $value->id }}">
                                                            {{ $value->name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </td>
                                            <td colspan="5" class="text-right">
                                                <div class="btn-group btn-corner">
                                                    <button class="btn btn-xs btn-primary"><i class="fa fa-search"></i>
                                                        Search</button>
                                                    <a href="{{ request()->url() }}" class="btn btn-xs btn-warning"><i
                                                            class="fa fa-refresh"></i> Refresh</a>
                                                </div>
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="card mb-4">
                        <div class="card-body">
                            <div class="table-responsive">
                                <style>
                                    .jobs-table-custom,
                                    .jobs-table-custom th,
                                    .jobs-table-custom td {
                                        border: 1px solid #dee2e6 !important;
                                        border-collapse: collapse !important;
                                    }

                                    .jobs-table-custom th,
                                    .jobs-table-custom td {
                                        padding: 12px;
                                        vertical-align: middle;
                                    }

                                    .jobs-table-custom thead th {
                                        background-color: #f8f9fa;
                                        border-bottom-width: 2px !important;
                                    }

                                    .table thead th {
                                        background-color: #35526e !important;
                                        color: #ffffff !important;
                                        font-weight: 600 !important;
                                        text-transform: uppercase;
                                        font-size: 0.85rem !important;
                                        letter-spacing: 0.08em;
                                        border-bottom: 2px solid #2a4054 !important;
                                        padding: 14px 16px !important;
                                        vertical-align: middle;
                                        text-align: center;
                                    }
                                </style>
                                <table id="zero-config" class="table jobs-table-custom dt-table-hover"
                                    data-page='@include('utils.table_paginate', ['data' => $jobTemplates])'
                                    style="width:100%">
                                    <thead>
                                        <tr>
                                            <th>Sl</th>
                                            <th>Title</th>
                                            <th>Branch</th>
                                            <th>Department</th>
                                            <th>Designation</th>
                                            <th>Salary</th>
                                            <th>Status</th>
                                            <th class="no-content">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($jobTemplates as $job)
                                            <tr>
                                                <td class="text-center">
                                                    {{ ($jobTemplates->currentPage() - 1) * $jobTemplates->perPage() + $loop->iteration  }}
                                                </td>
                                                <td>
                                                    <p>{{ $job->title }}</p>
                                                </td>
                                                <td>{{ @$job->branch->name }}</td>
                                                <td>{{  @$job->department->name }}</td>
                                                <td>{{ @$job->designation->name }}</td>

                                                <td>{{ $job->salary ? $job->salary : 'Negotiable' }}</td>
                                                <td>
                                                    @if ($job->status == '1')
                                                        <span class="badge badge-success badge-round">Active</span>
                                                    @else
                                                        <span class="badge badge-danger badge-round">Inactive</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    <div class="btn-group btn-group-sm" role="group"
                                                        aria-label="Small button group">
                                                        @if (hasPermission('hrm.job-templates.update'))
                                                            <a class="btn btn-outline-warning"
                                                                href="{{ route('hrm.job-templates.edit', $job->id) }}"><i
                                                                    class="far fa-edit"></i></a>
                                                        @endif
                                                        @if (hasPermission('hrm.job-templates.destroy'))
                                                            <button type="button"
                                                                data-action="{{ route('hrm.job-templates.destroy', $job->id) }}"
                                                                class="btn btn-outline-danger delete-confirm"><i
                                                                    class="far fa-trash-alt"></i></button>
                                                        @endif

                                                        {{-- @if (hasPermission('hrm.job-templates.show'))
                                                        <a class="btn btn-outline-primary"
                                                            href="{{ route('hrm.job-templates.show', $job->id) }}"><i
                                                                class="fas fa-eye"></i></a>
                                                        @endif --}}
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <div class="d-none">
                                <form class="delete-form" action="" method="POST">
                                    @csrf
                                    @method('DELETE')
                                </form>
                            </div>
                        </div>

                    </div>

                </div>
            </div>

        </div>
    </div>


@endsection
<!-- CONTENT AREA -->
@section('page_scripts')


@endsection