<?php

namespace Modules\Account\Controllers;

use App\Models\AccessControl\CompanyInfo;
use App\Models\Company;
use App\Traits\CheckPermission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use Modules\Account\Models\Account;

use Modules\Account\Models\AccountGroup;
use Modules\Account\Models\AccountSubsidiary;
use Modules\Account\Models\AdvanceChequeEntry;
use Modules\Account\Models\Transaction;
use Modules\Account\Services\AccountLedgerReportService;
use Modules\Account\Services\DataService;
use Modules\Account\Services\JournalLedgerReportService;
use Modules\Account\Services\SubsidiaryWiseLedgerReportService;
use Modules\Account\Services\SupplierLedgerReportService;
use Modules\Account\Services\TransactionLedgerReportService;
use Modules\Account\Services\VoucherReportService;
use Modules\CMS\Models\ApplicationEntry;
use Modules\CRM\Models\Customer\Customer;
use Modules\HRMS\Models\Employee;
use Modules\Inventory\Services\ExportService;
use Modules\Legal\Models\LegalBillEntry;
use Modules\Purchase\Models\Supplier;
use Modules\Purchase\Models\Vendor;

class AccountReportController extends Controller
{
    

    private $dataService;

    private $reportService;

    private $journalLedger;

    private $subsidiaryLedger;

    private $transactionLedger;











    /*
     |--------------------------------------------------------------------------
     | CONSTRUCTOR
     |--------------------------------------------------------------------------
    */
    public function __construct()
    {
        $this->dataService          = new DataService();

        $this->reportService        = new AccountLedgerReportService();

        $this->journalLedger        = new JournalLedgerReportService();

        $this->transactionLedger    = new TransactionLedgerReportService();

        $this->subsidiaryLedger     = new SubsidiaryWiseLedgerReportService();
    }











    /*
     |--------------------------------------------------------------------------
     | ACCOUNT LEDGER REPORT
     |--------------------------------------------------------------------------
    */
    public function accountLedgerReport(Request $request)
    {
        

        $data1 = $this->dataService->getAccountData(['accounts']);	

        $data2 = $this->reportService->getLedger($request);


        // $data1['companies']  = Company::userCompanies();

        $view = 'Account::reports.account-ledger.' . ($request->print ? 'print' : 'index');

        return view($view, $data1, $data2);
    }











    /*
     |--------------------------------------------------------------------------
     | VOUCHER REPORT
     |--------------------------------------------------------------------------
    */
    public function getVoucherReport(Request $request)
    {
        

        $data1 = $this->dataService->getAccountData(['accounts']);

        $data2 = (new VoucherReportService)->getReportData($request);


        // $data1['companies']  = Company::userCompanies();

        $view = 'Account::reports.voucher-reports.' . ($request->print ? 'print' : 'index');

        return view($view, $data1, $data2);
    }











    /*
     |--------------------------------------------------------------------------
     | LEDGER JOURNAL REPORT
     |--------------------------------------------------------------------------
    */
    public function JournalReport(Request $request)
    {
        


        $data = $this->journalLedger->getJournalReport($request);

        $data['grandDebit']  = Transaction::get()->sum('debit_amount');
        $data['grandCredit'] = Transaction::get()->sum('credit_amount');

        $view = 'Account::reports.journal-report.' . ($request->print ? 'print' : 'index');

        return view($view, $data);
    }












    /*
     |--------------------------------------------------------------------------
     | TRIAL BALANCE
     |--------------------------------------------------------------------------
    */
    public function trialBalanceReport(Request $request)
    {
        $accountGroups = $this->transactionLedger->getTrialBalanceReportData($request);
        $data['accountGroups'] = $accountGroups;
        $data['company_info'] = CompanyInfo::first();
        
        // Add filter parameters to data array
        $data['from'] = $request->from ?? today()->format('Y-m-d');
        $data['to'] = $request->to ?? today()->format('Y-m-d');
        $data['show_zero'] = $request->show_zero ?? false;

        $view = 'Account::reports.trial-balance.' . ($request->print ? 'print' : 'index');
        
        if ($request->filled('export_type')) {
            $request->merge(['page' => '1']);
            $filename = 'Trial Balance ' . today()->format('Y_m_d');

            return (new ExportService())->exportData($data, 'Account::reports.trial-balance.export.', $filename);
        }

        return view($view, compact('accountGroups'));
    }












