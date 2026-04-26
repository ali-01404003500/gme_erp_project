<?php
namespace Modules\CMS\Controllers;

use App\Http\Controllers\Controller;
use App\Models\AccessControl\CompanyInfo;
use Illuminate\Http\Request;
use Modules\CMS\Models\DocumentEntry;
use Modules\CMS\Models\DocumentHead;
use Modules\CMS\Models\DocumentType;
use Modules\CMS\Services\DocumentEntryService;
use Modules\Inventory\Services\ExportService;

class DocumentEntryController extends Controller
{

    /**
     * Service variable
     *
     * @var DocumentEntryService
     */
    private $service;
    public function __construct(DocumentEntryService $service)
    {
        $this->service = $service;
    }

    /**W
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $data['documentEntries'] = $this->service->getAll();
        $data['documentTypes']   = DocumentType::all();
        $data['documentHeads']   = DocumentHead::all();
        $data['company_info']    = CompanyInfo::first();

        if ($request->filled('export_type')) {
            $request->merge(['page' => '1']);
            $data['documentEntries'] = $this->service->getAll($data['documentEntries']->total());
            $filename                = 'DocumentEntry_list_' . today()->format(date('Y-m-d'), 'Y_m_d');

            return (new ExportService())->exportData($data, 'CMS::document-entries.export.', $filename);
        }
        return view("CMS::document-entries.index", $data);
    }
    public function getDocumentHeads(Request $request)
    {
        $documentHeads = DocumentHead::where('document_type_id', $request->id)->get();
        return response()->json($documentHeads);
    }

    /**
     * Show the form for creating a new resource.
     */

    public function create()
    {
        $data['documentTypes'] = DocumentType::all();
        $data['documentHeads'] = DocumentHead::all();
        return view('CMS::document-entries.create', $data);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // dd($request->all());
        $validate = $request->validate([
            'document_type_id' => 'required|exists:document_types,id',
            'document_head_id' => 'required|exists:document_heads,id',
            'date'             => 'required|date',
            'remarks'          => 'nullable',
            'attachment'       => 'required',

        ]);
        $this->service->store($validate);
        return redirect()->route('cms.document-entries.index')->with('success', 'DocumentEntry created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $data['documentEntry'] = $this->service->show($id);

        return view("documentEntrys.show", $data);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(DocumentEntry $documentEntry)
    {
        $data['documentEntry'] = $documentEntry;
        $data['documentTypes'] = DocumentType::all();
        $data['documentHeads'] = DocumentHead::all();
        return view("CMS::document-entries.edit", $data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, DocumentEntry $documentEntry)
    {
        $validate = $request->validate([
            'document_type_id' => 'required|exists:document_types,id',
            'document_head_id' => 'required|exists:document_heads,id',
            'date'             => 'required|date',
            'remarks'          => 'nullable',
            'attachment'       => 'nullable',
        ]);
        $this->service->update($documentEntry, $validate);

        return redirect()->route('cms.document-entries.index')->with('success', 'DocumentEntry updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(DocumentEntry $documentEntry)
    {
        $this->service->delete($documentEntry);
        return redirect()->route('cms.document-entries.index')->with('success', 'DocumentEntry deleted successfully.');
    }
}
