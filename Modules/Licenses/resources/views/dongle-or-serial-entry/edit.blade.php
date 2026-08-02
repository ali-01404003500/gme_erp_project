@section('title', 'Dongle Update')
@section('description', 'Dongle Update')
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
                                        {{ trans('menu.update-dongle-or-serial-menu-title') }}</li>
                                </ol>
                            </nav>
                        </div>
                        <div class="action-btn mt-sm-0 mt-15 row">
                            <a href="{{ route('licenses.dongle-or-serial-entries.index') }}"
                                class="btn btn-warning btn-default btn-squared radius-md shadow2 btn-sm"><i
                                    class="fa fa-list"></i> List</a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12 m-2">
                    <h4 class="text-capitalize breadcrumb-title">
                        {{ trans('menu.create-dongle-or-serial-menu-title') }}
                    </h4>
                    <x-error-alart />
                </div>
                <div class="col-md-12">
                    <div class="card mb-4">
                        <div class="card-body">
                            <form action="{{ route('licenses.dongle-or-serial-entries.update', $dongleOrSerialEntry->id) }}" method="POST"
                                enctype="multipart/form-data">
                                @method('PUT')
                                @csrf
                                <div class="row mb-4">
                                    <div class="col-md-6 mt-4">
                                        <div class="form-group">
                                            <label for="customer_id">Customer Name<span class="text-danger">*</span></label>
                                            <select name="customer_id" id="customer_id" class="form-control required" required>
                                                <option value="{{ $dongleOrSerialEntry->customer_id}}" selected >
                                                    {{ $dongleOrSerialEntry->customer->company_name }} -  {{ $dongleOrSerialEntry->customer->area->area }} 
                                                </option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6 mt-4">
                                        <div class="form-group">
                                            <label for="address">Address</label>
                                            <input type="text" name="address" class="form-control" id="address" value="{{ $dongleOrSerialEntry->address }}"
                                                placeholder="Address" readonly>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="product_id">Product Name<span class="text-danger">*</span></label>
                                            <select name="product_id" id="product_id" class="form-control" required>  
                                                <option value="{{ $dongleOrSerialEntry->product_id }}" selected >
                                                    {{ $dongleOrSerialEntry->product->name }} Brand: {{ $dongleOrSerialEntry->product->brand->name }}
                                                </option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="product_type">Product Type</label>
                                            <select name="product_type" id="product_type" class="form-control" >
                                                <option value="USG" {{ old('product_type', $dongleOrSerialEntry->product_type) == 'USG' ? 'selected' : '' }}>USG</option>
                                                <option value="OPG" @if (old('product_type', $dongleOrSerialEntry->product_type) == 'OPG') selected @endif>OPG</option>
                                                <option value="X-Ray" {{ old('product_type', $dongleOrSerialEntry->product_type) == 'X-Ray' ? 'selected' : '' }}>X-Ray</option>
                                                <option value="C-ARM" @if (old('product_type', $dongleOrSerialEntry->product_type) == 'C-ARM') selected @endif>C-ARM</option>
                                                <option value="CBC" {{ old('product_type', $dongleOrSerialEntry->product_type) == 'CBC' ? 'selected' : '' }}>CBC</option>  
                                            </select>
                                        </div>
                                    </div>
                                   
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="dongle_id"> Dongle Id<span class="text-danger">*</span></label>
                                            <input type="text" name="dongle_id" class="form-control" value="{{ $dongleOrSerialEntry->dongle_id }}"
                                                id="dongle_id" placeholder="Dongle Id" required>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="software_version">Software Version</label>
                                            <select name="software_version" id="software_version" class="form-control">
                                                <option value="">-- Select Software Version --</option>

                                                <option value="Old Software Version" @selected(optional($dongleOrSerialEntry)->software_version == 'Old Software Version')>
                                                    Old Software Version
                                                </option>

                                                <option value="New Software Version" @selected(optional($dongleOrSerialEntry)->software_version == 'New Software Version')>
                                                    New Software Version
                                                </option>

                                                <option value="G-55 Power & Smart" @selected(optional($dongleOrSerialEntry)->software_version == 'G-55 Power & Smart')>
                                                    G-55 Power & Smart
                                                </option>

                                                <option value="MAC Id" @selected(optional($dongleOrSerialEntry)->software_version == 'MAC Id')>
                                                    MAC Id
                                                </option>

                                                <option value="Loading" @selected(optional($dongleOrSerialEntry)->software_version == 'Loading')>
                                                    Loading
                                                </option>

                                                <option value="Device Id-12 Digit" @selected(optional($dongleOrSerialEntry)->software_version == 'Device Id-12 Digit')>
                                                    Device Id-12 Digit
                                                </option>

                                                <option value="Device Id-16 Digit" @selected(optional($dongleOrSerialEntry)->software_version == 'Device Id-16 Digit')>
                                                    Device Id-16 Digit
                                                </option>

                                                <option value="Others" @selected(optional($dongleOrSerialEntry)->software_version == 'Others')>
                                                    Others
                                                </option>
                                            </select>
                                            
                                        </div>
                                    </div>

                                    
                                    <div class="col-md-3"> 
                                        <div class="form-group">
                                            <label for="status">Dongle Status</label>
                                            <select name="status" id="status" class="form-control">
                                                <option value="Active" {{ old('status', $dongleOrSerialEntry->status) == 'Active' ? 'selected' : '' }}>Active</option>
                                                <option value="Inactive" {{ old('status', $dongleOrSerialEntry->status) == 'Inactive' ? 'selected' : '' }}>Inactive</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="file_up">File Up</label>
                                              <x-file-uploader :value="$dongleOrSerialEntry->file_upload" name="file_upload"/>

                                            {{-- <input type="file"
                                                class="file-control form-control"
                                                id="file_upload" name="file_upload"> --}}
                                        </div>
                                    </div>


                                   
                                    <div class="col-md-12">
                                        <div
                                            class="button-group d-flex pt-25 justify-content-md-end justify-content-start">
                                            <button type="submit" class="btn btn-primary">Update</button>
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
<script>
    document.addEventListener('DOMContentLoaded', function() {
        var multiplePhoneNos = []; // Array to store multiple phone numbers

        function addMultiplePhone() {
            var phoneNo = document.getElementById('multiple_phone_no').value;
            var bangladeshPhonePattern = /^(?:\+?88|01)?01[3-9]\d{8}$/; 
            if (phoneNo === "") {
                toastr.warning('Please Enter Phone Number');
            } else if (!bangladeshPhonePattern.test(phoneNo)) {
                toastr.warning('Invalid Bangladeshi Phone Number.');
            } else if (multiplePhoneNos.includes(phoneNo)) {
                toastr.warning('This phone number is already added.');
            } else {
                addPhoneRow(phoneNo);
                multiplePhoneNos.push(phoneNo);
                document.getElementById('multiple_phone_no').value = "";
            }
        }

        function addPhoneRow(phoneNo) {
            var table = document.getElementById('product_info_table').getElementsByTagName('tbody')[0];
            var rowCount = table.rows.length;
            var row = table.insertRow(rowCount);
            row.insertCell(0).innerHTML = rowCount + 1;
            row.insertCell(1).innerHTML = '<input type="hidden" name="multiple_phone_nos[]" value="' + phoneNo + '">' + phoneNo;
            row.insertCell(2).innerHTML =
                '<button type="button" class="btn btn-danger btn-xs" onclick="deleteRow(this, \'' + phoneNo +
                '\')"><i class="fa fa-trash"></i></button>';
        }

        function deleteRow(button, phoneNo) {
            var row = button.parentNode.parentNode;
            row.parentNode.removeChild(row);
            var index = multiplePhoneNos.indexOf(phoneNo);
            if (index > -1) {
                multiplePhoneNos.splice(index, 1);
            }
            // Re-index the Sl column
            var table = document.getElementById('product_info_table').getElementsByTagName('tbody')[0];
            for (var i = 0; i < table.rows.length; i++) {
                table.rows[i].cells[0].innerHTML = i + 1;
            }
        }

        window.addMultiplePhone = addMultiplePhone;
        window.deleteRow = deleteRow;
    });
