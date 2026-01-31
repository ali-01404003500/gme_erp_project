<?php

namespace Modules\Sales\Controllers;

use App\Http\Controllers\Controller;
use App\Models\AccessControl\CompanyInfo;
use Modules\CRM\Models\Customer\Customer;
use Modules\Inventory\Models\ProductCatalog;
use Modules\Sales\Models\SalesOrder;
use Modules\Sales\Models\SalesOrderDetails;
use Modules\Sales\Models\SalesReturn;
use Modules\Sales\Models\SalesReturnDetail;
use Modules\Sales\Services\SalesReturnService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SalesReturnController extends Controller
{

    /**
     * Service variable
     *
     * @var SalesReturnService
     */
    private $service; 
    function __construct(SalesReturnService $service)
    {
        $this->service = $service;
    }
    
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data['salesReturns'] = $this->service->getAll();
        $data['customers'] = Customer::activeCustomers()->get();

        return view("Sales::sales-return.index", $data);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
         $data['invoices'] = SalesOrder::where('status', 'delivered')->get();

        $data['products'] = SalesOrderDetails::where('sales_order_id', $request->invoice_id)
            ->with(['salesOrder.delivery.deliveryDetails.deliveryStocks'])
            ->get();
        $data['delivery'] = optional(optional($data['products']->first())->salesOrder)->delivery;
//   dd($data['products'], $data['delivery'], $data['invoices']);
        return view('Sales::sales-return.create', $data);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // dd($request->all());
        // Validate main fields
         $license_no = $this->getLicenseNumber(); 

        if($request->input('checks') == null){
            return redirect()->back()->with('error', 'Please select atleast one product');
        }

        $validate = $request->validate([
            'reference_invoice' => 'required|string',
            'customer_id' => 'required|integer|exists:customers,id',
            'return_date' => 'required|date',
            'deliveries_id' => 'required|integer|exists:deliveries,id',
            'sales_order_id' => 'required|integer|exists:sales_orders,id',
            'discount' => 'nullable|numeric|min:0',
            'total_amount' => 'required|numeric|min:0',
            'net_amount' => 'required|numeric|min:0',
            'remarks' => 'nullable|string',
        ]);

        // Validate array fields separately
        $products = $request->validate([
            'product_ids' => 'required|array|min:1',
            'product_ids.*' => 'required|integer',
            'quantity' => 'required|array|min:1',
            'quantity.*' => 'nullable|numeric',
            'price' => 'required|array|min:1',
            'price.*' => 'required|numeric|min:0',
            'unit_discount' => 'required|array|min:1',
            'unit_discount.*' => 'nullable|numeric|min:0',
            'total_discount' => 'required|array|min:1',
            'total_discount.*' => 'nullable|numeric|min:0',
            'amount' => 'required|array|min:1',
            'amount.*' => 'required|numeric|min:0',
            'checks' => 'required|array|min:1',
            'checks.*' => 'nullable|boolean',
        ]);


        $payments = $request->validate([
            'payments_pay_mode' => 'nullable|array',
            'payments_pay_mode.*' => 'required|in:Cash,Cheque,Online Deposit,bKash,Nagad,Rocket,Card,EMI,Card Payment',
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
        $validate['invoice_no'] = $license_no;

        $this->service->store($validate, $products, $payments);
        return redirect()->route('sales.sales-returns.index')->with('success', 'SalesReturn created successfully.');
    }

      public function getLicenseNumber()
    {
        $today = date('Y-m-d');
        
        $customer_count = SalesReturn::whereDate(DB::raw('DATE(created_at)'), $today)->count();

        $authUser = auth()->user()->id;
        $authUserBranch = auth()->user()->branch_id;
        $authUserBranchType = auth()->user()->branch->branch_type_id;

        $licensesToday = SalesReturn::whereDate(DB::raw('DATE(created_at)'), $today)
            ->where('created_by', $authUser)
            ->count();
        
        // Generate license number with the appropriate format
        $licenseNumber = sprintf(
            'SCT-%02d-SC-%02d-%s-USR-%06d-PR-%06d',
            $authUserBranch,
            $authUserBranchType,
            date('Ymd'),
            $authUser,
            $licensesToday + 1
        );
        
        return $licenseNumber;
    }

    /**
     * Display the specified resource.
     */
    public function show( $id)
    {
        $data['salesReturn'] = $this->service->show($id);
        $data['company_info'] = CompanyInfo::first();

        return view("Sales::sales-return.show", $data);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(SalesReturn $salesReturn)
    {
        $data['salesReturn'] = $salesReturn;
        return view("Sales::sales-return.edit", $data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $salesReturn = $this->service->show($id);
        if($request->input('checks') == null){
            return redirect()->back()->with('error', 'Please select atleast one product');
        }

        $validate = $request->validate([
            'reference_invoice' => 'required|string',
            'customer_id' => 'required|integer|exists:customers,id',
            'return_date' => 'required|date',
            'deliveries_id' => 'nullable|integer|exists:deliveries,id',
            'sales_order_id' => 'nullable|integer|exists:sales_orders,id',
            'discount' => 'nullable|numeric|min:0',
            'total_amount' => 'required|numeric|min:0',
            'net_amount' => 'required|numeric|min:0',
            'remarks' => 'nullable|string',
        ]);

        // Validate array fields separately
        $products = $request->validate([
            'product_ids' => 'required|array|min:1',
            'product_ids.*' => 'required|integer',
            'quantity' => 'required|array|min:1',
            'quantity.*' => 'nullable|numeric',
            'price' => 'required|array|min:1',
            'price.*' => 'required|numeric|min:0',
            'unit_discount' => 'required|array|min:1',
            'unit_discount.*' => 'nullable|numeric|min:0',
            'total_discount' => 'required|array|min:1',
            'total_discount.*' => 'nullable|numeric|min:0',
            'amount' => 'required|array|min:1',
            'amount.*' => 'required|numeric|min:0',
            'checks' => 'required|array|min:1',
            'checks.*' => 'nullable|boolean',
        ]);
        $payments = $request->validate([
            'payments_pay_mode' => 'nullable|array',
            'payments_pay_mode.*' => 'required|in:Cash,Cheque,Online Deposit,bKash,Nagad,Rocket,Card,EMI,Card Payment',
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
        $this->service->update($salesReturn, $validate, $products,   $payments);

        return redirect()->route('sales.sales-returns.index')->with('success', 'SalesReturn updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $salesReturn = $this->service->show($id);
        $this->service->delete($salesReturn);
        return redirect()->route('sales.sales-returns.index')->with('success', 'SalesReturn deleted successfully.');
    }

    public function approve($id)
    {
        $data['salesReturn'] = SalesReturn::where('status', 'Pending')->findOrFail($id);

        return view("Sales::sales-return.approve", $data);

    }

    public function selectStock($product_id, $sales_order_id, $sales_return_id)
    {
        $data["product"] = ProductCatalog::find($product_id);
        $salesOrder = SalesOrder::with(['delivery.deliveryDetails.deliveryStocks'])->find($sales_order_id);
        $data['salesOrder'] = $salesOrder;
        $data['stock'] = 0;

        if ($salesOrder && $salesOrder->delivery) {
            // All delivery stocks for this product
            $stocks = $salesOrder->delivery->deliveryDetails
                ->where('product_id', $product_id)
                ->pluck('deliveryStocks')
                ->flatten();

            // All returned stocks for this product
            $returnedStocks = SalesReturn::where('sales_order_id', $salesOrder->id)
                ->where('status', 'Returned')
                ->whereHas('salesReturnDetails', function ($query) use ($product_id) {
                    $query->whereHas('salesReturnStock', function ($subQuery) use ($product_id) {
                        $subQuery->where('product_id', $product_id);
                    });
                })
                ->with(['salesReturnDetails.salesReturnStock' => function ($query) use ($product_id) {
                    $query->where('product_id', $product_id);
                }])
                ->get()
                ->pluck('salesReturnDetails')
                ->flatten()
                ->pluck('salesReturnStock')
                ->flatten();

            // Process available stock
            $filteredStocks = collect();

            if ($data["product"]->is_serial_product) {
                // For serial-based products: exclude by serial_no
                $returnedSerials = $returnedStocks->pluck('serial_no')->filter()->unique()->toArray();
                $filteredStocks = $stocks->filter(function ($stock) use ($returnedSerials) {
                    return !in_array($stock->serial_no, $returnedSerials);
                });
            } else {
                // For lot-based products: reduce quantities
                $groupedReturned = $returnedStocks
                    ->groupBy('lot_no')
                    ->map(function ($group) {
                        return $group->sum('quantity');
                    });

                $filteredStocks = $stocks->map(function ($stock) use ($groupedReturned) {
                    if ($stock->lot_no && isset($groupedReturned[$stock->lot_no])) {
                        $availableQty = ($stock->quantity ?? 0) - $groupedReturned[$stock->lot_no];
                        if ($availableQty > 0) {
                            $stock->quantity = $availableQty;
                            return $stock;
                        }
                        return null;
                    }
                    return $stock;
                })->filter();
            }

            $data['stocks'] = $filteredStocks->values(); // Reindex
        }

        $data['total_stock'] = SalesReturnDetail::where('sales_return_id', $sales_return_id)
            ->where('product_id', $product_id)
            ->sum('quantity');

        return view('Sales::sales-return.select-stock', $data);
    }


    public function approveStore(Request $request, $id)
    {
        // dd($request->all());
        $validate = $request->validate([ 
            'sales_return_id' => 'required|exists:sales_returns,id',
        ]);
        $validateDetails = $request->validate([
            'product_ids.*' => 'required|exists:product_catalogs,id',
            'quantity.*' => 'nullable|numeric',
            'return_qty.*' => 'nullable|numeric',
        ]);

        foreach($validateDetails['return_qty'] as $key => $salesQuantity){
            if($validateDetails['return_qty'][$key] != $validateDetails['quantity'][$key]){
                return redirect()->back()->withErrors(['quantity.'.$key => 'The sales quantity and quantity of product '.$key.' should be same.']);
            }
        }
        
        $deliveryStockDetails = $request->validate([
            'lot_no.*.*' => 'nullable|string',
            'lots_quantity.*.*' => 'nullable|numeric',
            'serial_no.*.*' => 'nullable|string',
        ]);
        $this->service->approveStore($validate, $validateDetails, $deliveryStockDetails);
        return redirect()->route('sales.sales-returns.index')->with('success', 'SalesReturn Approved successfully.');
    }
}