    /*
     |--------------------------------------------------------------------------
     | INCOME STATEMENT
     |--------------------------------------------------------------------------
    */
    public function incomeStatement(Request $request)
    {
        $data = $this->transactionLedger->getIncomeStatementReportData($request);

        if ($request->filled('export_type')) {
            $request->merge(['page' =>  '1']);
            $filename = 'Income Statement' . today()->format(date('Y-m-d'), 'Y_m_d');

            return (new ExportService())->exportData($data, 'Account::reports.income-statement.export.', $filename);
        }
        $view = 'Account::reports.income-statement.' . ($request->print ? 'print' : 'index');

        return view($view, $data);
    }












    /*
     |--------------------------------------------------------------------------
     | EQUITY STATEMENT
     |--------------------------------------------------------------------------
    */
    public function equityStatement(Request $request)
    {
        

        $item = $this->transactionLedger->getIncomeStatementReportData($request);

        $revenues = $item['revenues']->accountControls->sum(function ($control) {
            return $control->accounts->sum('balance');
        });

        $purchases = $item['purchases']->accountControls->sum(function ($control) {
            return $control->accounts->sum('balance');
        });

        $expenses = $item['expenses']->accountControls->sum(function ($control) {
            return $control->accounts->sum('balance');
        });

        // $depreciations = $item['depreciations']->accountControls->sum(function ($control) {
        //     return $control->accounts->sum('balance');
        // });

        $equity = $item['equity']->accountControls->sum(function ($control) {
            return $control->accounts->sum('balance');
        });

        $tax  = $item['tax']->accountControls->pluck('accountSubsidiaries')->flatten()->pluck('accounts')->flatten()->sum('balance');
        $less_amount = $item['purchases']->accountControls->pluck('accountSubsidiaries')->flatten()->pluck('accounts')->flatten()->sum('balance');



        $data['profit_and_loss']    = $revenues - $purchases - $less_amount - $expenses - $tax;
        $data['equity']             = $equity;
        // $data['companies']          = Company::userCompanies();
        $data['company_info'] = CompanyInfo::first();

        $view                       = 'Account::reports.equity-statement.' . ($request->print ? 'print' : 'index');

        if ($request->filled('export_type')) {
            $request->merge(['page' =>  '1']);
            $filename = 'Equity Statement' . today()->format(date('Y-m-d'), 'Y_m_d');

            return (new ExportService())->exportData($data, 'Account::reports.equity-statement.export.', $filename);
        }

        return view($view, $data);
    }












    /*
     |--------------------------------------------------------------------------
     | CHART OF ACCOUNT REPORT
     |--------------------------------------------------------------------------
    */
    public function chartOfAccountReport(Request $request)
    {
        // $data['companies']  = Company::userCompanies();

        $data['accounts'] = Account::query()
            ->orderBy('name')
            ->withCount(['transaction_items as debit' => function ($quer) {
                $quer->select(DB::Raw('SUM(debit_amount)'));
            }])->withCount(['transaction_items as credit' => function ($quer) {
                $quer->select(DB::Raw('SUM(credit_amount)'));
            }])
            ->filterDate('created_at')
            ->with('transactions');

        if ($request->print) {
            $data['accounts'] = $data['accounts']->get();
        } else {
            $data['accounts'] = $data['accounts']->paginate(30);
        }

        return view('Account::reports.chart-of-account.' . ($request->print ? 'print' : 'index'), $data);
    }












