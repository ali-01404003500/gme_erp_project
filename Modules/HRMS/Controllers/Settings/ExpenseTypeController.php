<?php

namespace Modules\HRMS\Controllers\Settings;
use App\Http\Controllers\Controller;
use Modules\HRMS\Models\Settings\ExpenseType;
use Modules\HRMS\Services\Settings\ExpenseTypeService;
use Illuminate\Http\Request;

class ExpenseTypeController extends Controller
{

    /**
     * Service variable
     *
     * @var ExpenseTypeService
     */
    private $service; 
    function __construct(ExpenseTypeService $service)
    {
        $this->service = $service;
    }
    
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data['expenseTypes'] = $this->service->getAll();

        return view("HRMS::settings.expense-types.index", $data);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('expenseTypes.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validate = $request->validate([
            'code' => 'nullable|string',
            'name' => 'required|string|unique:expense_types,name,NULL,id,deleted_at,NULL',
            'description' => 'nullable|string',
            'status' => 'nullable|boolean',
        ]);
        $this->service->store($validate);
        return redirect()->route('hrm.settings.expense-types.index')->with('success', 'ExpenseType created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show( $id)
    {
        $data['expenseType'] = $this->service->show($id);

        return view("expenseTypes.show", $data);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ExpenseType $expenseType)
    {
        $data['expenseType'] = $expenseType;
        //
        return view("expenseTypes.edit", $data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ExpenseType $expenseType)
    {
        $validate = $request->validate([
            'code' => 'nullable|string',
            'name' => 'required|string',
            'description' => 'nullable|string',
            'status' => 'nullable|boolean',
        ]);
        $this->service->update($expenseType, $validate);

        return redirect()->route('hrm.settings.expense-types.index')->with('success', 'ExpenseType updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ExpenseType $expenseType)
    {
        $this->service->delete($expenseType);
        return redirect()->route('hrm.settings.expense-types.index')->with('success', 'ExpenseType deleted successfully.');
    }
}
