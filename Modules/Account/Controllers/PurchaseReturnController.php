<?php

namespace Modules\Account\Controllers;

use App\Models\Company;
use App\Traits\CheckPermission;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Modules\Account\Models\Account;
use Modules\Account\Models\Product;
use Modules\Account\Models\Purchase;
use Modules\Account\Models\PurchaseExchangeDetail;
use Modules\Account\Models\PurchaseReturn;
use Modules\Account\Models\PurchaseReturnDetail;
use Modules\Account\Models\StockSummary;
use Modules\Account\Models\Supplier;
use Modules\Account\Services\AccPurchaseReturnService;
use Modules\Account\Services\InvoiceNumberService;
use Modules\Account\Services\StockService;

class PurchaseReturnController extends Controller
{
    



    private $transactionService;
    private $service;
    public $stockService;










    /*
     |--------------------------------------------------------------------------
     | CONSTRUCTOR METHOD
     |--------------------------------------------------------------------------
    */
    public function __construct()
    {
        $this->invoiceNumberService     = new InvoiceNumberService();
        $this->service                  = new AccPurchaseReturnService();
        $this->stockService             = new StockService();
    }










    /*
     |--------------------------------------------------------------------------
     | index METHOD
     |--------------------------------------------------------------------------
    */
    public function index()
    {
        


        $purchaseReturns = PurchaseReturn::latest()->paginate(30);

        return view('Account::purchase.purchase-returns.index', compact('purchaseReturns'));
    }










    /*
     |--------------------------------------------------------------------------
     | CREATE METHOD
     |--------------------------------------------------------------------------
    */
    public function create()
    {
        

        $data['products']   = Product::with('unit')->get();
        $data['companies']  = Company::userCompanies();
        $data['suppliers']  = Supplier::pluck('name', 'id');
        $data['account']    = Account::where('name', 'Cash')->first();

        return view('Account::purchase.purchase-returns.create', $data);
    }










    /*
     |--------------------------------------------------------------------------
     | STORE/SAVE METHOD
     |--------------------------------------------------------------------------
    */
    public function store(Request $request)
    {
        

        
        
        try {
            
            $this->service->validateData($request);
            

            DB::transaction(function () use($request) {



                $this->service->storePurchaseReturn($request);


                $this->service->storePurchaseReturnDetails($request);


                $this->service->storePurchaseExchangeDetails($request);


                $this->service->makeTransaction();


                $this->service->invoiceNumberService->setNextInvoiceNo($request->company_id, 'Purchase Return', date('Y'));

            });

        } catch (Exception $ex) {

            return redirect()->back()->with('error', $ex->getMessage());
        }


        return redirect()->back()->with('success', 'Purchase Return Created Successfully!');

    }










    /*
     |--------------------------------------------------------------------------
     | SHOW/DETAIL METHOD
     |--------------------------------------------------------------------------
    */
    public function show($id)
    {
        

        $purchaseReturn = PurchaseReturn::with('return_details', 'exchange_details', 'supplier')->find($id);

        return view('Account::purchase.purchase-returns.invoice', compact('purchaseReturn'));
    }












    /*
     |--------------------------------------------------------------------------
     | DELETE/DESTROY METHOD
     |--------------------------------------------------------------------------
    */
    public function destroy($id)
    {
        

        try {


            DB::transaction(function () use($id) {


                $purchaseReturn = PurchaseReturn::find($id);


                optional($purchaseReturn->transactions())->delete();


                $purchaseReturnDetails = PurchaseReturnDetail::select('id', 'product_id', 'quantity', 'price')
                    ->where('purchase_return_id', $id)
                    ->get();


                foreach ($purchaseReturnDetails as $detail) {
                    
                    $detail->pos_stocks()->delete();


                    $this->service->productStockService->updateStockInHand($detail->product_id, $purchaseReturn->company_id, $purchaseReturn->branch_id, date('Y-m-d'));

                    $detail->delete();
                }



                $purchaseExchangeDetails = PurchaseExchangeDetail::select('id', 'product_id', 'quantity', 'price')
                    ->where('purchase_return_id', $id)
                    ->get();


                foreach ($purchaseExchangeDetails as $detail) {
                    
                    $detail->pos_stocks()->delete();


                    $this->service->productStockService->updateStockInHand($detail->product_id, $purchaseReturn->company_id, $purchaseReturn->branch_id, date('Y-m-d'));

                    $detail->delete();
                }


                $purchaseReturn->delete();

            });


            return redirect()->back()->with('success', 'Purchase Successfully Deleted!');


        } catch (\Exception $ex) {

            return redirect()->back()->withMessage($ex->getMessage());
        }
    }










    /*
     |--------------------------------------------------------------------------
     | GET RETURNABLE SALE INVOICES
     |--------------------------------------------------------------------------
    */
    public function getReturnablePurchaseInvoices(Request $request)
    {
        return Purchase::where('supplier_id', $request->supplier_id)->get(['invoice_no', 'id']);
    }






    /*
     |--------------------------------------------------------------------------
     | GET RETURNABLE SALE ITEMS
     |--------------------------------------------------------------------------
    */
    public function getReturnablePurchaseItems(Request $request)
    {
        $purchase = Purchase::where('id', $request->purchase_id)->with('details.product.unit')->first();

        return view('Account::purchase/purchase-returns/includes/returnable-items', compact('purchase'))->render();
    }
}
