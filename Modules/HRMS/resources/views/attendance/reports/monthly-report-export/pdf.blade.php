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
                  <h2>MonthlyAttendance Report</h2>
              </section>

             <div class="table-responsive">
                            @php
                                $groupedData = $groupedStats->groupBy(fn($item) =>
                                    optional($item['employee']->employementDetail->branch)->name ?? 'N/A'
                                )->map(function ($items) {
                                    return $items->groupBy(fn($item) =>
                                        optional($item['employee']->employementDetail->department)->name ?? 'N/A'
                                    );
                                });
                            @endphp

                            @foreach($groupedData as $branchName => $departments)
                                <table class="table table-bordered mb-4">
                                    <thead>
                                        <tr>
                                            <th colspan="10">Branch: {{ $branchName }}</th>
                                        </tr>
                                        <tr>
                                            <th>No.</th>
                                            <th>Emp ID</th>
                                            <th>Employee Name</th>
                                            <th>Designation</th>
                                            <th>Present Days</th>
                                            <th>Absent Days</th>
                                            <th>Late Days</th>
                                            <th>Leave Days</th>
                                            <th>Holiday Days</th>
                                            <th>Total Days</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($departments as $departmentName => $employees)
                                            <tr>
                                                <td colspan="10">Department: {{ $departmentName }}</td>
                                            </tr>
                                            @foreach($employees as $index => $report)
                                                @php
                                                    $employee = $report['employee'];
                                                    $employment = $employee->employementDetail;
                                                @endphp
                                                <tr>
                                                    <td>{{ $index + 1 }}</td>
                                                    <td>{{ $employment->card_no ?? 'N/A' }}</td>
                                                    <td>{{ $employee->full_name }}</td>
                                                    <td>{{ $employment->designation->name ?? 'N/A' }}</td>
                                                    <td>{{ $report['present_days'] }}</td>
                                                    <td>{{ $report['absent_days'] }}</td>
                                                    <td>{{ $report['late_days'] }}</td>
                                                    <td>{{ $report['leave_days'] }}</td>
                                                    <td>{{ $report['holy_days'] }}</td>
                                                    <td>{{ $report['total_days'] + $report['late_days'] }}</td>
                                                </tr>
                                            @endforeach
                                        @endforeach
                                    </tbody>
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
