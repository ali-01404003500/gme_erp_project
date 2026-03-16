
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
                <form action="{{ route('hrm.settings.leave-statuses.store') }}" method="POST">
                @csrf 
                    <div class="row">
                        <div class="col-md-5"> 
                            <input type="hidden" name="type" value="employee_wise">
                            <input type="hidden" name="leave_year_id" value="{{ $leaveYearId }}">
                            <div class="row g-3">
                                <div class="col-md-7">
                                    <label class="form-label fw-bold small">Employee Name</label>
                                    <select id="employee_id"  name="employee_id" class="form-select select2-employee">
                                        <option value="">Select Employee</option>
                                        @foreach($employees as $emp)
                                            <option value="{{ $emp->id }}" data-joining="{{ $emp->employementDetail->date_of_joining }}" >{{ $emp->full_name }} </option>
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
                                    <select name="leave_group_id" id="leave_group_id"
                                        class="form-select select2-searchable"> 
                                        @foreach($leaveGroups as $group)
                                            <option value="{{ $group->id }}" {{ $group->id == 1 ? 'selected' : '' }} >{{ $group->group_name }}</option>
                                        @endforeach
                                    </select>
                                </div>  

                                <div class="col-md-5">
                                    <label class="form-label fw-bold small">Effective Date</label>
                                    <input type="text"
                                        class="form-control flatdate"
                                        value="{{ old('effective_date', date('Y-m-d')) }}" name="effective_date" id="effective_date" placeholder="Effective Date">
                                
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

            
            // employee_id change event handler
            $('#employee_id').on('change', function () {
                var id = $('#employee_id').val();
                let joiningDate = $(this).find('option:selected').data('joining');
                $('#join_date').val(joiningDate).trigger('change');

                $("#leave-group").html($("#leave_group_id option:selected").text());
                let groupId = $("#leave_group_id").val();

                $.ajax({
 
                    url: `{{ route('hrm.settings.leave-statuses.get-balance') }}`, 
                    data: {
                        employee_id: id
                    },
                    type: 'GET',
                    dataType: 'json',
                    success: function(response) { 
                        //console.log(response);
                        if (response != '') { 
                            $('#balance_body, #rules_body').html('');
                            let balanceRows = ruleRows = "";
                            $.each(response, function(index, leave) {
                                balanceRows += '<tr>';
                                balanceRows += '<td class="text-start ps-3">'+leave.leave_type.leave_type_name+'</td>';
                                balanceRows += '<td>'+leave.groupwise_balance+'</td>';
                                balanceRows += '<td>'+leave.groupwise_balance+'</td>';
                                balanceRows += '<td>0</td>';
                                balanceRows += '</tr>';
                            }); 
                            $('#balance_body').html(balanceRows);
 

                            $.each(response, function(index, leave) {
 
                                ruleRows += '<tr>';
                                ruleRows +='<td class="d-none">'; 
                                    ruleRows += '<input type="hidden" name="leave_type[]" id="leave_type" value="'+leave.leave_type.id+'"  >';
                                    ruleRows += '<input type="hidden" name="groupwise_balance[]"  value="'+leave.groupwise_balance+'" >';
                                    ruleRows += '<input type="hidden" name="remaining_balance[]"  id="remaining_balance" value="'+leave.remaining_balance+'" >';
                                    ruleRows += '<input type="hidden" name="balance_forwarded[]" value="'+leave.balance_forwarded+'">';
                                    ruleRows += '<input type="hidden" name="max_forward_balance[]"  value="'+leave.max_forward_balance+'" >';
                                    ruleRows += '<input type="hidden" name="continuous[]"  value="'+leave.continuous+'" >';
                                    ruleRows += '<input type="hidden" name="continuous_sanction[]"  value="'+leave.continuous_sanction+'">';
                                    ruleRows += '<input type="hidden" name="half_day[]" value="'+leave.half_day+'" >';
                                    ruleRows += '<input type="hidden" name="max_sanction_per_year[]" value="'+leave.max_sanction_per_year+'" > ';
                                ruleRows += '</td>';

                                ruleRows += '<td class="text-start ps-3">'+leave.leave_type.leave_type_name+'</td>';
                                ruleRows += '<td class="rule-allowed" >'+leave.groupwise_balance+'</td>';
                                ruleRows += '<td class="dynamic-remaining">'+leave.remaining_balance+'</td>';
                                
                                if(leave.balance_forwarded>0)
                                    ruleRows +='<td><span class="text-success" style="font-size: 1.2rem;">✔</span></td>';
                                else 
                                    ruleRows +='<td><span class="text-mute" style="font-size: 1.2rem;">✖</span></td>';

                                ruleRows += '<td>'+leave.max_forward_balance+'</td>';
                                
                                if(leave.continuous == 1)
                                    ruleRows +='<td><span class="text-success" style="font-size: 1.2rem;">✔</span></td>';
                                else 
                                    ruleRows +='<td><span class="text-danger" style="font-size: 1.2rem;">✖</span></td>';
 
                                ruleRows += '<td>'+leave.continuous_sanction+'</td>';

                                if(leave.half_day == 1)
                                    ruleRows +='<td><span class="text-success" style="font-size: 1.2rem;">✔</span></td>';
                                else 
                                    ruleRows +='<td><span class="text-danger" style="font-size: 1.2rem;">✖</span></td>';
  
                                ruleRows += '<td>'+leave.max_sanction_per_year+'</td>';
                                ruleRows += '</tr>';
                            }); 
                            $('#rules_body').html(ruleRows);

                            updateRemainingBalances(); 
 
                        }
                        else
                        {
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
                        }
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
                        row.find("#remaining_balance").val(remaining);
                        row.find('.dynamic-remaining').text(remaining);
                    }
                });
            }
            // leave_group_id change event handler
            $('#leave_group_id').on('change', function () {
                let groupId = $(this).val();
                $("#leave-group").html($("#leave_group_id option:selected").text());
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
        });
    </script>
@endsection