<?php

namespace Modules\HRMS\Controllers\Kpi;

use App\Http\Controllers\Controller;
use Modules\HRMS\Models\Kpi\ResponsibilityEntry;
use Modules\HRMS\Services\Kpi\ResponsibilityEntryService;
use Illuminate\Http\Request;

class ResponsibilityEntryController extends Controller
{

    /**
     * Service variable
     *
     * @var ResponsibilityEntryService
     */
    private $service; 
    function __construct(ResponsibilityEntryService $service)
    {
        $this->service = $service;
    }
    
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data['responsibilityEntrys'] = $this->service->getAll();

        return view("HRMS::kpi.responsibility-entries.index", $data);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('HRMS::kpi.responsibility-entries.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validate = $request->validate([
            //validate rules
        'code' => 'required|unique:responsibility_entries,code,NULL,id,deleted_at,NULL',
        'description' => 'required',
        'weight' => 'required|numeric',
        'time' => 'required',
        'frequency' => 'required|in:Day,Month,Year',
        'status' => 'required|in:Active,Inactive'
        ]);
        $this->service->store($validate);
        return redirect()->route('hrm.kpis.responsibility-entries.index')->with('success', 'ResponsibilityEntry created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show( $id)
    {
        $data['responsibilityEntry'] = $this->service->show($id);

        return view("HRMS::kpi.responsibility-entries.show", $data);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ResponsibilityEntry $responsibilityEntry)
    {
        $data['responsibilityEntry'] = $responsibilityEntry;
        //
        return view("HRMS::kpi.responsibility-entries.edit", $data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ResponsibilityEntry $responsibilityEntry)
    {
        $validate = $request->validate([
            'code' => 'required|unique:responsibility_entries,code,'.$responsibilityEntry->id.',id,deleted_at,NULL',
            'description' => 'required',
            'weight' => 'required|numeric',
            'time' => 'required',
            'frequency' => 'required|in:Day,Month,Year,',
            'status' => 'required|in:Active,Inactive'
        ]);
        $this->service->update($responsibilityEntry, $validate);

        return redirect()->route('hrm.kpis.responsibility-entries.index')->with('success', 'ResponsibilityEntry updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ResponsibilityEntry $responsibilityEntry)
    {
        $this->service->delete($responsibilityEntry);
        return redirect()->route('hrm.kpis.responsibility-entries.index')->with('success', 'ResponsibilityEntry deleted successfully.');
    }
}
