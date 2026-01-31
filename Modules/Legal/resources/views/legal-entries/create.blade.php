@extends('layout.app')
@section('title', 'Create Legal')
@section('description', 'Create Legal')
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
                                        {{ trans('Create Legal Entry') }}</li>
                                </ol>
                            </nav>
                        </div>
                        <div class="action-btn mt-sm-0 mt-15">
                            @if (hasPermission('legal.legal-entries.index'))
                                <a href="{{ route('legal.legal-entries.index') }}"
                                    class="btn btn-warning btn-default btn-squared radius-md shadow2 btn-sm"><i
                                        class="fa fa-list"></i> List</a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12 m-2">
                    <h4 class="text-capitalize breadcrumb-title">{{ trans('Create Legal Entry') }}</h4>
                    <x-error-alart />
                </div>
                <div class="card mb-50">
                    <div class="row justify-content-center" id="justify-content-center">
                        <div class="col-sm-11">
                            <div class="mt-40 mb-50">
                                <h2 class="mb-3">Legal Entry</h2>
                                <form action="{{ route('legal.legal-entries.store', app()->getLocale()) }}" method="POST"
                                    enctype="multipart/form-data">
                                    @csrf
                                    <fieldset class="border p-2">
                                        <legend class="float-none w-auto p-2">
                                            Basic Information
                                        </legend>
                                        <div class="row">
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label for="date">Date<span class="text-danger">*</span>:</label>
                                                    <input type="test" name="date" id="date"
                                                        class="form-control px-15 flatdate"
                                                        value="{{ old('date', date('Y-m-d')) }}" >
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label for="amount">Amount<span class="text-danger">*</span>:</label>
                                                    <input type="number" name="amount" id="amount" value="{{ old('amount') }}"
                                                        class="form-control px-15" step="0.01" >
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label for="legal_type">Legal Type<span
                                                            class="text-danger">*</span>:</label>
                                                    <select name="legal_type" id="legal_type"
                                                        class="form-control px-15 tom-select" >
                                                        <option value="">Select Type</option>
                                                        <option value="case" selected>Case</option>
                                                        <option value="notice" @if(old('legal_type') == 'notice') selected @endif>Notice</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div id="caseFields" class="d-none">
                                                <div class="row">

                                                    <div class="col-md-3">
                                                        <div class="form-group">
                                                            <label for="case_no">Case No<span
                                                                    class="text-danger">*</span>:</label>
                                                            <input type="text" name="case_no" id="case_no" value="{{ old('case_no') }}"
                                                                class="form-control px-15">
                                                        </div>
                                                    </div>

                                                    <div class="col-md-3">
                                                        <div class="form-group">
                                                            <label for="occurrence_info">Occurrence Info:</label>
                                                            <input type="text" name="occurrence_info" value="{{ old('occurrence_info') }}"
                                                                id="occurrence_info" class="form-control px-15">
                                                        </div>
                                                    </div>

                                                    <div class="col-md-3">
                                                        <div class="form-group">
                                                            <label for="occurrence_date">Occurrence Date:</label>
                                                            <input type="text" name="occurrence_date" value="{{ old('occurrence_date') }}"
                                                                id="occurrence_date" class="form-control px-15 flatdate">
                                                        </div>
                                                    </div>

                                                    <div class="col-md-3">
                                                        <div class="form-group">
                                                            <label for="first_hajira_date">First Hajira Date:</label>
                                                            <input type="text" name="first_hajira_date" value="{{ old('first_hajira_date') }}"
                                                                id="first_hajira_date" class="form-control px-15 flatdate">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-8">
                                                <div class="form-group">
                                                    <label for="legal_description">Legal Description:</label>
                                                    <textarea name="legal_description" id="legal_description" class="form-control px-15" rows="3">{{ old('legal_description') }}</textarea>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label for="file">File:</label>
                                                        <x-file-uploader multiple name="attachment" />
                                                </div>

                                            </div>
                                        </div>
                                    </fieldset>

                                    <fieldset class="border p-2">
                                        <legend class="float-none w-auto p-2">
                                            Convict Information

                                        </legend>
                                        <div class="row">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="customer_id">{{ __('Customer') }}<span
                                                                class="text-danger">*</span>:</label>
                                                        <select name="customer_id" id="customer_id"
                                                            class="form-control px-15 tom-select"
                                                            onchange="onChangeCustomer(this)" >
                                                            <option value="">{{ __('Select Customer') }}</option>
                                                            @foreach ($customers as $customer)
                                                                <option value="{{ $customer->id }}"
                                                                    {{ old('customer_id') == $customer->id ? 'selected' : '' }}
                                                                    data-phone = "{{ $customer->phone }}"
                                                                    data-address="{{ $customer->address }}">
                                                                    {{ $customer->company_name }} - {{ $customer->address}}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label for="convict_name"> Name<span
                                                            class="text-danger">*</span>:</label>
                                                    <input type="text" name="convict_name" id="convict_name"
                                                        class="form-control px-15" placeholder="{{ __('Name') }}">
                                                </div>
                                            </div>

                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label for="convict_designation"> Designation<span
                                                            class="text-danger">*</span>:</label>
                                                    <input type="text" name="convict_designation"
                                                        id="convict_designation" class="form-control px-15"
                                                        placeholder="{{ __('Designation') }}">
                                                </div>
                                            </div>

                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label for="convict_phone"> Phone No<span
                                                            class="text-danger">*</span>:</label>
                                                    <input type="text" name="convict_phone" id="convict_phone"
                                                        class="form-control px-15" placeholder="{{ __('Phone') }}">
                                                </div>
                                            </div>

                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label>
                                                        <input type="radio" name="father_or_husband" value="father"
                                                            checked> Father's Name
                                                    </label>
                                                    <label>
                                                        <input type="radio" name="father_or_husband" value="husband">
                                                        Husband’s Name
                                                    </label>
                                                    <label for="convict_father_name" class="d-block mt-2">

                                                    </label>
                                                    <input type="text" name="convict_father_name"
                                                        id="convict_father_name" class="form-control px-15"
                                                        placeholder="{{ __('Father/Husband Name') }}">
                                                </div>
                                            </div>

                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label for="convict_mother_name"> Mother's Name:</label>
                                                    <input type="text" name="convict_mother_name"
                                                        id="convict_mother_name" class="form-control px-15"
                                                        placeholder="{{ __('Mothers Name') }}">
                                                </div>
                                            </div>

                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label for="convict_nid"> NID
                                                        :</label>
                                                    <input type="text" name="convict_nid" id="convict_nid"
                                                        class="form-control px-15" placeholder="{{ __('NID') }}">
                                                </div>
                                            </div>

                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label for="convict_address"> Address<span
                                                            class="text-danger">*</span>:</label>
                                                    <input type="text" name="convict_address" id="convict_address"
                                                        class="form-control px-15" placeholder="{{ __('Address') }}">
                                                </div>

                                            </div>
                                            <div class="col-md-3">
                                                <label for="addConvictRow"></label>
                                                <div class="form-group" style="">
                                                    <button type="button" class="btn btn-sm btn-primary"
                                                        id="addConvictRow">{{ __('Add') }}</button>
                                                </div>
                                            </div>
                                        </div>


                                        <div class="form-group">
                                            <table class="table table-bordered" id="convict_table">
                                                <thead>
                                                    <tr>
                                                        <th>Sl</th>
                                                        <th>Name</th>
                                                        <th>Designation</th>
                                                        <th>Phone No</th>
                                                        <th>Father's/Husband’s Name</th>
                                                        <th>Mother's Name</th>
                                                        <th>NID</th>
                                                        <th>Address</th>
                                                        <th>Action</th>
                                                    </tr>
                                                </thead>
                                                <tbody>

                                                </tbody>
                                            </table>
                                        </div>
                                    </fieldset>

                                    <fieldset class="border p-2">
                                        <legend class="float-none w-auto p-2">
                                            Complainant Information

                                        </legend>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="Company">{{ __('Company Name') }}<span
                                                            class="text-danger">*</span>:</label>
                                                    <input type="text" name="company_name" id="company_name"
                                                        value="{{ old('company_name', 'Global Medical Engineering(BD) Ltd.') }}"
                                                        class="form-control px-15"
                                                        placeholder="{{ __('Company Name') }}">

                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label for="complainant_name"> Name<span
                                                            class="text-danger">*</span>:</label>
                                                    <input type="text" name="complainant_name" id="complainant_name"
                                                        value="{{ old('complainant_name', 'Md Hasibur Rahman') }}"
                                                        class="form-control px-15" placeholder="{{ __('Name') }}">
                                                </div>
                                            </div>

                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label for="complainant_designation"> Designation<span
                                                            class="text-danger">*</span>:</label>
                                                    <input type="text" name="complainant_designation"
                                                        value="{{ old('complainant_designation', 'Executive Officer') }}"
                                                        id="complainant_designation" class="form-control px-15"
                                                        placeholder="{{ __('Designation') }}">
                                                </div>
                                            </div>

                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label for="complainant_phone"> Phone No<span
                                                            class="text-danger">*</span>:</label>
                                                    <input type="text" name="complainant_phone"
                                                        value="{{ old('complainant_phone', '01404003510') }}"
                                                        id="complainant_phone" class="form-control px-15"
                                                        placeholder="{{ __('Phone') }}">
                                                </div>
                                            </div>



                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label for="complainant_father"> Father's Name:</label>
                                                    <input type="text" name="complainant_father"
                                                        value="{{ old('complainant_father', 'Md. Mojibur Rahman') }}"
                                                        id="complainant_father" class="form-control px-15"
                                                        placeholder="{{ __('Father Name') }}">
                                                </div>
                                            </div>

                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label for="complainant_nid"> NID
                                                        :</label>
                                                    <input type="text" name="complainant_nid" id="complainant_nid"
                                                        value="{{ old('complainant_nid', '19997815588000069') }}"
                                                        class="form-control px-15" placeholder="{{ __('NID') }}">
                                                </div>
                                            </div>

                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label for="complainant_address"> Address<span
                                                            class="text-danger">*</span>:</label>
                                                    <input type="text" name="complainant_address"
                                                        value="{{ old('complainant_address', '17/2, Topkhana Road,(2nd Floor), Dhaka-1000') }}"
                                                        id="complainant_address" class="form-control px-15"
                                                        placeholder="{{ __('Address') }}">
                                                </div>

                                            </div>

                                        </div>



                                    </fieldset>

                                    <fieldset class="border p-2">
                                        <legend class="float-none w-auto p-2">
                                            Advocate Information

                                        </legend>
                                        <div class="row">

                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label for="advocate_name"> Name<span
                                                            class="text-danger">*</span>:</label>
                                                    <input type="text" name="advocate_name" id="advocate_name"
                                                        value="{{ old('advocate_name', 'MD. Abed Ali') }}"
                                                        class="form-control px-15" placeholder="{{ __('Name') }}">
                                                </div>
                                            </div>

                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label for="advocate_designation"> Designation<span
                                                            class="text-danger">*</span>:</label>
                                                    <input type="text" name="advocate_designation"
                                                        value="{{ old('advocate_designation', 'Advocate') }}"
                                                        id="advocate_designation" class="form-control px-15"
                                                        placeholder="{{ __('Designation') }}">
                                                </div>
                                            </div>

                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label for="advocate_phone"> Phone No<span
                                                            class="text-danger">*</span>:</label>
                                                    <input type="text" name="advocate_phone"
                                                        value="{{ old('advocate_phone', '01916001396') }}"
                                                        id="advocate_phone" class="form-control px-15"
                                                        placeholder="{{ __('Phone') }}">
                                                </div>
                                            </div>

                                            <div class="col-md-9">
                                                <div class="form-group">
                                                    <label for="advocate_address"> Address<span
                                                            class="text-danger">*</span>:</label>
                                                    <input type="text" name="advocate_address"
                                                        value="{{ old('advocate_address', 'Chamber:36, Court House Street Agar-Batir Goli,1st Floor, Room No:205, Kotwali, Dhaka-1100. ') }}"
                                                        id="advocate_address" class="form-control px-15"
                                                        placeholder="{{ __('Address') }}">
                                                </div>

                                            </div>

                                        </div>

                                        <fieldset class="border p-2" id="witnessSection">
                                            <legend class="float-none w-auto p-2">
                                                Witness Information

                                            </legend>
                                            <div class="row">
                                                <div class="row">

                                                    <div class="col-md-3">
                                                        <div class="form-group">
                                                            <label for="witness_name"> Name<span
                                                                    class="text-danger">*</span>:</label>
                                                            <input type="text" name="witness_name" id="witness_name"
                                                                class="form-control px-15"
                                                                placeholder="{{ __('Name') }}">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <div class="form-group">
                                                            <label for="witness_father_name"> Father’s Name:</label>
                                                            <input type="text" name="witness_father_name"
                                                                id="witness_father_name" class="form-control px-15"
                                                                placeholder="{{ __('Father Name') }}">
                                                        </div>
                                                    </div>

                                                    <div class="col-md-3">
                                                        <div class="form-group">
                                                            <label for="witness_mother_name"> Mother's Name:</label>
                                                            <input type="text" name="witness_mother_name"
                                                                id="witness_mother_name" class="form-control px-15"
                                                                placeholder="{{ __('Mothers Name') }}">
                                                        </div>
                                                    </div>

                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="witness_address"> Address<span
                                                                    class="text-danger">*</span>:</label>
                                                            <input type="text" name="witness_address"
                                                                id="witness_address" class="form-control px-15"
                                                                placeholder="{{ __('Address') }}">
                                                        </div>

                                                    </div>
                                                    <div class="col-md-3">
                                                        <div class="form-group">
                                                            <label for="witness_phone"> Phone No<span
                                                                    class="text-danger">*</span>:</label>
                                                            <input type="text" name="witness_phone" id="witness_phone"
                                                                class="form-control px-15"
                                                                placeholder="{{ __('Phone') }}">
                                                        </div>
                                                    </div>

                                                    <div class="col-md-3">
                                                        <label for="addWitnessRow"></label>
                                                        <div class="form-group" style="">
                                                            <button type="button" class="btn btn-sm btn-primary"
                                                                id="addWitnessRow">{{ __('Add') }}</button>
                                                        </div>
                                                    </div>
                                                </div>


                                                <div class="form-group">
                                                    <table class="table table-bordered" id="witness_table">
                                                        <thead>
                                                            <tr>
                                                                <th>Sl</th>
                                                                <th>Name</th>
                                                                <th>Phone No</th>
                                                                <th>Father's Name</th>
                                                                <th>Mother's Name</th>
                                                                <th>Address</th>
                                                                <th>Action</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>

                                                        </tbody>
                                                    </table>
                                                </div>
                                        </fieldset>



                                    </fieldset>
                                    <div class="d-flex justify-content-end mt-4">
                                        <button type="submit"
                                            class="btn btn-primary btn-sm">{{ __('Save') }}</button>
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
    $(document).ready(function () {
        function toggleCaseFields() {
            const type = $('#legal_type').val();
            if (type === 'case') {
                $('#caseFields').removeClass('d-none');
                $('#witnessSection').removeClass('d-none');
            } else {
                $('#caseFields').addClass('d-none');
                $('#witnessSection').addClass('d-none');
            }
        }

        // Initial check
        toggleCaseFields();

        // On change
        $('#legal_type').on('change', toggleCaseFields);
    });
