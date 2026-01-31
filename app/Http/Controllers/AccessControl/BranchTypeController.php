<?php

namespace App\Http\Controllers\AccessControl;

use App\Http\Controllers\Controller;
use App\Models\AccessControl\BranchType;
use App\Services\AccessControl\BranchTypeService;
use Illuminate\Http\Request;

class BranchTypeController extends Controller
{

    /**
     * Service variable
     *
     * @var BranchTypeService
     */
    private $service; 
    function __construct(BranchTypeService $service)
    {
        $this->service = $service;
        $this->middleware('permited');

    }
    
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data['branchTypes'] = $this->service->getAll();

        return view("access_control.branch-types.index", $data);
    }

    // /**
    //  * Show the form for creating a new resource.
    //  */
    // public function create()
    // {
    //     return view('branchTypes.create');
    // }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validate = $request->validate([
            'name' => "required|string|max:255|unique:branch_types,name,NULL,id,deleted_at,NULL",
            'description' => 'nullable|string|max:255',
        ]);
        $this->service->store($validate);
        return redirect()->route('access_control.branch-types.index')->with('success', 'BranchType created successfully.');
    }

    // /**
    //  * Display the specified resource.
    //  */
    // public function show( $id)
    // {
    //     $data['branchType'] = $this->service->show($id);

    //     return view("branchTypes.show", $data);
    // }

    // /**
    //  * Show the form for editing the specified resource.
    //  */
    // public function edit(BranchType $branchType)
    // {
    //     $data['branchType'] = $branchType;
    //     //
    //     return view("branchTypes.edit", $data);
    // }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, BranchType $branchType)
    {
        $validate = $request->validate([
            'name' => "required|string|max:255",
            'description' => 'nullable|string|max:255',
        ]);
        $this->service->update($branchType, $validate);

        return redirect()->route('access_control.branch-types.index')->with('success', 'BranchType updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(BranchType $branchType)
    {
        $this->service->delete($branchType);
        return redirect()->route('access_control.branch-types.index')->with('success', 'BranchType deleted successfully.');
    }
}
