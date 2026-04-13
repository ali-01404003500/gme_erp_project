<?php

namespace Modules\Account\Services;

use Illuminate\Support\Arr;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB as FacadesDB;
use Modules\Account\Models\EMIEntry;
use Modules\Account\Models\EMIEntryDetail;
use Illuminate\Support\Facades\DB;
use Modules\Account\Models\Collections\Collection;
use Modules\Account\Services\Collections\CollectionService;
use Exception;
use Illuminate\Support\Str;
use Modules\CRM\Models\Customer\Customer;
use Modules\Sales\Models\SalesOrder;

class EMIEntryService
{
    /**
     * Undocumented variable
     *
     * @var  CollectionService
     */
    private $collectionService;

    public function __construct(CollectionService $collectionService)
    {
        $this->collectionService = $collectionService;
    }

    /**
     * Rollback an EMI collection: set status to 'due', delete payments, delete associated collection.
     *
     * @param int $emiDetailId
     * @return EMIEntryDetail
     * @throws Exception
     */
    public function getAll(int $limit = 20)
    {
        return EMIEntry::query()
            ->when(request()->filled('from'), function ($qr) {
                $qr->whereDate('created_at', '>=', Carbon::parse(request('from'))->format('Y-m-d'));
            })
            ->when(request()->filled('to'), function ($qr) {
                $qr->whereDate('created_at', '<=', Carbon::parse(request('to'))->format('Y-m-d'));
            })
            ->paginate($limit);
    }

    public function getInvoices($customer_id)
    {
        return SalesOrder::where('customer_id', $customer_id)->get();
    }

    public function store(array $data, array $emiDetails)
    {
        $data['emi_number'] = $this->getEMINumber();

        $result['eMIEntry'] = EMIEntry::create([
            'emi_number' => $data['emi_number'],
            'customer_id' => $data['customer_id'],
            'sales_order_id' => $data['sales_order_id'] ?? null,
            'start_date' => $data['start_date'],
            'tenure_type' => $data['tenure_type'],
            'tenure_no' => $data['tenure_no'],
            'interest_rate' => $data['interest_rate'],
            'emi_amount' => $data['amount'],
            'description' => $data['description'],
        ]);

        $result['eMIEntryDetail'] = [];
        foreach ($emiDetails['emi_date'] as $key => $value) {
            $result['eMIEntryDetail'][] = EMIEntryDetail::create([
                'emi_entry_id' => $result['eMIEntry']->id,
                'emi_date' => $value,
                'emi_amount' => $emiDetails['emi_amount'][$key] ?? 0,
                'interest_amount' => $emiDetails['interest_amount'][$key] ?? 0,
                'principal_amount' => $emiDetails['principal_amount'][$key] ?? 0,
            ]);
        }

        return $result;
    }

    public function reschedule(array $data, array $scheduleDetails)
    {
        DB::beginTransaction();
        try {
            // Get the original EMI entry
            $originalEmi = EMIEntry::findOrFail($data['emi_id']);

            // Get the original customer and sales order data
            $originalCustomerId = $originalEmi->customer_id;
            $originalSalesOrderId = $originalEmi->sales_order_id;

            // Update the original EMI status to 'rescheduled'
            $originalEmi->update([
                'status' => 'rescheduled',
            ]);

            // Mark all remaining unpaid EMI details as rescheduled
            EMIEntryDetail::where('emi_entry_id', $data['emi_id'])
                ->where('status', '!=', 'paid')
                ->update([
                    'status' => 'rescheduled',
                ]);

            // Prepare data for new EMI entry
            $newEmiData = [
                'customer_id' => $originalCustomerId,
                'sales_order_id' => $originalSalesOrderId,
                'start_date' => $data['schedule_date'],
                'tenure_type' => $data['tenure_type'],
                'tenure_no' => $data['tenure_no'],
                'interest_rate' => $data['interest_rate'],
                'amount' => $data['settlement_amount'],
                'description' => "Rescheduled from EMI #{$originalEmi->emi_number}",
            ];

            // Prepare schedule details for new EMI
            $newScheduleDetails = [
                'emi_date' => [],
                'emi_amount' => [],
                'interest_amount' => [],
                'principal_amount' => [],
            ];

            foreach ($scheduleDetails as $schedule) {
                $newScheduleDetails['emi_date'][] = $schedule['repayment_date'];
                $newScheduleDetails['emi_amount'][] = $schedule['emi_amount'];
                $newScheduleDetails['interest_amount'][] = $schedule['interest_amount'];
                $newScheduleDetails['principal_amount'][] = $schedule['principal_amount'];
            }

            // Create new EMI entry using existing store method
            $result = $this->store($newEmiData, $newScheduleDetails);

            DB::commit();

            return [
                'original_emi' => $originalEmi,
                'new_emi' => $result['eMIEntry'],
                'new_emi_details' => $result['eMIEntryDetail'],
            ];
        } catch (\Exception $e) {
            FacadesDB::rollback();
            throw $e;
        }
    }
     public function getReceiptNo()
    {
        $authUser = auth()->user()->id;
        $today = date('Ymd');
        $prefix = 'PR-';
        $sequence = $this->getSequence($prefix, $today);
        return sprintf('%s%s-USR-%05d-%06d', $prefix, $today, $sequence, $authUser, $sequence);
    }

