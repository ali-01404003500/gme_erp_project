<?php

namespace Modules\Account\Controllers\Api;

use App\Http\Controllers\Controller;
use Modules\Account\Models\Setup\BankBranch;
use Modules\Account\Services\Setup\BankBranchService;
use Illuminate\Http\Request;
use Modules\Account\Models\Bank;
class BankBranchApiController extends Controller
{

    /**
     * Service variable
     *
     * @var BankBranchService
     */
    private $service; 
    function __construct(BankBranchService $service)
    {
        $this->service = $service;
    }
    
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data['bankBranches'] = $this->service->getAll();

        $data['banks'] = Bank::all();

        return response()->json($data);
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validate = $request->validate([
            //validate rules
            'name'=> 'required|unique:bank_branches,name,NULL,id,bank_id,'.$request->bank_id.',deleted_at,NULL',
            'bank_id'=> 'required|exists:banks,id',
        ]);
        $this->service->store($validate);
        return response()->json(['success' => 'Bank Branch created successfully.']);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $data['bankBranch'] = $this->service->show($id);

        return response()->json($data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, BankBranch $bankBranch)
    {
        $validate = $request->validate([
            //validate rules
            'name'=> 'required',
            'bank_id'=> 'required|exists:banks,id',
        ]);
        $this->service->update($bankBranch, $validate);

        return response()->json(['success' => 'Bank Branch updated successfully.']);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(BankBranch $bankBranch)
    {
        $this->service->delete($bankBranch);
        return response()->json(['success' => 'Bank Branch deleted successfully.']);
    }

    public function getBranches(Request $request)
    {
        $request->validate([
            'bank_id' => 'required',
        ]);
        
        $branches = BankBranch::where('bank_id', $request->bank_id)->get();
        return response()->json($branches);
    }
}

