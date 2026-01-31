<table>
    @php
    $groupedData = $attendanceReports->groupBy([
        fn ($item) => $item->employee->employementDetail->branch->name ?? 'N/A',
        fn ($item) => $item->employee->employementDetail->department->name ?? 'N/A'
    ]);
    @endphp

    @foreach($groupedData as $branchName => $departments)
        <tr><td colspan="10">Branch Name: {{ $branchName }} ({{ request('date') ?? \Carbon\Carbon::now()->format('d-m-Y') }})</td></tr>

        @foreach($departments as $departmentName => $attendances)
            <tr><td colspan="10">Department Name: {{ $departmentName }}</td></tr>

            <tr>
                <th>SL</th>
                <th>Emp ID</th>
                <th>Employee Name</th>
                <th>Designation</th>
                <th>In</th>
                <th>Out</th>
                <th>Late</th>
                <th>Status</th>
                <th>Entry By</th>
                <th>Remarks</th>
            </tr>

            @foreach($attendances as $key => $attendance)
                @php
                    $shift = $attendance->shift ?? \Modules\HRMS\Models\Settings\Shift::find(10000);
                    $work_duration = 'N/A';
                    $late = 0;

                    if($attendance->check_in_time && $attendance->check_out_time){
                        $checkIn = \Carbon\Carbon::parse($attendance->check_in_time);
                        $checkOut = \Carbon\Carbon::parse($attendance->check_out_time);
                        $late = max(0, $checkIn->diffInMinutes(\Carbon\Carbon::parse($shift->in_time)) - ($shift->grace_time ?? 0));
                    }
                @endphp
                <tr>
                    <td>{{ $key + 1 }}</td>
                    <td>{{ $attendance->employee->employementDetail->card_no }}</td>
                    <td>{{ $attendance->employee->full_name }}</td>
                    <td>{{ $attendance->employee->employementDetail->designation->name ?? '' }}</td>
                    <td>{{ $attendance->check_in_time ? \Carbon\Carbon::parse($attendance->check_in_time)->format('M d, Y, g:i A') : 'N/A' }}</td>
                    <td>{{ $attendance->check_out_time ? \Carbon\Carbon::parse($attendance->check_out_time)->format('M d, Y, g:i A') : 'N/A' }}</td>
                    <td>{{ $late }}</td>
                    <td>{{ $attendance->attendance_type }}</td>
                    <td>{{ $attendance->entryBy->name ?? '' }}</td>
                    <td>{{ $attendance->remarks }}</td>
                </tr>
            @endforeach

            <tr>
                <td colspan="10" style="text-align: right;">
                    Department Total: {{ count($attendances) }}
                </td>
            </tr>

        @endforeach

       <tr>
            <td colspan="10" style="text-align: right;">
                    Branch Total: {{ $departments->flatten()->count() }}
                </td>
            </tr>
            <tr>
        </tr>
    @endforeach
</table>
