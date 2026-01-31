<?php

namespace Modules\Sales\Services;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Modules\Sales\Models\SalesOrder;
use Modules\CRM\Models\Customer\Customer;
use Modules\Inventory\Models\ProductCatalog;
use Modules\Sales\Models\Courier;
use Modules\LocationManager\Models\Area;
use Modules\Account\Models\Setup\Bank;
use Modules\Account\Models\Setup\BankBranch;
use Exception;
use Carbon\Carbon;
use Modules\Account\Models\AccountSetup\BankAccount;

class SalesOrderImportService
{
    protected $salesOrderService;
    protected $deliveryService;
    protected $errors = [];
    protected $warnings = [];
    protected $converted_errors = [];
    protected $processed = 0;
    protected $successful = 0;

    public function __construct(SalesOrderService $salesOrderService, DeliveryService $deliveryService)
    {
        $this->salesOrderService = $salesOrderService;
        $this->deliveryService = $deliveryService;
    }

    /**
     * Validate JSON content without importing
     *
     * @param string $jsonContent JSON content
     * @return array
     */
    public function validateJson($jsonContent)
    {
        $this->resetStats();

        try {
            $data = json_decode($jsonContent, true);

            if (!$data) {
                throw new Exception('Invalid JSON format');
            }

            $this->validateJsonStructure($data);
            // dd($data);

            // Validate each order
            foreach ($data['sales_orders'] as $index => $orderData) {
                $this->validateOrder($orderData, $index + 1);
                // dd($orderData);
            }

            // Filter out errors for optional empty fields and convert to warnings
            $errors = $this->filterOptionalFieldErrors();

            return [
                'valid' => empty($errors),
                'message' => empty($errors) ? 'JSON validation successful!' : 'JSON validation completed with warnings' . json_encode($errors),
                'stats' => [
                    'total_orders' => count($data['sales_orders']),
                    'orders_to_approve' => count(array_filter($data['sales_orders'], fn($o) => ($o['status'] ?? 'pending') === 'approved'))
                ],
                'warnings' => array_merge($this->warnings, $this->converted_errors),
                'errors' => $errors
            ];

        } catch (Exception $e) {
            return [
                'valid' => false,
                'message' => 'JSON validation failed: ' . $e->getMessage(),
                'errors' => [$e->getMessage()],
                'warnings' => []
            ];
        }
    }

    /**
     * Validate a single order
     */
    protected function validateOrder($orderData, $orderNumber)
    {
        $orderLabel = "Order {$orderNumber}";
        if (isset($orderData['reference'])) {
            $orderLabel .= " ({$orderData['reference']})";
        }

        // Check required fields - either customer_id or customer_name must be provided
        if ((!isset($orderData['customer_id']) || empty($orderData['customer_id'])) &&
            (!isset($orderData['customer_name']) || empty($orderData['customer_name']))) {
            $this->errors[] = "{$orderLabel}: Either customer_id or customer_name is required";
        }

        if (!isset($orderData['products']) || !is_array($orderData['products']) || empty($orderData['products'])) {
            $this->errors[] = "{$orderLabel}: products array is required and cannot be empty";
        }

        // Validate customer exists if customer_name is provided
        if (isset($orderData['customer_name']) && !empty($orderData['customer_name'])) {
            $customerId = $this->mapCustomerNameToId($orderData['customer_name']);
            if (!$customerId) {
                $this->errors[] = "{$orderLabel}: Customer '{$orderData['customer_name']}' not found";
            }
        }

        // Validate customer exists if customer_id is provided
        if (isset($orderData['customer_id']) && !empty($orderData['customer_id'])) {
            if (!$this->customerExistsById($orderData['customer_id'])) {
                $this->errors[] = "{$orderLabel}: Customer with ID '{$orderData['customer_id']}' not found";
            }
        }

        // Validate products
        if (isset($orderData['products']) && is_array($orderData['products'])) {
            foreach ($orderData['products'] as $productIndex => $product) {
                $productLabel = $productIndex + 1;
                $this->validateProduct($product, $orderLabel, $productLabel);
            }
        }

        // Validate shipment if present and not empty
        if (isset($orderData['shipment']) && !empty($orderData['shipment'])) {
            $this->validateShipment($orderData['shipment'], $orderLabel);
        }

        // Validate payments if present
        if (isset($orderData['payments']['payment_details'])) {
            $this->validatePayments($orderData['payments']['payment_details'], $orderLabel);
        }

        // Check financial calculations
        if (isset($orderData['products']) && isset($orderData['total_amount'])) {
            $calculatedTotal = 0;
            foreach ($orderData['products'] as $product) {
                $calculatedTotal += $product['amount'] ?? 0;
            }

            if (abs($calculatedTotal - $orderData['total_amount']) > 0.01) { // Allow small rounding differences
                $this->warnings[] = "{$orderLabel}: Product total ({$calculatedTotal}) doesn't match order total_amount ({$orderData['total_amount']})";
            }
        }
    }

