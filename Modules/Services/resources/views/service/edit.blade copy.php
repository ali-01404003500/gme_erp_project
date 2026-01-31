@section('title', 'Edit Service')
@section('description', 'Edit Service')
@extends('layout.app')
@section('content')

    <style>
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
        .ts-control,
        .form-control {
        height: 48px!important;
    }
    </style>
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
                                        {{ trans('menu.edit-service-menu-title') }}</li>
                                </ol>
                            </nav>
                        </div>
                        <div class="action-btn mt-sm-0 mt-15">
                            @if (hasPermission('services.service.index'))
                            <a href="{{ route('services.service.index') }}" class="btn btn-warning btn-default btn-squared radius-md shadow2 btn-sm"><i class="fa fa-list"></i> List</a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12 m-2">
                    <h4 class="text-capitalize breadcrumb-title">{{ trans('menu.edit-service-menu-title') }}</h4>
                    <x-error-alart />
                </div>
            <div class="card mb-50">
                <div class="row justify-content-center" id="justify-content-center">
                    <div class="col-sm-11">
                        <div class="mt-40 mb-50">
                            <h2 class="mb-3">Service Token Entry</h2>
                            <form action="{{ route('services.service.update',$service->id) }}" method="POST"
                                enctype="multipart/form-data">
                                @csrf
                                @method('PUT')
                                
                                <div class="row">
                                    <div class="col-md-6">
                                       <div class="form-group">
                                        <label for="customer_id">{{ __('Customer') }}:</label>
                                        <select name="customer_id" id="customer_id" class="form-control tom-select" disabled>
                                                <option value="{{ $service->serviceTokens->first()->customer_id ?? '' }}" selected>
                                                    {{ optional($service->serviceTokens->first()->customer)->company_name ?? __('Select Customer') }}
                                                </option>
                                            </select>
                                            <!-- Add a hidden input to still submit the customer_id -->
                                            <input type="hidden" name="customer_id" value="{{ $service->serviceTokens->first()->customer_id ?? '' }}">
                                    </div>

                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="contact_person_phone">Contact Person Phone No</label>
                                            <input type="text" name="contact_person_phone" id="contact_person_phone" class="form-control" placeholder="{{ __('Contact Person Phone No') }}">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="token_date">{{ __('Token Date') }}:</label>
                                            <input type="date" name="token_date" id="token_date" class="form-control" value="{{ date('Y-m-d') }}">
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="sales_order_id">{{ __('Invoice ID') }}:</label>
                                            <select name="sales_order_id" id="sales_order_id" class="form-control tom-select" onchange="handleInvoiceChange(this)">
                                                <option value="">{{ __('Select Invoice ID') }}</option>
                                                @foreach($salesOrders as  $salesOrder)

                                                    <option value="{{ $salesOrder->id }}" @if(old('sales_order_id') == $salesOrder->id) selected @endif data-customer = "{{ $salesOrder->customer_id }}" data-invoice_date = "{{ $salesOrder->invoice_date }}">{{$salesOrder->sales_order_id}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="invoice_date">{{ __('Invoice Date') }}:</label>
                                            <input type="text" name="invoice_date" id="invoice_date" class="form-control flatdate">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="expire_date">{{ __('Expire Date') }}:</label>
                                            <input type="text" name="expire_date" id="expire_date" class="form-control flatdate" readonly>
                                        </div>

                                        
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="product_id">{{ __('Product') }}:</label>
                                            <select name="product_catalog_id" id="product_catalog_id" class="form-control tom-select">
                                                <option value="">{{ __('Select Product') }}</option>
                                                @foreach ($productCatalogs as $productCatalog)
                                                    <option value="{{ $productCatalog->id }}">{{ $productCatalog->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="serial_number">{{ __('Serial Number') }}:</label>
                                            <select name="serial_number" id="serial_number" class="form-control tom-select">
                                            </select>
                                        </div>
                                    </div>
                                
                                
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="service_type">{{ __('Service Type') }}:</label>
                                            <select name="service_type" id="service_type" class="form-control">
                                                <option value="">{{ __('Select Service Type') }}</option>
                                                <option value="ON SPOT">ON SPOT</option>
                                                <option value="IN HOUSE">IN HOUSE</option>
                                                <option value="ON CALL">ON CALL</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-9">
                                        <div class="form-group">
                                            <label for="problem_details">{{ __('Problem Details') }}:</label>
                                            <textarea name="problem_details" id="problem_details" class="form-control" placeholder="{{ __('Problem Details') }}"></textarea>
                                        </div>
                                    </div>
                                    <meta name="csrf-token" content="{{ csrf_token() }}">

                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="problem_type">{{ __('Problem Type') }}:</label>
                                            <select name="problem_type" id="problem_type" class="form-control">
                                                <option value="">{{ __('Select Problem Type') }}</option>
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label for="work_type">{{ __('Work Type') }}:</label>
                                            <select name="work_type" id="work_type" class="form-control">
                                                <option value="">{{ __('Select Work Type') }}</option>
                                                <option value="Maintenance">Maintenance</option>
                                                <option value="Software Update">Software Update</option>
                                                <option value="New Installation">New Installation</option>
                                                <option value="Re Installation">Re Installation</option>
                                                <option value="Operating Training">Operating Training</option>
                                            </select>
                                        </div>
                                        <div class="form-group" style="">
                                            <button type="button" class="btn btn-sm btn-primary" id="addRow">{{ __('Add') }}</button>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <table class="table table-bordered" id="service_table">
                                        <thead>
                                            <tr>
                                                <th>Sl</th>
                                                <th>Customer</th>
                                                <th>Product</th>
                                                <th>Quantity</th>
                                                <th>Serial Number</th>
                                                <th>Problem Details</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($service->serviceTokens as $key => $serviceToken)
                                                <tr id="{{ $serviceToken->id }}">
                                                    <th scope="row">
                                                        {{ $key+1 }}
                                                        <input type="hidden" name="customer_id[]" value={{$serviceToken->customer_id}}>
                                                        <input type="hidden" name="contact_person_phone[]" value={{$serviceToken->contact_person_phone}}>
                                                        <input type="hidden" name="token_date[]" value="{{$serviceToken->token_date}}">
                                                        <input type="hidden" name="invoice_id[]" value="{{$serviceToken->invoice_id}}">
                                                        <input type="hidden" name="invoice_date[]" value="{{$serviceToken->invoice_date}}">
                                                        <input type="hidden" name="expire_date[]" value="{{$serviceToken->expire_date}}">
                                                        <input type="hidden" name="service_type[]" value="{{$serviceToken->service_type}}">
                                                        <input type="hidden" name="problem_details[]" value="{{$serviceToken->problem_details}}">
                                                        <input type="hidden" name="problem_type[]" value="{{$serviceToken->problem_type}}">
                                                        <input type="hidden" name="work_type[]" value="{{$serviceToken->work_type}}">
                                                        <input type="hidden" name="product_id[]" value="{{$serviceToken->product_id}}">
                                                        <input type="hidden" name="serial_number[]" value="{{$serviceToken->serial_number}}">
                                                        
                                                        
                                                    </th>
                                                    
                                                    <td>{{ optional($serviceToken->customer)->company_name }}</td>
                                                    <td>{{ optional($serviceToken->product)->name }}</td>
                                                    {{-- <td>{{ $serviceToken->quantity }}</td> --}}
                                                    <td><input type="number" name="quantity[]" value="{{ old('quantity')[$key] ?? $serviceToken->quantity }}" class="form-control text-center"></td>
                                                    <td>{{ $serviceToken->serial_number }}</td>
                                                    <td>{{ $serviceToken->problem_details }}</td>
                                                    <td>                    
                                                        <button type="button" class="btn btn-sm btn-danger" onclick="removeRow(this)"><i class="fa fa-trash"></i></button>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                                <div class="row">
                                    <div class="form-group">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="is_assigned" id="is_assigned" onchange="this.value = this.checked ? 1 : 0; 
                                                showHideEngineer(this)" value="{{ old('is_assigned') ?? $service->is_assigned }}" @if (old('is_assigned') == 1 || $service->is_assigned == 1) checked @endif>
                                            <label class="form-check-label" for="is_assigned">
                                                {{ __('Is Assigned') }}
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-md-5">
                                        <div class="form-group">
                                            <label for="assigned_engineer_id">{{ __('Assigned Engineer') }}:</label>
                                            <select name="assigned_engineer_id" id="assigned_engineer_id" class="form-control select2">
                                                <option value="" >{{ __('Select Engineer') }}</option>
                                                @foreach ($employees as $employee)
                                                    <option value="{{ $employee->id }}" {{ $service->assigned_engineer_id == $employee->id ? 'selected' : '' }}>{{ $employee->full_name }}-{{ $employee->id }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label for="service_date">{{ __('Service Date') }}:</label>
                                            <input type="date" name="service_date" id="service_date" class="form-control" value="{{ $service->service_date }}">
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label for="service_priority">{{ __('Service Priority') }}:</label>
                                            <select name="service_priority" id="service_priority" class="form-control">
                                                <option value="">{{ __('Select Priority') }}</option>
                                                <option value="HIGH" {{ $service->service_priority == 'HIGH' ? 'selected' : '' }}>HIGH</option>
                                                <option value="MEDIUM" {{ $service->service_priority == 'MEDIUM' ? 'selected' : '' }}>MEDIUM</option>
                                                <option value="LOW" {{ $service->service_priority == 'LOW' ? 'selected' : '' }}>LOW</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="service_type_name">{{ __('Service Type') }}:</label>
                                            <select name="service_type_name" id="service_type_name" class="form-control">
                                                <option value="">{{ __('Select Service Type') }}</option>
                                                <option value="ON SPOT" {{ $service->service_type_name == 'ON SPOT' ? 'selected' : '' }}>ON SPOT</option>
                                                <option value="IN HOUSE" {{ $service->service_type_name == 'IN HOUSE' ? 'selected' : '' }}>IN HOUSE</option>
                                                <option value="ON CALL" {{ $service->service_type_name == 'ON CALL' ? 'selected' : '' }}>ON CALL</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="remarks">{{ __('Remarks') }}:</label>
                                    <textarea name="remarks" id="remarks" class="form-control" placeholder="{{ __('Remarks') }}">{{ $service->remarks }}</textarea>
                                </div>
                                
                                <input type="hidden" name="status" id="status" value="{{ $service->status }}" />
                                
                                <div class="d-flex justify-content-between mt-4">
                                    <button type="submit" id="quit" class="btn btn-danger btn-sm">{{ __('Quit') }}</button>
                                    <button type="submit" class="btn btn-primary btn-sm">{{ __('Save') }}</button>
                                    
                                </div>
                                
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
    document.addEventListener('DOMContentLoaded', function() {
    // Get the customer phone from the first service token
    const firstToken = @json($service->serviceTokens->first());
    if (firstToken && firstToken.contact_person_phone) {
        document.getElementById('contact_person_phone').value = firstToken.contact_person_phone;
    }
});
</script>

<script>
    $(document).ready(function () {
        const csrfToken = $('meta[name="csrf-token"]').attr('content');

        new TomSelect('#problem_type', {
            valueField: 'value',
            labelField: 'text',
            searchField: 'text',
            create: function (input, callback) {
                $.ajax({
                    url: '{{ route('services.settings.problem-types.store') }}',
                    method: 'POST',
                    data: {
                        name: input,
                        _token: csrfToken
                    },
                    success: function (response) {
                        callback(response);
                    },
                    error: function (xhr) {
                        alert(xhr.responseJSON?.message || 'Failed to create new Problem Type');
                        callback();
                    }
                });
            },
            load: function (query, callback) {
                if (!query.length) return callback();
                $.ajax({
                    url: '{{ route('services.settings.problem-types.search') }}',
                    data: { q: query },
                    success: function (results) {
                        callback(results);
                    },
                    error: function () {
                        callback();
                    }
                });
            }
        });
    });
</script>
<script>
        $('#is_assigned').on('change', function() {
            if ($(this).is(':checked')) {
                $('#status').val('pending');
            }
        });
        $('#quit').on('click', function() {
            if( $('#status').val() == 'pending' ) {
                $('#status').val('Failed');
            }
            return true;
        });
</script>
<script>
    $(document).on('click', '#addRow', function() {
        var customer_id = $('#customer_id').val();
        var customer_name = $('#customer_id option:selected').text();
        var contact_person_phone = $('#contact_person_phone').val();
        var token_date = $('#token_date').val();
        var invoice_id = $('#sales_order_id').val();
        var invoice_date = $('#invoice_date').val();
        var expire_date = $('#expire_date').val();
        var product_name = $('#product_catalog_id option:selected').text();
        var product_catalog_id = $('#product_catalog_id').val();
        var serial_number = $('#serial_number').val();
        var service_type = $('#service_type').val();
        var problem_details = $('#problem_details').val();
        var problem_type = $('#problem_type').val();
        var work_type = $('#work_type option:selected').text();
        var row = `
             <tr>
                <th scope="row">
                    ${$('#service_table tbody tr').length + 1}
                    <input type="hidden" name="customer_id[]" value="${customer_id}">
                    <input type="hidden" name="contact_person_phone[]" value="${contact_person_phone}">
                    <input type="hidden" name="token_date[]" value="${token_date}">
                    <input type="hidden" name="invoice_id[]" value="${invoice_id}">
                    <input type="hidden" name="invoice_date[]" value="${invoice_date}">
                    <input type="hidden" name="expire_date[]" value="${expire_date}">
                    <input type="hidden" name="service_type[]" value="${service_type}">
                    <input type="hidden" name="problem_details[]" value="${problem_details}">
                    <input type="hidden" name="problem_type[]" value="${problem_type}">
                    <input type="hidden" name="serial_number[]" value="${serial_number}">
                    <input type="hidden" name="work_type[]" value="${work_type}">
                    <input type="hidden" name="product_id[]" value="${product_catalog_id}">  
                    
                </th>
                <td>${customer_name}</td>
                <td>${product_name}</td>
                <td><input type="number" name="quantity[]" value="1" class="form-control text-center" min="1"></td>
                <td>${serial_number}</td>
                <td>${problem_details}</td>
                <td>
                    <button type="button" class="btn btn-sm btn-danger" onclick="removeRow(this)"><i class="fa fa-trash"></i></button>
                </td>
            </tr>
        `;
        
        $('#service_table tbody').append(row);
    });
    
    function removeRow(button) {
        $(button).closest('tr').remove();
    }
</script>
<script>
$(document).ready(function () {

    $('#serial_number').on('change', function () {
        let serialNumber = $(this).val();
        let customerId = $('#customer_id').val();
        let productId = $('#product_catalog_id').val();
        let csrfToken = $('meta[name="csrf-token"]').attr('content');

        if (serialNumber && customerId && productId) {
            $.ajax({
                url: "{{ route('services.get.invoice.by.serial') }}",
                data: {
                    serial_number: serialNumber,
                    customer_id: customerId,
                    product_id: productId,
                    _token: csrfToken
                },
                success: function (res) {
                    // Set Invoice ID using TomSelect
                    if ($('#sales_order_id')[0]?.tomselect) {
                        $('#sales_order_id')[0].tomselect.setValue(res.sales_order_id);
                    } else {
                        $('#sales_order_id').val(res.sales_order_id);
                    }

                    // Set Invoice Date using Flatpickr
                    if ($('#invoice_date').prop('_flatpickr')) {
                        $('#invoice_date').prop('_flatpickr').setDate(res.invoice_date);
                    } else {
                        $('#invoice_date').val(res.invoice_date);
                    }
                    updateWarrantyExpiry(productId, customerId, res.invoice_date);

                },
                error: function () {
                    alert("Invoice not found for selected serial number.");
                    $('#sales_order_id').val('');
                    $('#invoice_date').val('');
                    $('#expire_date').val('');
                }
            });
        }
    });
});
// === Product change: update serials, warranty expire date, and quantity ===
    function updateWarrantyExpiry(productId, customerId, invoiceDate) {

        if (invoiceDate && productId) {
            let warrantyUnit = $('#product_catalog_id').find(':selected').data('warranty_period'); // year/month/day
            let warrantyValue = parseInt($('#product_catalog_id').find(':selected').data('warranty_period_input')) || 0;

            let expireDate = moment(invoiceDate);
            if (warrantyUnit === 'year') {
                expireDate.add(warrantyValue, 'years');
            } else if (warrantyUnit === 'month') {
                expireDate.add(warrantyValue, 'months');
            } else if (warrantyUnit === 'day') {
                expireDate.add(warrantyValue, 'days');
            }

            if ($('#expire_date').prop('_flatpickr')) {
                $('#expire_date').prop('_flatpickr').setDate(expireDate.format('YYYY-MM-DD'));
            } else {
                $('#expire_date').val(expireDate.format('YYYY-MM-DD'));
            }
        }
    }
    function onChangeCustomer(element=null){
        const phone = $('#customer_id').find(':selected').data('phone');
        $("#contact_person_phone").val(phone);
        if(element){
            const customerId = $(element).val();
                    $.ajax({
                        url: '{{ route('services.service-get-invoices') }}',
                        type: 'GET',
                        data: {
                            customer_id: customerId
                        },
                        success: function(response) {
                            var sales_order_select = $('#sales_order_id');
                            if(response.length > 0 ){
                                sales_order_select.empty();
                                sales_order_select.prop('tomselect').clearOptions();
                            }
                            console.log(response);
                            sales_order_select.append('<option value="">Select Sales Order</option>');
                            response.forEach(function(salesOrder) {
                                sales_order_select.append('<option value="' + salesOrder.id + '" data-invoice_date="' + salesOrder.invoice_date + '" data-customer="' + salesOrder.customer_id + '">' + salesOrder.sales_order_id + '</option>');
                            });
                            if(response.length > 0 ){
                                sales_order_select.prop('tomselect').sync();
                            }
                        }
                    });
                }
        }
        
            
        // function handleInvoiceChange(element){
        //     const customerId = $(element).find(':selected').data('customer');
        //     const phone = $(element).find(':selected').data('phone');
        //     console.log({customerId, phone});

        //     const invoiceDate = $(element).find(':selected').data('invoice_date');
        //     $("#invoice_date").prop('_flatpickr').setDate(invoiceDate);
        //     const invoiceId = $(element).find(':selected').val();
        //     console.log({customerId,invoiceDate});
            
        //     $("#customer_id").find(`option[value="${customerId}"]`).attr('selected', 'selected');
        //     $("#contact_person_phone").val(phone);
            
        //     $("#customer_id").prop(`tomselect`).sync();
        //     onChangeCustomer();
            
            
        //     $.ajax({
        //         url: '{{ route('services.service-get-products') }}',
        //         type: 'GET',
        //         data: {
        //             invoice_id: invoiceId
        //         },
        //         success: function(response) {
        //             var product_catalog_select = $('#product_catalog_id');
        //             product_catalog_select.empty();
        //             console.log(response);
        //             product_catalog_select.prop('tomselect').clearOptions();
        //             product_catalog_select.append('<option value="">Select Product Catalog</option>');
        //             response.forEach(function(productCatalog) {
        //                 product_catalog_select.append('<option value="' + productCatalog.product.id + '" data-warranty_period="' + productCatalog.product.warranty_period + '" data-warranty_period_input="' + productCatalog.product.warranty_period_input + '">' + productCatalog.product.name + '</option>');
        //             });
        //             product_catalog_select.prop('tomselect').sync();
        //         }
        //     });
        // }   

        $(document).ready(function() {
            // === Product change: update serials, warranty expire date, and quantity ===
            $('#product_catalog_id').on('change', function () {
                let productId = $(this).val();
                let customerId = $('#customer_id').val();
                let invoiceDate = $('#invoice_date').val();

                if (productId && customerId) {
                    $.ajax({
                        type: "GET",
                        url: "{{ route('services.service-get-serial-ids') }}",
                        data: {
                            product_id: productId,
                            customer_id: customerId
                        },
                        success: function(data) {
                            let serialSelect = $('#serial_number')[0]?.tomselect;
                            if (serialSelect) {
                                serialSelect.clearOptions();
                                serialSelect.addOption({ value: '', text: 'Select Serial Number' });

                                $.each(data, function(index, item) {
                                    serialSelect.addOption({ value: item.dongle_id, text: item.dongle_id });
                                });

                                serialSelect.refreshOptions();
                            }
                        },
                        error: function(xhr) {
                            console.error("Error loading serial numbers", xhr);
                        }
                    });
                }

                // === 3. Load Quantity based on sales order + product ===
                let salesOrderId = $('#sales_order_id').val();
                if (salesOrderId && productId) {
                    $.ajax({
                        type: "GET",
                        url: "{{ route('services.service-get-quantity') }}",
                        data: {
                            sales_order_id: salesOrderId,
                            product_id: productId
                        },
                        success: function(res) {
                            $('#quantity').val(res.quantity);
                        }
                    });
                }
            });
        });

</script>
<script>
    function showHideEngineer(checkbox) {
        var engineerDiv = $('#engineerDiv');
        var assignedEngineer = $('#assigned_engineer_id');
        var serviceDate = $('#service_date');
        var servicePriority = $('#service_priority');
        var remarks = $('#remarks');

        if (checkbox.checked) {
            engineerDiv.removeAttr('disabled');
            assignedEngineer.removeAttr('disabled');
            serviceDate.removeAttr('disabled');
            servicePriority.removeAttr('disabled');
            remarks.removeAttr('disabled');
        } else {
            engineerDiv.attr('disabled', true);
            assignedEngineer.attr('disabled', true);
            serviceDate.attr('disabled', true);
            servicePriority.attr('disabled', true);
            remarks.attr('disabled', true);
        }
    }
            


</script>
    <script>
        $('.datePicker').datepicker({
            format: 'yyyy-mm-dd',
            autoclose: true
        });
    </script>


@endSection
