<?php

namespace Modules\Sales\Services;


use Modules\Sales\Models\Delivery;
use Modules\Sales\Models\SalesRequisition;
use Modules\Sales\Models\SalesRequisitionDetail;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use App\Services\Notifications\GeneralNotificationService;
use Illuminate\Support\Str;
use Modules\Account\Models\AccountSetup\BankAccount;
use Modules\Account\Models\EMIEntry;
use Modules\Account\Models\Setup\Bank;
use Modules\Account\Models\Setup\BankBranch;
use Modules\CRM\Models\Customer\Customer;
use Modules\Inventory\Models\ProductCatalog;
use Modules\LocationManager\Models\Area;
use Modules\Sales\Controllers\SalesRequisitionController;
use Modules\Sales\Models\Courier;

class SalesRequisitionService
{
    private $salesOrderService;

    /**
     * The GeneralNotificationService instance variable
     *
     * @var GeneralNotificationService
     */
    private $generalNotificationService;


    public function __construct(SalesOrderService $salesOrderService, GeneralNotificationService $generalNotificationService)
    {
        $this->salesOrderService = $salesOrderService;
        $this->generalNotificationService = $generalNotificationService;
    }


    /**
     * Generates a unique sales order number based on the current date, branch and user.
     * @param int $supplier_id
     * @return string
     */
    public function getInvoiceId($customerId = null)
    {
        $today = date('Y-m-d');
        $customer_count = SalesRequisition::whereDate(DB::raw('DATE(created_at)'), $today)->count();
        $authUser = auth()->user()->id;
        $authUserBranch = auth()->user()->branch_id;
        $authUserBranchType = auth()->user()->branch->branch_type_id;
        $SalesOrderToday = SalesRequisition::whereDate(DB::raw('DATE(created_at)'), $today)
            ->where('created_by', $authUser)
            ->count();
        // Generate Sales Order number with the appropriate format
        $SalesOrderNumber = sprintf(
            'SCT-%02d-SC-%02d-%s-USR-%06d-SL-%06d',
            $authUserBranch,
            $authUserBranchType,
            date('Ymd'),
            $authUser,
            $SalesOrderToday + 1
        );
        return $SalesOrderNumber;
    }

    public function getAll(int $limit = 20)
    {
        return SalesRequisition::query()
            // ->likeSearch('additional_phone')
            ->searchByFields(['customer_id'])
            ->when(request()->filled('additional_phone'),function ($qr){
                $qr->where('additional_phone', request('additional_phone'))
                ->orWhereHas('customer', function ($q) {
                    $q->where('phone', request('additional_phone'));
                });
            })
            ->filterByDateRange('invoice_date')
            ->with(['salesRequisitionDetails', 'delivery', 'customer', 'createdBy', 'updatedBy'])
            ->paginate($limit);
    }