    /**
     * Validate product details
     */
    protected function validateProduct($productData, $orderLabel, $productLabel)
    {
        $productName = $productData['product_name'] ?? '';
        $model = $productData['model'] ?? null;
        $productCode = $productData['product_code'] ?? null;

        // Check if either product_name or product_code is provided
        if (empty($productName) && empty($productCode)) {
            $this->errors[] = "{$orderLabel} Product {$productLabel}: Either product_name or product_code is required";
        } else {
            // Look up product by product_code first, then by product_name
            $productId = null;
            if (!empty($productCode)) {
                $productId = $this->mapProductCodeToId($productCode);
            }
            if (!$productId && !empty($productName)) {
                $productId = $this->mapProductNameToId($productName, $model);
            }

            if (!$productId) {
                $identifier = $productCode ?: $productName;
                $modelInfo = $model ? " (Model: {$model})" : "";
                $this->errors[] = "{$orderLabel} Product {$productLabel}: Product '{$identifier}'{$modelInfo} not found";
            }
        }

        // Check numeric fields
        $numericFields = ['quantity', 'price', 'unit_discount', 'total_discount', 'amount'];
        foreach ($numericFields as $field) {
            if (isset($productData[$field])) {
                if (!is_numeric($productData[$field])) {
                    $this->errors[] = "{$orderLabel} Product {$productLabel}: {$field} must be numeric";
                } elseif ($productData[$field] < 0) {
                    $this->errors[] = "{$orderLabel} Product {$productLabel}: {$field} cannot be negative";
                }
            }
        }

        // Validate stock details if present
        if (isset($productData['stock_details'])) {
            $this->validateStockDetails($productData['stock_details'], $orderLabel, $productLabel);
        }
    }

    /**
     * Validate stock details
     */
    protected function validateStockDetails($stockDetails, $orderLabel, $productLabel)
    {
        if (!is_array($stockDetails)) {
            $this->errors[] = "{$orderLabel} Product {$productLabel}: stock_details must be an array";
            return;
        }

        $totalStockQty = 0;
        foreach ($stockDetails as $stockIndex => $stock) {
            $stockLabel = "Stock " . ($stockIndex + 1);

            if (!isset($stock['batch_no']) || empty($stock['batch_no'])) {
                $this->errors[] = "{$orderLabel} Product {$productLabel} {$stockLabel}: batch_no is required";
            }

            if (!isset($stock['quantity']) || !is_numeric($stock['quantity'])) {
                $this->errors[] = "{$orderLabel} Product {$productLabel} {$stockLabel}: quantity must be numeric";
            } elseif ($stock['quantity'] <= 0) {
                $this->errors[] = "{$orderLabel} Product {$productLabel} {$stockLabel}: quantity must be greater than 0";
            }

            if (!isset($stock['type']) || !in_array($stock['type'], ['lot', 'serial'])) {
                $this->errors[] = "{$orderLabel} Product {$productLabel} {$stockLabel}: type must be 'lot' or 'serial'";
            }

            if (isset($stock['quantity'])) {
                $totalStockQty += $stock['quantity'];
            }
        }

        // Note: We don't check if stock quantities match product quantities as that might need to be verified during import
    }

