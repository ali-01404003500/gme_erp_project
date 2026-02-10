<?php

namespace Modules\Services\Services;

use App\Models\OtpVerification;
use Illuminate\Support\Facades\DB;
use Modules\Sales\Services\SalesOrderService;
use Modules\Sales\Models\SalesOrder;
use Modules\Services\Models\Service;
use Modules\Services\Models\ServiceMyTask;

class ServiceMyTaskService
{
    /**
     * it is for sales order service
     * @var SalesOrderService
     */
    private $salesOrderService;


    public function __construct(SalesOrderService $salesOrderService)
    {
        $this->salesOrderService = $salesOrderService;
    }
    /**
     * Get all service entries
     *
     * @param int $limit
     *
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function getAll(int $limit = 20) {
        return Service::query()->paginate($limit);
    }
    
    public function store(array $data, $pendingServiceToken = [], $payments = [], $serviceBills = [], $serviceReturnBills = [],   array $serviceMyTaskShipments = [])
    {
        DB::beginTransaction();
        // dd(request()->all());
        try {
            // Update or create main ServiceMyTask entry
            $serviceMyTask = ServiceMyTask::updateOrCreate(
                ['service_token_id' => $data['service_token_id'] ?? null],
                $data
            );

            // dd($serviceMyTask,  $data);

            // Clean up previous relations if it's an update
            $serviceMyTask->pendingServiceTokens()->delete();
            $serviceMyTask->bills()->delete();
            $serviceMyTask->returnBills()->delete();
            $serviceMyTask->payments()->delete(); // assuming relation name is `payments()`

            $result['serviceMyTask'] = $serviceMyTask;

            // Save pending service tokens
            foreach ($pendingServiceToken['pending_token_ids'] ?? [] as $pendingServiceTokenId) {
                if ($pendingServiceTokenId) {
                    $result['pendingServiceToken'][] = $serviceMyTask->pendingServiceTokens()->create([
                        'service_token_id' => $pendingServiceTokenId,
                        'description' => $pendingServiceToken['pending_descriptions'][$pendingServiceTokenId] ?? null,
                    ]);
                }
            }


            // Save service bills
            if( $data['bill_type'] == 'service_bill' ) {
                foreach ($serviceBills['bill_product_ids'] ?? [] as $key => $productId) {
                    if ($productId) {
                        $result['serviceBills'][] = $serviceMyTask->bills()->create([
                            'product_id' => $productId,
                            'quantity' => $serviceBills['bill_quantity'][$key] ?? 0,
                            'price' => $serviceBills['bill_price'][$key] ?? 0,
                            'unit_discount' => $serviceBills['bill_unit_discount'][$key] ?? 0,
                            'total_discount' => $serviceBills['bill_total_discount'][$key] ?? 0,
                            'amount' => $serviceBills['bill_amount'][$key] ?? 0,
                        ]);
                    }
                }
            }
            

            if($data['bill_type'] == 'service_return_bill') {
                // Save service return bills
                foreach ($serviceReturnBills['return_bill_product_ids'] ?? [] as $key => $productId) {
                    if ($productId) {
                        $result['serviceReturnBills'][] = $serviceMyTask->returnBills()->create([
                            'product_id' => $productId,
                            'quantity' => $serviceReturnBills['return_bill_quantity'][$key] ?? 0,
                            'price' => $serviceReturnBills['return_bill_price'][$key] ?? 0,
                            'unit_discount' => $serviceReturnBills['return_bill_unit_discount'][$key] ?? 0,
                            'total_discount' => $serviceReturnBills['return_bill_total_discount'][$key] ?? 0,
                            'amount' => $serviceReturnBills['return_bill_amount'][$key] ?? 0,
                        ]);
                    }
                }
            } 
            

            // Save payments
            foreach ($payments['payments_pay_mode'] ?? [] as $key => $payMode) {
                if ($payMode) {
                    $result['payments'][] = $serviceMyTask->payments()->create([
                        'pay_mode' => $payMode,
                        'bank_id' => $payments['payments_bank_id'][$key] ?? null,
                        'branch_id' => $payments['payments_branch_id'][$key] ?? null,
                        'transaction_id' => $payments['payments_transaction_id'][$key] ?? null,
                        'e_m_i_entries_id' => $payments['payments_emi_id'][$key] ?? null,
                        'amount' => $payments['payments_amount'][$key] ?? 0,
                        'date' => $payments['payments_date'][$key] ?? null,
                        'attachments' => $payments['payments_attachments'][$key] ?? null,
                        'verified' => $payments['payments_verified'][$key] ?? false,
                        'remarks' => $payments['payments_remark'][$key] ?? null,
                        'remarks' => $payments['payments_remark'][$key] ?? null
                    ]);
                }
            }
            // dd($serviceMyTask->serviceToken);

            switch ($serviceMyTask->status) {
                case 'pending':
                    break;

                case 'approved':
                    $serviceMyTask->serviceToken->update(['action' => 'Done']);
                    $this->storeToSalesOrders($serviceMyTask);
                    break;

                case 'rejected':
                case 'cancelled':
                    $serviceMyTask->serviceToken->update(['action' => 'Failed']);
                    break;

                case 'live':
                    $serviceMyTask->serviceToken->update(['action' => 'Started']);
                    break;
                default:
                    break;
            }

             // Update date values of $salesOrder->otp_verifications
            if (request()->filled('otp_verifications')) {

                foreach (request()->otp_verifications as $otpJson) {
                    $otpData = json_decode($otpJson, true);

                    $otpData['sourceable_id'] =  $serviceMyTask->id;
                    $otpData['sourceable_type'] = ServiceMyTask::class;

                    OtpVerification::updateOrCreate(
                        ['id' => $otpData['id']??null],
                        $otpData
                    );
                }
            }


            if(!empty($serviceMyTaskShipments['area_id'])) {
                $result['serviceMyTaskShipments'] = $serviceMyTask->shipment()->create([
                    'courier_id' => $serviceMyTaskShipments['courier_id'],
                    'area_id' => $serviceMyTaskShipments['area_id'] == 'address' ? null : $serviceMyTaskShipments['area_id'],
                    'address' => $serviceMyTaskShipments['address'],
                    'contact_person_name' => $serviceMyTaskShipments['contact_person_name'],
                    'contact_person_number' => $serviceMyTaskShipments['contact_person_number'],
                    'condition' => ($serviceMyTaskShipments['condition'] ?? false) ? true : false,
                    'additional_amount' => ($serviceMyTaskShipments['condition'] ?? false) ? $serviceMyTaskShipments['additional_amount'] : null,
                    'condition_remarks' => ($serviceMyTaskShipments['condition'] ?? false) ? $serviceMyTaskShipments['condition_remarks'] : null,
                ]);
            } else {
                $serviceMyTask->shipment()->delete();
            }

            // dd($serviceMyTask->shipment);
            DB::commit();
            return $result;

        } catch (\Exception $e) {
            DB::rollBack();
            dd($e);
            report($e); // log exception
            throw $e;   // or return an error response
        }
    }


    public function update(ServiceMyTask $serviceMyTask, array $data)
    {
        $serviceMyTask->update($data);
        return $serviceMyTask;
    }

    public function delete(ServiceMyTask $serviceMyTask)
    {
        $serviceMyTask->delete();
    }


    private function storeToSalesOrders(ServiceMyTask $serviceMyTask)
    {
        $bills = $serviceMyTask->bill_type == 'service_return_bill' ? $serviceMyTask->returnBills : $serviceMyTask->bills;

        $data = [
            'customer_id' => $serviceMyTask->serviceToken->customer_id,
            'service_id' => $serviceMyTask->serviceToken->service_id,
            'additional_phone' => $serviceMyTask->handover_info_contact_no ?? $serviceMyTask->serviceToken->contact_person_phone,
            'invoice_date' => now()->format('Y-m-d'),
            'total_amount' => $bills->sum(function ($bill) { return $bill->price * $bill->quantity; }),
            'discount' => $bills->sum('total_discount'),
            'commission' => 0,
            'total' => $bills->sum('amount'),
            'vat' => 0,
            'net_amount' => $bills->sum('amount'),
            'remarks' => $serviceMyTask->remarks,
            'status' => 'approved',
            'is_shipment' => $serviceMyTask->shipment ? 1 : 0,
            'is_courier' => $serviceMyTask->shipment ? 1 : 0,
            'delivery_date' => now()->format('Y-m-d'),
            'sales_type' => 'service_sales',
            'reference_id' => null,
            'source_type' => ServiceMyTask::class,
            'source_id' => $serviceMyTask->id,
        ];

        $salesOrderDetails = [
            'product_ids' => [],
            'quantity' => [],
            'price' => [],
            'unit_discount' => [],
            'total_discount' => [],
            'amount' => [],
            'sales_order_detail_id' => [],
        ];

        $existingSalesOrder = SalesOrder::where('source_type', ServiceMyTask::class)
            ->where('source_id', $serviceMyTask->id)
            ->first();

        if ($existingSalesOrder) {
            $existingDetails = $existingSalesOrder->salesOrderDetails->keyBy('product_id');
        } else {
            $existingDetails = collect();
        }

        foreach ($bills as $key => $bill) {
            $salesOrderDetails['product_ids'][$key] = $bill->product_id;
            $salesOrderDetails['quantity'][$key] = $bill->quantity;
            $salesOrderDetails['price'][$key] = $bill->price;
            $salesOrderDetails['unit_discount'][$key] = $bill->unit_discount;
            $salesOrderDetails['total_discount'][$key] = $bill->total_discount;
            $salesOrderDetails['amount'][$key] = $bill->amount;

            $existingDetail = $existingDetails->get($bill->product_id);
            $salesOrderDetails['sales_order_detail_id'][$key] = $existingDetail ? $existingDetail->id : null;
        }

        $salesOrderShipments = [];
        if ($serviceMyTask->shipment) {
            $shipment = $serviceMyTask->shipment;
            $salesOrderShipments = [
                'courier_id' => $shipment->courier_id,
                'area_id' => $shipment->area_id,
                'address' => $shipment->address,
                'contact_person_name' => $shipment->contact_person_name,
                'contact_person_number' => $shipment->contact_person_number,
                'condition' => $shipment->condition,
                'additional_amount' => $shipment->additional_amount,
                'condition_remarks' => $shipment->condition_remarks,
            ];
            $data['is_shipment'] = 1;
        }

        $payments = [];
        if ($serviceMyTask->payments->count() > 0) {
            $payments = [
                'payments_pay_mode' => [],
                'payments_bank_id' => [],
                'payments_branch_id' => [],+
                'payments_transaction_id' => [],
                'payments_emi_id' => [],
                'payments_amount' => [],
                'payments_date' => [],
                'payments_attachments' => [],
                'payments_verified' => [],
                'payments_remark' => [],
            ];
            foreach ($serviceMyTask->payments as $key => $payment) {
                $payments['payments_pay_mode'][$key] = $payment->pay_mode;
                $payments['payments_bank_id'][$key] = $payment->bank_id ?? null;
                $payments['payments_branch_id'][$key] = $payment->branch_id ?? null;
                $payments['payments_transaction_id'][$key] = $payment->transaction_id ?? null;
                $payments['payments_emi_id'][$key] = $payment->e_m_i_entries_id ?? null;
                $payments['payments_amount'][$key] = $payment->amount ?? 0;
                $payments['payments_date'][$key] = $payment->date;
                $payments['payments_attachments'][$key] = $payment->attachments ?? null;
                $payments['payments_verified'][$key] = $payment->verified ?? false;
                $payments['payments_remark'][$key] = $payment->remarks ?? null;
            }
        }

        if ($existingSalesOrder) {
            $salesOrder = $this->salesOrderService->update($existingSalesOrder, $data, $salesOrderDetails, $salesOrderShipments, $payments);
        } else {
            $result = $this->salesOrderService->store($data, $salesOrderDetails, $salesOrderShipments, $payments);
            $salesOrder = $result['salesOrder'];
        }

        return $salesOrder;
    }
    public function show($id)
    {
       $serviceMyTask = ServiceMyTask::where('service_token_id', $id)
            ->with(['serviceToken.customer', 'pendingServiceTokens', 'bills', 'returnBills', 'payments'])
            ->first();
            if( $serviceMyTask){
                return $serviceMyTask;

            }
        return ;
    }
}
