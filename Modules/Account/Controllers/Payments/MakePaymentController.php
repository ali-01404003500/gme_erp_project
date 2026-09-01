<?php

namespace Modules\Account\Controllers\Payments;

use App\Http\Controllers\Controller;
use App\Models\AccessControl\CompanyInfo;
use Modules\Account\Models\Payments\MakePayment;
use Modules\Account\Services\Payments\MakePaymentService;
use Illuminate\Http\Request;
use Modules\Inventory\Services\ExportService;
use Modules\Purchase\Models\Supplier;
use Modules\Purchase\Models\Vendor;
use Modules\CRM\Models\Customer\Broker;
use Modules\CRM\Models\Customer\Customer;
use Modules\Account\Models\Account;

class MakePaymentController extends Controller
{

    /**
     * Service variable
     *
     * @var MakePaymentService
     */
    private $service;
    function __construct(MakePaymentService $service)
    {
        $this->service = $service;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data['makePayments'] = $this->service->getAll();

        return view("Account::payments.make-payments.index", $data);
    }

    public function verification()
    {
        $data['makePayments'] = $this->service->getAll();
 
        return view('Account::payments.payment-verifications.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('Account::payments.make-payments.create-payments');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
 

        // dd($request->all());
        $validate = $request->validate([
            'payment_to_type' => 'required|in:supplier,vendor,broker,petty_cash_expense,withdrawal,equipment,loan_payment,non_current_assets',
            'payment_to_id' => 'required|integer',
            'payments_total_amount' => 'required|numeric|min:0',
            'payments_due_amount' => 'required|numeric|min:0',
            'payments_advance_amount' => 'required|numeric|min:0',
            'status' => 'required|in:pending,verified,approved',
        ]);

        $payments = $request->validate([
            'payments_pay_mode' => 'nullable|array',
            'payments_pay_mode.*' => 'required|in:Cash,Cheque,Online Deposit,bKash,Nagad,Rocket,Card,EMI,Card Payment,AIT,Waiver,Waiver Bad Debt',
            'payments_bank_id' => 'nullable|array',
            'payments_bank_id.*' => 'nullable|integer|exists:bank_accounts,id',
            'payments_transaction_id' => 'nullable|array',
            'payments_transaction_id.*' => 'nullable|string',
            'payments_date' => 'nullable|array',
            'payments_date.*' => 'required|date',
            'payments_amount' => 'nullable|array',
            'payments_amount.*' => 'nullable|numeric|min:0',
            'payments_attachments' => 'nullable|array',
            'payments_attachments.*' => 'nullable|string',
            'payments_verified' => 'nullable|array',
            'payments_verified.*' => 'nullable|in:0,1',
            'payments_remark' => 'nullable|array',
            'payments_remark.*' => 'nullable|string',
        ]);



        $this->service->store($validate, $payments);
        return redirect()->route('account.payments.make-payments.index')->with('success', 'MakePayment created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, $id)
    {
        $data['makePayment'] = $this->service->show($id);
        $data['company_info'] = CompanyInfo::first();

        // Check if export is requested
        if ($request->filled('export_type')) {
            $filename = 'Payment_Receipt_' . $data['makePayment']->payment_id . '_' . today()->format('Y_m_d');

            return (new ExportService())->exportData(
                $data,
                'Account::payments.make-payments.export.',
                $filename
            );
        }

        return view("Account::payments.make-payments.show", $data);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(MakePayment $makePayment)
    {
        $data['makePayment'] = $makePayment;
        //
        return view("Account::payments.make-payments.edit-payments", $data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, MakePayment $makePayment)
    {
        
        $validate = $request->validate([
            //validate rules
            'payment_to_type' => 'required|in:supplier,vendor,broker,petty_cash_expense,withdrawal,equipment,loan_payment,non_current_assets',
            'payment_to_id' => 'required|integer',
            'payments_total_amount' => 'required|numeric|min:0',
            'payments_due_amount' => 'required|numeric|min:0',
            'payments_advance_amount' => 'required|numeric|min:0',
            'status' => 'required|in:pending,verified,approved,denied',
        ]);

        $payments = $request->validate([
            'payments_pay_mode' => 'nullable|array',
            'payments_pay_mode.*' => 'required|in:Cash,Cheque,Online Deposit,bKash,Nagad,Rocket,Card,EMI,Card Payment,AIT,Waiver,Waiver Bad Debt',
            'payments_bank_id' => 'nullable|array',
            'payments_bank_id.*' => 'nullable|integer|exists:bank_accounts,id',
            'payments_transaction_id' => 'nullable|array',
            'payments_transaction_id.*' => 'nullable|string',
            'payments_date' => 'nullable|array',
            'payments_date.*' => 'required|date',
            'payments_amount' => 'nullable|array',
            'payments_amount.*' => 'nullable|numeric|min:0',
            'payments_attachments' => 'nullable|array',
            'payments_attachments.*' => 'nullable|string',
            'payments_verified' => 'nullable|array',
            'payments_verified.*' => 'nullable|in:0,1',
            'payments_remark' => 'nullable|array',
            'payments_remark.*' => 'nullable|string',
        ]);

        $this->service->update($makePayment, $validate, $payments);

        // Determine success message based on status
        $status = $validate['status'];
        if ($status === 'verified') {
            $message = 'Payment Requisition Verified Successfully (Verified).';
        } elseif ($status === 'approved') {
            $message = 'Payment Requisition Approved Successfully (Final).';
        } elseif ($status === 'denied') {
            $message = 'Payment Requisition Denied.';
        } else {
            $message = 'MakePayment updated successfully.';
        }

        return redirect()->route('account.payments.make-payments.index')->with('success', $message);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(MakePayment $makePayment)
    {
        $this->service->delete($makePayment);
        return redirect()->route('account.payments.make-payments.index')->with('success', 'MakePayment deleted successfully.');
    }


    public function loadAccount(Request $request)
    {

        $validate = $request->validate([
            'type' => 'required',
        ]);

        $data = [];

        switch ($validate['type']) {
            case 'supplier':
                $data['accounts'] = Supplier::where('status', '1')->select('id', 'company_name as name')->get();
                break;
  
            case 'customer':
                $data['accounts'] = Customer::activeCustomers()->select('id', 'company_name', 'company_place_id')->with('area')->get()
                    ->map(function ($item) {
                        return [
                            'id' => $item->id,
                            'name' => $item->company_name.' - '.$item->area?->area, // alias here
                        ];
                    });
                break; 

            case 'vendor':
                $data['accounts'] = Vendor::where('status', '1')->select('id', 'company_name as name')->get();
                break;
 
            case 'broker':
                $data['accounts'] = Broker::where('status', 2)->select('id', 'broker_name as name')->get();
                break;

            case 'petty_cash_expense':
                $data['accounts'] = Account::where('account_group_id', 5)->where('status', 1)->select('id', 'name')->get();
                break;

            case 'withdrawal':
                $data['accounts'] = Account::where('account_group_id', 3)->where('status', 1)->select('id', 'name')->get();
                break;

            case 'equipment':
                $data['accounts'] = Account::where('account_group_id', 1)->where('account_control_id', 1050)->where('account_subsidiary_id', 5109)->where('status', 1)->select('id', 'name')->get();
                break;  

            case 'non_current_assets':
                $data['accounts'] = Account::where('account_group_id', 1)->whereIn('account_control_id', [5092])->where('status', 1)->select('id', 'name')->get();
                break;  

            case 'loan_payment':
                $data['accounts'] = Account::where('account_group_id', 2)->whereIn('account_control_id', [2010, 2030])->where('status', 1)->select('id', 'name')->get();
                break;  

                
            default:
                break;
        }

        return response()->json($data);
    }




    public function getBalance(Request $request)
    {
        $validate = $request->validate([
            'type' => 'required',
            'account_id' => 'required'
        ]);

        $data = [];

        switch ($validate['type']) {
            case 'supplier':
                $data['account'] = Supplier::where('id', $validate['account_id'])->first()->getAccount();
                break;
            case 'customer':
                $data['account'] = Customer::where('id', $validate['account_id'])->first()->getAccount();
                break;
            case 'vendor':
                $data['account'] = Vendor::where('id', $validate['account_id'])->first()->getAccount();
                break;
            case 'broker':
                $data['account'] = Broker::where('id', $validate['account_id'])->first()->getAccount();
                break;
            case 'petty_cash_expense':
                $data['account'] = Account::where('id', $validate['account_id'])->first();
                break;

            case 'Withdrawal':
                $data['account'] = Account::where('id', $validate['account_id'])->first();
                break;

            case 'Equipment':
                $data['account'] = Account::where('id', $validate['account_id'])->first();
                break;  

            case 'Loan Payment':
                $data['account'] = Account::where('id', $validate['account_id'])->first();
                break;  
            default:
                break;
        }


        // $data = $this->service->getBalance($validate['type'], $validate['id']);

        return response()->json($data);
    }
}
