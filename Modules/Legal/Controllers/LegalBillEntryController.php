<?php

namespace Modules\Legal\Controllers;

use App\Http\Controllers\Controller;
use App\Models\AccessControl\CompanyInfo;
use Dompdf\Dompdf;
use Dompdf\Options;
use Modules\Legal\Models\LegalBillEntry;
use Modules\Legal\Services\LegalBillEntryService;
use Illuminate\Http\Request;
use Modules\Legal\Models\LegalEntry;
use Modules\Purchase\Models\Vendor;

class LegalBillEntryController extends Controller
{

    /**
     * Service variable
     *
     * @var LegalBillEntryService
     */
    private $service; 
    function __construct(LegalBillEntryService $service)
    {
        $this->service = $service;
    }
    
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data['legalBillEntrys'] = $this->service->getAll();

        return view("Legal::legal-bill-entries.index", $data);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $data['advocates'] = Vendor::get();
        $data['legalEntries'] = LegalEntry::get();
        return view('Legal::legal-bill-entries.create', $data);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validate = $request->validate([
            'date' => 'required|date',
            'vendor_id' => 'required|integer|exists:vendors,id',
            'legal_entry_id' => 'required|integer|exists:legal_entries,id',
            'particular' => 'required|string|max:255',
            'amount' => 'required|numeric|min:0',
            'attachment' => 'nullable|array|min:1',
            'attachment.*' => 'nullable|string',
            'description' => 'nullable|string|max:255',
        ]);
        $this->service->store($validate);
        return redirect()->route('legal.legal-bill-entries.index')->with('success', 'LegalBillEntry created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show( $id, Request $request)
    {
        $data['legalBillEntry'] = $this->service->show($id);
        $data['company_info'] = CompanyInfo::first();

        if ($request->export == "pdf") {
            set_time_limit(1000);
            $html = view('Legal::legal-bill-entries.view', $data)->render();

            // Set Dompdf options
            $options = new Options();
            $options->setIsHtml5ParserEnabled(true);
            $options->setIsRemoteEnabled(true);

            $dompdf = new Dompdf($options);
            $dompdf->loadHtml($html);
            $dompdf->setPaper('A4', 'portrait');
            $dompdf->render();

            return $dompdf->stream('legal_bill_entry_' . $data['legalBillEntry']->company_name . '.pdf', ['Attachment' => false]);
        }
        return view("Legal::legal-bill-entries.show", $data);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(LegalBillEntry $legalBillEntry)
    {
        $data['legalBillEntry'] = $legalBillEntry;
        $data['advocates'] = Vendor::get();
        $data['legalEntries'] = LegalEntry::get();
        
        return view("Legal::legal-bill-entries.edit", $data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, LegalBillEntry $legalBillEntry)
    {
        $validate = $request->validate([
            'date' => 'required|date',
            'vendor_id' => 'required|integer|exists:vendors,id',
            'legal_entry_id' => 'required|integer|exists:legal_entries,id',
            'particular' => 'required|string|max:255',
            'amount' => 'required|numeric|min:0',
            'attachment' => 'nullable|array|min:1',
            'attachment.*' => 'nullable|string',
            'description' => 'nullable|string|max:255',
        ]);
        $this->service->update($legalBillEntry, $validate);

        return redirect()->route('legal.legal-bill-entries.index')->with('success', 'LegalBillEntry updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(LegalBillEntry $legalBillEntry)
    {
        $this->service->delete($legalBillEntry);
        return redirect()->route('legal.legal-bill-entries.index')->with('success', 'LegalBillEntry deleted successfully.');
    }
}
