<?php

namespace Modules\Purchase\Controllers;

use App\Http\Controllers\Controller;
use App\Models\AccessControl\Branch;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;
use Modules\CRM\Models\Customer\Customer;
use Modules\Inventory\Models\ProductCatalog;
use Modules\Inventory\Services\StockService;
use Modules\Purchase\Models\Requisition;
use Modules\Purchase\Models\RequisitionDetail;
use Modules\Purchase\Models\RequisitionReceive;
use Modules\Purchase\Models\RequisitionReceiveDetail;
use Modules\Purchase\Models\RequisitionReceiveBatch;
use Modules\Purchase\Models\RequisitionReceiveSerial;
use Modules\Purchase\Models\Supplier;
use Modules\Purchase\Services\RequisitionService;

class RequisitionImportController extends Controller
{
    protected $service;
    protected $stockService;

    public function __construct(RequisitionService $service, StockService $stockService)
    {
        $this->service = $service;
        $this->stockService = $stockService;
    }

    /**
     * Show import form
     */
    public function import()
    {
        return view('Purchase::requisition.import');
    }

    /**
     * Download JSON template
     */
    public function downloadTemplate()
    {
        $headers = [
            'Content-Type' => 'application/json',
            'Content-Disposition' => 'attachment; filename="requisition_import_template.json"',
        ];

        $jsonData = $this->getJsonTemplate();

        return response($jsonData, 200, $headers);
    }

    /**
     * Generate JSON template with sample data
     */
    private function getJsonTemplate()
    {
        $template = [
            [
                'requisition_no' => 'RN-25-0001',
                'customer_name' => 'John Doe Customer',
                'supplier_name' => 'ABC Supplier Ltd',
                'branch_name' => 'Main Branch',
                'invoice_date' => '2024-12-01',
                'description' => 'Sample requisition for migration',
                'total_amount' => 1000.00,
                'discount' => 50.00,
                'net_amount' => 950.00,
                'status' => 4,
                'approved_by_name' => 'Admin User',
                'created_at' => '2024-12-01 10:00:00',
                'products' => [
                    [
                        'name' => 'Test Product One',
                        'model'=> 'ICH-2024-V1',
                        'price' => 100.00,
                        'sales_price' => 120.00,
                        'quantity' => 2,
                        'amount' => 200.00,
                        'approved_quantity' => 2,
                        'received_quantity' => 2,
                        'serials' => [
                            [
                                'serial_no' => 'TP001',
                                'dongle_no' => 'DG001',
                                'manufacture_date' => '2024-01-01',
                                'quantity' => 1
                            ],
                            [
                                'serial_no' => 'TP002',
                                'dongle_no' => 'DG002',
                                'manufacture_date' => '2024-01-02',
                                'quantity' => 1
                            ]
                        ]
                    ],
                    [
                        'name' => 'TSH ® i-chroma™',
                        'model'=> 'ICH-2024-V1',
                        'price' => 200.00,
                        'sales_price' => 240.00,
                        'quantity' => 5,
                        'amount' => 1000.00,
                        'approved_quantity' => 5,
                        'received_quantity' => 5,
                        'batches' => [
                            [
                                'batch_no' => 'TSH-BT001',
                                'manufacture_no' => 'MF2024-001',
                                'lot_no' => 'LOT-TSH-001',
                                'expired_date' => '2025-12-01',
                                'quantity' => 1
                            ],
                            [
                                'batch_no' => 'TSH-BT002',
                                'manufacture_no' => 'MF2024-002',
                                'lot_no' => 'LOT-TSH-002',
                                'expired_date' => '2025-12-01',
                                'quantity' => 1
                            ]
                        ]
                    ]
                ],
                'purchase_invoice' => 'INV-2024-001'
            ]
        ];

        return json_encode($template, JSON_PRETTY_PRINT);
    }

