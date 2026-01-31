<?php

namespace Modules\HRMS\Controllers\Settings;
use App\Http\Controllers\Controller;
use Modules\HRMS\Models\Settings\SalarySetup;
use Modules\HRMS\Services\Settings\SalarySetupService;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;


class SalarySetupController extends Controller
{

    /**
     * Service variable
     *
     * @var SalarySetupService
     */
    private $service; 
    function __construct(SalarySetupService $service)
    {
        $this->service = $service;
    }
    
    /**
     * Display a listing of the resource.
     */
    public function index( Request $request)
    {
        if ($request->has('export') && $request->get('export') === 'pdf') {
            $salarySetups = $this->service->getAll(1000);
            $pdf = Pdf::loadView('HRMS::settings.salary-setups.index-pdf', compact('salarySetups'));
            return $pdf->download('salary-setups.pdf');
        }

        $data['salarySetups'] = $this->service->getAll();

        return view("HRMS::settings.salary-setups.index", $data);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('HRMS::settings.salary-setups.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validate = $request->validate([
            'title' => 'required|string|max:255',
            'effective_date' => 'required|date|after_or_equal:today',
            'basic' => 'nullable|numeric|min:1',
            'house_rent' => 'nullable|numeric|min:0',
            'conveyance' => 'nullable|numeric|min:0',
            'is_conveyance_fixed' => 'nullable|in:0,1',
            'medical' => 'nullable|numeric|min:0',
            'is_medical_fixed' => 'nullable|in:0,1',
            'others' => 'nullable|numeric|min:0',
            'is_others_fixed' => 'nullable|in:0,1',
        ]);
        $this->service->store($validate);
        return redirect()->route('hrm.settings.salary-setups.index')->with('success', 'SalarySetup created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show( $id)
    {
        $data['salarySetup'] = $this->service->show($id);

        return view("salarySetups.show", $data);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(SalarySetup $salarySetup)
    {
        $data['salarySetup'] = $salarySetup;
        return view("HRMS::settings.salary-setups.edit", $data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, SalarySetup $salarySetup)
    {
        
         $request->merge([
        'is_conveyance_fixed' => $request->has('is_conveyance_fixed') ? 1 : 0,
        'is_medical_fixed'    => $request->has('is_medical_fixed') ? 1 : 0,
        'is_others_fixed'     => $request->has('is_others_fixed') ? 1 : 0,
    ]);
        $validate = $request->validate([
            'title' => 'required|string|max:255',
            'effective_date' => 'required|date|after_or_equal:today',
            'basic' => 'nullable|numeric|min:1',
            'house_rent' => 'nullable|numeric|min:0',
            'conveyance' => 'nullable|numeric|min:0',
            'is_conveyance_fixed' => 'nullable|in:0,1',
            'medical' => 'nullable|numeric|min:0',
            'is_medical_fixed' => 'nullable|in:0,1',
            'others' => 'nullable|numeric|min:0',
            'is_others_fixed' => 'nullable|in:0,1',
        ]);
        // dd($validate);

        $this->service->update($salarySetup, $validate);

        return redirect()->route('hrm.settings.salary-setups.index')->with('success', 'SalarySetup updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(SalarySetup $salarySetup)
    {
        $this->service->delete($salarySetup);
        return redirect()->route('hrm.settings.salary-setups.index')->with('success', 'SalarySetup deleted successfully.');
    }
}
