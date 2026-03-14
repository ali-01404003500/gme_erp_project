<?php

namespace Modules\Sales\Controllers\Api;


use App\Http\Controllers\Controller;
use App\Models\AccessControl\CompanyInfo;
use Modules\Inventory\Models\Product;
use Modules\Inventory\Models\ProductCatalog;
use Modules\Sales\Models\Courier;
use Modules\Sales\Models\SalesOrder;
use App\Services\Notifications\GeneralNotificationService;
use Modules\Sales\Services\SalesOrderService;
use Illuminate\Http\Request;
use Dompdf\Dompdf;
use Dompdf\Options;
use Illuminate\Support\Facades\DB;
use Modules\Account\Models\Setup\Bank;
use Modules\Account\Models\Setup\BankBranch;
use Modules\CRM\Models\Customer\Customer;
use Modules\CRM\Models\Customer\CustomerSetting;
use Modules\LocationManager\Models\Area;
use Modules\Services\Models\Service;

class SalesOrderController extends Controller
{

    /**
     * Service variable
     *
     * @var SalesOrderService
     */
    private $service;
    /**
     * GeneralNotificationService variable
     *
     * @var GeneralNotificationService
     */
    private $generalNotificationService;
    function __construct(SalesOrderService $service, GeneralNotificationService $generalNotificationService)
    {
        $this->service = $service;
        $this->generalNotificationService = $generalNotificationService;

        $this->middleware('permited')->only(['store', 'update', 'destroy']);

    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $salesOrders = $this->service->getAll();
        // dd($salesOrders);
        $companyInfo = CompanyInfo::first();

        // Prepare data for JSON response
        $data = [
            'status' => 'success',
            'message' => 'Sales orders retrieved successfully.',
            'data' => [
                'salesOrders' => $salesOrders,
                'company_info' => $companyInfo,
            ],
        ];

        return response()->json($data, 200);
    }



