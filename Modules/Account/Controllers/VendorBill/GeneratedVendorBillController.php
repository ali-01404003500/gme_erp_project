<?php

namespace Modules\Account\Controllers\VendorBill;

use App\Http\Controllers\Controller;
use Modules\Account\Models\VendorBill\GeneratedVendorBill;
use Modules\Account\Services\VendorBill\GeneratedVendorBillService;
use Illuminate\Http\Request;

class GeneratedVendorBillController extends Controller
{

    /**
     * Service variable
     *
     * @var GeneratedVendorBillService
     */
    private $service;
    function __construct(GeneratedVendorBillService $service)
    {
        $this->service = $service;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data['generatedVendorBills'] = $this->service->getAll();

        return view("Account::vendor-bill.generated-vendor-bills.index", $data);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('Account::vendor-bill.generated-vendor-bills.create');
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
        return redirect()->route('account.vendor-bills.generated-vendor-bills.index')->with('success', 'GeneratedVendorBill created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, $id)
    {
        $data['generatedVendorBill'] = $this->service->show($id);
        $data['company_info'] = \App\Models\AccessControl\CompanyInfo::first();

        // Check if export is requested
        if ($request->filled('export_type')) {
            $filename = 'Vendor_Bill_' . $data['generatedVendorBill']->bill_id . '_' . today()->format('Y_m_d');

            return (new \Modules\Inventory\Services\ExportService())->exportData(
                $data,
                'Account::vendor-bill.generated-vendor-bills.export.',
                $filename
            );
        }

        return view("Account::vendor-bill.generated-vendor-bills.show", $data);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(GeneratedVendorBill $generatedVendorBill)
    {
        // dd($generatedVendorBill);
        $data['generatedVendorBill'] = $generatedVendorBill;
        //
        return view("Account::vendor-bill.generated-vendor-bills.edit", $data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, GeneratedVendorBill $generatedVendorBill)
    {
        // dd($request->all());
        $validate = $request->validate([
            //validate rules
            'amount' => 'required|numeric',
            'remarks' => 'nullable|string',
            'status' => 'required|in:pending,verified,approved,denied',
        ]);
        $this->service->update($generatedVendorBill, $validate);

        return redirect()->route('account.vendor-bills.generated-vendor-bills.index')->with('success', 'GeneratedVendorBill updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(GeneratedVendorBill $generatedVendorBill)
    {
        $this->service->delete($generatedVendorBill);
        return redirect()->route('account.vendor-bills.generated-vendor-bills.index')->with('success', 'GeneratedVendorBill deleted successfully.');
    }
}
