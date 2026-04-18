<?php
namespace Modules\Legal\Controllers;

use App\Http\Controllers\Controller;
use App\Models\AccessControl\CompanyInfo;
use Illuminate\Http\Request;
use Modules\CRM\Models\Customer\Customer;
use Modules\Inventory\Services\ExportService;
use Modules\Legal\Models\LegalEntry;
use Modules\Legal\Models\LegalEntryConvict;
use Modules\Legal\Services\LegalEntryService;

class LegalEntryController extends Controller
{

    /**
     * Service variable
     *
     * @var LegalEntryService
     */
    private $service;
    public function __construct(LegalEntryService $service)
    {
        $this->service = $service;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data['legalEntrys'] = $this->service->getAll();

        return view("Legal::legal-entries.index", $data);
    }

    public function report(Request $request)
    {
        $tab  = $request->input('tab', 'case'); // Default to 'case' tab
        $data = [];

        // Base query for Case Report (legal_type = 'case')
        $caseQuery = LegalEntry::with(['convicts.customer', 'hajiras'])
            ->where('legal_type', 'case');

        // Base query for Notice Report (legal_type = 'notice')
        $noticeQuery = LegalEntry::with(['convicts.customer', 'hajiras'])
            ->where('legal_type', 'notice');

        // Apply search for case tab
        $search = $request->input('search');
        if ($search && $tab === 'case') {
            $caseQuery->where(function ($q) use ($search) {
                $q->where('case_no', 'like', "%{$search}%")
                    ->orWhereHas('convicts', function ($q) use ($search) {
                        $q->where('convict_name', 'like', "%{$search}%")
                            ->orWhere('convict_phone', 'like', "%{$search}%")
                            ->orWhere('convict_address', 'like', "%{$search}%");
                    })
                    ->orWhereHas('convicts.customer', function ($q) use ($search) {
                        $q->where('company_name', 'like', "%{$search}%");
                    })
                    ->orWhere('advocate_name', 'like', "%{$search}%")
                    ->orWhere('advocate_phone', 'like', "%{$search}%");
            });
        }

        // Apply search for notice tab
        if ($search && $tab === 'notice') {
            $noticeQuery->where(function ($q) use ($search) {
                $q->where('case_no', 'like', "%{$search}%")
                    ->orWhereHas('convicts', function ($q) use ($search) {
                        $q->where('convict_name', 'like', "%{$search}%")
                            ->orWhere('convict_phone', 'like', "%{$search}%")
                            ->orWhere('convict_address', 'like', "%{$search}%");
                    })
                    ->orWhereHas('convicts.customer', function ($q) use ($search) {
                        $q->where('company_name', 'like', "%{$search}%");
                    })
                    ->orWhere('advocate_name', 'like', "%{$search}%")
                    ->orWhere('advocate_phone', 'like', "%{$search}%");
            });
        }

        $data['company_info'] = CompanyInfo::first();

        // Handle Export (PDF/Excel) - use filtered data
        if ($request->filled('export_type')) {
            if ($tab === 'case') {
                $data['caseReportEntrys']   = $caseQuery->get();
                $data['noticeReportEntrys'] = collect([]);
            } else {
                $data['noticeReportEntrys'] = $noticeQuery->get();
                $data['caseReportEntrys']   = collect([]);
            }

            $data['tab']    = $tab;
            $data['search'] = $search;

            $filename = 'Legal_' . ucfirst($tab) . '_Report_' . today()->format('Y_m_d');

            return (new ExportService())->exportData($data, 'Legal::legal-entries.export.', $filename);
        }

        // Paginate both queries to ensure data is available
        $data['caseReportEntrys']   = $caseQuery->paginate(20, ['*'], 'case_page');
        $data['noticeReportEntrys'] = $noticeQuery->paginate(20, ['*'], 'notice_page');

        $data['tab']       = $tab;
        $data['customers'] = Customer::activeCustomers()->get();

        return view("Legal::legal-entries.report", $data);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $data['customers'] = Customer::activeCustomers()->get();

        return view('Legal::legal-entries.create', $data);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Hajira validation (multiple)
        $hajiraValidate = $request->validate([
            'hajira_date'          => 'nullable|array',
            'hajira_date.*'        => 'nullable|date',
            'hajira_description'   => 'nullable|array',
            'hajira_description.*' => 'nullable|string',
        ]);

        // Main LegalEntry validation
        $validate = $request->validate([
            'date'                 => 'required|date',
            'amount'               => 'required|numeric',
            'legal_type'           => 'required|string',
            'case_no'              => 'nullable|string',
            'occurrence_info'      => 'nullable|string',
            'occurrence_date'      => 'nullable|date',
            'legal_description'    => 'nullable|string',
            'advocate_name'        => 'nullable|string',
            'advocate_designation' => 'nullable|string',
            'advocate_phone'       => 'nullable|string',
            'advocate_address'     => 'nullable|string',
            'attachment'           => 'nullable|array|min:1',
            'attachment.*'         => 'nullable|string',
        ]);

        // Convict validation
        $convictValidate = $request->validate([
            'customer_id'           => 'required|array',
            'customer_id.*'         => 'exists:customers,id',
            'convict_name'          => 'required|array',
            'convict_name.*'        => 'required|string',
            'convict_designation'   => 'nullable|array',
            'convict_designation.*' => 'nullable|string',
            'convict_phone'         => 'nullable|array',
            'convict_phone.*'       => 'nullable|string',
            'father_or_husband'     => 'nullable|array',
            'father_or_husband.*'   => 'nullable|string',
            'convict_father_name'   => 'nullable|array',
            'convict_father_name.*' => 'nullable|string',
            'convict_mother_name'   => 'nullable|array',
            'convict_mother_name.*' => 'nullable|string',
            'convict_nid'           => 'nullable|array',
            'convict_nid.*'         => 'nullable|string',
            'convict_address'       => 'nullable|array',
            'convict_address.*'     => 'nullable|string',
        ]);

        // Complainant validation
        $complainantValidate = $request->validate([
            'company_name'            => 'nullable|string',
            'complainant_name'        => 'required|string',
            'complainant_designation' => 'nullable|string',
            'complainant_phone'       => 'nullable|string',
            'complainant_father'      => 'nullable|string',
            'complainant_nid'         => 'nullable|string',
            'complainant_address'     => 'nullable|string',
        ]);

        // Witness validation
        $witnessValidate = $request->validate([
            'witness_name'          => 'nullable|array',
            'witness_name.*'        => 'nullable|string',
            'witness_father_name'   => 'nullable|array',
            'witness_father_name.*' => 'nullable|string',
            'witness_mother_name'   => 'nullable|array',
            'witness_mother_name.*' => 'nullable|string',
            'witness_address'       => 'nullable|array',
            'witness_address.*'     => 'nullable|string',
            'witness_phone'         => 'nullable|array',
            'witness_phone.*'       => 'nullable|string',
        ]);
// dd($request->all());

        $this->service->store($validate, $convictValidate, $complainantValidate, $witnessValidate, $hajiraValidate);
        

        return redirect()->route('legal.legal-entries.index')->with('success', 'LegalEntry created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $data['legalEntry'] = $this->service->show($id);

        return view("legalEntrys.show", $data);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(LegalEntry $legalEntry)
    {
        $data['legalEntry'] = $legalEntry;
        $data['customers']  = Customer::activeCustomers()->get();
        return view("Legal::legal-entries.edit", $data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $validate = $request->validate([
            'date'                 => 'required|date',
            'amount'               => 'required|numeric',
            'legal_type'           => 'required|string',
            'case_no'              => 'nullable|string',
            'occurrence_info'      => 'nullable|string',
            'occurrence_date'      => 'nullable|date',
            'legal_description'    => 'nullable|string',
            'advocate_name'        => 'nullable|string',
            'advocate_designation' => 'nullable|string',
            'advocate_phone'       => 'nullable|string',
            'advocate_address'     => 'nullable|string',
            'attachment'           => 'nullable|array|min:1',
            'attachment.*'         => 'nullable|string',
        ]);

        // Convict validation
        $convictValidate = $request->validate([
            'customer_id'           => 'required|array',
            'customer_id.*'         => 'exists:customers,id',
            'convict_name'          => 'required|array',
            'convict_name.*'        => 'required|string',
            'convict_designation'   => 'nullable|array',
            'convict_designation.*' => 'nullable|string',
            'convict_phone'         => 'nullable|array',
            'convict_phone.*'       => 'nullable|string',
            'father_or_husband'     => 'nullable|array',
            'father_or_husband.*'   => 'nullable|string',
            'convict_father_name'   => 'nullable|array',
            'convict_father_name.*' => 'nullable|string',
            'convict_mother_name'   => 'nullable|array',
            'convict_mother_name.*' => 'nullable|string',
            'convict_nid'           => 'nullable|array',
            'convict_nid.*'         => 'nullable|string',
            'convict_address'       => 'nullable|array',
            'convict_address.*'     => 'nullable|string',
        ]);

        // Complainant validation
        $complainantValidate = $request->validate([
            'company_name'            => 'nullable|string',
            'complainant_name'        => 'required|string',
            'complainant_designation' => 'nullable|string',
            'complainant_phone'       => 'nullable|string',
            'complainant_father'      => 'nullable|string',
            'complainant_nid'         => 'nullable|string',
            'complainant_address'     => 'nullable|string',
        ]);

        // Witness validation
        $witnessValidate = $request->validate([
            'witness_name'          => 'nullable|array',
            'witness_name.*'        => 'nullable|string',
            'witness_father_name'   => 'nullable|array',
            'witness_father_name.*' => 'nullable|string',
            'witness_mother_name'   => 'nullable|array',
            'witness_mother_name.*' => 'nullable|string',
            'witness_address'       => 'nullable|array',
            'witness_address.*'     => 'nullable|string',
            'witness_phone'         => 'nullable|array',
            'witness_phone.*'       => 'nullable|string',
        ]);
        $hajira = $request->validate([

            'first_hajira_date' => 'nullable|date',

        ]);
        $this->service->update($validate, $convictValidate, $complainantValidate, $witnessValidate, $hajira, $id);

        return redirect()->route('legal.legal-entries.index')->with('success', 'LegalEntry updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(LegalEntry $legalEntry)
    {
        $this->service->delete($legalEntry);
        return redirect()->route('legal.legal-entries.index')->with('success', 'LegalEntry deleted successfully.');
    }

    public function getForScheduleUpdate(Request $request)
    {
        $status = $request->input('status', 'withdraw'); // default to 'withdraw'

        $query = LegalEntry::where('legal_type', 'case')
            ->where('status', $status); // always filter by status

        if ($request->filled('customer_id') && $request->customer_id !== 'all') {
            $query->whereHas('convicts', function ($q) use ($request) {
                $q->where('customer_id', $request->customer_id);
            });
        }

        $data['legalEntrys'] = $query->get();
        $data['customers']   = LegalEntryConvict::with('customer')->get();

        return view("Legal::legal-entries.schedule-update", $data);
    }

// Load modal data
// Update the getScheduleData method
    public function getScheduleData($id)
    {
        $entry = LegalEntry::with(['hajiras' => function ($q) {
            $q->orderByDesc('hajira_date');
        }, 'convicts.customer'])->findOrFail($id);

        $lastHajira = $entry->hajiras->first();

        return response()->json([
            'id'               => $entry->id,
            'case_no'          => $entry->case_no,
            'customer_name'    => $entry->convicts->first()->customer->company_name ?? 'N/A',
            'attachments'      => $entry->attachment ?? [],
            'convict_name'     => $entry->convicts->pluck('convict_name')->toArray(),
            'last_hajira_date' => $lastHajira ? $lastHajira->hajira_date : 'N/A',
            'status'           => $entry->status,
            'remarks'          => $entry->hajiras->map(fn($r) => [
                'date' => $r->hajira_date,
                'note' => $r->hajira_description ?? 'N/A',
            ]),
        ]);
    }
    public function updateSchedule(Request $request)
    {
        $entry                  = LegalEntry::findOrFail($request->legal_entry_id);
        $entry->status          = $request->modal_status;
        $entry->attachment      = $request->attachment;
        $entry->approval_status = $request->approval_status ?? 'pending';

        $entry->save();

        // Save remarks
        if ($request->hajira_remarks) {
            $entry->hajiras()->create([
                'hajira_date'        => $request->next_hajira_date,
                'hajira_description' => $request->hajira_remarks,
            ]);
        }

        return response()->json(['success' => true]);
    }

// Update the updateSchedule method
// public function updateSchedule(Request $request)
//     {
//         $validated = $request->validate([
//             'legal_entry_id' => 'required|exists:legal_entries,id',
//             'modal_status' => 'required|in:running,withdraw',
//             'next_hajira_date' => 'required|date',
//             'hajira_remarks' => 'nullable|string',
//             'attachment' => 'nullable',
//         ]);

//         try {
//             $this->service->updateSchedule($validated);

//             return response()->json([
//                 'success' => true,
//                 'message' => 'Schedule updated successfully'
//             ]);
//         } catch (\Exception $e) {
//             return response()->json([
//                 'success' => false,
//                 'message' => 'Failed to update schedule: ' . $e->getMessage()
//             ], 500);
//         }
//     }

// Update getHajiraRemarks method
    public function getHajiraRemarks($id)
    {
        $entry = LegalEntry::with(['hajiras' => function ($q) {
            $q->orderByDesc('hajira_date');
        }])->findOrFail($id);

        $remarks = $entry->hajiras->map(function ($h) {
            return [
                'date'        => $h->hajira_date,
                'description' => $h->hajira_description ?? 'N/A',
            ];
        });

        return response()->json($remarks);
    }

    public function approve($id)
    {
        $legalEntry                  = LegalEntry::findOrFail($id);
        $legalEntry->status          = 'withdraw';
        $legalEntry->approval_status = 'approved';
        $legalEntry->save();

        return redirect()->back()->with('success', 'Legal Entry approved successfully.');

    }

    public function deny($id)
    {
        $legalEntry                  = LegalEntry::findOrFail($id);
        $legalEntry->status          = 'running';
        $legalEntry->approval_status = 'rejected';
        $legalEntry->save();

        return redirect()->back()->with('warning', 'Legal Entry denied successfully.');
    }

}
