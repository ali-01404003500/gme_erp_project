<?php

namespace Modules\Account\Controllers;

use App\Http\Controllers\Controller;
use App\Models\AccessControl\CompanyInfo;
use Illuminate\Http\Request;
use Modules\Account\Models\InvoiceWiseCollection;
use Modules\Account\Services\InvoiceWiseCollectionService;
use Modules\CRM\Models\Customer\Customer;
use Modules\Inventory\Services\ExportService;

class InvoiceWiseCollectionController extends Controller
{
    private $service;

    public function __construct(InvoiceWiseCollectionService $service)
    {
        $this->service = $service;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data['collections'] = $this->service->getAll();
        // dd($data['collections']->first());

        return view("Account::invoice-wise-collections.index", $data);
    }

    /**
     * Show the form for creating a new invoice-wise collection.
     */
    public function create(Request $request)
    {
        $data['customers'] = Customer::select('id', 'company_name')->get();
        $data['customer'] = null;

        if ($request->has('customer_id')) {
            $customer = Customer::with(['salesOrders' => function ($query) use ($request) {
                $query->whereIn('status', ['approved', 'delivered'])
                    ->where(function ($q) {
                        $q->where('paid_status', 'due')->orWhere('paid_status', 'unpaid');
                    });

                if ($request->filled('date_from')) {
                    $query->where('date', '>=', $request->date_from);
                }
                if ($request->filled('date_to')) {
                    $query->where('date', '<=', $request->date_to);
                }
            }])->find($request->customer_id);

            if ($customer) {
                // Get all approved collections for this customer to calculate total paid amounts accurately.
                $approvedCollections = InvoiceWiseCollection::where("status", 'approved')
                    ->where('customer_id', $customer->id)
                    ->with('salesOrders')
                    ->get();
                // dd($approvedCollections,  $customer->salesOrders);
                $customer->dueInvoices = $customer->salesOrders->filter(function ($order) use ($approvedCollections) {
                    // Start with payments made directly (not through a collection).
                    $paidAmount = $order->payments()->where('pay_mode', '!=', 'Collection')->sum('amount');
                    // Add amounts from all approved collections for this specific sales order.
                    foreach ($approvedCollections as $collection) {
                        $pivotAmount = $collection->salesOrders->firstWhere('id', $order->id)->pivot->amount ?? 0;
                        $paidAmount += $pivotAmount;
                    }
                    
                    $order->paid_amount = $paidAmount;
                    $order->due_amount = $order->net_amount - $paidAmount;

                    return $order->due_amount > 0;
                });
                // dd($customer->dueInvoices);
                // Calculate total paid & due for the customer
                $data['total_paid_amount'] = $customer->dueInvoices->sum('paid_amount');
                $data['total_due_amount'] = $customer->dueInvoices->sum('due_amount');

                $data['customer'] = $customer;
            }
        }

        return view("Account::invoice-wise-collections.create", $data);
    }

