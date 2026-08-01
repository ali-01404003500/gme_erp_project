<?php

namespace Modules\Purchase\Controllers;


use App\Http\Controllers\Controller;
use App\Models\AccessControl\Branch;
use App\Models\AccessControl\CompanyInfo;
use App\Services\AutocompleteService;
use Modules\Inventory\Models\Product\Settings\ProductType;
use Modules\Inventory\Models\ProductCatalog;
use Modules\Inventory\Models\Settings\Unit;
use Modules\Purchase\Models\Requisition;
use Modules\Purchase\Models\Supplier;
use App\Services\Notifications\GeneralNotificationService;
use Modules\Purchase\Services\RequisitionService;
use Illuminate\Http\Request;
use Dompdf\Dompdf;
use Dompdf\Options;
use Illuminate\Support\Facades\DB;
use Modules\Account\Models\Payments\MakePayment;
use Modules\Account\Models\Supplier as ModelsSupplier;
use Modules\CRM\Models\Customer\Customer;

class RequisitionController extends Controller
{

    /**
     * Service variable
     *
     * @var RequisitionService
     */
    private $service;

    /**
     * GeneralNotificationService variable
     *
     * @var GeneralNotificationService
     */
    private $generalNotificationService;
    function __construct(RequisitionService $service, GeneralNotificationService $generalNotificationService)
    {
        $this->service = $service;
        $this->generalNotificationService = $generalNotificationService;
        $this->middleware('permited')->except(['getProduct', 'approve', 'approve', 'approveStore', 'getRequisionNumber', 'getSerials', 'batches', 'productAutocomplete','supplierAutocomplete','customerAutocomplete']);
 
        // $this->middleware('CheckSlugPermited:get,put')->only(['update', 'store']);
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $data['warehouses'] = Branch::query()->get();
        $data['productTypes'] = ProductType::query()->where('status', 1)->get();
        $data['units'] = Unit::all();
        $data['suppliers'] = Supplier::find($request->supplier_id) ?? null;
        $data['customers'] = Customer::find($request->customer_id) ?? null;

        $data['requisitions'] = $this->service->getAll();
        $data['company_info'] = CompanyInfo::first();

        if ($request->export == "pdf") {
            set_time_limit(1000);
            $html = view('Purchase::requisition.indexView', $data)->render();

            // Set Dompdf options
            $options = new Options();
            $options->setIsHtml5ParserEnabled(true);
            $options->setIsRemoteEnabled(true);

            $dompdf = new Dompdf($options);
            $dompdf->loadHtml($html);
            $dompdf->setPaper('A4', 'portrait');
            $dompdf->render();

            return $dompdf->stream('stock_list_' . date('Y-m-d') . '.pdf', ['Attachment' => false]);
        }

        return view("Purchase::requisition.index", $data);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $data['warehouses'] = Branch::query()->get();
        $data['productTypes'] = ProductType::query()->where('status', 1)->get();
        $data['units'] = Unit::all(); 
        return view('Purchase::requisition.create', $data);
    }

