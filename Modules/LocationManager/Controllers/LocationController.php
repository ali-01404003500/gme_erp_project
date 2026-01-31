<?php

namespace Modules\LocationManager\Controllers;
use App\Http\Controllers\Controller;


use Illuminate\Http\Request;
use Modules\LocationManager\Models\Location;
use Modules\LocationManager\Models\LocationType;
use Modules\LocationManager\Services\LocationService;

class LocationController extends Controller
{

    /**
     * Service variable
     *
     * @var LocationService
     */
    private $service; 
    function __construct(LocationService $service)
    {
        $this->service = $service;
        $this->middleware('permited');
    }
    
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data['locations'] = $this->service->getAll();

        return view("LocationManager::locations.index", $data);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $data['locationTypes'] = LocationType::all();
        return view('LocationManager::locations.create', $data);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validate = $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'required|string',
            'location_type_id' => 'required|exists:location_types,id',
            'contact_person_name' => 'required|string|max:255',
            'contact_person_mobile' => 'required|string|max:20',
            'contact_person_email' => 'required|email|max:255',
            'operational_hours' => 'required|string',
            'lat' => 'nullable',
            'long' => 'nullable',
            'map_link' => 'nullable',
            'map_iframe' => 'nullable'
        ]);
        $location = $this->service->create($validate);
        return redirect()->route('location_manager.locations.edit', $location->id)->with('success', 'Location created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show( $id)
    {
        $data['location'] = $this->service->show($id);

        return view("locations.show", $data);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Location $location)
    {
        $data['location'] = $location;
        $data['locationTypes'] = LocationType::all();
        return view("LocationManager::locations.edit", $data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Location $location)
    {
        $validate = $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'required|string',
            'contact_person_name' => 'required|string|max:255',
            'contact_person_mobile' => 'required|string|max:20',
            'contact_person_email' => 'required|email|max:255',
            'operational_hours' => 'required|string',
            'lat' => 'nullable',
            'long' => 'nullable',
            'map_link' => 'nullable',
            'map_iframe' => 'nullable'        
        ]);
        $location = $this->service->update($location, $validate);

        return redirect()->route('location_manager.locations.edit', $location->id)->with('success', 'Location updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Location $location)
    {
        $this->service->delete($location);
        return redirect()->route('location_manager.locations.index')->with('success', 'Location deleted successfully.');
    }
}
