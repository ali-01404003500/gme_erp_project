<?php

namespace Modules\LocationManager\Controllers;

use App\Http\Controllers\Controller;


use Illuminate\Http\Request;
use Modules\LocationManager\Models\LocationType;
use Modules\LocationManager\Services\LocationTypeService;

class LocationTypeController extends Controller
{

    /**
     * Service variable
     *
     * @var LocationTypeService
     */
    private $service; 
    function __construct(LocationTypeService $service)
    {
        $this->service = $service;
        $this->middleware('permited');

    }
    
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data['locationTypes'] = $this->service->getAll();

        return view("LocationManager::settings.location-type.index", $data);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('locationTypes.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validate = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:255', 
            'status' => 'required|string|max:255'            
        ]);
        $this->service->create($validate);
        return redirect()->route('location_manager.location-types.index')->with('success', 'LocationType created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show( $id)
    {
        $data['locationType'] = $this->service->show($id);

        return view("locationTypes.show", $data);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(LocationType $locationType)
    {
        $data['locationType'] = $locationType;
        //
        return view("locationTypes.edit", $data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, LocationType $locationType)
    {
        $validate = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:255', 
            'status' => 'required|string|max:255' 
        ]);
        $this->service->update($locationType, $validate);

        return redirect()->route('location_manager.location-types.index')->with('success', 'LocationType updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(LocationType $locationType)
    {
        $this->service->delete($locationType);
        return redirect()->route('location_manager.location-types.index')->with('success', 'LocationType deleted successfully.');
    }
}
