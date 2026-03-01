<?php

namespace Modules\Sales\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Sales\Services\SalesOrderImportService;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;

class SalesOrderImportController extends Controller
{
    protected $importService;

    public function __construct(SalesOrderImportService $importService)
    {
        $this->importService = $importService;
    }

    /**
     * Show the import form
     */
    public function index(): View
    {
        return view('Sales::sales-order-import.index');
    }

    /**
     * Validate the uploaded JSON file first
     */
    public function validateFile(Request $request): JsonResponse
    {
        try {
            $request->validate([
                'json_file' => 'required|file',
            ]);
            // dd($request->json_file);

            $jsonContent = file_get_contents($request->file('json_file')->getRealPath());
            // dd($jsonContent);
            $validationResult = $this->importService->validateJson($jsonContent);

            return response()->json($validationResult, $validationResult['valid'] ? 200 : 422);

        } catch (\Exception $e) {
            return response()->json([
                'valid' => false,
                'message' => 'Validation error: ' . $e->getMessage(),
                'errors' => [$e->getMessage()],
                'warnings' => []
            ], 500);
        }
    }

    /**
     * Process the uploaded JSON file
     */
    public function import(Request $request): JsonResponse
    {
        // set max exec time infinity, veriable size infinity, memory limit infinity
        ini_set('max_execution_time', 0); // unlimited execution time
        ini_set('memory_limit', '-1');    // unlimited memory

        set_time_limit(0); // alternative for execution time


        try {
            $validator = Validator::make($request->all(), [
                'json_file' => 'required|file', // 10MB max
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            $createDeliveries = $request->has('create_deliveries');
            $validateFirst = $request->boolean('validate_first', true); // Validate by default

            // Validate first if requested
            if ($validateFirst) {
                $jsonContent = file_get_contents($request->file('json_file')->getRealPath());
                $validationResult = $this->importService->validateJson($jsonContent);

                if (!$validationResult['valid']) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Pre-import validation failed',
                        'validation' => $validationResult,
                        'errors' => $validationResult['errors']
                    ], 422);
                }
            }

            $result = $this->importService->importFromFile(
                $request->file('json_file'),
                $createDeliveries
            );

            $response = [
                'success' => $result['failed'] === 0,
                'message' => $this->generateResultMessage($result),
                'stats' => $result
            ];

            return response()->json($response, $result['failed'] === 0 ? 200 : 207);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Import failed: ' . $e->getMessage(),
                'errors' => [$e->getMessage()]
            ], 500);
        }
    }

    /**
     * Process bulk JSON import via textarea
     */
    public function bulkImport(Request $request): JsonResponse
    {
        try {
            $validator = Validator::make($request->all(), [
                'json_data' => 'required|string',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            $createDeliveries = $request->has('create_deliveries');

            $result = $this->importService->importFromJson(
                $request->input('json_data'),
                $createDeliveries
            );

            $response = [
                'success' => $result['failed'] === 0,
                'message' => $this->generateResultMessage($result),
                'stats' => $result
            ];

            return response()->json($response, $result['failed'] === 0 ? 200 : 207);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Import failed: ' . $e->getMessage(),
                'errors' => [$e->getMessage()]
            ], 500);
        }
    }

    /**
     * Validate JSON structure without processing
     */
    public function validateJson(Request $request): JsonResponse
    {
        try {
            $validator = Validator::make($request->all(), [
                'json_data' => 'required|string',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'valid' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            $jsonData = json_decode($request->input('json_data'), true);

            if (json_last_error() !== JSON_ERROR_NONE) {
                return response()->json([
                    'valid' => false,
                    'message' => 'Invalid JSON format',
                    'errors' => ['JSON parsing error: ' . json_last_error_msg()]
                ]);
            }

            // Basic structure validation
            $errors = [];
            if (!isset($jsonData['sales_orders']) || !is_array($jsonData['sales_orders'])) {
                $errors[] = 'JSON must contain "sales_orders" array';
            } else {
                foreach ($jsonData['sales_orders'] as $index => $order) {
                    if (!isset($order['customer_name']) && !isset($order['customer_id'])) {
                        $errors[] = "Order " . ($index + 1) . ": customer_name is required";
                    }
                    if (!isset($order['products']) || !is_array($order['products']) && !empty($order['products_code'])) {
                        $errors[] = "Order " . ($index + 1) . ": products array is required";
                    }
                }
            }

            if (!empty($errors)) {
                return response()->json([
                    'valid' => false,
                    'message' => 'JSON validation failed',
                    'errors' => $errors
                ]);
            }

            return response()->json([
                'valid' => true,
                'message' => 'JSON structure is valid',
                'order_count' => count($jsonData['sales_orders'])
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'valid' => false,
                'message' => 'Validation error: ' . $e->getMessage(),
                'errors' => [$e->getMessage()]
            ], 500);
        }
    }

    /**
     * Download template as a file
     */
    public function downloadTemplate()
    {
        // Get latest 3 sales orders from the database
        $salesOrders = \Modules\Sales\Models\SalesOrder::with([
            'customer',
            'salesOrderDetails.product' => function ($query) {
                $query->with('brand', 'productType');
            },
            'salesOrderDetails',
            'payments',
            'shipment.courier',
            'shipment'
        ])
            ->latest()
            ->take(3)
            ->get();

        $template = ['sales_orders' => []];



        // If no sales orders found, use default template
        if (empty($template['sales_orders'])) {
            $template = [
                'sales_orders' => [
                    [
                        'sales_order_id' => 'SCT-01-SC-02-20251129-USR-000001-SL-000004',
                        'customer_id' => 'CUS-00000002632',
                        'invoice_date' => '2024-09-08',
                        'delivery_date' => '2024-09-10',
                        'total_amount' => 10000.00,
                        'discount' => 500.00,
                        'commission' => 0.00,
                        'vat' => 1500.00,
                        'net_amount' => 11000.00,
                        'total' => 11000.00,
                        'additional_phone' => '01234567890',
                        'remarks' => 'Bulk import sample (no existing sales orders found)',
                        'status' => 'pending',
                        'sales_type' => 'general_sales',
                        'is_offer' => false,
                        'products' => [
                            [
                                'product_code' => "PCT-01-SC-02-20251129-USR-000001-SL-000004",
                                'quantity' => 2,
                                'price' => 5000.00,
                                'unit_discount' => 100.00,
                                'total_discount' => 200.00,
                                'amount' => 1000.00,
                                'stock_details' => [
                                    [
                                        'batch_no' => 'LOT001',
                                        'quantity' => 1,
                                        'type' => 'lot'
                                    ],
                                    [
                                        'batch_no' => 'LOT002',
                                        'quantity' => 1,
                                        'type' => 'lot'
                                    ]
                                ]
                            ],
                            [
                                'product_code' => "PCT-01-SC-02-20251129-USR-000001-SL-000004",
                                'quantity' => 1,
                                'price' => 65500,
                                'unit_discount' => 100,
                                'total_discount' => 100,
                                'amount' => 65500,
                                'stock_details' => [
                                    [
                                        'batch_no' => 'SN1001',
                                        'quantity' => 1,
                                        'type' => 'serial'
                                    ]
                                ]
                            ]
                        ],
                        'shipment' => [
                            'courier_name' => 'Sundarban Courier',
                            'area_name' => 'Dhaka',
                            'address' => '123 Main St, Dhaka',
                            'contact_person_name' => 'John Doe',
                            'contact_person_number' => '01987654321',
                            'condition' => true,
                            'additional_amount' => 500.00,
                            'condition_remarks' => 'Handle with care'
                        ],
                        'payments' => [
                            'payment_details' => [
                                [
                                    'pay_mode' => 'Cash',
                                    'bank_name' => 'ABC Bank Ltd',
                                    'amount' => 5000.00,
                                    'payment_date' => '2024-09-08',
                                    'remarks' => 'Cash payment'
                                ],
                                [
                                    'pay_mode' => 'Cheque',
                                    'bank_name' => 'City Bank Ltd',
                                    'branch_name' => 'Motijheel Branch',
                                    'amount' => 6000.00,
                                    'payment_date' => '2024-09-08',
                                    'transaction_id' => 'CHKNO123',
                                    'remarks' => 'Cheque payment'
                                ]
                            ]
                        ]
                    ],
                    [
                        'sales_order_id' => 'SCT-01-SC-02-20251129-USR-000001-SL-000005',
                        'customer_id' => 'CUS-00000002632',
                        'invoice_date' => '2024-09-08',
                        'delivery_date' => '2024-09-10',
                        'total_amount' => 0.00,
                        'discount' => 0.00,
                        'commission' => 0.00,
                        'vat' => 0.00,
                        'net_amount' => 0.00,
                        'total' => 0.00,
                        'additional_phone' => '01234567890',
                        'remarks' => 'Free sales sample example',
                        'status' => 'pending',
                        'sales_type' => 'free_sales',
                        'is_offer' => false,
                        'products' => [
                            [
                                'product_name' => 'Free Sample Product B',
                                'model' => '456',
                                'quantity' => 1,
                                'price' => 0.00,
                                'unit_discount' => 0.00,
                                'total_discount' => 0.00,
                                'amount' => 0.00,
                                'stock_details' => [
                                    [
                                        'batch_no' => 'FREE001',
                                        'quantity' => 1,
                                        'type' => 'lot'
                                    ]
                                ]
                            ]
                        ],
                        'shipment' => [
                            'courier_name' => 'Sundarban Courier',
                            'area_name' => 'Dhaka',
                            'address' => '456 Free Sample St, Dhaka',
                            'contact_person_name' => 'Jane Doe',
                            'contact_person_number' => '01987654321',
                            'condition' => false,
                            'additional_amount' => 0.00,
                            'condition_remarks' => 'Free shipment'
                        ],
                        'payments' => [
                            'payment_details' => [
                                [
                                    'pay_mode' => 'Online Transfer',
                                    'bank_name' => 'City Bank Ltd',
                                    'amount' => 7000.00,
                                    'payment_date' => '2024-09-08',
                                    'transaction_id' => 'ODT789012',
                                    'remarks' => 'Online transfer payment'
                                ],
                                [
                                    'pay_mode' => 'bKash',
                                    'bank_name' => '01231234567',
                                    'amount' => 8000.00,
                                    'payment_date' => '2024-09-08',
                                    'transaction_id' => 'bKashtran123',
                                    'remarks' => 'bKash payment'
                                ]
                            ]
                        ]
                    ],
                    [
                        'customer_id' => 'CUS-00000002632',
                        'invoice_date' => '2024-09-08',
                        'delivery_date' => '2024-09-12',
                        'total_amount' => 25000.00,
                        'discount' => 1250.00,
                        'commission' => 250.00,
                        'vat' => 3750.00,
                        'net_amount' => 27750.00,
                        'total' => 27750.00,
                        'additional_phone' => '01567890123',
                        'remarks' => 'Partial payment sales example - 50% advance payment',
                        'status' => 'pending',
                        'sales_type' => 'partial_sales',
                        'is_offer' => false,
                        'products' => [
                            [
                                'product_name' => 'Enterprise Software License',
                                'quantity' => 5,
                                'price' => 5000.00,
                                'unit_discount' => 250.00,
                                'total_discount' => 1250.00,
                                'amount' => 23750.00,
                                'stock_details' => [
                                    [
                                        'batch_no' => 'ENT-BATCH-001',
                                        'quantity' => 3,
                                        'type' => 'lot'
                                    ],
                                    [
                                        'batch_no' => 'ENT-BATCH-002',
                                        'quantity' => 2,
                                        'type' => 'lot'
                                    ]
                                ]
                            ]
                        ],
                        'shipment' => [
                            'courier_name' => 'DHL Express',
                            'area_name' => 'Chittagong',
                            'address' => '789 Industrial Area, Chittagong',
                            'contact_person_name' => 'Mike Johnson',
                            'contact_person_number' => '01876543210',
                            'condition' => true,
                            'additional_amount' => 750.00,
                            'condition_remarks' => 'Fragile software licenses - handle with care'
                        ],
                        'payments' => [
                            'payment_details' => [
                                [
                                    'pay_mode' => 'Bank Transfer',
                                    'bank_name' => 'Dutch Bangla Bank Ltd',
                                    'amount' => 14000.00,
                                    'payment_date' => '2024-09-08',
                                    'transaction_id' => 'BT789456123',
                                    'remarks' => '50% advance payment'
                                ]
                            ]
                        ]
                    ]
                ]
            ];
        }

        $jsonContent = json_encode($template, JSON_PRETTY_PRINT);

        return response($jsonContent)
            ->header('Content-Type', 'application/json')
            ->header('Content-Disposition', 'attachment; filename="sales_orders_last_3_template.json"');
    }

    /**
     * Generate result message from stats
     */
    private function generateResultMessage(array $result): string
    {
        $total = $result['processed'];
        $success = $result['successful'];
        $failed = $result['failed'];

        if ($failed === 0) {
            return "Successfully imported all {$success} sales orders.";
        } elseif ($success === 0) {
            return "Failed to import all {$total} sales orders.";
        } else {
            return "Imported {$success} out of {$total} sales orders. {$failed} failed.";
        }
    }
}