    /**
     * Validate shipment details
     */
    protected function validateShipment($shipmentData, $orderLabel)
    {
        if (isset($shipmentData['courier_name']) && !empty($shipmentData['courier_name'])) {
            $courierId = $this->mapCourierNameToId($shipmentData['courier_name']);
            if (!$courierId) {
                $this->errors[] = "{$orderLabel}: Courier '{$shipmentData['courier_name']}' not found";
            }
        }

        // Skip area validation for now as it's optional

        if (isset($shipmentData['area_name']) && !empty($shipmentData['area_name'])) {
            $areaId = $this->mapAreaNameToId($shipmentData['area_name']);
            if (!$areaId) {
                $this->errors[] = "{$orderLabel}: Area '{$shipmentData['area_name']}' not found";
            }
        }
    }

    /**
     * Validate payment details
     */
    protected function validatePayments($paymentsData, $orderLabel)
    {
        foreach ($paymentsData as $paymentIndex => $payment) {
            $paymentLabel = "Payment " . ($paymentIndex + 1);

            if (!isset($payment['pay_mode']) || empty($payment['pay_mode'])) {
                $this->errors[] = "{$orderLabel} {$paymentLabel}: pay_mode is required";
            }

            if (isset($payment['amount']) && !is_numeric($payment['amount'])) {
                $this->errors[] = "{$orderLabel} {$paymentLabel}: amount must be numeric";
            }

            // Validate bank details for bank transfers if present and not empty
            if (isset($payment['bank_name']) && !empty($payment['bank_name'])) {
                if ($payment['pay_mode'] === 'Cheque') {
                    $bankId = $this->mapBankNameToId($payment['bank_name']);
                } else {
                    $bankId = $this->mapBankAccountNameToId($payment['bank_name']);
                }

                if (!$bankId) {
                    $type = $payment['pay_mode'] === 'Cheque' ? 'Bank' : 'Bank Account';
                    $this->errors[] = "{$orderLabel} {$paymentLabel}: {$type} '{$payment['bank_name']}' not found. mode" . $payment['pay_mode'];
                }
            }
        }
    }

    /**
     * Filter out errors for optional empty fields and convert to warnings
     */
    protected function filterOptionalFieldErrors()
    {
        $filteredErrors = [];
        $this->converted_errors = [];

        foreach ($this->errors as $error) {
            // Convert errors for empty optional fields to warnings
            if (
                preg_match('/Courier \'\' not found/', $error) ||
                preg_match('/Bank \'\' not found/', $error) ||
                preg_match('/Area \'\' not found/', $error)
            ) {
                $this->converted_errors[] = 'Optional field is empty - will be skipped during import';
            } else {
                $filteredErrors[] = $error;
            }
        }

        return $filteredErrors;
    }

    /**
     * Import sales orders from JSON file
     *
     * @param string $jsonContent JSON content
     * @param bool $validateFirst Whether to validate first
     * @param bool $createDeliveries Whether to create deliveries for approved orders
     * @return array
     */
    public function importFromJson($jsonContent, $validateFirst = true, $createDeliveries = true)
    {
        $this->resetStats();

        try {
            $data = json_decode($jsonContent, true);

            if (!$data) {
                throw new Exception('Invalid JSON format');
            }

            $this->validateJsonStructure($data);

            DB::beginTransaction();

            foreach ($data['sales_orders'] as $orderData) {
                $this->processOrder($orderData, $createDeliveries);
            }

            DB::commit();

            return $this->getStats();

        } catch (Exception $e) {
            DB::rollback();
            $this->errors[] = $e->getMessage();
            Log::error('Sales Order Import Error: ' . $e->getMessage());

            return $this->getStats();
        }
    }

    /**
     * Import from uploaded file
     *
     * @param \Illuminate\Http\UploadedFile $file
     * @param bool $createDeliveries
     * @return array
     */
    public function importFromFile($file, $createDeliveries = true)
    {
        if (!$file->isValid()) {
            throw new Exception('Invalid file uploaded');
        }

        $jsonContent = file_get_contents($file->getRealPath());

        return $this->importFromJson($jsonContent, $createDeliveries);
    }

