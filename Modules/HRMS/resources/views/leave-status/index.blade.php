@extends('layout.app')

@section('content')
    <div class="container-fluid">
        <div class="card mb-4 border-0 shadow-sm mt-4">
            <div class="card-body">
                <h4 class="mb-0 fw-bold text-secondary">Leave Status Management</h4>
            </div>
        </div>

        <div class="card border-0 shadow-sm">
            <div class="card-body p-4">
                <div class="row">
                    <div class="col-md-5">
                        <form action="{{ route('hrm.settings.leave-statuses.store') }}" method="POST">
                            @csrf
                            <input type="hidden" name="type" value="employee_wise">
                            <div class="row g-3">
                                <div class="col-md-12">
                                    <label class="form-label fw-bold small">Employee Name</label>
                                    <select name="employee_id" class="form-select select2-employee">
                                        <option value="">Select Employee...</option>
                                        @foreach($employees as $emp)
                                            <option value="{{ $emp->id }}">[{{ $emp->account_number }}] {{ $emp->full_name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-md-12">
                                    <label class="form-label fw-bold small">Join Date</label>
                                    <input type="date" name="join_date" class="form-control">
                                </div>

                                <div class="col-md-12">
                                    <label class="form-label fw-bold small">Leave Group</label>
                                    <select name="leave_group_id" id="leave_group_id"
                                        class="form-select select2-searchable">
                                        <option value="">Select Leave Group</option>
                                        @foreach($leaveGroups as $group)
                                            <option value="{{ $group->id }}">{{ $group->group_name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-12">
                                    <label class="form-label fw-bold small">Effective Date</label>
                                    <input type="date" name="effective_date" id="effective_date" class="form-control"
                                        value="{{ date('Y-m-d') }}">
                                </div>
                            </div>
                            <div class="mt-4">
                                <button type="submit" class="btn btn-primary w-100">Apply Leave Balance</button>
                            </div>
                        </form>
                    </div>

                    <div class="col-md-7">
                        <div class="table-responsive">
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
                    <h6 class="fw-bold text-secondary mb-3">Leave Group Rules Details</h6>
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
                        <td>{{ $type->leave_type_name ?? $type->name }}</td>
                        <td class="rule-allowed">{{ number_format($p->allowed_balance ?? 0, 0) }}</td>
                        <td class="dynamic-remaining">0</td>
                        <td>0</td>
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
                        row.find('.dynamic-remaining').text(remaining);
                    }
                });
            }
            // leave_group_id change event handler
            $('#leave_group_id').on('change', function () {
                let groupId = $(this).val();
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