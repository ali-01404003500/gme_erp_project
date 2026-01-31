<?php

namespace Modules\Services\Controllers\Settings;
use App\Http\Controllers\Controller;
use Modules\Services\Services\Settings\ServiceTypeService;
use Illuminate\Http\Request;
use Modules\Services\Models\Settings\ServiceType;

class ServiceTypeController extends Controller
{

    /**
     * Service variable
     *
     * @var ServiceTypeService
     */
    private $service; 
    function __construct(ServiceTypeService $service)
    {
        $this->service = $service;
    }
    
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data['serviceTypes'] = $this->service->getAll();

        return view("Services::settings.service-type.index", $data);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('serviceTypes.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validate = $request->validate([
            'name' => 'required|unique:service_types,name,NULL,id,deleted_at,NULL',
            'code' => 'required',
        ]);
        $this->service->store($validate);
        return redirect()->route('services.settings.service-types.index')->with('success', 'Service Type created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show( $id)
    {
        $data['serviceType'] = $this->service->show($id);

        return view("serviceTypes.show", $data);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ServiceType $serviceType)
    {
        $data['serviceType'] = $serviceType;
        //
        return view("serviceTypes.edit", $data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ServiceType $serviceType)
    {
        $validate = $request->validate([
            'name' => 'required',
            'code' => 'required',
        ]);
        $this->service->update($serviceType, $validate);

        return redirect()->route('services.settings.service-types.index')->with('success', 'ServiceType updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ServiceType $serviceType)
    {
        $this->service->delete($serviceType);
        return redirect()->route('services.settings.service-types.index')->with('success', 'ServiceType deleted successfully.');
    }
}