    /**
     * Process JSON import with transaction per requisition
     */
    public function processImport(Request $request)
    {
        $request->validate([
            'json_file' => 'required|file|mimes:json|max:10240',
        ]);

        try {
            $jsonContent = file_get_contents($request->file('json_file')->getPathname());
            $records = json_decode($jsonContent, true);

            if (json_last_error() !== JSON_ERROR_NONE) {
                throw new \Exception('Invalid JSON format: ' . json_last_error_msg());
            }

            $imported = 0;
            $errors = [];

            foreach ($records as $index => $record) {
                try {
                    // Process each requisition in its own transaction
                    DB::transaction(function () use ($record, $index, &$imported) {
                        $this->importRequisitionRecord($record, $index + 1);
                        $imported++;
                    });
                } catch (\Exception $e) {
                    $errors[] = 'Record ' . ($index + 1) . ': ' . $e->getMessage();
                }
            }

            $message = "Successfully imported {$imported} requisitions.";
            if (!empty($errors)) {
                $message .= ' Errors: ' . implode(', ', array_slice($errors, 0, 5));
                if (count($errors) > 5) {
                    $message .= ' and ' . (count($errors) - 5) . ' more...';
                }
            }

            return redirect()->route('purchase.requisition.import')->with('success', $message);
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->with('error', 'Import failed: ' . $e->getMessage());
        }
    }

    /**
     * Import single requisition record from JSON
     */
    private function importRequisitionRecord(array $record, int $rowNumber)
    {
        // Validate and convert names to IDs
        $requisitionData = $this->validateRequisitionData($record, $rowNumber);

        // Parse product details and convert product names to IDs
        $productDetails = $this->parseProductDetails($record, $rowNumber);

        // Create or update requisition
        $requisition = $this->createOrUpdateRequisition($requisitionData);

        // Create product details
        $this->createProductDetails($requisition, $productDetails);

        // Handle serials and batches if product is received
        if ($requisition->status == 4) {
            // received status
            $this->handleSerialsAndBatches($requisition, $record, $productDetails, $rowNumber);
            $this->createRequisitionReceive($requisition, $record, $productDetails);

            // Create dummy transaction for received requisitions
            $this->makeDummyTransaction($requisition);
        }
    }

    /**
     * Validate main requisition data and convert names to IDs
     */
    private function validateRequisitionData(array $record, int $rowNumber): array
    {
        // Convert names to IDs
        $convertedRecord = $this->convertNamesToIds($record, $rowNumber);

        $validator = Validator::make($convertedRecord, [
            'requisition_no' => 'required|string|unique:requisitions,requisition_no',
            'customer_id' => 'nullable|exists:customers,id',
            'supplier_id' => 'nullable|exists:suppliers,id',
            'branch_id' => 'required|exists:branches,id',
            'invoice_date' => 'nullable|date',
            'description' => 'nullable|string',
            'total_amount' => 'nullable|numeric|min:0',
            'discount' => 'nullable|numeric|min:0',
            'net_amount' => 'nullable|numeric|min:0',
            'status' => 'required|in:0,1,2,3,4',
            'approved_by' => 'nullable|exists:users,id',
            'created_at' => 'nullable',
        ]);

        if ($validator->fails()) {
            throw new \Exception("Record {$rowNumber} validation failed: " . implode(', ', $validator->errors()->all()));
        }

        $data = $validator->validated();

        // Set defaults
        $data['created_at'] = $data['created_at'] ? Carbon::parse($data['created_at']) : now();
        $data['updated_at'] = now();

        return $data;
    }

    /**
     * Convert names to IDs for foreign key relationships
     */
    private function convertNamesToIds(array $record, int $rowNumber): array
    {
        $converted = $record;

        // Convert customer name to ID
        if (!empty($record['customer_name'])) {
            $customer = Customer::where('company_name',trim($record['customer_name']))->first();
            if (!$customer) {
                throw new \Exception("Record {$rowNumber}: Customer '{$record['customer_name']}' not found");
            }
            $converted['customer_id'] = $customer->id;
        }

        // Convert supplier name to ID
        if (!empty($record['supplier_name'])) {
            $supplier = Supplier::where('company_name', trim($record['supplier_name']))
                ->where('status', 1)
                ->first();
            if (!$supplier) {
                throw new \Exception("Record {$rowNumber}: Supplier '{$record['supplier_name']}' not found");
            }
            $converted['supplier_id'] = $supplier->id;
        }

        // Convert branch name to ID
        if (!empty($record['branch_name'])) {
            $branch = Branch::where('name', $record['branch_name'])->first();
            if (!$branch) {
                throw new \Exception("Record {$rowNumber}: Branch '{$record['branch_name']}' not found");
            }
            $converted['branch_id'] = $branch->id;
        }

        // Convert approved by name to user ID
        if (!empty($record['approved_by_name'])) {
            $user = User::where('name',trim($record['approved_by_name']))
                ->orWhere('email', 'LIKE', '%' . trim($record['approved_by_name']) . '%')
                ->first();
            if (!$user) {
                throw new \Exception("Record {$rowNumber}: User '{$record['approved_by_name']}' not found");
            }
            $converted['approved_by'] = $user->id;
        }

        return $converted;
    }

