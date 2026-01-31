<?php

namespace Modules\Services\Controllers;

use App\Http\Controllers\Controller;
use Modules\Inventory\Models\ProductCatalog;
use Modules\Services\Models\ServiceDocumentEntry;
use Modules\Services\Services\ServiceDocumentEntryService;
use Illuminate\Http\Request;

class ServiceDocumentEntryController extends Controller
{

    /**
     * Service variable
     *
     * @var ServiceDocumentEntryService
     */
    private $service; 
    function __construct(ServiceDocumentEntryService $service)
    {
        $this->service = $service;
    }
    
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data['serviceDocumentEntrys'] = $this->service->getAll();
        return view("Services::document-entries.index", $data);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $data['products'] = ProductCatalog::all();
        return view('Services::document-entries.create', $data);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validate = $request->validate([
            'product_id' => 'required|exists:product_catalogs,id',
            'document_date' => 'nullable',
            'documents' => 'nullable|string',
            'remarks' => 'nullable|string',
        ]);
        $this->service->store($validate);
        return redirect()->route('services.document-entries.index')->with('success', 'Service Document Entry created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show( $id)
    {
        $data['serviceDocumentEntry'] = $this->service->show($id);

        return view("serviceDocumentEntrys.show", $data);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $serviceDocumentEntry = $this->service->show($id);
        $data['serviceDocumentEntry'] = $serviceDocumentEntry;
        $data['products'] = ProductCatalog::all();
        return view("Services::document-entries.edit", $data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $serviceDocumentEntry = $this->service->show($id);
        $validate = $request->validate([
            'product_id' => 'required|exists:product_catalogs,id',
            'document_date' => 'nullable|date',
            'documents' => 'nullable|string',
            'remarks' => 'nullable|string',
        ]);
        $this->service->update($serviceDocumentEntry, $validate);

        return redirect()->route('services.document-entries.index')->with('success', 'Service Document Entry updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $serviceDocumentEntry = $this->service->show($id);

        $this->service->delete($serviceDocumentEntry);
        return redirect()->route('services.document-entries.index')->with('success', 'Service Document Entry deleted successfully.');
    }
}