</script>

    <script>
        $(document).ready(function() {
            $('#valid_period, #valid_period_type').change(function() {
                var startDate = $('#start_date').val();
                var validPeriod = $('#valid_period').val();
                var validPeriodType = $('#valid_period_type').val();

                if (startDate && validPeriod && validPeriodType) {
                    var expiredDate = calculateExpiredDate(startDate, validPeriod, validPeriodType);
                    console.log(expiredDate);
                    $('#expired_date').prop('_flatpickr').setDate(expiredDate);
                }
            });

            function calculateExpiredDate(startDate, validPeriod, validPeriodType) {
                var momentStartDate = moment(startDate, 'YYYY-MM-DD');
                var period = parseInt(validPeriod);

                switch (validPeriodType) {
                    case 'days':
                        momentStartDate.add(period, 'days');
                        break;
                    case 'months':
                        momentStartDate.add(period, 'months');
                        break;
                    case 'years':
                        momentStartDate.add(period, 'years');
                        break;
                    case 'unlimited':
                        return '2124-12-31'; // or some other arbitrary date in the future
                }

                return momentStartDate.toDate();
            }
        });
    </script>

    <script>
        $(document).ready(function() {
            $('#customer_id').change(getCustomerSettings);

            const companySelect = new TomSelect("#customer_id", {
                valueField: "id",
                labelField: "text",
                searchField: [], 
                load: function(query, callback) {

                    if (!query.length || query.length < 2) return callback();

                    $.ajax({
                        url: "{{ route('licenses.dongle-or-serial-autocomplete.customers') }}",
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


            const productSelect = new TomSelect("#product_id", {
                valueField: "id",
                labelField: "text",
                searchField: [], 
                load: function(query, callback) {

                    if (!query.length || query.length < 2) return callback();

                    $.ajax({
                        url: "{{ route('licenses.dongle-or-serial-autocomplete.products') }}",
                        type: "GET",
                        data: { search: query },
                        success: function(res) {
                            productSelect.clearOptions();
                            callback(res.map(item => ({ id: item.id, text: item.label })));
                        },
                        error: function() {
                            callback();
                        }
                    });
                }
            }); 

            @if(request('product_id'))
                productSelect.addOption({
                    id: "{{ request('product_id') }}",
                    text: "{{ request('product_id') }}"
                });
                productSelect.setValue("{{ request('product_id') }}");
            @endif

        });

        function getCustomerSettings() {
            var id = $("#customer_id option:selected").val();
            if (id) {
                $.ajax({
                    url: "{{ route('sales.get.customer.setting') }}?id=" + id,
                    success: function(data) {
                        console.log(data);

                        if (data && data.customers && data.customers.customer) {

                            $("#address").val(data.customers.customer.address);
                            $("#phone").val(data.customers.customer.phone);
                        } else {
                            clearFields();
                        }
                    }
                });
            }
        }

        function clearFields() {
            $("#address").val("");
            $("#phone").val("");
        }
    </script>

    <script>
        $(".datePicker").datepicker({
            format: 'dd-mm-yyyy',
            autoclose: true
        });
    </script>
@endsection