    /**
     * Parse product details from JSON and convert product names to IDs
     */
    private function parseProductDetails(array $record, int $rowNumber): array
    {
        $products = $record['products'] ?? [];
        if (empty($products)) {
            throw new \Exception("Record {$rowNumber}: No products specified");
        }

        $productIds = [];
        $productTypes = [];
        $prices = [];
        $salesPrices = [];
        $quantities = [];
        $amounts = [];
        $approvedQuantities = [];
        $receivedQuantities = [];

        foreach ($products as $index => $product) {
            if (empty(trim($product['name']))) {
                throw new \Exception("Record {$rowNumber}, Product {$index}: Product name cannot be empty");
            }

            // $productModel = ProductCatalog::where('name', $product['name'])
            //     ->where('model', $product['model'])
            //     ->where('status', 'active')
            //     ->first();
            $productModel = ProductCatalog::where('product_code', $product['product_code'])->first();

            

            if (!$productModel) {
                throw new \Exception("Record {$rowNumber}, Product {$index}: Product '{$product['name']}' not found or inactive");
            }

            $productIds[] = $productModel->id;
            $productTypes[$index] = [
                'is_serial' => $productModel->is_serial ?? 'no',
                'is_expire_date' => $productModel->is_expire_date ?? 'no',
                'product' => $productModel,
            ];
            $prices[] = $product['price'] ?? 0;
            $salesPrices[] = $product['sales_price'] ?? 0;
            $quantities[] = $product['quantity'] ?? 0;
            $amounts[] = $product['amount'] ?? 0;
            $approvedQuantities[] = $product['approved_quantity'] ?? $product['quantity'] ?? 0;
            $receivedQuantities[] = $product['received_quantity'] ?? $product['quantity'] ?? 0;
        }

        return [
            'product_ids' => $productIds,
            'product_types' => $productTypes,
            'prices' => $prices,
            'sales_prices' => $salesPrices,
            'quantities' => $quantities,
            'amounts' => $amounts,
            'approved_quantities' => $approvedQuantities,
            'received_quantities' => $receivedQuantities,
            'products' => $products,
        ];
    }

    /**
     * Create or update requisition
     */
    private function createOrUpdateRequisition(array $data): Requisition
    {
        $existing = Requisition::where('requisition_no', $data['requisition_no'])->first();
        if ($existing) {
            $existing->update($data);
            return $existing;
        }
        return Requisition::create($data);
    }

    /**
     * Create product details
     */
    private function createProductDetails(Requisition $requisition, array $productDetails)
    {
        RequisitionDetail::where('requisition_id', $requisition->id)->delete();

        foreach ($productDetails['product_ids'] as $key => $productId) {
            RequisitionDetail::create([
                'requisition_id' => $requisition->id,
                'product_id' => $productId,
                'price' => $productDetails['prices'][$key] ?? 0,
                'sales_price' => $productDetails['sales_prices'][$key] ?? 0,
                'quantity' => $productDetails['quantities'][$key] ?? 0,
                'amount' => $productDetails['amounts'][$key] ?? 0,
            ]);
        }
    }

