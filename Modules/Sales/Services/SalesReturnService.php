<?php

namespace Modules\Sales\Services;

use Illuminate\Support\Facades\DB;
use Modules\Account\Models\Account;
use Modules\Account\Models\AccountSetup\BankAccount;
use Modules\CRM\Models\Customer\Customer;
use Modules\Inventory\Models\ProductCatalog;
use Modules\Inventory\Services\StockService;
use Modules\Sales\Models\SalesOrder;
use Modules\Sales\Models\SalesReturn;
use Modules\Sales\Models\SalesReturnStock;

use function PHPUnit\Framework\isEmpty;

class SalesReturnService
{

    private $stockService;

    public function __construct(StockService $stockService)
    {
        $this->stockService = $stockService;
    }

    public function getAll(int $limit = 20)
    {
        return SalesReturn::query()
        ->searchByFields(['customer_id', 'status'])
        ->filterByDateRange('return_date')
        ->paginate($limit);
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
            'SCT-%02d-SC-%02d-%s-USR-%06d-SR-%06d',
            $authUserBranch,
            $authUserBranchType,
            date('Ymd'),
            $authUser,
            $licensesToday + 1
        );

        return $licenseNumber;
    }

    public function store(array $data, array $products, $payments)
    {
        $license_no = $this->getLicenseNumber();

        $data['invoice_no'] = $license_no;

        $salesReturn = SalesReturn::create($data);
        $result['salesReturn'] = $salesReturn;


        foreach ($products['checks'] as $key => $check) {
            $result['make_payment_details'][] = $salesReturn->salesReturnDetails()->create([
                'product_id' => $products['product_ids'][$key],
                'price' => $products['price'][$key],
                'quantity' => $products['quantity'][$key],
                'amount' => $products['amount'][$key],
                'unit_discount' => $products['unit_discount'][$key],
                'total_discount' => $products['total_discount'][$key],
            ]);
        }


        if (!empty($payments['payments_pay_mode'])) {
            foreach ($payments['payments_pay_mode'] as $key => $payment) {
                $paymentDetail = $salesReturn->paymentDetails()->create([
                    'pay_mode' => $payments['payments_pay_mode'][$key] ?? null,
                    'amount' => $payments['payments_amount'][$key] ?? 0,
                    'date' => $payments['payments_date'][$key] ?? null,
                    'bank_id' => $payments['payments_bank_id'][$key] ?? null,
                    'attachments' => $payments['payments_attachments'][$key] ?? null,
                    'verified' => $payments['payments_verified'][$key] ?? false,
                    'transaction_id' => $payments['payments_transaction_id'][$key] ?? null,
                    'remark' => $payments['payments_remark'][$key] ?? null,
                ]);
                $result['make_payment_details'][] = $paymentDetail;
            }
        }

        return $result;
    }

    public function update(SalesReturn $salesReturn, array $data, array $products, array $payments)
    {
        $salesReturn->update($data);
        $salesReturn->salesReturnDetails()->delete();

        foreach ($products['checks'] as $key => $check) {
            $salesReturn->salesReturnDetails()->create([
                'product_id' => $products['product_ids'][$key],
                'price' => $products['price'][$key],
                'quantity' => $products['quantity'][$key],
                'amount' => $products['amount'][$key],
                'unit_discount' => $products['unit_discount'][$key],
                'total_discount' => $products['total_discount'][$key],
            ]);
        }

        if (!empty($payments['payments_pay_mode'])) {
            $salesReturn->paymentDetails()->delete();

            foreach ($payments['payments_pay_mode'] as $key => $payment) {
                $paymentDetail = $salesReturn->paymentDetails()->create([
                    'pay_mode' => $payments['payments_pay_mode'][$key] ?? null,
                    'amount' => $payments['payments_amount'][$key] ?? 0,
                    'date' => $payments['payments_date'][$key] ?? null,
                    'bank_id' => $payments['payments_bank_id'][$key] ?? null,
                    'attachments' => $payments['payments_attachments'][$key] ?? null,
                    'verified' => $payments['payments_verified'][$key] ?? false,
                    'transaction_id' => $payments['payments_transaction_id'][$key] ?? null,
                    'remark' => $payments['payments_remark'][$key] ?? null,
                ]);
            }
        }

        return $salesReturn;
    }

    public function delete(SalesReturn $salesReturn)
    {
        $salesReturn->delete();
    }

    public function show($id)
    {
        return SalesReturn::findOrFail($id);
    }

    public function stockIn($returnStock)
    {
        if ($returnStock->lot_no) {
            $stock = $this->stockService->store([
                'product_id' => $returnStock->product_id,
                'source_type' => SalesReturnStock::class,
                'source_id' => $returnStock->id,
                'stock_type' => 'in',
                'in_qty' => $returnStock->quantity,
                'lot_no' => $returnStock->lot_no,
                'branch_id' => $returnStock->to_branch_id,
                'date' => $returnStock->salesReturnDetail?->salesReturn?->return_date

            ]);
            return $stock;
        }
        if ($returnStock->serial_no) {
            $stock = $this->stockService->store([
                'product_id' => $returnStock->product_id,
                'source_type' => SalesReturnStock::class,
                'source_id' => $returnStock->id,
                'stock_type' => 'in',
                'in_qty' => 1,
                'serial_no' => $returnStock->serial_no,
                'branch_id' => $returnStock->to_branch_id,
                'date' => $returnStock->salesReturnDetail?->salesReturn?->return_date,
            ]);
            return $stock;
        }
    }

    public function approveStore(array $data, array $returnDetails, array $returnStockDetails)
    {
        DB::beginTransaction();
        $return = SalesReturn::find($data['sales_return_id']);
        $return->update(
            [
                'status' => 'Returned',
                'approved_by' => auth()->id(),
                'approved_at' => now(),
            ]
        );
        $result['return'] = $return;
        /*foreach ($returnDetails['product_ids'] as $key => $product_id) {

            $returnDetail = $return->salesReturnDetails()->where('product_id', $product_id)->first();

            // Handle lot-based stock information
            if (isset($returnStockDetails['lot_no'][$product_id])) {
                foreach ($returnStockDetails['lot_no'][$product_id] as $key2 => $lotNo) {
                    $returnStock = SalesReturnStock::create([
                        'sales_return_detail_id' => $returnDetail->id,
                        'product_id' => $product_id,
                        'quantity' => $returnStockDetails['lots_quantity'][$product_id][$key2] ?? null,
                        'lot_no' => $lotNo,
                    ]);
                    $this->stockIn($returnStock);
                    $result['returnStock'][$returnDetail->id][] = $returnStock;
                }
            }

            // Handle serial-based stock information
            if (isset($returnStockDetails['serial_no'][$product_id])) {
                foreach ($returnStockDetails['serial_no'][$product_id] as $key2 => $serialNo) {
                    $returnStock = SalesReturnStock::create([
                        'sales_return_detail_id' => $returnDetail->id,
                        'product_id' => $product_id,
                        'serial_no' => $serialNo,
                    ]);
                    $this->stockIn($returnStock);
                    $result['returnStock'][$returnDetail->id][] = $returnStock;
                }
            }
        }*/
        $this->makeDummyTransaction($return);
        // dd ($result, $result['return']->transactions);
        DB::commit();
        return $result;
    }


    public function makeDummyTransaction(SalesReturn $salesReturn)
    {
        $salesReturn->transactions()->delete();



        // --- Revenue Layer (Reversal of Sale) ---
        // Debit Sales Return (Contra-Revenue)
        // Credit Customer (Receivable Decrease - Credit Note)

        $totalSellingPrice = 0;



        // Credit Customer (We owe them now, or reduce what they owe)
        $customerAccount = $salesReturn->customer->getAccount();
     
        $salesReturnAccount = Account::where('name', 'Sales Return')->first();
        $customerAccount = $salesReturn->customer->getAccount();
        /**
         *  Revenue Layer (based on selling price):
         *   Account	Debit	Credit
         *   Sales Return (Expense / Contra-Revenue)
         *   Income	Sales Income	Sales Returns & Allowances	Contra Revenue
         *   700	
         *   Customer Account	
         *   700
         */
        foreach ($salesReturn->salesReturnDetails as $detail) {
            $salesReturnAccount = $detail->product->getAccountForSalesReturnsAndAllowances();
            // Debit Sales Return   
            $salesReturn->transactions()->create([
                'account_id' => $salesReturnAccount->id,
                'balance_type' => 'debit',
                'invoice_no' => $salesReturn->invoice_no,
                'debit_amount' => $detail->amount,
                'credit_amount' => 0,
                'description' => 'Sales Return #' . $salesReturn->invoice_no,
                'transaction_date' => $salesReturn->return_date,
            ]);
        }
        $salesReturn->transactions()->create([
            'account_id' => $customerAccount->id,
            'balance_type' => 'credit',
            'invoice_no' => $salesReturn->invoice_no,
            'debit_amount' => 0,
            'credit_amount' => $salesReturn->total_amount,
            'description' => 'Sales Return #' . $salesReturn->invoice_no,
            'transaction_date' => $salesReturn->return_date,
        ]);


        // --- Refund/Payment Layer ---
        // If payment exists (Refund to customer)
        // Debit Customer (Liability Decrease / Payout)
        // Credit Bank/Cash (Asset Decrease)
        if ($salesReturn->paymentDetails->sum('amount') > 0) {
            //with payments


            foreach ($salesReturn->paymentDetails ?? [] as $payment) {
                if ($payment->amount > 0 && $payment->bank) {
                    // Check if bank exists
                    // Debit Customer
                    $salesReturn->transactions()->create([
                        'account_id' => $customerAccount->id,
                        'balance_type' => 'debit',
                        'invoice_no' => $salesReturn->invoice_no,
                        'debit_amount' => $payment->amount,
                        'credit_amount' => 0,
                        'description' => 'Sales Return #' . $salesReturn->invoice_no,
                        'transaction_date' => $salesReturn->return_date,
                    ]);

                    // Credit Bank/Cash
                    $salesReturn->transactions()->create([
                        'account_id' => $payment->bank->getAccount()->id,
                        'balance_type' => 'credit',
                        'invoice_no' => $salesReturn->invoice_no,
                        'debit_amount' => 0,
                        'credit_amount' => $payment->amount,
                        'description' => 'Sales Return #' . $salesReturn->invoice_no,
                        'transaction_date' => $salesReturn->return_date,
                    ]);
                }
            }
        }

        // --- Inventory Layer (Restocking at Cost) ---
        // Debit Inventory (Asset Increase)
        // Credit COGS (Expense Decrease)
        $cogsAccount = Account::where('account_number', 5300)->first();

        $totalCost = 0;

        foreach ($salesReturn->salesReturnDetails as $detail) {
            $inventoryAccount = $detail->product->getInventoryAccount();
            // dd($detail);
            // dd($detail->salesReturnStocks);
            $productCost = 0;
            foreach ($detail->salesReturnStocks as $stock) {
                $price = 0;
                $itemCost = 0;
                // dd($detail->product->is_serial_product, $stock->serial_no);
                if ($detail->product->is_serial_product) {
                    $price = ProductCatalog::find($stock->product_id)->getLandedPrice($stock->serial_no);
                    $itemCost = $price;
                    // dd($detail->product->is_serial_product, $stock->serial_no, $price);
                } else {
                    $price = ProductCatalog::find($stock->product_id)->getLandedPrice($stock->lot_no);
                    $itemCost = $stock->quantity * $price;
                }

                $totalCost += $itemCost;
                $productCost += $itemCost;
            }
            // Debit Inventory
            $salesReturn->transactions()->create([
                'account_id' => $inventoryAccount->id,
                'balance_type' => 'debit',
                'invoice_no' => $salesReturn->invoice_no,
                'debit_amount' => $productCost,
                'credit_amount' => 0,
                'description' => 'Sales Return #' . $salesReturn->invoice_no,
                'transaction_date' => $salesReturn->return_date,
            ]);
        }

        // Credit COGS
        if ($totalCost > 0) {
            $salesReturn->transactions()->create([
                'account_id' => $cogsAccount->id,
                'balance_type' => 'credit',
                'invoice_no' => $salesReturn->invoice_no,
                'debit_amount' => 0,
                'credit_amount' => $totalCost,
                'description' => 'Sales Return #' . $salesReturn->invoice_no,
                'transaction_date' => $salesReturn->return_date,
            ]);
        }

        // Verification (Optional but recommended)
        $totalDebits = $salesReturn->transactions()->sum('debit_amount');
        $totalCredits = $salesReturn->transactions()->sum('credit_amount');
        if (abs($totalDebits - $totalCredits) > 0.01) {
            throw new \Exception("Unbalanced journal entries for Sales Return #{$salesReturn->invoice_no}. Debits: $totalDebits, Credits: $totalCredits");
        }
    }

    /**
     * Map JSON data to database format
     */
    public function mapJson(array $jsonData): array
    {
        $customer = null;

        // If customer_name is provided, try to find existing customer and load their details
        if (!empty($jsonData['customer_name'])) {
            $customer = Customer::where('company_name', $jsonData['customer_name'])
                ->first();
        } elseif (!empty($jsonData['customer_id'])) {
            $customer = Customer::where('customer_id', $jsonData['customer_id'])
                ->first();
        }

        if (!$customer) {
            throw new \Exception("Customer not found: " . ($jsonData['customer_name'] ?? $jsonData['customer_id']));
        }

        $salerOrder = null;

        if (!empty($jsonData['invoice_no'])) {
            $salerOrder = SalesOrder::where('sales_order_id', $jsonData['invoice_no'])->first();
        }

        // if (!$salerOrder) {
        //     throw new \Exception("Sales Order not found: " . $jsonData['invoice_no']);
        // }

        // Prepare main sales return data
        $mainData = [
            'customer_id' => $customer->id,
            'return_date' => $jsonData['date'] ?? now()->toDateString(),
            'reference_invoice' => $jsonData['invoice_no'] ?? null,
            'deliveries_id' => '240231',
            'sales_order_id' => '240241',
            'total_amount' => 0, // Will be calculated
            'discount' => 0, // Will be calculated
            'net_amount' => 0, // Will be calculated
            'status' => $jsonData['status'] ?? 'Pending',
            'remarks' => $jsonData['remarks'] ?? null,
            'created_by' => $jsonData['created_by'] ?? auth()->id(),
        ];

        // Prepare sales return details (products)
        $salesReturnDetails = [
            'checks' => [],
            'product_ids' => [],
            'quantity' => [],
            'price' => [],
            'unit_discount' => [],
            'total_discount' => [],
            'amount' => [],
        ];

        $totalAmount = 0;
        $totalDiscount = 0;

        // Prepare payment details
        $paymentDetails = [
            'payments_pay_mode' => [],
            'payments_amount' => [],
            'payments_date' => [],
            'payments_bank_id' => [],
            'payments_attachments' => [],
            'payments_verified' => [],
            'payments_transaction_id' => [],
            'payments_remark' => [],
        ];

        // Initialize stocks structure separately from payment details
        $stockDetails = [
            'lot_no' => [],
            'lots_quantity' => [],
            'serial_no' => [],
        ];

        if (isset($jsonData['products']) && is_array($jsonData['products'])) {
            foreach ($jsonData['products'] as $key => $item) {
                 $product = null;
                 if(!empty($item['product_code'])) {
                     $product = \Modules\Inventory\Models\ProductCatalog::where('product_code', $item['product_code'])->first();
                 }
                // First try to find by product model if provided
                if (!empty($item['product_model'])) {
                    $product = \Modules\Inventory\Models\ProductCatalog::where('model', $item['product_model'])
                        ->where('name', $item['product_name'])
                        ->first();
                }

                if (!$product) {
                    throw new \Exception("Product not found: " . ($item['product_name'] . " - " . $item['product_model'] . " - " . $item['product_code']));
                }

                // Collect stocks from nested array (New Format)
                if (isset($item['stocks']) && is_array($item['stocks'])) {
                    foreach ($item['stocks'] as $s) {
                        if (!empty($s['lot_no'])) {
                            $stockDetails['lot_no'][$product->id][] = $s['lot_no'];
                            $stockDetails['lots_quantity'][$product->id][] = $s['lots_quantity'] ?? 1;
                        }
                        if (!empty($s['serial_no'])) {
                            $stockDetails['serial_no'][$product->id][] = $s['serial_no'];
                        }
                    }
                }

                // Collect lot_no and serial_no if present as direct fields (Fallback/Simple Format)
                if (!empty($item['lot_no']) && empty($item['stocks'])) {
                    $itemLotNos = is_array($item['lot_no']) ? $item['lot_no'] : [$item['lot_no']];
                    $itemLotQtys = is_array($item['lots_quantity'] ?? null) ? $item['lots_quantity'] : [($item['lots_quantity'] ?? $item['quantity'] ?? 1)];

                    foreach ($itemLotNos as $idx => $ln) {
                        $stockDetails['lot_no'][$product->id][] = $ln;
                        $stockDetails['lots_quantity'][$product->id][] = $itemLotQtys[$idx] ?? $itemLotQtys[0] ?? 1;
                    }
                }

                if (!empty($item['serial_no']) && empty($item['stocks'])) {
                    $itemSerialNos = is_array($item['serial_no']) ? $item['serial_no'] : [$item['serial_no']];
                    foreach ($itemSerialNos as $sn) {
                        $stockDetails['serial_no'][$product->id][] = $sn;
                    }
                }

                // Auto-load product price if not provided
                $price = $item['price'];
                $quantity = $item['quantity'] ?? 1;
                $unitDiscount = $item['unit_discount'] ?? 0;
                $totalDiscountForRow = $quantity * $unitDiscount;
                $amount = ($quantity * $price);

                $salesReturnDetails['checks'][] = $key; // Add check for each product
                $salesReturnDetails['product_ids'][] = $product->id;
                $salesReturnDetails['quantity'][] = $quantity;
                $salesReturnDetails['price'][] = $price;
                $salesReturnDetails['unit_discount'][] = $unitDiscount;
                $salesReturnDetails['total_discount'][] = $totalDiscountForRow;
                $salesReturnDetails['amount'][] = $amount;

                $totalAmount += ($quantity * $price);
                $totalDiscount += $totalDiscountForRow;
            }
        }

        // Calculate totals
        $mainData['total_amount'] = $totalAmount;
        $mainData['discount'] = $totalDiscount;
        $mainData['net_amount'] = $totalAmount - $totalDiscount;

        if (isset($jsonData['payments']) && is_array($jsonData['payments'])) {
            foreach ($jsonData['payments'] as $key => $payment) {
                $paymentDetails['payments_pay_mode'][] = $payment['pay_mode'] ?? null;
                $paymentDetails['payments_amount'][] = $payment['amount'] ?? 0;
                $paymentDetails['payments_date'][] = $payment['date'] ?? null;

                // Find BankAccount by account_name if provided, otherwise use bank_id
                $bankId = null;
                if (!empty($payment['bank_id'])) {
                    $bankAccount = BankAccount::where('account_name', $payment['bank_id'])->first();
                    $bankId = $bankAccount ? $bankAccount->id : null;
                } else {
                    $bankId = $payment['bank_id'] ?? null;
                }

                $paymentDetails['payments_bank_id'][] = $bankId;
                $paymentDetails['payments_attachments'][] = $payment['attachments'] ?? null;
                $paymentDetails['payments_verified'][] = $payment['verified'] ?? false;
                $paymentDetails['payments_transaction_id'][] = $payment['transaction_id'] ?? null;
                $paymentDetails['payments_remark'][] = $payment['remark'] ?? null;
            }
        }

        return [
            'main_data' => $mainData,
            'sales_return_details' => $salesReturnDetails,
            'payment_details' => $paymentDetails,
            'stock_details' => $stockDetails,
        ];
    }

    /**
     * Store data from JSON file
     */
    public function storeFromJsonFile()
    {
        $jsonFileDir = storage_path('app/json_formats');
        $jsonFile = $jsonFileDir . '/' . \Illuminate\Support\Str::snake(request()->input('name')) . '.json';

        // Ensure directory exists
        if (!is_dir($jsonFileDir)) {
            mkdir($jsonFileDir, 0755, true);
        }

        // Create file if it doesn't exist
        if (!file_exists($jsonFile)) {
            file_put_contents($jsonFile, json_encode([]));
        }

        $jsonData = json_decode(file_get_contents($jsonFile), true);

        if (empty($jsonData)) {
            return redirect()->back()->with('error', 'JSON file is empty.');
        }

        $savedCount = 0;
        $errors = [];

        foreach ($jsonData as $index => $item) {
            try {
                $mappedData = $this->mapJson($item);
                $result = $this->store(
                    $mappedData['main_data'],
                    $mappedData['sales_return_details'],
                    $mappedData['payment_details']
                );

                if (($item['status'] ?? '') === 'Approved' && !empty($mappedData['stock_details'])) {
                    $this->approveStore(
                        ['sales_return_id' => $result['salesReturn']->id],
                        $mappedData['sales_return_details'],
                        $mappedData['stock_details']
                    );
                }
                $savedCount++;
            } catch (\Exception $e) {
                $errors[] = "Row {$index}: " . $e->getMessage();
            }
        }

        $message = "Sales Returns import completed. Successfully saved: {$savedCount}";
        if (!empty($errors)) {
            $message .= '. Errors: ' . implode('; ', $errors);
        }

        return redirect()->back()->with('success', $message);
    }

    /**
     * Store data from direct API request
     */
    public function handleDirectImport($data = null)
    {
        if ($data === null) {
            return response()->json([
                'success' => false,
                'message' => 'No data provided.'
            ], 422);
        }

        $savedCount = 0;
        $errors = [];

        // Support both single object and array of objects
        $items = isset($data[0]) ? $data : [$data];

        foreach ($items as $index => $item) {
            try {
                DB::beginTransaction();
                // @dd($item);
                $mappedData = $this->mapJson($item);
                $result = $this->store(
                    $mappedData['main_data'],
                    $mappedData['sales_return_details'],
                    $mappedData['payment_details']
                );

                if (($item['status'] ?? '') === 'Approved' && !empty($mappedData['stock_details'])) {
                    $this->approveStore(
                        ['sales_return_id' => $result['salesReturn']->id],
                        $mappedData['sales_return_details'],
                        $mappedData['stock_details']
                    );
                }
                // dd($result);
                $savedCount++;
                DB::commit();
            } catch (\Exception $e) {
                DB::rollBack();
                $errors[] = "Row {$index}: " . $e->getMessage();
            }
        }

        $message = "Import completed. Successfully saved: {$savedCount}";
        if (!empty($errors)) {
            $message .= '. Errors: ' . implode('; ', $errors);
        }

        return response()->json([
            'success' => empty($errors) || $savedCount > 0,
            'message' => $message,
            'saved_count' => $savedCount,
            'error_count' => count($errors),
            'errors' => $errors
        ], empty($errors) ? 200 : 207);
    }
}