    public function store(array $data, array $salesRequisitionDetails, array $salesRequisitionShipments, array $payments)
    {
        DB::beginTransaction();
        try {
            if (!isset($data['is_shipment']) || $data['is_shipment'] == null) {
                $data['is_shipment'] = 0;
            }
            if (!isset($data['is_courier']) || $data['is_courier'] == null) {
                $data['is_courier'] = 0;
            }
            $data['invoice_id'] = $this->getInvoiceId($data['customer_id']);

            $salesRequisition = SalesRequisition::create($data);
            $result['salesRequisition'] = $salesRequisition;
            $result['salesRequisitionDetails'] = [];
            foreach ($salesRequisitionDetails['product_ids'] as $key => $productId) {
                $result['salesRequisitionDetails'][] = $result['salesRequisition']->salesRequisitionDetails()->create([
                    'product_id' => $productId,
                    'quantity' => $salesRequisitionDetails['quantity'][$key],
                    'price' => $salesRequisitionDetails['price'][$key],
                    'unit_discount' => $salesRequisitionDetails['unit_discount'][$key],
                    'total_discount' => $salesRequisitionDetails['total_discount'][$key],
                    'amount' => $salesRequisitionDetails['amount'][$key],
                ]);
            }
            if (isset($data['is_shipment']) && $data['is_shipment'] == 1) {
                $salesRequisition->shipment()->delete();
                $result['salesRequisitionShipments'] = $result['salesRequisition']->shipment()->create([
                    'courier_id' => $salesRequisitionShipments['courier_id'] ?? null,
                    'area_id' => $salesRequisitionShipments['area_id'] == 'address' ? null : $salesRequisitionShipments['area_id'],
                    'address' => $salesRequisitionShipments['address'] ?? null,
                    'contact_person_name' => $salesRequisitionShipments['contact_person_name'] ?? null,
                    'contact_person_number' => $salesRequisitionShipments['contact_person_number'] ?? null,
                    'condition' => ($salesRequisitionShipments['condition'] ?? false) ? true : false,
                    'additional_amount' => ($salesRequisitionShipments['condition'] ?? false) ? $salesRequisitionShipments['additional_amount'] : null,
                    'condition_remarks' => ($salesRequisitionShipments['condition'] ?? false) ? $salesRequisitionShipments['condition_remarks'] : null,
                    'is_courier' => ($data['is_courier'] ?? false) ? true : false,
                ]);
            } else {
                $salesRequisition->shipment()->delete();
            }
            if ($data['status'] == 'approved') {
                // //create Delivery
                // $delivery = Delivery::updateOrCreate([
                //     'source_id' => $salesRequisition->id,
                //     'source_type' => SalesRequisition::class,
                // ], [
                //     'delivery_date' => $data['delivery_date'] ?? $data['invoice_date'],
                // ]);

                // $result['delivery'] = $delivery;
            }

            foreach ($payments['payments_pay_mode'] ?? [] as $key => $payMode) {
                if ($payMode) {
                    $result['payments'][] = $result['salesRequisition']->payments()->create([
                        'pay_mode' => $payMode,
                        'bank_id' => $payments['payments_bank_id'][$key] ?? null,
                        'branch_id' => $payments['payments_branch_id'][$key] ?? null,
                        'transaction_id' => $payments['payments_transaction_id'][$key] ?? null,
                        'e_m_i_entries_id' => $payments['payments_emi_id'][$key] ?? null,
                        'amount' => $payments['payments_amount'][$key] ?? 0,
                        'date' => $payments['payments_date'][$key] ?? null,
                        'attachments' => $payments['payments_attachments'][$key] ?? null,
                        'verified' => $payments['payments_verified'][$key] ?? false,
                        'remarks' => $payments['payments_remark'][$key] ?? null
                    ]);
                }
            }


            if ($salesRequisition->status == 'pending') {
                $this->generalNotificationService->store([
                    'title' => 'New Sales Requisition Added',
                    'description' => '#' . $salesRequisition->invoice_id . ' Sales Requisition Added, needs approval',
                    'action' => $this->generalNotificationService->actionBuilder(SalesRequisitionController::class, 'edit', [$result['salesRequisition']->id]),
                ], $this->generalNotificationService->getPermittedUsers('sales.sales-requisitions.verify'), $salesRequisition);
            }


            // dd($result);
            // Commit the transaction
            DB::commit();
            return $result;
        } catch (\Exception $e) {
            // Rollback the transaction in case of any exception
            DB::rollback();

            // Re-throw the exception to be handled by the calling code
            throw $e;
        }
    }


