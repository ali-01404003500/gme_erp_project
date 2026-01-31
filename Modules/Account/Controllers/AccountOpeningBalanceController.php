<?php

namespace Modules\Account\Controllers;

use App\Models\Company;
use App\Traits\CheckPermission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Modules\Account\Models\Account;
use Modules\Account\Models\AccountControl;
use Modules\Account\Models\AccountGroup;
use Modules\Account\Models\AccountOpeningBalance;
use Modules\Account\Models\OpeningBalanceDetail;
use Modules\Account\Services\AccountTransactionService;

class AccountOpeningBalanceController extends Controller
{
    
    /**
     * Account Transaction Service
     *
     * @var AccountTransactionService 
     */
    private $transactionService;

    public function __construct(AccountTransactionService $transactionService)
    {
        $this->transactionService   = $transactionService;
    }


    public function create(Request $request)
    {
        $data['accountGroups']      = AccountGroup::pluck('name', 'id');
        $data['accountControls']    = AccountControl::pluck('name', 'id');
        $data['accounts']           = Account::query()
                                        ->active()
                                        ->searchByField('account_group_id')
                                        ->searchByField('account_control_id')
                                        // ->with(['opening_balances' => function($q) use($request) {
                                        //     $q->where('company_id', $request->company_id);
                                        // }])
                                        ->orderBy('name')
                                        ->paginate(15);

        return view('Account::setup.account-opening-balances.create', $data);
    }




    public function store(Request $request)
    {
        // dd($request->all());
        $validate = $request->validate([
            "opening_balance_date" => 'required|date'
        ]);

        $openingDetails = $request->validate([
            "debit.*" => 'nullable|numeric',
            "credit.*" => 'nullable|numeric',
        ]);
        DB::beginTransaction();
        $result= [];
        $accountOpeningBalance = AccountOpeningBalance::create($validate);
        $result['accountOpeningBalance'] = $accountOpeningBalance;
        foreach ( $openingDetails['debit'] as $account_id => $value) {
            if($openingDetails['debit'][$account_id] != null || $openingDetails['credit'][$account_id] != null) {
             $result['openingBalanceDetails'][] =   OpeningBalanceDetail::create([
                    'account_id' => $account_id,
                    'aop_id' => $accountOpeningBalance->id,
                    'debit_amount' => $openingDetails['debit'][$account_id],
                    'credit_amount' => $openingDetails['credit'][$account_id]
                ]);
            }

            if($openingDetails['debit'][$account_id] != null){
                //debit transaction
                //debit transaction
                $this->transactionService->storeTransaction(
                    AccountOpeningBalance::class, 
                    $accountOpeningBalance->id,
                    null, 
                    $account_id, 
                    $openingDetails['debit'][$account_id], 
                    $openingDetails['debit'][$account_id], 
                    0, 
                    'debit', 
                    "Opening Balance"
                );
                
            }

            if($openingDetails['credit'][$account_id] != null){
                //credit transaction
                $this->transactionService->storeTransaction(
                    AccountOpeningBalance::class, 
                    $accountOpeningBalance->id,
                    null, 
                    $account_id, 
                    -$openingDetails['credit'][$account_id], 
                    0, 
                    $openingDetails['credit'][$account_id], 
                    'credit', 
                    "Opening Balance"
                );
            }
            
        }
        DB::commit();
        // dd( $result);
        // $this->transactionService->createOpeningBalance($request);

        return redirect()->back()->withMessage('Opening Balance Successfully Updated');
        
    }
}
