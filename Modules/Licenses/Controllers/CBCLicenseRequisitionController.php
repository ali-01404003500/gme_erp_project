<?php

namespace Modules\Licenses\Controllers;

use App\Http\Controllers\Controller;
use Modules\Licenses\Models\CBCLicenseRequisition;
use Modules\Licenses\Models\DongleOrSerialEntry;
use App\Services\Notifications\GeneralNotificationService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Modules\CRM\Models\Customer\Customer;
use Modules\Licenses\Services\CBCLicenseRequisitionService;

class CBCLicenseRequisitionController extends Controller
{

    /**
     * Service variable
     *
     * @var CBCLicenseRequisitionService
     */
    private $service;
    /**
     * GeneralNotificationService variable
     *
     * @var GeneralNotificationService
     */
    private $generalNotificationService;
    function __construct(CBCLicenseRequisitionService $service, GeneralNotificationService $generalNotificationService)
    {
        $this->service = $service;
        $this->generalNotificationService = $generalNotificationService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data['cBCLicenseRequisitions'] = $this->service->getAll(); 
        $data['customer'] = Customer::find(request('customer_id'));
        return view("Licenses::cbc-license-requisition.index", $data);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    { 
        return view('Licenses::cbc-license-requisition.create' );
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // dd($request->all());

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
            'software_version' => 'nullable',

        ]);


        $result = $this->service->store($validate, $request->input('multiple_phone_nos', []));
        $this->generalNotificationService->store([
            'title' => 'New CBC License Requisition Added',
            'description' => 'New CBC License Requisition Added needed approval',
            'action' => $this->generalNotificationService->actionBuilder(CBCLicenseRequisitionController::class, 'approve', [$result->id]),
        ], $this->generalNotificationService->getPermittedUsers('licenses.cbc-license-requisitions.approve'));
        return redirect()->route('licenses.cbc-license-requisitions.edit', $result->id)->with('success', 'CBCLicenseRequisition created successfully.');
    }

   

    public function getDongleIds(Request $request)
    {
        $customer_id = $request->input('customer_id');
        $data['dongles'] = DongleOrSerialEntry::where('customer_id', $customer_id)->where('product_type', 'Hematology Analyzer')->with('product')->get();
        return response()->json($data);
    }
    public function getNotes(Request $request)
    {
        $dongle_id = $request->input('dongle_id');
        $customer_id = $request->input('customer_id');
        $data['notes'] = CBCLicenseRequisition::where('dongle_id', $dongle_id)->where('customer_id', $customer_id)->latest()->first();
        return response()->json($data);
    }
    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $data['cBCLicenseRequisition'] = $this->service->show($id);

        return view("Licenses::cbc-license-requisition.show", $data);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $cBCLicenseRequisition = $this->service->show($id);
        $data['license'] = $cBCLicenseRequisition; 

        return view("Licenses::cbc-license-requisition.edit", $data);
    }
    public function approve($id)
    {
        $cBCLicenseRequisition = $this->service->show($id);
        $data['license'] = $cBCLicenseRequisition;
        $data['customers'] = Customer::activeCustomers()->get();

        return view("Licenses::cbc-license-requisition.approval", $data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $uSGOrOPGLicenseRequisition = $this->service->show($id);
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
            'software_version' => 'nullable',

        ]);
        $phone = $request->validate([
            'multiple_phone_nos' => 'nullable|array',
            'multiple_phone_nos.*' => 'nullable|string|max:20',
        ]);

        $this->service->update($uSGOrOPGLicenseRequisition, $validate, $phone);
        return redirect()->route('licenses.cbc-license-requisitions.edit', $id)->with('success', 'CBCLicenseRequisition updated successfully.');
    }

    public function approveStore(Request $request, $id)
    {
        $uSGOrOPGLicenseRequisition = $this->service->show($id);
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
            'status' => 'required|in:Approved,Rejected',
            'software_version' => 'nullable',

        ]);
        $phone = $request->validate([
            'multiple_phone_nos' => 'nullable|array',
            'multiple_phone_nos.*' => 'nullable|string|max:20',
        ]);
        $validate['approved_by'] = Auth::user()->id;

        $this->service->update($uSGOrOPGLicenseRequisition, $validate, $phone);
        if ($validate['status'] == 'Approved') {
            return redirect()->route('licenses.cbc-license-requisitions.index')->with('success', 'CBC License Requisition approved successfully.');
        } else {
            return redirect()->route('licenses.cbc-license-requisitions.index')->with('success', 'CBC License Requisition Rejected successfully.');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $cBCLicenseRequisition = $this->service->show($id);
        $this->service->delete($cBCLicenseRequisition);
        return redirect()->route('licenses.cbc-license-requisitions.index')->with('success', 'CBCLicenseRequisition deleted successfully.');
    }
}