    protected function getSequence(string $prefix, string $today)
    {
        $count = EMIEntryDetail::whereDate('created_at', $today)->count();
        return $count + 1;
    }
    public function collectionStore(array $validated)
    {
        DB::beginTransaction();
        $receipt_no = $this->getReceiptNo();
        $paymentAmount = 0;

        $emiEntry = EMIEntryDetail::findOrFail($validated['emi_detail_id']);

        $emiEntry->update([
            'status' => 'processing',
            'receipt_no' => $receipt_no,
        ]);
        $emiEntry->payments()->delete();
        foreach ($validated['payments'] as $payment) {
            $paymentAmount += $payment['amount'];
            $emiEntry->payments()->create([
                'pay_mode' => $payment['pay_mode'],
                'bank_id' => $payment['bank_id'] ?? null,
                'branch_id' => $payment['branch_id'] ?? null,
                'transaction_id' => $payment['transaction_id'] ?? null,
                'date' => $payment['date'],
                'amount' => $payment['amount'],
                'attachments' => $payment['attachments'] ?? null,
                'remarks' => $payment['remark'],
            ]);
        }
        $emiEntry->update([ 
            'paid_amount' => $emiEntry->paid_amount + $paymentAmount,
        ]);


        $collectionData = [
            'payments_total_amount' => $validated['payments_total_amount'],
            'payments_advance_amount' => $validated['payments_advance_amount'],
            'collection_type' => 'customer',
            'collection_from' => $emiEntry->emiEntry->customer_id,
        ];

            $this->collectionService->storeForSales($collectionData, $emiEntry->payments, $emiEntry);

        DB::commit();

        return $emiEntry;
    }
    public function settlementCollectionStore(array $validated)
    {
        DB::beginTransaction();
        $receipt_no = $this->getReceiptNo();

        $emiEntry = EMIEntry::findOrFail($validated['emi_id']);

        $emiEntry->update([
            'status' => 'settlement_processing',
        ]);
        $emiEntry->payments()->delete();
        foreach ($validated['payments'] as $payment) {
            $emiEntry->payments()->create([
                'pay_mode' => $payment['pay_mode'],
                'bank_id' => $payment['bank_id'] ?? null,
                'branch_id' => $payment['branch_id'] ?? null,
                'transaction_id' => $payment['transaction_id'] ?? null,
                'date' => $payment['date'],
                'amount' => $payment['amount'],
                'attachments' => $payment['attachments'] ?? null,
                'remarks' => $payment['remark'],
            ]);
        }

        $collectionData = [
            'payments_total_amount' => $validated['settlement_payments_total_amount'],
            'payments_advance_amount' => $validated['settlement_payments_advance_amount'],
            'collection_type' => 'customer',
            'collection_from' => $emiEntry->customer_id,
        ];

        $this->collectionService->storeForSales($collectionData, $emiEntry->payments, $emiEntry);

        $emiEntry->emiDetails()->where('status', '=', 'due')
            ->update(['status' => 'settlement_processing', 'receipt_no' => $receipt_no]);
            
        DB::commit();

        return $emiEntry;
    }

    public function getEMINumber()
    {
        $count_purchase_number = EMIEntry::count();
        if ($count_purchase_number == 0) {
            return 'EMI-' . date('Ymd') . '-' . str_pad($count_purchase_number + 1, 3, '0', STR_PAD_LEFT);
        } else {
            $last_job_id = EMIEntry::orderBy('id', 'desc')->pluck('id')->first();

            return 'EMI-' . date('Ymd') . '-' . str_pad($last_job_id + 1, 3, '0', STR_PAD_LEFT);
        }
    }

    public function update($id, array $data, array $emiDetails)
    {
        $emiEntry = EMIEntry::findOrFail($id);

        $emiEntry->update([
            'customer_id' => $data['customer_id'],
            'sales_order_id' => $data['sales_order_id'],
            'start_date' => $data['start_date'],
            'tenure_type' => $data['tenure_type'],
            'tenure_no' => $data['tenure_no'],
            'interest_rate' => $data['interest_rate'],
            'emi_amount' => $data['amount'],
            'description' => $data['description'],
        ]);

        // Delete existing schedule and re-insert
        EMIEntryDetail::where('emi_entry_id', $emiEntry->id)->delete();

        foreach ($emiDetails['emi_date'] as $key => $value) {
            EMIEntryDetail::create([
                'emi_entry_id' => $emiEntry->id,
                'emi_date' => $value,
                'emi_amount' => $emiDetails['emi_amount'][$key] ?? 0,
                'interest_amount' => $emiDetails['interest_amount'][$key] ?? 0,
                'principal_amount' => $emiDetails['principal_amount'][$key] ?? 0,
            ]);
        }

        return $emiEntry;
    }

