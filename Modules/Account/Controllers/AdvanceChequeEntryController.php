<?php

namespace Modules\Account\Controllers;

use App\Http\Controllers\Controller;
use App\Models\AccessControl\CompanyInfo;
use Carbon\Carbon;
use Dompdf\Dompdf;
use Dompdf\Options;
use Modules\Account\Models\AdvanceChequeEntry;
use Modules\Account\Services\AdvanceChequeEntryService;
use Illuminate\Http\Request;
use Modules\Account\Models\EMIEntry;
use Modules\Account\Models\Setup\Bank;
use Modules\Account\Models\Setup\BankBranch;
use Modules\CRM\Models\Customer\Customer;
use NumberToWords\Legacy\Numbers\Words\Locale\Pt\Br;

class AdvanceChequeEntryController extends Controller
{
    /**
     * Service variable
     *
     * @var AdvanceChequeEntryService
     */
    private $service;
    function __construct(AdvanceChequeEntryService $service)
    {
        $this->service = $service;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data['advanceChequeEntrys'] = $this->service->getAll();
        $data['customers'] = Customer::activeCustomers()->get();

        return view('Account::advance-cheque-entries.index', $data);
    }

    public function saveSignature(Request $request, $id)
    {
        $request->validate([
            'signature' => 'required',
            'timestamp' => 'required',
        ]);

        $entry = AdvanceChequeEntry::findOrFail($id);

       
        // Update the entry
        $entry->update([
            'signature' => $request->signature,
            'signature_timestamp' => $request->timestamp,
        ]);

        return response()->json(['success' => true, 'path' => $entry->signature]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function getCustomerReferences(Request $request)
    {
        $customerId = $request->input('customer_id');
        $chequeType = $request->input('cheque_type');
        $referenceId = $request->input('reference_id');

        $response = [
            'references' => [],
            'emi_details' => [],
        ];

        if ($chequeType === 'installment') {
            // Load EMI references
            $emiEntriesQuery = EMIEntry::where('customer_id', $customerId)->where('status', 'due');

            if ($referenceId) {
                $emiEntriesQuery->where('id', $referenceId);
            }

            $emiEntries = $emiEntriesQuery->get();

            $response['references'] = $emiEntries
                ->map(function ($emi) {
                    return [
                        'id' => $emi->id,
                        'reference_number' => $emi->emi_number,
                    ];
                })
                ->toArray();

            if ($referenceId && $emiEntries->isNotEmpty()) {
                $emi = $emiEntries->first();
                $response['emi_details'] = $emi
                    ->emiDetails()
                    ->get()
                    ->where('status', 'due')
                    ->map(function ($detail) {
                        return [
                            'id' => $detail->id,
                            'due_date' => $detail->emi_date,
                            'amount' => $detail->emi_amount,
                            'bank_name' => '', // Empty as per your clarification
                            'branch_name' => '', // Empty as per your clarification
                            'cheque_no' => '', // Empty as per your clarification
                        ];
                    })
                    ->toArray();
            }
        }

        return response()->json($response);
    }

    public function create()
    {
        $data['customers'] = Customer::activeCustomers()->get();
        $data['emis'] = EMIEntry::all();
        $data['banks'] = Bank::all();
        $data['branches'] = BankBranch::all();
        return view('Account::advance-cheque-entries.create', $data);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // dd($request->all());
        $validate = $request->validate([
            'cheque_type' => 'required|string',
            'customer_id' => 'required',
            'collection_date' => 'required',
            'no_of_cheque' => 'nullable',
            'reference' => 'nullable|unique:advance_cheque_entries',
            'remarks' => 'nullable|string',
            'document' => 'nullable|string',
        ]);
        $details = $request->validate([
            'emi_detail_id' => 'nullable|array',
            'emi_detail_id.*' => 'nullable|integer|exists:e_m_i_entry_details,id',
            'bank_ids' => 'required|array',
            'bank_ids.*' => 'required|integer',
            'branch_ids' => 'required|array',
            'branch_ids.*' => 'required|integer',
            'cheque_no' => 'required|array',
            'cheque_no.*' => 'required|string',
            'cheque_date' => 'required|array',
            'cheque_date.*' => 'required_if:is_security_cheque.*,"0"|nullable|date',
            'amount' => 'required|array',
            'amount.*' => 'required',
            'documents' => 'nullable|array',
            'documents.*' => 'nullable|string',
            'is_security_cheque' => 'required|array',
            'is_security_cheque.*' => 'nullable',
        ]);
        $this->service->store($validate, $details);
        return redirect()->route('account.advance-cheque-entries.index')->with('success', 'Advance Cheque Entry created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, $id)
    {
        $data['advanceChequeEntry'] = $this->service->show($id);
        // dd($data['advanceChequeEntry']);
        $data['company_info'] = CompanyInfo::first();
        if ($request->export == 'pdf') {
            set_time_limit(1000);
            $html = view('Account::advance-cheque-entries.view', $data)->render();

            // Set Dompdf options
            $options = new Options();
            $options->setIsHtml5ParserEnabled(true);
            $options->setIsRemoteEnabled(true);

            $dompdf = new Dompdf($options);
            $dompdf->loadHtml($html);
            $dompdf->setPaper('A4', 'portrait');
            $dompdf->render();

            return $dompdf->stream('advance-cheque-entries_' . $data['advanceChequeEntry']->company_name . '.pdf', ['Attachment' => false]);
        }
        return view('Account::advance-cheque-entries.show', $data);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $data['entry'] = AdvanceChequeEntry::with('details')->findOrFail($id);
        $data['customers'] = Customer::activeCustomers()->get();
        $data['emis'] = EMIEntry::all();
        $data['banks'] = Bank::all();
        $data['branches'] = BankBranch::all();

        return view('Account::advance-cheque-entries.edit', $data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, AdvanceChequeEntry $advanceChequeEntry)
    {
        $validate = $request->validate([
            'cheque_type' => 'required|string',
            'customer_id' => 'required',
            'collection_date' => 'required',
            'no_of_cheque' => 'nullable',
            'reference' => 'nullable',
            'remarks' => 'nullable|string',
            'document' => 'nullable|string',
        ]);

        $details = $request->validate([
            'bank_ids' => 'required|array',
            'bank_ids.*' => 'required|integer',
            'branch_ids' => 'required|array',
            'branch_ids.*' => 'required|integer',
            'cheque_no' => 'required|array',
            'cheque_no.*' => 'required|string',
            'cheque_date' => 'required|array',
            'cheque_date.*' => 'required_if:is_security_cheque.*,"0"|nullable|date',
            'amount' => 'required|array',
            'amount.*' => 'required',
            'documents' => 'nullable|array',
            'documents.*' => 'nullable|string',
            'is_security_cheque' => 'required|array',
            'is_security_cheque.*' => 'nullable',
        ]);

        $this->service->update($advanceChequeEntry, $validate, $details);

        return redirect()->route('account.advance-cheque-entries.index')->with('success', 'AdvanceChequeEntry updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(AdvanceChequeEntry $advanceChequeEntry)
    {
        $this->service->delete($advanceChequeEntry);
        return redirect()->route('account.advance-cheque-entries.index')->with('success', 'AdvanceChequeEntry deleted successfully.');
    }
    public function check($id)
    {
        $cheque = AdvanceChequeEntry::findOrFail($id);
        $cheque->checked_by = auth()->user()->id;
        $cheque->status = 'Checked';
        $cheque->save();

        return redirect()->route('account.advance-cheque-entries.index')->with('success', 'Checker approved successfully.');
    }
    public function approve($id)
    {
        $cheque = AdvanceChequeEntry::findOrFail($id);
        $cheque->approved_by = auth()->user()->id;
        $cheque->status = 'Approved';
        $cheque->save();

        return redirect()->route('account.advance-cheque-entries.index')->with('success', 'Approver approved successfully.');
    }

    public function deny($id)
    {
        $cheque = AdvanceChequeEntry::findOrFail($id);
        $cheque->rejected_by = auth()->user()->id;
        $cheque->status = 'Denied';
        $cheque->save();

        return redirect()->route('account.advance-cheque-entries.index')->with('warning', 'Approver denied successfully.');
    }


    public function chequeCollection(Request $request)
    {
        $data['entries'] = AdvanceChequeEntry::with([
            'customer',
            'details' => function ($q) {
                $q->where('status', 'Pending')
                ->when(request()->filled('from'), function ($qr) {
                    $qr->where('cheque_date', '>=', Carbon::parse( request('from'))->format('Y-m-d'));
                })
                ->when(request()->filled('to'), function ($qr) {
                    $qr->where('cheque_date', '<=', Carbon::parse( request('to'))->format('Y-m-d'));
                });
            }
        ])
        ->searchByFields(['customer_id'])
        ->where('status', 'Approved')->get();
        $data['customers'] = Customer::activeCustomers()->get();

        return view('Account::advance-cheque-entries.collection', $data);
        
    }
}
