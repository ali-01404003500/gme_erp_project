@section('title',"SMS Template List")
@section('description',"SMS Template List")
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
                                <li class="breadcrumb-item active" aria-current="page">{{ trans('menu.sms-template-list-menu-title') }}</li>
                            </ol>
                        </nav>
                    </div>
                    <div class="breadcrumb-main__wrapper">
                        <div class="action-btn mt-sm-0 mt-15 d-flex align-items-center">
                            @if (hasPermission('sms.templates.create'))
                            <a href="{{ route('sms.templates.create') }}" class="btn px-20 btn-primary btn-sm">
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
        <div class="row">
            <div class="col-md-12">
                <h4 class="text-capitalize breadcrumb-title">{{ trans('menu.sms-template-list-menu-title') }}</h4>
            </div>
            <div class="col-md-12">
                <div class="col-md-12 my-4">
                    <div class="card">
                        <div class="card-body">
                            <form>
                                <div class="col-sm-12">
                                    <table class="table table-bordered">
                                        <tr>
                                            <td>
                                                <select name="service_name_id" id="service_name_id" class="form-control tom-select"
                                                    data-placeholder="Select Service Name">
                                                    <option value=""></option>
                                                    @foreach ($serviceNames as $key => $value)
                                                        <option {{ request('service_name_id') == $value->id ? 'selected' : '' }}
                                                            value="{{ $value->id }}">
                                                            {{ optional($value)->name }}</option>
                                                    @endforeach
                                                </select>
                                            </td>
                                            <td>
                                                <select name="trigger_name_id" id="trigger_name_id" class="form-control tom-select"
                                                    data-placeholder="Select Trigger Name">
                                                    <option value=""></option>
                                                    @foreach ($triggerNames as $key => $value)
                                                        <option {{ request('trigger_name_id') == $value->id ? 'selected' : '' }}
                                                            value="{{ $value->id }}">
                                                            {{ optional($value)->name }}</option>
                                                    @endforeach
                                                </select>
                                            </td>
                                            <td>
                                                <input type="text" class="form-control" name="template_title" id="template_title"
                                                    placeholder="Enter SMS template title" value="{{ request('template_title') }}">
                                            </td>
                                            
                                            <td  class="text-right">
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
                        <table id="zero-config"class="table dt-table-hover" data-page='@include('utils.table_paginate', ['data' => $smsTemplates])' style="width:100%">
                            <thead>
                                <tr>
                                    <th>Sl</th>
                                    <th>Service Name</th>
                                    <th>Trigger Name</th>
                                    <th>SMS Template Title</th>
                                    <th class="no-content">Action</th>
                                </tr>
                            </thead>
                            <tbody>

                                @foreach ($smsTemplates as $value)
                                    <tr>
                                            <td class="text-center">{{ ($smsTemplates->currentPage() - 1) * $smsTemplates->perPage() + $loop->iteration  }}</td>
                                        <td>
                                            {{ optional($value->serviceNames)->name }}
                                        </td>
                                        <td>{{ optional($value->triggerNames)->name }}</td>
                                        <td>{{ $value->template_title }}</td>
                                      
                                        <td>
                                            <div class="btn-group btn-group-sm" role="group" aria-label="Small button group">
                                                    @if (hasPermission('sms.templates.update'))
                                                        <a class="btn btn-outline-warning" href="{{ route('sms.templates.edit', $value->id) }}"><i
                                                                class="far fa-edit"></i></a>
                                                    @endif

                                                    @if (hasPermission('sms.templates.destroy'))
                                                        <button type="button" data-action="{{ route('sms.templates.destroy', $value->id) }}"
                                                            class="btn btn-outline-danger delete-confirm"><i
                                                                class="far fa-trash-alt"></i></button>
                                                    @endif

                                                    @if (hasPermission('sms.templates.show'))
                                                        <a class="btn btn-outline-primary" href="{{ route('sms.templates.show', $value->id) }}"><i class="fas fa-eye"></i></a>
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
    <script>
        $(".datePicker").datepicker({
            format: 'dd-mm-yyyy',
            autoclose: true
        });
    </script>
@endSection