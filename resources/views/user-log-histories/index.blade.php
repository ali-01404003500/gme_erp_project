@section('title', 'Product Catalog List')
@section('description', 'Product Catalog List')
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
                                        {{ trans('menu.user-log-history-menu-title') }}</li>
                                </ol>
                            </nav>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12" style="padding-bottom: 20px">
                    <h4 class="text-capitalize breadcrumb-title">{{ trans('User Log History') }}</h4>
                </div>
                <div class="col-md-12">
                    <div class="card mb-4">
                        <div class="card-body">
                            <table id="zero-config"class="table dt-table-hover" data-page='@include('utils.table_paginate', ['data' => $userLogHistories])'
                                style="width:100%">
                                <thead>
                                    <tr>
                                        <th>Title</th>
                                        <th>Activity</th>
                                        <th>Times</th>
                                        <th class="no-content">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($userLogHistories as $userLogHistory)
                                        {{-- @dd($userLogHistory); --}}
                                        <tr>
                                            <td>{{ Str::ucfirst(explode('\\', $userLogHistory->title)[count(explode('\\', $userLogHistory->title)) - 1] )}} {{  Str::ucfirst($userLogHistory->action) }}</td>
                                            <td>{{ $userLogHistory->action }}</td>
                                            <td>{{ $userLogHistory->count }}</td>
                                            <td>
                                                <div class="btn-group btn-group-sm" role="group" aria-label="Small button group">
                                                    @if(hasPermission('user-log-histories.show'))
                                                        <a class="btn btn-outline-primary" href=""><i class="fas fa-eye"></i></a>
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
    </script>
@endsection
