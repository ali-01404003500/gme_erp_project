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

              <table class="table table-bordered" style="width:100%">
                <thead>
                    <tr class="table-header-bg">
                        <th>SL</th>
                        <th>Branch</th>
                        <th>Job Title</th>
                        <th>Applicant</th>
                        <th>Address</th>
                        <th class="text-center">Status</th>
                    </tr>
                </thead>

                <tbody>

                    @forelse ($jobApplications ?? [] as $key => $jobApplication)
                        <tr>
                            <td>{{ $key + $jobApplications->firstItem() }}</td>
                            <td>
                                {{ optional(optional($jobApplication->job)->branch)->name ?? '' }}
                            </td>
                            <td>
                                <p>{{ optional($jobApplication->job)->title }}</p>
                                <p><b>Submitted At:</b> <span
                                        class="text-success">{{ $jobApplication->created_at->format('Y-m-d') }}</span>
                                </p>
                            </td>

                            <td>
                                <b>{{ $jobApplication->name }}</b>
                                <p>{{ $jobApplication->email }}</p>
                                <p>{{ $jobApplication->mobile }}</p>
                            </td>

                            <td>
                                <p>{{ $jobApplication->permanent_address }}</p>
                            </td>
                            <td class="text-center">

                                @if ($jobApplication->status == 0)
                                    Pending
                                @elseif($jobApplication->status == 1)
                                    Select For Interview
                                @elseif($jobApplication->status == 2)
                                    Attended
                                @elseif($jobApplication->status == 3)
                                    Selected
                                @elseif($jobApplication->status == 4)
                                    Hired
                                @endif

                            </td>
                           
                        </tr>
                    @empty
                        @noTableRecordsFound
                    @endforelse
                </tbody>
            </table>

              <footer style="margin-top: 100px">
                  @include('partials._for_pdf_footer')
              </footer>
          </div>
      </div>
  </div>
</div>
