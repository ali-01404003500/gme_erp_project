@section('title', 'KPI List')
@section('description', 'Key Performance Indicators')
@extends('layout.app')

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
                                    <li class="breadcrumb-item active" aria-current="page">KPI List</li>
                                </ol>
                            </nav>
                        </div>
                        <div class="action-btn mt-sm-0 mt-15 d-flex align-items-center">
                            @if (hasPermission('hrm.kpis.kpi-setups.create'))
                                <a href="{{ route('hrm.kpis.kpi-setups.create', app()->getLocale()) }}"
                                    class="btn px-20 btn-primary btn-sm">
                                    <i class="las la-plus fs-16"></i>Add New
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Table Filters -->
            <div class="row">
                <div class="col-md-12" style="padding-bottom: 20px">
                    <h4 class="text-capitalize breadcrumb-title">KPI List</h4>
                </div>
                {{-- <div class="col-md-12 my-4">
                    <div class="card">
                        <div class="card-body">
                            <form>
                                <div class="col-sm-12">
                                    <table class="table table-bordered">
                                        <tr>
                                            <td colspan="2">
                                                <div class="input-daterange input-group">
                                                    <input type="text" class="form-control datePicker" name="from"
                                                        value="{{ request('from') }}" autocomplete="off"
                                                        placeholder="From" />
                                                    <span class="input-group-text">
                                                        <i class="fa fa-exchange-alt"></i>
                                                    </span>
                                                    <input type="text" class="form-control datePicker" name="to"
                                                        value="{{ request('to') }}" autocomplete="off" placeholder="To" />
                                                </div>
                                            </td>
                                            <td colspan="3" class="text-right">
                                                <div class="btn-group btn-corner">
                                                    <button class="btn btn-xs btn-primary"><i class="fa fa-search"></i> Search</button>
                                                    <a href="{{ request()->url() }}" class="btn btn-xs btn-warning"><i class="fa fa-refresh"></i> Refresh</a>
                                                </div>
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                            </form>
                        </div>
                    </div>
                </div> --}}

                <!-- KPI Table -->
                <div class="col-md-12">
                    <div class="card mb-4">
                        <div class="card-body">
                            <table id="zero-config" class="table dt-table-hover"
                                   data-page='@include("utils.table_paginate", ["data" => $kpiSetups])'
                                   style="width:100%">
                                <thead>
                                    <tr>
                                        <th>SL</th>
                                        <th>Designation</th>
                                        <th>Created At</th>
                                        <th>Total KPI Value </th>
                                        <th class="no-content">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($kpiSetups as $kpi)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $kpi->designation->name ?? 'N/A' }}</td>
                                            <td>{{ $kpi->created_at->format('d-m-Y') }}</td>
                                            <td>{{ $kpi->total_weight }}</td>
                                           
                                            <td>
                                                <div class="btn-group btn-group-sm">
                                                    @if (hasPermission('hrm.kpis.kpi-setups.update'))
                                                        <a href="{{ route('hrm.kpis.kpi-setups.edit', $kpi->id) }}"
                                                            class="btn btn-outline-warning"><i class="far fa-edit"></i></a>
                                                    @endif
                                                    @if (hasPermission('hrm.kpis.kpi-setups.destroy'))
                                                        <button type="button" class="btn btn-outline-danger delete-confirm"
                                                            data-action="{{ route('hrm.kpis.kpi-setups.destroy', $kpi->id) }}">
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
    <script>
        $(".datePicker").datepicker({
            format: 'dd-mm-yyyy',
            autoclose: true
        });

        $(".delete-confirm").on("click", function () {
            const url = $(this).data("action");

            Swal.fire({
                title: "Are you sure?",
                text: "This KPI will be permanently deleted.",
                icon: "warning",
                showCancelButton: true,
                confirmButtonText: "Yes, delete it!",
                cancelButtonText: "Cancel",
            }).then((result) => {
                if (result.isConfirmed) {
                    const form = $(".delete-form");
                    form.attr("action", url);
                    form.submit();
                }
            });
        });
    </script>
@endsection
