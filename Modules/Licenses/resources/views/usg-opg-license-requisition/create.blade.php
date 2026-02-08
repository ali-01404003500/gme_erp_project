@section('title', 'USG OPG License Requisition Create')
@section('description', 'USG OPG License Requisition Create')
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
                                        {{ trans('menu.create-usg-opg-license-requisition-menu-title') }}</li>
                                </ol>
                            </nav>
                        </div>
                        <div class="action-btn mt-sm-0 mt-15 row">
                            <a href="{{ route('licenses.usg-opg-license-requisitions.index') }}"
                                class="btn btn-warning btn-default btn-squared radius-md shadow2 btn-sm"><i
                                    class="fa fa-list"></i> List</a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12 m-2">
                    <h4 class="text-capitalize breadcrumb-title">
                        {{ trans('menu.create-usg-opg-license-requisition-menu-title') }}
                    </h4>
                    <x-error-alart />
                </div>
                <div class="col-md-12">
                    <div class="card mb-4">
                        <div class="card-body">
                            <form action="{{ route('licenses.usg-opg-license-requisitions.store') }}" method="POST"
                                enctype="multipart/form-data">
                                @csrf
                                <div class="row mb-4">
                                    <div class="col-md-12 text-end">
                                        Balance: <span id="balance"></span>
                                    </div>

                                    <div class="col-md-4 mt-4">
                                        <div class="form-group">
                                            <label for="customer_id">Customer Name<span class="text-danger">*</span></label>
                                            <select name="customer_id" id="customer_id" class="form-control tom-select">
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
                                    <div class="col-md-4 mt-4">
                                        <div class="form-group">
                                            <label for="address">Address</label>
                                            <input type="text" name="address" class="form-control" id="address"
                                                placeholder="Address" readonly>
                                        </div>
                                    </div>
                                    <div class="col-md-4 mt-4">
                                        <div class="form-group">
                                            <label for="phone"> Customer Phone</label>
                                            <input type="text" name="phone" class="form-control" id="phone"
                                                placeholder="Phone Number" readonly>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="dongle_id"> Dongle Id<span class="text-danger">*</span></label>
                                            <select name="dongle_id" id="dongle_id" class="form-control tom-select">
                                                <option value="">Choose Dongle Id</option>

                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="product_model">Product Model</label>
                                            <input type="text" name="product_model" class="form-control"
                                                id="product_model" placeholder="Product Model" readonly>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="software_version">Software Version</label>
                                            <input type="text" name="software_version" class="form-control"
                                                id="software_version" placeholder="Software Version" readonly>

                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="start_date">Start Date<span class="text-danger">*</span></label>
                                            <input type="text" name="start_date" class="form-control flatdate"
                                                id="start_date" value="{{ old('start_date', date('Y-m-d')) }}"
                                                placeholder="Start Date">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="valid_period">Valid Period <span
                                                    class="text-danger">*</span></label>
                                            <div class="input-group">
                                                <input type="text" name="valid_period" class="form-control"
                                                    id="valid_period" placeholder="Valid Period"
                                                    value="{{ old('valid_period', 1) }}">
                                                <select name="valid_period_type" id="valid_period_type"
                                                    class="form-control">
                                                    <option value="days">Days</option>
                                                    <option value="months">Months</option>
                                                    <option value="years">Years</option>
                                                    <option value="unlimited">Unlimited</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="expired_date"> Expired Date<span
                                                    class="text-danger">*</span></label>
                                            <input type="text" name="expired_date" class="form-control flatdate"
                                                id="expired_date" value="{{ old('expired_date', date('Y-m-d')) }}"
                                                placeholder="Expired Date">
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="remarks">Remarks</label>
                                            <textarea name="remarks" id="remarks" class="form-control"
                                                placeholder="Remarks">{{ old('remarks') }}</textarea>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="note"><span class="text-danger">Note: </span> <span id="note" class="text-danger note"></span> </label>
                                        </div>
                                    </div>
                                    <div class="col-md-12 p-4">
                                        <fieldset class="border p-4 m-4">
                                            <legend class="float-none w-auto p-2">
                                                Multiple Phone No Info
                                            </legend>
                                            <div class="row">
                                                <div class="col-md-3"></div>
                                                <div class="col-md-5">
                                                    <div class="form-group mb-25">
                                                        <label for="multiple_phone_no" class="color-dark fs-14 fw-500 align-center">Phone No</label>
                                                        <input type="text" class="form-control" id="multiple_phone_no" name="multiple_phone_no" value=""
                                                            placeholder="Multiple Phone No">
                                                    </div>
                                                </div>
                                                <div class="col-md-2">
                                                    <div class="form-group mb-25">
                                                        <br>
                                                        <button type="button" class="btn btn-primary btn-default btn-squared radius-md shadow2 btn-xs"
                                                            onclick="addMultiplePhone()">+ Add</button>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row mt-4">
                                                <div class="col-md-12 p-4">
                                                    <table class="table table-bordered" id="product_info_table">
                                                        <thead>
                                                            <tr>
                                                                <th style="width: 5%; text-align: center">Sl</th>
                                                                <th style="width: 15%; text-align: center">Phone No</th>
                                                                <th style="width: 8%; text-align: center;">Action</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody style="text-align: center">
                                                            @if(old('multiple_phone_no'))
                                                                @foreach(old('multiple_phone_no') as $phoneNo)
                                                                    <tr>
                                                                        <td>{{ $loop->iteration }}</td>
                                                                        <td>{{ $phoneNo }}</td>
                                                                        <td>
                                                                            <button type="button" class="btn btn-danger btn-default btn-squared radius-md shadow2 btn-xs"
                                                                                onclick="removeMultiplePhone(this)">- Remove</button>
                                                                        </td>
                                                                    </tr>
                                                                @endforeach
                                                            @endif
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </fieldset>
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
    $(document).ready(function() {

        $('#customer_id').on('change', function() {
                var customerId = $(this).val();
                if (customerId) {
                    $.ajax({
                        type: "GET",
                        url: "{{ route('licenses.usg-opg.getDongleIds') }}",
                        data: { customer_id: customerId },
                        success: function(data) {
                            clearFields();
                            $('#dongle_id').append('<option value="">Choose Dongle Id</option>');
                            $.each(data['dongles'], function(index, dongle) {
                                $('#dongle_id').append('<option value="' + dongle.id + '" data-product-model="' + dongle.product.model+ '" data-software-version="' + dongle.software_version + '">' + dongle.dongle_id + '</option>');
                            });
                            $('#dongle_id').prop('tomselect').clearOptions();
                            $('#dongle_id').prop('tomselect')?.sync();
                        },
                        error: function(xhr, status, error) {
                            clearFields();
                        }
                    });

                    $.ajax({
                        url: `{{ route('account.get-ballance') }}?account_id=${customerId}&type=customer`,
                        type: 'GET',
                        dataType: 'json',
                        success: function(data) {
                            if (data) {
                                console.log(data);
                                let currentDate = new Date().toISOString().slice(0, 10); 
                                const balanceLink = "{{ route('account.report.customer-ledger', ['account_id' => 'AccountId']) }}".replace('AccountId', data.id) + `&from=2021-10-05&to=${currentDate}`;
                                $('#balance').html('<a href="'+balanceLink+'" target="_blank">'+data.balance+'</a>'); 
                                // Populate additional details based on the response
                            }
                        },
                        error: function(xhr) {
                            toastr.error('Failed to load details. Please check the console for errors.');
                            console.error(xhr.responseText);
                        }
                    });
                    
                } else {
                    clearFields();
                }
                
            });

            function clearFields() {
                $('#dongle_id').prop('selectedIndex', 0); 
                $('#dongle_id').prop('tomselect').clearOptions();
                $('#dongle_id').prop('tomselect')?.sync();                
                $('#product_model').val('');
                $('#software_version').val('');
                $('.note').text('');

            }

        $('#dongle_id').on('change', function() {
            var selectedOption = $(this).find('option:selected');
            var productModel = selectedOption.data('product-model');
            $('#product_model').val(productModel);

            var softwareVersion = selectedOption.data('software-version');
            $('#software_version').val(softwareVersion);
        }); 
        
    });
