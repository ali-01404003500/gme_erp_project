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
                  <h2>Chart of Accounts</h2>
              </section>

              <table  class="table dt-table-hover" style="width:100%">
                <thead>
                    <tr>
                        <th class="text-center" style="width: 8%">Sl</th>
                        <th class="text-center">Account Group</th>
                        <th class="text-center">Account Control</th>
                        <th class="text-center">Account Subsidiary</th>
                        <th class="text-center">Name</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($accounts as $key => $accountSubsidiary)
                        <tr>
                            <td class="text-center">{{ $loop->iteration }}</td>
                            <td class="text-center">{{ $accountSubsidiary->accountGroup->name ?? '' }}</td>
                            <td class="text-center">{{ $accountSubsidiary->accountControl->name ?? '' }}</td>
                            <td class="text-center">{{ $accountSubsidiary->accountSubsidiary->name ?? '' }}</td>
                            <td class="text-center">{{ $accountSubsidiary->name }}</td>
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