    public function getBranchByBank(Request $request)
    {
        $branches = BankBranch::where('bank_id', $request->id)->get();
        return response()->json($branches);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        /**
         * ✅ Step 1: Validate Main Sales Order Data
         */
        $validate = $request->validate([
            'customer_id' => 'required|integer|exists:customers,id',
            'invoice_date' => 'required|date',
            'total_amount' => 'nullable|numeric|min:0',
            'discount' => 'nullable|numeric|min:0',
            'commission' => 'nullable|numeric|min:0',
            'total' => 'nullable|numeric|min:0',
            'vat' => 'nullable|numeric|min:0',
            'net_amount' => 'nullable|numeric|min:0',
            'paid_amount' => 'nullable|numeric|min:0',
            'due_amount' => 'nullable|numeric|min:0',
            'advance_amount' => 'nullable|numeric|min:0',
            'remarks' => 'nullable|string',
            'status' => 'nullable|string|in:approved,pending',
            'is_shipment' => 'nullable|boolean',
            'delivery_date' => 'nullable|date',
            'is_offer' => 'nullable|boolean',
            'sales_type' => 'nullable|string',
            'reference_id' => $request->input('sales_type') == 'free_sales'
                ? 'required|integer|exists:sales_orders,id'
                : 'nullable'
        ]);

        $validate['discount'] = $validate['discount'] ?? 0;
        $validate['commission'] = $validate['commission'] ?? 0;
        $validate['vat'] = $validate['vat'] ?? 0;


        /**
         * ✅ Step 2: Validate Products (products_details from JSON)
         */
        $salesOrderDetails = $request->validate([
            'products_details' => 'required|array|min:1',
            'products_details.*.product_id' => 'required|integer|exists:product_catalogs,id',
            'products_details.*.quantity' => 'required|numeric|min:1',
            'products_details.*.price' => 'required|numeric|min:0',
            'products_details.*.unit_discount' => 'required|numeric|min:0',
        ]);

        $salesOrderDetails = [
            'product_ids'    => collect($request->products_details)->pluck('product_id')->toArray(),
            'quantity'       => collect($request->products_details)->pluck('quantity')->toArray(),
            'price'          => collect($request->products_details)->pluck('price')->toArray(),
            'unit_discount'  => collect($request->products_details)->pluck('unit_discount')->toArray(),
            'total_discount' => collect($request->products_details)->map(fn($p) => $p['unit_discount'] * $p['quantity'])->toArray(),
            'amount'         => collect($request->products_details)->map(fn($p) => ($p['price'] - $p['unit_discount']) * $p['quantity'])->toArray(),
        ];


        /**
         * ✅ Step 3: Validate Shipment
         */
        $salesOrderShipments = [];
        if ($validate['is_shipment'] ?? false) {
            $salesOrderShipments = $request->validate([
                'shipment.courier_id' => 'required|integer|exists:couriers,id',
                'shipment.area_id' => 'required',
                'shipment.address' => 'required|string',
                'shipment.contact_person_name' => 'required|string',
                'shipment.contact_person_number' => 'required|string',
                'shipment.condition' => 'nullable|boolean',
                'shipment.additional_amount' => 'nullable|numeric|min:0',
                'shipment.condition_remarks' => 'nullable|string',
            ])['shipment'];
        } else {
            $validate['is_shipment'] = 0;
        }


        /**
         * ✅ Step 4: Validate Payments
         */
        $payments = $request->validate([
            'payments' => 'nullable|array',
            'payments.*.payments_pay_mode' => 'required|in:Cash,Cheque,Online Deposit,bKash,Nagad,Rocket,Card,EMI,Card Payment',
            'payments.*.payments_bank_id' => 'nullable|integer',
            'payments.*.payments_branch_id' => 'nullable|integer|exists:bank_branches,id',
            'payments.*.payments_emi_id' => 'nullable|integer|exists:e_m_i_entries,id',
            'payments.*.payments_transaction_id' => 'nullable|string',
            'payments.*.payments_date' => 'required|date',
            'payments.*.payments_amount' => 'required|numeric|min:0',
            'payments.*.payments_attachments' => 'nullable|string',
            'payments.*.payments_verified' => 'nullable|in:0,1',
            'payments.*.payments_remark' => 'nullable|string',
        ]);

        $payments = [
            'payments_pay_mode'     => collect($request->payments)->pluck('payments_pay_mode')->toArray(),
            'payments_bank_id'      => collect($request->payments)->pluck('payments_bank_id')->toArray(),
            'payments_branch_id'    => collect($request->payments)->pluck('payments_branch_id')->toArray(),
            'payments_emi_id'       => collect($request->payments)->pluck('payments_emi_id')->toArray(),
            'payments_transaction_id' => collect($request->payments)->pluck('payments_transaction_id')->toArray(),
            'payments_amount'       => collect($request->payments)->pluck('payments_amount')->toArray(),
            'payments_date'         => collect($request->payments)->pluck('payments_date')->toArray(),
            'payments_attachments'  => collect($request->payments)->pluck('payments_attachments')->toArray(),
            'payments_verified'     => collect($request->payments)->pluck('payments_verified')->toArray(),
            'payments_remark'       => collect($request->payments)->pluck('payments_remark')->toArray(),
        ];



        /**
         * ✅ Step 5: Auto-calc values if not provided
         */
        $totalAmount = $validate['total_amount'] ?? collect($request->products_details)->sum(fn($p) => $p['price'] * $p['quantity']);
        $paidAmount = $validate['paid_amount'] ?? array_sum($payments['payments_amount'] ?? []);
        $netAmount = $validate['net_amount'] ?? ($totalAmount - $validate['discount'] + $validate['vat']);
        $dueAmount = $validate['due_amount'] ?? max($netAmount - $paidAmount, 0);
        $advanceAmount = $validate['advance_amount'] ?? ($paidAmount > $netAmount ? $paidAmount - $netAmount : 0);

        $validate['total_amount'] = $totalAmount;
        $validate['total'] = $validate['total'] ?? $totalAmount;
        $validate['net_amount'] = $netAmount;
        $validate['status'] = 'pending';
    // $validate['paid_amount'] = $paidAmount;
    // $validate['due_amount'] = $dueAmount;
    // $validate['advance_amount'] = $advanceAmount;


    /**
     * ✅ Step 6: Call the Service
     */
    // $result = $salesOrderService->store($validate, $salesOrderDetails, $salesOrderShipments, $payments);

    // return response()->json([
    //     'message' => 'Sales order created successfully',
    //     'data' => $result
    // ], 201);

        try {
                /**
                 * ✅ Step 5: Call the Service
                */
            $result = $this->service->store($validate, $salesOrderDetails, $salesOrderShipments, $payments);

            $requiredOtp = $request->validate([
                'required_otp' => 'nullable|array',
            ]); 
            // dd($requiredOtp);

            foreach ($requiredOtp['required_otp'] ?? [] as $otp) {
                $title = key($otp);
                $value = $otp[$title];
                // dd($title, $value);
                $result['salesOrder']->otpVerifications()->create([
                    'title' => $title,
                    'request_value' => $value
                ]);
            }
            $this->generalNotificationService->store([
                'title' => 'New Sales Order Added via APP',
                'description' => 'New Sales Order Added via APP needed approval',
                'action' => $this->generalNotificationService->actionBuilder(SalesOrderController::class, 'approve', [$result['salesOrder']->id]),
            ], $this->generalNotificationService->getPermittedUsers('sales.sales-orders.approve'));

            return response()->json([
                'status' => 'success',
                'message' => 'SalesOrder created successfully.',
                'data' => $result,
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to create SalesOrder: ' . $e->getMessage(),
                'data' => null,
            ], 500);
        }
    }