    public function update(SalesRequisition $salesRequisition, array $data, array $salesRequisitionDetails, array $salesRequisitionShipments, array $payments)
    {
        DB::beginTransaction();
        if (!isset($data['is_shipment']) || $data['is_shipment'] == null) {
            $data['is_shipment'] = 0;
        }
        if (!isset($data['is_courier']) || $data['is_courier'] == null) {
            $data['is_courier'] = 0;
        }

        $salesRequisition->update($data);
        $result['salesRequisition'] = $salesRequisition;
        // dd($data, $result['salesRequisition']);
        $result['salesRequisitionDetails'] = [];

        SalesRequisitionDetail::where('sales_requisition_id', $salesRequisition->id)->delete();

        foreach ($salesRequisitionDetails['product_ids'] as $key => $productId) {
            $result['salesRequisitionDetails'][] = $salesRequisition->salesRequisitionDetails()->create([
                'product_id' => $productId,
                'quantity' => $salesRequisitionDetails['quantity'][$key],
                'price' => $salesRequisitionDetails['price'][$key],
                'unit_discount' => $salesRequisitionDetails['unit_discount'][$key],
                'total_discount' => $salesRequisitionDetails['total_discount'][$key],
                'amount' => $salesRequisitionDetails['amount'][$key],
            ]);
        }

        if (isset($data['is_shipment']) && $data['is_shipment'] == 1) {
            // dd($data['is_shipment'], $salesRequisitionShipments);

            // @dd($salesRequisitionShipments);
            $salesRequisition->shipment()->delete();
            $result['salesRequisitionShipments'] = $result['salesRequisition']->shipment()->create([
                'courier_id' => $salesRequisitionShipments['courier_id'] ?? null,
                'area_id' => $salesRequisitionShipments['area_id'] == 'address' ? null : $salesRequisitionShipments['area_id'],
                'address' => $salesRequisitionShipments['address'] ?? null,
                'contact_person_name' => $salesRequisitionShipments['contact_person_name'],
                'contact_person_number' => $salesRequisitionShipments['contact_person_number'],
                'condition' => ($salesRequisitionShipments['condition'] ?? false) ? true : false,
                'additional_amount' => ($salesRequisitionShipments['condition'] ?? false) ? $salesRequisitionShipments['additional_amount'] : null,
                'condition_remarks' => ($salesRequisitionShipments['condition'] ?? false) ? $salesRequisitionShipments['condition_remarks'] : null,
                'is_courier' => ($data['is_courier'] ?? false) ? true : false,

            ]);
            // dd($result['salesRequisitionShipments']);
        } else {
            $salesRequisition->shipment()->delete();
        }
        if ($data['status'] == 'approved') {
            //create Delivery
            // $delivery = Delivery::updateOrCreate([
            //     'source_id' => $salesRequisition->id,
            //     'source_type' => SalesRequisition::class,
            // ], [
            //     'delivery_date' => $data['delivery_date'] ?? $data['invoice_date'],
            // ]);

            // $result['delivery'] = $delivery;
        } else if ($data['status'] == 'pending') {
            $result['salesRequisition']->update(['status' => 'pending']);
            //remove delivery
            $result['salesRequisition']->delivery()->delete();
        }
        $result['salesRequisition']->payments()->delete();

        foreach ($payments['payments_pay_mode'] ?? [] as $key => $payMode) {
            if ($payMode) {
                $result['payments'][] = $result['salesRequisition']->payments()->create([
                    'pay_mode' => $payMode,
                    'bank_id' => $payments['payments_bank_id'][$key] ?? null,
                    'branch_id' => $payments['payments_branch_id'][$key] ?? null,
                    'transaction_id' => $payments['payments_transaction_id'][$key] ?? null,
                    'e_m_i_entries_id' => $payments['payments_emi_id'][$key] ?? null,
                    'amount' => $payments['payments_amount'][$key] ?? 0,
                    'date' => $payments['payments_date'][$key] ?? null,
                    'attachments' => $payments['payments_attachments'][$key] ?? null,
                    'verified' => $payments['payments_verified'][$key] ?? false,
                    'remarks' => $payments['payments_remark'][$key] ?? null
                ]);
            }
        }

        // dd($salesRequisition);

        if ($salesRequisition->status == 'verified') {
            //   $this->generalNotificationService->updateBySource($salesRequisition);

            $notification = $this->generalNotificationService->store([
                'title' => 'Sales Requisition Verified',
                'description' => '#' . $salesRequisition->invoice_id . ' Sales Requisition verified, needs approval',
                'action' => $this->generalNotificationService->redirectBuilder(route('sales.sales-requisitions.show', $salesRequisition->id)),
                'type' => 'urgent',
            ], $this->generalNotificationService->getPermittedUsers('sales.sales-requisitions.verify'), $salesRequisition);
            //    dd(request()->all(), $notification);
        } else if ($salesRequisition->status == 'approved') {
            $this->generalNotificationService->updateBySource($salesRequisition);
            //    dd(request()->all(), $notification);
        }


        DB::commit();
        return $result;
    }

    public function delete(SalesRequisition $salesRequisition)
    {
        $salesRequisition->delete();
    }

    public function show($id)
    {
        return SalesRequisition::with([
            'salesRequisitionDetails.product',
            'delivery',
            'customer',
            'createdBy',
            'updatedBy',
            'shipment.courier',
            'payments'
        ])->findOrFail($id);
    }




    public function saveToSalesOrder(SalesRequisition $salesRequisition)
    {
        $salesOrder = $this->salesOrderService->saveFromRequisition($salesRequisition);
        // Use the injected SalesOrderService to convert the requisition to order
        $salesRequisition->update(['status' => 'sended_to_sales_order']);
        return $salesOrder;
    }


