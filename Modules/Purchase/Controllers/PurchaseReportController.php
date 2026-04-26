<?php
namespace Modules\Purchase\Controllers;

use App\Http\Controllers\Controller;
use App\Models\AccessControl\Branch;
use App\Models\AccessControl\CompanyInfo;
use App\Models\User;
use Illuminate\Http\Request;
use Modules\CRM\Models\Customer\Customer;
use Modules\Inventory\Models\ProductCatalog;
use Modules\Inventory\Services\ExportService;
use Modules\Purchase\Models\PurchaseReturn;
use Modules\Purchase\Models\Requisition;
use Modules\Purchase\Models\Supplier;

class PurchaseReportController extends Controller
{
    // public function index(Request $request)
    // {
    //     // Base query for requisitions
    //     $query = Requisition::with([
    //         'customer',
    //         'supplier',
    //         'warehouse',
    //         'requisitionDetails.product',
    //         'createdBy',
    //         'paymentDetails.makePayment',
    //         'transactions',
    //         'invoiceWisePaymentInvoices.invoiceWisePayment' // NEW
    //     ]);

    //     $query = $this->applyRequisitionFilters($query, $request);

    //     if ($request->filled('export_type')) {
    //         $requisitions = $query->orderBy('created_at', 'desc')->get();
    //     } else {
    //         $requisitions = $query->orderBy('created_at', 'desc')->paginate(50);
    //     }

    //     // Base query for Purchase Returns
    //     $returnQuery = PurchaseReturn::with([
    //         'supplier',
    //         'requisition.warehouse',
    //         'purchaseReturnDetails.product',
    //         'createdBy'
    //     ]);

    //     $returnQuery = $this->applyReturnFilters($returnQuery, $request);
    //     $purchaseReturns = $returnQuery->orderBy('created_at', 'desc')->get();

    //     // Combine data for unified report
    //     $reportData = collect();

    //     // Add Purchases
    //     if (!$request->filled('invoice_type') || $request->invoice_type !== 'return') {
    //         foreach ($requisitions as $requisition) {
    //             if ($request->filled('product_id')) {
    //                 $hasProduct = $requisition->requisitionDetails->contains('product_id', $request->product_id);
    //                 if (!$hasProduct) continue;
    //             }

    //             $reportData->push([
    //                 'type' => 'Purchase',
    //                 'data' => $requisition,
    //                 'date' => $requisition->invoice_date
    //             ]);
    //         }
    //     }

    //     // Add Returns
    //     if (!$request->filled('invoice_type') || $request->invoice_type !== 'purchase') {
    //         foreach ($purchaseReturns as $return) {
    //             if ($request->filled('product_id')) {
    //                 $hasProduct = $return->purchaseReturnDetails->contains('product_id', $request->product_id);
    //                 if (!$hasProduct) continue;
    //             }

    //             $reportData->push([
    //                 'type' => 'Purchase Return',
    //                 'data' => $return,
    //                 'date' => $return->return_date
    //             ]);
    //         }
    //     }

    //     $reportData = $reportData->sortByDesc('date')->values();

    //     $data = [
    //         'reportData' => $reportData,
    //         'requisitions' => $requisitions,
    //         'purchaseReturns' => $purchaseReturns,
    //         'suppliers' => Supplier::where('status', 1)->get(),
    //         'customers' => Customer::activeCustomers()->get(),
    //         'branches' => Branch::all(),
    //         'products' => ProductCatalog::where('status', 'active')->get(),
    //         'users' => User::whereDoesntHave('roles', function ($query) {
    //             $query->where('slug', 'customer');
    //         })->get(),
    //         'company_info' => CompanyInfo::first(),
    //     ];

    //     if ($request->filled('export_type')) {
    //         $filename = 'Purchase_Report_' . now()->format('Y_m_d_His');
    //         return (new ExportService())->exportData(
    //             $data,
    //             'Purchase::reports.export.',
    //             $filename,
    //             $request->export_type
    //         );
    //     }

    //     return view('Purchase::reports.purchase-reports', $data);
    // }

    /**
     * Apply filters to requisition query
     */

