<?php

namespace Modules\Account\Controllers\Payments;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Modules\Account\Models\Payments\BrokerPayment;
use Modules\Account\Services\Payments\BrokerPaymentService;
use Illuminate\Http\Request;
use Modules\CRM\Models\Customer\Broker;
use Modules\Sales\Models\SalesCommission;

class BrokerPaymentController extends Controller
{

    /**
     * Service variable
     *
     * @var BrokerPaymentService
     */
    private $service; 
    function __construct(BrokerPaymentService $service)
    {
        $this->service = $service;
    }
    
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data['brokerPayments'] = $this->service->getAll();
        $data['brokers'] = Broker::all();

        return view("Account::payments.broker-payments.index", $data);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {        
        $data['brokerPayments'] = SalesCommission::query()
        ->searchByFields(['broker_id', 'type'])
            ->when(request()->filled('from'), function ($qr) {
                $qr->where('commission_date', '>=', Carbon::parse(request('from'))->format('Y-m-d'));
            })
            ->when(request()->filled('to'), function ($qr) {
                $qr->where('commission_date', '<=', Carbon::parse(request('to'))->format('Y-m-d'));
            })
        ->where('status', 'verify') ->paginate(20);
        $data['brokers'] = Broker::all();

        return view('Account::payments.broker-payments.create', $data);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        
        $validate = $request->validate([
            'broker_payment_bank_id' => 'nullable|array',
            'broker_payment_bank_id.*' => 'nullable',
            'remaining_amount' => 'required|array',
            'remaining_amount.*' => 'required|numeric|min:0',
            'ids' => 'nullable|array',
            'ids.*' => 'nullable|integer|exists:sales_commissions,id',
            'remarks' => 'nullable|string', 
            'attachment_name' => 'nullable|array',
            'attachment_name.*' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',

        ]);
        

        $this->service->store($validate);
        return redirect()->route('account.payments.broker-payments.index')->with('success', 'BrokerPayment created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show( $id)
    {
        $data['brokerPayment'] = $this->service->show($id);

        return view("brokerPayments.show", $data);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(BrokerPayment $brokerPayment)
    {
        $data['brokerPayment'] = $brokerPayment;
        //
        return view("brokerPayments.edit", $data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, BrokerPayment $brokerPayment)
    {
        $validate = $request->validate([
            //validate rules
        ]);
        $this->service->update($brokerPayment, $validate);

        return redirect()->route('brokerPayments.index')->with('success', 'BrokerPayment updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(BrokerPayment $brokerPayment)
    {
        $this->service->delete($brokerPayment);
        return redirect()->route('brokerPayments.index')->with('success', 'BrokerPayment deleted successfully.');
    }

    public function verify($id)
    {
        $cheque = BrokerPayment::findOrFail($id);
        $cheque->verified_by = auth()->user()->id;
        $cheque->status = 'Verified';
        $cheque->save();

        return redirect()->route('account.payments.broker-payments.index')->with('success', 'Checker approved successfully.');
    }
    public function approve($id)
    {
        $payment = $this->service->approve($id);

        return redirect()
            ->route('account.payments.broker-payments.index')
            ->with('success', 'Broker payment approved successfully.');
    }


    

    public function deny($id)
    {
        $cheque = BrokerPayment::findOrFail($id);
        $cheque->rejected_by = auth()->user()->id;
        $cheque->status = 'Denied';
        $cheque->save();

        return redirect()->route('account.payments.broker-payments.index')->with('warning', 'Approver denied successfully.');
    }
}
