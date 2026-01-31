@extends('layout.app')

@section('title', 'Responsibilities List')
@section('description', 'List of Responsibilities for KPI Templates')

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
                                <li class="breadcrumb-item active" aria-current="page">Responsibilities List</li>
                            </ol>
                        </nav>
                    </div>
                    <div class="action-btn mt-sm-0 mt-15 d-flex align-items-center">
                        @if (hasPermission('hrm.kpis.responsibility-entries.create'))
                            <a href="{{ route('hrm.kpis.responsibility-entries.create') }}"
                                class="btn px-20 btn-primary btn-sm">
                                <i class="las la-plus fs-16"></i>Add New
                            </a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
         <div class="col-md-12 mb-3">
                    <h4 class="text-capitalize breadcrumb-title">Responsibilities List</h4>
                    <x-error-alart />
                </div>

        <div class="row">
            <div class="col-md-12">
                <div class="card mb-4">
                    <div class="card-body">
                        <table id="zero-config" class="table dt-table-hover" style="width:100%"  data-page='@include('utils.table_paginate', ['data' => $responsibilityEntrys])'>
                            <thead>
                                <tr>
                                    <th>SL</th>
                                    <th>Code</th>
                                    <th>Responsibilities</th>
                                    <th>Weight</th>
                                    <th>Target Days</th>
                                    <th>Frequency</th>
                                    <th>Status</th>
                                    <th class="no-content">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($responsibilityEntrys as $responsibility)
                                    <tr>
                                        <td class="text-center">{{ ($responsibilityEntrys->currentPage() - 1) * $responsibilityEntrys->perPage() + $loop->iteration  }}</td>
                                        <td>{{ $responsibility->code }}</td>
                                        <td>{{ $responsibility->description }}</td>
                                        <td>{{ $responsibility->weight }}</td>
                                        <td>{{ $responsibility->time }}</td>
                                        <td>{{ $responsibility->frequency }}</td>
                                        <td>{{ $responsibility->status }}</td>
                                        <td>
                                            <div class="btn-group btn-group-sm">
                                                @if (hasPermission('hrm.kpis.responsibility-entries.update'))
                                                    <a href="{{ route('hrm.kpis.responsibility-entries.edit', $responsibility->id) }}"
                                                        class="btn btn-outline-warning"><i class="far fa-edit"></i></a>
                                                @endif
                                                @if (hasPermission('hrm.kpis.responsibility-entries.destroy'))
                                                    <button type="button" class="btn btn-outline-danger delete-confirm"
                                                        data-action="{{ route('hrm.kpis.responsibility-entries.destroy', $responsibility->id) }}">
                                                        <i class="far fa-trash-alt"></i>
                                                    </button>
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
