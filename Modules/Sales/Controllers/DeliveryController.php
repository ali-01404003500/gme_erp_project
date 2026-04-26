<?php
namespace Modules\Sales\Controllers;

use App\Http\Controllers\Controller;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Modules\HRMS\Models\Employee;
use Modules\Sales\Models\Delivery;
use Modules\Sales\Models\DeliveryDetail;
use Modules\Sales\Services\DeliveryService;

class DeliveryController extends Controller
{

    /**
     * Service variable
     *
     * @var DeliveryService
     */
    private $service;
    public function __construct(DeliveryService $service)
    {
        $this->service = $service;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->has('export') && $request->get('export') === 'pdf') {
            $deliveries = $this->service->getAll(1000);
            $pdf        = Pdf::loadView('Sales::deliveries.pdf', compact('deliveries'));
            return $pdf->download('deliveries.pdf');
        }

        $data['deliveries'] = $this->service->getAll();
        // dd($this->service->getAll()->first());
        return view("Sales::deliveries.index", $data);
    }

    /**
     * Show the form for creating a new resource.
     */
    // public function create(Request $request)
    // {
    //     $data['source']             = Delivery::with('source')->findOrFail($request->delivery_id)?->source;
    //     $data['previousDeliveries'] = Delivery::where('id', $request->delivery_id)
    //         ->with('deliveryDetails')
    //         ->get();
    //     $data['employees'] = Employee::query()->select('id', 'full_name as name')->get();
    //     //    dd($data);
    //     return view('Sales::deliveries.create', $data);
    // }

    public function create(Request $request)
    {
        // OPTIMIZED
        $delivery = Delivery::with(['source', 'deliveryDetails'])->findOrFail($request->delivery_id);

        $data['source']             = $delivery->source;
        $data['previousDeliveries'] = collect([$delivery]); // Use the same delivery
        $data['employees']          = Employee::query()->select('id', 'full_name as name')->get();

        return view('Sales::deliveries.create', $data);
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
        return redirect()->route('sales.deliveries.index')->with('success', 'Delivery created successfully.');
    }

    /**
     * Display the specified resource.
     */
    // public function show($id, Request $request)
    // {
    //     $delivery           = $this->service->show($id);
    //     $source             = Delivery::with('source')->findOrFail($id)?->source;
    //     $previousDeliveries = Delivery::where('id', $id)
    //         ->with('deliveryDetails')
    //         ->get();

    //     if ($request->has('export') && $request->get('export') === 'pdf') {
    //         $pdf = PDF::loadView('Sales::deliveries.pdf', compact('delivery', 'source', 'previousDeliveries'));
    //         return $pdf->download('delivery_' . $delivery->id . '.pdf');
    //     }

    //     return view("Sales::deliveries.show", compact('delivery', 'source', 'previousDeliveries'));
    // }

    public function show($id, Request $request)
    {
        // OPTIMIZED
        $delivery = Delivery::with(['source', 'deliveryDetails'])->findOrFail($id);

        $source             = $delivery->source;
        $previousDeliveries = collect([$delivery]);

        if ($request->has('export') && $request->get('export') === 'pdf') {
            $pdf = PDF::loadView('Sales::deliveries.pdf', compact('delivery', 'source', 'previousDeliveries'));
            return $pdf->download('delivery_' . $delivery->id . '.pdf');
        }

        return view("Sales::deliveries.show", compact('delivery', 'source', 'previousDeliveries'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    // public function edit(Delivery $delivery)
    // {
    //     $data['delivery'] = Delivery::with('source')->find($delivery->id);
    //     //
    //     return view("Sales::deliveries.edit", $data);
    // }

    public function edit(Delivery $delivery)
    {
        // OPTIMIZED
        $data['delivery'] = Delivery::with(['source', 'deliveryDetails'])->find($delivery->id);

        return view("Sales::deliveries.edit", $data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Delivery $delivery)
    {
        $validate = $request->validate([
            'arranged_by' => 'required|integer|exists:employees,id',
            'checked_by'  => 'required|integer|exists:employees,id',

            'carton_no'   => 'required|string|max:255',

            'carton_no'   => 'required|integer|min:1',

        ]);

        $deliveryDetails = $request->validate([
            'product_id.*'     => 'required|integer|exists:product_catalogs,id',
            'sales_quantity.*' => 'required|numeric',
            'quantity.*'       => 'nullable|numeric',
        ]);

        // foreach ($deliveryDetails['sales_quantity'] as $key => $salesQuantity) {
        //     if ($deliveryDetails['sales_quantity'][$key] != $deliveryDetails['quantity'][$key]) {
        //         return redirect()->back()->withErrors(['quantity.' . $key => 'The sales quantity and quantity of product ' . $key . ' should be same.']);
        //     }
        // }

        $deliveryStockDetails = $request->validate([
            'lot_no.*.*'        => 'nullable|string',
            'lots_quantity.*.*' => 'nullable|numeric',
            'serial_no.*.*'     => 'nullable|string',
        ]);

        $this->service->update($delivery, $validate, $deliveryDetails, $deliveryStockDetails);

        return redirect()->route('sales.deliveries.index')->with('success', 'Delivery updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Delivery $delivery)
    {
        $this->service->delete($delivery);
        return redirect()->route('sales.deliveries.index')->with('success', 'Delivery deleted successfully.');
    }

    /**
     * Show Delivery Details
     */

    // public function details(Request $request)
    // {
    //     $deliveryId = $request->delivery_id;
    //     $productId  = $request->product_id;

    //     $deliveryDetails = DeliveryDetail::with('deliveryStocks')
    //         ->where('delivery_id', $deliveryId)
    //         ->where('product_id', $productId)
    //         ->get();

    //     return view("Sales::deliveries.details", [
    //         'deliveryDetails' => $deliveryDetails,
    //     ]);
    // }

    public function details(Request $request)
    {
        $deliveryId = $request->delivery_id;
        $productId  = $request->product_id;

        // OPTIMIZED: select only needed columns
        $deliveryDetails = DeliveryDetail::with(['deliveryStocks' => function ($q) {
            $q->select('id', 'delivery_detail_id', 'lot_no', 'lots_quantity', 'serial_no');
        }])
            ->where('delivery_id', $deliveryId)
            ->where('product_id', $productId)
            ->select('id', 'delivery_id', 'product_id', 'sales_quantity', 'quantity')
            ->get();

        return view("Sales::deliveries.details", [
            'deliveryDetails' => $deliveryDetails,
        ]);
    }
}
