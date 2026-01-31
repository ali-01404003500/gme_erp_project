<?php

namespace Modules\Purchase\Controllers;


use App\Http\Controllers\Controller;
use App\Models\AccessControl\Branch;
use App\Traits\S3FileHandler;
use Modules\Inventory\Models\Product\Settings\ProductType;
use Modules\Inventory\Models\ProductCatalog;
use Modules\Inventory\Models\Settings\Unit;
use Modules\Purchase\Models\Requisition;
use Modules\Purchase\Models\RequisitionReceive;
use Modules\Purchase\Models\RequisitionReceiveBatch;
use Modules\Purchase\Models\RequisitionReceiveDetail;
use Modules\Purchase\Models\RequisitionReceiveSerial;
use Modules\Purchase\Models\Supplier;
use Modules\Inventory\Services\StockService;
use Modules\Purchase\Services\RequisitionService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Modules\Account\Services\Payments\MakePaymentService;
use Modules\CRM\Models\Customer\Customer;

class RequisitionReceiveController extends Controller
{
    use S3FileHandler;

    /**
     * Service variable
     *
     * @var RequisitionService
     */
    private $service;

    /**
     * Service variable
     *
     * @var StockService
     */
    /**
     * Service variable
     *
     * @var MakePaymentService
     */

    private $paymentService;

     
    private $stockService;
    function __construct(RequisitionService $service, StockService $stockService, MakePaymentService $paymentService)
    {
        $this->service = $service;
        $this->paymentService = $paymentService;

        $this->stockService = $stockService;


        $this->middleware('permited')->except(['store', 'getProduct', 'storeSerial', 'storeBatch', 'show', 'getSerials', 'batches']);
        
        // Disable CSRF protection for storeSerial method
        // $this->middleware('CheckSlugPermited:get,put')->only(['update', 'store']);
        // $this->middleware('permitedSlug:purchase.requisitions.receive')->only(['store,getProduct','storeSerial', 'storeBatch','show','getSerials','batches']);
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data['warehouses'] = Branch::query()->get();
        $data['productTypes'] = ProductType::query()->where('status', 1)->get();
        $data['units'] = Unit::all();
        $data['products'] = ProductCatalog::where('status', 'active')->get();
        $data['suppliers'] = Supplier::query()->where('status', 1)->get();
        $data['customers'] = Customer::activeCustomers()->get();

        $data['requisitions'] = $this->service->getAll(); // it is spaling mistake

        return view("Purchase::requisition.index", $data);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create($id)
    {
        $data['warehouses'] = Branch::query()->get();
        $data['productTypes'] = ProductType::query()->where('status', 1)->get();
        $data['units'] = Unit::all();
        $data['products'] = ProductCatalog::where('status', 'active')->get();
        $data['suppliers'] = Supplier::query()->where('status', 1)->get();
        $data['customers'] = Customer::activeCustomers()->get();
        $data['requisition'] = $this->service->show($id);

        return view('Purchase::requisition.receive', $data);
    }

    public function getProduct(Request $request)
    {
        $services = ProductCatalog::query()
            ->where('id', $request->id)
            ->where('status', 'active')
            ->with('product')
            ->get();
        return $services;
    }


    /**
     * Store a newly created resource in storage.
     */
    public function storeSerial(Request $request)
    {
        // dd($request->all());
        RequisitionReceiveSerial::where('requisition_id', $request->requisition_id)->where('product_id', $request->product_id)->delete();


        foreach ($request->serial_no ?? [] as $key => $serial_no) {
            $image = null;
            if ($request->hasFile("image.$key")) {
                $image = $this->uploadFile($request->file("image.$key"), 'Purchase Requisition Serial');
            }
            RequisitionReceiveSerial::create([
                'serial_no' => $serial_no,
                'product_id' => $request->product_id,
                'requisition_id' => $request->requisition_id,
                'dongle_no' => $request->dongle_no[$key],
                'manufacture_date' => $request->manufacture_date[$key],
                'quantity' => $request->quantity[$key],
                'image' => $image

            ]);
        }
        return response()->json(['success' => true, 'message' => 'Serial add successfully.']);
    }

    public function storeBatch(Request $request)
    {
        RequisitionReceiveBatch::where('requisition_id', $request->requisition_id)->where('product_id', $request->product_id)->delete();

        foreach ($request->batch_no ?? [] as $key => $batch_no) {
            RequisitionReceiveBatch::create([
                'batch_no' => $batch_no,
                'product_id' => $request->product_id,
                'requisition_id' => $request->requisition_id,
                'manufacture_no' => $request->manufacture_no[$key],
                'lot_no' => $request->lot_no[$key],
                'expired_date' => $request->expired_date[$key],
                'quantity' => $request->quantity[$key],
            ]);
        }
        return response()->json(['success' => true, 'message' => 'Batch add successfully.']);
    }

    public function storeProductStock(Request $request)
    {
        $stock = $this->stockService->store([
            'product_id' => $request->product_id,
            'source_type' => RequisitionReceive::class,
            'source_id' => $request->requisition_id,
            'stock_type' => 'in',
            'in_qty' => $request->quantity,
            'date' => Requisition::find($request->requisition_id)->invoice_date,
        ]);
        return $stock;
    }
    public function store(Request $request)
    {
        try {
            $productIds = $request->product_ids ?? [];
            $requisitionId = $request->requisition_id;

            $serialsRequired = [];
            $batchesRequired = [];

            DB::beginTransaction();

            $services = ProductCatalog::query()
                ->whereIn('id', $productIds)
                ->where('status', 'active')
                ->with('product')
                ->get();

            // Identify products that require serials or batches
            foreach ($services as $service) {
                if ($service->is_expire_date == 'yes') {
                    $batchesRequired[] = $service->id;
                }
                if ($service->is_serial == 'yes') {
                    $serialsRequired[] = $service->id;
                }
            }

            // Fetch required serials and batches
            $batches = RequisitionReceiveBatch::where('requisition_id', $requisitionId)
                ->whereIn('product_id', $batchesRequired)
                ->get()
                ->groupBy('product_id');

            $serials = RequisitionReceiveSerial::where('requisition_id', $requisitionId)
                ->whereIn('product_id', $serialsRequired)
                ->get()
                ->groupBy('product_id');
            $requisition = Requisition::find($requisitionId);


            // dd(    $requisition);
            // Check if all required serials and batches are available
            foreach ($batchesRequired as $productId) {
                if (!isset($batches[$productId]) || $batches[$productId]->count() == 0) {
                    return redirect()->route('purchase.requisitions.receive', $requisitionId)
                        ->with('error', 'Received Product Batch not created for all required products.');
                }
            }

            foreach ($serialsRequired as $productId) {
                if (!isset($serials[$productId]) || $serials[$productId]->count() == 0) {
                    return redirect()->route('purchase.requisitions.receive', $requisitionId)
                        ->with('error', 'Received Product Serial not created for all required products.');
                }
            }

            // Create Requisition Receive record
            $receive = RequisitionReceive::create([
                'requisition_id' => $requisitionId,
                'purchase_invoice' => $request->purchase_invoice,
            ]);

            // Create Requisition Receive Detail records
            foreach ($productIds as $key => $productId) {
                RequisitionReceiveDetail::create([
                    'product_id' => $productId,
                    'requisition_receive_id' => $receive->id,
                    'requisition_id' => $requisitionId,
                    'approved_quantity' => $request->approved_quantity[$key],
                    'received_quantity' => $request->receive_quantity[$key],
                ]);
            }

            // Insert product stock
            foreach ($batches as $productBatches) {
                foreach ($productBatches as $batch) {
                    $this->stockService->store([
                        'product_id' => $batch->product_id,
                        'source_type' => RequisitionReceiveBatch::class,
                        'source_id' => $batch->id,
                        'branch_id' => $batch->requisition->branch_id,
                        'stock_type' => 'in',
                        'in_qty' => $batch->quantity,
                        'lot_no' => $batch->lot_no,
                        'date' => $requisition->invoice_date,
                    ]);
                }
            }

            foreach ($serials as $productSerials) {
                foreach ($productSerials as $serial) {
                    $this->stockService->store([
                        'product_id' => $serial->product_id,
                        'source_type' => RequisitionReceiveSerial::class,
                        'source_id' => $serial->id,
                        'branch_id' => $serial->requisition->branch_id,
                        'stock_type' => 'in',
                        'in_qty' => $serial->quantity,
                        'serial_no' => $serial->serial_no,
                        'date' => $requisition->invoice_date,
                    ]);
                }
            }

            // Update Requisition status
            $requisition->update(['status' => 4]);

            $this->service->makeDummyTransaction($requisition);

            $paymentData = [
                'payments_total_amount' => $requisition->net_amount ?? 0,
                'payments_advance_amount' => $requisition->paymentDetails()->sum('amount') - $requisition->net_amount,
                'payment_type' => Supplier::class,
                'payment_from' => $requisition->supplier_id,
            ];

            $this->paymentService->storeForPurchases($paymentData, $requisition->paymentDetails, $requisition);


            DB::commit();
            return redirect()->route('purchase.requisitions.index')
                ->with('success', 'Requisition Product Received successfully.');
        } catch (\Throwable $th) {
            DB::rollBack();
            dd($th);
            return back()->withErrors(['error' => $th->getMessage()]);
        }
    }


    
    public function transactions(Request $request, $requisitionId)
    {
        try {
            $requisition = Requisition::findOrFail($requisitionId);
            $transactions = $requisition->transactions;

            return response()->json([
                'success' => true,
                'data' => $transactions
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }





    public function getRequisitionNumber()
    {
        $count_purchase_number = Requisition::count();
        if ($count_purchase_number == 0) {

            return 'RN-'
                . date('y')
                . '-'
                . str_pad($count_purchase_number + 1, 4, "0", STR_PAD_LEFT);
        } else {
            $last_job_id = Requisition::orderBy('id', 'desc')->pluck('id')->first();

            return 'RN-'
                . date('y')
                . '-'
                . str_pad($last_job_id + 1, 4, "0", STR_PAD_LEFT);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id, Request $request)
    {
        $receive = RequisitionReceive::where('requisition_id', $id)->first();
        $data = [
            'receive' => $receive,
        ];
        return view('Purchase::requisition.receive-show', $data);
    }

    public function getSerials($requisition_id, Request $request)
    {
        $serials = RequisitionReceiveSerial::where('requisition_id', $requisition_id)
            ->where('product_id', $request->product_id)
            ->get();
        return response()->json(['serials' => $serials]);
    }

    public function batches($requisition_id, Request $request)
    {
        $batches = RequisitionReceiveBatch::where('requisition_id', $requisition_id)
            ->where('product_id', $request->product_id)
            ->get();
        return response()->json(['batches' => $batches]);
    }



    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $data['requisition'] = Requisition::with(['requisitionDetails.product'])->find($id);
        $data['warehouses'] = Branch::query()->get();
        $data['productTypes'] = ProductType::query()->where('status', 1)->get();
        $data['units'] = Unit::all();
        $data['products'] = ProductCatalog::where('status', 'active')->get();
        $data['suppliers'] = Supplier::query()->where('status', 1)->get();
        $data['customers'] = Customer::activeCustomers()->get();
        return view("Purchase::requisition.receive", $data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Requisition $requisition)
    {
        $validate = $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'supplier_id' => 'required',
            'warehouse_id' => 'required|exists:warehouses,id',
            'invoice_date' => 'nullable|date',
            'description' => 'nullable|string',
            'total_amount' => 'nullable|numeric|min:0',
            'discount' => 'nullable|numeric|min:0',
            'net_amount' => 'nullable|numeric|min:0',
        ]);
        $productValidate = $request->validate([
            'product_ids' => 'required|array',
            'product_ids.*' => 'required|exists:product_catalogs,id',
            'price' => 'nullable|array',
            'price.*' => 'nullable|min:0',
            'sales_price' => 'nullable|array',
            'sales_price.*' => 'nullable|min:0',
            'quantity' => 'nullable|array',
            'quantity.*' => 'nullable|min:0',
            'amount' => 'nullable|array',
            'amount.*' => 'nullable|min:0',
        ]);
        $this->service->update($requisition, $validate, $productValidate);

        return redirect()->route('purchase.requisitions.index')->with('success', 'Requisition updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Requisition $requisition)
    {
        $this->service->delete($requisition);
        return redirect()->route('purchase.requisitions.index')->with('success', 'Requisition deleted successfully.');
    }
}