    /*
     |--------------------------------------------------------------------------
     | CUSTOMER LEDGER
     |--------------------------------------------------------------------------
    */
    // public function customerLedgerReport(Request $request)
    // {
        

    //     $data['customers'] = Customer::with(['accounts'])
    //     ->whereHas('accounts') // Ensures only customers with accounts are retrieved
    //     ->get();
    
    //     // dd($data['customers']);

    //     $transactions = Transaction::query()
    //         ->searchByField('company_id')
    //         ->where('account_id', $request->account_id)
    //         ->whereDate('transaction_date', '<',  $request->from ?? today())
    //         ->get();


    //     // $data['companies']  = Company::userCompanies();
    //     $data['balance'] = $transactions->sum('debit_amount') - $transactions->sum('credit_amount');

    //     $data['transactions'] = Transaction::query()
    //         ->with('transactionable')
    //         ->searchByField('company_id')
    //         ->where('account_id', $request->account_id)
    //         ->when($request->from, function ($q) use ($request) {
    //             $q->whereDate('transaction_date', '>=', $request->from);
    //         })
    //         ->when($request->to, function ($q) use ($request) {
    //             $q->whereDate('transaction_date', '<=', $request->to);
    //         });

    //     $data['transactions'] = $request->print
    //         ? $data['transactions']->get()
    //         : $data['transactions']->paginate(30);

