<?php

namespace Modules\Licenses\Controllers;

use App\Http\Controllers\Controller;
use App\Models\AccessControl\ServiceName;
use App\Models\AccessControl\SmsTemplate;
use App\Models\AccessControl\TriggerName;
use Modules\Licenses\Models\DongleOrSerialEntry;
use Modules\Licenses\Models\USGOrOPGLicenseRequisition;
use Modules\Licenses\Models\UsgOrOpgSms;
use Modules\Licenses\Services\USGOrOPGLicenseSmsService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Modules\CRM\Models\Customer\Customer;

class USGOrOPGSmsController extends Controller
{
    /**
     * Service variable
     *
     * @var USGOrOPGLicenseSmsService
     */
    private $service;

    function __construct(USGOrOPGLicenseSmsService $service)
    {
        $this->service = $service;
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $serviceName = ServiceName::where('code', 'usg_or_opg_sms')->where('status', 1)->first();
        $triggerName = TriggerName::where('code', 'T01')->where('status', 1)->first();
        $sms = SmsTemplate::where('service_name_id', $serviceName->id)->where('trigger_name_id', $triggerName->id)->first();

        $validatedData = $request->validate([
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
            'u_s_g_or_o_p_g_license_requisition_id' => 'required|integer|exists:u_s_g_or_o_p_g_license_requisitions,id',
            'status' => 'required|in:Send,Deny',
            'software_version' => 'nullable',
        ]);

        $template = $sms->template_body ?? '';

        // Build replacements
        $replacements = [];
        // dd($validatedData);
        foreach ($validatedData as $key => $value) {
            switch ($key) {
                case 'customer_id':
                    $customer = Customer::find($value);
                    $replacements['$' . $key] = $customer ? $customer->company_name : 'N/A';
                    break;

                case 'dongle_id':
                    $dongle = DongleOrSerialEntry::find($value);
                    $replacements['$' . $key] = $dongle ? $dongle->dongle_id : 'N/A';
                    break;

                case 'valid_period_type':
                    // combine with valid_period and handle plural
                    $periodCount = isset($validatedData['valid_period']) ? (int) $validatedData['valid_period'] : null;
                    $unit = $value; // days / months / years

                    if ($periodCount === 1) {
                        $unit = rtrim($unit, 's'); // singular (Month, Day, Year)
                    }

                    $combined = trim(($periodCount !== null ? $periodCount : '') . ' ' . ucfirst($unit));
                    $replacements['$' . $key] = $combined;

                    // prevent $valid_period from showing separately
                    $replacements['$valid_period'] = '';
                    break;

                case 'valid_period':
                    if (!isset($replacements['$valid_period'])) {
                        $replacements['$' . $key] = (string) $value;
                    }
                    break;

                default:
                    $replacements['$' . $key] = (string) $value;
            }
        }

        // Safe replacement
        $processedTemplate = strtr($template, $replacements);

        $validatedData['sms'] = $processedTemplate;

        $phone = $request->validate([
            'multiple_phone_nos' => 'nullable|array',
            'multiple_phone_nos.*' => 'nullable|string|max:20',
        ]);

        $result = $this->service->store($validatedData, $phone);

        return redirect()->route('licenses.usg-opg-license-requisitions.index')->with('success', 'USG/OPG License Requisition created successfully.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $uSGOrOPGLicenseRequisition = USGOrOPGLicenseRequisition::with(['customer'])->findOrFail($id);
        $data['license'] = $uSGOrOPGLicenseRequisition;
        $data['customers'] = Customer::activeCustomers()->get();
        $data['notes'] = USGOrOPGLicenseRequisition::where('customer_id', $uSGOrOPGLicenseRequisition->customer_id)->where('dongle_id', $uSGOrOPGLicenseRequisition->dongle_id)->latest()->take(5)->get();
        $data['sms'] = UsgOrOpgSms::where('customer_id', $uSGOrOPGLicenseRequisition->customer_id)->where('dongle_id', $uSGOrOPGLicenseRequisition->dongle_id)->latest()->first();
        return view('Licenses::usg-opg-license-requisition.sms', $data);
    }
}
