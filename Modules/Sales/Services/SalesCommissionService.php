<?php

namespace Modules\Sales\Services;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Modules\CRM\Models\Customer\Broker;
use Modules\CRM\Models\Customer\BrokerCommission;
use Modules\Sales\Models\SalesOrder;
use Modules\Sales\Models\SalesCommission;
use Illuminate\Support\Str;

class SalesCommissionService
{
    public function getAll(int $limit = 20)
    {
        return SalesCommission::query()
            ->whereIn('status', ['pending', 'verify', 'deny'])
            ->searchByFields(['broker_id', 'type'])
            ->when(request()->filled('from'), function ($qr) {
                $qr->where('commission_date', '>=', Carbon::parse(request('from'))->format('Y-m-d'));
            })
            ->when(request()->filled('to'), function ($qr) {
                $qr->where('commission_date', '<=', Carbon::parse(request('to'))->format('Y-m-d'));
            })
            ->paginate($limit);
    }

    public function calculateInvoiceData($salesOrder, $brokerId)
    {
        $commission = 0;
        $commissionableAmount = 0;

        $brokerCommissions = BrokerCommission::where('broker_id', $brokerId)->get()->keyBy('percentage_type');

        foreach ($salesOrder->details as $detail) {
            
            $fixedProductCommission = BrokerCommission::where('broker_id', $brokerId)->first();

            if($fixedProductCommission->fixed_type == $detail->product_id)
            {
                $productTagId = $fixedProductCommission->fixed_type;
                $amount = $detail->amount - $detail->total_discount ?? 0; 
                
                $commission += $detail->quantity *  $fixedProductCommission->fixed;
                $commissionableAmount += $amount; 
            }
            else
            {
                $productTagId = optional($detail->product)->product_tag_id;
                $amount = $detail->amount - $detail->total_discount ?? 0;

                if ($brokerCommissions->has($productTagId)) {
                    $percentage = $brokerCommissions[$productTagId]->percentage ?? 0;
                    $commission += ($percentage / 100) * $amount;
                    $commissionableAmount += $amount;
                }
            }
        }

        return [
            'commission' => $commission,
            'commissionable_amount' => $commissionableAmount,
        ];
    }