    //     return view('Account::reports.customer-ledger.' . ($request->print ? 'print' : 'index'), $data);
    // }
public function customerLedgerReport(Request $request)
    {
        $data['customers'] = Customer::with(['accounts'])
            ->whereHas('accounts')
            ->get();

        $data['company_info'] = CompanyInfo::first();
        
        // Get selected customer details
        if ($request->account_id) {
            $account = Account::find($request->account_id);
            $customer = Customer::whereHas('accounts', function($q) use ($request) {
                $q->where('id', $request->account_id);
            })->first();
            
            if ($customer) {
                $data['selectedCustomer'] = $customer;
                
                // Calculate opening balance
                $openingTransactions = Transaction::query()
                    ->searchByField('company_id')
                    ->where('account_id', $request->account_id)
                    ->whereDate('transaction_date', '<', $request->from ?? today())
                    ->get();
                
                $data['balance'] = $openingTransactions->sum('debit_amount') - $openingTransactions->sum('credit_amount');
                
                // Get commission amount (from broker commission module)
                $data['commission_amount'] = 0; // TODO: Implement broker commission calculation
                
                // Get legal expenses within date range
                $data['legal_expense'] = LegalBillEntry::whereHas('legalEntry.convicts', function($q) use ($customer) {
                        $q->where('customer_id', $customer->id);
                    })
                    ->when($request->from, function($q) use ($request) {
                        $q->whereDate('date', '>=', $request->from);
                    })
                    ->when($request->to, function($q) use ($request) {
                        $q->whereDate('date', '<=', $request->to);
                    })
                    ->sum('amount');
                
                // Get advance cheque information
                $advanceCheques = AdvanceChequeEntry::where('customer_id', $customer->id)
                    ->where('status', 'Approved')
                    ->with(['details' => function($q) {
                        $q->whereNotIn('status', ['Honored', 'Returned', 'Converted']);
                    }])
                    ->get();

                
                
                $data['collected_cheque_amount'] = $advanceCheques->sum(function($entry) {
                    return $entry->details->sum('amount');
                });
                
                $data['collected_cheque_count'] = $advanceCheques->sum(function($entry) {
                    return $entry->details->count();
                });
                
                // Get refunded cheques
                $refundedCheques = ApplicationEntry::where('customer_id', $customer->id)
                    ->where('type', 'Cheque')
                    ->where('status', 'received')
                    ->whereNotNull('advance_cheque_entry_detail_id')
                    ->with('advanceChequeEntryDetail')
                    ->get();
                
                $data['refunded_cheque_count'] = $refundedCheques->count();
                
                // Get advance cheque details for modal
                $data['advance_cheques'] = $advanceCheques->flatMap(function($entry) {
                    return $entry->details->map(function($detail) use ($entry) {
                        return [
                            'cheque_date' => $entry->collection_date,
                            'cheque_no' => $detail->cheque_no,
                            'amount' => $detail->amount,
                            'cheque_type' => $entry->cheque_type,
                            'document' => $detail->document,
                        ];
                    });
                });
                
                // Get refunded cheque details for modal
                $data['refunded_cheques'] = $refundedCheques->map(function($app) {
                    return [
                        'cheque_date' => $app->advanceChequeEntryDetail?->advanceChequeEntry->collection_date,
                        'refund_date' => $app->date,
                        'cheque_no' => $app->advanceChequeEntryDetail?->cheque_no,
                        'amount' => $app->advanceChequeEntryDetail?->amount,
                        'cheque_type' => $app->advanceChequeEntryDetail?->advanceChequeEntry->cheque_type,
                        'document' => $app->advanceChequeEntryDetail?->document,
                    ];
                });
                
                // Get deed document
                $deedEntry = AdvanceChequeEntry::where('customer_id', $customer->id)
                    ->whereNotNull('document')
                    ->first();
                $data['deed_document'] = $deedEntry?->document;
                
                // Get transactions
                $data['transactions'] = Transaction::query()
                    ->with('transactionable')
                    ->searchByField('company_id')
                    ->where('account_id', $request->account_id)
                    ->when($request->from, function ($q) use ($request) {
                        $q->whereDate('transaction_date', '>=', $request->from);
                    })
                    ->when($request->to, function ($q) use ($request) {
                        $q->whereDate('transaction_date', '<=', $request->to);
                    })
                    ->when($request->service_bill, function($q) {
                        // Filter for service bill transactions only
                        $q->whereHasMorph('transactionable', ['App\Models\ServiceBill']);
                    })
                    ->orderBy('transaction_date', 'asc');
                
                $data['transactions'] = $request->print??$request->filled('export_type')
                    ? $data['transactions']->get()
                    : $data['transactions']->get();
            }
        }
        
        if ($request->filled('export_type')) {
            $filename = 'Customer Ledger Report_' . today()->format('Y_m_d');
            return (new ExportService())->exportData($data, 'Account::reports.customer-ledger.export.', $filename);
        }
        
        return view('Account::reports.customer-ledger.' . ($request->print ? 'print' : 'index'), $data);
    }

public function vendorLedgerReport(Request $request)
{
    $data['vendors'] = Vendor::with(['accounts'])
        ->whereHas('accounts') // Ensures only vendors with accounts are retrieved
        ->get();

    $transactions = Transaction::query()
        ->searchByField('company_id')
        ->where('account_id', $request->account_id)
        ->whereDate('transaction_date', '<', $request->from ?? today())
        ->get();

    $data['balance'] = $transactions->sum('credit_amount') -$transactions->sum('debit_amount') ;

    $data['transactions'] = Transaction::query()
        ->with('transactionable')
        ->searchByField('company_id')
        ->where('account_id', $request->account_id)
        ->when($request->from, function ($q) use ($request) {
            $q->whereDate('transaction_date', '>=', $request->from);
        })
        ->when($request->to, function ($q) use ($request) {
            $q->whereDate('transaction_date', '<=', $request->to);
        });
    $data['company_info'] = CompanyInfo::first();

 
        $data['transactions'] =  $data['transactions']->get();


    // Get selected vendor details if account_id is provided
    if ($request->account_id) {
        $data['selectedVendor'] = Vendor::whereHas('accounts', function($q) use ($request) {
            $q->where('id', $request->account_id);
        })->first();
    }
        if ($request->filled('export_type')) {
            $filename = 'Vendor Ledger Report' . today()->format('Y_m_d');
            
            return (new ExportService())->exportData(
                $data, 
                'Account::reports.vendor-ledger.export.', 
                $filename
            );
        }

    return view('Account::reports.vendor-ledger.' . ($request->print ? 'print' : 'index'), $data);
}






