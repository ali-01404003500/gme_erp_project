@extends('layout.app')

@section('title', 'Score Wise Suggestions List')
@section('description', 'Score-to-Grade Mappings')

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
                                <li class="breadcrumb-item active" aria-current="page">Score Suggestions List</li>
                            </ol>
                        </nav>
                    </div>
                    <div class="action-btn mt-sm-0 mt-15 d-flex align-items-center">
                        @if (hasPermission('hrm.kpis.score-wise-suggestions.create'))
                            <a href="{{ route('hrm.kpis.score-wise-suggestions.create') }}"
                                class="btn px-20 btn-primary btn-sm">
                                <i class="las la-plus fs-16"></i>Add New
                            </a>
                        @endif
                    </div>
                </div>
            </div>
        </div>


        <div class="row">
             <div class="col-md-12 mb-3">
                    <h4 class="text-capitalize breadcrumb-title">Score Wise Suggestions List</h4>
                    <x-error-alart />
                </div>
            <div class="col-md-12">
                <div class="card mb-4">
                    <div class="card-body">
                        <table id="zero-config" class="table dt-table-hover" style="width:100%" data-page='@include('utils.table_paginate', ['data' => $scoreSuggestions])'>
                            <thead>
                                <tr>
                                    <th>SL</th>
                                    <th>Score Range</th>
                                    <th>Rating/Grade</th>
                                    <th>Remarks</th>
                                    <th>Training Need</th>
                                    <th class="no-content">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($scoreSuggestions as $suggestion)
                                    <tr>
                                        <td class="text-center">{{ ($scoreSuggestions->currentPage() - 1) * $scoreSuggestions->perPage() + $loop->iteration  }}</td>
                                        <td>{{ $suggestion->min_score }} - {{ $suggestion->max_score }}</td>
                                        <td>{{ $suggestion->rating_grade }}</td>
                                        <td>{{ $suggestion->remarks }}</td>
                                        <td>{{ $suggestion->training_need }}</td>
                                        <td>
                                            <div class="btn-group btn-group-sm">
                                                @if (hasPermission('hrm.kpis.score-wise-suggestions.update'))
                                                    <a href="{{ route('hrm.kpis.score-wise-suggestions.edit', $suggestion->id) }}"
                                                        class="btn btn-outline-warning"><i class="far fa-edit"></i></a>
                                                @endif
                                                @if (hasPermission('hrm.kpis.score-wise-suggestions.destroy'))
                                                    <button type="button" class="btn btn-outline-danger delete-confirm"
                                                        data-action="{{ route('hrm.kpis.score-wise-suggestions.destroy', $suggestion->id) }}">
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
    $(".delete-confirm").on("click", function () {
        const url = $(this).data("action");

        Swal.fire({
            title: "Are you sure?",
            text: "This score suggestion will be permanently deleted.",
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