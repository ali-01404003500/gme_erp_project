@section('title', 'Salary Generate List')
@section('description', 'Salary Generate List')
@extends('layout.app')
@section('content')
@section('content')
    <!-- CONTENT AREA -->
    <div class="container-fluid">
        <div class="social-dash-wrap">
            <div class="row">
                <div class="col-lg-12">
                    <div class="breadcrumb-main">
                        <div class="breadcrumb-action justify-content-center flex-wrap">
                            <nav aria-label="breadcrumb">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="/"><i class="las la-home"></i>Home</a></li>
                                    <li class="breadcrumb-item active" aria-current="page">
                                        {{ trans('Salary Generate List') }}</li>
                                </ol>
                            </nav>
                        </div>
                        <div class="breadcrumb-main__wrapper">
                            <div class="action-btn mt-sm-0 mt-15">
                                @if (hasPermission('hrm.salary-generates.create'))
                                    <button class="btn btn-xs btn-primary me-1" data-bs-toggle="modal"
                                        data-bs-target="#createModal">
                                        Make Salary Generate
                                    </button>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>


            <div class="row">
                <div class="col-md-12" style="padding-bottom: 20px">
                    <div class="row" style="width: 100%">
                        <div class="col-md-6">
                            <h4 class="text-capitalize breadcrumb-title">{{ trans('Salary Generate List') }}</h4>
                        </div>
                    </div>
                    <x-error-alart />
                </div>
                <div class="col-md-12 my-4">
                    <div class="card">
                        <div class="card-body">
                            <form>
                                <div class="col-sm-12">
                                    <table class="table table-bordered">
                                        <tr>
                                            <td>
                                                <select name="department_id" id="department_id"
                                                    class="form-select tom-select">
                                                    <option value="">All Department</option>
                                                    @foreach ($departments as $key => $value)
                                                        <option
                                                            {{ request('department_id') == $value->id ? 'selected' : '' }}
                                                            value="{{ $value->id }}">
                                                            {{ $value->name }}</option>
                                                    @endforeach
                                                </select>
                                            </td>
                                            <td>
                                                <input type="text" name="year_month"
                                                    class="form-control input-sm flatmonth"
                                                    value="{{ request('year_month') }}">
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
                <div class="col-md-12">
                    <div class="card mb-4">
                        <div class="card-body">
                            <div class="table-responsive">
                                <table id="zero-config"class="table dt-table-hover" data-page='@include('utils.table_paginate', ['data' => $payrolls])' style="width:100%">
                                    <thead>
                                        <tr>
                                            <th class="text-center" style="width: 8%">Sl</th>
                                            <th class="text-center">ID</th>
                                            <th class="text-center">Department</th>
                                            <th class="text-center">Month</th>
                                            <th class="text-center">Year</th>
                                            <th class="text-center">Total Employees</th>
                                            <th class="text-center">Net Salary</th>
                                            <th class="text-center no-content" >Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @csrf
                                        @foreach ($payrolls as $key => $item)
                                            <tr>
                                            <td class="text-center">{{ ($payrolls->currentPage() - 1) * $payrolls->perPage() + $loop->iteration  }}</td>
                                                <td class="text-center">{{ $item->invoice_id }}</td>
                                                <td class="text-center">{{ optional($item->department)->name ??'All Department'}}</td>
                                                <td class="text-center">{{ date('F', strtotime($item->year_month)) }}</td>
                                                <td class="text-center">{{ date('Y', strtotime($item->year_month)) }}</td>
                                                <td class="text-center">{{ $item->total_employees }}</td> 
                                                <td class="text-center">{{ numberFormat($item->total_net_earning) }}</td> 
                                                <td class="text-center">                                       
                                                    <div class="btn-group btn-group-sm" role="group" aria-label="Small button group"> 
                                                        @if (hasPermission('hrm.salary-generates.index'))
                                                            <a href="{{ route('hrm.salary-generates.index', ['payroll_id' => $item->id]) }}" class="btn btn-outline-primary" title="View">
                                                                <i class="fas fa-eye"></i>
                                                            </a>
                                                        @endif
                                                    </div>
                                                </td>
                                            </tr>
                                            
                                        @endforeach
                                    </tbody>
                                </table>

                            </div>
                           
                        </div>
                      
                    </div>

                </div>
            </div>

            <!-- Create Modal -->
            <div class="modal fade inputForm-modal" id="createModal" tabindex="-1" role="dialog"
                aria-labelledby="createModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-md" role="document">
                    <div class="modal-content">

                        <div class="modal-header" id="createModalLabel">
                            <h5 class="modal-title">Salary Generate</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                aria-hidden="true"></button>
                        </div>
                        <form action="{{ route('hrm.salary-generates.store') }}" method="post">
                            @csrf
                            <div class="modal-body">
                                <div class="row mb-4">
                                    <label class="col-sm-12 col-form-label">Employee</label>
                                    <div class="col-sm-12">
                                        <select name="employee_id"  
                                            class="form-select tom-select">
                                            <option value="">All Employee</option> 
                                            @foreach ($employees as $item)
                                                <option value="{{ $item->id }}">{{ $item->full_name }}</option>
                                            @endforeach

                                        </select>
                                    </div>
                                </div>
                                <div class="row mb-4">
                                    <label class="col-sm-12 col-form-label">Department</label>
                                    <div class="col-sm-12">
                                        <select class="form-select tom-select" name="department_id">
                                            <option value="">Select Department</option>
                                            @foreach ($departments as $item)
                                                <option value="{{ $item->id }}">{{ $item->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                
                                <div class="row mb-4">
                                    <label class="col-sm-12 col-form-label">Year Month</label>
                                    <div class="col-sm-12">
                                        <input type="text" name="year_month" class="form-control flatmonth"
                                            value="{{ date('Y-m') }}" required>
                                    </div>
                                </div>
                            </div>


                            <div class="modal-footer">
                                <button type="button" class="btn btn-light-danger mt-2 mb-2 btn-no-effect"
                                    data-bs-dismiss="modal">Cancel</button>
                                <button type="submit" class="btn btn-primary mt-2 mb-2 btn-no-effect" id="generateSalaryBtn">
                                    </span>&nbsp;<span class="nav-icon fa fa-cog"></span>Generate
                                    </span></button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="modal fade inputForm-modal" id="editModal" tabindex="-1" role="dialog"
            aria-labelledby="editModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-md" role="document">
                    <div class="modal-content">

                        <div class="modal-header" id="editModalLabel">
                            <h5 class="modal-title">Edit Department</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
                        </div>
                        <form action="" method="post" id="editFrom">
                            @csrf
                            <div class="modal-body">
                                <div class="row mb-4">
                                    <label for="paymentable_amount" class="col-sm-12 col-form-label">Paymentable Amount</label>
                                    <div class="col-sm-12">
                                        <input name="paymentable_amount" id="paymentable_amount" class="form-control" type="text" readonly>
                                    </div>
                                </div>
                                <div class="row mb-4">
                                    <label for="amount" class="col-sm-12 col-form-label">Paid amount</label>
                                    <div class="col-sm-12">
                                        <input name="amount" id="amount" class="form-control" type="number" required>
                                    </div>
                                </div>
                                
                            </div>

                            <div class="modal-footer">
                                <button type="button" class="btn btn-light-danger mt-2 mb-2 btn-no-effect"
                                    data-bs-dismiss="modal">Cancel</button>
                                <button type="submit" class="btn btn-primary mt-2 mb-2 btn-no-effect">Paid</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <div class="modal fade inputForm-modal" id="partiallyPaidModal" tabindex="-1" role="dialog" aria-labelledby="partiallyPaidModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="partiallyPaidModalLabel">Partially Paid</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>

                        </div>
                        <form id="partiallyPaidForm">
                            <div class="modal-body">
                                <input type="hidden" id="partiallyPaidIds" name="ids">
                                <div class="form-group">
                                    <label for="partiallyPaidAmount">Amount:</label>
                                    <input type="number" class="form-control" id="partiallyPaidAmount" name="amount" required>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-light-danger mt-2 mb-2 btn-no-effect"
                                data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-primary mt-2 mb-2 btn-no-effect">Paid</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div id="overlay" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,0.5); z-index:9999; text-align:center;">
                <div style="position:absolute; top:50%; left:50%; transform:translate(-50%, -50%); color:white; font-size:1.5rem;">
                    <div class="spinner-border text-light" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                    <div>Salary Generating...</div>
                </div>
            </div>
        </div>
    </div>


@endsection
<!-- CONTENT AREA -->
@section('page_scripts')
    <meta name="csrf-token" content="{{ csrf_token() }}">   
    <script>
        $(document).ready(function(e) {
           $(document).on('click', '.btn-edit', function() {
               $('#paymentable_amount').val($(this).data('paymentable_amount'));
               $("#editFrom").attr("action", $(this).data('action'));

           });
           $('#editFrom').submit(function(e) {
            var paymentableAmount = parseFloat($('#paymentable_amount').val());
            var amount = parseFloat($('#amount').val());

            if (amount > paymentableAmount) {
                toastr.error('Paid amount cannot be greater than paymentable amount');
                $('#amount').val(paymentableAmount);
                e.preventDefault();
            }
        });
       });
    </script>

    {{-- <script>
        $(document).ready(function() {
            // Get CSRF token from meta tag
            var csrfToken = $('meta[name="csrf-token"]').attr('content');

            // Toggle check/uncheck all checkboxes
            $('#checkAll').click(function() {
                $('.checkBoxClass').prop('checked', $(this).is(':checked'));
            });

            // Handle Paid and Partially Paid buttons
            $('#paidAll, #partiallyPaidAll').click(function(event) {
                event.preventDefault(); // Prevent form submission

                var ids = [];
                // Collect all checked checkboxes
                $('.checkBoxClass:checked').each(function() {
                    ids.push($(this).val());
                });

                if (ids.length > 0) {
                    var url = $(this).attr('formaction');
                    var status = $(this).val();

                    // AJAX request to update salaries
                    $.ajax({
                        type: 'POST',
                        url: url,
                        headers: {
                            'X-CSRF-TOKEN': csrfToken // Include CSRF token in the header
                        },
                        data: {
                            id: ids,
                            status: status
                        },
                        success: function(response) {
                            // Store success message in localStorage
                            localStorage.setItem('successMessage',
                                'Salaries updated successfully!');

                            // Reload the page after success
                            location.reload();
                        },
                        error: function(xhr, status, error) {
                            toastr.error('Error: ' + error);
                        }
                    });
                } else {
                    toastr.error('Please select at least one salary to update.');
                }
            });

            // Show success message if it exists in localStorage
            if (localStorage.getItem('successMessage')) {
                toastr.success(localStorage.getItem('successMessage'));
                localStorage.removeItem('successMessage'); // Remove it after showing
            }
        });
    </script> --}}
<script>
    $(document).ready(function() {

    $(document).ready(function(){
        $('#generateSalaryBtn').on('click', function(){
            $('#overlay').show(); // Show overlay
        });
    });


    // Get CSRF token from meta tag
    var csrfToken = $('meta[name="csrf-token"]').attr('content');

    // Toggle check/uncheck all checkboxes
    $('#checkAll').click(function() {
        $('.checkBoxClass').prop('checked', $(this).is(':checked'));
    });

    // Handle Paid and Partially Paid buttons
    $('#paidAll, #partiallyPaidAll').click(function(event) {
        event.preventDefault(); // Prevent form submission

        var ids = [];
        // Collect all checked checkboxes
        $('.checkBoxClass:checked').each(function() {
            ids.push($(this).val());
        });

        if (ids.length > 0) {
            if ($(this).attr('id') === 'partiallyPaidAll') {
                // Open modal for partially paid
                $('#partiallyPaidModal').modal('show');
                $('#partiallyPaidIds').val(ids.join(','));
            } else {
                var url = $(this).attr('formaction');
                var status = $(this).val();

                // AJAX request to update salaries
                $.ajax({
                    type: 'POST',
                    url: url,
                    headers: {
                        'X-CSRF-TOKEN': csrfToken // Include CSRF token in the header
                    },
                    data: {
                        id: ids,
                        status: status
                    },
                    success: function(response) {
                        // Store success message in localStorage
                        localStorage.setItem('successMessage',
                            'Salaries updated successfully!');

                        // Reload the page after success
                        location.reload();
                    },
                    error: function(xhr, status, error) {
                        toastr.error('Error: ' + error);
                    }
                });
            }
        } else {
            toastr.error('Please select at least one salary to update.');
        }
    });

    // Handle partially paid modal form submission
    $('#partiallyPaidForm').submit(function(event) {
        event.preventDefault();

        var ids = $('#partiallyPaidIds').val().split(',');
        var amount = $('#partiallyPaidAmount').val();
        var url = $('#partiallyPaidAll').attr('formaction');

        // AJAX request to update salaries
        $.ajax({
            type: 'POST',
            url: url,
            headers: {
                'X-CSRF-TOKEN': csrfToken // Include CSRF token in the header
            },
            data: {
                id: ids,
                status: 'Partially Paid',
                amount: amount
            },
            success: function(response) {
                // Store success message in localStorage
                localStorage.setItem('successMessage',
                    'Salaries updated successfully!');

                // Reload the page after success
                location.reload();
            },
            error: function(xhr, status, error) {
                toastr.error('Error: ' + error);
            }
        });
    });

    // Show success message if it exists in localStorage
    if (localStorage.getItem('successMessage')) {
        toastr.success(localStorage.getItem('successMessage'));
        localStorage.removeItem('successMessage'); // Remove it after showing
    }
});
</script>
@endsection
