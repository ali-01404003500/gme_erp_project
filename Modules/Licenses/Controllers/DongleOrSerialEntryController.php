<?php

namespace Modules\Licenses\Controllers;


use App\Http\Controllers\Controller;
use Modules\Inventory\Models\ProductCatalog;
use Modules\Licenses\Models\DongleOrSerialEntry;
use Modules\Licenses\Services\DongleOrSerialEntryService;
use Illuminate\Http\Request;
use Modules\CRM\Models\Customer\Customer;

class DongleOrSerialEntryController extends Controller
{

    /**
     * Service variable
     *
     * @var DongleOrSerialEntryService
     */
    private $service;
    function __construct(DongleOrSerialEntryService $service)
    {
        $this->service = $service;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data['customers'] = Customer::activeCustomers()->get();
        $data['dongleOrSerialEntrys'] = $this->service->getAll();

        return view("Licenses::dongle-or-serial-entry.index", $data);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {

        $data['customers'] = Customer::activeCustomers()->get();
        $data['products'] = ProductCatalog::query()->get();
        return view('Licenses::dongle-or-serial-entry.create', $data);
    }
    public function dropdown(Request $request)
    {
        $search = $request->get('q', '');

        $items = DongleOrSerialEntry::where('dongle_id', 'LIKE', "%{$search}%")
            ->select('id', 'dongle_id')
            ->limit(10)
            ->get();

        return response()->json($items);
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
            'message' => 'DongleOrSerialEntry created successfully.',
            'dongleOrSerialEntry' => $data['dongleOrSerialEntry']
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validate = $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'address' => 'required|string',
            'product_id' => 'required|exists:product_catalogs,id',
            'product_type' => 'required|string',
            'dongle_id' => 'required|string|unique:dongle_or_serial_entries,dongle_id,NULL,id,deleted_at,NULL',
            'software_version' => 'nullable|string',
            'status' => 'required|string',
            'file_upload' => 'nullable',
        ]);
        $this->service->store($validate);
        return redirect()->route('licenses.dongle-or-serial-entries.index')->with('success', 'DongleOrSerialEntry created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $data['dongleOrSerialEntry'] = $this->service->show($id);

        return view("Licenses::dongle-or-serial-entry.show", $data);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(DongleOrSerialEntry $dongleOrSerialEntry)
    {
        $data['dongleOrSerialEntry'] = $dongleOrSerialEntry;
        $data['customers'] = Customer::activeCustomers()->get();
        $data['products'] = ProductCatalog::query()->get();
        return view("Licenses::dongle-or-serial-entry.edit", $data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, DongleOrSerialEntry $dongleOrSerialEntry)
    {
        $validate = $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'address' => 'required|string',
            'product_id' => 'required|exists:product_catalogs,id',
            'product_type' => 'required|string',
            'dongle_id' => 'required|string|unique:dongle_or_serial_entries,dongle_id,' . $dongleOrSerialEntry->id,
            'software_version' => 'nullable|string',
            'status' => 'required|string',
            'file_upload' => 'nullable',
        ]);
        $this->service->update($dongleOrSerialEntry, $validate);

        return redirect()->route('licenses.dongle-or-serial-entries.index')->with('success', 'DongleOrSerialEntry updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(DongleOrSerialEntry $dongleOrSerialEntry)
    {
        $this->service->delete($dongleOrSerialEntry);
        return redirect()->route('licenses.dongle-or-serial-entries.index')->with('success', 'DongleOrSerialEntry deleted successfully.');
    }

    
}