    public function getCustomerSetting(Request $request)
    {

        $data['customers'] = CustomerSetting::where('customer_id', $request->id)
            ->with('customer', 'customer.area', 'customerSettingBrokers', 'customerSettingDiscounts', 'customerSettingFixedDiscounts', 'customerSettingSelfCommissions', 'customerSettingSelfCommissions', 'customer.customerType')->first();
        return response()->json($data);
    }

    public function getSalesDiscount(Request $request)
    {
        $customerSetting = CustomerSetting::with(["customerSettingBrokers", "customerSettingDiscounts", "customerSettingFixedDiscounts", "customerSettingSelfCommissions"])->where('customer_id', $request->customer_id)->first();
        $productSetting = Product::where('product_catalog_id', $request->product_id)->first();
        $percentage = null;
        $productPrice = null;
        $discountRange = null;
        if ($productSetting && $customerSetting) {
            if ($productSetting->discount_type == "Percentage") {
                if ($customerSetting->discount_type == 1 || $customerSetting->discount_type == 3) {// percentage

                    // dd($customerSetting);
                    $percentage = $customerSetting->customerSettingDiscounts->where("percentage_type", $productSetting->product_tag_id)->first();

                }
            } else if ($productSetting->discount_type == "Fixed") {
                if ($customerSetting->discount_type == 2 || $customerSetting->discount_type == 3) {// fixed
                    $productPrice = $customerSetting->customerSettingFixedDiscounts->where('product_id', $request->product_id)->first();
                }
                if (!$productPrice) {
                    $discountRange = ['min' => $productSetting->min_discount, 'max' => $productSetting->max_discount];
                }
            }
        }
        return response()->json(['customerSetting' => $customerSetting, 'productSetting' => $productSetting, 'discount' => ['percentage' => $percentage, 'productPrice' => $productPrice, 'discountRange' => $discountRange]]);
    }

    /**
     * Display the specified resource.
     */
    public function show($id, Request $request)
    {
        $data['salesOrder'] = $this->service->show($id);
        $data['salesOrder']->payments->transform(function ($payment) {
            $pays =  $payment;
          $pays  ['bank'] =$payment->bank;
              $pays  ['branch'] =$payment->branch;

            return $pays;
        });
        // dd();
        $data['company_info'] = CompanyInfo::first();

        return response()->json($data);
    }


