@extends('layout.app')

@section('title', 'KPI Templates List')
@section('description', 'List of Designation-wise KPI Templates')

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
                                <li class="breadcrumb-item active" aria-current="page">KPI Templates List</li>
                            </ol>
                        </nav>
                    </div>
                    <div class="action-btn mt-sm-0 mt-15 d-flex align-items-center">
                        @if (hasPermission('hrm.kpis.kpi-templates.create'))
                            <a href="{{ route('hrm.kpis.kpi-templates.create') }}"
                                class="btn px-20 btn-primary btn-sm">
                                <i class="las la-plus fs-16"></i>Add New
                            </a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-12 m-2">
                <h4 class="text-capitalize breadcrumb-title">{{ trans('KPI Template List') }}</h4>
                <x-error-alart />
            </div>
        <div class="row">
            <div class="col-md-12">
                <div class="card mb-4">
                    <div class="card-body">
                        <table id="zero-config" class="table dt-table-hover" style="width:100%" data-page='@include('utils.table_paginate', ['data' => $kpiTemplates])'>
                            <thead>
                                <tr>
                                    <th>SL</th>
                                    <th>Department</th>
                                    <th>Designation</th>
                                    <th>Status</th>
                                    <th>Total Responsibilities</th>
                                    <th class="no-content">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($kpiTemplates as $kpiTemplate)
                                    <tr>
                                        <td>{{ ($kpiTemplates->currentPage() - 1) * $kpiTemplates->perPage() + $loop->iteration  }}</td>
                                        <td>{{ $kpiTemplate->department->name ?? 'N/A' }}</td>
                                        <td>{{ $kpiTemplate->designation->name ?? 'N/A' }}</td>
                                        <td>{{ $kpiTemplate->status }}</td>
                                        <td>{{ $kpiTemplate->responsibilities->count() }}</td>
                                        <td>
                                            <div class="btn-group btn-group-sm">
                                                @if (hasPermission('hrm.kpis.kpi-templates.update'))
                                                    <a href="{{ route('hrm.kpis.kpi-templates.edit', $kpiTemplate->id) }}"
                                                        class="btn btn-outline-warning"><i class="far fa-edit"></i></a>
                                                @endif
                                                @if (hasPermission('hrm.kpis.kpi-templates.destroy'))
                                                    <button type="button" class="btn btn-outline-danger delete-confirm"
                                                        data-action="{{ route('hrm.kpis.kpi-templates.destroy', $kpiTemplate->id) }}">
                                                        <i class="far fa-trash-alt"></i>
                                                    </button>
                                                @endif
                                                @if (hasPermission('hrm.kpis.kpi-templates.show'))
                                                    <a href="{{ route('hrm.kpis.kpi-templates.show', $kpiTemplate->id) }}"
                                                        class="btn btn-outline-info"><i class="far fa-eye"></i></a>
                                                    
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>

                        <!-- Hidden Delete Form -->
                        <div class="d-none">
                            <form class="delete-form" method="POST" action="">
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

@section('page_scripts')

@endsection