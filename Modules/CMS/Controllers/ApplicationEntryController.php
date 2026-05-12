<?php

namespace Modules\CMS\Controllers;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Modules\CMS\Models\ApplicationEntry;
use Modules\CMS\Services\ApplicationEntryService;
use Illuminate\Http\Request;
use Modules\Account\Models\AdvanceChequeEntry;
use Modules\CRM\Models\Customer\Customer;

class ApplicationEntryController extends Controller
{
    private $service; 

    function __construct(ApplicationEntryService $service)
    {
        $this->service = $service;
    }
    
    public function index(Request $request)
    {
        $data['applicationEntrys'] = $this->service->getAll();  
        $data['customer'] = $request->customer_id  ? Customer::activeCustomers()->where('id', $request->customer_id)->first() : null;
        return view("CMS::application-entries.index", $data);
    }

    public function create(Request $request)
    { 
        $data['entries'] = AdvanceChequeEntry::with([
            'customer',
            'details' => function ($q) {
                $q->where('status', 'Pending')
                    ->when(request()->filled('from'), function ($qr) {
                        $qr->where('cheque_date', '>=', Carbon::parse(request('from'))->format('Y-m-d'));
                    })
                    ->when(request()->filled('to'), function ($qr) {
                        $qr->where('cheque_date', '<=', Carbon::parse(request('to'))->format('Y-m-d'));
                    });
            }
        ])
        ->searchByFields(['customer_id'])
        ->where('status', 'Approved')
        ->get();

        return view('CMS::application-entries.create', $data);
    }

    public function store(Request $request)
    {
        if($request->type === 'Cheque'){
            $validate = $request->validate([
                'advance_cheque_entry_detail_id' => 'required|array',
                'advance_cheque_entry_detail_id.*' => 'integer',
                'customer_id' => 'required|array',
                'descriptions' => 'required|string',
                'type' => 'required|string',
                'date' => 'required|date',
            ]);
        } else {
            $validate = $request->validate([
                'customer_id' => 'required|integer|exists:customers,id',
                'type' => 'required|string',
                'date' => 'required|date',
                'description' => 'required|string',
            ]);
        }

        $this->service->store($validate);
        return redirect()->route('cms.application-entries.index')->with('success', 'ApplicationEntry created successfully.');
    }

    public function show($id)
    {
        $data['applicationEntry'] = $this->service->show($id);
        return view("applicationEntrys.show", $data);
    }

    public function edit(ApplicationEntry $applicationEntry)
    {
        $data['applicationEntry'] = $applicationEntry; 
        return view("CMS::application-entries.edit", $data);
    }

    public function update(Request $request, ApplicationEntry $applicationEntry)
    {
        $validate = $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'type' => 'required|string',
            'date' => 'required|date',
            'description' => 'required|string',
        ]);
        $this->service->update($applicationEntry, $validate);

        return redirect()->route('cms.application-entries.index')->with('success', 'ApplicationEntry updated successfully.');
    }

    public function destroy(ApplicationEntry $applicationEntry)
    {
        $this->service->delete($applicationEntry);
        return redirect()->route('cms.application-entries.index')->with('success', 'ApplicationEntry deleted successfully.');
    }

    public function approved($id)
    {
        $this->service->updateBatchStatus($id, 'approved', [
            'approved_by' => auth()->id(),
            'approval_note' => request('approved_comment'),
        ]);
        
        return redirect()->route('cms.application-entries.index')->with('success', 'Application approved successfully.');
    }

    public function handover($id)
    {
        $this->service->updateBatchStatus($id, 'handover', [
            'handover_by' => auth()->id(),
            'handover_note' => request('handover_comment'),
        ]);
        
        return redirect()->route('cms.application-entries.index')->with('success', 'Document handover successfully.');
    }

    public function received($id)
    {
        $this->service->updateBatchStatus($id, 'received', [
            'received_by' => auth()->id(),
            'received_note' => request('received_comment'),
        ]);
        
        return redirect()->route('cms.application-entries.index')->with('success', 'Document received successfully.');
    }

    public function deny(Request $request, $id)
    {
        $applicationEntry = $this->service->show($id);
        $remark = $request->input('deny_comment');
        
        $updateData = [
            'denied_note' => $remark,
            'denied_by' => auth()->id(),
        ];

        switch ($applicationEntry->status) {
            case 'approved':
                $this->service->updateBatchStatus($id, 'pending', $updateData);
                break;

            case 'handover':
                $this->service->updateBatchStatus($id, 'approved', $updateData);
                break;

            case 'received':
                $this->service->updateBatchStatus($id, 'handover', $updateData);
                break;

            default:
                $this->service->updateBatchStatus($id, 'denied', $updateData);
                
                // If it's a batch of cheques, update all related cheque details
                if ($applicationEntry->batch_id) {
                    $chequeDetailIds = ApplicationEntry::where('batch_id', $applicationEntry->batch_id)
                        ->whereNotNull('advance_cheque_entry_detail_id')
                        ->pluck('advance_cheque_entry_detail_id');
                    
                    if ($chequeDetailIds->isNotEmpty()) {
                        \Modules\Account\Models\AdvanceChequeEntryDetail::whereIn('id', $chequeDetailIds)
                            ->update(['status' => 'Pending']);
                    }
                } else if ($applicationEntry->advanceChequeEntryDetail) {
                    $applicationEntry->advanceChequeEntryDetail->update(['status' => 'Pending']);
                }
                break;
        }

        return redirect()->route('cms.application-entries.index')
            ->with('success', 'Application denied and sent back successfully.');
    }
}