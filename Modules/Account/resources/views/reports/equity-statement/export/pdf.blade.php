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
          
              @php 
              $previous_year_share_capital = 0;
              $previous_year_retained_earnings = 0;
          
              $profit_loss_share_capital = 0;
              $profit_los_retained_earnings = $profit_and_loss;
          
              $addition_share_capital = 0;
              $addition_retained_earnings = $equity > 0 ? $equity : 0;
          
              $adjustment_share_capital = 0;
              $adjusement_retained_earnings = $equity < 0 ? $equity : 0;
          
              $closing_share_capital = $previous_year_share_capital + $profit_loss_share_capital + $addition_share_capital - $adjustment_share_capital;
              $closing_retained_earnings = $previous_year_retained_earnings + $profit_los_retained_earnings + $addition_retained_earnings - $adjusement_retained_earnings;
          @endphp
          
          <table border="1">
              <thead>
                  <tr>
                      <th>Particular</th>
                      <th>Share Capital</th>
                      <th>Retained Earnings</th>
                      <th>Total</th>
                  </tr>
              </thead>
              <tbody>
                  <tr>
                      <td>Opening Balance</td>
                      <td>{{ number_format($previous_year_share_capital) }}</td>
                      <td>{{ number_format($previous_year_retained_earnings) }}</td>
                      <td>{{ number_format($previous_year_share_capital + $previous_year_retained_earnings) }}</td>
                  </tr>
          
                  <tr>
                      <td>Add: Profit/Loss during the year</td>
                      <td>{{ number_format($profit_loss_share_capital) }}</td>
                      <td>{{ number_format($profit_los_retained_earnings) }}</td>
                      <td>{{ number_format($profit_loss_share_capital + $profit_los_retained_earnings) }}</td>
                  </tr>
          
                  <tr>
                      <td>Add: Addition during the year</td>
                      <td>{{ number_format($addition_share_capital) }}</td>
                      <td>{{ number_format($addition_retained_earnings) }}</td>
                      <td>{{ number_format($addition_share_capital + $addition_retained_earnings) }}</td>
                  </tr>
          
                  <tr>
                      <td>Less: Adjustment during the year</td>
                      <td>{{ number_format($adjustment_share_capital) }}</td>
                      <td>{{ number_format($adjusement_retained_earnings) }}</td>
                      <td>{{ number_format($adjustment_share_capital + $adjusement_retained_earnings) }}</td>
                  </tr>
          
                  <tr>
                      <td><strong>Closing Balance</strong></td>
                      <td><strong>{{ number_format($closing_share_capital) }}</strong></td>
                      <td><strong>{{ number_format($closing_retained_earnings) }}</strong></td>
                      <td><strong>{{ number_format($closing_share_capital + $closing_retained_earnings) }}</strong></td>
                  </tr>
          
                  <tr>
                      <td>Previous Year Balance</td>
                      <td>{{ number_format($previous_year_share_capital) }}</td>
                      <td>{{ number_format($previous_year_retained_earnings) }}</td>
                      <td>{{ number_format($previous_year_share_capital + $previous_year_retained_earnings) }}</td>
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
