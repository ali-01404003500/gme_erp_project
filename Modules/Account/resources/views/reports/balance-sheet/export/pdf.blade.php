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
                  <h2>Balance Sheet</h2>
                  <p style="text-align: center;">From: {{ request('from') ?? date('Y-m-d') }} To: {{ request('to') ?? date('Y-m-d') }}</p>

              </section>
          
              {{-- Assets --}}
              @foreach($accountGroups->where('id', 1) as $accountGroup)
                  <div class="section-title">{{ $accountGroup->name }}</div>
                  <table>
                      <tbody>
                          @php $totalBalance = 0; @endphp
                          @foreach($accountGroup->accountControls as $control)
                              @php
                                  $balance = $control->accounts->sum('debit_balance') - $control->accounts->sum('credit_balance');
                                  $totalBalance += $balance;
                              @endphp
                              <tr>
                                  <td width="80%"><strong>{{ $control->name }}</strong></td>
                                  <td class="text-right" width="20%">{{ number_format($balance) }}</td>
                              </tr>
                              @foreach($control->accounts as $account)
                                  <tr>
                                      <td style="padding-left: 20px;">{{ $account->name }}</td>
                                      <td class="text-right">{{ number_format($account->debit_balance - $account->credit_balance) }}</td>
                                  </tr>
                              @endforeach
                          @endforeach
                          <tr>
                              <td class="text-right total">Total {{ $accountGroup->name }}</td>
                              <td class="text-right total">{{ number_format($totalBalance) }}</td>
                          </tr>
                      </tbody>
                  </table>
              @endforeach
          
              {{-- Owners Equity --}}
              <div class="section-title">Owners Equity</div>
              <table>
                  <tbody>
                      <tr>
                          <td><strong>Total Equity Balance</strong></td>
                          <td class="text-right"><strong>{{ number_format($equity_balance) }}</strong></td>
                      </tr>
                  </tbody>
              </table>
          
              {{-- Liabilities --}}
              @php $liabilityBalance = 0; @endphp
              @foreach($accountGroups->whereIn('id', [2, 10]) as $accountGroup)
                  <div class="section-title">{{ $accountGroup->name }}</div>
                  <table>
                      <tbody>
                          @foreach($accountGroup->accountControls as $control)
                              @php
                                  $balance = $control->accounts->sum('credit_balance') - $control->accounts->sum('debit_balance');
                                  $liabilityBalance += $balance;
                              @endphp
                              <tr>
                                  <td width="80%"><strong>{{ $control->name == 'None' && $accountGroup->id == 10 ? 'Accumulated Depreciation' : $control->name }}</strong></td>
                                  <td class="text-right" width="20%">{{ number_format($balance) }}</td>
                              </tr>
                              @foreach($control->accounts as $account)
                                  <tr>
                                      <td style="padding-left: 20px;">{{ $account->name }}</td>
                                      <td class="text-right">{{ number_format($account->credit_balance - $account->debit_balance) }}</td>
                                  </tr>
                              @endforeach
                          @endforeach
                      </tbody>
                  </table>
              @endforeach
          
              <table>
                  <tbody>
                      <tr>
                          <td class="text-right total">Total Liabilities</td>
                          <td class="text-right total">{{ number_format($liabilityBalance) }}</td>
                      </tr>
                      <tr>
                          <td class="text-right grand-total">Total Liabilities & Owners Equity</td>
                          <td class="text-right grand-total">{{ number_format($liabilityBalance + $equity_balance) }}</td>
                      </tr>
                  </tbody>
              </table>

              <footer style="margin-top: 100px">
                  @include('partials._for_pdf_footer')
              </footer>
          </div>
      </div>
  </div>
</div>
