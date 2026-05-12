<?php

namespace Modules\Purchase\Controllers;

use App\Http\Controllers\Controller;
use App\Models\AccessControl\CompanyInfo;
use Modules\Purchase\Models\Supplier;
use Modules\Purchase\Services\SupplierService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

use Dompdf\Dompdf;
use Dompdf\Options;
use Illuminate\Support\Facades\Log;
use Modules\CRM\Models\Customer\Customer;

class SupplierController extends Controller
{
    /**
     * Service variable
     *
     * @var SupplierService
     */
    private $service;
    function __construct(SupplierService $service)
    {
        $this->service = $service;
        $this->middleware('permited')->except('convertImageToBase64');
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $data['suppliers'] = $this->service->getAll(); 
        $data['supplierSearch'] = Supplier::get();

        if ($request->export == 'pdf') {
            set_time_limit(1000);
            $html = view('Purchase::supplier.indexView', $data)->render();

            // Set Dompdf options
            $options = new Options();
            $options->setIsHtml5ParserEnabled(true);
            $options->setIsRemoteEnabled(true);

            $dompdf = new Dompdf($options);
            $dompdf->loadHtml($html);
            $dompdf->setPaper('A4', 'portrait');
            $dompdf->render();

            return $dompdf->stream('supplier_list_' . date('Y-m-d') . '.pdf', ['Attachment' => false]);
        }

        return view('Purchase::supplier.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    { 
        return view('Purchase::supplier.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'company_name' => 'required|string|max:255',
            'company_place' => 'required|string|max:255',
            'tnt_number' => 'nullable|string|max:255',
            'opening_balance' => 'nullable',
            'email' => 'nullable|email|max:255|unique:suppliers,email,NULL,id,deleted_at,NULL',
            'contact_for_sms' => 'nullable|string|max:20',
            'customer_id' => 'nullable|string|max:255',
            'country' => 'nullable|string|max:255',
            'address' => 'required|string',
            'profile_picture' => 'nullable',
            'owner_name' => 'nullable|string|max:255',
            'owner_designation' => 'nullable|string|max:255',
            'owner_mobile' => 'nullable|string|max:20',
            'owner_email' => 'nullable|email|max:255',
            'owner_dob' => 'nullable|date',
            'owner_address' => 'nullable|string|max:255',
            'nid' => 'nullable|string|max:255|unique:suppliers,nid',
            'front_image' => 'nullable',
            'back_image' => 'nullable',
            'visiting_card_front' => 'nullable',
            'visiting_card_back' => 'nullable',
            'trade_license' => 'nullable',
            'signature' => 'nullable',
            'remarks' => 'nullable|string',
            'country_code' => 'required|string',
            'phone' => 'required|string',
        ]);

        // Phone number validation after basic validation
        $fullNumber = $validated['country_code'] . $validated['phone'];
        $phoneUtil = \libphonenumber\PhoneNumberUtil::getInstance();

        try {
            $phoneNumberObject = $phoneUtil->parse($fullNumber, null);
            if (!$phoneUtil->isValidNumber($phoneNumberObject)) {
                return back()
                    ->withErrors(['phone' => 'Invalid phone number.'])
                    ->withInput();
            }
        } catch (\libphonenumber\NumberParseException $e) {
            return back()
                ->withErrors(['phone' => 'Invalid phone number format.'])
                ->withInput();
        }

        $supplier = $this->service->store($validated);

        return redirect()->route('purchase.suppliers.edit', $supplier->id)->with('success', 'Supplier created successfully.');
    }

    /**
     * Display the specified resource.
     */
    // public function show( $id)
    // {
    //     $data['supplier'] = $this->service->show($id);

    //     return view("Purchase::supplier.show", $data);
    // }

    /**
     * Convert image to base64 string
     *
     * @param string $path
     * @return string|null
     */
    private function convertImageToBase64($path)
    {
        $fileContents = file_exists($path) ? file_get_contents($path) : null;

        if ($fileContents !== false) {
            $type = pathinfo($path, PATHINFO_EXTENSION);
            $base64 = 'data:image/' . $type . ';base64,' . base64_encode($fileContents);
            return $base64;
        }

        return null;
    }

