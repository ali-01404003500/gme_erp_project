@extends('layout.app')

@section('content')
    <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center mt-4 mb-4">
            <h4 class="fw-bold text-secondary">Leave Group Management</h4>
            <button class="btn btn-primary shadow-sm px-4" data-bs-toggle="modal" data-bs-target="#leaveGroupModal"
                onclick="resetForm()">
                <i class="fas fa-plus-circle me-2"></i>Add Leave Group
            </button>
        </div>

        <div class="card border-0 shadow-sm mb-4">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th class="ps-4">Group Name</th>
                                <th class="text-center">Leave Type Count</th>
                                <th class="text-center">Total Leave</th>
                                <th class="text-center">Employee Count</th>
                                <th class="text-center pe-4">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($leaveGroups as $group) 
                                <tr class="">
                                    <td class="ps-4"><span class="fw-bold text-dark">{{ $group->group_name }}</span></td>
                                    <td class="text-center"><span
                                            class="badge bg-soft-info text-info">{{ $group->leaveTypes->count() }}</span>
                                    </td>
                                    <td class="text-center">{{ $group->leaveTypes->sum('pivot.allowed_balance') }}</td>
                                    <td class="text-center">0</td>
                                    {{-- <td class="text-center">{{ $group->employees_count ?? $group->employees->count() }}
                                    </td> --}}
                                    <td class="text-end pe-4">
                                        <div class="d-flex justify-content-end gap-2">
                                            <button class="btn btn-sm btn-outline-primary edit-group-btn"
                                                data-id="{{ $group->id }}" data-name="{{ $group->group_name }}"
                                                data-details="{{ json_encode($group->leaveTypes) }}">
                                                <i class="fas fa-edit"></i>
                                            </button>

                                            <form action="{{ route('hrm.settings.leave-groups.destroy', $group->id) }}"
                                                method="POST"
                                                onsubmit="return confirm('Are you sure you want to delete this group?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-outline-danger">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center py-5 text-muted">No data found.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        {{-- Modal section remains unchanged based on logic --}}
        <div class="modal fade" id="leaveGroupModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
                <div class="modal-content border-0 shadow-lg">
                    <div class="modal-header bg-white border-bottom py-3">
                        <h5 class="modal-title fw-bold" id="modalTitle">Add Leave Group</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <form id="leaveGroupForm" method="POST" action="{{ route('hrm.settings.leave-groups.store') }}">
                        @csrf
                        <input type="hidden" name="_method" id="formMethod" value="POST">

                        <div class="modal-body p-4 " style="max-height: 70vh; overflow-y: auto;">
                            <div class="row g-3 mb-4">
                                <div class="col-md-5">
                                    <label class="form-label fw-bold small">Leave Group <span
                                            class="text-danger">*</span></label>
                                    <input type="text" name="group_name" id="group_name" class="form-control"
                                        placeholder="Enter Group Name" required>
                                </div>
                                <div class="col-md-7">
                                    <label class="form-label fw-bold small">Available Leave Type <span
                                            class="text-danger">*</span></label>
                                    <select id="leave_type_select" class="form-select select2-professional" multiple>
                                        @foreach($leaveTypes as $type)
                                            <option value="{{ $type->id }}">{{ $type->leave_type_name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div id="config-container"></div>
                        </div>
                        <div class="modal-footer border-top bg-white">
                            <button type="button" class="btn btn-light px-4" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-primary px-5 fw-bold shadow">Save</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    {{-- Scripts section remains unchanged --}}
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function () {  
            $('#leave_type_select').on('change', function () {
                let selected = $(this).val() || [];
                $('.config-card').each(function () {
                    let id = $(this).data('id').toString();
                    if (!selected.includes(id)) { $(this).remove(); }
                });
                selected.forEach(function (id) {
                    if ($('#card-' + id).length === 0) {
                        let name = $("#leave_type_select option[value='" + id + "']").text();
                        createCard(id, name);
                    }
                });
            });

            $(document).on('click', '.edit-group-btn', function () {
                let id = $(this).data('id');
                let name = $(this).data('name');
                let details = $(this).data('details');

                resetForm();
                $('#modalTitle').text('Edit Leave Group');
                $('#group_name').val(name);
                $('#formMethod').val('PUT');
                $('#leaveGroupForm').attr('action', '/hrm/settings/leave-groups/' + id);

                let selectedIds = details.map(d => d.id.toString());
                $('#leave_type_select').val(selectedIds).trigger('change');

                details.forEach(d => {
                    setTimeout(() => { fillCardData(d.id, d.pivot); }, 200);
                });
                $('#leaveGroupModal').modal('show');
            });
        });

        function createCard(id, name, data = null) {
            let pivot = data ? (data.pivot || data) : {};
            // console.log(data);
            let html = `
                                <div class="card border-0 shadow-sm mb-4 config-card" id="card-${id}" data-id="${id}">
                                    <div class="card-header bg-white border-bottom py-3 d-flex justify-content-between align-items-center">
                                        <span class="fw-bold text-dark">
                                            <i class="fas fa-trash text-danger me-2 cursor-pointer" onclick="removeCard(${id})"></i>
                                            Leave Type: <strong>${name}</strong>
                                        </span>
                                        <div class="d-flex gap-4">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" name="configs[${id}][is_balance_forward]" value="1">
                                                <label class="form-check-label small fw-bold">Balance Forward</label>
                                            </div>
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" name="configs[${id}][allow_leave_encashment]" value="1">
                                                <label class="form-check-label small fw-bold">Allow Leave Encashment</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card-body bg-white">
                                        <div class="row g-3">
                                            <div class="col-md-4">
                                                <div class="form-check mb-2"><input class="form-check-input" type="checkbox" name="configs[${id}][balance_forwarding_on_group_change]" value="1"><label class="small text-muted">Balance Forwarding On Change</label></div>
                                                <div class="form-check mb-2"><input class="form-check-input" type="checkbox" name="configs[${id}][leave_allow_between_multiple_years]" value="1"><label class="small text-muted">Allow Between Multiple Years</label></div>
                                                <div class="d-flex gap-3 mb-2">
                                                    <div class="form-check"><input class="form-check-input" type="checkbox" name="configs[${id}][negative_balance]" value="1"><label class="small text-muted">Negative Balance</label></div>
                                                    <div class="form-check"><input class="form-check-input" type="checkbox" name="configs[${id}][is_half_day]" value="1"><label class="small text-muted">Half Day</label></div>
                                                </div>
                                                <div class="form-check mb-2"><input class="form-check-input" type="checkbox" name="configs[${id}][continuous_days_allow]" value="1"><label class="small text-muted">Continuous Days Allow</label></div>
                                                <div class="form-check mb-2"><input class="form-check-input" type="checkbox" name="configs[${id}][requires_leave_attachment]" value="1"><label class="small text-muted">Requires Attachment</label></div>
                                                <div class="form-check"><input class="form-check-input" type="checkbox" name="configs[${id}][allow_earn_leave]" value="1"><label class="small text-muted">Allow Earn Leave</label></div>
                                            </div>
                                            <div class="col-md-4 border-start">
                                                <div class="mb-2"><label class="small fw-bold">Allowed Balance *</label><input type="number" step="0.1" name="configs[${id}][allowed_balance]" class="form-control" value="0"></div>
                                                <div class="mb-2"><label class="small fw-bold">Continuous Sanction *</label><input type="number" name="configs[${id}][continuous_sanction]" class="form-control" value="0"></div>
                                                <div class="mb-2"><label class="small fw-bold">Max Sanction in Service Life *</label><input type="number" name="configs[${id}][max_sanction_in_service_life]" class="form-control" value="0"></div>
                                            </div>
                                            <div class="col-md-4 border-start">
                                                <div class="mb-2"><label class="small fw-bold">Max Leave Balance (Year) *</label><input type="number" step="0.1" name="configs[${id}][max_leave_balance_in_year]" class="form-control" value="0"></div>
                                                <div class="mb-2"><label class="small fw-bold">Max Forward Prev. Year *</label><input type="number" step="0.1" name="configs[${id}][max_forward_from_previous_year]" class="form-control" value="0"></div>
                                                <div class="mb-2"><label class="small fw-bold">Max Balance for Encashment</label><input type="number" step="0.1" name="configs[${id}][max_balance_for_encashment]" class="form-control" value="0"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>`;
            $("#config-container").append(html);
        }

        function fillCardData(id, pivot) { 
            let card = $(`#card-${id}`);
            if (pivot.balance_forwarding_on_group_change == 1) card.find(`input[name="configs[${id}][balance_forwarding_on_group_change]"]`).prop('checked', true);
            if (pivot.leave_allow_between_multiple_years == 1) card.find(`input[name="configs[${id}][leave_allow_between_multiple_years]"]`).prop('checked', true);
             if (pivot.is_half_day == 1) card.find(`input[name="configs[${id}][is_half_day]"]`).prop('checked', true);
            if (pivot.negative_balance == 1) card.find(`input[name="configs[${id}][negative_balance]"]`).prop('checked', true);
             if (pivot.continuous_days_allow == 1) card.find(`input[name="configs[${id}][continuous_days_allow]"]`).prop('checked', true);
            if (pivot.requires_leave_attachment == 1) card.find(`input[name="configs[${id}][requires_leave_attachment]"]`).prop('checked', true);
            if (pivot.allow_earn_leave == 1) card.find(`input[name="configs[${id}][allow_earn_leave]"]`).prop('checked', true); 
            if (pivot.is_balance_forward == 1) card.find(`input[name="configs[${id}][is_balance_forward]"]`).prop('checked', true);
            if (pivot.allow_leave_encashment == 1) card.find(`input[name="configs[${id}][allow_leave_encashment]"]`).prop('checked', true); 

  

            card.find(`input[name="configs[${id}][allowed_balance]"]`).val(pivot.allowed_balance);
            card.find(`input[name="configs[${id}][continuous_sanction]"]`).val(pivot.continuous_sanction);
            card.find(`input[name="configs[${id}][max_sanction_in_service_life]"]`).val(pivot.max_sanction_in_service_life);
            card.find(`input[name="configs[${id}][max_leave_balance_in_year]"]`).val(pivot.max_leave_balance_in_year);
            card.find(`input[name="configs[${id}][max_forward_from_previous_year]"]`).val(pivot.max_forward_from_previous_year);
            card.find(`input[name="configs[${id}][max_balance_for_encashment]"]`).val(pivot.max_balance_for_encashment);
        }

        function removeCard(id) {
            $("#card-" + id).remove();
            let select = $("#leave_type_select");
            let values = select.val() || [];
            values = values.filter(v => v != id.toString());
            select.val(values).trigger('change');
        }

        function resetForm() {
            $("#config-container").html("");
            $("#leave_type_select").val([]).trigger('change');
            $('#group_name').val('');
            $('#formMethod').val('POST');
            $('#modalTitle').text('Add Leave Group');
            $('#leaveGroupForm').attr('action', "{{ route('hrm.settings.leave-groups.store') }}");
        }
    </script>

    <style>
        .modal-dialog-scrollable .modal-body {
            scrollbar-width: thin;
            scrollbar-color: #5e72e4 #f1f1f1;
        }

        .bg-soft-info {
            background-color: #e0f7fa;
        }

        .text-info {
            color: #00bcd4 !important;
        }

        .config-card {
            border-radius: 10px;
            border: 1px solid #eee !important;
            transition: all 0.2s;
        }

        .cursor-pointer {
            cursor: pointer;
        }
    </style>
@endsection