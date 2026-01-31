<?php

namespace Modules\HRMS\Controllers\Settings;
use App\Http\Controllers\Controller;
use Modules\HRMS\Models\Settings\Designation;
use Modules\HRMS\Services\Settings\DesignationService;
use Illuminate\Http\Request;

class DesignationController extends Controller
{

    /**
     * Service variable
     *
     * @var DesignationService
     */
    private $service; 
    function __construct(DesignationService $service)
    {
        $this->service = $service;
    }
    
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data['designations'] = $this->service->getAll();

        return view("HRMS::settings.designations.index", $data);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('designations.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validate = $request->validate([
           'name' => 'required|unique:designations,name,NULL,id,deleted_at,NULL',
            'code' => 'nullable|unique:designations,code,NULL,id,deleted_at,NULL',
            'status' => 'required'
        ]);
        $this->service->store($validate);
        return redirect()->route('hrm.settings.designations.index')->with('success', 'Designation created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show( $id)
    {
        $data['designation'] = $this->service->show($id);

        return view("designations.show", $data);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Designation $designation)
    {
        $data['designation'] = $designation;
        //
        return view("designations.edit", $data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Designation $designation)
    {
        $validate = $request->validate([
            'name' => 'required|unique:designations,name,' . $designation->id . ',id,deleted_at,NULL',
            'code' => 'nullable|unique:designations,code,' . $designation->id . ',id,deleted_at,NULL',
            'status' => 'required',
        ]);
        $this->service->update($designation, $validate);

        return redirect()->route('hrm.settings.designations.index')->with('success', 'Designation updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Designation $designation)
    {
        $this->service->delete($designation);
        return redirect()->route('hrm.settings.designations.index')->with('success', 'Designation deleted successfully.');
    }
}
