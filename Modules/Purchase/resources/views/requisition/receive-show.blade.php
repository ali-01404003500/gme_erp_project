@section('title', 'Purchase Requisition Receive Detail')
@section('description', 'Purchase Requisition Receive Detail')
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
                                        {{ trans('Requisition Receive Detail') }}</li>
                                </ol>
                            </nav>
                        </div>
                    </div>
                </div>
            </div>
            <style>
                body {
                    font-family: Arial, sans-serif;
                    margin: 0;
                    padding: 0;
                    background-color: #f4f4f4;
                }

                .invoice-container {
                    width: 80%;
                    margin: 20px auto;
                    padding: 100px;
                    background-color: #fff;
                    border: 1px solid #ccc;
                    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
                }

                header {
                    text-align: center;
                    margin-bottom: 20px;
                }

                header h1 {
                    margin: 0;
                    font-size: 30px;
                    font-weight: bold;
                    color: rgb(0, 0, 187);
                }

                header p {
                    margin: 5px 0;
                    font-size: 12px;
                }

                .title {
                    text-align: center;
                    margin-bottom: 20px;
                }

                .title h2 {
                    margin: 0;
                    font-size: 20px;
                    text-decoration: underline;
                }

                .requisition-info {
                    display: flex;
                    justify-content: space-between;
                    margin-bottom: 20px;
                }

                .requisition-info .left,
                .requisition-info .right {
                    width: 70%;
                    /* Adjusted width */
                }

                .requisition-info table {
                    width: 100%;
                    border-collapse: collapse;
                    border: none;
                    /* Removed border color */
                }

                .requisition-info th,
                .requisition-info td {
                    padding: 5px;
                    text-align: left;
                    font-size: 14px;
                }

                .invoice-details {
                    margin-bottom: 20px;
                }

                .invoice-details table {
                    width: 100%;
                    border-collapse: collapse;
                    margin-bottom: 10px;
                }

                .invoice-details table,
                .invoice-details th,
                .invoice-details td {
                    border: 1px solid #000;
                }

                .invoice-details th,
                .invoice-details td {
                    padding: 8px;
                    text-align: left;
                    font-size: 14px;
                }

                .invoice-details p {
                    margin: 5px 0;
                    font-size: 14px;
                }

                .invoice-details .totals {
                    text-align: right;
                }

                .invoice-details .totals p {
                    margin: 5px 0;
                    font-size: 14px;
                }

                /* footer {
                            display: flex;
                            justify-content: space-between;
                            margin-top: 20px;
                        }

                        footer p {
                            margin: 10px 0;
                            font-size: 14px;
                            width: 45%;
                            text-align: center;
                        } */
            </style>
            <div class="row">
                <div class="col-md-12 m-2">
                    <h4 class="text-capitalize breadcrumb-title">{{ trans('Requisition Receive Detail') }}</h4>
                </div>
                <div class="col-md-12">
                    <div class="card mb-4">
                        <div class="card-body">

                            <header>
                                <h1>Global Medical Engineering (BD) Ltd.</h1>
                                <p>Provider of Medical Equipment & Solutions for Hospitals, Clinics And HealthCare
                                    Institutes.</p>
                                <p>Address : 17/2 (1st & 2nd Floor), Topkhana Road, Dhaka-1000</p>
                                <p>Hotline : +88 09678 020555 Mobile : +8801404003500</p>
                                <p>e-mail : <a href="mailto:info@gmebd.com">info@gmebd.com</a> web: <a
                                        href="http://www.gmebd.com">www.gmebd.com</a></p>
                            </header>

                            <section class="title">
                                <h2>Purchase Requisition Received</h2>
                            </section>

                            <section class="requisition-info">
                                <div class="left">
                                    <table>
                                        <tr>
                                            <th>Requisition No</th>
                                            <td>:</td>
                                            <th>{{ $receive->requisition->requisition_no }}</th>
                                        </tr>
                                        <tr>
                                            <th>Supplier Name</th>
                                            <td>:</td>
                                            <th>{{ @$receive->requisition->supplier->company_name }}</th>
                                        </tr>
                                        <tr>
                                            <th>Invoice To</th>
                                            <td>:</td>
                                            <td>{{ $receive->requisition->warehouse->name }}</td>
                                        </tr>

                                    </table>
                                </div>
                                <div class="right">
                                    <table>
                                        <tr>
                                            <th>Description</th>
                                            <td>:</td>
                                            <th>{{ $receive->requisition->description }}</th>
                                        </tr>
                                        <tr>
                                            <th> Date</th>
                                            <td>:</td>
                                            <th>{{ date('d F, Y', strtotime($receive->created_at)) }}</th>
                                        </tr>
                                        <tr>
                                            <th>Created By</th>
                                            <td>:</td>
                                            <td>{{ $receive->requisition->createdBy->name }}</td>
                                        </tr>
                                    </table>
                                </div>
                            </section>

                            <section class="invoice-details">
                                <table>
                                    <thead>
                                        <tr>
                                            <th>SN</th>
                                            <th>Product Name</th>
                                            <th>Approved Quantity</th>
                                            <th>Received Quantity</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            @foreach ($receive->receiveDetails as $key => $item)
                                        <tr>
                                            <td>{{ $key + 1 }}</td>
                                            <td>
                                                {{ $item->product->name }} {{ $item->product->tag->name }} <br>
                                                <b>Brand:</b>
                                                {{ $item->product->brand->name }}
                                            </td>
                                            <td>
                                                {{ $item->approved_quantity }}
                                            </td>
                                            <td>
                                                {{ $item->received_quantity }}
                                            </td>
                                            <td>
                                                @if ($item->product->is_expire_date == 'yes')
                                                    <button class="btn btn-xs btn-primary me-1" type="button"
                                                        data-bs-toggle="modal"
                                                        data-product_name="{{ $item->product->name }} {{ $item->product->tag->name }} Brand: {{ $item->product->brand->name }}"
                                                        data-product_id="{{ $item->product_id }}"
                                                        data-requisition_id="{{ $receive->requisition->id }}"
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
                                                        data-requisition_id="{{ $receive->requisition->id }}"
                                                        data-bs-target="#createModalSerial">
                                                        <i class="fa fa-plus"></i>
                                                    </button>
                                                @endif
                                            </td>
                                        </tr>
                                        @endforeach
                                        </tr>
                                    </tbody>
                                </table>
                                <!-- <p><strong>IN WORD : Taka Twenty Eight Lac Only</strong></p>
                                                    <table>
                                                        <div class="totals">
                                                            <p>Total : <strong>2,800,000.00</strong></p>
                                                            <p>Discount : <strong>0.00</strong></p>
                                                            <p><strong>Grand Total : 2,800,000.00</strong></p>
                                                        </div>
                                                    </table> -->



                            </section>

                            <footer>
                                <p>Received : {{ $receive->aceptedBy->name }} </p>
                                <p>Authorized ___________________________</p>
                            </footer>

                        </div>
                    </div>
                </div>
                <div class="modal fade inputForm-modal" id="createModal" tabindex="-1" role="dialog"
                    aria-labelledby="createModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-xl" role="document">
                        <div class="modal-content">
                            <div class="modal-header" id="createModalLabel">
                                <h5 class="modal-title">{{ trans('Batch Entry') }}</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-hidden="true"></button>
                            </div>
                            <div class="modal-body">
                                <div class="row mb-4">
                                    <div class="mt-4">
                                        <div class="form-group">
                                            <label for="product_name">Product Name:</label><span id="product_name"></span>
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
                                                <th>Lot No</th>
                                                <th>Expired Date</th>
                                                <th>Quantity</th>
                                            </tr>
                                        </thead>
                                        <tbody id="batch_table_body">
                                            <!-- Batch data will be loaded here via AJAX -->
                                        </tbody>
                                    </table>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-light-danger mt-2 mb-2 btn-no-effect"
                                        data-bs-dismiss="modal">Close</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="modal fade inputForm-modal" id="createModalSerial" tabindex="-1" role="dialog"
                    aria-labelledby="createModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-xl" role="document">
                        <div class="modal-content">
                            <div class="modal-header" id="createModalLabel">
                                <h5 class="modal-title">{{ trans('Serial Entry') }}</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-hidden="true"></button>
                            </div>
                            <div class="modal-body">
                                <div class="row mb-4">
                                    <div class="mt-4">
                                        <div class="form-group">
                                            <label for="product_name">Product Name:</label><span id="product_name"></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="row mb-4">
                                    <table class="table table-borderless">
                                        <thead>
                                            <tr>
                                                <th>Sl</th>
                                                <th>Serial No</th>
                                                <th>Dongle ID</th>
                                                <th>Manufacture Date</th>
                                                <th>Image/File</th>
                                                <th>Quantity</th>
                                            </tr>
                                        </thead>
                                        <tbody id="serial_table_body">
                                            <!-- Serial data will be loaded here via AJAX -->
                                        </tbody>
                                    </table>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-light-danger mt-2 mb-2 btn-no-effect"
                                        data-bs-dismiss="modal">Close</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>


            </div>
        @endsection

        @section('page_scripts')
            <script>
                $(document).ready(function() {
                    $('#createModal').on('show.bs.modal', function(event) {
                        var button = $(event.relatedTarget);
                        var productName = button.data('product_name');
                        var productId = button.data('product_id');
                        var requisitionId = button.data('requisition_id');
                        var modal = $(this);

                        console.log(productName, productId, requisitionId);

                        modal.find('#product_name').text('');
                        modal.find('#batch_table_body').empty();

                        modal.find('#product_name').text(productName);

                        var url = '{{ route('purchase.requisitions.batches', ['requisition_id' => ':id']) }}';
                        url = url.replace(':id', requisitionId);

                        $.ajax({
                            url: url,
                            type: 'GET',
                            data: {
                                product_id: productId
                            },
                            success: function(response) {
                                console.log(response);

                                if (response.batches) {
                                    var rows = '';
                                    response.batches.forEach(function(batch, index) {
                                        rows += `
                            <tr>
                                <td>${index + 1}</td>
                                <td>${batch.batch_no}</td>
                                <td>${batch.manufacture_no}</td>
                                <td>${batch.lot_no}</td>
                                <td>${new Date(batch.expired_date).toLocaleDateString()}</td>
                                <td>${batch.quantity}</td>
                            </tr>`;
                                    });
                                    modal.find('#batch_table_body').html(rows);
                                }
                            },
                            error: function(xhr) {
                                console.error(xhr.responseText);
                                showToast('error',
                                    'An error occurred while loading the product data. Please try again later.'
                                );
                            }
                        });
                    });

                });
                $('#createModalSerial').on('show.bs.modal', function(event) {
                    var button = $(event.relatedTarget);
                    var productName = button.data('product_name');
                    var productId = button.data('product_id');
                    var requisitionId = button.data('requisition_id');
                    var modal = $(this);

                    // Clear previous data
                    modal.find('#product_name').text('');
                    modal.find('#serial_table_body').empty();

                    // Set new data
                    modal.find('#product_name').text(productName);

                    // AJAX request to load serial data
                    var url = '{{ route('purchase.requisitions.serials', ['requisition_id' => ':id']) }}';
                    url = url.replace(':id', requisitionId);

                    $.ajax({
                        url: url,
                        type: 'GET',
                        data: {
                            product_id: productId
                        },
                        success: function(response) {
                            if (response.serials) {
                                // Populate serial_table_body with the response data
                                var rows = '';
                                response.serials.forEach(function(serial, index) {
                                    rows += `
                            <tr>
                                <td>${index + 1}</td>
                                <td>${serial.serial_no}</td>
                                <td>${serial.dongle_no}</td>
                                <td>${new Date(serial.manufacture_date).toLocaleDateString()}</td>
                                <td>${serial.image ? `<a href="${serial.image}" target="_blank"><img src="${serial.image}" style="max-width: 50px; max-height: 50px;"></a>` : ''}</td>
                                <td>${serial.quantity}</td>
                            </tr>`;
                                });
                                modal.find('#serial_table_body').html(rows);
                            }
                        },
                        error: function(xhr) {
                            console.error(xhr.responseText);
                            showToast('error',
                                'An error occurred while loading the serial data. Please try again later.');
                        }
                    });
                });
            </script>
            <script type="text/javascript">
                const row = $("#product_info_table tbody tr:first-child").clone();
                row.find('input').val('');
                row.find('tom-select option:selected').removeAttr('selected');
                row.find('#remove_row').removeClass('disabled');
                row.find('#remove_row').removeAttr('disabled');

                $("#add_row").click(function() {
                    const newRow = row.clone();
                    newRow.find('.tom-select').each(function() {
                        new TomSelect(this, {})
                    });
                    $("#product_info_table tbody").append(newRow);

                });
                $("#product_info_table tbody").on("keyup", "#quantity", function() {
                    calculateTotalPrice($(this).closest('tr'));
                });

                function removeRow(row) {
                    $(row).closest('tr').remove();
                    calculateTotalAmount();
                    calculateNetAmount();

                }


                function calculateTotalPrice(row) {
                    var qty = $(row).find("#quantity").val() ? $(row).find("#quantity").val() : 0;
                    var price = $(row).find("#price").val() ? $(row).find("#price").val() : 0;

                    var total = parseFloat(qty) * parseFloat(price);
                    $(row).find("#amount").val(total);
                    console.log(total);
                }

                // Initial calculation for existing rows
                $("#product_info_table tbody tr").each(function() {
                    calculateTotalPrice($(this));
                    calculateTotalAmount();
                    calculateNetAmount();

                });
            </script>

            <script type="text/javascript">
                function calculateTotalAmount() {
                    var totalAmount = 0;
                    $("#product_info_table tbody tr").each(function() {
                        var amount = parseFloat($(this).find("#amount").val()) || 0;
                        totalAmount += amount;
                    });
                    $("#total_amount").val(totalAmount);
                }

                $(document).ready(function() {
                    calculateTotalAmount();

                    $("#product_info_table tbody").on("keyup", "#quantity", function() {
                        calculateTotalPrice($(this).closest('tr'));
                        calculateTotalAmount();
                        calculateNetAmount();
                    });
                });
            </script>
            <script type="text/javascript">
                function calculateNetAmount() {
                    var totalAmount = parseFloat($("#total_amount").val()) || 0;
                    var discount = parseFloat($("#discount").val()) || 0;
                    var netAmount = totalAmount - discount;
                    $("#net_amount").val(netAmount);
                }
                $(document).ready(function() {
                    calculateNetAmount();

                    $("#discount").on("keyup", function() {
                        calculateNetAmount();
                    });
                    $("#product_info_table tbody").on("keyup", "#quantity", function() {
                        calculateTotalPrice($(this).closest('tr'));
                        calculateTotalAmount();
                        calculateNetAmount();
                    });
                });
            </script>




        @endsection
