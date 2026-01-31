<?php

namespace Modules\HRMS\Controllers\Settings;
use App\Http\Controllers\Controller;
use Modules\HRMS\Models\Settings\TransportType;
use Modules\HRMS\Services\Settings\TransportTypeService;
use Illuminate\Http\Request;

class TransportTypeController extends Controller
{

    /**
     * Service variable
     *
     * @var TransportTypeService
     */
    private $service; 
    function __construct(TransportTypeService $service)
    {
        $this->service = $service;
    }
    
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data['transportTypes'] = $this->service->getAll();

        return view("HRMS::settings.transport-types.index", $data);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('transportTypes.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validate = $request->validate([
            'code' => 'nullable|string',
            'name' => 'required|string|unique:transport_types,name,NULL,id,deleted_at,NULL',
            'description' => 'nullable|string',
            'status' => 'nullable|boolean',
        ]);
        $this->service->store($validate);
        return redirect()->route('hrm.settings.transport-types.index')->with('success', 'TransportType created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show( $id)
    {
        $data['transportType'] = $this->service->show($id);

        return view("transportTypes.show", $data);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(TransportType $transportType)
    {
        $data['transportType'] = $transportType;
        //
        return view("transportTypes.edit", $data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, TransportType $transportType)
    {
        $validate = $request->validate([
            'code' => 'nullable|string',
            'name' => 'required|string',
            'description' => 'nullable|string',
            'status' => 'nullable|boolean',
        ]);
        $this->service->update($transportType, $validate);

        return redirect()->route('hrm.settings.transport-types.index')->with('success', 'TransportType updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(TransportType $transportType)
    {
        $this->service->delete($transportType);
        return redirect()->route('hrm.settings.transport-types.index')->with('success', 'TransportType deleted successfully.');
    }
}