</script>


    <script>
        function onChangeCustomer(select) {
            const selectedOption = select.options[select.selectedIndex];

            const phone = selectedOption.getAttribute('data-phone') || '';
            const address = selectedOption.getAttribute('data-address') || '';

            $('#convict_phone').val(phone);
            $('#convict_address').val(address);
        }

        // Optional: Trigger once on page load if old value is selected
        $(document).ready(function() {
            const selectedCustomer = document.getElementById('customer_id');
            if (selectedCustomer && selectedCustomer.value) {
                onChangeCustomer(selectedCustomer);
            }
        });
    </script>


    <script>
        $(document).ready(function() {
            let convictSerial = 1;

            $('#addConvictRow').on('click', function() {
                // Required fields
                const fields = [{
                        id: 'convict_name',
                        name: 'Name'
                    },
                    {
                        id: 'convict_designation',
                        name: 'Designation'
                    },
                    {
                        id: 'convict_phone',
                        name: 'Phone No'
                    },
                    {
                        id: 'convict_address',
                        name: 'Address'
                    },
                ];

                for (const field of fields) {
                    const value = $('#' + field.id).val();
                    if (!value || value.trim() === '') {
                        toastr.error(`${field.name} is required`);
                        return;
                    }
                }
                const cusomer_id = $('#customer_id').val();
                const name = $('#convict_name').val();
                const designation = $('#convict_designation').val();
                const phone = $('#convict_phone').val();
                const fatherOrHusband = $('input[name="father_or_husband"]:checked').val();
                const fatherName = $('#convict_father_name').val();
                const motherName = $('#convict_mother_name').val();
                const nid = $('#convict_nid').val();
                const address = $('#convict_address').val();

                const row = `
                <tr>
                    <td>${convictSerial++}</td>
                    <td>
                        ${name}
                        <input type="hidden" name="convict_name[]" value="${name}">
                    </td>
                    <td>
                        ${designation}
                        <input type="hidden" name="convict_designation[]" value="${designation}">
                    </td>
                    <td>
                        ${phone}
                        <input type="hidden" name="convict_phone[]" value="${phone}">
                    </td>
                    <td>
                        ${fatherName}
                        <input type="hidden" name="convict_father_name[]" value="${fatherName}">
                    </td>
                    <td>
                        ${motherName}
                        <input type="hidden" name="convict_mother_name[]" value="${motherName}">
                    </td>
                    <td>
                        ${nid}
                        <input type="hidden" name="convict_nid[]" value="${nid}">
                    </td>
                    <td>
                        ${address}
                        <input type="hidden" name="customer_id[]" value="${cusomer_id}">
                        <input type="hidden" name="convict_address[]" value="${address}">
                        <input type="hidden" name="father_or_husband[]" value="${fatherOrHusband}">
                    </td>
                    <td>
                        <button type="button" class="btn btn-sm btn-danger" onclick="removeConvictRow(this)">
                            <i class="fa fa-trash"></i>
                        </button>
                    </td>
                </tr>
            `;

                $('#convict_table tbody').append(row);

                // Reset form inputs (optional)
                $('#customer_id').val('');
                $('#convict_name').val('');
                $('#convict_designation').val('');
                $('#convict_phone').val('');
                $('#convict_father_name').val('');
                $('#convict_mother_name').val('');
                $('#convict_nid').val('');
                $('#convict_address').val('');
            });
        });

        function removeConvictRow(button) {
            $(button).closest('tr').remove();
            // Optionally re-number serials
            $('#convict_table tbody tr').each((index, tr) => {
                $(tr).find('td:first').text(index + 1);
            });
        }
    </script>
    <script>
        $(document).ready(function() {
            let witnessSerial = 1;

            $('#addWitnessRow').on('click', function() {
                const requiredFields = [{
                        id: 'witness_name',
                        name: 'Name'
                    },
                    {
                        id: 'witness_phone',
                        name: 'Phone No'
                    },
                    {
                        id: 'witness_address',
                        name: 'Address'
                    }
                ];

                for (const field of requiredFields) {
                    const value = $('#' + field.id).val();
                    if (!value || value.trim() === '') {
                        toastr.error(`${field.name} is required`);
                        return;
                    }
                }

                const name = $('#witness_name').val();
                const father = $('#witness_father_name').val();
                const mother = $('#witness_mother_name').val();
                const address = $('#witness_address').val();
                const phone = $('#witness_phone').val();

                const row = `
                <tr>
                    <td>${witnessSerial++}</td>
                    <td>
                        ${name}
                        <input type="hidden" name="witness_name[]" value="${name}">
                    </td>
                    <td>
                        ${phone}
                        <input type="hidden" name="witness_phone[]" value="${phone}">
                    </td>
                    <td>
                        ${father}
                        <input type="hidden" name="witness_father_name[]" value="${father}">
                    </td>
                    <td>
                        ${mother}
                        <input type="hidden" name="witness_mother_name[]" value="${mother}">
                    </td>
                    <td>
                        ${address}
                        <input type="hidden" name="witness_address[]" value="${address}">
                    </td>
                    <td>
                        <button type="button" class="btn btn-sm btn-danger" onclick="removeWitnessRow(this)">
                            <i class="fa fa-trash"></i>
                        </button>
                    </td>
                </tr>
            `;

                $('#witness_table tbody').append(row);

                // Clear form inputs
                $('#witness_name').val('');
                $('#witness_father_name').val('');
                $('#witness_mother_name').val('');
                $('#witness_phone').val('');
                $('#witness_address').val('');
            });
        });

        function removeWitnessRow(button) {
            $(button).closest('tr').remove();
            // Recalculate serial numbers
            $('#witness_table tbody tr').each(function(index, row) {
                $(row).find('td:first').text(index + 1);
            });
        }
    </script>





    <script>
        $('.datePicker').datepicker({
            format: 'yyyy-mm-dd',
            autoclose: true
        });
    </script>
@endSection
