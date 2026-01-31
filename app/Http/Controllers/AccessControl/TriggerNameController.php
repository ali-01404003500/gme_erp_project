<?php

namespace App\Http\Controllers\AccessControl;

use App\Http\Controllers\Controller;
use App\Models\AccessControl\ServiceName;
use App\Models\AccessControl\TriggerName;
use App\Services\AccessControl\TriggerNameService;
use Illuminate\Http\Request;

class TriggerNameController extends Controller
{

    /**
     * Service variable
     *
     * @var TriggerNameService
     */
    private $service; 
    function __construct(TriggerNameService $service)
    {
        $this->service = $service;
    }
    
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data['triggerNames'] = $this->service->getAll();
        $data['serviceNames'] = ServiceName::query()->where('status',1)->get();

        return view("access_control.sms.trigger-names.index", $data);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $data['serviceNames'] = ServiceName::query()->where('status',1)->get();
        return view('triggerNames.create', $data);
    }

  

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validate = $request->validate([
            'service_name_id' => 'required|exists:service_names,id',
            'name' => 'required|unique:trigger_names,name,NULL,id,deleted_at,NULL,service_name_id,' . $request->input('service_name_id'),
            'code' => 'nullable',
            'status' => 'required',
        ]);
        $this->service->store($validate);
        return redirect()->route('sms.trigger-names.index')->with('success', 'TriggerName created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show( $id)
    {
        $data['triggerName'] = $this->service->show($id);

        return view("triggerNames.show", $data);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(TriggerName $triggerName)
    {
        $data['triggerName'] = $triggerName;
        //
        return view("triggerNames.edit", $data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, TriggerName $triggerName)
    {
        $validate = $request->validate([
            'service_name_id' => 'required|exists:service_names,id',
            'name' => 'required|unique:trigger_names,name,' . $triggerName->id . ',id,deleted_at,NULL',
            'code' => 'nullable',
            'status' => 'required',
        ]);
        $this->service->update($triggerName, $validate);

        return redirect()->route('sms.trigger-names.index')->with('success', 'TriggerName updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(TriggerName $triggerName)
    {
        $this->service->delete($triggerName);
        return redirect()->route('sms.trigger-names.index')->with('success', 'TriggerName deleted successfully.');
    }
}
