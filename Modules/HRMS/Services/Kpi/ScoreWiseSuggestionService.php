<?php

namespace Modules\HRMS\Services\Kpi;

use Modules\HRMS\Models\Kpi\ScoreWiseSuggestion;

class ScoreWiseSuggestionService
{
    
    public function getAll(int $limit = 20) {
        return ScoreWiseSuggestion::query()->paginate($limit);
    }
    
    public function store(array $data)
    {
        return ScoreWiseSuggestion::create($data);
    }

    public function update(ScoreWiseSuggestion $scoreWiseSuggestion, array $data)
    {
        $scoreWiseSuggestion->update($data);
        return $scoreWiseSuggestion;
    }

    public function delete(ScoreWiseSuggestion $scoreWiseSuggestion)
    {
        $scoreWiseSuggestion->delete();
    }

    public function show($id)
    {
        return ScoreWiseSuggestion::findOrFail($id);
    }
}
