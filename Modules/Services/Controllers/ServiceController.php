<?php

namespace Modules\Services\Controllers;


use App\Http\Controllers\Controller;
use App\Models\AccessControl\CompanyInfo;
use Carbon\Carbon;
use Dompdf\Dompdf;
use Dompdf\Options;
use Modules\HRMS\Models\Employee;
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
use Mpdf\Mpdf;
use Mpdf\MpdfException;
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
        $data ['serviceTypes'] = ServiceType::all();
        $data['service'] = $this->service->getAll();
        $data['company_info'] = CompanyInfo::first();

        if ($request->export == "pdf") {
            set_time_limit(1000);
            $html = view('Services::service.indexView', $data)->render();

            // Set Dompdf options
            $options = new Options();
            $options->setIsHtml5ParserEnabled(true);
            $options->setIsRemoteEnabled(true);
            
            $dompdf = new Dompdf($options);
            $dompdf->loadHtml($html);
            $dompdf->setPaper('A4', 'portrait');
            $dompdf->render();

            return $dompdf->stream('services_list_' . date('Y-m-d') . '.pdf', ['Attachment' => false]);
        }


        return view("Services::service.index", $data);
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
        $data['productCatalogs'] = ProductCatalog::all();
        $data['salesOrders'] = SalesOrder::where('status', 'delivered')
        ->whereHas('salesOrderDetails', function($query) {
            $query->whereHas('product', function($query) {
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



        $this->service->store($validate, $serviceTokens);
        return redirect()->route('services.service.index')->with('success', 'Service created successfully.');
    }

    
    public function updateAction(Request $request, $id)
    {
        // dd($request->all(), $id);
        $token = ServiceToken::find($id);
        $validate = $request->validate([
            'note'=> 'required|string',
            'action' => 'required|in:Pending,Failed,Junk',
        ]);

        $token->update([
            'action' => $validate['action']
        ]);
        $token->service->update([
            'action' => $validate['action']
        ]);

        EmergencyNote::create([
            'service_token_id' => $id,
            'service_id'=> $token->service_id,
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

   


    /**
     * Display the specified resource.
     */
 public function show($id, Request $request)
{
    $data['company_info'] = CompanyInfo::first();
    $data['service'] = $this->service->show($id);

        $tempPath = storage_path('app/mpdf-temp');
    if ($request->export == "pdf") {
        if (!file_exists($tempPath)) {
            mkdir($tempPath, 0755, true);
        }
        // Create mPDF instance with Bangla font support and image handling
        $mpdf = new Mpdf([
            'mode' => 'utf-8',
            'format' => 'A4',
            'orientation' => 'P',
            'tempDir' => $tempPath,
            'fontDir' => [public_path('fonts/')],
            'fontdata' => [
                'solaimanlipi' => [
                    'R' => 'solaimanlipi.ttf',
                    'useOTL' => 0xFF,
                    'useKashida' => 75,
                ]
            ],
            'imageQuality' => 100,
            'default_font' => 'solaimanlipi',
            'allow_output_buffering' => true,
            'autoScriptToLang' => true,
            'autoLangToFont' => true,
            'enable_remote' => true,
            'chroot' => public_path(),
            'allow_url_fopen' => true,
            'curlFollowLocation' => true,
            'curlTimeout' => 10,
            'allow_output_buffering' => true,
            'defaultMargin' => 0,
            'defaultMarginHeader' => 0,
            'defaultMarginFooter' => 0
            // Add more options as needed
            
        ]);
        $mpdf->debug = true;

        // Define font path
        $fontDir = public_path('fonts/');
        $fontFile = $fontDir . 'solaimanlipi.ttf';

        // Verify font file exists
        if (!file_exists($fontFile)) {
            throw new \Exception("Font file not found at: " . $fontFile);
        }

        // return view('partials._for_pdf_header_2nd', $data);
        // Prepare header content
        // $headerHtml = view('partials._for_pdf_header_2nd', $data)->render();
        
        // return view('Services::service.viewv2', $data);
        // Prepare main content (without header since we're setting it separately)
        $html = view('Services::service.viewv2', $data)->render();

        // Add CSS for Bangla support
        $css = '
        <style>
            @font-face {
                font-family: "solaimanlipi";
                src: url("' . public_path('fonts/solaimanlipi.ttf') . '") format("truetype");
                font-weight: normal;
                font-style: normal;
            }
            body, * {
                font-family:  sans-serif, "solaimanlipi" !important;
                line-height: 1.5;
            }
        </style>';

        // $html = '<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>' . $css . $html;

        // Set header
        // $mpdf->SetHTMLHeader($headerHtml);
        
        // Set footer (empty for now, but can be added if needed)
        // $mpdf->SetHTMLFooter('');
        
        // Write HTML to mPDF
        $mpdf->WriteHTML($html);

        // Stream PDF to browser
        return $mpdf->Output('service_' . $data['service']->service_unique_id . '.pdf', 'I');
    }

    return view("Services::service.show", $data);
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
        $data['productCatalogs'] = ProductCatalog::all();
        $data['salesOrders'] = SalesOrder::all();
        $data['dongleOrSerialEntries'] = DongleOrSerialEntry::all();
                $data['company_info'] = CompanyInfo::first();

        //
        return view("Services::service.edit", $data);
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
            // dd($request->all());
            $invoice = SalesOrder::where('customer_id', $request->customer_id)
                ->where('status', 'delivered')
                ->whereHas('delivery', function($query) use ($request) {
                    $query->whereHas('deliveryDetails', function($query) use ($request) {
                        $query->whereHas('deliveryStocks', function($query) use ($request) {
                            $query->where('product_catalog_id', $request->product_id)
                                ->where('serial_no', $request->serial_number);
                        });
                    });
                })
                ->with('customer') // optional
                ->first();

            if ($invoice) {
                $invoiceDate = Carbon::parse($invoice->invoice_date);

                return response()->json([
                    'sales_order_id' => $invoice->id,
                    'sales_order_code' => $invoice->sales_order_id,
                    'invoice_date' => $invoiceDate->toDateString(),
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
        $data = $this->service->getSerialIds($request->product_id, $request->customer_id);
        return response()->json($data);
    }

    public function getQuantity(Request $request) 
    {
        $data = $this->service->getQuantity($request->sales_order_id, $request->product_id);
        return response()->json($data);
    }
}