    // public function employeeCashHandlingReport()
    // { 
    //     $employees = Employee::where('status', '1')->get();

    //     $data = $employees->map(function ($employee) {
    //         $account = $employee->getAccount(); 

    //         return [
    //             'name' => $employee->full_name,
    //             'balance' => $account ? $account->balance : 0,
    //         ];
    //     })->toArray();

    //     return view('Account::reports.employee-cash-handling.index', ['data' => $data]);
    // }


    // public function employeeCashHandlingReport(Request $request)
    // {
    //     $employees = Employee::where('status', '1')->get();

    //     $query = Employee::where('status', '1');

    //     // Employee filter
    //     if ($request->filled('employee_id')) {
    //         $query->where('id', $request->employee_id);
    //     }

    //     $data = $query->get()->map(function ($employee) {

    //         $account = $employee->getAccount();

    //         return [
    //             'id'      => $employee->id,
    //             'name'    => $employee->full_name,
    //             'balance' => $account ? $account->balance : 0,
    //         ];

    //     })->toArray();

    //     return view(
    //         'Account::reports.employee-cash-handling.index',
    //         [
    //             'data'      => $data,
    //             'employees' => $employees,
    //         ]
    //     );
    // }

    public function employeeCashHandlingReport(Request $request)
    {
        // Get all active employees for dropdown
        $employees = Employee::where('status', '1')->get();

        // Employee query
        $query = Employee::where('status', '1');

        // Filter by employee
        if ($request->filled('employee_id')) {
            $query->where('id', $request->employee_id);
        }

        // Prepare report data
        $data = $query->get()->map(function ($employee) {

            // Get employee account
            $account = $employee->getAccount();

            // Get account transactions
            $transactions = $account
                ? $account->transactions()
                    ->orderBy('created_at', 'asc')
                    ->get()
                : collect();

            // Running balance
            $runningBalance = 0;

            // Prepare transaction details
            $transactionDetails = $transactions->map(function ($transaction) use (&$runningBalance) {

                $amount = (float) $transaction->amount;

                if ($transaction->balance_type === 'credit') {
                    $runningBalance += $amount;
                } else {
                    $runningBalance -= $amount;
                }

                return [
                    'id'            => $transaction->id,
                    'date'          => $transaction->created_at
                        ? $transaction->created_at->format('d-m-Y H:i:s')
                        : '',
                    'invoice_no'    => $transaction->invoice_no,
                    'balance_type'  => $transaction->balance_type,
                    'amount'        => $amount,
                    'balance'       => $runningBalance,
                ];
            })->values()->toArray();

            return [
                'id'           => $employee->id,
                'name'         => $employee->full_name,
                'balance'      => $account ? (float) $account->balance : 0,
                'transactions' => $transactionDetails,
            ];
        })->values()->toArray();

        return view(
            'Account::reports.employee-cash-handling.index',
            [
                'data'      => $data,
                'employees' => $employees,
            ]
        );
    }


    /*
     |--------------------------------------------------------------------------
     | ACCOUNT RECEIVABLE
     |--------------------------------------------------------------------------
    */
    public function accountReceivableReport(Request $request)
    {
        


        // $data['companies'] = Company::userCompanies();
        $query = Account::query()->asset()->currentAsset()->where('account_subsidiary_id', 1005);

        // dd($query->get());

        $data['transactions'] = (clone $query)
                                ->when($request->filled('account_id'), function($q) use($request) {
                                    $q->where('id', $request->account_id);
                                })
                                ->withCount(['transaction_items as balance' => function ($quer) {
                                    $quer->searchByField('company_id')->select(DB::Raw('SUM(debit_amount - credit_amount)'));
                                }])
                                ->paginate(50);

        $data['accounts'] = $query->get(['name', 'id']);

        return view('Account::reports.account-receivables.' . ($request->print ? 'print' : 'index'), $data);
    }