    /**
     * Store a newly created invoice-wise collection in storage.
     */
    public function store(Request $request)
    {
        // dd($request->all());
        $validate = $request->validate([
            'collection_from' => 'required|integer|exists:customers,id',
            'sales_order_ids' => 'required|array',
            'sales_order_ids.*' => 'required|integer|exists:sales_orders,id',
            'pay_amount' => 'required|array',
            'pay_amount.*' => 'nullable|numeric',
            'checked_invoices' => 'nullable|array',
        ]);

        // Validate payment details
        $request->validate([
            'payments_pay_mode' => 'required|array',
            'payments_pay_mode.*' => 'required|string',
            'payments_bank_id' => 'nullable|array',
            'payments_amount' => 'required|array',
            'payments_amount.*' => 'required|numeric|min:0',
            'payments_date' => 'required|array',
            'payments_date.*' => 'required|date',
        ]);

        $this->service->store($validate, $request->all());
        return redirect()->route('account.collections.invoice-wise-collections.index')->with('success', 'Invoice-wise collection created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, InvoiceWiseCollection $invoiceWiseCollection)
    {
        $data['collection'] = $this->service->show($invoiceWiseCollection->id);
        $customer = $data['collection']->customer;

        // Get all collections for this customer to calculate payments accurately
        $allCollectionsForCustomer = InvoiceWiseCollection::withoutGlobalScope('latest')
            ->where('customer_id', $customer->id)
            ->where('id', '!=', $invoiceWiseCollection->id) // Exclude the current collection
            ->where('status', 'approved') // Only consider approved collections for payment calculation
            ->with('salesOrders')
            ->get();

            $povetAmount[] = 0;
        // Recalculate paid and due amounts for each sales order in the current collection
        foreach ($data['collection']->salesOrders as $salesOrder) {
            // Base paid amount from direct payments
            $paidAmount = $salesOrder->payments()->where('pay_mode', '!=', 'Collection')->sum('amount');

            // Add payments from other approved collections
            foreach ($allCollectionsForCustomer as $otherCollection) {
                $pivotAmount = optional($otherCollection->salesOrders->firstWhere('id', $salesOrder->id))->pivot->amount ?? 0;
                $paidAmount += $pivotAmount;
                $povetAmount[$salesOrder->sales_order_id][] = $pivotAmount;
            }
            $salesOrder->paid_amount = $paidAmount;
            $salesOrder->due_amount = $salesOrder->net_amount - $paidAmount;
        }
// dd($povetAmount, $data['collection']->salesOrders);
        $data['sales_order_ids'] = $data['collection']->salesOrders->pluck('id')->toArray();
        $data['company_info'] = CompanyInfo::first();

        // Check if export is requested
        if ($request->filled('export_type')) {
            $filename = 'Invoice_Wise_Collection_' . $data['collection']->invoice_wise_collection_id . '_' . today()->format('Y_m_d');
            
            return (new ExportService())->exportData(
                $data, 
                'Account::invoice-wise-collections.export.', 
                $filename
            );
        }

        return view("Account::invoice-wise-collections.show", $data);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(InvoiceWiseCollection $invoiceWiseCollection)
    {
        $data['collection'] = $invoiceWiseCollection->load('customer', 'payments', 'salesOrders.payments');
        $data['customers'] = Customer::select('id', 'company_name')->get();
        $collectionSalesOrderIds = $invoiceWiseCollection->salesOrders->pluck('id')->toArray();
    
        $customer = Customer::with(['salesOrders' => function ($query) {
            $query->whereIn('status', ['approved', 'delivered']);
        }])->find($invoiceWiseCollection->customer_id);
    
        if ($customer) {
            // Get all other approved collections for this customer to calculate payments accurately
            $otherApprovedCollections = InvoiceWiseCollection::withoutGlobalScope('latest')
                ->where('customer_id', $customer->id)
                ->where('id', '!=', $invoiceWiseCollection->id) // Exclude the current collection being edited
                ->where('status', 'approved')
                ->with('salesOrders')
                ->get();
    
            $customer->dueInvoices = $customer->salesOrders->map(function ($order) use ($collectionSalesOrderIds, $invoiceWiseCollection, $otherApprovedCollections) {
                // Base paid amount from direct payments (non-collection)
                $paidAmount = $order->payments()->where('pay_mode', '!=', 'Collection')->sum('amount');
    
                // Add payments from other approved collections
                foreach ($otherApprovedCollections as $otherCollection) {
                    $pivotAmount = optional($otherCollection->salesOrders->firstWhere('id', $order->id))->pivot->amount ?? 0;
                    $paidAmount += $pivotAmount;
                }
    
                $order->paid_amount = $paidAmount;
                $order->due_amount = $order->net_amount - $paidAmount;
    
                return $order;
            })->filter(function ($order) use ($collectionSalesOrderIds) {
                // Show the invoice if it's part of the current collection OR if it still has a due amount
                return in_array($order->id, $collectionSalesOrderIds) || $order->due_amount > 0;
            });
            $data['customer'] = $customer;
        }

        $data['collectionSalesOrderIds'] = $collectionSalesOrderIds;

        return view("Account::invoice-wise-collections.edit", $data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, InvoiceWiseCollection $invoiceWiseCollection)
    {
        $validate = $request->validate([
            'collection_from' => 'required|integer|exists:customers,id',
            'sales_order_ids' => 'required|array',
            'sales_order_ids.*' => 'required|integer|exists:sales_orders,id',
            'pay_amount' => 'required|array',
            'pay_amount.*' => 'nullable|numeric',
            'status' => 'sometimes|in:pending,verified,approved,denied',
            'checked_invoices' => 'array',
        ]);

        $validate['status'] = $request->input('status', $invoiceWiseCollection->status);
        
        if ($request->has('total_amount')) {
            $validate['total_amount'] = $request->input('total_amount');
        }

        // Track status changes for verified_by and approved_by
        if ($validate['status'] === 'verified' && $invoiceWiseCollection->status !== 'verified') {
            $invoiceWiseCollection->verified_by = auth()->user()->id;
            $invoiceWiseCollection->save();
        }
        
        if ($validate['status'] === 'approved' && $invoiceWiseCollection->status !== 'approved') {
            $invoiceWiseCollection->approved_by = auth()->user()->id;
            $invoiceWiseCollection->save();
        }

        $this->service->update($invoiceWiseCollection, $validate, $request->all());

        // Determine success message based on status
        $status = $validate['status'];
        if ($status === 'verified') {
            $message = 'Payment Requisition Verified Successfully (Verified).';
        } elseif ($status === 'approved') {
            $message = 'Payment Requisition Approved Successfully (Final).';
        } elseif ($status === 'denied') {
            $message = 'Payment Requisition Denied.';
        } else {
            $message = 'Invoice-wise collection updated successfully.';
        }

        return redirect()->route('account.collections.invoice-wise-collections.index')->with('success', $message);
    }

    /**
     * Approve the specified invoice-wise collection.
     */
    public function approve(InvoiceWiseCollection $invoiceWiseCollection)
    {
        $this->service->approve($invoiceWiseCollection);
        return redirect()->back()->with('success', 'Payment Requisition Approved Successfully (Final).');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(InvoiceWiseCollection $invoiceWiseCollection)
    {
        if (in_array($invoiceWiseCollection->status, ['approved', 'denied'])) {
            return redirect()->back()->with('error', 'Cannot delete approved or denied collection.');
        }

        $this->service->delete($invoiceWiseCollection);
        return redirect()->route('account.collections.invoice-wise-collections.index')->with('success', 'Invoice-wise collection deleted successfully.');
    }
}