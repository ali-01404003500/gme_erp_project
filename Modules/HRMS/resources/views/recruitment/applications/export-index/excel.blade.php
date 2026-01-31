<table class="table dt-table-hover" style="width:100%">
    <thead>
        <tr>
            <td colspan="8" style="font-family: 'Arial Black';text-align: center; font-size: 36px">
                {{ $company_info->company_name ?? 'All Branch' }}
            </td>
        </tr>
        <tr>
            <td colspan="8" style="font-family: 'Arial Black';text-align: center; font-size: 28px">
                {{ $department ?? '' }}
            </td>
        </tr>
        <tr>
            <td colspan="8" style="font-family: 'Arial Black';text-align: center; font-size: 24px">Attendance Report</td>
        </tr>
        <tr>
            <td colspan="8" style="font-family: 'Arial Black';text-align: center">{{ request('date', now()) }}
            </td>
        </tr>
        <tr>
            <th class="text-center" style="width: 8%">Sl</th>
            <th class="text-center">Employee</th>
            <th class="text-center">Date</th>
            <th class="text-center">Check-In</th>
            <th class="text-center">Check-Out</th>
            <th class="text-center">Shift</th>
            <th class="text-center">Work Duration</th>
            <th class="text-center">Attended Type</th>
        </tr>
    </thead>

    <tbody>
        @php
            $shift = \Modules\HRMS\Models\Settings\Shift::where('id', 10000)->first()
        @endphp

        @foreach ($attendances as $value)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>
                {{ $value->employee->full_name }}
                </td>
                <td>{{ $value->date }}</td>
                <td>
                    @if ($value->check_in_date && $value->check_in_time)
                        {{ date('M. d, Y, g:i A', strtotime($value->check_in_date . ' ' . $value->check_in_time)) }}</td>
                    @else
                        N/A
                    @endif
                <td>
                    @if($value->check_out_date && $value->check_out_time)
                        {{ date('M. d, Y, g:i A', strtotime($value->check_out_date . ' ' . $value->check_out_time)) }}
                    @else
                        N/A
                    @endif
                </td>
                <td>
                    @if($value->shift)
                    {{ @$value->shift->shift_name }} ({{ date('h:i A', strtotime(@$value->shift->in_time)) }}-{{ date('h:i A', strtotime(@$value->shift->out_time)) }})
                    @else
                     {{ $shift->shift_name }} ({{ date('h:i A', strtotime($shift->in_time)) }}-{{ date('h:i A', strtotime($shift->out_time)) }})
                    @endif
                </td>
                @php
                    $work_duration = 'N/A';
                    $late = 0;

                    if($value->check_in_time && $value->check_out_time){
                        $checkIn = \Carbon\Carbon::parse($value->check_in_time);
                        $checkOut = \Carbon\Carbon::parse($value->check_out_time);
                        $work_duration_hours = $checkIn->diffInHours($checkOut);
                        $work_duration_minutes = $checkIn->diffInMinutes($checkOut) % 60;
                        $work_duration = $work_duration_hours . ' Hours ' . $work_duration_minutes . ' Minutes';

                        $shiftInTime = \Carbon\Carbon::parse($value->shift->in_time ?? $shift->in_time);
                        $graceTime = $value->shift->grace_time ?? $shift->grace_time;
                        $difference = $checkIn->diffInMinutes($shiftInTime);

                        $late = max(0, $difference - $graceTime);
                    }
                @endphp
                <td> {{$work_duration}} </td> 

                <td>
                    @if($late > 0) 
                    <span class="badge badge-round badge-warning">
                        Late 
                    </span>
                    @elseif ($value->attendance_type == 'Present')
                    <span class="badge badge-round badge-success">
                        {{ $value->attendance_type }}   
                    </span>
                    @elseif ($value->attendance_type == 'Absent')
                    <span class="badge badge-round badge-danger">
                        {{ $value->attendance_type }}
                    </span>
                    @elseif ($value->attendance_type == 'Holiday')
                    <span class="badge badge-round badge-info">
                        {{ $value->attendance_type }}
                    </span>
                    @elseif ($value->attendance_type == 'Leave')
                    <span class="badge badge-round badge-primary">
                        {{ $value->attendance_type }}
                    </span>
                    @endif
                </td>
                     
               
            </tr>
        @endforeach
    </tbody>
</table>
