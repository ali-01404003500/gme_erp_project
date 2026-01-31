<?php

namespace Modules\Sales\Controllers\Api;

use App\Http\Controllers\Controller;
use Modules\Sales\Models\SalesRequisition;
use Modules\Sales\Services\SalesRequisitionService;
use Illuminate\Http\Request;
use App\Models\AccessControl\CompanyInfo;
use App\Services\Notifications\GeneralNotificationService;


class SalesRequisitionController extends Controller
{
    /**
     * Service variable
     *
     * @var SalesRequisitionService
     */
    private $service;
    /**
     * GeneralNotificationService variable
     *
     * @var GeneralNotificationService
     */
    private $generalNotificationService;

    function __construct(SalesRequisitionService $service, GeneralNotificationService $generalNotificationService)
    {
        $this->service = $service;
        $this->generalNotificationService = $generalNotificationService;
        $this->middleware('permited')->only(['index','store', 'update', 'destroy']);
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $salesRequisitions = $this->service->getAll();
        $companyInfo = CompanyInfo::first();

        $data = [
            'status' => 'success',
            'message' => 'Sales requisitions retrieved successfully.',
            'data' => [
                'salesRequisitions' => $salesRequisitions,
                'company_info' => $companyInfo,
            ],
        ];

        return response()->json($data, 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'customer_id' => 'required|integer|exists:customers,id',
            'invoice_date' => 'required|date',
            'products_details' => 'required|array',
            'products_details.*.product_id' => 'required|integer|exists:product_catalogs,id',
            'products_details.*.quantity' => 'required|numeric|min:0',
            'products_details.*.price' => 'required|numeric|min:0',
            'products_details.*.unit_discount' => 'nullable|numeric|min:0',
            'remarks' => 'nullable|string',
            'status' => 'nullable|string',
            'is_shipment' => 'nullable|boolean',
            'is_courier' => 'nullable|boolean',
            'delivery_date' => 'nullable|date',
            'courier_id' => 'nullable',
            'vat' => 'nullable|numeric|min:0',
        ]);

        $productIds = [];
        $quantities = [];
        $prices = [];
        $unitDiscounts = [];
        $totalDiscounts = [];
        $amounts = [];

        $totalAmount = 0;
        $totalDiscount = 0;
        $vat =  $validatedData['vat'] ?? 0;
        $commission = 0;

        foreach ($validatedData['products_details'] as $product) {
            $quantity = $product['quantity'];
            $price = $product['price'];
            $unitDiscount = $product['unit_discount'] ?? 0;

            $amount = ($price * $quantity) - ($unitDiscount * $quantity);
            $productTotalDiscount = $unitDiscount * $quantity;

            $productIds[] = $product['product_id'];
            $quantities[] = $quantity;
            $prices[] = $price;
            $unitDiscounts[] = $unitDiscount;
            $totalDiscounts[] = $productTotalDiscount;
            $amounts[] = $amount;

            $totalAmount += $amount;
            $totalDiscount += $productTotalDiscount;
        }

        $salesRequisitionDetails = [
            'product_ids' => $productIds,
            'quantity' => $quantities,
            'price' => $prices,
            'unit_discount' => $unitDiscounts,
            'total_discount' => $totalDiscounts,
            'amount' => $amounts,
        ];

        $netAmount = $totalAmount - $totalDiscount + $vat;

        $salesRequisitionData = [
            'customer_id' => $validatedData['customer_id'],
            'invoice_date' => $validatedData['invoice_date'],
            'total_amount' => $totalAmount,
            'discount' => $totalDiscount,
            // 'commission' => $commission,
            'total' => $totalAmount,
            'vat' => $vat,
            'net_amount' => $netAmount,
            'remarks' => $validatedData['remarks'] ?? null,
            'status' => $validatedData['status'] ?? 'pending',
            'is_shipment' => $validatedData['is_shipment'] ?? 0,
            'is_courier' => $validatedData['is_courier'] ?? 0,
            'delivery_date' => $validatedData['delivery_date'] ?? $validatedData['invoice_date'],
            'percentage' => 0,
        ];

        /**
         * ✅ Step 3: Validate Shipment
         */
        $salesRequisitionShipments = [];
        if ($validatedData['is_shipment'] ?? false) {
            $shipmentData = $request->validate([
                'shipment' => 'required|array',
                'shipment.courier_id' => ($validatedData['is_courier'] ?? false) ? 'required|integer|exists:couriers,id' : 'nullable|integer|exists:couriers,id',
                'shipment.area_id' => 'required|integer',
                'shipment.address' => 'required|string',
                'shipment.contact_person_name' => 'required|string',
                'shipment.contact_person_number' => 'required|string',
                'shipment.condition' => 'nullable|boolean',
                'shipment.additional_amount' => 'nullable|numeric|min:0',
                'shipment.condition_remarks' => 'nullable|string',
            ]);
            $salesRequisitionShipments = $shipmentData['shipment'];
        } else {
            $validatedData['is_shipment'] = 0;
        }