    public function storeCommissions($brokerId, array $commissionIds, array $bankIds)
    {
        DB::beginTransaction();
        try {
            foreach ($commissionIds as $item) {
                $bankId = $bankIds[$item] ?? null;

                if (is_numeric($item)) {
                    $exists = SalesCommission::where('broker_id', $brokerId)
                        ->where('type', 'invoice')
                        ->where('sales_order_id', $item)
                        ->exists();

                    if (!$exists) {
                        $salesOrder = SalesOrder::find($item);
                        if ($salesOrder) {
                            $data = $this->calculateInvoiceData($salesOrder, $brokerId);

                            SalesCommission::create([
                                'broker_id' => $brokerId,
                                'type' => 'invoice',
                                'sales_order_id' => $salesOrder->id,
                                'commission_date' => now(),
                                'amount' => $data['commission'],
                                'commissionable_amount' => $data['commissionable_amount'],
                                'broker_bank_id' => $bankId,
                            ]);
                        }
                    }
                    continue;
                }

                if (preg_match('/^(monthly|yearly|eid_ul_fitr|eid_ul_adha|durga_puja)_(\d{4})(?:_(\d{2}))?$/', $item, $matches)) {
                    $type = $matches[1];
                    $year = (int) $matches[2];
                    $month = isset($matches[3]) ? (int) $matches[3] : null;

                    $commissionDate = $month
                        ? Carbon::createFromDate($year, $month, 1)
                        : Carbon::createFromDate($year, 1, 1);

                    $existsQuery = SalesCommission::where('broker_id', $brokerId)
                        ->where('type', $type)
                        ->whereYear('commission_date', $year);

                    if ($month) {
                        $existsQuery->whereMonth('commission_date', $month);
                    }

                    if (!$existsQuery->exists()) {
                        SalesCommission::create([
                            'broker_id' => $brokerId,
                            'type' => $type,
                            'commission_date' => $commissionDate,
                            'amount' => $this->getFixedAmount($brokerId, $type),
                            'broker_bank_id' => $bankId,
                        ]);
                    }
                }
            }
            DB::commit();
        } catch (\Throwable $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function verifyCommissions(array $ids, string $action, $verifiedBy = null)
    {
        DB::beginTransaction();
        try {
            foreach ($ids as $id) {
                $commission = SalesCommission::find($id);
                if ($commission) {
                    if ($action == 'deny') {
                        $commission->delete();
                        continue;
                    }
                    $commission->update([
                        'status' => $action,
                        'verified_at' => now(),
                        'verified_by' => $verifiedBy,
                    ]);

                    if($commission->status == 'verify'){
                        $this->makeDummyTransaction($commission);
                    }
                }
            }
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function makeDummyTransaction(SalesCommission $commission)
    {
        if(get_class($commission->broker) == Broker::class){
            $commission->transactions()->delete();

            $expensesAccount = $commission->broker->getExpenseAccount();
            $payableAccount = $commission->broker->getAccount();

            $commission->transactions()->create([
                'account_id'            => $expensesAccount->id,
                'balance_type'          => "debit",
                'invoice_no'            => $commission->id,
                'debit_amount'          => $commission->amount,
                'credit_amount'         => 0,
                'description'           => "Bill Created. #" . $commission->id
            ]);

            $commission->transactions()->create([
                'account_id'            => $payableAccount->id,
                'balance_type'          => "credit",
                'invoice_no'            => $commission->id,
                'debit_amount'          => 0,
                'credit_amount'         => $commission->amount,
                'description'           => "Bill Created. #" . $commission->id
            ]);
        }
    }

    protected function getFixedAmount($brokerId, $type)
    {
        $commission = BrokerCommission::where('broker_id', $brokerId)->first();

        if (!$commission) {
            return 0;
        }

        switch ($type) {
            case 'monthly':
                return $commission->fixed_type == 2 ? $commission->fixed : 0;
            case 'yearly':
                return $commission->fixed_type == 3 ? $commission->fixed : 0;
            case 'eid_ul_fitr':
            case 'eid_ul_adha':
                return $commission->fixed_type == 4 ? $commission->fixed : 0;
            case 'durga_puja':
                return $commission->fixed_type == 5 ? $commission->fixed : 0;
            default:
                return 0;
        }
    }

    public function store(array $data)
    {
        return SalesCommission::create($data);
    }

    public function update(SalesCommission $salesCommission, array $data)
    {
        $salesCommission->update($data);
        return $salesCommission;
    }

    public function delete(SalesCommission $salesCommission)
    {
        $salesCommission->delete();
    }

    public function show($id)
    {
        return SalesCommission::findOrFail($id);
    }

    /**
     * Map JSON data to database format
     */
    public function mapJson(array $jsonData): array
    {
        // Map broker name to ID
        $broker = Broker::where('broker_name', $jsonData['broker_name'])
            ->first();
            
        if (!$broker) {
            throw new \Exception("Broker not found: {$jsonData['broker_name']}");
        }

        // Validate commission type
        $validTypes = ['invoice', 'monthly', 'yearly', 'eid_ul_fitr', 'eid_ul_adha', 'durga_puja'];
        if (!in_array($jsonData['type'], $validTypes)) {
            throw new \Exception("Invalid commission type: {$jsonData['type']}");
        }

        $mappedData = [
            'broker_id' => $broker->id,
            'type' => $jsonData['type'],
            'commission_date' => $jsonData['commission_date'] ?? now()->toDateString(),
            'amount' => $jsonData['amount'],
            'commissionable_amount' => $jsonData['commissionable_amount'] ?? null,
            'status' => $jsonData['status'] ?? 'pending',
        ];

        // If type is invoice, map sales_order_id
        if ($jsonData['type'] === 'invoice' && !empty($jsonData['sales_order_number'])) {
            $salesOrder = SalesOrder::where('sales_order_id', $jsonData['sales_order_number'])->first();
            if (!$salesOrder) {
                throw new \Exception("Sales Order not found: {$jsonData['sales_order_number']}");
            }
            $mappedData['sales_order_id'] = $salesOrder->id;
        }

        // Optional: Map broker bank if provided
        if (!empty($jsonData['broker_bank_id'])) {
            $mappedData['broker_bank_id'] = $jsonData['broker_bank_id'];
        }

        return $mappedData;
    }

    /**
     * Store data from JSON file
     */
    public function storeFromJsonFile()
    {
        $jsonFileDir = storage_path('app/json_formats');
        $jsonFile = $jsonFileDir . '/' . Str::snake(request()->input('name')) . '.json';

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
                $this->store($mappedData);
                $savedCount++;
            } catch (\Exception $e) {
                $errors[] = "Row {$index}: " . $e->getMessage();
            }
        }

        $message = "Sales Commissions import completed. Successfully saved: {$savedCount}";
        if (!empty($errors)) {
            $message .= '. Errors: ' . implode('; ', $errors);
        }

        return redirect()->back()->with('success', $message);
    }
}