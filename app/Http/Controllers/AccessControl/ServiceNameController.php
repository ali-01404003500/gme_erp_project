<?php

namespace App\Http\Controllers\AccessControl;

use App\Http\Controllers\Controller;
use App\Models\AccessControl\ServiceName;
use App\Services\AccessControl\ServiceNameService;
use Illuminate\Http\Request;

class ServiceNameController extends Controller
{

    /**
     * Service variable
     *
     * @var ServiceNameService
     */
    private $service; 
    function __construct(ServiceNameService $service)
    {
        $this->service = $service;
    }
    
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data['serviceNames'] = $this->service->getAll();

        return view("access_control.sms.service-names.index", $data);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('access_control.sms.service-names.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validate = $request->validate([
            'name' => 'required|unique:service_names,name,NULL,id,deleted_at,NULL',
            'code' => 'required',
            'status' => 'required',
        ]);
        $this->service->store($validate);
        return redirect()->route('sms.service-names.index')->with('success', 'ServiceName created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show( $id)
    {
        $data['serviceName'] = $this->service->show($id);

        return view("sms.service-names.show", $data);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ServiceName $serviceName)
    {
        $data['serviceName'] = $serviceName;
        //
        return view("sms.service-names.edit", $data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ServiceName $serviceName)
    {
        $validate = $request->validate([
            'name' => 'required|unique:service_names,name,' . $serviceName->id . ',id,deleted_at,NULL',
            'code' => 'nullable',
            'status' => 'required',
        ]);
        $this->service->update($serviceName, $validate);

        return redirect()->route('sms.service-names.index')->with('success', 'ServiceName updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ServiceName $serviceName)
    {
        $this->service->delete($serviceName);
        return redirect()->route('sms.service-names.index')->with('success', 'ServiceName deleted successfully.');
    }
}
