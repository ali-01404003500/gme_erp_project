@section('title', 'Daily Visit Plan Details')
@section('description', 'Daily Visit Plan Details')
@extends('layout.app')

@section('page-head')
    <style>
        .ts-control {
            padding: 10px;
            margin: 10px;
        }

        .table-responsive {
            padding: 10px;
            margin: 10px;
        }
    </style>
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row" id="title">
            <div class="col-lg-12">
                <div class="breadcrumb-main">
                    <div class="breadcrumb-action justify-content-center flex-wrap">
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="#"><i class="las la-home"></i>Home</a></li>
                                <li class="breadcrumb-item active" aria-current="page">
                                    {{ trans('Daily Visit Plan View') }}
                                </li>
                            </ol>
                        </nav>
                    </div>
                    <div class="d-flex justify-content-between align-items-center user-member__title">
                        <div class="row">
                            @if (hasPermission('hrm.daily-visit-plans.index'))
                                <a href="{{ route('hrm.daily-visit-plans.index') }}"
                                    class="btn btn-warning btn-default btn-squared radius-md shadow2 btn-sm">
                                    <i class="fa fa-list"></i> List
                                </a>
                            @endif 
                            
                        </div>
                    </div>
                </div>
                <div class="d-flex align-items-center user-member__title mt-30">
                    <h3 class="text-capitalize">{{ trans('Daily Visit Plan View') }}</h3>
                </div>
                <x-error-alart />
            </div>
        </div>

        <div class="row mb-4 p-10 mt-3">
            <div class="col-md-12">
                <div class="card mb-4">
                    <div class="card-header">
                        <h4 class="text-capitalize breadcrumb-title">Visit Plan Details</h4>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <tbody>
                                    <tr>
                                        <th>Company's Name</th>
                                        <td>{{ $dailyVisitPlan->company_name }}</td>
                                        <th>Phone No</th>
                                        <td>{{ $dailyVisitPlan->phone_no }}</td>
                                    </tr>
                                    <tr>
                                        <th>Visit Date</th>
                                        <td>{{ $dailyVisitPlan->date }}</td>
                                        <th>Address</th>
                                        <td>{{ $dailyVisitPlan->address }}</td>
                                    </tr>
                                    <tr>
                                        <th>Contact Person</th>
                                        <td>{{ $dailyVisitPlan->contact_person }}</td>
                                        <th>Business Type</th>
                                        <td>{{ $dailyVisitPlan->business_type }}</td>
                                    </tr>
                                    <tr>
                                        <th>Visit Purpose</th>
                                        <td colspan="3">{{ $dailyVisitPlan->visit_purpose }}</td>
                                    </tr>
                                    <tr>
                                        <th>Attachments</th>
                                        <td colspan="3">
                                             @php
                                                    $documents = is_string($dailyVisitPlan->attachment)
                                                        ? json_decode($dailyVisitPlan->attachment, true)
                                                        : $dailyVisitPlan->attachment;

                                                    // Ensure it's an array and remove null/empty values
                                                    $documents = is_array($documents) ? array_filter($documents) : [];
                                                @endphp

                                                @if (!empty($documents))
                                                    @foreach ($documents as $doc)
                                                        <a href="{{ $doc }}" target="_blank"><i class="fa fa-download" style="font-size: 24px;"></i></a>
                                                    @endforeach
                                                @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Product Requirements / Remarks</th>
                                        <td colspan="3">{{ $dailyVisitPlan->description }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div> <!-- end table -->
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
