<?php

namespace Modules\Sales\Controllers;


use App\Http\Controllers\Controller;
use Barryvdh\DomPDF\Facade\Pdf;
use Modules\Sales\Models\Delivery;
use Modules\Sales\Models\DeliveryDetail;
use Modules\Sales\Services\DeliveryService;
use Illuminate\Http\Request;
use Modules\HRMS\Models\Employee;

class DeliveryController extends Controller
{

    /**
     * Service variable
     *
     * @var DeliveryService
     */
    private $service;
    function __construct(DeliveryService $service)
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
            $pdf = Pdf::loadView('Sales::deliveries.pdf', compact('deliveries'));
            return $pdf->download('deliveries.pdf');
        }

        $data['deliveries'] = $this->service->getAll();
        // dd($this->service->getAll()->first());
        return view("Sales::deliveries.index", $data);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $data['source'] = Delivery::with('source')->findOrFail($request->delivery_id)?->source;
        $data['previousDeliveries'] = Delivery::where('id', $request->delivery_id)
            ->with('deliveryDetails')
            ->get();
        $data['employees'] = Employee::query()->select('id', 'full_name as name')->get();
        //    dd($data);
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
    public function show($id, Request $request)
    {
        $data['delivery'] = $this->service->show($id);
        $data['source'] = Delivery::with('source')->findOrFail($id)?->source;
        $data['previousDeliveries'] = Delivery::where('id', $id)
            ->with('deliveryDetails')
            ->get();

        if ($request->has('export') && $request->get('export') === 'pdf') {
            $html = view('Sales::deliveries.pdf', $data)->render();

            // Set Dompdf options
            $options = new \Dompdf\Options();
            $options->setIsHtml5ParserEnabled(true);
            $options->setIsRemoteEnabled(true);

            $dompdf = new \Dompdf\Dompdf($options);
            $dompdf->loadHtml($html);
            $dompdf->setPaper('A4', 'portrait');
            $dompdf->render();

            return $dompdf->stream('delivery_' . $data['delivery']->id . '.pdf', ['Attachment' => false]);
        }

        return view("Sales::deliveries.show", $data);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Delivery $delivery)
    {
        $data['delivery'] = Delivery::with('source')->find($delivery->id);
        //
        return view("Sales::deliveries.edit", $data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Delivery $delivery)
    {
        $validate = $request->validate([
            'arranged_by' => 'required|integer|exists:employees,id',
            'checked_by' => 'required|integer|exists:employees,id',

            'carton_no' => 'required|string|max:255',

            'carton_no' => 'required|integer|min:1',

        ]);

        $deliveryDetails = $request->validate([
            'product_id.*' => 'required|integer|exists:product_catalogs,id',
            'sales_quantity.*' => 'required|numeric',
            'quantity.*' => 'nullable|numeric',
        ]);

        // foreach ($deliveryDetails['sales_quantity'] as $key => $salesQuantity) {
        //     if ($deliveryDetails['sales_quantity'][$key] != $deliveryDetails['quantity'][$key]) {
        //         return redirect()->back()->withErrors(['quantity.' . $key => 'The sales quantity and quantity of product ' . $key . ' should be same.']);
        //     }
        // }

        $deliveryStockDetails = $request->validate([
            'lot_no.*.*' => 'nullable|string',
            'lots_quantity.*.*' => 'nullable|numeric',
            'serial_no.*.*' => 'nullable|string',
            'expire_date.*.*' => 'nullable|date',
        ]);

        $this->service->update($delivery,  $validate, $deliveryDetails, $deliveryStockDetails);

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

    public function details(Request $request)
    {
        $deliveryId = $request->delivery_id;
        $productId = $request->product_id;

        $deliveryDetails = DeliveryDetail::with('deliveryStocks')
            ->where('delivery_id', $deliveryId)
            ->where('product_id', $productId)
            ->get();

        return view("Sales::deliveries.details", [
            'deliveryDetails' => $deliveryDetails
        ]);
    }
}
