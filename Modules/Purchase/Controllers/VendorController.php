<?php

namespace Modules\Purchase\Controllers;

use App\Http\Controllers\Controller;
use App\Models\AccessControl\CompanyInfo;
use Modules\Purchase\Models\Vendor;
use Modules\Purchase\Services\VendorService;
use Illuminate\Http\Request;
use Dompdf\Dompdf;
use Dompdf\Options;

class VendorController extends Controller
{

    /**
     * Service variable
     *
     * @var VendorService
     */
    private $service; 
    function __construct(VendorService $service)
    {
        $this->service = $service;
        $this->middleware('permited')->except('convertImageToBase64','getAllVendors');

    }
    
    /**
     * Display a listing of the resource.
     */
    public function index( Request $request)
    {
        $data['vendors'] = $this->service->getAll();
        $data['company_info'] = CompanyInfo::first();
        $data['vendorSearch'] = Vendor::get();

        if ($request->export == "pdf") {
            set_time_limit(1000);
            $html = view('Purchase::vendor.indexView', $data)->render();

            // Set Dompdf options
            $options = new Options();
            $options->setIsHtml5ParserEnabled(true);
            $options->setIsRemoteEnabled(true);
            
            $dompdf = new Dompdf($options);
            $dompdf->loadHtml($html);
            $dompdf->setPaper('A4', 'portrait');
            $dompdf->render();

            return $dompdf->stream('vendor_list_' . date('Y-m-d') . '.pdf', ['Attachment' => false]);
        }

        return view("Purchase::vendor.index", $data);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('Purchase::vendor.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validate = $request->validate([
            'company_name' => 'required|string|max:255',
            'account_head_id' => 'nullable|string|max:255',
            'company_type_id' => 'nullable|string|max:255',
            'phone' =>  ['required', 'regex:/^(?:\+?88|00)?01[3-9]\d{8}$/','unique:vendors,phone,NULL,id,deleted_at,NULL'],
            'opening_balance' => 'nullable|numeric|min:0',
            'email' => 'nullable|email|max:255|unique:vendors,email,NULL,id,deleted_at,NULL',
            'address' => 'required|string',
            'owner_name' => 'nullable|string|max:255',
            'owner_designation' => 'nullable|string|max:255',
            'owner_mobile' => 'nullable|string|max:20',
            'owner_email' => 'nullable|email|max:255',
            'owner_dob' => 'nullable|date',
            'owner_address' => 'nullable|string|max:255',
            'nid' => 'nullable|string|max:255|unique:vendors,nid',
            'front_image' => 'nullable',
            'back_image' => 'nullable',
            'visiting_card_front' => 'nullable',
            'trade_license' => 'nullable',
            'signature' => 'nullable',
        ]);
        $vendor = $this->service->store($validate);
        return redirect()->route('purchase.vendors.edit', $vendor->id)->with('success', 'Vendor created successfully.');
    }

    /**
     * Display the specified resource.
     */
    // public function show( $id)
    // {
    //     $data['vendor'] = $this->service->show($id);

    //     return view("Purchase::vendor.show", $data);
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
    $vendor = $this->service->show($id);

    $data['vendor'] = $vendor;

    if ($request->export == "pdf") {
        set_time_limit(1000);
        $html = view('Purchase::vendor.view', $data)->render();

        // Set Dompdf options
        $options = new Options();
        $options->setIsHtml5ParserEnabled(true);
        $options->setIsRemoteEnabled(true);
        
        $dompdf = new Dompdf($options);
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();

        return $dompdf->stream('vendor_' . $data['vendor']->company_name . '.pdf', ['Attachment' => false]);
    }

    return view("Purchase::vendor.show", $data);
}


    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Vendor $vendor)
    {
        $data['vendor'] = $vendor;
        //
        return view("Purchase::vendor.edit", $data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Vendor $vendor)
    {
        $validate = $request->validate([
            'company_name' => 'required|string|max:255',
            'account_head_id' => 'nullable|string|max:255',
            'company_type_id' => 'nullable|string|max:255',
            'phone' => 'required|string|max:20',
            'opening_balance' => 'nullable|numeric|min:0',
            'email' => 'nullable|email|max:255',
            'address' => 'required|string',
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
            'trade_license' => 'nullable',
            'signature' => 'nullable',
        ]);
        $vendor = $this->service->update($vendor, $validate);

        return redirect()->route('purchase.vendors.edit', $vendor->id)->with('success', 'Vendor updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Vendor $vendor)
    {
        $this->service->delete($vendor);
        return redirect()->route('purchase.vendors.index')->with('success', 'Vendor deleted successfully.');
    }

    public function downloadSampleCSV() {
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="sample_vendors.csv"',
        ];
    
        $columns = ['company_name', 'account_head', 'company_type', 'phone', 'opening_balance', 'email', 'address', 'owner_name', 'owner_designation', 'owner_mobile', 'owner_email', 'owner_dob', 'owner_address', 'nid'];
        
        $sampleData = [
            ['ABC Ltd.', 'Cash', 'Private Limited', '+8801712345678', '1000', 'abc@example.com', '123 Street, NY', 'John Doe', 'Director', '01711234567', 'johndoe@example.com', '1980-01-01', '123 Street, NY', '01895452675'],
        ];
        
        $callback = function() use ($columns, $sampleData) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);
            foreach ($sampleData as $row) {
                fputcsv($file, $row);
            }
            fclose($file);
        };
    
        return response()->stream($callback, 200, $headers);
    }
    

    public function insertFromCSV(Request $request){
        $file = $request->file('csv_file');
        // if(!$file){
        //     return redirect()->route('inv.product-catalogs.index')->with('error', 'File cannot be uploaded.');
        // }
        $filename = $file->getClientOriginalName();
        $path = $file->storeAs('public', $filename);
        $this->service->insertFromCSV($filename);
        return redirect()->route('purchase.vendors.index')->with('success', 'Vendor imported successfully.');
    }




    
    public function getAllVendors(){
        $vendors = Vendor::select('id', 'company_name as name')->get(); 
        return response()->json([
            'data' => $vendors
        ]);
    }
}
