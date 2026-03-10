<?php

namespace Modules\HRMS\Controllers\Settings;

use App\Http\Controllers\Controller;
use Modules\HRMS\Models\Settings\Hotspot;
use Modules\HRMS\Services\Settings\HotspotService;
use Modules\HRMS\Models\Settings\Department;
use App\Models\AccessControl\Branch;
use Illuminate\Http\Request;

class HotspotController extends Controller
{
    /**
     * Service variable
     *
     * @var HotspotService
     */
    private $service;

    function __construct(HotspotService $service)
    {
        $this->service = $service;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data['hotspots'] = $this->service->getAll();
        $data['branches'] = Branch::query()->select(["id", "name"])->get();

        return view("HRMS::settings.hotspots.index", $data);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $data['branches'] = Branch::query()->select(["id", "name"])->get();
        return view('HRMS::settings.hotspots.create', $data);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validate = $request->validate([
            'branch_id' => 'required|exists:branches,id',
            'name' => 'nullable|string|max:255',
            'latitude' => 'required|numeric|between:-90,90',
            'longitude' => 'required|numeric|between:-180,180',
            'radius' => 'required|integer|min:1|max:10000',
            'status' => 'required|boolean',
            'address' => 'nullable|string|max:1000',
        ]);

        $this->service->store($validate);

        return redirect()->route('hrm.settings.hotspots.index')->with('success', 'Hotspot created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $data['hotspot'] = $this->service->show($id);

        return view("HRMS::settings.hotspots.show", $data);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Hotspot $hotspot)
    {
        $data['hotspot'] = $hotspot;
        $data['branches'] = Branch::query()->select(["id", "name"])->get();
        
        return view("HRMS::settings.hotspots.edit", $data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Hotspot $hotspot)
    {
        $validate = $request->validate([
            'branch_id' => 'required|exists:branches,id',
            'name' => 'nullable|string|max:255',
            'latitude' => 'required|numeric|between:-90,90',
            'longitude' => 'required|numeric|between:-180,180',
            'radius' => 'required|integer|min:1|max:10000',
            'status' => 'required|boolean',
            'address' => 'nullable|string|max:1000',
        ]);

        $this->service->update($hotspot, $validate);

        return redirect()->route('hrm.settings.hotspots.index')->with('success', 'Hotspot updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Hotspot $hotspot)
    {
        $this->service->delete($hotspot);
        return redirect()->route('hrm.settings.hotspots.index')->with('success', 'Hotspot deleted successfully.');
    }
}
