@section('title', 'Customer Settings')
@section('description', 'Customer Settings')
@extends('layout.app')
@section('content')

    <Style>
        #right-column {
            margin-bottom: 10px !importent;
        }

        .row {
            padding: 15px;
            margin-top: 10px;
        }

        .form-group label {
            margin-bottom: 3px;
        }

        .form-group input,
        .form-group select,
        .form-group textarea {
            margin-top: 3px;
        }

        #title {
            padding: 0;
            margin-top: 0;
        }

        .fs-15 ms-20 fw-500 text-capitalize {
            margin-top:
        }

        #justify-content-center {
            margin-top: 10px !importent;
        }

        /* Style for all <a> tags */
        .nav-tabs.vertical-tabs .nav-item .nav-link {
            background-color: #f7ecfd;
            /* Background color */
            color: #3d3d3d;
            /* Text color */
            border-radius: 5px 5px 0 0;
            /* 5px radius for top-left and top-right corners */
        }

        /* Style for active tab */
        .nav-tabs.vertical-tabs .nav-item .nav-link.active {
            background-color: var(--color-primary);
            /* Background color */
            color: #ffffff;
            /* Text color */
        }

        .modal-lg {
            max-width: 90%;
        }
    </Style>
    <div class="container-fluid">
        <div class="row" id="title">
            <div class="col-lg-12">
                <div class="d-flex align-items-center user-member__title mb-30 mt-30">
                    <h3 class="text-capitalize">{{ trans('customer settings') }}</h3>
                </div>
            </div>
        </div>
        <div class="card mb-50">
            <div class="row justify-content-center" id="justify-content-center">
                <div class="col-sm-10">
                    <div class="mt-40 mb-50">
                        <form action="{{ route('crm.customers.settings.store', $customer->id) }}" method="POST"
                            enctype="multipart/form-data">
                            @csrf
                            @method('PUT')

                            <div class="dm-tab tab-horizontal">
                                <h4>
                                    <div class="col-sm-14">
                                        <label class="col-sm-12 control-label">Customer Name :
                                            @if ($customer->customer)
                                                {{ $customer->customer->company_name . ' (' . optional($customer->customer->area)->area . ') ' }}
                                            @else
                                                {{ $customer->company_name . ' (' . optional($customer->area)->area . ') ' }}
                                            @endif
                                        </label>

                                    </div>
                                </h4>
                                <br>
                                <ul class="nav nav-tabs vertical-tabs" role="tablist">
                                    <li class="nav-item">
                                        <a class="nav-link active" id="tab-v-1-tab" data-bs-toggle="tab" href="#tab-v-1"
                                            role="tab" aria-selected="true">Customer Settings</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" id="tab-v-2-tab" data-bs-toggle="tab" href="#tab-v-2"
                                            role="tab" aria-selected="true">Discount</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" id="tab-v-3-tab" data-bs-toggle="tab" href="#tab-v-3"
                                            role="tab" aria-selected="true">Broker/Technologist Commission Info</a>
                                    </li>
                                </ul>
                                <div class="tab-content">
                                    <div class="tab-pane fade show active" id="tab-v-1" role="tabpanel"
                                        aria-labelledby="tab-v-1-tab">
                                        <div class="row" id="company-row">
                                            <div class="form-group col-md-4 mb-25">
                                                <label for="company_name"
                                                    class="color-dark fs-14 fw-500 align-center">Customer
                                                    Name <span class="text-danger">*</span></label>
                                                @if ($customer->customer)
                                                    <input type="text" class="form-control" name="company_name"
                                                        value="{{ old('company_name', $customer->customer->company_name . ' (' . optional($customer->customer->area)->area . ') ') }}"
                                                        id="company_name" placeholder="Company Name" readonly>
                                                    <input type="hidden" name="customer_id"
                                                        value="{{ $customer->customer->id }}">
                                                @else
                                                    <input type="text" class="form-control" name="company_name"
                                                        value="{{ old('company_name', $customer->company_name . ' (' . optional($customer->area)->area . ') ') }}"
                                                        id="company_name" placeholder="Company Name" readonly>
                                                    <input type="hidden" name="customer_id" value="{{ $customer->id }}">
                                                @endif
                                                @if ($errors->has('company_name'))
                                                    <p class="text-danger">{{ $errors->first('company_name') }}</p>
                                                @endif
                                            </div>
                                            <div class="form-group col-md-4 mb-25">
                                                <label for="customer_rating"
                                                    class="color-dark fs-14 fw-500 align-center">Customer Rating</label>
                                                <select name="customer_rating" id="customer_rating"
                                                    class="form-control tom-select">
                                                    <option value="">Select Customer Rating</option>
                                                    @foreach ($customerRatings as $key => $value)
                                                        <option value="{{ $value->id }}"
                                                            @if (optional($customer)->customer_rating == $value->id) selected @endif>{{ $value->name }}</option>
                                                    @endforeach
                                                </select>
                                                @if ($errors->has('customer_rating'))
                                                    <p class="text-danger">{{ $errors->first('customer_rating') }}</p>
                                                @endif
                                            </div>
                                            {{-- @dd($customer) --}}
                                            <div class="form-group col-md-4 mb-25">
                                                <label for="customer_status"
                                                    class="color-dark fs-14 fw-500 align-center">Customer Status</label>
                                                <select name="customer_status" id="customer_status"
                                                    class="form-control tom-select">
                                                    <option value="1"
                                                        @if (optional($customer)->customer_status == 1) selected @endif>Active</option>
                                                    <option value="0"
                                                        @if (optional($customer)->customer_status == 0) selected @endif>Inactive
                                                    </option>
                                                </select>
                                                @if ($errors->has('customer_status'))
                                                    <p class="text-danger">{{ $errors->first('customer_status') }}</p>
                                                @endif
                                            </div>
                                            <div class="form-group col-md-4 mb-25">
                                                <label for="credit_limit"
                                                    class="color-dark fs-14 fw-500 align-center">CREDIT LIMIT</label>
                                                <input type="text" class="form-control" name="credit_limit"
                                                    value="{{ old('credit_limit', optional($customer)->credit_limit ?? 0) }}"
                                                    id="credit_limit" placeholder="Credit Limit">
                                                @if ($errors->has('credit_limit'))
                                                    <p class="text-danger">{{ $errors->first('credit_limit') }}</p>
                                                @endif
                                            </div>
                                            <div class="form-group col-md-4 mb-25">
                                                <label for="additional_credit_limit"
                                                    class="color-dark fs-14 fw-500 align-center">Additional Credit Limit
                                                </label>
                                                <input type="text" class="form-control" name="additional_credit_limit"
                                                    value="{{ old('additional_credit_limit', optional($customer)->additional_credit_limit ?? 0) }}"
                                                    id="additional_credit_limit" placeholder="Additional Credit Limit">
                                                @if ($errors->has('additional_credit_limit'))
                                                    <p class="text-danger">{{ $errors->first('additional_credit_limit') }}
                                                    </p>
                                                @endif
                                            </div>
                                            <div class="form-group col-md-4 mb-25">
                                                <label for=" opening_balance"
                                                    class="color-dark fs-14 fw-500 align-center">Opening Balance
                                                    <span class="text-danger">*</span></label>
                                                <input type="text" class="form-control" name="opening_balance"
                                                    value="{{ old('opening_balance', optional($customer)->opening_balance ?? 0) }}"
                                                    id="opening_balance" placeholder="Opening Balance" required>
                                                @if ($errors->has('opening_balance'))
                                                    <p class="text-danger">{{ $errors->first('opening_balance') }}</p>
                                                @endif
                                            </div>

                                            <div class="form-group col-md-4 mb-25">
                                                <label for="is_condition_bill"
                                                    class="color-dark fs-14 fw-500 align-center">Condition Bill
                                                    Applicable</label>
                                                <select name="is_condition_bill" id="is_condition_bill"
                                                    class="form-control tom-select">
                                                    <option value="1"
                                                        @if (optional($customer)->is_condition_bill == 1) selected @endif>Yes</option>
                                                    <option value="0"
                                                        @if (optional($customer)->is_condition_bill == 0) selected @endif>No</option>
                                                </select>
                                                @if ($errors->has('is_condition_bill'))
                                                    <p class="text-danger">{{ $errors->first('is_condition_bill') }}</p>
                                                @endif
                                            </div>
                                            <div class="form-group col-md-4 mb-25">
                                                <label for="minimum_condition_bill"
                                                    class="color-dark fs-14 fw-500 align-center">Minimum Amount for
                                                    Condition Bill </label>
                                                <select name="minimum_condition_bill" id="minimum_condition_bill"
                                                    class="form-control tom-select">
                                                    <option value="1"
                                                        @if (optional($customer)->minimum_condition_bill == 1) selected @endif>HALF</option>
                                                    <option value="2"
                                                        @if (optional($customer)->minimum_condition_bill == 2) selected @endif>FULL</option>
                                                </select>
                                                @if ($errors->has('minimum_condition_bill'))
                                                    <p class="text-danger">{{ $errors->first('minimum_condition_bill') }}
                                                    </p>
                                                @endif
                                            </div>
                                            <div class="form-group col-md-4 mb-25">
                                                <label for="vat_status" class="color-dark fs-14 fw-500 align-center">Vat
                                                    Status</label>
                                                <select name="vat_status" id="vat_status"
                                                    class="form-control tom-select">
                                                    <option value="1"
                                                        @if (optional($customer)->vat_status == 1) selected @endif>Yes</option>
                                                    <option value="0"
                                                        @if (optional($customer)->vat_status == 0) selected @endif>No</option>
                                                </select>
                                                @if ($errors->has('vat_status'))
                                                    <p class="text-danger">{{ $errors->first('vat_status') }}</p>
                                                @endif
                                            </div>
                                            <div class="form-group col-md-4 mb-25">
                                                <label for="is_document_return"
                                                    class="color-dark fs-14 fw-500 align-center">Document Return/License
                                                    Key Applicable</label>
                                                <select name="is_document_return" id="is_document_return"
                                                    class="form-control tom-select">
                                                    <option value="1"
                                                        @if (optional($customer)->is_document_return == 1) selected @endif>Yes</option>
                                                    <option value="0"
                                                        @if (optional($customer)->is_document_return == 0) selected @endif>No</option>
                                                </select>
                                                @if ($errors->has('is_document_return'))
                                                    <p class="text-danger">{{ $errors->first('is_document_return') }}</p>
                                                @endif
                                            </div>


                                            <div class="form-group col-md-4 mb-25">
                                                <label for="service_applicable"
                                                    class="color-dark fs-14 fw-500 align-center">Service Applicable</label>
                                                <select name="service_applicable" id="service_applicable"
                                                    class="form-control tom-select">
                                                    <option value="1"
                                                        @if (optional($customer)->service_applicable == 1) selected @endif>Yes</option>
                                                    <option value="0"
                                                        @if (optional($customer)->service_applicable == 0) selected @endif>No</option>
                                                </select>
                                                @if ($errors->has('service_applicable'))
                                                    <p class="text-danger">{{ $errors->first('service_applicable') }}</p>
                                                @endif
                                            </div>

                                            <div class="form-group col-md-4 mb-25"> 
                                                <label for="ledger_files" class="color-dark fs-14 fw-500 align-center">
                                                    Old Ledger Files
                                                </label> 
                                                <div class="account-profile w-100 mb-25">

                                                    <div class="form-group w-100">  
                                                        <x-file-uploader :value="$customer->ledger_files ?? old('ledger_files')"  name="ledger_files" multiple/>
                                                    </div> 
                                                </div> 
                                            </div> 
                                        </div>
                                    </div>

                                    <div class="tab-pane fade" id="tab-v-2" role="tabpanel" aria-labelledby="tab-v-2">

                                        <div class="row">
                                            <div class="form-group">
                                                <div class="row">

                                                    <div class="col-sm-6" style="text-align: right">
                                                        <label class="control-label">Discount Type</label>
                                                    </div>
                                                    <div class="col-sm-6" style="text-align: left">
                                                        <div class=" @error('discount_type') has-error @enderror">

                                                            @if ($customer->discount_type == 3)
                                                                <label>
                                                                    <input id="percentage" name="discount_type"
                                                                        type="checkbox" value="1" class="ace"
                                                                        checked>
                                                                    <span class="lbl"> Percentage</span>
                                                                </label>
                                                                <label>
                                                                    <input id="fixed" name="discount_type"
                                                                        type="checkbox" value="2" class="ace"
                                                                        checked>
                                                                    <span class="lbl"> Fixed</span>
                                                                </label>
                                                            @elseif($customer->discount_type == 1)
                                                                <label>
                                                                    <input id="na" name="discount_type"
                                                                        type="checkbox" value="0" class="ace">
                                                                    <span class="lbl"> N/A</span>
                                                                </label>
                                                                <label>
                                                                    <input id="percentage" name="discount_type"
                                                                        type="checkbox" value="1" class="ace"
                                                                        checked>
                                                                    <span class="lbl"> Percentage</span>
                                                                </label>
                                                                <label>
                                                                    <input id="fixed" name="discount_type"
                                                                        type="checkbox" value="2" class="ace">
                                                                    <span class="lbl"> Fixed</span>
                                                                @elseif($customer->discount_type == 2)
                                                                    <label>
                                                                        <input id="na" name="discount_type"
                                                                            type="checkbox" value="0"
                                                                            class="ace">
                                                                        <span class="lbl"> N/A</span>
                                                                    </label>
                                                                    <label>
                                                                        <input id="percentage" name="discount_type"
                                                                            type="checkbox" value="1"
                                                                            class="ace">
                                                                        <span class="lbl"> Percentage</span>
                                                                    </label>
                                                                    <label>
                                                                        <input id="fixed" name="discount_type"
                                                                            type="checkbox" value="2" class="ace"
                                                                            checked>
                                                                        <span class="lbl"> Fixed</span>
                                                                    </label>
                                                                @elseif($customer->discount_type == 0)
                                                                    <label>
                                                                        <input id="na" name="discount_type"
                                                                            type="checkbox" value="0" class="ace"
                                                                            checked>
                                                                        <span class="lbl"> N/A</span>
                                                                    </label>
                                                                    <label>
                                                                        <input id="percentage" name="discount_type"
                                                                            type="checkbox" value="1"
                                                                            class="ace">
                                                                        <span class="lbl"> Percentage</span>
                                                                    </label>
                                                                    <label>
                                                                        <input id="fixed" name="discount_type"
                                                                            type="checkbox" value="2"
                                                                            class="ace">
                                                                        <span class="lbl"> Fixed</span>
                                                                    </label>
                                                                @else
                                                                    <label>
                                                                        <input id="na" name="discount_type"
                                                                            type="checkbox" value="0" class="ace"
                                                                            {{ is_array(old('discount_type')) && in_array('0', old('discount_type', $customer->discount_type)) ? 'checked' : '' }}>
                                                                        <span class="lbl"> N/A</span>
                                                                    </label>
                                                                    <label>
                                                                        <input id="percentage" name="discount_type"
                                                                            type="checkbox" value="1" class="ace"
                                                                            {{ is_array(old('discount_type')) && in_array('1', old('discount_type', $customer->discount_type)) ? 'checked' : '' }}>
                                                                        <span class="lbl"> Percentage</span>
                                                                    </label>
                                                                    <label>
                                                                        <input id="fixed" name="discount_type"
                                                                            type="checkbox" value="2" class="ace"
                                                                            {{ is_array(old('discount_type')) && in_array('2', old('discount_type', $customer->discount_type)) ? 'checked' : '' }}>
                                                                        <span class="lbl"> Fixed</span>
                                                                    </label>
                                                            @endif
                                                            <input type="hidden" id="combined_discount_type"
                                                                name="combined_discount_type" value="">

                                                            @error('discount_type')
                                                                <span class="text-danger">
                                                                    {{ $message }}
                                                                </span>
                                                            @enderror
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group" id="percentage-section" style="display: none;">
                                                <br>
                                                <h2>Discount Info</h2>
                                                <br>
                                                <table class="table table-bordered percentage-table">
                                                    <thead>
                                                        <th>Percentage Type</th>
                                                        <th>Percentage %</th>
                                                        <th>
                                                            <div class="btn-group btn-corner">
                                                                <button class="btn btn-success btn-xs add-row"
                                                                    onclick="addPercentageRow()" type="button">
                                                                    <i class="fa fa-plus"></i>
                                                                </button>
                                                            </div>
                                                        </th>
                                                    </thead>
                                                    <tbody>
                                                        @if ($customer->customerSettingDiscounts && count($customer->customerSettingDiscounts) > 0)
                                                            @foreach ($customer->customerSettingDiscounts as $key => $value)
                                                                <tr>
                                                                    <td>
                                                                        <select name="percentage_type[]"
                                                                            class="form-control"
                                                                            onchange="getPercentage(this)">
                                                                            <option value="">Select Type</option>
                                                                            @foreach ($percentageTypes as $percentageType)
                                                                                <option value="{{ $percentageType->id }}"
                                                                                    {{ $value->percentage_type == $percentageType->id ? 'selected' : '' }}>
                                                                                    {{ $percentageType->name }}</option>
                                                                            @endforeach
                                                                        </select>
                                                                    </td>
                                                                    <td>
                                                                        <input type="number"
                                                                            class="form-control input-sm"
                                                                            name="percentage[]"
                                                                            value="{{ $value->percentage }}"
                                                                            placeholder="Enter Percentage"
                                                                            oninput="this.value = Math.min(this.value, 100);">
                                                                    </td>

                                                                    <td>
                                                                        <div class="btn-group btn-corner">
                                                                            <button class="btn btn-danger btn-xs"
                                                                                onclick="deletePercentageRow(this)"
                                                                                type="button">
                                                                                <i class="fa fa-trash"></i>
                                                                            </button>
                                                                        </div>
                                                                    </td>
                                                                </tr>
                                                            @endforeach
                                                        @else
                                                            <tr>
                                                                <td>
                                                                    <select name="percentage_type[]" class="form-control"
                                                                        onchange="getPercentage(this)">
                                                                        <option value="">Select Type</option>
                                                                        @foreach ($percentageTypes as $percentageType)
                                                                            <option value="{{ $percentageType->id }}">
                                                                                {{ $percentageType->name }}</option>
                                                                        @endforeach
                                                                    </select>
                                                                </td>
                                                                <td>
                                                                    <input type="text" class="form-control input-sm"
                                                                        name="percentage[]" value=""
                                                                        placeholder="percentage">
                                                                </td>

                                                                <td>
                                                                    <div class="btn-group btn-corner">
                                                                        <button class="btn btn-danger btn-xs"
                                                                            onclick="deletePercentageRow(this)"
                                                                            type="button">
                                                                            <i class="fa fa-trash"></i>
                                                                        </button>
                                                                    </div>
                                                                </td>
                                                            </tr>
                                                        @endif
                                                    </tbody>
                                                </table>

                                            </div>
                                            <div class="form-group" id="fixed-section" style="display: none;">
                                                <br>
                                                <h2>Product Wise Fixed Price</h2>
                                                <div class="form-check pt-3">
                                                    <input class="form-check-input"   type="checkbox"     id="broker_price"    name="broker_price"  value="1"  style="width: 22px; height: 22px;"  >
                                                    <label class="form-check-label ms-2"   for="broker_price"  style="font-size: 20px; font-weight: 600;"  >
                                                        Set Broker Price
                                                    </label>
                                                </div>

                                                <br>
                                                <div class="row">
                                                    <div class="col-md-5">
                                                        <div class="form-group mb-25">
                                                            <label for="product_id"
                                                                class="color-dark fs-14 fw-500 align-center">Product</label>
                                                            <select class="form-control " name="product_id"
                                                                id="product_id">
                                                                <option value="">Choose Product</option> 
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-2">
                                                        <div class="form-group mb-25">
                                                            <label for="mrp"
                                                                class="color-dark fs-14 fw-500 align-center">MRP</label>
                                                            <input type="number" class="form-control" id="mrp"
                                                                name="mrp" value="" placeholder="MRP" readonly>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-2">
                                                        <div class="form-group mb-25">
                                                            <label for="sales_amount"
                                                                class="color-dark fs-14 fw-500 align-center">Sales Amount</label>
                                                            <input type="number" class="form-control" id="sales_amount"
                                                                name="sales_amount" value="" placeholder="Amount">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-2 ">

                                                        <div class="form-group mb-25">
                                                            <br>
                                                            <button type="button"
                                                                class="btn btn-primary btn-default btn-squared radius-md shadow2 btn-xs"
                                                                onclick="addProduct()">+ Add</button>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-12">
                                                    <div class="row mt-4">
                                                        <div class="col-md-12">
                                                            <h3>Product Information</h3>
                                                            <table class="table table-bordered" id="product_info_table">
                                                                <thead>
                                                                    <tr>
                                                                        <th style="width: 5%; text-align: center">Sl</th>
                                                                        <th style="width: 25%; text-align: center">Product Name  </th>
                                                                        <th style="width: 15%; text-align: center ">
                                                                            MRP
                                                                        </th>
                                                                        <th style="width: 15%; text-align: center ">
                                                                            Sales Amount
                                                                        </th>
                                                                        <th style="width: 8%" style="text-align: right;">
                                                                            Action
                                                                        </th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody>
                                                                    @if ($customer->customerSettingFixedDiscounts && count($customer->customerSettingFixedDiscounts) > 0)
                                                                        @foreach ($customer->customerSettingFixedDiscounts as $key => $value)
                                                                            <tr>
                                                                                <td>{{ $key + 1 }}</td>
                                                                                <td>
                                                                                    <input type="hidden"
                                                                                        name="product_ids[]"
                                                                                        class="form-control"
                                                                                        value="{{ $value->product_id }}">
                                                                                    {{ old('product_id', $value->product->name) }}
                                                                                </td>
                                                                                <td> 
                                                                                    {{ $value->product->mrp }}
                                                                                </td>
                                                                                <td>
                                                                                    <input type="hidden"
                                                                                        name="sales_amounts[]"
                                                                                        class="form-control"
                                                                                        value="{{ $value->sales_amounts }}">
                                                                                    {{ old('sales_amount', $value->sales_amounts) }}
                                                                                </td>
                                                                                <td>
                                                                                    <div class="btn-group btn-corner">
                                                                                        <button
                                                                                            class="btn btn-danger btn-xs"
                                                                                            onclick="deleteProductTag(this)"
                                                                                            type="button">
                                                                                            <i class="fa fa-trash"></i>
                                                                                        </button>
                                                                                    </div>
                                                                                </td>
                                                                            </tr>
                                                                        @endforeach
                                                                    @endif
                                                                </tbody>
                                                            </table>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="tab-pane fade" id="tab-v-3" role="tabpanel" aria-labelledby="tab-v-3">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="broker_id">Broker Name</label>
                                                    <select class="form-control" name="broker_id"
                                                        id="broker_id">
                                                        <option value="">Select Broker</option> 
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-2 mt-30">
                                                <div class="form-group">
                                                    <button type="button"
                                                        class="btn btn-primary btn-squared radius-md btn-xs"
                                                        onclick="addBroker()">+ Add</button>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="row mt-4">
                                                    <div class="col-md-12">
                                                        <h3>Broker Information</h3>
                                                        <table class="table table-bordered" id="broker_info_table">
                                                            <thead>
                                                                <tr>
                                                                    <th style=" text-align: center">Sl</th>
                                                                    <th style=" text-align: center">Broker</th>
                                                                    <th style=" text-align: center">Status</th>
                                                                    <th style="text-align: center;">Action</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                @if ($customer->customerSettingBrokers && $customer->customerSettingBrokers->count() > 0)
                                                                    @foreach ($customer->customerSettingBrokers as $key => $value)
                                                                        <tr>
                                                                            <td>{{ $key + 1 }}</td>
                                                                            <td>
                                                                                <input type="hidden" name="broker_id[]"
                                                                                    class="form-control"
                                                                                    value="{{ $value->broker_id }}">
                                                                                <input type="hidden"
                                                                                    name="customer_setting_broker_id[]"
                                                                                    class="form-control"
                                                                                    value="{{ $value->id }}">
                                                                                {{ old('broker_id.' . $key, @$value->broker->broker_name) }}
                                                                            </td>
                                                                            <td>
                                                                                <select name="broker_status[]"
                                                                                    class="form-control"
                                                                                    id="broker_status">
                                                                                    <option value="1"
                                                                                        {{ $value->broker_status == 1 ? 'selected' : '' }}>
                                                                                        Active</option>
                                                                                    <option value="0"
                                                                                        {{ $value->broker_status == 0 ? 'selected' : '' }}>
                                                                                        Inactive</option>
                                                                                </select>
                                                                            </td>
                                                                            <td class="text-center">
                                                                                <div class="btn-group btn-group-sm">
                                                                                    <button class="btn btn-primary btn-xs"
                                                                                        type="button"
                                                                                        onclick="viewBroker({{ @$value->broker->id }})"
                                                                                        data-bs-toggle="modal"
                                                                                        data-bs-target="#editModal">
                                                                                        <i class="fas fa-cog"></i>
                                                                                    </button>
                                                                                    <button class="btn btn-danger btn-xs"
                                                                                        onclick="removeBroker(this)"
                                                                                        type="button">
                                                                                        <i class="fa fa-trash"></i>
                                                                                    </button>
                                                                                </div>
                                                                            </td>
                                                                        </tr>
                                                                    @endforeach
                                                                @endif
                                                            </tbody>

                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                    </div>

                    <div class="button-group d-flex pt-25 justify-content-md-end justify-content-start">
                        <a href="{{ route('crm.customers.index') }}"
                            class="btn btn-warning btn-default btn-squared radius-md shadow2 btn-sm">Back</a>
                        <button type="submit"
                            class="btn btn-primary btn-default btn-squared radius-md shadow2 btn-sm">Save</button>
                    </div>
                    </form>
                    <!-- Modal Structure -->
                    <div class="modal fade inputForm-modal" id="editModal" tabindex="-1" role="dialog"
                        aria-labelledby="editModalLabel" aria-hidden="true">
                        <div class="modal-dialog modal-lg modal-fullscreen-sm-down modal-dialog-centered modal-dialog-scrollable">
                            <div class="modal-content">
                                <div class="modal-header" id="editModalLabel">
                                    <h5 class="modal-title">Edit</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                        aria-hidden="true"></button>
                                </div>
                                <div class="modal-body" id="brokerDetails">
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-light-danger mt-2 mb-2 btn-no-effect"
                                        data-bs-dismiss="modal">Cancel</button>
                                </div>
                            </div>
                        </div>
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
        document.addEventListener('DOMContentLoaded', function() {
            var percentageCheckbox = document.getElementById('percentage');
            var fixedCheckbox = document.getElementById('fixed');
            var combinedInput = document.getElementById('combined_discount_type');

            function updateCombinedValue() {
                if (percentageCheckbox.checked && fixedCheckbox.checked) {
                    combinedInput.value = '3';
                } else {
                    combinedInput.value = '';
                }
            }

            percentageCheckbox.addEventListener('change', updateCombinedValue);
            fixedCheckbox.addEventListener('change', updateCombinedValue);

            // Initialize the combined value on page load
            updateCombinedValue();
        });
         function deleteProductTag(object) {
            $(object).closest('tr').remove();
        }
    </script>
    <script>
        // Add broker to the table
        function addBroker() {
            var brokerSelect = document.getElementById('broker_id');
            var brokerId = brokerSelect.value;
            var brokerName = brokerSelect.options[brokerSelect.selectedIndex].text;

            if (brokerId) {
                $.ajax({
                    url: '{{ route('crm.get-broker-details') }}',
                    type: 'GET',
                    data: {
                        id: brokerId
                    },
                    success: function(response) {
                        var table = document.getElementById('broker_info_table').getElementsByTagName('tbody')[0];
                        var newRow = table.insertRow();
                        newRow.innerHTML = `
                        <td>${table.rows.length}</td>
                        <td><input type="hidden" name="broker_id[]" value="${brokerId}">${brokerName}</td>
                        <td><select class="form-control" name="broker_status[]"><option value="1">Active</option><option value="0">Inactive</option></select></td>
                        <td class="text-center">
                            <div class="btn-group btn-group-sm" role="group"
                                                    aria-label="Small button group">
                            <button class="btn btn-primary btn-xs" type="button" onclick='viewBroker(${response.id})' data-bs-toggle="modal" data-bs-target="#editModal"><i class="fas fa-eye"></i></button>
                            <button class="btn btn-danger btn-xs" type="button" onclick="removeBroker(this)"><i class="fa fa-trash"></i></button>
                        </div>
                        </td>
                    `;
                    },
                    error: function(xhr) {
                        console.error(xhr.responseText);
                    }
                });
            }
        }

        function removeBroker(button) {
            var row = button.closest('tr');
            row.remove();
        }

        function viewBroker(broker_id) {
            //console.log(broker_id);
            $.ajax({
                url: '{{ route('crm.get-broker-details') }}',
                type: 'GET',
                data: {
                    id: broker_id
                },
                success: function(response) {
                    var broker = response;

                    //console.log(broker);
  
                    if (broker.broker_commission && broker.broker_commission.length > 0) 
                    {
                        percentageRows = broker.broker_commission
                            .filter(item => Number(item.commission_type) === 1) // only commission_type 1
                            .map(item => createPercentageRow(item, percentageTypes))
                            .join('');
                    } else {
                        // Optionally, create empty row if no data
                        percentageRows = createPercentageRow({}, percentageTypes);
                    }

                    if (broker.broker_commission && broker.broker_commission.length > 0) 
                    {   
                        fixedRows = broker.broker_commission
                            .filter(item => Number(item.commission_type) === 3) // only commission_type 1
                            .map(item => showFixedRow(item, percentageTypes))
                            .join('');


                        if (!fixedRows) {
                           fixedRows = createFixedRow({}, percentageTypes);
                        }
                    } 
                    else { alert(5);
                        // Optionally, create empty row if no data
                        fixedRows = createFixedRow({}, percentageTypes);
                    }

                    if (broker.broker_commission && broker.broker_commission.length > 0) 
                    {
                        fixedProductRows = broker.broker_commission
                            .filter(item => Number(item.commission_type) === 2) // only commission_type 2
                            .map(item => showFixedProductRow(item, percentageTypes))
                            .join('');

                        if (!fixedProductRows) {
                           fixedProductRows = createFixedProductRow({}, percentageTypes);
                        }
                    } 
                    else {
                        // Optionally, create empty row if no data
                        fixedProductRows = createFixedProductRow({}, percentageTypes);
                    }


                    var commissionTypeHtmlPercentage = createCommissionTypeHtmlPercentage(broker.commission_type, percentageTypes,broker.broker_commission, broker);
                    var commissionTypeHtmlFixed = createCommissionTypeHtmlFixed(broker.commission_type, percentageTypes,broker.broker_commission, broker);
                   
                    var route = "{{ route('crm.update-broker-details', ':ID') }}";
                    route = route.replace(':ID', broker.id);

                    var brokerDetails = `
                        <form id="brokerForm" action="${route}" method="POST">
                            @csrf
                            <div class="form-group">
                                <div class="row">
                                    <div class="row col-sm-6" style="border-right:1px solid">
                                        <div class="col-sm-5 text-right">
                                            <label class="col-sm-12 control-label">Commission Type</label>
                                        </div>
                                        <div class="col-sm-7">
                                            <div>
                                                ${commissionTypeHtmlPercentage}
                                            </div>
                                            <input type="hidden" name="broker_id" value="${broker.id}">
                                        </div>
                                    </div>
                                    <div class="row col-sm-6">
                                        <div class="col-sm-12">
                                            <div>
                                                ${commissionTypeHtmlFixed}
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row col-sm-6" style="border-right:1px solid">
                                        <div class="form-group" id="commission_percentage" style="">
                                            <table class="table table-bordered broker-percentage-table">
                                                <thead>
                                                    <tr>
                                                        <th>Percentage Type</th>
                                                        <th>Percentage %</th>
                                                        <th><button class="btn btn-success btn-xs" onclick="addBrokerPercentageRow()" type="button"><i class="fa fa-plus"></i></button></th>
                                                    </tr>
                                                </thead>
                                                <tbody>${percentageRows}</tbody>
                                            </table>
                                        </div>
                                    </div>
                                    <div class="row col-sm-6">
                                        <div class="form-group" id="commission_fixed" style="">
                                            <table class="table table-bordered broker-fixed-table" width="100%">
                                                <thead>
                                                    <tr>
                                                        <th width="60%">Product Name</th>
                                                        <th width="30%">Amount</th>
                                                        <th width="10%"><button class="btn btn-success btn-xs" onclick="addBrokerFixedRow()" type="button"><i class="fa fa-plus"></i></button></th>
                                                    </tr>
                                                </thead>
                                                <tbody> 
                                                    ${fixedRows} 
                                                    ${fixedProductRows} 
                                                </tbody>
                                            </table> 
                                        </div>
                                    </div>
                                </div>
                                <div class="row"> 
                                    <div class="modal-footer">
                                        <button type="submit" class="btn btn-primary">Update</button>
                                    </div> 
                                </div> 
                            </div> 
                        </form>
                    `;

                    document.getElementById('brokerDetails').innerHTML = brokerDetails;
                    $('#editModal').modal('show');

                    $('#brokerForm').on('submit', function(e) {
                        e.preventDefault();
                        var formData = $(this).serialize();
                        $.ajax({
                            url: $(this).attr('action'),
                            type: 'POST',
                            data: formData,
                            success: function(response) {
                                if (response.success) {
                                    $('#editModal').modal('hide');
                                    showToast('success', response.message);

                                } else {
                                    alert('An error occurred while updating the broker.');
                                }
                            },
                            error: function(xhr) {
                                console.error(xhr.responseText);
                                alert('An error occurred while updating the broker.');
                            }
                        });
                    });
                },
                error: function(xhr) {
                    console.error(xhr.responseText);
                }
            });
        }

        function createPercentageRow(item, percentageTypes) {
            return `
            <tr>
                <td>
                    <select name="percentage_type[]" class="form-control percentage_type">
                        <option value="">Select Type</option>
                        ${percentageTypes.map(percentageType => `
                                            <option value="${percentageType.id}" ${percentageType.id == item.percentage_type?.id ? 'selected' : ''}>${percentageType.name}</option>
                                        `).join('')}
                    </select>
                </td>
                <td><input type="text" class="form-control input-sm" name="percentage[]" value="${item.percentage || ''}" placeholder="percentage"></td>
                <td><button class="btn btn-danger btn-xs" onclick="deletePercentageRow(this)"><i class="fa fa-trash"></i></button></td>
            </tr>
        `;
        }

        function showFixedRow(item,percentageTypes) {  
         
            return `
                <tr>
                    <td>
                        <select class="form-control fixed_type" name="fixed_type[]">
                            <option value="">Select Type</option>
                            <option value="1" ${(item?.fixed_type ?? '') == "1" ? 'selected' : ''}>Invoice Wise</option>
                            <option value="2" ${(item?.fixed_type ?? '') == "2" ? 'selected' : ''}>Monthly</option>
                            <option value="3" ${(item?.fixed_type ?? '') == "3" ? 'selected' : ''}>Yearly</option>
                            <option value="4" ${(item?.fixed_type ?? '') == "4" ? 'selected' : ''}>Festival-Eid</option>
                            <option value="5" ${(item?.fixed_type ?? '') == "5" ? 'selected' : ''}>Festival-Durga Puja</option>
                        </select>
                    </td>
                    <td><input type="text" class="form-control input-sm" name="fixed[]" value="${percentageTypes?.fixed ?? item?.fixed ?? ''}" placeholder="Fixed"></td>
                    <td><button type="button" class="btn btn-danger btn-xs" onclick="deleteFixedRow(this)"><i class="fa fa-trash"></i></button></td>
                </tr>  
            `;
        }

        function createFixedRow(item,percentageTypes) {
         
            return `
                <tr>
                    <td>
                        <select class="form-control fixed_type" name="fixed_type[]">
                            <option value="">Select Type</option>
                            <option value="1">Invoice Wise</option>
                            <option value="2">Monthly</option>
                            <option value="3">Yearly</option>
                            <option value="4">Festival-Eid</option>
                            <option value="5">Festival-Durga Puja</option>
                        </select>
                    </td>
                    <td><input type="text" class="form-control input-sm" name="fixed[]" value="0" placeholder="Fixed"></td>
                    <td><button type="button" class="btn btn-danger btn-xs" onclick="deleteFixedRow(this)"><i class="fa fa-trash"></i></button></td>
                </tr>  
            `;
        }

        function createFixedProductRow(item) {   
            return `
                <tr>
                    <td>
                        <select class="form-control borker_product_ids fixed_type" name="fixed_type[]">
                            <option value=""  >--Select Product--</option> 
                        </select>
                    </td>
                    <td><input type="text" class="form-control input-sm" name="fixed[]" value="0" placeholder="Fixed"></td>
                    <td><button type="button" class="btn btn-danger btn-xs" onclick="deleteFixedRow(this)"><i class="fa fa-trash"></i></button></td>
                </tr>  
            `;
        }

        function showFixedProductRow(item) {  
            return `
                <tr>
                    <td>
                        <select class="form-control fixed_type" name="fixed_type[]">
                            <option value="${item.fixed_type}"  >${ item.product.name }</option> 
                        </select>
                    </td>
                    <td><input type="text" class="form-control input-sm" name="fixed[]" value="${ item?.fixed }" placeholder="Fixed"></td>
                    <td><button type="button" class="btn btn-danger btn-xs" onclick="deleteFixedRow(this)"><i class="fa fa-trash"></i></button></td>
                </tr>  
            `;
        } 

        function createCommissionTypeHtmlPercentage(commissionType, percentageTypes, brokerCommission, broker) {
            return `

            <input class="form-check-input ace" type="checkbox" name="commission_type[]" value="0" id="commission_n_a" 
            ${broker.commission_type == 0 ? 'checked' : ''} onchange="changeCommissionType(this)" >
            <label class="form-check-label" for="commission_n_a">
                N/A
            </label>

            <input class="form-check-input ace" type="checkbox" name="commission_type[]" value="1" id="commission_percentage"
                ${broker.commission_type == 1 ? 'checked' : ''} onchange="changeCommissionType(this)">
            <label class="form-check-label" for="commission_percentage">
            Percentage
            </label>
            `;
        }
        function createCommissionTypeHtmlFixed(commissionType, percentageTypes, brokerCommission, broker) {
            return `
            <input class="form-check-input ace" type="checkbox" name="commission_type[]" value="2" id="commission_fixed"
                ${broker.commission_type == 1 ? 'checked' : ''} onchange="changeCommissionType(this)" >
            <label class="form-check-label" for="commission_fixed">
                Fixed
            </label>`;
        }

        function createFixedTypeHtml(commission) {
            return `
                <tr>
                    <td>
                        <select class="form-control" name="fixed_type[]">
                            <option value="1" ${commission?.fixed_type == 1 ? 'selected' : ''}>Invoice Wise</option>
                            <option value="2" ${commission?.fixed_type == 2 ? 'selected' : ''}>Monthly</option>
                            <option value="3" ${commission?.fixed_type == 3 ? 'selected' : ''}>Yearly</option>
                            <option value="4" ${commission?.fixed_type == 4 ? 'selected' : ''}>Festival-Eid</option>
                            <option value="5" ${commission?.fixed_type == 5 ? 'selected' : ''}>Festival-Durga Puja</option>
                        </select>
                    </td>
                    <td><input type="text" class="form-control input-sm" name="fixed[]" value="${commission?.fixed || ''}" placeholder="Fixed"></td>
                    <td><button type="button" class="btn btn-danger btn-xs" onclick="deleteFixedRow(this)"><i class="fa fa-trash"></i></button></td>
                </tr>  
            `;
        }

        function changeCommissionType(el) {
            if($(el).val() == "0" && $(el).is(":checked")){
                // hide sections
                $("#commission_percentage, #commission_fixed").prop("checked", false);
                $(".broker-percentage-table, .broker-fixed-table").hide();
            } else { 
                // show sections
                $("#commission_percentage, #commission_fixed").prop("checked", true); 
                $(".broker-percentage-table, .broker-fixed-table").show();
            } 
        }

        function addPercentageRow() {
            var table = document.querySelector('.percentage-table tbody');
            var newRow = document.createElement('tr');
            newRow.innerHTML = createPercentageRow({}, percentageTypes);
            table.appendChild(newRow);
        }

        function addBrokerPercentageRow() {
            var table = document.querySelector('.broker-percentage-table tbody');
            var newRow = document.createElement('tr');
            newRow.innerHTML = createPercentageRow({}, percentageTypes);
            table.appendChild(newRow);  
            duplicateCheck('percentage_type');
        }
       

        function addBrokerFixedRow() {

            var tableBody = document.querySelector('.broker-fixed-table tbody');
  
            var table = document.querySelector('.broker-fixed-table tbody');
            var newRow = document.createElement('tr');
            
             var rows = table.querySelectorAll('tr');

            if(rows.length === 0){
                newRow.innerHTML = createFixedRow({}, percentageTypes);
            }else{
                newRow.innerHTML = createFixedProductRow({}, percentageTypes);
            }

            table.appendChild(newRow);

            // Attach TomSelect only to the select in this new row
            const productSelectEl = newRow.querySelector('.borker_product_ids');
            if(productSelectEl){
                new TomSelect(productSelectEl, {
                    valueField: "id",
                    labelField: "text",
                    searchField: [],
                    load: function(query, callback){
                        if(!query.length || query.length < 2) return callback();

                        $.ajax({
                            url: "{{ route('sales.sales-orders-autocomplete.products') }}",
                            type: "GET",
                            data: { search: query },
                            success: function(res){
                                productSelectEl.tomselect.clearOptions();
                                callback(res.map(item => ({ id: item.id, text: item.label })));
                            },
                            error: function(){
                                callback();
                            }
                        });
                    }
                });
            }
            duplicateCheck('fixed_type');
        }
         
       function deleteFixedRow(object) {
            var row = $(object).closest('tr');
            var table = row.closest('tbody').find('tr'); // rows in this tbody
            var rowIndex = table.index(row); // 0-based index

           if(rowIndex === 0){
                // First row → clear inputs/selects but do NOT remove
                row.find('input').val('');

                row.find('select').each(function(){
                    if(this){
                        this.selectedIndex = 0; // select first option

                        if(this.tomselect){
                            this.tomselect.setValue(this.options[0].value); // TomSelect first option
                        }
                    }
                });
            } else {
                // Other rows → remove if more than 1 row exists
                if(table.length > 1){
                    row.remove();
                } else {
                    // fallback: clear if only one row left
                    row.find('input').val('');
                    row.find('select').each(function(){
                        if(this){
                            this.value = "";
                            if(this.tomselect){
                                this.tomselect.clear();
                            }
                        }
                    });
                }
            }
        }
 

        $(document).ready(function() {
            changeCommissionType($('input[name="commission_type"]:checked')[0]);
            $('input[name="commission_type"]').change(function() {
                changeCommissionType(this);
            });
        });
    </script>
    <script>
        var percentageTypes = @json($percentageTypes); 

        
    </script>
    </script>
    <script>
        var selectedPercentageIds = []; // Array to store selected percentage type IDs

        function getPercentage(selectElement) {
            var percentageId = selectElement.value;
            if (percentageId === "") {
                // If no option is selected, do nothing
                return;
            }
            if (selectedPercentageIds.includes(percentageId)) {
                showToast('warning', 'You have already selected this Percentage Type.');
                // Reset the select element to default value
                selectElement.value = "";
                return;
            }
            // Add the selected percentage type ID to the array
            selectedPercentageIds.push(percentageId);
        }

        function showToast(type, message) {
            // Display toast message
            if (type === 'warning') {
                toastr.warning(message);
            } else if (type === 'error') {
                toastr.error(message);
            }
        }
    </script>
    <script>
        var selectedPercentageIds = []; // Array to store selected percentage type IDs

        function getSelfCommission(selectElement) {
            var percentageId = selectElement.value;
            if (percentageId === "") {
                // If no option is selected, do nothing
                return;
            }
            if (selectedPercentageIds.includes(percentageId)) {
                showToast('warning', 'You have already selected this Percentage Type.');
                // Reset the select element to default value
                selectElement.value = "";
                return;
            }
            // Add the selected percentage type ID to the array
            selectedPercentageIds.push(percentageId);
        }

        function showToast(type, message) {
            // Display toast message
            if (type === 'warning') {
                toastr.warning(message);
            } else if (type === 'success') {
                toastr.success(message);
            } else if (type === 'error') {
                toastr.error(message);
            }
        }
    </script>

    <script>
        $(document).ready(function() {
            // Function to show or hide sections based on checkbox status
            function toggleSections() {
                if ($('#na').is(':checked')) {
                    $('#percentage').prop('checked', false);
                    $('#fixed').prop('checked', false);
                    $('#percentage-section').hide();
                    $('#fixed-section').hide();
                } else {
                    if ($('#percentage').is(':checked')) {
                        $('#percentage-section').show();
                    } else {
                        $('#percentage-section').hide();
                    }
                    if ($('#fixed').is(':checked')) {
                        $('#fixed-section').show();
                    } else {
                        $('#fixed-section').hide();
                    }
                }
            }

            // Initial display based on previous selection
            toggleSections();

            // Event listeners for checkbox changes
            $('#na').change(function() {
                if (this.checked) {
                    $('#percentage').prop('checked', false);
                    $('#fixed').prop('checked', false);
                }
                toggleSections();
            });

            $('#percentage, #fixed').change(function() {
                if ($('#percentage').is(':checked') || $('#fixed').is(':checked')) {
                    $('#na').prop('checked', false);
                }
                toggleSections();
            }); 
            
            const productSelect = new TomSelect("#product_id", {
                valueField: "id",
                labelField: "text",
                searchField: [], 
                load: function(query, callback) {

                    if (!query.length || query.length < 2) return callback();

                    $.ajax({
                        url: "{{ route('crm.autocomplete.customer.products') }}",
                        type: "GET",
                        data: { search: query },
                        success: function(res) { 
                            //console.log('Full Response:', res);
                            productSelect.clearOptions();
                            callback(res.map(item => ({ id: item.id, text: item.label, mrp: item.mrp, broker_price: item.broker_price})));
                        },
                        error: function() {
                            callback();
                        }
                    });
                },

                onItemAdd: function(value) {

                    const item = this.options[value]; 

                    $('#mrp').val(item?.mrp ?? '');
                   
                    if ($("#broker_price").is(':checked')) {
                        $('#sales_amount').val(item?.broker_price ?? '5').prop('readonly', true);
                    } else {
                        $('#sales_amount').val('').prop('readonly', false);
                    }
                },

                onItemRemove: function() {
                    $('#mrp').val('');
                    $('#sales_amount').val('');
                }
            });
 
     
                
            const brokerSelect = new TomSelect("#broker_id", {
                valueField: "id",
                labelField: "text",
                searchField: [], 
                load: function(query, callback) {

                    if (!query.length || query.length < 2) return callback();

                    $.ajax({
                        url: "{{ route('crm.autocomplete.brokers') }}",
                        type: "GET",
                        data: { search: query },
                        success: function(res) {
                            brokerSelect.clearOptions();
                            callback(res.map(item => ({ id: item.id, text: item.label })));
                        },
                        error: function() {
                            callback();
                        }
                    });
                }
            }); 

        });

        function duplicateCheck(el)
        {
            $("."+el).on("change", function() {
                const selectedValue = $(this).val();
                if(!selectedValue) return;

                const allSelected = $("."+el).map(function(){
                    return $(this).val();
                }).get();

                const duplicateCount = allSelected.filter(v => v === selectedValue).length;

                if(duplicateCount > 1){
                    // Inject error message inside modal-body
                    const errorHtml = `
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <strong>Error!</strong> This item is already selected in another row.
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    `;

                    $("#brokerDetails").prepend(errorHtml); // show on top of modal content

                    $(this).val(""); // reset current select
                }
            });
        }
    </script>
    <script>
        // function addPercentageRow() {

        //     var table = $(".percentage-table tbody tr:last")
        //     table.clone().find('input').val('').end().insertAfter(table);
        // }

        function deletePercentageRow(object) {

            var table = $(".percentage-table tbody tr")

            if (table.length > 1) {
                $(object).closest('tr').remove()
            } else {
                $(object).closest('tr').find('input').val('');
                $(object).closest('tr').find('select').val('');
                $(object).closest('tr').find('select').each(function() {
                    this.tomselect?.clear();
                });
            }

        }
    </script>

    <script>
        $('.datePicker').datepicker({
            format: 'yyyy-mm-dd',
            autoclose: true
        });
    </script>



    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var selectedProductIds = []; // Array to store selected product IDs
 

            function addProduct() {
                var productId = document.getElementById('product_id').value;
                var productName = $('#product_id option:selected').text();
                var mrpAmount = document.getElementById('mrp').value;
                var salesAmount = document.getElementById('sales_amount').value;

                if (salesAmount === "") {
                    toastr.warning("Please provide sales amount.");
                    return;
                }
                if (productId === "") {
                    toastr.warning("Please select a product.");
                    return;
                }
    
                if (!selectedProductIds.includes(productId)) { ;
                    if (productId) {
                        addProductRow(productId, productName, mrpAmount,salesAmount);
                        selectedProductIds.push(productId);
                    }
                } else {
                    toastr.warning("You have already selected this product.");
                }
        
                // Clear the input fields after adding the product
                document.getElementById('sales_amount').value = "";
                document.getElementById('product_id').tomselect.clear();
                document.getElementById('product_id').value = "";
            }



            function addProductRow(id, name, mrp, amount) {
                var table = document.getElementById('product_info_table').getElementsByTagName('tbody')[0];
                var rowCount = table.rows.length;
                var row = table.insertRow(rowCount);
                row.insertCell(0).innerHTML = rowCount + 1;
                row.insertCell(1).innerHTML = '<input type="hidden" name="product_ids[]" value="' + id + '">' +
                    name;
                row.insertCell(2).innerHTML = '<input type="hidden" name="mrp_amounts[]" value="' + mrp +
                    '">' + mrp;
                row.insertCell(3).innerHTML = '<input type="hidden" name="sales_amounts[]" value="' + amount +
                    '">' + amount;
                row.insertCell(4).innerHTML =
                    '<button type="button" class="btn btn-danger btn-xs" onclick="deleteRow(this, \'' + id +
                    '\')"><i class="fa fa-trash"></i></button>';
            }

            function deleteRow(button, id) {
                var row = button.parentNode.parentNode;
                row.parentNode.removeChild(row);
                var index = selectedProductIds.indexOf(id);
                if (index > -1) {
                    selectedProductIds.splice(index, 1);
                }
                // Re-index the Sl column
                var table = document.getElementById('product_info_table').getElementsByTagName('tbody')[0];
                for (var i = 0; i < table.rows.length; i++) {
                    table.rows[i].cells[0].innerHTML = i + 1;
                }
            }
  
            window.addProduct = addProduct;
            window.deleteRow = deleteRow;
        });
    </script>

@endSection
