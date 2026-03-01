<?php

namespace Modules\Purchase\Controllers;

use App\Http\Controllers\Controller;
use App\Models\AccessControl\CompanyInfo;
use Modules\Purchase\Models\PurchaseReturn;
use Modules\Purchase\Models\RequisitionDetail;
use Modules\Purchase\Models\RequisitionReceive;
use Modules\Purchase\Models\Supplier;
use Modules\Purchase\Services\PurchaseReturnService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Dompdf\Dompdf;
use Dompdf\Options;

class PurchaseReturnController extends Controller
{
    private $service;

    function __construct(PurchaseReturnService $service)
    {
        $this->service = $service;
    }

    public function index(Request $request)
    {
        $data['purchaseReturns'] = $this->service->getAll();
        $data['suppliers'] = Supplier::query()->where('status', 1)->get();
        $data['company_info'] = CompanyInfo::first();

        if ($request->export == "pdf") {
            set_time_limit(1000);
            $html = view('Purchase::returns.indexView', $data)->render();

            $options = new Options();
            $options->setIsHtml5ParserEnabled(true);
            $options->setIsRemoteEnabled(true);

            $dompdf = new Dompdf($options);
            $dompdf->loadHtml($html);
            $dompdf->setPaper('A4', 'portrait');
            $dompdf->render();

            return $dompdf->stream('purchase_returns_' . date('Y-m-d') . '.pdf', ['Attachment' => false]);
        }
        return view("Purchase::returns.index", $data);
    }

    public function create(Request $request)
    {
        $data['invoices'] = RequisitionReceive::with('receiveDetails.requitions')->get();

        $data['products'] = RequisitionDetail::where('requisition_id', $request->invoice_id)
            ->with(['requisition.supplier', 'requisition.receive'])
            ->get();

        $data['receive'] = optional(optional($data['products']->first())->requisition)->receive;
        return view('Purchase::returns.create', $data);
    }

    public function store(Request $request)
    {
        $license_no = $this->getLicenseNumber($request->input('supplier_id'));

        if ($request->input('checks') == null) {
            return redirect()->back()->with('error', 'Please select atleast one product');
        }

        $validate = $request->validate([
            'requisition_id' => 'required|exists:requisitions,id',
            'requisition_receive_id' => 'required|exists:requisition_receives,id',
            'supplier_id' => 'required|exists:suppliers,id',
            'main_inv_discount' => 'required|numeric|min:0',
            'discount' => 'required|numeric|min:0',
            'total_amount' => 'required|numeric|min:0',
            'net_amount' => 'required|numeric|min:0',
            'remarks' => 'nullable|string|max:255',
            'return_date' => 'required|date',
            'reference_invoice' => 'nullable|string|max:255',
        ]);

        $products = $request->validate([
            'product_ids' => 'required|array',
            'product_ids.*' => 'required|exists:product_catalogs,id',
            'recived_quantity' => 'required|array',
            'recived_quantity.*' => 'required|numeric|min:0',
            'quantity' => 'required|array',
            'quantity.*' => 'required|numeric|min:0',
            'price' => 'required|array',
            'price.*' => 'required|numeric',
            'amount' => 'required|array',
            'amount.*' => 'required|numeric',
            'checks' => 'required|array',
            'checks.*' => 'required|boolean',
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

        $validate['invoice_no'] = $license_no;

        $this->service->store($validate, $products, $payments);
        return redirect()->route('purchase.returns.index')->with('success', 'PurchaseReturn created successfully.');
    }

    public function getLicenseNumber($supplier_id)
    {
        $today = date('Y-m-d');

        $authUser = auth()->user()->id;
        $authUserBranch = auth()->user()->branch_id;
        $authUserBranchType = auth()->user()->branch->branch_type_id;

        $licensesToday = PurchaseReturn::whereDate(DB::raw('DATE(created_at)'), $today)
            ->where('created_by', $authUser)
            ->count();

        $licenseNumber = sprintf(
            'SCT-%02d-SC-%02d-%s-USR-%06d-PR-%06d',
            $authUserBranch,
            $authUserBranchType,
            date('Ymd'),
            $authUser,
            $licensesToday + 1
        );

        return $licenseNumber;
    }

    public function show($id)
    {
        $data['purchaseReturn'] = $this->service->show($id);
        return view("Purchase::returns.show", $data);
    }

    public function print($id)
    {
        $data['purchaseReturn'] = $this->service->show($id);
        $data['company_info'] = CompanyInfo::first();
        return view("Purchase::returns.print", $data);
    }

    public function edit($id)
    {
        $purchaseReturn = $this->service->show($id);
        $data['purchaseReturn'] = $purchaseReturn;
        return view("Purchase::returns.edit", $data);
    }

    public function update(Request $request, $id)
    {
        $purchaseReturn = $this->service->show($id);

        $validate = $request->validate([
            'requisition_id' => 'required|exists:requisitions,id',
            'requisition_receive_id' => 'required|exists:requisition_receives,id',
            'supplier_id' => 'required|exists:suppliers,id',
            'main_inv_discount' => 'required|numeric|min:0',
            'discount' => 'required|numeric|min:0',
            'total_amount' => 'required|numeric|min:0',
            'net_amount' => 'required|numeric|min:0',
            'remarks' => 'nullable|string|max:255',
            'return_date' => 'required|date',
            'reference_invoice' => 'nullable|string|max:255',
        ]);

        $products = $request->validate([
            'product_ids' => 'required|array',
            'product_ids.*' => 'required|exists:product_catalogs,id',
            'recived_quantity' => 'required|array',
            'recived_quantity.*' => 'required|numeric|min:0',
            'quantity' => 'required|array',
            'quantity.*' => 'required|numeric|min:0',
            'price' => 'required|array',
            'price.*' => 'required|numeric|min:0',
            'amount' => 'required|array',
            'amount.*' => 'required|numeric|min:0',
            'checks' => 'required|array',
            'checks.*' => 'required|boolean',
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

        $this->service->update($purchaseReturn, $validate, $products, $payments);

        return redirect()->route('purchase.returns.index')->with('success', 'PurchaseReturn updated successfully.');
    }

    public function destroy($id)
    {
        $purchaseReturn = $this->service->show($id);
        $this->service->delete($purchaseReturn);
        return redirect()->route('purchase.returns.index')->with('success', 'PurchaseReturn deleted successfully.');
    }
}