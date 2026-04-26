@section('title', 'EMI List')
@section('description', 'EMI List')
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
                                    <li class="breadcrumb-item active" aria-current="page">{{ trans('EMI list') }}</li>
                                </ol>
                            </nav>
                        </div>
                        <div class="action-btn mt-sm-0 mt-15 d-flex align-items-center">
                            @if (hasPermission('account.emi-entries.create'))
                            <a href="{{ route('account.emi-entries.create') }}" class="btn px-20 btn-primary btn-sm mr-5">
                                <i class="las la-plus fs-16"></i>Add New
                            </a>
                            @endif
                            {{-- <a href="{{ request()->fullUrlWithQuery(['export' => 'pdf']) }}" target="_blank"
                                class="btn btn-danger btn-sm mr-5" style="margin-left: 5px;">
                                <i class="las la-file-pdf fs-16"></i> PDF
                            </a> --}}
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12" style="padding-bottom: 20px">
                    <h4 class="text-capitalize breadcrumb-title">{{ trans('EMI list') }}</h4>
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
                                               <div class="input-daterange input-group">
                                                        <input type="text" class="form-control datePicker" name="from"
                                                            value="{{ request('from') }}" autocomplete="off"
                                                            placeholder="From" />
                                                        <span class="input-group-text">
                                                            <i class="fa fa-exchange-alt"></i>
                                                        </span>

                                                        <input type="text" class="form-control datePicker" name="to"
                                                            value="{{ request('to') }}" autocomplete="off"
                                                            placeholder="To" />
                                                    </div>
                                                </td>
                                              
                                                <td class="text-right">
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
                            <table id="zero-config"class="table dt-table-hover table-bordered align-top" data-page='@include('utils.table_paginate', ['data' => $eMIEntrys])'
                                style="width:100%; table-layout: fixed;">                              
                                    <thead>
                                        <tr class="align-top ">
                                            <th class="text-wrap" style="width: 5%;">SL</th>
                                            
                                            <th class="text-wrap" style="width: 15%;">EMI ID</th>
                                            
                                            <th class="text-wrap" style="width: 25%;">Customer</th>
                                            
                                            <th class="text-wrap" style="width: 10%;">No Of EMI</th>
                                            
                                            <th class="text-wrap" style="width: 15%;">Prepared By</th>
                                            
                                            <th class="text-wrap" style="width: 10%;">Amount</th>
                                            
                                            <th class="text-wrap" style="width: 10%;">Date</th>
                                            
                                            <th class="text-wrap no-content" style="width: 10%;">Action</th>
                                                                            

                                        </tr>
                                </thead>
                                <tbody class="align-top">
                                    @foreach ($eMIEntrys as $emi)
                                        <tr>
                                            <td class="text-center text-wrap">{{ ($eMIEntrys->currentPage() - 1) * $eMIEntrys->perPage() + $loop->iteration  }}</td>
                                            <td class="text-wrap">
                                                {{ $emi->emi_number }}
                                            </td>
                                            <td class="text-wrap">
                                                {{ optional($emi->customer)->company_name }} <br>
                                               <span class="text-muted">Address: {{ optional($emi->customer)->address }}</span> 
                                            </td>
                                            <td class="text-wrap">
                                                {{ $emi->tenure_no  }} ({{ $emi->tenure_type }})
                                            </td>
                                            <td class="text-wrap">
                                                {{ optional($emi->createdBy)->name }}
                                            </td>
                                            <th class="text-wrap">
                                                {{ $emi->emi_amount }}
                                            </th>
                                            <td class="text-wrap">
                                                {{ $emi->created_at->format('Y M d') }}
                                            </td>
                                            
                                            <td class="text-wrap ">
                                                <div class="d-flex flex-wrap justify-content-center" style="gap: 5px;">
                                                    @if (hasPermission('account.emi-entries.update'))
                                                    <a class="btn btn-xs btn-outline-warning"
                                                        href="{{ route('account.emi-entries.edit', $emi->id) }}"><i
                                                            class="far fa-edit"></i></a>
                                                @endif

                                                @if (hasPermission('account.emi-entries.destroy'))
                                                    <button type="button"
                                                        data-action="{{ route('account.emi-entries.destroy', $emi->id) }}"
                                                        class="btn btn-xs btn-outline-danger delete-confirm"><i
                                                            class="far fa-trash-alt"></i></button>
                                                @endif

                                                {{-- @if (hasPermission('account.emi-entries.show'))
                                                    <a class="btn btn-xs btn-outline-primary"
                                                        href="{{ route('account.emi-entries.show', $emi->id) }}"><i
                                                            class="fas fa-eye"></i></a>
                                                @endif --}}

                                               
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