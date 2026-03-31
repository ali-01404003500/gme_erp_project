<?php

namespace App\Http\Controllers\AccessControl;

use App\Http\Controllers\Controller;
use App\Models\AccessControl\CompanyInfo;
use App\Models\AccessControl\GlobalSetting;
use App\Services\AccessControl\GlobalSettingService;
use Illuminate\Http\Request;

class GlobalSettingController extends Controller
{

    /**
     * Service variable
     *
     * @var GlobalSettingService
     */
    private $service; 
    function __construct(GlobalSettingService $service)
    {
        $this->service = $service;
    }
    
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data['globalSettings'] = $this->service->getAll();

        return view("globalSettings.index", $data);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('globalSettings.create');
    }

    /**
     * Store a newly created resource in storage.
     */


    /**
     * Display the specified resource.
     */
  
    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $data['setting'] = CompanyInfo::find($id);
        return view("access_control.global-setting.create", $data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        // dd($request->all(), $id);
        $validate = $request->validate([
            'company_name'      => 'required|string|max:255', 
            'software_title'     => 'nullable|string|max:255',
            'company_email'     => 'nullable|string|max:255',
            'company_phone'     => 'required|string|max:255',
            'company_address'   => 'required|string',
            'website'   => 'nullable|string|max:255',
            'company_logo'       => 'nullable|string',
            'company_fav'       => 'nullable|string',
            'report_logo'       => 'nullable|string',
            'company_bio'       => 'nullable|string',
        ]);

        $commercialData = $request->validate([
            'vat_percentage' => 'nullable',
            'ait_percentage' => 'nullable',
            'remarks' => 'nullable|string|max:255',
        ]);
        $this->service->update($id, $request);

        return redirect()->route('access_control.global-settings.edit', $id)->with('success', 'GlobalSetting updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
}
