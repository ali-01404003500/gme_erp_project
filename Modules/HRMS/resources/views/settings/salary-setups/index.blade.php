@section('title', 'Salary Setup List')
@section('description', 'Salary Setup List')
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
                                        {{ trans('menu.salary-setup-list-menu-title') }}</li>
                                </ol>
                            </nav>
                        </div>
                        <div class="breadcrumb-main__wrapper">
                            <div class="action-btn mt-sm-0 mt-15 d-flex align-items-center">
                                @if (hasPermission('hrm.settings.salary-setups.create'))
                                    <a href="{{ route('hrm.settings.salary-setups.create') }}" class="btn px-20 btn-primary btn-sm">
                                        <i class="las la-plus fs-16"></i>Add New
                                    </a>
                                @endif
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
                    <h4 class="text-capitalize breadcrumb-title">{{ trans('menu.salary-setup-list-menu-title') }}</h4>
                </div>

                <div class="col-md-12">
                    <div class="card mb-4">
                        <div class="card-body">
                            <table id="zero-config" class="table dt-table-hover" data-page='@include('utils.table_paginate', ['data' => $salarySetups])'
                                style="width:100%">
                                <thead>
                                    <tr>
                                        <th>Sl</th>
                                        <th>Title</th>
                                        <th>Effective Date</th>
                                        <th>Basic(%)</th>
                                        <th>House Rent(%)</th>
                                        <th>Conveyance(% / Tk.)</th>
                                        <th>Medical(% / Tk.)</th>
                                        <th>Others(% / Tk.)</th>
                                        <th>Status</th>
                                        <th class="no-content">Action</th>
                                    </tr>
                                </thead>
                                <tbody>

                                    @foreach ($salarySetups as $value)
                                        <tr>
                                        <td class="text-center">{{ ($salarySetups->currentPage() - 1) * $salarySetups->perPage() + $loop->iteration  }}</td>
                                            <td>
                                                {{ $value->title }}
                                            </td>
                                            <td>{{ $value->effective_date }}</td>
                                            <td>{{ number_format($value->basic) }}</td>
                                            <td>{{  number_format($value->house_rent) }}</td>
                                            <td>{{  number_format($value->conveyance) }}</td>
                                            <td>{{  number_format($value->medical) }}</td>
                                            <td>{{  number_format($value->others) }}</td>
                                            <td>
                                                @if ($value->status == '0')
                                                    <span class="badge badge-round badge-warning">De-Active</span>
                                                @elseif($value->status == '1')
                                                    <span class="badge badge-round badge-success">Active</span>
                                                @endif
                                            </td>

                                            <td class="text-center">
                                                <div class="btn-group btn-group-sm" role="group"
                                                    aria-label="Small button group">

                                                    @if (hasPermission('hrm.settings.salary-setups.update'))
                                                        <a class="btn btn-outline-warning"
                                                            href="{{ route('hrm.settings.salary-setups.edit', $value->id) }}"
                                                            title="Edit"><i class="far fa-edit"></i></a>
                                                    @endif
                                                  
                                                    @if (hasPermission('hrm.settings.salary-setups.destroy'))
                                                        <button type="button"
                                                            data-action="{{ route('hrm.settings.salary-setups.destroy', $value->id) }}"
                                                            class="btn btn-outline-danger delete-confirm"
                                                            title="Delete"><i class="far fa-trash-alt"></i></button>
                                                    @endif

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