    /**
     * Process a single sales order
     */
    protected function processOrder($orderData, $createDeliveries = true)
    {
        $this->processed++;

        try {
            $mappedData = $this->mapNamesToIds($orderData);

            if (empty($mappedData['data']['customer_id'])) {
                $customerIdentifier = $orderData['customer_id'] ?? $orderData['customer_name'] ?? 'Unknown';
                throw new Exception("Customer not found: " . $customerIdentifier);
            }

            $this->validateOrderData($mappedData);
            $result = $this->salesOrderService->store(
                $mappedData['data'],
                $mappedData['salesOrderDetails'],
                $mappedData['salesOrderShipments'],
                $mappedData['payments'],
            );
            if($mappedData['data']['status'] == 'approved' && $createDeliveries) {
                // dd($result['delivery']);
                $this->processDeliverywithStockDetails($result['delivery'], $mappedData['salesOrderDetails']);
            }

            $this->successful++;

        } catch (Exception $e) {
            $this->errors[] = "Order " . ($orderData['reference'] ?? $this->processed) . ": " . $e->getMessage() . " - " . $e->getFile() . ":" . $e->getLine();
        }
    }

    protected function processDeliverywithStockDetails($delivery, $salesOrderDetails)
    {
        // dd($delivery, $salesOrderDetails);
        $deliveryService = $this->deliveryService;
        $deliveryDetails = ['product_id' => [], 'quantity' => []];
        $deliveryStockDetails = ['lot_no' => [], 'lots_quantity' => [], 'serial_no' => []];

        $hasStockDetails = false;

        foreach ($salesOrderDetails['product_ids'] as $index => $productId) {
            // $productId = $detail['product_id'];
            $quantity = $salesOrderDetails['quantity'][$index];

            if ($salesOrderDetails['stock_details'][$index]) {
                // dd($salesOrderDetails['stock_details'][$index]);
                $stockDetails = $salesOrderDetails['stock_details'][$index];

                if (!empty($stockDetails)) {
                    $hasStockDetails = true;

                    if (!isset($deliveryStockDetails['lot_no'][$productId])) {
                        $deliveryStockDetails['lot_no'][$productId] = [];
                    }
                    if (!isset($deliveryStockDetails['lots_quantity'][$productId])) {
                        $deliveryStockDetails['lots_quantity'][$productId] = [];
                    }
                    if (!isset($deliveryStockDetails['serial_no'][$productId])) {
                        $deliveryStockDetails['serial_no'][$productId] = [];
                    }

                    foreach ($stockDetails as $stock) {
                        $batchNo = $stock['batch_no'] ?? '';
                        $stockType = $stock['type'] ?? 'lot';
                        $stockQuantity = $stock['quantity'] ?? 1;

                        //if any of this null thow error
                        if (empty($batchNo) || !in_array($stockType, ['lot', 'serial'])) {
                            throw new Exception('Invalid stock details');
                        }

                        if ($stockType === 'lot') {
                            $deliveryStockDetails['lot_no'][$productId][] = $batchNo;
                            $deliveryStockDetails['lots_quantity'][$productId][] = $stockQuantity;
                        } elseif ($stockType === 'serial') {
                            $deliveryStockDetails['serial_no'][$productId][] = $batchNo;
                        }
                    }
                }
            }

            $deliveryDetails['product_id'][] = $productId;
            $deliveryDetails['quantity'][] = $quantity;
            $deliveryDetails['sales_quantity'][] = $quantity;
        }

        if ($hasStockDetails) {
            $deliveryService->update($delivery, $delivery->toArray(), $deliveryDetails, $deliveryStockDetails);
        }
    }

