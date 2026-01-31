<?php


namespace Modules\Account\Services;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Modules\Account\Models\AccountGroup;

class TransactionLedgerReportService
{
    public function getTrialBalanceReportData(Request $request)
    {
        return AccountGroup::with(['accountControls' => function ($q) use ($request) {
            $q->with(['accountSubsidiaries' => function ($qr) use ($request) {
                $qr->with(['accounts' => function ($qur) use ($request) {
                    $from = $request->routeIs('report.trial-balance') ? '1580-01-01' : $request->from ?? today();
                    $to = $request->to ?? today();

                    $qur->withCount(['transaction_items as debit' => function ($quer) use ($from, $to) {
                        $quer->select(DB::Raw('SUM(debit_amount)'))
                            ->whereDate('transaction_date', '>=',  $from)
                            ->whereDate('transaction_date', '<=',  $to);
                    }])->withCount(['transaction_items as credit' => function ($quer) use ($from, $to) {
                        $quer->select(DB::Raw('SUM(credit_amount)'))
                            ->whereDate('transaction_date', '>=',  $from)
                            ->whereDate('transaction_date', '<=',  $to);
                    }]);
                }]);
            }]);
        }])->get();
    }










    /*
     |--------------------------------------------------------------------------
     | INCOME STATEMENT
     |--------------------------------------------------------------------------
    */
    public function getIncomeStatementReportData(Request $request)
    {
        // $data['search'] = $search = $request->month ?? $request->year ?? $request->date_range ?? date('Y');

        $search = null;
        
        if ($request->filled('date_range')) {
            $dates = explode(' to ', $request->date_range);
        
            // Parse the first date using Carbon and extract the year
            $fromDate = Carbon::parse($dates[0]);
            $search = $fromDate->year;

            // dd($search, $dates);
        } else {
            // Default to the specified month, year, or current year
            $search = $request->month ?? $request->year ?? date('Y');
        }
        
        $data['search'] = $search;
        
    
        // REVENUE 
        $data['revenues']       = $this->accountGroupSummary($account_group_id = 4, $query = DB::Raw('SUM(credit_amount - debit_amount)'), $search);

        // purchases
        // $data['purchases']      = $this->accountGroupSummary($account_group_id = 6, $query = DB::Raw('SUM(debit_amount - credit_amount)'), $search);
        // exenses
        $data['expenses']       = $this->accountGroupSummary($account_group_id = 5, $query = DB::Raw('SUM(debit_amount - credit_amount)'), $search);

        //depreciations
        $data['depreciations']  = $this->accountGroupSummary($account_group_id = 9, $query = DB::Raw('SUM(credit_amount - debit_amount)'), $search);

        //equity
        $data['equity']         = $this->accountGroupSummary($account_group_id = 3, $query = DB::Raw('SUM(credit_amount - debit_amount)'), $search);

        $data['liabilities'] =  $this->accountGroupSummary($account_group_id = 3, $query = DB::Raw('SUM(credit_amount - debit_amount)'), $search);

        $data['tax'] =  AccountGroup::where('id', 2)
        ->with(['accountControls' => function ($q) use ($request) {
            $q->where('id', 2010)->with(['accountSubsidiaries' => function ($qur) use ($request) {  // 2010 = Long Term Liabilities
                
                $qur->where('id', 2012)->with(['accounts' => function ($qur) use ($request) {       // 2012 = Tax Payable
                                           // $from = fdate($request->from ?? today());
                    // $to = fdate($request->to ?? today());

                    $qur->withCount(['transaction_items as balance' => function ($quer) use ($request) {
                        $quer->select(DB::Raw('SUM(credit_amount - debit_amount )'))
                            // ->searchByField('company_id')
                            ->when(request()->filled('date_range'), function ($q) {
                                $dateRange = explode(' to ', request()->input('date_range'));
                                $from = date('Y-m-01', strtotime($dateRange[0])); // First day of the start month
                                $to = date('Y-m-t', strtotime($dateRange[1] ?? $dateRange[0])); // Last day of the end month
                                $q->whereBetween('transaction_date', [$from, $to]);

                            })
                            ->when(request()->filled('company_id'), function ($q) {
                                $q->whereIn('company_id', request('company_id'));
                            });
                    }]);
                }]);
                
            }]);
        }])->first();

        // purchases
        $data['purchases'] = AccountGroup::where('id', 5)
            ->with(['accountControls' => function ($q) use ($request) {
                $q->where('id', 5010)->with(['accountSubsidiaries' => function ($qur) use ($request) { // 1000 = Current Assets
                    
                    $qur->where('id', 5011)->with(['accounts' => function ($qur) use ($request) {       // 1007 = Inventory
                                               // $from = fdate($request->from ?? today());
                        // $to = fdate($request->to ?? today());

                        $qur->withCount(['transaction_items as balance' => function ($quer) use ($request) {
                            $quer->select(DB::Raw('SUM(debit_amount - credit_amount)'))
                                // ->searchByField('company_id')
                                ->when(request()->filled('date_range'), function ($q) {
                                    $dateRange = explode(' to ', request()->input('date_range'));
                                    $from = date('Y-m-01', strtotime($dateRange[0])); // First day of the start month
                                    $to = date('Y-m-t', strtotime($dateRange[1] ?? $dateRange[0])); // Last day of the end month
                                    $q->whereBetween('transaction_date', [$from, $to]);

                                })
                                ->when(request()->filled('company_id'), function ($q) {
                                    $q->whereIn('company_id', request('company_id'));
                                });
                        }]);
                    }]);
                    
                }]);
            }])
            ->first();

            // dd($data['purchases']);
            return $data;


        
    }