    /*
     |--------------------------------------------------------------------------
     | ACCOUNT PAYABLE
     |--------------------------------------------------------------------------
    */
    public function accountPayableReport(Request $request)
    {
        

        // $data['companies'] = Company::userCompanies();
        

        $query = Account::query()->liabilities()->where('account_control_id', 2000)->where('account_subsidiary_id', 2001);

        $data['transactions'] = (clone $query)
                                ->when($request->filled('account_id'), function($q) use($request) {
                                    $q->where('id', $request->account_id);
                                })
                                ->withCount(['transaction_items as balance' => function ($quer) {
                                    $quer->searchByField('company_id')->select(DB::Raw('SUM(debit_amount - credit_amount)'));
                                }])
                                ->paginate(50);

        $data['accounts'] = $query->get(['name', 'id']);

        return view('Account::reports.account-payables.' . ($request->print ? 'print' : 'index'), $data);
    }











    /*
     |--------------------------------------------------------------------------
     | SUPPLIER LEDGER
     |--------------------------------------------------------------------------
    */
    public function supplierLedgerReport(Request $request)
    {

        

        // For Supplier Ledger Report
        $data['supplier']   = Supplier::with(['accounts'])
        ->whereHas('accounts')
        ->get();


        $data2 = (new SupplierLedgerReportService)->getLedger($request);
        $data2['company_info'] = CompanyInfo::first();

        if ($request->account_id) {
        $data2['selectedSupplier'] = Supplier::whereHas('accounts', function($q) use ($request) {
            $q->where('id', $request->account_id);
        })->first();
    }

         if ($request->filled('export_type')) {
            $filename = 'Supplier Ledger Report' . today()->format('Y_m_d');
            
            return (new ExportService())->exportData(
                $data2, 
                'Account::reports.supplier-ledger.export.', 
                $filename
            );
        }

        return view('Account::reports.supplier-ledger.' . ($request->print ? 'print' : 'index'), $data, $data2);
    }





    /*
     |--------------------------------------------------------------------------
     | SUPPLIER REPORT
     |--------------------------------------------------------------------------
    */
    public function supplierReport(Request $request)
    {

        

        
        $data                   = (new SupplierLedgerReportService)->supplierPurchaseReport($request);
        $data['suppliers']      = Supplier::with(['accounts'])
        ->whereHas('accounts')->get();
        // $data['companies']      = Company::userCompanies();
        

        return view('Account::reports/supplier.' . ($request->print ? 'print' : 'index'), $data);
    }

















    /*
     |--------------------------------------------------------------------------
     | TRIAL BALANCE
     |--------------------------------------------------------------------------
    */
    public function transactionLedgerReport(Request $request)
    {
        

        $accountGroups = $this->transactionLedger->getTrialBalanceReportData($request);

        $view = 'reports.transaction-ledger.category-' . ($request->print ? 'print' : 'index');

        return view($view, compact('accountGroups'));
    }

    public function ledgerJournalReport(Request $request)
    {
        

        $data = $this->dataService->getAccountData(['accounts']);
        $data2 = $this->journalLedger->getLedger($request);

        $view = 'reports.ledger-journal.' . ($request->print ? 'print' : 'index');

        return view($view, $data, $data2);
    }

    public function subsidiaryWiseLedgerReport(Request $request)
    {
        

        $data = $this->dataService->getAccountData(['accountSubsidiaries']);
        $data2 = $this->subsidiaryLedger->getLedger($request);
        // $data['companies']  = Company::userCompanies();


        $view = 'reports.subsidiary-wise-ledger.' . ($request->print ? 'print' : 'index');

        return view($view, $data, $data2);
    }

