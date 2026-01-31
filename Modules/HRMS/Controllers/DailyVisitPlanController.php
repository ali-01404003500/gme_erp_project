<?php

namespace Modules\HRMS\Controllers;

use App\Http\Controllers\Controller;
use Modules\HRMS\Models\DailyVisitPlan;
use Modules\HRMS\Services\DailyVisitPlanService;
use Illuminate\Http\Request;

class DailyVisitPlanController extends Controller
{

    /**
     * Service variable
     *
     * @var DailyVisitPlanService
     */
    private $service; 
    function __construct(DailyVisitPlanService $service)
    {
        $this->service = $service;
    }
    
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data['dailyVisitPlans'] = $this->service->getAll();

        return view("HRMS::daily-visit-plans.index", $data);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('HRMS::daily-visit-plans.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // dd($request->all());
        $validate = $request->validate([
            'company_name' => 'required|string|max:255',
            'phone_no' => 'required|string|max:20',
            'date' => 'required|date',
            'address' => 'required|string|max:255',
            'contact_person' => 'nullable|string|max:255',
            'business_type' => 'required|string|max:255',
            'visit_purpose' => 'nullable|string|max:255',
            'attachment' => 'nullable|array',
            'description' => 'required|string',
        ]);
        $this->service->store($validate);
        return redirect()->route('hrm.daily-visit-plans.index')->with('success', 'Daily Visit Plan created successfully.');
    }

    public function approve($id)
    {
        $plan = DailyVisitPlan::findOrFail($id);
        $plan->approved_by = auth()->user()->id;
        $plan->status = 'approved'; // Assuming 1 is for approved
        $plan->save();

        return redirect()->route('hrm.daily-visit-plans.index')->with('success', 'Customer approved successfully.');
    }

    public function deny($id)
    {
        $plan = DailyVisitPlan::findOrFail($id);
        $plan->approved_by = auth()->user()->id;
        $plan->status = 'deny'; // Assuming 2 is for denied
        $plan->save();

        return redirect()->route('hrm.daily-visit-plans.index')->with('warning', 'Customer denied successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show( $id)
    {
        $data['dailyVisitPlan'] = $this->service->show($id);

        return view("HRMS::daily-visit-plans.show", $data);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(DailyVisitPlan $dailyVisitPlan)
    {
        $data['dailyVisitPlan'] = $dailyVisitPlan;
       
        return view("HRMS::daily-visit-plans.edit", $data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, DailyVisitPlan $dailyVisitPlan)
    {
        $validate = $request->validate([
            'company_name' => 'required|string|max:255',
            'phone_no' => 'required|string|max:20',
            'date' => 'required|date',
            'address' => 'required|string|max:255',
            'contact_person' => 'nullable|string|max:255',
            'business_type' => 'required|string|max:255',
            'visit_purpose' => 'nullable|string|max:255',
            'attachment' => 'nullable|array',
            'description' => 'required|string',
        ]);
        $this->service->update($dailyVisitPlan, $validate);

        return redirect()->route('hrm.daily-visit-plans.index')->with('success', 'DailyVisitPlan updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(DailyVisitPlan $dailyVisitPlan)
    {
        $this->service->delete($dailyVisitPlan);
        return redirect()->route('hrm.daily-visit-plans.index')->with('success', 'DailyVisitPlan deleted successfully.');
    }
}
