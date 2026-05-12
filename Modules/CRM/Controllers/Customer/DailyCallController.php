<?php

namespace Modules\CRM\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\AccessControl\CompanyInfo;

use Illuminate\Http\Request;
use Dompdf\Dompdf;
use Dompdf\Options;
use Modules\CRM\Models\Customer\Customer;
use Modules\CRM\Models\Customer\DailyCall;
use Modules\CRM\Services\Customer\DailyCallService;

class DailyCallController extends Controller
{

    /**
     * Service variable
     *
     * @var DailyCallService
     */
    private $service;
    function __construct(DailyCallService $service)
    {
        $this->service = $service;
        $this->middleware('permited');
    }

    /**
     * Display a listing of the resource.
     */
    public function index( Request $request)
    {
        $data['customer'] = Customer::find($request->customer_id)?->company_name;
        $data['dailyCalls'] = $this->service->getAll();
        $data['company_info'] = CompanyInfo::first();

        if ($request->export == "pdf") {
            set_time_limit(1000);
            $html = view('CRM::daily-call.indexView', $data)->render();

            // Set Dompdf options
            $options = new Options();
            $options->setIsHtml5ParserEnabled(true);
            $options->setIsRemoteEnabled(true);
            
            $dompdf = new Dompdf($options);
            $dompdf->loadHtml($html);
            $dompdf->setPaper('A4', 'portrait');
            $dompdf->render();

            return $dompdf->stream('daily_call_list_' . date('Y-m-d') . '.pdf', ['Attachment' => false]);
        }
 
        return view("CRM::daily-call.index", $data);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    { 
        return view('CRM::daily-call.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // dd($request->all());
        $validate = $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'call_type_id' => 'nullable',
            'call_date' => 'required|date',
            'is_account_complain' => 'nullable|boolean',
            'complains_details' => 'nullable|string',
            'is_service_complain' => 'nullable|boolean',
            'service_complain_details' => 'nullable|string',
            'is_sales_complain' => 'nullable|boolean',
            'sales_complain_details' => 'nullable|string',
            'is_product_required' => 'nullable|boolean',
            'product_required_details' => 'nullable|string',
            'remarks' => 'nullable|string',
        ]);
 
        try {
            // dd($validate);
            $dailyCall = $this->service->store($validate);

            return redirect()->route('crm.daily-calls.edit',$dailyCall->id)->with('success', 'Daily Call created successfully.');
        }
        catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show( $id)
    {
        $data['dailyCall'] = $this->service->show($id);

        return view("CRM::daily-call.show", $data);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(DailyCall $dailyCall)
    {
        $data['dailyCall'] = $dailyCall; 
        return view("CRM::daily-call.edit", $data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, DailyCall $dailyCall)
    {
        $validate = $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'call_type_id' => 'nullable',
            'call_date' => 'required|date',
            'is_account_complain' => 'nullable|boolean',
            'complains_details' => 'nullable|string',
            'is_service_complain' => 'nullable|boolean',
            'service_complain_details' => 'nullable|string',
            'is_sales_complain' => 'nullable|boolean',
            'sales_complain_details' => 'nullable|string',
            'is_product_required' => 'nullable|boolean',
            'product_required_details' => 'nullable|string',
            'remarks' => 'nullable|string',
        ]);
        $dailyCall = $this->service->update($dailyCall, $validate);

        return redirect()->route('crm.daily-calls.edit', $dailyCall->id)->with('success', 'DailyCall updated successfully.');

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(DailyCall $dailyCall)
    {
        $this->service->delete($dailyCall);
        return redirect()->route('crm.daily-calls.index')->with('success', 'DailyCall deleted successfully.');
    }
}
