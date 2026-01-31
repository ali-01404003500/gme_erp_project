@extends('layout.app')
@section('title',"Customer Receive Payments")
@section('description',"Customer Receive Payments")
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
                                    <li class="breadcrumb-item"><a href="/"><i class="las la-home"></i>Home</a></li>
                                    <li class="breadcrumb-item active" aria-current="page">
                                        {{ trans('menu.customer-receive-payments-menu-title') }}</li>
                                </ol>
                            </nav>
                        </div>
                        <div class="breadcrumb-main__wrapper">
                            <div class="action-btn mt-sm-0 mt-15">
                                @if (hasPermission('account.payments.customer-payments.create'))
                                    <a href="{{ route('account.payments.customer-payments.create') }}" class="btn btn-primary btn-default btn-squared radius-md shadow2 btn-sm">
                                        <i class="las la-plus fs-16"></i>
                                        {{ trans('Receive Payment') }}
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
                            <h4 class="text-capitalize breadcrumb-title">{{ trans('menu.customer-receive-payments-menu-title') }}
                            </h4>
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
                                            
                                        </table>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    <div class="card mb-4">
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered" style="width:100%" id="zero-config" >
                                    <thead>
                                        <tr>
                                            <th>Sl</th>
                                            <th>Customer Name</th>
                                            <th>Receive Amount</th>
                                            <th>Advance</th>
                                            <th>Due Amount</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($customerPayments as $key => $customerPayment)
                                           <tr>
                                               <td>{{ $key + 1 }}</td>
                                               <td>{{ $customerPayment->customer->company_name }}</td>
                                               <td>{{ $customerPayment->total_amount }}</td>
                                               <td>{{ $customerPayment->advance_amount }}</td>
                                               <td>{{ $customerPayment->due_amount }}</td>
                                               <td>
                                                 <div class="input-group">
                                                   <a href="{{ route('account.payments.customer-payments.show', $customerPayment->id) }}"
                                                     class="btn btn-primary btn-xs">
                                                     <i class="la la-eye"></i>
                                                   </a>
                                                   {{-- <a href="{{ route('account.payments.customer-payments.edit', $customerPayment->id) }}"
                                                     class="btn btn-success btn-xs">
                                                     <i class="las la-edit"></i>
                                                   </a> --}}
                                                 </div>
                                               </td>
                                           </tr>
                                        @endforeach
                                    </tbody>
                                    
                                    
                                </table>
                                
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('page_scripts')


@endsection