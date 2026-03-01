<?php

namespace Modules\Services\Controllers;


use App\Http\Controllers\Controller;
use App\Services\SmsService;
use Modules\Inventory\Models\ProductCatalog;
use Modules\Services\Models\Service;
use Modules\Services\Models\ServiceBill;
use Modules\Services\Models\ServiceToken;
use Modules\Services\Services\ServiceBillService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;
use Modules\Account\Models\Setup\Bank;
use Modules\CRM\Models\Customer\Customer;
use Modules\Services\Models\ServiceMyTask;

class ServiceBillController extends Controller
{

    /**
     * Service variable
     *
     * @var ServiceBillService
     */
    private $service;
    private $smsService;
    function __construct(ServiceBillService $service, SmsService $smsService)
    {
        $this->service = $service;
        $this->smsService = $smsService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data['serviceBills'] = $this->service->getAll();

        return view("serviceBills.index", $data);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $data['service'] = Service::first();
        $data['serviceToken'] = ServiceToken::find($request->token_id);
        if ($data['serviceToken']->service_type == 'ON SPOT') {
            $data['product'] = ProductCatalog::where('name', 'Service Charge With (TA) (DA)')->first();
        } else if ($data['serviceToken']->service_type == 'ON CALL') {
            $data['product'] = ProductCatalog::where('name', 'Service Charge On Call')->first();
        } else {
            $data['product'] = ProductCatalog::where('name', 'Service Charge (IN HOUSE)')->first();

        }

        $data['return_product'] = ProductCatalog::where('name', 'Checking Charge')->first();
        $data['customers'] = Customer::activeCustomers()->get();

        $data['serviceMyTasks'] = $this->service->getAll();
        $data['products'] = ProductCatalog::all();
        $data['productCatalogs'] = ProductCatalog::all();
        // $data['BillingProducts'] = ProductCatalog::whereIn('name', ['Service Charge (IN HOUSE)', 'Service Charge With (TA) (DA)', 'Service Charge On Call'])->get();
        $data['banks'] = Bank::all();
        $data['pendingServiceTokens'] = ServiceToken::with("engineerAssign")->whereIn('action', ['Pending', 'Live'])->where('customer_id', $data['serviceToken']->customer_id)->get();
        // dd($data['pendingServiceTokens']);

        $data['serviceMyTask'] = ServiceMyTask::where('service_token_id', $request->token_id)->first();

        // Add areas and couriers for shipment information
        $data['areas'] = \Modules\LocationManager\Models\Area::all();
        $data['couriers'] = \Modules\Sales\Models\Courier::all();

        // dd(ServiceMyTask::with(['payments', 'shipment'])->where('service_token_id', $request->token_id)->first());

        return view('Services::service-my-task.bill', $data);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        $validate = $request->validate([
            //validate rules
        ]);
        $this->service->store($validate);
        return redirect()->route('serviceBills.index')->with('success', 'ServiceBill created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $data['serviceBill'] = $this->service->show($id);

        return view("serviceBills.show", $data);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ServiceBill $serviceBill)
    {
        $data['serviceBill'] = $serviceBill;
        //
        return view("serviceBills.edit", $data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ServiceBill $serviceBill)
    {
        $validate = $request->validate([
            //validate rules
        ]);
        $this->service->update($serviceBill, $validate);

        return redirect()->route('serviceBills.index')->with('success', 'ServiceBill updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ServiceBill $serviceBill)
    {
        $this->service->delete($serviceBill);
        return redirect()->route('serviceBills.index')->with('success', 'ServiceBill deleted successfully.');
    }

    /**
     * Send OTP to the customer's phone for service bill verification.
     */
    public function sendOtp(Request $request)
    {
        $request->validate([
            'customer_id' => 'required|exists:customers,id',
        ]);

        $customer = Customer::findOrFail($request->customer_id);

        if (!$customer->phone) {
            return response()->json([
                'success' => false,
                'message' => 'Customer phone number is not available.',
            ]);
        }

        // Generate a 6-digit OTP
        $otp = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);

        // Store OTP in cache with a 5-minute expiration (300 seconds)
        Cache::put('service_bill_otp_' . $customer->id, $otp, 300);

        // Prepare customer phone number for SMS service
        $customerPhone = $customer->phone;
        if (substr($customerPhone, 0, 2) === '01') {
            $customerPhone = '88' . $customerPhone;
        }

        $message = "Your service is successfully completed. If you are satisfied with the service, please share your pin code. Pin Code is {$otp}.";

        try {
            $sent = $this->smsService->send($customerPhone, $message);

            if ($sent) {
                return response()->json([
                    'success' => true,
                    'message' => 'OTP sent to customer successfully.',
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to send OTP. Please try again.',
                ]);
            }
        } catch (\Exception $e) {
            Log::error("Error sending OTP for Service Bill to Customer ID {$customer->id}: " . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while sending the OTP.',
            ], 500);
        }
    }

    /**
     * Verify the OTP for service bill confirmation.
     */
    public function verifyOtp(Request $request)
    {
        $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'otp' => 'required|string|size:6',
        ]);

        $customerId = $request->customer_id;
        $cachedOtp = Cache::get('service_bill_otp_' . $customerId);

        if (!$cachedOtp) {
            return response()->json([
                'success' => false,
                'message' => 'OTP has expired or is invalid. Please request a new one.',
            ]);
        }

        if ($cachedOtp !== $request->otp) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid OTP. Please try again.',
            ]);
        }

        // OTP is correct, clear it from the cache
        Cache::forget('service_bill_otp_' . $customerId);

        return response()->json([
            'success' => true,
            'message' => 'OTP verified successfully.',
        ]);
    }
}
