<?php

namespace Modules\LocationManager\Controllers;

use App\Http\Controllers\Controller;
use App\Models\AccessControl\CompanyInfo;

use Illuminate\Http\Request;
use Dompdf\Dompdf;
use Dompdf\Options;
use App\Models\GeoLocation;

use Modules\LocationManager\Services\ThanaService;

class ThanaController extends Controller
{

    /**
     * Service variable
     *
     * @var ThanaService
     */
    private $service; 
    function __construct(ThanaService $service)
    {
        $this->service = $service;
    }
    
    /**
     * Display a listing of the resource.
     */
    public function index( Request $request)
    {
        $data['divisions'] = GeoLocation::where('type', 'Division')->get();
        $data['districts'] = GeoLocation::where('type', 'District')->get();
        $data['thanas'] = $this->service->getAll();
        $data['company_info'] = CompanyInfo::first();

        if ($request->export == "pdf") {
            set_time_limit(1000);
            $html = view('LocationManager::area.indexView', $data)->render();

            // Set Dompdf options
            $options = new Options();
            $options->setIsHtml5ParserEnabled(true);
            $options->setIsRemoteEnabled(true);
            
            $dompdf = new Dompdf($options);
            $dompdf->loadHtml($html);
            $dompdf->setPaper('A4', 'portrait');
            $dompdf->render();

            return $dompdf->stream('area_list_' . date('Y-m-d') . '.pdf', ['Attachment' => false]);
        }

        return view("LocationManager::thana.index", $data);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('location_manager.thana.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validate = $request->validate([
           'name' => 'required|unique:geo_locations,name,Null,id,parent_id,'.$request->parent_id,
           'parent_id' => 'required',
           'type' => 'required',
        ]);
        $this->service->store($validate);
        return redirect()->route('location_manager.thanas.index')->with('success', 'District created successfully.');
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
        $district = GeoLocation::findOrFail($id);
        $validate = $request->validate([
            'name' => 'required',
            'parent_id' => 'required',
        ]);
        $this->service->update($district, $validate);

        return redirect()->route('location_manager.thanas.index')->with('success', 'District updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $district = GeoLocation::findOrFail($id);
        $this->service->delete($district);
        return redirect()->route('location_manager.thanas.index')->with('success', 'District deleted successfully.');
    }
}