    /**
     * Map human-readable names to database IDs
     */
    protected function mapNamesToIds($orderData)
    {
        $mapped = [
            'data' => [],
            'salesOrderDetails' => [
                'product_ids' => [],
                'quantity' => [],
                'price' => [],
                'unit_discount' => [],
                'total_discount' => [],
                'amount' => [],
                'sales_order_detail_id' => [],
                'stock_details' => [],
            ],
            'salesOrderShipments' => [],
            'payments' => []
        ];

        // Map sales order data
        $mapped['data'] = $this->mapOrderData($orderData);

        // Map products
        if (isset($orderData['products']) && is_array($orderData['products'])) {
            foreach ($orderData['products'] as $product) {
                // Look up product by product_code first, then by product_name
                $productId = null;
                if (!empty($product['product_code'])) {
                    $productId = $this->mapProductCodeToId($product['product_code']);
                }
                if (!$productId && !empty($product['product_name'])) {
                    $productId = $this->mapProductNameToId($product['product_name'], $product['model'] ?? null);
                }

                if (!$productId) {
                    $identifier = $product['product_code'] ?? $product['product_name'];
                    $modelInfo = isset($product['model']) ? " (Model: {$product['model']})" : "";
                    $this->warnings[] = "Product not found: {$identifier}{$modelInfo}";
                    continue;
                }

                $mapped['salesOrderDetails']['product_ids'][] = $productId;
                $mapped['salesOrderDetails']['quantity'][] = $product['quantity'];
                $mapped['salesOrderDetails']['price'][] = $product['price'];
                $mapped['salesOrderDetails']['unit_discount'][] = $product['unit_discount'] ?? 0;
                $mapped['salesOrderDetails']['total_discount'][] = $product['total_discount'] ?? 0;
                $mapped['salesOrderDetails']['amount'][] = $product['price']*$product['quantity'];
                $mapped['salesOrderDetails']['sales_order_detail_id'][] = null; // New records

                // Handle is_offer field for partial_sales
                if (isset($product['is_offer'])) {
                    $mapped['salesOrderDetails']['is_offer'][] = (bool) $product['is_offer'];
                } else {
                    $mapped['salesOrderDetails']['is_offer'][] = false; // Default to false if not specified
                }

                // Store stock details for delivery processing
                $mapped['salesOrderDetails']['stock_details'][] = isset($product['stock_details']) && is_array($product['stock_details']) ?
                    $product['stock_details'] : [];
            }
        }

        // Map shipments
        if (isset($orderData['shipment']) && !empty($orderData['shipment'])) {
            $mapped['data']['is_shipment'] = 1;
            $mapped['salesOrderShipments'] = $this->mapShipmentData($orderData['shipment']);
        } else {
            $mapped['data']['is_shipment'] = 0;
        }

        // Map payments
        if (isset($orderData['payments']) && !empty($orderData['payments'])) {
            $mapped['payments'] = $this->mapPaymentData($orderData['payments']);
        } else {
            $mapped['payments'] = [
                'payments_pay_mode' => [],
                'payments_bank_id' => [],
                'payments_branch_id' => [],
                'payments_transaction_id' => [],
                'payments_emi_id' => [],
                'payments_amount' => [],
                'payments_date' => [],
                'payments_attachments' => [],
                'payments_verified' => [],
                'payments_remark' => [],
            ];
        }

        return $mapped;
    }

