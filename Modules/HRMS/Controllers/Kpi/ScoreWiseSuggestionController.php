<?php

namespace Modules\HRMS\Controllers\Kpi;

use App\Http\Controllers\Controller;
use Modules\HRMS\Models\Kpi\ScoreWiseSuggestion;
use Modules\HRMS\Services\Kpi\ScoreWiseSuggestionService;
use Illuminate\Http\Request;

class ScoreWiseSuggestionController extends Controller
{

    /**
     * Service variable
     *
     * @var ScoreWiseSuggestionService
     */
    private $service; 
    function __construct(ScoreWiseSuggestionService $service)
    {
        $this->service = $service;
    }
    
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data['scoreSuggestions'] = $this->service->getAll();

        return view("HRMS::kpi.score-wise-suggestions.index", $data);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('HRMS::kpi.score-wise-suggestions.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validate = $request->validate([
            'min_score' => 'required|integer|min:0',
            'max_score' => 'required|integer|min:0|gte:min_score',
            'rating_grade' => 'required|string|max:255',
            'remarks' => 'required|string',
            'training_need' => 'required|string',
        ]);
        $this->service->store($validate);
        return redirect()->route('hrm.kpis.score-wise-suggestions.index')->with('success', 'ScoreWiseSuggestion created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show( $id)
    {
        $data['scoreWiseSuggestion'] = $this->service->show($id);

        return view("HRMS::kpi.score-wise-suggestions.show", $data);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ScoreWiseSuggestion $scoreWiseSuggestion)
    {
        $data['scoreSuggestion'] = $scoreWiseSuggestion;
        //
        return view("HRMS::kpi.score-wise-suggestions.edit", $data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ScoreWiseSuggestion $scoreWiseSuggestion)
    {
        $validate = $request->validate([
            'min_score' => 'required|integer|min:0',
            'max_score' => 'required|integer|min:0|gte:min_score',
            'rating_grade' => 'required|string|max:255',
            'remarks' => 'required|string',
            'training_need' => 'required|string',        ]);
        $this->service->update($scoreWiseSuggestion, $validate);

        return redirect()->route('hrm.kpis.score-wise-suggestions.index')->with('success', 'ScoreWiseSuggestion updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ScoreWiseSuggestion $scoreWiseSuggestion)
    {
        $this->service->delete($scoreWiseSuggestion);
        return redirect()->route('hrm.kpis.score-wise-suggestions.index')->with('success', 'ScoreWiseSuggestion deleted successfully.');
    }
}
