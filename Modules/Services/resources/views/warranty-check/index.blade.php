@extends('layout.app')
@section('title', 'Warranty Check')
@section('description', 'Warranty Check Report')

@section('content')
<div class="container-fluid">
    <div class="row">
                <div class="col-lg-12">
                    <div class="breadcrumb-main">
                        <div class="breadcrumb-action justify-content-center flex-wrap">
                            <nav aria-label="breadcrumb">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="#"><i class="las la-home"></i>Home</a></li>
                                    <li class="breadcrumb-item active" aria-current="page">Warranty Check Report</li>
                                </ol>
                            </nav>
                        </div>
                        
                    </div>
                </div>
            </div>
             <div class="col-md-12">
                    <h4 class="text-capitalize breadcrumb-title">
                        Warranty Date Check Report
                     
                    </h4>
                </div>
             
    <div class="card mt-3">
        <div class="card-header">Search Warranty</div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <label>Customer *</label>
                    <select id="customer_select" class="form-control" placeholder="Select Customer">
                        <option value=""></option> 
                    </select>

                    <label class="mt-3">Serial *</label>
                    <select id="serial_select" class="form-control" disabled placeholder="Select Serial Number">
                        <option>Select customer first</option>
                    </select>
                </div>

                <div class="col-md-6">
                    <label>Serial Direct *</label>
                    <select id="serial_direct_select" class="form-control" placeholder="Select Serial Number">
                        <option value=""></option>
                        @foreach ($serialNumbers as $s)
                            <option value="{{ $s->dongle_id }}">{{ $s->product->model ?? '' }} - {{ $s->dongle_id }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>
    </div>

    <div id="warranty_info_section" class="mt-3" style="display:none;">
        <div class="card">
            <div class="card-header">Warranty Info</div>
            <div class="card-body" id="warranty_info_content"></div>
        </div>
    </div>
</div>
@endsection

@section('page_scripts')
<script>
$(document).ready(function() {

    let customerTomSelect = new TomSelect('#customer_select');
    let serialTomSelect = new TomSelect('#serial_select');
    let serialDirectTomSelect = new TomSelect('#serial_direct_select');

    // ============= Load Customer Based Serials ============
    customerTomSelect.on('change', function(customerId) {

        if (!customerId) {
            serialTomSelect.clearOptions();
            serialTomSelect.disable();
            return;
        }

        $.ajax({
            url: '{{ route("services.reports.warranty-check.customer-serials") }}',
            data: { customer_id: customerId },
            success: function(res) {
                serialTomSelect.clear();
                serialTomSelect.clearOptions();
                serialTomSelect.enable();

                serialTomSelect.addOption({value:'', text:'Select Serial Number'});

                res.forEach(function(s){
                    serialTomSelect.addOption({value:s.dongle_id, text:s.text});
                });

                serialTomSelect.refreshOptions(false);
            }
        });
    });

    // ============= Customer + Serial search =============
    serialTomSelect.on('change', function(serial) {
        if (!serial || !customerTomSelect.getValue()) return;

        loadWarrantyCustomerMixed(customerTomSelect.getValue(), serial);
    });

    // ============= Direct Serial Search =============
    serialDirectTomSelect.on('change', function(serial) {
        if (!serial) return;

        customerTomSelect.clear();
        serialTomSelect.clear();
        serialTomSelect.disable();

        loadWarrantyBySerial(serial);
    });

    // =========================================================
    // AJAX LOAD WARRANTY: CUSTOMER + SERIAL (MULTIPLE POSSIBLE)
    // =========================================================
    function loadWarrantyCustomerMixed(customerId, serialNumber) {
        showLoading();

        $.ajax({
            url: '{{ route("services.reports.warranty-check.by-customer") }}',
            data: { customer_id: customerId, serial_number: serialNumber },
            success: function(response) {
                if (Array.isArray(response)) {
                    displayMultipleWarrantyInfo(response);
                } else {
                    displaySingleWarrantyInfo(response);
                }
            }
        });
    }

    // =========================================================
    // AJAX LOAD WARRANTY BY SERIAL ONLY
    // =========================================================
    function loadWarrantyBySerial(serial) {
        showLoading();
        $.ajax({
            url: '{{ route("services.reports.warranty-check.by-serial") }}',
            data: { serial_number: serial },
            success: function(r) {
                if (Array.isArray(r)) {
                    displayMultipleWarrantyInfo(r);
                } else {
                    displaySingleWarrantyInfo(r);
                }
            }
        });
    }

    // =========================================================
    // SINGLE RESULT RENDER
    // =========================================================
    function displaySingleWarrantyInfo(d) {
        let html = `
            <div class="card mb-3">
                <div class="card-header">
                    Warranty Record
                </div>
                <div class="card-body">
                    ${buildWarrantyTable(d)}
                </div>
            </div>
        `;
        $('#warranty_info_content').html(html);
        $('#warranty_info_section').show();
    }

    // =========================================================
    // MULTIPLE WARRANTY RECORDS
    // =========================================================
    function displayMultipleWarrantyInfo(list) {
        let html = `
            <div class="alert alert-info">
                Multiple warranty records found for this serial.
            </div>
        `;

        list.forEach((d, i) => {
            html += `
                <div class="card mb-3">
                    <div class="card-header">
                        Record ${i+1}
                        ${d.is_latest ? '<span class="badge badge-round bg-success">Latest</span>' : '<span class="badge badge-round bg-secondary">Previous</span>'}
                    </div>
                    <div class="card-body">
                        ${buildWarrantyTable(d)}
                    </div>
                </div>
            `;
        });

        $('#warranty_info_content').html(html);
        $('#warranty_info_section').show();
    }

    function buildWarrantyTable(d) {
        return `
            <div class="row">
                <div class="col-md-6">
                    <table class="table table-bordered table-sm">
                        <tr><th>Customer</th><td>${d.customer_name}</td></tr>
                        <tr><th>Serial</th><td>${d.serial_no}</td></tr>
                        <tr><th>Product</th><td>${d.product_name}</td></tr>
                    </table>
                </div>
                <div class="col-md-6">
                    <table class="table table-bordered table-sm">
                        <tr><th>Invoice Date</th><td>${d.invoice_date_formatted}</td></tr>
                        <tr><th>Warranty Period</th><td>${d.warranty_period}</td></tr>
                        <tr><th>Expiry Date</th><td>${d.warranty_expiry_formatted}</td></tr>
                        <tr><th>Remaining Warranty Period</th><td>${d.remaining_period}</td></tr>
                    </table>
                </div>
            </div>
        `;
    }

    function showLoading() {
        $('#warranty_info_content').html(`
            <div class="text-center py-5">
                <i class="las la-spinner la-spin" style="font-size: 40px;"></i>
                <p>Loading...</p>
            </div>
        `);
        $('#warranty_info_section').show();
    }

});

        $(document).ready(function() {
            const companySelect = new TomSelect("#customer_select", {
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
                            callback(res.map(item => ({ id: item.id, text: item.label, phone: item.phone, address: item.address    })));
                        },
                        error: function() {
                            callback();
                        }
                    });
                }
            }); 

            @if(isset($customer))
                companySelect.addOption({
                    id: "{{ $customer->id }}",
                    text: "{{ $customer->company_name }}"
                });
                companySelect.setValue("{{ $customer->id }}");
            @endif
        });
</script>
@endsection
