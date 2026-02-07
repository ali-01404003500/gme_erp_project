<?php

namespace Modules\SalesTarget\Controllers;

use App\Http\Controllers\Controller;
use Modules\SalesTarget\Models\AchievementBasedSalaryPolicy;
use Modules\SalesTarget\Services\AchievementBasedSalaryPolicyService;
use Illuminate\Http\Request;

class AchievementBasedSalaryPolicyController extends Controller
{

    /**
     * Service variable
     *
     * @var AchievementBasedSalaryPolicyService
     */
    private $service; 
    function __construct(AchievementBasedSalaryPolicyService $service)
    {
        $this->service = $service;
    }
    
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data['achievementBasedSalaryPolicys'] = $this->service->getAll();

        return view("SalesTarget::settings.achievement-based-salary-policy.index", $data);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('achievementBasedSalaryPolicys.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validate = $request->validate([
            //validate rules
        ]);
        $this->service->store($validate);
        return redirect()->route('achievementBasedSalaryPolicys.index')->with('success', 'AchievementBasedSalaryPolicy created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show( $id)
    {
        $data['achievementBasedSalaryPolicy'] = $this->service->show($id);

        return view("achievementBasedSalaryPolicys.show", $data);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(AchievementBasedSalaryPolicy $achievementBasedSalaryPolicy)
    {
        $data['achievementBasedSalaryPolicy'] = $achievementBasedSalaryPolicy;
        //
        return view("achievementBasedSalaryPolicys.edit", $data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, AchievementBasedSalaryPolicy $achievementBasedSalaryPolicy)
    {
        $validate = $request->validate([
            //validate rules
        ]);
        $this->service->update($achievementBasedSalaryPolicy, $validate);

        return redirect()->route('achievementBasedSalaryPolicys.index')->with('success', 'AchievementBasedSalaryPolicy updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(AchievementBasedSalaryPolicy $achievementBasedSalaryPolicy)
    {
        $this->service->delete($achievementBasedSalaryPolicy);
        return redirect()->route('achievementBasedSalaryPolicys.index')->with('success', 'AchievementBasedSalaryPolicy deleted successfully.');
    }
}