    public function approve($sales_order_id)
    {

        return redirect()->route('sales.sales-orders.edit', [
            'sales_order' => $sales_order_id,
            'approved' => 1,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'customer_id' => 'required|integer|exists:customers,id',
            'invoice_date' => 'required|date',
            'products_details' => 'required|array',
            'products_details.*.product_id' => 'required|integer|exists:product_catalogs,id',
            'products_details.*.quantity' => 'required|numeric|min:0',
            'products_details.*.price' => 'required|numeric|min:0',
            'products_details.*.unit_discount' => 'nullable|numeric|min:0',
            'products_details.*.sales_order_detail_id' => 'nullable|numeric|min:0',
            'remarks' => 'nullable|string',
                        'delivery_date' => 'nullable|date',
                        'sales_type' => 'nullable',

        ]);

        $salesOrderDetailsIds=[];
        $productIds = [];
        $quantities = [];
        $prices = [];
        $unitDiscounts = [];
        $totalDiscounts = [];
        $amounts = [];

        $totalAmount = 0;
        $totalDiscount = 0;
        $vat = 0;
        $commission = 0;

        foreach ($validatedData['products_details'] as $product) {
            $quantity = $product['quantity'];
            $price = $product['price'];
            $unitDiscount = $product['unit_discount'] ?? 0;

            $amount = ($price * $quantity) - ($unitDiscount * $quantity);
            $productTotalDiscount = $unitDiscount * $quantity;


            $salesOrderDetailsIds[] = $product['sales_order_details_id'] ?? null;
            $productIds[] = $product['product_id'];
            $quantities[] = $quantity;
            $prices[] = $price;
            $unitDiscounts[] = $unitDiscount;
            $totalDiscounts[] = $productTotalDiscount;
            $amounts[] = $amount;


            $totalAmount += $amount;
            $totalDiscount += $productTotalDiscount;
        }

        $salesOrderDetails = [
            'sales_order_detail_id' => $salesOrderDetailsIds,
            'product_ids' => $productIds,
            'quantity' => $quantities,
            'price' => $prices,
            'unit_discount' => $unitDiscounts,
            'total_discount' => $totalDiscounts,
            'amount' => $amounts,
        ];

        $netAmount = $totalAmount - $totalDiscount + $vat;

        $salesOrderData = [
            'customer_id' => $validatedData['customer_id'],
            'invoice_date' => $validatedData['invoice_date'],
            'total_amount' => $totalAmount,
            'discount' => $totalDiscount,
            'commission' => $commission,
            'total' => $totalAmount,
            'vat' => $vat,
            'net_amount' => $netAmount,
            'remarks' => $validatedData['remarks'] ?? null,
            'status' => 'pending',
            'is_shipment' => 0,
            'is_courier' => 0,
            'sales_type' => $validatedData['sales_type'] ?? null,
            'reference_id' => null,
            'delivery_date' => $validatedData['delivery_date'] ?? null,
        ];

        $salesOrderShipments = [];

        try {
            $salesOrder = SalesOrder::findOrFail($id);
            $this->service->update($salesOrder, $salesOrderData, $salesOrderDetails, $salesOrderShipments, $paymentDetails = []);

            return response()->json([
                'status' => 'success',
                'message' => 'SalesOrder updated successfully.',
                'data' => $salesOrder,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to update SalesOrder: ' . $e->getMessage(),
                'data' => null,
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(SalesOrder $salesOrder)
    {
        try {
            $this->service->delete($salesOrder);
            return response()->json([
                'status' => 'success',
                'message' => 'SalesOrder deleted successfully.',
                'data' => null,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to delete SalesOrder: ' . $e->getMessage(),
                'data' => null,
            ], 500);
        }
    }

    public function countSalesOrder()
    {
        return response()->json(["count" => $this->service->countSalesOrder(), "current_month" => $this->service->countSalesOrderCurrentMonth(), "previous_month" => $this->service->countSalesOrderPreviousMonth()]);
    }

    public function countTotalSales()
    {
        return response()->json(["count" => $this->service->countTotalSales(), "current_month" => $this->service->countTotalSalesCurrentMonth(), "previous_month" => $this->service->countTotalSalesPreviousMonth()]);
    }

    //get all sales order with ids
    public function getAllSalesOrder()
    {
        $request = request();

        if ($request->has('customer_id')) {
            $customerId = $request->input('customer_id');
            $salesOrders = SalesOrder::where('customer_id', $customerId)->select(['id', 'sales_order_id'])->get();
        } else {
            $salesOrders = SalesOrder::select(['id', 'sales_order_id'])->get();
        }

        return  response()->json([
            'status' => 'success',
            'message' => 'Sales orders retrieved successfully.',
            'data' => $salesOrders,
        ]);
    }

}