    public function expenseAnalysisReport(Request $request)
    {
        

        $data = $this->dataService->getAccountData(['accountControls', 'accountSubsidiaries']);

        $data['accountSubsidiaries'] = AccountSubsidiary::query()
            ->where('account_control_id', $request->account_control_id)
            ->select('id', 'name')
            ->get();

        $data['accounts'] = Account::query()
            ->when($request->account_subsidiary_id, function ($q) use ($request) {
                $q->where('account_subsidiary_id', $request->account_subsidiary_id);
            })
            ->when($request->account_control_id, function ($q) use ($request) {
                $q->where('account_control_id', $request->account_control_id);
            })
            ->select('id', 'name')
            ->get();

        $data['transaction_items'] = Transaction::query()
            ->whereHas('account', function ($q) use ($request) {
                $q->where('account_group_id', 5)
                    ->with('accountSubsidiary', 'accountControl')
                    ->where('balance_type', 'Debit')
                    ->when($request->account_subsidiary_id, function ($r) use ($request) {
                        $r->where('account_subsidiary_id', $request->account_subsidiary_id);
                    })
                    ->when($request->account_control_id, function ($r) use ($request) {
                        $r->where('account_control_id', $request->account_control_id);
                    })
                    ->when($request->account_id, function ($r) use ($request) {
                        $r->where('account_id', $request->account_id);
                    });
            })
            ->where('date', '>=', $request->from ?? date('Y-m-d'))
            ->where('date', '<=', $request->to ?? date('Y-m-d'))
            ->withCount(['account as account_subsidiary_id' => function ($q) {
                $q->select(DB::raw('SUM(account_subsidiary_id)'));
            }])
            ->withCount(['account as account_control_id' => function ($q) {
                $q->select(DB::raw('SUM(account_control_id)'));
            }]);

        if ($request->print) {
            $data['transaction_items'] = $data['transaction_items']->get();
        } else {
            $data['transaction_items'] = $data['transaction_items']->paginate(30);
        }

        return view('Account::reports.expense-analysis.' . ($request->print ? 'print' : 'index'), $data);
    }













    /*
     |--------------------------------------------------------------------------
     | BALANCE SHEET
     |--------------------------------------------------------------------------
    */
    public function balanceSheetReport(Request $request)
    {
        


        $item  = $this->transactionLedger->getIncomeStatementReportData($request);


        $revenues = $item['revenues']->accountControls->sum(function ($control) {
            return $control->accounts->sum('balance');
        });
        $purchases = $item['purchases']->accountControls->pluck('accountSubsidiaries')->flatten()->pluck('accounts')->flatten()->sum('balance');

        $expenses = $item['expenses']->accountControls->sum(function ($control) {
            return $control->accounts->sum('balance');
        });

     

        $equity = $item['equity']->accountControls->sum(function ($control) {
            return $control->accounts->sum('balance');
        });
        // dd($equity, $revenues, $purchases, $expenses);
        $data['equity_balance']     = $revenues + $equity - $expenses - $purchases;
        // $data['companies']          = Company::userCompanies();


        $data['accountGroups'] = AccountGroup::with(['accountControls' => function ($q) use ($request) {
            $q->with(['accounts' => function ($qr) use ($request) {
                $qr->withCount(['transaction_items as debit_balance' => function ($qur) use ($request) {
                    return $qur
                        ->searchByField('company_id')
                        ->whereDate('transaction_date', '<=', $request->date ?? today())
                        ->when(request()->filled('date_range'), function ($q) {
                            $dateRange = explode(' to ', request()->input('date_range'));
                            $from = date('Y-m-01', strtotime($dateRange[0])); // First day of the start month
                            $to = date('Y-m-t', strtotime($dateRange[1] ?? $dateRange[0])); // Last day of the end month
                            $q->whereBetween('transaction_date', [$from, $to]);
                        })
                        ->select(DB::Raw('SUM(debit_amount)'));
                }])
                    ->withCount(['transaction_items as credit_balance' => function ($qur) use ($request) {
                        return $qur
                            ->searchByField('company_id')
                            ->whereDate('transaction_date', '<=', $request->date ?? today())
                            ->when(request()->filled('date_range'), function ($q) {
                                $dateRange = explode(' to ', request()->input('date_range'));
                                $from = date('Y-m-01', strtotime($dateRange[0])); // First day of the start month
                                $to = date('Y-m-t', strtotime($dateRange[1] ?? $dateRange[0])); // Last day of the end month
                                $q->whereBetween('transaction_date', [$from, $to]);
                            })
                            ->select(DB::Raw('SUM(credit_amount)'));
                    }]);
            }]);
        }])
        ->get();

        $data['company_info'] = CompanyInfo::first();

        $view = 'Account::reports.balance-sheet.' . ($request->print ? 'print' : 'index');

        if ($request->filled('export_type')) {
            $request->merge(['page' =>  '1']);
            $filename = 'Balance Sheet' . today()->format(date('Y-m-d'), 'Y_m_d');

            return (new ExportService())->exportData($data, 'Account::reports.balance-sheet.export.', $filename);
        }

        return view($view, $data);
    }









