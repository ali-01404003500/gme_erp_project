@section('title', 'USG OPG License Requisition Edit')
@section('description', 'USG OPG License Requisition Edit')
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
                                        {{ trans('menu.update-usg-opg-license-requisition-menu-title') }}</li>
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
                        {{ trans('menu.update-usg-opg-license-requisition-menu-title') }}
                    </h4>
                    <x-error-alart />
                </div>
                <div class="col-md-12">
                    <div class="card mb-4">
                        <div class="card-body">
                            <form action="{{ route('licenses.usg-opg-license-requisitions.update', $license->id)}}" method="POST"
                                enctype="multipart/form-data">
                                @method('PUT')
                                @csrf
                                <div class="row mb-4">
                                    <div class="col-md-12 text-end">
                                        Balance: <span id="balance"></span>
                                    </div>
                                    <div class="col-md-4 mt-4">
                                        <div class="form-group">
                                            <label for="customer_id">Customer Name<span class="text-danger">*</span></label>
                                            <input type="hidden" name="customer_id" class="form-control" value="{{ $license->customer_id}}">
                                            <input type="text" name="customer_name" class="form-control" value="{{ $license->customer->company_name}}" readonly>
                                        </div>
                                    </div>
                                    <div class="col-md-4 mt-4">
                                        <div class="form-group">
                                            <label for="address">Address</label>
                                            <input type="text" name="address" class="form-control" id="address" value="{{ $license->address }}"
                                                placeholder="Address" readonly>
                                        </div>
                                    </div>
                                    <div class="col-md-4 mt-4">
                                        <div class="form-group">
                                            <label for="phone"> Customer Phone</label>
                                            <input type="text" name="phone" class="form-control" id="phone" value="{{ $license->phone }}"
                                                placeholder="Phone Number" readonly>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="dongle_id"> Dongle Id<span class="text-danger">*</span></label>
                                            <input type="hidden" name="dongle_id" class="form-control" value="{{ $license->dongle_id}}">
                                            <input type="text" name="dongle_name" class="form-control" value="{{ $license->dongles->dongle_id}}" readonly>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="product_model">Product Model</label>
                                            <input type="text" name="product_model" class="form-control" value="{{ $license->product_model }}"
                                                id="product_model" placeholder="Product Model" readonly>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="software_version">Software Version</label>
                                            <select name="software_version" id="software_version" class="form-control">
                                                <option value="">-- Select Software Version --</option>
                                                <option value="Old Software Version" {{ (old('software_version', $entry->software_version ?? '') == 'Old Software Version') ? 'selected' : '' }}>Old Software Version</option>
                                                <option value="New Software Version" {{ (old('software_version', $entry->software_version ?? '') == 'New Software Version') ? 'selected' : '' }}>New Software Version</option>
                                                <option value="G-55 Power & Smart" {{ (old('software_version', $entry->software_version ?? '') == 'G-55 Power & Smart') ? 'selected' : '' }}>G-55 Power & Smart</option>
                                                <option value="MAC Id" {{ (old('software_version', $entry->software_version ?? '') == 'MAC Id') ? 'selected' : '' }}>MAC Id</option>
                                                <option value="Loading" {{ (old('software_version', $entry->software_version ?? '') == 'Loading') ? 'selected' : '' }}>Loading</option>
                                                <option value="Device Id-12 Digit" {{ (old('software_version', $entry->software_version ?? '') == 'Device Id-12 Digit') ? 'selected' : '' }}>Device Id-12 Digit</option>
                                                <option value="Device Id-16 Digit" {{ (old('software_version', $entry->software_version ?? '') == 'Device Id-16 Digit') ? 'selected' : '' }}>Device Id-16 Digit</option>
                                                <option value="Others" {{ (old('software_version', $entry->software_version ?? '') == 'Others') ? 'selected' : '' }}>Others</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="start_date">Start Date<span class="text-danger">*</span></label>
                                            <input type="text" name="start_date" class="form-control flatdate"
                                                id="start_date" value="{{ old('start_date', $license->start_date) }}"
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
                                                    value="{{ old('valid_period', $license->valid_period) }}">
                                                <select name="valid_period_type" id="valid_period_type"
                                                    class="form-control">
                                                    <option value="days" {{ $license->valid_period_type == 'days' ? 'selected' : '' }}>Days</option>
                                                    <option value="months" {{ $license->valid_period_type == 'months' ? 'selected' : '' }}>Months</option>
                                                    <option value="years" {{ $license->valid_period_type == 'years' ? 'selected' : '' }}>Years</option>
                                                    <option value="unlimited" {{ $license->valid_period_type == 'unlimited' ? 'selected' : '' }}>Unlimited</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="expired_date"> Expired Date<span
                                                    class="text-danger">*</span></label>
                                            <input type="text" name="expired_date" class="form-control flatdate"
                                                id="expired_date" value="{{ old('expired_date', $license->expired_date) }}"
                                                placeholder="Expired Date">
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="remarks">Remarks</label>
                                            <textarea name="remarks" id="remarks" class="form-control"
                                                placeholder="Remarks">{{ old('remarks', $license->remarks) }}</textarea>
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
                                                           @foreach ($license->phones as  $phone)
                                                                <tr>
                                                                    <td>{{ $loop->iteration }}</td>
                                                                    <td>{{ $phone->multiple_phone_no }}
                                                                        <input type="hidden" id="multiple_phone_nos" name="multiple_phone_nos[]" value="{{ $phone->multiple_phone_no }}">
                                                                    </td>
                                                                    <td>
                                                                        <button type="button" class="btn btn-danger btn-xs" onclick="deleteRow(this)"><i class="fa fa-trash"></i></button>                                                                    
                                                                    </td>
                                                           @endforeach
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
        flatpickr('#expired_date', {
                    altInput: true,
                    altFormat: "d/m/Y",
                    dateFormat: "Y-m-d",
                    noCalendar: true,

                });

        $('#customer_id').on('change', function() {
            var customerId = $(this).val();
            if (customerId) {
                $.ajax({
                    type: "GET",
                    url: "{{ route('licenses.usg-opg.getDongleIds') }}",
                    data: { customer_id: customerId },
                    success: function(data) {
                        $('#dongle_id').empty();
                        $('#product_model').val('');
                        $('#dongle_id').append('<option value="">Choose Dongle Id</option>');
                        $.each(data, function(index, dongle) {
                            $('#dongle_id').append('<option value="' + dongle.id + '" data-product-model="' + dongle.product.model + '">' + dongle.dongle_id + '</option>');
                        });
                        $('#dongle_id').prop('tomselect').clearOptions();
                        $('#dongle_id').prop('tomselect')?.sync();
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
            }

            
        });

        $('#dongle_id').on('change', function() {
            var selectedOption = $(this).find('option:selected');
            var productModel = selectedOption.data('product-model');
            $('#product_model').val(productModel);
        });
        
        
    });
</script>
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
            } else if ($(`#multiple_phone_nos[value='${phoneNo}']`).length > 0) {
                toastr.warning('This phone number is already added.');
                return;
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
            row.insertCell(1).innerHTML = '<input type="hidden" id="multiple_phone_nos" name="multiple_phone_nos[]" value="' + phoneNo + '">' + phoneNo;
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
    $(document).ready(function () {
        $('#start_date, #valid_period, #valid_period_type').on('change', function () {
            updateExpiredDate();
        });

        function updateExpiredDate() {
            var startDate = $('#start_date').val();
            var validPeriod = $('#valid_period').val();
            var validPeriodType = $('#valid_period_type').val();

            if (startDate && validPeriod && validPeriodType) {
                var expiredDate = calculateExpiredDate(startDate, validPeriod, validPeriodType);
                if ($('#expired_date')[0]._flatpickr) {
                    $('#expired_date')[0]._flatpickr.setDate(expiredDate);
                } else {
                    $('#expired_date').val(expiredDate);
                }
            }
        }

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

    <script>
        $(".datePicker").datepicker({
            format: 'dd-mm-yyyy',
            autoclose: true
        });
    </script>
@endsection
