@section('title', 'Issue Products List')
@section('description', 'Issue Products List')
@extends('layout.app')
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
                                        {{ trans('menu.issue-product-list-menu-title') }}</li>
                                </ol>
                            </nav>
                        </div>
                        <div class="breadcrumb-main__wrapper">
                            <div class="action-btn mt-sm-0 mt-15">
                                @if (hasPermission('inv.issue-products.create'))
                                    <a class="btn btn-xs btn-primary me-1"
                                        href="{{ route('inv.issue-products.create') }}">
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
                            <h4 class="text-capitalize breadcrumb-title">{{ trans('menu.issue-product-list-menu-title') }}
                            </h4>
                        </div>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="card mb-4">
                        <div class="card-body">
                            <table id="zero-config" class="table dt-table-hover" data-page='@include('utils.table_paginate', ['data' => $issue_products])'
                                style="width:100%">
                                <thead>
                                    <tr>
                                        <th class="text-center" style="width: 8%">Sl</th>
                                        <th class="text-center">Warehouse</th>
                                        <th class="text-center">purpose</th>
                                        <th class="text-center">Order Number</th>
                                        <th class="text-center no-content">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @csrf
                                    @foreach ($issue_products as $key => $issue_product)
                                        <tr>
                                            <td class="text-center">{{ $key + 1 }}</td>
                                            {{-- <td class="text-center">{{ $issue_product->warehouse->name }}</td> --}}
                                            <td class="text-center">
                                                <a href="{{ route('inv.issue-products.show', $issue_product->id) }}">{{ $issue_product->warehouse->name }}</a>
                                            </td>
                                            <td class="text-center">{{ $issue_product->purpose_id }}</td>
                                            <td class="text-center">{{ $issue_product->order_number }}</td>
                                            {{-- <td class="text-center">{{ $issue_product->purpose->name }}</td> --}}
                                            <td class="text-center">
                                                <div class="btn-group btn-group-sm" role="group"
                                                    aria-label="Small button group">

                                                    @if (hasPermission('inv.issue-products.update'))
                                                        <a class="btn btn-outline-warning"
                                                            href="{{ route('inv.issue-products.edit', $issue_product->id) }}"><i
                                                                class="far fa-edit"></i></a>
                                                    @endif

                                                    @if (hasPermission('inv.issue-products.destroy'))
                                                        <button type="button"
                                                            data-action="{{ route('inv.issue-products.destroy', $issue_product->id) }}"
                                                            class="btn btn-outline-danger delete-confirm"><i
                                                                class="far fa-trash-alt"></i></button>
                                                    @endif

                                                    @if (hasPermission('inv.issue-products.show'))
                                                        <a class="btn btn-outline-primary"
                                                            href="{{ route('inv.issue-products.show', $issue_product->id) }}"><i
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

    <!-- Edit Modal -->

    </div>
@endsection
<!-- CONTENT AREA -->
@section('page_scripts')

    <script>
        $(document).ready(function(e) {
            $(document).on('click', '.btn-edit', function() {
                console.log($(this).data('name'));
                $('#name').val($(this).data('name'));
                $('#code').val($(this).data('code'));
                $("#editFrom").attr("action", $(this).data('action'));
            });
        });

        // function edit(element) {
        //     let name = $(element).data('name');
        //     let code = $(element).data('code');
        //     let action = $(element).data('action');
        //     $('#name').val(name);
        //     $('#code').val(code);
        //     $("#editFrom").attr("action", action);
        // }
    </script>
@endsection
