<?php

// Example JSON structure for testing the Sales Order Import functionality

// This is an example of the JSON format that can be imported

$example_json = [
    'sales_orders' => [
        [
            'customer_name' => 'ABC Corporation Ltd',
            'invoice_date' => '2024-09-08',
            'delivery_date' => '2024-09-12',
            'total_amount' => 10000.00,
            'discount' => 500.00,
            'commission' => 0.00,
            'vat' => 1500.00,
            'net_amount' => 11000.00,
            'total' => 11000.00,
            'additional_phone' => '01712345678',
            'remarks' => 'Bulk import test order',
            'status' => 'pending',
            'sales_type' => 'general_sales',
            'products' => [
                [
                    'product_name' => 'HP Laptop 15s-fq0000',
                    'quantity' => 1,
                    'price' => 75000.00,
                    'unit_discount' => 500.00,
                    'total_discount' => 500.00,
                    'amount' => 74500.00
                ],
                [
                    'product_name' => 'HP Mouse Wireless',
                    'quantity' => 2,
                    'price' => 1500.00,
                    'unit_discount' => 0.00,
                    'total_discount' => 0.00,
                    'amount' => 3000.00
                ]
            ],
            'shipment' => [
                'courier_name' => 'Sundarban Courier Service',
                'area_name' => 'Dhaka Cantonment',
                'address' => 'House #12, Road #8, Dhanmondi, Dhaka',
                'contact_person_name' => 'Mr. Rahman',
                'contact_person_number' => '01876543210',
                'condition' => true,
                'additional_amount' => 200.00,
                'condition_remarks' => 'Fragile items - handle with care'
            ],
            'payments' => [
                'payment_details' => [
                    [
                        'pay_mode' => 'Cash',
                        'amount' => 40000.00,
                        'payment_date' => '2024-09-08',
                        'verified' => true,
                        'remarks' => 'Cash payment received'
                    ],
                    [
                        'pay_mode' => 'Cheque',
                        'bank_name' => 'Eastern Bank Ltd',
                        'branch_name' => 'Gulshan Branch',
                        'transaction_id' => 'CHK20240908001',
                        'amount' => 37500.00,
                        'payment_date' => '2024-09-08',
                        'verified' => false,
                        'remarks' => 'Post dated cheque'
                    ]
                ]
            ]
        ],
        [
            'customer_name' => 'XYZ Trading Company',
            'invoice_date' => '2024-09-08',
            'delivery_date' => '2024-09-10',
            'total_amount' => 5000.00,
            'discount' => 250.00,
            'commission' => 0.00,
            'vat' => 750.00,
            'net_amount' => 5500.00,
            'total' => 5500.00,
            'additional_phone' => '01987654321',
            'remarks' => 'Second test order',
            'status' => 'approved',
            'sales_type' => 'general_sales',
            'products' => [
                [
                    'product_name' => 'Dell Desktop Computer',
                    'quantity' => 3,
                    'price' => 25000.00,
                    'unit_discount' => 0.00,
                    'total_discount' => 0.00,
                    'amount' => 75000.00,
                    'stock_details' => [
                        [
                            'batch_no' => 'BATCH001',
                            'quantity' => 2,
                            'type' => 'lot'
                        ],
                        [
                            'batch_no' => 'BATCH002',
                            'quantity' => 1,
                            'type' => 'lot'
                        ]
                    ]
                ],
                [
                    'product_name' => 'Software License Key',
                    'quantity' => 2,
                    'price' => 500.00,
                    'unit_discount' => 0.00,
                    'total_discount' => 0.00,
                    'amount' => 1000.00,
                    'stock_details' => [
                        [
                            'batch_no' => 'LIC2024001',
                            'quantity' => 1,
                            'type' => 'serial'
                        ],
                        [
                            'batch_no' => 'LIC2024002',
                            'quantity' => 1,
                            'type' => 'serial'
                        ]
                    ]
                ]
            ],
            'payments' => [
                'payment_details' => [
                    [
                        'pay_mode' => 'Online Deposit',
                        'bank_name' => 'City Bank Ltd',
                        'branch_name' => 'Uttara Branch',
                        'amount' => 5500.00,
                        'payment_date' => '2024-09-08',
                        'transaction_id' => 'OD20240908002',
                        'verified' => true,
                        'remarks' => 'Bkash payment'
                    ]
                ]
            ]
        ]
    ]
];

// To use this in your testing:

// 1. Save this as a JSON file named 'sales_orders_import.json'
// 2. Upload it via the web interface at /sales/sales-order-import
// 3. The system will automatically:
//    - Map customer_name to customer ID in the customers table
//    - Map product_name to product ID in product_catalogs table
//    - Map courier_name to courier ID in couriers table
//    - Map area_name to area ID in areas table
//    - Create sales orders using the existing SalesOrderService
//    - Create deliveries for approved orders with STOCK DETAILS
//    - Handle payments and shipments properly
//    - PROCESS BATCH NO / SERIAL NO for each product delivery

// STOCK DETAILS FEATURE:
// Each product can now include stock_details array with:
// - batch_no: LOT001, SN1001 (for batch or serial numbers)
// - type: 'lot' or 'serial'
// - quantity: how many from this batch/serial to allocate
//
// When creating deliveries for APPROVED orders, the system will:
// 1. Store stock details in sales_order_details.stock_details
// 2. Automatically create DeliveryStock records for each batch/serial
// 3. Perform stock out operations for the specified batches
// 4. Handle both lot numbers and serial numbers properly

// Features of this import system:
// - Human-readable names instead of IDs
// - Batch processing (multiple orders per file)
// - STOCK DETAILS for precise inventory control
// - Automatic delivery creation for approved orders
// - Comprehensive error handling with rollback
// - Progress tracking and detailed statistics
// - File upload and JSON validation
// - Template download (includes latest 3 orders + examples)

echo json_encode($example_json, JSON_PRETTY_PRINT);