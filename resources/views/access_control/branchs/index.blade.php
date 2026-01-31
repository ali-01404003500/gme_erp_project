@section('title', 'Branch List')
@section('description', 'Branch List')
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
                                        {{ trans('menu.branch-list-menu-title') }}</li>
                                </ol>
                            </nav>
                        </div>
                        <div class="breadcrumb-main__wrapper">
                            <div class="action-btn mt-sm-0 mt-15 d-flex align-items-center">
                                @if (hasPermission('access_control.branchs.create'))
                                    <a href="{{ route('access_control.branchs.create') }}"
                                        class="btn btn-sm btn-primary btn-add btn-sm"><i class="las la-plus"></i>
                                        {{ trans('menu.branch-create-menu-title') }}</a>
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


            <div class="row">
                <div class="col-md-12" style="padding-bottom: 20px">
                    <div class="row" style="width: 100%">
                        <div class="col-md-6">
                            <h4 class="text-capitalize breadcrumb-title">{{ trans('menu.branch-list-menu-title') }}</h4>
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
                                                <td class="text-center">
                                                    <input type="text" class="form-control" placeholder="Search" name="name" value="{{ request('name') }}">
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
                    <div class="card mb-4">
                        <div class="card-body">
                            <table id="zero-config" class="table dt-table-hover" data-page='@include('utils.table_paginate', ['data' => $branchs])'
                                style="width:100%">
                                <thead>
                                    <tr>
                                        <th class="text-center" style="width: 8%">Sl</th>
                                        <th class="text-center">Name</th>
                                        <th class="text-center">Branch Type</th>
                                        <th class="text-center no-content">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @csrf
                                    @foreach ($branchs as $key => $branch)
                                        <tr>
                                            <td class="text-center">{{ ($branchs->currentPage() - 1) * $branchs->perPage() + $loop->iteration  }}</td>
                                            <td class="text-center">
                                                <a href="{{ route('access_control.branchs.show', $branch->id) }}">{{ $branch->name }}</a>
                                            </td>
                                            <td class="text-center">{{ @$branch->branchType->name }}</td>
                                            <td class="text-center">
                                                <div class="btn-group btn-group-sm" role="group"
                                                    aria-label="Small button group">

                                                    @if (hasPermission('access_control.branchs.update'))
                                                        <a class="btn btn-outline-warning"
                                                            href="{{ route('access_control.branchs.edit', $branch->id) }}"><i
                                                                class="far fa-edit"></i></a>
                                                    @endif

                                                    @if (hasPermission('access_control.branchs.destroy', $branch->id))
                                                        <button type="button"
                                                            data-action="{{ route('access_control.branchs.destroy', $branch->id) }}"
                                                            class="btn btn-outline-danger delete-confirm"><i
                                                                class="far fa-trash-alt"></i></button>
                                                    @endif

                                                    @if (hasPermission('access_control.branchs.show'))
                                                        <a class="btn btn-outline-primary"
                                                            href="{{ route('access_control.branchs.show', $branch->id) }}">
                                                            <i class="fas fa-eye"></i></a>
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

@endsection
<!-- CONTENT AREA -->
@section('page_scripts')

    <script>
    </script>
@endsection
