<?php

namespace Modules\HRMS\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\HRMS\Models\SalaryGenerationPolicy;
use Illuminate\Support\Facades\Auth;
use Modules\HRMS\Services\SalaryGenerationPolicyService;

class SalaryGenerationPolicyController extends Controller
{
    private $service;
    function __construct(SalaryGenerationPolicyService $service)
    {
        $this->service = $service;
    }


    public function index()
    {

        $policy = $this->service->getPolicy();

        return view('HRMS::salary-generation-policy.index', compact('policy'));
    }


    public function store(Request $request)
    {
        $validate = $request->validate([
            'calculation_type' => 'required|in:actual_days,working_days,fixed_days', 
            'fixed_days'       => 'required_if:calculation_type,fixed_days|nullable|numeric|min:1|max:31',
            'rounded_salary' => 'nullable|boolean',
            'is_salary_end_date_different_from_month_end_date'       => 'nullable|boolean', 
 
        ]); 

        $this->service->updatePolicy($validate);

        return redirect()->back()->with('success', 'Salary Generation Policy updated successfully.');
    }
}
