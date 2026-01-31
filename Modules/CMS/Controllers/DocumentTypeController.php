<?php

namespace Modules\CMS\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\CMS\Models\DocumentType;
use Modules\CMS\Services\DocumentTypeService;

class DocumentTypeController extends Controller
{

    /**
     * Service variable
     *
     * @var DocumentTypeService
     */
    private $service; 
    function __construct(DocumentTypeService $service)
    {
        $this->service = $service;
    }
    
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data['documentTypes'] = $this->service->getAll();

        return view("CMS::document-types.index", $data);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('documentTypes.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validate = $request->validate([
            'name' => 'required|unique:document_types,name,NULL,id,deleted_at,NULL',
            'description' => 'nullable|string|max:255',
        ]);
        $this->service->store($validate);
        return redirect()->route('cms.document-types.index')->with('success', 'DocumentType created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show( $id)
    {
        $data['documentType'] = $this->service->show($id);

        return view("documentTypes.show", $data);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(DocumentType $documentType)
    {
        $data['documentType'] = $documentType;
        //
        return view("documentTypes.edit", $data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, DocumentType $documentType)
    {
        $validate = $request->validate([
            'name' => 'required|unique:document_types,name,'.$documentType->id.',id,deleted_at,NULL',
            'description' => 'nullable|string|max:255',
        ]);
        $this->service->update($documentType, $validate);

        return redirect()->route('cms.document-types.index')->with('success', 'DocumentType updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(DocumentType $documentType)
    {
        $this->service->delete($documentType);
        return redirect()->route('cms.document-types.index')->with('success', 'DocumentType deleted successfully.');
    }
}