    /*
     |--------------------------------------------------------------------------
     | CASH FLOW
     |--------------------------------------------------------------------------
    */
    public function cashFlowReport(Request $request)
{
    $item = $this->transactionLedger->getIncomeStatementReportData($request);

    // Calculating key balances
    $revenues = $item['revenues']->accountControls->where('transaction_date', '<=', $request->date ?? today())->sum(fn($control) => $control->accounts->sum('balance'));
    $purchases = $item['purchases']->accountControls->where('transaction_date', '<=', $request->date ?? today())->sum(fn($control) => $control->accounts->sum('balance'));
    $expenses = $item['expenses']->accountControls->where('transaction_date', '<=', $request->date ?? today())->sum(fn($control) => $control->accounts->sum('balance'));

    $data['equity_balance'] = $revenues - $purchases - $expenses;
    $data['depreciations'] = 0; // Placeholder for depreciation calculations

    // Fetching account group details
    $data['accountGroups'] = AccountGroup::with(['accountControls.accounts' => function ($query) use ($request) {
        $query->withCount([
            'transaction_items as debit_balance' => function ($q) use ($request) {
                $q->searchByField('company_id')
                  ->where('transaction_date', '<=', $request->date ?? today())
                  ->select(DB::raw('SUM(debit_amount)'));
            },
            'transaction_items as credit_balance' => function ($q) use ($request) {
                $q->searchByField('company_id')
                  ->where('transaction_date', '<=', $request->date ?? today())
                  ->select(DB::raw('SUM(credit_amount)'));
            }
        ]);
    }])->get();

    // Asset and Liability Calculations
    $asset = $data['accountGroups']->where('id', 1)->first();
    $liabilities = $data['accountGroups']->where('id', 2)->first();

    $data['asset'] = [0, 0];
    foreach ($asset->accountControls as $key => $accountControl) {
        $data['asset'][$key] = $accountControl->accounts->sum('debit_balance') - $accountControl->accounts->sum('credit_balance');
    }

    $data['liabilities'] = [0, 0];
    foreach ($liabilities->accountControls as $key => $accountControl) {
        $data['liabilities'][$key] = $accountControl->accounts->sum('credit_balance') - $accountControl->accounts->sum('debit_balance');
    }

    $view = 'Account::reports.cash-flow.' . ($request->print ? 'print' : 'index');
    $data['company_info'] = CompanyInfo::first();

    if ($request->filled('export_type')) {
        $request->merge(['page' =>  '1']);
        $filename = 'Cash Flow' . today()->format(date('Y-m-d'), 'Y_m_d');

        return (new ExportService())->exportData($data, 'Account::reports.cash-flow.export.', $filename);
    }

    return view($view, $data);
}
}
