<?php

namespace Modules\Services\Services;

use App\Models\OtpVerification;
use Illuminate\Support\Facades\DB;
use Modules\Services\Models\Service;
use Modules\Services\Models\ServiceMyTask;

class ServiceMyTaskService
{
    
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
