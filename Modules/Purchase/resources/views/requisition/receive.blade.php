@extends('layout.app')
@section('title', 'Purchase Requisition Receive')
@section('description', 'Purchase Requisition Receive')
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
                                        {{ trans('Requisition Receive') }}</li>
                                </ol>
                            </nav>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12 m-2">
                    <h4 class="text-capitalize breadcrumb-title">{{ trans('Requisition Receive') }}</h4>
                </div>
                <div class="col-md-12">
                    <div class="card mb-4">
                        <div class="card-body">
                            <form action="{{ route('purchase.requisitions.receive.store') }}" method="POST"
                                enctype="multipart/form-data">
                                @csrf
                                <div class="row mb-4">
                                    <div class="col-md-6  mt-4">
                                        <div class="form-group">
                                            <label for="requisition_no">Requisition Id : </label>
                                            {{ $requisition->requisition_no }}
                                            <input type="hidden" name="requisition_id" value="{{ $requisition->id }}">
                                        </div>
                                    </div>
                                    <div class="col-md-6  mt-4">
                                        <div class="form-group">
                                            <label for="invoice_date">Invoice Date : </label>
                                            {{ date('d F, Y', strtotime($requisition->invoice_date)) }}
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="supplier_id">Supplier Name :</label>
                                            {{ @$requisition->supplier->company_name }}
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="warehouse_id">Warehouse Name : </label>
                                            {{ @$requisition->warehouse->name }}
                                        </div>
                                    </div>
                                    <div class="col-md-6 ">
                                        <div class="input-group  align-items-center">
                                            <label for="purchase_invoice" class="input-group-text">Reference Bill No:</label>
                                            <input type="text" name="purchase_invoice" class="form-control"
                                                value="{{ old('purchase_invoice') }}">
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="created_by">Created By : </label>
                                            {{ $requisition->createdBy->name }}
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="row mt-4">
                                            <div class="col-md-12">
                                                <h3>Product Information</h3>
                                                <div class="table-responsive">
                                                    <table class="table table-bordered" id="product_info_table">
                                                        <thead>
                                                            <tr>
                                                                <th class="text-center" style="width: 5%">Sl</th>
                                                                <th class="text-center" style="width: 25%">Product Name</th>
                                                                <th class="text-center" style="width: 15%">Approved Quantity
                                                                </th>
                                                                <th class="text-center" style="width: 15%">Receive Quantity</th>
                                                                </th>
                                                                <th class="text-center" style="width: 15%">Action</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @foreach ($requisition->requisitionDetails as $key => $item)
                                                                <tr>
                                                                    <td class="text-center">{{ $key + 1 }}</td>
                                                                    <td>
                                                                        <input type="hidden" name="product_ids[]"
                                                                            value="{{ $item->product_id }}">
                                                                        {{ $item->product->name }}
                                                                        {{ $item->product->tag->name }}
                                                                        <b>Brand:</b>
                                                                        {{ $item->product->brand->name }}
                                                                    </td>
                                                                    <td class="text-center">
                                                                        <input type="text" name="approved_quantity[]"
                                                                            id="approved_quantity_{{ $loop->index }}"
                                                                            value="{{ $item->quantity }}"
                                                                            class="form-control text-center" readonly>
                                                                    </td>

                                                                    <td class="text-center">
                                                                        <input type="text" name="receive_quantity[]"
                                                                            id="receive_quantity_{{ $loop->index }}"
                                                                            value="{{ $item->quantity }}"
                                                                            class="form-control text-center" readonly>
                                                                    </td>
                                                                    <td class="text-center">
                                                                        <div class="btn-group btn-group-sm" role="group"
                                                                            aria-label="Small button group">
                                                                            {{-- @if (hasPermission('purchase.requisitions.receive')) --}}
                                                                                @if ($item->product->is_expire_date == 'yes')
                                                                                    <button class="btn btn-xs btn-primary me-1"
                                                                                        type="button" data-bs-toggle="modal"
                                                                                        data-product_name="{{ $item->product->name }} {{ $item->product->tag->name }} Brand: {{ $item->product->brand->name }}"
                                                                                        data-product_id="{{ $item->product_id }}"
                                                                                        data-requisition_id="{{ $requisition->id }}"
                                                                                        data-quantity="{{ $item->quantity }}"
                                                                                        data-index="{{ $loop->index }}"
                                                                                        data-bs-target="#createModal">
                                                                                        <i class="fa fa-plus"></i>
                                                                                    </button>
                                                                                @endif
                                                                                @if ($item->product->is_serial == 'yes')
                                                                                    <button type="button"
                                                                                        class="btn btn-xs btn-primary me-1 createModalSerial"
                                                                                        data-bs-toggle="modal"
                                                                                        data-product_name=" {{ $item->product->name }} {{ $item->product->tag->name }} Brand: {{ $item->product->brand->name }}"
                                                                                        data-product_id="{{ $item->product_id }}"
                                                                                        data-quantity="{{ $item->quantity }}"
                                                                                        data-index="{{ $loop->index }}"
                                                                                        data-requisition_id="{{ $requisition->id }}"
                                                                                        data-bs-target="#createModalSerial">
                                                                                        <i class="fa fa-plus"></i>
                                                                                    </button>
                                                                                @endauth
                                                                            {{-- @endif --}}
                                                                    </div>
                                                                </td>

                                                            </tr>
                                                        @endforeach

                                                    </tbody>
                                                </div>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-11">
                                    <div
                                        class="button-group d-flex pt-25 justify-content-md-end justify-content-start">
                                        <button type="submit" class="btn btn-success btn-sm">Received</button>
                                        <a href="{{ route('purchase.requisitions.index') }}"
                                            class="btn btn-primary btn-sm">Back</a>
                                    </div>
                                </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade inputForm-modal" id="createModal" tabindex="-1" role="dialog"
        aria-labelledby="createModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header" id="createModalLabel">
                    <h5 class="modal-title">{{ trans('Batch Entry') }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
                </div>
                <form id="batchForm" action="{{ route('purchase.requisitions.storeBatch') }}" method="post">
                    @csrf
                    <meta name="csrf-token" content="{{ csrf_token() }}">

                    <div class="modal-body">
                        <div class="row mb-4">
                            <div class="mt-4">
                                <div class="form-group">
                                    <label for="product_name">Product Name:</label><span id="product_name"></span>
                                    <input type="hidden" name="product_id" id="batch_product_id" value=""
                                        class="form-control">
                                    <input type="hidden" id="received_quantity" value="">
                                </div>
                                <div class="form-group">
                                    <label for="received_quantity">Receiving Quantity:</label><span id="received_quantity1"></span>
                                </div>

                            </div>
                        </div>
                        <div class="row mb-4">
                            <table class="table table-borderless">
                                <thead>
                                    <tr>
                                        <th>Sl</th>
                                        <th>Batch No</th>
                                        <th>Manufacture No</th>
                                        <th>Lot No <span class="text-danger">*</span></th>
                                        <th>Expired Date<span class="text-danger">*</span></th>
                                        <th>Quantity<span class="text-danger">*</span></th>
                                        <th>
                                            <button type="button" class="btn btn-info btn-sm" id="add_row">
                                                <i class="fa fa-plus"></i> Add
                                            </button>
                                        </th>
                                    </tr>
                                </thead>
                                <tbody id="batch_table_body">
                                    <tr>
                                        <td>1</td>
                                        <td>
                                            <input type="hidden" name="requisition_id"
                                                value="{{ $requisition->id }}">
                                            <input type="text" name="batch_no[]" class="form-control">
                                        </td>
                                        <td><input type="text" name="manufacture_no[]" class="form-control"></td>
                                        <td><input type="text" name="lot_no[]" class="form-control" required></td>
                                        <td><input type="date" name="expired_date[]" class="form-control"
                                                required></td>
                                        <td><input type="text" name="quantity[]" class="form-control" required>
                                        </td>
                                        <td>
                                            <button type="button" class="btn btn-danger btn-sm"
                                                onclick="removeRow(this)">
                                                <i class="fa fa-times"></i>
                                            </button>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-light-danger mt-2 mb-2 btn-no-effect"
                                data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-primary mt-2 mb-2 btn-no-effect">Save</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade inputForm-modal" id="createModalSerial" tabindex="-1" role="dialog"
        aria-labelledby="createModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">

                <div class="modal-header" id="createModalLabel">
                    <h5 class="modal-title">{{ trans('Batch Entry') }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
                </div>
                <form action="{{ route('purchase.requisitions.storeSerial') }}" method="post">
                    @csrf
                    <div class="modal-body">
                        <div class="row mb-4">
                            <div class="mt-4">
                                <div class="form-group">
                                    <label for="product_name">Product Name :</label><span id="product_name"></span>
                                    <input type="hidden" name="product_id" id="serial_product_id" value=""
                                        class="form-control">
                                </div>
                            </div>
                        </div>
                        <div class="row mb-4">
                            <table class="table table-borderless">
                                <thead>
                                    <tr>
                                        <th>Sl</th>
                                        <th>Serial No<span class="text-danger">*</span></th>
                                        <th>Dongle ID</th>
                                        <th>Manufacture Date<span class="text-danger">*</span></th>
                                        <th>Image/File</th>
                                        <th>Quantity<span class="text-danger">*</span></th>
                                    </tr>
                                </thead>
                                <tbody id="serial_table_body">
                                    <tr>
                                        <td>{{ 1 }}</td>
                                        <td>
                                            <input type="hidden" name="requisition_id"
                                                value="{{ $requisition->id }}">
                                            <input type="text" name="serial_no[]" class="form-control" required>

                                        </td>
                                        <td><input type="text" name="dongle_no[]" class="form-control"></td>
                                        <td><input type="date" name="manufacture_date[]" class="form-control"
                                                value="{{ date('Y-m-d') }}" required>
                                        </td>
                                        <td><input type="file" name="image[]" class="file-control form-control"
                                            data-preview-element="front-image-preview"></td>
                                        <td><input type="text" name="quantity[]" value="1"
                                                class="form-control text-center" readonly></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-light-danger mt-2 mb-2 btn-no-effect"
                                data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-primary mt-2 mb-2 btn-no-effect">Save</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
</div>
@endsection

@section('page_scripts')
<script>
    // Batch Modal Handling
    $('#createModal').on('show.bs.modal', function(event) {
        var button = $(event.relatedTarget);
        var productName = button.data('product_name');
        var productId = button.data('product_id');
        var index = button.data('index');
        var requisitionId = "{{ $requisition->id }}";
        var receivedQuantity = $('#receive_quantity_' + index).val();

        var modal = $(this);
        modal.find('#product_name').text(productName);
        modal.find('#received_quantity1').text(receivedQuantity);

        modal.find('#batch_product_id').val(productId);
        modal.find('#received_quantity').val(receivedQuantity);
        var url = '{{ route('purchase.requisitions.batches', ['requisition_id' => ':id']) }}';
        url = url.replace(':id', requisitionId);
        // Fetch existing batches
        $.ajax({
            url: url,
            method: 'GET',
            data: { product_id: productId },
            success: function(response) {
                var tbody = modal.find('#batch_table_body');
                tbody.empty();
                response.batches.forEach(function(batch, idx) {
                    var row = `
                        <tr>
                            <td>${idx + 1}</td>
                            <td>
                                <input type="hidden" name="requisition_id" value="${requisitionId}">
                                <input type="text" name="batch_no[]" class="form-control" value="${batch.batch_no}">
                            </td>
                            <td><input type="text" name="manufacture_no[]" class="form-control" value="${batch.manufacture_no}"></td>
                            <td><input type="text" name="lot_no[]" class="form-control" required value="${batch.lot_no}"></td>
                            <td><input type="date" name="expired_date[]" class="form-control" required value="${batch.expired_date}"></td>
                            <td><input type="number" name="quantity[]" class="form-control" required value="${batch.quantity}"></td>
                            <td>
                                <button type="button" class="btn btn-danger btn-sm" onclick="removeRow(this)">
                                    <i class="fa fa-times"></i>
                                </button>
                            </td>
                        </tr>`;
                    tbody.append(row);
                });
                
                // Add empty row for new entries
                tbody.append(`
                    <tr>
                        <td>${response.batches.length + 1}</td>
                        <td>
                            <input type="hidden" name="requisition_id" value="${requisitionId}">
                            <input type="text" name="batch_no[]" class="form-control">
                        </td>
                        <td><input type="text" name="manufacture_no[]" class="form-control"></td>
                        <td><input type="text" name="lot_no[]" class="form-control" required></td>
                        <td><input type="date" name="expired_date[]" class="form-control" required></td>
                        <td><input type="number" name="quantity[]" class="form-control" required></td>
                        <td>
                            <button type="button" class="btn btn-danger btn-sm" onclick="removeRow(this)">
                                <i class="fa fa-times"></i>
                            </button>
                        </td>
                    </tr>`);
                updateSerialNumbers();
            }
        });
    });
        $('#add_row').click(function() {
        // Get the current number of rows in the table body
        var rowCount = $('#batch_table_body tr').length;

        // Create a new row (customize the inputs as needed)
        var newRow = `
            <tr>
                <td>${rowCount + 1}</td>
                <td>
                    <input type="hidden" name="requisition_id" value="{{ $requisition->id }}">
                    <input type="text" name="batch_no[]" class="form-control">
                </td>
                <td><input type="text" name="manufacture_no[]" class="form-control"></td>
                <td><input type="text" name="lot_no[]" class="form-control" required></td>
                <td><input type="date" name="expired_date[]" class="form-control" required></td>
                <td><input type="number" name="quantity[]" class="form-control" required></td>
                <td>
                    <button type="button" class="btn btn-danger btn-sm" onclick="removeRow(this)">
                        <i class="fa fa-times"></i>
                    </button>
                </td>
            </tr>
        `;

        // Append the new row to the table body
        $('#batch_table_body').append(newRow);
        updateSerialNumbers();
    });

    // Serial Modal Handling
    $('#createModalSerial').on('show.bs.modal', function(event) {
        var button = $(event.relatedTarget);
        var productName = button.data('product_name');
        var productId = button.data('product_id');
        var index = button.data('index');
        var requisitionId = "{{ $requisition->id }}";

        var modal = $(this);
        modal.find('#product_name').text(productName);
        modal.find('#serial_product_id').val(productId);

        // Fetch existing serials
        var url = '{{ route('purchase.requisitions.serials', ['requisition_id' => ':id']) }}';
        url = url.replace(':id', requisitionId);

        $.ajax({
            url: url,
            method: 'GET',
            data: { product_id: productId },
            success: function(response) {
                var tbody = modal.find('#serial_table_body');
                tbody.empty();
                
                response.serials.forEach(function(serial, idx) {
                    var row = `
                        <tr>
                            <td>${idx + 1}</td>
                            <td>
                                <input type="hidden" name="requisition_id" value="${requisitionId}">
                                <input type="text" name="serial_no[]" class="form-control" value="${serial.serial_no}" required>
                            </td>
                            <td><input type="text" name="dongle_no[]" class="form-control" value="${serial.dongle_no == null ? '' : serial.dongle_no || ''}">
</td>
                            <td><input type="date" name="manufacture_date[]" class="form-control" value="${serial.manufacture_date}" required></td>
                            <td>
                                ${serial.image ? `<a href="${serial.image}" target="_blank"><img src="${serial.image}" style="max-width: 50px; max-height: 50px;" /></a>` : ''}
                                <input type="file" name="image[]" value="${serial.image}" class="file-control form-control"
                                                    data-preview-element="front-image-preview">
                            </td>
                            <td><input type="text" name="quantity[]" value="1" class="form-control text-center" readonly></td>
                        </tr>`;
                    tbody.append(row);
                });

                // Add new rows if needed
                var qty = $('#receive_quantity_' + index).val();
                for (let i = response.serials.length; i < qty; i++) {
                    var newRow = `
                        <tr>
                            <td>${i + 1}</td>
                            <td>
                                <input type="hidden" name="requisition_id" value="${requisitionId}">
                                <input type="text" name="serial_no[]" class="form-control" required>
                            </td>
                            <td><input type="text" name="dongle_no[]" class="form-control"></td>
                            <td><input type="date" name="manufacture_date[]" class="form-control" value="{{ date('Y-m-d') }}" required></td>
                            <td><input type="file" name="image[]" class="file-control form-control"
                                                    data-preview-element="front-image-preview"></td>
                            <td><input type="text" name="quantity[]" value="1" class="form-control text-center" readonly></td>
                        </tr>`;
                    tbody.append(newRow);
                }
            }
        });
    });

    // Common Functions
    function updateSerialNumbers() {
        $('#batch_table_body tr, #serial_table_body tr').each(function(index) {
            $(this).find('td:first').text(index + 1);
        });
    }

    window.removeRow = function(row) {
        $(row).closest('tr').remove();
        updateSerialNumbers();
    }

    // Batch Form Submission
    $('#batchForm').submit(function(e) {
        e.preventDefault();
        var formData = new FormData(this);
        var total = Array.from(formData.getAll('quantity[]')).reduce((a, b) => a + parseInt(b), 0);
        var expected = parseInt($('#received_quantity').val());

        if (total !== expected) {
            toastr.error(`Total batch quantities (${total}) must match received quantity (${expected})`);
            return;
        }

        $.ajax({
            url: $(this).attr('action'),
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                $('#createModal').modal('hide');
                toastr.success(response.message);
            },
            error: function(xhr) {
                toastr.error(xhr.responseJSON.message);
            }
        });
    });

    // Serial Form Submission
    $('#createModalSerial form').submit(function(e) {
        e.preventDefault();
        var formData = new FormData(this);
        
        $.ajax({
            url: $(this).attr('action'),
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                $('#createModalSerial').modal('hide');
                toastr.success(response.message);
            },
            error: function(xhr) {
                toastr.error(xhr.responseJSON.message);
            }
        });
    });

    // Duplicate Checkers
    $(document).on('change', 'input[name="serial_no[]"], input[name="dongle_no[]"]', function() {
        var currentVal = $(this).val();
        var duplicates = $(`input[name="${this.name}"][value="${currentVal}"]`).length;
        
        if (duplicates > 1) {
            $(this).val('');
            toastr.error(`Duplicate ${this.name.includes('serial') ? 'serial' : 'dongle'} number found`);
        }
    });
</script>

@endsection