    public function index(Request $request)
    {
        // Optimized Requisition query with specific columns in eager loading
        $query = Requisition::with([
            'customer'                   => function ($q) {
                $q->select('id', 'company_name');
            },
            'supplier'                   => function ($q) {
                $q->select('id', 'company_name');
            },
            'warehouse'                  => function ($q) {
                $q->select('id', 'name');
            },
            'requisitionDetails'         => function ($q) {
                $q->select('id', 'requisition_id', 'product_id', 'quantity', 'price', 'amount');
            },
            'requisitionDetails.product' => function ($q) {
                $q->select('id', 'name');
            },
            'createdBy'                  => function ($q) {
                $q->select('id', 'name');
            },
            'paymentDetails',
        ]);

        $query = $this->applyRequisitionFilters($query, $request);

        if ($request->filled('export_type')) {
            $requisitions = $query->orderBy('created_at', 'desc')->get();
        } else {
            $requisitions = $query->orderBy('created_at', 'desc')->paginate(50);
        }

        // Optimized Purchase Returns query
        $returnQuery = PurchaseReturn::with([
            'supplier'                      => function ($q) {
                $q->select('id', 'company_name');
            },
            'requisition'                   => function ($q) {
                $q->select('id', 'branch_id', 'requisition_no');
            },
            'requisition.warehouse'         => function ($q) {
                $q->select('id', 'name');
            },
            'purchaseReturnDetails'         => function ($q) {
                $q->select('id', 'purchase_return_id', 'product_id', 'quantity', 'price', 'amount');
            },
            'purchaseReturnDetails.product' => function ($q) {
                $q->select('id', 'name');
            },
            'createdBy'                     => function ($q) {
                $q->select('id', 'name');
            },
        ]);

        $returnQuery     = $this->applyReturnFilters($returnQuery, $request);
        $purchaseReturns = $returnQuery->orderBy('created_at', 'desc')->get();

        // Combine data for unified report
        $reportData = collect();

        // Add Purchases
        if (! $request->filled('invoice_type') || $request->invoice_type !== 'return') {
            foreach ($requisitions as $requisition) {
                if ($request->filled('product_id')) {
                    $hasProduct = $requisition->requisitionDetails->contains('product_id', $request->product_id);
                    if (! $hasProduct) {
                        continue;
                    }

                }

                $reportData->push([
                    'type' => 'Purchase',
                    'data' => $requisition,
                    'date' => $requisition->invoice_date,
                ]);
            }
        }

        // Add Returns
        if (! $request->filled('invoice_type') || $request->invoice_type !== 'purchase') {
            foreach ($purchaseReturns as $return) {
                if ($request->filled('product_id')) {
                    $hasProduct = $return->purchaseReturnDetails->contains('product_id', $request->product_id);
                    if (! $hasProduct) {
                        continue;
                    }

                }

                $reportData->push([
                    'type' => 'Purchase Return',
                    'data' => $return,
                    'date' => $return->return_date,
                ]);
            }
        }

        $reportData = $reportData->sortByDesc('date')->values();

        // OPTIMIZED: Filter data with select only needed columns
        $data = [
            'reportData'      => $reportData,
            'requisitions'    => $requisitions,
            'purchaseReturns' => $purchaseReturns,
            'suppliers'       => Supplier::where('status', 1)->select('id', 'company_name')->get(),
            'customers'       => Customer::activeCustomers()->select('id', 'company_name')->get(),
            'branches'        => Branch::select('id', 'name')->get(),
            'products'        => ProductCatalog::where('status', 'active')->select('id', 'name')->get(),
            'users'           => User::whereDoesntHave('roles', function ($query) {
                $query->where('slug', 'customer');
            })->select('id', 'name')->get(),
            'company_info'    => CompanyInfo::first(),
        ];

        if ($request->filled('export_type')) {
            $filename = 'Purchase_Report_' . now()->format('Y_m_d_His');
            return (new ExportService())->exportData(
                $data,
                'Purchase::reports.export.',
                $filename,
                $request->export_type
            );
        }

        return view('Purchase::reports.purchase-reports', $data);
    }
    private function applyRequisitionFilters($query, $request)
    {
        // Supplier filter
        if ($request->filled('supplier_id')) {
            $query->where('supplier_id', $request->supplier_id);
        }

        // Customer filter
        if ($request->filled('customer_id')) {
            $query->where('customer_id', $request->customer_id);
        }

        // Branch filter
        if ($request->filled('branch_id')) {
            $query->where('branch_id', $request->branch_id);
        }

        // Invoice ID filter
        if ($request->filled('requisition_no')) {
            $query->where('requisition_no', 'LIKE', '%' . $request->requisition_no . '%');
        }

        // User filter
        if ($request->filled('user_id')) {
            $query->where('created_by', $request->user_id);
        }

        // Status filter
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Date range filter
        if ($request->filled('from') && $request->filled('to')) {
            $query->whereBetween('invoice_date', [$request->from, $request->to]);
        } elseif ($request->filled('from')) {
            $query->where('invoice_date', '>=', $request->from);
        } elseif ($request->filled('to')) {
            $query->where('invoice_date', '<=', $request->to);
        }

        // Product filter (handled in main method due to relationship)
        if ($request->filled('product_id')) {
            $query->whereHas('requisitionDetails', function ($q) use ($request) {
                $q->where('product_id', $request->product_id);
            });
        }

        // Price range filter
        if ($request->filled('min_price')) {
            $query->where('net_amount', '>=', $request->min_price);
        }

        if ($request->filled('max_price')) {
            $query->where('net_amount', '<=', $request->max_price);
        }

        return $query;
    }

