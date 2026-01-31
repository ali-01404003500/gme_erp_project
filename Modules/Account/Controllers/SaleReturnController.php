<?php

namespace Modules\Account\Controllers;

use App\Models\Company;
use App\Traits\CheckPermission;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Modules\Account\Models\Account;
use Modules\Account\Models\Customer;
use Modules\Account\Models\Product;
use Modules\Account\Models\Sale;
use Modules\Account\Models\SaleExchangeDetail;
use Modules\Account\Models\SaleReturn;
use Modules\Account\Models\SaleReturnDetail;
use Modules\Account\Models\StockSummary;
use Modules\Account\Services\AccSaleReturnService;
use Modules\Account\Services\InvoiceNumberService;
use Modules\Account\Services\StockService;

class SaleReturnController extends Controller
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
        $this->service                  = new AccSaleReturnService();
        $this->stockService             = new StockService();
    }










    /*
     |--------------------------------------------------------------------------
     | index METHOD
     |--------------------------------------------------------------------------
    */
    public function index()
    {
        


        $saleReturns = SaleReturn::latest()->paginate(30);

        return view('Account::sale.sale-returns.index', compact('saleReturns'));
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
        $data['customers']  = Customer::pluck('name', 'id');
        $data['account']    = Account::where('name', 'Cash')->first();

        return view('Account::sale.sale-returns.create', $data);
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



                $this->service->storeSaleReturn($request);


                $this->service->storeSaleReturnDetails($request);


                $this->service->storeSaleExchangeDetails($request);


                $this->service->makeTransaction();


                $this->service->storeDamageData($request);


                $this->service->invoiceNumberService->setNextInvoiceNo($request->company_id, 'Sale Return', date('Y'));

            });

        } catch (Exception $ex) {

            return redirect()->back()->with('error', $ex->getMessage());
        }


        return redirect()->back()->with('success', 'Sale Return Created Successfully!');

    }










    /*
     |--------------------------------------------------------------------------
     | SHOW/DETAIL METHOD
     |--------------------------------------------------------------------------
    */
    public function show($id)
    {
        

        $saleReturn = SaleReturn::with('return_details', 'exchange_details', 'customer')->find($id);

        return view('Account::sale.sale-returns.invoice', compact('saleReturn'));
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


                $saleReturn = SaleReturn::find($id);


                optional($saleReturn->transactions())->delete();


                $saleReturnDetails = SaleReturnDetail::select('id', 'product_id', 'quantity', 'price')
                    ->where('sale_return_id', $id)
                    ->get();


                foreach ($saleReturnDetails as $detail) {
                    
                    $detail->pos_stocks()->delete();


                    $this->service->productStockService->updateStockInHand($detail->product_id, $saleReturn->company_id, $saleReturn->branch_id, date('Y-m-d'));

                    $detail->delete();
                }



                $saleExchangeDetails = SaleExchangeDetail::select('id', 'product_id', 'quantity', 'price')
                    ->where('sale_return_id', $id)
                    ->get();


                foreach ($saleExchangeDetails as $detail) {
                    
                    $detail->pos_stocks()->delete();


                    $this->service->productStockService->updateStockInHand($detail->product_id, $saleReturn->company_id, $saleReturn->branch_id, date('Y-m-d'));

                    $detail->delete();
                }


                $saleReturn->delete();

            });


            return redirect()->back()->with('success', 'Sale Successfully Deleted!');


        } catch (\Exception $ex) {

            return redirect()->back()->withMessage($ex->getMessage());
        }
    }










    /*
     |--------------------------------------------------------------------------
     | GET RETURNABLE SALE INVOICES
     |--------------------------------------------------------------------------
    */
    public function getReturnableSaleInvoices(Request $request)
    {
        return Sale::where('customer_id', $request->customer_id)->get(['invoice_no', 'id']);
    }






    /*
     |--------------------------------------------------------------------------
     | GET RETURNABLE SALE ITEMS
     |--------------------------------------------------------------------------
    */
    public function getReturnableSaleItems(Request $request)
    {
        $sale = Sale::where('id', $request->sale_id)->with('details.product.unit')->first();

        return view('Account::sale/sale-returns/includes/returnable-items', compact('sale'))->render();
    }
}