    public function directJsonImport($jsonData)
    {

        if (empty($jsonData)) {
            return response()->json([
                'success' => false,
                'message' => 'No data provided.'
            ], 422);
        }

        DB::beginTransaction();
        $savedCount = 0;
        $errors = [];

        foreach ($jsonData as $item) {
            try {
                $mapped = $this->mapJson($item);
                $ddval = $this->store(
                    $mapped['data'],
                    $mapped['salesRequisitionDetails'],
                    $mapped['salesRequisitionShipments'],
                    $mapped['payments']
                );
                // dd($ddval);
                $savedCount++;
            } catch (\Exception $e) {
                $errors[] = "Error processing item: " . $e->getMessage();
            }
        }
        // dd( $result);

        DB::commit();
        $message = "Import completed. Successfully saved: {$savedCount}";

        return response()->json([
            'success' => empty($errors) || $savedCount > 0,
            'message' => $message,
            'saved_count' => $savedCount,
            'error_count' => count($errors),
            'errors' => $errors
        ], empty($errors) ? 200 : 207); // 207 Multi-Status for partial success
    }
    public function storeFromJsonFile()
    {
        $jsonFileDir = storage_path('app/json_formats');
        $jsonFile = $jsonFileDir . '/' . Str::snake(request()->input('name')) . '.json';

        // Create directory if it doesn't exist
        if (!is_dir($jsonFileDir)) {
            mkdir($jsonFileDir, 0755, true);
        }

        // Create file if it doesn't exist
        if (!file_exists($jsonFile)) {
            file_put_contents($jsonFile, json_encode([]));
        }

        $jsonData = json_decode(file_get_contents($jsonFile), true);

        $this->directJsonImport($jsonData);

        return response()->json([
            'success' => true,
            'message' => 'USG/OPG License Requisitions imported successfully.'
        ], 200);
    }

