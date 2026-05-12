<?php

namespace Modules\Sales\Controllers;


use App\Http\Controllers\Controller;
use Modules\CRM\Models\Customer\Customer;
use Modules\Sales\Models\Courier;
use Modules\Sales\Models\ShipmentVerify;
use Modules\Sales\Services\ShipmentVerifyService;
use App\Services\SmsService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

use Modules\Sales\Models\ConditionAmountCollect;
use Modules\Sales\Models\SalesOrder;
use Modules\Sales\Models\ShipmentConditionInfo;

class ShipmentVerifyController extends Controller
{

    /**
     * Service variable
     *
     * @var ShipmentVerifyService
     */
    private $service;
    private $smsService;

    function __construct(ShipmentVerifyService $service, SmsService $smsService)
    {
        $this->service = $service;
        $this->smsService = $smsService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $filters = [
            'status' => $request->get('status'),
            'courier_name' => $request->get('courier_name'),
            'courier_id' => $request->get('courier_id'),
            'from' => $request->get('from'),
            'to' => $request->get('to'),
            'customer_id' => $request->get('customer_id'),
            'additional_phone' => $request->get('additional_phone'),
        ];

        $shipmentVerifies = $this->service->getAll(20, $filters);
        $data['shipmentVerifies'] = $shipmentVerifies;
        $data['couriers'] = Courier::all();
        $data['customers'] = Customer::select('id', 'company_name as name')->get();

        // Calculate grand totals
        $grandTotalInvAmt = 0;
        $grandTotalCdlAmt = 0;

        foreach ($shipmentVerifies as $shipmentVerify) {
            $grandTotalInvAmt += $shipmentVerify->source?->source?->net_amount ?? 0;
            $grandTotalCdlAmt += ($shipmentVerify->source?->source?->shipment?->additional_amount ?? 0) + ($shipmentVerify->source?->source->due_amount ?? 0);
        }

        $data['grandTotalInvAmt'] = $grandTotalInvAmt;
        $data['grandTotalCdlAmt'] = $grandTotalCdlAmt;

        return view("Sales::shipment-verifies.index", $data);
    }

    /**
     * Show the form for creating a new resource.
     */
    // public function create()
    // {
    //     return view('shipmentVerifys.create');
    // }

    /**
     * Store a newly created resource in storage.
     */
    // public function store(Request $request)
    // {
    //     $validate = $request->validate([
    //         //validate rules
    //     ]);
    //     $this->service->store($validate);
    //     return redirect()->route('shipmentVerifys.index')->with('success', 'ShipmentVerify created successfully.');
    // }

