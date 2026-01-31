<?php

namespace Modules\Sales\Services;


use Modules\Sales\Models\ShipmentVerify;
use App\Traits\S3FileHandler;
use Illuminate\Support\Facades\DB;
use Modules\Account\Models\Account;
use Modules\Sales\Models\SalesOrder;

class ShipmentVerifyService
{

    use S3FileHandler;

    public function getAll(int $limit = 20, $filters = [])
    {
        $query = ShipmentVerify::query()->with(['customer', 'courier', 'source.source.shipment']);

        // Handle status filter - if no status filter is provided, default to 'pending'
        if (!empty($filters['status'])) {
            if (in_array($filters['status'], ['condition', 'without_condition'])) {
                // For condition filtering, we need to handle it differently to avoid the relationship error
                if ($filters['status'] === 'condition') {
                    // Find shipment verifies where the related sales order has a shipment condition
                    $query->whereHas('source', function ($q) {
                        $q->where('source_type', SalesOrder::class)->whereHas('source', function ($q2) {
                            $q2->whereHas('shipment', function ($q3) {
                                $q3->where('condition', 1);
                            });
                        });
                    });
                } elseif ($filters['status'] === 'without_condition') {
                    // Find shipment verifies where the related sales order does not have a shipment condition
                    $query->whereHas('source', function ($q) {
                        $q->whereHas('source', function ($q2) {
                            $q2->where('source_type', SalesOrder::class)
                                ->whereHas('shipment', function ($q3) {
                                    $q3->where('condition', 1);
                                });
                        });
                    })->whereDoesntHave('source', function ($q) {
                        $q->whereHas('source', function ($q2) {
                            $q2->where('source_type', SalesOrder::class)->
                                whereHas('shipment', function ($q3) {
                                    $q3->where('condition', 1);
                                });
                        });
                    });
                }
            } else {
                $query->where('status', $filters['status']);
            }
        } else {
            // Default to pending status if no status filter is provided
            $query->where('status', 'pending');
        }

        // Filter by courier name (using LIKE for partial match)
        if (!empty($filters['courier_name'])) {
            $query->whereHas('courier', function ($q) use ($filters) {
                $q->where('courier_name', 'LIKE', '%' . $filters['courier_name'] . '%');
            });
        }

        // Filter by courier ID (exact match)
        if (!empty($filters['courier_id'])) {
            $query->where('courier_id', $filters['courier_id']);
        }

        // Filter by date range
        if (!empty($filters['from'])) {
            $query->whereDate('courier_date', '>=', $filters['from']);
        }

        if (!empty($filters['to'])) {
            $query->whereDate('courier_date', '<=', $filters['to']);
        }

        // Filter by customer_id (existing filter)
        if (!empty($filters['customer_id'])) {
            $query->where('customer_id', $filters['customer_id']);
        }

        // Filter by phone number (existing filter)
        if (!empty($filters['additional_phone'])) {
            $query->where('customer_address', 'LIKE', '%' . $filters['additional_phone'] . '%');
        }

        return $query->paginate($limit);
    }
    private function getShipmentId()
    {
        $shipment_count = ShipmentVerify::count();

        // Generate license number with the appropriate format
        $licenseNumber = sprintf(
            'SHP-%06d',
            $shipment_count + 1
        );

        return $licenseNumber;
    }
    public function initStore(array $data)
    {
        $shipments = $this->getShipmentId();
        return ShipmentVerify::create([
            'shipment_id' => $shipments,
            'customer_id' => $data['customer_id'],
            'customer_address' => $data['customer_address'],
            'challan_no' => $data['challan_no'],
            'courier_id' => $data['courier_id'],
            'courier_date' => $data['courier_date'],
            'source_id' => $data['source_id'],
            'source_type' => $data['source_type'],
        ]);
    }

    public function store(array $data)
    {
        return ShipmentVerify::create($data);
    }

    public function update(ShipmentVerify $shipmentVerify, array $data, $files = [])
    {
        return DB::transaction(function () use ($shipmentVerify, $data, $files) {
            $uploadedFilePaths = [];
            foreach ($files['files'] ?? [] as $file) {
                $uploadedFilePaths[] = $this->uploadFile($file, 'shipment_verifies');
            }

            if (count($uploadedFilePaths) > 0) {
                // To prevent orphaned files in S3, delete old files from storage.
                // The $shipmentVerify->files attribute is now an array of paths.
                foreach ($shipmentVerify->files ?? [] as $oldFilePath) {
                    $this->deleteFile($oldFilePath);
                }

                // Assign the new file paths array to the 'files' attribute.
                // The 'array' cast on the model will handle JSON encoding.
                $data['files'] = $uploadedFilePaths;
            }

            $shipmentVerify->update($data);

            if ($shipmentVerify->status == 'approved') {
                $this->makeDummyTransaction($shipmentVerify);
            }

            return $shipmentVerify;
        });
    }

    public function delete(ShipmentVerify $shipmentVerify)
    {
        $shipmentVerify->delete();
    }

    public function show($id)
    {
        return ShipmentVerify::with(['customer', 'courier', 'source', 'files'])->findOrFail($id);
    }

