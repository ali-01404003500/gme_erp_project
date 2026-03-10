<?php
namespace Modules\HRMS\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SalaryGenerationPolicyController extends Controller
{
    /**
     * Display the Salary Generation Policy form.
     */
    public function index()
    {
        
        $policy = (object) [
            'calculation_type'             => 'fixed_days',
            'fixed_days'                   => 30,
            'is_rounded_salary'            => false,
            'is_salary_end_date_different' => false,
        ];

        return view('HRMS::salary-generation-policy.index', compact('policy'));
    }

    /**
     * Update the Salary Generation Policy.
     */
    public function store(Request $request)
    {
        $request->validate([
            'calculation_type' => 'required|in:actual_days,working_days,fixed_days',
            'fixed_days'       => 'required_if:calculation_type,fixed_days|nullable|numeric|min:1|max:31',
        ]);

        return redirect()->back()->with('success', 'Salary Generation Policy updated successfully.');
    }
}
