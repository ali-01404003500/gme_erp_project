<?php

namespace Modules\Account\Controllers\Api;

use App\Http\Controllers\Controller;
use Modules\Account\Models\Collections\Collection;
use Modules\Account\Services\Collections\CollectionService;
use Illuminate\Http\Request;
use Modules\CRM\Models\Customer\Customer;
use Modules\HRMS\Models\Employee;
use Modules\Purchase\Models\Supplier;
use Modules\Purchase\Models\Vendor;

class CollectionControllerApi extends Controller
{

    /**
     * Service variable
     *
     * @var CollectionService
     */
    private $service;
    function __construct(CollectionService $service)
    {
        $this->service = $service;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $collections = $this->service->getAll();
        // $customers = Customer::select('id', 'company_name as name')->get();

        return response()->json([
            'collections' => $collections
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'voucher_type' => 'required|string|in:Collection',
            'collection_type' => 'required|string|in:customer,vendor,supplier,employee',
            'collection_from' => 'required|integer',
            'payments_total_amount' => 'nullable|numeric|min:0',
            'payments_payable_amount' => 'nullable|numeric|min:0',
            'payments_due_amount' => 'nullable|numeric|min:0',
            'payments_advance_amount' => 'nullable|numeric|min:0',
            'status' => 'nullable|in:pending,verified,approved,denied',
            'payments' => 'array|min:1',
            'payments.*.pay_mode' => 'required|in:Cash,Cheque,Online Deposit,bKash,Nagad,Rocket,Card,EMI,Card Payment',
            'payments.*.bank_id' => 'nullable|integer',
            'payments.*.branch_id' => 'nullable|integer|exists:bank_branches,id',
            'payments.*.emi_id' => 'nullable|integer|exists:e_m_i_entries,id',
            'payments.*.transaction_id' => 'nullable|string',
            'payments.*.date' => 'required|date',
            'payments.*.amount' => 'nullable|numeric|min:0',
            'payments.*.attachments' => 'nullable|string',
            'payments.*.verified' => 'nullable|in:0,1',
            'payments.*.remark' => 'nullable|string',
        ]);

        $data = [
            'voucher_type' => $validated['voucher_type'],
            'collection_type' => $validated['collection_type'],
            'collection_from' => $validated['collection_from'],
            'payments_total_amount' => $validated['payments_total_amount'],
            'payments_payable_amount' => $validated['payments_payable_amount']??  $validated['payments_total_amount'],
            'payments_due_amount' => $validated['payments_due_amount']??0,
            'payments_advance_amount' => $validated['payments_advance_amount']??0,
            'status' => $validated['status']??"pending",
        ];

        $payments = [];
        foreach ($validated['payments'] as $payment) {
            $payments['payments_pay_mode'][] = $payment['pay_mode'];
            $payments['payments_bank_id'][] = $payment['bank_id'] ?? null;
            $payments['payments_branch_id'][] = $payment['branch_id'] ?? null;
            $payments['payments_emi_id'][] = $payment['emi_id'] ?? null;
            $payments['payments_transaction_id'][] = $payment['transaction_id'] ?? null;
            $payments['payments_date'][] = $payment['date'];
            $payments['payments_amount'][] = $payment['amount'] ?? 0;
            $payments['payments_attachments'][] = $payment['attachments'] ?? null;
            $payments['payments_verified'][] = $payment['verified'] ?? false;
            $payments['payments_remark'][] = $payment['remark'] ?? null;
        }
        $payments['payments_total_amount'] = $validated['payments_total_amount'];
        $payments['payments_payable_amount'] = $validated['payments_payable_amount']??  $payments['payments_total_amount'];
        $payments['payments_due_amount'] = $validated['payments_due_amount']??0;
        $payments['payments_advance_amount'] = $validated['payments_advance_amount']??0;

        $collection = $this->service->store($data, $payments);

        return response()->json([
            'message' => 'Collection created successfully.',
            'collection' => $collection,
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $collection = $this->service->show($id);
// dd( $collection->payments);
            $collection->payments->transform(function ($payment) {
                $payment['bank'] = $payment->bank;
                $payment['branch'] = $payment->branch;
                return $payment;
            });
        return response()->json([
            'collection' => $collection,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Collection $collection)
    {
        $validated = $request->validate([
            'voucher_type' => 'required|string|in:Collection',
            'collection_type' => 'required|string|in:customer,vendor,supplier,employee',
            'collection_from' => 'required|integer',
            'payments_total_amount' => 'required|numeric|min:0',
            'payments_payable_amount' => 'nullable|numeric|min:0',
            'payments_due_amount' => 'nullable|numeric|min:0',
            'payments_advance_amount' => 'nullable|numeric|min:0',
            'status' => 'nullable|in:pending,verified,approved,denied',
            'payments' => 'array|min:1',
            'payments.*.pay_mode' => 'required|in:Cash,Cheque,Online Deposit,bKash,Nagad,Rocket,Card,EMI,Card Payment',
            'payments.*.bank_id' => 'nullable|integer',
            'payments.*.branch_id' => 'nullable|integer|exists:bank_branches,id',
            'payments.*.emi_id' => 'nullable|integer|exists:e_m_i_entries,id',
            'payments.*.transaction_id' => 'nullable|string',
            'payments.*.date' => 'required|date',
            'payments.*.amount' => 'nullable|numeric|min:0',
            'payments.*.attachments' => 'nullable|string',
            'payments.*.verified' => 'nullable|in:0,1',
            'payments.*.remark' => 'nullable|string',
        ]);

        $data = [
            'voucher_type' => $validated['voucher_type'],
            'collection_type' => $validated['collection_type'],
            'collection_from' => $validated['collection_from'],
            'payments_total_amount' => $validated['payments_total_amount'],
            'payments_payable_amount' => $validated['payments_payable_amount']??$validated['payments_total_amount'],
            'payments_due_amount' => $validated['payments_due_amount']??0,
            'payments_advance_amount' => $validated['payments_advance_amount']??0,
            'status' => $validated['status']??"pending",
        ];

        $payments = [];
        foreach ($validated['payments'] as $key => $payment) {
            $payments['payments_pay_mode'][$key] = $payment['pay_mode'];
            $payments['payments_bank_id'][$key] = $payment['bank_id'] ?? null;
            $payments['payments_branch_id'][$key] = $payment['branch_id'] ?? null;
            $payments['payments_emi_id'][$key] = $payment['emi_id'] ?? null;
            $payments['payments_transaction_id'][$key] = $payment['transaction_id'] ?? null;
            $payments['payments_date'][$key] = $payment['date'];
            $payments['payments_amount'][$key] = $payment['amount'] ?? 0;
            $payments['payments_attachments'][$key] = $payment['attachments'] ?? null;
            $payments['payments_verified'][$key] = $payment['verified'] ?? false;
            $payments['payments_remark'][$key] = $payment['remark'] ?? null;
        }
        $payments['payments_total_amount'] = $validated['payments_total_amount'];
        $payments['payments_payable_amount'] = $validated['payments_payable_amount']??$validated['payments_total_amount'];
        $payments['payments_due_amount'] = $validated['payments_due_amount']??0;
        $payments['payments_advance_amount'] = $validated['payments_advance_amount']??0;

        $this->service->update($collection, $data, $payments);

        return response()->json([
            'message' => 'Collection updated successfully.',
            'collection' => $collection,
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Collection $collection)
    {
        $this->service->delete($collection);
        return response()->json([
            'message' => 'Collection deleted successfully.',
        ]);
    }

    public function getAccountsByType(Request $request)
    {
        $type = $request->query('type');
        $query = null;

        switch ($type) {
            case 'customer':
                // Assuming Customer model and 'company_name' field
                $query = Customer::select('id', 'company_name as name');
                break;
            case 'vendor':
                // Assuming Vendor model from Purchase module and 'name' field
                $query = Vendor::select('id', 'company_name as name');
                break;
            case 'supplier':
                // Assuming Supplier model from Purchase module and 'company_name' field
                $query = Supplier::select('id', 'company_name as name');
                break;
            case 'employee':
                // Adjust the model and field name as per your application structure
                $query = Employee::select('id', 'full_name as name');
                break;
        }

        $data = $query ? $query->get() : [];

        return response()->json($data);
    }


    public function getBalance(Request $request)
    {
        $validated = $request->validate([
            'type' => 'required',
            'account_id' => 'required',
        ]);

        $type = $validated['type'];
        $accountId = $validated['account_id'];
        $query = null;
        switch ($type) {
            case 'customer':
                // Assuming Customer model and 'company_name' field
                $query = Customer::find($accountId)?->getAccount();
                break;
            case 'vendor':
                // Assuming Vendor model from Purchase module and 'name' field
                $query = Vendor::find($accountId)?->getAccount();
                break;
            case 'supplier':
                // Assuming Supplier model from Purchase module and 'company_name' field
                $query = Supplier::find($accountId)?->getAccount();
                break;
            case 'employee':
                // Adjust the model and field name as per your application structure
                $query = Employee::find($accountId)?->getAccount();
                break;
        }

        if (!$query) {
            return response()->json(['error' => 'Account not found'], 404);
        }

        return response()->json(["balance"=>floatval($query->balance ?? 0)]);
    }
}