    public function mapJson(array $jsonData): array
    {
        // === 1. Resolve customer ID ===
        $customerId = Customer::where('company_name', $jsonData['customer_name'])
            ->value('id') ?? throw new \Exception("Customer not found: {$jsonData['customer_name']}");

        // === 2. Resolve product IDs and collect line items ===
        $productLines = [];
        $totalAmount = 0;
        $totalDiscount = 0;

        foreach ($jsonData['products'] ?? [] as $product) {
            $productId = ProductCatalog::where('name', $product['product_name'])
                ->where('model', $product['model'])
                ->value('id');

            if (!$productId) {
                throw new \Exception("Product not found: '{$product['product_name']}' (Model: {$product['model']})");
            }

            $lineAmount = $product['quantity'] * $product['price'];
            $lineDiscount = $product['total_discount'] ?? 0;

            // Validate discount doesn't exceed amount
            if ($lineDiscount > $lineAmount) {
                throw new \Exception("Discount cannot exceed product amount for {$product['product_name']} (Model: {$product['model']})");
            }

            $productLines[] = [
                'product_id' => $productId,
                'quantity' => $product['quantity'],
                'price' => $product['price'],
                'unit_discount' => $product['unit_discount'] ?? 0,
                'total_discount' => $lineDiscount,
                'amount' => $lineAmount - $lineDiscount,
            ];

            $totalAmount += $lineAmount;
            $totalDiscount += $lineDiscount;
        }

        // === 3. Calculate financials ===
        $VAT_RATE = 0.05; // 5% VAT (adjust as needed)

        $calculatedTotalAmount = $totalAmount;
        $calculatedDiscount = $totalDiscount;
        $calculatedPercentage = $calculatedTotalAmount > 0
            ? round(($calculatedDiscount / $calculatedTotalAmount) * 100, 2)
            : 0;

        $preVatAmount = $calculatedTotalAmount - $calculatedDiscount;
        $calculatedVat = round($preVatAmount * $VAT_RATE, 2);
        $calculatedTotal = round($preVatAmount + $calculatedVat, 2);
        $calculatedNetAmount = $calculatedTotal; // or apply further logic (e.g., rounding)

        // === 4. Build main data ===
        $data = [
            'customer_id' => $customerId,
            'additional_phone' => $jsonData['additional_phone'] ?? null,
            'invoice_date' => $jsonData['invoice_date'],
            'delivery_date' => $jsonData['delivery_date'],
            'total_amount' => $calculatedTotalAmount,
            'discount' => $calculatedDiscount,
            'percentage' => $calculatedPercentage,
            'vat' => $calculatedVat,
            'total' => $calculatedTotal,
            'net_amount' => $calculatedNetAmount,
            'remarks' => $jsonData['remarks'] ?? null,
            'is_shipment' => $jsonData['is_shipment'] ?? false,
            'status' => $jsonData['status'] ?? 'pending',
            'is_courier' => $jsonData['is_courier'] ?? false,
        ];

        // === 5. Rebuild salesRequisitionDetails with calculated amounts ===
        $salesRequisitionDetails = [
            'product_ids' => [],
            'quantity' => [],
            'price' => [],
            'unit_discount' => [],
            'total_discount' => [],
            'amount' => [],
        ];

        foreach ($productLines as $line) {
            $salesRequisitionDetails['product_ids'][] = $line['product_id'];
            $salesRequisitionDetails['quantity'][] = $line['quantity'];
            $salesRequisitionDetails['price'][] = $line['price'];
            $salesRequisitionDetails['unit_discount'][] = $line['unit_discount'];
            $salesRequisitionDetails['total_discount'][] = $line['total_discount'];
            $salesRequisitionDetails['amount'][] = $line['amount'];
        }

        // === 6. Shipment (unchanged) ===
        $salesRequisitionShipments = [];
        if ($data['is_shipment']) {
            $shipment = $jsonData['shipment'] ?? [];

            $courierId = null;
            if (!empty($shipment['courier_name'])) {
                $courierId = Courier::where('courier_name', $shipment['courier_name'])
                    ->value('id') ?? throw new \Exception("Courier not found: {$shipment['courier_name']}");
            }

            $areaId = null;
            if (!empty($shipment['area_name']) && $shipment['area_name'] !== 'address') {
                $areaId = Area::where('area', $shipment['area_name'])
                    ->value('id') ?? throw new \Exception("Area not found: {$shipment['area_name']}");
            }

            $salesRequisitionShipments = [
                'courier_id' => $courierId,
                'area_id' => $areaId,
                'address' => $shipment['address'] ?? null,
                'contact_person_name' => $shipment['contact_person_name'] ?? null,
                'contact_person_number' => $shipment['contact_person_number'] ?? null,
                'condition' => $shipment['condition'] ?? false,
                'additional_amount' => $shipment['condition'] ? ($shipment['additional_amount'] ?? 0) : null,
                'condition_remarks' => $shipment['condition'] ? ($shipment['condition_remarks'] ?? null) : null,
            ];
        }

        // === 7. Payments (with calculated totals) ===
        $payments = [
            'payments_total_amount' => $calculatedTotal,
            'payments_payable_amount' => $calculatedNetAmount,
            'payments_due_amount' => 0,
            'payments_advance_amount' => 0,
            'payments_pay_mode' => [],
            'payments_bank_id' => [],
            'payments_branch_id' => [],
            'payments_emi_id' => [],
            'payments_transaction_id' => [],
            'payments_date' => [],
            'payments_amount' => [],
            'payments_attachments' => [],
            'payments_verified' => [],
            'payments_remark' => [],
        ];

        if (!empty($jsonData['payments'])) {
            // Preload reference data for performance
            $banks = Bank::pluck('id', 'name')->toArray();
            $accounts = BankAccount::pluck('id', 'account_name')->toArray();
            $branches = BankBranch::pluck('id', 'name')->toArray();
            $emis = EmiEntry::pluck('id', 'emi_number')->toArray();

            // Process each payment entry
            foreach ($jsonData['payments'] as $payment) {
                // Payment mode validation
                $validModes = ['Cash', 'Cheque', 'Online Deposit', 'bKash', 'Nagad', 'Rocket', 'Card', 'EMI', 'Card Payment'];
                if (!in_array($payment['pay_mode'], $validModes)) {
                    throw new \Exception("Invalid payment mode: {$payment['pay_mode']}");
                }
                // dd( $payment);

                // Map bank references
                $bankId = $payment['pay_mode'] === 'Cheque'
                    ? ($banks[$payment['bank_name']] ?? null)
                    : ($accounts[$payment['bank_name']] ?? null);

                $branchId = $payment['branch_name']
                    ? ($branches[$payment['branch_name']] ?? throw new \Exception("Branch not found: {$payment['branch_name']}"))
                    : null;

                $emiId = $payment['pay_mode'] === 'EMI'
                    ? ($emis[$payment['bank_name']] ?? throw new \Exception("EMI reference not found: {$payment['bank_name']}"))
                    : null;

                // Add to payments array
                $payments['payments_pay_mode'][] = $payment['pay_mode'];
                $payments['payments_bank_id'][] = $bankId;
                $payments['payments_branch_id'][] = $branchId;
                $payments['payments_emi_id'][] = $emiId;
                $payments['payments_transaction_id'][] = $payment['transaction_id'] ?? null;
                $payments['payments_date'][] = $payment['date'] ?? now()->format('Y-m-d');
                $payments['payments_amount'][] = $payment['amount'] ?? 0;
                $payments['payments_attachments'][] = $payment['attachment'] ?? null;
                $payments['payments_verified'][] = false;
                $payments['payments_remark'][] = $payment['remark'] ?? null;
            }
        }

        return compact(
            'data',
            'salesRequisitionDetails',
            'salesRequisitionShipments',
            'payments'
        );
    }

}
