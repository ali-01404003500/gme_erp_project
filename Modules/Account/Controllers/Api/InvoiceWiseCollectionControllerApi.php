<?php

namespace Modules\Account\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Account\Models\InvoiceWiseCollection;
use Modules\Account\Services\InvoiceWiseCollectionService;
use Modules\CRM\Models\Customer\Customer;

class InvoiceWiseCollectionControllerApi extends Controller
{
    private $service;

    public function __construct(InvoiceWiseCollectionService $service)
    {
        $this->service = $service;
    }

    public function getBalance(Request $request, $id)
    {
        $customer = Customer::with([
            'salesOrders' => function ($query) use ($request) {
                $query->whereIn('status', ['approved', 'delivered'])
                    ->where(function ($q) {
                        $q->where('paid_status', 'due')->orWhere('paid_status', 'unpaid');
                    });
            }
        ])->find($id);

        if (!$customer) {
            return response()->json(['message' => 'Customer not found.'], 404);
        }

        // Get all approved collections for this customer to calculate total paid amounts accurately.
        $approvedCollections = InvoiceWiseCollection::where("status", 'approved')
            ->where('customer_id', $customer->id)
            ->with('salesOrders')
            ->get();

        $customer->dueInvoices = $customer->salesOrders->filter(function ($order) use ($approvedCollections) {
            // Start with payments made directly (not through a collection).
            $paidAmount = $order->payments()->where('pay_mode', '!=', 'Collection')->sum('amount');

            // Add amounts from all approved collections for this specific sales order.
            foreach ($approvedCollections as $collection) {
                $pivotAmount = optional($collection->salesOrders->firstWhere('id', $order->id))->pivot->amount ?? 0;
                $paidAmount += $pivotAmount;
            }

            $order->paid_amount = $paidAmount;
            $order->due_amount = $order->net_amount - $paidAmount;

            return $order->due_amount > 0;
        });

        // Calculate total paid & due for the customer
        $totalPaidAmount = $customer->dueInvoices->sum('paid_amount');
        $totalDueAmount = $customer->dueInvoices->sum('due_amount');

        return response()->json([
            'customer_id' => $customer->id,
            'total_paid_amount' => $totalPaidAmount,
            'total_due_amount' => $totalDueAmount,
            'invoices' => $customer->dueInvoices->values() // Re-index keys
        ]);
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $collections = $this->service->getAll();
        return response()->json([
            'data' => $collections,
            'message' => 'Invoice wise collections retrieved successfully.'
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'collection_from' => 'required|integer|exists:customers,id',
            'status' => 'nullable|in:pending,verified,approved,denied',
            'invoices' => 'required|array|min:1',
            'invoices.*.sales_order_id' => 'required|integer|exists:sales_orders,id',
            'invoices.*.pay_amount' => 'required|numeric|min:0',
            'payments' => 'required|array|min:1',
            'payments.*.pay_mode' => 'required|string',
            'payments.*.amount' => 'required|numeric|min:0',
            'payments.*.date' => 'required|date',
            'payments.*.bank_id' => 'nullable|integer',
            'payments.*.branch_id' => 'nullable|integer',
            'payments.*.transaction_id' => 'nullable|string',
            'payments.*.emi_id' => 'nullable|integer',
            'payments.*.verified' => 'nullable|boolean',
            'payments.*.remark' => 'nullable|string',
            'payments.*.attachments' => 'nullable|string',
        ]);

        // Transform strict JSON to flat arrays for Service
        $data = [
            'collection_from' => $validated['collection_from'],
            'status' => $validated['status'] ?? 'pending',
            'sales_order_ids' => [],
            'pay_amount' => [], // Keyed by sales_order_id for service lookup logic or index? Service checks index AND ID key sometimes.
            'checked_invoices' => [],
        ];

        // The service has logic: $data['pay_amount'][$index] ?? ($data['pay_amount'][$salesOrderId] ?? 0);
        // And iterates: foreach ($data['sales_order_ids'] as $index => $salesOrderId)
        // And checks: in_array($salesOrderId, $data['checked_invoices'])

        foreach ($validated['invoices'] as $invoice) {
            $sid = $invoice['sales_order_id'];
            $data['sales_order_ids'][] = $sid;
            // We'll use the sales_order_id as key to be safe with the service logic
            $data['pay_amount'][$sid] = $invoice['pay_amount'];
            // Also add as indexed array to match the order of sales_order_ids just in case
            // But since we can't easily mix keyed and indexed in the same array for PHP to treat it exactly how form data comes usually...
            // Let's rely on the service line: ($data['pay_amount'][$salesOrderId] ?? 0)
            $data['checked_invoices'][] = $sid;
        }
        // Fix for the indexed access in service: $data['pay_amount'][$index] matches $data['sales_order_ids'][$index]
        // actually simpler: Re-build pay_amount as a purely indexed array matching sales_order_ids order is safest if form relies on indices.
        // BUT the service code: $data['pay_amount'][$index] ?? ($data['pay_amount'][$salesOrderId] ?? 0)
        // implying it handles both. Let's do indexed to be form-like.
        $payAmountIndexed = [];
        foreach ($validated['invoices'] as $invoice) {
            $payAmountIndexed[] = $invoice['pay_amount'];
        }
        $data['pay_amount'] = $payAmountIndexed;


        $paymentsData = [
            'payments_pay_mode' => [],
            'payments_amount' => [],
            'payments_date' => [],
            'payments_bank_id' => [],
            'payments_branch_id' => [],
            'payments_transaction_id' => [],
            'payments_emi_id' => [],
            'payments_verified' => [],
            'payments_remark' => [],
            'payments_attachments' => [],
        ];

        foreach ($validated['payments'] as $payment) {
            $paymentsData['payments_pay_mode'][] = $payment['pay_mode'];
            $paymentsData['payments_amount'][] = $payment['amount'];
            $paymentsData['payments_date'][] = $payment['date'];
            $paymentsData['payments_bank_id'][] = $payment['bank_id'] ?? null;
            $paymentsData['payments_branch_id'][] = $payment['branch_id'] ?? null;
            $paymentsData['payments_transaction_id'][] = $payment['transaction_id'] ?? null;
            $paymentsData['payments_emi_id'][] = $payment['emi_id'] ?? null;
            $paymentsData['payments_verified'][] = $payment['verified'] ?? 0;
            $paymentsData['payments_remark'][] = $payment['remark'] ?? null;
            $paymentsData['payments_attachments'][] = $payment['attachments'] ?? null;
        }

        try {
            $collection = $this->service->store($data, $paymentsData);
            return response()->json([
                'message' => 'Invoice wise collection created successfully.',
                'data' => $collection,
            ], 201);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        try {
            $collection = $this->service->show($id);
            $collection->payments->transform(function ($payment) {
                $payment['bank'] = $payment->bank ;
                $payment['branch'] = $payment->branch;
                return $payment;
            });
            return response()->json([
                'data' => $collection,
                'message' => 'Invoice wise collection retrieved successfully.'
            ]);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Collection not found.'], 404);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $invoiceWiseCollection = InvoiceWiseCollection::find($id);
        if (!$invoiceWiseCollection) {
            return response()->json(['message' => 'Collection not found.'], 404);
        }

        $validated = $request->validate([
            'collection_from' => 'sometimes|integer|exists:customers,id',
            'status' => 'sometimes|in:pending,verified,approved,denied',
            'invoices' => 'sometimes|array',
            'invoices.*.sales_order_id' => 'required_with:invoices|integer|exists:sales_orders,id',
            'invoices.*.pay_amount' => 'required_with:invoices|numeric|min:0',
            'payments' => 'sometimes|array',
            'payments.*.id' => 'nullable|integer', // For updating existing payments
            'payments.*.pay_mode' => 'required_with:payments|string',
            'payments.*.amount' => 'required_with:payments|numeric|min:0',
            'payments.*.date' => 'required_with:payments|date',
            'payments.*.bank_id' => 'nullable|integer',
            'payments.*.branch_id' => 'nullable|integer',
            'payments.*.transaction_id' => 'nullable|string',
            'payments.*.emi_id' => 'nullable|integer',
            'payments.*.verified' => 'nullable|boolean',
            'payments.*.remark' => 'nullable|string',
            'payments.*.attachments' => 'nullable|string',
        ]);

        // Prepare Data for Update
        $data = $request->only(['collection_from', 'status']);
        // If invoices provided, transform them
        if ($request->has('invoices')) {
            $data['sales_order_ids'] = [];
            $data['checked_invoices'] = [];
            $payAmountIndexed = [];
            foreach ($validated['invoices'] as $invoice) {
                $sid = $invoice['sales_order_id'];
                $data['sales_order_ids'][] = $sid;
                $data['checked_invoices'][] = $sid;
                $payAmountIndexed[] = $invoice['pay_amount'];
            }
            $data['pay_amount'] = $payAmountIndexed;
        } else {
            // If not updating invoices, we need to preserve existing ones or handle as per business logic.
            // The Service update method expects 'sales_order_ids' and 'pay_amount' to re-associate.
            // If they are missing from request, we might wipe them out if we pass empty arrays.
            // For API "PATCH" usually partial update, but the service logic is "detach all, then attach new".
            // So we really SHOULD require invoices if we want to keep them, OR fetch existing ones.
            // For now, let's assume if 'invoices' is missing in request, we keep existing (we need to fetch and repopulate).

            // ... Looking at service update: 
            // $invoiceWiseCollection->salesOrders()->detach(); 
            // foreach ($data['sales_order_ids'] ...

            // So if we don't pass sales_order_ids, it will crash or detach all.
            // Let's enforce that if 'invoices' is not passed, re-submit existing ones? 
            // Or better, just require invoices for update to be safe, or fetch them:

            $existingOrders = $invoiceWiseCollection->salesOrders;
            if (!$request->has('invoices')) {
                $data['sales_order_ids'] = $existingOrders->pluck('id')->toArray();
                $data['checked_invoices'] = $data['sales_order_ids'];
                // Pivot amount
                $data['pay_amount'] = [];
                foreach ($existingOrders as $order) {
                    $data['pay_amount'][] = $order->pivot->amount;
                }
            }
        }


        // Transform Payments
        $paymentsData = [];
        if ($request->has('payments')) {
            $paymentsData = [
                'payments_id' => [],
                'payments_pay_mode' => [],
                'payments_amount' => [],
                'payments_date' => [],
                'payments_bank_id' => [],
                'payments_branch_id' => [],
                'payments_transaction_id' => [],
                'payments_emi_id' => [],
                'payments_verified' => [],
                'payments_remark' => [],
                'payments_attachments' => [],
            ];
            foreach ($validated['payments'] as $payment) {
                $paymentsData['payments_id'][] = $payment['id'] ?? null;
                $paymentsData['payments_pay_mode'][] = $payment['pay_mode'];
                $paymentsData['payments_amount'][] = $payment['amount'];
                $paymentsData['payments_date'][] = $payment['date'];
                $paymentsData['payments_bank_id'][] = $payment['bank_id'] ?? null;
                $paymentsData['payments_branch_id'][] = $payment['branch_id'] ?? null;
                $paymentsData['payments_transaction_id'][] = $payment['transaction_id'] ?? null;
                $paymentsData['payments_emi_id'][] = $payment['emi_id'] ?? null;
                $paymentsData['payments_verified'][] = $payment['verified'] ?? 0;
                $paymentsData['payments_remark'][] = $payment['remark'] ?? null;
                $paymentsData['payments_attachments'][] = $payment['attachments'] ?? null;
            }
        }

        try {
            $updatedCollection = $this->service->update($invoiceWiseCollection, $data, $paymentsData);
            return response()->json([
                'message' => 'Invoice wise collection updated successfully.',
                'data' => $updatedCollection,
            ]);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $invoiceWiseCollection = InvoiceWiseCollection::find($id);

        if (!$invoiceWiseCollection) {
            return response()->json(['message' => 'Collection not found.'], 404);
        }

        if (in_array($invoiceWiseCollection->status, ['approved', 'denied'])) {
            return response()->json(['message' => 'Cannot delete approved or denied collection.'], 403);
        }

        try {
            $this->service->delete($invoiceWiseCollection);
            return response()->json(['message' => 'Invoice wise collection deleted successfully.']);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }
}
