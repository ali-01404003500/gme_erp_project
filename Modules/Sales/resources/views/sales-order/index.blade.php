@section('title', 'Sales Order List')
@section('description', 'Sales Order List')
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
                                    <li class="breadcrumb-item active" aria-current="page">{{ trans('Sales Order list') }}
                                    </li>
                                </ol>
                            </nav>
                        </div>
                        <div class="action-btn mt-sm-0 mt-15 d-flex align-items-center">
                            @if (hasPermission('sales.sales-orders.create'))
                                <a href="{{ route('sales.sales-orders.create', app()->getLocale()) }}"
                                    class="btn px-20 btn-primary btn-sm">
                                    <i class="las la-plus fs-16"></i>Add New
                                </a>
                            @endif
                            <a href="{{ request()->fullUrlWithQuery(['export' => 'pdf']) }}" target="_blank"
                                class="btn btn-danger btn-sm mr-5" style="margin-left: 5px;">
                                <i class="las la-file-pdf fs-16"></i> PDF
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12" style="padding-bottom: 20px">
                    <h4 class="text-capitalize breadcrumb-title">{{ trans('Sales Order list') }}</h4>
                </div>
                <div class="col-md-12">
                    <div class="col-md-12 my-4">
                        <div class="card">
                            <div class="card-body">
                                <form>
                                    <div class="col-sm-12">
                                        <table class="table table-bordered">
                                            <tr>

                                                <td class="text-center">
                                                    <select name="customer_id" id="customer_id" class="  input-sm"
                                                        data-placeholder="Select Customer">
                                                        <option value=""></option> 
                                                    </select>
                                                </td>

                                                <td class="text-center">
                                                    <input type="text" class="form-control"
                                                        placeholder="Search Phone Number" name="additional_phone"
                                                        value="{{ request('additional_phone') }}">
                                                </td>
                                                <td>
                                                    <select name="sales_type" id="sales_type" class="tom-select  form-control" data-placeholder="Select Sales Type">
                                                        <option value=""></option>
                                                        <option value="general_sales" @if (request('sales_type') == 'general_sales') selected @endif>
                                                            General Sales</option>
                                                        <option value="partial_sales" @if (request('sales_type') == 'partial_sales') selected @endif>
                                                            Partial Sales</option>
                                                        <option value="free_sales" @if (request('sales_type') == 'free_sales') selected @endif>
                                                            Free Sales</option>
                                                        
                                                    </select>

                                                </td>

                                                <td>
                                                    <select name="status" id="status" class="tom-select form-control" data-placeholder="Select Status">
                                                        <option value="">All Statuses</option>
                                                        <option value="pending" @if (request('status') == 'pending') selected @endif>
                                                            Pending</option>
                                                        <option value="approved" @if (request('status') == 'approved') selected @endif>
                                                            Approved</option>
                                                        <option value="delivered" @if (request('status') == 'delivered') selected @endif>
                                                            Delivered</option>
                                                        <option value="partial" @if (request('status') == 'partial') selected @endif>
                                                            Partial</option>
                                                    </select>
                                                </td>

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

                                                <td colspan="5" class="text-right">
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
                            <table id="zero-config"class="table dt-table-hover" data-page='@include('utils.table_paginate', ['data' => $salesOrders])'
                                style="width:100%">
                                <thead>
                                    <tr>
                                        <th>SL</th>
                                        <th>
                                            Sales Order ID
                                        </th>
                                        <th>
                                            Invoice Date
                                        </th>
                                        <th>Sales Type</th>
                                        <th>
                                            Customer Name
                                        </th>
                                        <th>
                                            Amount
                                        </th>
                                        <th>
                                            Payment Status
                                        </th>
                                        <th>
                                            Status
                                        </th>
                                        <th>
                                            Prepared By
                                        </th>
                                        <th>
                                            Image/Documents
                                        </th>
                                        <th class="no-content">
                                            Actions
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    {{-- @dd($salesOrders) --}}
                                    @foreach ($salesOrders as $salesOrder)
                                        {{-- @dd($salesOrder->delivery) --}}
                                        <tr>
                                            <td>{{ ($salesOrders->currentPage() - 1) * $salesOrders->perPage() + $loop->iteration  }}</td>

                                            <td>
                                                {{ $salesOrder->sales_order_id }}
                                            </td>
                                            <td>
                                                {{ $salesOrder->invoice_date }}
                                            </td>
                                            <td>
                                                @if ($salesOrder->sales_type == 'free_sales')
                                                    Free Sales
                                                @else
                                                    {{ $salesOrder->sales_type == 'partial_sales' ? 'Partial Sales' : 'General Sales' }}
                                                @endif
                                            </td>
                                            <td>
                                                <a class="text-dark fw-500"
                                                    href="{{ route('sales.sales-orders.show', $salesOrder->id) }}">
                                                    {{ $salesOrder->customer->company_name }}</i>
                                                </a>
                                            </td>
                                            <td>
                                                {{ number_format($salesOrder->net_amount) }}
                                            </td>
                                            <td>
                                                @if ($salesOrder->paid_status == 'paid')
                                                    <span
                                                        class="badge badge-round badge-success text-capitalize">{{ $salesOrder->paid_status }}</span>
                                                @elseif($salesOrder->paid_status == 'due')
                                                    <span
                                                        class="badge badge-round badge-warning text-capitalize">{{ $salesOrder->paid_status }}</span>
                                                @elseif($salesOrder->paid_status == 'condition')
                                                    <span
                                                        class="badge badge-round badge-info text-capitalize">{{ $salesOrder->paid_status }}</span>
                                                @else
                                                    <span class="badge badge-round badge-danger text-capitalize">Unpaid</span>
                                                @endif
                                            </td>
                                            <td>
                                                {{-- {{ number_format($salesOrder->saleOrderDeliveries->pluck('salesOrderDeliveryDetails')->flatten()->sum('quantity')) }} --}}
                                                @if ($salesOrder->status == 'pending')
                                                    <span
                                                        class="badge badge-round badge-warning text-capitalize">{{ $salesOrder->status }}</span>
                                                @elseif($salesOrder->status == 'approved')
                                                    <span
                                                        class="badge badge-round badge-success text-capitalize">Undeliver</span>
                                                @elseif($salesOrder->status == 'delivered')
                                                    <span
                                                        class="badge badge-round badge-info text-capitalize">{{ $salesOrder->status }}</span>
                                                @elseif($salesOrder->status == 'partial')
                                                    <span
                                                        class="badge badge-round badge-warning text-capitalize">{{ $salesOrder->status }}</span>
                                                @endif
                                            </td>
                                            <td>
                                                {{ $salesOrder->createdBy->name }}
                                            </td>
                                            <td>
                                                @foreach( $salesOrder->payments as $payment)
                                                    @if($payment->attachments)
                                                        <a href="{{ asset($payment->attachments) }}" target="_blank" class="btn btn-sm btn-outline-info"><i class="fa fa-eye"></i></a>
                                                    @endif
                                                @endforeach
                                            </td>
                                            <td>
                                                <div class="btn-group btn-group-sm" role="group"
                                                    aria-label="Small button group">

                                                    @if (hasPermission('sales.sales-orders.approve') && $salesOrder->status == 'pending')
                                                        <a class="btn btn-outline-success"
                                                            href="{{ route('sales.sales-orders.edit', $salesOrder->id) }}?approve=1"><i
                                                                class="fas fa-check"></i>
                                                        </a>
                                                    @endif

                                                    @if (hasPermission('sales.sales-orders.update') &&
                                                            ($salesOrder->status == 'pending' || $salesOrder->status == 'approved'))
                                                        <a class="btn btn-outline-warning"
                                                            href="{{ route('sales.sales-orders.edit', $salesOrder->id) }}"><i
                                                                class="far fa-edit"></i>
                                                        </a>
                                                    @endif

                                                    @if (hasPermission('sales.sales-orders.destroy') && $salesOrder->status == 'pending')
                                                        <button type="button"
                                                            data-action="{{ route('sales.sales-orders.destroy', $salesOrder->id) }}"
                                                            class="btn btn-outline-danger delete-confirm"><i
                                                                class="far fa-trash-alt"></i>
                                                        </button>
                                                    @endif

                                                    @if (hasPermission('sales.sales-orders.show'))
                                                        <a class="btn btn-outline-primary"
                                                            href="{{ route('sales.sales-orders.show', $salesOrder->id) }}"><i
                                                                class="fas fa-eye"></i>
                                                        </a>
                                                    @endif

                                                    @if (hasPermission('sales.deliveries.create') && $salesOrder->status == 'approved' || $salesOrder->status == 'partial')
                                                        <a class="btn btn-outline-info" title="Make Delivery"
                                                            href="{{ route('sales.deliveries.create', ['delivery_id' => optional($salesOrder->delivery)->id]) }}"><i
                                                                class="fas fa-truck"></i>
                                                        </a>
                                                    @endif


                                                    @if (hasPermission('sales.sales-orders.product-free-sales-invoice') && $salesOrder->offers->where('offer_type', 'clearance')->count() > 0&& ($salesOrder->status == 'approved' || $salesOrder->status == 'partial' ))
                                                        <a class="btn btn-outline-info" title="Free Sales Invoice"
                                                            href="{{ route('sales.sales-orders.product-free-sales-invoice', $salesOrder->id) }}"><i
                                                                class="fas fa-gift"></i>
                                                        </a>
                                                    @endif


                                                    @if (hasPermission('sales.sales-orders.splits'))
                                                        <button type="button" class="btn btn-sm btn-outline-primary"
                                                                onclick="openSplitModal({{ $salesOrder->id }})">
                                                        <i class="fas fa-random"></i>
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
 
        
    {{-- Split Modal --}}
    <div class="modal fade" id="splitModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Sales Split - Employee wise Ratio</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div id="splitAlert" class="alert alert-danger d-none"></div>

                    <div id="splitRows">
                        {{-- JS দিয়ে row add হবে এখানে --}}
                    </div>

                    <button type="button" class="btn btn-sm btn-outline-secondary mt-2" onclick="addSplitRow()">
                        + Add Employee
                    </button>

                    <hr>
                    <div class="d-flex justify-content-between">
                        <strong>Total:</strong>
                        <strong id="splitTotal">0</strong>%
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-danger me-auto"  onclick="confirmRemoveSplit()">
                        Remove Split
                    </button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary"  onclick="confirmSaveSplit()">Save</button>
                </div>
            </div>
        </div>
    </div>
    <style>
        .swal2-container {
            z-index: 99999 !important;
        }
    </style>
