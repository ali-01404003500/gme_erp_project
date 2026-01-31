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
                  @include('partials._for_pdf_header_2nd')
              </header>

              <section class="title">
                  <h2>Attendance Report</h2>
              </section>

              <table class="table table-bordered" style="width:100%">
                <thead>
                    <tr>
                        <th>Sl</th>
                        <th>Employee</th>
                        <th>Date</th>
                        <th>Check-In</th>
                        <th>Check-Out</th>
                        <th>Shift</th>
                        <th>Work Duration</th>
                        <th>Attended Type</th>
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
                            {{ @$value->employee->full_name }}
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

              <footer style="margin-top: 100px">
                  @include('partials._for_pdf_footer')
              </footer>
          </div>
      </div>
  </div>
</div>