    public function makeDummyTransaction(ShipmentVerify $shipmentVerify)
    {
        $shipmentVerify->transactions()->delete();

        $service_charge = $shipmentVerify->service_charge ?? 0;
        $delivery_charge = $shipmentVerify->delivery_charge ?? 0;
        $other_charge = $shipmentVerify->other_charge ?? 0;

        $totalCourierCharge = $service_charge + $delivery_charge + $other_charge;

        if ($totalCourierCharge <= 0) {
            return;
        }

        // Customer Ledger (Receivable) - Debit
        $customer = $shipmentVerify->customer;
        $customerAccount = $customer->getAccount();

        // Employee Cash Account (User who entered the data) - Credit

        $employee = auth()->user()->employee;
        ;



        $employeeAccount = $employee?->getAccount();
        if (!$employeeAccount) {
            if (hasPermission('supper_admin')) {
                $employeeAccount = Account::where('name', 'Cash-in-Hand')->first();
            }
        }
        $invoice_no = $shipmentVerify->shipment_id;

        // Debit Transaction
        $shipmentVerify->transactions()->create([
            'account_id' => $customerAccount->id,
            'balance_type' => "debit",
            'invoice_no' => $invoice_no,
            'debit_amount' => $totalCourierCharge,
            'credit_amount' => 0,
            'description' => "Courier Charge Application",
            "transaction_date" => $shipmentVerify->courier_date
        ]);

        // Credit Transaction
        $shipmentVerify->transactions()->create([
            'account_id' => $employeeAccount->id,
            'balance_type' => "credit",
            'invoice_no' => $invoice_no,
            'debit_amount' => 0,
            'credit_amount' => $totalCourierCharge,
            'description' => "Courier Charge Application",
            "transaction_date" => $shipmentVerify->courier_date
        ]);
    }

    /**
     * Map JSON data to database format
     */
    public function mapJson(array $jsonData): array
    {
        // 1. Find the target ShipmentVerify record
        $shipmentVerify = null;

        if (!empty($jsonData['delivery_id'])) {
            $delivery = \Modules\Sales\Models\Delivery::find($jsonData['delivery_id']);

            if ($delivery) {
                // Case 1: ShipmentVerify is directly linked to the Delivery record
                $shipmentVerify = ShipmentVerify::where('source_type', get_class($delivery))
                    ->where('source_id', $delivery->id)
                    ->first();

                // Case 2: ShipmentVerify is linked to the same source as Delivery (e.g. SalesOrder)
                if (!$shipmentVerify) {
                    $shipmentVerify = ShipmentVerify::where('source_type', $delivery->source_type)
                        ->where('source_id', $delivery->source_id)
                        ->first();
                }
            }
        }

        // Fallback or alternative lookups if needed (e.g., by shipment_id directly)
        if (!$shipmentVerify && !empty($jsonData['shipment_id'])) {
            $shipmentVerify = ShipmentVerify::where('shipment_id', $jsonData['shipment_id'])->first();
        }

        if (!$shipmentVerify) {
            throw new \Exception("Shipment Verification record not found for Delivery ID: " . ($jsonData['delivery_id'] ?? 'N/A'));
        }

        // 2. Map fields to update
        $data = [];

        // Map simple fields if present in JSON
        if (isset($jsonData['service_charge']))
            $data['service_charge'] = $jsonData['service_charge'];
        if (isset($jsonData['delivery_charge']))
            $data['delivery_charge'] = $jsonData['delivery_charge'];
        if (isset($jsonData['other_charge']))
            $data['other_charge'] = $jsonData['other_charge'];
        if (isset($jsonData['status']))
            $data['status'] = $jsonData['status'];
        if (isset($jsonData['courier_date']))
            $data['courier_date'] = $jsonData['courier_date'];

        // Handle Courier ID lookup if courier_name is provided
        if (isset($jsonData['courier_name'])) {
            $courier = \Modules\Sales\Models\Courier::where('courier_name', $jsonData['courier_name'])->first();
            if ($courier) {
                $data['courier_id'] = $courier->id;
            }
        } elseif (isset($jsonData['courier_id'])) {
            $data['courier_id'] = $jsonData['courier_id'];
        }

        // 3. Prepare 'files' array structure if attachments are involved
        $files = [];

        return [
            'shipmentVerify' => $shipmentVerify,
            'data' => $data,
            'files' => $files
        ];
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
        $items = (isset($data['delivery_id']) || isset($data['shipment_id'])) ? [$data] : $data;

        foreach ($items as $index => $item) {
            try {
                $mapped = $this->mapJson($item);

                // Call the existing update method
                $this->update(
                    $mapped['shipmentVerify'],
                    $mapped['data'],
                    $mapped['files']
                );

                $savedCount++;
            } catch (\Exception $e) {
                $errors[] = "Item index {$index}: " . $e->getMessage();
            }
        }

        $message = "Import completed. Updated: {$savedCount}";
        if (!empty($errors)) {
            $message .= '. Errors: ' . implode('; ', $errors);
        }

        $statusCode = empty($errors) ? 200 : 207;

        return response()->json([
            'success' => empty($errors) || $savedCount > 0,
            'message' => $message,
            'updated_count' => $savedCount,
            'error_count' => count($errors),
            'errors' => $errors
        ], $statusCode);
    }
}