    /**
     * Handle serials and batches for received products
     */
    private function handleSerialsAndBatches(Requisition $requisition, array $record, array $productDetails, int $rowNumber)
    {
        foreach ($productDetails['product_ids'] as $key => $productId) {
            $productType = $productDetails['product_types'][$key];
            $product = $productDetails['products'][$key];

            if ($productType['is_serial'] == 'yes') {
                if (!empty($product['batches'])) {
                    throw new \Exception("Record {$rowNumber}, Product {$key}: Serial product '{$product['name']}' cannot have batch data");
                }
                if (!empty($product['serials'])) {
                    $this->createSerials($requisition, $productId, $product['serials'], $rowNumber, $key);
                } else {
                    //throw new \Exception("Record {$rowNumber}, Product {$key}: Serial product '{$product['name']}' must have serial data");

                    $dummySerials = [];

                    $qty = $product['quantity'] ?? 1; // fallback quantity

                    for ($i = 1; $i <= $qty; $i++) {
                        $dummySerials[] = [
                            'serial' => 'DUMMY-' . $productId . '-' . $rowNumber . '-' . $i
                        ];
                    }

                    $this->createSerials($requisition, $productId, $dummySerials, $rowNumber, $key);
                    
                }
            } elseif ($productType['is_expire_date'] == 'yes') {
                if (!empty($product['serials'])) {
                    throw new \Exception("Record {$rowNumber}, Product {$key}: Batch product '{$product['name']}' cannot have serial data");
                }
                if (!empty($product['batches'])) {
                    $this->createBatches($requisition, $productId, $product['batches'], $rowNumber, $key);
                } else {
                    //throw new \Exception("Record {$rowNumber}, Product {$key}: Batch product '{$product['name']}' must have batch data");

                    $dummyBatches = [];

                    $qty = $product['quantity'] ?? 1;

                    for ($i = 1; $i <= $qty; $i++) {
                        $dummyBatches[] = [
                            'batch_no'    => 'DUMMY-BATCH-' . $productId . '-' . $rowNumber . '-' . $i,
                            'expire_date' => now()->addYear()->format('Y-m-d'), // default expiry (1 year)
                        ];
                    }

                    $this->createBatches($requisition, $productId, $dummyBatches, $rowNumber, $key);

                    
                }
            }  elseif ($productType['is_serial'] == 'no' && $productType['is_expire_date'] == 'no') {
                    
                $dummyBatches = [];

                $qty = $product['quantity'] ?? 1;

                for ($i = 1; $i <= $qty; $i++) {
                    $dummyBatches[] = [
                        'batch_no'    => 'DUMMY-BATCH-' . $productId . '-' . $rowNumber . '-' . $i,
                        'expire_date' => now()->addYear()->format('Y-m-d'), // default expiry (1 year)
                    ];
                }

                $this->createBatches($requisition, $productId, $dummyBatches, $rowNumber, $key);


            } else {
                throw new \Exception("Record {$rowNumber}, Product {$key}: Product '{$product['name']}' must be configured as either serial or batch product");
            }
        }
    }

    /**
     * Create serial records
     */
    private function createSerials(Requisition $requisition, $productId, array $serials, int $rowNumber, int $prodcutIndex)
    {
        foreach ($serials as $index => $serial) {
            $serialNo = trim($serial['serial_no'] ?? '');
            // if (empty($serialNo)) {
            //     continue;
            // }

            // Check for global uniqueness of serial_no
            // $existingSerial = RequisitionReceiveSerial::where('serial_no', $serialNo)->first();

            // if ($existingSerial) {
            //     throw new \Exception("Record {$rowNumber}, Product {$productIndex}, Serial {$index}: Serial number '{$serialNo}' already exists");
            // }

            $serialRecord = RequisitionReceiveSerial::create([
                'serial_no' => $serialNo,
                'product_id' => $productId,
                'requisition_id' => $requisition->id,
                'dongle_no' => trim($serial['dongle_no'] ?? '') ?: null,
                'manufacture_date' => !empty($serial['manufacture_date']) ? Carbon::parse($serial['manufacture_date']) : null,
                'quantity' => $serial['quantity'] ?? 1,
                'image' => null,
            ]);

            $this->stockService->store([
                'product_id' => $productId,
                'source_type' => RequisitionReceiveSerial::class,
                'source_id' => $serialRecord->id,
                'branch_id' => $requisition->branch_id,
                'stock_type' => 'in',
                'in_qty' => $serial['quantity'] ?? 1,
                'serial_no' => $serialNo,
                'date' => $requisition->invoice_date,
            ]);
            // dd($requisition->invoice_date);
        }
    }

