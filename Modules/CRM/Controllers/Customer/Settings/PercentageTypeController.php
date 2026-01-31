<?php

namespace Modules\CRM\Controllers\Customer\Settings;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\CRM\Models\Customer\Settings\PercentageType;
use Modules\CRM\Services\Customer\Settings\PercentageTypeService;

class PercentageTypeController extends Controller
{

    /**
     * Service variable
     *
     * @var PercentageTypeService
     */
    private $service; 
    function __construct(PercentageTypeService $service)
    {
        $this->service = $service;
    }
    
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data['percentageTypes'] = $this->service->getAll();
        
        return view("CRM::settings.percentage-type.index", $data);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('percentageTypes.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validate = $request->validate([
            'name' => 'required',
            'code' => 'nullable',
        ]);
        $this->service->store($validate);
        return redirect()->route('crm.percentage-types.index')->with('success', 'PercentageType created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show( $id)
    {
        $data['percentageType'] = $this->service->show($id);

        return view("percentageTypes.show", $data);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(PercentageType $percentageType)
    {
        $data['percentageType'] = $percentageType;
        //
        return view("percentageTypes.edit", $data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, PercentageType $percentageType)
    {
        $validate = $request->validate([
           "name"=> "required",
           "code"=> "nullable",
        ]);
        $this->service->update($percentageType, $validate);

        return redirect()->route('crm.percentage-types.index')->with('success', 'PercentageType updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(PercentageType $percentageType)
    {
        $this->service->delete($percentageType);
        return redirect()->route('crm.percentage-types.index')->with('success', 'PercentageType deleted successfully.');
    }
}
