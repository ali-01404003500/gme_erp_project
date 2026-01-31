<?php

namespace Modules\Account\Controllers\Api;

use App\Http\Controllers\Controller;
use Modules\Account\Models\Setup\Bank;
use Modules\Account\Services\Setup\BankService;
use Illuminate\Http\Request;

class BankApiController extends Controller
{

    /**
     * Service variable
     *
     * @var BankService
     */
    private $service; 
    function __construct(BankService $service)
    {
        $this->service = $service;
    }
    
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data['banks'] = $this->service->getAll();

        return response()->json($data);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validate = $request->validate([
            //validate rules
            'name'=> 'required|unique:banks,name,NULL,id,deleted_at,NULL',
        ]);
        $this->service->store($validate);
        return response()->json(['success' => 'Bank created successfully.']);
    }

    /**
     * Display the specified resource.
     */
    public function show( $id)
    {
        $data['bank'] = $this->service->show($id);

        return response()->json($data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Bank $bank)
    {
        $validate = $request->validate([
            //validate rules
            'name'=> 'required|unique:banks,name,'.$bank->id,
        ]);
        $this->service->update($bank, $validate);

        return response()->json(['success' => 'Bank updated successfully.']);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Bank $bank)
    {
        $this->service->delete($bank);
        return response()->json(['success' => 'Bank deleted successfully.']);
    }


    
    /**
     * get all banks
     */
    public function getAllBanks()
    {
        $data['banks'] = Bank::select('id', 'name')->get();

        return response()->json($data);
    }
}

