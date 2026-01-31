<?php

namespace Modules\Inventory\Controllers;

use App\Http\Controllers\Controller;
use App\Models\AccessControl\CompanyInfo;
use Modules\Inventory\Models\Offer;
use Modules\Inventory\Models\Product\Settings\Brand;
use Modules\Inventory\Models\ProductCatalog;
use Modules\Inventory\Services\OfferService;
use Illuminate\Http\Request;
use Dompdf\Dompdf;
use Dompdf\Options;
class OfferController extends Controller
{

    /**
     * Service variable
     *
     * @var OfferService
     */
    private $service;
    /**
     * Constructor for the class.
     *
     * @param OfferService $service The offer service instance.
     */
    function __construct(OfferService $service)
    {
        $this->service = $service;
        $this->middleware('permited');

    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $data['offers'] = $this->service->getAll();
        $data['company_info'] = CompanyInfo::first();

        if ($request->export == "pdf") {
            set_time_limit(1000);
            $html = view('Inventory::offers.indexView', $data)->render();

            // Set Dompdf options
            $options = new Options();
            $options->setIsHtml5ParserEnabled(true);
            $options->setIsRemoteEnabled(true);

            $dompdf = new Dompdf($options);
            $dompdf->loadHtml($html);
            $dompdf->setPaper('A4', 'portrait');
            $dompdf->render();

            return $dompdf->stream('gift_offer_list_' . date('Y-m-d') . '.pdf', ['Attachment' => false]);
        }

        return view("Inventory::offers.index", $data);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $data['productCatalogs'] = ProductCatalog::all();
        $data['brands'] = Brand::all();
        return view('Inventory::offers.create', $data);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // dd($request->all());
        $validate = $request->validate([
            'title' => 'required|string|max:255',
            'applied_date' => 'required',
            'stop_date' => 'required',
            'times' => 'nullable|numeric',
            'offer_type' => 'required',
            'invoice_type' => 'nullable',
            'rule_status' => 'nullable',
            'rule_type' => 'nullable',
        ]);
$result = [];

        if ($request->offer_type == 'discount') {
            $offerDetails = $request->validate([
                'buying_product_id' => 'required|array',
                'buying_product_id.*' => 'required|array',
                'buying_product_id.*.*' => 'required|integer',
                'buying_quantity' => 'required|array',
                'buying_quantity.*' => 'required|array',
                'buying_quantity.*.*' => 'required|numeric',
                'discount_type' => 'required|array',
                'discount_type.*' => 'required|array',
                'discount_type.*.*' => 'required|string|in:percentage_discount,flat_discount',
                'discount_amount' => 'required|array',
                'discount_amount.*' => 'required|array',
                'discount_amount.*.*' => 'required|numeric',
            ]);
            $result =   $this->service->store($validate, $offerDetails);

        } else if ($request->offer_type == 'gift') {
            $offerDetails = $request->validate([
                'buying_product_id' => 'required|array',
                'buying_product_id.*' => 'required|array',
                'buying_product_id.*.*' => 'required|integer',
                'buying_quantity' => 'required|array',
                'buying_quantity.*' => 'required|array',
                'buying_quantity.*.*' => 'required|numeric',
                'offer_product_id' => 'required|array',
                'offer_product_id.*' => 'required|array',
                'offer_product_id.*.*' => 'required|numeric',
                'offer_quantity' => 'required|array',
                'offer_quantity.*' => 'required|array',
                'offer_quantity.*.*' => 'required|numeric',
            ]);
             $result =  $this->service->store($validate, $offerDetails);
        } else if ($request->offer_type == 'clearance') {
            $offerDetails = $request->validate([
                'buying_amount_from' => 'required|array',
                'buying_amount_from.*' => 'required|array',
                'buying_amount_from.*.*' => 'required|numeric',
                'buying_amount_to' => 'required|array',
                'buying_amount_to.*' => 'required|array',
                'buying_amount_to.*.*' => 'required|numeric',
                'gift_type' => 'nullable|array',
                'gift_type.*' => 'nullable|array',
                'gift_type.*.*' => 'nullable|string|in:percentage,flat',
                'gift_amount' => 'nullable|array',
                'gift_amount.*' => 'nullable|array',
                'gift_amount.*.*' => 'nullable|numeric',
                'clearance_product_id' => 'nullable|array',
                'clearance_product_id.*' => 'nullable|array',
                'clearance_product_id.*.*' => 'nullable|integer',
            ]);
            // dd($offerDetails);
            $result = $this->service->store($validate, $offerDetails);

        }
        // dd($request->offer_type, $result);

        return redirect()->route('inv.offers.edit', $result['offer']->id)->with('success', 'Offer created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $data['offer'] = $this->service->show($id);

        return view("Inventory::offers.show", $data);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $data['offer'] = $this->service->show($id);
        $data['productCatalogs'] = ProductCatalog::all();
        $data['brands'] = Brand::all();
        //
        return view("Inventory::offers.edit", $data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Offer $offer)
    {
        // dd($request->all());
        $validate = $request->validate([
            'title' => 'required|string|max:255',
            'applied_date' => 'required',
            'stop_date' => 'required',
            'times' => 'nullable|numeric',
            'offer_type' => 'required',
            'invoice_type' => 'nullable',
            'rule_status' => 'nullable',
            'rule_type' => 'nullable',
        ]);

        $offerDetails = [];
        if ($validate['offer_type'] == 'discount') {
            $offerDetails = $request->validate([
                'buying_product_id' => 'required|array',
                'buying_product_id.*' => 'required|array',
                'buying_product_id.*.*' => 'required|numeric',
                'buying_quantity' => 'required|array',
                'buying_quantity.*' => 'required|array',
                'buying_quantity.*.*' => 'required|numeric',
                'discount_type' => 'required|array',
                'discount_type.*' => 'required|array',
                'discount_type.*.*' => 'required|string|in:percentage_discount,flat_discount',
                'discount_amount' => 'required|array',
                'discount_amount.*' => 'required|array',
                'discount_amount.*.*' => 'required|numeric',
            ]);
        } else if ($validate['offer_type'] == 'gift') {
            $offerDetails = $request->validate([
                'buying_product_id' => 'required|array',
                'buying_product_id.*' => 'required|array',
                'buying_product_id.*.*' => 'required|numeric',
                'buying_quantity' => 'required|array',
                'buying_quantity.*' => 'required|array',
                'buying_quantity.*.*' => 'required|numeric',
                'offer_product_id' => 'required|array',
                'offer_product_id.*' => 'required|array',
                'offer_product_id.*.*' => 'required|numeric',
                'offer_quantity' => 'required|array',
                'offer_quantity.*' => 'required|array',
                'offer_quantity.*.*' => 'required|numeric',
            ]);
        } else if ($request->offer_type == 'clearance') {
            $offerDetails = $request->validate([
                'buying_amount_from' => 'required|array',
                'buying_amount_from.*' => 'required|array',
                'buying_amount_from.*.*' => 'required|numeric',
                'buying_amount_to' => 'required|array',
                'buying_amount_to.*' => 'required|array',
                'buying_amount_to.*.*' => 'required|numeric',
                'gift_type' => 'nullable|array',
                'gift_type.*' => 'nullable|array',
                'gift_type.*.*' => 'nullable|string|in:percentage,flat',
                'gift_amount' => 'nullable|array',
                'gift_amount.*' => 'nullable|array',
                'gift_amount.*.*' => 'nullable|numeric',
                'clearance_product_id' => 'nullable|array',
                'clearance_product_id.*' => 'nullable|array',
                'clearance_product_id.*.*' => 'nullable|integer',
            ]);
        }
        $this->service->update($offer, $validate, $offerDetails);

        return redirect()->route('inv.offers.edit', $offer->id)->with('success', 'Offer updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Offer $offer)
    {
        $this->service->delete($offer);
        return redirect()->route('inv.offers.index')->with('success', 'Offer deleted successfully.');
    }
}