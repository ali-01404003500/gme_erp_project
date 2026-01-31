<?php

namespace Modules\HRMS\Controllers\Settings;
use App\Http\Controllers\Controller;
use Modules\HRMS\Models\Settings\Department;
use Modules\HRMS\Services\Settings\DepartmentService;
use Illuminate\Http\Request;

class DepartmentController extends Controller
{

    /**
     * Service variable
     *
     * @var DepartmentService
     */
    private $service; 
    function __construct(DepartmentService $service)
    {
        $this->service = $service;
    }
    
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data['departments'] = $this->service->getAll();

        return view("HRMS::settings.departments.index", $data);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('departments.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validate = $request->validate([
           'name' => 'required|unique:departments,name,NULL,id,deleted_at,NULL',
            'code' => 'nullable|unique:departments,code,NULL,id,deleted_at,NULL',
            'status' => 'required'
        ]);
        $this->service->store($validate);
        return redirect()->route('hrm.settings.departments.index')->with('success', 'Department created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show( $id)
    {
        $data['department'] = $this->service->show($id);

        return view("departments.show", $data);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Department $department)
    {
        $data['department'] = $department;
       
        return view("departments.edit", $data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Department $department)
    {
        $validate = $request->validate([
            'name' => 'required|unique:departments,name,' . $department->id . ',id,deleted_at,NULL',
            'code' => 'nullable|unique:departments,code,' . $department->id . ',id,deleted_at,NULL',
            'status' => 'required'
        ]);
        $this->service->update($department, $validate);

        return redirect()->route('hrm.settings.departments.index')->with('success', 'Department updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Department $department)
    {
        $this->service->delete($department);
        return redirect()->route('hrm.settings.departments.index')->with('success', 'Department deleted successfully.');
    }
}
