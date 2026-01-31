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
                  <h2>Trial Balance</h2>
              </section>

              <table class="table table-sm table-bordered detail-table">
                <thead>
                    <tr style="border: none;">
                        <th class="text-center pb-0" style="font-size: 28px; border: none;" colspan="3">Income Statement</th>
                    </tr>
                    <tr style="border: none;">
                        <th class="text-center" style="font-size: 18px; border: none;" colspan="3">As on {{ request()->input('date_range') ?? date('Y-m') }}</th>
                    </tr>
                    <tr>
                        <th colspan="2">Particular</th>
                        <th class="text-end">{{ $search }}</th>
                    </tr>
                </thead>
            
                <tbody>
                    <!-- Sales Section -->
                    <tr>
                        <td colspan="2"><strong style="font-size: 15px">Sales</strong></td>
                        <td class="text-end">
                            {{ number_format($sale_amount = $revenues->accountControls->sum(function($accountControl) {
                                return $accountControl->accounts->sum('balance');
                            }), 0) }}
                        </td>
                    </tr>
            
                    <!-- Sales Account Details -->
                    <tr style="border:none !important" class="detail-rows">
                        <td colspan="3">
                            <div class="col-sm-8 col-sm-offset-1" style="width: 90% !important">
                                <table class="table table-borderless" style="border:none !important; margin-bottom: 0;">
                                    @foreach ($revenues->accountControls as $accountControl)
                                        @foreach ($accountControl->accounts as $account)
                                            <tr style="border:none !important">
                                                <td style="border:none !important; padding-left: 20px;">{{ $account->name }}</td>
                                                <td style="border:none !important" class="text-end">
                                                    {{ number_format($account->balance) }}
                                                </td>
                                            </tr>
                                        @endforeach
                                    @endforeach
                                </table>
                            </div>
                        </td>
                    </tr>
            
                    <!-- Cost of Goods Sold Section -->
                    <tr>
                        <td colspan="2">Less: Cost of Goods Sold</td>
                        <td class="text-end">
                            {{ number_format($less_amount = $purchases->accountControls->pluck('accountSubsidiaries')->flatten()->pluck('accounts')->flatten()->sum('balance'), 0) }}
                        </td>
                    </tr>
            
                    <!-- COGS Account Details -->
                    <tr style="border:none !important" class="detail-rows">
                        <td colspan="3">
                            <div class="col-sm-8 col-sm-offset-1" style="width: 90% !important">
                                <table class="table table-borderless" style="border:none !important; margin-bottom: 0;">
                                    @foreach ($purchases->accountControls as $accountControl)
                                        @foreach ($accountControl->accountSubsidiaries as $accountSubsidiary)
                                            @foreach ($accountSubsidiary->accounts as $account)
                                                <tr style="border:none !important">
                                                    <td style="border:none !important; padding-left: 20px;">{{ $account->name }}</td>
                                                    <td style="border:none !important" class="text-end">
                                                        {{ number_format($account->balance) }}
                                                    </td>
                                                </tr>
                                            @endforeach
                                        @endforeach
                                    @endforeach
                                </table>
                            </div>
                        </td>
                    </tr>
            
                    <!-- Gross Profit -->
                    <tr>
                        <td colspan="2" class="text-right">
                            <strong>Gross Profit:</strong>
                        </td>
                        <td class="text-end">
                            <strong>{{ number_format($gross_profit = $sale_amount - $less_amount, 0) }}</strong>
                        </td>
                    </tr>
            
                    <!-- Operating Expenses Section -->
                    <tr>
                        <td colspan="2">
                            <strong>Operating Expenses:</strong>
                        </td>
                        <td></td>
                    </tr>
            
                    <!-- Administrative Expenses -->
                    <tr>
                        <td colspan="2">Administrative Expenses</td>
                        <td class="text-end">
                            {{ number_format($expense = $expenses->accountControls->sum(function($accountControl) {
                                return $accountControl->accounts->sum('balance');
                            }), 0) }}
                        </td>
                    </tr>
            
                    <!-- Expense Account Details -->
                    <tr style="border:none !important" class="detail-rows">
                        <td colspan="3">
                            <div class="col-sm-8 col-sm-offset-1" style="width: 90% !important">
                                <table class="table table-borderless" style="border:none !important; margin-bottom: 0;">
                                    @foreach ($expenses->accountControls as $accountControl)
                                        @foreach ($accountControl->accounts as $account)
                                            <tr style="border:none !important">
                                                <td style="border:none !important; padding-left: 20px;">{{ $account->name }}</td>
                                                <td style="border:none !important" class="text-end">
                                                    {{ number_format($account->balance) }}
                                                </td>
                                            </tr>
                                        @endforeach
                                    @endforeach
                                </table>
                            </div>
                        </td>
                    </tr>
            
                    <!-- Operating Profit -->
                    <tr>
                        <td colspan="2" class="text-right">
                            <strong>Operating Profit:</strong>
                        </td>
                        <td class="text-end">
                            <strong>{{ number_format($operative_profit = $gross_profit - $expense, 0) }}</strong>
                        </td>
                    </tr>
            
                    <!-- Earnings Before Interest & Tax -->
                    <tr>
                        <td colspan="2" class="text-right">
                            <strong>Earnings Before Interest & Tax:</strong>
                        </td>
                        <td class="text-end">
                            <strong>{{ number_format($earning_interest_and_tax = $operative_profit, 0) }}</strong>
                        </td>
                    </tr>
            
                    <!-- Financial Expenses -->
                    <tr>
                        <td colspan="2">Less: Financial Expenses</td>
                        <td class="text-end">{{ $financial_expendex = 0 }}</td>
                    </tr>
            
                    <!-- Earnings Before Tax -->
                    <tr>
                        <td colspan="2" class="text-right">
                            <strong>Earnings Before Tax:</strong>
                        </td>
                        <td class="text-end">
                            <strong>{{ number_format($earning_before_tax = $earning_interest_and_tax + $financial_expendex, 0) }}</strong>
                        </td>
                    </tr>
            
                    <!-- Provision for Income Tax -->
                    @php
                        $income_tax = $tax->accountControls->pluck('accountSubsidiaries')->flatten()->pluck('accounts')->flatten()->sum('balance');
                    @endphp
                    <tr>
                        <td colspan="2">Less: Provision for Income Tax</td>
                        <td class="text-end">{{ number_format($income_tax) }}</td>
                    </tr>
            
                    <!-- Net Profit/(Loss) after Tax -->
                    <tr>
                        <td colspan="2">
                            <strong>Net Profit/(Loss) after Tax:</strong>
                        </td>
                        <td class="text-end">
                            <strong>{{ number_format($net_profit_and_loss = $earning_before_tax - $income_tax, 0) }}</strong>
                        </td>
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
