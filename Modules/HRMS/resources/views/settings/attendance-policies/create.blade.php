@section('title', 'Attendance Policy Create')
@section('description', 'Attendance Policy Create')
@extends('layout.app')
@section('content')
    <div class="container-fluid mt-4">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white py-3 d-flex justify-content-between">
                <h5 class="mb-0 fw-bold">New Attendance Policy</h5>
                <a href="{{ route('hrm.attendance-policies.index') }}" class="btn-close"></a>
            </div>
            <div class="card-body">
                <form action="{{ route('hrm.attendance-policies.store') }}" method="POST">
                    @csrf
                    <div class="row g-3">
                        <div class="col-md-3">
                            <label class="form-label small fw-bold">Policy Name <span class="text-danger">*</span></label>
                            <input type="text" name="name" class="form-control" placeholder="Type Name" required>
                        </div>
                     
                        <div class="col-md-3">
                            <label for="effective_from"
                                class="color-dark fs-14 fw-500 align-center">Effective from</label>
                            <input type="text" class="form-control flatdate"  value="{{ old('effective_from') }}"
                                name="effective_from" id="effective_from" placeholder="Date" required>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label small fw-bold">Working Hours <span class="text-danger">*</span></label>
                            <input type="text" id="working_hours" name="working_hours" class="form-control" placeholder="HH:mm">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label small fw-bold">In time <span class="text-danger">*</span></label>
                            <input type="time" id="in_time" name="in_time" class="form-control">
                        </div>

                        <div class="col-md-3">
                            <label class="form-label small fw-bold">Delay Buffer time</label>
                            <input type="text" id="delay_buffer" name="delay_buffer" class="form-control" placeholder="00:00">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label small fw-bold">Ex. Delay Buffer time</label>
                            <input type="text" id="ex_delay_buffer" name="ex_delay_buffer" class="form-control" placeholder="00:00">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label small fw-bold">Early Out Time</label>
                            <input type="time" id="early_out_time" name="early_out_time" class="form-control">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label small fw-bold">Break Time (In minutes)</label>
                            <input type="number" id="break_time" name="break_time" class="form-control" placeholder="0">
                        </div>
                    </div>

                    <div class="row mt-4 mb-4">
                        <div class="col-12 d-flex gap-4">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="ignore_ot_deduction" id="ot">
                                <label class="form-check-label small" for="ot">Ignore overtime and deduction
                                    calculations</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="exclude_from_reports" id="reports">
                                <label class="form-check-label small" for="reports">Exclude from attendance reports</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="discard_weekend" id="weekend">
                                <label class="form-check-label small" for="weekend">Discard Weekend Attendance</label>
                            </div>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-bordered align-middle">
                            <thead class="bg-light small">
                                <tr>
                                    <th>Day</th>
                                    <th>In time</th>
                                    <th>Working Hours</th>
                                    <th>Delay Buffer</th>
                                    <th>Ex. Delay Buffer</th>
                                    <th>Early Out Time</th>
                                    <th>Break Time</th>
                                    <th>Working Type</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach(['Saturday', 'Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday'] as $day)
                                    <tr>
                                        <td class="fw-bold small text-muted">{{ $day }}</td>
                                        <td><input type="time" name="days[{{ $day }}][in_time]"
                                                class="form-control form-control-sm in_time"></td>
                                        <td><input type="text" name="days[{{ $day }}][working_hours]"
                                                class="form-control form-control-sm working_hours" value="00:00"></td>
                                        <td><input type="number" name="days[{{ $day }}][delay_buffer]"
                                                class="form-control form-control-sm delay_buffer" value="0"></td>
                                        <td><input type="number" name="days[{{ $day }}][ex_delay_buffer]"
                                                class="form-control form-control-sm ex_delay_buffer" value="0"></td>
                                        <td><input type="time" name="days[{{ $day }}][early_out_time]"
                                                class="form-control form-control-sm early_out_time"></td>
                                        <td><input type="number" name="days[{{ $day }}][break_time]"
                                                class="form-control form-control-sm break_time" value="0"></td>
                                        <td>
                                            <select name="days[{{ $day }}][working_type]" class="form-select form-select-sm">
                                                <option value="Full Day">Full Day</option>
                                                <option value="Half Day">Half Day</option>
                                                <option value="Weekend">Weekend</option>
                                            </select>
                                        </td>
                                    </tr>  
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="text-end mt-4">
                        <button type="submit" class="btn btn-primary px-5">Save Policy</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

<!-- CONTENT AREA -->
@section('page_scripts')

    <script>
        $(document).ready(function(e) {
            $('#working_hours').on('focusout', function() {
                var value = $(this).val(); // input value
                $('.working_hours').val(value); // update span text
            });
            $('#in_time').on('focusout', function() {
                var value = $(this).val(); // input value
                $('.in_time').val(value); // update span text
            });
            $('#delay_buffer').on('focusout', function() {
                var value = $(this).val(); // input value
                $('.delay_buffer').val(value); // update span text
            });
            $('#ex_delay_buffer').on('focusout', function() {
                var value = $(this).val(); // input value
                $('.ex_delay_buffer').val(value); // update span text
            });
            $('#early_out_time').on('focusout', function() {
                var value = $(this).val(); // input value
                $('.early_out_time').val(value); // update span text
            });
            $('#break_time').on('focusout', function() {
                var value = $(this).val(); // input value
                $('.break_time').val(value); // update span text
            });
        });
 
    </script>
@endsection


 