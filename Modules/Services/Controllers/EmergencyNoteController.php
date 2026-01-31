<?php

namespace Modules\Services\Controllers;


use App\Http\Controllers\Controller;
use Modules\Services\Models\EmergencyNote;
use Modules\Services\Models\ServiceToken;
use Illuminate\Http\Request;
use Modules\Services\Models\Service;
use Modules\Services\Services\EmergencyNoteService;

class EmergencyNoteController extends Controller
{

    /**
     * Service variable
     *
     * @var EmergencyNoteService
     */
    private $service; 
    function __construct(EmergencyNoteService $service)
    {
        $this->service = $service;
    }
    
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data['emergencyNotes'] = $this->service->getAll();
        $data['services'] = Service::all();
        $data['serviceTokens'] = ServiceToken::all();

        return view("Services::service-assign.index", $data);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('emergencyNotes.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // dd($request->all());
        $validate = $request->validate([
            'note' => 'required|string|max:255',
            'service_id' => 'required|exists:services,id',
        ]);
        $this->service->store($validate);
        return redirect()->route('services.emergency-notes.index', $request->id)->with('success', 'EmergencyNote updated successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show( $id)
    {
        $data['emergencyNote'] = $this->service->show($id);

        return view("emergencyNotes.show", $data);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(EmergencyNote $emergencyNote)
    {
        $data['emergencyNote'] = $emergencyNote;
        //
        return view("emergencyNotes.edit", $data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, EmergencyNote $emergencyNote)
    {
        $validate = $request->validate([
            //validate rules
        ]);
        $this->service->update($emergencyNote, $validate);

        return redirect()->route('emergencyNotes.index')->with('success', 'EmergencyNote updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(EmergencyNote $emergencyNote)
    {
        $this->service->delete($emergencyNote);
        return redirect()->route('emergencyNotes.index')->with('success', 'EmergencyNote deleted successfully.');
    }
}
