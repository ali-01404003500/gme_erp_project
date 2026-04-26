@section('title', "Courier List")
@section('description', "Courier List")
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
                                        {{ trans('menu.courier-list-menu-title') }}</li>
                                </ol>
                            </nav>
                        </div>
                        <div class="action-btn mt-sm-0 mt-15">
                            @if(hasPermission('sales.couriers.create'))
                                <a href="{{ route('sales.couriers.create', app()->getLocale()) }}"
                                    class="btn px-20 btn-primary ">
                                    <i class="las la-plus fs-16"></i>Add New
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12" style="padding-bottom: 20px">
                    <h4 class="text-capitalize breadcrumb-title">{{ trans('menu.courier-list-menu-title') }}</h4>
                </div>
                <div class="col-md-12">
                    <div class="card mb-4">
                        <div class="card-body">
                            <style>
                                .courier-table-custom,
                                .courier-table-custom th,
                                .courier-table-custom td {
                                    border: 1px solid #dee2e6 !important;
                                    border-collapse: collapse !important;
                                }

                                .courier-table-custom th,
                                .courier-table-custom td {
                                    padding: 12px;
                                    vertical-align: middle;
                                }

                                .courier-table-custom thead th {
                                    background-color: #f8f9fa;
                                    border-bottom-width: 2px !important;
                                }
                            </style>

                            <table id="zero-config" class="table courier-table-custom dt-table-hover"
                                data-page='@include('utils.table_paginate', ['data' => $couriers])' style="width:100%">
                                <thead>
                                    <tr>
                                        <th>Sl</th>
                                        <th>Courier Name</th>
                                        <th>Courier Branch</th>
                                        <th>Courier Contact</th>
                                        <th>Contact Person Name</th>
                                        <th>Status</th>
                                        <th class="no-content">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($couriers as $courier)
                                        <tr>
                                            <td class="text-center">
                                                {{ ($couriers->currentPage() - 1) * $couriers->perPage() + $loop->iteration  }}
                                            </td>
                                            <td>
                                                <a
                                                    href="{{ route('sales.couriers.edit', $courier->id) }}">{{ $courier->courier_name }}</a>
                                            </td>
                                            <td>
                                                {{ $courier->courier_branch }}
                                            </td>
                                            <td>
                                                {{ $courier->courier_phone }}
                                            </td>
                                            <td>
                                                {{ $courier->contact_person_name }}
                                            </td>
                                            <td>
                                                @if ($courier->status == 1)
                                                    <span class="badge badge-round badge-success">Active</span>
                                                @else
                                                    <span class="badge badge-round badge-danger">Inactive</span>
                                                @endif
                                            </td>

                                            <td>
                                                <div class="btn-group btn-group-sm" role="group"
                                                    aria-label="Small button group">
                                                    @if (hasPermission('sales.couriers.update'))
                                                        <a class="btn btn-outline-warning"
                                                            href="{{ route('sales.couriers.edit', $courier->id) }}"><i
                                                                class="far fa-edit"></i>
                                                        </a>
                                                    @endif

                                                    @if (hasPermission('sales.couriers.destroy'))
                                                        <button type="button"
                                                            data-action="{{ route('sales.couriers.destroy', $courier->id) }}"
                                                            class="btn btn-outline-danger delete-confirm"><i
                                                                class="far fa-trash-alt"></i></button>
                                                    @endif

                                                    @if (hasPermission('sales.couriers.show'))
                                                        <a class="btn btn-outline-primary"
                                                            href="{{ route('sales.couriers.show', $courier->id) }}"><i
                                                                class="fas fa-eye"></i></a>
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