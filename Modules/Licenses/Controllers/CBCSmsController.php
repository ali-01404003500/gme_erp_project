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
        $serviceName = ServiceName::where('code', 'cbc_sms')->where('status', 1)->first();
        $triggerName = TriggerName::where('code', 'T02')->where('status', 1)->first();
        $sms = SmsTemplate::where('service_name_id', $serviceName->id)->where('trigger_name_id', $triggerName->id)->first();

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

        $template = $sms->template_body;

        $placeholders = array_map(fn($key) => '$' . $key, array_keys($validate));
 
        $values = [];
        foreach ($validate as $key => $value) {
            switch ($key) {
                case 'customer_id':
                    $customer = Customer::find($value);
                    $values[] = $customer ? $customer->company_name : 'N/A';
                    break;
                case 'dongle_id':
                    $dongle = DongleOrSerialEntry::find($value);
                    $values[] = $dongle ? $dongle->dongle_id : 'N/A';
                    break;
                case 'valid_period_type':
                    $values[] = ucfirst($value);
                    break;
                default:
                    $values[] = $value;
            }
        }

        $processedTemplate = str_replace($placeholders, $values, $template);


        // Assign the processed template to the 'sms' key in the validate array
        $validate['sms'] = $processedTemplate;

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
