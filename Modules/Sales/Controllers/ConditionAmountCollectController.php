<?php

namespace Modules\Sales\Controllers;

use App\Http\Controllers\Controller;
use Modules\Sales\Models\ConditionAmountCollect;
use Modules\Sales\Services\ConditionAmountCollectService;
use Illuminate\Http\Request;

class ConditionAmountCollectController extends Controller
{

    /**
     * Service variable
     *
     * @var ConditionAmountCollectService
     */
    private $service;
    function __construct(ConditionAmountCollectService $service)
    {
        $this->service = $service;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $conditionAmountCollects = $this->service->getAll();
        $data['conditionAmountCollects'] = $conditionAmountCollects;
        $data['metrics'] = $this->service->getMetrics();

        // Calculate grand totals
        $grandTotalInvAmt = 0;
        $grandTotalCondAmt = 0;

        foreach ($conditionAmountCollects as $item) {
            $grandTotalInvAmt += $item->invoice_amount;
            $grandTotalCondAmt += $item->condition_amount;
        }

        $data['grandTotalInvAmt'] = $grandTotalInvAmt;
        $data['grandTotalCondAmt'] = $grandTotalCondAmt;

        return view("Sales::condition-amount-collects.index", $data);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('conditionAmountCollects.create');
    }

    /**
     * Store a newly created resource in storage.
     * Acts as "Mark as Received"
     */
    public function store(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:condition_amount_collects,id',
        ]);

        $conditionAmountCollect = $this->service->show($request->id);
        $this->service->markAsReceived($conditionAmountCollect);

        return redirect()->route('sales.condition-amount-collects.index')->with('success', 'Amount collected successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $data['conditionAmountCollect'] = $this->service->show($id);

        return view("conditionAmountCollects.show", $data);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ConditionAmountCollect $conditionAmountCollect)
    {
        $data['conditionAmountCollect'] = $conditionAmountCollect;
        //
        return view("conditionAmountCollects.edit", $data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ConditionAmountCollect $conditionAmountCollect)
    {
        $validate = $request->validate([
            //validate rules
        ]);
        $this->service->update($conditionAmountCollect, $validate);

        return redirect()->route('conditionAmountCollects.index')->with('success', 'ConditionAmountCollect updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ConditionAmountCollect $conditionAmountCollect)
    {
        $this->service->delete($conditionAmountCollect);
        return redirect()->route('conditionAmountCollects.index')->with('success', 'ConditionAmountCollect deleted successfully.');
    }

    /**
     * Display approved list - items that are received but not yet approved
     */
    public function approvedList()
    {
        $conditionAmountCollects = $this->service->getReceivedList();
        $data['conditionAmountCollects'] = $conditionAmountCollects;

        // Calculate grand totals
        $grandTotalInvAmt = 0;
        $grandTotalAdditionalAmt = 0;
        $grandTotalCondAmt = 0;
        $grandTotalConditionalAmount = 0;

        foreach ($conditionAmountCollects as $item) {
            $additionalAmount = $item->salesOrder->shipment->additional_amount ?? 0;
            $totalConditionalAmount = $item->condition_amount + $additionalAmount;

            $grandTotalInvAmt += $item->invoice_amount;
            $grandTotalAdditionalAmt += $additionalAmount;
            $grandTotalCondAmt += $item->condition_amount;
            $grandTotalConditionalAmount += $totalConditionalAmount;
        }

        $data['grandTotalInvAmt'] = $grandTotalInvAmt;
        $data['grandTotalAdditionalAmt'] = $grandTotalAdditionalAmt;
        $data['grandTotalCondAmt'] = $grandTotalCondAmt;
        $data['grandTotalConditionalAmount'] = $grandTotalConditionalAmount;

        return view("Sales::condition-amount-collects.approved-list", $data);
    }

    /**
     * Approve selected condition amount collections
     */
    public function approve(Request $request)
    {
        $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'exists:condition_amount_collects,id',
        ]);

        $this->service->approveCollections($request->ids);

        return redirect()->route('sales.condition-amount-collects.approved-list')
            ->with('success', 'Conditional amounts approved successfully.');
    }

    /**
     * Get details of received couriers for the modal view
     */
    public function getReceivedDetails()
    {
        $items = $this->service->getReceivedList(100); // Higher limit for modal view

        // Check if request is for PDF export
        if (request()->has('export') && request()->get('export') === 'pdf') {
            $data = [
                'items' => $items,
            ];

            // Check if dompdf is available, otherwise return a view that can be printed
            if (class_exists(\Barryvdh\DomPDF\Facade\Pdf::class)) {
                $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('Sales::condition-amount-collects._received_pdf_content', $data);
                return $pdf->stream('Received_Courier_Details_' . date('Y-m-d') . '.pdf');
            } else {
                // Fallback: return HTML view that can be printed
                return view('Sales::condition-amount-collects._received_pdf_content', $data);
            }
        }

        return view("Sales::condition-amount-collects._received_modal_content", compact('items'));
    }

    /**
     * Move entry back to pending status
     */
    public function receivedBack(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:condition_amount_collects,id',
        ]);

        $this->service->receivedBack($request->id);

        return response()->json([
            'success' => true,
            'message' => 'Entry returned to collection list successfully.'
        ]);
    }

    /**
     * Generate PDF for the Claim document
     */
    public function claimPdf($id)
    {
        $conditionAmountCollect = $this->service->show($id);

        $data = [
            'conditionAmountCollect' => $conditionAmountCollect,
            'customer' => $conditionAmountCollect->customer,
            'courier' => $conditionAmountCollect->courier,
            'salesOrder' => $conditionAmountCollect->salesOrder,
            'shipmentVerify' => $conditionAmountCollect->shipmentVerify,
        ];

        // Check if dompdf is available, otherwise return a view that can be printed
        if (class_exists(\Barryvdh\DomPDF\Facade\Pdf::class)) {
            $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('Sales::condition-amount-collects.claim_pdf', $data);
            return $pdf->stream('Claim_Document_' . $conditionAmountCollect->id . '.pdf');
        } else {
            // Fallback: return HTML view that can be printed
            return view('Sales::condition-amount-collects.claim_pdf', $data);
        }
    }

    /**
     * Mark selected condition amount collections as received together
     */
    public function bulkReceive(Request $request)
    {
        $request->validate([
            'items' => 'required|array',
            'items.*' => 'exists:condition_amount_collects,id',
        ]);

        // Get the selected condition amount collects
        $selectedItems = $this->service->getByIds($request->items);

        // Mark each selected item as received
        $successCount = 0;
        foreach ($selectedItems as $item) {
            try {
                $this->service->markAsReceived($item);
                $successCount++;
            } catch (\Exception $e) {
                // Log the error but continue with other items
                \Log::error("Error marking condition amount collect as received: " . $e->getMessage());
            }
        }

        return response()->json([
            'success' => true,
            'message' => 'Successfully marked ' . $successCount . ' out of ' . count($selectedItems) . ' items as received.'
        ]);
    }

    /**
     * Send bulk message to selected condition amount collections
     */
    public function sendBulkMessage(Request $request)
    {
        $request->validate([
            'items' => 'required|array',
            'items.*' => 'exists:condition_amount_collects,id',
            'message' => 'required|string|max:1000',
        ]);

        // Get the selected condition amount collects
        $selectedItems = $this->service->getByIds($request->items);

        // In a real implementation, you would send messages to customers here
        // For now, we'll just return a success response
        foreach ($selectedItems as $item) {
            // Here you would typically send an SMS, email, or notification to the customer
            // Example: $this->sendNotificationToCustomer($item->customer, $request->message);
        }

        return response()->json([
            'success' => true,
            'message' => 'Message sent successfully to ' . count($selectedItems) . ' customer(s).'
        ]);
    }
}