    /**
     * Display the specified resource.
     */
    public function show($id, Request $request)
    {
        $data['shipmentVerify'] = $this->service->show($id);

        if ($request->has('export') && $request->get('export') === 'pdf') {
            $html = view('Sales::shipment-verifies.pdf', $data)->render();

            // Set Dompdf options
            $options = new \Dompdf\Options();
            $options->setIsHtml5ParserEnabled(true);
            $options->setIsRemoteEnabled(true);

            $dompdf = new \Dompdf\Dompdf($options);
            $dompdf->loadHtml($html);
            $dompdf->setPaper('A4', 'portrait');
            $dompdf->render();

            return $dompdf->stream('courier_challan_' . $data['shipmentVerify']->id . '.pdf', ['Attachment' => false]);
        } 
        return view("Sales::shipment-verifies.show", $data);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ShipmentVerify $shipmentVerify)
    {
        $data['shipmentVerify'] = $shipmentVerify;
        //
        return view("shipmentVerifys.edit", $data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ShipmentVerify $shipmentVerify)
    {
        $validate = $request->validate([
            'service_charge' => 'required|numeric|min:0',
            'courier_id' => 'required|exists:couriers,id',
            'service_type' => 'nullable|string|max:20',
            'delivery_charge' => 'required|numeric|min:1',
            'delivery_type' => 'nullable|string|max:20',
            'other_charge' => 'nullable|numeric|min:0',
            'other_type' => 'nullable|string|max:20',
            'receipt_no' => 'required|string|max:50',
            'cartoon_no' => 'required|numeric|min:1',
            'courier_date' => 'required|date',
            'receive_date' => 'nullable|date', // Receipt date should not be in the future
        ]);

        $files = $request->validate([
            'files' => 'nullable|array|max:5', // Maximum 5 files
            'files.*' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:10240', // Max 10MB per file
        ]);

        $this->service->update($shipmentVerify, $validate, $files);

        // Determine redirect route);
        $redirectRoute = $request->input('redirect_url', route('sales.shipment-verifies.index'));

        if ($request->has('send_sms') && $request->input('send_sms') == 1) {
            $shipmentVerify->refresh();
            $customerContact = $shipmentVerify->customer->contact_for_sms ?? $shipmentVerify->customer->phone;
            $message = "Your shipment with receipt no {$shipmentVerify->receipt_no} has been verified.";

            if (substr($customerContact, 0, 2) === '01') {
                $customerContact = '880' . substr($customerContact, 1);
            }

            try {
                DB::beginTransaction();
                $shipmentVerify->update([
                    'status' => 'verified'
                ]);
                $this->createConditionAmountCollect($shipmentVerify);
                $this->service->makeDummyTransaction($shipmentVerify);
                $sent = $this->smsService->send($customerContact, $message);

                if ($sent) {
                    DB::commit();
                    return redirect($redirectRoute)->with('success', 'ShipmentVerify updated and SMS sent successfully.');
                } else {
                    DB::rollBack();
                    return redirect($redirectRoute)->with('warning', 'ShipmentVerify updated but SMS failed to send.');
                }
            } catch (\Exception $e) {
                DB::rollBack();
                Log::error("Error sending SMS for shipment verify ID {$shipmentVerify->id}: " . $e->getMessage());
                return redirect($redirectRoute)->with('warning', 'ShipmentVerify updated but error occurred while sending SMS.');
            }
        }

        return redirect($redirectRoute)->with('success', 'ShipmentVerify updated successfully.');
        
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ShipmentVerify $shipmentVerify)
    {
        $this->service->delete($shipmentVerify);
        return redirect()->route('shipmentVerifys.index')->with('success', 'ShipmentVerify deleted successfully.');
    }

    /**
     * Send SMS for a specific shipment verification or multiple verifications.
     */
    public function sendSms(Request $request)
    {
        // Check if it's a single ID or multiple IDs
        if ($request->has('shipment_verify_ids') && is_array($request->shipment_verify_ids)) {
            // Handle multiple shipment verifications
            $shipmentVerifyIds = $request->shipment_verify_ids;

            $request->validate([
                'shipment_verify_ids' => 'required|array',
                'shipment_verify_ids.*' => 'required|exists:shipment_verifies,id',
            ]);

            $successCount = 0;
            $errorCount = 0;

            foreach ($shipmentVerifyIds as $id) {
                $shipmentVerify = ShipmentVerify::find($id);

                if (!$shipmentVerify) {
                    $errorCount++;
                    continue;
                }

                try {
                    DB::beginTransaction();
                    $this->createConditionAmountCollect($shipmentVerify);
                    $this->service->makeDummyTransaction($shipmentVerify);

                    $customerContact = $shipmentVerify->customer->contact_for_sms ?? $shipmentVerify->customer->phone;
                    $message = "Your shipment with receipt no {$shipmentVerify->receipt_no} has been verified.";

                    if (substr($customerContact, 0, 2) === '01') {
                        $customerContact = '880' . substr($customerContact, 1);
                    }

                    $sent = $this->smsService->send($customerContact, $message);

                    $shipmentVerify->update([
                        'status' => 'verified'
                    ]);

                    DB::commit();

                    if ($sent) {
                        $successCount++;
                    } else {
                        $errorCount++;
                    }
                } catch (\Exception $e) {
                    DB::rollBack();
                    Log::error("Error sending SMS for shipment verify ID {$shipmentVerify->id}: " . $e->getMessage());
                    $errorCount++;
                }
            }

            $message = "SMS sent successfully to {$successCount} out of " . count($shipmentVerifyIds) . " shipment verifications.";
            if ($errorCount > 0) {
                $message .= " {$errorCount} failed.";
            }

            return response()->json([
                'success' => $successCount > 0,
                'message' => $message,
                'success_count' => $successCount,
                'error_count' => $errorCount
            ]);
        } else {
            // Handle single shipment verification (existing functionality)
            $request->validate([
                'shipment_verify_id' => 'required|exists:shipment_verifies,id',
            ]);

            $shipmentVerify = ShipmentVerify::find($request->shipment_verify_id);

            if (!$shipmentVerify) {
                return response()->json(['success' => false, 'message' => 'Shipment verification not found.'], 404);
            }

            $customerContact = $shipmentVerify->customer->contact_for_sms ?? $shipmentVerify->customer->phone;
            $message = "Your shipment with receipt no {$shipmentVerify->receipt_no} has been verified.";

            if (substr($customerContact, 0, 2) === '01') {
                $customerContact = '880' . substr($customerContact, 1);
            }

            try {
                DB::beginTransaction();
                $this->createConditionAmountCollect($shipmentVerify);
                $this->service->makeDummyTransaction($shipmentVerify);
                $sent = $this->smsService->send($customerContact, $message);

                $shipmentVerify->update([
                    'status' => 'verified'
                ]);
                DB::commit();

                if ($sent) {
                    return response()->json(['success' => true, 'message' => 'SMS sent successfully.']);
                } else {
                    return response()->json(['success' => false, 'message' => 'Failed to send SMS. ']);
                }
            } catch (\Exception $e) {
                DB::rollBack();
                Log::error("Error sending SMS for shipment verify ID {$shipmentVerify->id}: " . $e->getMessage());
                return response()->json(['success' => false, 'message' => 'An error occurred while sending SMS.']);
            }
        }
    }

    private function createConditionAmountCollect(ShipmentVerify $shipmentVerify)
    {
        // Check if source is SalesOrder and it is conditional
        // dd($shipmentVerify, $shipmentVerify->source);
        // source_type is model class name, source_id is id
        // if ($shipmentVerify->source_type === SalesOrder::class) {
        $delivery = $shipmentVerify->source;
        // dd($shipmentVerify, $salesOrder);
        $salesOrder = $delivery->source;
        if ($delivery && $salesOrder) {
            // dd($delivery, $salesOrder);
            // Check for shipment condition info
            // SalesOrder has 'shipment' relationship which is morphOne ShipmentConditionInfo
            $conditionInfo = $delivery->source->shipment;

            if ($conditionInfo && $conditionInfo->condition == 1) {
                ConditionAmountCollect::create([
                    'shipment_verify_id' => $shipmentVerify->id,
                    'sales_order_id' => $salesOrder->id,
                    'customer_id' => $shipmentVerify->customer_id,
                    'courier_id' => $shipmentVerify->courier_id,
                    'invoice_amount' => $salesOrder->net_amount ?? $salesOrder->total_amount ?? 0, // Fallback if net_amount null
                    'condition_amount' => (($conditionInfo->additional_amount ?? 0) + ($salesOrder->due_amount ?? 0)), // Assuming additional_amount holds condition amount
                    'received_amount' => 0,
                    'status' => 'pending',
                ]);
            }
        }
        // }
    }
}