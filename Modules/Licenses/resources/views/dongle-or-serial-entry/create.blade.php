@section('title', 'Dongle Or Serial Entry')
@section('description', 'Dongle Or Serial Entry')
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
                                        {{ trans('menu.create-dongle-or-serial-menu-title') }}</li>
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
                            <form action="{{ route('licenses.dongle-or-serial-entries.store') }}" method="POST"
                                enctype="multipart/form-data">
                                @csrf
                                <div class="row mb-4">
                                    <div class="col-md-4 mt-4">
                                        <div class="form-group">
                                            <label for="customer_id">Customer Name<span class="text-danger">*</span></label>
                                            <select name="customer_id" id="customer_id" class="form-control tom-select required" required>
                                                <option value="">Choose Customer</option>
                                                @foreach ($customers as $customer)
                                                    <option value="{{ $customer->id }}"
                                                        {{ old('customer_id') == $customer->id ? 'selected' : '' }}>
                                                        {{ $customer->company_name }} - {{ $customer->address}}@if ($customer->area != null)
                                                            ({{ $customer->area->area }})
                                                        @endif
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-8 mt-4">
                                        <div class="form-group">
                                            <label for="address">Address</label>
                                            <input type="text" name="address" class="form-control" id="address"
                                                placeholder="Address" readonly>
                                        </div>
                                    </div>
                                    <div class="col-md-8">
                                        <div class="form-group">
                                            <label for="product_id">Product Name<span class="text-danger">*</span></label>
                                            <select name="product_id" id="product_id" class="form-control tom-select" required>
                                                <option value="">Choose Product</option>
                                                @foreach ($products as $product)
                                                    <option value="{{ $product->id }}"
                                                        {{ old('product_id') == $product->id ? 'selected' : '' }}>
                                                        {{ $product->name }}  Brand: {{ $product->brand->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="product_type">Product Type</label>
                                            <select name="product_type" id="product_type" class="form-control" >
                                                <option value="Imaging/Radiology Product" @if (old('product_type') == 'Imaging/Radiology Product') selected @endif>Imaging/Radiology Product</option>
                                                <option value="Hematology Analyzer" @if (old('product_type') == 'Hematology Analyzer') selected @endif>Hematology Analyzer</option>
                                            </select>
                                        </div>
                                    </div>
                                   
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="dongle_id"> Dongle Id/Serial No<span class="text-danger">*</span></label>
                                            <input type="text" name="dongle_id" class="form-control" value="{{ old('dongle_id') }}"
                                                id="dongle_id" placeholder="Dongle Id/Serial No" required>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="software_version">Software Version</label>
                                            <input type="text" name="software_version" class="form-control" value="{{ old('software_version') }}"
                                                id="software_version" placeholder="Software Version">
                                            
                                        </div>
                                    </div>

                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="file_upload">File Up</label>
                                          <x-file-uploader  name="file_upload"/>

                                            {{-- <input type="file"
                                                class="file-control form-control"
                                                id="file_upload" name="file_upload"> --}}
                                        </div>
                                    </div>

                                    <div class="col-md-3"> 
                                        <div class="form-group">
                                            <label for="status">Dongle Status</label>
                                            <select name="status" id="status" class="form-control">
                                                <option value="Active" {{ old('status') == 'Active' ? 'selected' : '' }}>Active</option>
                                                <option value="Inactive" {{ old('status') == 'Inactive' ? 'selected' : ''}}>Inactive</option>
                                            </select>
                                        </div>
                                    </div>


                                   
                                    <div class="col-md-12">
                                        <div
                                            class="button-group d-flex pt-25 justify-content-md-end justify-content-start">
                                            <button type="submit" class="btn btn-primary">Submit</button>
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
