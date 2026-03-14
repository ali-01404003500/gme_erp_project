<?php

namespace Modules\Services\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\AccessControl\CompanyInfo;
use Carbon\Carbon;
use Modules\HRMS\Models\Employee;
use Modules\Inventory\Models\Product;
use Modules\Inventory\Models\ProductCatalog;
use Modules\Licenses\Models\DongleOrSerialEntry;
use Modules\Sales\Models\SalesOrder;
use Modules\Services\Models\EmergencyNote;
use Modules\Services\Models\Service;
use Modules\Services\Models\ServiceToken;
use Modules\Services\Models\Settings\ProblemType;
use Modules\Services\Models\Settings\ServiceType;
use Modules\Services\Services\ServiceService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Dompdf\Dompdf;
use Dompdf\Options;
use Modules\CRM\Models\Customer\Customer;

class ServiceController extends Controller
{
    /**
     * Service variable
     *
     * @var ServiceService
     */
    private $service;
    function __construct(ServiceService $service)
    {
        $this->service = $service;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        try {
            $data['service'] = $this->service->getAll();

            return response()->json([
                'data' => $data,
                'status' => true,
                'message' => 'Data fetched successfully',
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()]);
        }
    }

    public function storeDongle(Request $request)
    {
        $data['dongleOrSerialEntry'] = DongleOrSerialEntry::create([
            'dongle_id' => $request->dongle_id,
            'product_id' => $request->product_id,
            'product_type' => $request->product_type,
            'customer_id' => $request->customer_id,
            'address' => $request->address,
            'software_version' => $request->software_version,
            'status' => $request->status,
        ]);
        return response()->json([
            'status' => true,
            'message' => 'DongleOrSerialEntry created successfully.',
            'dongleOrSerialEntry' => $data['dongleOrSerialEntry']
        ]);
    }
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $data['serviceTypes'] = ServiceType::all();
        $data['problemTypes'] = ProblemType::all();
        $data['employees'] = Employee::all();
        $data['customers'] = Customer::activeCustomers()->get();
        $data['productCatalogs'] =ProductCatalog::select('name', 'id', 'model', 'product_brand_id')->with('brand:name')->get();
        $data['salesOrders'] = SalesOrder::where('status', 'delivered')
            ->whereHas('salesOrderDetails', function ($query) {
                $query->whereHas('product', function ($query) {
                    $query->where('is_serial', 'yes');
                });
            })
            ->get();
        $data['dongleOrSerialEntries'] = DongleOrSerialEntry::all();
        return view('Services::service.create', $data);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'status' => 'required|string|max:50',
            'service_date' => 'required|date',
            'service_tokens' => 'required|array|min:1',
            'service_tokens.*.customer_id' => 'required|exists:customers,id',
            'service_tokens.*.contact_person_phone' => 'nullable|string|max:20',
            'service_tokens.*.token_date' => 'nullable|date',
            'service_tokens.*.invoice_id' => 'nullable|string|max:255',
            'service_tokens.*.invoice_date' => 'nullable|date',
            'service_tokens.*.expire_date' => 'nullable|date',
            'service_tokens.*.product_id' => 'required|string|max:255',
            'service_tokens.*.serial_number' => 'nullable|string|max:255',
            'service_tokens.*.service_type' => 'nullable|string|max:50',
            'service_tokens.*.problem_details' => 'nullable|string',
            'service_tokens.*.problem_type' => 'nullable|string|max:50',
            'service_tokens.*.work_type' => 'nullable|string|max:50',
            'service_tokens.*.quantity' => 'required|numeric|min:1',
            'service_tokens.*.internal_video_link' => 'nullable|string',
            'service_tokens.*.external_video_link' => 'nullable|string',
            'service_tokens.*.documents' => 'nullable|array',
        ]);

        // Separate core service data
        $serviceData = [
            'status' => $validatedData['status'],
            'service_date' => $validatedData['service_date'],
            'service_unique_id' => $this->getServiceId(),
        ];

        // Build structured details for service tokens
        $serviceTokenDetails = [];
        $customerIds = [];
        $contactPersonPhones = [];
        $tokenDates = [];
        $invoiceIds = [];
        $invoiceDates = [];
        $expireDates = [];
        $productIds = [];
        $serialNumbers = [];
        $serviceTypes = [];
        $problemDetails = [];
        $problemTypes = [];
        $workTypes = [];
        $quantities = [];
        $internalVideoLinks = [];
        $externalVideoLinks = [];
        $documents = [];
        $actions = [];

        foreach ($validatedData['service_tokens'] as $token) {
            $customerIds[] = $token['customer_id'];
            $contactPersonPhones[] = $token['contact_person_phone'] ?? null;
            $tokenDates[] = $token['token_date'] ?? null;
            $invoiceIds[] = $token['invoice_id'] ?? null;
            $invoiceDates[] = $token['invoice_date'] ?? null;
            $expireDates[] = $token['expire_date'] ?? null;
            $productIds[] = $token['product_id'];
            $serialNumbers[] = $token['serial_number'] ?? null;
            $serviceTypes[] = $token['service_type'] ?? null;
            $problemDetails[] = $token['problem_details'] ?? null;
            $problemTypes[] = $token['problem_type'] ?? null;
            $workTypes[] = $token['work_type'] ?? null;
            $quantities[] = $token['quantity'];
            $internalVideoLinks[] = $token['internal_video_link'] ?? null;
            $externalVideoLinks[] = $token['external_video_link'] ?? null;
            $documents[] = json_encode($token['documents']) ?? null;
            $actions[] = 'Pending';
        }

        $serviceTokenDetails = [
            'customer_id' => $customerIds,
            'contact_person_phone' => $contactPersonPhones,
            'token_date' => $tokenDates,
            'invoice_id' => $invoiceIds,
            'invoice_date' => $invoiceDates,
            'expire_date' => $expireDates,
            'product_id' => $productIds,
            'serial_number' => $serialNumbers,
            'service_type' => $serviceTypes,
            'problem_details' => $problemDetails,
            'problem_type' => $problemTypes,
            'work_type' => $workTypes,
            'quantity' => $quantities,
            'internal_video_link' => $internalVideoLinks,
            'external_video_link' => $externalVideoLinks,
            'documents' => $documents,
            'action' => $actions,
        ];

        // dd($serviceData, $serviceTokenDetails);

        // Store using service layer
        $result = $this->service->store($serviceData, $serviceTokenDetails);

        return response()->json(
            [
                'status' => 'success',
                'message' => 'Service created successfully.',
                'data' => $result['service'], // return service with tokens if needed
            ],
            201,
        );
    }

    public function updateAction(Request $request, $id)
    {
        // dd($request->all(), $id);
        $token = ServiceToken::find($id);
        $validate = $request->validate([
            'note' => 'required|string',
            'action' => 'required|in:Pending,Failed,Junk',
        ]);

        $token->update([
            'action' => $validate['action'],
        ]);
        $token->service->update([
            'action' => $validate['action'],
        ]);

        EmergencyNote::create([
            'service_token_id' => $id,
            'service_id' => $token->service_id,
            'note' => $validate['note'],
        ]);
        return redirect()->route('services.service-assign.index')->with('success', 'Service action updated successfully.');
    }

    // public function getStatus(Request $request)
    // {
    //     $serviceId = $request->serviceId;
    //     $isAssigned = $request->is_assigned;
    //     $status = '';

    //     if ($isAssigned == 0) {
    //         $status = 'entry';
    //     } else if ($isAssigned == 1) {
    //         $status = 'pending';
    //         if ($request->button == 'quit') {
    //             $status = 'failed';
    //         }
    //     }

    //     \App\Models\Services\Service::where('id', $serviceId)->update(['status' => $status]);

    //     return redirect()->route('services.service.index')->with('success', 'Service status updated successfully.');
    // }

    public function getServiceId()
    {
        // Get the current date in the format yymmdd
        $datePart = now()->format('ymd');

        // Count the number of services created on the current day
        $count = Service::whereDate('created_at', now()->toDateString())->count() + 1;

        // Format the count to be a 3-digit number
        $countPart = str_pad($count, 3, '0', STR_PAD_LEFT);

        // Combine the parts to form the service ID
        $serviceId = "ST-{$datePart}-{$countPart}";

        return $serviceId;
    }

    /**
     * Display the specified resource.
     */
    public function show($id, Request $request)
    {
        $data['company_info'] = CompanyInfo::first();
        $data['service'] = $this->service->show($id);
        $result['service'] = $data['service'];
        return response()->json(
            [
                'status' => 'success',
                'message' => 'Service found successfully.',
                'data' => $result['service'], 
            ],
            200
        );
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Service $service)
    {
        $data['employees'] = Employee::all();
        $data['serviceTypes'] = ServiceType::all();
        $data['problemTypes'] = ProblemType::all();
        $data['service'] = $service;
        $data['customers'] = Customer::activeCustomers()->get();
        $data['productCatalogs'] =ProductCatalog::select('name', 'id', 'model', 'product_brand_id')->with('brand:name')->get();
        $data['salesOrders'] = SalesOrder::all();
        $data['dongleOrSerialEntries'] = DongleOrSerialEntry::all();
        //
        return view('Services::service.edit', $data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Service $service)
    {
        $validate = $request->validate([
            'is_assigned' => 'nullable|boolean',
            'status' => 'nullable|string|max:50',
            'assigned_engineer_id' => 'nullable|exists:employees,id',
            'service_date' => 'nullable|date',
            'service_priority' => 'nullable|string|max:50',
            'remarks' => 'nullable|string',
        ]);

        $serviceTokens = $request->validate([
            'customer_id.*' => 'nullable|exists:customers,id',
            'contact_person_phone.*' => 'nullable|string|max:20',
            'token_date.*' => 'nullable|date',
            'invoice_id*' => 'nullable|string|max:255',
            'invoice_date.*' => 'nullable|date',
            'expire_date.*' => 'nullable|date',
            'product_id.*' => 'nullable|string|max:255',
            'serial_number.*' => 'nullable|string|max:255',
            'service_type.*' => 'nullable|string|max:50',
            'problem_details.*' => 'nullable|string',
            'problem_type.*' => 'nullable|string|max:50',
            'work_type.*' => 'nullable|string|max:50',
            'quantity.*' => 'nullable|numeric',
            'internal_video_link.*' => 'nullable',
            'external_video_link.*' => 'nullable',
            'documents.*' => 'nullable', // Add validation for documents
        ]);

        $this->service->update($service, $validate, $serviceTokens);

        return redirect()->route('services.service.edit', $service->id)->withInput($request->all())->with('success', 'Service updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Service $service)
    {
        $this->service->delete($service);
        return redirect()->route('services.service.index')->with('success', 'Service deleted successfully.');
    }

    public function getInvoices(Request $request)
    {
        $data = $this->service->getInvoices($request->customer_id);
        return response()->json($data);
    }

    public function getInvoiceBySerial(Request $request)
    {
        $invoice = SalesOrder::where('customer_id', $request->customer_id)
            ->where('status', 'delivered')
            ->whereHas('delivery', function ($query) use ($request) {
                $query->whereHas('deliveryDetails', function ($query) use ($request) {
                    $query->whereHas('deliveryStocks', function ($query) use ($request) {
                        $query->where('product_catalog_id', $request->product_id)->where('serial_no', $request->serial_number);
                    });
                });
            })
            ->with(['customer']) // Optional
            ->first();

        if ($invoice) {
            $invoiceDate = Carbon::parse($invoice->invoice_date);

            // Get product warranty info
            $product = ProductCatalog::find($request->product_id);
            $warrantyUnit = $product->warranty_period; // 'year', 'month', 'day'
            $warrantyValue = (int) $product->warranty_period_input;

            // Calculate expiry date
            $expireDate = clone $invoiceDate;
            if ($warrantyUnit === 'year') {
                $expireDate->addYears($warrantyValue);
            } elseif ($warrantyUnit === 'month') {
                $expireDate->addMonths($warrantyValue);
            } elseif ($warrantyUnit === 'day') {
                $expireDate->addDays($warrantyValue);
            }

            return response()->json([
                'sales_order_id' => $invoice->id,
                'sales_order_code' => $invoice->sales_order_id,
                'invoice_date' => $invoiceDate->toDateString(),
                'expire_date' => $expireDate->toDateString(), // ✅ Include expiry date in response
            ]);
        }

        return response()->json(['message' => 'Invoice not found'], 404);
    }

    public function getProducts(Request $request)
    {
        $data = $this->service->getProducts($request->invoice_id);
        return response()->json($data);
    }

    public function getSerialIds(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:product_catalogs,id',
            'customer_id' => 'required|exists:customers,id', // Assuming 'customers' table exists
        ]);

        $productId = $request->input('product_id');
        $customerId = $request->input('customer_id');

        $data = $this->service->getSerialIds($productId, $customerId);
        return response()->json([
            'data' => $data,
            'status' => true,
            'message' => 'Data fetched successfully',
        ]);
    }

    public function getQuantity(Request $request)
    {
        $data = $this->service->getQuantity($request->sales_order_id, $request->product_id);
        return response()->json($data);
    }
}