    /*
    |------------------------------------------------------------------------------------------------------------------
    | ACCOUNT GROUP SUM DATA BY ID AND QUERY    
    |------------------------------------------------------------------------------------------------------------------
    */
    public function accountGroupSummary($id, $query, $search)
    {
        return AccountGroup::query()
            ->where('id', $id)
            ->with(['accountControls' => function ($q) use ($query, $search) {
                $q->where('id', '!=', 5010) // Exclude account control with id 5010
                ->with(['accounts' => function ($qur) use ($query, $search) {
    
                    $qur->withCount(['transaction_items as balance' => function ($quer) use ($query, $search) {
                        $quer->select($query)
                        ->when(request()->filled('date_range'), function ($q) {
                            $dateRange = explode(' to ', request()->input('date_range'));
                            $from = date('Y-m-01', strtotime($dateRange[0])); // First day of the start month
                            $to = date('Y-m-t', strtotime($dateRange[1] ?? $dateRange[0])); // Last day of the end month
                            $q->whereBetween('transaction_date', [$from, $to]);
                        })
                        ->when(request()->filled('company_id'), function ($q) {
                            $q->whereIn('company_id', request('company_id'));
                        });
                    }]);
                }]);
            }])
            ->first();
    }









    /*
     |--------------------------------------------------------------------------
     | EQUITY STATEMENT
     |--------------------------------------------------------------------------
    */
    public function getEquityStatementReportData(Request $request)
    {


        // REVENUE
        $data['revenues'] = AccountGroup::whereIn('id', [4])->with(['accountControls' => function ($q) use ($request) {
            $q->with(['accounts' => function ($qur) use ($request) {
                $from = fdate($request->from ?? today());
                $to = fdate($request->to ?? today());

                $qur->withCount(['transaction_items as balance' => function ($quer) use ($request, $from, $to) {
                    $quer->select(DB::Raw('SUM(credit_amount - debit_amount)'))
                        ->searchByField('company_id');
                }]);
            }]);
        }])->get()->sum(function ($item) {

            return $item->accountControls->sum(function ($control) {
                return $control->accounts->sum('balance');
            });
        });



        // purchases
        $data['purchases'] = AccountGroup::where('id', 6)
            ->with(['accountControls' => function ($q) use ($request) {
                $q->with(['accounts' => function ($qur) use ($request) {
                    $from = fdate($request->from ?? today());
                    $to = fdate($request->to ?? today());

                    $qur->withCount(['transaction_items as balance' => function ($quer) use ($request, $from, $to) {
                        $quer->select(DB::Raw('SUM(debit_amount - credit_amount)'))
                            ->searchByField('company_id');
                    }]);
                }]);
            }])->get()->sum(function ($item) {

                return $item->accountControls->sum(function ($control) {
                    return $control->accounts->sum('balance');
                });
            });



        // exenses
        $data['expenses'] = AccountGroup::where('id', 5)
            ->with(['accountControls' => function ($q) use ($request) {
                $q->with(['accounts' => function ($qur) use ($request) {
                    $from = fdate($request->from ?? today());
                    $to = fdate($request->to ?? today());

                    $qur->withCount(['transaction_items as balance' => function ($quer) use ($request, $from, $to) {
                        $quer->select(DB::Raw('SUM(debit_amount - credit_amount)'))
                            ->searchByField('company_id');
                    }]);
                }]);
            }])->get()->sum(function ($item) {

                return $item->accountControls->sum(function ($control) {
                    return $control->accounts->sum('balance');
                });
            });



        // exenses
        $data['depreciations'] = AccountGroup::where('id', 9)
            ->with(['accountControls' => function ($q) use ($request) {
                $q->with(['accounts' => function ($qur) use ($request) {
                    $from = fdate($request->from ?? today());
                    $to = fdate($request->to ?? today());

                    $qur->withCount(['transaction_items as balance' => function ($quer) use ($request, $from, $to) {
                        $quer->select(DB::Raw('SUM(debit_amount - credit_amount)'))
                            ->searchByField('company_id');
                    }]);
                }]);
            }])->get()->sum(function ($item) {

                return $item->accountControls->sum(function ($control) {
                    return $control->accounts->sum('balance');
                });
            });


        return $data;
    }


    
}
