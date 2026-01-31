<?php

namespace Modules\Inventory\Controllers\Settings;

use App\Http\Controllers\Controller;
use Modules\Inventory\Models\Settings\Unit;
use Modules\Inventory\Services\Settings\UnitService;
use Illuminate\Http\Request;

class UnitController extends Controller
{

    /**
     * Service variable
     *
     * @var UnitService
     */
    private $service; 
    function __construct(UnitService $service)
    {
        $this->service = $service;
        $this->middleware('permited');
    }
    
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data['units'] = $this->service->getAll();

        return view("Inventory::settings.units.index", $data);
    }

    // /**
    //  * Show the form for creating a new resource.
    //  */
    // public function create()
    // {
    //     return view('Inventory::setting.units.create');
    // }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validate = $request->validate([
            'name' => 'required|string|max:255|unique:units,name,NULL,id,deleted_at,NULL',
            'description' => 'nullable|string|max:255',
        ]);
        $this->service->store($validate);
        return redirect()->route('inv.settings.units.index')->with('success', 'Unit created successfully.');
    }

    // /**
    //  * Display the specified resource.
    //  */
    // public function show( $id)
    // {
    //     $data['unit'] = $this->service->show($id);

    //     return view("Inventory::setting.units.show", $data);
    // }

    // /**
    //  * Show the form for editing the specified resource.
    //  */
    // public function edit(Unit $unit)
    // {
    //     $data['unit'] = $unit;
    //     //
    //     return view("Inventory::setting.units.edit", $data);
    // }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Unit $unit)
    {
        $validate = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:255',
        ]);
        $this->service->update($unit, $validate);

        return redirect()->route('inv.settings.units.index')->with('success', 'Unit created successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Unit $unit)
    {
        $this->service->delete($unit);
        return redirect()->route('inv.settings.units.index')->with('success', 'Unit deleted successfully.');
    }
}
