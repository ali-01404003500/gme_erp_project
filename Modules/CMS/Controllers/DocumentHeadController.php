<?php
namespace Modules\CMS\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\CMS\Models\DocumentHead;
use Modules\CMS\Models\DocumentType;
use Modules\CMS\Services\DocumentHeadService;

class DocumentHeadController extends Controller
{

    /**
     * Service variable
     *
     * @var DocumentHeadService
     */
    private $service;

    public function __construct(DocumentHeadService $service)
    {
        $this->service = $service;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data['documentHeads'] = $this->service->getAll(); // ← Eager load already done in service
        $data['documentTypes'] = DocumentType::get();      // ← all() এর পরিবর্তে get() use করুন

        return view("CMS::document-heads.index", $data);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $data['documentTypes'] = DocumentType::get();     // ← যোগ করুন
        return view('CMS::document-heads.create', $data); // ← path ঠিক করুন
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validate = $request->validate([
            'document_type_id' => 'required|exists:document_types,id',
            'name'             => 'required|unique:document_heads,name,NULL,id,deleted_at,NULL,document_type_id,' . $request->input('document_type_id'),
            'description'      => 'nullable',
            'status'           => 'required|in:0,1',
        ]);
        $this->service->store($validate);
        return redirect()->route('cms.document-heads.index')->with('success', 'DocumentHead created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $data['documentHead'] = $this->service->show($id);
        return view("CMS::document-heads.show", $data); // ← path ঠিক করুন
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(DocumentHead $documentHead)
    {
        $data['documentHead']  = $documentHead;
        $data['documentTypes'] = DocumentType::get();   // ← যোগ করুন
        return view("CMS::document-heads.edit", $data); // ← path ঠিক করুন
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, DocumentHead $documentHead)
    {
        $validate = $request->validate([
            'document_type_id' => 'required|exists:document_types,id',
            'name'             => 'required|unique:document_heads,name,' . $documentHead->id . ',id,deleted_at,NULL,document_type_id,' . $request->input('document_type_id'),
            'description'      => 'nullable',
            'status'           => 'required|in:0,1',
        ]);
        $this->service->update($documentHead, $validate);

        return redirect()->route('cms.document-heads.index')->with('success', 'DocumentHead updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(DocumentHead $documentHead)
    {
        $this->service->delete($documentHead);
        return redirect()->route('cms.document-heads.index')->with('success', 'DocumentHead deleted successfully.');
    }
}
