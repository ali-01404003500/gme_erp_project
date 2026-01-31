<?php

namespace Modules\Sales\Controllers;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use DB;
use Modules\CRM\Models\Customer\Broker;
use Modules\CRM\Models\Customer\BrokerCommission;
use Modules\CRM\Models\Customer\BrokerCustomerAttached;
use Modules\Sales\Models\SalesCommission;
use Modules\Sales\Models\SalesOrder;
use Modules\Sales\Services\SalesCommissionService;
use Illuminate\Http\Request;

class SalesCommissionController extends Controller
{
    /**
     * Service variable
     *
     * @var SalesCommissionService
     */
    private $service;
    function __construct(SalesCommissionService $service)
    {
        $this->service = $service;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data['salesCommissions'] = $this->service->getAll();
        // dd($data['salesCommissions']->toArray());
        $data['brokers'] = Broker::activeBrokers()->get();
        return view('Sales::sales-commissions.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $data['brokers'] = Broker::activeBrokers()->get();

        $broker = Broker::find($request->broker_id);
        $data['brokerCommissionType'] = $brokerCommissionType = $broker?->commission_type;
        $data['customerAttached'] = BrokerCustomerAttached::where('broker_id', $request->broker_id)->get();

        if (!$broker) {
            return view('Sales::sales-commissions.create', $data);
        }

        // Percentage Based
        if ($brokerCommissionType == 1) {
            $commissions = BrokerCommission::where('broker_id', $broker->id)->get()->keyBy('percentage_type');

            $query = SalesOrder::with(['details.product', 'customer'])
                ->where('status', 'delivered')
                ->whereIn('customer_id', $data['customerAttached']->pluck('customer_id')->toArray());

            if ($request->filled('from') && $request->filled('to')) {
                $from = Carbon::parse($request->from)->startOfDay();
                $to = Carbon::parse($request->to)->endOfDay();
                $query->whereBetween('invoice_date', [$from, $to]);
            }

            $data['invoices'] = $query->get();


            $invoiceCommissions = [];

            foreach ($data['invoices'] as $invoice) {
                $totalInvoiceCommission = 0;
                $totalAmount = 0;
                $invoiceBreakdown = [];

                foreach ($invoice->details as $detail) {
                    $productTagId = optional($detail->product)->product_tag_id;
                    // dd($detail);
                    $amount = $detail->amount - $detail->total_discount ?? 0;

                    if ($commissions->has($productTagId)) {
                        $commission = $commissions->get($productTagId); 
                        $commissionAmount = ($commission->percentage / 100) * $amount;

                        $invoiceBreakdown[] = [
                            'product_id' => $detail->product_id,
                            'product_tag_id' => $productTagId,
                            'amount' => $amount,
                            'percentage' => $commission->percentage,
                            'commission_amount' => $commissionAmount,
                        ];

                        $totalInvoiceCommission += $commissionAmount;
                        $totalAmount += $amount;
                    }
                }
                // dd($invoice->customer->customerSetting->pluck('customerSettingDiscounts')->toArray());
                if ($totalInvoiceCommission > 0) {
                    $invoiceCommissions[] = [
                        'invoice_id' => $invoice->id,
                        'sales_order_id' => $invoice->sales_order_id,
                        'invoice_date' => $invoice->invoice_date,
                        'customer' => $invoice->customer,
                        'broker' => $broker,
                        'broker_percentage' => $commissions,
                        'total_amount' => $totalAmount,
                        'total_commission' => $totalInvoiceCommission,
                    ];
                }
            }
            // dd($invoiceCommissions);

            $data['invoiceCommissions'] = $invoiceCommissions;
        }

        // Fixed Commission Based
        if ($brokerCommissionType == 2) {
            $fixedCommission = BrokerCommission::where('broker_id', $broker->id)->first();
            $from = $request->filled('from') ? Carbon::parse($request->from)->startOfMonth() : now()->startOfYear();
            $to = $request->filled('to') ? Carbon::parse($request->to)->endOfMonth() : now()->endOfYear();

            switch ($fixedCommission?->fixed_type) {
                case 1: // Invoice-wise
                    $query = SalesOrder::with(['details.product', 'customer'])
                        ->where('status', 'delivered')
                        ->whereIn('customer_id', $data['customerAttached']->pluck('customer_id')->toArray());

                    if ($request->filled('from') && $request->filled('to')) {
                        $from = Carbon::parse($request->from)->startOfDay();
                        $to = Carbon::parse($request->to)->endOfDay();
                        $query->whereBetween('invoice_date', [$from, $to]);
                    }

                    $invoices = $query->get();

                    $invoiceCommissions = [];
                    foreach ($invoices as $invoice) {
                        $invoiceCommissions[] = [
                            'invoice_id' => $invoice->id,
                            'sales_order_id' => $invoice->sales_order_id,
                            'customer' => $invoice->customer,
                            'broker' => $broker,
                            'invoice_date' => $invoice->invoice_date,
                            'broker_percentage' => $fixedCommission,
                            'total_amount' => $invoice->total_amount,
                            'total_commission' => $fixedCommission->fixed,
                        ];
                    }
                    $data['invoiceCommissions'] = $invoiceCommissions;
                    break;

                case 2: // Monthly
                $monthlyCommission = [];

                $period = $from->copy();
                while ($period <= $to) {
                    $exists = SalesCommission::where('broker_id', $broker->id)
                        ->where('type', 'monthly')
                        ->whereMonth('commission_date', $period->month)
                        ->whereYear('commission_date', $period->year)
                        ->exists();

                    if (!$exists) {
                        $monthlyCommission[] = [
                            'sales_order_id' => 'Monthly Commission',
                            'customer' => '',
                            'broker' => $broker,
                            'month' => $period->format('F'),
                            'date' => $period->format('Y-m-d'),
                            'total_amount' => 0,
                            'total_commission' => $fixedCommission->fixed,
                            'broker_percentage' => $fixedCommission,
                        ];
                    }

                    $period->addMonth();
                }

                $data['monthlyCommission'] = $monthlyCommission;
                break;

            case 3: // Yearly
                $years = [];
                $startYear = $from->year;
                $endYear = $to->year;

                for ($year = $startYear; $year <= $endYear; $year++) {
                    $exists = SalesCommission::where('broker_id', $broker->id)
                        ->where('type', 'yearly')
                        ->whereYear('commission_date', $year)
                        ->exists();

                    if (!$exists) {
                        $years[] = [
                            'sales_order_id' => 'Yearly Commission',
                            'customer' => '',
                            'broker' => $broker,
                            'commission_year' => $year,
                            'total_amount' => 0,
                            'total_commission' => $fixedCommission->fixed,
                            'broker_percentage' => $fixedCommission,
                        ];
                    }
                }

                $data['yearlyCommission'] = $years;
                break;

            case 4: // Festival - Eid
            case 5: // Festival - Durga Puja
                $festivals = [];
                if ($fixedCommission->fixed_type == 4) {
                    $festivals = ['Eid-ul-Fitr', 'Eid-ul-Adha'];
                } elseif ($fixedCommission->fixed_type == 5) {
                    $festivals = ['Durga Puja'];
                }

                $festivalCommission = [];
                $startYear = $from->year;
                $endYear = $to->year;

                foreach ($festivals as $festivalName) {
                    for ($year = $startYear; $year <= $endYear; $year++) {
                        $type = strtolower(str_replace([' ', '-'], '_',  $festivalName));

                        $exists = SalesCommission::where('broker_id', $broker->id)
                            ->where('type', $type)
                            ->whereYear('commission_date', $year)
                            ->exists();

                        if (!$exists) {
                            $festivalCommission[] = [
                                'sales_order_id' => $festivalName,
                                'customer' => '',
                                'broker' => $broker,
                                'commission_year' => $year,
                                'total_amount' => 0,
                                'total_commission' => $fixedCommission->fixed,
                                'broker_percentage' => $fixedCommission,
                            ];
                        }
                    }
                }

                $data['festivalCommission'] = $festivalCommission;
                break;
            }
        }

        return view('Sales::sales-commissions.create', $data);
    }

    /**
     * Store a newly created resource in storage.
     */
   public function store(Request $request)
{
    $request->validate([
        'broker_id' => 'required|exists:brokers,id',
    ]);

    if (!$request->has('id') || empty($request->id) || !is_array($request->id)) {
        return redirect()->back()->with('error', 'At least one commission must be checked.');
    }

    $this->service->storeCommissions($request->broker_id, $request->id, $request->broker_bank_id);

    return redirect()->route('sales.sales-commissions.create', ['broker_id' => $request->broker_id])
        ->with('success', 'Commissions stored successfully.');
}


    public function verify(Request $request)
    {
        $request->validate([
            'action' => 'required',
        ]);

        if (!$request->has('ids') || empty($request->ids) || !is_array($request->ids)) {
            return redirect()->back()->with('error', 'At least one commission must be checked.');
        }

        $this->service->verifyCommissions($request->ids, $request->action, auth()->id());
        return redirect()->back()->with('success', 'Selected commissions have been ' . $request->action . '.');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $data['salesCommission'] = $this->service->show($id);

        return view('salesCommissions.show', $data);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(SalesCommission $salesCommission)
    {
        $data['salesCommission'] = $salesCommission;
        //
        return view('salesCommissions.edit', $data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, SalesCommission $salesCommission)
    {
        $validate = $request->validate([
            //validate rules
        ]);
        $this->service->update($salesCommission, $validate);

        return redirect()->route('salesCommissions.index')->with('success', 'SalesCommission updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(SalesCommission $salesCommission)
    {
        $this->service->delete($salesCommission);
        return redirect()->route('salesCommissions.index')->with('success', 'SalesCommission deleted successfully.');
    }
}
