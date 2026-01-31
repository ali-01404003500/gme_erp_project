<?php

namespace Modules\Purchase\Controllers;

use App\Http\Controllers\Controller;
use App\Models\AccessControl\CompanyInfo;
use Modules\Purchase\Models\OfficePurchase;
use Modules\Purchase\Models\Supplier;
use Modules\Purchase\Models\Vendor;
use App\Services\Notifications\GeneralNotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Dompdf\Dompdf;
use Dompdf\Options;
use Illuminate\Support\Facades\DB;
use Modules\Purchase\Services\OfficePurchaseService;
class OfficePurchaseController extends Controller
{

    /**
     * Service variable
     *
     * @var OfficePurchaseService
     */
    private $service; 

    /**
     * GeneralNotificationService variable
     *
     * @var GeneralNotificationService
     */
    private $generalNotificationService;
    function __construct(OfficePurchaseService $service, GeneralNotificationService $generalNotificationService)
    {
        $this->service = $service;
        $this->generalNotificationService = $generalNotificationService;
        // $this->middleware('permited');
    }
    
    /**
     * Display a listing of the resource.
     */
    public function index( Request $request)
    {
        $data['officePurchases'] = $this->service->getAll();
        $data['company_info'] = CompanyInfo::first();

        if ($request->export == "pdf") {
            set_time_limit(1000);
            $html = view('Purchase::office-purchases.indexView', $data)->render();

            // Set Dompdf options
            $options = new Options();
            $options->setIsHtml5ParserEnabled(true);
            $options->setIsRemoteEnabled(true);
            
            $dompdf = new Dompdf($options);
            $dompdf->loadHtml($html);
            $dompdf->setPaper('A4', 'portrait');
            $dompdf->render();

            return $dompdf->stream('office_purchases_' . date('Y-m-d') . '.pdf', ['Attachment' => false]);
        }
        return view("Purchase::office-purchases.index", $data);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $data['vendors'] = Vendor::query()->where('status', 1)->get();

        return view('Purchase::office-purchases.create', $data);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $invoice_no = $this->getOPNumber(); 

        $validate = $request->validate([
            'vendor_id' => 'required|exists:vendors,id',
            'date'=> 'required',
            'reference_bill'=> 'required',
            'particular'=> 'required',
            'bill_amount'=> 'required|numeric',
            'remarks'=> 'nullable',
            'file_upload' =>  'nullable',
        ]);
        $validate['invoice_no'] = $invoice_no;

        $officePurchase = $this->service->store($validate);
        $this->generalNotificationService->store([
            'title' => 'New Office Purchase',
            'description' => 'New Office Purchase Added needed approval',
            'action' => $this->generalNotificationService->actionBuilder(OfficePurchaseController::class, 'approve', [$officePurchase->id]),
         ],$this->generalNotificationService->getPermittedUsers('purchase.offices.approve'));
        return redirect()->route('purchase.offices.edit',$officePurchase->id)->with('success', 'OfficePurchase created successfully.');
    }
    private function getOPNumber()
    {
        $today = date('Y-m-d');

        $authUser = auth()->user()->id;
        $authUserBranch = auth()->user()->branch_id;
        $authUserBranchType = auth()->user()->branch->branch_type_id;

        // Count today's purchase orders created by this user
        $todayOrders = OfficePurchase::whereDate(DB::raw('DATE(created_at)'), $today)
            ->where('created_by', $authUser)
            ->count();

        // Generate PO number in required format
        $poNumber = sprintf(
            'SCT-%02d-SC-%02d-%s-USR-%06d-FP-%05d',
            $authUserBranch,        // Branch ID (2 digits, padded)
            $authUserBranchType,    // Branch Type (2 digits, padded)
            date('Ymd'),            // YYYYMMDD
            $authUser,              // User ID (6 digits, padded)
            $todayOrders + 1        // Count of today’s entries (5 digits, padded)
        );

        return $poNumber;
    }
    /**
     * Display the specified resource.
     */
    public function show($id, Request $request)
    {
        $data['officePurchase'] = $this->service->show($id);
        $data['company_info'] = CompanyInfo::first();

        if ($request->export == "pdf") {
            set_time_limit(1000);
            $html = view('Purchase::office-purchases.view', $data)->render();

            // Set Dompdf options
            $options = new Options();
            $options->setIsHtml5ParserEnabled(true);
            $options->setIsRemoteEnabled(true);

            $dompdf = new Dompdf($options);
            $dompdf->loadHtml($html);
            $dompdf->setPaper('A4', 'portrait');
            $dompdf->render();

            return $dompdf->stream('officePurchase_' . $data['officePurchase']->company_name . '.pdf', ['Attachment' => false]);
        }
        return view("Purchase::office-purchases.show", $data);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit( $id)
    {
        $officePurchase = OfficePurchase::findOrFail($id);
        $data['officePurchase'] = $officePurchase;
        $data['vendors'] = Vendor::query()->where('status', 1)->get();
        return view("Purchase::office-purchases.edit", $data);
    }

    public function update(Request $request, $id)
    {
        $officePurchase = OfficePurchase::findOrFail($id);

        $validate = $request->validate([
            'vendor_id' => 'required|exists:vendors,id',
            'date'=> 'required',
            'reference_bill'=> 'required',
            'particular'=> 'required',
            'bill_amount'=> 'required|numeric',
            'remarks'=> 'nullable',
            'file_upload' =>  'nullable|',
        ]);
        $officePurchase = $this->service->update($officePurchase, $validate);

        return redirect()->route('purchase.offices.edit', $officePurchase->id)->with('success', 'OfficePurchase updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $officePurchase = OfficePurchase::findOrFail($id);
        $this->service->delete($officePurchase);
        return redirect()->route('purchase.offices.index')->with('success', 'OfficePurchase deleted successfully.');
    }

    public function approve( $id)
    {
        $data['officePurchase'] = $this->service->show($id);
        $data['vendors'] = Vendor::query()->where('status', 1)->get();
        return view("Purchase::office-purchases.approve", $data);
    }

    public function approveStore(Request $request, $id)
    {
        // dd($request->all());
        $officePurchase = $this->service->show($id);

        $validate = $request->validate([
            'vendor_id' => 'required|exists:vendors,id',
            'date'=> 'required',
            'reference_bill'=> 'required',
            'particular'=> 'required',
            'bill_amount'=> 'required',
            'remarks'=> 'nullable',
            'file_upload' =>  'nullable',
            'status' => 'required',
        ]);

        $validate['approved_by'] = Auth::user()->id;

        $officePurchase = $this->service->update($officePurchase, $validate);

        if($validate['status'] == '1'){
            // dd($officePurchase);
            $this->service->makeDummyTransaction($officePurchase);

            return redirect()->route('purchase.offices.index')->with('success', 'Office Purchase approved successfully.');
        }
        else{
            return redirect()->route('purchase.offices.index')->with('success', 'Office Purchase Rejected successfully.');
        }
    }

    
}