    /**
     * Create batch records
     */
    private function createBatches(Requisition $requisition, $productId, array $batches, int $rowNumber, int $productIndex)
    {
        foreach ($batches as $index => $batch) {
            $batchNo = trim($batch['batch_no'] ?? '');
            // if (empty($batchNo)) {
            //     continue;
            // }

            // Check for global uniqueness of batch_no
            // $existingBatch = RequisitionReceiveBatch::where('batch_no', $batchNo)->first();

            // if ($existingBatch) {
            //     throw new \Exception("Record {$rowNumber}, Product {$productIndex}, Batch {$index}: Batch number '{$batchNo}' already exists");
            // }

            $batchRecord = RequisitionReceiveBatch::create([
                'batch_no' => $batchNo,
                'product_id' => $productId,
                'requisition_id' => $requisition->id,
                'manufacture_no' => trim($batch['manufacture_no'] ?? '') ?: null,
                'lot_no' => trim($batch['lot_no'] ?? '') ?: null,
                'expired_date' => !empty($batch['expired_date']) ? Carbon::parse($batch['expired_date']) : null,
                'quantity' => $batch['quantity'] ?? 0,
            ]);

            $this->stockService->store([
                'product_id' => $productId,
                'source_type' => RequisitionReceiveBatch::class,
                'source_id' => $batchRecord->id,
                'branch_id' => $requisition->branch_id,
                'stock_type' => 'in',
                'in_qty' => $batch['quantity'] ?? 0,
                'lot_no' => trim($batch['lot_no'] ?? '') ?: null,
                'date' => $requisition->invoice_date,
            ]);
        }
    }

    /**
     * Create requisition receive record for received status
     */
    private function createRequisitionReceive(Requisition $requisition, array $record, array $productDetails)
    {
        if ($requisition->status != 4) {
            return;
        }

        $receive = RequisitionReceive::create([
            'requisition_id' => $requisition->id,
            'purchase_invoice' => $record['purchase_invoice'] ?? null,
        ]);

        foreach ($productDetails['product_ids'] as $key => $productId) {
            RequisitionReceiveDetail::create([
                'product_id' => $productId,
                'requisition_receive_id' => $receive->id,
                'requisition_id' => $requisition->id,
                'approved_quantity' => $productDetails['approved_quantities'][$key],
                'received_quantity' => $productDetails['received_quantities'][$key],
            ]);
        }
    }

    /**
     * Validate import before processing
     */
    public function validateImport(Request $request)
    {
        $request->validate([
            'json_file' => 'required|file|mimes:json|max:10240',
        ]);

        try {
            $jsonContent = file_get_contents($request->file('json_file')->getPathname());
            $records = json_decode($jsonContent, true);

            if (json_last_error() !== JSON_ERROR_NONE) {
                throw new \Exception('Invalid JSON format: ' . json_last_error_msg());
            }

            $errors = [];
            $validCount = 0;

            foreach ($records as $index => $record) {
                try {
                    $this->validateRequisitionData($record, $index + 1);
                    $productDetails = $this->parseProductDetails($record, $index + 1);
                    $this->validateSerialBatchData($record, $productDetails, $index + 1);
                    $validCount++;
                } catch (\Exception $e) {
                    $errors[] = 'Record ' . ($index + 1) . ': ' . $e->getMessage();
                }
            }

            return response()->json([
                'valid_count' => $validCount,
                'error_count' => count($errors),
                'errors' => array_slice($errors, 0, 10),
                'total_errors' => count($errors),
            ]);
        } catch (\Exception $e) {
            return response()->json(
                [
                    'error' => 'Validation failed: ' . $e->getMessage(),
                ],
                422
            );
        }
    }

