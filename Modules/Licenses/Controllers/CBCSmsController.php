<?php

namespace Modules\Licenses\Controllers;

use App\Http\Controllers\Controller;
use App\Models\AccessControl\ServiceName;
use App\Models\AccessControl\SmsTemplate;
use App\Models\AccessControl\TriggerName;
use Modules\Licenses\Models\CBCLicenseRequisition;
use Modules\Licenses\Models\CbcSms;
use Modules\Licenses\Models\DongleOrSerialEntry;
use Modules\Licenses\Models\USGOrOPGLicenseRequisition;
use Modules\Licenses\Models\UsgOrOpgSms;
use Modules\Licenses\Services\CBCLicenseSmsService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Modules\CRM\Models\Customer\Customer;

class CBCSmsController extends Controller
{
    /**
     * Service variable
     *
     * @var CBCLicenseSmsService
     */
    private $service;

    function __construct(CBCLicenseSmsService $service)
    {
        $this->service = $service;
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {   
        $validate = $request->validate([
            'customer_id' => 'required|integer|exists:customers,id',
            'address' => 'nullable',
            'phone' => 'nullable|string|max:20',
            'dongle_id' => 'required|integer|exists:dongle_or_serial_entries,id',
            'product_model' => 'required',
            'start_date' => 'required|date',
            'valid_period' => 'required|integer|min:1',
            'valid_period_type' => 'required|in:days,months,years',
            'expired_date' => 'required|date|after_or_equal:start_date',
            'remarks' => 'nullable',
            'license_key' => 'nullable',
            'license_id' => 'nullable',
            'c_b_c_license_requisition_id' => 'required|integer|exists:c_b_c_license_requisitions,id',
            'status' => 'required|in:Send,Deny',
            'software_version' => 'nullable',
        ]);

        $serviceName = ServiceName::where('code', 'S01')->where('status', 1)->first(); 
        if ( ($request->valid_period == 1 && strtolower($request->valid_period_type) === 'years') || ($request->valid_period == 365 && strtolower($request->valid_period_type) === 'days') || strtolower($request->valid_period_type) === 'unlimited'  ) 
        {
            $triggerName = TriggerName::where('code', 'T27')->where('status', 1)->first();

            $sms = SmsTemplate::where('code_name', 'TEM027')->first();
            $smsTemplate = $sms->template_body;

            $customerInfo = Customer::where('id', $request->customer_id)->first(); 

            $customerName = $customerInfo->company_name;
            $productName = $request->product_model;
            $dongleId = $request->dongle_id;
            $licenseKey = $request->license_key;

            $smsdata = [
                'customerName' =>  $customerName,
                'productName' => $productName,
                'dongleId' =>  $dongleId,
                'licenseKey' => $licenseKey
            ];  

            foreach ($smsdata as $key => $value) {
                $smsTemplate = str_replace('$' . $key, $value, $smsTemplate);
            } 
    
            
        } 
        else 
        {
            $triggerName = TriggerName::where('code', 'T01')->where('status', 1)->first();
            $sms = SmsTemplate::where('code_name', 'TEM026')->first(); 
            $smsTemplate = $sms->template_body;

            $customerInfo = Customer::where('id', $request->customer_id)->first(); 

            $customerName = $customerInfo->company_name;
            $productName = $request->product_model;
            $dongleId = $request->dongle_id;
            $active_and_expired_info = 'Activation: ' . $request->valid_period . ' ' . ucfirst($request->valid_period_type) . '. Valid Until: ' . date('d-M-Y', strtotime($request->expired_date)) . '.';
            $licenseKey = $request->license_key;

            $smsdata = [
                'customerName' =>  $customerName,
                'productName' => $productName,
                'dongleId' =>  $dongleId,
                'active_and_expired_info' => $active_and_expired_info,
                'licenseKey' => $licenseKey
            ];  

            foreach ($smsdata as $key => $value) {
                $smsTemplate = str_replace('$' . $key, $value, $smsTemplate);
            } 
        }
   
        // Assign the processed template to the 'sms' key in the validate array
        $validate['sms'] = $smsTemplate;

        $phone = $request->validate([
            'multiple_phone_nos' => 'nullable|array',
            'multiple_phone_nos.*' => 'nullable|string|max:20',
        ]);
        $result = $this->service->store($validate, $phone);
        return redirect()->route('licenses.cbc-license-requisitions.index')->with('success', 'SMS Send successfully.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $uSGOrOPGLicenseRequisition = CBCLicenseRequisition::with(['customer'])->findOrFail($id);
        $data['license'] = $uSGOrOPGLicenseRequisition;
        $data['customers'] = Customer::activeCustomers()->get();
        $data['notes'] = CBCLicenseRequisition::where('customer_id', $uSGOrOPGLicenseRequisition->customer_id)->where('dongle_id', $uSGOrOPGLicenseRequisition->dongle_id)->latest()->take(5)->get();
        $data['sms'] = CbcSms::where('customer_id', $uSGOrOPGLicenseRequisition->customer_id)->where('dongle_id', $uSGOrOPGLicenseRequisition->dongle_id)->latest()->first();

        return view('Licenses::cbc-license-requisition.sms', $data);
    }
}