        /**
         * ✅ Step 4: Validate Payments
         */
        $payments = $request->validate([
            'payments' => 'nullable|array',
            'payments.*.pay_mode' => 'required|in:Cash,Cheque,Online Deposit,bKash,Nagad,Rocket,Card,EMI,Card Payment',
            'payments.*.bank_id' => 'nullable|integer',
            'payments.*.branch_id' => 'nullable|integer|exists:bank_branches,id',
            'payments.*.emi_id' => 'nullable|integer|exists:e_m_i_entries,id',
            'payments.*.transaction_id' => 'nullable|string',
            'payments.*.date' => 'required|date',
            'payments.*.amount' => 'required|numeric|min:0',
            'payments.*.attachment' => 'nullable|string',
            'payments.*.verified' => 'nullable|in:0,1',
            'payments.*.remark' => 'nullable|string',
        ]);

        $payments = [
            'payments_pay_mode'     => collect($request->payments)->pluck('pay_mode')->toArray(),
            'payments_bank_id'      => collect($request->payments)->pluck('bank_id')->toArray(),
            'payments_branch_id'    => collect($request->payments)->pluck('branch_id')->toArray(),
            'payments_emi_id'       => collect($request->payments)->pluck('emi_id')->toArray(),
            'payments_transaction_id' => collect($request->payments)->pluck('transaction_id')->toArray(),
            'payments_amount'       => collect($request->payments)->pluck('amount')->toArray(),
            'payments_date'         => collect($request->payments)->pluck('date')->toArray(),
            'payments_attachments'  => collect($request->payments)->pluck('attachment')->toArray(),
            'payments_verified'     => collect($request->payments)->pluck('verified')->toArray(),
            'payments_remark'       => collect($request->payments)->pluck('remark')->toArray(),
        ];


        /**
         * ✅ Step 5: Auto-calc values if not provided
         */
        $totalAmount = $validatedData['total_amount'] ?? collect($request->products_details)->sum(fn($p) => $p['price'] * $p['quantity']);
        $paidAmount = $validatedData['paid_amount'] ?? array_sum($payments['payments_amount'] ?? []);
        $netAmount = $validatedData['net_amount'] ?? ($totalAmount - $salesRequisitionData['discount'] + $vat );
        $dueAmount = $validatedData['due_amount'] ?? max($netAmount - $paidAmount, 0);
        $advanceAmount = $validatedData['advance_amount'] ?? ($paidAmount > $netAmount ? $paidAmount - $netAmount : 0);

        $validatedData['total_amount'] = $totalAmount;
        $validatedData['total'] = $validatedData['total'] ?? $totalAmount;
        $validatedData['net_amount'] = $netAmount;
        $validatedData['status'] = 'pending';

        try {
            $result = $this->service->store($salesRequisitionData, $salesRequisitionDetails, $salesRequisitionShipments, $payments);

            $this->generalNotificationService->store([
                'title' => 'New Sales Requisition Added via APP',
                'description' => 'New Sales Requisition Added via APP needed approval',
                'action' => $this->generalNotificationService->actionBuilder(SalesRequisitionController::class, 'approve', [$result['salesRequisition']->id]),
            ], $this->generalNotificationService->getPermittedUsers('sales.sales-requisitions.approve'));

            return response()->json([
                'status' => 'success',
                'message' => 'SalesRequisition created successfully.',
                'data' => $result,
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to create SalesRequisition: ' . $e->getMessage(),
                'data' => null,
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $data['salesRequisition'] = $this->service->show($id);
        $data['company_info'] = CompanyInfo::first();

        return response()->json($data);
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
            'remarks' => 'nullable|string',
            'status' => 'nullable|string',
            'is_shipment' => 'nullable|boolean',
            'is_courier' => 'nullable|boolean',
            'delivery_date' => 'nullable|date',
            'courier_id' => 'nullable',
        ]);

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

            $productIds[] = $product['product_id'];
            $quantities[] = $quantity;
            $prices[] = $price;
            $unitDiscounts[] = $unitDiscount;
            $totalDiscounts[] = $productTotalDiscount;
            $amounts[] = $amount;

            $totalAmount += $amount;
            $totalDiscount += $productTotalDiscount;
        }

