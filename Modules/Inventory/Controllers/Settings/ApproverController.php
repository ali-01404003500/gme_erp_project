<?php

namespace Modules\Inventory\Controllers\Settings;
use App\Http\Controllers\Controller;
use Modules\Inventory\Models\Settings\Approver;
use Illuminate\Http\Request;
use Modules\Inventory\Services\Settings\ApproverService;

class ApproverController extends Controller
{

    /**
     * Service variable
     *
     * @var ApproverService
     */
    private $service; 
    function __construct(ApproverService $service)
    {
        $this->service = $service;
        $this->middleware('permited');

    }
    
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data['approvers'] = $this->service->getAll();

        return view("Inventory::settings.approvers.index", $data);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('approvers.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validate = $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'is_maker' => 'nullable|integer',
            'is_checker' => 'nullable|integer',
            'is_approver' => 'nullable|integer',
        ]);
        $this->service->store($validate);
        return redirect()->route('approvers.index')->with('success', 'Approver created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show( $id)
    {
        $data['approver'] = $this->service->show($id);

        return view("approvers.show", $data);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Approver $approver)
    {
        $data['approver'] = $approver;
        //
        return view("approvers.edit", $data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Approver $approver)
    {
        $validate = $request->validate([
            //validate rules
        ]);
        $this->service->update($approver, $validate);

        return redirect()->route('approvers.index')->with('success', 'Approver updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Approver $approver)
    {
        $this->service->delete($approver);
        return redirect()->route('approvers.index')->with('success', 'Approver deleted successfully.');
    }
}
