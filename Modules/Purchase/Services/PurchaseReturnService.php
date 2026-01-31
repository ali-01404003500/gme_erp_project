<?php

namespace Modules\Purchase\Services;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Modules\Account\Models\AccountSetup\BankAccount;
use Modules\Account\Models\Bank;
use Modules\Account\Models\Setup\BankBranch;
use Modules\Purchase\Models\PurchaseReturn;
use Modules\Purchase\Models\PurchaseReturnDetail;
use Modules\Purchase\Models\Requisition;
use Modules\Purchase\Models\RequisitionReceive;
use Modules\Purchase\Models\Supplier;
use Modules\Inventory\Models\ProductCatalog;
use Modules\Purchase\Models\PurchaseReturnApprove;
use Modules\Purchase\Models\PurchaseReturnApproveDetail;
use Modules\Purchase\Models\PurchaseReturnApproveStock;

class PurchaseReturnService
{
    public function getAll(int $limit = 20)
    {
        return PurchaseReturn::query()->searchByFields(['supplier_id', 'status'])
        ->filterByDateRange('return_date')
        ->paginate($limit);
    }

    public function store(array $data, array $products, array $payments = [])
    {
        DB::beginTransaction();
        try {
            $purchaseReturn = PurchaseReturn::create($data);

            foreach ($products['product_ids'] as $key => $product_id) {
                if (isset($products['checks'][$key]) && $products['checks'][$key] == 1) {
                    PurchaseReturnDetail::create([
                        'purchase_return_id' => $purchaseReturn->id,
                        'product_id' => $product_id,
                        'quantity' => $products['quantity'][$key],
                        'price' => $products['price'][$key],
                        'recived_quantity' => $products['recived_quantity'][$key],
                        'amount' => $products['amount'][$key],
                    ]);
                }
            }

            // Store payment details
            if (!empty($payments['payments_pay_mode'])) {
                foreach ($payments['payments_pay_mode'] as $key => $payMode) {
                    $purchaseReturn->paymentDetails()->create([
                        'pay_mode' => $payments['payments_pay_mode'][$key] ?? null,
                        'amount' => $payments['payments_amount'][$key] ?? 0,
                        'date' => $payments['payments_date'][$key] ?? null,
                        'bank_id' => $payments['payments_bank_id'][$key] ?? null,
                        'attachments' => $payments['payments_attachments'][$key] ?? null,
                        'verified' => $payments['payments_verified'][$key] ?? false,
                        'transaction_id' => $payments['payments_transaction_id'][$key] ?? null,
                        'remark' => $payments['payments_remark'][$key] ?? null,
                        'paymentable_type' => get_class($purchaseReturn),
                        'paymentable_id' => $purchaseReturn->id,
                    ]);
                }
            }

            DB::commit();
            return $purchaseReturn;
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function update(PurchaseReturn $purchaseReturn, array $data, array $products, array $payments = [])
    {
        DB::beginTransaction();
        try {
            $purchaseReturn->update($data);
            $purchaseReturn->purchaseReturnDetails()->delete();

            foreach ($products['product_ids'] as $key => $product_id) {
                if (isset($products['checks'][$key]) && $products['checks'][$key] == 1) {
                    PurchaseReturnDetail::create([
                        'purchase_return_id' => $purchaseReturn->id,
                        'product_id' => $product_id,
                        'quantity' => $products['quantity'][$key],
                        'recived_quantity' => $products['recived_quantity'][$key],
                        'price' => $products['price'][$key],
                        'amount' => $products['amount'][$key],
                    ]);
                }
            }

            // Update payment details
            $purchaseReturn->paymentDetails()->delete();
            if (!empty($payments['payments_pay_mode'])) {
                foreach ($payments['payments_pay_mode'] as $key => $payMode) {
                    $purchaseReturn->paymentDetails()->create([
                        'pay_mode' => $payments['payments_pay_mode'][$key] ?? null,
                        'amount' => $payments['payments_amount'][$key] ?? 0,
                        'date' => $payments['payments_date'][$key] ?? null,
                        'bank_id' => $payments['payments_bank_id'][$key] ?? null,
                        'attachments' => $payments['payments_attachments'][$key] ?? null,
                        'verified' => $payments['payments_verified'][$key] ?? false,
                        'transaction_id' => $payments['payments_transaction_id'][$key] ?? null,
                        'remark' => $payments['payments_remark'][$key] ?? null,
                        'paymentable_type' => get_class($purchaseReturn),
                        'paymentable_id' => $purchaseReturn->id,
                    ]);
                }
            }

            DB::commit();
            return $purchaseReturn;
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function delete(PurchaseReturn $purchaseReturn)
    {
        DB::beginTransaction();
        try {
            $purchaseReturn->paymentDetails()->delete();
            $purchaseReturn->purchaseReturnDetails()->delete();
            $purchaseReturn->transactions()->delete();
            $purchaseReturn->delete();
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function show($id)
    {
        return PurchaseReturn::with(['purchaseReturnDetails', 'supplier', 'paymentDetails'])->findOrFail($id);
    }

    public function makeDummyTransaction(PurchaseReturn $purchaseReturn)
    {
        $purchaseReturn->transactions()->delete();

        $supplierPayableAccount = $purchaseReturn->supplier->getAccount();
        $totalRefunded = $purchaseReturn->paymentDetails()->sum('amount');

        $purchaseReturn->transactions()->create([
            'account_id' => $supplierPayableAccount->id,
            'balance_type' => 'debit',
            'invoice_no' => $purchaseReturn->invoice_no,
            'amount' => $purchaseReturn->net_amount,
            'debit_amount' => $purchaseReturn->net_amount,
            'credit_amount' => 0,
            'description' => 'Purchase Return Created. #' . $purchaseReturn->invoice_no,
        ]);

        foreach ($purchaseReturn->purchaseReturnDetails as $detail) {
            $inventoryAccount = $detail->product->getAccount();
            $purchaseReturn->transactions()->create([
                'account_id' => $inventoryAccount->id,
                'balance_type' => 'credit',
                'invoice_no' => $purchaseReturn->invoice_no,
                'amount' => -$detail->amount,
                'debit_amount' => 0,
                'credit_amount' => $detail->amount,
                'description' => 'Purchase Return Created. #' . $purchaseReturn->invoice_no,
            ]);
        }

        if ($totalRefunded > 0) {
            foreach ($purchaseReturn->paymentDetails as $payment) {
                if ($payment->bank) {
                    $purchaseReturn->transactions()->create([
                        'account_id' => $payment->bank->getAccount()->id,
                        'balance_type' => 'debit',
                        'invoice_no' => 'Purchase Return',
                        'amount' => $payment->amount,
                        'debit_amount' => $payment->amount,
                        'credit_amount' => 0,
                        'description' => 'Supplier Refund - ' . $payment->pay_mode . '. #' . $purchaseReturn->invoice_no,
                    ]);

                    
                }
            }
            $purchaseReturn->transactions()->create([
                'account_id' => $supplierPayableAccount->id,
                'balance_type' => 'credit',
                'invoice_no' => 'Purchase Return',
                'amount' => - $purchaseReturn->paymentDetails()->sum('amount'),
                'debit_amount' => 0,
                'credit_amount' => $purchaseReturn->paymentDetails()->sum('amount'),
                'description' => 'Supplier Refund - ' . $payment->pay_mode . '. #' . $purchaseReturn->invoice_no,
            ]);
        }
    }

    public function mapJson(array $jsonData): array
{
    $supplier = Supplier::where('company_name', $jsonData['supplier_name'])->first();
    if (!$supplier) {
        throw new \Exception("Supplier not found: {$jsonData['supplier_name']}");
    }

    $requisition = Requisition::where('requisition_no', $jsonData['requisition_no'])->first();
    if (!$requisition) {
        throw new \Exception("Requisition not found: {$jsonData['requisition_no']}");
    }

    // Try to find existing receive, if not found will create later
    $requisitionReceive = RequisitionReceive::where('requisition_id', $requisition->id)->first();
    $requisitionReceiveId = $requisitionReceive ? $requisitionReceive->id : null;

    $productIds = [];
    $quantities = [];
    $receivedQuantities = [];
    $prices = [];
    $amounts = [];
    $checks = [];
    $returnSerials = [];
    $returnBatches = [];

    foreach ($jsonData['products'] as $productData) {
        $product = ProductCatalog::where('name', $productData['name'])->where('model', $productData['model'])->first();

        if (!$product) {
            throw new \Exception("Product not found: {$productData['name']}");
        }

        $productIds[] = $product->id;
        $quantities[] = $productData['return_quantity'] ?? $productData['quantity'];
        $receivedQuantities[] = $productData['received_quantity'] ?? $productData['quantity'];
        $prices[] = $productData['price'];
        $amounts[] = $productData['return_amount'] ?? ($productData['return_quantity'] ?? $productData['quantity']) * $productData['price'];
        $checks[] = 1;

        // Store serials for return approval
        if (!empty($productData['serials'])) {
            $returnSerials[$product->id] = $productData['serials'];
        }

        // Store batches for return approval
        if (!empty($productData['batches'])) {
            $returnBatches[$product->id] = $productData['batches'];
        }
    }

    // Process payments with proper validation and mapping
    $payments = [
        'payments_pay_mode' => [],
        'payments_bank_id' => [],
        'payments_branch_id' => [],
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

        foreach ($jsonData['payments'] as $payment) {
            // Payment mode validation
            $validModes = ['Cash', 'Cheque', 'Online Deposit', 'bKash', 'Nagad', 'Rocket', 'Card', 'Card Payment', 'Bank Transfer'];
            if (!in_array($payment['pay_mode'], $validModes)) {
                throw new \Exception("Invalid payment mode: {$payment['pay_mode']}");
            }

            // Map bank references based on payment mode
            $bankId = null;
            if (!empty($payment['bank_name'])) {
                if ($payment['pay_mode'] === 'Cheque') {
                    // For Cheque, use Bank table
                    $bankId = $banks[$payment['bank_name']] ?? throw new \Exception("Bank not found: {$payment['bank_name']}");
                } else {
                    // For other modes, use BankAccount table
                    $bankId = $accounts[$payment['bank_name']] ?? throw new \Exception("Bank account not found: {$payment['bank_name']}");
                }
            }

            // Map branch reference
            $branchId = null;
            if (!empty($payment['branch_name'])) {
                $branchId = $branches[$payment['branch_name']] ?? throw new \Exception("Branch not found: {$payment['branch_name']}");
            }

            // Add to payments array
            $payments['payments_pay_mode'][] = $payment['pay_mode'];
            $payments['payments_bank_id'][] = $bankId;
            $payments['payments_branch_id'][] = $branchId;
            $payments['payments_transaction_id'][] = $payment['transaction_id'] ?? null;
            $payments['payments_date'][] = $payment['date'] ?? now()->format('Y-m-d');
            $payments['payments_amount'][] = $payment['amount'] ?? 0;
            $payments['payments_attachments'][] = $payment['attachment'] ?? $payment['attachments'] ?? null;
            $payments['payments_verified'][] = $payment['verified'] ?? false;
            $payments['payments_remark'][] = $payment['remark'] ?? null;
        }
    }

    return [
        'requisition_id' => $requisition->id,
        'requisition_receive_id' => $requisitionReceiveId,
        'supplier_id' => $supplier->id,
        'main_inv_discount' => $jsonData['main_inv_discount'] ?? 0,
        'discount' => $jsonData['discount'] ?? 0,
        'total_amount' => $jsonData['total_amount'],
        'net_amount' => $jsonData['net_amount'],
        'remarks' => $jsonData['remarks'] ?? null,
        'return_date' => $jsonData['return_date'],
        'reference_invoice' => $jsonData['reference_invoice'] ?? $jsonData['purchase_invoice'] ?? $requisition->requisition_no,
        'status' => $jsonData['status'] ?? 'Pending',
        'products' => [
            'product_ids' => $productIds,
            'quantity' => $quantities,
            'recived_quantity' => $receivedQuantities,
            'price' => $prices,
            'amount' => $amounts,
            'checks' => $checks,
        ],
        'payments' => $payments,
        'return_serials' => $returnSerials,
        'return_batches' => $returnBatches,
    ];
}


    private function processImportData(array $jsonData)
    {
        $savedCount = 0;
        $errors = [];

        foreach ($jsonData as $index => $item) {
            try {
                $mappedData = $this->mapJson($item);

                $invoiceNo = $this->generateInvoiceNumber($mappedData['supplier_id']);
                $mappedData['invoice_no'] = $invoiceNo;

                $products = $mappedData['products'];
                $payments = $mappedData['payments'];
                $returnSerials = $mappedData['return_serials'] ?? [];
                $returnBatches = $mappedData['return_batches'] ?? [];
                $status = $mappedData['status'] ?? 'Pending';

                unset($mappedData['products'], $mappedData['payments'], $mappedData['return_serials'], $mappedData['return_batches'], $mappedData['status']);

                $purchaseReturn = $this->store($mappedData, $products, $payments);

                // If status is "Returned", create receive and process stock
                if ($status === 'Returned') {
                    $this->processReturnedStatus($purchaseReturn, $returnSerials, $returnBatches);
                } 

                $savedCount++;
            } catch (\Exception $e) {
                $errors[] = "Row {$index}: " . $e->getMessage();
            }
        }

        return [
            'saved_count' => $savedCount,
            'errors' => $errors,
        ];
    }

    /**
     * Process purchase return when status is "Returned"
     * Creates RequisitionReceive and deducts stock (OUT)
     */
    private function processReturnedStatus(PurchaseReturn $purchaseReturn, array $returnSerials, array $returnBatches)
    {
        DB::beginTransaction();
        try {
            // Create or get RequisitionReceive
            $requisitionReceive = RequisitionReceive::where('requisition_id', $purchaseReturn->requisition_id)->first();

            if (!$requisitionReceive) {
                $requisitionReceive = RequisitionReceive::create([
                    'requisition_id' => $purchaseReturn->requisition_id,
                    'receive_date' => $purchaseReturn->return_date,
                    'remarks' => 'Created from Purchase Return: ' . $purchaseReturn->invoice_no,
                    'status' => 'Received',
                ]);
            }

            // Update purchase return with receive ID
            $purchaseReturn->update([
                'requisition_receive_id' => $requisitionReceive->id,
                'status' => 'Returned',
            ]);

            // Create PurchaseReturnApprove record
            $purchaseReturnApprove = PurchaseReturnApprove::create([
                'purchase_return_id' => $purchaseReturn->id,
            ]);

            // Create approve details for each product
            foreach ($purchaseReturn->purchaseReturnDetails as $detail) {
                $approveDetail = PurchaseReturnApproveDetail::create([
                    'p_r_approve_id' => $purchaseReturnApprove->id,
                    'product_id' => $detail->product_id,
                    'quantity' => $detail->quantity,
                    'price' => $detail->price,
                    'amount' => $detail->amount,
                ]);

                // Process serials - stock OUT (returning to supplier)
                if (isset($returnSerials[$detail->product_id])) {
                    foreach ($returnSerials[$detail->product_id] as $serialData) {
                        $returnStock = PurchaseReturnApproveStock::create([
                            'p_r_approve_detail_id' => $approveDetail->id,
                            'product_id' => $detail->product_id,
                            'serial_no' => $serialData['serial_no'],
                            'quantity' => 1,
                        ]);

                        // STOCK OUT - removing from inventory (returning to supplier)
                        app(\Modules\Inventory\Services\StockService::class)->store([
                            'product_id' => $detail->product_id,
                            'source_type' => PurchaseReturnApproveStock::class,
                            'source_id' => $returnStock->id,
                            'stock_type' => 'out',
                            'out_qty' => 1,
                            'serial_no' => $serialData['serial_no'],
                        ]);
                    }
                }

                // Process batches - stock OUT (returning to supplier)
                if (isset($returnBatches[$detail->product_id])) {
                    foreach ($returnBatches[$detail->product_id] as $batchData) {
                        $returnStock = PurchaseReturnApproveStock::create([
                            'p_r_approve_detail_id' => $approveDetail->id,
                            'product_id' => $detail->product_id,
                            'lot_no' => $batchData['lot_no'],
                            'quantity' => $batchData['quantity'],
                        ]);

                        // STOCK OUT - removing from inventory (returning to supplier)
                        app(\Modules\Inventory\Services\StockService::class)->store([
                            'product_id' => $detail->product_id,
                            'source_type' => PurchaseReturnApproveStock::class,
                            'source_id' => $returnStock->id,
                            'stock_type' => 'out',
                            'out_qty' => $batchData['quantity'],
                            'lot_no' => $batchData['lot_no'],
                        ]);
                    }
                }
            }

            // Create transactions
            $this->makeDummyTransaction($purchaseReturn);

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    private function generateInvoiceNumber($supplierId)
    {
        $today = date('Y-m-d');
        $authUser = auth()->user()->id;
        $authUserBranch = auth()->user()->branch_id;
        $authUserBranchType = auth()->user()->branch->branch_type_id;

        $licensesToday = PurchaseReturn::whereDate(DB::raw('DATE(created_at)'), $today)->where('created_by', $authUser)->count();

        return sprintf('SCT-%02d-SC-%02d-%s-USR-%06d-PR-%06d', $authUserBranch, $authUserBranchType, date('Ymd'), $authUser, $licensesToday + 1);
    }

/**
 * Store a new purchase return from a json file
 *
 * This function will first create the directory if it doesn't exist, then create the json file if it doesn't exist.
 * It will then read the json file and process the import data.
 * If the json file is empty, it will redirect back with an error message.
 * After processing the import data, it will redirect back with a success message and the number of successfully saved records.
 * If there are any errors during the import process, it will also display the error messages.
 *
 * @return \Illuminate\Http\RedirectResponse
 */
    public function storeFromJsonFile()
    {
        $jsonFileDir = storage_path('app/json_formats');
        $jsonFile = $jsonFileDir . '/' . Str::snake(request()->input('name')) . '.json';

        if (!is_dir($jsonFileDir)) {
            mkdir($jsonFileDir, 0755, true);
        }

        if (!file_exists($jsonFile)) {
            file_put_contents($jsonFile, json_encode([]));
        }

        $jsonData = json_decode(file_get_contents($jsonFile), true);

        if (empty($jsonData)) {
            return redirect()->back()->with('error', 'JSON file is empty.');
        }

        $result = $this->processImportData($jsonData);

        $message = "Purchase Returns import completed. Successfully saved: {$result['saved_count']}";
        if (!empty($result['errors'])) {
            $message .= '. Errors: ' . implode('; ', $result['errors']);
        }

        return redirect()->back()->with('success', $message);
    }

    public function handleDirectImport($data)
    {
        if (empty($data)) {
            return response()->json(
                [
                    'success' => false,
                    'message' => 'No data provided.',
                ],
                422,
            );
        }

        $items = isset($data[0]) ? $data : [$data];
        $result = $this->processImportData($items);

        $message = "Purchase Returns import completed. Successfully saved: {$result['saved_count']}";
        if (!empty($result['errors'])) {
            $message .= '. Errors: ' . implode('; ', $result['errors']);
        }

        return response()->json(
            [
                'success' => empty($result['errors']) || $result['saved_count'] > 0,
                'message' => $message,
                'saved_count' => $result['saved_count'],
                'error_count' => count($result['errors']),
                'errors' => $result['errors'],
            ],
            empty($result['errors']) ? 200 : 207,
        );
    }
}