    public function delete(EMIEntry $eMIEntry)
    {
        $eMIEntry->delete();
    }

    public function show($id)
    {
        return EMIEntry::findOrFail($id);
    }

    public function rollback(int $emiDetailId): EMIEntryDetail
    {
        DB::beginTransaction();

        try {
            $emiDetail = EMIEntryDetail::findOrFail($emiDetailId);

            if ($emiDetail->status !== 'processing') {
                throw new Exception('Rollback is only allowed for EMI details in "processing" status.');
            }

            // Fetch associated payments
            $payments = $emiDetail->payments; // Assuming 'payments' relationship on EMIEntryDetail
            // dd($payments, $emiDetail);
            if ($payments->isNotEmpty()) {
                // Get collection_id (assuming all payments share the same collection_id)
                $collectionId = $payments->first()->collection_id; // Assuming Payment has 'collection_id' column

                // Verify all payments have the same collection_id
                if ($payments->pluck('collection_id')->unique()->count() > 1) {
                    throw new Exception('Payments are associated with multiple collections. Rollback aborted.');
                }

                // Delete the collection
                $collection = Collection::findOrFail($collectionId);
                $collection->delete(); // Or use $this->collectionService->delete($collectionId) if you have a delete method

                // Delete payments (cascading or explicit)
                $emiDetail->payments()->delete();
            }

            // Update EMI detail status to 'due'
            $emiDetail->update(['status' => 'due']);

            DB::commit();

            return $emiDetail;
        } catch (Exception $e) {
            DB::rollback();
            throw $e;
        }
    }




    function mapEmiEntryJson(array $jsonData): array
    {
        // Map customer name to ID
        $customerId = Customer::where('customer_id', $jsonData['customer_name'])
            ->value('id') ?? throw new \Exception("Customer not found: {$jsonData['customer_name']}");

        // Map sales order reference to ID
        $salesOrder = SalesOrder::where('sales_order_id', $jsonData['sales_order_reference'])->first();
        // $salesOrderId = $salesOrder->id ?? throw new \Exception("Sales order not found: {$jsonData['sales_order_reference']}");
        $salesOrderId = $salesOrder->id ?? "";

        // Prepare main data (root level)
        $data = [
            'customer_id' => $customerId,
            'sales_order_id' => $salesOrderId,
            'start_date' => $jsonData['start_date'],
            'tenure_type' => $jsonData['tenure_type'],
            'tenure_no' => $jsonData['tenure_no'],
            'interest_rate' => $jsonData['interest_rate'] ?? null,
            'amount' => $jsonData['amount'],
            'description' => $jsonData['description'] ?? null,
        ];

        // Prepare EMI details arrays
        $emiDetails = [
            'emi_date' => [],
            'emi_amount' => [],
            'interest_amount' => [],
            'principal_amount' => [],
        ];

        foreach ($jsonData['emi_schedule'] as $emi) {
            $emiDetails['emi_date'][] = $emi['emi_date'];
            $emiDetails['emi_amount'][] = $emi['emi_amount'];
            $emiDetails['interest_amount'][] = $emi['interest_amount'];
            $emiDetails['principal_amount'][] = $emi['principal_amount'];
        }

        return [
            'data' => $data,
            'emiDetails' => $emiDetails
        ];
    }
     public function storeFromJsonFile()
    {
        $jsonFile = storage_path('app/json_formats/'.Str::snake(request()->input('name')).'.json');
        if(!file_exists($jsonFile)){
            file_put_contents($jsonFile, json_encode([]));
        }
        $data = json_decode(file_get_contents($jsonFile), true);
        return $this->handleDirectImport($data);
    }

    /**
     * Handle direct data import from API request
     */
    public function handleDirectImport($data)
    {
        if (empty($data)) {
            return response()->json([
                'success' => false,
                'message' => 'No data provided.'
            ], 422);
        }

        $savedCount = 0;
        $errors = [];

        DB::beginTransaction();
        // Support both single object and array of objects
        $items = isset($data[0]) ? $data : [$data];

        foreach ($items as $index => $item) {
            try {
                $mappedData = $this->mapEmiEntryJson($item);
                $this->store($mappedData['data'], $mappedData['emiDetails']);
                $savedCount++;
            } catch (\Exception $e) {
                $errors[] = "Row " . ($index + 1) . ": " . $e->getMessage();
            }
        }

        if (empty($errors)) {
            DB::commit();
            $message = "Import completed. Successfully saved: {$savedCount}";
        } else {
            DB::rollBack();
            $message = "Import failed. Errors: " . implode('; ', $errors);
        }

        return response()->json([
            'success' => empty($errors) || $savedCount > 0,
            'message' => $message,
            'saved_count' => $savedCount,
            'error_count' => count($errors),
            'errors' => $errors
        ], empty($errors) ? 200 : 207); // 207 Multi-Status if partial success
    }
}
