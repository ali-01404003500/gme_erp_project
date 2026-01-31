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
  
  .date-range {
      text-align: center;
      font-size: 14px;
      margin-bottom: 10px;
  }
  
  table.table {
      width: 100%;
      border-collapse: collapse;
  }
  table.table th, table.table td {
      text-align: center;
      vertical-align: middle;
      padding: 10px;
  }
  
  .group-row {
      background-color: #f0f0f0;
      font-weight: bold;
  }
  
  .control-row {
      background-color: #f8f8f8;
      padding-left: 20px;
  }
  
  .subsidiary-row {
      background-color: #fcfcfc;
      padding-left: 40px;
  }
  
  .account-row {
      padding-left: 60px;
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
                  <h2>Trial Balance</h2>
              </section>
              
              <div class="date-range">
                  <strong>Period:</strong> {{ $from ?? 'N/A' }} <strong>to</strong> {{ $to ?? 'N/A' }}
              </div>

              <table class="table table-bordered" style="width:100%">
                <thead>
                    <tr style="font-size: 12px; border-bottom: 1px solid #ccc;">
                        <th class="text-start" width="20%">Group</th>
                        <th class="text-start" width="20%">Control</th>
                        <th class="text-start" width="20%">Subsidiary</th>
                        <th class="text-start" width="20%">Account</th>
                        <th class="text-end" width="10%">Dr.</th>
                        <th class="text-end" width="10%">Cr.</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $totalTrialAmountDebit = 0;
                        $totalTrialAmountCredit = 0;
                        $showZero = $show_zero ?? false;
                    @endphp
            
                    @foreach ($accountGroups as $accountGroup)
                        @php
                            $debitAccountGroup = $accountGroup->accountControls->sum(fn($control) =>
                                $control->accountSubsidiaries->sum(fn($item) =>
                                    $item->accounts->sum('debit')
                                )
                            );
                            $creditAccountGroup = $accountGroup->accountControls->sum(fn($control) =>
                                $control->accountSubsidiaries->sum(fn($item) =>
                                    $item->accounts->sum('credit')
                                )
                            );
                        @endphp

                        @if ($showZero || $debitAccountGroup != 0 || $creditAccountGroup != 0)
                            @php
                                $totalTrialAmountDebit += $debitAccountGroup;
                                $totalTrialAmountCredit += $creditAccountGroup;
                            @endphp

                            <!-- Group Row -->
                            <tr class="group-row">
                                <td class="text-start"><strong>{{ $accountGroup->name }}</strong></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td class="text-end"><strong>{{ number_format($debitAccountGroup) }}</strong></td>
                                <td class="text-end"><strong>{{ number_format($creditAccountGroup) }}</strong></td>
                            </tr>

                            <!-- Control Level -->
                            @foreach ($accountGroup->accountControls as $accountControl)
                                @php
                                    $debitAccountControl = $accountControl->accountSubsidiaries->sum(fn($item) => $item->accounts->sum('debit'));
                                    $creditAccountControl = $accountControl->accountSubsidiaries->sum(fn($item) => $item->accounts->sum('credit'));
                                @endphp

                                @if ($showZero || $debitAccountControl != 0 || $creditAccountControl != 0)
                                    <tr class="control-row">
                                        <td></td>
                                        <td class="text-start"><strong>{{ $accountControl->name }}</strong></td>
                                        <td></td>
                                        <td></td>
                                        <td class="text-end"><strong>{{ number_format($debitAccountControl) }}</strong></td>
                                        <td class="text-end"><strong>{{ number_format($creditAccountControl) }}</strong></td>
                                    </tr>

                                    <!-- Subsidiary Level -->
                                    @foreach ($accountControl->accountSubsidiaries as $accountSubsidiary)
                                        @php
                                            $debitSubsidiary = $accountSubsidiary->accounts->sum('debit');
                                            $creditSubsidiary = $accountSubsidiary->accounts->sum('credit');
                                        @endphp

                                        @if ($showZero || $debitSubsidiary != 0 || $creditSubsidiary != 0)
                                            <tr class="subsidiary-row">
                                                <td></td>
                                                <td></td>
                                                <td class="text-start">{{ ucfirst($accountSubsidiary->name) }}</td>
                                                <td></td>
                                                <td class="text-end"><strong>{{ number_format($debitSubsidiary) }}</strong></td>
                                                <td class="text-end"><strong>{{ number_format($creditSubsidiary) }}</strong></td>
                                            </tr>

                                            <!-- Accounts Level -->
                                            @foreach ($accountSubsidiary->accounts as $account)
                                                @if ($showZero || $account->debit != 0 || $account->credit != 0)
                                                    <tr class="account-row">
                                                        <td></td>
                                                        <td></td>
                                                        <td></td>
                                                        <td class="text-start">{{ $account->name }}</td>
                                                        <td class="text-end">{{ number_format($account->debit) }}</td>
                                                        <td class="text-end">{{ number_format($account->credit) }}</td>
                                                    </tr>
                                                @endif
                                            @endforeach
                                        @endif
                                    @endforeach
                                @endif
                            @endforeach
                        @endif
                    @endforeach
            
                    @if ($accountGroups->count() == 0)
                        <tr>
                            <td colspan="6" class="text-center text-danger" style="font-size: 16px;">NO RECORDS FOUND!</td>
                        </tr>
                    @endif
                </tbody>
                <tfoot>
                    <tr style="font-size: 15px; font-weight: bold; border-top: 1px solid #ccc;">
                        <td colspan="4" class="text-start" style="font-size: 18px; color: #1ba74d;">Total</td>
                        <td class="text-end" style="font-size: 18px; color: #1ba74d;">{{ number_format($totalTrialAmountDebit) }}</td>
                        <td class="text-end" style="font-size: 18px; color: #1ba74d;">{{ number_format($totalTrialAmountCredit) }}</td>
                    </tr>
                </tfoot>
            </table>

              <footer style="margin-top: 100px">
                  @include('partials._for_pdf_footer')
              </footer>
          </div>
      </div>
  </div>
</div>