<?php

namespace Modules\Licenses\Controllers;


use App\Http\Controllers\Controller;
use Modules\Licenses\Models\DongleOrSerialEntry;
use Modules\Licenses\Models\USGOrOPGLicenseRequisition;
use Modules\Licenses\Services\USGOrOPGLicenseRequisitionService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Modules\CRM\Models\Customer\Customer;

class USGOrOPGLicenseRequisitionController extends Controller
{

    /**
     * Service variable
     *
     * @var USGOrOPGLicenseRequisitionService
     */
    private $service;
    function __construct(USGOrOPGLicenseRequisitionService $service)
    {
        $this->service = $service;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data['uSGOrOPGLicenseRequisitions'] = $this->service->getAll();
        $data['customers'] = Customer::activeCustomers()->get();

        return view("Licenses::usg-opg-license-requisition.index", $data);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $data['customers'] = Customer::activeCustomers()->get();
        return view('Licenses::usg-opg-license-requisition.create', $data);
    }

    public function getDongleIds(Request $request)
    {
        $customer_id = $request->input('customer_id');
        $data['dongles'] = DongleOrSerialEntry::where('customer_id', $customer_id)->where('product_type', 'Imaging/Radiology Product')->with('product')->get();
        return response()->json($data);
    }
    public function getNotes(Request $request)
    {
        $dongle_id = $request->input('dongle_id');
        $customer_id = $request->input('customer_id');
        $data['notes'] = USGOrOPGLicenseRequisition::where('dongle_id', $dongle_id)->where('customer_id', $customer_id)->latest()->first();
        return response()->json($data);
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
            'software_version' => 'nullable',
        ]);


        $result = $this->service->store($validate, $request->input('multiple_phone_nos', []));
        return redirect()->route('licenses.usg-opg-license-requisitions.edit', $result->id)->with('success', 'USG/OPG License Requisition created successfully.');
    }
    
    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $data['uSGOrOPGLicenseRequisition'] = $this->service->show($id);

        return view("Licenses::usg-opg-license-requisition.show", $data);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $uSGOrOPGLicenseRequisition = $this->service->show($id);
        $data['license'] = $uSGOrOPGLicenseRequisition;
        $data['customers'] = Customer::activeCustomers()->get();

        return view("Licenses::usg-opg-license-requisition.edit", $data);
    }
    public function approve($id)
    {
        $uSGOrOPGLicenseRequisition = $this->service->show($id);
        $data['license'] = $uSGOrOPGLicenseRequisition;
        $data['customers'] = Customer::activeCustomers()->get();

        return view("Licenses::usg-opg-license-requisition.approval", $data);
    }

    /**
     * Update the specified resource in storage.
     */
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
            'software_version' => 'nullable',

            'status' => 'required',

        ]);
        $phone = $request->validate([
            'multiple_phone_nos' => 'nullable|array',
            'multiple_phone_nos.*' => 'nullable|string|max:20',
        ]);
        $validate['approved_by'] = Auth::user()->id;

        $this->service->update($uSGOrOPGLicenseRequisition, $validate, $phone);

        if ($validate['status'] == 'Approved') {
            return redirect()->route('licenses.usg-opg-license-requisitions.index')->with('success', 'OUG/OPG License Requisition approved successfully.');
        } else {
            return redirect()->route('licenses.usg-opg-license-requisitions.index')->with('success', 'OUG/OPG License Requisition Rejected successfully.');
        }
    }

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

        return redirect()->route('licenses.usg-opg-license-requisitions.edit', $id)->with('success', 'USG/OPG License Requisition updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $uSGOrOPGLicenseRequisition = $this->service->show($id);
        $this->service->delete($uSGOrOPGLicenseRequisition);
        return redirect()->route('licenses.usg-opg-license-requisitions.index')->with('success', 'USG/OPG License Requisition deleted successfully.');
    }
}
