<?php

namespace Modules\Licenses\Services;

use App\Services\SmsService;
use Modules\Licenses\Models\USGOrOPGLicenseRequisition;
use Modules\Licenses\Models\UsgOrOpgSms;
use Carbon\Carbon;

class USGOrOPGLicenseSmsService
{
    private $smsService;
    function __construct( SmsService $smsService)
    {
        $this->smsService = $smsService;
    }
    public function getAll(int $limit = 20) {
        return USGOrOPGSms::query()
        ->searchByFields(['customer_id'])
        ->when(request()->filled('from'), function ($qr) {
            $from = Carbon::parse(request('from'))->format('Y-m-d');
            $qr->whereRaw("DATE(created_at) >= ?", [$from]);
        })
        ->when(request()->filled('to'), function ($qr) {
            $to = Carbon::parse(request('to'))->format('Y-m-d');
            $qr->whereRaw("DATE(created_at) <= ?", [$to]);
        })
        ->paginate($limit);
    
    }
    
    public function store(array $data, array $phones)
    {
        
        $requisition = UsgOrOpgSms::create($data);
        
        $phones = collect($phones['multiple_phone_nos'])->map(function ($phone) use ($requisition) {
            return $requisition->phones()->create(['multiple_phone_no' => $phone]);
        });

        if($data['status'] == 'Send') {
            $usgOrOpgLicenseRequisition  = USGOrOPGLicenseRequisition::find($data['u_s_g_or_o_p_g_license_requisition_id']);
            $usgOrOpgLicenseRequisition->update([
                'status' => 'SMS Send',
                'software_version' => $data['software_version'],
            ]);
            $usgOrOpgLicenseRequisition->dongles()->update([
                'software_version' => $data['software_version'],
            ]);
            $this->smsService->sendBulk($usgOrOpgLicenseRequisition->phones()->pluck('multiple_phone_no')->toArray(), $data['sms']);

        }
        else{
            $usgOrOpgLicenseRequisition  = USGOrOPGLicenseRequisition::find($data['u_s_g_or_o_p_g_license_requisition_id']);
            $usgOrOpgLicenseRequisition->update([
                'status' => 'SMS Deny',
                'software_version' => $data['software_version'],
            ]);
            $usgOrOpgLicenseRequisition->dongles()->update([
                'software_version' => $data['software_version'],
            ]);
        }
        
                
        return $requisition;
    }

    public function storeWithoutSending(array $data, array $phones)
    {
        // Create SMS record with the provided data
        $requisition = UsgOrOpgSms::create($data);
        
        // Create phone records
        $phones = collect($phones['multiple_phone_nos'])->map(function ($phone) use ($requisition) {
            return $requisition->phones()->create(['multiple_phone_no' => $phone]);
        });

        // Get the license requisition
        $usgOrOpgLicenseRequisition = USGOrOPGLicenseRequisition::find($data['u_s_g_or_o_p_g_license_requisition_id']);

        // Update license requisition status (SMS stored but not sent)
        $usgOrOpgLicenseRequisition->update([
            'status' => 'SMS Send',
            'software_version' => $data['software_version'] ?? $usgOrOpgLicenseRequisition->software_version,
        ]);
        
        // Update dongle software version if provided
        if (!empty($data['software_version'])) {
            $usgOrOpgLicenseRequisition->dongles()->update([
                'software_version' => $data['software_version'],
            ]);
        }
        
        return $requisition;
    }


    
}
