<?php

namespace Modules\Sales\Controllers;


use App\Http\Controllers\Controller;
use Modules\Sales\Models\BackupChallan;
use Modules\Sales\Models\BackupChallanDelivery;
use Modules\Sales\Services\BackupChallanDeliveryService;
use Illuminate\Http\Request;

class BackupChallanDeliveryController extends Controller
{

    /**
     * Service variable
     *
     * @var BackupChallanDeliveryService
     */
    private $service; 
    function __construct(BackupChallanDeliveryService $service)
    {
        $this->service = $service;
    }
    
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data['backupChallanDeliverys'] = $this->service->getAll();

        return view("backupChallanDeliverys.index", $data);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $data['backupChallans'] = BackupChallan::query()->get();
        if($request->has('backup_challan_id')){
            $data['backupChallan'] = BackupChallan::find($request->backup_challan_id);
        }
        return view('Sales::backup-challan.delivery', $data);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // dd($request->all());
        $validate = $request->validate([
            'backup_challan_id' => 'required|exists:backup_challans,id',
            'backup_challan_type' => 'nullable|string',
        ]);
        $sODProductDetails = $request->validate([
            'product_id.*' => 'required|integer|exists:product_catalogs,id',
            'quantity.*' => 'required|numeric',
        ]);
        $productDetails = $request->validate([
            'lot_no.*.*' => 'nullable|string',
            'lots_quantity.*.*' => 'nullable|numeric',
            'serial_no.*.*' => 'nullable|string',
        ]);
        // dd(
        //     $validate,
        //     $sODProductDetails,
        //     $productDetails
        // );
        $this->service->store($validate, $sODProductDetails, $productDetails);
        return redirect()->route('sales.backup-challans.index')->with('success', 'BackupChallan Delivered successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show( $id)
    {
        $data['backupChallanDelivery'] = $this->service->show($id);

        return view("backupChallanDeliverys.show", $data);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(BackupChallanDelivery $backupChallanDelivery)
    {
        $data['backupChallanDelivery'] = $backupChallanDelivery;
        //
        return view("backupChallanDeliverys.edit", $data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, BackupChallanDelivery $backupChallanDelivery)
    {
        $validate = $request->validate([
            //validate rules
        ]);
        $this->service->update($backupChallanDelivery, $validate);

        return redirect()->route('backupChallanDeliverys.index')->with('success', 'BackupChallanDelivery updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(BackupChallanDelivery $backupChallanDelivery)
    {
        $this->service->delete($backupChallanDelivery);
        return redirect()->route('backupChallanDeliverys.index')->with('success', 'BackupChallanDelivery deleted successfully.');
    }
}
