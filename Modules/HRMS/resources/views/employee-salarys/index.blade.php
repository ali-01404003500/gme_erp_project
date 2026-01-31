@section('title', 'Salary List')
@section('description', 'Salary  List')
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
                                    <li class="breadcrumb-item"><a href="#"><i class="las la-home"></i>Home</a></li>
                                    <li class="breadcrumb-item active" aria-current="page">
                                        {{ trans('Salary List') }}</li>
                                </ol>
                            </nav>
                        </div>
                        <div class="breadcrumb-main__wrapper">
                            <div class="action-btn mt-sm-0 mt-15 d-flex align-items-center">
                                <a href="{{ request()->fullUrlWithQuery(['export' => 'pdf']) }}" target="_blank"
                                    class="btn btn-danger btn-sm mr-5" style="margin-left: 5px;">
                                    <i class="las la-file-pdf fs-16"></i> PDF
                                </a>
                            </div>
                        </div>

                    </div>
                </div>
            </div>

            <style>
                .nav-icon la la-cart-arrow-down {
                    font-size: 26px;
                }
            </style>

            <div class="row">
                <div class="col-md-12">
                    <h4 class="text-capitalize breadcrumb-title">{{ trans('Salary List') }}</h4>
                </div>

                <div class="col-md-12">
                    <div class="card mb-4">
                        <div class="card-body">
                            <table id="zero-config" class="table dt-table-hover" data-page='@include('utils.table_paginate', ['data' => $employeeSalaries])'
                                style="width:100%">
                                <thead >
                                    <tr>
                                        <th class="text-center">Sl</th>
                                        <th class="text-center">Effect From</th>
                                        <th class="text-center">Basic</th>
                                        <th class="text-center">House Rent</th>
                                        <th class="text-center">Conveyance</th>
                                        <th class="text-center">Medical</th>
                                        <th class="text-center">Others</th>
                                        <th class="text-center">Gross</th>
                                        <th class="text-center">Status</th>
                                        <th class="text-center">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($employeeSalaries as $key => $value)
                                        <tr>
                                            <td class="text-center">{{ $key + 1 }}</td>
                                            <td class="text-center">{{ $value->effective_date }}</td>
                                            <td class="text-center">{{ number_format($value->basic) }}</td>
                                            <td class="text-center">{{ number_format($value->house_rent) }}</td>
                                            <td class="text-center">{{ number_format($value->conveyance) }}</td>
                                            <td class="text-center">{{ number_format($value->medical) }}</td>
                                            <td class="text-center">{{ number_format($value->others) }}</td>
                                            <td class="text-center">{{ number_format($value->gross) }}</td>
                                            <td class="text-center">
                                                <span class="badge badge-round badge-{{ $value->status == 1 ? 'success' : 'danger' }}">{{ $value->status == 1 ? 'Active' : 'Inactive' }}</span>
                                            </td>
                                            <td class="text-center">
                                                <div class="btn-group btn-group-sm" role="group">
                                                    <a href="{{ route('hrm.employee-salarys.create', ['employee_id' => $value->employee_id, 'salary_id' => $value->id]) }}" class="btn btn-primary btn-sm">
                                                        <i class="fa fa-edit"></i>
                                                    </a>
                                                    <button type="button"
                                                            data-action="{{ route('hrm.employee-salarys.destroy', $value->id) }}"
                                                            class="btn btn-danger delete-confirm"
                                                            title="Delete"><i class="far fa-trash-alt"></i></button>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>


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
@section('page_scripts')


@endSection