    /**
     * Display the specified resource.
     */
    public function show($id, Request $request)
{
    try {
        // Get supplier data
        $supplier = $this->service->show($id);
        
        if (!$supplier) {
            abort(404, 'Supplier not found');
        }

        $data = [
            'supplier' => $supplier,
            'purchaseOrders' => $supplier->purchaseRequisitions()->latest()->take(20)->get(),
        ];

        // Handle PDF export request
        if ($request->has('export') && $request->export == 'pdf') {
            set_time_limit(300); // More reasonable time limit
            ini_set('memory_limit', '256M'); // Increase memory limit for PDF generation
            
            try {
                // Generate PDF with proper error handling
                $html = view('Purchase::supplier.view', $data)->render();

                $options = new Options();
                $options->setIsHtml5ParserEnabled(true);
                $options->setIsRemoteEnabled(true);
                $options->setDefaultFont('DejaVu Sans'); // Better font support for special characters

                $dompdf = new Dompdf($options);
                $dompdf->loadHtml($html, 'UTF-8');
                $dompdf->setPaper('A4', 'portrait');
                
                // Improve PDF quality
                $dompdf->set_option('isPhpEnabled', true);
                $dompdf->set_option('isRemoteEnabled', true);
                $dompdf->set_option('isHtml5ParserEnabled', true);
                
                $dompdf->render();

                $filename = 'supplier_' . Str::slug($supplier->company_name) . '_' . now()->format('Y-m-d') . '.pdf';

                return $dompdf->stream($filename, [
                    'Attachment' => false,
                    'compress' => true,
                ]);

            } catch (\Exception $e) {
                Log::error('PDF Generation Error: ' . $e->getMessage());
                return back()->with('error', 'Failed to generate PDF. Please try again.');
            }
        }

        // Return normal view
        return view('Purchase::supplier.show', $data);

    } catch (\Exception $e) {
        Log::error('Supplier Show Error: ' . $e->getMessage());
        return back()->with('error', 'An error occurred while loading supplier details.');
    }
}

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Supplier $supplier)
    {
        $data['supplier'] = $supplier; 
        return view('Purchase::supplier.edit', $data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Supplier $supplier)
    {
        $validate = $request->validate([
            'company_name' => 'required|string|max:255',
            'company_place' => 'required|string|max:255',
            'country_code' => 'required|string',
            'phone' => 'required|string',
            'tnt_number' => 'nullable|string|max:255',
            'opening_balance' => 'nullable',
            'email' => 'nullable|email|max:255',
            'contact_for_sms' => 'nullable|string|max:20',
            'customer_id' => 'nullable|string|max:255',
            'country' => 'nullable|string|max:255',
            'address' => 'required|string',
            'profile_picture' => 'nullable',
            'owner_name' => 'nullable|string|max:255',
            'owner_designation' => 'nullable|string|max:255',
            'owner_mobile' => 'nullable|string|max:20',
            'owner_email' => 'nullable|email|max:255',
            'owner_dob' => 'nullable|date',
            'owner_address' => 'nullable|string|max:255',
            'nid' => 'nullable|string|max:255',
            'front_image' => 'nullable',
            'back_image' => 'nullable',
            'visiting_card_front' => 'nullable',
            'visiting_card_back' => 'nullable',
            'trade_license' => 'nullable',
            'signature' => 'nullable',
            'remarks' => 'nullable|string',
        ]);
        $fullNumber = $request->country_code . $request->phone;

        $phoneUtil = \libphonenumber\PhoneNumberUtil::getInstance();
        $phoneNumberObject = $phoneUtil->parse($fullNumber, null);

        if (!$phoneUtil->isValidNumber($phoneNumberObject)) {
            return back()->withErrors(['phone' => 'Invalid phone number.']);
        }
        $supplier = $this->service->update($supplier, $validate);

        return redirect()->route('purchase.suppliers.edit', $supplier->id)->with('success', 'Supplier updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Supplier $supplier)
    {
        $this->service->delete($supplier);
        return redirect()->route('purchase.suppliers.index')->with('success', 'Supplier deleted successfully.');
    }

    public function downloadSampleCSV()
    {
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="sample_suppliers.csv"',
        ];

        $columns = ['company_name', 'company_place', 'country_code', 'phone', 'tnt_number', 'opening_balance', 'email', 'contact_for_sms', 'customer_id', 'country', 'address', 'profile_picture', 'owner_name', 'owner_designation', 'owner_mobile', 'owner_email', 'owner_dob', 'owner_address', 'nid', 'front_image', 'back_image', 'visiting_card_front', 'visiting_card_back', 'trade_license', 'signature', 'remarks'];

        $sampleData = [['ABC Ltd.', 'Dhaka City', '+88', '01987654321', '09678020555', '1000', 'abc@example.com', '013456789', 'Maria & Co.', 'Bangladesh', '123 Street, NY', '', 'John Doe', 'Director', '9876543210', 'johndoe@example.com', '1980-01-01', '123 Street, NY', '123456789', '', '', '', '', '', '', 'No remarks']];

        $callback = function () use ($columns, $sampleData) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);
            foreach ($sampleData as $row) {
                fputcsv($file, $row);
            }
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function insertFromCSV(Request $request)
    {
        $file = $request->file('csv_file');
        // if(!$file){
        //     return redirect()->route('inv.product-catalogs.index')->with('error', 'File cannot be uploaded.');
        // }
        $filename = $file->getClientOriginalName();
        $path = $file->storeAs('public', $filename);
        $this->service->insertFromCSV($filename);
        return redirect()->route('purchase.suppliers.index')->with('success', 'Supplier imported successfully.');
    }
}
