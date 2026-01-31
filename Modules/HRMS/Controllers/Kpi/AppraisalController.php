<?php

namespace Modules\HRMS\Controllers\Kpi;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Modules\HRMS\Models\Kpi\Appraisal;
use Modules\HRMS\Services\Kpi\AppraisalService;
use Illuminate\Http\Request;
use Modules\HRMS\Models\Employee;
use Modules\HRMS\Models\Settings\AppraisalPolicy;

class AppraisalController extends Controller
{

    /**
     * Service variable
     *
     * @var AppraisalService
     */
    private $service; 
    function __construct(AppraisalService $service)
    {
        $this->service = $service;
    }
    
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data['appraisals'] = $this->service->getAll();
        $data['employees'] = Employee::with('employementDetail.designation')->where('status', 1)->get();

        return view("HRMS::kpi.appraisals.index", $data);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $policies = AppraisalPolicy::with('designation')->get();

        $eligibleEmployees = Employee::with(['employementDetail.designation', 'latestEmployeeSalary', 'assessments'])
            ->whereHas('employementDetail', function ($q) {
                $q->whereNotNull('date_of_joining');
            })
            ->get()
            ->filter(function ($employee) use ($policies) {
                foreach ($policies as $policy) {
                    if (
                        $employee->employementDetail->designation_id === $policy->designation_id &&
                        $employee->employementDetail &&
                        $this->isEligible($employee->employementDetail->date_of_joining, $policy->period, $policy->period_type)
                    ) {
                        return true;
                    }
                }
                return false;
            })
           ->map(function ($employee) {
                $employee->avg_score = round($employee->assessments->where('status','submitted')->take(-6)->avg('overall_score') ?? 0, 2);
                $employee->recent_assessments = $employee->assessments->where('status','submitted')
                    ->sortByDesc('created_at')
                    ->take(6)
                    ->values();
                return $employee;
            })

            ->values()
            ->all();

        return view('HRMS::kpi.appraisals.create', [
            'eligibleEmployees' => $eligibleEmployees,
            'policies' => $policies,
        ]);
    }


    private function isEligible($joiningDate, $period, $type): bool
    {
        $join = Carbon::parse($joiningDate);
        $now = Carbon::now();

        switch (strtolower($type)) {
            case 'day':
                return $join->diffInDays($now) >= $period;
            case 'month':
                return $join->diffInMonths($now) >= $period;
            case 'year':
                return $join->diffInYears($now) >= $period;
            default:
                return false;
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validate = $request->validate([
            'employee_id' => 'required|integer|exists:employees,id',
            'increment_percent' => 'required|numeric|min:1|max:100',
            'increment_amount' => 'required|numeric|min:1',
            'new_salary' => 'required|numeric|min:1',
            'remarks' => 'nullable|string|max:255',
            'status' => 'required|string',
        ]);
        $this->service->store($validate);
        return redirect()->route('hrm.kpis.appraisals.index')->with('success', 'Appraisal created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show( $id)
    {
        $data['appraisal'] = $this->service->show($id);

        return view("appraisals.show", $data);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Appraisal $appraisal)
    {
        $policies = AppraisalPolicy::with('designation')->get();

        $eligibleEmployees = Employee::with(['employementDetail.designation', 'latestEmployeeSalary', 'assessments'])
            ->whereHas('employementDetail', function ($q) {
                $q->whereNotNull('date_of_joining');
            })
            ->get()
            ->filter(function ($employee) use ($policies) {
                foreach ($policies as $policy) {
                    if (
                        $employee->employementDetail->designation_id === $policy->designation_id &&
                        $employee->employementDetail &&
                        $this->isEligible($employee->employementDetail->date_of_joining, $policy->period, $policy->period_type)
                    ) {
                        return true;
                    }
                }
                return false;
            })
            ->map(function ($employee) {
                $employee->avg_score = round($employee->assessments->where('status','submitted')->take(-6)->avg('overall_score') ?? 0, 2);
                $employee->recent_assessments = $employee->assessments->where('status','submitted')
                    ->sortByDesc('created_at')
                    ->take(6)
                    ->values();
                return $employee;
            })
            ->values()
            ->all();

        return view('HRMS::kpi.appraisals.edit', [
            'appraisal' => $appraisal,
            'eligibleEmployees' => $eligibleEmployees,
            'policies' => $policies,
        ]);
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Appraisal $appraisal)
    {
        // dd($request->all(), $appraisal); 
        $validate = $request->validate([
            'employee_id' => 'required|integer|exists:employees,id',
            'increment_percent' => 'required|numeric|min:1|max:100',
            'increment_amount' => 'required|numeric|min:1',
            'new_salary' => 'required|numeric|min:1',
            'remarks' => 'nullable|string|max:255',
            'status' => 'required|string',
        ]);
        $this->service->update($appraisal, $validate);

        return redirect()->route('hrm.kpis.appraisals.index')->with('success', 'Appraisal updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Appraisal $appraisal)
    {
        $this->service->delete($appraisal);
        return redirect()->route('appraisals.index')->with('success', 'Appraisal deleted successfully.');
    }
}
