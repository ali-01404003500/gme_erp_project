
@section('title', 'Create Leave Status ')
@section('description', 'Current Leave Status')
@extends('layout.app')
@section('content')
    <div class="container-fluid">
        <div class="card mb-4 border-0 shadow-sm mt-4">
            <div class="card-body">
                <h4 class="mb-0 fw-bold text-secondary">Current Leave Status</h4>
            </div>
        </div>

        <div class="card border-0 shadow-sm">
            <div class="card-body p-4">
                <form action="{{ route('hrm.leave-statuses.store') }}" method="POST">
                @csrf 
                    <div class="row">
                        <div class="col-md-5"> 
                            <input type="hidden" name="type" value="employee_wise">
                            <input type="hidden" name="leave_year_id" value="{{ $leaveYearId }}">
                            <div class="row g-3">
                                <div class="col-md-7">
                                    <label class="form-label fw-bold small">Employee Name</label>
                                    <select id="employee_id"  name="employee_id" class="form-select">
                                        <option value="">Select Employee</option>
                                        @foreach($employees as $emp)
                                            <option value="{{ $emp->id }}" data-joining="{{ $emp->employementDetail->date_of_joining }}" 
                                                {{ old('employee_id') == $emp->id ? 'selected' : '' }}  >{{ $emp->full_name }} </option>
                                        @endforeach
                                    </select>
                                </div> 

                                <div class="col-md-5">
                                    <label class="form-label fw-bold small">Joining Date</label>
                                    <input type="text"
                                        class="form-control "
                                        value="{{ old('join_date') }}" name="join_date" id="join_date" placeholder="Joining Date" readonly>
                                </div> 


                                <div class="col-md-7">
                                    <label class="form-label fw-bold small">Leave Group</label> 
                                    <select name="leave_group_id" id="leave_group_id" class="form-select">
                                        <option value="">Select Leave Group</option> 
                                        @foreach($leaveGroups as $group)
                                            <option value="{{ $group->id }}"
                                                {{ $group->id == 1 ? 'selected' : '' }}>
                                                {{ $group->group_name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>  

                              
                                <div class="col-md-5">
                                    <label class="form-label fw-bold small">Effective Date</label> 
                                    <input type="text"
                                        class="form-control"
                                        value="{{ old('effective_date', date('Y-m-d')) }}"
                                        name="effective_date"
                                        id="effective_date"
                                        placeholder="Effective Date"
                                        autocomplete="off">
                                </div>
                                
                            </div>
                            <div class="mt-4">
                                <button type="submit" class="btn btn-primary w-100">Apply Leave Balance</button>
                            </div> 
                        </div>

                        <div class="col-md-7">
                            <div class="table-responsive">
                                <h4>Employee Current Leave Balance</h4>
                                <table class="table table-sm table-bordered text-center">
                                    <thead class="bg-light"> 
                                        <tr>
                                            <th>Leave Type</th>
                                            <th>Allowed Balance</th>
                                            <th>Leave Balance</th>
                                            <th>Excess Balance</th>
                                        </tr>
                                    </thead>
                                    <tbody id="balance_body">
                                        <tr>
                                            <td colspan="4" class="text-muted">Select Leave Group to view balance</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <div class="mt-5">
                        <h6 class="fw-bold text-secondary mb-3">Leave Group: <span id="leave-group"></span></h6>
                        <div class="table-responsive">
                            <table class="table table-sm table-striped border text-center align-middle">
                                <thead class="table-dark">
                                    <tr>
                                        <th>Leave Type</th>
                                        <th>Groupwise Bal.</th>
                                        <th>Remaining Bal.</th>
                                        <th>Bal. Forwarded</th>
                                        <th>Max. F. Bal.</th>
                                        <th>Continuous</th>
                                        <th>Cont. Sanction</th>
                                        <th>Half Day</th>
                                        <th>Max. Sanc. Year</th>
                                    </tr>
                                </thead>
                                <tbody id="rules_body">
                                    <tr>
                                        <td colspan="9" class="text-muted">No group data available</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div style="display:none"> 
        @foreach($leaveGroups as $group)
            <template id="group_bal_rows_{{ $group->id }}">
                @foreach($group->leaveTypes as $type)
                    <tr>
                        <td class="text-start ps-3">{{ $type->leave_type_name ?? $type->name ?? 'Leave' }}</td>
                        <td>{{ number_format($type->pivot->allowed_balance ?? 0, 0) }}</td>
                        <td>{{ number_format($type->pivot->allowed_balance ?? 0, 0) }}</td>
                        <td>0</td>
                    </tr>
                @endforeach
            </template>

            <template id="group_rule_rows_{{ $group->id }}"> 
                @foreach($group->leaveTypes as $type)
                    @php $p = $type->pivot; @endphp
                    <tr>
                        <td class="d-none">  
                            <input type='hidden' name="leave_type[]" id="leave_type" value='{{ $p->leave_type_id  }}'  >
                            <input type='hidden' name="groupwise_balance[]"  value='{{ $p->allowed_balance  }}'>
                            <input type='hidden' name="remaining_balance[]"  id="remaining_balance" value='0' >
                            <input type='hidden' name="balance_forwarded[]" value='{{ $p->max_forward_from_previous_year  }}'>
                            <input type='hidden' name="max_forward_balance[]"  value='{{ $p->max_forward_from_previous_year  }}' >
                            <input type='hidden' name="continuous[]"  value='{{ $p->continuous_days_allow  }}'>
                            <input type='hidden' name="continuous_sanction[]"  value='{{ $p->continuous_sanction  }}' >
                            <input type='hidden' name="half_day[]" value='{{ $p->is_half_day  }}' >
                            <input type='hidden' name="max_sanction_per_year[]" value='{{ number_format($p->max_leave_balance_in_year, 0, '', '') }}' > 
                        </td> 
                        <td> 
                            {{ $type->leave_type_name ?? $type->name }}
                        </td>
                        <td class="rule-allowed">{{ number_format($p->allowed_balance ?? 0, 0) }}</td>
                        <td class="dynamic-remaining">0</td>
                 
                        
                        {{-- balance forwared --}}
                        <td class="fw-bold">
                            @if($p->max_forward_from_previous_year > 0)
                                <span class="text-success" style="font-size: 1.2rem;">✔</span>
                            @else
                                <span class="text-mute" style="font-size: 1.2rem;">✖</span>
                            @endif
                        </td>

                        <td>{{ number_format($p->max_forward_from_previous_year ?? 0, 0) }}</td>

                        

                        {{-- Continuous Column with Color --}}
                        <td class="fw-bold">
                            @if($p->continuous_days_allow == 1)
                                <span class="text-success" style="font-size: 1.2rem;">✔</span>
                            @else
                                <span class="text-danger" style="font-size: 1.2rem;">✖</span>
                            @endif
                        </td>

                        <td>{{ $p->continuous_sanction ?? 0 }}</td>

                        {{-- Half Day Column with Color --}}
                        <td class="fw-bold">
                            @if($p->is_half_day == 1)
                                <span class="text-success" style="font-size: 1.2rem;">✔</span>
                            @else
                                <span class="text-danger" style="font-size: 1.2rem;">✖</span>
                            @endif
                        </td>

                        <td>{{ number_format($p->max_leave_balance_in_year ?? 0, 0) }}</td>
                    </tr>
                @endforeach
            </template>
        @endforeach
    </div>
@endsection

@section('page_scripts')
    <script>

        $(document).ready(function () {
 
            
            // Employee TomSelect
            let employeeSelect = new TomSelect('#employee_id', {
                placeholder: 'Select Employee',
                create: false,
                searchField: ['text']
            });

            // Leave Group TomSelect
            let leaveGroupSelect = new TomSelect('#leave_group_id', {
                placeholder: 'Select Leave Group',
                create: false,
                searchField: ['text']
            });

            // Effective Date
            $('#effective_date').datepicker({
                dateFormat: "yy-mm-dd",
                autoclose: true,
                todayHighlight: true
            });

            employeeSelect.on('change', function (value) {

                if (!value) {
                    return;
                }

                let option = document.querySelector(
                    '#employee_id option[value="' + value + '"]'
                );

                let joiningDate = option ? option.getAttribute('data-joining') : '';

                console.log('Joining Date:', joiningDate);
            });
 
            
            // employee_id change event handler
            $('#employee_id').on('change', function () {

                let id = $(this).val();

                if (!id) {
                    $('#join_date').val('');

                    $('#balance_body').html(
                        '<tr><td colspan="4" class="text-muted">Select Employee to view balance</td></tr>'
                    );

                    $('#rules_body').html(
                        '<tr><td colspan="9" class="text-muted">No employee data available</td></tr>'
                    );

                    return;
                }

                // Get joining date from TomSelect
                let employeeData = employeeSelect.options[id];

                let joiningDate = employeeData
                    ? employeeData.joining
                    : '';

                $('#join_date').val(joiningDate);

                let groupId = $('#leave_group_id').val();

                $("#leave-group").html(
                    leaveGroupSelect.options[leaveGroupSelect.getValue()]?.text || ''
                );

                $.ajax({
                    url: `{{ route('hrm.leave-statuses.get-balance') }}`,
                    type: 'GET',
                    data: {
                        employee_id: id
                    },
                    dataType: 'json',

                    beforeSend: function () {

                        $('#balance_body').html(
                            '<tr><td colspan="4">Loading...</td></tr>'
                        );

                        $('#rules_body').html(
                            '<tr><td colspan="9">Loading...</td></tr>'
                        );
                    },

                    success: function (response) {

                        console.log('Employee Leave Balance:', response);

                        /*
                        |--------------------------------------------------------------------------
                        | Existing Saved Leave Status Found
                        |--------------------------------------------------------------------------
                        */

                        if (response && response.length > 0) {

                            // Get saved Leave Group
                            let savedGroupId = response[0].leave_group_id;

                            if (savedGroupId) {
                                leaveGroupSelect.setValue(savedGroupId);

                                $("#leave-group").html(
                                    leaveGroupSelect.options[savedGroupId]?.text || ''
                                );
                            }

                            // Get saved Effective Date
                            let savedEffectiveDate = response[0].effective_date;

                            if (savedEffectiveDate) {
                                $('#effective_date').val(savedEffectiveDate);
                            }

                            
                            let balanceRows = '';
                            let ruleRows = '';

                            $.each(response, function (index, leave) {

                                let leaveTypeName =
                                    leave.leave_type?.leave_type_name ?? 'Leave';

                                let groupwiseBalance =
                                    parseFloat(leave.groupwise_balance) || 0;

                                let remainingBalance =
                                    parseFloat(leave.remaining_balance) || 0;

                                /*
                                |--------------------------------------------------------------------------
                                | Current Balance Table
                                |--------------------------------------------------------------------------
                                */

                                balanceRows += '<tr>';

                                balanceRows +=
                                    '<td class="text-start ps-3">' +
                                    leaveTypeName +
                                    '</td>';

                                balanceRows +=
                                    '<td>' +
                                    groupwiseBalance +
                                    '</td>';

                                balanceRows +=
                                    '<td>' +
                                    remainingBalance +
                                    '</td>';

                                let excessBalance =
                                    remainingBalance > groupwiseBalance
                                        ? remainingBalance - groupwiseBalance
                                        : 0;

                                balanceRows +=
                                    '<td>' +
                                    excessBalance +
                                    '</td>';

                                balanceRows += '</tr>';


                                /*
                                |--------------------------------------------------------------------------
                                | Rules Table
                                |--------------------------------------------------------------------------
                                */

                                ruleRows += '<tr>';

                                ruleRows += '<td class="d-none">';

                                ruleRows +=
                                    '<input type="hidden" name="leave_type[]" value="' +
                                    leave.leave_type.id +
                                    '">';

                                ruleRows +=
                                    '<input type="hidden" name="groupwise_balance[]" value="' +
                                    groupwiseBalance +
                                    '">';

                                ruleRows +=
                                    '<input type="hidden" name="remaining_balance[]" value="' +
                                    remainingBalance +
                                    '">';

                                ruleRows +=
                                    '<input type="hidden" name="balance_forwarded[]" value="' +
                                    (leave.balance_forwarded ?? 0) +
                                    '">';

                                ruleRows +=
                                    '<input type="hidden" name="max_forward_balance[]" value="' +
                                    (leave.max_forward_balance ?? 0) +
                                    '">';

                                ruleRows +=
                                    '<input type="hidden" name="continuous[]" value="' +
                                    (leave.continuous ?? 0) +
                                    '">';

                                ruleRows +=
                                    '<input type="hidden" name="continuous_sanction[]" value="' +
                                    (leave.continuous_sanction ?? 0) +
                                    '">';

                                ruleRows +=
                                    '<input type="hidden" name="half_day[]" value="' +
                                    (leave.half_day ?? 0) +
                                    '">';

                                ruleRows +=
                                    '<input type="hidden" name="max_sanction_per_year[]" value="' +
                                    (leave.max_sanction_per_year ?? 0) +
                                    '">';

                                ruleRows += '</td>';

                                ruleRows +=
                                    '<td class="text-start ps-3">' +
                                    leaveTypeName +
                                    '</td>';

                                ruleRows +=
                                    '<td class="rule-allowed">' +
                                    groupwiseBalance +
                                    '</td>';

                                ruleRows +=
                                    '<td class="dynamic-remaining">' +
                                    remainingBalance +
                                    '</td>';


                                // Balance Forwarded
                                if (parseFloat(leave.balance_forwarded) > 0) {

                                    ruleRows +=
                                        '<td class="fw-bold">' +
                                        '<span class="text-success" style="font-size:1.2rem;">✔</span>' +
                                        '</td>';

                                } else {

                                    ruleRows +=
                                        '<td class="fw-bold">' +
                                        '<span class="text-danger" style="font-size:1.2rem;">✖</span>' +
                                        '</td>';
                                }


                                // Max Forward Balance
                                ruleRows +=
                                    '<td>' +
                                    (leave.max_forward_balance ?? 0) +
                                    '</td>';


                                // Continuous
                                if (parseInt(leave.continuous) === 1) {

                                    ruleRows +=
                                        '<td class="fw-bold">' +
                                        '<span class="text-success" style="font-size:1.2rem;">✔</span>' +
                                        '</td>';

                                } else {

                                    ruleRows +=
                                        '<td class="fw-bold">' +
                                        '<span class="text-danger" style="font-size:1.2rem;">✖</span>' +
                                        '</td>';
                                }


                                // Continuous Sanction
                                ruleRows +=
                                    '<td>' +
                                    (leave.continuous_sanction ?? 0) +
                                    '</td>';


                                // Half Day
                                if (parseInt(leave.half_day) === 1) {

                                    ruleRows +=
                                        '<td class="fw-bold">' +
                                        '<span class="text-success" style="font-size:1.2rem;">✔</span>' +
                                        '</td>';

                                } else {

                                    ruleRows +=
                                        '<td class="fw-bold">' +
                                        '<span class="text-danger" style="font-size:1.2rem;">✖</span>' +
                                        '</td>';
                                }


                                // Max Sanction Per Year
                                ruleRows +=
                                    '<td>' +
                                    (leave.max_sanction_per_year ?? 0) +
                                    '</td>';

                                ruleRows += '</tr>';
                            });


                            $('#balance_body').html(balanceRows);
                            $('#rules_body').html(ruleRows);
                            updateRemainingBalances();

                            /*
                            IMPORTANT:
                            Existing saved balance হলে এখানে
                            updateRemainingBalances() call করবেন না।

                            কারণ সেটা saved remaining_balance overwrite করে ফেলবে।
                            */

                            return;
                        }


                        /*
                        |--------------------------------------------------------------------------
                        | No Saved Data → Load Leave Group Default Data
                        |--------------------------------------------------------------------------
                        */

                        if (!groupId) {

                            $('#balance_body').html(
                                '<tr><td colspan="4" class="text-muted">' +
                                'Select Leave Group to view balance' +
                                '</td></tr>'
                            );

                            $('#rules_body').html(
                                '<tr><td colspan="9" class="text-muted">' +
                                'No group data available' +
                                '</td></tr>'
                            );

                            return;
                        }

                        let balanceRows =
                            $('#group_bal_rows_' + groupId).html();

                        let ruleRows =
                            $('#group_rule_rows_' + groupId).html();

                        if (balanceRows) {

                            $('#balance_body').html(balanceRows);
                            $('#rules_body').html(ruleRows);

                            updateRemainingBalances();
                        }
                    },

                    error: function (xhr) {

                        console.log('Leave Balance Error:', xhr.responseText);

                        $('#balance_body').html(
                            '<tr><td colspan="4" class="text-danger">' +
                            'Unable to load leave balance' +
                            '</td></tr>'
                        );
                    }
                });
            });


            function updateRemainingBalances() {
                let effectiveDateVal = $('#effective_date').val();
                if (!effectiveDateVal) return; // return if effective date is not selected

                let date = new Date(effectiveDateVal);
                let currentMonth = date.getMonth();
                let monthsRemaining = 12 - currentMonth;

                $('#rules_body tr').each(function () {
                    let row = $(this);
                    let allowedBalance = parseFloat(row.find('.rule-allowed').text()) || 0;
                    if (allowedBalance > 0) {
                        let remaining = Math.round((allowedBalance / 12) * monthsRemaining);
                        row.find('input[name="remaining_balance[]"]').val(remaining);
                        row.find('.dynamic-remaining').text(remaining);
                    }
                });
            }
            // leave_group_id change event handler
            $('#leave_group_id').on('change', function () {
                let groupId = $(this).val();
                $("#leave-group").html(
                    leaveGroupSelect.options[leaveGroupSelect.getValue()]?.text || ''
                );
                if (!groupId) {
                    $('#balance_body').html('<tr><td colspan="4" class="text-muted">Select Leave Group to view balance</td></tr>');
                    $('#rules_body').html('<tr><td colspan="9" class="text-muted">No group data available</td></tr>');
                    return;
                }
                // balance and rule rows
                let balanceRows = $('#group_bal_rows_' + groupId).html();
                let ruleRows = $('#group_rule_rows_' + groupId).html();

                if (balanceRows) {
                    $('#balance_body').html(balanceRows);
                    $('#rules_body').html(ruleRows);
                    updateRemainingBalances();
                }
            });

            $('#effective_date').on('change', updateRemainingBalances); // Update remaining balances on effective date change
            
            let existingEmployeeId = employeeSelect.getValue();

            if (existingEmployeeId) {
                employeeSelect.trigger('change');
            }
        });
    </script>
@endsection