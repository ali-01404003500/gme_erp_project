<?php

namespace Modules\Licenses\Services;

use App\Services\SmsService;
use Modules\Licenses\Models\CBCLicenseRequisition;
use Modules\Licenses\Models\CbcSms;
use Carbon\Carbon;

class CBCLicenseSmsService
{
    private $smsService;
    
    function __construct(SmsService $smsService)
    {
        $this->smsService = $smsService;
    }
    
    public function getAll(int $limit = 20)
    {
        return CbcSms::query()
            ->searchByFields(['customer_id'])
            ->when(request()->filled('from'), function ($qr) {
                $from = Carbon::parse(request('from'))->format('Y-m-d');
                $qr->whereRaw('DATE(created_at) >= ?', [$from]);
            })
            ->when(request()->filled('to'), function ($qr) {
                $to = Carbon::parse(request('to'))->format('Y-m-d');
                $qr->whereRaw('DATE(created_at) <= ?', [$to]);
            })
            ->paginate($limit);
    }

    public function store(array $data, array $phones)
    {
        // Create SMS record
        $requisition = CbcSms::create($data);
 
        // Create phone records
        $phones = collect($phones['multiple_phone_nos'])->map(function ($phone) use ($requisition) {
            return $requisition->phones()->create(['multiple_phone_no' => $phone]);
        });
        

        // Get the license requisition
        $cbcLicenseRequisition = CBCLicenseRequisition::find($data['c_b_c_license_requisition_id']);

        $cbcLicenseRequisition->phones()->delete();
        
        foreach ($phones['multiple_phone_nos'] as $phone) {
            $cbcLicenseRequisition->phones()->create([
                'multiple_phone_no' => $phone,
            ]);
        }


        if ($data['status'] == 'Send') {
            // Update license requisition status
            $cbcLicenseRequisition->update([
                'status' => 'SMS Send',
                'software_version' => $data['software_version'],
            ]);
            
            // Update dongle software version
            $cbcLicenseRequisition->dongles()->update([
                'software_version' => $data['software_version'],
            ]);
            
            // Send SMS to all phone numbers
            $this->smsService->sendBulk(
                $cbcLicenseRequisition->phones()->pluck('multiple_phone_no')->toArray(), 
                $data['sms']
            );
        } else {
            // Status is 'Deny'
            $cbcLicenseRequisition->update([
                'status' => 'SMS Deny',
                'software_version' => $data['software_version'],
            ]);
            
            $cbcLicenseRequisition->dongles()->update([
                'software_version' => $data['software_version'],
            ]);
        }
        
        return $requisition;
    }
    
    /**
     * Store SMS record without sending (for JSON import)
     */
    public function storeWithoutSending(array $data, array $phones)
    {
        // Create SMS record with the provided data
        $requisition = CbcSms::create($data);
        
        // Create phone records
        $phones = collect($phones['multiple_phone_nos'])->map(function ($phone) use ($requisition) {
            return $requisition->phones()->create(['multiple_phone_no' => $phone]);
        });

        // Get the license requisition
        $cbcLicenseRequisition = CBCLicenseRequisition::find($data['c_b_c_license_requisition_id']);

        // Update license requisition status (SMS stored but not sent)
        $cbcLicenseRequisition->update([
            'status' => 'SMS Send',
            'software_version' => $data['software_version'] ?? $cbcLicenseRequisition->software_version,
        ]);
        
        // Update dongle software version if provided
        if (!empty($data['software_version'])) {
            $cbcLicenseRequisition->dongles()->update([
                'software_version' => $data['software_version'],
            ]);
        }
        
        return $requisition;
    }
}