    /**
     * Map order data and customer
     */
    protected function mapOrderData($orderData)
    {
        $data = [];

        // Find customer by ID if provided, otherwise by name
        if (isset($orderData['customer_id']) && !empty($orderData['customer_id'])) {
            $data['customer_id'] = $this->mapCustomerIdToId($orderData['customer_id']) ;
        } else {
            $data['customer_id'] = $this->mapCustomerNameToId($orderData['customer_name']);
        }

        // Map other basic fields
        $data['invoice_date'] = isset($orderData['invoice_date']) ? Carbon::parse($orderData['invoice_date'])->format('Y-m-d') : now()->format('Y-m-d');
        $data['delivery_date'] = isset($orderData['delivery_date']) ? Carbon::parse($orderData['delivery_date'])->format('Y-m-d') : null;

        // Calculate financial fields
        $data['total_amount'] = $orderData['total_amount'] ?? 0;
        $data['discount'] = $orderData['discount'] ?? 0;
        $data['commission'] = $orderData['commission'] ?? 0;
        $data['vat'] = $orderData['vat'] ?? 0;
        $data['net_amount'] = $orderData['net_amount'] ?? 0;
        $data['total'] =($orderData['total_amount'] ?? 0) - ($orderData['discount'] ?? 0) ;

        // Other fields
        $data['additional_phone'] = $orderData['additional_phone'] ?? null;
        $data['remarks'] = $orderData['remarks'] ?? null;
        $data['status'] = $orderData['status'] ?? 'pending';
        $data['sales_type'] = $orderData['sales_type'] ?? 'general_sales';
        $data['reference_id'] = $orderData['reference_id'] ?? null;

        // Preserve sales_order_id from JSON if provided, otherwise it will be generated by the service
        if (isset($orderData['sales_order_id']) && !empty($orderData['sales_order_id'])) {
            $data['sales_order_id'] = $orderData['sales_order_id'];
        }

        return $data;
    }

    /**
     * Check if customer exists by ID
     */
    protected function customerExistsById($customerId)
    {
        if (!$customerId)
            return false;

        $customer = Customer::where("customer_id",$customerId)->first();

        return $customer !== null;
    }

    // mapCustomerIdToId
    protected function mapCustomerIdToId($customerId)
    {
        if (!$customerId)
            return null;

        $customer = Customer::where("customer_id",$customerId)->first();

        return $customer ? $customer->id : null;
    }


    /**
     * Map customer name to ID
     */
    protected function mapCustomerNameToId($customerName)
    {
        if (!$customerName)
            return null;

        $customer = Customer::where('company_name', $customerName)
            ->first();

        return $customer ? $customer->id : null;
    }


    protected function mapProductCodeToId($productCode){
        if (!$productCode)
            return null;

        $product = ProductCatalog::where('product_code', $productCode)
            ->first();

        return $product ? $product->id : null;
    }

    /**
     * Map product name and model to ID
     */
    protected function mapProductNameToId($productName, $model = null)
    {
        if (!$productName)
            return null;

        $query = ProductCatalog::where('name',  $productName);

        // If model is provided, prioritize exact match on both name and model
        if ($model) {
            $product = $query->where('model',  $model)
                ->first();

            // If exact match not found, search by name only
            if (!$product) {
                $product = $query->first();
            }
        } else {
            // Search by name, and also check if model field contains the product name
            $product = $query
                ->orWhere('model', $productName)
                ->first();
        }

        return $product ? $product->id : null;
    }

    /**
     * Map courier name to ID
     */
    protected function mapCourierNameToId($courierName)
    {
        if (!$courierName)
            return null;

        $courier = Courier::where('courier_name', 'LIKE', "%{$courierName}%")
            ->first();

        return $courier ? $courier->id : null;
    }

    /**
     * Map area name to ID
     */
    protected function mapAreaNameToId($areaName)
    {
        if (!$areaName)
            return null;

        $area = Area::where('area', 'LIKE', "%{$areaName}%")
            ->first();

        return $area ? $area->id : null;
    }

    /**
     * Map shipment data
     */
    protected function mapShipmentData($shipmentData)
    {
        $data = [];

        $data['courier_id'] = isset($shipmentData['courier_name']) ? $this->mapCourierNameToId($shipmentData['courier_name']) : null;
        $data['area_id'] = isset($shipmentData['area_name']) ? $this->mapAreaNameToId($shipmentData['area_name']) : null;
        $data['address'] = $shipmentData['address'] ?? null;
        $data['contact_person_name'] = $shipmentData['contact_person_name'] ?? null;
        $data['contact_person_number'] = $shipmentData['contact_person_number'] ?? null;
        $data['condition'] = isset($shipmentData['condition']) ? ($shipmentData['condition'] ? 'on' : 'off') : null;
        $data['additional_amount'] = $shipmentData['additional_amount'] ?? null;
        $data['condition_remarks'] = $shipmentData['condition_remarks'] ?? null;

        return $data;
    }