    public function getProduct(Request $request)
    {
        $services = ProductCatalog::query()
            ->where('id', $request->id)
            // ->where('status', 'active')
            ->with('product')
            ->get();
        return $services;

    }
    public function approve($id)
    {
        $data['warehouses'] = Branch::query()->get();
        $data['productTypes'] = ProductType::query()->where('status', 1)->get();
        $data['units'] = Unit::all();
        $data['products'] = ProductCatalog::where('status', 'active')->get();
        $data['suppliers'] = Supplier::query()->where('status', 1)->get();
        $data['customers'] = Customer::activeCustomers()->get();
        $data['requisition'] = $this->service->show($id);

        return view("Purchase::requisition.approve", $data);
    }
    public function approveStore($id, Request $request)
    {
        // dd($request->all());
        if ($request->status == 1) {
            try {
                $requisition = Requisition::query()->findOrFail($id);
                $validate = $request->validate([
                    'customer_id' => 'nullable|exists:customers,id',
                    'supplier_id' => 'nullable|exists:suppliers,id',  // assuming there is a suppliers table
                    'branch_id' => 'required|exists:branches,id',
                    'invoice_date' => 'nullable|date',
                    'description' => 'nullable|string',
                    'total_amount' => 'nullable|numeric|min:0',
                    'discount' => 'nullable|numeric|min:0',
                    'net_amount' => 'nullable|numeric|min:0',
                    'status' => 'nullable|string', // it's better to specify the type
                ]);

                $validate['approved_by'] = auth()->user()->id;

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
                $this->service->approve($requisition, $validate, $productValidate);

                $this->generalNotificationService->store([
                    'title' => 'Requisition Approved & Receive now',
                    'description' => 'Requisition Approved And Request For Receive',
                    'action' => $this->generalNotificationService->actionBuilder(RequisitionReceiveController::class, 'create', [$id]),
                ], $this->generalNotificationService->getPermittedUsers('purchase.requisitions.receive'));

                return redirect()->route('purchase.requisitions.index')->with('success', 'Requisition Approved successfully.');
            } catch (\Exception $e) {
                return redirect()->back()->with('error', $e->getMessage());
            }
        } else {
            try {
                $requisition = Requisition::query()->findOrFail($id);
                $validate = $request->validate([
                    'customer_id' => 'nullable|exists:customers,id',
                    'supplier_id' => 'nullable|exists:suppliers,id',
                    'branch_id' => 'required|exists:branches,id',
                    'invoice_date' => 'nullable|date',
                    'description' => 'nullable|string',
                    'total_amount' => 'nullable|numeric|min:0',
                    'discount' => 'nullable|numeric|min:0',
                    'net_amount' => 'nullable|numeric|min:0',
                    'status' => 'nullable',
                ]);
                $validate['approved_by'] = auth()->user()->id;

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
                $this->service->approve($requisition, $validate, $productValidate);

                return redirect()->route('purchase.requisitions.index')->with('danger', 'Requisition Rejected successfully.');
            } catch (\Exception $e) {
                return redirect()->back()->with('error', $e->getMessage());
            }
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // dd($request->all());
        $requisition_no = $this->getRequisitionNumber();

        $validate = $request->validate([
            'customer_id' => 'nullable|exists:customers,id',
            'supplier_id' => 'nullable',
            'branch_id' => 'required|exists:branches,id',
            'invoice_date' => 'nullable|date',
            'description' => 'nullable|string',
            'total_amount' => 'nullable|numeric|min:0',
            'discount' => 'nullable|numeric|min:0',
            'net_amount' => 'nullable|numeric|min:0',
            'file_uploads' => 'nullable|array|min:1',
            'file_uploads.*' => 'nullable|string',
        ]);
        $productValidate = $request->validate([
            'product_ids' => 'nullable|array|min:1',
            'product_ids.*' => 'required|exists:product_catalogs,id',
            'price' => 'nullable|array|min:1',
            'price.*' => 'required|min:0',
            'sales_price' => 'nullable|array',
            'sales_price.*' => 'nullable|min:0',
            'quantity' => 'nullable|array|min:1',
            'quantity.*' => 'required|min:0',
            'amount' => 'nullable|array|min:1',
            'amount.*' => 'required|min:0',
        ]);

        $payments = $request->validate([
            'payments_pay_mode' => 'nullable|array',
            'payments_pay_mode.*' => 'required|in:Cash,Cheque,Online Deposit,bKash,Nagad,Rocket,Card,EMI,Card Payment,AIT,Waiver,Waiver Bad Debt',
            'payments_bank_id' => 'nullable|array',
            'payments_bank_id.*' => 'nullable|integer|exists:bank_accounts,id',
            'payments_transaction_id' => 'nullable|array',
            'payments_transaction_id.*' => 'nullable|string',
            'payments_date' => 'nullable|array',
            'payments_date.*' => 'required|date',
            'payments_amount' => 'nullable|array',
            'payments_amount.*' => 'nullable|numeric|min:0',
            'payments_attachments' => 'nullable|array',
            'payments_attachments.*' => 'nullable|string',
            'payments_verified' => 'nullable|array',
            'payments_verified.*' => 'nullable|in:0,1',
            'payments_remark' => 'nullable|array',
            'payments_remark.*' => 'nullable|string',
        ]);


        // Add requisition_no to the validation data
        $validate['requisition_no'] = $requisition_no;

        $result = $this->service->store($validate, $productValidate, $payments);

        $this->generalNotificationService->store([
            'title' => 'New Requisition',
            'description' => 'New Requisition Added needed approval',
            'action' => $this->generalNotificationService->actionBuilder(RequisitionController::class, 'approve', [$result['requisition']->id]),
        ], $this->generalNotificationService->getPermittedUsers('purchase.requisitions.approve'));
        return redirect()->route('purchase.requisitions.edit', $result['requisition']->id)->with('success', 'Requisition created successfully.');
    }


    public function getRequisitionNumber()
    {
        $today = date('Y-m-d');

        $authUser = auth()->user()->id;
        $authUserBranch = auth()->user()->branch_id;
        $authUserBranchType = auth()->user()->branch->branch_type_id;

        // Count today's purchase orders created by this user
        $todayOrders = Requisition::whereDate(DB::raw('DATE(created_at)'), $today)
            ->where('created_by', $authUser)
            ->count();

        // Generate PO number in required format
        $poNumber = sprintf(
            'SCT-%02d-SC-%02d-%s-USR-%06d-PP-%05d',
            $authUserBranch,        // Branch ID (2 digits, padded)
            $authUserBranchType,    // Branch Type (2 digits, padded)
            date('Ymd'),            // YYYYMMDD
            $authUser,              // User ID (6 digits, padded)
            $todayOrders + 1        // Count of today’s entries (5 digits, padded)
        );

        return $poNumber;
    }

    /**
     * Display the specified resource.
     */
    // public function show( $id)
    // {
    //     $data['requisition'] = $this->service->show($id);

    //     return view("Purchase::requisition.show", $data);
    // }


    public function show($id, Request $request)
    {
        $requisition = $this->service->show($id);

        // dd($requisition->receiveSerials);

        $data = [
            'requisition' => $requisition,
        ];
        $data['company_info'] = CompanyInfo::first();

        if ($request->export == "pdf") {
            set_time_limit(1000);
            $html = view('Purchase::requisition.view', $data)->render();

            // Set Dompdf options
            $options = new Options();
            $options->setIsHtml5ParserEnabled(true);
            $options->setIsRemoteEnabled(true);

            $dompdf = new Dompdf($options);
            $dompdf->loadHtml($html);
            $dompdf->setPaper('A4', 'portrait');
            $dompdf->render();

            return $dompdf->stream('requisition_' . $data['requisition']->company_name . '.pdf', ['Attachment' => false]);
        }

        return view("Purchase::requisition.show", $data);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Requisition $requisition)
    {
        $data['requisition'] = $requisition;
        $data['warehouses'] = Branch::query()->get();
        $data['productTypes'] = ProductType::query()->where('status', 1)->get();
        $data['units'] = Unit::all(); 
        return view("Purchase::requisition.edit", $data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Requisition $requisition)
    {
        $validate = $request->validate([
            'customer_id' => 'nullable|exists:customers,id',
            'supplier_id' => 'nullable',
            'branch_id' => 'required|exists:branches,id',
            'invoice_date' => 'nullable|date',
            'description' => 'nullable|string',
            'total_amount' => 'nullable|numeric|min:0',
            'discount' => 'nullable|numeric|min:0',
            'net_amount' => 'nullable|numeric|min:0',
            'file_uploads' => 'nullable|array|min:1',
            'file_uploads.*' => 'nullable|string',
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
        $payments = $request->validate([
            'payments_pay_mode' => 'nullable|array',
            'payments_pay_mode.*' => 'required|in:Cash,Cheque,Online Deposit,bKash,Nagad,Rocket,Card,EMI,Card Payment,AIT,Waiver,Waiver Bad Debt',
            'payments_bank_id' => 'nullable|array',
            'payments_bank_id.*' => 'nullable|integer|exists:bank_accounts,id',
            'payments_transaction_id' => 'nullable|array',
            'payments_transaction_id.*' => 'nullable|string',
            'payments_date' => 'nullable|array',
            'payments_date.*' => 'required|date',
            'payments_amount' => 'nullable|array',
            'payments_amount.*' => 'nullable|numeric|min:0',
            'payments_attachments' => 'nullable|array',
            'payments_attachments.*' => 'nullable|string',
            'payments_verified' => 'nullable|array',
            'payments_verified.*' => 'nullable|in:0,1',
            'payments_remark' => 'nullable|array',
            'payments_remark.*' => 'nullable|string',
        ]);
        $this->service->update($requisition, $validate, $productValidate, $payments);

        return redirect()->route('purchase.requisitions.edit', $requisition->id)->with('success', 'Requisition updated successfully.')->with('success', 'Requisition updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Requisition $requisition)
    {
        $this->service->delete($requisition);
        return redirect()->route('purchase.requisitions.index')->with('success', 'Requisition deleted successfully.');
    }


    public function customerAutocomplete(Request $request, AutocompleteService $autocompleteService)
    { 
        //search( string $model,  array $searchColumns, string $searchValue,  array $displayColumns = ['id', 'name'], int $limit = 10,  array $extraConditions = []
  
        $data = $autocompleteService->customerSearch(
            Customer::class,
            ['company_name','address','phone'],
            $request->search,
            ['id', 'company_name','company_place_id', 'phone', 'customer_type', 'address'],
            30
        ); 

        
        return response()->json($data);
    }

    public function productAutocomplete(Request $request, AutocompleteService $autocompleteService)
    {  
        //search( string $model,  array $searchColumns, string $searchValue,  array $displayColumns = ['id', 'name'], int $limit = 10,  array $extraConditions = []
        $data = $autocompleteService->productSearch(
            ProductCatalog::class,
            ['name','model'],
            $request->search,
            ['id', 'name','model','product_brand_id'],
            30
        ); 
        return response()->json($data);
    }
    public function supplierAutocomplete(Request $request, AutocompleteService $autocompleteService)
    {  
        //search( string $model,  array $searchColumns, string $searchValue,  array $displayColumns = ['id', 'name'], int $limit = 10,  array $extraConditions = []
        $data = $autocompleteService->search(
            Supplier::class,
            ['company_name'],

            $request->search,
            ['id', 'company_name'],
            20,
            ['status' => '1']
        ); 
        return response()->json($data);
    }
}