@endsection
@section('page_scripts')
    <script>
        $(".datePicker").datepicker({
            format: 'dd-mm-yyyy',
            autoclose: true
        });


        function confirmRemoveSplit() {
            Swal.fire({
                title: 'Remove Split?',
                text: 'Split will be removed and the order will revert to a single employee.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes, Remove',
                cancelButtonText: 'Cancel',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    removeSplit();
                }
            });
        }

        function confirmSaveSplit() {
            Swal.fire({
                title: 'Save Split?',
                text: 'Are you sure you want to save this employee-wise split?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Yes, Save',
                cancelButtonText: 'Cancel',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    saveSplit();
                }
            });
        }

        $(document).ready(function () {
            const companySelect = new TomSelect("#customer_id", {
                valueField: "id",
                labelField: "text",
                searchField: [], 
                load: function(query, callback) {

                    if (!query.length || query.length < 2) return callback();

                    $.ajax({
                        url: "{{ route('sales.sales-orders-autocomplete.customers') }}",
                        type: "GET",
                        data: { search: query },
                        success: function(res) {
                            companySelect.clearOptions();
                            callback(res.map(item => ({ id: item.id, text: item.label })));
                        },
                        error: function() {
                            callback();
                        }
                    });
                }
            }); 

            @if(request('customer_id'))
                companySelect.addOption({
                    id: "{{ request('customer_id') }}",
                    text: "{{ request('customer_id') }}"
                });
                companySelect.setValue("{{ request('customer_id') }}");
            @endif
        }); 
    </script>
    <script> 
        let currentOrderId = null;
        const employeeList = @json(\Modules\Hrms\Models\Employee::select('id', 'full_name')->where('status', 1)->get());
        let rowCounter = 0; // প্রতিটা row-কে ইউনিক id দেওয়ার জন্য

        function employeeOptionsHtml(selectedId = null) {
            return employeeList.map(e =>
                `<option value="${e.id}" ${e.id == selectedId ? 'selected' : ''}>${e.full_name}</option>`
            ).join('');
        }

        function addSplitRow(employeeId = '', percentage = '') {
            rowCounter++;
            const rowId = `split-employee-${rowCounter}`;

            const rowHtml = `
                <div class="row g-2 mb-2 split-row">
                    <div class="col-7">
                        <select class="form-control split-employee" id="${rowId}">
                            <option value="">-- Select Employee --</option>
                            ${employeeOptionsHtml(employeeId)}
                        </select>
                    </div>
                    <div class="col-3">
                        <input type="number" step="0.01" class="form-control split-percentage" value="${percentage}" placeholder="%">
                    </div>
                    <div class="col-2">
                        <button type="button" class="btn btn-outline-danger btn-sm w-100 remove-split-row">✕</button>
                    </div>
                </div>
            `;

            const $row = $(rowHtml);
            $('#splitRows').append($row);

            // TomSelect init করা হচ্ছে
            const tomSelectInstance = new TomSelect(`#${rowId}`, {
                placeholder: 'Employee Search',
                maxItems: 1,
                allowEmptyOption: true,
            });

            $row.find('.split-percentage').on('input', updateTotal);

            $row.find('.remove-split-row').on('click', function () {
                tomSelectInstance.destroy(); // TomSelect instance destroy করে দিতে হবে
                $(this).closest('.split-row').remove();
                updateTotal();
            });

            updateTotal();
        }

        function updateTotal() {
            let total = 0;
            $('.split-percentage').each(function () {
                total += parseFloat($(this).val()) || 0;
            });
            $('#splitTotal').text(total.toFixed(2));
            $('#splitTotal').attr('class', total == 100 ? 'text-success' : 'text-danger');
        }

        function openSplitModal(orderId) {
            currentOrderId = orderId;

            // পুরনো সব TomSelect instance destroy করে দিন
            $('#splitRows .split-employee').each(function () {
                if (this.tomselect) {
                    this.tomselect.destroy();
                }
            });

            $('#splitRows').empty();
            $('#splitAlert').addClass('d-none');
            rowCounter = 0;

            $.ajax({
                url: `{{ url('sales/sales-orders') }}/${orderId}/splits`,
                method: 'GET',
                success: function (data) {
                    if (data.splits && data.splits.length > 0) {
                        data.splits.forEach(s => addSplitRow(s.employee_id, s.percentage));
                    } else {
                        addSplitRow();
                        addSplitRow();
                    }
                    $('#splitModal').modal('show');
                },
                error: function (xhr) {
                    console.log('Status:', xhr.status);
                    console.log('Response:', xhr.responseText);
                }
            });
        }
        function saveSplit() {
            const splits = [];
            $('.split-row').each(function () {
                const employeeId = $(this).find('.split-employee').val();
                const percentage = $(this).find('.split-percentage').val();
                if (employeeId && percentage) {
                    splits.push({ employee_id: employeeId, percentage: percentage });
                }
            });

            $.ajax({
                url: `{{ url('sales/sales-orders') }}/${currentOrderId}/splits`,
                method: 'POST',
                contentType: 'application/json',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                },
                data: JSON.stringify({ splits: splits }),
                success: function () {
                    $('#splitModal').modal('hide');
                    location.reload();
                },
                error: function (xhr) {
                    const message = xhr.responseJSON?.message || 'কোনো একটা সমস্যা হয়েছে।';
                    $('#splitAlert').text(message).removeClass('d-none');
                }
            });
        }

        function removeSplit() {
            if (!confirm('Split মুছে দিলে এই order আবার single employee (user_ref_id) এর হিসেবে ফিরে যাবে। নিশ্চিত?')) return;

            $.ajax({
                url: `{{ url('sales/sales-orders') }}/${currentOrderId}/splits`,
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                },
                success: function () {
                    $('#splitModal').modal('hide');
                    location.reload();
                },
                error: function (xhr) {
                    console.error('Split delete failed', xhr);
                }
            });
        }

        // বাটন ক্লিক event delegation দিয়ে (dynamically loaded row হলেও কাজ করবে)
        $(document).on('click', '.open-split-modal-btn', function () {
            const orderId = $(this).data('order-id');
            openSplitModal(orderId);
        });
    </script>
@endSection