</script>
<script>
     $(document).ready(function() {

    $('#dongle_id').on('change', function() {
            var customerId = $('#customer_id').val();	
            var dongleId = $(this).val();
            console.log(dongleId, customerId);
            
            if (dongleId) {
                $.ajax({
                    type: "GET",
                    url: "{{ route('licenses.usg-opg.getNotes') }}",
                    data: { 
                        customer_id: customerId, 
                        dongle_id: dongleId
                     },
                    success: function(data) {
                        clearFields();
                        console.log(data);
                        
                        var note = data['notes'];
                        $('.note').text(note?.remarks);
                        
                    },
                    error: function(xhr, status, error) {
                        clearFields();
                    }
                });
            } else {
                clearFields();
            }
        });

        function clearFields() {
            $('.note').text('');
        }
    });
</script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        var multiplePhoneNos = []; // Array to store multiple phone numbers

        function addMultiplePhone() {
            var phoneNo = document.getElementById('multiple_phone_no').value;
            var customerPhone = document.getElementById('phone').value
            var bangladeshPhonePattern = /^(?:\+?88|01)?01[3-9]\d{8}$/; 
            if (phoneNo === "") {
                toastr.warning('Please Enter Phone Number');
            } else if (!bangladeshPhonePattern.test(phoneNo)) {
                toastr.warning('Invalid Bangladeshi Phone Number.');
            } else if (multiplePhoneNos.includes(phoneNo)|| customerPhone.includes(phoneNo)) {
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
        flatpickr('#expired_date', {
            altInput: true,
            altFormat: "d/m/Y",
            dateFormat: "Y-m-d",
            noCalendar: true,
        });

        $('#valid_period, #valid_period_type, #start_date').change(function() {
            var startDate = $('#start_date').val();
            var validPeriod = $('#valid_period').val();
            var validPeriodType = $('#valid_period_type').val();

            if (startDate && validPeriod && validPeriodType) {
                var expiredDate = calculateExpiredDate(startDate, validPeriod, validPeriodType);
                $('#expired_date').prop('_flatpickr').setDate(expiredDate);
            }
        });

        function calculateExpiredDate(startDate, validPeriod, validPeriodType) {
            var momentStartDate = moment(startDate, 'YYYY-MM-DD');
            var period = parseInt(validPeriod);

            switch (validPeriodType) {
                case 'days':
                    // Add days and subtract 1 day to include the start day
                    momentStartDate.add(period - 1, 'days');
                    break;
                case 'months':
                    // Add months and subtract 1 day to include the start day
                    momentStartDate.add(period, 'months').subtract(1, 'day');
                    break;
                case 'years':
                    // Add years and subtract 1 day to include the start day
                    momentStartDate.add(period, 'years').subtract(1, 'day');
                    break;
                case 'unlimited':
                    return '2124-12-31'; // Far future date
            }

            return momentStartDate.format('YYYY-MM-DD');
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


@endsection
