@section('title', 'Legal Notice List')
@section('description', 'Legal Notice List')
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
                                    <li class="breadcrumb-item active" aria-current="page">{{ trans('Legal Notice list') }}
                                    </li>
                                </ol>
                            </nav>
                        </div>
                        <div class="action-btn mt-sm-0 mt-15 d-flex align-items-center">
                            @if (hasPermission('legal.legal-entries.create'))
                                <a href="{{ route('legal.legal-entries.create', app()->getLocale()) }}"
                                    class="btn px-20 btn-primary btn-sm">
                                    <i class="las la-plus fs-16"></i>Add New
                                </a>
                            @endif
                           
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12" style="padding-bottom: 20px">
                    <h4 class="text-capitalize breadcrumb-title">{{ trans('Legal Notice list') }}</h4>
                </div>
                <div class="col-md-12 my-4">
                    <div class="card">
                        <div class="card-body">
                            <form>
                                <div class="col-sm-12">
                                    <table class="table table-bordered">
                                        <tr>
                                         
                                          
                                            <td colspan="2">
                                                <div class="input-daterange input-group">
                                                    <input type="text" class="form-control datePicker" name="from"
                                                        value="{{ request('from') }}" autocomplete="off"
                                                        placeholder="From" />
                                                    <span class="input-group-text">
                                                        <i class="fa fa-exchange-alt"></i>
                                                    </span>

                                                    <input type="text" class="form-control datePicker" name="to"
                                                        value="{{ request('to') }}" autocomplete="off" placeholder="To" />
                                                </div>
                                            </td>
                                            <td colspan="3" class="text-right">
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
                <div class="col-md-12">
                   
                    <div class="card mb-4">
                        <div class="card-body">
                            <table id="zero-config"class="table dt-table-hover table-bordered align-top" data-page='@include('utils.table_paginate', ['data' => $legalEntrys])'
                                style="width:100%; table-layout: fixed;">
                                <thead class="align-top">
                                    <tr>
                                        <th style="width: 4%;" class="text-wrap">SL</th>
                                        <th style="width: 10%;" class="text-wrap">Legal ID</th>
                                        <th style="width: 20%;" class="text-wrap">Customer Name</th> 
                                        <th style="width: 18%;" class="text-wrap">Convict Name</th> 
                                        <th style="width: 15%;" class="text-wrap">Complainant Name</th>
                                        <th style="width: 12%;" class="text-wrap">Advocate Name</th>
                                        <th style="width: 10%;" class="text-wrap">Prepared By</th>
                                        <th style="width: 7%;" class="text-wrap">Date</th>
                                        <th class="no-content" style="width: 4%;" class="text-wrap">Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="align-top">
                                    {{-- @dd($legalEntrys) --}}
                                    @foreach ($legalEntrys as $legal)
                                        {{-- @dd($salesOrder->delivery) --}}
                                        <tr>
                                            <td class="text-center text-wrap">{{ ($legalEntrys->currentPage() - 1) * $legalEntrys->perPage() + $loop->iteration  }}</td>

                                            <td class="text-wrap">
                                                {{ $legal->legal_id }}
                                            </td>
                                             <td class="text-wrap">
                                                @foreach ($legal->convicts as $convict )
                                                    {{ $convict->customer->company_name }},
                                                @endforeach
                                            </td>
                                            <td class="text-wrap">
                                                @foreach ($legal->convicts as $convict )
                                                    {{ $convict->convict_name }},
                                                @endforeach
                                            </td>
                                           
                                           
                                            <td class="text-wrap">
                                                {{ $legal->complainant->complainant_name }}
                                            </td>
                                            <td class="text-wrap">
                                                {{ $legal->advocate_name }}
                                            </td>
                                            <td class="text-wrap">
                                                {{ $legal->createdBy->name }}
                                            </td>
                                            <td class="text-wrap">
                                                {{ \Carbon\Carbon::parse($legal->created_at)->format('d-m-Y') }}
                                            </td>
                                            <td class="text-wrap">
                                                <div class="btn-group btn-group-sm" role="group"
                                                    aria-label="Small button group">


                                                    @if (hasPermission('legal.legal-entries.update') )
                                                        <a class="btn btn-outline-warning"
                                                            href="{{ route('legal.legal-entries.edit', $legal->id) }}"><i
                                                                class="far fa-edit"></i>
                                                        </a>
                                                    @endif

                                                    @if (hasPermission('legal.legal-entries.destroy') )
                                                        <button type="button"
                                                            data-action="{{ route('legal.legal-entries.destroy', $legal->id) }}"
                                                            class="btn btn-outline-danger delete-confirm"><i
                                                                class="far fa-trash-alt"></i>
                                                        </button>
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