    /**
     * Apply filters to purchase return query
     */
    private function applyReturnFilters($query, $request)
    {
        // Supplier filter
        if ($request->filled('supplier_id')) {
            $query->where('supplier_id', $request->supplier_id);
        }

        // User filter
        if ($request->filled('user_id')) {
            $query->where('created_by', $request->user_id);
        }

        // Date range filter
        if ($request->filled('from') && $request->filled('to')) {
            $query->whereBetween('return_date', [$request->from, $request->to]);
        } elseif ($request->filled('from')) {
            $query->where('return_date', '>=', $request->from);
        } elseif ($request->filled('to')) {
            $query->where('return_date', '<=', $request->to);
        }

        // Product filter
        if ($request->filled('product_id')) {
            $query->whereHas('purchaseReturnDetails', function ($q) use ($request) {
                $q->where('product_id', $request->product_id);
            });
        }

        // Price range filter
        if ($request->filled('min_price')) {
            $query->where('net_amount', '>=', $request->min_price);
        }

        if ($request->filled('max_price')) {
            $query->where('net_amount', '<=', $request->max_price);
        }

        // Branch filter (through requisition relationship)
        if ($request->filled('branch_id')) {
            $query->whereHas('requisition', function ($q) use ($request) {
                $q->where('branch_id', $request->branch_id);
            });
        }

        // Invoice number filter
        if ($request->filled('requisition_no')) {
            $query->where('invoice_no', 'LIKE', '%' . $request->requisition_no . '%');
        }

        return $query;
    }

    /**
     * Get payment details for a specific requisition
     */
    public function getPaymentDetails(Request $request, $requisitionId)
    {
        $requisition = Requisition::with('paymentDetails.makePayment')->findOrFail($requisitionId);

        $payments = $requisition->paymentDetails->map(function ($detail) {
            return [
                'payment_id'     => $detail->makePayment->payment_id ?? 'N/A',
                'date'           => $detail->makePayment->date ?? null,
                'amount'         => $detail->amount,
                'pay_mode'       => $detail->pay_mode,
                'transaction_id' => $detail->transaction_id,
                'verified'       => $detail->verified,
            ];
        });

        return response()->json([
            'success'      => true,
            'payments'     => $payments,
            'total_paid'   => $requisition->paymentDetails->sum('amount'),
            'total_amount' => $requisition->net_amount,
            'due_amount'   => $requisition->net_amount - $requisition->paymentDetails->sum('amount'),
        ]);
    }

    /**
     * Update editable field
     */
    /**
     * Update editable field
     */
    public function updateField(Request $request)
    {
        $request->validate([
            'id'          => 'required',
            'type'        => 'required|in:remarks,reference_invoice,creation_date',
            'value'       => 'nullable',
            'record_type' => 'required|in:requisition,purchase_return', // Add record_type to distinguish between Requisition and PurchaseReturn
        ]);

        try {
            $record_type = $request->record_type;
            $id          = $request->id;
            $type        = $request->type;
            $value       = $request->value;

            if ($record_type === 'requisition') {
                $requisition = Requisition::findOrFail($id);

                // For reference_invoice in Requisition, check if it's editable
                if ($type === 'reference_invoice') {
                    // Check if there is an associated PurchaseReturn with a reference_invoice
                    $hasReturnWithReference = PurchaseReturn::where('requisition_id', $id)
                        ->whereNotNull('reference_invoice')
                        ->exists();

                    if (! $hasReturnWithReference) {
                        return response()->json([
                            'success' => false,
                            'message' => 'Reference invoice is read-only for purchases without associated returns.',
                        ], 403);
                    }
                }

                switch ($type) {
                    case 'remarks':
                        $requisition->description = $value;
                        break;
                    case 'reference_invoice':
                        $requisition->purchase_invoice = $value;
                        break;
                    case 'creation_date':
                        // Validate date format
                        $date                      = \Carbon\Carbon::parse($value)->format('Y-m-d');
                        $requisition->invoice_date = $date;
                        break;
                }

                $requisition->save();
            } elseif ($record_type === 'purchase_return') {
                $purchaseReturn = PurchaseReturn::findOrFail($id);

                switch ($type) {
                    case 'remarks':
                        $purchaseReturn->remarks = $value;
                        break;
                    case 'reference_invoice':
                        $purchaseReturn->reference_invoice = $value;
                        break;
                    case 'creation_date':
                        // Validate date format
                        $date                        = \Carbon\Carbon::parse($value)->format('Y-m-d');
                        $purchaseReturn->return_date = $date;
                        break;
                }

                $purchaseReturn->save();
            }

            return response()->json([
                'success' => true,
                'message' => 'Field updated successfully',
                'value'   => $value,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update field: ' . $e->getMessage(),
            ], 500);
        }
    }
}
