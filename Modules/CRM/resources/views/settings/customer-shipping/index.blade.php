@section('title',"Customer Shipping Address")
@section('description',"Customer Shipping Address")
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
                                <li class="breadcrumb-item active" aria-current="page">{{ trans('Customer Shipping Address') }}</li>
                            </ol>
                        </nav>
                    </div>
                    <div class="breadcrumb-main__wrapper">
                        <div class="action-btn mt-sm-0 mt-15">
                            <div class="action-btn mt-sm-0 mt-15">
                                @if (hasPermission('crm.customer-shippings.create'))
                                <a href="{{ route('crm.customer-shippings.create', app()->getLocale()) }}" class="btn px-20 btn-primary ">
                                    <i class="las la-plus fs-16"></i>Add New
                                </a>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12" style="padding-bottom: 20px">
                <div class="row" style="width: 100%">
                <div class="col-md-6">
                <h4 class="text-capitalize breadcrumb-title">{{ trans('shipping address') }}</h4>
                </div>
                </div>
            </div>
            <div class="col-md-12">
                <div class="card mb-4">
                    <div class="card-body">
                        <table id="zero-config"class="table dt-table-hover" data-page='@include('utils.table_paginate', ['data' => $customerShippings])' style="width:100%">
                            <thead>
                                <tr>
                                    <th>Customer Name</th>
                                    <th>Division</th>
                                    <th>District</th>
                                    <th>Contac Person Name</th>
                                    <th>Phone</th>
                                    <th class="no-content">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                {{-- <tr>
                                    <td>name</td>
                                    <td>email</td>
                                    <td class="no-content">Action</td>
                                </tr> --}}
                                {{-- @dd($employees) --}}
                                @foreach ($customerShippings as $item)
                                    <tr>
                                        <td>{{ optional($item->customer)->company_name }}</td>
                                        <td>
                                            {{ match($item->division_id) {
                                                1 => 'Dhaka',
                                                2 => 'Chittagong',
                                                3 => 'Khulna',
                                                4 => 'Rajshahi',
                                                5 => 'Barisal',
                                                6 => 'Sylhet',
                                                7 => 'Rangpur',
                                                default => ''
                                            } }}
                                         </td>
                                        <td>{{ match($item->district_id) {
                                            1 => 'Dhaka',
                                            2 => 'Chittagong',
                                            3 => 'Khulna',
                                            4 => 'Rajshahi',
                                            5 => 'Barisal',
                                            6 => 'Sylhet',
                                            7 => 'Rangpur',
                                            default => ''
                                        } }}</td>
                                        <td>{{ $item->contact_person_name }}</td>
                                        <td>{{ $item->contact_person_phone }}</td>
                                        <td>
                                            <div class="btn-group btn-group-sm" role="group" aria-label="Small button group">
                                                    <a class="btn btn-outline-warning" href="{{ route('crm.customer-shippings.edit', $item->id) }}"><i
                                                            class="far fa-edit"></i></a>



                                                    <button type="button" data-action="{{ route('crm.customer-shippings.destroy', $item->id) }}"
                                                        class="btn btn-outline-danger delete-confirm"><i
                                                            class="far fa-trash-alt"></i></button>

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
