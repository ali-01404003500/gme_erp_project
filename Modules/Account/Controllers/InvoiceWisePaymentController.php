<?php

namespace Modules\Account\Controllers;

use App\Http\Controllers\Controller;
use App\Models\AccessControl\CompanyInfo;
use Illuminate\Http\Request;
use Modules\Account\Models\InvoiceWisePayment;
use Modules\Account\Services\InvoiceWisePaymentService;
use Modules\Inventory\Services\ExportService;
use Modules\Purchase\Models\Supplier;
use Modules\Purchase\Models\Vendor;

class InvoiceWisePaymentController extends Controller
{
    private $service;

    public function __construct(InvoiceWisePaymentService $service)
    {
        $this->service = $service;
    }

    /**
     * Display a listing of invoice wise payments
     */
    public function index()
    {
        $data['payments'] = $this->service->getAll();
        return view("Account::invoice-wise-payments.index", $data);
    }

    /**
     * Show the form for creating a new invoice wise payment
     */
public function create(Request $request)
{
    $data['suppliers'] = Supplier::select('id', 'company_name')->where('status', 1)->get();
    $data['vendors'] = Vendor::select('id', 'company_name')->where('status', 1)->get();
    $data['paymentTo'] = null;

    // Load invoices if payment type and ID are provided
    if ($request->has('payment_to_type') && $request->has('payment_to_id')) {
        $paymentToType = $request->payment_to_type;
        $paymentToId = $request->payment_to_id;

        if ($paymentToType === 'supplier') {
            $paymentTo = Supplier::with(['requisitions' => function ($query) use ($request) {
                $query->where('status', 4); // Approved/Received requisitions
                
                if ($request->filled('date_from')) {
                    $query->where('invoice_date', '>=', $request->date_from);
                }
                if ($request->filled('date_to')) {
                    $query->where('invoice_date', '<=', $request->date_to);
                }
            }])->find($paymentToId);

            if ($paymentTo) {
                $paymentTo->dueInvoices = $paymentTo->requisitions->filter(function ($requisition) {
                    // Calculate already paid amount from approved invoice-wise payments
                    $paidAmount = $requisition->invoiceWisePaymentInvoices()
                        ->whereHas('invoiceWisePayment', function ($query) {
                            $query->where('status', 'approved');
                        })
                        ->sum('amount');

                    $requisition->paid_amount = $paidAmount;
                    $requisition->due_amount = max($requisition->net_amount - $paidAmount, 0);
                    $requisition->invoice_type = get_class($requisition);
                    $requisition->invoice_no = $requisition->invoice_no;
                    $requisition->date = $requisition->invoice_date;
                    $requisition->net_amount = $requisition->net_amount;

                    // Show only invoices with due
                    return $requisition->due_amount > 0;
                });

                // Calculate total paid & due for the supplier
                $data['total_paid_amount'] = $paymentTo->dueInvoices->sum('paid_amount');
                $data['total_due_amount'] = $paymentTo->dueInvoices->sum('due_amount');

                $data['paymentTo'] = $paymentTo;
            }
        } elseif ($paymentToType === 'vendor') {
            $paymentTo = Vendor::with(['officePurchases' => function ($query) use ($request) {
                $query->where('status', 1); // Approved office purchases

                if ($request->filled('date_from')) {
                    $query->where('date', '>=', $request->date_from);
                }
                if ($request->filled('date_to')) {
                    $query->where('date', '<=', $request->date_to);
                }
            }])->find($paymentToId);

            if ($paymentTo) {
                $paymentTo->dueInvoices = $paymentTo->officePurchases->filter(function ($purchase) {
                    // Calculate already paid amount from approved invoice-wise payments
                    $paidAmount = $purchase->invoiceWisePaymentInvoices()
                        ->whereHas('invoiceWisePayment', function ($query) {
                            $query->where('status', 'approved');
                        })
                        ->sum('amount');

                    $purchase->paid_amount = $paidAmount;
                    $purchase->due_amount = max($purchase->bill_amount - $paidAmount, 0);
                    $purchase->invoice_type = get_class($purchase);
                    $purchase->invoice_no = $purchase->invoice_no;
                    $purchase->date = $purchase->date;
                    $purchase->net_amount = $purchase->bill_amount;

                    // Show only invoices with due
                    return $purchase->due_amount > 0;
                });

                // Calculate total paid & due for the vendor
                $data['total_paid_amount'] = $paymentTo->dueInvoices->sum('paid_amount');
                $data['total_due_amount'] = $paymentTo->dueInvoices->sum('due_amount');

                $data['paymentTo'] = $paymentTo;
            }
        }
    }

    return view("Account::invoice-wise-payments.create", $data);
}