    /**
     * Validate serial/batch data consistency
     */
    private function validateSerialBatchData(array $record, array $productDetails, int $rowNumber)
    {
        foreach ($productDetails['product_types'] as $index => $productType) {
            $product = $productDetails['products'][$index];
            $serialData = $product['serials'] ?? [];
            $batchData = $product['batches'] ?? [];

            if ($productType['is_serial'] == 'yes') {
                if (!empty($batchData)) {
                    throw new \Exception("Record {$rowNumber}, Product {$index}: Serial product '{$product['name']}' cannot have batch data");
                }
                if (empty($serialData)) {
                    throw new \Exception("Record {$rowNumber}, Product {$index}: Serial product '{$product['name']}' must have serial data");
                }
                foreach ($serialData as $serialIndex => $serial) {
                    $serialNo = trim($serial['serial_no'] ?? '');
                    if (empty($serialNo)) {
                        continue;
                    }
                    // $existingSerial = RequisitionReceiveSerial::where('serial_no', $serialNo)->first();
                    // if ($existingSerial) {
                    //     throw new \Exception("Record {$rowNumber}, Product {$index}, Serial {$serialIndex}: Serial number '{$serialNo}' already exists");
                    // }
                }
            } elseif ($productType['is_expire_date'] == 'yes') {
                if (!empty($serialData)) {
                    throw new \Exception("Record {$rowNumber}, Product {$index}: Batch product '{$product['name']}' cannot have serial data");
                }
                if (empty($batchData)) {
                    throw new \Exception("Record {$rowNumber}, Product {$index}: Batch product '{$product['name']}' must have batch data");
                }
                foreach ($batchData as $batchIndex => $batch) {
                    $batchNo = trim($batch['batch_no'] ?? '');
                    if (empty($batchNo)) {
                        continue;
                    }
                    // $existingBatch = RequisitionReceiveBatch::where('batch_no', $batchNo)->first();
                    // if ($existingBatch) {
                    //     throw new \Exception("Record {$rowNumber}, Product {$index}, Batch {$batchIndex}: Batch number '{$batchNo}' already exists");
                    // }
                }
            } else {
                throw new \Exception("Record {$rowNumber}, Product {$index}: Product '{$product['name']}' must be configured as either serial or batch product");
            }
        }
    }

    /**
     * Get available names for reference
     */
    public function getAvailableNames()
    {
        $customers = Customer::select('id', 'company_name')->get();
        $suppliers = Supplier::where('status', 1)->select('id', 'company_name')->get();
        $branches = Branch::select('id', 'name')->get();
        $products = ProductCatalog::select('id', 'name', 'is_serial', 'is_expire_date')
            ->get()
            ->map(function ($product) {
                $type = 'regular';
                if ($product->is_serial == 'yes') {
                    $type = 'serial';
                } elseif ($product->is_expire_date == 'yes') {
                    $type = 'batch';
                }
                return [
                    'id' => $product->id,
                    'name' => $product->name,
                    'type' => $type,
                ];
            });
        $users = User::select('id', 'name', 'email')->get();

        return response()->json([
            'customers' => $customers,
            'suppliers' => $suppliers,
            'branches' => $branches,
            'products' => $products,
            'users' => $users,
        ]);
    }

    /**
     * Create dummy transaction for accounting
     */
    public function makeDummyTransaction(Requisition $requisition)
    {
        if (!$requisition->supplier) {
            return;
        }

        foreach ($requisition->requisitionDetails as $requisitionDetail) {
            $InventoryAccount = $requisitionDetail->product->getInventoryAccount();

            $requisition->transactions()->create([
                'account_id' => $InventoryAccount->id,
                'balance_type' => 'debit',
                'invoice_no' => $requisition->requisition_no,
                'debit_amount' => $requisitionDetail->amount,
                'credit_amount' => 0,
                'description' => 'Purchase Order Created. #' . $requisition->requisition_no,
                'transaction_date'      => $requisition->invoice_date

            ]);
        }

        $AccountsPayable = $requisition->supplier->getAccount();

        $requisition->transactions()->create([
            'account_id' => $AccountsPayable->id,
            'balance_type' => 'credit',
            'invoice_no' => $requisition->requisition_no,
            'debit_amount' => 0,
            'credit_amount' => $requisition->net_amount,
            'description' => 'Purchase Order Created. #' . $requisition->requisition_no,
            'transaction_date'      => $requisition->invoice_date

        ]);
    }
}