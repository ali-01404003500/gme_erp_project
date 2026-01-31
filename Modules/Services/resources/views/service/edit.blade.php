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
            height: 48px !important;
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
                        <div class="action-btn mt-sm-0 mt-15 d-flex align-items-center">
                            <button type="button" class="btn px-20 btn-success btn-sm mr-5" onclick="printAllQRCodes()">
                                <i class="fa fa-qrcode"></i> Print QR Codes
                            </button>
                          
                            @if (hasPermission('services.service.index'))
                                <a href="{{ route('services.service.index') }}"
                                    class="btn btn-warning btn-default btn-squared radius-md shadow2 btn-sm"><i
                                        class="fa fa-list"></i> List</a>
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
                                <form action="{{ route('services.service.update', $service->id) }}" method="POST"
                                    enctype="multipart/form-data">
                                    @csrf
                                    @method('PUT')

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="customer_id">{{ __('Customer') }}<span
                                                        class="text-danger">*</span>:</label>
                                                <select name="customer_id" id="customer_id" class="form-control tom-select" disabled>
                                                <option value="{{ $service->serviceTokens->first()->customer_id ?? '' }}" selected>
                                                    {{ optional($service->serviceTokens->first()->customer)->company_name ?? __('Select Customer') }}
                                                </option>
                                            </select>
                                            <input type="hidden" name="customer_id" value="{{ $service->serviceTokens->first()->customer_id ?? '' }}">
                                            <input type="hidden" name="address" id="address" value="{{ $service->serviceTokens->first()->customer->address ?? '' }}">
                                            <input type="hidden" name="product_type" id="product_type"
                                                value="Imaging/Radiology Product">
                                            </div>
                                        </div>                                       
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="contact_person_phone">Contact Person Phone No<span
                                                        class="text-danger">*</span>:</label>
                                                <input type="text" name="contact_person_phone" id="contact_person_phone"
                                                    class="form-control" placeholder="{{ __('Contact Person Phone No') }}">
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="token_date">{{ __('Token Date') }}<span
                                                        class="text-danger">*</span>:</label>
                                                <input type="date" name="token_date" id="token_date" class="form-control"
                                                    value="{{ date('Y-m-d') }}">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="sales_order_id">{{ __('Invoice ID') }}:</label>
                                                <select name="invoice_id" id="sales_order_id"
                                                    class="form-control tom-select" >
                                                    <option value="">{{ __('Select Invoice ID') }}</option>

                                                    @foreach ($salesOrders as $salesOrder)
                                                        <option value="{{ $salesOrder->id }}"
                                                            @if (old('sales_order_id') == $salesOrder->id) selected @endif
                                                            data-customer = "{{ $salesOrder->customer_id }}"
                                                            data-invoice_date = "{{ $salesOrder->invoice_date }}">
                                                            {{ $salesOrder->sales_order_id }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="invoice_date">{{ __('Invoice Date') }}:</label>
                                                <input type="text" name="invoice_date" id="invoice_date"
                                                    class="form-control flatdate">
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="expire_date">{{ __('Expire Date') }}:</label>
                                                <input type="text" name="expire_date" id="expire_date"
                                                    class="form-control flatdate" readonly>
                                            </div>


                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="product_id">{{ __('Product') }}<span
                                                        class="text-danger">*</span>:</label>
                                                <select name="product_catalog_id" id="product_catalog_id"
                                                    class="form-control tom-select">
                                                    <option value="">{{ __('Select Product') }}</option>
                                                    @foreach ($productCatalogs as $productCatalog)
                                                        <option value="{{ $productCatalog->id }}"
                                                            data-warranty_period="{{ $productCatalog->warranty_period }}"
                                                            data-warranty_period_input="{{ $productCatalog->warranty_period_input }}">
                                                            {{ $productCatalog->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <input type="hidden" name="quantity" id="quantity" value="0">

                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="serial_number">{{ __('Serial Number') }}<span
                                                        class="text-danger">*</span>:</label>
                                                <select name="serial_number" id="serial_number" class="form-control">
                                                </select>
                                            </div>
                                        </div>


                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="service_type">{{ __('Service Type') }} <span
                                                        class="text-danger">*</span>:</label>
                                                <select name="service_type" id="service_type" class="form-control">
                                                    <option value="ON SPOT">ON SPOT</option>
                                                    <option value="IN HOUSE">IN HOUSE</option>
                                                    <option value="ON CALL">ON CALL</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="InternalVideoLink">{{ __('Internal Video Link') }}<span
                                                        class="text-danger">*</span>:</label>
                                                <input type="text" name="internal_video_link" id="InternalVideoLink"
                                                    class="form-control">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="ExternalVideoLink">{{ __('External Video Link') }}<span
                                                        class="text-danger">*</span>:</label>
                                                <input type="text" name="external_video_link" id="ExternalVideoLink"
                                                    class="form-control">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-9">
                                            <div class="form-group">
                                                <label for="problem_details">{{ __('Problem Details') }}<span
                                                        class="text-danger">*</span>:</label>
                                                <textarea name="problem_details" id="problem_details" class="form-control"
                                                    placeholder="{{ __('Problem Details') }}"></textarea>
                                            </div>
                                        </div>
                                        <meta name="csrf-token" content="{{ csrf_token() }}">

                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="problem_type">{{ __('Problem Type') }}<span
                                                        class="text-danger">*</span>:</label>
                                                <select name="problem_type" id="problem_type" class="form-control">
                                                    <option value="">{{ __('Select Problem Type') }}</option>
                                                </select>
                                            </div>

                                            <div class="form-group">
                                                <label for="work_type">{{ __('Work Type') }}<span
                                                        class="text-danger">*</span>:</label>
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
                                                <button type="button" class="btn btn-sm btn-primary"
                                                    id="addRow">{{ __('Add') }}</button>
                                            </div>
                                        </div>
                                    </div>
                                   
                                    <div class="form-group">
                                        <table class="table table-bordered" id="service_table">
                                            <thead>
                                                <tr>
                                                    <th>Sl</th>
                                                    <th>Attachments</th>
                                                    <th>Customer</th>
                                                    <th>Product</th>
                                                    <th>Quantity</th>
                                                    <th>Serial Number</th>
                                                    <th>Problem Details</th>
                                                    <th>Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($service->serviceTokens as $key => $serviceToken)
                                                    <tr id="{{ $serviceToken->id }}">
                                                        <th scope="row">
                                                            {{ $key + 1 }}
                                                            <input type="hidden" name="customer_id[]"
                                                                value={{ $serviceToken->customer_id }}>
                                                            <input type="hidden" name="contact_person_phone[]"
                                                                value={{ $serviceToken->contact_person_phone }}>
                                                            <input type="hidden" name="token_date[]"
                                                                value="{{ $serviceToken->token_date }}">
                                                            <input type="hidden" name="invoice_id[]"
                                                                value="{{ $serviceToken->invoice_id }}">
                                                            <input type="hidden" name="invoice_date[]"
                                                                value="{{ $serviceToken->invoice_date }}">
                                                            <input type="hidden" name="expire_date[]"
                                                                value="{{ $serviceToken->expire_date }}">
                                                            <input type="hidden" name="service_type[]"
                                                                value="{{ $serviceToken->service_type }}">
                                                            <input type="hidden" name="problem_type[]"
                                                                value="{{ $serviceToken->problem_type }}">
                                                            <input type="hidden" name="work_type[]"
                                                                value="{{ $serviceToken->work_type }}">
                                                            <input type="hidden" name="product_id[]"
                                                                value="{{ $serviceToken->product_id }}">
                                                            <input type="hidden" name="serial_number[]"
                                                                value="{{ $serviceToken->serial_number }}">
                                                            <input type="hidden" name="quantity[]"
                                                                value="{{ $serviceToken->quantity }}">
                                                            <input type="hidden" name="internal_video_link[]"
                                                                value="{{ $serviceToken->internal_video_link }}">
                                                            <input type="hidden" name="external_video_link[]"
                                                                value="{{ $serviceToken->external_video_link }}">
                                                        </th>
                                                        
                                                        <td>
                                                            <div class="dropdown dropdown-click">
                                                                <div class="btn-group dropleft">
                                                                    <button type="button" class="btn btn-xs btn-secondary attachments">
                                                                        <i class="fa fa-paperclip"></i>
                                                                    </button>
                                                                </div>
                                                            </div>
                                                            <input type="hidden" name="documents[]" value='{{$serviceToken->documents}}'' class="attachments_input">
                                                        </td>
                                                        <td>{{ optional($serviceToken->customer)->company_name }}</td>
                                                        <td>{{ optional($serviceToken->product)->name }}</td>
                                                        {{-- <td>{{ $serviceToken->quantity }}</td> --}}
                                                        <td><input type="number" name="quantity[]"
                                                                value="{{ old('quantity')[$key] ?? $serviceToken->quantity }}"
                                                                class="form-control" readonly></td>
                                                        <td>{{ $serviceToken->serial_number }}</td>
                                                        <td><input type="text" class="form-control" name="problem_details[]"
                                                                value="{{ $serviceToken->problem_details }}"></td>
                                                        <td>
                                                            <button type="button" class="btn btn-sm btn-danger"
                                                                onclick="removeRow(this)"><i class="fa fa-trash"></i></button>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                    <div id="all_qr_codes" style="display:none;">
                                    @foreach ($service->serviceTokens as $key => $serviceToken)
                                        <div class="qr-item">
                                            <h4>Service QR Code - {{ $key + 1 }}</h4>
                                            <img id="qr_img_{{ $key }}" />                                            
                                            <p><strong>Serial:</strong> {{ $serviceToken->serial_number }}</p>
                                            Company Name: {{ optional($company_info)->company_name }}
                                        </div>
                                        @if(($key + 1) % 3 == 0) <!-- After every 3 items -->
                                            <div class="page-break"></div>
                                        @endif
                                    @endforeach
                                </div>

                                    {{-- <div class="row">
                                        <div class="form-group">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" name="is_assigned"
                                                    id="is_assigned"
                                                    onchange="this.value = this.checked ? 1 : 0; 
                                                showHideEngineer(this)"
                                                    value="{{ old('is_assigned') ?? $service->is_assigned }}"
                                                    @if (old('is_assigned') == 1 || $service->is_assigned == 1) checked @endif>
                                                <label class="form-check-label" for="is_assigned">
                                                    {{ __('Is Assigned') }}
                                                </label>
                                            </div>
                                        </div>
                                        <div class="col-md-5">
                                            <div class="form-group">
                                                <label for="assigned_engineer_id">{{ __('Assigned Engineer') }}:</label>
                                                <select name="assigned_engineer_id" id="assigned_engineer_id"
                                                    class="form-control select2">
                                                    <option value="">{{ __('Select Engineer') }}</option>
                                                    @foreach ($employees as $employee)
                                                        <option value="{{ $employee->id }}"
                                                            {{ $service->assigned_engineer_id == $employee->id ? 'selected' : '' }}>
                                                            {{ $employee->full_name }}-{{ $employee->id }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <label for="service_date">{{ __('Service Date') }}:</label>
                                                <input type="date" name="service_date" id="service_date"
                                                    class="form-control" value="{{ $service->service_date }}">
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <label for="service_priority">{{ __('Service Priority') }}:</label>
                                                <select name="service_priority" id="service_priority"
                                                    class="form-control">
                                                    <option value="">{{ __('Select Priority') }}</option>
                                                    <option value="HIGH"
                                                        {{ $service->service_priority == 'HIGH' ? 'selected' : '' }}>HIGH
                                                    </option>
                                                    <option value="MEDIUM"
                                                        {{ $service->service_priority == 'MEDIUM' ? 'selected' : '' }}>
                                                        MEDIUM</option>
                                                    <option value="LOW"
                                                        {{ $service->service_priority == 'LOW' ? 'selected' : '' }}>LOW
                                                    </option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="service_type_name">{{ __('Service Type') }}:</label>
                                                <select name="service_type_name" id="service_type_name"
                                                    class="form-control">
                                                    <option value="">{{ __('Select Service Type') }}</option>
                                                    <option value="ON SPOT"
                                                        {{ $service->service_type_name == 'ON SPOT' ? 'selected' : '' }}>ON
                                                        SPOT</option>
                                                    <option value="IN HOUSE"
                                                        {{ $service->service_type_name == 'IN HOUSE' ? 'selected' : '' }}>
                                                        IN HOUSE</option>
                                                    <option value="ON CALL"
                                                        {{ $service->service_type_name == 'ON CALL' ? 'selected' : '' }}>ON
                                                        CALL</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="remarks">{{ __('Remarks') }}:</label>
                                        <textarea name="remarks" id="remarks" class="form-control" placeholder="{{ __('Remarks') }}">{{ $service->remarks }}</textarea>
                                    </div> --}}

                                    <input type="hidden" name="status" id="status"
                                        value="{{ $service->status }}" />
                                    <input type="hidden" name="action" id="action" value="{{ $service->action }}" />

                                    <div class="d-flex justify-content-end mt-4">
                                        {{-- <button type="submit" id="quit"
                                            class="btn btn-danger btn-sm">{{ __('Quit') }}</button> --}}
                                        <button type="submit"
                                            class="btn btn-primary btn-sm">{{ __('Update') }}</button>

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
<script src="https://cdnjs.cloudflare.com/ajax/libs/qrious/4.0.2/qrious.min.js"></script>
<script>

    function updateDocumentsPreview() {
                $("input[name='documents[]']").each(function(index, file) {
                    const closet = $(this).closest('td');
                    if (this.value) {
                        const filesUrls = JSON.parse(this.value);
                        let dropdown = `
                    <button type="button" class="btn btn-secondary btn-xs dropdown-toggle-split" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class="fa fa-download" ></i>
                    </button>
                    <div class="dropdown-default dropdown-menu" style="">
            `;
                        filesUrls.forEach((fileUrl, index) => {
                            const fileName = fileUrl.substr(fileUrl.lastIndexOf('-') + 1);
                            const shortFileName = fileName.length > 20 ? fileName.substr(0, 10) +
                                '...' +
                                fileName.substr(fileName.length - 10) : fileName;
                            dropdown += `
                <div class="dropdown-item d-flex justify-content-between align-items-center">
                    <a href="${fileUrl}" target="_blank" class="text-truncate" style="max-width: 80%;">${index + 1}. ${shortFileName}</a>
                    <button type="button" class="btn text-danger remove-doc" data-index="${index}" data-file="${fileUrl}">
                        <i class="fa fa-times"></i>
                    </button>
                </div>
                `
                        });
                        dropdown += `
                    </div>
            `;
                        //clear previous dropdown buttons except .attachments
                        closet.find(".btn-group>*").not(".attachments").remove();

                        closet.find(".btn-group").append(dropdown);
                    };
                });

                // Add click handler for remove buttons
                $(document).on('click', '.remove-doc', function() {
                    const index = $(this).data('index');
                    const fileUrl = $(this).data('file');
                    const inputField = $(this).closest('td').find('.attachments_input');
                    const row = $(this).closest('tr');

                    let filesUrls = JSON.parse(inputField.val());

                    // Remove the file from the array
                    filesUrls = filesUrls.filter(url => url !== fileUrl);

                    // Update the hidden input
                    inputField.val(JSON.stringify(filesUrls));

                    // Extract just the path portion from the full URL if needed
                    const filePath = extractFilePathFromUrl(fileUrl);

                    // Show loading state
                    $(this).html('<i class="fa fa-spinner fa-spin"></i>');

                    // Send AJAX request to delete the file from S3
                    $.ajax({
                        url: "{{ route('delete_file') }}",
                        type: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': "{{ csrf_token() }}"
                        },
                        data: {
                            path: filePath
                        },
                        success: function(response) {
                            toastr.success(response.message);
                            // Refresh the preview
                            updateDocumentsPreview();
                        },
                        error: function(xhr) {
                            toastr.error('Failed to delete file: ' + (xhr.responseJSON
                                ?.message || 'Server error'));
                            // Restore the delete icon
                            $(this).html('<i class="fa fa-times"></i>');
                        }
                    });
                });
            }

            // Helper function to extract path from URL if needed
            function extractFilePathFromUrl(url) {
                // If you're storing full URLs but need just the path for S3
                // Example: https://bucket.s3.region.amazonaws.com/uploads/file.pdf -> uploads/file.pdf
                const matches = url.match(/amazonaws\.com\/(.*)/);
                return matches ? matches[1] : url;
            }
    document.addEventListener("DOMContentLoaded", function() {
        @foreach ($service->serviceTokens as $key => $serviceToken)
            var qr{{ $key }} = new QRious({
                value: `Service ID: {{ $service->service_unique_id }}\nCustomer: {{ optional($serviceToken->customer)->company_name }}\nProduct: {{ optional($serviceToken->product)->name }}\nSerial: {{ $serviceToken->serial_number }}\nProblem: {{ $serviceToken->problem_details }}`,
                size: 200
            });
            document.getElementById('qr_img_{{ $key }}').src = qr{{ $key }}.toDataURL();
        @endforeach

        updateDocumentsPreview();
    });

    function printAllQRCodes() {
    var content = document.getElementById('all_qr_codes').innerHTML;
    var printWindow = window.open('', '', 'width=1000,height=800,left=300,top=100');
    printWindow.document.open();
    printWindow.document.write(`
        <html>
            <head>
                <title>Service Tokens</title>
                <style>
                    .qr-item {
                        margin-bottom: 20px;
                        page-break-inside: avoid;
                        text-align: center;
                    }
                    .page-break {
                        page-break-after: always; /* Force page break after every 3 items */
                    }
                </style>
            </head>
            <body>
                <div class="qr-container">
                    ${content}
                </div>
                <script>
                    window.onload = function() {
                        window.print();
                    };
                    window.onafterprint = function() {
                        window.close();
                    };
                <\/script>
            </body>
        </html>
    `);
    printWindow.document.close();
}


</script>




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
        $('#is_assigned').on('change', function() {
            if ($(this).is(':checked')) {
                $('#status').val('pending');
            }
        });
        $('#quit').on('click', function() {
            if ($('#status').val() == 'pending') {
                $('#status').val('Failed');
            }
            return true;
        });
    </script>
    <script>
        $(document).ready(function() {
            const csrfToken = $('meta[name="csrf-token"]').attr('content');

            const serialSelect = new TomSelect('#serial_number', {
                valueField: 'id',
                labelField: 'name',
                searchField: 'name',
                create: function(input, callback) {
                    const customerId = $('#customer_id').val();
                    const address = $('#address').val();
                    const productCatalogId = $('#product_catalog_id').val();
                    const productType = $('#product_type').val();

                    $.ajax({
                        url: '{{ route('licenses.dongle-or-serial-entries.store-dongle') }}',
                        method: 'POST',
                        data: {
                            customer_id: customerId,
                            address: address,
                            product_id: productCatalogId,
                            product_type: productType,
                            dongle_id: input,
                            status: "Active",
                            _token: csrfToken
                        },
                        success: function(response) {
                            const newOption = {
                                id: response.dongleOrSerialEntry.dongle_id,
                                name: response.dongleOrSerialEntry.dongle_id
                            };
                            callback(newOption);
                        },
                        error: function(xhr) {
                            alert(xhr.responseJSON?.message ||
                                'Failed to create new Serial Number');
                            callback();
                        }
                    });
                },
            });

            $('#product_catalog_id').on('change', function() {
                let productId = $(this).val();
                let customerId = $('#customer_id').val();

                if (productId && customerId) {
                    $.ajax({
                        type: "GET",
                        url: "{{ route('services.service-get-serial-ids') }}",
                        data: {
                            product_id: productId,
                            customer_id: customerId
                        },
                        success: function(data) {
                            serialSelect.clearOptions();

                            data.forEach(item => {
                                serialSelect.addOption({
                                    id: item.dongle_id,
                                    name: item.dongle_id
                                });
                            });

                            serialSelect.refreshOptions();
                        },
                        error: function(xhr) {
                            console.error("Error loading serial numbers", xhr);
                        }
                    });
                }
            });


            $(document).on('click', '.attachments', function(e) {
                e.preventDefault();

                // Create a hidden file input element
                const fileInput = $('<input type="file" multiple>').appendTo('body').hide();
                fileInput.trigger('click');

                fileInput.on('change', function() {
                    const files = fileInput[0].files;
                    
                    if (files.length > 0) {
                        for (const file of files) {
                            const formData = new FormData();
                            formData.append('file', file);

                            // add class to button to disable it
                            $(e.currentTarget).addClass('btn-loading');
                            $.ajax({
                                url: "{{ route('upload_file') }}",
                                type: 'POST',
                                headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                                data: formData,
                                processData: false,
                                contentType: false,
                                success: function(response) {
                                    // Assuming response contains an array of URLs
                                    let urls = $(e.currentTarget).data('urls') || [];
                                    urls.push(response.path);
                                    $(e.currentTarget).data('urls', urls);

                                    //closeset of currentTarget 
                                    $(e.currentTarget).closest('tr').find('.attachments_input').val(JSON.stringify(urls));

                                    // Remove the class after the upload is complete
                                    $(e.currentTarget).removeClass('btn-loading');
                                    toastr.success('Files uploaded successfully!');
                                    updateDocumentsPreview();
                                },
                                error: function() {
                                    // Remove the class after the upload is complete
                                    $(e.currentTarget).removeClass('btn-loading');
                                    toastr.error('Failed to upload files.');
                                }
                            });
                        }
                    }

                    // Remove the file input element after processing
                    fileInput.remove();
                });
            });
        });
    </script>

    <script>
        $(document).ready(function() {
            const csrfToken = $('meta[name="csrf-token"]').attr('content');

            new TomSelect('#problem_type', {
                valueField: 'value',
                labelField: 'text',
                searchField: 'text',
                create: function(input, callback) {

                    $.ajax({
                        url: '{{ route('services.settings.problem-types.store') }}',
                        method: 'POST',
                        data: {
                            name: input,
                            _token: csrfToken
                        },
                        success: function(response) {
                            callback(response);
                        },
                        error: function(xhr) {
                            alert(xhr.responseJSON?.message ||
                                'Failed to create new Problem Type');
                            callback();
                        }
                    });
                },
                load: function(query, callback) {
                    if (!query.length) return callback();
                    $.ajax({
                        url: '{{ route('services.settings.problem-types.search') }}',
                        data: {
                            q: query
                        },
                        success: function(results) {
                            callback(results);
                        },
                        error: function() {
                            callback();
                        }
                    });
                }
            });
        });
    </script>
    <script>
        function onChangeCustomer() {
            var select = document.getElementById('customer_id');
            if (select.value) {
                select.disabled = true;
            }
        }
        // Show/hide Internal/External Video Link based on Service Type
        $('#service_type').on('change', function() {
            var serviceType = $(this).val();
            if (serviceType === "IN HOUSE") {
                $('#InternalVideoLink').closest('.form-group').show().find('input').prop('required', true);
                $('#ExternalVideoLink').closest('.form-group').show().find('input').prop('required', true);
            } else {
                $('#InternalVideoLink').closest('.form-group').hide().find('input').prop('required', false).val('');
                $('#ExternalVideoLink').closest('.form-group').hide().find('input').prop('required', false).val('');
            }
        }).trigger('change'); // Run on load in case default is "IN HOUSE"

        // Handle Add Row
        $(document).on('click', '#addRow', function() {
            const requiredFields = [{
                    id: 'customer_id',
                    name: 'Customer'
                },
                {
                    id: 'contact_person_phone',
                    name: 'Contact Person Phone'
                },
                {
                    id: 'token_date',
                    name: 'Token Date'
                },
                {
                    id: 'product_catalog_id',
                    name: 'Product'
                },
                {
                    id: 'serial_number',
                    name: 'Serial Number'
                },
                {
                    id: 'service_type',
                    name: 'Service Type'
                },
                {
                    id: 'problem_details',
                    name: 'Problem Details'
                },
                {
                    id: 'problem_type',
                    name: 'Problem Type'
                },
                {
                    id: 'work_type',
                    name: 'Work Type'
                }
            ];

            // If service_type is IN HOUSE, add video link fields to validation
            if ($('#service_type').val() === 'IN HOUSE') {
                requiredFields.push({
                    id: 'InternalVideoLink',
                    name: 'Internal Video Link'
                }, {
                    id: 'ExternalVideoLink',
                    name: 'External Video Link'
                });
            }

            // Validation loop
            for (const field of requiredFields) {
                const value = $('#' + field.id).val();
                if (!value || value.trim() === '') {
                    toastr.error(`${field.name} is required`);
                    return;
                }
            }

            // All fields valid — Add row
            var customer_id = $('#customer_id').val();
            var customer_name = $('#customer_id option:selected').text();
            var contact_person_phone = $('#contact_person_phone').val();
            var token_date = $('#token_date').val();
            var invoice_id = $('#sales_order_id').val();
            var invoice_date = $('#invoice_date').val();
            var expire_date = $('#expire_date').val();
            var product_name = $('#product_catalog_id option:selected').text();
            var product_catalog_id = $('#product_catalog_id').val();
            var quantity = $('#quantity').val();
            var serial_number = $('#serial_number').val();
            var service_type = $('#service_type').val();
            var problem_details = $('#problem_details').val();
            var problem_type = $('#problem_type').val();
            var work_type = $('#work_type option:selected').text();
            var internal_video_link = $('#InternalVideoLink').val();
            var external_video_link = $('#ExternalVideoLink').val();

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
                <input type="hidden" name="internal_video_link[]" value="${internal_video_link}">
                <input type="hidden" name="external_video_link[]" value="${external_video_link}">
            </th>
             <td>
                <div class="dropdown dropdown-click">
                    <div class="btn-group dropleft">
                        <button type="button" class="btn btn-xs btn-secondary attachments">
                            <i class="fa fa-paperclip"></i>
                        </button>
                    </div>
                </div>
                <input type="hidden" name="documents[]" value="" class="attachments_input">
            </td>
            <td>${customer_name}</td>
            <td>${product_name}</td>
            <td><input type="number" name="quantity[]" value="1" class="form-control text-center" min="1"></td>
            <td>${serial_number}</td>
            <td>${problem_details}</td>
            <td>
                <button type="button" class="btn btn-sm btn-danger" onclick="removeRow(this)">
                    <i class="fa fa-trash"></i>
                </button>
            </td>
        </tr>`;

            $('#service_table tbody').append(row);

            $('#customer_id').prop('disabled', true).trigger('change');
            $('#customer_id').prop('tomselect')?.lock();
        });

        function removeRow(button) {
            $(button).closest('tr').remove();
        }
    </script>

    <script>
        $(document).ready(function() {

            $('#serial_number').on('change', function() {
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
                        success: function(res) {
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
                        error: function() {
                            toastr.error('Invoice not found!', 'Error');
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

        function onChangeCustomer(element = null) {
            const phone = $('#customer_id').find(':selected').data('phone');
            $("#contact_person_phone").val(phone);
            $("#address").val($('address'));

            // === Unselect Product and Serial when customer changes ===
            if ($('#product_catalog_id')[0]?.tomselect) {
                $('#product_catalog_id')[0].tomselect.clear(true); // clear product
            } else {
                $('#product_catalog_id').val('');
            }

            if ($('#serial_number')[0]?.tomselect) {
                $('#serial_number')[0].tomselect.clear(true); // clear serial
            } else {
                $('#serial_number').val('');
            }

            if (element) {
                const customerId = $(element).val();
                $.ajax({
                    url: '{{ route('services.service-get-invoices') }}',
                    type: 'GET',
                    data: {
                        customer_id: customerId
                    },
                    success: function(response) {
                        var sales_order_select = $('#sales_order_id');
                        if (response.length > 0) {
                            sales_order_select.empty();
                            sales_order_select.prop('tomselect').clearOptions();
                        }
                        sales_order_select.append('<option value="">Select Sales Order</option>');
                        response.forEach(function(salesOrder) {
                            sales_order_select.append('<option value="' + salesOrder.id +
                                '" data-invoice_date="' + salesOrder.invoice_date +
                                '" data-customer="' + salesOrder.customer_id + '">' + salesOrder
                                .sales_order_id + '</option>');
                        });
                        if (response.length > 0) {
                            sales_order_select.prop('tomselect').sync();
                        }
                    }
                });
            }
        }

        $(document).ready(function() {
            // === Product change: update serials, warranty expire date, and quantity ===
            $('#product_catalog_id').on('change', function() {
                let productId = $(this).val();
                let customerId = $('#customer_id').val();
                let invoiceDate = $('#invoice_date').val();

                // === Clear serial number if product is unselected ===
                if (!productId && $('#serial_number')[0]?.tomselect) {
                    $('#serial_number')[0].tomselect.clear(true);
                } else if (!productId) {
                    $('#serial_number').val('');
                }

                // === Load Quantity from sales order ===
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

                // Optional: updateWarrantyExpiry if needed here too
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

