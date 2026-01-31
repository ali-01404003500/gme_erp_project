<?php


namespace Modules\Account\Services;


use Illuminate\Http\Request;
use Modules\Account\Models\Account;
use Modules\Account\Models\Purchase;
use Modules\Account\Models\Transaction;
use Modules\Purchase\Models\PurchaseOrderReceive;

class SupplierLedgerReportService
{



    public function getSupplierLedgerReport(Request $request)
    {
        $accounts = Account::where('account_subsidiary_id', '=', 1006)->get();
        $transactions = Transaction::where('transactionable_type', '=', PurchaseOrderReceive::class)->get();
        return view('Account::supplier-ledger-report', compact('accounts', 'transactions'));
    }




    public function getLedger(Request $request)
    {
        $per_page = 30;

        $data['selected_account'] = Account::find($request->account_id);

        $data['paginate_debit_balance'] = 0;
        $data['paginate_credit_balance'] = 0;

        $balance = Transaction::query()
            ->searchByField('company_id')
            ->where('account_id', $request->account_id)
            ->whereDate('transaction_date', '<',  $request->from ?? date('Y-m-d'))
            ->get();

        $data['debit_balance']  = $balance->sum('debit_amount');
        $data['credit_balance'] = $balance->sum('credit_amount');



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

        $data['transactions'] = $request->print
            ? $data['transactions']->get()
            : $data['transactions']->paginate($per_page);



        if ($request->filled('page')) {
           
            $paginate_balance = Transaction::query()
                ->with('transactionable')
                ->searchByField('company_id')
                ->where('account_id', $request->account_id)
                ->when($request->from, function ($q) use ($request) {
                    $q->whereDate('transaction_date', '>=', $request->from);
                })
                ->when($request->to, function ($q) use ($request) {
                    $q->whereDate('transaction_date', '<=', $request->to);
                })
                ->limit(($request->page - 1) * $per_page)
                ->get(); 


            $data['paginate_debit_balance'] = $paginate_balance->sum('debit_amount');
            $data['paginate_credit_balance'] = $paginate_balance->sum('credit_amount');
        }

        if(!$request->filled('print')) {

            if($data['transactions']->currentPage() == $data['transactions']->lastPage()) {


            $total_value_query = Transaction::query()
                                ->with('transactionable')
                                ->searchByField('company_id')
                                ->where('account_id', $request->account_id)
                                ->when($request->from, function ($q) use ($request) {
                                    $q->whereDate('transaction_date', '>=', $request->from);
                                })
                                ->when($request->to, function ($q) use ($request) {
                                    $q->whereDate('transaction_date', '<=', $request->to);
                                }); 

                $data['grand_total_debit_balance'] = (clone $total_value_query)->sum('debit_amount');
                $data['grand_total_credit_balance'] = (clone $total_value_query)->sum('credit_amount');
            }
        }

        return $data;
    }






    public function supplierPurchaseReport($request)
    {
        $purchase                           = PurchaseOrderReceive::query()->with(['purchaseOrder' => function ($q) {
            $q->select('id', 'supplier_id');
            $q->with('supplier:id,company_name');
        }])->searchByFields(['supplier_id']);
        
        $data['purchases']                  = $request->print ? $purchase->get() : $purchase->paginate(30);

        $data['grand_total_amount']         = $purchase->get()->sum('total_amount');
        $data['grand_total_paid_amount']    = $purchase->get()->sum('paid_amount');


        return $data;
    }
}
