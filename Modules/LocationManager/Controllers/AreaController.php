<?php

namespace Modules\LocationManager\Controllers;
use App\Http\Controllers\Controller;
use App\Models\AccessControl\CompanyInfo;
use Modules\LocationManager\Services\AreaService;
use Illuminate\Http\Request;
use Dompdf\Dompdf;
use Dompdf\Options;
use Modules\LocationManager\Models\Area;

class AreaController extends Controller
{

    /**
     * Service variable
     *
     * @var AreaService
     */
    private $service; 
    function __construct(AreaService $service)
    {
        $this->service = $service;
        $this->middleware('permited');

    }
    
    /**
     * Display a listing of the resource.
     */
    public function index( Request $request)
    {
        $data['areas'] = $this->service->getAll();
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

        return view("LocationManager::area.index", $data);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('LocationManager::area.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validate = $request->validate([
            'division' => 'required',
            'district'=> 'required',
            'thana' => 'required',
            'area'=> 'required|unique:areas,area,Null,id,division_id,'.$request->division.',district_id,'.$request->district.',thana_id,'.$request->thana,
            'union'=> 'nullable',
            'village'=> 'nullable',
            'post_code'=>'nullable',
            'street'=> 'nullable',
            'lat'=> 'nullable',
            'lon'=> 'nullable',
        ]);
        $area = $this->service->store($request);
        return redirect()->route('location_manager.areas.edit', $area->id)->with('success', 'Area created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show( $id)
    {
        $data['area'] = $this->service->show($id);

        return view("LocationManager::area.show", $data);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Area $area)
    {
        $data['area'] = $area;
        //
        return view("LocationManager::area.edit", $data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Area $area)
    {
        $validate = $request->validate([
            'division' => 'required',
            'district'=> 'required',
            'thana' => 'required',
            'area'=> 'required',
            'union'=> 'nullable',
            'village'=> 'nullable',
            'post_code'=>'nullable',
            'street'=> 'nullable',
            'lat'=> 'nullable',
            'lon'=> 'nullable',
        ]);
        $area = $this->service->update($area, $request);

        return redirect()->route('location_manager.areas.edit', $area->id)->with('success', 'Area updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Area $area)
    {
        $this->service->delete($area);
        return redirect()->route('location_manager.areas.index')->with('success', 'Area deleted successfully.');
    }
}
