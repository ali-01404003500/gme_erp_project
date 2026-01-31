<?php

namespace Modules\Account\Controllers;

use App\Traits\CheckPermission;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Modules\Account\Models\FundTransfer;
use Modules\Account\Models\Transaction;
use Modules\Account\Services\AccountTransactionService;
use Modules\Account\Services\DataService;
use Modules\Account\Services\IndexDataService;
use Modules\Account\Services\InvoiceNumberService;

class FundTransferController extends Controller
{
    

    private $dataService;
    private $indexService;
    private $invoiceNumberService;
    private $transactionService;

    public function __construct()
    {
        $this->dataService = new DataService();
        $this->indexService = new IndexDataService();
        $this->invoiceNumberService = new InvoiceNumberService();
        $this->transactionService = new AccountTransactionService();
    }

    public function index()
    {
        

        $data['transfers'] = $this->indexService->getFundTransferData();

        return view('Account::fund-transfers.index', $data);
    }

    public function create()
    {
        

        $data = $this->dataService->getAccountData(['accounts']);

        return view('Account::fund-transfers.create', $data);
    }

    public function store(Request $request): RedirectResponse
    {
        

        $this->validateData($request);

        try {
            DB::beginTransaction();

            $transfer = FundTransfer::query()->create($request->all());

            $transfer->update([
                'invoice_no' => $this->invoiceNumberService->getFundTransferInvoiceNo($transfer->company_id),
            ]);

            DB::commit();
        } catch (Exception $e) {
            DB::rollback();
            return back()->with('error', $e->getMessage());
        }

        return redirect()->route('account.fund-transfers.index')->with('success', 'Fund Transferred Successfully');
    }

    public function approveFundTransfer(FundTransfer $fundTransfer)
    {
        

        $fundTransfer->update([
            'is_approved' => 1
        ]);

        $this->transactionService->storeFundTransfer($fundTransfer);

        $this->invoiceNumberService->setNextInvoiceNo($fundTransfer->company_id, 'Fund Transfer', date('Y'));

        return redirect()->route('account.fund-transfers.index')->with('success', 'Fund Transfer Approved Successfully!');
    }

    public function edit(FundTransfer $fundTransfer)
    {
        

        $data = $this->dataService->getAccountData(['accounts']);

        return view('Account::fund-transfers.edit', compact('fundTransfer'), $data);
    }

    public function update(Request $request, FundTransfer $fundTransfer): RedirectResponse
    {
        

        $this->validateData($request);

        try {
            DB::beginTransaction();

//            $from_account_id = $fundTransfer->from_account_id;
//            $to_account_id = $fundTransfer->to_account_id;

            $fundTransfer->where('id', $fundTransfer->id)->update($request->except(['_token', '_method']));
//            $fundTransfer->refresh();
//
//            Transaction::query()->where('invoice_no', $fundTransfer->invoice_no)->where('account_id', $from_account_id)
//                ->update([
//                    'account_id' => $fundTransfer->from_account_id,
//                    'amount' => $fundTransfer->amount,
//                    'balance_type' => $fundTransfer->fromAccount->balance_type
//                ]);
//
//            Transaction::query()->where('invoice_no', $fundTransfer->invoice_no)->where('account_id', $to_account_id)
//                ->update([
//                    'account_id' => $fundTransfer->to_account_id,
//                    'amount' => $fundTransfer->amount,
//                    'balance_type' => $fundTransfer->toAccount->balance_type
//                ]);

            DB::commit();
        } catch (Exception $e) {
            DB::rollback();
            return back()->with('error', $e->getMessage());
        }

        return redirect()->route('account.fund-transfers.index')->with('success', 'Fund Transferred Successfully');
    }

    public function destroy(FundTransfer $fundTransfer): RedirectResponse
    {
        

        try {
            Transaction::query()->where('invoice_no', $fundTransfer->invoice_no)->delete();
            $fundTransfer->delete();

            return back()->with('success', 'Fund Transfer Successfully Deleted!');
        } catch (Exception $ex) {
            return back()->with('error', $ex->getMessage());
        }
    }

    private function validateData(Request $request)
    {
        $request->validate([
            'date' => 'required',
            'description' => 'required',
            'from_account_id' => 'required',
            'to_account_id' => 'required|different:from_account_id',
            'amount' => 'required',
        ]);
    }
}