    /**
     * Store a newly created invoice wise payment
     */
    public function store(Request $request)
    {
        $validate = $request->validate([
            'payment_to_type' => 'required|string',
            'payment_to_id' => 'required|integer',
            'invoice_ids' => 'required|array',
            'invoice_ids.*' => 'required|integer',
            'invoice_types' => 'required|array',
            'invoice_types.*' => 'required|string',
            'pay_amount' => 'required|array',
            'pay_amount.*' => 'nullable|numeric|min:0',
            'status' => 'nullable|in:pending,verified,approved,denied',
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
        
        return redirect()->route('account.payments.invoice-wise-payments.index')
            ->with('success', 'Invoice-wise payment created successfully.');
    }

    /**
     * Display the specified invoice wise payment
     */
    public function show(Request $request, InvoiceWisePayment $invoiceWisePayment)
    {
        $data['payment'] = $this->service->show($invoiceWisePayment->id);
        $data['company_info'] = CompanyInfo::first();
        
        // Check if export is requested
        if ($request->filled('export_type')) {
            $filename = 'Invoice_Wise_Payment_' . $data['payment']->invoice_wise_payment_id . '_' . today()->format('Y_m_d');
            
            return (new ExportService())->exportData(
                $data, 
                'Account::invoice-wise-payments.export.', 
                $filename
            );
        }

        return view("Account::invoice-wise-payments.show", $data);
    }

    /**
     * Show the form for editing the specified invoice wise payment
     */
    public function edit(InvoiceWisePayment $invoiceWisePayment)
    {
        $data['payment'] = $invoiceWisePayment->load('paymentTo', 'payments', 'invoices.invoice');
        $data['suppliers'] = Supplier::select('id', 'company_name')->where('status', 1)->get();
        $data['vendors'] = Vendor::select('id', 'company_name')->where('status', 1)->get();
        
        $paymentTo = $invoiceWisePayment->paymentTo;
        $paymentInvoiceIds = $invoiceWisePayment->invoices->pluck('invoice_id')->toArray();

        // Determine payment type and load invoices
        $paymentToType = $invoiceWisePayment->payment_to_type;
        
        if ($paymentToType === Supplier::class) {
            $paymentTo->load(['requisitions' => function ($query) {
                $query->where('status', 4); // Approved requisitions
            }]);
            
            $paymentTo->dueInvoices = $paymentTo->requisitions->filter(function ($requisition) use ($paymentInvoiceIds, $invoiceWisePayment) {
                // Calculate paid amount excluding THIS invoice wise payment
                $paidAmount = $requisition->invoiceWisePaymentInvoices()
                    ->whereHas('invoiceWisePayment', function($query) use ($invoiceWisePayment) {
                        $query->where('status', 'approved')
                              ->where('id', '!=', $invoiceWisePayment->id);
                    })
                    ->sum('amount');
                
                $requisition->paid_amount = $paidAmount;
                $requisition->due_amount = $requisition->net_amount - $paidAmount;
                $requisition->invoice_type = get_class($requisition);
                return in_array($requisition->id, $paymentInvoiceIds) || $requisition->due_amount > 0;
            });
        } elseif ($paymentToType === Vendor::class) {
            $paymentTo->load(['officePurchases' => function ($query) {
                $query->where('status', 1); // Approved office purchases
            }]);
            
            $paymentTo->dueInvoices = $paymentTo->officePurchases->filter(function ($purchase) use ($paymentInvoiceIds, $invoiceWisePayment) {
                // Calculate paid amount excluding THIS invoice wise payment
                $paidAmount = $purchase->invoiceWisePaymentInvoices()
                    ->whereHas('invoiceWisePayment', function($query) use ($invoiceWisePayment) {
                        $query->where('status', 'approved')
                              ->where('id', '!=', $invoiceWisePayment->id);
                    })
                    ->sum('amount');
                
                $purchase->paid_amount = $paidAmount;
                $purchase->due_amount = $purchase->bill_amount - $paidAmount;
                $purchase->invoice_type = get_class($purchase);
                $purchase->invoice_no = $purchase->invoice_no;
                $purchase->net_amount = $purchase->bill_amount;
                return in_array($purchase->id, $paymentInvoiceIds) || $purchase->due_amount > 0;
            });
        }

        $data['paymentTo'] = $paymentTo;
        $data['paymentInvoiceIds'] = $paymentInvoiceIds;

        return view("Account::invoice-wise-payments.edit", $data);
    }

    /**
     * Update the specified invoice wise payment
     */
    public function update(Request $request, InvoiceWisePayment $invoiceWisePayment)
    {
        $validate = $request->validate([
            'payment_to_type' => 'required|string',
            'payment_to_id' => 'required|integer',
            'invoice_ids' => 'required|array',
            'invoice_ids.*' => 'required|integer',
            'invoice_types' => 'required|array',
            'invoice_types.*' => 'required|string',
            'pay_amount' => 'required|array',
            'pay_amount.*' => 'nullable|numeric|min:0',
            'status' => 'sometimes|in:pending,verified,approved,denied',
        ]);

        $validate['status'] = $request->input('status', $invoiceWisePayment->status);
        
        if ($request->has('total_amount')) {
            $validate['total_amount'] = $request->input('total_amount');
        }

        // Track status changes for verified_by and approved_by
        if ($validate['status'] === 'verified' && $invoiceWisePayment->status !== 'verified') {
            $invoiceWisePayment->verified_by = auth()->user()->id;
            $invoiceWisePayment->save();
        }
        
        if ($validate['status'] === 'approved' && $invoiceWisePayment->status !== 'approved') {
            $invoiceWisePayment->approved_by = auth()->user()->id;
            $invoiceWisePayment->save();
        }

        $this->service->update($invoiceWisePayment, $validate, $request->all());

        // Determine success message based on status
        $status = $validate['status'];
        if ($status === 'verified') {
            $message = 'Payment Requisition Verified Successfully (Verified).';
        } elseif ($status === 'approved') {
            $message = 'Payment Requisition Approved Successfully (Final).';
        } elseif ($status === 'denied') {
            $message = 'Payment Requisition Denied.';
        } else {
            $message = 'Invoice-wise payment updated successfully.';
        }

        return redirect()->route('account.payments.invoice-wise-payments.index')
            ->with('success', $message);
    }

    /**
     * Approve the specified invoice wise payment
     */
    public function approve(InvoiceWisePayment $invoiceWisePayment)
    {
        $this->service->approve($invoiceWisePayment);
        return redirect()->back()->with('success', 'Payment Requisition Approved Successfully (Final).');
    }

    /**
     * Remove the specified invoice wise payment
     */
    public function destroy(InvoiceWisePayment $invoiceWisePayment)
    {
        if (in_array($invoiceWisePayment->status, ['approved', 'denied'])) {
            return redirect()->back()->with('error', 'Cannot delete approved or denied payment.');
        }
        
        $this->service->delete($invoiceWisePayment);
        return redirect()->route('account.payments.invoice-wise-payments.index')
            ->with('success', 'Invoice-wise payment deleted successfully.');
    }
}