        $salesRequisitionDetails = [
            'product_ids' => $productIds,
            'quantity' => $quantities,
            'price' => $prices,
            'unit_discount' => $unitDiscounts,
            'total_discount' => $totalDiscounts,
            'amount' => $amounts,
        ];

        $netAmount = $totalAmount - $totalDiscount + $vat;

         $salesRequisitionData = [
            'customer_id' => $validatedData['customer_id'],
            'invoice_date' => $validatedData['invoice_date'],
            'total_amount' => $totalAmount,
            'discount' => $totalDiscount,
            // 'commission' => $commission,
            'total' => $totalAmount,
            // 'vat' => $vat,
            'net_amount' => $netAmount,
            'remarks' => $validatedData['remarks'] ?? null,
            'status' => $validatedData['status'] ?? 'pending',
            'is_shipment' => $validatedData['is_shipment'] ?? 0,
            'is_courier' => $validatedData['is_courier'] ?? 0,
            'delivery_date' => $validatedData['delivery_date'] ?? $validatedData['invoice_date'],
            'percentage' => 0,
        ];
        /**
         * ✅ Step 3: Validate Shipment
         */
        $salesRequisitionShipments = [];
        if ($validatedData['is_shipment'] ?? false) {
            $shipmentData = $request->validate([
                'shipment' => 'required|array',
                'shipment.courier_id' => ($validatedData['is_courier'] ?? false) ? 'required|integer|exists:couriers,id' : 'nullable|integer|exists:couriers,id',
                'shipment.area_id' => 'required|integer',
                'shipment.address' => 'required|string',
                'shipment.contact_person_name' => 'required|string',
                'shipment.contact_person_number' => 'required|string',
                'shipment.condition' => 'nullable|boolean',
                'shipment.additional_amount' => 'nullable|numeric|min:0',
                'shipment.condition_remarks' => 'nullable|string',
            ]);
            $salesRequisitionShipments = $shipmentData['shipment'];
        } else {
            $validatedData['is_shipment'] = 0;
        }



        /**
         * ✅ Step 4: Validate Payments
         */
        $payments = $request->validate([
            'payments' => 'nullable|array',
            'payments.*.pay_mode' => 'required|in:Cash,Cheque,Online Deposit,bKash,Nagad,Rocket,Card,EMI,Card Payment',
            'payments.*.bank_id' => 'nullable|integer',
            'payments.*.branch_id' => 'nullable|integer|exists:bank_branches,id',
            'payments.*.emi_id' => 'nullable|integer|exists:e_m_i_entries,id',
            'payments.*.transaction_id' => 'nullable|string',
            'payments.*.date' => 'required|date',
            'payments.*.amount' => 'required|numeric|min:0',
            'payments.*.attachment' => 'nullable|string',
            'payments.*.verified' => 'nullable|in:0,1',
            'payments.*.remark' => 'nullable|string',
        ]);

        $payments = [
            'payments_pay_mode'     => collect($request->payments)->pluck('pay_mode')->toArray(),
            'payments_bank_id'      => collect($request->payments)->pluck('bank_id')->toArray(),
            'payments_branch_id'    => collect($request->payments)->pluck('branch_id')->toArray(),
            'payments_emi_id'       => collect($request->payments)->pluck('emi_id')->toArray(),
            'payments_transaction_id' => collect($request->payments)->pluck('transaction_id')->toArray(),
            'payments_amount'       => collect($request->payments)->pluck('amount')->toArray(),
            'payments_date'         => collect($request->payments)->pluck('date')->toArray(),
            'payments_attachments'  => collect($request->payments)->pluck('attachment')->toArray(),
            'payments_verified'     => collect($request->payments)->pluck('verified')->toArray(),
            'payments_remark'       => collect($request->payments)->pluck('remark')->toArray(),
        ];

        try {
            $salesRequisition = SalesRequisition::findOrFail($id);
            $rusult = $this->service->update($salesRequisition, $salesRequisitionData, $salesRequisitionDetails, $salesRequisitionShipments, $payments);

            return response()->json([
                'status' => 'success',
                'message' => 'SalesRequisition updated successfully.',
                'data' =>  $rusult,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to update SalesRequisition: ' . $e->getMessage(),
                'data' => null,
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(SalesRequisition $salesRequisition)
    {
        try {
            $this->service->delete($salesRequisition);
            return response()->json([
                'status' => 'success',
                'message' => 'SalesRequisition deleted successfully.',
                'data' => null,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to delete SalesRequisition: ' . $e->getMessage(),
                'data' => null,
            ], 500);
        }
    }
}