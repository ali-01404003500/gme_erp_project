@section('title', 'Legal Bill')
@section('description', 'Legal Bill')
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
                                    <li class="breadcrumb-item active" aria-current="page">{{ trans('Legal Bill list') }}
                                    </li>
                                </ol>
                            </nav>
                        </div>
                        <div class="action-btn mt-sm-0 mt-15 d-flex align-items-center">
                            @if (hasPermission('legal.legal-bill-entries.create'))
                                <a href="{{ route('legal.legal-bill-entries.create', app()->getLocale()) }}"
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
                    <h4 class="text-capitalize breadcrumb-title">{{ trans('Legal Bill list') }}</h4>
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
                            <table id="zero-config"class="table dt-table-hover" data-page='@include('utils.table_paginate', ['data' => $legalBillEntrys])'
                                style="width:100%">
                                <thead>
                                    <tr>
                                        <th>SL</th>
                                        <th>
                                            Bill ID
                                        </th>
                                        <th>
                                            Bill Date
                                        </th>
                                        <th>
                                            Case Name
                                        </th>
                                        <th>
                                            Advocate Name
                                        </th>
                                        <th>
                                           Bill Amount
                                        </th>
                                        <th>
                                             Status
                                        </th>
                                       
                                        <th>
                                            Prepared By
                                        </th>
                                       
                                        <th class="no-content">
                                            Actions
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    {{-- @dd($legalBillEntrys) --}}
                                    @foreach ($legalBillEntrys as $bill)
                                        {{-- @dd($salesOrder->delivery) --}}
                                        <tr>
                                            <td class="text-center">{{ ($legalBillEntrys->currentPage() - 1) * $legalBillEntrys->perPage() + $loop->iteration  }}</td>
                                            <td>
                                                {{ $bill->bill_no }}
                                            </td>
                                            <td>
                                                {{ $bill->date }}
                                            </td>
                                            <td>
                                                {{ $bill->legalEntry->case_no ?? 'N/A' }}
                                            </td>
                                            <td>
                                                {{ $bill->vendor->company_name ?? 'N/A' }}
                                            </td>
                                            <td>
                                                {{ $bill->amount }}
                                            </td>
                                            <td>
                                                {{ $bill->status }}
                                            </td>
                                            <td>
                                                {{ $bill->createdBy->name ?? 'N/A' }}
                                            </td>
                                           
                                            <td>
                                                <div class="btn-group btn-group-sm" role="group"
                                                    aria-label="Small button group">

                                                    @if (hasPermission('legal.legal-bill-entries.update') )
                                                        <a class="btn btn-outline-warning"
                                                            href="{{ route('legal.legal-bill-entries.edit', $bill->id) }}"><i
                                                                class="far fa-edit"></i>
                                                        </a>
                                                    @endif

                                                    @if (hasPermission('legal.legal-bill-entries.destroy') )
                                                        <button type="button"
                                                            data-action="{{ route('legal.legal-bill-entries.destroy', $bill->id) }}"
                                                            class="btn btn-outline-danger delete-confirm"><i
                                                                class="far fa-trash-alt"></i>
                                                        </button>
                                                    @endif
                                                    @if(hasPermission('legal.legal-bill-entries.show'))
                                                    <a class="btn btn-outline-info"
                                                    href="{{ route('legal.legal-bill-entries.show', $bill->id) }}"><i
                                                        class="far fa-eye"></i>
                                                    </a>
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
