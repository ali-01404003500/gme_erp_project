<style>
    .my-header {
      display: flex;
      align-items: center;
      justify-content: center;
      text-align: center;
  }
  .my-header img {
      max-width: 100px;
      margin-right: 20px;
  }

  .my-header h1 {
      margin: 0;
      font-size: 50px;
      font-weight: bold;
      color: rgb(0, 0, 187);
  }

  .my-header p {
      margin: 5px 0;
      font-size: 12px;
  }

  .title {
      text-align: center;
      margin-bottom: 20px;
  }

  .title h2 {
      margin: 0;
      font-size: 20px;
      text-decoration: underline;
  }
  table.table {
      width: 100%;
      border-collapse: collapse;
  }
  table.table th, table.table td {
      text-align: center; /* Centers text horizontally */
      vertical-align: middle; /* Centers text vertically */
      padding: 10px; /* Optional: Adds some spacing for better readability */
  }

</style>
<div class="row" style="font-size: 12px!important;">
  <div class="col-md-12 m-2">
      <x-error-alart />
  </div>
  <div class="col-md-12">
      <div class="card mb-4">
          <div class="card-body">
              
              <header class="my-header">
                  @include('partials._for_pdf_header')
              </header>

              <section class="title">
                  <h2>Attendance Report</h2>
              </section>

              <div class="table-responsive">
                                @php
                                $groupedData = $attendanceReports->groupBy([
                                    function ($item) {
                                        return $item->employee->employementDetail->branch->name ?? 'N/A';
                                    },
                                    function ($item) {
                                        return $item->employee->employementDetail->department->name ?? 'N/A';
                                    }
                                ]);
                                @endphp

                                @foreach($groupedData as $branchName => $departments)
                                <table class="table table-bordered mb-4"  style="padding-bottom:10px">
                                    <thead>
                                        <tr>
                                            <th colspan="10">
                                                Branch Name : {{ $branchName }} ({{ request('date')?? \Carbon\Carbon::now()->format('d-m-Y') }})
                                            </th>
                                        </tr>
                                    </thead>
                                    
                                    @foreach($departments as $departmentName => $attendances)
                                        <tbody>
                                            <tr>
                                                <td colspan="10">
                                                    Department Name : {{ $departmentName }}
                                                </td>
                                            </tr>
                                            
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
                                                $shift = \Modules\HRMS\Models\Settings\Shift::where('id', 10000)->first();
                                                $work_duration = 'N/A';
                                                $late = 0;

                                                if($attendance->check_in_time && $attendance->check_out_time){
                                                    $checkIn = \Carbon\Carbon::parse($attendance->check_in_time);
                                                    $checkOut = \Carbon\Carbon::parse($attendance->check_out_time);
                                                    $work_duration_hours = $checkIn->diffInHours($checkOut);
                                                    $work_duration_minutes = $checkIn->diffInMinutes($checkOut) % 60;
                                                    $work_duration = $work_duration_hours . ' Hours ' . $work_duration_minutes . ' Minutes';

                                                    $shiftInTime = \Carbon\Carbon::parse($attendance->shift->in_time ?? $shift->in_time);
                                                    $graceTime = $attendance->shift->grace_time ?? $shift->grace_time;
                                                    $difference = $checkIn->diffInMinutes($shiftInTime);

                                                    $late = max(0, $difference - $graceTime);
                                                }
                                            @endphp
                                            <tr>
                                                <td>{{ $key+1 }}</td>
                                                <td>{{ @$attendance->employee->employementDetail->card_no }}</td>
                                                <td>{{ @$attendance->employee->full_name }}</td>
                                                <td>{{ $attendance->employee->employementDetail->designation->name ?? '' }}</td>
                                                <td> @if ($attendance->check_in_date && $attendance->check_in_time)
                                                    {{ date('M. d, Y, g:i A', strtotime($attendance->check_in_date . ' ' . $attendance->check_in_time)) }}</td>
                                                @else
                                                    N/A
                                                @endif</td>
                                                <td>@if($attendance->check_out_date && $attendance->check_out_time)
                                                    {{ date('M. d, Y, g:i A', strtotime($attendance->check_out_date . ' ' . $attendance->check_out_time)) }}
                                                @else
                                                    N/A
                                                @endif</td>
                                                <td>{{ $late }}</td>
                                                <!-- Status Display (no changes needed) -->
                                            <td>
                                                @php
                                                    $isLate = false;
                                                    if ($attendance->check_in_time) {
                                                        $shift = $attendance->shift ?? \Modules\HRMS\Models\Settings\Shift::find(10000);
                                                        if ($shift) {
                                                            $checkIn = \Carbon\Carbon::parse($attendance->check_in_time);
                                                            $shiftInTime = \Carbon\Carbon::parse($shift->in_time);
                                                            $graceTime = $shift->grace_time ?? 0;
                                                            $isLate = $checkIn->diffInMinutes($shiftInTime) > $graceTime;
                                                        }
                                                    }
                                                @endphp
                                                
                                                @if($isLate)
                                                    <span class="badge badge-round badge-warning">Late</span>
                                                @elseif ($attendance->attendance_type == 'Present')
                                                    <span class="badge badge-round badge-success">{{ $attendance->attendance_type }}</span>
                                                @else
                                                    <span class="badge badge-round badge-{{ $attendance->attendance_type == 'Absent' ? 'danger' : ($attendance->attendance_type == 'Holiday' ? 'info' : ($attendance->attendance_type == 'Leave' ? 'primary' : ($attendance->attendance_type == 'Weekend' ? 'secondary' : 'dark'))) }}">{{ $attendance->attendance_type }}</span>
                                                @endif
                                            </td>
                                                
                                                <td>{{ @$attendance->entryBy->name }}</td>
                                                <td>{{ $attendance->remarks }}</td>
                                            </tr>
                                            @endforeach
                                            
                                            <tr>
                                                <td colspan="10" style="text-align: right!important">
                                                        Department Total: {{ count($attendances) }}

                                                </td>
                                            </tr>
                                        </tbody>
                                    @endforeach
                                    
                                    <tfoot>
                                        <tr>
                                            <td colspan="10" style="text-align: right!important">
                                                    Branch Total: {{ $departments->flatten()->count() }}
                                            </td>
                                        </tr>
                                    </tfoot>
                                </table>
                                @endforeach
                            </div>

              <footer style="margin-top: 100px">
                  @include('partials._for_pdf_footer')
              </footer>
          </div>
      </div>
  </div>
</div>
