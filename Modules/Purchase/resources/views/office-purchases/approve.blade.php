@section('title', 'Office Purchase Edit')
@section('description', 'Office Purchase Edit')
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
                                    <li class="breadcrumb-item active" aria-current="page">
                                        {{ trans('menu.update-office-purchase-menu-title') }}</li>
                                </ol>
                            </nav>
                        </div>
                        <div class="breadcrumb-main__wrapper">
                            <div class="action-btn mt-sm-0 mt-15 row">
                                @if (hasPermission('purchase.offices.index'))
                                <a href="{{ route('purchase.offices.index') }}" class="btn btn-warning btn-default btn-squared radius-md shadow2 btn-sm"><i class="fa fa-list"></i> List</a>
                                @endif

                                @if (hasPermission('purchase.offices.create'))
                                <a href="{{ route('purchase.offices.create') }}" class="btn px-20 btn-primary btn-sm" style="margin-left: 5px;">
                                    <i class="las la-plus fs-16"></i>Add New
                                </a>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12 m-2">
                    <h4 class="text-capitalize breadcrumb-title">{{ trans('menu.update-office-purchase-menu-title') }}</h4>
                    <x-error-alart />
                </div>
                <div class="col-md-12">
                    <div class="card mb-4">
                        <div class="card-body">
                            <form action="{{ route('purchase.offices.approveStore', $officePurchase->id) }}" method="POST"
                                enctype="multipart/form-data">
                                @method('PUT')
                                @csrf
                                <div class="text-end float-end mt-2 mb-2">
                                    <p>Balance: 100tk</p>
                                </div>
                                <div class="row mb-4">
                                  
                                    <div class="col-md-6 mt-4">
                                        <div class="form-group">
                                            <label for="vendor_id">Vendor Name<span class="text-danger">*</span></label>
                                            <select name="vendor_id" id="vendor_id" class="form-control tom-select required" required>
                                                <option value="">Choose Vendor Name</option>
                                                @foreach ($vendors as $value)
                                                    <option value="{{ $value->id }}"
                                                        {{ old('vendor_id', $officePurchase->vendor_id) == $value->id ? 'selected' : '' }}>
                                                        {{ $value->company_name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6 mt-4">
                                        <div class="form-group">
                                            <label for="date">Date<span class="text-danger">*</span></label>
                                            <input type="date" name="date" class="form-control"
                                                id="date" placeholder="Date"
                                                value="{{ date('Y-m-d', strtotime($officePurchase->date)) }}" required>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="reference_bill">Reference Bill No<span class="text-danger">*</span></label>
                                            <input type="text" name="reference_bill" class="form-control"
                                                id="reference_bill" placeholder="Reference Bill No"
                                                value="{{ old('reference_bill', $officePurchase->reference_bill) }}">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="particular">Particular<span class="text-danger">*</span></label>
                                            <input type="text" name="particular" class="form-control"
                                                id="particular" placeholder="Particular........"
                                                value="{{ old('particular', $officePurchase->particular) }}" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="bill_amount">Bill Amount<span class="text-danger">*</span></label>
                                            <input type="text" name="bill_amount" class="form-control"
                                                id="bill_amount" placeholder="Bill Amount"
                                                value="{{ old('bill_amount', $officePurchase->bill_amount) }}" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6 mb-2">
                                        <div class="form-group">
                                            <label for="file_upload"
                                                class="color-dark fs-14 fw-500 align-center">File Up</label>
                                                <x-file-uploader :value="$officePurchase->file_upload" name="file_upload"/>

                                            {{-- <input type="file"
                                                class="file-control form-control"
                                                id="file_upload" value="{{ old('file_upload', $officePurchase->file_upload) }}" name="file_upload" 
                                                data-value="{{ old('file_upload', $officePurchase->file_upload) }}"> --}}
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="remarks">Remarks</label>
                                            <textarea name="remarks" id="remarks" cols="30" rows="3" class="form-control"
                                                placeholder="Bill Remarks">{{ old('remarks', $officePurchase->remarks) }}</textarea>
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="button-group d-flex pt-25 justify-content-md-end justify-content-start">
                                            <button type="submit" name="status" value="1"  class="btn btn-success btn-sm">Approve</button>
                                            <button type="submit" name="status" value="2" class="btn btn-danger btn-sm">Reject</button>
                                            <a href="{{ route('purchase.offices.index') }}" class="btn btn-primary btn-sm">Back</a>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('page_scripts')


@endsection