    /**
     * Map payment data
     */
    protected function mapPaymentData($paymentsData)
    {
        // dd($paymentsData);
        $data = [
            'payments_pay_mode' => [],
            'payments_bank_id' => [],
            'payments_branch_id' => [],
            'payments_transaction_id' => [],
            'payments_emi_id' => [],
            'payments_amount' => [],
            'payments_date' => [],
            'payments_attachments' => [],
            'payments_verified' => [],
            'payments_remark' => [],
        ];

        if (isset($paymentsData['payment_details']) && is_array($paymentsData['payment_details'])) {
            // dd($paymentsData['payment_details']);
            foreach ($paymentsData['payment_details'] as $payment) {
                $data['payments_pay_mode'][] = $payment['pay_mode'] ?? 'Cash';
                $data['payments_bank_id'][] = isset($payment['bank_name']) ? ($payment['pay_mode'] === 'Cheque' ? $this->mapBankNameToId($payment['bank_name']) : $this->mapBankAccountNameToId($payment['bank_name'])) : null;
                $data['payments_branch_id'][] = isset($payment['branch_name']) ? $this->mapBranchNameToId($payment['branch_name']) : null;
                $data['payments_transaction_id'][] = $payment['transaction_id'] ?? null;
                $data['payments_emi_id'][] = $payment['emi_id'] ?? null;
                $data['payments_amount'][] = $payment['amount'] ?? 0;
                $data['payments_date'][] = isset($payment['payment_date']) ? Carbon::parse($payment['payment_date'])->format('Y-m-d') : now()->format('Y-m-d');
                $data['payments_attachments'][] = $payment['attachments'] ?? null;
                $data['payments_verified'][] = $payment['verified'] ?? false;
                $data['payments_remark'][] = $payment['remarks'] ?? null;
            }
        }

        return $data;
    }

    /**
     * Map bank name to ID
     */
    protected function mapBankNameToId($bankName)
    {
        if (!$bankName)
            return null;

        $bank = Bank::where('name', 'LIKE', "%{$bankName}%")
            ->first();

        return $bank ? $bank->id : null;
    }


    /**
     * Map bank account name to ID
     */
    protected function mapBankAccountNameToId($bankName)
    {
        if (!$bankName)
            return null;

        $bank = BankAccount::where('account_name', 'LIKE', "%{$bankName}%")
            ->first();

        return $bank ? $bank->id : null;
    }


    /**
     * Map branch name to ID
     */
    protected function mapBranchNameToId($branchName)
    {
        if (!$branchName)
            return null;

        $branch = BankBranch::where('name', 'LIKE', "%{$branchName}%")
            ->first();

        return $branch ? $branch->id : null;
    }

    /**
     * Validate JSON structure
     */
    protected function validateJsonStructure($data)
    {
        if (!isset($data['sales_orders']) || !is_array($data['sales_orders'])) {
            throw new Exception('JSON must contain "sales_orders" array');
        }

        if (empty($data['sales_orders'])) {
            throw new Exception('No sales orders found in JSON');
        }
    }

    /**
     * Validate order data
     */
    protected function validateOrderData($mappedData)
    {
        if (!$mappedData['data']['customer_id']) {
            throw new Exception('Invalid customer ID');
        }

        if (empty($mappedData['salesOrderDetails']['product_ids'])) {
            throw new Exception('No valid products found');
        }
    }

    /**
     * Reset statistics
     */
    protected function resetStats()
    {
        $this->errors = [];
        $this->warnings = [];
        $this->processed = 0;
        $this->successful = 0;
    }

    /**
     * Get statistics
     */
    public function getStats()
    {
        return [
            'processed' => $this->processed,
            'successful' => $this->successful,
            'failed' => $this->processed - $this->successful,
            'errors' => $this->errors,
            'warnings' => $this->warnings,
        ];
    }

    /**
     * Get errors
     */
    public function getErrors()
    {
        return $this->errors;
    }

    /**
     * Get warnings
     */
    public function getWarnings()
    {
        return $this->warnings;
    }
}