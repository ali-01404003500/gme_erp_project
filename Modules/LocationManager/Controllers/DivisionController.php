<?php

namespace Modules\LocationManager\Controllers;

use App\Http\Controllers\Controller;
use App\Models\AccessControl\CompanyInfo;
use Illuminate\Http\Request;
use Dompdf\Dompdf;
use Dompdf\Options;
use App\Models\GeoLocation;

use Modules\LocationManager\Services\DivisionService;
class DivisionController extends Controller
{

    /**
     * Service variable
     *
     * @var DivisionService
     */
    private $service; 
    function __construct(DivisionService $service)
    {
        $this->service = $service;
    }
    
    /**
     * Display a listing of the resource.
     */
    public function index( Request $request)
    {
        $data['divisions'] = $this->service->getAll();
        $data['company_info'] = CompanyInfo::first();

        if ($request->export == "pdf") {
            set_time_limit(1000);
            $html = view('LocationManager::division.indexView', $data)->render();

            // Set Dompdf options
            $options = new Options();
            $options->setIsHtml5ParserEnabled(true);
            $options->setIsRemoteEnabled(true);
            
            $dompdf = new Dompdf($options);
            $dompdf->loadHtml($html);
            $dompdf->setPaper('A4', 'portrait');
            $dompdf->render();

            return $dompdf->stream('division_list_' . date('Y-m-d') . '.pdf', ['Attachment' => false]);
        }

        return view("LocationManager::division.index", $data);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('location_manager.divisions.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validate = $request->validate([
           'name' => 'required|unique:geo_locations,name,Null,id,type,Division,deleted_at,NULL',
           'type' => 'required',
        ]);
        $this->service->store($validate);
        return redirect()->route('location_manager.divisions.index')->with('success', 'Division created successfully.');
    }

    /**
     * Display the specified resource.
     */
 

    /**
     * Show the form for editing the specified resource.
     */
   

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request,  $id)
    {
        $division = GeoLocation::findOrFail($id);
        $validate = $request->validate([
           'name' => 'required',
        ]);
        $this->service->update($division, $validate);

        return redirect()->route('location_manager.divisions.index')->with('success', 'Division updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $division = GeoLocation::findOrFail($id);
        $this->service->delete($division);
        return redirect()->route('location_manager.divisions.index')->with('success', 'Division deleted successfully.');
    }
}
